<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            // شماره پرداخت
            $table->string('payment_number', 50)->unique()->comment('شماره پرداخت');
            $table->string('payment_type', 20)->default('invoice')->comment('نوع پرداخت');

            // فاکتور مربوطه
            $table->foreignId('invoice_id')->constrained('invoices', 'invoice_id');

            // تاریخ پرداخت شمسی
            $table->string('payment_jalali_date', 10)->comment('تاریخ پرداخت شمسی');
            $table->string('payment_jalali_year', 4)->comment('سال پرداخت شمسی');
            $table->string('payment_jalali_month', 2)->comment('ماه پرداخت شمسی');
            $table->string('payment_jalali_day', 2)->comment('روز پرداخت شمسی');
            $table->date('payment_gregorian_date')->comment('تاریخ پرداخت میلادی');

            // زمان پرداخت
            $table->time('payment_time')->comment('زمان پرداخت');

            // مبلغ
            $table->decimal('amount', 14, 2)->comment('مبلغ پرداخت');
            $table->decimal('received_amount', 14, 2)->comment('مبلغ دریافتی');
            $table->decimal('change_amount', 14, 2)->default(0)->comment('مبلغ برگشتی');

            // روش پرداخت
            $table->enum('payment_method', [
                'cash',            // نقدی
                'card_debit',      // کارت بدهی
                'card_credit',     // کارت اعتباری
                'bank_transfer',   // حواله بانکی
                'cheque',          // چک
                'online',          // آنلاین
                'mobile_wallet',   // کیف پول موبایل
                'insurance',       // بیمه
                'credit_note',     // یادداشت اعتباری
                'voucher',         // وoucher
                'other'            // سایر
            ])->default('cash')->comment('روش پرداخت');

            // جزئیات کارت
            $table->string('card_type', 20)->nullable()->comment('نوع کارت');
            $table->string('card_last_four', 4)->nullable()->comment('چهار رقم آخر کارت');
            $table->string('card_transaction_id', 100)->nullable()->comment('شناسه تراکنش کارت');

            // جزئیات چک
            $table->string('cheque_number', 50)->nullable()->comment('شماره چک');
            $table->string('cheque_bank', 100)->nullable()->comment('بانک چک');
            $table->string('cheque_branch', 100)->nullable()->comment('شعبه چک');
            $table->date('cheque_date')->nullable()->comment('تاریخ چک');

            // جزئیات حواله
            $table->string('transfer_reference', 100)->nullable()->comment('شماره مرجع حواله');
            $table->string('transfer_bank', 100)->nullable()->comment('بانک حواله');
            $table->string('transfer_account', 100)->nullable()->comment('شماره حساب حواله');

            // جزئیات آنلاین
            $table->string('online_gateway', 100)->nullable()->comment('درگاه آنلاین');
            $table->string('online_transaction_id', 100)->nullable()->comment('شناسه تراکنش آنلاین');
            $table->string('online_receipt_url', 500)->nullable()->comment('آدرس رسید آنلاین');

            // بیمه
            $table->boolean('insurance_payment')->default(false)->comment('پرداخت بیمه است؟');
            $table->string('insurance_claim_number', 50)->nullable()->comment('شماره پرونده بیمه');
            $table->decimal('insurance_approved_amount', 14, 2)->default(0)->comment('مبلغ تأیید شده بیمه');
            $table->decimal('insurance_deductible', 14, 2)->default(0)->comment('کسورات بیمه');

            // ارز
            $table->string('currency', 3)->default('AFN')->comment('واحد پول');
            $table->decimal('exchange_rate', 10, 4)->default(1)->comment('نرخ ارز');
            $table->decimal('base_currency_amount', 14, 2)->comment('مبلغ به ارز پایه');

            // وضعیت
            $table->enum('status', [
                'pending',         // در انتظار
                'processing',      // در حال پردازش
                'completed',       // تکمیل شده
                'failed',          // ناموفق
                'cancelled',       // لغو شده
                'refunded',        // بازپرداخت شده
                'reversed',        // معکوس شده
                'disputed'         // مورد اختلاف
            ])->default('pending')->comment('وضعیت پرداخت');

            // تأیید
            $table->boolean('is_verified')->default(false)->comment('تأیید شده است؟');
            $table->foreignId('verified_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تأییدکننده');
            $table->timestamp('verified_at')->nullable()->comment('تأیید شده در');

            // تصفیه
            $table->boolean('is_settled')->default(false)->comment('تصفیه شده است؟');
            $table->timestamp('settled_at')->nullable()->comment('تصفیه شده در');
            $table->foreignId('settled_by')->nullable()->constrained('users', 'user_id')->nullOnDelete()->comment('تصفیه‌کننده');

            // بازپرداخت
            $table->boolean('is_refundable')->default(true)->comment('قابل بازپرداخت است؟');
            $table->decimal('refunded_amount', 14, 2)->default(0)->comment('مبلغ بازپرداخت شده');
            $table->text('refund_reason')->nullable()->comment('دلیل بازپرداخت');

            // پرداخت‌کننده
            $table->string('payer_name', 100)->nullable()->comment('نام پرداخت‌کننده');
            $table->string('payer_type', 20)->default('patient')->comment('نوع پرداخت‌کننده');
            $table->foreignId('payer_id')->nullable()->comment('شناسه پرداخت‌کننده');

            // دریافت‌کننده
            $table->foreignId('received_by')->constrained('users', 'user_id')->comment('دریافت‌کننده');
            $table->string('receiver_name', 100)->nullable()->comment('نام دریافت‌کننده');

            // دستگاه پرداخت
            $table->string('terminal_id', 50)->nullable()->comment('شناسه ترمینال');
            $table->string('terminal_location', 100)->nullable()->comment('موقعیت ترمینال');

            // هزینه‌ها
            $table->decimal('processing_fee', 14, 2)->default(0)->comment('هزینه پردازش');
            $table->decimal('transaction_fee', 14, 2)->default(0)->comment('هزینه تراکنش');
            $table->decimal('other_fees', 14, 2)->default(0)->comment('سایر هزینه‌ها');

            // امنیت
            $table->string('authorization_code', 100)->nullable()->comment('کد مجوز');
            $table->string('security_code', 100)->nullable()->comment('کد امنیتی');
            $table->json('security_data')->nullable()->comment('داده‌های امنیتی');

            // رسید
            $table->boolean('receipt_printed')->default(false)->comment('رسید چاپ شده؟');
            $table->integer('receipt_print_count')->default(0)->comment('تعداد چاپ رسید');
            $table->timestamp('receipt_printed_at')->nullable()->comment('رسید چاپ شده در');
            $table->string('receipt_number', 50)->nullable()->comment('شماره رسید');

            // یادداشت‌ها
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->text('internal_notes')->nullable()->comment('یادداشت‌های داخلی');
            $table->text('customer_notes')->nullable()->comment('یادداشت‌های مشتری');

            // پیوست‌ها
            $table->json('attachments')->nullable()->comment('پیوست‌ها');
            $table->string('receipt_url', 500)->nullable()->comment('آدرس رسید');

            // ایجادکننده
            $table->foreignId('created_by')->constrained('users', 'user_id')->comment('ایجادکننده');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // تاریخ‌های وضعیت
            $table->timestamp('processed_at')->nullable()->comment('پردازش شده در');
            $table->timestamp('completed_at')->nullable()->comment('تکمیل شده در');
            $table->timestamp('failed_at')->nullable()->comment('ناموفق شده در');
            $table->timestamp('cancelled_at')->nullable()->comment('لغو شده در');
            $table->timestamp('refunded_at')->nullable()->comment('بازپرداخت شده در');

            // ایندکس‌ها
            $table->index('payment_number');
            $table->index('invoice_id');
            $table->index('payment_jalali_date');
            $table->index('payment_gregorian_date');
            $table->index(['payment_jalali_year', 'payment_jalali_month']);
            $table->index('payment_method');
            $table->index('status');
            $table->index('is_verified');
            $table->index('is_settled');
            $table->index('received_by');
            $table->index('payer_name');
            $table->index('payer_type');
            $table->index('payer_id');
            $table->index('terminal_id');
            $table->index('receipt_printed');
            $table->index('receipt_number');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['status', 'payment_gregorian_date']);
            $table->index(['payment_method', 'payment_gregorian_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
