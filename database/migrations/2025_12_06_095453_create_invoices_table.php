<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');

            // شماره فاکتور
            $table->string('invoice_number', 50)->unique()->comment('شماره فاکتور');
            $table->string('invoice_type', 20)->default('service')->comment('نوع فاکتور');

            // بیمار و ویزیت
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('visit_id')->nullable()->constrained('visits', 'visit_id')->nullOnDelete();

            // تاریخ فاکتور شمسی
            $table->string('invoice_jalali_date', 10)->comment('تاریخ فاکتور شمسی');
            $table->string('invoice_jalali_year', 4)->comment('سال فاکتور شمسی');
            $table->string('invoice_jalali_month', 2)->comment('ماه فاکتور شمسی');
            $table->string('invoice_jalali_day', 2)->comment('روز فاکتور شمسی');
            $table->date('invoice_gregorian_date')->comment('تاریخ فاکتور میلادی');

            // تاریخ سررسید شمسی
            $table->string('due_jalali_date', 10)->nullable()->comment('تاریخ سررسید شمسی');
            $table->date('due_gregorian_date')->nullable()->comment('تاریخ سررسید میلادی');

            // مبالغ
            $table->decimal('subtotal_amount', 14, 2)->default(0)->comment('جمع جزء');
            $table->decimal('discount_amount', 14, 2)->default(0)->comment('تخفیف');
            $table->decimal('tax_amount', 14, 2)->default(0)->comment('مالیات');
            $table->decimal('other_charges', 14, 2)->default(0)->comment('سایر هزینه‌ها');
            $table->decimal('total_amount', 14, 2)->comment('جمع کل');

            // بیمه
            $table->decimal('insurance_amount', 14, 2)->default(0)->comment('مبلغ بیمه');
            $table->decimal('patient_amount', 14, 2)->comment('مبلغ بیمار');

            // پرداخت‌ها
            $table->decimal('paid_amount', 14, 2)->default(0)->comment('پرداخت شده');
            $table->decimal('remaining_amount', 14, 2)->comment('مانده');
            $table->decimal('refund_amount', 14, 2)->default(0)->comment('مبلغ بازپرداخت');

            // وضعیت
            $table->enum('status', [
                'draft',           // پیش‌نویس
                'issued',          // صادر شده
                'sent',            // ارسال شده
                'viewed',          // مشاهده شده
                'partially_paid',  // پرداخت جزئی
                'paid',            // پرداخت کامل
                'overdue',         // معوق
                'cancelled',       // لغو شده
                'refunded',        // بازپرداخت شده
                'disputed'         // مورد اختلاف
            ])->default('draft')->comment('وضعیت فاکتور');

            // روش پرداخت
            $table->enum('payment_method', [
                'cash',            // نقدی
                'card',            // کارت
                'bank_transfer',   // حواله بانکی
                'cheque',          // چک
                'online',          // آنلاین
                'wallet',          // کیف پول
                'credit',          // اعتباری
                'insurance',       // بیمه
                'mixed'            // ترکیبی
            ])->nullable()->comment('روش پرداخت');

            // جزئیات پرداخت
            $table->string('payment_reference', 100)->nullable()->comment('شماره مرجع پرداخت');
            $table->timestamp('payment_date')->nullable()->comment('تاریخ پرداخت');
            $table->foreignId('received_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('دریافت‌کننده');

            // تخفیف
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('درصد تخفیف');
            $table->string('discount_reason', 200)->nullable()->comment('دلیل تخفیف');
            $table->foreignId('discount_approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده تخفیف');

            // مالیات
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->string('tax_type', 50)->nullable()->comment('نوع مالیات');

            // شرایط
            $table->integer('payment_terms_days')->default(0)->comment('شرایط پرداخت (روز)');
            $table->decimal('late_fee_percentage', 5, 2)->default(0)->comment('درصد جریمه دیرکرد');
            $table->decimal('late_fee_amount', 14, 2)->default(0)->comment('مبلغ جریمه دیرکرد');

            // ارز
            $table->string('currency', 3)->default('AFN')->comment('واحد پول');
            $table->decimal('exchange_rate', 10, 4)->default(1)->comment('نرخ ارز');

            // چاپ
            $table->integer('print_count')->default(0)->comment('تعداد چاپ');
            $table->timestamp('last_printed_at')->nullable()->comment('آخرین چاپ در');

            // ایمیل
            $table->boolean('email_sent')->default(false)->comment('ایمیل ارسال شده؟');
            $table->timestamp('email_sent_at')->nullable()->comment('ایمیل ارسال شده در');

            // SMS
            $table->boolean('sms_sent')->default(false)->comment('SMS ارسال شده؟');
            $table->timestamp('sms_sent_at')->nullable()->comment('SMS ارسال شده در');

            // نسخه‌بندی
            $table->integer('version')->default(1)->comment('نسخه');
            $table->foreignId('previous_version_id')->nullable()->constrained('invoices', 'invoice_id')->nullOnDelete()->comment('نسخه قبلی');
            $table->text('version_notes')->nullable()->comment('یادداشت‌های نسخه');

            // تأیید
            $table->boolean('is_approved')->default(true)->comment('تأیید شده است؟');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');
            $table->timestamp('approved_at')->nullable()->comment('تأیید شده در');

            // اعتبارسنجی
            $table->boolean('is_validated')->default(false)->comment('اعتبارسنجی شده است؟');
            $table->foreignId('validated_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('اعتبارسنج‌کننده');
            $table->timestamp('validated_at')->nullable()->comment('اعتبارسنجی شده در');

            // بایگانی
            $table->boolean('is_archived')->default(false)->comment('بایگانی شده است؟');
            $table->timestamp('archived_at')->nullable()->comment('بایگانی شده در');
            $table->foreignId('archived_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('بایگانی‌کننده');

            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('terms_conditions')->nullable()->comment('شرایط و ضوابط');
            $table->text('footer_notes')->nullable()->comment('یادداشت‌های پاورقی');

            // پیوست‌ها
            $table->json('attachments')->nullable()->comment('پیوست‌ها');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // تاریخ‌های وضعیت
            $table->timestamp('issued_at')->nullable()->comment('صادر شده در');
            $table->timestamp('sent_at')->nullable()->comment('ارسال شده در');
            $table->timestamp('viewed_at')->nullable()->comment('مشاهده شده در');
            $table->timestamp('paid_at')->nullable()->comment('پرداخت شده در');
            $table->timestamp('cancelled_at')->nullable()->comment('لغو شده در');
            $table->timestamp('refunded_at')->nullable()->comment('بازپرداخت شده در');
            $table->timestamp('overdue_at')->nullable()->comment('معوق شده در');

            // ایندکس‌ها
            $table->index('invoice_number');
            $table->index('patient_id');
            $table->index('visit_id');
            $table->index('invoice_jalali_date');
            $table->index('invoice_gregorian_date');
            $table->index(['invoice_jalali_year', 'invoice_jalali_month']);
            $table->index('due_jalali_date');
            $table->index('due_gregorian_date');
            $table->index('status');
            $table->index('payment_method');
            $table->index('payment_date');
            $table->index('received_by');
            $table->index('total_amount');
            $table->index('paid_amount');
            $table->index('remaining_amount');
            $table->index('is_approved');
            $table->index('is_validated');
            $table->index('is_archived');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['status', 'due_gregorian_date']);
            $table->index(['patient_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
