<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = \Illuminate\Support\Facades\Session::get('user');

        if (!$user) {
            return redirect('/taikhoan/dangnhap')->with('Error', 'Vui lòng đăng nhập!');
        }

        if ($user->VaiTro !== 'Admin') {
            abort(403, 'Không có quyền truy cập.');
        }

        return $next($request);
    }
}