<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('pages.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('pages.customer.create');
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
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Log activity
        \App\Models\ActivityLog::log(
            'customer',
            "Customer baru: {$user->name}",
            null,
            'Admin',
            '👤',
            'purple'
        );

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $customer->delete();

        return back()->with('success', 'Customer berhasil dihapus');
    }
}
