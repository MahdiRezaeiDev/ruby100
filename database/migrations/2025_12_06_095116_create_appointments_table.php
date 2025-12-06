<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');

            // بیمار و پزشک
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('specialty_id')->constrained('specialties', 'specialty_id');

            // تاریخ نوبت شمسی
            $table->string('appointment_jalali_date', 10)->comment('تاریخ نوبت شمسی');
            $table->string('appointment_jalali_year', 4)->comment('سال نوبت شمسی');
            $table->string('appointment_jalali_month', 2)->comment('ماه نوبت شمسی');
            $table->string('appointment_jalali_day', 2)->comment('روز نوبت شمسی');
            $table->string('appointment_jalali_day_name', 20)->nullable()->comment('نام روز هفته');
            $table->date('appointment_gregorian_date')->comment('تاریخ نوبت میلادی');

            // زمان نوبت
            $table->time('appointment_time')->comment('زمان نوبت');
            $table->time('end_time')->nullable()->comment('زمان پایان');
            $table->integer('duration_minutes')->default(15)->comment('مدت زمان (دقیقه)');

            // وضعیت
            $table->enum('status', [
                'scheduled',      // برنامه‌ریزی شده
                'confirmed',      // تأیید شده
                'checked_in',     // حاضر شده
                'in_progress',    // در حال ویزیت
                'completed',      // تکمیل شده
                'cancelled',      // لغو شده
                'no_show',        // عدم حضور
                'rescheduled'     // مجدد برنامه‌ریزی شده
            ])->default('scheduled')->comment('وضعیت');

            // نوع ویزیت
            $table->enum('appointment_type', [
                'consultation',   // ویزیت معمولی
                'followup',       // پیگیری
                'emergency',      // اورژانسی
                'procedure',      // اقدام درمانی
                'surgery',        // جراحی
                'checkup',        // معاینه دوره‌ای
                'test',           // آزمایش
                'vaccination'     // واکسیناسیون
            ])->default('consultation')->comment('نوع ویزیت');

            // وضعیت پرداخت
            $table->enum('payment_status', [
                'pending',        // در انتظار پرداخت
                'partial',        // پرداخت جزئی
                'paid',           // پرداخت کامل
                'free',           // رایگان
                'insurance',      // بیمه
                'discounted'      // تخفیف دار
            ])->default('pending')->comment('وضعیت پرداخت');

            // مالی
            $table->decimal('appointment_fee', 10, 2)->default(0)->comment('حق ویزیت');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('مقدار تخفیف');
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('درصد تخفیف');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('مبلغ پرداخت شده');
            $table->decimal('remaining_amount', 10, 2)->comment('مبلغ باقی‌مانده');

            // علت مراجعه
            $table->text('reason')->nullable()->comment('دلیل مراجعه');
            $table->text('symptoms')->nullable()->comment('علائم');
            $table->text('notes')->nullable()->comment('یادداشت‌ها');

            // مراجعه‌کننده
            $table->string('companion_name', 100)->nullable()->comment('نام همراه');
            $table->string('companion_relation', 50)->nullable()->comment('نسبت همراه');
            $table->string('companion_phone', 15)->nullable()->comment('تلفن همراه');

            // روش رزرو
            $table->enum('booking_method', [
                'walk_in',        // مراجعه حضوری
                'phone',          // تلفنی
                'online',         // آنلاین
                'mobile_app',     // اپ موبایل
                'reception',      // پذیرش
                'doctor_referral' // ارجاع پزشک
            ])->default('walk_in')->comment('روش رزرو');

            // اولویت
            $table->enum('priority', [
                'normal',         // عادی
                'urgent',         // فوری
                'emergency'       // اورژانسی
            ])->default('normal')->comment('اولویت');

            // اتاق
            $table->string('room_number', 10)->nullable()->comment('شماره اتاق');
            $table->string('room_type', 50)->nullable()->comment('نوع اتاق');

            // ارجاع
            $table->foreignId('referred_by_doctor_id')->nullable()->constrained('doctors', 'doctor_id')->nullOnDelete()->comment('پزشک ارجاع‌دهنده');
            $table->string('referral_notes', 500)->nullable()->comment('یادداشت‌های ارجاع');

            // تکراری
            $table->boolean('is_recurring')->default(false)->comment('تکراری است؟');
            $table->enum('recurrence_pattern', [
                'daily',
                'weekly',
                'monthly',
                'yearly',
                'custom'
            ])->nullable()->comment('الگوی تکرار');
            $table->integer('recurrence_interval')->nullable()->comment('فاصله تکرار');
            $table->date('recurrence_end_date')->nullable()->comment('تاریخ پایان تکرار');

            // اعلان‌ها
            $table->boolean('sms_reminder_sent')->default(false)->comment('یادآوری SMS ارسال شده؟');
            $table->boolean('email_reminder_sent')->default(false)->comment('یادآوری ایمیل ارسال شده؟');
            $table->timestamp('reminder_sent_at')->nullable()->comment('یادآوری ارسال شده در');

            // تأیید
            $table->boolean('is_confirmed')->default(false)->comment('تأیید شده است؟');
            $table->timestamp('confirmed_at')->nullable()->comment('تأیید شده در');
            $table->foreignId('confirmed_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // تاریخ‌های وضعیت
            $table->timestamp('checked_in_at')->nullable()->comment('حاضر شده در');
            $table->timestamp('started_at')->nullable()->comment('شروع شده در');
            $table->timestamp('completed_at')->nullable()->comment('تکمیل شده در');
            $table->timestamp('cancelled_at')->nullable()->comment('لغو شده در');

            // ایندکس‌ها
            $table->index('appointment_jalali_date');
            $table->index('appointment_gregorian_date');
            $table->index(['appointment_jalali_year', 'appointment_jalali_month']);
            $table->index('status');
            $table->index('appointment_type');
            $table->index('payment_status');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('specialty_id');
            $table->index('booking_method');
            $table->index('priority');
            $table->index('room_number');
            $table->index('is_recurring');
            $table->index('is_confirmed');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['doctor_id', 'appointment_gregorian_date', 'appointment_time']);
            $table->index(['patient_id', 'appointment_gregorian_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
