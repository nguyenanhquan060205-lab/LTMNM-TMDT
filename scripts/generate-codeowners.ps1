param(
    [Parameter(Mandatory = $true)][string] $TV1Username,
    [Parameter(Mandatory = $true)][string] $TV2Username,
    [Parameter(Mandatory = $true)][string] $TV3Username,
    [Parameter(Mandatory = $true)][string] $TV4Username,
    [Parameter(Mandatory = $true)][string] $TV5Username
)

$ErrorActionPreference = 'Stop'

function Normalize-GitHubUsername {
    param([Parameter(Mandatory = $true)][string] $Username)

    $normalized = $Username.Trim().TrimStart('@')
    if ($normalized -eq '' -or $normalized -notmatch '^[A-Za-z0-9](?:[A-Za-z0-9-]{0,37}[A-Za-z0-9])$') {
        throw "Invalid GitHub username: $Username"
    }

    return "@$normalized"
}

$gitRoot = (& git rev-parse --show-toplevel).Trim()
Set-Location $gitRoot

$replacements = @{
    '@TV1_USERNAME' = Normalize-GitHubUsername $TV1Username
    '@TV2_USERNAME' = Normalize-GitHubUsername $TV2Username
    '@TV3_USERNAME' = Normalize-GitHubUsername $TV3Username
    '@TV4_USERNAME' = Normalize-GitHubUsername $TV4Username
    '@TV5_USERNAME' = Normalize-GitHubUsername $TV5Username
}

$templatePath = Join-Path $gitRoot '.github/CODEOWNERS.template'
$targetPath = Join-Path $gitRoot '.github/CODEOWNERS'
$content = Get-Content -LiteralPath $templatePath -Raw

foreach ($placeholder in $replacements.Keys) {
    $content = $content.Replace($placeholder, $replacements[$placeholder])
}

if ($content -match '@TV[1-5]_USERNAME') {
    throw 'Refusing to write CODEOWNERS while placeholders remain.'
}

Set-Content -LiteralPath $targetPath -Value $content -Encoding UTF8
Write-Host "Created $targetPath"

