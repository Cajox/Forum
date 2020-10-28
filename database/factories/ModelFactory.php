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

use App\Models\Channel;
use App\Models\User;
use App\Models\Reply;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'remember_token' => Str::random(10),
    ];
});


$factory->define(\App\Models\Thread::class, function (Faker\Generator $faker) {

    return [

        'user_id' => function(){
            return factory(User::class)->create()->id;
        },

        'channel_id' => function(){
            return factory(Channel::class)->create()->id;
        },

        'title' => $faker->sentence,
        'body' => $faker->paragraph,

    ];

});


$factory->define(Channel::class, function (Faker\Generator $faker) {

    $name = $faker->word;

    return [

        'name' => $name,
        'slug' => $name

    ];

});


$factory->define(Reply::class, function (Faker\Generator $faker) {

    return [

        'thread_id' => function(){
            return factory('App\Models\Thread')->create()->id;
        },

        'user_id' => function(){
            return factory('App\Models\User')->create()->id;
        },

        'body' => $faker->paragraph,

    ];

});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker\Generator $faker) {

    return [

        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function(){
            return Auth::id() ?: factory(User::class)->create()->id;
        },

        'notifiable_type' => 'App\Models\User',
        'data' => ['foo'=>'bar'],

    ];

});
