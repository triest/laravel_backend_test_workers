<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Storage;
    use Mockery\Exception;
    use Symfony\Component\Filesystem\Exception\IOException;

    /** @var \Illuminate\Database\Eloquent\Factory $factory */
    class Employee extends Model
    {
        //
        protected $table = "worker";

        private function _create()
        {
            try {
                Schema::create(
                        'worker',
                        function (Blueprint $table) {
                            $table->id();
                            $table->string('name', 255)->comment('Имя');
                            $table->string('tel', 100)->nullable()->default(null)->comment('Телефон');
                            $table->string('address', 255)->nullable()->default(null)->comment('Адрес');
                            $table->float('salary', 255, 2)->nullable()->default(null)->comment('Размер зарплаты');
                            $table->string('vkld', 255)->nullable()->default(null)->comment('ID Вконтакте');
                            $table->string('photo', 255)->nullable()->default(null)->comment('Фото');
                            $table->timestamps();
                        }
                );
            } catch (\Illuminate\Database\QueryException  $exception) {
                echo "error create worker table.";
                die();
            }

            try {
                Schema::create(
                        'cabinet',
                        function (Blueprint $table) {
                            $table->id();
                            $table->integer('num')->nullable()->default(null)->comment('Номер');
                            $table->integer('flor')->nullable()->default(null)->comment('Этаж');
                            $table->integer('capacity')->nullable()->default(null)->comment('Вместимость');
                            $table->timestamps();
                        }
                );
            } catch (\Illuminate\Database\QueryException  $exception) {
                echo "error create cabinet table.";
                die();
            }

            try {
                Schema::create(
                        'worker_cabinet',
                        function (Blueprint $table) {
                            $table->id();
                            $table->foreignId('workerld')->constrained('worker');
                            $table->foreignId('cabinetld')->constrained('cabinet');
                            $table->timestamps();
                        }
                );
            } catch (\Illuminate\Database\QueryException  $exception) {
                echo "error create worker_cabinet table.";
                die();
            }
        }

        private function _fill()
        {
            //заполняем кабинет
            $workers = Worker::select(['*'])->get();

            $number_workers = count($workers);
            //dump($number_workers);
            $cabinets = Cabinet::select(['*'])->get();
            //dump($cabinets);
            $count = 0;
            foreach ($cabinets as $cabinet => $item) {
                $chunk = $workers->splice($count, $item->capacity); //получаем людей для посадки в кабинет.//сажаем
                foreach ($chunk as $chunk_item) {
                    //    $item->worker()->attach($chunk_item);
                    $chunk_item->cabinet()->attach($item);
                }
                if ($count >= $number_workers) {
                    break;
                }
            }
            $workers = Worker::select(['*'])->get();
            //создание каталога
            foreach ($workers as $worker) {
                Storage::makeDirectory('docs/' . $worker->id);
            }
        }


        public function test_fill()
        {
            $this->_fill();
        }


        public function test_create()
        {
            $this->_create();
        }

        //Оптимизировать запрос и создать метод, выполняющий выборку: SELECT * FROM worker, cabinet, worker_cabinet WHERE worker_cabinet.workerId = worker.id AND worker_cabinet.cabinetId = cabinet.id;
        public function selectWorkerCabinet()
        {
            $workers = Worker::select(['*'])->with(['cabinet'])->get();
            dump($workers);
            dump($workers->cabinet);
        }

        //2. Создать метод выбирающий всех сотрудников на определенном этаже;
        private function selectWorkersOnFlor(int $flor)
        {
            $workers=Worker::whereHas('cabinet',function ($query) use ($flor) {
                $query->where('flor','=',$flor);
            })->get();

            return $workers;
        }

        public function testWorkerFlor(int $flor)
        {
           return $this->selectWorkersOnFlor($flor);
        }

    }
