<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

    public function show(User $user)
    {
        $user = User::with('division')->find($user->id);
        return Inertia::render('Admin/User', [
            'user' => $user
        ]);
    }

    // Memperbarui role pengguna (admin only)
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user,petugas'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role berhasil diupdate');
    }
}
