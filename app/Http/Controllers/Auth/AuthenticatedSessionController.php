<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login choice view.
     */
    public function create(): View
    {
        return view('auth.loginchoice');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.login', ['role' => 'admin']);
    }

    /**
     * Display the karyawan login view.
     */
    public function createKaryawan(): View
    {
        return view('auth.login', ['role' => 'karyawan']);
    }

    /**
     * Display the customer login view.
     */
    public function createCustomer(): View
    {
        return view('auth.login', ['role' => 'customer']);
    }

    /**
     * Handle an incoming general authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return $this->logoutWithError($request, 'Halaman ini hanya untuk akun admin.');
        }

        \App\Models\ActivityLog::log(
            'login',
            "{$user->name} login sebagai Admin",
            $user->id,
            $user->name,
            '🔐',
            'green'
        );

        return redirect('/admin/dashboard')->with('toast', [
            'variant' => 'success',
            'title' => 'Selamat Datang!',
            'message' => 'Login berhasil sebagai Admin'
        ]);
    }

    /**
     * Handle an incoming karyawan authentication request.
     */
    public function storeKaryawan(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role !== 'karyawan') {
            return $this->logoutWithError($request, 'Halaman ini hanya untuk akun karyawan.');
        }

        \App\Models\ActivityLog::log(
            'login',
            "{$user->name} login sebagai Karyawan",
            $user->id,
            $user->name,
            '🔐',
            'green'
        );

        return redirect('/karyawan/dashboard');
    }

    /**
     * Handle an incoming customer authentication request.
     */
    public function storeCustomer(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role !== 'customer') {
            return $this->logoutWithError($request, 'Halaman ini hanya untuk akun customer.');
        }

        \App\Models\ActivityLog::log(
            'login',
            "{$user->name} login sebagai Customer",
            $user->id,
            $user->name,
            '🔐',
            'green'
        );

        return redirect('/customer/dashboard');
    }

    /**
     * Redirect based on user role.
     */
    private function redirectBasedOnRole($user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'karyawan' => redirect('/karyawan/dashboard'),
            'customer' => redirect('/customer/dashboard'),
            default => redirect('/dashboard'),
        };
    }

    /**
     * Logout user and return with error message.
     */
    private function logoutWithError(Request $request, string $errorMessage): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => $errorMessage,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
