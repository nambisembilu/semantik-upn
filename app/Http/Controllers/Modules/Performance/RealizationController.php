<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Constants\SkpStatus;
use App\Constants\RealizationStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\AttachmentCategory;
use App\Models\Master\BehaviorCategory;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\RealizationPeriodType;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpBehaviorRealization;
use App\Models\Transaction\SkpPlanRealization;
use App\Models\Transaction\SkpRealization;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Master\RealizationPeriod;
use App\Models\Master\RealizationType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UI\PersonalInfo;
use App\Helpers\Helper;
use PDF;

class RealizationController extends Controller
{

    private $route = "modules.performance.realization.";

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
        $period = Period::find(session('period_id'));

        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])
        ->where([
            ['personal_id', '=', $personal->id],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', session('work_unit_id')],
            ['is_active', '=', true]])->whereNull('deleted_at')
        ->first();

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $personal->id, 'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')])->first();

        $skpRealization = null;
        $mainSkp = null;
        $additionalSkp = null;

        if(!empty($skp))
        {

            $skpRealization = SkpRealization::with(['skp'])
            ->where([
                'skp_id' => $skp->id,
            ])->first();
    
            if(!empty($skpRealization))
            {
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
            }
        }
        
        
        $officerWorkUnit = null;
        $upperOfficerWorkUnit = null;
        if(!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id))
        {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workTitle', 'personal','personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);

            $upperOfficerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workTitle', 'personal','personal.workRank', 'personal.workTitle'])
            ->find($officerWorkUnit->assessor_personal_work_unit_id);
        }
        
        $helper = new Helper();
        $date = Carbon::now();
        $dateSetting = 'Surabaya, '.$helper->dateEnglishtoIndoMMMFormat($date->format('Y-m-d'));
        
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            'officerWorkUnit' => $officerWorkUnit,
            'upperOfficerWorkUnit' => $upperOfficerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'skpRealization' => $skpRealization,
            'dateSetting' => $dateSetting,
            'period' => $period,
            'helper' => $helper
        ];
        return view($this->route . 'index', $data);
    }

    public function create_realization(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();
            $personal = Personal::where('user_id', $user->id)->first();
            $skp = Skp::with(['skpWorkPlans', 'skpBehaviors'])->where(['personal_id' => $personal->id, 'period_id' => session('period_id'),
            'work_unit_id' => session('work_unit_id')])->first();

            $realizationPeriod = RealizationPeriod::join('realization_period_types', 'realization_period_types.id', '=', 'realization_periods.realization_period_type_id')
            ->where(
                [
                    'realization_period_types.name' =>'Tahunan',
                    'realization_periods.period_id' =>session('period_id')
                ])
            ->first(['realization_periods.*']);

            $skpRealizationPeriod = SkpRealization::where([
                'skp_id' => $skp->id, 
                'realization_period_id' => $realizationPeriod->id])->first();
            
            if(empty($skpRealizationPeriod))
            {
                $newSkpRealization = SkpRealization::create([
                    'skp_id' => $skp->id,
                    'realization_period_id' => $realizationPeriod->id,
                    'realization_status' => RealizationStatus::BelumDiajukan
                ]);
    
                $newSkpRealizationId = $newSkpRealization->id;
                
                foreach($skp->skpWorkPlans as $skpWorkPlan)
                {
                    $newSkpPlanRealization = SkpPlanRealization::create([
                        'skp_work_plan_id' => $skpWorkPlan->id,
                        'skp_realization_id' => $newSkpRealizationId,
                    ]);
                }
    
                foreach($skp->skpBehaviors as $skpBehavior)
                {
                    $newSkpBehaviorRealization = SkpBehaviorRealization::create([
                        'skp_behavior_id' => $skpBehavior->id,
                        'skp_realization_id' => $newSkpRealizationId,
                    ]);
                }

                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Realisasi berhasil dibuat',
                ]);
            }   
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

    public function reset_realization(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $skpRealization = SkpRealization::find($request->get('skp_realization_id'));

            SkpBehaviorRealization::where('skp_realization_id', $skpRealization->id)->delete();
            SkpPlanRealization::where('skp_realization_id', $skpRealization->id)->delete();
            $skpRealization->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'Realisasi berhasil direset',
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

    public function apply_realization(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $skpRealization = SkpRealization::with(['skpPlanRealizations'])->find($request->get('skp_realization_id'));

            $emptyRealisation = false;
            foreach($skpRealization->skpPlanRealizations as $skpPlanRealization)
            {
                if(empty($skpPlanRealization->realization) || empty($skpPlanRealization->supporting_evidence))
                {
                    $emptyRealisation = true;
                    break;
                }
            }

            if($emptyRealisation)
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'masih terdapat rencana kerja yang belum terisi realisasinya',
                ]);
            }

            $skpRealization->realization_status = RealizationStatus::BelumDievaluasi;
            $skpRealization->realization_date = Carbon::now();
            $skpRealization->save();

            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'Realisasi berhasil diajukan',
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

    public function cancel_applyment_realization(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $skpRealization = SkpRealization::find($request->get('skp_realization_id'));
            $skpRealization->realization_status = RealizationStatus::BelumDiajukan;
            $skpRealization->realization_date = null;
            $skpRealization->save();

            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'Pengajuan Realisasi berhasil dibatalkan',
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

    public function update_realization_value(Request $request)
    {
        try
        {
            $skpPlanRealization = SkpPlanRealization::find($request->get('skp_plan_realization_id'));
            $skpPlanRealization->realization = $request->get('realization');
            $skpPlanRealization->supporting_evidence = $request->get('supporting_evidence');
            
            $skpPlanRealization->save();
            return response()->json([
                'status' => 1,
                'message' => 'Realisasi dari berhasil diupdate',
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

    public function empty_realization_value(Request $request)
    {
        try
        {
            $skpPlanRealization = SkpPlanRealization::find($request->get('skp_plan_realization_id'));
            $skpPlanRealization->realization = "";
            $skpPlanRealization->supporting_evidence = "";
            $skpPlanRealization->save();
            return response()->json([
                'status' => 1,
                'message' => 'Realisasi dari berhasil dikosongkan',
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

    public function print_evaluation_skp(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $personal->id, 'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')])->first();
        
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
            ->orderBy('id', 'asc')->get();
        
        $additionalSkp = SkpPlanRealization::join('skp_work_plans', 'skp_work_plans.id', '=', 'skp_plan_realizations.skp_work_plan_id')
        ->where(
            [
                'skp_work_plans.is_main' => 0,
                'skp_plan_realizations.skp_realization_id' => $skpRealization->id
            ])
        ->select('skp_plan_realizations.*')
        ->orderBy('id', 'asc')->get();
        
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
 
    	$pdf = PDF::loadview('modules.performance.realization.print-evaluation-skp-pdf',$data);
        return $pdf->stream();
    	//return $pdf->download('print-skp.pdf');
    }
    
    public function print_doc_evaluation_skp(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $personal->id, 'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')])->first();
        
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

        $upperOfficerWorkUnit = new PersonalInfo();
        $upperOfficerWorkUnit->name = $request->get('upper_asessor_name');
        $upperOfficerWorkUnit->workIdNumber = $request->get('upper_asessor_nip');
        $upperOfficerWorkUnit->rank = $request->get('upper_asessor_rank');
        $upperOfficerWorkUnit->position = $request->get('upper_asessor_position');
        $upperOfficerWorkUnit->workUnit = $request->get('upper_asessor_work_unit');
        
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
 
    	$pdf = PDF::loadview('modules.performance.realization.print-doc-evaluation-skp-pdf',$data);
        return $pdf->stream();
    	//return $pdf->download('print-skp.pdf');
    }

}
