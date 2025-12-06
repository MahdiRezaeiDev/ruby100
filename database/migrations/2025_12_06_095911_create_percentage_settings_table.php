<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('percentage_settings', function (Blueprint $table) {
            $table->id('setting_id');

            // پزشک و تخصص
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('specialty_id')->constrained('specialties', 'specialty_id');

            // درصدها
            $table->decimal('clinic_percentage', 5, 2)->comment('درصد کلینیک');
            $table->decimal('doctor_percentage', 5, 2)->comment('درصد پزشک');
            $table->decimal('technical_percentage', 5, 2)->default(0)->comment('درصد فنی');
            $table->decimal('assistant_percentage', 5, 2)->default(0)->comment('درصد دستیار');

            // تاریخ اعمال شمسی
            $table->string('effective_jalali_date', 10)->comment('تاریخ اعمال شمسی');
            $table->date('effective_gregorian_date')->comment('تاریخ اعمال میلادی');

            // تاریخ انقضا
            $table->string('expiry_jalali_date', 10)->nullable()->comment('تاریخ انقضا شمسی');
            $table->date('expiry_gregorian_date')->nullable()->comment('تاریخ انقضا میلادی');

            // اعتبار
            $table->enum('validity_period', [
                'permanent',    // دائم
                'temporary',    // موقت
                'contractual'   // قراردادی
            ])->default('permanent')->comment('دوره اعتبار');

            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_default')->default(false)->comment('پیش‌فرض است؟');
            $table->boolean('is_approved')->default(true)->comment('تأیید شده است؟');

            // تأیید
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');
            $table->timestamp('approved_at')->nullable()->comment('تأیید شده در');

            // محدودیت‌ها
            $table->json('restrictions')->nullable()->comment('محدودیت‌ها');
            $table->json('conditions')->nullable()->comment('شرایط');

            // خدمات خاص
            $table->json('service_exceptions')->nullable()->comment('استثناهای خدمات');
            $table->json('service_overrides')->nullable()->comment('تغییرات خدمات');

            // نرخ‌های ویژه
            $table->decimal('emergency_percentage', 5, 2)->nullable()->comment('درصد اورژانسی');
            $table->decimal('surgery_percentage', 5, 2)->nullable()->comment('درصد جراحی');
            $table->decimal('procedure_percentage', 5, 2)->nullable()->comment('درصد اقدام درمانی');

            // حداقل و حداکثر
            $table->decimal('minimum_doctor_share', 12, 2)->nullable()->comment('حداقل سهم پزشک');
            $table->decimal('maximum_doctor_share', 12, 2)->nullable()->comment('حداکثر سهم پزشک');
            $table->decimal('minimum_clinic_share', 12, 2)->nullable()->comment('حداقل سهم کلینیک');
            $table->decimal('maximum_clinic_share', 12, 2)->nullable()->comment('حداکثر سهم کلینیک');

            // ضریب‌ها
            $table->decimal('weekend_multiplier', 5, 2)->default(1)->comment('ضریب آخر هفته');
            $table->decimal('holiday_multiplier', 5, 2)->default(1)->comment('ضریب تعطیل');
            $table->decimal('night_multiplier', 5, 2)->default(1)->comment('ضریب شب');

            // مالیات و کسر
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->decimal('commission_percentage', 5, 2)->default(0)->comment('درصد کارمزد');
            $table->decimal('deduction_percentage', 5, 2)->default(0)->comment('درصد کسر');

            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('agreement_terms')->nullable()->comment('شرایط توافق');

            // نسخه
            $table->integer('version')->default(1)->comment('نسخه');
            $table->foreignId('previous_version_id')->nullable()->constrained('percentage_settings', 'setting_id')->nullOnDelete()->comment('نسخه قبلی');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // کلیدهای منحصربه‌فرد
            $table->unique(['doctor_id', 'specialty_id', 'effective_jalali_date']);

            // ایندکس‌ها
            $table->index('doctor_id');
            $table->index('specialty_id');
            $table->index('effective_jalali_date');
            $table->index('effective_gregorian_date');
            $table->index('expiry_jalali_date');
            $table->index('expiry_gregorian_date');
            $table->index('is_active');
            $table->index('is_default');
            $table->index('is_approved');
            $table->index('validity_period');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['doctor_id', 'is_active']);
            $table->index(['specialty_id', 'is_active']);

            // بررسی جمع درصدها
            $table->check('clinic_percentage + doctor_percentage + technical_percentage + assistant_percentage = 100');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('percentage_settings');
    }
};
