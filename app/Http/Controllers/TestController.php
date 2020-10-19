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
        $emploee->test_fill();
    }
}
