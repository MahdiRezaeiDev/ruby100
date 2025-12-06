<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('patient_id');
            $table->foreignId('person_id')->constrained('people', 'person_id')->onDelete('cascade');

            // کد بیمار
            $table->string('patient_code', 20)->unique()->comment('کد بیمار (مثلا P-1403-001)');

            // اطلاعات پزشکی
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-', 'Unknown'])->default('Unknown')->comment('گروه خونی');
            $table->text('allergies')->nullable()->comment('آلرژی‌ها');
            $table->text('chronic_diseases')->nullable()->comment('بیماری‌های مزمن');
            $table->text('current_medications')->nullable()->comment('داروهای جاری');
            $table->text('medical_history')->nullable()->comment('سابقه پزشکی');
            $table->text('surgical_history')->nullable()->comment('سابقه جراحی');
            $table->text('family_history')->nullable()->comment('سابقه خانوادگی');

            // تاریخ ثبت‌نام شمسی
            $table->string('registration_jalali_date', 10)->comment('تاریخ ثبت‌نام شمسی');
            $table->string('registration_jalali_year', 4)->comment('سال ثبت‌نام شمسی');
            $table->string('registration_jalali_month', 2)->comment('ماه ثبت‌نام شمسی');
            $table->string('registration_jalali_day', 2)->comment('روز ثبت‌نام شمسی');
            $table->date('registration_gregorian_date')->comment('تاریخ ثبت‌نام میلادی');

            // مراجعه‌کننده توسط
            $table->string('referred_by', 100)->nullable()->comment('ارجاع شده توسط');
            $table->enum('referral_type', ['self', 'doctor', 'hospital', 'other'])->default('self')->comment('نوع ارجاع');

            // وضعیت
            $table->enum('status', ['active', 'inactive', 'deceased', 'transferred'])->default('active')->comment('وضعیت بیمار');
            $table->enum('patient_type', ['regular', 'vip', 'staff', 'insurance'])->default('regular')->comment('نوع بیمار');

            $table->string('insurance_company', 100)->nullable()->comment('شرکت بیمه');
            $table->string('insurance_number', 50)->nullable()->comment('شماره بیمه');
            $table->decimal('insurance_coverage', 5, 2)->default(0)->comment('پوشش بیمه (درصد)');

            // مالی
            $table->decimal('credit_limit', 12, 2)->default(0)->comment('سقف اعتبار');
            $table->decimal('current_balance', 12, 2)->default(0)->comment('مانده حساب');

            // اطلاعات اضافی
            $table->text('special_instructions')->nullable()->comment('دستورات خاص');
            $table->text('notes')->nullable()->comment('یادداشت‌ها');

            // پرونده پزشکی
            $table->string('medical_file_number', 50)->nullable()->comment('شماره پرونده پزشکی');
            $table->string('medical_file_location', 100)->nullable()->comment('محل پرونده پزشکی');

            $table->timestamps();

            // ایندکس‌ها
            $table->index('patient_code');
            $table->index('registration_jalali_date');
            $table->index('registration_gregorian_date');
            $table->index(['registration_jalali_year', 'registration_jalali_month']);
            $table->index('status');
            $table->index('patient_type');
            $table->index('insurance_company');
            $table->index('medical_file_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
