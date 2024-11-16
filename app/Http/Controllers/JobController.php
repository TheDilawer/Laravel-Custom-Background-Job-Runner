<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    //
    public function index()
    {
        $logs = file(storage_path('logs/laravel.log'));
        return view('jobs.index', ['logs' => $logs]);
    }
}
