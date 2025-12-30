<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only check for karyawan role
        if ($user && $user->isKaryawan() && !$user->is_profile_complete) {
            // Set a flag in session to show the modal
            session(['show_profile_completion_modal' => true]);
        }

        return $next($request);
    }
}
