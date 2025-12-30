<?php

/**
 * Toast Notification Helper Functions
 * 
 * Helper functions untuk memudahkan trigger toast notification
 * dari controller atau anywhere in your Laravel app.
 */

if (!function_exists('toast')) {
    /**
     * Create a toast notification
     * 
     * @param string $variant success|danger|warning|info
     * @param string $title Toast title
     * @param string $message Toast message
     * @return void
     */
    function toast(string $variant, string $title, string $message): void
    {
        session()->flash('toast', [
            'variant' => $variant,
            'title' => $title,
            'message' => $message,
        ]);
    }
}

if (!function_exists('toast_success')) {
    /**
     * Create a success toast notification
     * 
     * @param string $title Toast title
     * @param string $message Toast message
     * @return void
     */
    function toast_success(string $title, string $message): void
    {
        toast('success', $title, $message);
    }
}

if (!function_exists('toast_error')) {
    /**
     * Create an error/danger toast notification
     * 
     * @param string $title Toast title
     * @param string $message Toast message
     * @return void
     */
    function toast_error(string $title, string $message): void
    {
        toast('danger', $title, $message);
    }
}

if (!function_exists('toast_warning')) {
    /**
     * Create a warning toast notification
     * 
     * @param string $title Toast title
     * @param string $message Toast message
     * @return void
     */
    function toast_warning(string $title, string $message): void
    {
        toast('warning', $title, $message);
    }
}

if (!function_exists('toast_info')) {
    /**
     * Create an info toast notification
     * 
     * @param string $title Toast title
     * @param string $message Toast message
     * @return void
     */
    function toast_info(string $title, string $message): void
    {
        toast('info', $title, $message);
    }
}
