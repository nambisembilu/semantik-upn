<?php

namespace App\Http\Controllers\Modules\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkUnit;
use App\Models\Master\RealizationPeriod;
use App\Models\Master\RealizationPeriodType;
use App\Models\System\WorkUnitIdMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use App\Models\System\LogError;
use App\Models\Transaction\EmploymentAgreement;
use App\Models\Transaction\EmploymentAgreementIndicator;
use App\Models\Transaction\EmploymentAgreementIndicatorPerspective;
use Carbon\Carbon;

class PeriodController extends Controller
{
    private $route = "modules.master.period.";
    private $view = "modules.master.period.";
    private $menu_title = 'Master - Periode';
    private $validate_fields = [
        'description' => 'required',
        'start_period' => 'required',
        'end_period' => 'required',
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

    public function get_by_id(Request $request)
    {
        $periods = Period::select('id', 'year', 'description')->where('id', $request->get('id'))->first();
        return response()->json($periods);
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
            $data = new Period();
            $data->description = $request->description;
            $data->start_period = $request->start_period;
            $data->end_period = $request->end_period;
            $data->year = $request->year;
            $data->save();

            $this->bulk_import_data_from_previous($data);
            
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getTraceAsString()]);
        }
    }

    public function edit($id)
    {
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => Period::find($id),
        ];
        return view($this->view . 'form', $param);
    }

    public function save(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Period::find($id);
            $data->description = $request->description;
            $data->start_period = $request->start_period;
            $data->end_period = $request->end_period;
            $data->year = $request->year;
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
            $data = Period::find($id);

            $pwu = PersonalWorkUnit::where(['period_id' => $data->id])->first();
            if (!empty($pwu)) {
                return Redirect::back()->with([
                    'toast-warning' => 'warning',
                    'warning' => 'Data tidak dapat dihapus, data sudah digunakan di personal unit kerja'
                ]);
            }

            $data->delete();
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
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
        $data = DB::select("
            select * from periods where deleted_at is null
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

    public function bulk_import_data_from_previous($newPeriod)
    {
        $previousYear = $newPeriod->year - 1;
        $previousPeriod = Period::where('year', $previousYear)->first();

        $previousWorkUnits = WorkUnit::where([
            ['period_id', '=', $previousPeriod->id]])->get();

        $startDate = $newPeriod->year.'-01-01';
        $endDate = $newPeriod->year.'-12-31';

        $realizationPeriodTypes = RealizationPeriodType::get();
        foreach($realizationPeriodTypes as $realizationPeriodType)
        {
            $newRealizationPeriod = new RealizationPeriod();
            $newRealizationPeriod->period_id = $newPeriod->id;
            $newRealizationPeriod->realization_period_type_id = $realizationPeriodType->id;
            $newRealizationPeriod->name = $realizationPeriodType->name." ".$newPeriod->year;
            $newRealizationPeriod->save();
        }

        foreach($previousWorkUnits as $previousWorkUnit)
        {
            $newWorkUnit = new WorkUnit();
            $newWorkUnit->period_id = $newPeriod->id;
            $newWorkUnit->parent_id = $previousWorkUnit->parent_id;
            $newWorkUnit->name = $previousWorkUnit->name;
            $newWorkUnit->description = $previousWorkUnit->description;
            $newWorkUnit->save();

            $newWorkUnitIdMapping = new WorkUnitIdMapping();
            $newWorkUnitIdMapping->period_id = $newPeriod->id;
            $newWorkUnitIdMapping->previous_id = $previousWorkUnit->id;
            $newWorkUnitIdMapping->new_id = $newWorkUnit->id;
            $newWorkUnitIdMapping->save();
        }

        DB::statement("UPDATE work_units wu
        SET parent_id = wuim.new_id
        FROM work_unit_id_mappings wuim
        WHERE wu.parent_id = wuim.previous_id AND wu.period_id = ".$newPeriod->id);

        $previousPersonalWorkUnits = PersonalWorkUnit::where([
            ['period_id', '=', $previousPeriod->id],
            ['is_active', '=', true]])->get();

        foreach($previousPersonalWorkUnits as $previousPersonalWorkUnit)
        {
            $workUnitMappingForWorkUnit = WorkUnitIdMapping::where([
                ['period_id', '=', $newPeriod->id],
                ['previous_id', '=', $previousPersonalWorkUnit->work_unit_id]])->first();

            $newWorkUnit = WorkUnit::find($workUnitMappingForWorkUnit->new_id);  

            $workUnitMappingForRootWorkUnit = WorkUnitIdMapping::where([
                ['period_id', '=', $newPeriod->id],
                ['previous_id', '=', $previousPersonalWorkUnit->root_work_unit_id]])->first();

            $newRootWorkUnit = WorkUnit::find($workUnitMappingForRootWorkUnit->new_id);

            $newPersonalWorkUnit = new PersonalWorkUnit();
            $newPersonalWorkUnit->period_id = $newPeriod->id;
            $newPersonalWorkUnit->work_unit_id = $newWorkUnit->id;
            $newPersonalWorkUnit->work_position_id = $previousPersonalWorkUnit->work_position_id;
            $newPersonalWorkUnit->personal_id = $previousPersonalWorkUnit->personal_id;
            $newPersonalWorkUnit->role_id = $previousPersonalWorkUnit->role_id;
            $newPersonalWorkUnit->assessor_personal_work_unit_id = $previousPersonalWorkUnit->assessor_personal_work_unit_id;
            $newPersonalWorkUnit->root_work_unit_id = $newRootWorkUnit->id;
            $newPersonalWorkUnit->is_active = $previousPersonalWorkUnit->is_active;
            $newPersonalWorkUnit->is_head = $previousPersonalWorkUnit->is_head;
            $newPersonalWorkUnit->start_date = $startDate;
            $newPersonalWorkUnit->end_date = $endDate;
            $newPersonalWorkUnit->save();
        }

        DB::statement("update personal_work_units pwu set assessor_personal_work_unit_id = temp.pwuan_id
        from (select pwuan.id as pwuan_id, pwua.id as pwua_id from work_units wu
        inner join work_unit_id_mappings wuim on wuim.previous_id = wu.id
        inner join personal_work_units pwuan on pwuan.work_unit_id = wuim.new_id and ".$newPeriod->id."
        inner join personal_work_units pwua on pwua.personal_id = pwuan.personal_id) temp
        where temp.pwua_id = pwu.assessor_personal_work_unit_id and pwu.assessor_personal_work_unit_id is not null and pwu.period_id = ".$newPeriod->id);

        $previousEmploymentAgreements = EmploymentAgreement::where([
            ['period_id', '=', $previousPeriod->id]])->get();

        foreach($previousEmploymentAgreements as $previousEmploymentAgreement)
        {
            $employmentAgreementFound = EmploymentAgreement::where([
                ['period_id', '=', $newPeriod->id],
                ['personal_id', '=', $previousEmploymentAgreement->personal_id],
                ['no', '=', $previousEmploymentAgreement->no],
                ['title', '=', $previousEmploymentAgreement->title]
                ])->first();

            if(empty($employmentAgreementFound))
            {
                $newEmploymentAgreement = new EmploymentAgreement();
                $newEmploymentAgreement->period_id = $newPeriod->id;
                $newEmploymentAgreement->personal_id = $previousEmploymentAgreement->personal_id;
                $newEmploymentAgreement->no = $previousEmploymentAgreement->no;
                $newEmploymentAgreement->title = $previousEmploymentAgreement->title;
                $newEmploymentAgreement->description = $previousEmploymentAgreement->description;
                $newEmploymentAgreement->get_task_from = $previousEmploymentAgreement->get_task_from;
                $newEmploymentAgreement->save();

                $previousEmploymentAgreementIndicators = EmploymentAgreementIndicator::where([
                    ['employment_agreement_id', '=', $previousEmploymentAgreement->id]])->get();

                foreach($previousEmploymentAgreementIndicators as $previousEmploymentAgreementIndicator)
                {
                    $newEmploymentAgreementIndicator = new EmploymentAgreementIndicator();
                    $newEmploymentAgreementIndicator->employment_agreement_id = $newEmploymentAgreement->id;
                    $newEmploymentAgreementIndicator->code = $previousEmploymentAgreementIndicator->code;
                    $newEmploymentAgreementIndicator->title = $previousEmploymentAgreementIndicator->title;
                    $newEmploymentAgreementIndicator->description = $previousEmploymentAgreementIndicator->description;
                    $newEmploymentAgreementIndicator->target = $previousEmploymentAgreementIndicator->target;
                    $newEmploymentAgreementIndicator->save();

                    $previousEmploymentAgreementIndicatorPerspectives = EmploymentAgreementIndicatorPerspective::where([
                        ['employment_agreement_indicator_id', '=', $previousEmploymentAgreementIndicator->id]])->get();

                    foreach($previousEmploymentAgreementIndicatorPerspectives as $previousEmploymentAgreementIndicatorPerspective)
                    {
                        $newEmploymentAgreementIndicatorPerspective = new EmploymentAgreementIndicatorPerspective();
                        $newEmploymentAgreementIndicatorPerspective->employment_agreement_indicator_id = $newEmploymentAgreementIndicator->id;
                        $newEmploymentAgreementIndicatorPerspective->perspective_indicator_id = $previousEmploymentAgreementIndicatorPerspective->perspective_indicator_id;
                        $newEmploymentAgreementIndicatorPerspective->save();
                    }    
                }
            }
        }
    }
}

?>