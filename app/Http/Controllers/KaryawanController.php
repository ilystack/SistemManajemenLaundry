<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = User::where('role', 'karyawan')
            ->latest()
            ->paginate(20);

        return view('pages.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('pages.karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'karyawan',
            'is_active' => true,
        ]);

        \App\Models\ActivityLog::log(
            'karyawan',
            "Karyawan baru: {$user->name}",
            null,
            'Admin',
            '👔',
            'amber'
        );

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function destroy(User $karyawan)
    {
        if ($karyawan->role !== 'karyawan') {
            abort(403, 'Unauthorized action.');
        }

        $karyawan->delete();

        return back()->with('success', 'Karyawan berhasil dihapus');
    }
}
