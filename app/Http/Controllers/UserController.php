<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request; // Pastikan import yang benar
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Menampilkan data pengguna (admin only)
    public function index()
    {
        $users = User::all();
        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    // Memperbarui role pengguna (admin only)
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role berhasil diupdate');
    }
}
