<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id('specialty_id');
            
            // کد و نام
            $table->string('specialty_code', 10)->unique()->comment('کد تخصص');
            $table->string('name', 100)->comment('نام تخصص');
            $table->string('name_dari', 100)->nullable()->comment('نام تخصص (دری)');
            $table->string('name_pashto', 100)->nullable()->comment('نام تخصص (پشتو)');
            
            // طبقه‌بندی
            $table->foreignId('parent_specialty_id')->nullable()->constrained('specialties', 'specialty_id')->nullOnDelete();
            $table->integer('level')->default(1)->comment('سطح (1: اصلی, 2: فرعی)');
            $table->string('category', 50)->nullable()->comment('دسته‌بندی');
            
            // توضیحات
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('description_dari')->nullable()->comment('توضیحات (دری)');
            $table->text('description_pashto')->nullable()->comment('توضیحات (پشتو)');
            
            // درصدهای پیش‌فرض
            $table->decimal('default_clinic_percentage', 5, 2)->default(50.00)->comment('درصد پیش‌فرض کلینیک');
            $table->decimal('default_doctor_percentage', 5, 2)->default(50.00)->comment('درصد پیش‌فرض پزشک');
            $table->decimal('default_technical_percentage', 5, 2)->default(0)->comment('درصد پیش‌فرض فنی');
            
            // نرخ‌های پیش‌فرض
            $table->decimal('default_consultation_fee', 10, 2)->default(0)->comment('حق ویزیت پیش‌فرض');
            $table->decimal('default_followup_fee', 10, 2)->default(0)->comment('حق ویزیت پیگیری پیش‌فرض');
            $table->decimal('default_emergency_fee', 10, 2)->default(0)->comment('حق ویزیت اورژانسی پیش‌فرض');
            
            // تنظیمات
            $table->integer('default_slot_duration')->default(15)->comment('مدت نوبت پیش‌فرض (دقیقه)');
            $table->integer('default_max_patients_per_day')->default(30)->comment('حداکثر بیمار روزانه پیش‌فرض');
            $table->boolean('requires_special_equipment')->default(false)->comment('نیاز به تجهیزات خاص دارد؟');
            $table->boolean('requires_assistant')->default(false)->comment('نیاز به دستیار دارد؟');
            
            // منابع
            $table->string('icon', 50)->nullable()->comment('آیکون');
            $table->string('color', 20)->nullable()->comment('رنگ');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');
            
            // اتاق پیش‌فرض
            $table->string('default_room_type', 50)->nullable()->comment('نوع اتاق پیش‌فرض');
            $table->json('required_equipment')->nullable()->comment('تجهیزات مورد نیاز');
            
            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_visible')->default(true)->comment('قابل نمایش است؟');
            
            // آمار
            $table->integer('total_doctors')->default(0)->comment('تعداد پزشکان');
            $table->integer('total_appointments')->default(0)->comment('تعداد نوبت‌ها');
            $table->integer('total_visits')->default(0)->comment('تعداد ویزیت‌ها');
            
            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // ایندکس‌ها
            $table->index('specialty_code');
            $table->index('name');
            $table->index('parent_specialty_id');
            $table->index('category');
            $table->index('is_active');
            $table->index('sort_order');
        });
        
        // اضافه کردن constraint برای جمع درصدها
        Schema::table('specialties', function (Blueprint $table) {
            $table->check('default_clinic_percentage + default_doctor_percentage + default_technical_percentage = 100');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};