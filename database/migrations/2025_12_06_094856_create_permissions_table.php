<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id');
            
            // کد و نام
            $table->string('permission_code', 100)->unique()->comment('کد دسترسی');
            $table->string('name', 100)->comment('نام دسترسی');
            $table->string('name_dari', 100)->nullable()->comment('نام دسترسی (دری)');
            $table->string('name_pashto', 100)->nullable()->comment('نام دسترسی (پشتو)');
            
            // توضیحات
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('description_dari')->nullable()->comment('توضیحات (دری)');
            $table->text('description_pashto')->nullable()->comment('توضیحات (پشتو)');
            
            // ماژول و گروه
            $table->enum('module', [
                'dashboard',
                'patients',
                'doctors',
                'appointments',
                'visits',
                'prescriptions',
                'inventory',
                'pharmacy',
                'laboratory',
                'radiology',
                'billing',
                'finance',
                'payroll',
                'human_resources',
                'reports',
                'settings',
                'system'
            ])->comment('ماژول');
            
            $table->string('group', 50)->nullable()->comment('گروه');
            $table->string('sub_group', 50)->nullable()->comment('زیرگروه');
            
            // نوع دسترسی
            $table->enum('permission_type', ['create', 'read', 'update', 'delete', 'export', 'import', 'approve', 'reject'])->comment('نوع دسترسی');
            $table->enum('access_level', ['none', 'own', 'department', 'all'])->default('none')->comment('سطح دسترسی');
            
            // تنظیمات
            $table->boolean('is_crud')->default(false)->comment('عملیات CRUD است؟');
            $table->boolean('is_system')->default(false)->comment('دسترسی سیستمی است؟');
            $table->boolean('is_required')->default(false)->comment('الزامی است؟');
            $table->boolean('is_dangerous')->default(false)->comment('دسترسی خطرناک است؟');
            
            // محدودیت‌ها
            $table->json('conditions')->nullable()->comment('شرایط');
            $table->json('restrictions')->nullable()->comment('محدودیت‌ها');
            
            // آیکون
            $table->string('icon', 50)->nullable()->comment('آیکون');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');
            
            // وضعیت
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            
            // تاریخ‌ها
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // ایندکس‌ها
            $table->index('permission_code');
            $table->index('name');
            $table->index('module');
            $table->index('group');
            $table->index('permission_type');
            $table->index('access_level');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index(['module', 'group', 'permission_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};