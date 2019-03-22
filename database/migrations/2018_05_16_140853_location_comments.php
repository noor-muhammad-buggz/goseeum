<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocationComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_comments', function (Blueprint $table) {
            $table->bigIncrements('comment_id');
            $table->text('comment_body')->nullable();
            $table->integer('comment_parent_type')->default(1);
            $table->bigInteger('comment_user_id');
            $table->bigInteger('comment_parent_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_comments');
    }
}
