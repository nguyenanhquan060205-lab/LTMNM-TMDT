<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Social login error ($provider): " . $e->getMessage());
            return redirect()->route('taikhoan.dangnhap')->with('error', 'Có lỗi xảy ra khi đăng nhập bằng mạng xã hội.');
        }

        $providerIdField = $provider . '_id';

        // Tìm người dùng theo provider_id hoặc email
        $user = NguoiDung::where($providerIdField, $socialUser->id)
                    ->orWhere('Email', $socialUser->email)
                    ->first();

        if ($user) {
            // Nếu tìm thấy theo email nhưng chưa có provider_id, thì cập nhật
            if (empty($user->$providerIdField)) {
                $user->$providerIdField = $socialUser->id;
                
                // Đã xác thực bằng Google/FB thì xem như email đã được xác minh
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                
                $user->save();
            }

            if ($user->Khoa == true) {
                return redirect()->route('taikhoan.dangnhap')->with('error', 'Tài khoản của bạn đã bị khóa! Vui lòng liên hệ Admin.');
            }

            // Đăng nhập
            Session::put('user', $user);
            $this->updateCartCount($user);

            return redirect()->route('index');
        } else {
            // Tạo mới người dùng
            $baseUsername = strtolower(explode('@', $socialUser->email)[0] ?? Str::slug($socialUser->name));
            $username = $baseUsername;
            $counter = 1;
            while (NguoiDung::where('TaiKhoan', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // Tải ảnh avatar từ MXH (nếu có thể)
            $avatarPath = 'Default.jpg';
            if ($socialUser->avatar) {
                $avatarPath = $socialUser->avatar; // Lưu trực tiếp URL ảnh
            }

            $newUser = NguoiDung::create([
                'HoTen' => $socialUser->name,
                'TaiKhoan' => $username,
                'Email' => $socialUser->email,
                'MatKhau' => Hash::make(Str::random(16)), // Mật khẩu ngẫu nhiên
                'VaiTro' => 'User',
                'AnhDaiDien' => $avatarPath,
                'Khoa' => false,
                'NgayTao' => now(),
                $providerIdField => $socialUser->id,
                'email_verified_at' => now(), // Đăng nhập MXH thì email đã xác thực
            ]);

            Session::put('user', $newUser);
            Session::put('CartCount', 0);

            return redirect()->route('index');
        }
    }

    private function updateCartCount($user)
    {
        $gio = \App\Models\GioHang::where('MaKH', $user->MaKH)->first();
        if ($gio) {
            $tong = \App\Models\CtGioHang::where('MaGH', $gio->MaGH)->sum('SoLuong');
            Session::put('CartCount', $tong);
        } else {
            Session::put('CartCount', 0);
        }
    }
}
