<?php

namespace App\Http\Controllers\Modules\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OrganizationPerformanceController extends Controller
{
    private $route = "modules.master.org-performance.";
    private $view = "modules.master.org-performance.";
    private $menu_title = 'Master - Kinerja Organisasi';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $param = [
            'route' => $this->route,
            'org_performances' => getOrgPerformanceList(),
            'menu_title' => $this->menu_title,
            'data' => Period::find(session('period_id'))
        ];
        return view($this->view . 'form', $param);
    }

    public function save(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Period::find($id);
            $data->organization_performance = $request->organization_performance;
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
}

?>