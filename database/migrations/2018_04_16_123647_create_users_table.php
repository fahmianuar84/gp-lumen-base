<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        1. Deliver a User Management Services using Lumen (https://lumen.laravel.com/) and MySQL as Database, the database comprise of just one table->"Users"
//        - emailAddress - primary key, varchar(40),
//        - userName - varchar(100),
//        - password - varchar(60), one way hash
//        - createdDate, datetime
//        - updatedDate, datetime
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userName', 100);
            $table->string('emailAddress', 40)->unique();
            $table->string('password', 60);
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
        Schema::dropIfExists('users');
    }
}
