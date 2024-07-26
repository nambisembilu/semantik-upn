<?php

use App\Http\Controllers\Modules\Master\EmployeeController;
use App\Http\Controllers\Modules\Master\EmploymentAgreementController;
use App\Http\Controllers\Modules\Master\OrganizationPerformanceController;
use App\Http\Controllers\Modules\Master\PeriodController;
use App\Http\Controllers\Modules\Master\PersonalWorkUnitController;
use App\Http\Controllers\Modules\Master\WorkPositionController;
use App\Http\Controllers\Modules\Master\WorkUnitController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'period',
], function () {
    $prefix_menu_name = 'modules.master.period';
    Route::post('get_by_id', [
        PeriodController::class,
        'get_by_id'
    ])->name('modules.master.period.get_by_id');
    Route::get('/', [PeriodController::class, 'index'])->name($prefix_menu_name . '.index');
    Route::get('/create', [PeriodController::class, 'create'])->name($prefix_menu_name . '.create');
    Route::post('/store', [PeriodController::class, 'store'])->name($prefix_menu_name . '.store');
    Route::get('/edit/{id}', [PeriodController::class, 'edit'])->name($prefix_menu_name . '.edit');
    Route::post('/save', [PeriodController::class, 'save'])->name($prefix_menu_name . '.save');
    Route::post('/delete', [PeriodController::class, 'delete'])->name($prefix_menu_name . '.delete');
    Route::get('/datatable', [PeriodController::class, 'datatable'])->name($prefix_menu_name . '.datatable');
});
Route::group([
    'prefix' => 'personal_work_unit',
], function () {
    Route::post('get_work_units_by_personal_period', [
        PersonalWorkUnitController::class,
        'get_work_units_by_personal_period'
    ])->name('modules.master.personal_work_unit.get_work_units_by_personal_period');
    Route::post('get_roleposition_by_personal_period_workunit', [
        PersonalWorkUnitController::class,
        'get_roleposition_by_personal_period_workunit'
    ])->name('modules.master.personal_work_unit.get_roleposition_by_personal_period_workunit');
});
Route::prefix('employee')->group(function () {
    $base_route = 'modules.master.employee';
    Route::get('/', [EmployeeController::class, 'index'])->name($base_route . '.index');
    Route::get('/create', [EmployeeController::class, 'create'])->name($base_route . '.create');
    Route::post('/store', [EmployeeController::class, 'store'])->name($base_route . '.store');
    Route::post('/save', [EmployeeController::class, 'save'])->name($base_route . '.save');
    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name($base_route . '.edit');
    Route::post('/edit-history', [EmployeeController::class, 'editHistory'])->name($base_route . '.editHistory');
    Route::post('/saveProfile/{id}', [EmployeeController::class, 'saveProfile'])->name($base_route . '.saveProfile');
    Route::post('/addHistory/{id}', [EmployeeController::class, 'addHistory'])->name($base_route . '.addHistory');
    Route::post('/saveHistory', [EmployeeController::class, 'saveHistory'])->name($base_route . '.saveHistory');
    Route::post('/deleteHistory', [EmployeeController::class, 'deleteHistory'])->name($base_route . '.deleteHistory');
    Route::post('/delete', [EmployeeController::class, 'delete'])->name($base_route . '.delete');
    Route::get('/datatable', [EmployeeController::class, 'datatable'])->name($base_route . '.datatable');
});


Route::prefix('org-performance')->group(function () {
    $base_route = 'modules.master.org-performance';
    Route::get('/', [OrganizationPerformanceController::class, 'index'])->name($base_route . '.index');
    Route::post('/save', [OrganizationPerformanceController::class, 'save'])->name($base_route . '.save');

});

Route::prefix('employment-agreement')->group(function () {
    $base_route = 'modules.master.employment-agreement';
    Route::get('/', [EmploymentAgreementController::class, 'index'])->name($base_route . '.index');
    Route::post('/create_employment_agreement', [EmploymentAgreementController::class, 'create_employment_agreement'])->name($base_route . '.create_employment_agreement');
    Route::post('/create_employment_agreement_indicator', [EmploymentAgreementController::class, 'create_employment_agreement_indicator'])->name($base_route . '.create_employment_agreement_indicator');
    Route::post('/delete_employment_agreement', [EmploymentAgreementController::class, 'delete_employment_agreement'])->name($base_route . '.delete_employment_agreement');
    Route::post('/delete_employment_agreement_indicator', [EmploymentAgreementController::class, 'delete_employment_agreement_indicator'])->name($base_route . '.delete_employment_agreement_indicator');
    
});

Route::group([
    'prefix' => 'work-unit',
], function () {
    $prefix_menu_name = 'modules.master.work-unit';
    Route::get('/', [WorkUnitController::class, 'index'])->name($prefix_menu_name . '.index');
    Route::get('/create', [WorkUnitController::class, 'create'])->name($prefix_menu_name . '.create');
    Route::post('/store', [WorkUnitController::class, 'store'])->name($prefix_menu_name . '.store');
    Route::get('/edit/{id}', [WorkUnitController::class, 'edit'])->name($prefix_menu_name . '.edit');
    Route::post('/save', [WorkUnitController::class, 'save'])->name($prefix_menu_name . '.save');
    Route::post('/delete', [WorkUnitController::class, 'delete'])->name($prefix_menu_name . '.delete');
    Route::get('/datatable', [WorkUnitController::class, 'datatable'])->name($prefix_menu_name . '.datatable');
});

Route::group([
    'prefix' => 'work-position',
], function () {
    $prefix_menu_name = 'modules.master.work-position';
    Route::get('/', [WorkPositionController::class, 'index'])->name($prefix_menu_name . '.index');
    Route::get('/create', [WorkPositionController::class, 'create'])->name($prefix_menu_name . '.create');
    Route::post('/store', [WorkPositionController::class, 'store'])->name($prefix_menu_name . '.store');
    Route::get('/edit/{id}', [WorkPositionController::class, 'edit'])->name($prefix_menu_name . '.edit');
    Route::post('/save', [WorkPositionController::class, 'save'])->name($prefix_menu_name . '.save');
    Route::post('/delete', [WorkPositionController::class, 'delete'])->name($prefix_menu_name . '.delete');
    Route::get('/datatable', [WorkPositionController::class, 'datatable'])->name($prefix_menu_name . '.datatable');
});
?>