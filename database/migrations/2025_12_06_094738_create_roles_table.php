<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');

            // نام و شناسه
            $table->string('role_code', 50)->unique()->comment('کد نقش');
            $table->string('name', 100)->comment('نام نقش');
            $table->string('name_dari', 100)->nullable()->comment('نام نقش (دری)');
            $table->string('name_pashto', 100)->nullable()->comment('نام نقش (پشتو)');

            // توضیحات
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('description_dari')->nullable()->comment('توضیحات (دری)');
            $table->text('description_pashto')->nullable()->comment('توضیحات (پشتو)');

            // نوع نقش
            $table->enum('role_type', ['system', 'custom', 'department'])->default('custom')->comment('نوع نقش');
            $table->enum('access_level', ['super_admin', 'admin', 'manager', 'staff', 'user'])->default('user')->comment('سطح دسترسی');

            // محدودیت‌ها
            $table->integer('max_users')->nullable()->comment('حداکثر کاربران');
            $table->boolean('is_default')->default(false)->comment('نقش پیش‌فرض است؟');
            $table->boolean('is_assignable')->default(true)->comment('قابل انتساب است؟');
            $table->boolean('is_editable')->default(true)->comment('قابل ویرایش است؟');
            $table->boolean('is_deletable')->default(true)->comment('قابل حذف است؟');

            // تنظیمات
            $table->json('settings')->nullable()->comment('تنظیمات نقش');
            $table->json('restrictions')->nullable()->comment('محدودیت‌ها');

            // ماژول‌های مجاز
            $table->json('allowed_modules')->nullable()->comment('ماژول‌های مجاز');
            $table->json('denied_modules')->nullable()->comment('ماژول‌های غیرمجاز');

            // آیکون و رنگ
            $table->string('icon', 50)->nullable()->comment('آیکون');
            $table->string('color', 20)->nullable()->comment('رنگ');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');

            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_visible')->default(true)->comment('قابل نمایش است؟');

            // تاریخ‌ها
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ایندکس‌ها
            $table->index('role_code');
            $table->index('name');
            $table->index('role_type');
            $table->index('access_level');
            $table->index('is_active');
            $table->index('is_default');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
