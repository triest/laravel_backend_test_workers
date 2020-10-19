<?php

    /** @var \Illuminate\Database\Eloquent\Factory $factory */

    use App\Cabinet;
    use Faker\Generator as Faker;

    $factory->define(
            Cabinet::class,
            function (Faker $faker) {
                return [
                    //
                        'num' => $faker->numberBetween($min = 1, $max = 1000),
                        'flor' => $faker->numberBetween(1, 45),
                        'capacity' => $faker->numberBetween(1, 10)
                ];
            }
    );
