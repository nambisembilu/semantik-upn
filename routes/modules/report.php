<?php

use App\Http\Controllers\Modules\Report\ReportSkpEvaluationController;
use App\Http\Controllers\Modules\Report\ReportSkpPlanController;
use App\Http\Controllers\Modules\Report\ReportSkpMatrixController;
use Illuminate\Support\Facades\Route;

Route::group([
        'prefix' => 'skp-plan',
    ], function () {
        $base_route = 'modules.report.skp-plan';
        Route::any('/', [
            ReportSkpPlanController::class,
            'index'
        ])->name($base_route.'.skp-plan.index');
        Route::post('/get_print_skp_plan_data', [ReportSkpPlanController::class, 'get_print_skp_plan_data'])->name($base_route . '.get_print_skp_plan_data');
        Route::get('/print_skp', [ReportSkpPlanController::class, 'print_skp'])->name($base_route . '.print_skp');
    
    });

    
    Route::group([
        'prefix' => 'skp-evaluation',
    ], function () {
        $base_route = 'modules.report.skp-evaluation';
        Route::any('/', [
            ReportSkpEvaluationController::class,
            'index'
        ])->name($base_route.'.skp-evaluation.index');
        Route::post('/get_print_skp_evaluation_data', [ReportSkpEvaluationController::class, 'get_print_skp_evaluation_data'])->name($base_route . '.get_print_skp_evaluation_data');
        Route::get('/print_evaluation_skp', [ReportSkpEvaluationController::class, 'print_evaluation_skp'])->name($base_route . '.print_evaluation_skp');
        Route::get('/print_doc_evaluation_skp', [ReportSkpEvaluationController::class, 'print_doc_evaluation_skp'])->name($base_route . '.print_doc_evaluation_skp');
    });

    Route::group([
        'prefix' => 'skp-matrix',
    ], function () {
        $base_route = 'modules.report.skp-matrix';
        Route::any('/', [
            ReportSkpMatrixController::class,
            'index'
        ])->name($base_route.'.skp-matrix.index');
        Route::post('/get_skp_matrix_data', [ReportSkpMatrixController::class, 'get_skp_matrix_data'])->name($base_route . '.get_skp_matrix_data');
    });
