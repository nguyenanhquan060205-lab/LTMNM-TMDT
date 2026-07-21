<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
$tables = [
    'ct_gio_hangs', 'gio_hangs',
    'ct_hoa_dons', 'hoa_dons',
    'danh_gias', 'khieu_nais', 'tin_nhans',
    'hinh_anh_s_p_s', 'san_phams', 'loai_san_phams',
    'nguoi_dungs'
];

foreach ($tables as $table) {
    DB::table($table)->truncate();
}
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Database cleared successfully!\n";
