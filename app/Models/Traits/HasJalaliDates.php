<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Helpers\JalaliHelper;

trait HasJalaliDates
{
    /**
     * Scope برای فیلتر بر اساس تاریخ شمسی
     */
    public function scopeWhereJalaliDate(Builder $query, string $jalaliDate, string $column = 'date'): Builder
    {
        $gregorianDate = JalaliHelper::toGregorian($jalaliDate);
        if (!$gregorianDate) {
            return $query;
        }

        $gregorianColumn = $column . '_gregorian';
        if ($this->hasColumn($gregorianColumn)) {
            return $query->whereDate($gregorianColumn, $gregorianDate->format('Y-m-d'));
        }

        return $query->whereDate($column, $gregorianDate->format('Y-m-d'));
    }

    /**
     * Scope برای فیلتر بر اساس ماه شمسی
     */
    public function scopeWhereJalaliMonth(Builder $query, int $year, int $month, string $column = 'date'): Builder
    {
        $startJalali = sprintf('%04d/%02d/01', $year, $month);
        $dayNumbers = JalaliHelper::getDaysInJalaliMonth($year, $month);
        $endJalali = sprintf('%04d/%02d/%02d', $year, $month,  $dayNumbers);

        $startDate = JalaliHelper::toGregorian($startJalali);
        $endDate = JalaliHelper::toGregorian($endJalali);

        if (!$startDate || !$endDate) {
            return $query;
        }

        $gregorianColumn = $column . '_gregorian';
        if ($this->hasColumn($gregorianColumn)) {
            return $query->whereBetween($gregorianColumn, [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]);
        }

        return $query->whereBetween($column, [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);
    }

    /**
     * Scope برای فیلتر بر اساس سال شمسی
     */
    public function scopeWhereJalaliYear(Builder $query, int $year, string $column = 'date'): Builder
    {
        $startJalali = sprintf('%04d/01/01', $year);
        $endJalali = sprintf('%04d/12/29', $year); // 29 روز برای سال کبیسه

        $startDate = JalaliHelper::toGregorian($startJalali);
        $endDate = JalaliHelper::toGregorian($endJalali);

        if (!$startDate || !$endDate) {
            return $query;
        }

        $gregorianColumn = $column . '_gregorian';
        if ($this->hasColumn($gregorianColumn)) {
            return $query->whereBetween($gregorianColumn, [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]);
        }

        return $query->whereBetween($column, [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);
    }

    /**
     * Scope برای فیلتر بر اساس بازه تاریخ شمسی
     */
    public function scopeWhereJalaliDateBetween(Builder $query, string $startJalali, string $endJalali, string $column = 'date'): Builder
    {
        $startDate = JalaliHelper::toGregorian($startJalali);
        $endDate = JalaliHelper::toGregorian($endJalali);

        if (!$startDate || !$endDate) {
            return $query;
        }

        $gregorianColumn = $column . '_gregorian';
        if ($this->hasColumn($gregorianColumn)) {
            return $query->whereBetween($gregorianColumn, [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]);
        }

        return $query->whereBetween($column, [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ]);
    }

    /**
     * Scope برای امروز (شمسی)
     */
    public function scopeWhereTodayJalali(Builder $query, string $column = 'date'): Builder
    {
        $today = JalaliHelper::today();
        return $this->scopeWhereJalaliDate($query, $today, $column);
    }

    /**
     * Scope برای این ماه (شمسی)
     */
    public function scopeWhereThisMonthJalali(Builder $query, string $column = 'date'): Builder
    {
        $today = JalaliHelper::today();
        $parts = explode('/', $today);

        if (count($parts) === 3) {
            [$year, $month, $day] = $parts;
            return $this->scopeWhereJalaliMonth($query, (int)$year, (int)$month, $column);
        }

        return $query;
    }

    /**
     * Scope برای این سال (شمسی)
     */
    public function scopeWhereThisYearJalali(Builder $query, string $column = 'date'): Builder
    {
        $today = JalaliHelper::today();
        $parts = explode('/', $today);

        if (count($parts) === 3) {
            [$year, $month, $day] = $parts;
            return $this->scopeWhereJalaliYear($query, (int)$year, $column);
        }

        return $query;
    }

    /**
     * بررسی وجود ستون در جدول
     */
    private function hasColumn(string $column): bool
    {
        return isset($this->jalaliColumns) && in_array($column, $this->jalaliColumns);
    }

    /**
     * Accessor برای تبدیل تاریخ به شمسی
     */
    protected function getJalaliDateAttribute(): array
    {
        $dates = [];

        foreach ($this->jalaliDateColumns ?? [] as $column) {
            if ($this->{$column}) {
                $dates[$column] = [
                    'gregorian' => $this->{$column},
                    'jalali' => JalaliHelper::toJalali($this->{$column}),
                    'jalali_display' => JalaliHelper::formatForDisplay(
                        JalaliHelper::toJalali($this->{$column})
                    ),
                ];
            }
        }

        return $dates;
    }
}
