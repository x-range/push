<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('body', 512)->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable(); //192x192
            $table->string('badge')->nullable(); //96x96 monochrome
            $table->string('link', 512);
            $table->unsignedBigInteger('notification')->default(0);
            $table->unsignedBigInteger('click')->default(0);
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
        Schema::dropIfExists('messages');
    }
}
