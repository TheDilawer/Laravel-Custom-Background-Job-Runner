<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\CustomJobs\BackgroundJobRunner;
use App\CustomJobs\DummyJob;
use App\Http\Controllers\JobController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-job', function () {
    $className = \App\CustomJobs\DummyJob::class;
    $methodName = 'sayHello';
    $parameters = ['Laravel'];

    // Run immediately
    runBackgroundJob(App\CustomJobs\DummyJob::class, 'sayHello', ['Laravel']);

    // Run after 5 seconds
    runBackgroundJob(App\CustomJobs\DummyJob::class, 'sayHello', ['Laravel'], 5);

    // Simulate a error
    runBackgroundJob('NonExistentClass', 'someMethod', []);


    return response()->json(['status' => 'Job started']);
});

Route::get('/jobs', [JobController::class, 'index']);
