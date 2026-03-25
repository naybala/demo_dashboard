<?php

use App\Enums\Announcements\AnnouncementDepartment;
use App\Enums\Announcements\AnnouncementDestination;
use App\Enums\Common\Status;
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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('department', AnnouncementDepartment::toArray())->nullable();

            $table->date('date')->nullable();
            $table->enum('destination', AnnouncementDestination::toArray())->nullable();
            $table->enum('status',Status::toArray())->default(Status::Active->value);

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
        Schema::dropIfExists('announcements');
    }
};
