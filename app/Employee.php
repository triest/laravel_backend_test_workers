<?php

    namespace App;

    use DOMDocument;
    use DOMXPath;
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
                //      mkdir(base_path() . '/docs/' . $worker->id);
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
            $workers = Worker::whereHas(
                    'cabinet',
                    function ($query) use ($flor) {
                        $query->where('flor', '=', $flor);
                    }
            )->get();

            return $workers;
        }

        //3. Создать метод, выбирающий сотрудников с наибольшей зар. платой на этаже/в кабинете;
        public function selectWorkerWithMaxSalary($flor, $type = "flor")
        {
            if ($type == "flor") {
                $workers = Worker::whereHas(
                        'cabinet',
                        function ($query) use ($flor) {
                            $query->where('flor', '=', $flor);
                        }
                )->orderBy('salary', 'desc')->first();;
            } elseif ($type == "cabinet") {
                $workers = Worker::whereHas(
                        'cabinet',
                        function ($query) use ($flor) {
                            $query->where('num', '=', $flor);
                        }
                )->orderBy('salary', 'desc')->first()->first();
            } else {
                $workers = null;
            }
            return $workers;
        }

        //4. Создать метод, выбирающий всех сотрудников из кабинета с наибольшей/наименьшей вместимостью;
        public function selectWorkersFromCabinetOrderByCapacity($order = "max")
        {
            if ($order == "max") {
                $cabinet = Cabinet::select(['*'])->orderby('capacity', 'desc')->first();
            } elseif ($order == "min") {
                $cabinet = Cabinet::select(['*'])->orderby('capacity', 'asc')->first();
            }
            if ($cabinet == null) {
                return null;
            }

            //теперь сотрудников из кабинета из кабинета
            $workers = Worker::whereHas(
                    'cabinet',
                    function ($query) use ($cabinet) {
                        $query->where('cabinet.id', '=', $cabinet->id);
                    }
            )->get();

            return $workers;
        }

        //5.Создать метод, который в каталоге /docs/<worker.id> находит все файлы, имена которых состоят из цифр и букв
        // латинского алфавита, имеют расширение txt и выводит на экран имена этих файлов, упорядоченных по имени.
        // Каталог выбирать по полю name из таблицы worker. Задачу выполнить с применением регулярных выражений;
        public function searchFiles($workerid)
        {
            $files = Storage::files('/public/docs/' . $workerid . "/");
            $rez = [];
            foreach ($files as $file) {
                $rex = "/^[a-zA-Z0-9].*\.(txt)$/i";
                // $rex="/^([a-zA-Z0-9\s_\\.\-\(\):])+\.(txt)$/i";
                if ($rex != "" && preg_match($rex, $file)) {
                    array_push($rez, basename($file));
                }
            }

            return asort($rez);
        }


        public function getVkphoto($user_id)
        {
            //page_avatar_img
            $user = Worker::select(['*'])->where('id', $user_id)->first();
            if ($user == null) {
                return;
            }
            $url = "https://vk.com/" . $user->vkld;
            //$linkimg = $this->getTags("https://vk.com/mcsidar");

            $htmlString = file_get_contents($url);

//Create a new DOMDocument object.
            $htmlDom = new DOMDocument;

//Load the HTML string into our DOMDocument object.
            @$htmlDom->loadHTML($htmlString);

            $finder = new DomXPath(@$htmlDom);
            $classname = "page_avatar_img";


            //надо по класса page_avatar_img
            $imageTags = $htmlDom->getElementsByTagName('img');

//Create an array to add extracted images to.
            $extractedImages = array();

//Loop through the image tags that DOMDocument found.
            foreach ($imageTags as $imageTag) {
                //Get the src attribute of the image.
                $imgSrc = $imageTag->getAttribute('src');

                //Get the alt text of the image.
                $altText = $imageTag->getAttribute('alt');

                //Get the title text of the image, if it exists.
                $titleText = $imageTag->getAttribute('title');

                //Add the image details to our $extractedImages array.
                $extractedImages[] = array(
                        'src' => $imgSrc,
                        'alt' => $altText,
                        'title' => $titleText
                );
            }
            try {
                $src = ($extractedImages[0]['src']);
            } catch (\Exception $exception) {
                return 1;
            }
            $user->photo = $src;
            $user->save();
            return $user;
        }


        public function testWorkerFlor(int $flor)
        {
            return $this->selectWorkersOnFlor($flor);
        }

    }
