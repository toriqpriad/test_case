<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Provinsi_Model;
use Faker\Generator as Faker;

$factory->define(Provinsi_Model::class, function (Faker $faker) {
    return [
        'id'   => $faker->numerify(),
        'nama' => $faker->text,
    ];
});
