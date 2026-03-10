<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('placements', function (Blueprint $table) {
            // إضافة عمود يحدد من استلم الفلوس بيده ('hirehub' أو 'partner')
            $table->string('fee_collector')->default('hirehub')->after('application_id');
        });
    }

    public function down()
    {
        Schema::table('placements', function (Blueprint $table) {
            $table->dropColumn('fee_collector');
        });
    }
};