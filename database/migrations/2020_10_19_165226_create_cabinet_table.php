<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabinetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabinet', function (Blueprint $table) {
            $table->id();
            $table->integer('num')->nullable()->default(null)->comment('Номер');
            $table->integer('flor')->nullable()->default(null)->comment('Этаж');
            $table->integer('capacity')->nullable()->default(null)->comment('Вместимость');
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
        Schema::dropIfExists('cabinet');
    }
}
