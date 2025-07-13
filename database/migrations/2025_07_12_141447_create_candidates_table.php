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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('constituency_id')->constrained()->onDelete('cascade');
            
            // Required signup fields
            $table->string('party_bloc_name'); // اسم الكتلة او الحزب او الإتلاف
            $table->string('phone'); // رقم الهاتف او الواتساب
            $table->text('biography'); // اكتب سيرتك الذاتية او عرف عن نفسك
            
            // Optional profile fields
            $table->string('list_number')->nullable(); // رقم القائمة او الكتلة
            $table->string('current_position')->nullable(); // المنصب الحالي او السابق او العشائري
            $table->text('achievements')->nullable(); // إنجازات المرشح
            $table->text('additional_info')->nullable(); // معلومات إضافية
            $table->text('experience')->nullable(); // الخبرة العملية
            $table->text('skills')->nullable(); // المهارات
            $table->string('campaign_slogan')->nullable(); // شعار حملتك الانتخابية
            $table->text('voter_promises')->nullable(); // ماذا ستقدم للناخبين
            
            // Image fields
            $table->string('profile_image')->nullable(); // صورة الملف الشخصي
            $table->string('profile_banner_image')->nullable(); // صورة الغلاف
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};