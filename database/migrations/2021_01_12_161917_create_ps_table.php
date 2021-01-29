<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreatePsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ps', function (Blueprint $collection) {
            $collection->string('nationalId')->unique();
            $collection->string('email')->nullable();
            $collection->string('phone')->nullable();
            $collection->string('employeeType')->nullable();
            $collection->string('firstName')->nullable();
            $collection->string('lastName')->nullable();
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ps');
    }
}
