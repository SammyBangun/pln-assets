<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah pengguna sudah login dan memiliki role 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Jika admin, lanjutkan ke permintaan berikutnya
        }

        // Jika bukan admin, arahkan ke halaman login
        return redirect()->route('login');
    }
}
