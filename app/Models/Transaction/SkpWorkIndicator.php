<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpWorkAssignment;

class SkpWorkIndicator extends Model
{
    protected $table = 'skp_work_indicators';
    protected $fillable = array('skp_work_plan_id','employment_agreement_indicator_id','goal','title', 'description',
    'definition', 'formula', 'measurement', 'level_of_control', 'data_source', 'report_period');
    use HasFactory;

    public function skpWorkPlan()
    {
        return $this->belongsTo(SkpWorkPlan::class, 'skp_work_plan_id');
    }

    public function skpWorkAssignments()
    {
        return $this->hasMany(SkpWorkAssignment::class);
    }
}
