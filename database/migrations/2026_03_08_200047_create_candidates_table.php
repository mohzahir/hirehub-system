<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            // الحقل التالي nullable لأن المرشح قد يأتي مباشرة وليس عبر مكتب
            $table->foreignId('partner_id')->nullable()->constrained()->onDelete('set null'); 
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nationality');
            $table->string('profession');
            $table->integer('experience_years')->default(0);
            $table->string('original_cv_path')->nullable();
            // مسار السيرة الذاتية بعد تظليل بيانات الاتصال (لربطها بمخرجات سكربت البايثون الخاص بك)
            $table->string('redacted_cv_path')->nullable(); 
            $table->enum('passport_status', ['ready', 'processing', 'not_ready'])->default('not_ready');
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
