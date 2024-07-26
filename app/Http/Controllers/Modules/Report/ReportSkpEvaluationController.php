<?php

namespace App\Http\Controllers\Modules\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\Period;
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
use App\Models\UI\PersonalInfo;
use App\Models\UI\PrintSkpInfo;
use Carbon\Carbon;
use PDF;

class ReportSkpEvaluationController extends Controller
{

    private $route = "modules.report.skp-evaluation.";
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
        $pwuSkps = null;
        $period = Period::find(session('period_id'));
        if(session('role_name') == "Superadmin")
        {
            $pwuSkps = DB::select("SELECT skpr.id as skpr_id, pwuc.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
                CASE
                    WHEN skpr.id IS NOT NULL THEN skpr.realization_status
                ELSE 'Belum Dibuat'
                END AS status, per.description as period_description, skpr.performance_predicate
                FROM personal_work_units pwuc
                INNER JOIN work_positions wp on wp.id = pwuc.work_position_id
                INNER JOIN work_units wu on wu.id = pwuc.work_unit_id 
                INNER JOIN personals p on p.id = pwuc.personal_id
                INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
                INNER JOIN work_titles wt on wt.id = p.work_title_id
                INNER JOIN roles r on r.id = pwuc.role_id
                INNER JOIN periods per on per.id = pwuc.period_id
                LEFT JOIN skps on skps.personal_id = pwuc.personal_id AND skps.work_unit_id = pwuc.work_unit_id 
                LEFT JOIN skp_realizations skpr on skpr.skp_id = skps.id
                WHERE pwuc.deleted_at is null AND pwuc.period_id = ".session('period_id')." AND pwuc.is_active = '1'");  
        }
        else if(session('role_name') == "SuperadminUK")
        {
            $pwuSkps = DB::select("SELECT skpr.id as skpr_id, pwuc.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
            CASE
                WHEN skpr.id IS NOT NULL THEN skpr.realization_status
            ELSE 'Belum Dibuat'
            END AS status, per.description as period_description, skpr.performance_predicate
            FROM personal_work_units pwuc
            INNER JOIN work_positions wp on wp.id = pwuc.work_position_id
            INNER JOIN work_units wu on wu.id = pwuc.work_unit_id 
            INNER JOIN personals p on p.id = pwuc.personal_id
            INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
            INNER JOIN work_titles wt on wt.id = p.work_title_id
            INNER JOIN roles r on r.id = pwuc.role_id
            INNER JOIN periods per on per.id = pwuc.period_id
            LEFT JOIN skps on skps.personal_id = pwuc.personal_id AND skps.work_unit_id = pwuc.work_unit_id 
            LEFT JOIN skp_realizations skpr on skpr.skp_id = skps.id
            WHERE pwuc.deleted_at is null AND pwuc.period_id = ".session('period_id')." AND pwuc.is_active = '1' AND pwuc.root_work_unit_id = ".session('work_unit_id'));    
        }
        
        $data = [
            "route" => $this->route,
            'pwuSkps' => $pwuSkps,
            'period' => $period
        ];
        return view($this->route . 'index', $data);
    }

    public function get_print_skp_evaluation_data(Request $request)
    {
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])
        ->find($request->get('pwu_id'));

        $officerWorkUnit = null;
        if(!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id))
        {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workPosition','personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }

        $upperOfficerWorkUnit = null;
        if(!empty($officerWorkUnit) && !empty($officerWorkUnit->assessor_personal_work_unit_id))
        {
            $upperOfficerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workPosition','personal.workRank', 'personal.workTitle'])
                ->find($officerWorkUnit->assessor_personal_work_unit_id);
        }

        $helper = new Helper();
        $date = Carbon::now();
        $dateSetting = 'Surabaya, '.$helper->dateEnglishtoIndoMMMFormat($date->format('Y-m-d'));

        $printSkpInfo = new PrintSkpInfo();
        $printSkpInfo->personalWorkUnit = $personalWorkUnit;
        $printSkpInfo->officerWorkUnit = $officerWorkUnit;
        $printSkpInfo->upperOfficerWorkUnit = $upperOfficerWorkUnit;
        $printSkpInfo->personalGrade = !empty($personalWorkUnit) ? $helper->getGradeValue($personalWorkUnit->personal->employee_type, $personalWorkUnit->personal->workRank->grade_name, $personalWorkUnit->personal->workRank->name) : '-' ;
        $printSkpInfo->officerGrade = !empty($officerWorkUnit) ? $helper->getGradeValue($officerWorkUnit->personal->employee_type, $officerWorkUnit->personal->workRank->grade_name, $officerWorkUnit->personal->workRank->name) : '-' ;
        $printSkpInfo->upperOfficerGrade = !empty($upperOfficerWorkUnit) ? $helper->getGradeValue($upperOfficerWorkUnit->personal->employee_type, $upperOfficerWorkUnit->personal->workRank->grade_name, $upperOfficerWorkUnit->personal->workRank->name) : '-' ;
        $printSkpInfo->dateSetting = $dateSetting;

        return response()->json($printSkpInfo);
    }

    public function print_evaluation_skp(Request $request)
    {
        $user = Auth::user();
        $pwu = PersonalWorkUnit::find($request->get('pwu_id'));

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $pwu->personal_id, 'period_id' => $pwu->period_id,
        'work_unit_id' => $pwu->work_unit_id])->first();
        
        $personalWorkUnit = new PersonalInfo();
        $personalWorkUnit->name = $request->get('employee_name');
        $personalWorkUnit->workIdNumber = $request->get('employee_nip');
        $personalWorkUnit->rank = $request->get('employee_rank');
        $personalWorkUnit->position = $request->get('employee_position');
        $personalWorkUnit->workUnit = $request->get('employee_work_unit');

        
        $officerWorkUnit = new PersonalInfo();
        $officerWorkUnit->name = $request->get('asessor_name');
        $officerWorkUnit->workIdNumber = $request->get('asessor_nip');
        $officerWorkUnit->rank = $request->get('asessor_rank');
        $officerWorkUnit->position = $request->get('asessor_position');
        $officerWorkUnit->workUnit = $request->get('asessor_work_unit');
        
        
        $periodName = $request->get('period_name');
        $period = Period::find(session('period_id'));

        $skpBehaviors = null;

        $skpRealization = SkpRealization::with(['skp','feedbackWorkCategory', 'feedbackBehaviorCategory'])
        ->where(
        [
            'skp_id' => $skp->id,
        ])
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
        
        $skpBehaviorRealizations = SkpBehaviorRealization::join('skp_behaviors', 'skp_behaviors.id', '=', 'skp_behavior_realizations.skp_behavior_id')
            ->where(
                [
                    'skp_behavior_realizations.skp_realization_id' => $skpRealization->id
                ])
            ->select('skp_behavior_realizations.*')
            ->orderBy('id', 'asc')->get();
            
        $dateSetting = $request->get('date_setting');
        
        $helper = new Helper();
        $data = [
            "route" => $this->route,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'skpBehaviorRealizations' => $skpBehaviorRealizations,
            'skpRealization' => $skpRealization,
            'periodName' => $periodName,
            'period' => $period,
            'dateSetting' => $dateSetting,
            'helper' => $helper
        ];
 
    	$pdf = PDF::loadview('modules.report.skp-evaluation.print-evaluation-skp-pdf',$data);
        return $pdf->stream();
    	//return $pdf->download('print-skp.pdf');
    }
    
    public function print_doc_evaluation_skp(Request $request)
    {
        $user = Auth::user();
        $pwu = PersonalWorkUnit::find($request->get('doc_pwu_id'));

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $pwu->personal_id, 'period_id' => $pwu->period_id,
        'work_unit_id' => $pwu->work_unit_id])->first();
        
        $personalWorkUnit = new PersonalInfo();
        $personalWorkUnit->name = $request->get('doc_employee_name');
        $personalWorkUnit->workIdNumber = $request->get('doc_employee_nip');
        $personalWorkUnit->rank = $request->get('doc_employee_rank');
        $personalWorkUnit->position = $request->get('doc_employee_position');
        $personalWorkUnit->workUnit = $request->get('doc_employee_work_unit');

        
        $officerWorkUnit = new PersonalInfo();
        $officerWorkUnit->name = $request->get('doc_asessor_name');
        $officerWorkUnit->workIdNumber = $request->get('doc_asessor_nip');
        $officerWorkUnit->rank = $request->get('doc_asessor_rank');
        $officerWorkUnit->position = $request->get('doc_asessor_position');
        $officerWorkUnit->workUnit = $request->get('doc_asessor_work_unit');

        $upperOfficerWorkUnit = new PersonalInfo();
        $upperOfficerWorkUnit->name = $request->get('doc_upper_asessor_name');
        $upperOfficerWorkUnit->workIdNumber = $request->get('doc_upper_asessor_nip');
        $upperOfficerWorkUnit->rank = $request->get('doc_upper_asessor_rank');
        $upperOfficerWorkUnit->position = $request->get('doc_upper_asessor_position');
        $upperOfficerWorkUnit->workUnit = $request->get('doc_upper_asessor_work_unit');
        
        $periodName = $request->get('period_name');
        $period = Period::find(session('period_id'));

        $skpRealization = SkpRealization::with(['skp','feedbackWorkCategory', 'feedbackBehaviorCategory'])
        ->where(
        [
            'skp_id' => $skp->id,
        ])
        ->first();
            
        $dateSettingEmployee = $request->get('date_setting_employee');
        $dateSettingOfficer = $request->get('date_setting_officer');
        
        $helper = new Helper();
        $data = [
            "route" => $this->route,
            'officerWorkUnit' => $officerWorkUnit,
            'upperOfficerWorkUnit' => $upperOfficerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'skpRealization' => $skpRealization,
            'periodName' => $periodName,
            'period' => $period,
            'dateSettingEmployee' => $dateSettingEmployee,
            'dateSettingOfficer' => $dateSettingOfficer,
            'helper' => $helper
        ];
 
    	$pdf = PDF::loadview('modules.report.skp-evaluation.print-doc-evaluation-skp-pdf',$data);
        return $pdf->stream();
    	//return $pdf->download('print-skp.pdf');
    }
}
