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
    $testimonials = \App\Models\Testimonial::approved()
        ->with(['user', 'order.paket'])
        ->latest()
        ->limit(6)
        ->get();

    $paketKg = \App\Models\Paket::where('satuan', 'kg')->get();
    $paketPcs = \App\Models\Paket::where('satuan', 'pcs')->get();

    return view('welcome', compact('testimonials', 'paketKg', 'paketPcs'));
})->name('welcome');

Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

Route::get('/login-choice', [AuthenticatedSessionController::class, 'create'])->name('login.choice');

Route::get('/login/admin', [AuthenticatedSessionController::class, 'createAdmin'])->name('login.admin');
Route::post('/login/admin', [AuthenticatedSessionController::class, 'storeAdmin']);

Route::get('/login/karyawan', [AuthenticatedSessionController::class, 'createKaryawan'])->name('login.karyawan');
Route::post('/login/karyawan', [AuthenticatedSessionController::class, 'storeKaryawan']);

Route::get('/login/customer', [AuthenticatedSessionController::class, 'createCustomer'])->name('login.customer');
Route::post('/login/customer', [AuthenticatedSessionController::class, 'storeCustomer']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/complete', [App\Http\Controllers\ProfileCompletionController::class, 'update'])->name('profile.complete');

    Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::whereIn('status', ['selesai', 'diambil'])->sum('total_harga');
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $totalKaryawan = \App\Models\User::where('role', 'karyawan')->count();
        $recentActivities = \App\Models\ActivityLog::latest()->take(10)->get();

        return view('pages.dashboard.admin', compact('totalOrders', 'totalRevenue', 'totalCustomers', 'totalKaryawan', 'recentActivities'));
    })->name('admin.dashboard');

    Route::resource('paket', PaketController::class)->except(['show', 'edit']);

    Route::resource('user', UserController::class)->except(['show', 'edit', 'update']);

    Route::resource('customer', CustomerController::class)->except(['show', 'edit', 'update']);

    Route::resource('karyawan', KaryawanController::class)->except(['show', 'edit', 'update']);



    Route::patch('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    Route::resource('jam-kerja', JamKerjaController::class);
    Route::post('/jam-kerja/{jamKerja}/toggle', [JamKerjaController::class, 'toggleActive'])->name('jam-kerja.toggle');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
});

Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/dashboard', [DashboardController::class, 'karyawan'])->name('karyawan.dashboard');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        $pakets = \App\Models\Paket::all();

        $admin = \App\Models\User::where('role', 'admin')->first();
        $laundryLocation = [
            'latitude' => $admin->latitude ?? null,
            'longitude' => $admin->longitude ?? null,
        ];

        return view('pages.dashboard.customer', compact('pakets', 'laundryLocation'));
    })->name('customer.dashboard');

    Route::get('/payment/{order}', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/{payment}/status', [App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payment.status');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('order', OrderController::class)->except(['edit', 'destroy']);
    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::get('/order/{order}/receipt', [OrderController::class, 'receipt'])->name('order.receipt');

    Route::get('/absensi/check', [AbsensiController::class, 'checkAbsensi'])->name('absensi.check');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

    Route::post('/testimonial/store', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

require __DIR__ . '/auth.php';
