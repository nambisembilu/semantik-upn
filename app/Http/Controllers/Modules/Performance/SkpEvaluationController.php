<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpRealization;
use App\Models\Transaction\SkpPlanRealization;
use App\Models\Transaction\SkpBehaviorRealization;
use App\Constants\SkpStatus;
use App\Models\Master\FeedbackWorkCategory;
use App\Models\Master\FeedbackBehaviorCategory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\System\LogError;
use Illuminate\Validation\ValidationException;
use App\Constants\RealizationStatus;
use App\Helpers\Helper;

class SkpEvaluationController extends Controller
{

    private $route = "modules.performance.skp-evaluation.";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $pwuSkps = DB::select("SELECT skpr.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
        CASE
            WHEN skpr.id IS NOT NULL THEN skpr.realization_status
        ELSE 'Belum Dibuat'
        END AS status, per.description as period_description, skpr.performance_predicate
        FROM personal_work_units pwu
        INNER JOIN personal_work_units pwuc ON pwuc.assessor_personal_work_unit_id = pwu.id
        INNER JOIN work_positions wp on wp.id = pwuc.work_position_id
        INNER JOIN work_units wu on wu.id = pwuc.work_unit_id 
        INNER JOIN personals p on p.id = pwuc.personal_id
        INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
        INNER JOIN work_titles wt on wt.id = p.work_title_id
        INNER JOIN roles r on r.id = pwuc.role_id
        INNER JOIN periods per on per.id = pwu.period_id
        LEFT JOIN skps on skps.personal_id = pwuc.personal_id AND skps.work_unit_id = pwuc.work_unit_id  AND skps.period_id = pwuc.period_id
        LEFT JOIN skp_realizations skpr on skpr.skp_id = skps.id
        WHERE pwu.deleted_at is null AND pwu.personal_id = ".$personal->id." AND pwu.period_id = ".session('period_id')." AND pwu.is_active = '1' AND pwu.work_unit_id = ".session('work_unit_id'));
        $data = [
            "route" => $this->route,
            'pwuSkps' => $pwuSkps
        ];
        return view($this->route . 'index', $data);
    }

    public function revert_to_applyment_process(Request $request)
    {
        try
        {
            $skpRealization = SkpRealization::find($request->get('realization_id'));
            $skpRealization->realization_status = RealizationStatus::BelumDiajukan;
            $skpRealization->realization_date = null;
            $skpRealization->save();
            return response()->json([
                'status' => 1,
                'message' => 'Proses kembalikan ke pengaju berhasil',
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            LogError::create([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'params' => json_encode([$request->all()]),
                'stack_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'url' => $request->fullurl(),
                'ip_source' => $request->ip(),
                'client_code' => '',
                'user_agent' => $request->header('User-Agent'),
                'error_code' => $e->getCode(),
                'http_code' => '500',
            ]);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function save_evaluation(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();
            $skpRealization = SkpRealization::find($request->get('id'));

            if(!empty($skpRealization))
            {
                if(!empty($request->get('main_plan_id')))
                {
                    foreach($request->get('main_plan_id') as $key => $mainPlanId)
                    {
                        $skpPlanRealization = SkpPlanRealization::find($mainPlanId);
                        $skpPlanRealization->feedback_work_category_id = $request->get('main_plan_work_category_id')[$key];
                        $skpPlanRealization->feedback = $request->get('main_plan_value')[$key];
                        $skpPlanRealization->save();
                    }
                }
                

                if(!empty($request->get('additional_plan_id')))
                {
                    foreach($request->get('additional_plan_id') as $key => $additionalPlanId)
                    {
                        $skpPlanRealization = SkpPlanRealization::find($additionalPlanId);
                        $skpPlanRealization->feedback_work_category_id = $request->get('additional_plan_work_category_id')[$key];
                        $skpPlanRealization->feedback = $request->get('additional_plan_value')[$key];
                        $skpPlanRealization->save();
                    }
                }
                
                if(!empty($request->get('behavior_plan_id')))
                {
                    foreach($request->get('behavior_plan_id') as $key => $behaviorlPlanId)
                    {
                        $skpBehaviorRealization = SkpBehaviorRealization::find($behaviorlPlanId);
                        $skpBehaviorRealization->feedback_behavior_category_id = $request->get('behavior_plan_category_id')[$key];
                        $skpBehaviorRealization->feedback = $request->get('behavior_plan_value')[$key];
                        $skpBehaviorRealization->save();
                    }
                }
                
                $skpRealization->feedback_work_category_id = $request->get('work_category_id');
                $skpRealization->feedback_behavior_category_id = $request->get('behavior_category_id');
                $skpRealization->feedback_work_summary = $request->get('feedback_work_summary');
                $skpRealization->feedback_behavior_summary = $request->get('feedback_behavior_summary');
                $skpRealization->performance_predicate = $request->get('predicate_work');

                $skpRealization->realization_status = RealizationStatus::SudahDievaluasi;
                $skpRealization->save();
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' =>'Evaluasi berhasil disimpan',
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            LogError::create([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'params' => json_encode([$request->all()]),
                'stack_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'url' => $request->fullurl(),
                'ip_source' => $request->ip(),
                'client_code' => '',
                'user_agent' => $request->header('User-Agent'),
                'error_code' => $e->getCode(),
                'http_code' => '500',
            ]);

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function edit_evaluation($id)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $skpRealization = SkpRealization::with(['skp'])->find($id);

        $skp = Skp::find($skpRealization->skp_id);

        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])
        ->where([
            ['personal_id', '=', $skp->personal_id],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', $skp->work_unit_id],
            ['is_active', '=', true]])->whereNull('deleted_at')
        ->first();

        $mainSkp = SkpPlanRealization::join('skp_work_plans', 'skp_work_plans.id', '=', 'skp_plan_realizations.skp_work_plan_id')
            ->where(
                [
                    'skp_work_plans.is_main' => 1,
                    'skp_plan_realizations.skp_realization_id' => $skpRealization->id
                ])
            ->select('skp_plan_realizations.*')
            ->orderBy('skp_work_plans.id', 'asc')->get();
        
        $additionalSkp = SkpPlanRealization::join('skp_work_plans', 'skp_work_plans.id', '=', 'skp_plan_realizations.skp_work_plan_id')
        ->where(
            [
                'skp_work_plans.is_main' => 0,
                'skp_plan_realizations.skp_realization_id' => $skpRealization->id
            ])
        ->select('skp_plan_realizations.*')
        ->orderBy('skp_work_plans.id', 'asc')->get();
        
        $officerWorkUnit = null;
        if(!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id))
        {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workTitle', 'personal','personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }

        $skpBehaviorRealizations = SkpBehaviorRealization::join('skp_behaviors', 'skp_behaviors.id', '=', 'skp_behavior_realizations.skp_behavior_id')
            ->where(
                [
                    'skp_behavior_realizations.skp_realization_id' => $skpRealization->id
                ])
            ->select('skp_behavior_realizations.*')
            ->orderBy('id', 'asc')->get();

        $feedbackWorkCategories = FeedbackWorkCategory::with(['feedbackWorkTextTemplates'])->orderBy('id', 'asc')->get();
        $feedbackBehaviorCategories = FeedbackBehaviorCategory::with(['feedbackBehaviorTextTemplates'])->orderBy('id', 'asc')->get();
        $helper = new Helper();
        $data = [
            "route" => $this->route,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'skpBehaviorRealizations' => $skpBehaviorRealizations,
            'skpRealization' => $skpRealization,
            'feedbackWorkCategories' => $feedbackWorkCategories,
            'feedbackBehaviorCategories' => $feedbackBehaviorCategories,
            'helper' => $helper

        ];
        return view($this->route . 'edit-evaluation', $data);
    }
}
