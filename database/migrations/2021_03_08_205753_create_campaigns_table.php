<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('campaign_id', '50');
            $table->string('v_mail_campaign_id', '50')->nullable();

            $table->unsignedInteger('campaign_type_id');
            $table->foreign('campaign_type_id')->references('id')->on('campaign_types');

            $table->unsignedInteger('campaign_filter_id');
            $table->foreign('campaign_filter_id')->references('id')->on('campaign_filters');

            //$table->enum('campaign_status', ['1', '2', '3', '4', '5'])->default('1')->comment('1-Live,2-Paused,3-Cancelled,4-Delivered,5-Reactivated');

            $table->longText('note')->nullable();

            $table->enum('status', ['0', '1'])->default('1')->comment('1-Active, 0-Inactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
        Schema::dropIfExists('campaigns');
    }
}
