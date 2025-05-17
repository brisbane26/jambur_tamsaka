<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'nama_lengkap' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'username' => 'required|string|max:255|unique:users', 
        'telepon' => 'required|string|min:10|max:15',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'username' => $request->username, 
        'telepon' => $request->telepon,
        'password' => Hash::make($request->password),
    ]);

    // Assign role customer secara default
    $user->assignRole('customer');

    event(new Registered($user));

    Auth::login($user);

    return redirect()->route('dashboard');
}
}
