<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('marital_status')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nrc')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            
            $table->string('professional_subject')->nullable();
            $table->string('possessive_grade')->nullable();
            
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            $table->json('education_background')->nullable();
            $table->json('work_experience')->nullable();
            $table->json('professional_qualifications')->nullable();
            
            $table->string('department_name')->nullable();
            $table->string('position')->nullable();
            $table->string('service_duration')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
