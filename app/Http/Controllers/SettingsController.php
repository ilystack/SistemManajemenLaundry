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
            if ($user->laundry_logo && Storage::exists('public/' . $user->laundry_logo)) {
                Storage::delete('public/' . $user->laundry_logo);
            }

            $path = $request->file('laundry_logo')->store('logos', 'public');
            $user->laundry_logo = $path;
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
