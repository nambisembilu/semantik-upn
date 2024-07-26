<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SkpMonthController extends Controller
{
    private $route = "modules.performance.skp-month.";

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

    public function detail($id)
    {
        $skp_plans = [];
        $personal = Personal::findOrFail($id);
        $period = Period::find(session('period_id'));
        $activities = DB::connection('pgsql-seskom')->select("
                select ac.* from activities ac 
                where ac.id in (
                    select pwa.activity_id from personal_workload_activities pwa
                    join personal_workloads pw on pw.id=pwa.personal_workload_id
                    join personals p on p.id=pw.personal_id
                    where p.work_id_number='{$personal->work_id_number}'
                    and pw.year='{$period->year}'
                )
                order by title
                ");
        $skp = Skp::where('personal_id', $personal->id)->where('period_id', session('period_id'))->first();
        if (!empty($skp)) {
            $skp_plans = SkpWorkPlan::where('skp_id', $skp->id)->get();
        }
        $data = [
            "skp" => $skp,
            "skp_plans" => $skp_plans,
            "personal" => $personal,
            "period" => $period,
            "route" => $this->route,
            "activities" => $activities,
            "months" => getListMonthId(),
        ];
        return view($this->route . 'detail', $data);
    }

    public function datatable(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $current_period_id = session('period_id');
        if (session('role_name') == 'SuperadminUK') {
            $personal_work_unit = $personal->lastUnitPosition;
            $data = DB::select("
                select p.*,wr.grade_code,wp.name work_position,wu.name main_unit,sa.jumlah_kegiatan from personals p
                join work_ranks wr on wr.id=p.work_rank_id
                left join personal_work_units pwu on pwu.personal_id=p.id and pwu.period_id='{$current_period_id}'
                left join work_positions wp on pwu.work_position_id=wp.id
                left join work_units wu on pwu.work_unit_id=wu.id
                left join (
                    select s.personal_id,count(distinct(sa.id)) jumlah_kegiatan from skp_activities sa 
                    join skp_work_plans swp on sa.skp_work_plan_id =swp.id 
                    join skps s on s.id=swp.skp_id 
                    where s.period_id=1 and sa.deleted_at is null
                    group by s.personal_id
                ) sa on sa.personal_id=p.id
                where p.deleted_at is null and (pwu.root_work_unit_id='{$personal_work_unit->root_work_unit_id}' or pwu.work_unit_id='{$personal_work_unit->root_work_unit_id}') and pwu.deleted_at is null
            ");
        } else {
            $data = DB::select("
                select p.*,wr.grade_code,wp.name work_position,wu.name main_unit,sa.jumlah_kegiatan  from personals p
                join work_ranks wr on wr.id=p.work_rank_id
                left join personal_work_units pwu on pwu.personal_id=p.id and pwu.period_id='{$current_period_id}'  and pwu.deleted_at is null
                left join work_positions wp on pwu.work_position_id=wp.id
                left join work_units wu on pwu.work_unit_id=wu.id
                left join (
                    select s.personal_id,count(distinct(sa.id)) jumlah_kegiatan from skp_activities sa 
                    join skp_work_plans swp on sa.skp_work_plan_id =swp.id 
                    join skps s on s.id=swp.skp_id 
                    where s.period_id=1 and sa.deleted_at is null
                    group by s.personal_id
                ) sa on sa.personal_id=p.id
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
        })->addColumn('jumlah_kegiatan', function ($d) {
            if($d->jumlah_kegiatan>0){
                $html = '
                    <span class="badge bg-success rounded-pill">'.$d->jumlah_kegiatan.'</span>
                ';
            }else{
                $html = '
                    <span class="badge bg-danger rounded-pill">0</span>
                ';
            }
            return $html;
        })->addColumn('action', function ($d) {
            $html = '
                <div class="d-flex justify-content-center">
                    <a href="' . route($this->route . 'detail', $d->id) . '" class="btn btn-sm btn-primary btn-icon me-1" title="Kegiatan Bulanan" >
                        <i class="ph-list"></i>
                    </a>';
            $html .='</div>';
            return $html;
        })->rawColumns([
            'identity',
            'position_unit',
            'jumlah_kegiatan',
            'action'
        ])->make(true);
    }
}
