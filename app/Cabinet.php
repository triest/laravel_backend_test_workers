<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    //
    protected $table="cabinet";

    public function worker()
    {
        return $this->belongsToMany(Worker::class,'worker_cabinet','cabinetld','workerld');
    }
}
