<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('person_id')->nullable()->constrained('people', 'person_id')->nullOnDelete();

            // اطلاعات ورود
            $table->string('username', 50)->unique()->comment('نام کاربری');
            $table->string('email', 100)->unique()->nullable()->comment('ایمیل');
            $table->string('password_hash')->comment('هش رمز عبور');

            // نوع کاربر
            $table->enum('user_type', [
                'super_admin',
                'admin',
                'doctor',
                'receptionist',
                'accountant',
                'nurse',
                'pharmacist',
                'lab_technician',
                'radiologist',
                'manager',
                'assistant',
                'patient'
            ])->default('patient')->comment('نوع کاربر');

            // وضعیت حساب
            $table->boolean('is_active')->default(true)->comment('فعال است؟');
            $table->boolean('is_verified')->default(false)->comment('تأیید شده است؟');
            $table->boolean('is_locked')->default(false)->comment('قفل شده است؟');

            // امنیت
            $table->timestamp('last_login')->nullable()->comment('آخرین ورود');
            $table->string('last_login_ip', 45)->nullable()->comment('آخرین IP ورود');
            $table->integer('login_attempts')->default(0)->comment('تعداد تلاش‌های ورود');
            $table->timestamp('locked_until')->nullable()->comment('قفل شده تا');

            // تغییر رمز
            $table->timestamp('password_changed_at')->nullable()->comment('تاریخ تغییر رمز');
            $table->boolean('password_change_required')->default(false)->comment('نیاز به تغییر رمز دارد؟');

            // احراز هویت دو مرحله‌ای
            $table->boolean('mfa_enabled')->default(false)->comment('احراز هویت دو مرحله‌ای فعال است؟');
            $table->string('mfa_secret', 100)->nullable()->comment('رمز احراز دو مرحله‌ای');
            $table->json('mfa_backup_codes')->nullable()->comment('کدهای پشتیبان احراز دو مرحله‌ای');

            // توکن‌ها
            $table->rememberToken();
            $table->string('api_token', 80)->unique()->nullable()->comment('توکن API');
            $table->timestamp('api_token_expires_at')->nullable()->comment('انقضای توکن API');

            // تنظیمات کاربر
            $table->string('language', 10)->default('fa')->comment('زبان');
            $table->string('timezone', 50)->default('Asia/Kabul')->comment('منطقه زمانی');
            $table->json('preferences')->nullable()->comment('تنظیمات کاربر');

            // آواتار
            $table->string('avatar_url', 500)->nullable()->comment('آدرس آواتار');
            $table->string('avatar_type', 20)->default('default')->comment('نوع آواتار');

            // اعلان‌ها
            $table->boolean('email_notifications')->default(true)->comment('اعلان‌های ایمیلی');
            $table->boolean('sms_notifications')->default(true)->comment('اعلان‌های SMS');
            $table->boolean('push_notifications')->default(true)->comment('اعلان‌های Push');

            // جلسات
            $table->json('active_sessions')->nullable()->comment('جلسات فعال');
            $table->integer('max_sessions')->default(3)->comment('حداکثر جلسات همزمان');

            // تاریخ‌ها
            $table->timestamp('email_verified_at')->nullable()->comment('تاریخ تأیید ایمیل');
            $table->timestamp('phone_verified_at')->nullable()->comment('تاریخ تأیید تلفن');
            $table->timestamp('verified_at')->nullable()->comment('تاریخ تأیید کلی');

            // اطلاعات اضافی
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->json('metadata')->nullable()->comment('اطلاعات اضافی');

            // تاریخ‌های سیستم
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ایندکس‌ها
            $table->index('username');
            $table->index('email');
            $table->index('user_type');
            $table->index('is_active');
            $table->index('is_verified');
            $table->index('last_login');
            $table->index('api_token');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
