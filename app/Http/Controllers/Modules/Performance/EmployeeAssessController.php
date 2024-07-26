<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\System\LogError;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EmployeeAssessController extends Controller
{
    private $route = "modules.performance.employee-assess.";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function saveLead(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->pwu_ids)) {
                $pwu_ids=explode(',',$request->pwu_ids);
                foreach ($pwu_ids as $pwu_id){
                    $personalWorkUnit = PersonalWorkUnit::find($pwu_id);
                    $personalWorkUnit->assessor_personal_work_unit_id=$request->pwu_parent_id;
                    $personalWorkUnit->save();
                }
                DB::commit();
                return Redirect::route($this->route . 'index')->with([
                    'toast-success'=>'success',
                    'success' => 'Data PPK berhasil disimpan'
                ]);
            }else{
                DB::rollBack();
                return Redirect::back()->withErrors(['Tidak ada data yang dirubah']);
            }

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
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function clearLead(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->pwu_ids)) {
                $pwu_ids=explode(',',$request->pwu_ids);
                foreach ($pwu_ids as $pwu_id){
                    $personalWorkUnit = PersonalWorkUnit::find($pwu_id);
                    $personalWorkUnit->assessor_personal_work_unit_id=null;
                    $personalWorkUnit->save();
                }
                DB::commit();
                return Redirect::route($this->route . 'index')->with([
                    'toast-success'=>'success',
                    'success' => 'Data PPK berhasil disimpan'
                ]);
            }else{
                DB::rollBack();
                return Redirect::back()->withErrors(['Tidak ada data yang dirubah']);
            }

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
            return Redirect::back()->withErrors([$e->getMessage()]);
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
            $assess_staffs = DB::select("
            SELECT pwu.id,p.name,p.work_id_number,wp.name work_position,prd.description period_desc,pl.name name_lead,pl.work_id_number work_id_number_lead,wpl.name work_position_lead ,
               pll.name name_lead_lead,pll.work_id_number work_id_number_lead_lead,wpll.name work_position_lead_lead 
            FROM personal_work_units pwu
            JOIN personals p on p.id=pwu.personal_id
            JOIN work_positions wp on wp.id=pwu.work_position_id
            JOIN periods prd on prd.id=pwu.period_id
            LEFT JOIN personal_work_units pwul on pwul.id=pwu.assessor_personal_work_unit_id
            LEFT JOIN personals pl on pl.id=pwul.personal_id
            LEFT JOIN work_positions wpl on wpl.id=pwul.work_position_id
            LEFT JOIN personal_work_units pwull on pwull.id=pwul.assessor_personal_work_unit_id
            LEFT JOIN personals pll on pll.id=pwull.personal_id
            LEFT JOIN work_positions wpll on wpll.id=pwull.work_position_id
            WHERE pwu.period_id='{$current_period->id}' and (pwu.root_work_unit_id='{$personal_work_unit->root_work_unit_id}' or pwu.work_unit_id='{$personal_work_unit->root_work_unit_id}')  and pwu.deleted_at is null
            order by p.name
        ");
        } elseif (session('role_name') == 'JAJF') {
            $personal_work_unit = $personal->lastUnitPosition;
            $assess_staffs = DB::select("
            SELECT pwu.id,p.name,p.work_id_number,wp.name work_position,prd.description period_desc,pl.name name_lead,pl.work_id_number work_id_number_lead,wpl.name work_position_lead ,
               pll.name name_lead_lead,pll.work_id_number work_id_number_lead_lead,wpll.name work_position_lead_lead 
            FROM personal_work_units pwu
            JOIN personals p on p.id=pwu.personal_id
            JOIN work_positions wp on wp.id=pwu.work_position_id
            JOIN periods prd on prd.id=pwu.period_id
            LEFT JOIN personal_work_units pwul on pwul.id=pwu.assessor_personal_work_unit_id
            LEFT JOIN personals pl on pl.id=pwul.personal_id
            LEFT JOIN work_positions wpl on wpl.id=pwul.work_position_id
            LEFT JOIN personal_work_units pwull on pwull.id=pwul.assessor_personal_work_unit_id
            LEFT JOIN personals pll on pll.id=pwull.personal_id
            LEFT JOIN work_positions wpll on wpll.id=pwull.work_position_id
            WHERE pwu.period_id='{$current_period->id}' and (pwu.root_work_unit_id='{$personal_work_unit->root_work_unit_id}' or pwu.work_unit_id='{$personal_work_unit->root_work_unit_id}')  and pwu.deleted_at is null
            and pwu.personal_id='{$personal->id}'
            order by p.name
        ");
        } else {
            $assess_staffs = DB::select("
            SELECT pwu.id,p.name,p.work_id_number,wp.name work_position,prd.description period_desc,pl.name name_lead,pl.work_id_number work_id_number_lead,wpl.name work_position_lead ,
               pll.name name_lead_lead,pll.work_id_number work_id_number_lead_lead,wpll.name work_position_lead_lead 
            FROM personal_work_units pwu
            JOIN personals p on p.id=pwu.personal_id
            JOIN work_positions wp on wp.id=pwu.work_position_id
            JOIN periods prd on prd.id=pwu.period_id
            LEFT JOIN personal_work_units pwul on pwul.id=pwu.assessor_personal_work_unit_id
            LEFT JOIN personals pl on pl.id=pwul.personal_id
            LEFT JOIN work_positions wpl on wpl.id=pwul.work_position_id
            LEFT JOIN personal_work_units pwull on pwull.id=pwul.assessor_personal_work_unit_id
            LEFT JOIN personals pll on pll.id=pwull.personal_id
            LEFT JOIN work_positions wpll on wpll.id=pwull.work_position_id
            WHERE pwu.period_id='{$current_period->id}'  and pwu.deleted_at is null
            order by p.name
        ");
        }

        $data = [
            "route" => $this->route,
            'current_period' => $current_period,
            'assess_staffs' => $assess_staffs
        ];
        return view($this->route . 'index', $data);
    }
}
