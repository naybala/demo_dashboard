<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
            // Re-adding columns is complex as we'd need to restore data, 
            // but for simplicity we'll just define the columns again if needed.
        });
    }
};
