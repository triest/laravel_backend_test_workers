<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    //
    protected $table="worker";

    public function cabinet()
    {
        return $this->belongsToMany(Cabinet::class,'worker_cabinet','workerId','cabinetId');
    }
}
