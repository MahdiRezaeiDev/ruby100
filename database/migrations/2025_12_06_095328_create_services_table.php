<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');

            // کد و نام
            $table->string('service_code', 50)->unique()->comment('کد خدمت');
            $table->string('name', 200)->comment('نام خدمت');
            $table->string('name_dari', 200)->nullable()->comment('نام خدمت (دری)');
            $table->string('name_pashto', 200)->nullable()->comment('نام خدمت (پشتو)');

            // توضیحات
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('description_dari')->nullable()->comment('توضیحات (دری)');
            $table->text('description_pashto')->nullable()->comment('توضیحات (پشتو)');

            // دسته‌بندی
            $table->foreignId('specialty_id')->constrained('specialties', 'specialty_id');
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories', 'category_id')->nullOnDelete();

            // نوع خدمت
            $table->enum('service_type', [
                'consultation',     // ویزیت
                'procedure',        // اقدام درمانی
                'surgery',          // جراحی
                'test',             // آزمایش
                'imaging',          // تصویربرداری
                'therapy',          // درمان
                'vaccination',      // واکسیناسیون
                'other'             // سایر
            ])->default('procedure')->comment('نوع خدمت');

            $table->enum('category', [
                'medical',
                'surgical',
                'diagnostic',
                'therapeutic',
                'preventive',
                'rehabilitative',
                'cosmetic',
                'dental'
            ])->default('medical')->comment('دسته‌بندی');

            // قیمت‌ها
            $table->decimal('base_price', 12, 2)->comment('قیمت پایه');
            $table->decimal('cost_price', 12, 2)->nullable()->comment('قیمت تمام شده');
            $table->decimal('insurance_price', 12, 2)->nullable()->comment('قیمت بیمه');
            $table->decimal('government_price', 12, 2)->nullable()->comment('قیمت دولتی');
            $table->decimal('private_price', 12, 2)->nullable()->comment('قیمت خصوصی');

            // درصدها
            $table->decimal('clinic_percentage', 5, 2)->default(50.00)->comment('درصد کلینیک');
            $table->decimal('doctor_percentage', 5, 2)->default(50.00)->comment('درصد پزشک');
            $table->decimal('technical_percentage', 5, 2)->default(0)->comment('درصد فنی');
            $table->decimal('assistant_percentage', 5, 2)->default(0)->comment('درصد دستیار');

            // زمان و منابع
            $table->integer('duration_minutes')->default(30)->comment('مدت زمان تخمینی (دقیقه)');
            $table->integer('preparation_time')->default(15)->comment('زمان آماده‌سازی (دقیقه)');
            $table->integer('recovery_time')->nullable()->comment('زمان ریکاوری (دقیقه)');
            $table->json('required_equipment')->nullable()->comment('تجهیزات مورد نیاز');
            $table->json('required_materials')->nullable()->comment('مواد مورد نیاز');
            $table->json('required_personnel')->nullable()->comment('پرسنل مورد نیاز');

            // اتاق و مکان
            $table->string('default_room_type', 50)->nullable()->comment('نوع اتاق پیش‌فرض');
            $table->string('room_requirements', 500)->nullable()->comment('نیازمندی‌های اتاق');

            // تنظیمات
            $table->boolean('requires_doctor')->default(true)->comment('نیاز به پزشک دارد؟');
            $table->boolean('requires_assistant')->default(false)->comment('نیاز به دستیار دارد؟');
            $table->boolean('requires_nurse')->default(false)->comment('نیاز به پرستار دارد؟');
            $table->boolean('requires_anesthesia')->default(false)->comment('نیاز به بی‌هوشی دارد؟');
            $table->boolean('requires_consent')->default(false)->comment('نیاز به رضایت دارد؟');
            $table->boolean('requires_fasting')->default(false)->comment('نیاز به ناشتایی دارد؟');
            $table->boolean('requires_preparation')->default(false)->comment('نیاز به آماده‌سازی دارد؟');

            // بیمه و تخفیف
            $table->boolean('insurance_coverage')->default(true)->comment('تحت پوشش بیمه است؟');
            $table->decimal('insurance_coverage_percentage', 5, 2)->default(70)->comment('درصد پوشش بیمه');
            $table->boolean('discount_allowed')->default(true)->comment('تخفیف مجاز است؟');
            $table->decimal('max_discount_percentage', 5, 2)->default(20)->comment('حداکثر درصد تخفیف');

            // خطرات و عوارض
            $table->text('risks')->nullable()->comment('خطرات');
            $table->text('complications')->nullable()->comment('عوارض');
            $table->text('contraindications')->nullable()->comment('ممنوعیت‌ها');
            $table->text('precautions')->nullable()->comment('احتیاط‌ها');

            // دستورالعمل‌ها
            $table->text('pre_procedure_instructions')->nullable()->comment('دستورالعمل‌های قبل از عمل');
            $table->text('post_procedure_instructions')->nullable()->comment('دستورالعمل‌های بعد از عمل');
            $table->text('home_care_instructions')->nullable()->comment('دستورالعمل‌های مراقبت در منزل');

            // کدگذاری
            $table->string('cpt_code', 20)->nullable()->comment('کد CPT');
            $table->string('icd10_code', 20)->nullable()->comment('کد ICD-10');
            $table->string('hcpcs_code', 20)->nullable()->comment('کد HCPCS');

            // مالیاتی
            $table->decimal('tax_rate', 5, 2)->default(0)->comment('نرخ مالیات (%)');
            $table->boolean('tax_inclusive')->default(true)->comment('مالیات شامل قیمت است؟');

            // موجودی
            $table->boolean('requires_inventory')->default(false)->comment('نیاز به موجودی دارد؟');
            $table->json('inventory_items')->nullable()->comment('موارد موجودی مورد نیاز');

            // آمار
            $table->integer('total_performed')->default(0)->comment('تعداد انجام شده');
            $table->integer('total_cancelled')->default(0)->comment('تعداد لغو شده');
            $table->decimal('success_rate', 5, 2)->default(0)->comment('نرخ موفقیت (%)');
            $table->decimal('complication_rate', 5, 2)->default(0)->comment('نرخ عوارض (%)');

            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_available')->default(true)->comment('در دسترس است؟');
            $table->enum('availability_status', [
                'available',
                'limited',
                'by_appointment',
                'not_available'
            ])->default('available')->comment('وضعیت دسترسی');

            // اولویت
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');

            // آیکون و رنگ
            $table->string('icon', 50)->nullable()->comment('آیکون');
            $table->string('color', 20)->nullable()->comment('رنگ');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ایندکس‌ها
            $table->index('service_code');
            $table->index('name');
            $table->index('specialty_id');
            $table->index('service_category_id');
            $table->index('service_type');
            $table->index('category');
            $table->index('base_price');
            $table->index('insurance_coverage');
            $table->index('is_active');
            $table->index('is_available');
            $table->index('availability_status');
            $table->index('sort_order');
            $table->index('created_at');

            // بررسی جمع درصدها
            $table->check('clinic_percentage + doctor_percentage + technical_percentage + assistant_percentage = 100');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
