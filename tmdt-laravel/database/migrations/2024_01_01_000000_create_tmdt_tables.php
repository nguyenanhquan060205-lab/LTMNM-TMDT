<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng NGUOIDUNG
        Schema::create('NGUOIDUNG', function (Blueprint $table) {
            $table->id('MaKH');
            $table->string('HoTen', 100);
            $table->string('GioiTinh', 10)->nullable();
            $table->date('NgaySinh')->nullable();
            $table->string('VaiTro', 10)->default('User'); // 'Admin' or 'User'
            $table->string('MatKhau', 100);
            $table->string('TaiKhoan', 50)->unique();
            $table->string('Email', 100)->unique()->nullable();
            $table->string('SDT', 20)->unique()->nullable();
            $table->string('DiaChi', 200)->nullable();
            $table->string('AnhDaiDien', 255)->default('default.jpg');
            $table->boolean('Khoa')->default(false);
            $table->string('SoTaiKhoan', 50)->nullable();
            $table->string('TenNganHang', 100)->nullable();
            $table->dateTime('NgayTao')->useCurrent();
        });

        // 2. Bảng LOAISANPHAM
        Schema::create('LOAISANPHAM', function (Blueprint $table) {
            $table->id('MaLoai');
            $table->string('TenLoai', 100);
        });

        // 3. Bảng SANPHAM
        Schema::create('SANPHAM', function (Blueprint $table) {
            $table->id('MaSP');
            $table->unsignedBigInteger('MaKH');
            $table->unsignedBigInteger('MaLoai');
            $table->string('TenSP', 200);
            $table->text('MoTa')->nullable();
            $table->decimal('Gia', 18, 2)->default(0);
            $table->integer('SoLuong')->default(0);
            $table->float('DanhGiaTB')->default(0);
            $table->integer('TongDanhGia')->default(0);
            $table->string('TrangThai', 20)->default('Đã duyệt'); // 'Đã duyệt', 'Đã bán', 'Ẩn'
            $table->dateTime('NgayTao')->useCurrent();

            $table->foreign('MaKH')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
            $table->foreign('MaLoai')->references('MaLoai')->on('LOAISANPHAM')->onDelete('cascade');
        });

        // 4. Bảng HINHANHSP
        Schema::create('HINHANHSP', function (Blueprint $table) {
            $table->id('MaHA');
            $table->unsignedBigInteger('MaSP');
            $table->string('URLAnh', 255);
            $table->boolean('AnhBia')->default(false);

            $table->foreign('MaSP')->references('MaSP')->on('SANPHAM')->onDelete('cascade');
        });

        // 5. Bảng GIOHANG
        Schema::create('GIOHANG', function (Blueprint $table) {
            $table->id('MaGH');
            $table->unsignedBigInteger('MaKH')->unique();
            $table->integer('TongSoLuong')->default(0);

            $table->foreign('MaKH')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
        });

        // 6. Bảng CT_GIOHANG
        Schema::create('CT_GIOHANG', function (Blueprint $table) {
            $table->unsignedBigInteger('MaGH');
            $table->unsignedBigInteger('MaSP');
            $table->integer('SoLuong')->default(1);
            $table->decimal('ThanhTien', 18, 2)->default(0);
            
            $table->primary(['MaGH', 'MaSP']);
            $table->foreign('MaGH')->references('MaGH')->on('GIOHANG')->onDelete('cascade');
            $table->foreign('MaSP')->references('MaSP')->on('SANPHAM')->onDelete('cascade');
        });

        // 7. Bảng HOADON
        Schema::create('HOADON', function (Blueprint $table) {
            $table->id('MaHD');
            $table->unsignedBigInteger('MaKH');
            $table->decimal('TongTien', 18, 2)->default(0);
            $table->string('PhuongThucTT', 50)->nullable();
            $table->string('DiaChiGiaoHang', 200)->nullable();
            $table->dateTime('NgayTT')->nullable();
            $table->dateTime('NgayDat')->useCurrent();
            $table->string('TrangThai', 20)->default('Đang chờ xử lý'); // Đang chờ xử lý, Đã thanh toán, Đã Huỷ

            $table->foreign('MaKH')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
        });

        // 8. Bảng CT_HOADON
        Schema::create('CT_HOADON', function (Blueprint $table) {
            $table->unsignedBigInteger('MaHD');
            $table->unsignedBigInteger('MaSP');
            $table->integer('SoLuong');
            $table->decimal('ThanhTien', 18, 2);
            $table->string('TrangThaiCT', 50)->default('Chờ xác nhận'); // Chờ xác nhận, Đã xác nhận, Đã Huỷ
            $table->boolean('DaDanhGia')->default(false);

            $table->primary(['MaHD', 'MaSP']);
            $table->foreign('MaHD')->references('MaHD')->on('HOADON')->onDelete('cascade');
            $table->foreign('MaSP')->references('MaSP')->on('SANPHAM')->onDelete('cascade');
        });

        // 9. Bảng DANHGIA
        Schema::create('DANHGIA', function (Blueprint $table) {
            $table->id('MaDG');
            $table->unsignedBigInteger('MaKH');
            $table->unsignedBigInteger('MaSP');
            $table->unsignedBigInteger('MaHD');
            $table->integer('SoSao');
            $table->text('NoiDung')->nullable();
            $table->dateTime('NgayDG')->useCurrent();

            $table->unique(['MaKH', 'MaHD', 'MaSP']);
            $table->foreign('MaKH')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
            // Constraints to CT_HOADON
            $table->foreign(['MaHD', 'MaSP'])->references(['MaHD', 'MaSP'])->on('CT_HOADON')->onDelete('cascade');
        });

        // 10. Bảng KHIEUNAI
        Schema::create('KHIEUNAI', function (Blueprint $table) {
            $table->id('MaKN');
            $table->unsignedBigInteger('MaKH');
            $table->unsignedBigInteger('MaSP');
            $table->text('MoTa')->nullable();
            $table->text('PhanHoi')->nullable();
            $table->dateTime('NgayGui')->useCurrent();
            $table->string('TrangThai', 20)->default('Chưa xử lý');

            $table->foreign('MaKH')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
            $table->foreign('MaSP')->references('MaSP')->on('SANPHAM')->onDelete('cascade');
        });

        // 11. Bảng TINNHAN
        Schema::create('TINNHAN', function (Blueprint $table) {
            $table->id('MaTN');
            $table->unsignedBigInteger('NguoiGui');
            $table->unsignedBigInteger('NguoiNhan');
            $table->unsignedBigInteger('MaSP')->nullable();
            $table->dateTime('NgayGui')->useCurrent();
            $table->text('NoiDung')->nullable();
            $table->boolean('DaDoc')->default(false);
            $table->string('Anh', 255)->nullable();

            $table->foreign('NguoiGui')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
            $table->foreign('NguoiNhan')->references('MaKH')->on('NGUOIDUNG')->onDelete('cascade');
            $table->foreign('MaSP')->references('MaSP')->on('SANPHAM')->onDelete('set null');
        });

        // TRIGGERS (Replicating SQL Server triggers to MySQL)
        DB::unprepared('
            CREATE TRIGGER trg_ct_giohang_insert AFTER INSERT ON CT_GIOHANG FOR EACH ROW
            BEGIN
                UPDATE GIOHANG SET TongSoLuong = (SELECT IFNULL(SUM(SoLuong), 0) FROM CT_GIOHANG WHERE MaGH = NEW.MaGH) WHERE MaGH = NEW.MaGH;
            END;
        ');
        DB::unprepared('
            CREATE TRIGGER trg_ct_giohang_update AFTER UPDATE ON CT_GIOHANG FOR EACH ROW
            BEGIN
                UPDATE GIOHANG SET TongSoLuong = (SELECT IFNULL(SUM(SoLuong), 0) FROM CT_GIOHANG WHERE MaGH = NEW.MaGH) WHERE MaGH = NEW.MaGH;
            END;
        ');
        DB::unprepared('
            CREATE TRIGGER trg_ct_giohang_delete AFTER DELETE ON CT_GIOHANG FOR EACH ROW
            BEGIN
                UPDATE GIOHANG SET TongSoLuong = (SELECT IFNULL(SUM(SoLuong), 0) FROM CT_GIOHANG WHERE MaGH = OLD.MaGH) WHERE MaGH = OLD.MaGH;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_danhgia_insert AFTER INSERT ON DANHGIA FOR EACH ROW
            BEGIN
                UPDATE SANPHAM SET 
                    DanhGiaTB = (SELECT IFNULL(AVG(SoSao), 0) FROM DANHGIA WHERE MaSP = NEW.MaSP),
                    TongDanhGia = (SELECT COUNT(*) FROM DANHGIA WHERE MaSP = NEW.MaSP)
                WHERE MaSP = NEW.MaSP;
            END;
        ');
        DB::unprepared('
            CREATE TRIGGER trg_danhgia_update AFTER UPDATE ON DANHGIA FOR EACH ROW
            BEGIN
                UPDATE SANPHAM SET 
                    DanhGiaTB = (SELECT IFNULL(AVG(SoSao), 0) FROM DANHGIA WHERE MaSP = NEW.MaSP),
                    TongDanhGia = (SELECT COUNT(*) FROM DANHGIA WHERE MaSP = NEW.MaSP)
                WHERE MaSP = NEW.MaSP;
            END;
        ');
        DB::unprepared('
            CREATE TRIGGER trg_danhgia_delete AFTER DELETE ON DANHGIA FOR EACH ROW
            BEGIN
                UPDATE SANPHAM SET 
                    DanhGiaTB = (SELECT IFNULL(AVG(SoSao), 0) FROM DANHGIA WHERE MaSP = OLD.MaSP),
                    TongDanhGia = (SELECT COUNT(*) FROM DANHGIA WHERE MaSP = OLD.MaSP)
                WHERE MaSP = OLD.MaSP;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_ct_giohang_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_ct_giohang_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_ct_giohang_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_danhgia_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_danhgia_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_danhgia_delete');

        Schema::dropIfExists('TINNHAN');
        Schema::dropIfExists('KHIEUNAI');
        Schema::dropIfExists('DANHGIA');
        Schema::dropIfExists('CT_HOADON');
        Schema::dropIfExists('HOADON');
        Schema::dropIfExists('CT_GIOHANG');
        Schema::dropIfExists('GIOHANG');
        Schema::dropIfExists('HINHANHSP');
        Schema::dropIfExists('SANPHAM');
        Schema::dropIfExists('LOAISANPHAM');
        Schema::dropIfExists('NGUOIDUNG');
    }
};
