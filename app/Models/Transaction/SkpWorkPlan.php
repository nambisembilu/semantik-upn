<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkpWorkPlan extends Model
{
    protected $table = 'skp_work_plans';
    protected $fillable = array(
        'intervention_assignment_id',
        'employment_agreement_id',
        'skp_id',
        'title',
        'description',
        'get_task_from',
        'is_main'
    );
    use HasFactory;

    public function skpWorkIndicators()
    {
        return $this->hasMany(SkpWorkIndicator::class);
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'Skp_id');
    }

    public function interventionAssignment()
    {
        return $this->belongsTo(SkpWorkAssignment::class, 'intervention_assignment_id');
    }

    public function skpActivity()
    {
        return $this->hasMany(SkpActivity::class);
    }
}
