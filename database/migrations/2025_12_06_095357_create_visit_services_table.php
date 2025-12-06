<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_services', function (Blueprint $table) {
            $table->id('visit_service_id');

            // ارتباط با ویزیت و خدمت
            $table->foreignId('visit_id')->constrained('visits', 'visit_id')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services', 'service_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');

            // تاریخ خدمت شمسی
            $table->string('service_jalali_date', 10)->comment('تاریخ ارائه خدمت شمسی');
            $table->date('service_gregorian_date')->comment('تاریخ ارائه خدمت میلادی');

            // کمیت و واحد
            $table->integer('quantity')->default(1)->comment('تعداد');
            $table->string('unit', 20)->default('session')->comment('واحد');
            $table->decimal('unit_price', 12, 2)->comment('قیمت واحد');

            // مالی
            $table->decimal('total_price', 12, 2)->comment('قیمت کل');
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('درصد تخفیف');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('مبلغ تخفیف');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('مبلغ مالیات');
            $table->decimal('final_price', 12, 2)->comment('قیمت نهایی');

            // درصدهای تقسیم درآمد
            $table->decimal('clinic_percentage', 5, 2)->default(50.00)->comment('درصد کلینیک');
            $table->decimal('doctor_percentage', 5, 2)->default(50.00)->comment('درصد پزشک');
            $table->decimal('technical_percentage', 5, 2)->default(0)->comment('درصد فنی');
            $table->decimal('assistant_percentage', 5, 2)->default(0)->comment('درصد دستیار');

            // محاسبه سهم‌ها
            $table->decimal('clinic_share', 12, 2)->comment('سهم کلینیک');
            $table->decimal('doctor_share', 12, 2)->comment('سهم پزشک');
            $table->decimal('technical_share', 12, 2)->default(0)->comment('سهم فنی');
            $table->decimal('assistant_share', 12, 2)->default(0)->comment('سهم دستیار');

            // وضعیت
            $table->enum('status', [
                'scheduled',      // برنامه‌ریزی شده
                'in_progress',    // در حال انجام
                'completed',      // تکمیل شده
                'cancelled',      // لغو شده
                'billed',         // فاکتور شده
                'paid'           // پرداخت شده
            ])->default('scheduled')->comment('وضعیت خدمت');

            // نوع پرداخت
            $table->enum('payment_type', [
                'cash',
                'card',
                'insurance',
                'mixed',
                'credit',
                'free'
            ])->default('cash')->comment('نوع پرداخت');

            // بیمه
            $table->boolean('insurance_covered')->default(false)->comment('تحت پوشش بیمه است؟');
            $table->decimal('insurance_amount', 12, 2)->default(0)->comment('مبلغ بیمه');
            $table->decimal('patient_amount', 12, 2)->default(0)->comment('مبلغ بیمار');

            // زمان‌ها
            $table->time('start_time')->nullable()->comment('زمان شروع');
            $table->time('end_time')->nullable()->comment('زمان پایان');
            $table->integer('actual_duration_minutes')->nullable()->comment('مدت زمان واقعی (دقیقه)');

            // اتاق و تجهیزات
            $table->string('room_number', 10)->nullable()->comment('شماره اتاق');
            $table->string('equipment_used', 500)->nullable()->comment('تجهیزات استفاده شده');

            // پرسنل
            $table->foreignId('assistant_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('دستیار');
            $table->foreignId('nurse_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('پرستار');
            $table->foreignId('technician_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('تکنسین');

            // جزئیات اجرا
            $table->text('procedure_notes')->nullable()->comment('یادداشت‌های اقدام');
            $table->text('complications')->nullable()->comment('عوارض');
            $table->text('findings')->nullable()->comment('یافته‌ها');
            $table->text('results')->nullable()->comment('نتایج');
            $table->text('recommendations')->nullable()->comment('توصیه‌ها');

            // مواد مصرفی
            $table->json('materials_used')->nullable()->comment('مواد مصرفی');
            $table->decimal('material_cost', 12, 2)->default(0)->comment('هزینه مواد');

            // تجویزها
            $table->boolean('requires_prescription')->default(false)->comment('نیاز به نسخه دارد؟');
            $table->text('prescription_details')->nullable()->comment('جزئیات نسخه');

            // پیگیری
            $table->boolean('requires_followup')->default(false)->comment('نیاز به پیگیری دارد؟');
            $table->string('followup_jalali_date', 10)->nullable()->comment('تاریخ پیگیری شمسی');
            $table->date('followup_gregorian_date')->nullable()->comment('تاریخ پیگیری میلادی');

            // تأیید
            $table->boolean('is_approved')->default(true)->comment('تأیید شده است؟');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');
            $table->timestamp('approved_at')->nullable()->comment('تأیید شده در');

            // ارجاع آزمایش
            $table->boolean('lab_test_required')->default(false)->comment('نیاز به آزمایش دارد؟');
            $table->text('lab_test_instructions')->nullable()->comment('دستورالعمل آزمایش');

            // ارجاع تصویربرداری
            $table->boolean('imaging_required')->default(false)->comment('نیاز به تصویربرداری دارد؟');
            $table->text('imaging_instructions')->nullable()->comment('دستورالعمل تصویربرداری');

            // یادداشت‌ها
            $table->text('doctor_notes')->nullable()->comment('یادداشت‌های پزشک');
            $table->text('nurse_notes')->nullable()->comment('یادداشت‌های پرستار');
            $table->text('patient_feedback')->nullable()->comment('بازخورد بیمار');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // تاریخ‌های وضعیت
            $table->timestamp('completed_at')->nullable()->comment('تکمیل شده در');
            $table->timestamp('billed_at')->nullable()->comment('فاکتور شده در');
            $table->timestamp('paid_at')->nullable()->comment('پرداخت شده در');

            // ایندکس‌ها
            $table->index('visit_id');
            $table->index('service_id');
            $table->index('doctor_id');
            $table->index('service_jalali_date');
            $table->index('service_gregorian_date');
            $table->index('status');
            $table->index('payment_type');
            $table->index('insurance_covered');
            $table->index('room_number');
            $table->index('assistant_id');
            $table->index('nurse_id');
            $table->index('technician_id');
            $table->index('requires_followup');
            $table->index('followup_jalali_date');
            $table->index('is_approved');
            $table->index('lab_test_required');
            $table->index('imaging_required');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['visit_id', 'service_id']);
            $table->index(['doctor_id', 'service_gregorian_date']);

            // بررسی جمع درصدها
            $table->check('clinic_percentage + doctor_percentage + technical_percentage + assistant_percentage = 100');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_services');
    }
};
