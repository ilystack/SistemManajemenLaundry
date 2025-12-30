<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JamKerjaController;
use App\Http\Controllers\TestimonialController;


Route::get('/', function () {
    // Get approved testimonials
    $testimonials = \App\Models\Testimonial::approved()
        ->with(['user', 'order.paket'])
        ->latest()
        ->limit(6)
        ->get();

    // Get paket data
    $paketKg = \App\Models\Paket::where('satuan', 'kg')->get();
    $paketPcs = \App\Models\Paket::where('satuan', 'pcs')->get();

    return view('welcome', compact('testimonials', 'paketKg', 'paketPcs'));
})->name('welcome');

// Midtrans Webhook (no auth required)
Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::get('/login-choice', [AuthenticatedSessionController::class, 'create'])->name('login.choice');

Route::get('/login/admin', [AuthenticatedSessionController::class, 'createAdmin'])->name('login.admin');
Route::post('/login/admin', [AuthenticatedSessionController::class, 'storeAdmin']);

Route::get('/login/karyawan', [AuthenticatedSessionController::class, 'createKaryawan'])->name('login.karyawan');
Route::post('/login/karyawan', [AuthenticatedSessionController::class, 'storeKaryawan']);

Route::get('/login/customer', [AuthenticatedSessionController::class, 'createCustomer'])->name('login.customer');
Route::post('/login/customer', [AuthenticatedSessionController::class, 'storeCustomer']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// ============================================
// PROFILE ROUTES (All authenticated users)
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Completion (for karyawan)
    Route::post('/profile/complete', [App\Http\Controllers\ProfileCompletionController::class, 'update'])->name('profile.complete');

    // Global Search
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function () {
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::whereIn('status', ['selesai', 'diambil'])->sum('total_harga');
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $totalKaryawan = \App\Models\User::where('role', 'karyawan')->count();
        $recentActivities = \App\Models\ActivityLog::latest()->take(10)->get();

        return view('pages.dashboard.admin', compact('totalOrders', 'totalRevenue', 'totalCustomers', 'totalKaryawan', 'recentActivities'));
    })->name('admin.dashboard');

    // Paket Management
    Route::resource('paket', PaketController::class)->except(['show', 'edit']);

    // User Management (General - Karyawan & Customer)
    Route::resource('user', UserController::class)->except(['show', 'edit', 'update']);

    // Customer Management
    Route::resource('customer', CustomerController::class)->except(['show', 'edit', 'update']);

    // Karyawan Management
    Route::resource('karyawan', KaryawanController::class)->except(['show', 'edit', 'update']);



    // Settings (Modal - PATCH only)
    Route::patch('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    // Jam Kerja Management
    Route::resource('jam-kerja', JamKerjaController::class);
    Route::post('/jam-kerja/{jamKerja}/toggle', [JamKerjaController::class, 'toggleActive'])->name('jam-kerja.toggle');

    // Absensi Management (View riwayat)
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
});

// ============================================
// KARYAWAN ROUTES
// ============================================
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/dashboard', [DashboardController::class, 'karyawan'])->name('karyawan.dashboard');
});

// ============================================
// CUSTOMER ROUTES
// ============================================
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        $pakets = \App\Models\Paket::all();

        // Get laundry location from admin user
        $admin = \App\Models\User::where('role', 'admin')->first();
        $laundryLocation = [
            'latitude' => $admin->latitude ?? null,
            'longitude' => $admin->longitude ?? null,
        ];

        return view('pages.dashboard.customer', compact('pakets', 'laundryLocation'));
    })->name('customer.dashboard');

    // Payment routes
    Route::get('/payment/{order}', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/{payment}/status', [App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payment.status');
});

// ============================================
// SHARED ROUTES (Admin & Karyawan)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Order Management (Admin & Karyawan can manage orders)
    Route::resource('order', OrderController::class)->except(['edit', 'destroy']);
    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::get('/order/{order}/receipt', [OrderController::class, 'receipt'])->name('order.receipt');

    // Absensi routes (untuk karyawan)
    Route::get('/absensi/check', [AbsensiController::class, 'checkAbsensi'])->name('absensi.check');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

    // Testimonial routes
    Route::post('/testimonial/store', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');

    // Transaksi (Riwayat) - Admin & Karyawan can view
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

require __DIR__ . '/auth.php';
