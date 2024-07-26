<?php

use Illuminate\Support\Facades\Route;

Route::prefix('questionnaire')->group(function () {
    $prefix_module_name = 'questionnaire';
    Route::get('/', function () use ($prefix_module_name) {
        return view($prefix_module_name);
    })->name($prefix_module_name);
});

Route::group([
    'prefix' => 'questionnaire',
    'namespace' => 'questionnaire',
], function () {
    require base_path('routes/modules/questionnaire.php');
});

Route::prefix('config')->group(function () {
    $prefix_module_name = 'config';
    Route::get('/', function () use ($prefix_module_name) {
        return view($prefix_module_name);
    })->name($prefix_module_name);

    Route::group([
        'prefix' => 'account',
        'namespace' => 'Account',
    ], function () {
        require base_path('routes/modules/account.php');
    });
    
    Route::group([
        'prefix' => 'master',
        'namespace' => 'Master',
    ], function () {
        require base_path('routes/modules/master.php');
    });
    
});


Route::prefix('report')->group(function () {
    $prefix_module_name = 'report';
    Route::get('/', function () use ($prefix_module_name) {
        return view($prefix_module_name);
    })->name($prefix_module_name);

    Route::group([
        'namespace' => 'Report',
    ], function () {
        require base_path('routes/modules/report.php');
    });
});


