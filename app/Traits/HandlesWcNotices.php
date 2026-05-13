<?php

namespace App\Traits;

trait HandlesWcNotices
{
    protected function getNotice(string $type, string $fallback = ''): string
    {
        $notices = wc_get_notices($type);
        $message = !empty($notices)
            ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
            : $fallback;
        wc_clear_notices();
        return $message;
    }
}
