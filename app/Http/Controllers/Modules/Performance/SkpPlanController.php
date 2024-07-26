<?php

namespace App\Http\Controllers\Modules\Performance;

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
use App\Models\Transaction\EmploymentAgreement;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpPlanRealization;
use App\Models\Transaction\SkpWorkAttachment;
use App\Models\Transaction\SkpWorkIndicator;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\UI\PersonalInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class SkpPlanController extends Controller
{

    private $route = "modules.performance.skp-plan.";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_intervention_assignment(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $workIndicatorAssignments = DB::select("select swa.*, 
                                CASE
                                    WHEN swi.title IS NOT NULL THEN swi.title
                                ELSE swp.title
                                END AS title
                                from skp_work_assignments swa 
                                left join skp_work_indicators swi on swi.id = swa.skp_work_indicator_id
                                left join skp_work_plans swp on swp.id = swa.skp_work_plan_id
                                join personal_work_units pwu on pwu.id = swa.assigned_to_personal_work_unit_id
                                where pwu.deleted_at is null AND pwu.personal_id = ".$personal->id." and pwu.work_unit_id = ".session('work_unit_id')." and pwu.period_id = ".session('period_id')."
                                order by swa.id");
        return response()->json($workIndicatorAssignments);
    }

    public function get_employment_agreement(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $employmentAgreements = DB::select("select ea.*
                                from employment_agreements ea ");
        return response()->json($employmentAgreements);
    }

    public function get_intervention_assignment_assigner(Request $request)
    {
        $skpWorkAssignment = SkpWorkAssignment::find($request->get('swa_id'));

        $personal = null;
        if(!empty($skpWorkAssignment))
        {
            if(!empty($skpWorkAssignment->skp_work_indicator_id))
            {
                $personal = DB::select("select wp.*
                from skp_work_indicators swi 
                join skp_work_plans swp on swp.id = swi.skp_work_plan_id
                join skps on skps.id = swp.skp_id
                join personal_work_units pwu on pwu.personal_id = skps.personal_id and pwu.work_unit_id = skps.work_unit_id
                join work_positions wp on wp.id = pwu.work_position_id
                where pwu.deleted_at is null AND swi.id=".$skpWorkAssignment->skp_work_indicator_id);
            }
            else if(!empty($skpWorkAssignment->skp_work_plan_id))
            {
                $personal = DB::select("select wp.*
                from skp_work_plans swp
                join skps on skps.id = swp.skp_id
                join personal_work_units pwu on pwu.personal_id = skps.personal_id and pwu.work_unit_id = skps.work_unit_id
                join work_positions wp on wp.id = pwu.work_position_id
                where pwu.deleted_at is null AND swp.id=".$skpWorkAssignment->skp_work_plan_id);
            }
        }
        return response()->json($personal);
    }

    public function get_work_attachment_by_id_and_category(Request $request)
    {
        $skpWorkAttachments = SkpWorkAttachment::where([
                ['skp_id', '=', $request->get('skp_id')],
                ['attachment_category_id', '=', $request->get('attachment_category_id')],
                ])
            ->get();
        return response()->json($skpWorkAttachments);
    }
    
    public function get_attachment_text_template_by_category(Request $request)
    {
        $attachmentTextTemplates = AttachmentTextTemplate::where([
                ['attachment_category_id', '=', $request->get('attachment_category_id')],
                ])
            ->get();
        return response()->json($attachmentTextTemplates);
    }

    public function get_work_plan_by_id(Request $request)
    {
        $skpWorkAttachments = SkpWorkPlan::with(['skpWorkIndicators'])
        ->where([
                ['id', '=', $request->get('skp_plan_id')],
                ])
            ->first();
        return response()->json($skpWorkAttachments);
    }

    public function delete_work_plan(Request $request)
    {
        try
        {
            $skpWorkPlan = SkpWorkPlan::find($request->get('skp_work_plan_id'));

            $skpWorkAssignments = SkpWorkAssignment::where('skp_work_plan_id', $skpWorkPlan->id)->get();
            if(!empty($skpWorkAssignments) && count($skpWorkAssignments) > 0)
            {
                foreach($skpWorkAssignments as $skpWorkAssignment)
                {
                    $skpWorkPlanUpdates = SkpWorkPlan::where('intervention_assignment_id', $skpWorkAssignment->id)->get();
                    if(!empty($skpWorkPlanUpdates) && count($skpWorkPlanUpdates) > 0)
                    {
                        foreach($skpWorkPlanUpdates as $skpWorkPlanUpdate)
                        {
                            $skpWorkPlanUpdate->intervention_assignment_id = null;
                            $skpWorkPlanUpdate->save();
                        }
                    }
                    $skpWorkAssignment->delete();
                }
            }

            $skpPlanRealization = SkpPlanRealization::where('skp_work_plan_id', $skpWorkPlan->id)->first();
            if(!empty($skpPlanRealization))
            {
                $skpPlanRealization->delete();
            }

            $skpWorkPlan->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Hasil kerja berhasil dihapus',
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

    public function delete_get_task_from(Request $request)
    {
        try
        {
            $skpWorkPlan = SkpWorkPlan::find($request->get('skp_work_plan_id'));
            $skpWorkPlan->get_task_from = "";
            $skpWorkPlan->save();
            return response()->json([
                'status' => 1,
                'message' => 'Penugasan dari berhasil dihapus',
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

    public function create_skp(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();
            $personal = Personal::where('user_id', $user->id)->first();
    
            $skpPeriod = Skp::where([
                'personal_id' => $personal->id, 
                'period_id' => session('period_id'),
                'work_unit_id' => session('work_unit_id'),
                'realization_period_type_id' => RealizationPeriodType::where('name', 'Tahunan')->first()->id])->first();

            if(empty($skpPeriod))
            {
                $newSkp = Skp::create([
                    'personal_id' => $personal->id,
                    'period_id' => session('period_id'),
                    'work_unit_id' => session('work_unit_id'),
                    'application_status' => SkpStatus::BelumDiajukan,
                    'realization_period_type_id' => RealizationPeriodType::where('name', 'Tahunan')->first()->id
                ]);
    
                $newSkpId = $newSkp->id;
                
                $behaviorCategories = BehaviorCategory::get();
                $attachmentCategories = AttachmentCategory::get();
        
                if(!empty($behaviorCategories) && count($behaviorCategories) > 0)
                {
                    foreach($behaviorCategories as $behaviorCategory)
                    {
                        $newSkp = SkpBehavior::create([
                            'skp_id' => $newSkpId,
                            'behavior_category_id' => $behaviorCategory->id,
                        ]);
                    }
                }
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'SKP berhasil dibuat',
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

    public function apply_skp(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $skp = Skp::find($request->get('skp_id'));

            $emptyAttachment = false;
            $attachmentCategories = AttachmentCategory::get();
            if(!empty($attachmentCategories) && count($attachmentCategories) > 0)
            {
                foreach($attachmentCategories as $attachmentCategory)
                {
                    $skpWorkAttachments = SkpWorkAttachment::where([
                        ['skp_id', '=', $request->get('skp_id')],
                        ['attachment_category_id', '=', $attachmentCategory->id],
                        ])
                    ->get();
                    if(empty($skpWorkAttachments) || count($skpWorkAttachments) == 0)
                    {
                        $emptyAttachment = true;
                        break;
                    }
                }
            }
            
            if($emptyAttachment)
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'Masih ada lampiran yang belum diisi',
                ]);
            }

            $workPlans = SkpWorkPlan::with(['skpWorkIndicators'])->where([
                ['skp_id', '=', $request->get('skp_id')]
                ])
            ->get();

            if(empty($workPlans) || count($workPlans) == 0)
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'rencana kerja tidak boleh kosong',
                ]);
            }

            $emptyIndicator = false;
            $emptyTaskFrom = false;
            foreach($workPlans as $workPlan)
            {
                if(empty($workPlan->skpWorkIndicators) || count($workPlan->skpWorkIndicators) == 0)
                {
                    $emptyIndicator = true;
                    break;
                }

                if(empty($workPlan->get_task_from))
                {
                    $emptyTaskFrom = true;
                    break;
                }
            }

            if($emptyIndicator)
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'Masih ada rencana kerja yang belum diisi indikator nya',
                ]);
            }

            if($emptyTaskFrom)
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'Masih ada rencana kerja yang belum diisi penugasan dari nya',
                ]);
            }

            $skp->application_status = SkpStatus::BelumDisetujui;
                $skp->application_date = Carbon::now();
                $skp->save();
    
            DB::commit();
                
            return response()->json([
                'status' => 1,
                'message' => 'SKP berhasil diajukan',
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

    public function reset_skp(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();

            $workAssignmentFromPlans = DB::table('skp_work_assignments')
            ->leftJoin('skp_work_plans', 'skp_work_plans.id', '=', 'skp_work_assignments.skp_work_plan_id')
            ->leftJoin('skps', 'skps.id', '=', 'skp_work_plans.skp_id')
            ->where('skps.id', $request->get('skp_id'))->get();

            $workAssignmentFromIndicators = DB::table('skp_work_assignments')
            ->leftJoin('skp_work_indicators', 'skp_work_indicators.id', '=', 'skp_work_assignments.skp_work_indicator_id')
            ->leftJoin('skp_work_plans', 'skp_work_plans.id', '=', 'skp_work_indicators.skp_work_plan_id')
            ->leftJoin('skps', 'skps.id', '=', 'skp_work_plans.skp_id')
            ->where('skps.id', $request->get('skp_id'))->get();

            if(!empty($workAssignmentFromPlans) || !empty($workAssignmentFromIndicators))
            {
                return response()->json([
                    'status' => 0,
                    'message' => 'SKP tidak dapat direset, SKP sudah di assign ke tim kerja',
                ]);
            }
            SkpBehavior::where('skp_id', $request->get('skp_id'))->delete();

            DB::table('skp_work_indicators')
            ->leftJoin('skp_work_plans', 'skp_work_plans.id', '=', 'skp_work_indicators.skp_work_plan_id')
            ->leftJoin('skps', 'skps.id', '=', 'skp_work_plans.skp_id')
            ->where('skps.id', $request->get('skp_id'))
            ->delete();

            SkpWorkPlan::where('skp_id', $request->get('skp_id'))->delete();
            SkpWorkAttachment::where('skp_id', $request->get('skp_id'))->delete();
            
            SKp::where('id', $request->get('skp_id'))->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'SKP berhasil direset',
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

    public function cancel_applyment_skp(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $skp = Skp::find($request->get('skp_id'));
            $skp->application_status = SkpStatus::BelumDiajukan;
            $skp->application_date = null;
            $skp->save();

            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => $skp,
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

    public function create_cascading_skp_work_indicator_plan(Request $request)
    {
        $customMessages = [
            'intervention_indicator.required'=> 'Intervensi hasil kerja harus diisi',
            'cascading_work_plan_title.required'=> 'Hasil kerja harus diisi',
            'cascading_work_plan_indicator.required'   => 'Indikator kerja harus diisi',
            'cascading_ork_plan_indicator.*.required'  => 'Indikator kerja harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'intervention_indicator'		    	=> 'required',
            'cascading_work_plan_title'			=> 'required',
            'cascading_work_plan_indicator'   => 'required|array|min:1',
            'cascading_work_plan_indicator.*'  => 'required|string|distinct|min:1',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $user = Auth::user();
                $personal = Personal::where('user_id', $user->id)->first();

                $skpWorkIntervention = SkpWorkAssignment::find($request->get('intervention_indicator'));
                if($request->get('cascading_skp_plan_id') > 0)
                {

                    $skpWorkPlan = SkpWorkPlan::find($request->get('cascading_skp_plan_id'));
                    $skpWorkPlan->intervention_assignment_id = $skpWorkIntervention->id; 
                    $skpWorkPlan->title = $request->get('cascading_work_plan_title');
                    $skpWorkPlan->save();

                    $skpWorkIndicatorDbs = SkpWorkIndicator::where('skp_work_plan_id', $skpWorkPlan->id)->get();
                    $workPlanIndicators = $request->get('cascading_work_plan_indicator');
                    $workPlanIndicatorIds = $request->get('cascading_work_plan_indicator_id');
            
                    if($workPlanIndicators && count($workPlanIndicators))
                    {
                        for($i = 0; $i < count($workPlanIndicators); $i++) 
                        {
                            if($workPlanIndicatorIds[$i] == 0)
                            {
                                $newSkpIndicator = SkpWorkIndicator::create([
                                    'skp_work_plan_id' => $skpWorkPlan->id,
                                    'title' => $workPlanIndicators[$i],
                                ]);
                            }
                            else
                            {
                                $skpWorkIndicatorFound = SkpWorkIndicator::find($workPlanIndicatorIds[$i]);
                                $skpWorkIndicatorFound->title = $workPlanIndicators[$i];
                                $skpWorkIndicatorFound->save();
                            }
                        }
                    }
                    if($skpWorkIndicatorDbs && count($skpWorkIndicatorDbs))
                    {
                        foreach($skpWorkIndicatorDbs as $skpWorkIndicatorDb)
                        {
                            $deletedObj = array_filter($workPlanIndicatorIds, function($workPlanIndicatorId) use ($skpWorkIndicatorDb){
                                return ($workPlanIndicatorId > 0 && $workPlanIndicatorId == $skpWorkIndicatorDb->id);
                            });

                            if(!$deletedObj || count($deletedObj) == 0)
                            {
                                $skpWorkAssignments = SkpWorkAssignment::where('skp_work_indicator_id', $deletedObj->id)->get();
                                if(!empty($skpWorkAssignments) && count($skpWorkAssignments) > 0)
                                {
                                    foreach($skpWorkAssignments as $skpWorkAssignment)
                                    {
                                        $skpWorkPlanUpdates = SkpWorkPlan::where('intervention_assignment_id', $skpWorkAssignment->id)->get();
                                        if(!empty($skpWorkPlanUpdates) && count($skpWorkPlanUpdates) > 0)
                                        {
                                            foreach($skpWorkPlanUpdates as $skpWorkPlanUpdate)
                                            {
                                                $skpWorkPlanUpdate->intervention_assignment_id = null;
                                                $skpWorkPlanUpdate->save();
                                            }
                                        }
                                        $skpWorkAssignment->delete();
                                    }
                                    $skpWorkIndicatorDb->delete();
                                }
                            }
                        }
                    }
                }
                else
                {
                    $newSkpWorkPlan = SkpWorkPlan::create([
                        'skp_id' => $request->get('skp_id'),
                        'intervention_assignment_id' => $skpWorkIntervention->id,
                        'title' => $request->get('cascading_work_plan_title'),
                        'is_main' => true
                    ]);
        
                    $newSkpWorkPlanId = $newSkpWorkPlan->id;
                    
                    $workPlanIndicators = $request->get('cascading_work_plan_indicator');
            
                    foreach($workPlanIndicators as $workPlanIndicator)
                    {
                        $newSkp = SkpWorkIndicator::create([
                            'skp_work_plan_id' => $newSkpWorkPlanId,
                            'title' => $workPlanIndicator,
                        ]);
                    }
                }
                
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Rencana dan Hasil Kerja berhasil ditambahkan',
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
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
    }

    public function create_skp_work_indicator_plan(Request $request)
    {
        $customMessages = [
            'work_plan_title.required'=> 'Hasil kerja harus diisi',
            'work_plan_indicator.required'   => 'Indikator kerja harus diisi',
            'work_plan_indicator.*.required'  => 'Indikator kerja harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'work_plan_title'			=> 'required',
            'work_plan_indicator'   => 'required|array|min:1',
            'work_plan_indicator.*'  => 'required|string|distinct|min:1',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $user = Auth::user();
                $personal = Personal::where('user_id', $user->id)->first();

                if($request->get('skp_plan_id') > 0)
                {
                    $skpWorkPlan = SkpWorkPlan::find($request->get('skp_plan_id'));
                    $skpWorkPlan->title = $request->get('work_plan_title');
                    $skpWorkPlan->is_main = $request->get('is_main_checked');
                    $skpWorkPlan->save();

                    $skpWorkIndicatorDbs = SkpWorkIndicator::where('skp_work_plan_id', $skpWorkPlan->id)->get();
                    $workPlanIndicators = $request->get('work_plan_indicator');
                    $workPlanIndicatorIds = $request->get('work_plan_indicator_id');
            
                    if($workPlanIndicators && count($workPlanIndicators))
                    {
                        for ($i = 0; $i < count($workPlanIndicators); $i++) 
                        {
                            if($workPlanIndicatorIds[$i] == 0)
                            {
                                $newSkpIndicator = SkpWorkIndicator::create([
                                    'skp_work_plan_id' => $skpWorkPlan->id,
                                    'title' => $workPlanIndicators[$i],
                                ]);
                            }
                            else
                            {
                                $skpWorkIndicatorFound = SkpWorkIndicator::find($workPlanIndicatorIds[$i]);
                                $skpWorkIndicatorFound->title = $workPlanIndicators[$i];
                                $skpWorkIndicatorFound->save();
                            }
                        }
                    }
                    if($skpWorkIndicatorDbs && count($skpWorkIndicatorDbs))
                    {
                        foreach($skpWorkIndicatorDbs as $skpWorkIndicatorDb)
                        {
                            $deletedObj = array_filter($workPlanIndicatorIds, function($workPlanIndicatorId) use ($skpWorkIndicatorDb){
                                return ($workPlanIndicatorId > 0 && $workPlanIndicatorId == $skpWorkIndicatorDb->id);
                            });

                            if(!$deletedObj || count($deletedObj) == 0)
                            {
                                $skpWorkAssignments = SkpWorkAssignment::where('skp_work_indicator_id', $deletedObj->id)->get();
                                if(!empty($skpWorkAssignments) && count($skpWorkAssignments) > 0)
                                {
                                    foreach($skpWorkAssignments as $skpWorkAssignment)
                                    {
                                        $skpWorkPlanUpdates = SkpWorkPlan::where('intervention_assignment_id', $skpWorkAssignment->id)->get();
                                        if(!empty($skpWorkPlanUpdates) && count($skpWorkPlanUpdates) > 0)
                                        {
                                            foreach($skpWorkPlanUpdates as $skpWorkPlanUpdate)
                                            {
                                                $skpWorkPlanUpdate->intervention_assignment_id = null;
                                                $skpWorkPlanUpdate->save();
                                            }
                                        }
                                        $skpWorkAssignment->delete();
                                    }
                                    $skpWorkIndicatorDb->delete();
                                }
                            }
                        }
                    }
                }
                else
                {
                    $newSkpWorkPlan = SkpWorkPlan::create([
                        'skp_id' => $request->get('skp_id'),
                        'title' => $request->get('work_plan_title'),
                        'is_main' => $request->get('is_main_checked')
                    ]);
        
                    $newSkpWorkPlanId = $newSkpWorkPlan->id;
                    
                    $workPlanIndicators = $request->get('work_plan_indicator');
            
                    foreach($workPlanIndicators as $workPlanIndicator)
                    {
                        $newSkp = SkpWorkIndicator::create([
                            'skp_work_plan_id' => $newSkpWorkPlanId,
                            'title' => $workPlanIndicator,
                        ]);
                    }
                }
                
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Rencana dan Hasil Kerja berhasil ditambahkan',
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
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
    }


    public function create_cascading_skp_work_indicator_plan_for_jpt(Request $request)
    {
        $customMessages = [
            'intervention_indicator.required'=> 'Intervensi hasil kerja harus diisi',
            'cascading_work_plan_title.required'=> 'Hasil kerja harus diisi',
            'cascading_work_plan_indicator.required'   => 'Indikator kerja harus diisi',
            'cascading_ork_plan_indicator.*.required'  => 'Indikator kerja harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'intervention_indicator'		    	=> 'required',
            'cascading_work_plan_title'			=> 'required',
            'cascading_work_plan_indicator'   => 'required|array|min:1',
            'cascading_work_plan_indicator.*'  => 'required|string|distinct|min:1',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $user = Auth::user();
                $personal = Personal::where('user_id', $user->id)->first();

                $skpWorkIntervention = EmploymentAgreement::find($request->get('intervention_indicator'));
                if($request->get('cascading_skp_plan_id') > 0)
                {

                    $skpWorkPlan = SkpWorkPlan::find($request->get('cascading_skp_plan_id'));
                    $skpWorkPlan->employment_agreement_id = $skpWorkIntervention->id; 
                    $skpWorkPlan->title = $request->get('cascading_work_plan_title');
                    $skpWorkPlan->save();

                    $skpWorkIndicatorDbs = SkpWorkIndicator::where('skp_work_plan_id', $skpWorkPlan->id)->get();
                    $workPlanIndicators = $request->get('cascading_work_plan_indicator');
                    $workPlanIndicatorIds = $request->get('cascading_work_plan_indicator_id');
            
                    if($workPlanIndicators && count($workPlanIndicators))
                    {
                        for($i = 0; $i < count($workPlanIndicators); $i++) 
                        {
                            if($workPlanIndicatorIds[$i] == 0)
                            {
                                $newSkpIndicator = SkpWorkIndicator::create([
                                    'skp_work_plan_id' => $skpWorkPlan->id,
                                    'title' => $workPlanIndicators[$i],
                                ]);
                            }
                            else
                            {
                                $skpWorkIndicatorFound = SkpWorkIndicator::find($workPlanIndicatorIds[$i]);
                                $skpWorkIndicatorFound->title = $workPlanIndicators[$i];
                                $skpWorkIndicatorFound->save();
                            }
                        }
                    }
                    if($skpWorkIndicatorDbs && count($skpWorkIndicatorDbs))
                    {
                        foreach($skpWorkIndicatorDbs as $skpWorkIndicatorDb)
                        {
                            $deletedObj = array_filter($workPlanIndicatorIds, function($workPlanIndicatorId) use ($skpWorkIndicatorDb){
                                return ($workPlanIndicatorId > 0 && $workPlanIndicatorId == $skpWorkIndicatorDb->id);
                            });

                            if(!$deletedObj || count($deletedObj) == 0)
                            {
                                $skpWorkAssignments = SkpWorkAssignment::where('skp_work_indicator_id', $deletedObj->id)->get();
                                if(!empty($skpWorkAssignments) && count($skpWorkAssignments) > 0)
                                {
                                    foreach($skpWorkAssignments as $skpWorkAssignment)
                                    {
                                        $skpWorkPlanUpdates = SkpWorkPlan::where('intervention_assignment_id', $skpWorkAssignment->id)->get();
                                        if(!empty($skpWorkPlanUpdates) && count($skpWorkPlanUpdates) > 0)
                                        {
                                            foreach($skpWorkPlanUpdates as $skpWorkPlanUpdate)
                                            {
                                                $skpWorkPlanUpdate->intervention_assignment_id = null;
                                                $skpWorkPlanUpdate->save();
                                            }
                                        }
                                        $skpWorkAssignment->delete();
                                    }
                                    $skpWorkIndicatorDb->delete();
                                }
                            }
                        }
                    }
                }
                else
                {
                    $newSkpWorkPlan = SkpWorkPlan::create([
                        'skp_id' => $request->get('skp_id'),
                        'employment_agreement_id' => $skpWorkIntervention->id,
                        'title' => $request->get('cascading_work_plan_title'),
                        'is_main' => true
                    ]);
        
                    $newSkpWorkPlanId = $newSkpWorkPlan->id;
                    
                    $workPlanIndicators = $request->get('cascading_work_plan_indicator');
            
                    foreach($workPlanIndicators as $workPlanIndicator)
                    {
                        $newSkp = SkpWorkIndicator::create([
                            'skp_work_plan_id' => $newSkpWorkPlanId,
                            'title' => $workPlanIndicator,
                        ]);
                    }
                }
                
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Rencana dan Hasil Kerja berhasil ditambahkan',
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
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
    }

    public function create_skp_work_attachment(Request $request)
    {
        $customMessages = [
            'work_attachment.required'   => 'Lampiran harus diisi',
            'work_attachment.*.required'  => 'Lampiran harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'work_attachment'   => 'required|array|min:1',
            'work_attachment.*'  => 'required|string|distinct|min:1',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $workAttachments = $request->get('work_attachment');
                $skpWorkAttachmentDbs = SkpWorkAttachment::where(['skp_id' => $request->get('skp_id'), 
                'attachment_category_id' => $request->get('attachment_category_id')])->get();

                foreach($workAttachments as $workAttachment)
                {
                    $skpWorkIndicatorFound = SkpWorkAttachment::where(['skp_id' => $request->get('skp_id'), 
                    'attachment_category_id' => $request->get('attachment_category_id'),
                    'description' => $workAttachment])->first();
                    if(!$skpWorkIndicatorFound)
                    {
                        $newSkpWorkAtachment = SkpWorkAttachment::create([
                            'skp_id' => $request->get('skp_id'),
                            'attachment_category_id' => $request->get('attachment_category_id'),
                            'description' => $workAttachment
                        ]);
                    }
                }

                if($skpWorkAttachmentDbs && count($skpWorkAttachmentDbs))
                {
                    foreach($skpWorkAttachmentDbs as $skpWorkAttachmentDb)
                    {
                        $deletedObj = array_filter($workAttachments, function($workAttachment) use ($skpWorkAttachmentDb){
                            return ($workAttachment == $skpWorkAttachmentDb->description);
                        });

                        if(!$deletedObj || count($deletedObj) == 0)
                        {
                            $skpWorkAttachmentDb->delete();
                        }
                    }
                }
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Lampiran berhasil ditambahkan',
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
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
    }

    public function update_get_task_from(Request $request)
    {
        $customMessages = [
            'get_task_from.required'=> 'Penugasan kerja harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'get_task_from'			=> 'required',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $skpWorkPlan = SkpWorkPlan::find($request->get('skp_work_plan_id'));
                $skpWorkPlan->get_task_from = $request->get('get_task_from');
                $skpWorkPlan->save();
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => 'Penugasan dari berhasil di update',
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
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
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

        $mainSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 1)->orderBy('id', 'asc');
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $personal->id, 
        'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')
        ])->get();

        $additionalSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 0)->orderBy('id', 'asc');
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $personal->id, 
        'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')
        ])->get();
        
        $assessingOfficer = null;
       

        $officerWorkUnit = null;
        if(!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id))
        {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workUnit','workTitle', 'personal','personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }

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

        $helper = new Helper();
        $date = Carbon::now();
        $dateSetting = 'Surabaya, '.$helper->dateEnglishtoIndoMMMFormat($date->format('Y-m-d'));
        
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'dateSetting' => $dateSetting,
            'period' => $period,
            'helper' => $helper
        ];

        if(session("role_name") == 'JPT')
        {
            return view($this->route . 'index-jpt', $data);
        }
        else
        {
            return view($this->route . 'index', $data);
        }
    }

    public function print_skp(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

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

        $skp = Skp::with(['skpWorkPlans'])->where(['personal_id' => $personal->id, 'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')])->first();

        $mainSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 1);
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $personal->id, 
        'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')
        ])->orderBy('id', 'asc')->get();

        $additionalSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 0);
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['personal_id' => $personal->id, 
        'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id')
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
