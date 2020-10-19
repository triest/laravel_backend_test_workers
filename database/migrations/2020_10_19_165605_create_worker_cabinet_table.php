<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerCabinetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_cabinet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workerld')->nullable()->constrained('worker');
            $table->foreignId('cabinetld')->nullable()->constrained('cabinet');
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
        Schema::dropIfExists('worker_cabinet');
    }
}