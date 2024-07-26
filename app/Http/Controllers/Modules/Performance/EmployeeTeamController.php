<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EmployeeTeamController extends Controller
{
    private $route = "modules.performance.employee-team.";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')->where('period_id', session('period_id'))->first();
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $work_unit_main->teams = PersonalWorkUnit::join('personals', 'personals.id', '=', 'personal_work_units.personal_id')
            ->where('personal_work_units.period_id', session('period_id'))
            ->where('personal_work_units.work_unit_id', $work_unit_main->id)
            ->whereNull('personals.deleted_at')
            ->where('personals.name', 'not like', "%admin%")
            ->orderBy('personal_work_units.is_head', 'desc')
            ->orderBy('personals.name', 'asc')
            ->select('personal_work_units.*', 'personals.name')
            ->get();
        if (session('role_name') == 'SuperadminUK') {
            $personal_work_unit = $personal->lastUnitPosition;
            $work_units = WorkUnit::where('parent_id', $work_unit_main->id)->where('id', $personal_work_unit->root_work_unit_id)->get();
        } elseif (session('role_name') == 'JAJF') {
            $personal_work_unit = $personal->lastUnitPosition;
            $work_units = WorkUnit::where('parent_id', $work_unit_main->id)->where('id', $personal_work_unit->root_work_unit_id)->get();
        } else {
            $work_units = WorkUnit::where('parent_id', $work_unit_main->id)->get();
        }
        $index = 0;
        foreach ($work_units as $work_unit) {
            $work_units[$index]->teams = PersonalWorkUnit::join('personals', 'personals.id', '=', 'personal_work_units.personal_id')
                ->where('personal_work_units.period_id', session('period_id'))
                ->where('personal_work_units.work_unit_id', $work_unit->id)
                ->where('personals.name', 'not like', "%admin%")
                ->whereNull('personals.deleted_at')
                ->orderBy('personal_work_units.is_head', 'desc')
                ->orderBy('personals.name', 'asc')
                ->select('personal_work_units.*', 'personals.name')
                ->get();
            $index++;
        }
        $data = [
            "route" => $this->route,
            "work_unit_main" => $work_unit_main,
            "work_units" => $work_units,
        ];
        return view($this->route . 'index', $data);
    }

    public function ajaxLoadStaff(Request $request)
    {
        $work_unit = WorkUnit::where('id', $request->unit_id)->first();
        $root_work_unit = DB::select("select * from recursive_unit_non_root ( {$work_unit->id} )");
        if (!empty($root_work_unit)) {
            $root_work_unit_id = $root_work_unit[0]->id;
        } else {
            $root_work_unit_id = 1;
        }
        $staffs = Personal::join('personal_work_units', 'personals.id', '=', 'personal_work_units.personal_id')
            ->where('personal_work_units.period_id', session('period_id'))
            ->where('personals.name', 'not like', "%admin%")
            ->where(function ($query) use ($root_work_unit_id) {
                $query->where('personal_work_units.root_work_unit_id', $root_work_unit_id)->orWhere('personal_work_units.work_unit_id', $root_work_unit_id);
            })
            ->orderBy('personals.name', 'asc')
            ->select('personals.*')
            ->get();
        $data = [
            "route" => $this->route,
            "work_unit" => $work_unit,
            "staffs" => $staffs,
            "is_head" => $request->is_head
        ];
        return view($this->route . '_form-tim', $data);
    }

    public function ajaxLoadSubteam(Request $request)
    {
        $work_unit = WorkUnit::where('id', $request->unit_id)->first();
        $data = [
            "route" => $this->route,
            "work_unit" => $work_unit,
        ];
        return view($this->route . '_form-subtim', $data);
    }

    public function saveTim(Request $request)
    {
        try {
            $is_head = $request->is_head;
            $personalWorkUnit = PersonalWorkUnit::where('personal_id', $request->personal_id)->where('period_id', session('period_id'))->first();
            $personalWorkUnitLast = PersonalWorkUnit::where('personal_id', $request->personal_id)->first();
            if (!empty($personalWorkUnitLast)) {
                if (empty($personalWorkUnit)) {
                    PersonalWorkUnit::create([
                        'work_position_id' => $personalWorkUnitLast->work_position_id,
                        'work_unit_id' => $request->work_unit_id,
                        'role_id' => $personalWorkUnitLast->role_id,
                        'personal_id' => $personalWorkUnitLast->personal_id,
                        'period_id' => $personalWorkUnitLast->period_id,
                        'assessor_personal_work_unit_id' => $personalWorkUnitLast->assessor_personal_work_unit_id,
                        'root_work_unit_id' => $personalWorkUnitLast->root_work_unit_id,
                        'start_date' => $personalWorkUnitLast->start_date,
                        'end_date' => $personalWorkUnitLast->end_date,
                        'is_head' => $is_head ? true : false,
                        'is_active' => $personalWorkUnitLast->is_active,
                    ]);
                } else {
                    $personalWorkUnit->is_head = $is_head ? true : false;
                    $personalWorkUnit->work_unit_id = $request->work_unit_id;
                    $personalWorkUnit->save();
                    $skp = Skp::where(['personal_id' => $personalWorkUnit->personal_id, 'period_id' => $personalWorkUnit->period_id])->first();
                    if (!empty($skp)) {
                        $skp->work_unit_id = $request->work_unit_id;
                        $skp->save();
                    }
                }
                return Redirect::route($this->route . 'index')->with([
                    'toast-success' => 'success',
                    'success' => 'Data berhasil disimpan'
                ]);
            } else {
                return Redirect::back()->withErrors(['Jabatan pegawai belum di set']);
            }
        } catch (\Exception $e) {
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
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function setStatusTeam(Request $request)
    {
        try {
            $is_head = $request->is_head;
            $personalWorkUnit = PersonalWorkUnit::where('personal_id', $request->personal_id)->where('period_id', session('period_id'))->first();
            if ($is_head == 1) {
                PersonalWorkUnit::where('root_work_unit_id', $request->unit_id)->where('period_id', session('period_id'))->update(['is_head' => 0]);
            }
            if (!empty($personalWorkUnit)) {
                $personalWorkUnit->is_head = $is_head;
                $personalWorkUnit->save();
            }
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
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
            return Redirect::route($this->route . 'index')->with([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteTeam(Request $request)
    {
        try {
            $pwu = PersonalWorkUnit::find($request->id);
            $pwu->work_unit_id = $pwu->root_work_unit_id;
            $pwu->is_head = false;
            $pwu->save();
            $skp = Skp::where(['personal_id' => $pwu->personal_id, 'period_id' => $pwu->period_id])->first();
            if (!empty($skp)) {
                $skp->work_unit_id = $pwu->root_work_unit_id;
                $skp->save();
            }
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
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
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function addSubteam(Request $request)
    {
        try {
            WorkUnit::create([
                'parent_id' => $request->work_unit_id,
                'name' => $request->name,
            ]);
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
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
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function deleteSubteam(Request $request)
    {
        try {
            $work_unit = WorkUnit::find($request->work_unit_id);
            $pwus = PersonalWorkUnit::where('work_unit_id', $request->work_unit_id)->get();
            foreach ($pwus as $pwu) {
                $pwu->work_unit_id = $pwu->root_work_unit_id;
                $pwu->save();
                $skp = Skp::where(['personal_id' => $pwu->personal_id, 'period_id' => $pwu->period_id])->first();
                if (!empty($skp)) {
                    $skp->work_unit_id = $pwu->root_work_unit_id;
                    $skp->save();
                }
            }
            $work_unit->delete();
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
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
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }
}
