<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->comment('Имя');
            $table->string('tel',100)->nullable()->default(null)->comment('Телефон');
            $table->string('address',255)->nullable()->default(null)->comment('Адрес');
            $table->float('salary',255,2)->nullable()->default(null)->comment('Размер зарплаты');
            $table->string('vkld',255)->nullable()->default(null)->comment('ID Вконтакте');
            $table->string('photo',255)->nullable()->default(null)->comment('Фото');
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
        Schema::dropIfExists('worker');
    }
}
