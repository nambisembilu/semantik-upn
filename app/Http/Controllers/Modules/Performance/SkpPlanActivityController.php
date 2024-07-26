<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\System\LogError;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpActivity;
use App\Models\Transaction\SkpWorkPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SkpPlanActivityController extends Controller
{
    private $route = "modules.performance.skp-activity.";

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
        $skp_plans = [];
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $personal_work_unit = $personal->lastUnitPosition;
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
        return view($this->route . 'index', $data);
    }

    public function addActivity(Request $request)
    {
        $activity = explode('#', $request->activity);
        try {
            $data = SkpActivity::where('ref_external_id', $activity[0])->where('skp_work_plan_id', $request->skp_plan_id)->first();
            if (empty($data)) {
                SkpActivity::create([
                    'skp_work_plan_id' => $request->skp_plan_id,
                    'activity' => $activity[1],
                    'ref_external_id' => $activity[0]
                ]);
                return Redirect::route($this->route . 'index')->with([
                    'toast-success' => 'success',
                    'success' => 'Data berhasil disimpan'
                ]);
            } else {
                return Redirect::back()->withErrors(['Kegiatan sudah ada']);
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

    public function deleteActivity(Request $request)
    {
        try {
            $data = SkpActivity::findOrFail($request->skp_activity_id);
            $data->delete();
            return Redirect::route($this->route . 'index')->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil dihapus'
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
