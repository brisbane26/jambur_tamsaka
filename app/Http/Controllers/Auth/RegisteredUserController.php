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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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
        'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
        'telepon' => ['required', 'string', 'min:11', 'max:15', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ], 
    [
        // INI ADALAH PARAMETER KEDUA (PESAN KUSTOM)
        'email.unique' => 'Maaf, email ini sudah terdaftar. Silakan gunakan email lain.',
        'telepon.unique' => 'Maaf, nomor telepon ini sudah terdaftar. Silakan gunakan nomor lain.',
        'username.unique' => 'Maaf, username ini sudah terdaftar. Silakan gunakan username lain.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'username.required' => 'Username wajib diisi.',
        'telepon.required' => 'Nomor telepon wajib diisi.',
        'password.required' => 'Password wajib diisi.',
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

public function checkUsername(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'username' => ['required', 'string', 'unique:'.User::class],
    ]);

    return response()->json(['exists' => $validator->fails()]);
}

/**
 * Mengecek keunikan nomor telepon via AJAX.
 */
public function checkTelepon(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'telepon' => ['required', 'string', 'unique:'.User::class],
    ]);

    return response()->json(['exists' => $validator->fails()]);
}

    /**
     * Mengecek keunikan email via AJAX.
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'unique:'.User::class],
        ]);

        return response()->json(['exists' => $validator->fails()]);
    }
}
