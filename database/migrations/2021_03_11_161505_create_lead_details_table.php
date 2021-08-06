<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('allocation');
            $table->integer('deliver_count')->default(0);
            $table->integer('shortfall_count')->nullable();
            $table->enum('campaign_status', ['1', '2', '3', '4', '5', '6'])->default('1')->comment('1-Live,2-Paused,3-Cancelled,4-Delivered,5-Reactivated,6-Shortfall');
            $table->enum('pacing', ['Daily', 'Monthly', 'Weekly']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_details');
    }
}
