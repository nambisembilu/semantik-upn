<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Constants\SkpStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\AttachmentCategory;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpWorkAttachment;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmployeeAssessMonthController extends Controller
{
    private $route = "modules.performance.emp-assess-month.";

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
        try {
            $personalWorkUnit = PersonalWorkUnit::find($request->get('pwu_id'));
            $skp = Skp::where([
                'personal_id' => $personalWorkUnit->personal_id,
                'period_id' => $personalWorkUnit->period_id,
                'work_unit_id' => $personalWorkUnit->work_unit_id
            ])->first();
            $skp->application_status = SkpStatus::SudahDisetujui;
            $skp->save();
            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
            ]);
        } catch (\Exception $e) {
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
        try {
            if (!empty($request->get('pwu_ids'))) {
                foreach ($request->get('pwu_ids') as $pwuId) {
                    $personalWorkUnit = PersonalWorkUnit::find($pwuId);
                    $skp = Skp::where([
                        'personal_id' => $personalWorkUnit->personal_id,
                        'period_id' => $personalWorkUnit->period_id,
                        'work_unit_id' => $personalWorkUnit->work_unit_id
                    ])->first();
                    if (empty($skp) || $skp->application_status == SkpStatus::BelumDiajukan) {
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
        } catch (\Exception $e) {
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
        try {
            $personalWorkUnit = PersonalWorkUnit::find($request->get('pwu_id'));
            $skp = Skp::where([
                'personal_id' => $personalWorkUnit->personal_id,
                'period_id' => $personalWorkUnit->period_id,
                'work_unit_id' => $personalWorkUnit->work_unit_id
            ])->first();
            $skp->application_status = SkpStatus::TidakDisetujui;
            $skp->save();
            return response()->json([
                'status' => 1,
                'message' => 'Proses persetujuan berhasil',
            ]);
        } catch (\Exception $e) {
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
        try {
            if (!empty($request->get('pwu_ids'))) {
                foreach ($request->get('pwu_ids') as $pwuId) {
                    $personalWorkUnit = PersonalWorkUnit::find($pwuId);
                    $skp = Skp::where([
                        'personal_id' => $personalWorkUnit->personal_id,
                        'period_id' => $personalWorkUnit->period_id,
                        'work_unit_id' => $personalWorkUnit->work_unit_id
                    ])->first();
                    if (empty($skp) || $skp->application_status == SkpStatus::BelumDiajukan) {
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
        } catch (\Exception $e) {
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
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit', 'workPosition', 'workUnit', 'personal.workRank', 'personal.workTitle'])->where([
            ['personal_id', '=', $personal->id],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', session('work_unit_id')],
            ['is_active', '=', true]
        ])->first();
        $skp = Skp::with(['skpWorkPlans'])->where(['id' => $id])->first();
        $mainSkp = Skp::with([
            'skpWorkPlans' => function ($query) {
                $query->where('is_main', 1)->orderBy('id', 'asc');
            },
            'skpWorkPlans.skpWorkIndicators'
        ])->where(['id' => $id])->get();
        $additionalSkp = Skp::with([
            'skpWorkPlans' => function ($query) {
                $query->where('is_main', 0)->orderBy('id', 'asc');
            },
            'skpWorkPlans.skpWorkIndicators'
        ])->where(['id' => $id])->get();
        $assessingOfficer = null;
        $officerWorkUnit = null;
        if (!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id)) {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit', 'workUnit', 'workTitle', 'personal', 'personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }
        $attachmentCategories = null;
        $skpBehaviors = null;
        if (!empty($skp)) {
            $attachmentCategories = AttachmentCategory::get();
            foreach ($attachmentCategories as $attachmentCategory) {
                $attachmentCategory->skpWorkAttachments = SkpWorkAttachment::where([
                    ['skp_id', '=', $skp->id],
                    ['attachment_category_id', '=', $attachmentCategory->id]
                ])->get();
            }
            $skpBehaviors = SkpBehavior::with(['behaviorCategory.behaviorCriterias'])->where('skp_id', $skp->id)->orderBy('id', 'asc')->get();
        }
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "mainSkp" => $mainSkp,
            "additionalSkp" => $additionalSkp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit
        ];
        return view($this->route . 'detail', $data);
    }

    public function edit_behavior_note($id)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personalWorkUnit = PersonalWorkUnit::with(['rootWorkUnit', 'workPosition', 'workUnit', 'personal.workRank', 'personal.workTitle'])->where([
            ['personal_id', '=', $personal->id],
            ['period_id', '=', session('period_id')],
            ['work_unit_id', '=', session('work_unit_id')],
            ['is_active', '=', true]
        ])->first();
        $skp = Skp::with(['skpWorkPlans'])->where(['id' => $id])->first();
        $officerWorkUnit = null;
        if (!empty($personalWorkUnit) && !empty($personalWorkUnit->assessor_personal_work_unit_id)) {
            $officerWorkUnit = PersonalWorkUnit::with(['rootWorkUnit', 'workUnit', 'workTitle', 'personal', 'personal.workRank', 'personal.workTitle'])
                ->find($personalWorkUnit->assessor_personal_work_unit_id);
        }
        $attachmentCategories = null;
        $skpBehaviors = null;
        if (!empty($skp)) {
            $attachmentCategories = AttachmentCategory::get();
            foreach ($attachmentCategories as $attachmentCategory) {
                $attachmentCategory->skpWorkAttachments = SkpWorkAttachment::where([
                    ['skp_id', '=', $skp->id],
                    ['attachment_category_id', '=', $attachmentCategory->id]
                ])->get();
            }
            $skpBehaviors = SkpBehavior::with(['behaviorCategory.behaviorCriterias'])->where('skp_id', $skp->id)->orderBy('id', 'asc')->get();
        }
        $data = [
            "route" => $this->route,
            'skp' => $skp,
            "attachmentCategories" => $attachmentCategories,
            "skpBehaviors" => $skpBehaviors,
            'officerWorkUnit' => $officerWorkUnit,
            'personalWorkUnit' => $personalWorkUnit
        ];
        return view($this->route . 'edit-behavior-note', $data);
    }

    public function save_behavior_note(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $skp = Skp::find($request->get('id'));
            if (!empty($skp)) {
                if (!empty($request->get('skp_behavior_id'))) {
                    foreach ($request->get('skp_behavior_id') as $key => $skpBehaviorId) {
                        if (!empty($request->get('behavior_note')[$key])) {
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
                'message' => 'Peubahan catatan ekpektasi pemimpin berhasil disimpan',
            ]);
        } catch (\Exception $e) {
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
        $current_period = Period::find(session('period_id'));
        if (session('role_name') == 'SuperadminUK') {
            $personal_work_unit = $personal->lastUnitPosition;
            $staffs = Personal::select('work_id_number')->whereIn('id', PersonalWorkUnit::where('root_work_unit_id', $personal_work_unit->root_work_unit_id)
                ->orWhere('work_unit_id', $personal_work_unit->root_work_unit_id)
                ->where('period_id', session('period_id'))
                ->get()
                ->pluck('personal_id'))->get();
        } elseif (session('role_name') == 'JAJF') {
            $personal_work_unit = $personal->lastUnitPosition;
            $staffs = Personal::select('work_id_number')->whereIn('id', PersonalWorkUnit::where('root_work_unit_id', $personal_work_unit->root_work_unit_id)
                ->orWhere('work_unit_id', $personal_work_unit->root_work_unit_id)
                ->where('period_id', session('period_id'))
                ->get()
                ->pluck('personal_id'))->get();
        } else {
            $staffs = Personal::select('work_id_number')->whereIn('id', PersonalWorkUnit::where('period_id', session('period_id'))->get()->pluck('personal_id'))->get();
        }
        $staff_work_ids = str_replace('"', "'", trim($staffs->pluck('work_id_number')->toJson(), '[]'));
        $assess_staffs = DB::connection('pgsql-seskom')->select("
            SELECT ps.id,p.name,p.work_id_number,wp.name work_position,pl.name name_lead,pl.work_id_number work_id_number_lead,wpl.name work_position_lead ,
               pll.name name_lead_lead,pll.work_id_number work_id_number_lead_lead,wpll.name work_position_lead_lead 
            FROM personal_workloads ps
            JOIN personals p on p.id=ps.personal_id
            JOIN work_positions wp on wp.id=p.work_position_id
            JOIN personals pl on pl.id=ps.lead_id
            JOIN work_positions wpl on wpl.id=pl.work_position_id
            LEFT JOIN personal_workloads pwll on pwll.personal_id=pl.id 
            and pwll.year='{$current_period->year}'
            LEFT JOIN personals pll on pll.id=pwll.lead_id
            LEFT JOIN work_positions wpll on wpll.id=pll.work_position_id
            WHERE ps.year='{$current_period->year}'
            AND p.work_id_number in ({$staff_work_ids})
            order by p.name
        ");
        $data = [
            "route" => $this->route,
            'months' => getListMonthId(),
            'current_period' => $current_period,
            'assess_staffs' => $assess_staffs
        ];
        return view($this->route . 'index', $data);
    }
}
