<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): RedirectResponse
    {
        $role = $request->user()->role;
        if ($role === 'admin') {
            return Redirect::route('admin.dashboard');
        } elseif ($role === 'karyawan') {
            return Redirect::route('karyawan.dashboard');
        } else {
            return Redirect::route('customer.dashboard');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // 5MB max
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($request->user()->profile_photo) {
                try {
                    if (str_contains($request->user()->profile_photo, 'cloudinary')) {
                        $publicId = basename(parse_url($request->user()->profile_photo, PHP_URL_PATH), '.' . pathinfo($request->user()->profile_photo, PATHINFO_EXTENSION));
                        \Storage::disk('cloudinary')->delete('profile_photos/' . $publicId);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old profile photo: ' . $e->getMessage());
                }
            }

            $uploadedFile = $request->file('profile_photo');
            $path = \Storage::disk('cloudinary')->putFile('profile_photos', $uploadedFile);
            $validated['profile_photo'] = $path;
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $role = $request->user()->role;
        if ($role === 'admin') {
            return Redirect::route('admin.dashboard')->with('success', 'Profil berhasil diperbarui!');
        } elseif ($role === 'karyawan') {
            return Redirect::route('karyawan.dashboard')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return Redirect::route('customer.dashboard')->with('success', 'Profil berhasil diperbarui!');
        }
    }

    /**
     * Delete the user's account.
     */
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
