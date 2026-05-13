<?php

namespace App\Traits;

trait HandlesWcNotices
{
    /** Reads and clears ALL WC notices for $type. Only safe to call at AJAX request end. */
    protected function getNotice(string $type, string $fallback = ''): string
    {
        $notices = wc_get_notices($type);
        $texts   = array_filter(array_column($notices, 'notice'));
        $message = !empty($texts)
            ? wp_strip_all_tags(implode(' ', $texts))
            : $fallback;
        wc_clear_notices();
        return $message;
    }
}
