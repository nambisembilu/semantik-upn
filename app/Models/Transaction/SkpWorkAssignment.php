<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\SkpWorkIndicator;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Master\PersonalWorkUnit;

class SkpWorkAssignment extends Model
{
    protected $table = 'skp_work_assignments';
    protected $fillable = array('skp_work_indicator_id','skp_work_plan_id','assigned_to_personal_work_unit_id','is_external');
    
    use HasFactory;

    public function skpWorkIndicator()
    {
        return $this->belongsTo(SkpWorkIndicator::class, 'skp_work_indicator_id');
    }

    public function skpWorkPlan()
    {
        return $this->belongsTo(SkpWorkPlan::class, 'skp_work_plan_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(PersonalWorkUnit::class, 'assigned_to_personal_work_unit_id');
    }

    public function skpWorkPlans()
    {
        return $this->hasMany(SkpWorkPlan::class, 'intervention_assignment_id','id');
    }
}
