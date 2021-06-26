<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('details')->nullable();
            $table->string('thumbnail');
            $table->string('image');
            $table->boolean('home')->default(false);
            $table->boolean('post')->default(false);
            $table->boolean('event')->default(false);
            $table->boolean('gallery')->default(false);
            $table->boolean('contact')->default(false);
            $table->boolean('about')->default(false);
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
        Schema::dropIfExists('images');
    }
}
