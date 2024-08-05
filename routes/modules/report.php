<?php

use App\Http\Controllers\Modules\Report\QuestionnaireResultController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'questionnaire-result',
], function () {
    $base_route = 'modules.report.questionnaire-result';
    Route::any('/', [
        QuestionnaireResultController::class,
        'index'
    ])->name($base_route . '.index');
    Route::post('/detail/{q_id}', [QuestionnaireResultController::class, 'detail'])->name($base_route . '.detail');
});