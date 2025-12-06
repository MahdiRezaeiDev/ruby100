<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_distribution', function (Blueprint $table) {
            $table->id('distribution_id');

            // ارتباط با خدمات ویزیت
            $table->foreignId('visit_service_id')->constrained('visit_services', 'visit_service_id')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices', 'invoice_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');

            // تاریخ توزیع شمسی
            $table->string('distribution_jalali_date', 10)->comment('تاریخ توزیع شمسی');
            $table->string('distribution_jalali_year', 4)->comment('سال توزیع شمسی');
            $table->string('distribution_jalali_month', 2)->comment('ماه توزیع شمسی');
            $table->string('distribution_jalali_day', 2)->comment('روز توزیع شمسی');
            $table->date('distribution_gregorian_date')->comment('تاریخ توزیع میلادی');

            // تاریخ خدمت
            $table->string('service_jalali_date', 10)->comment('تاریخ خدمت شمسی');
            $table->date('service_gregorian_date')->comment('تاریخ خدمت میلادی');

            // مبالغ خدمت
            $table->decimal('service_amount', 14, 2)->comment('مبلغ خدمت');
            $table->decimal('discount_amount', 14, 2)->default(0)->comment('تخفیف خدمت');
            $table->decimal('net_service_amount', 14, 2)->comment('مبلغ خالص خدمت');

            // درصدهای توزیع
            $table->decimal('clinic_percentage', 5, 2)->comment('درصد کلینیک');
            $table->decimal('doctor_percentage', 5, 2)->comment('درصد پزشک');
            $table->decimal('technical_percentage', 5, 2)->default(0)->comment('درصد فنی');
            $table->decimal('assistant_percentage', 5, 2)->default(0)->comment('درصد دستیار');

            // سهم‌ها
            $table->decimal('clinic_share', 14, 2)->comment('سهم کلینیک');
            $table->decimal('doctor_share', 14, 2)->comment('سهم پزشک');
            $table->decimal('technical_share', 14, 2)->default(0)->comment('سهم فنی');
            $table->decimal('assistant_share', 14, 2)->default(0)->comment('سهم دستیار');

            // پرسنل دیگر
            $table->foreignId('assistant_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('دستیار');
            $table->foreignId('technician_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('تکنسین');
            $table->foreignId('nurse_id')->nullable()->constrained('staff', 'staff_id')->nullOnDelete()->comment('پرستار');

            // وضعیت تسویه
            $table->enum('settlement_status', [
                'pending',         // در انتظار
                'calculated',      // محاسبه شده
                'approved',        // تأیید شده
                'processed',       // پردازش شده
                'paid',            // پرداخت شده
                'cancelled',       // لغو شده
                'adjusted'         // تعدیل شده
            ])->default('pending')->comment('وضعیت تسویه');

            // تاریخ‌های تسویه
            $table->string('settlement_jalali_date', 10)->nullable()->comment('تاریخ تسویه شمسی');
            $table->date('settlement_gregorian_date')->nullable()->comment('تاریخ تسویه میلادی');
            $table->timestamp('settlement_processed_at')->nullable()->comment('پردازش تسویه در');
            $table->timestamp('settlement_paid_at')->nullable()->comment('پرداخت تسویه در');

            // پرداخت به پزشک
            $table->boolean('doctor_paid')->default(false)->comment('پزشک پرداخت شده؟');
            $table->decimal('doctor_paid_amount', 14, 2)->default(0)->comment('مبلغ پرداختی به پزشک');
            $table->timestamp('doctor_paid_at')->nullable()->comment('پزشک پرداخت شده در');
            $table->foreignId('doctor_paid_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('پرداخت‌کننده به پزشک');

            // پرداخت به پرسنل
            $table->boolean('assistant_paid')->default(false)->comment('دستیار پرداخت شده؟');
            $table->decimal('assistant_paid_amount', 14, 2)->default(0)->comment('مبلغ پرداختی به دستیار');
            $table->boolean('technician_paid')->default(false)->comment('تکنسین پرداخت شده؟');
            $table->decimal('technician_paid_amount', 14, 2)->default(0)->comment('مبلغ پرداختی به تکنسین');
            $table->boolean('nurse_paid')->default(false)->comment('پرستار پرداخت شده؟');
            $table->decimal('nurse_paid_amount', 14, 2)->default(0)->comment('مبلغ پرداختی به پرستار');

            // تعدیل‌ها
            $table->decimal('adjustment_amount', 14, 2)->default(0)->comment('مبلغ تعدیل');
            $table->text('adjustment_reason')->nullable()->comment('دلیل تعدیل');
            $table->foreignId('adjusted_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تعدیل‌کننده');
            $table->timestamp('adjusted_at')->nullable()->comment('تعدیل شده در');

            // مالیات
            $table->decimal('tax_amount', 14, 2)->default(0)->comment('مبلغ مالیات');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->string('tax_type', 50)->nullable()->comment('نوع مالیات');

            // کارمزد
            $table->decimal('commission_amount', 14, 2)->default(0)->comment('مبلغ کارمزد');
            $table->decimal('commission_percentage', 5, 2)->default(0)->comment('درصد کارمزد');

            // بیمه
            $table->boolean('insurance_covered')->default(false)->comment('تحت پوشش بیمه است؟');
            $table->decimal('insurance_share', 14, 2)->default(0)->comment('سهم بیمه');

            // ارز
            $table->string('currency', 3)->default('AFN')->comment('واحد پول');
            $table->decimal('exchange_rate', 10, 4)->default(1)->comment('نرخ ارز');

            // تأیید
            $table->boolean('is_approved')->default(false)->comment('تأیید شده است؟');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');
            $table->timestamp('approved_at')->nullable()->comment('تأیید شده در');

            // اعتبارسنجی
            $table->boolean('is_validated')->default(false)->comment('اعتبارسنجی شده است؟');
            $table->foreignId('validated_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('اعتبارسنج‌کننده');
            $table->timestamp('validated_at')->nullable()->comment('اعتبارسنجی شده در');

            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('calculation_notes')->nullable()->comment('یادداشت‌های محاسبه');
            $table->text('payment_notes')->nullable()->comment('یادداشت‌های پرداخت');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ایندکس‌ها
            $table->index('visit_service_id');
            $table->index('invoice_id');
            $table->index('doctor_id');
            $table->index('distribution_jalali_date');
            $table->index('distribution_gregorian_date');
            $table->index(['distribution_jalali_year', 'distribution_jalali_month']);
            $table->index('service_jalali_date');
            $table->index('service_gregorian_date');
            $table->index('settlement_status');
            $table->index('settlement_jalali_date');
            $table->index('settlement_gregorian_date');
            $table->index('doctor_paid');
            $table->index('assistant_id');
            $table->index('technician_id');
            $table->index('nurse_id');
            $table->index('is_approved');
            $table->index('is_validated');
            $table->index('insurance_covered');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['doctor_id', 'settlement_status']);
            $table->index(['distribution_gregorian_date', 'settlement_status']);

            // بررسی جمع درصدها
            $table->check('clinic_percentage + doctor_percentage + technical_percentage + assistant_percentage = 100');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_distribution');
    }
};
