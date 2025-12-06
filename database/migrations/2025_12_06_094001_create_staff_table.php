<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->foreignId('person_id')->constrained('people', 'person_id')->onDelete('cascade');
            
            // کد کارمندی
            $table->string('employee_code', 20)->unique()->comment('کد کارمندی');
            
            // موقعیت شغلی
            $table->enum('position', [
                'receptionist', 
                'accountant', 
                'nurse', 
                'assistant',
                'pharmacist',
                'lab_technician',
                'radiologist',
                'cleaner',
                'security',
                'driver',
                'manager',
                'supervisor',
                'secretary',
                'other'
            ])->comment('موقعیت شغلی');
            
            $table->string('job_title', 100)->comment('عنوان شغلی');
            $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id')->nullOnDelete();
            
            // تاریخ استخدام شمسی
            $table->string('hire_jalali_date', 10)->comment('تاریخ استخدام شمسی');
            $table->string('hire_jalali_year', 4)->comment('سال استخدام شمسی');
            $table->string('hire_jalali_month', 2)->comment('ماه استخدام شمسی');
            $table->string('hire_jalali_day', 2)->comment('روز استخدام شمسی');
            $table->date('hire_gregorian_date')->comment('تاریخ استخدام میلادی');
            
            // تاریخ پایان قرارداد
            $table->string('contract_end_jalali_date', 10)->nullable()->comment('تاریخ پایان قرارداد شمسی');
            $table->date('contract_end_gregorian_date')->nullable()->comment('تاریخ پایان قرارداد میلادی');
            
            // نوع قرارداد
            $table->enum('contract_type', ['permanent', 'temporary', 'contractual', 'probation'])->default('probation')->comment('نوع قرارداد');
            $table->enum('employment_type', ['full_time', 'part_time', 'shift', 'consultant'])->default('full_time')->comment('نوع اشتغال');
            
            // شیفت کاری
            $table->enum('shift_type', ['morning', 'evening', 'night', 'rotating', 'flexible'])->default('morning')->comment('نوع شیفت');
            $table->time('shift_start_time')->nullable()->comment('زمان شروع شیفت');
            $table->time('shift_end_time')->nullable()->comment('زمان پایان شیفت');
            
            // حقوق و مزایا
            $table->decimal('basic_salary', 10, 2)->default(0)->comment('حقوق پایه');
            $table->decimal('housing_allowance', 10, 2)->default(0)->comment('کمک هزینه مسکن');
            $table->decimal('transportation_allowance', 10, 2)->default(0)->comment('کمک هزینه حمل و نقل');
            $table->decimal('food_allowance', 10, 2)->default(0)->comment('کمک هزینه غذا');
            $table->decimal('other_allowances', 10, 2)->default(0)->comment('سایر کمک هزینه‌ها');
            $table->decimal('night_shift_allowance', 10, 2)->default(0)->comment('حق شیفت شب (روزانه)');
            $table->decimal('overtime_rate', 10, 2)->default(0)->comment('نرخ اضافه کار (ساعتی)');
            
            // اطلاعات پرداخت
            $table->integer('salary_payment_day')->default(1)->comment('روز پرداخت حقوق (1-31)');
            $table->string('bank_name', 100)->nullable()->comment('نام بانک');
            $table->string('bank_account_number', 50)->nullable()->comment('شماره حساب بانکی');
            $table->string('bank_account_name', 100)->nullable()->comment('نام صاحب حساب');
            
            // مرخصی
            $table->integer('annual_leave_days')->default(30)->comment('مرخصی سالانه (روز)');
            $table->integer('sick_leave_days')->default(15)->comment('مرخصی استعلاجی (روز)');
            $table->integer('casual_leave_days')->default(10)->comment('مرخصی عادی (روز)');
            $table->integer('taken_annual_leave')->default(0)->comment('مرخصی سالانه گرفته شده');
            $table->integer('taken_sick_leave')->default(0)->comment('مرخصی استعلاجی گرفته شده');
            $table->integer('taken_casual_leave')->default(0)->comment('مرخصی عادی گرفته شده');
            
            // گواهی‌ها و مدارک
            $table->string('qualification', 100)->nullable()->comment('مدرک تحصیلی');
            $table->string('certifications', 500)->nullable()->comment('گواهی‌ها');
            $table->string('license_number', 50)->nullable()->comment('شماره پروانه (برای پرستاران، داروسازان)');
            
            // اطلاعات تماس کاری
            $table->string('work_email', 100)->nullable()->comment('ایمیل کاری');
            $table->string('work_phone', 15)->nullable()->comment('تلفن کاری');
            $table->string('extension', 10)->nullable()->comment('داخلی');
            
            // محل کار
            $table->string('work_location', 100)->nullable()->comment('محل کار');
            $table->string('desk_number', 20)->nullable()->comment('شماره میز');
            
            // گزارش به
            $table->foreignId('reports_to')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('گزارش به');
            
            // وضعیت
            $table->enum('status', ['active', 'on_leave', 'suspended', 'terminated', 'retired'])->default('active')->comment('وضعیت استخدام');
            $table->enum('leave_status', ['working', 'annual_leave', 'sick_leave', 'maternity_leave', 'unpaid_leave'])->default('working')->comment('وضعیت مرخصی');
            
            // تاریخ‌های وضعیت
            $table->string('status_change_jalali_date', 10)->nullable()->comment('تاریخ تغییر وضعیت شمسی');
            $table->date('status_change_gregorian_date')->nullable()->comment('تاریخ تغییر وضعیت میلادی');
            
            // ارزیابی
            $table->decimal('performance_rating', 3, 2)->default(0)->comment('امتیاز عملکرد');
            $table->date('last_evaluation_date')->nullable()->comment('تاریخ آخرین ارزیابی');
            
            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('emergency_procedures')->nullable()->comment('روش‌های اضطراری');
            
            // مدارک
            $table->string('contract_url', 500)->nullable()->comment('آدرس قرارداد');
            $table->string('id_card_url', 500)->nullable()->comment('آدرس کارت شناسایی');
            $table->string('degree_url', 500)->nullable()->comment('آدرس مدرک تحصیلی');
            $table->string('certificate_url', 500)->nullable()->comment('آدرس گواهی‌ها');
            
            $table->timestamps();
            
            // ایندکس‌ها
            $table->index('employee_code');
            $table->index('position');
            $table->index('department_id');
            $table->index('hire_jalali_date');
            $table->index('hire_gregorian_date');
            $table->index('contract_type');
            $table->index('employment_type');
            $table->index('shift_type');
            $table->index('status');
            $table->index('reports_to');
            $table->index(['hire_jalali_year', 'hire_jalali_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};