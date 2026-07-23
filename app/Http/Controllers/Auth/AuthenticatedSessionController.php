<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, AuthServiceContract $authService): RedirectResponse
    {
        $authService->attemptLogin($request->validated());

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request, AuthServiceContract $authService): RedirectResponse
    {
        $authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
