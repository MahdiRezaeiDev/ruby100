<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('doctor_id');
            $table->foreignId('person_id')->constrained('people', 'person_id')->onDelete('cascade');

            // کد و شناسه
            $table->string('doctor_code', 20)->unique()->comment('کد پزشک (مثلا DR-001)');
            $table->string('medical_system_id', 50)->unique()->nullable()->comment('شماره نظام پزشکی');
            $table->string('license_number', 50)->nullable()->comment('شماره پروانه طبابت');

            // تخصص
            $table->foreignId('specialty_id')->constrained('specialties', 'specialty_id');
            $table->string('sub_specialty', 100)->nullable()->comment('تخصص فرعی');
            $table->string('qualification', 100)->nullable()->comment('مدرک تحصیلی');
            $table->string('university', 100)->nullable()->comment('دانشگاه فارغ‌التحصیلی');
            $table->integer('graduation_year')->nullable()->comment('سال فارغ‌التحصیلی');

            // تاریخ شروع همکاری شمسی
            $table->string('hire_jalali_date', 10)->comment('تاریخ شروع همکاری شمسی');
            $table->string('hire_jalali_year', 4)->comment('سال شروع همکاری شمسی');
            $table->string('hire_jalali_month', 2)->comment('ماه شروع همکاری شمسی');
            $table->string('hire_jalali_day', 2)->comment('روز شروع همکاری شمسی');
            $table->date('hire_gregorian_date')->comment('تاریخ شروع همکاری میلادی');

            // قرارداد
            $table->enum('contract_type', ['full_time', 'part_time', 'visiting', 'consultant'])->default('full_time')->comment('نوع قرارداد');
            $table->enum('payment_type', ['salary', 'percentage', 'mixed'])->default('percentage')->comment('نوع پرداخت');

            // نرخ‌ها
            $table->decimal('consultation_fee', 10, 2)->default(0)->comment('حق ویزیت پایه');
            $table->decimal('followup_fee', 10, 2)->default(0)->comment('حق ویزیت پیگیری');
            $table->decimal('emergency_fee', 10, 2)->default(0)->comment('حق ویزیت اورژانسی');

            // تخصص‌ها
            $table->boolean('is_surgeon')->default(false)->comment('جراح است؟');
            $table->boolean('is_consultant')->default(true)->comment('مشاور است؟');
            $table->boolean('can_do_surgery')->default(false)->comment('می‌تواند عمل جراحی انجام دهد؟');
            $table->boolean('can_prescribe')->default(true)->comment('می‌تواند نسخه بنویسد؟');

            // برنامه زمانی
            $table->json('working_days')->nullable()->comment('روزهای کاری (شنبه تا جمعه)');
            $table->time('work_start_time')->nullable()->comment('زمان شروع کار');
            $table->time('work_end_time')->nullable()->comment('زمان پایان کار');
            $table->integer('slot_duration')->default(15)->comment('مدت هر نوبت (دقیقه)');
            $table->integer('max_patients_per_day')->default(30)->comment('حداکثر بیمار در روز');

            // اتاق
            $table->string('room_number', 10)->nullable()->comment('شماره اتاق');
            $table->string('room_location', 100)->nullable()->comment('محل اتاق');

            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->enum('availability', ['available', 'busy', 'on_leave', 'off_duty'])->default('available')->comment('دردسترس بودن');

            // تجربه و رتبه
            $table->integer('experience_years')->default(0)->comment('سال‌های تجربه');
            $table->decimal('rating', 3, 2)->default(0)->comment('امتیاز');
            $table->integer('total_reviews')->default(0)->comment('تعداد نظرات');

            // مالی
            $table->decimal('monthly_salary', 12, 2)->default(0)->comment('حقوق ماهیانه (اگر حقوق‌بگیر است)');
            $table->decimal('percentage_rate', 5, 2)->default(50)->comment('درصد سهم (اگر درصدی است)');
            $table->decimal('minimum_guarantee', 12, 2)->default(0)->comment('حداقل درآمد تضمینی');

            // اطلاعات تماس حرفه‌ای
            $table->string('professional_email', 100)->nullable()->comment('ایمیل حرفه‌ای');
            $table->string('professional_phone', 15)->nullable()->comment('تلفن حرفه‌ای');
            $table->string('whatsapp_number', 15)->nullable()->comment('شماره واتساپ');

            // اطلاعات بانکی
            $table->string('bank_name', 100)->nullable()->comment('نام بانک');
            $table->string('bank_account_number', 50)->nullable()->comment('شماره حساب');
            $table->string('bank_account_name', 100)->nullable()->comment('نام صاحب حساب');

            // مدارک
            $table->string('cv_url', 500)->nullable()->comment('آدرس رزومه');
            $table->string('license_url', 500)->nullable()->comment('آدرس پروانه طبابت');
            $table->string('degree_url', 500)->nullable()->comment('آدرس مدرک تحصیلی');

            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('special_skills')->nullable()->comment('مهارت‌های خاص');

            $table->timestamps();

            // ایندکس‌ها
            $table->index('doctor_code');
            $table->index('medical_system_id');
            $table->index('specialty_id');
            $table->index('hire_jalali_date');
            $table->index('hire_gregorian_date');
            $table->index('is_active');
            $table->index('contract_type');
            $table->index('payment_type');
            $table->index('room_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
