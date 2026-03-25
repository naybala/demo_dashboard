<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Basic Info enhancements
            if (!Schema::hasColumn('students', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('student_code');
            }
            if (!Schema::hasColumn('students', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable()->after('academic_year');
            }
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->nullable()->after('class_id');
            }
            if (!Schema::hasColumn('students', 'nrc')) {
                $table->string('nrc')->nullable()->after('gender');
            }

            // Academic Details
            if (!Schema::hasColumn('students', 'school_attended')) {
                $table->string('school_attended')->nullable();
            }
            if (!Schema::hasColumn('students', 'grade_attended')) {
                $table->string('grade_attended')->nullable();
            }
            if (!Schema::hasColumn('students', 'year_attended')) {
                $table->string('year_attended')->nullable();
            }
            if (!Schema::hasColumn('students', 'grade_id')) {
                $table->unsignedBigInteger('grade_id')->nullable();
            }
        });

        // Drop guardian columns if they exist
        Schema::table('students', function (Blueprint $table) {
            $colsToDrop = [
                'father_name', 'father_nrc', 'father_qualification', 'father_job', 'father_phone', 'father_email', 'father_address', 'father_alive_status',
                'mother_name', 'mother_nrc', 'mother_qualification', 'mother_job', 'mother_phone', 'mother_email', 'mother_address', 'mother_alive_status',
                'guardian_name', 'guardian_nrc', 'guardian_qualification', 'guardian_job', 'guardian_phone', 'guardian_email', 'guardian_address', 'guardian_alive_status'
            ];
            
            foreach ($colsToDrop as $col) {
                if (Schema::hasColumn('students', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'academic_year', 'class_id', 'full_name', 'nrc',
                'school_attended', 'grade_attended', 'year_attended', 'grade_id'
            ]);
        });
    }
};
