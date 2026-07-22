<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$scanRoots = [
    'app',
    'bootstrap',
    'config',
    'database',
    'resources',
    'routes',
];

$forbidden = [
    'Razor marker' => [
        '/@using\b/',
        '/Html\.BeginForm/',
        '/@Html\./',
        '/\bModel\./',
        '/\bViewBag\b/',
        '/\bViewData\b/',
    ],
    'Mojibake' => [
        '/Ä/u',
        '/Ã/u',
        '/Â/u',
        '/á»/u',
        '/áº/u',
    ],
    'Manual session user' => [
        '/Session::put\(\s*[\'"]user[\'"]/',
        '/Session::get\(\s*[\'"]user[\'"]/',
    ],
    'Legacy active asset' => [
        '/public\/Content/',
        '/public\/Scripts/',
        '/asset\(\s*[\'"]Content\//',
        '/asset\(\s*[\'"]Scripts\//',
        '/\/Content\//',
        '/\/Scripts\//',
    ],
    'Legacy Vietnamese status' => [
        '/Đã duyệt/u',
        '/Chưa duyệt/u',
        '/Đã bán/u',
        '/Đã giao/u',
        '/Chờ xác nhận/u',
        '/Đã xác nhận/u',
        '/Đang chờ xử lý/u',
        '/Hoàn tất/u',
    ],
    'Plain text password comparison' => [
        '/where\(\s*[\'"]password[\'"]\s*,/',
        '/where\(\s*[\'"]MatKhau[\'"]\s*,/',
        '/Hash::check\([^)]*===/',
    ],
];

$failures = [];

foreach ($scanRoots as $relativeRoot) {
    $directory = $root.DIRECTORY_SEPARATOR.$relativeRoot;

    if (! is_dir($directory)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (! $file->isFile()) {
            continue;
        }

        $path = $file->getPathname();
        $relativePath = str_replace($root.DIRECTORY_SEPARATOR, '', $path);

        if (str_starts_with($relativePath, 'database'.DIRECTORY_SEPARATOR.'factories')) {
            continue;
        }

        $contents = @file_get_contents($path);
        if ($contents === false) {
            $failures[] = "Cannot read {$relativePath}";

            continue;
        }

        foreach ($forbidden as $label => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $contents) === 1) {
                    $failures[] = "{$label}: {$relativePath} matches {$pattern}";
                }
            }
        }
    }
}

$viewRoot = $root.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views';
if (is_dir($viewRoot)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($viewRoot, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir() && preg_match('/[A-Z]/', $item->getFilename()) === 1) {
            $relativePath = str_replace($root.DIRECTORY_SEPARATOR, '', $item->getPathname());
            $failures[] = "Uppercase view directory: {$relativePath}";
        }
    }
}

$routeFile = $root.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'web.php';
if (is_file($routeFile)) {
    $routeContents = file_get_contents($routeFile);
    $unsafeGetPattern = '/Route::get\([^;]*(store|update|destroy|delete|cancel|logout|lock|unlock|confirm|read)/i';
    if ($routeContents !== false && preg_match($unsafeGetPattern, $routeContents) === 1) {
        $failures[] = 'Unsafe GET mutation route detected in routes/web.php';
    }
}

foreach (glob($root.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'*.php') ?: [] as $routeFile) {
    $routeContents = file_get_contents($routeFile);
    $relativePath = str_replace($root.DIRECTORY_SEPARATOR, '', $routeFile);
    $unsafeGetPattern = '/Route::get\([^;]*(store|update|destroy|delete|cancel|logout|lock|unlock|confirm|read)/i';
    if ($routeContents !== false && preg_match($unsafeGetPattern, $routeContents) === 1) {
        $failures[] = "Unsafe GET mutation route detected in {$relativePath}";
    }
}

if ($failures !== []) {
    fwrite(STDERR, "Forbidden patterns found:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, "- {$failure}\n");
    }
    exit(1);
}

fwrite(STDOUT, "Forbidden pattern scan passed.\n");
