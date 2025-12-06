<?php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use Illuminate\Support\Str;

class JalaliHelper
{
    /**
     * تبدیل تاریخ میلادی به شمسی
     */
    public static function toJalali($gregorianDate, $format = 'Y/m/d'): ?string
    {
        if (!$gregorianDate) {
            return null;
        }

        try {
            if ($gregorianDate instanceof Carbon) {
                return Jalalian::fromCarbon($gregorianDate)->format($format);
            }

            return Jalalian::fromDateTime($gregorianDate)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * تبدیل تاریخ شمسی به میلادی
     */
    public static function toGregorian(string $jalaliDate): ?Carbon
    {
        if (!$jalaliDate || !self::isValidJalaliDate($jalaliDate)) {
            return null;
        }

        try {
            $parts = explode('/', $jalaliDate);
            if (count($parts) !== 3) {
                return null;
            }

            [$year, $month, $day] = $parts;
            return Jalalian::fromFormat('Y/m/d', $jalaliDate)->toCarbon();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * اعتبارسنجی تاریخ شمسی
     */
    public static function isValidJalaliDate(string $jalaliDate): bool
    {
        if (!preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $jalaliDate)) {
            return false;
        }

        $parts = explode('/', $jalaliDate);
        [$year, $month, $day] = $parts;

        // محدوده سال
        if ($year < 1300 || $year > 1500) {
            return false;
        }

        // محدوده ماه
        if ($month < 1 || $month > 12) {
            return false;
        }

        // محدوده روز
        $daysInMonth = self::getDaysInJalaliMonth($year, $month);
        if ($day < 1 || $day > $daysInMonth) {
            return false;
        }

        return true;
    }

    /**
     * تعداد روزهای ماه شمسی
     */
    public static function getDaysInJalaliMonth(int $year, int $month): int
    {
        return Jalalian::fromFormat('Y/m/d', "{$year}/{$month}/01")->getMonthDays();
    }


    /**
     * بررسی سال کبیسه شمسی
     */
    public static function isLeapJalaliYear(int $year): bool
    {
        return Jalalian::fromFormat('Y/m/d', "{$year}/01/01")->isLeapYear();
    }


    /**
     * تاریخ امروز به شمسی
     */
    public static function today(string $format = 'Y/m/d'): string
    {
        return Jalalian::now()->format($format);
    }

    /**
     * نام ماه شمسی
     */
    public static function getMonthName(int $monthNumber): string
    {
        $months = [
            1 => 'حمل',
            2 => 'ثور',
            3 => 'جوزا',
            4 => 'سرطان',
            5 => 'اسد',
            6 => 'سنبله',
            7 => 'میزان',
            8 => 'عقرب',
            9 => 'قوس',
            10 => 'جدی',
            11 => 'دلو',
            12 => 'حوت',
        ];

        return $months[$monthNumber] ?? 'نامشخص';
    }

    /**
     * نام روز هفته
     */
    public static function getDayName(int $dayOfWeek): string
    {
        $days = [
            0 => 'یکشنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چارشنبه',
            4 => 'پنجشنبه',
            5 => 'جمعه',
            6 => 'شنبه',
        ];

        return $days[$dayOfWeek] ?? 'نامشخص';
    }

    /**
     * محاسبه سن
     */
    public static function calculateAge(string $birthJalaliDate): ?int
    {
        if (!self::isValidJalaliDate($birthJalaliDate)) {
            return null;
        }

        $birthDate = self::toGregorian($birthJalaliDate);
        $today = Carbon::now();

        return $today->diffInYears($birthDate);
    }

    /**
     * تاریخ و زمان به شمسی
     */
    public static function toJalaliDateTime($gregorianDateTime, string $dateFormat = 'Y/m/d', string $timeFormat = 'H:i'): ?string
    {
        if (!$gregorianDateTime) {
            return null;
        }

        try {
            $carbon = $gregorianDateTime instanceof Carbon
                ? $gregorianDateTime
                : Carbon::parse($gregorianDateTime);

            $jalaliDate = self::toJalali($carbon, $dateFormat);
            $time = $carbon->format($timeFormat);

            return $jalaliDate . ' ' . $time;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * افزودن روز به تاریخ شمسی
     */
    public static function addDays(string $jalaliDate, int $days): ?string
    {
        $gregorian = self::toGregorian($jalaliDate);
        if (!$gregorian) {
            return null;
        }

        $newDate = $gregorian->addDays($days);
        return self::toJalali($newDate);
    }

    /**
     * تفاوت دو تاریخ شمسی
     */
    public static function diffInDays(string $startJalaliDate, string $endJalaliDate): ?int
    {
        $start = self::toGregorian($startJalaliDate);
        $end = self::toGregorian($endJalaliDate);

        if (!$start || !$end) {
            return null;
        }

        return $start->diffInDays($end);
    }

    /**
     * فرمت کردن تاریخ شمسی برای نمایش
     */
    public static function formatForDisplay(string $jalaliDate, bool $withDayName = false): ?string
    {
        if (!self::isValidJalaliDate($jalaliDate)) {
            return $jalaliDate;
        }

        $parts = explode('/', $jalaliDate);
        [$year, $month, $day] = $parts;

        $formatted = $day . ' ' . self::getMonthName((int)$month) . ' ' . $year;

        if ($withDayName) {
            $gregorian = self::toGregorian($jalaliDate);
            $dayName = self::getDayName($gregorian->dayOfWeek);
            $formatted = $dayName . '، ' . $formatted;
        }

        return $formatted;
    }

    /**
     * تبدیل اعداد انگلیسی به فارسی
     */
    public static function toPersianNumbers(string $text): string
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($english, $persian, $text);
    }

    /**
     * تبدیل اعداد فارسی به انگلیسی
     */
    public static function toEnglishNumbers(string $text): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persian, $english, $text);
    }
}
