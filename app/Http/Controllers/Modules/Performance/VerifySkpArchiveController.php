<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Transaction\SkpArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VerifySkpArchiveController extends Controller
{
    private $route = "modules.performance.verify-archive.";
    private $menu_title = 'Kinerja - Verifikasi Arsip SKP';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function plan()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personal_work_unit = $personal->lastUnitPosition;
        $root_work_unit = DB::select("select * from recursive_unit_non_root ( {$personal_work_unit->root_work_unit_id} )");
        if (!empty($root_work_unit)) {
            $root_work_unit_id = $root_work_unit[0]->id;
        } else {
            $root_work_unit_id = 1;
        }
        if (session('role_name') == 'Superadmin') {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))->get()->pluck('personal_id'))
                ->whereNotNull('plan_file')
                ->get();
        } else {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))
                ->where('root_work_unit_id', $root_work_unit_id)
                ->get()
                ->pluck('personal_id'))->whereNotNull('plan_file')->get();
        }
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'type' => 'plan',
            'skp_archives' => $skp_archives
        ];
        return view($this->route . 'index', $param);
    }

    public function eval()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personal_work_unit = $personal->lastUnitPosition;
        $root_work_unit = DB::select("select * from recursive_unit_non_root ( {$personal_work_unit->root_work_unit_id} )");
        if (!empty($root_work_unit)) {
            $root_work_unit_id = $root_work_unit[0]->id;
        } else {
            $root_work_unit_id = 1;
        }
        if (session('role_name') == 'Superadmin') {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))->get()->pluck('personal_id'))
                ->whereNotNull('eval_file')
                ->get();
        } else {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))
                ->where('root_work_unit_id', $root_work_unit_id)
                ->get()
                ->pluck('personal_id'))->whereNotNull('eval_file')->get();
        }
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'type' => 'eval',
            'skp_archives' => $skp_archives
        ];
        return view($this->route . 'index', $param);
    }

    public function doc()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personal_work_unit = $personal->lastUnitPosition;
        $root_work_unit = DB::select("select * from recursive_unit_non_root ( {$personal_work_unit->root_work_unit_id} )");
        if (!empty($root_work_unit)) {
            $root_work_unit_id = $root_work_unit[0]->id;
        } else {
            $root_work_unit_id = 1;
        }
        if (session('role_name') == 'Superadmin') {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))->get()->pluck('personal_id'))
                ->whereNotNull('doc_eval_file')
                ->get();
        } else {
            $skp_archives = SkpArchive::whereIn('personal_id', PersonalWorkUnit::where('period_id', session('period_id'))
                ->where('root_work_unit_id', $root_work_unit_id)
                ->get()
                ->pluck('personal_id'))->whereNotNull('doc_eval_file')->get();
        }
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'type' => 'doc',
            'skp_archives' => $skp_archives
        ];
        return view($this->route . 'index', $param);
    }

    public function changeStatus(Request $request)
    {
        $user = Auth::user();
        $type = $request->type;
        $status = $request->status;
        DB::beginTransaction();
        try {
            if (!empty($request->ids)) {
                $ids = explode(',', $request->ids);
                foreach ($ids as $id) {
                    $skp_archive = SkpArchive::find($id);
                    if ($type == 'plan') {
                        $skp_archive->plan_status = $status;
                    } elseif ($type == 'eval') {
                        $skp_archive->eval_status = $status;
                    } elseif ($type == 'doc') {
                        $skp_archive->doc_eval_status = $status;
                    }
                    $skp_archive->save();
                }
                DB::commit();
                return Redirect::route($this->route . $type)->with([
                    'toast-success' => 'success',
                    'success' => 'Data Arsip SKP berhasil dirubah'
                ]);
            } else {
                DB::rollBack();
                return Redirect::back()->withErrors(['Tidak ada data yang dirubah']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }
}

?>