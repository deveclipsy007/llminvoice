<?php

declare(strict_types=1);

/**
 * Format a date string.
 */
function format_date(?string $date, string $format = 'd/m/Y'): string
{
    if (!$date) {
        return '-';
    }

    try {
        return (new DateTime($date))->format($format);
    } catch (Exception) {
        return $date;
    }
}

/**
 * Format a datetime string.
 */
function format_datetime(?string $datetime, string $format = 'd/m/Y H:i'): string
{
    return format_date($datetime, $format);
}

/**
 * Get relative time ago string.
 */
function time_ago(?string $datetime): string
{
    if (!$datetime) {
        return '-';
    }

    try {
        $now = new DateTime();
        $time = new DateTime($datetime);
        $diff = $now->diff($time);

        if ($diff->y > 0) {
            return $diff->y === 1 ? __('messages.time_year_ago') : __('messages.time_years_ago', ['n' => $diff->y]);
        }
        if ($diff->m > 0) {
            return $diff->m === 1 ? __('messages.time_month_ago') : __('messages.time_months_ago', ['n' => $diff->m]);
        }
        if ($diff->d > 0) {
            if ($diff->d === 1) {
                return __('messages.yesterday');
            }
            return __('messages.time_days_ago', ['n' => $diff->d]);
        }
        if ($diff->h > 0) {
            return $diff->h === 1 ? __('messages.time_hour_ago') : __('messages.time_hours_ago', ['n' => $diff->h]);
        }
        if ($diff->i > 0) {
            return $diff->i === 1 ? __('messages.time_minute_ago') : __('messages.time_minutes_ago', ['n' => $diff->i]);
        }

        return __('messages.just_now');
    } catch (Exception) {
        return $datetime;
    }
}

/**
 * Format duration in days to human readable.
 */
function format_duration(int $days): string
{
    if ($days < 7) {
        return $days . ' ' . ($days === 1 ? __('messages.day') : __('messages.days'));
    }

    $weeks = (int) floor($days / 7);
    $remaining = $days % 7;

    $result = $weeks . ' ' . ($weeks === 1 ? __('messages.week') : __('messages.weeks'));

    if ($remaining > 0) {
        $result .= ' ' . __('messages.and') . ' ' . $remaining . ' ' . ($remaining === 1 ? __('messages.day') : __('messages.days'));
    }

    return $result;
}
