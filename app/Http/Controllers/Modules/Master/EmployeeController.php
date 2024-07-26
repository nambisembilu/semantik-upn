<?php

namespace App\Http\Controllers\Modules\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Periode;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\Role;
use App\Models\Master\WorkPosition;
use App\Models\Master\WorkRank;
use App\Models\Master\WorkUnit;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    private $route = "modules.master.employee.";

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
        $param = [
            'route' => $this->route,
        ];
        return view($this->route . 'index', $param);
    }

    public function create()
    {
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')
        ->where('period_id', session('period_id'))->first();
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personal_work_unit = $personal->lastUnitPosition;
        if (session('role_name') == 'SuperadminUK') {
            $work_units = WorkUnit::where('id', $personal_work_unit->root_work_unit_id)->get();
        } else {
            $work_units = WorkUnit::where('parent_id', $work_unit_main->id)->orwhere('id', $work_unit_main->id)->get();
        }
        $param = [
            'route' => $this->route,
            'menu_title' => 'Master Pegawai',
            'employee_types' => Personal::select('employee_type as name')->groupBy('employee_type')->whereNotNull('employee_type')->where('employee_type', '!=', '')->get(),
            'work_ranks' => WorkRank::get(),
            'personal_work_units' => PersonalWorkUnit::where('personal_id', $personal->id)->where('period_id', session('period_id'))->orderBy('start_date', 'desc')->get(),
            'work_units' => $work_units,
            'roles' => Role::get(),
            'work_positions' => WorkPosition::get(),
            'data' => [],
        ];
        return view($this->route . 'form', $param);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $check = Personal::where('work_id_number', $request->work_id_number)->first();
            if (!empty($check)) {
                DB::rollBack();
                return Redirect::back()->withErrors(['NIK Pegawai sudah ada']);
            } else {
                $user = new User();
                $user->name = $request->name;
                $user->username = $request->work_id_number;
                $user->password = bcrypt('airlangga');
                $user->save();
                $personal = new Personal();
                $personal->user_id = $user->id;
                $personal->work_rank_id = $request->work_rank_id;
                $personal->work_title_id = 3;
                $personal->work_id_number = $request->work_id_number;
                $personal->employee_type = $request->employee_type;
                $personal->name = $request->name;
                $personal->gender = $request->gender;
                $personal->address = '-';
                $personal->save();
                $personal_work_unit = new PersonalWorkUnit();
                $personal_work_unit->personal_id = $personal->id;
                $personal_work_unit->root_work_unit_id = $request->root_work_unit_id;
                $personal_work_unit->work_unit_id = $request->work_unit_id;
                $personal_work_unit->work_position_id = $request->work_position_id;
                $personal_work_unit->role_id = $request->role_id;
                $personal_work_unit->period_id = session('period_id');
                $personal_work_unit->is_head = $request->is_head == 1 ? true : false;
                $personal_work_unit->start_date = $request->date;
                $personal_work_unit->end_date = $request->date;
                $personal_work_unit->save();
                DB::commit();
                return Redirect::route($this->route . 'index')->with([
                    'toast-success' => 'success',
                    'success' => 'Data berhasil disimpan'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $data = new Periode();
            $data->nm_periode = $request->nm_periode;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->save();
            DB::commit();
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')
        ->where('period_id', session('period_id'))->first();
        if (session('role_name') == 'SuperadminUK') {
            $roles=Role::whereIn('name',['JAJF','SuperadminUK'])->get();
        } else {
            $roles=Role::get();
        }
        $param = [
            'route' => $this->route,
            'data' => Personal::find($id),
            'employee_types' => Personal::select('employee_type as name')->groupBy('employee_type')->whereNotNull('employee_type')->where('employee_type', '!=', '')->get(),
            'work_ranks' => WorkRank::get(),
            'personal_work_units' => PersonalWorkUnit::where('personal_id', $id)->where('period_id', session('period_id'))->orderBy('start_date', 'desc')->get(),
            'work_units' => WorkUnit::where('parent_id', $work_unit_main->id)->orwhere('id', $work_unit_main->id)->get(),
            'roles' => $roles,
            'work_positions' => WorkPosition::get(),
        ];
        return view($this->route . 'detail', $param);
    }

    public function editHistory(Request $request)
    {
        $work_unit_main = WorkUnit::where('name', 'Universitas Airlangga')
        ->where('period_id', session('period_id'))->first();
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        if (session('role_name') == 'SuperadminUK') {
            $roles=Role::whereIn('name',['JAJF','SuperadminUK'])->get();
        } else {
            $roles=Role::get();
        }
        $current_period_id = session('period_id');
        $personal_work_unit = $personal->lastUnitPosition;
        if (session('role_name') == 'SuperadminUK') {
            $work_units = WorkUnit::where('id', $personal_work_unit->root_work_unit_id)->get();
        } else {
            $work_units = WorkUnit::where('parent_id', $work_unit_main->id)->orwhere('id', $work_unit_main->id)->get();
        }
        $param = [
            'route' => $this->route,
            'data' => PersonalWorkUnit::find($request->id),
            'work_units' => $work_units,
            'roles' => $roles,
            'work_positions' => WorkPosition::get(),
        ];
        return view($this->route . '_form-edit-history', $param);
    }

    public function saveProfile($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = Personal::find($id);
            $data->name = $request->name;
            $data->work_rank_id = $request->work_rank_id;
            $data->employee_type = $request->employee_type;
            $data->save();
            DB::commit();
            return Redirect::back()->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function addHistory($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = new PersonalWorkUnit();
            $data->root_work_unit_id = $request->root_work_unit_id;
            $data->work_unit_id = $request->work_unit_id;
            $data->work_position_id = $request->work_position_id;
            $data->role_id = $request->role_id;
            $data->personal_id = $id;
            $data->period_id = session('period_id');
            $data->is_head = $request->is_head == 1 ? true : false;
            $data->start_date = $request->date;
            $data->end_date = $request->date;
            $data->save();
            DB::commit();
            return Redirect::back()->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function saveHistory(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = PersonalWorkUnit::find($request->id);
            $skp = Skp::where([
                'personal_id' => $data->personal_id,
                'period_id' => session('period_id'),
                'work_unit_id' => $data->work_unit_id
            ])->first();
            if (!empty($skp)) {
                $skp->work_unit_id = $request->work_unit_id;
                $skp->save();
            }
            $data->root_work_unit_id = $request->root_work_unit_id;
            $data->work_unit_id = $request->work_unit_id;
            $data->work_position_id = $request->work_position_id;
            $data->role_id = $request->role_id;
            $data->is_head = $request->is_head == 1 ? true : false;
            $data->start_date = $request->date;
            $data->end_date = $request->date;
            $data->save();
            $skp = Skp::where(['personal_id' => $data->personal_id, 'period_id' => session('period_id')])->first();
            if (!empty($skp)) {
                $skp->work_unit_id = $request->work_unit_id;
                $skp->save();
            }
            DB::commit();
            return Redirect::back()->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function deleteHistory(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = PersonalWorkUnit::find($request->id);
            $skp = Skp::where(['personal_id' => $data->personal_id, 'period_id' => $data->period_id, 'work_unit_id' => $data->work_unit_id])->first();
            $skpAssignment = SkpWorkAssignment::where(['assigned_to_personal_work_unit_id' => $data->id])->first();
            $assessorPwu = PersonalWorkUnit::where(['assessor_personal_work_unit_id' => $data->id])->first();
            if (!empty($skp) || !empty($skpAssignment) || !empty($assessorPwu)) {
                return Redirect::back()->with([
                    'toast-warning' => 'warning',
                    'warning' => 'Data tidak dapat dihapus, data sudah digunakan di rencana kerja'
                ]);
            }
            $data->delete();
            DB::commit();
            return Redirect::back()->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Personal::find($id);
            $skp = Skp::where(['personal_id' => $data->id])->first();
            $pwu = PersonalWorkUnit::where(['personal_id' => $data->id])->first();
            if (!empty($skp) || !empty($pwu)) {
                return Redirect::back()->with([
                    'toast-warning' => 'warning',
                    'warning' => 'Data tidak dapat dihapus, data sudah digunakan di rencana kerja'
                ]);
            }
            $data->delete();
            DB::commit();
            return Redirect::back()->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function datatable(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $current_period_id = session('period_id');
        if (session('role_name') == 'SuperadminUK') {
            $personal_work_unit = $personal->lastUnitPosition;
            $data = DB::select("
                select p.*,wr.grade_code,wp.name work_position,wu.name main_unit from personals p
                join work_ranks wr on wr.id=p.work_rank_id
                left join personal_work_units pwu on pwu.personal_id=p.id and pwu.period_id='{$current_period_id}' and pwu.deleted_at is null
                left join work_positions wp on pwu.work_position_id=wp.id
                left join work_units wu on pwu.work_unit_id=wu.id
                where p.deleted_at is null and (pwu.root_work_unit_id='{$personal_work_unit->root_work_unit_id}' or pwu.work_unit_id='{$personal_work_unit->root_work_unit_id}') and pwu.deleted_at is null
            ");
        } else {
            $data = DB::select("
                select p.*,wr.grade_code,wp.name work_position,wu.name main_unit  from personals p
                join work_ranks wr on wr.id=p.work_rank_id
                left join personal_work_units pwu on pwu.personal_id=p.id and pwu.period_id='{$current_period_id}'  and pwu.deleted_at is null
                left join work_positions wp on pwu.work_position_id=wp.id
                left join work_units wu on pwu.work_unit_id=wu.id
                where p.deleted_at is null
            ");
        }
        return DataTables::of($data)->addColumn('identity', function ($d) {
            $html = '
                <div class="d-flex flex-column justify-content-start">
                    <span>' . $d->name . '</span>
                    <b>' . $d->work_id_number . '</b>
                </div>';
            return $html;
        })->addColumn('position_unit', function ($d) {
            $html = '
                <div class="d-flex flex-column justify-content-start">
                    <span>' . $d->work_position . '</span>
                    <b>' . $d->main_unit . '</b>
                </div>';
            return $html;
        })->addColumn('action', function ($d) {
            $html = '
                <div class="d-flex justify-content-center">
                    <a href="' . route($this->route . 'edit', $d->id) . '" class="btn btn-sm btn-primary btn-icon me-1">
                        <i class="ph-pencil"></i>
                    </a>';
            if (session('role_name') == 'SuperadminUK') {
                $html .= '
                    <form action="' . route($this->route . 'delete') . '" method="post">
                        <input type="hidden" name="id" value="' . $d->id . '"/>
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <button type="submit" onclick="deleteRow(event)" class="btn btn-sm btn-danger btn-icon">
                           <i class="ph-x"></i>
                        </button>
                    </form>';
            }
            $html .= '</div>';
            return $html;
        })->rawColumns([
            'identity',
            'position_unit',
            'action'
        ])->make(true);
    }
}
