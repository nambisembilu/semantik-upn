<?php

namespace App\Http\Controllers\Modules\Master;

use Illuminate\Http\Request;
use App\Models\Master\PersonalWorkUnit;
use App\Http\Controllers\Controller;

class PersonalWorkUnitController extends Controller
{
    private $route = "modules.master.personal_work_unit.";

    public function get_work_units_by_personal_period(Request $request)
    {
        $workUnits = PersonalWorkUnit::with('workUnit:id,name')
            ->where([
                ['personal_id', '=', $request->get('personal_id')],
                ['period_id', '=', $request->get('period_id')],
                ['is_active', '=', true]])->whereNull('deleted_at')
            ->orderBy('id')->get();
    
        return response()->json($workUnits);
    }

    public function get_roleposition_by_personal_period_workunit(Request $request)
    {
        $roleAndPositions = PersonalWorkUnit::with('workPosition:id,name')
            ->where([
                ['personal_id', '=', $request->get('personal_id')],
                ['period_id', '=', $request->get('period_id')],
                ['work_unit_id', '=', $request->get('work_unit_id')],
                ['is_active', '=', true]])->whereNull('deleted_at')
            ->first();
    
        return response()->json($roleAndPositions);
    }
}

?>