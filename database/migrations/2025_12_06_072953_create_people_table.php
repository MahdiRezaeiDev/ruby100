<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id('person_id');
            $table->string('national_id', 20)->unique()->nullable()->comment('کد ملی/تذکره');
            $table->string('first_name', 50)->comment('نام');
            $table->string('last_name', 50)->comment('نام خانوادگی');
            $table->string('father_name', 50)->nullable()->comment('نام پدر');
            $table->enum('gender', ['M', 'F', 'Other'])->nullable()->comment('جنسیت');

            // تاریخ تولد شمسی
            $table->string('birth_year', 4)->nullable()->comment('سال تولد شمسی');
            $table->string('birth_month', 2)->nullable()->comment('ماه تولد شمسی');
            $table->string('birth_day', 2)->nullable()->comment('روز تولد شمسی');
            $table->date('birth_date_gregorian')->nullable()->comment('تاریخ تولد میلادی');

            $table->string('phone', 15)->nullable()->comment('تلفن ثابت');
            $table->string('mobile', 15)->comment('موبایل');
            $table->string('email', 100)->nullable()->comment('ایمیل');

            // آدرس
            $table->string('province', 100)->nullable()->comment('ولایت');
            $table->string('district', 100)->nullable()->comment('ولسوالی');
            $table->string('village', 100)->nullable()->comment('قریه');
            $table->text('address')->nullable()->comment('آدرس کامل');

            $table->string('emergency_contact', 100)->nullable()->comment('تماس اضطراری');
            $table->string('emergency_phone', 15)->nullable()->comment('تلفن اضطراری');

            // تاریخ‌های سیستم
            $table->timestamps();

            // ایندکس‌ها
            $table->index(['first_name', 'last_name']);
            $table->index('national_id');
            $table->index('mobile');
            $table->index('province');
            $table->index('district');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
