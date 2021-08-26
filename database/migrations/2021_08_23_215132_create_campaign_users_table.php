<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_users', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->date('display_date')->nullable();
            $table->integer('allocation')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-active,0-inactive');
            $table->enum('progress_status', ['0','1','2'])->nullable();
            $table->integer('assigned_by');
            $table->integer('parent_id')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_At')->useCurrent();
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
        Schema::dropIfExists('campaign_users');
    }
}
