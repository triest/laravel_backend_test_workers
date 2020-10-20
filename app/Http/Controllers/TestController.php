<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //

    public function create(Request $request){
        $emploee=new Employee();
      //  $emploee->test_create();
        $emploee->fill();
    }

    public function workerCabinet(){
        $emploee=new Employee();
        $emploee->selectWorkerCabinet();
    }

    public function workerFlor($flor){
        $emploee=new Employee();
       $worker=$emploee->testWorkerFlor($flor);
        dump($worker);
    }
}
