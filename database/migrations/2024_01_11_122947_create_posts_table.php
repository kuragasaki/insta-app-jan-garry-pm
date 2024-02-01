<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();                          //post id
            $table->text('description');           //text can handle characters until 1000 chars
            $table->longText('image');             // posts image
            $table->unsignedBigInteger('user_id'); //the user id of the owner of the posts
            $table->timestamps();                  //date and time

            //This line will create a connection between the posts and users table in the database
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
