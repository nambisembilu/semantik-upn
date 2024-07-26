<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\BehaviorCategory;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpBehaviorRealization;

class SkpBehavior extends Model
{
    protected $fillable = array('behavior_category_id','skp_id','notes');
    protected $table = 'skp_behaviors';
    use HasFactory;

    public function behaviorCategory()
    {
        return $this->belongsTo(BehaviorCategory::class, 'behavior_category_id');
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }

    public function skpBehaviorRealizations()
    {
        return $this->hasMany(SkpBehaviorRealization::class);
    }
}

?>