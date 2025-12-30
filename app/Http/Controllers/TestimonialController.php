<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Store a new testimonial
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Cek apakah order milik user yang login
        $order = Order::findOrFail($request->order_id);
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan!'
            ], 403);
        }

        // Cek apakah order sudah selesai
        if ($order->status !== 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya order yang selesai yang bisa diberi penilaian!'
            ], 400);
        }

        // Cek apakah sudah pernah kasih testimoni untuk order ini
        $existingTestimonial = Testimonial::where('order_id', $request->order_id)->first();
        if ($existingTestimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan penilaian untuk order ini!'
            ], 400);
        }

        // Simpan testimonial
        $testimonial = Testimonial::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true, // Auto approve
        ]);

        // Log activity
        \App\Models\ActivityLog::log(
            'testimonial',
            "Testimoni baru: {$request->rating} ⭐ dari " . Auth::user()->name,
            Auth::id(),
            Auth::user()->name,
            '⭐',
            'yellow'
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas penilaian Anda! 🌟',
            'testimonial' => $testimonial->load('user', 'order')
        ]);
    }

    /**
     * Get approved testimonials for welcome page
     */
    public function index()
    {
        $testimonials = Testimonial::approved()
            ->with(['user', 'order.paket'])
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials
        ]);
    }
}
