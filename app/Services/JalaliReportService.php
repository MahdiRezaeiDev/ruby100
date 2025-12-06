<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\Doctor;
use App\Helpers\JalaliHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class JalaliReportService
{
    /**
     * گزارش روزانه
     */
    public function getDailyReport(string $jalaliDate): array
    {
        $gregorianDate = JalaliHelper::toGregorian($jalaliDate);
        if (!$gregorianDate) {
            return [];
        }

        $dateStr = $gregorianDate->format('Y-m-d');

        return [
            'date' => [
                'jalali' => JalaliHelper::toPersianNumbers($jalaliDate),
                'gregorian' => $dateStr,
                'display' => JalaliHelper::formatForDisplay($jalaliDate, true),
            ],
            'appointments' => $this->getDailyAppointments($dateStr),
            'visits' => $this->getDailyVisits($dateStr),
            'financial' => $this->getDailyFinancial($dateStr),
            'patients' => $this->getDailyPatients($dateStr),
        ];
    }

    private function getDailyAppointments(string $date): array
    {
        $total = Appointment::whereDate('appointment_gregorian_date', $date)->count();

        $byStatus = Appointment::whereDate('appointment_gregorian_date', $date)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $byDoctor = Appointment::whereDate('appointment_gregorian_date', $date)
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.doctor_id')
            ->join('people', 'doctors.person_id', '=', 'people.person_id')
            ->select(
                'doctors.doctor_id',
                'people.first_name',
                'people.last_name',
                DB::raw('count(*) as count')
            )
            ->groupBy('doctors.doctor_id', 'people.first_name', 'people.last_name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->doctor_id,
                    'name' => $item->first_name . ' ' . $item->last_name,
                    'count' => $item->count,
                ];
            });

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_doctor' => $byDoctor,
        ];
    }

    private function getDailyVisits(string $date): array
    {
        $total = Visit::whereDate('visit_gregorian_date', $date)->count();

        $bySpecialty = Visit::whereDate('visit_gregorian_date', $date)
            ->join('specialties', 'visits.specialty_id', '=', 'specialties.specialty_id')
            ->select('specialties.name', DB::raw('count(*) as count'))
            ->groupBy('specialties.name')
            ->pluck('count', 'name')
            ->toArray();

        return [
            'total' => $total,
            'by_specialty' => $bySpecialty,
        ];
    }

    private function getDailyFinancial(string $date): array
    {
        $invoices = Invoice::whereDate('invoice_gregorian_date', $date)
            ->where('status', 'paid')
            ->get();

        $total = $invoices->sum('final_amount');
        $count = $invoices->count();

        $byPaymentMethod = $invoices->groupBy('payment_method')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('final_amount'),
                ];
            });

        return [
            'total' => $total,
            'count' => $count,
            'average' => $count > 0 ? $total / $count : 0,
            'by_payment_method' => $byPaymentMethod,
        ];
    }

    private function getDailyPatients(string $date): array
    {
        $newPatients = Patient::whereDate('registration_gregorian_date', $date)->count();

        $returningPatients = Visit::whereDate('visit_gregorian_date', $date)
            ->select('patient_id')
            ->distinct()
            ->count();

        return [
            'new' => $newPatients,
            'returning' => $returningPatients,
            'total' => $newPatients + $returningPatients,
        ];
    }

    /**
     * گزارش ماهانه
     */
    public function getMonthlyReport(int $year, int $month): array
    {
        $monthName = JalaliHelper::getMonthName($month);

        $report = [
            'year' => $year,
            'month' => $month,
            'month_name' => $monthName,
            'summary' => $this->getMonthlySummary($year, $month),
            'daily_breakdown' => $this->getDailyBreakdown($year, $month),
            'weekly_trend' => $this->getWeeklyTrend($year, $month),
            'comparison' => $this->getMonthComparison($year, $month),
        ];

        return $report;
    }

    private function getMonthlySummary(int $year, int $month): array
    {
        $startJalali = sprintf('%04d/%02d/01', $year, $month);
        $endJalali = sprintf('%04d/%02d/29', $year, $month);

        $startDate = JalaliHelper::toGregorian($startJalali);
        $endDate = JalaliHelper::toGregorian($endJalali);

        $startStr = $startDate->format('Y-m-d');
        $endStr = $endDate->format('Y-m-d');

        // نوبت‌ها
        $appointments = Appointment::whereBetween('appointment_gregorian_date', [$startStr, $endStr])
            ->select(
                DB::raw('count(*) as total'),
                DB::raw('sum(case when status = "completed" then 1 else 0 end) as completed'),
                DB::raw('sum(case when status = "cancelled" then 1 else 0 end) as cancelled'),
                DB::raw('sum(case when status = "no_show" then 1 else 0 end) as no_show')
            )
            ->first();

        // ویزیت‌ها
        $visits = Visit::whereBetween('visit_gregorian_date', [$startStr, $endStr])->count();

        // مالی
        $financial = Invoice::whereBetween('invoice_gregorian_date', [$startStr, $endStr])
            ->where('status', 'paid')
            ->select(
                DB::raw('count(*) as count'),
                DB::raw('sum(final_amount) as total'),
                DB::raw('avg(final_amount) as average')
            )
            ->first();

        // بیماران
        $patients = Patient::whereBetween('registration_gregorian_date', [$startStr, $endStr])->count();

        return [
            'appointments' => [
                'total' => $appointments->total ?? 0,
                'completed' => $appointments->completed ?? 0,
                'cancelled' => $appointments->cancelled ?? 0,
                'no_show' => $appointments->no_show ?? 0,
                'completion_rate' => $appointments->total > 0
                    ? round(($appointments->completed / $appointments->total) * 100, 2)
                    : 0,
            ],
            'visits' => $visits,
            'financial' => [
                'total' => $financial->total ?? 0,
                'count' => $financial->count ?? 0,
                'average' => $financial->average ?? 0,
            ],
            'patients' => $patients,
        ];
    }

    private function getDailyBreakdown(int $year, int $month): array
    {
        $breakdown = [];
        $daysInMonth = JalaliHelper::getDaysInJalaliMonth($year, $month);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $jalaliDate = sprintf('%04d/%02d/%02d', $year, $month, $day);
            $gregorianDate = JalaliHelper::toGregorian($jalaliDate);

            if ($gregorianDate) {
                $dateStr = $gregorianDate->format('Y-m-d');

                $appointments = Appointment::whereDate('appointment_gregorian_date', $dateStr)->count();
                $revenue = Invoice::whereDate('invoice_gregorian_date', $dateStr)
                    ->where('status', 'paid')
                    ->sum('final_amount');

                $breakdown[] = [
                    'day' => $day,
                    'jalali_date' => JalaliHelper::toPersianNumbers($jalaliDate),
                    'gregorian_date' => $dateStr,
                    'appointments' => $appointments,
                    'revenue' => $revenue,
                    'day_name' => JalaliHelper::getDayName($gregorianDate->dayOfWeek),
                    'is_weekend' => $gregorianDate->dayOfWeek == 5, // جمعه
                ];
            }
        }

        return $breakdown;
    }

    private function getWeeklyTrend(int $year, int $month): array
    {
        $trend = [];
        $startJalali = sprintf('%04d/%02d/01', $year, $month);
        $startDate = JalaliHelper::toGregorian($startJalali);

        $weekNumber = 1;
        $currentWeek = [
            'week' => $weekNumber,
            'start_date' => JalaliHelper::toJalali($startDate),
            'appointments' => 0,
            'revenue' => 0,
        ];

        $daysInMonth = JalaliHelper::getDaysInJalaliMonth($year, $month);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $jalaliDate = sprintf('%04d/%02d/%02d', $year, $month, $day);
            $gregorianDate = JalaliHelper::toGregorian($jalaliDate);

            if ($gregorianDate) {
                $dateStr = $gregorianDate->format('Y-m-d');

                $currentWeek['appointments'] += Appointment::whereDate('appointment_gregorian_date', $dateStr)->count();
                $currentWeek['revenue'] += Invoice::whereDate('invoice_gregorian_date', $dateStr)
                    ->where('status', 'paid')
                    ->sum('final_amount');

                // اگر جمعه است یا آخر ماه است، هفته را ذخیره کن
                if ($gregorianDate->dayOfWeek == 5 || $day == $daysInMonth) {
                    $currentWeek['end_date'] = JalaliHelper::toJalali($gregorianDate);
                    $trend[] = $currentWeek;

                    $weekNumber++;
                    $nextDay = $day + 1;
                    if ($nextDay <= $daysInMonth) {
                        $nextJalali = sprintf('%04d/%02d/%02d', $year, $month, $nextDay);
                        $nextDate = JalaliHelper::toGregorian($nextJalali);

                        $currentWeek = [
                            'week' => $weekNumber,
                            'start_date' => JalaliHelper::toJalali($nextDate),
                            'appointments' => 0,
                            'revenue' => 0,
                        ];
                    }
                }
            }
        }

        return $trend;
    }

    private function getMonthComparison(int $year, int $month): array
    {
        $currentMonth = $this->getMonthlySummary($year, $month);

        // ماه قبل
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }

        $previousMonth = $this->getMonthlySummary($prevYear, $prevMonth);

        // سال قبل
        $lastYearMonth = $this->getMonthlySummary($year - 1, $month);

        return [
            'previous_month' => $this->calculateComparison($currentMonth, $previousMonth),
            'same_month_last_year' => $this->calculateComparison($currentMonth, $lastYearMonth),
        ];
    }

    private function calculateComparison(array $current, array $previous): array
    {
        $comparison = [];

        foreach ($current as $key => $currentData) {
            if (is_array($currentData) && isset($previous[$key])) {
                $prevData = $previous[$key];

                if (is_array($currentData) && is_array($prevData)) {
                    foreach ($currentData as $subKey => $value) {
                        if (is_numeric($value) && isset($prevData[$subKey]) && is_numeric($prevData[$subKey])) {
                            $prevValue = $prevData[$subKey];
                            $change = $prevValue != 0
                                ? (($value - $prevValue) / $prevValue) * 100
                                : ($value > 0 ? 100 : 0);

                            $comparison[$key][$subKey] = [
                                'current' => $value,
                                'previous' => $prevValue,
                                'change' => round($change, 2),
                                'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable'),
                            ];
                        }
                    }
                }
            }
        }

        return $comparison;
    }

    /**
     * گزارش سالانه
     */
    public function getYearlyReport(int $year): array
    {
        $report = [
            'year' => $year,
            'summary' => $this->getYearlySummary($year),
            'monthly_breakdown' => $this->getMonthlyBreakdown($year),
            'quarterly_summary' => $this->getQuarterlySummary($year),
            'year_comparison' => $this->getYearComparison($year),
        ];

        return $report;
    }

    private function getYearlySummary(int $year): array
    {
        $startJalali = sprintf('%04d/01/01', $year);
        $endJalali = sprintf('%04d/12/29', $year);

        $startDate = JalaliHelper::toGregorian($startJalali);
        $endDate = JalaliHelper::toGregorian($endJalali);

        $startStr = $startDate->format('Y-m-d');
        $endStr = $endDate->format('Y-m-d');

        return [
            'appointments' => Appointment::whereBetween('appointment_gregorian_date', [$startStr, $endStr])->count(),
            'visits' => Visit::whereBetween('visit_gregorian_date', [$startStr, $endStr])->count(),
            'revenue' => Invoice::whereBetween('invoice_gregorian_date', [$startStr, $endStr])
                ->where('status', 'paid')
                ->sum('final_amount'),
            'patients' => Patient::whereBetween('registration_gregorian_date', [$startStr, $endStr])->count(),
            'invoices' => Invoice::whereBetween('invoice_gregorian_date', [$startStr, $endStr])
                ->where('status', 'paid')
                ->count(),
        ];
    }

    private function getMonthlyBreakdown(int $year): array
    {
        $breakdown = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthReport = $this->getMonthlySummary($year, $month);

            $breakdown[] = [
                'month' => $month,
                'month_name' => JalaliHelper::getMonthName($month),
                'appointments' => $monthReport['appointments']['total'] ?? 0,
                'revenue' => $monthReport['financial']['total'] ?? 0,
                'visits' => $monthReport['visits'] ?? 0,
                'patients' => $monthReport['patients'] ?? 0,
            ];
        }

        return $breakdown;
    }

    private function getQuarterlySummary(int $year): array
    {
        $quarters = [];

        $quarterMonths = [
            1 => [1, 2, 3],   // فصل اول: حمل، ثور، جوزا
            2 => [4, 5, 6],   // فصل دوم: سرطان، اسد، سنبله
            3 => [7, 8, 9],   // فصل سوم: میزان، عقرب، قوس
            4 => [10, 11, 12], // فصل چهارم: جدی، دلو، حوت
        ];

        foreach ($quarterMonths as $quarter => $months) {
            $quarterData = [
                'quarter' => $quarter,
                'months' => [],
                'total_appointments' => 0,
                'total_revenue' => 0,
                'total_visits' => 0,
                'total_patients' => 0,
            ];

            foreach ($months as $month) {
                $monthReport = $this->getMonthlySummary($year, $month);

                $quarterData['months'][$month] = [
                    'month_name' => JalaliHelper::getMonthName($month),
                    'appointments' => $monthReport['appointments']['total'] ?? 0,
                    'revenue' => $monthReport['financial']['total'] ?? 0,
                    'visits' => $monthReport['visits'] ?? 0,
                    'patients' => $monthReport['patients'] ?? 0,
                ];

                $quarterData['total_appointments'] += $monthReport['appointments']['total'] ?? 0;
                $quarterData['total_revenue'] += $monthReport['financial']['total'] ?? 0;
                $quarterData['total_visits'] += $monthReport['visits'] ?? 0;
                $quarterData['total_patients'] += $monthReport['patients'] ?? 0;
            }

            $quarters[$quarter] = $quarterData;
        }

        return $quarters;
    }

    private function getYearComparison(int $year): array
    {
        $currentYear = $this->getYearlySummary($year);
        $previousYear = $this->getYearlySummary($year - 1);

        $comparison = [];

        foreach ($currentYear as $key => $currentValue) {
            $previousValue = $previousYear[$key] ?? 0;

            $change = $previousValue != 0
                ? (($currentValue - $previousValue) / $previousValue) * 100
                : ($currentValue > 0 ? 100 : 0);

            $comparison[$key] = [
                'current' => $currentValue,
                'previous' => $previousValue,
                'change' => round($change, 2),
                'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable'),
            ];
        }

        return $comparison;
    }

    /**
     * گزارش مالی با فیلتر تاریخ شمسی
     */
    public function getFinancialReport(string $startJalaliDate, string $endJalaliDate): array
    {
        $startDate = JalaliHelper::toGregorian($startJalaliDate);
        $endDate = JalaliHelper::toGregorian($endJalaliDate);

        if (!$startDate || !$endDate) {
            return [];
        }

        $startStr = $startDate->format('Y-m-d');
        $endStr = $endDate->format('Y-m-d');

        $invoices = Invoice::with(['patient.person', 'visit'])
            ->whereBetween('invoice_gregorian_date', [$startStr, $endStr])
            ->where('status', 'paid')
            ->orderBy('invoice_gregorian_date')
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'date' => [
                        'jalali' => JalaliHelper::toJalali($invoice->invoice_date),
                        'gregorian' => $invoice->invoice_date,
                        'display' => JalaliHelper::formatForDisplay(
                            JalaliHelper::toJalali($invoice->invoice_date)
                        ),
                    ],
                    'patient' => $invoice->patient ? [
                        'id' => $invoice->patient->patient_id,
                        'name' => $invoice->patient->person->first_name . ' ' . $invoice->patient->person->last_name,
                    ] : null,
                    'amount' => $invoice->final_amount,
                    'payment_method' => $invoice->payment_method,
                    'status' => $invoice->status,
                ];
            });

        $revenueByDoctor = DB::table('revenue_distribution')
            ->join('doctors', 'revenue_distribution.doctor_id', '=', 'doctors.doctor_id')
            ->join('people', 'doctors.person_id', '=', 'people.person_id')
            ->whereBetween('revenue_distribution.service_date', [$startStr, $endStr])
            ->select(
                'doctors.doctor_id',
                'people.first_name',
                'people.last_name',
                DB::raw('SUM(revenue_distribution.doctor_share) as total_share'),
                DB::raw('COUNT(DISTINCT revenue_distribution.visit_service_id) as services_count')
            )
            ->groupBy('doctors.doctor_id', 'people.first_name', 'people.last_name')
            ->orderByDesc('total_share')
            ->get();

        $revenueBySpecialty = DB::table('revenue_distribution')
            ->join('visit_services', 'revenue_distribution.visit_service_id', '=', 'visit_services.visit_service_id')
            ->join('services', 'visit_services.service_id', '=', 'services.service_id')
            ->join('specialties', 'services.specialty_id', '=', 'specialties.specialty_id')
            ->whereBetween('revenue_distribution.service_date', [$startStr, $endStr])
            ->select(
                'specialties.specialty_id',
                'specialties.name',
                DB::raw('SUM(revenue_distribution.service_amount) as total_revenue'),
                DB::raw('COUNT(DISTINCT revenue_distribution.visit_service_id) as services_count')
            )
            ->groupBy('specialties.specialty_id', 'specialties.name')
            ->orderByDesc('total_revenue')
            ->get();

        $paymentMethods = DB::table('payments')
            ->whereBetween('payment_date', [$startStr, $endStr])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('payment_method')
            ->get();

        $totalDays = $startDate->diffInDays($endDate) + 1;

        return [
            'period' => [
                'start' => [
                    'jalali' => JalaliHelper::toPersianNumbers($startJalaliDate),
                    'gregorian' => $startStr,
                    'display' => JalaliHelper::formatForDisplay($startJalaliDate),
                ],
                'end' => [
                    'jalali' => JalaliHelper::toPersianNumbers($endJalaliDate),
                    'gregorian' => $endStr,
                    'display' => JalaliHelper::formatForDisplay($endJalaliDate),
                ],
                'days' => $totalDays,
            ],
            'summary' => [
                'total_revenue' => $invoices->sum('amount'),
                'total_invoices' => $invoices->count(),
                'average_invoice' => $invoices->count() > 0 ? $invoices->sum('amount') / $invoices->count() : 0,
                'daily_average' => $totalDays > 0 ? $invoices->sum('amount') / $totalDays : 0,
            ],
            'invoices' => $invoices,
            'revenue_by_doctor' => $revenueByDoctor,
            'revenue_by_specialty' => $revenueBySpecialty,
            'payment_methods' => $paymentMethods,
            'charts' => $this->prepareFinancialCharts($invoices, $startStr, $endStr),
        ];
    }

    private function prepareFinancialCharts(Collection $invoices, string $startDate, string $endDate): array
    {
        $dailyRevenue = [];
        $doctorRevenue = [];
        $specialtyRevenue = [];

        // محاسبه درآمد روزانه
        $currentDate = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);

        while ($currentDate <= $endCarbon) {
            $dateStr = $currentDate->format('Y-m-d');
            $jalaliDate = JalaliHelper::toJalali($currentDate);

            $dailyAmount = $invoices->filter(function ($invoice) use ($dateStr) {
                return $invoice['date']['gregorian'] == $dateStr;
            })->sum('amount');

            $dailyRevenue[] = [
                'date' => JalaliHelper::toPersianNumbers($jalaliDate),
                'amount' => $dailyAmount,
            ];

            $currentDate->addDay();
        }

        return [
            'daily_revenue' => $dailyRevenue,
            'doctor_revenue' => $doctorRevenue,
            'specialty_revenue' => $specialtyRevenue,
        ];
    }

    /**
     * گزارش عملکرد پزشکان
     */
    public function getDoctorsPerformanceReport(string $startJalaliDate, string $endJalaliDate): array
    {
        $startDate = JalaliHelper::toGregorian($startJalaliDate);
        $endDate = JalaliHelper::toGregorian($endJalaliDate);

        if (!$startDate || !$endDate) {
            return [];
        }

        $startStr = $startDate->format('Y-m-d');
        $endStr = $endDate->format('Y-m-d');

        $doctors = Doctor::with(['person', 'specialty'])
            ->where('is_active', true)
            ->get()
            ->map(function ($doctor) use ($startStr, $endStr) {
                $appointments = Appointment::where('doctor_id', $doctor->doctor_id)
                    ->whereBetween('appointment_gregorian_date', [$startStr, $endStr])
                    ->selectRaw('
                        COUNT(*) as total,
                        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                        SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                        SUM(CASE WHEN status = "no_show" THEN 1 ELSE 0 END) as no_show
                    ')
                    ->first();

                $revenue = DB::table('revenue_distribution')
                    ->where('doctor_id', $doctor->doctor_id)
                    ->whereBetween('service_date', [$startStr, $endStr])
                    ->selectRaw('
                        SUM(service_amount) as total_revenue,
                        SUM(doctor_share) as doctor_share,
                        COUNT(DISTINCT visit_service_id) as services_count
                    ')
                    ->first();

                $visits = Visit::where('doctor_id', $doctor->doctor_id)
                    ->whereBetween('visit_gregorian_date', [$startStr, $endStr])
                    ->count();

                return [
                    'id' => $doctor->doctor_id,
                    'name' => $doctor->person->first_name . ' ' . $doctor->person->last_name,
                    'specialty' => $doctor->specialty->name ?? null,
                    'appointments' => [
                        'total' => $appointments->total ?? 0,
                        'completed' => $appointments->completed ?? 0,
                        'cancelled' => $appointments->cancelled ?? 0,
                        'no_show' => $appointments->no_show ?? 0,
                        'completion_rate' => $appointments->total > 0
                            ? round(($appointments->completed / $appointments->total) * 100, 2)
                            : 0,
                    ],
                    'revenue' => [
                        'total' => $revenue->total_revenue ?? 0,
                        'doctor_share' => $revenue->doctor_share ?? 0,
                        'services_count' => $revenue->services_count ?? 0,
                        'average_per_service' => $revenue->services_count > 0
                            ? round(($revenue->total_revenue ?? 0) / $revenue->services_count, 2)
                            : 0,
                    ],
                    'visits' => $visits,
                ];
            });

        return [
            'period' => [
                'start' => JalaliHelper::toPersianNumbers($startJalaliDate),
                'end' => JalaliHelper::toPersianNumbers($endJalaliDate),
            ],
            'summary' => [
                'total_doctors' => $doctors->count(),
                'total_appointments' => $doctors->sum('appointments.total'),
                'total_revenue' => $doctors->sum('revenue.total'),
                'total_doctor_share' => $doctors->sum('revenue.doctor_share'),
            ],
            'doctors' => $doctors->sortByDesc('revenue.total')->values(),
            'top_performers' => $doctors->sortByDesc('revenue.total')->take(5)->values(),
        ];
    }
}
