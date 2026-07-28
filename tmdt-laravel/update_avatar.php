<?php
DB::update("UPDATE nguoidung SET AnhDaiDien = 'Default.jpg' WHERE AnhDaiDien = 'default.jpg'");
echo "Cập nhật thành công!";
