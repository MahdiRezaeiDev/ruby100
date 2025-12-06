<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id('permission_role_id');

            // کلیدهای خارجی
            $table->foreignId('role_id')->constrained('roles', 'role_id')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions', 'permission_id')->onDelete('cascade');

            // سطح دسترسی
            $table->enum('access_type', ['allow', 'deny', 'conditional'])->default('allow')->comment('نوع دسترسی');
            $table->enum('access_level', ['none', 'own', 'department', 'all'])->default('none')->comment('سطح دسترسی');

            // شرایط
            $table->json('conditions')->nullable()->comment('شرایط');
            $table->json('restrictions')->nullable()->comment('محدودیت‌ها');

            // تاریخ‌های شمسی
            $table->string('effective_jalali_date', 10)->nullable()->comment('تاریخ اثرگذاری شمسی');
            $table->date('effective_gregorian_date')->nullable()->comment('تاریخ اثرگذاری میلادی');

            $table->string('expiry_jalali_date', 10)->nullable()->comment('تاریخ انقضا شمسی');
            $table->date('expiry_gregorian_date')->nullable()->comment('تاریخ انقضا میلادی');

            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_inherited')->default(false)->comment('ارثی است؟');

            // اولویت
            $table->integer('priority')->default(0)->comment('اولویت');

            // نظرات
            $table->text('notes')->nullable()->comment('یادداشت‌ها');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // کلیدهای منحصربه‌فرد
            $table->unique(['role_id', 'permission_id', 'access_type']);

            // ایندکس‌ها
            $table->index(['role_id', 'permission_id']);
            $table->index('access_type');
            $table->index('access_level');
            $table->index('effective_jalali_date');
            $table->index('effective_gregorian_date');
            $table->index('expiry_jalali_date');
            $table->index('expiry_gregorian_date');
            $table->index('is_active');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
};
