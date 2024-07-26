<?php

use App\Http\Controllers\Modules\Performance\EmployeeAssessController;
use App\Http\Controllers\Modules\Performance\EmployeeAssessMonthController;
use App\Http\Controllers\Modules\Performance\EmployeeTeamController;
use App\Http\Controllers\Modules\Performance\RealizationController;
use App\Http\Controllers\Modules\Performance\SkpApprovalController;
use App\Http\Controllers\Modules\Performance\SkpArchiveController;
use App\Http\Controllers\Modules\Performance\SkpEvaluationController;
use App\Http\Controllers\Modules\Performance\SkpMatrixController;
use App\Http\Controllers\Modules\Performance\SkpMonthController;
use App\Http\Controllers\Modules\Performance\SkpPlanActivityController;
use App\Http\Controllers\Modules\Performance\VerifySkpArchiveController;
use App\Http\Controllers\Modules\Questionnaire\QuestionnaireQuestionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'question',
], function () {
    $base_route = 'modules.questionnaire.question';
    Route::get('/', [QuestionnaireQuestionController::class, 'index'])->name($base_route . '.index');
    Route::get('/create', [QuestionnaireQuestionController::class, 'create'])->name($base_route . '.create');
    Route::post('/store', [QuestionnaireQuestionController::class, 'store'])->name($base_route . '.store');
    Route::get('/edit/{id}', [QuestionnaireQuestionController::class, 'edit'])->name($base_route . '.edit');
    Route::post('/save', [QuestionnaireQuestionController::class, 'save'])->name($base_route . '.save');
    Route::post('/delete', [QuestionnaireQuestionController::class, 'delete'])->name($base_route . '.delete');
    Route::get('/datatable', [QuestionnaireQuestionController::class, 'datatable'])->name($base_route . '.datatable');
});
Route::group([
    'prefix' => 'skp-matrix',
], function () {
    $base_route = 'modules.performance.skp-matrix';
    Route::get('/', [SkpMatrixController::class, 'index'])->name($base_route . '.index');
    Route::post('/get_assignments', [SkpMatrixController::class, 'get_assignments'])->name($base_route . '.get_assignments');
    Route::get('/get_internal_assignment_options', [SkpMatrixController::class, 'get_internal_assignment_options'])->name($base_route . '.get_internal_assignment_options');
    Route::post('/create_skp_assignment', [SkpMatrixController::class, 'create_skp_assignment'])->name($base_route . '.create_skp_assignment');
    Route::post('/delete_internal_assignment', [SkpMatrixController::class, 'delete_internal_assignment'])->name($base_route . '.delete_internal_assignment');
    Route::post('/get_assignments_on_person', [SkpMatrixController::class, 'get_assignments_on_person'])->name($base_route . '.get_assignments_on_person');
});
Route::group([
    'prefix' => 'skp-approval',
], function () {
    $base_route = 'modules.performance.skp-approval';
    Route::get('/', [SkpApprovalController::class, 'index'])->name($base_route . '.index');
    Route::get('/detail/{id}', [SkpApprovalController::class, 'detail'])->name($base_route . '.detail');
    Route::get('/edit_behavior_note/{id}', [SkpApprovalController::class, 'edit_behavior_note'])->name($base_route . '.edit_behavior_note');
    Route::post('/save_behavior_note', [SkpApprovalController::class, 'save_behavior_note'])->name($base_route . '.save_behavior_note');
    Route::post('/approve_skp', [SkpApprovalController::class, 'approve_skp'])->name($base_route . '.approve_skp');
    Route::post('/reject_skp', [SkpApprovalController::class, 'reject_skp'])->name($base_route . '.reject_skp');
    Route::post('/approve_bulk_skp', [SkpApprovalController::class, 'approve_bulk_skp'])->name($base_route . '.approve_bulk_skp');
    Route::post('/reject_bulk_skp', [SkpApprovalController::class, 'reject_bulk_skp'])->name($base_route . '.reject_bulk_skp');
});
Route::group([
    'prefix' => 'realization',
], function () {
    $base_route = 'modules.performance.realization';
    Route::get('/', [RealizationController::class, 'index'])->name($base_route . '.index');
    Route::post('/create_realization', [RealizationController::class, 'create_realization'])->name($base_route . '.create_realization');
    Route::post('/reset_realization', [RealizationController::class, 'reset_realization'])->name($base_route . '.reset_realization');
    Route::post('/update_realization_value', [RealizationController::class, 'update_realization_value'])->name($base_route . '.update_realization_value');
    Route::post('/empty_realization_value', [RealizationController::class, 'empty_realization_value'])->name($base_route . '.empty_realization_value');
    Route::post('/apply_realization', [RealizationController::class, 'apply_realization'])->name($base_route . '.apply_realization');
    Route::post('/cancel_applyment_realization', [RealizationController::class, 'cancel_applyment_realization'])->name($base_route . '.cancel_applyment_realization');
    Route::get('/print_evaluation_skp', [RealizationController::class, 'print_evaluation_skp'])->name($base_route . '.print_evaluation_skp');
    Route::get('/print_doc_evaluation_skp', [RealizationController::class, 'print_doc_evaluation_skp'])->name($base_route . '.print_doc_evaluation_skp');
});
Route::group([
    'prefix' => 'skp-evaluation',
], function () {
    $base_route = 'modules.performance.skp-evaluation';
    Route::get('/', [SkpEvaluationController::class, 'index'])->name($base_route . '.index');
    Route::get('/edit_evaluation/{id}', [SkpEvaluationController::class, 'edit_evaluation'])->name($base_route . '.edit_evaluation');
    Route::post('/save_evaluation', [SkpEvaluationController::class, 'save_evaluation'])->name($base_route . '.save_evaluation');
    Route::post('/revert_to_applyment_process', [SkpEvaluationController::class, 'revert_to_applyment_process'])->name($base_route . '.revert_to_applyment_process');
});
Route::group([
    'prefix' => 'skp-activity',
], function () {
    $base_route = 'modules.performance.skp-activity';
    Route::get('/', [SkpPlanActivityController::class, 'index'])->name($base_route . '.index');
    Route::post('/addActivity', [SkpPlanActivityController::class, 'addActivity'])->name($base_route . '.addActivity');
    Route::post('/deleteActivity', [SkpPlanActivityController::class, 'deleteActivity'])->name($base_route . '.deleteActivity');
});
Route::group([
    'prefix' => 'employee-team',
], function () {
    $base_route = 'modules.performance.employee-team';
    Route::get('/', [EmployeeTeamController::class, 'index'])->name($base_route . '.index');
    Route::any('/ajaxLoadStaff', [EmployeeTeamController::class, 'ajaxLoadStaff'])->name($base_route . '.ajaxLoadStaff');
    Route::any('/ajaxLoadSubteam', [EmployeeTeamController::class, 'ajaxLoadSubteam'])->name($base_route . '.ajaxLoadSubteam');
    Route::any('/saveTim', [EmployeeTeamController::class, 'saveTim'])->name($base_route . '.saveTim');
    Route::any('/setStatusTeam', [EmployeeTeamController::class, 'setStatusTeam'])->name($base_route . '.setStatusTeam');
    Route::any('/deleteTeam', [EmployeeTeamController::class, 'deleteTeam'])->name($base_route . '.deleteTeam');
    Route::any('/addSubteam', [EmployeeTeamController::class, 'addSubteam'])->name($base_route . '.addSubteam');
    Route::any('/deleteSubteam', [EmployeeTeamController::class, 'deleteSubteam'])->name($base_route . '.deleteSubteam');
});
Route::group([
    'prefix' => 'employee-assess',
], function () {
    $base_route = 'modules.performance.employee-assess';
    Route::get('/', [EmployeeAssessController::class, 'index'])->name($base_route . '.index');
    Route::post('/saveLead', [EmployeeAssessController::class, 'saveLead'])->name($base_route . '.saveLead');
    Route::post('/clearLead', [EmployeeAssessController::class, 'clearLead'])->name($base_route . '.clearLead');
});
Route::group([
    'prefix' => 'emp-assess-month',
], function () {
    $base_route = 'modules.performance.emp-assess-month';
    Route::get('/', [EmployeeAssessMonthController::class, 'index'])->name($base_route . '.index');
});
Route::group([
    'prefix' => 'skp-month',
], function () {
    $base_route = 'modules.performance.skp-month';
    Route::get('/', [SkpMonthController::class, 'index'])->name($base_route . '.index');
    Route::get('/detail/{id}', [SkpMonthController::class, 'detail'])->name($base_route . '.detail');
    Route::get('/datatable', [SkpMonthController::class, 'datatable'])->name($base_route . '.datatable');
});
Route::group([
    'prefix' => 'skp-archive',
], function () {
    $base_route = 'modules.performance.skp-archive';
    Route::get('/', [SkpArchiveController::class, 'index'])->name($base_route . '.index');
    Route::post('/saveFile', [SkpArchiveController::class, 'saveFile'])->name($base_route . '.saveFile');
});
Route::group([
    'prefix' => 'verify-archive',
], function () {
    $base_route = 'modules.performance.verify-archive';
    Route::get('/plan', [VerifySkpArchiveController::class, 'plan'])->name($base_route . '.plan');
    Route::get('/eval', [VerifySkpArchiveController::class, 'eval'])->name($base_route . '.eval');
    Route::get('/doc', [VerifySkpArchiveController::class, 'doc'])->name($base_route . '.doc');
    Route::post('/changeStatus', [VerifySkpArchiveController::class, 'changeStatus'])->name($base_route . '.changeStatus');
});
?>