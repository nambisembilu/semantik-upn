<?php

use App\Http\Controllers\DefaultController;
use App\Http\Controllers\Modules\Questionnaire\QuestionnaireQuestionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Modules\Master\PeriodController;
use App\Http\Controllers\Modules\Master\PersonalWorkUnitController;
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

Route::get('/', [DefaultController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

/* api */

 
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    require __DIR__ . '/module.php';
});

// Public Link
Route::get('/questionnaire/link/{id}', [QuestionnaireQuestionController::class, 'link'])->name( 'modules.questionnaire.question.link');

