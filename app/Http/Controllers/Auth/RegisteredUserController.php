<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $divisions = Division::select('id', 'nama_divisi')->get();

        return Inertia::render('Auth/Register', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|string|max:16|unique:users',
            'divisi' => 'required|exists:divisions,id',
            'lokasi' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'id' => $request->id,
            'divisi' => $request->divisi,
            'lokasi' => $request->lokasi,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect(route('admin.users'))->with('status', 'Registrasi berhasil, silahkan login.');
    }
}
