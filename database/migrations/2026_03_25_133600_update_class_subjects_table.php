<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_subjects', function (Blueprint $table) {
            $table->integer('hours_per_week')->nullable()->after('teacher_id');
        });
    }

    public function down()
    {
        Schema::table('class_subjects', function (Blueprint $table) {
            $table->dropColumn('hours_per_week');
        });
    }
};
