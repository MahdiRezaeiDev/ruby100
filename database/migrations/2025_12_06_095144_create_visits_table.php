<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id('visit_id');

            // ارتباط با نوبت
            $table->foreignId('appointment_id')->nullable()->constrained('appointments', 'appointment_id')->nullOnDelete();

            // بیمار و پزشک
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('specialty_id')->constrained('specialties', 'specialty_id');

            // تاریخ ویزیت شمسی
            $table->string('visit_jalali_date', 10)->comment('تاریخ ویزیت شمسی');
            $table->string('visit_jalali_year', 4)->comment('سال ویزیت شمسی');
            $table->string('visit_jalali_month', 2)->comment('ماه ویزیت شمسی');
            $table->string('visit_jalali_day', 2)->comment('روز ویزیت شمسی');
            $table->string('visit_jalali_day_name', 20)->nullable()->comment('نام روز هفته');
            $table->date('visit_gregorian_date')->comment('تاریخ ویزیت میلادی');

            // زمان ویزیت
            $table->time('visit_time')->comment('زمان شروع ویزیت');
            $table->time('end_time')->nullable()->comment('زمان پایان ویزیت');
            $table->integer('duration_minutes')->nullable()->comment('مدت زمان ویزیت (دقیقه)');

            // نوع ویزیت
            $table->enum('visit_type', [
                'consultation',   // ویزیت معمولی
                'followup',       // پیگیری
                'emergency',      // اورژانسی
                'procedure',      // اقدام درمانی
                'surgery',        // جراحی
                'post_op',        // پس از عمل
                'checkup',        // معاینه دوره‌ای
                'vaccination'     // واکسیناسیون
            ])->default('consultation')->comment('نوع ویزیت');

            // وضعیت ویزیت
            $table->enum('status', [
                'scheduled',      // برنامه‌ریزی شده
                'in_progress',    // در حال انجام
                'completed',      // تکمیل شده
                'cancelled',      // لغو شده
                'no_show'         // عدم حضور
            ])->default('scheduled')->comment('وضعیت ویزیت');

            // علائم و تشخیص
            $table->text('symptoms')->nullable()->comment('علائم');
            $table->text('complaints')->nullable()->comment('شکایات');
            $table->text('diagnosis')->nullable()->comment('تشخیص');
            $table->text('differential_diagnosis')->nullable()->comment('تشخیص تفریقی');
            $table->text('clinical_findings')->nullable()->comment('یافته‌های بالینی');

            // تاریخچه
            $table->text('history_of_present_illness')->nullable()->comment('تاریخچه بیماری فعلی');
            $table->text('past_medical_history')->nullable()->comment('سابقه پزشکی گذشته');
            $table->text('family_history')->nullable()->comment('سابقه خانوادگی');
            $table->text('social_history')->nullable()->comment('سابقه اجتماعی');
            $table->text('medication_history')->nullable()->comment('سابقه دارویی');

            // معاینه فیزیکی
            $table->text('physical_examination')->nullable()->comment('معاینه فیزیکی');
            $table->text('vital_signs_notes')->nullable()->comment('یادداشت‌های علائم حیاتی');

            // علائم حیاتی
            $table->decimal('height', 5, 2)->nullable()->comment('قد (سانتی‌متر)');
            $table->decimal('weight', 5, 2)->nullable()->comment('وزن (کیلوگرم)');
            $table->decimal('bmi', 5, 2)->nullable()->comment('شاخص توده بدنی');
            $table->decimal('temperature', 4, 1)->nullable()->comment('دمای بدن (°C)');
            $table->string('blood_pressure', 20)->nullable()->comment('فشار خون (mmHg)');
            $table->integer('heart_rate')->nullable()->comment('ضربان قلب (در دقیقه)');
            $table->integer('respiratory_rate')->nullable()->comment('تعداد تنفس (در دقیقه)');
            $table->integer('oxygen_saturation')->nullable()->comment('اشباع اکسیژن (%)');
            $table->decimal('pain_level', 3, 1)->nullable()->comment('سطح درد (0-10)');

            // طرح درمان
            $table->text('treatment_plan')->nullable()->comment('طرح درمان');
            $table->text('recommendations')->nullable()->comment('توصیه‌ها');
            $table->text('instructions')->nullable()->comment('دستورات');
            $table->text('followup_instructions')->nullable()->comment('دستورات پیگیری');

            // پیگیری
            $table->boolean('followup_needed')->default(false)->comment('نیاز به پیگیری دارد؟');
            $table->string('followup_jalali_date', 10)->nullable()->comment('تاریخ پیگیری شمسی');
            $table->date('followup_gregorian_date')->nullable()->comment('تاریخ پیگیری میلادی');
            $table->text('followup_reason')->nullable()->comment('دلیل پیگیری');

            // اتاق و تجهیزات
            $table->string('room_number', 10)->nullable()->comment('شماره اتاق');
            $table->string('room_type', 50)->nullable()->comment('نوع اتاق');
            $table->json('equipment_used')->nullable()->comment('تجهیزات استفاده شده');

            // همراهان
            $table->string('companion_name', 100)->nullable()->comment('نام همراه');
            $table->string('companion_relation', 50)->nullable()->comment('نسبت همراه');
            $table->text('companion_notes')->nullable()->comment('یادداشت‌های همراه');

            // ارجاع‌ها
            $table->boolean('requires_lab_test')->default(false)->comment('نیاز به آزمایش دارد؟');
            $table->boolean('requires_imaging')->default(false)->comment('نیاز به تصویربرداری دارد؟');
            $table->boolean('requires_specialist')->default(false)->comment('نیاز به متخصص دارد؟');
            $table->text('referrals')->nullable()->comment('ارجاع‌ها');

            // بستری
            $table->boolean('admission_required')->default(false)->comment('نیاز به بستری دارد؟');
            $table->enum('admission_type', [
                'emergency',
                'elective',
                'day_care',
                'observation'
            ])->nullable()->comment('نوع بستری');

            // جراحی
            $table->boolean('surgery_required')->default(false)->comment('نیاز به جراحی دارد؟');
            $table->text('surgery_notes')->nullable()->comment('یادداشت‌های جراحی');

            // خطرات و هشدارها
            $table->text('allergy_warnings')->nullable()->comment('هشدارهای آلرژی');
            $table->text('contraindications')->nullable()->comment('ممنوعیت‌ها');
            $table->text('risk_factors')->nullable()->comment('عوامل خطر');

            // رضایت
            $table->boolean('consent_obtained')->default(false)->comment('رضایت گرفته شده؟');
            $table->text('consent_details')->nullable()->comment('جزئیات رضایت');

            // اعلان‌ها
            $table->boolean('patient_notified')->default(false)->comment('بیمار مطلع شده؟');
            $table->text('notification_details')->nullable()->comment('جزئیات اعلان');

            // یادداشت‌های پزشک
            $table->text('doctor_notes')->nullable()->comment('یادداشت‌های پزشک');
            $table->text('nurse_notes')->nullable()->comment('یادداشت‌های پرستار');
            $table->text('reception_notes')->nullable()->comment('یادداشت‌های پذیرش');

            // وضعیت ترخیص
            $table->enum('discharge_status', [
                'admitted',
                'discharged',
                'transferred',
                'absconded',
                'deceased'
            ])->nullable()->comment('وضعیت ترخیص');

            // تأیید
            $table->boolean('is_verified')->default(false)->comment('تأیید شده است؟');
            $table->timestamp('verified_at')->nullable()->comment('تأیید شده در');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // تاریخ‌های وضعیت
            $table->timestamp('checked_in_at')->nullable()->comment('حاضر شده در');
            $table->timestamp('started_at')->nullable()->comment('شروع شده در');
            $table->timestamp('completed_at')->nullable()->comment('تکمیل شده در');
            $table->timestamp('discharged_at')->nullable()->comment('ترخیص شده در');

            // ایندکس‌ها
            $table->index('visit_jalali_date');
            $table->index('visit_gregorian_date');
            $table->index(['visit_jalali_year', 'visit_jalali_month']);
            $table->index('status');
            $table->index('visit_type');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('specialty_id');
            $table->index('appointment_id');
            $table->index('room_number');
            $table->index('followup_needed');
            $table->index('followup_jalali_date');
            $table->index('admission_required');
            $table->index('surgery_required');
            $table->index('is_verified');
            $table->index('discharge_status');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['patient_id', 'visit_gregorian_date']);
            $table->index(['doctor_id', 'visit_gregorian_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
