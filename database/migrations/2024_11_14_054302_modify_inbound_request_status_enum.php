<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInboundRequestStatusEnum extends Migration
{
    public function up()
    {
        // Schema::table('inbound_requests', function (Blueprint $table) {
        //     $table->enum('status', [
        //         'In Transit',
        //         'Received - Pending Verification',
        //         'Quantity Discrepancy',
        //         'Completed',
        //         'Ready to Complete'
        //     ])->notNullable()->change();
        // });
    }

    public function down()
    {
        Schema::table('inbound_requests', function (Blueprint $table) {
            $table->enum('status', [
                'In Transit',
                'Received - Pending Verification',
                'Quantity Discrepancy',
                'Completed',
            ])->notNullable()->change();
        });
    }
}

