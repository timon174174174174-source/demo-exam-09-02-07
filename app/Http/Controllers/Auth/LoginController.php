<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /** Форма входа. */
    public function create(): View
    {
        return view('auth.login');
    }

    /** Аутентификация по паре логин-пароль. */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt(
            ['login' => $credentials['login'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Неверный логин или пароль.']);
        }

        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Вы вошли как администратор.');
        }

        return redirect()->intended(route('cabinet'))
            ->with('success', 'Вы успешно вошли в систему.');
    }

    /** Выход из системы. */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Вы вышли из системы.');
    }
}
