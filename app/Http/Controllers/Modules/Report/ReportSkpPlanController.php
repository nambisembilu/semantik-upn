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
use App\Models\Master\RealizationPeriodType;
use App\Models\Master\AttachmentTextTemplate;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpWorkAttachment;
use App\Models\Transaction\SkpWorkIndicator;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\UI\PersonalInfo;
use App\Models\UI\PrintSkpInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class ReportSkpPlanController extends Controller
{

    private $route = "modules.report.skp-plan.";
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
            $pwuSkps = DB::select("SELECT skps.id as skp_id, pwu.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
            CASE
                WHEN skps.id IS NOT NULL THEN skps.application_status
            ELSE 'Belum Dibuat'
            END AS status, per.description as period_description
            FROM personal_work_units pwu
            INNER JOIN work_positions wp on wp.id = pwu.work_position_id
            INNER JOIN work_units wu on wu.id = pwu.work_unit_id 
            INNER JOIN personals p on p.id = pwu.personal_id
            INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
            INNER JOIN work_titles wt on wt.id = p.work_title_id
            INNER JOIN roles r on r.id = pwu.role_id
            INNER JOIN periods per on per.id = pwu.period_id
            LEFT JOIN skps on skps.personal_id = pwu.personal_id AND skps.work_unit_id = pwu.work_unit_id 
            WHERE pwu.deleted_at is null AND pwu.period_id = ".session('period_id')." AND pwu.is_active = '1'");    
        }
        else if(session('role_name') == "SuperadminUK")
        {
            $pwuSkps = DB::select("SELECT skps.id as skp_id, pwu.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
            CASE
                WHEN skps.id IS NOT NULL THEN skps.application_status
            ELSE 'Belum Dibuat'
            END AS status, per.description as period_description
            FROM personal_work_units pwu
            INNER JOIN work_positions wp on wp.id = pwu.work_position_id
            INNER JOIN work_units wu on wu.id = pwu.work_unit_id 
            INNER JOIN personals p on p.id = pwu.personal_id
            INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
            INNER JOIN work_titles wt on wt.id = p.work_title_id
            INNER JOIN roles r on r.id = pwu.role_id
            INNER JOIN periods per on per.id = pwu.period_id
            LEFT JOIN skps on skps.personal_id = pwu.personal_id AND skps.work_unit_id = pwu.work_unit_id 
            WHERE pwu.deleted_at is null AND pwu.period_id = ".session('period_id')." AND pwu.is_active = '1' AND pwu.root_work_unit_id = ".session('work_unit_id'));    
        }
        
        $data = [
            "route" => $this->route,
            'pwuSkps' => $pwuSkps,
            'period' => $period
        ];
        return view($this->route . 'index', $data);
    }

    public function get_print_skp_plan_data(Request $request)
    {
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])
        ->find($request->get('pwu_id'));

        $officerWorkUnit = null;
        if(!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id))
        {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workPosition','personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }

        $helper = new Helper();
        $date = Carbon::now();
        $dateSetting = 'Surabaya, '.$helper->dateEnglishtoIndoMMMFormat($date->format('Y-m-d'));

        $printSkpInfo = new PrintSkpInfo();
        $printSkpInfo->personalWorkUnit = $personalWorkUnit;
        $printSkpInfo->officerWorkUnit = $officerWorkUnit;
        $printSkpInfo->personalGrade = !empty($personalWorkUnit) ? $helper->getGradeValue($personalWorkUnit->personal->employee_type, $personalWorkUnit->personal->workRank->grade_name, $personalWorkUnit->personal->workRank->name) : '-' ;
        $printSkpInfo->officerGrade = !empty($officerWorkUnit) ? $helper->getGradeValue($officerWorkUnit->personal->employee_type, $officerWorkUnit->personal->workRank->grade_name, $officerWorkUnit->personal->workRank->name) : '-' ;
        $printSkpInfo->dateSetting = $dateSetting;

        return response()->json($printSkpInfo);
    }

    public function print_skp(Request $request)
    {
        $pwu = PersonalWorkUnit::find($request->get('pwu_id'));

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

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $pwu->personal_id, 'period_id' => $pwu->period_id,
        'work_unit_id' => $pwu->work_unit_id])->first();

        $mainSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 1);
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $pwu->personal_id, 
        'period_id' => $pwu->period_id,
        'work_unit_id' => $pwu->work_unit_id
        ])->orderBy('id', 'asc')->get();

        $additionalSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 0);
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $pwu->personal_id, 
        'period_id' => $pwu->period_id,
        'work_unit_id' => $pwu->work_unit_id
        ])->orderBy('id', 'asc')->get();
        
        $periodName = $request->get('period_name');

        $attachmentCategories = null;
        $skpBehaviors = null;

        if(!empty($skp))
        {
            $attachmentCategories = AttachmentCategory::get();
            foreach($attachmentCategories as $attachmentCategory)
            {
                $attachmentCategory->skpWorkAttachments = SkpWorkAttachment::where([
                    ['skp_id' , '=', $skp->id],
                    ['attachment_category_id' , '=', $attachmentCategory->id]
                ])->get();
            }
            $skpBehaviors = SkpBehavior::with(['behaviorCategory.behaviorCriterias'])->where('skp_id', $skp->id)->orderBy('id', 'asc')->get();
        }

        $dateSetting = $request->get('date_setting');
        $helper = new Helper();
        
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'periodName' => $periodName,
            'dateSetting' => $dateSetting,
            'helper' => $helper
        ];
 
    	$pdf = PDF::loadview('modules.performance.skp-plan.print-skp-pdf',$data);
        return $pdf->stream();
    	//return $pdf->download('print-skp.pdf');
    }
}
