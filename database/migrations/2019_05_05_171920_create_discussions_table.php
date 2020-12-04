<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussionsTable extends Migration
{
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->bigInteger('category_id')->unsigned()->default(1);
            $table->string('title');
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('sticky')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('private')->default(false);
            $table->integer('replies')->unsigned()->default(0);
            $table->timestamp('last_reply_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('discussions');
    }
}
