<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JalaliHelper;

class ConvertJalaliDates
{
    protected $dateFields = [
        'birth_date',
        'appointment_date',
        'visit_date',
        'invoice_date',
        'payment_date',
        'admission_date',
        'discharge_date',
        'prescription_date',
        'expense_date',
        'purchase_date',
        'start_date',
        'end_date',
        'due_date',
        'effective_date',
        'registration_date',
        'hire_date',
        'loan_date',
        'advance_date',
    ];

    public function handle(Request $request, Closure $next)
    {
        // تبدیل تاریخ‌های ورودی از شمسی به میلادی
        $this->convertRequestDates($request);

        $response = $next($request);

        // تبدیل تاریخ‌های خروجی از میلادی به شمسی
        return $this->convertResponseDates($response);
    }

    private function convertRequestDates(Request $request): void
    {
        foreach ($this->dateFields as $field) {
            if ($request->has($field) && $request->input($field)) {
                $jalaliDate = JalaliHelper::toEnglishNumbers($request->input($field));

                // اعتبارسنجی تاریخ شمسی
                if (!JalaliHelper::isValidJalaliDate($jalaliDate)) {
                    continue;
                }

                // تبدیل به میلادی
                $gregorianDate = JalaliHelper::toGregorian($jalaliDate);
                if ($gregorianDate) {
                    $request->merge([
                        $field . '_jalali' => JalaliHelper::toPersianNumbers($jalaliDate),
                        $field . '_gregorian' => $gregorianDate->format('Y-m-d'),
                    ]);
                }
            }
        }

        // تبدیل فیلدهای تاریخ و زمان
        $this->convertDateTimeFields($request);
    }

    private function convertDateTimeFields(Request $request): void
    {
        $dateTimeFields = [
            'appointment_datetime',
            'visit_datetime',
        ];

        foreach ($dateTimeFields as $field) {
            if ($request->has($field) && $request->input($field)) {
                $value = JalaliHelper::toEnglishNumbers($request->input($field));

                // فرمت: 1403/01/15 14:30
                $parts = explode(' ', $value);
                if (count($parts) === 2) {
                    [$jalaliDate, $time] = $parts;

                    if (JalaliHelper::isValidJalaliDate($jalaliDate)) {
                        $gregorianDate = JalaliHelper::toGregorian($jalaliDate);
                        if ($gregorianDate) {
                            $datetime = $gregorianDate->format('Y-m-d') . ' ' . $time . ':00';
                            $request->merge([
                                $field . '_jalali' => JalaliHelper::toPersianNumbers($value),
                                $field . '_gregorian' => $datetime,
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function convertResponseDates($response)
    {
        if (method_exists($response, 'getData')) {
            $data = $response->getData();
            $this->convertDatesInResponse($data);
            $response->setData($data);
        }

        return $response;
    }

    private function convertDatesInResponse(&$data): void
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if (is_array($value) || is_object($value)) {
                    $this->convertDatesInResponse($value);
                } elseif ($this->isDateField($key) && $value) {
                    // تبدیل تاریخ میلادی به شمسی برای نمایش
                    $jalaliDate = JalaliHelper::toJalali($value);
                    if ($jalaliDate) {
                        $data[$key . '_jalali'] = JalaliHelper::toPersianNumbers($jalaliDate);
                        $data[$key . '_jalali_display'] = JalaliHelper::formatForDisplay($jalaliDate);
                    }
                }
            }
        } elseif (is_object($data)) {
            foreach (get_object_vars($data) as $key => &$value) {
                if (is_array($value) || is_object($value)) {
                    $this->convertDatesInResponse($value);
                } elseif ($this->isDateField($key) && $value) {
                    $jalaliDate = JalaliHelper::toJalali($value);
                    if ($jalaliDate) {
                        $data->{$key . '_jalali'} = JalaliHelper::toPersianNumbers($jalaliDate);
                        $data->{$key . '_jalali_display'} = JalaliHelper::formatForDisplay($jalaliDate);
                    }
                }
            }
        }
    }

    private function isDateField(string $fieldName): bool
    {
        $dateFieldPatterns = [
            '/_date$/',
            '/_at$/',
            '/date_/',
        ];

        foreach ($dateFieldPatterns as $pattern) {
            if (preg_match($pattern, $fieldName)) {
                return true;
            }
        }

        return in_array($fieldName, $this->dateFields);
    }
}
