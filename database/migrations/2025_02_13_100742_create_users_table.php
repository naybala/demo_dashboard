<?php

use App\Enums\Common\Status;
use App\Enums\Users\UserType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type',UserType::toArray());
            $table->string('fullname',100)->nullable();
            $table->string('phone_number',50)->nullable();
            $table->string('email',50)->nullable();
            $table->string('password',100);
            $table->text('avatar')->nullable();
            $table->enum('status',Status::toArray());
            $table->string('role_marked')->nullable();
            $table->string('remember_token',100)->nullable();
            
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    //is_role,country,address,username,branch,avartar->avatar
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('users');
    }
};
