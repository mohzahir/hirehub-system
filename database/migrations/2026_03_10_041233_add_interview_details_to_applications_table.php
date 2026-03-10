<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->date('interview_date')->nullable();
            $table->time('interview_time')->nullable();
            $table->string('interview_link')->nullable(); // قد يكون رابط Zoom أو مكان المقابلة
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['interview_date', 'interview_time', 'interview_link']);
        });
    }
};