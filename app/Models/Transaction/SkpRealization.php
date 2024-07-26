<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Skp;
use App\Models\Master\RealizationPeriodType;
use App\Models\Master\FeedbackBehaviorCategory;
use App\Models\Master\FeedbackWorkCategory;
use App\Models\Transaction\SkpPlanRealization;


class SkpRealization extends Model
{
    protected $table = 'skp_realizations';

    protected $fillable = array('skp_id','realization_period_id','feedback_work_category_id',
    'feedback_behavior_category_id', 'feedback_work_summary', 'performance_predicate',
    'realization_status', 'realization_date');

    use HasFactory;

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }

    public function realizationPeriodType()
    {
        return $this->belongsTo(RealizationPeriodType::class, 'realization_period_id');
    }

    public function skpPlanRealizations()
    {
        return $this->hasMany(SkpPlanRealization::class);
    }

    public function feedbackWorkCategory()
    {
        return $this->belongsTo(FeedbackWorkCategory::class, 'feedback_work_category_id');
    }

    public function feedbackBehaviorCategory()
    {
        return $this->belongsTo(FeedbackBehaviorCategory::class, 'feedback_behavior_category_id');
    }

    use HasFactory;
}
