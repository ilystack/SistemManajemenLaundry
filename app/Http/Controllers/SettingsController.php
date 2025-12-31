<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        return view('pages.settings.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $request->validate([
            'laundry_name' => 'nullable|string|max:255',
            'laundry_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $user->laundry_name = $request->laundry_name;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;

        if ($request->hasFile('laundry_logo')) {
            if ($user->laundry_logo) {
                try {
                    if (str_contains($user->laundry_logo, 'cloudinary')) {
                        $publicId = basename(parse_url($user->laundry_logo, PHP_URL_PATH), '.' . pathinfo($user->laundry_logo, PATHINFO_EXTENSION));
                        Storage::disk('cloudinary')->delete('logos/' . $publicId);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old laundry logo: ' . $e->getMessage());
                }
            }

            $uploadedFile = $request->file('laundry_logo');
            $path = Storage::disk('cloudinary')->putFile('logos', $uploadedFile);
            $user->laundry_logo = $path;
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
