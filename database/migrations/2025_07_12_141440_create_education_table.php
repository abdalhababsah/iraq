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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('degree'); // الدرجة العلمية
            $table->string('institution'); // المؤسسة التعليمية
            $table->string('field_of_study')->nullable(); // مجال الدراسة
            $table->year('start_year')->nullable(); // سنة البدء
            $table->year('end_year')->nullable(); // سنة الانتهاء
            $table->text('description')->nullable(); // وصف إضافي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
