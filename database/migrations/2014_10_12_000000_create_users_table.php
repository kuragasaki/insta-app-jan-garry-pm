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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //255 characters
            $table->string('email')->unique();
            $table->longText('avatar')->nullable(); //profile image -- more than 1000 characters
            $table->string('password');
            $table->string('introduction', 100)->nullable(); // self introduction of the users
            $table->unsignedBigInteger('role_id') //is use for making the user 1-Administrator, 2 - Regular User only
                ->default(2)
                ->comment('1:admin 2:user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
