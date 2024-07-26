<?php

namespace App\Http\Controllers\Modules\Report;

use App\Constants\SkpStatus;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Master\AttachmentCategory;
use App\Models\Master\BehaviorCategory;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\Master\RealizationPeriodType;
use App\Models\Master\AttachmentTextTemplate;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpWorkAttachment;
use App\Models\Transaction\SkpWorkIndicator;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\UI\MatrixReportInfo;
use App\Models\UI\MatrixReportPersonalWorkPlanInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Collection;

class ReportSkpMatrixController extends Controller
{

    private $route = "modules.report.skp-matrix.";
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
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')
        ->where('period_id', session('period_id'))->first();
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])
        ->where([
            ['personal_id', '=', $personal->id],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', session('work_unit_id')],
            ['is_active', '=', true]])->whereNull('deleted_at')
        ->first();

        if(session('role_name') == "SuperadminUK")
        {
            $workUnits = WorkUnit::where('id', $personalWorkUnit->root_work_unit_id)->get();
        }
        else if(session('role_name') == "Superadmin")
        {
            $workUnits = WorkUnit::where('parent_id', $work_unit_main->id)->orwhere('id', $work_unit_main->id)->get(); 
        }
        
        $data = [
            "route" => $this->route,
            'workUnits' => $workUnits
        ];
        return view($this->route . 'index', $data);
    }

    public function get_skp_matrix_data(Request $request)
    {
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')
        ->where('period_id', session('period_id'))->first();
        $personalHead = PersonalWorkUnit::with(['personal'])->where([
            ['is_head', '=', true],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', $request->get('work_unit_id')],
            ['is_active', '=', true]])->whereNull('deleted_at')
        ->first();

        $personalHeadWorkPlans =  Skp::with(['skpWorkPlans', 'skpWorkPlans.skpWorkIndicators'])->where([
            ['personal_id', '=', $personalHead->personal_id],
            ['period_id', '=', $personalHead->period_id],
            ['work_unit_id', '=', $personalHead->work_unit_id]])
        ->first();

        
        $personals = null;
        // main root unit 
        if($request->get('work_unit_id') == $work_unit_main->id)
        {
            $personals = DB::select("SELECT p.name, p.id, pwu.id as pwu_id
                    FROM personal_work_units pwu
                    INNER JOIN work_units wu on wu.id = pwu.work_unit_id
                    INNER JOIN personals p on p.id = pwu.personal_id
                    INNER JOIN skps on skps.personal_id = $personalHead->personal_id and skps.period_id = $personalHead->period_id and skps.work_unit_id = $personalHead->work_unit_id
                    INNER JOIN skp_work_plans swp on swp.skp_id = skps.id
                    INNER JOIN skp_work_indicators swi on swi.skp_work_plan_id = swp.id
                    INNER JOIN skp_work_assignments swa on swa.assigned_to_personal_work_unit_id = pwu.id and swa.skp_work_indicator_id = swi.id
                    WHERE pwu.deleted_at is null AND pwu.period_id = $personalHead->period_id AND pwu.is_active = '1' GROUP BY p.name, p.id, pwu.id");
        }
        else
        {
            $personals = DB::select("SELECT p.name, p.id, pwu.id as pwu_id
                    FROM personal_work_units pwu
                    INNER JOIN work_units wu on wu.id = pwu.work_unit_id
                    INNER JOIN personals p on p.id = pwu.personal_id
                    INNER JOIN skps on skps.personal_id = $personalHead->personal_id and skps.period_id = $personalHead->period_id and skps.work_unit_id = $personalHead->work_unit_id
                    INNER JOIN skp_work_plans swp on swp.skp_id = skps.id
                    INNER JOIN skp_work_assignments swa on swa.assigned_to_personal_work_unit_id = pwu.id and swa.skp_work_plan_id = swp.id
                    WHERE pwu.deleted_at is null AND pwu.period_id = $personalHead->period_id AND pwu.is_active = '1' GROUP BY p.name, p.id, pwu.id");
        }

        $matrixReportInfo = new MatrixReportInfo();
        $matrixReportInfo->personalHead = $personalHead->personal->name;
        $matrixReportInfo->personalHeadWorkPlans = $personalHeadWorkPlans;
        $matrixReportInfo->isJPTInfo = false;

        $matrixReportInfo->personalWorkPlanInfos = new Collection;
        if(!empty($matrixReportInfo->personalHeadWorkPlans) && count($matrixReportInfo->personalHeadWorkPlans->skpWorkPlans) > 0)
        {
            // main root unit 
            if($request->get('work_unit_id') == $work_unit_main->id)
            {
                $matrixReportInfo->isJPTInfo = true;
                foreach ($personals as $personal)
                {
                    $personalWorkPlanInfo = new MatrixReportPersonalWorkPlanInfo();
                    $personalWorkPlanInfo->personalInfo = $personal->name;
                    $personalWorkPlanInfo->personalWorkPlans = new Collection;
                    foreach($matrixReportInfo->personalHeadWorkPlans->skpWorkPlans as $skpWorkPlan)
                    {
                        if(!empty($skpWorkPlan->skpWorkIndicators) && count($skpWorkPlan->skpWorkIndicators) > 0)
                        {
                            foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                            {
                                $skpWorkAssignment = SkpWorkAssignment::where([
                                    ['skp_work_indicator_id', '=', $skpWorkIndicator->id],
                                    ['assigned_to_personal_work_unit_id', '=', $personal->pwu_id]])
                                ->first();

                                if(!empty($skpWorkAssignment))
                                {
                                    $skpWorkPlanInterventions = SkpWorkPlan::with(['skpWorkIndicators'])->where([
                                        ['intervention_assignment_id', '=', $skpWorkAssignment->id]])
                                    ->get();
                                    $personalWorkPlanInfo->personalWorkPlans->push($skpWorkPlanInterventions);    
                                }
                                else
                                {
                                    $personalWorkPlanInfo->personalWorkPlans->push(null);
                                }
                            }
                        }
                    }
                    $matrixReportInfo->personalWorkPlanInfos->push($personalWorkPlanInfo);    
                }
            }
            else
            {
                foreach ($personals as $personal)
                {
                    $personalWorkPlanInfo = new MatrixReportPersonalWorkPlanInfo();
                    $personalWorkPlanInfo->personalInfo = $personal->name;
                    $personalWorkPlanInfo->personalWorkPlans = new Collection;
                    foreach($matrixReportInfo->personalHeadWorkPlans->skpWorkPlans as $skpWorkPlan)
                    {
                        $skpWorkAssignment = SkpWorkAssignment::where([
                            ['skp_work_plan_id', '=', $skpWorkPlan->id],
                            ['assigned_to_personal_work_unit_id', '=', $personal->pwu_id]])
                        ->first();

                        if(!empty($skpWorkAssignment))
                        {
                            $skpWorkPlanInterventions = SkpWorkPlan::with(['skpWorkIndicators'])->where([
                                ['intervention_assignment_id', '=', $skpWorkAssignment->id]])
                            ->get();
                            $personalWorkPlanInfo->personalWorkPlans->push($skpWorkPlanInterventions);    
                        }
                        else
                        {
                            $personalWorkPlanInfo->personalWorkPlans->push(null);
                        }
                    }
                    $matrixReportInfo->personalWorkPlanInfos->push($personalWorkPlanInfo);    
                }
            }
        }

        return response()->json($matrixReportInfo);
    }
}
