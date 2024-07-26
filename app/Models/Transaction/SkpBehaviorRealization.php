<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\SkpBehavior;
use App\Models\Transaction\SkpRealization;

class SkpBehaviorRealization extends Model
{
    protected $table = 'skp_behavior_realizations';
    protected $fillable = array('skp_behavior_id','skp_realization_id',
    'feedback');

    use HasFactory;

    public function skpBehavior()
    {
        return $this->belongsTo(SkpBehavior::class, 'skp_behavior_id');
    }

    public function skpRealization()
    {
        return $this->belongsTo(SkpRealization::class, 'skp_realization_id');
    }
}
