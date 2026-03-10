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
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->decimal('total_agency_fee', 10, 2);
            $table->decimal('partner_commission', 10, 2)->default(0);
            // حقل مخصص لحساب حافز مسؤول التوظيف (لاستخراج قيمة الـ 5% وغيرها تلقائياً في التقارير)
            $table->decimal('recruiter_commission', 10, 2)->default(0); 
            $table->decimal('net_profit', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partially_paid', 'paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};
