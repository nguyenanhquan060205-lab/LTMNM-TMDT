<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, AuthServiceContract $authService): RedirectResponse
    {
        $authService->register($request->validated());

        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
