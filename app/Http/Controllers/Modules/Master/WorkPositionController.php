<?php

namespace App\Http\Controllers\Modules\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class WorkPositionController extends Controller
{
    private $route = "modules.master.work-position.";
    private $view = "modules.master.work-position.";
    private $menu_title = 'Master - Jabatan';
    private $validate_fields = [
        'name' => 'required',
    ];

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
            'menu_title' => $this->menu_title,
        ];
        return view($this->view . 'index', $param);
    }

    public function create()
    {
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => [],
        ];
        return view($this->view . 'form', $param);
    }

    public function store(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $data = new WorkPosition();
            $data->name = $request->name;
            $data->description = $request->description;
            $data->save();
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
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
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => WorkPosition::find($id),
        ];
        return view($this->view . 'form', $param);
    }

    public function save(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = WorkPosition::find($id);
            $data->name = $request->name;
            $data->description = $request->description;
            $data->save();
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
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
            $data = WorkPosition::find($id);
            $personal_work_unit_check = PersonalWorkUnit::where('work_unit_id', $data->id)->orWhere('root_work_unit_id', $data->id)->first();
            if (!empty($personal_work_unit_check)) {
                DB::rollBack();
                return Redirect::back()->withErrors(['Gagal Masih ada jabatan yang melekat pada pegawai']);
            } else {
                $data->delete();
                DB::commit();
                return redirect(route($this->route . 'index'))->with([
                    'toast-success' => 'success',
                    'success' => 'Data berhasil dihapus'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function datatable(Request $request)
    {
        $data = DB::select("
            select * from work_positions where deleted_at is null order by id asc
        ");
        return DataTables::of($data)->addColumn('action', function ($d) {
            $html = '
                <div class="d-flex justify-content-center">
                    <a href="' . route($this->route . 'edit', $d->id) . '" class="btn btn-sm btn-primary btn-icon me-1">
                        <i class="ph-pencil"></i>
                    </a>
                    <form action="' . route($this->route . 'delete') . '" method="post">
                        <input type="hidden" name="id" value="' . $d->id . '"/>
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <button type="submit" onclick="deleteRow(event)" class="btn btn-sm btn-danger btn-icon">
                           <i class="ph-x"></i>
                        </button>
                    </form>
                </div>';
            return $html;
        })->rawColumns([
            'action'
        ])->make(true);
    }
}

?>