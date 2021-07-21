<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', '50');
            $table->string('slug', '50');
            $table->integer('parent_id')->nullable();
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->enum('sidebar_visibility',['0','1'])->default('0')->comment('1-Yes, 0-No');
            $table->integer('priority');
            $table->enum('is_module', ['0', '1'])->default('0')->comment('1-yes, 0-no');

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
        Schema::dropIfExists('permissions');
    }
}
