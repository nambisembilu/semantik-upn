<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Constants\SkpStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\System\LogError;

class SkpMatrixController extends Controller
{

    private $route = "modules.performance.skp-matrix.";

    private $internalWorkUnits = [];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_assignments(Request $request)
    {
        try
        {
            if(session('role_name') == 'JPT')
            {
                $skpWorkPlan = SkpWorkPlan::with(['skpWorkIndicators'])
                ->where([
                    ['id', '=', $request->get('skp_work_plan_id')]
                    ])
                ->first();
        
                if(!empty($skpWorkPlan) && !empty($skpWorkPlan->skpWorkIndicators) && count($skpWorkPlan->skpWorkIndicators) > 0)
                {
                    foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                    {
                        $skpWorkIndicator->skpWorkAssignments = SkpWorkAssignment::with(['skpWorkPlans','assignedTo.workPosition','assignedTo.workUnit', 'assignedTo.personal.workRank', 'assignedTo.personal.workTitle'])->where([
                            ['skp_work_indicator_id', '=', $skpWorkIndicator->id],
                            ])
                        ->get();
                        
                        if(!empty($skpWorkIndicator->skpWorkAssignments) && count($skpWorkIndicator->skpWorkAssignments) > 0)
                        {
                            $skpWorkAssignmentsFiltered = $skpWorkIndicator->skpWorkAssignments->filter(function ($skpWorkAssignment) {
                                return !empty($skpWorkAssignment->assignedTo);
                            })->values();
            
                            $skpWorkIndicator->skpWorkAssignments = $skpWorkAssignmentsFiltered;

                            if(!empty($skpWorkIndicator->skpWorkAssignments) && count($skpWorkIndicator->skpWorkAssignments) > 0)
                            {
                                foreach($skpWorkIndicator->skpWorkAssignments as $skpWorkAssignment)
                                {
                                    if(empty($skpWorkAssignment->assignedTo->personal))
                                    {
                                        $skpWorkAssignment->assignedTo->personal = Personal::with(['workRank', 'workTitle'])->find($skpWorkAssignment->assignedTo->personal_id);
                                    }
                                }
                            }
                        }
                        
                    }
                }
                return response()->json($skpWorkPlan);
            }
            else
            {
                $skpWorkPlan = SkpWorkPlan::where([
                    ['id', '=', $request->get('skp_work_plan_id')]
                    ])
                ->first();
        
                if(!empty($skpWorkPlan))
                {
                    $skpWorkPlan->skpWorkAssignments = SkpWorkAssignment::with(['skpWorkPlans','assignedTo.workPosition','assignedTo.workUnit', 'assignedTo.personal.workRank', 'assignedTo.personal.workTitle'])->where([
                        ['skp_work_plan_id', '=', $skpWorkPlan->id],
                        ])
                    ->get();

                    
                    if(!empty($skpWorkPlan->skpWorkAssignments) && count($skpWorkPlan->skpWorkAssignments) > 0)
                    {
                        $skpWorkAssignmentsFiltered = $skpWorkPlan->skpWorkAssignments->filter(function ($skpWorkAssignment) {
                                return !empty($skpWorkAssignment->assignedTo);
                            })->values();
        
                        $skpWorkPlan->skpWorkAssignments = $skpWorkAssignmentsFiltered;

                        if(!empty($skpWorkPlan->skpWorkAssignments) && count($skpWorkPlan->skpWorkAssignments) > 0)
                        {
                            foreach($skpWorkPlan->skpWorkAssignments as $skpWorkAssignment)
                            {
                                if(empty($skpWorkAssignment->assignedTo->personal))
                                {
                                    $skpWorkAssignment->assignedTo->personal = Personal::with(['workRank', 'workTitle'])->find($skpWorkAssignment->assignedTo->personal_id);
                                }
                            }
                        }
                    }
                }
                return response()->json($skpWorkPlan);
            }
        }
        catch(\Exception $e)
        {
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
                'message' => $e->getTraceAsString(),
            ]);
        }
        
    }

    
    public function get_assignments_on_person(Request $request)
    {
        if(session('role_name') == 'JPT')
        {
            $skpWorkAssignments = SkpWorkAssignment::with(['skpWorkIndicator','assignedTo.personal'])
            ->where([
                ['assigned_to_personal_work_unit_id', '=', $request->get('personal_work_unit_id')]
                ])
            ->get();
    
            return response()->json($skpWorkAssignments);
        }
        else
        {
            $skpWorkAssignments = SkpWorkAssignment::with(['skpWorkPlan','assignedTo.personal'])
            ->where([
                ['assigned_to_personal_work_unit_id', '=', $request->get('personal_work_unit_id')]
                ])
            ->get();
    
            return response()->json($skpWorkAssignments);
        }
    }

    public function get_internal_assignment_options(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        //fill internal work units
        if(empty($this->internalWorkUnits) || count($this->internalWorkUnits) > 0)
        {
            $workUnit = WorkUnit::find(session('work_unit_id'));
            $this->generate_internal_work_unit($workUnit);
        }

        $internalWorkUnitStr = join(",",$this->internalWorkUnits);
        $pwuConditionStr = "";
        if(!empty($request->get('skp_work_indicator_id')))
        {
            $pwuConditionStr = " where skp_work_indicator_id = ".$request->get('skp_work_indicator_id')."";
        }
        else if(!empty($request->get('skp_work_plan_id')))
        {
            $pwuConditionStr = " where skp_work_plan_id = ".$request->get('skp_work_plan_id')."";
        }
        $assigneeOptions = DB::select("SELECT pwu.id, wp.name as position, wu.name as unit, p.name as name, p.work_id_number, wr.name as rank, wt.name as title
        FROM personal_work_units pwu
        INNER JOIN work_positions wp on wp.id = pwu.work_position_id
        INNER JOIN work_units wu on wu.id = pwu.work_unit_id 
        INNER JOIN personals p on p.id = pwu.personal_id
        INNER JOIN work_ranks wr  on wr.id = p.work_rank_id
        INNER JOIN work_titles wt on wt.id = p.work_title_id
        INNER JOIN roles r on r.id = pwu.role_id
        WHERE pwu.period_id = ".session('period_id')." AND pwu.is_active = '1' AND pwu.deleted_at IS NULL
        AND wu.id IN (".$internalWorkUnitStr.") 
        AND p.id != ".$personal->id."
        AND pwu.id NOT IN (SELECT assigned_to_personal_work_unit_id FROM skp_work_assignments ".$pwuConditionStr.")
        order by wu.id");

        return DataTables::of($assigneeOptions)->make(true);
    }

    private function generate_internal_work_unit($workUnit)
    {
        array_push($this->internalWorkUnits, $workUnit->id);
        $workUnitChilds = WorkUnit::where('parent_id', $workUnit->id)->get();
        if(!empty($workUnitChilds) && count($workUnitChilds) > 0)
        {
            foreach($workUnitChilds as $workUnitChild)
            {
                $this->generate_internal_work_unit($workUnitChild);
            }
        }
    }

    public function create_skp_assignment(Request $request)
    {
        DB::beginTransaction();
        try
        {
            if(!empty($request->get('pwu')) && count($request->get('pwu')) > 0)
            {
                foreach($request->get('pwu') as $pwu)
                {
                    $newSkp = SkpWorkAssignment::create([
                        'skp_work_indicator_id' => $request->get('skp_work_indicator_id'),
                        'skp_work_plan_id' => $request->get('skp_work_plan_id'),
                        'assigned_to_personal_work_unit_id' => $pwu,
                        'is_external' => false,
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 1,
                'message' => 'Penugasan hasil kerja berhasil dibuat',
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

    public function delete_internal_assignment(Request $request)
    {
        try
        {
            $skpWorkPlan = SkpWorkAssignment::where(
                ['skp_work_indicator_id' => $request->get('skp_work_indicator_id'), 
                'skp_work_plan_id' => $request->get('skp_work_plan_id'),
                'assigned_to_personal_work_unit_id' => $request->get('pwu_id')]
            );
            $skpWorkPlan->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Penugasan Hasil kerja berhasil dihapus',
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

        $skp = Skp::with(['skpWorkPlans'=> function($query){
            $query->where('is_main', 1)->orderBy('id', 'asc');
        }])
        ->where(['personal_id' => $personal->id, 
        'period_id' => session('period_id'),
        'work_unit_id' => session('work_unit_id'),
        'application_status' => SkpStatus::SudahDisetujui
        ])->first();
        $data = [
            "route" => $this->route,
            'skp' => $skp
        ];
        return view($this->route . 'index', $data);
    }

    
}
