<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. إضافة الراتب والعمولة لجدول المستخدمين (الموظفين)
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('base_salary', 10, 2)->default(2000.00)->after('password');
            $table->decimal('commission_rate', 5, 2)->default(5.00)->after('base_salary'); // 5.00 تعني 5%
        });

        // 2. ربط المشروع بالموظف المسؤول عنه لضمان ذهاب العمولة له
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('client_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['base_salary', 'commission_rate']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};