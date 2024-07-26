<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Helpers\Helper;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\Master\AttachmentCategory;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpWorkAttachment;
use App\Constants\SkpStatus;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\System\LogError;
use Illuminate\Validation\ValidationException;

class SkpApprovalController extends Controller
{

    private $route = "modules.performance.skp-approval.";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function approve_skp(Request $request)
    {
        try
        {
            $personalWorkUnit = PersonalWorkUnit::find($request->get('pwu_id'));

            $skp = Skp::where(
                ['personal_id' => $personalWorkUnit->personal_id, 
                'period_id' => $personalWorkUnit->period_id,
                'work_unit_id' => $personalWorkUnit->work_unit_id]
            )->first();
            $skp->application_status = SkpStatus::SudahDisetujui; 
            $skp->save();
            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
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

    public function approve_bulk_skp(Request $request)
    {
        try
        {
            if(!empty($request->get('pwu_ids')))
            {
                foreach($request->get('pwu_ids') as $pwuId)
                {
                    $personalWorkUnit = PersonalWorkUnit::find($pwuId);
                    $skp = Skp::where(
                        ['personal_id' => $personalWorkUnit->personal_id, 
                        'period_id' => $personalWorkUnit->period_id,
                        'work_unit_id' => $personalWorkUnit->work_unit_id]
                    )->first();

                    if(empty($skp) || $skp->application_status == SkpStatus::BelumDiajukan)
                    {
                        throw ValidationException::withMessages(['error' => 'status skp tidak sesuai']);
                    }
                    $skp->application_status = SkpStatus::SudahDisetujui; 
                    $skp->save();
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
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

    public function reject_skp(Request $request)
    {
        try
        {
            $personalWorkUnit = PersonalWorkUnit::find($request->get('pwu_id'));

            $skp = Skp::where(
                ['personal_id' => $personalWorkUnit->personal_id, 
                'period_id' => $personalWorkUnit->period_id,
                'work_unit_id' => $personalWorkUnit->work_unit_id]
            )->first();
            $skp->application_status = SkpStatus::TidakDisetujui; 
            $skp->save();
            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
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

    public function reject_bulk_skp(Request $request)
    {
        try
        {
            if(!empty($request->get('pwu_ids')))
            {
                foreach($request->get('pwu_ids') as $pwuId)
                {
                    $personalWorkUnit = PersonalWorkUnit::find( $pwuId);
                    $skp = Skp::where(
                        ['personal_id' => $personalWorkUnit->personal_id, 
                        'period_id' => $personalWorkUnit->period_id,
                        'work_unit_id' => $personalWorkUnit->work_unit_id]
                    )->first();

                    if(empty($skp) || $skp->application_status == SkpStatus::BelumDiajukan)
                    {
                        throw ValidationException::withMessages(['error' => 'status skp tidak sesuai']);
                    }
                    $skp->application_status = SkpStatus::TidakDisetujui; 
                    $skp->save();
                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
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

    public function detail($id)
    {
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])->find($id);
        $skp = Skp::with(['skpWorkPlans'])->where(
            ['personal_id' => $personalWorkUnit->personal_id, 
            'period_id' => $personalWorkUnit->period_id,
            'work_unit_id' => $personalWorkUnit->work_unit_id]
        )->first();

        $mainSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 1)->orderBy('id', 'asc');
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['id' => $skp->id])->get();

        $additionalSkp = Skp::with(['skpWorkPlans' => function($query){
            $query->where('is_main', 0)->orderBy('id', 'asc');
        }, 'skpWorkPlans.skpWorkIndicators'])
        ->where(['id' => $skp->id])->get();
        
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
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'helper' => $helper
        ];
        return view($this->route . 'detail', $data);
    }

    public function edit_behavior_note($id)
    {
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit','workPosition','workUnit', 'personal.workRank', 'personal.workTitle'])->find($id);
        $skp = Skp::with(['skpWorkPlans'])->where(
            ['personal_id' => $personalWorkUnit->personal_id, 
            'period_id' => $personalWorkUnit->period_id,
            'work_unit_id' => $personalWorkUnit->work_unit_id]
        )->first();
        
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
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit,
            'helper' => $helper
        ];
        return view($this->route . 'edit-behavior-note', $data);
    }

    public function save_behavior_note(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $user = Auth::user();
            $skp = Skp::find($request->get('id'));

            if(!empty($skp))
            {
                if(!empty($request->get('skp_behavior_id')))
                {
                    foreach($request->get('skp_behavior_id') as $key => $skpBehaviorId)
                    {
                        if(!empty($request->get('behavior_note')[$key]))
                        {
                            $skpBehavior = SkpBehavior::find($skpBehaviorId);
                            $skpBehavior->notes = $request->get('behavior_note')[$key];
                            $skpBehavior->save();
                        }
                    }
                }
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' =>'Peubahan catatan ekpektasi pemimpin berhasil disimpan',
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();

        $pwuSkps = DB::select("SELECT skps.id as skp_id, pwu.id, pwuc.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title,
        CASE
            WHEN skps.id IS NOT NULL THEN skps.application_status
        ELSE 'Belum Dibuat'
        END AS status, per.description as period_description
        FROM personal_work_units pwu
        INNER JOIN personal_work_units pwuc ON pwuc.assessor_personal_work_unit_id = pwu.id
        INNER JOIN work_positions wp on wp.id = pwuc.work_position_id
        INNER JOIN work_units wu on wu.id = pwuc.work_unit_id 
        INNER JOIN personals p on p.id = pwuc.personal_id
        INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
        INNER JOIN work_titles wt on wt.id = p.work_title_id
        INNER JOIN roles r on r.id = pwuc.role_id
        INNER JOIN periods per on per.id = pwu.period_id
        LEFT JOIN skps on skps.personal_id = pwuc.personal_id AND skps.work_unit_id = pwuc.work_unit_id AND skps.period_id = pwuc.period_id
        WHERE pwu.deleted_at is null AND pwu.personal_id = ".$personal->id." AND pwu.period_id = ".session('period_id')." AND pwu.is_active = '1' AND pwu.work_unit_id = ".session('work_unit_id'));
        $data = [
            "route" => $this->route,
            'pwuSkps' => $pwuSkps
        ];
        return view($this->route . 'index', $data);
    }

    
}
