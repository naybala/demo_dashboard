<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->morphs('owner'); // student or teacher
            $table->string('relation'); // father, mother, guardian
            $table->string('name');
            $table->text('avatar')->nullable();
            $table->string('nrc')->nullable();
            $table->text('qualification')->nullable();
            $table->text('job')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string("password")->nullable(); // for guardian login
            $table->text('address')->nullable();
            $table->string('alive_status')->default('alive'); // alive, dead
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guardians');
    }
};
