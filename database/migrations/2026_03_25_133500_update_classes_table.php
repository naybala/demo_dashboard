<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            // Remove section_id placeholder if it exists and replace with section string
            if (Schema::hasColumn('classes', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
            
            $table->string('section')->nullable()->after('grade_id');
            
            // Rename class_teacher_id to head_teacher_id for clarity
            if (Schema::hasColumn('classes', 'class_teacher_id')) {
                $table->renameColumn('class_teacher_id', 'head_teacher_id');
            } else {
                $table->foreignId('head_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            }

            $table->foreignId('co_teacher_id')->nullable()->after('head_teacher_id')->constrained('users')->nullOnDelete();
            
            $table->string('teaching_days')->nullable();
            $table->string('daily_time')->nullable();
            $table->string('attendance_mode')->nullable();
            $table->string('allow_makeup_attendance')->default('enable');
            $table->text('notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['co_teacher_id']);
            $table->dropColumn(['section', 'co_teacher_id', 'teaching_days', 'daily_time', 'attendance_mode', 'allow_makeup_attendance', 'notes']);
            
            if (Schema::hasColumn('classes', 'head_teacher_id')) {
                $table->renameColumn('head_teacher_id', 'class_teacher_id');
            }
        });
    }
};
