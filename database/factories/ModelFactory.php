<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Jobs::class, function (Faker\Generator $faker) {
    return [
    'title' => $faker->sentence(6, true),
    'description' => $faker->paragraphs(3, true),
    'category' => $faker->slug,
    'company_name'=>$faker->slug,
    'company_website'=>'www.abc.com',
    'company_email'=>'company@company.com',
    'company_phone'=>'123445677',
    'company_logo'=>'',
    'company_facebook'=>'',
    'company_video'=>'',
    'keywords'=>$faker->slug,
    'type'=> 'part_time',
    'requirements'=>$faker->sentence(10, true),
    'user_id' => $faker->numberBetween(1,10),
    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    'finish' => Carbon::now()->format('Y-m-d H:i:s'),
    'city'=>$faker->city,
    'district'=>$faker->state,
    'zone'=>'Narayani',
    'country'=>$faker->country,

    ];
});