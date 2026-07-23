param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('TV1', 'TV2', 'TV3', 'TV4', 'TV5')]
    [string] $MemberId,

    [string] $ExpectedCommit,

    [string] $OutputDirectory = 'storage/app/readiness'
)

$ErrorActionPreference = 'Stop'
$startedAt = (Get-Date).ToUniversalTime().ToString('o')
$commands = @()
$finalStatus = 'FAIL'
$reportPath = $null
$gitRoot = $null
$branch = $null
$commit = $null
$dbConnection = $null
$dbName = $null
$versions = [ordered]@{}
$tests = [ordered]@{ passed = 0; failed = 0; skipped = 0; assertions = 0 }

function Invoke-CommandCapture {
    param(
        [Parameter(Mandatory = $true)]
        [string] $Command,
        [switch] $StopOnFailure
    )

    $output = Invoke-Expression $Command 2>&1
    $exitCode = $LASTEXITCODE
    if ($null -eq $exitCode) {
        $exitCode = 0
    }

    $text = ($output | Out-String).Trim()
    $script:commands += [ordered]@{
        command = $Command
        exit_code = $exitCode
        status = $(if ($exitCode -eq 0) { 'PASS' } else { 'FAIL' })
        summary = $(if ($text.Length -gt 1200) { $text.Substring(0, 1200) } else { $text })
    }

    if ($Command -eq 'php artisan test') {
        $plain = $text -replace "`e\[[0-9;]*m", ''
        if ($plain -match 'Tests:\s+(\d+)\s+passed(?:,\s+(\d+)\s+failed)?(?:,\s+(\d+)\s+skipped)?.*\((\d+)\s+assertions\)') {
            $script:tests.passed = [int] $Matches[1]
            $script:tests.failed = if ($Matches[2]) { [int] $Matches[2] } else { 0 }
            $script:tests.skipped = if ($Matches[3]) { [int] $Matches[3] } else { 0 }
            $script:tests.assertions = [int] $Matches[4]
        }
    }

    if ($StopOnFailure -and $exitCode -ne 0) {
        throw "Command failed: $Command"
    }

    return $text
}

function Read-DotenvValue {
    param(
        [Parameter(Mandatory = $true)]
        [string] $Path,
        [Parameter(Mandatory = $true)]
        [string] $Name
    )

    foreach ($line in Get-Content -LiteralPath $Path) {
        $trimmed = $line.Trim()
        if ($trimmed -eq '' -or $trimmed.StartsWith('#')) {
            continue
        }

        $separator = $trimmed.IndexOf('=')
        if ($separator -lt 1) {
            continue
        }

        $key = $trimmed.Substring(0, $separator).Trim()
        if ($key -ne $Name) {
            continue
        }

        $value = $trimmed.Substring($separator + 1).Trim()
        if (($value.StartsWith('"') -and $value.EndsWith('"')) -or ($value.StartsWith("'") -and $value.EndsWith("'"))) {
            $value = $value.Substring(1, $value.Length - 2)
        }

        return $value
    }

    return $null
}

function Resolve-OutputDirectory {
    param([Parameter(Mandatory = $true)][string] $Directory)

    $candidate = if ([System.IO.Path]::IsPathRooted($Directory)) {
        [System.IO.Path]::GetFullPath($Directory)
    } else {
        [System.IO.Path]::GetFullPath((Join-Path $gitRoot $Directory))
    }

    $storageRoot = [System.IO.Path]::GetFullPath((Join-Path $gitRoot 'storage'))
    if (-not $candidate.StartsWith($storageRoot, [System.StringComparison]::OrdinalIgnoreCase)) {
        & git -C $gitRoot check-ignore -q -- $candidate
        if ($LASTEXITCODE -ne 0) {
            throw 'OutputDirectory must be under storage or ignored by git.'
        }
    }

    New-Item -ItemType Directory -Force -Path $candidate | Out-Null
    return $candidate
}

try {
    $gitRoot = (& git rev-parse --show-toplevel).Trim()
    if (-not (Test-Path -LiteralPath (Join-Path $gitRoot 'artisan'))) {
        throw 'Laravel root not found at Git root.'
    }

    Set-Location $gitRoot
    $branch = (& git branch --show-current).Trim()
    $commit = (& git rev-parse HEAD).Trim()
    if ($ExpectedCommit -and $commit -ne $ExpectedCommit) {
        throw "ExpectedCommit mismatch. Expected $ExpectedCommit, got $commit."
    }

    $outputRoot = Resolve-OutputDirectory -Directory $OutputDirectory
    $safeCommit = if ($commit.Length -ge 12) { $commit.Substring(0, 12) } else { $commit }
    $reportPath = Join-Path $outputRoot "$MemberId-$safeCommit-readiness.json"

    $status = (& git status --short | Out-String).Trim()
    if ($status.Length -gt 0) {
        throw 'Working tree must be clean before five-machine verification.'
    }

    $dotenv = Join-Path $gitRoot '.env.testing'
    if (-not (Test-Path -LiteralPath $dotenv)) {
        throw '.env.testing is required.'
    }

    $dbConnection = Read-DotenvValue -Path $dotenv -Name 'DB_CONNECTION'
    $dbName = Read-DotenvValue -Path $dotenv -Name 'DB_DATABASE'
    $dotenvAppEnv = Read-DotenvValue -Path $dotenv -Name 'APP_ENV'
    $env:APP_ENV = 'testing'

    if (($dotenvAppEnv -ne 'testing' -and $env:APP_ENV -ne 'testing') -or $dbConnection -ne 'mysql' -or -not $dbName.StartsWith('techsecond_test')) {
        throw 'Unsafe testing DB.'
    }

    $script:versions = [ordered]@{
        php = (Invoke-CommandCapture -Command 'php -v' -StopOnFailure).Split("`n")[0].Trim()
        composer = (Invoke-CommandCapture -Command 'composer --version' -StopOnFailure).Split("`n")[0].Trim()
        node = (Invoke-CommandCapture -Command 'node -v' -StopOnFailure).Trim()
        npm = (Invoke-CommandCapture -Command 'npm -v' -StopOnFailure).Trim()
    }

    $gateCommands = @(
        'git diff --check',
        'composer validate --strict',
        'composer check-platform-reqs',
        'composer install --dry-run --no-interaction --prefer-dist',
        'npm ci',
        'php artisan optimize:clear',
        'php artisan route:list --json',
        'php artisan migrate:fresh --seed --env=testing',
        'php artisan migrate:status --env=testing',
        'vendor/bin/pint --test',
        'composer run check:quality',
        'php artisan test',
        'npm run build',
        'git diff --check'
    )

    foreach ($command in $gateCommands) {
        Invoke-CommandCapture -Command $command -StopOnFailure | Out-Null
    }

    $finalStatus = 'PASS'
}
catch {
    $script:commands += [ordered]@{
        command = 'verification-script'
        exit_code = 1
        status = 'FAIL'
        summary = $_.Exception.Message
    }
}
finally {
    if (-not $gitRoot) {
        $gitRoot = (Get-Location).Path
    }
    if (-not $reportPath) {
        $outputRoot = Resolve-OutputDirectory -Directory $OutputDirectory
        $safeCommit = if ($commit -and $commit.Length -ge 12) { $commit.Substring(0, 12) } else { 'unknown' }
        $reportPath = Join-Path $outputRoot "$MemberId-$safeCommit-readiness.json"
    }

    $report = [ordered]@{
        member_id = $MemberId
        hostname = [System.Net.Dns]::GetHostName()
        os = [System.Runtime.InteropServices.RuntimeInformation]::OSDescription
        branch = $branch
        commit = $commit
        started_at = $startedAt
        completed_at = (Get-Date).ToUniversalTime().ToString('o')
        versions = $versions
        database_connection = $dbConnection
        database_name = $dbName
        commands = $commands
        exit_codes = @($commands | ForEach-Object { $_.exit_code })
        tests = $tests
        final_status = $finalStatus
    }

    $report | ConvertTo-Json -Depth 8 | Set-Content -LiteralPath $reportPath -Encoding UTF8
    Write-Host "Readiness report: $reportPath"
}

if ($finalStatus -ne 'PASS') {
    exit 1
}
