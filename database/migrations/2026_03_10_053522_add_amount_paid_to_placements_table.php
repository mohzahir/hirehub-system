<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('placements', function (Blueprint $table) {
            // إضافة عمود المبلغ المدفوع (الافتراضي 0)
            $table->decimal('amount_paid', 10, 2)->default(0)->after('net_profit');
        });
    }

    public function down()
    {
        Schema::table('placements', function (Blueprint $table) {
            $table->dropColumn('amount_paid');
        });
    }
};