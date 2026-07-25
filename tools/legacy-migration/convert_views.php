<?php

$srcDir = 'e:/LuuDuLieuSinhVien/Nhom3_HeThongTMDTMini_14DHTH07/Nhom3_HeThongTMDTMini_14DHTH07/ThuongMaiDienTu-DoAn/Views';
$destDir = __DIR__ . '/resources/views';

function convertRazorToBlade($content) {
    // Basic replacements
    $content = str_replace('~/Content/', "{{ asset('Content/') }}/", $content);
    $content = str_replace('~/Scripts/', "{{ asset('Scripts/') }}/", $content);
    
    // ViewBag.xyz -> $xyz
    $content = preg_replace('/@?ViewBag\.([a-zA-Z0-9_]+)/', '$$1', $content);
    
    // Html.Raw(xyz) -> {!! xyz !!}
    $content = preg_replace('/@Html\.Raw\((.*?)\)/s', '{!! $1 !!}', $content);

    // @Session["xyz"] -> session('xyz')
    $content = preg_replace('/@Session\["([^"]+)"\]/', "session('$1')", $content);
    $content = preg_replace('/Session\["([^"]+)"\]/', "session('$1')", $content);

    // Html.ActionLink("Text", "Action", "Controller", ...)
    // It's too complex, let's just make a rudimentary replacement or leave it to manual
    
    // @model xyz
    $content = preg_replace('/@model\s+([^\r\n]+)/', '{{-- @model $1 --}}', $content);
    
    // Model. -> $model->
    // @Model -> $Model
    $content = preg_replace('/@Model([^a-zA-Z0-9_])/', '$Model$1', $content);

    // Layout
    $content = preg_replace('/@{[\s\S]*?Layout\s*=\s*"[^"]*_Layout(Admin)?\.cshtml";[\s\S]*?}/', "@extends('shared._layout$1')", $content);
    $content = preg_replace('/@{[\s\S]*?Layout\s*=\s*null;[\s\S]*?}/', "", $content);

    // In Razor: @RenderBody() -> @yield('content')
    $content = str_replace('@RenderBody()', "@yield('content')", $content);
    
    // @RenderSection("scripts", required: false) -> @yield('scripts')
    $content = preg_replace('/@RenderSection\([^)]+\)/', "@yield('scripts')", $content);
    
    // @section scripts { ... } -> @section('scripts') ... @endsection
    $content = preg_replace('/@section\s+([a-zA-Z0-9_]+)\s*{/', "@section('$1')\n", $content);
    // (Ending braces for section have to be fixed manually or by a smart parser, we'll just fix them manually)

    return $content;
}

function processDirectory($src, $dest) {
    if (!is_dir($dest)) {
        mkdir($dest, 0777, true);
    }
    
    $files = scandir($src);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $srcPath = $src . '/' . $file;
        $destPath = $dest . '/' . $file;
        
        if (is_dir($srcPath)) {
            processDirectory($srcPath, $destPath);
        } else {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'cshtml') {
                $content = file_get_contents($srcPath);
                $content = convertRazorToBlade($content);
                
                $destFile = $dest . '/' . str_replace('.cshtml', '.blade.php', strtolower($file));
                // exception for _Layout which we might want to keep exact casing or lower
                file_put_contents($destFile, $content);
            }
        }
    }
}

processDirectory($srcDir, $destDir);
echo "Converted views.\n";
