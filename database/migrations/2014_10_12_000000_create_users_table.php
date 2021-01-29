<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $collection) {
            $collection->string('name');
            $collection->string('email')->unique()->nullable();
            $collection->timestamp('email_verified_at')->nullable();
            $collection->string('password')->nullable();
            $collection->rememberToken();
            $collection->timestamps();

            $collection->string('last_name')->nullable();
            $collection->string('provider')->nullable();
            $collection->string('provider_id')->nullable();
            $collection->string('preferred_username')->unique()->nullable();
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
