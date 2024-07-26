<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpRealization;
use App\Models\Master\FeedbackWorkTextTemplate;
use App\Models\Master\FeedbackWorkCategory;

class SkpPlanRealization extends Model
{
    protected $table = 'skp_plan_realizations';
    protected $fillable = array('skp_work_plan_id','skp_realization_id',
    'feedback_work_category_id','feedback_text_template_id', 'realization',
     'feedback','supporting_evidence');

    use HasFactory;

    public function skpWorkPlan()
    {
        return $this->belongsTo(SkpWorkPlan::class, 'skp_work_plan_id');
    }

    public function skpRealization()
    {
        return $this->belongsTo(SkpRealization::class, 'skp_realization_id');
    }

    public function feedbackWorkCategory()
    {
        return $this->belongsTo(FeedbackWorkCategory::class, 'feedback_work_category_id');
    }

    public function feedbackWorkTextTemplate()
    {
        return $this->belongsTo(FeedbackWorkTextTemplate::class, 'feedback_text_template_id');
    }
}
