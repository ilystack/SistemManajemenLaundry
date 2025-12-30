<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileCompletionController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            // Only allow karyawan to use this endpoint
            if (!$user->isKaryawan()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            ]);

            // Handle photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                    \Storage::disk('public')->delete($user->profile_photo);
                }

                // Store new photo
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $validated['profile_photo'] = $path;
            }

            // Update profile completion status
            $validated['is_profile_complete'] = true;
            $validated['profile_completed_at'] = now();

            // Update user
            $user->update($validated);

            // Clear the session flag
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
