<?php

if (!function_exists('get_current_locale')) {
    /**
     * Get current locale
     */
    function get_current_locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     */
    function is_rtl()
    {
        return app()->getLocale() === 'ar';
    }
}

if (!function_exists('trans_status')) {
    /**
     * Translate order status
     */
    function trans_status($status)
    {
        return __('messages.' . $status);
    }
}
