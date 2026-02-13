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
        Schema::create('daily_incomes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name')->nullable();
            $table->decimal('amount', 10, 2);
            $table->foreignId('own_product_id')->constrained()->restrictOnDelete();
            $table->integer('price');
            $table->integer('investment');
            $table->integer('profit');
            $table->foreignId('daily_income_total_id')->nullable()->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('daily_incomes');
    }
};
