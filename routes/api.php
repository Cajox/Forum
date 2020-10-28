<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*$timeStart = new Carbon($childCoursePeriod->coursePeriod->time_start);
$timeDuration = $childCoursePeriod->coursePeriod->time_duration;

$twentyBeforeFinishingClass = $timeStart->addMinutes($timeDuration - 20);
$twentyBeforeFinishReminder = $time->addMinutes(20);

$differenceFinishingClass = $twentyBeforeFinishReminder->diffInMinutes($twentyBeforeFinishingClass);

if ($differenceFinishingClass <= 60) {

    $parent->notify(new FinishedClassNotification($childCoursePeriod->user->username, $timeStart->addMinutes($timeDuration)));

}*/
