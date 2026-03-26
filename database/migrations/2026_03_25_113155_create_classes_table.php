<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->string('section')->nullable();
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->nullOnDelete();

            $table->foreignId('head_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('co_teacher_id')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('capacity')->nullable();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('teaching_days')->nullable();
            $table->string('daily_time')->nullable();
            $table->string('attendance_mode')->nullable();
            $table->string('allow_makeup_attendance')->default('enable');
            $table->text('notes')->nullable();
            $table->string('status')->default('active');

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};
