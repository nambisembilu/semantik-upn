<?php

    use App\Http\Controllers\Modules\Account\ProfileController;
    use App\Http\Controllers\Modules\Account\LoginAsOtherController;
    use Illuminate\Support\Facades\Route;

    Route::get('/', [
        ProfileController::class,
        'index'
    ])->name('modules.account.profile.index');

    Route::post('/update', [
        ProfileController::class,
        'update'
    ])->name('modules.account.profile.update');

    Route::get('/new-password', [
        ProfileController::class,
        'newPassword'
    ])->name('modules.account.profile.new-password');

    Route::post('/update-password', [
        ProfileController::class,
        'updatePassword'
    ])->name('modules.account.profile.update-password');


    Route::prefix('loginasother')->group(function () {
        $base_route = 'modules.account.loginasother';
        Route::get('/', [LoginAsOtherController::class, 'index'])->name($base_route . '.index');
        Route::get('/datatable', [LoginAsOtherController::class, 'datatable'])->name($base_route . '.datatable');
        Route::post('/post', [LoginAsOtherController::class, 'post'])->name($base_route . '.post');
    });


