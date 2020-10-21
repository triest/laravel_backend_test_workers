<?php

    namespace Tests\Unit;

    use App\Cabinet;
    use App\Employee;
    use App\Worker;
    use Illuminate\Support\Facades\Http;
    use Tests\TestCase;

    class EmployeeTest extends TestCase
    {
        /**
         * A basic unit test example.
         *
         * @return void
         */
        public function testExample()
        {
            parent::setUp();
            $this->assertTrue(true);
        }

        public function testConstructorTest()
        {
            parent::setUp();
            $employee = new Employee();

            $workers = Worker::select(['*'])->get()->count();

            $this->assertEquals($workers, 50);
            $cabinets = Cabinet::select(['*'])->count();
            $this->assertEquals($cabinets, 10);
        }

        public function testVkphoto()
        {
            parent::setUp();
            $employee = new Employee();

            $worker = Worker::select(["*"])->first();

            $employee->getVkphoto($worker->id);

              //$worker->photo)
            $worker = Worker::select(["*"])->where('id',$worker->id)->first();
            $response = Http::get($worker->photo);

            $this->assertEquals(200,$response->status());

        }


    }
