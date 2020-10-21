<?php

    namespace App\Http\Controllers;

    use App\Employee;
    use Illuminate\Http\Request;

    class TestController extends Controller
    {
        //

        public function create(Request $request)
        {
            $employee = new Employee();
            $employee->fill();
        }

        public function workerCabinet()
        {
            $employee = new Employee();
            $employee->selectWorkerCabinet();
        }

        public function workerFlor($flor)
        {
            $employee = new Employee();
            $worker = $employee->testWorkerFlor($flor);
            dump($worker);
        }

        public function maxSalary($flor)
        {
            $employee = new Employee();
            $salary = $employee->selectWorkerWithMaxSalary($flor);
            dump($salary);
        }

        public function capacity($type)
        {
            $employee = new Employee();
            $workers = $employee->selectWorkersFromCabinetOrderByCapacity($type);
            dump($workers);
        }

        public function searchFiles($workerid)
        {
            $employee = new Employee();
            $files = $employee->searchFiles($workerid);
            dump($files);
        }

        public function vkPhoto()
        {
            $employee = new Employee();
            $employee->getVkphoto(1);
        }

        public function testget(){
            $employee=new Employee();
            $data=$employee->searchFiles(2);
            dump($data);
        }
    }
