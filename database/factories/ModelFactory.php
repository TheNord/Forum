<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirmed' => false,
    ];
});

$factory->state(App\User::class, 'confirmed', function () {
    return [
        'confirmed' => true,
    ];
});

$factory->define(App\Channel::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->word,
        'slug' => str_slug($name)
    ];
});

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'title' => $title = $faker->sentence,
        'body' => $faker->paragraph,
        'slug' => str_slug($title),
        'best_reply_id' => null,
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'thread_id' => function () {
            return factory('App\Thread')->create()->id;
        },
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

// php artisan Tinker
// >> $threads = factory('App\Thread', 50)->create();
// >> $threads->each(function ($thread) { factory('App\Reply', 10)->create(['thread_id' => $thread->id]); });
