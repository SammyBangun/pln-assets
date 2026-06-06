<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Izinkan akses hanya untuk role yang terdaftar.
     * Pemakaian: ->middleware('role:admin') atau ->middleware('role:admin,petugas')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
