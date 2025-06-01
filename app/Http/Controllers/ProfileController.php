<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
   public function edit()
{
    $user = Auth::user();

    if (!$user instanceof \App\Models\User) {
        abort(500, 'Authenticated user is not an instance of the User model.');
    }

    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'username' => 'required',
        'email' => 'required|email',
        'nama_lengkap' => 'required',
        'telepon' => 'nullable',
        'password' => 'nullable|min:6',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $user->username = $request->username;
    $user->email = $request->email;
    $user->nama_lengkap = $request->nama_lengkap;
    $user->telepon = $request->telepon;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    if ($request->hasFile('gambar')) {
        $gambar = $request->file('gambar')->store('profile_images', 'public');
        $user->gambar = $gambar;
    }

    $user->save();

            $notifications = [
            'message' => 'Profil Berhasil Diperbarui!',
            'alert-type' => 'success'
        ];

        return redirect()->route('profile.edit')->with($notifications);
}

public function destroy(Request $request): RedirectResponse
{
    $request->validateWithBag('userDeletion', [
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
}

}
