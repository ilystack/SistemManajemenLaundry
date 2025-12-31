<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileCompletionController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user->isKaryawan()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            ]);

            if ($request->hasFile('profile_photo')) {
                // Delete old photo from Cloudinary if exists
                if ($user->profile_photo) {
                    try {
                        // Extract public_id from URL if it's a Cloudinary URL
                        if (str_contains($user->profile_photo, 'cloudinary')) {
                            $publicId = basename(parse_url($user->profile_photo, PHP_URL_PATH), '.' . pathinfo($user->profile_photo, PATHINFO_EXTENSION));
                            \Storage::disk('cloudinary')->delete('profile_photos/' . $publicId);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old profile photo: ' . $e->getMessage());
                    }
                }

                // Upload to Cloudinary
                $uploadedFile = $request->file('profile_photo');
                $path = \Storage::disk('cloudinary')->putFile('profile_photos', $uploadedFile);
                $validated['profile_photo'] = $path;
            }

            $validated['is_profile_complete'] = true;
            $validated['profile_completed_at'] = now();

            $user->update($validated);

            session()->forget('show_profile_completion_modal');

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil dilengkapi!',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Profile completion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
