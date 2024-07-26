<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workTitle()
    {
        return $this->belongsTo(WorkTitle::class, 'work_title_id');
    }

    public function workRank()
    {
        return $this->belongsTo(WorkRank::class, 'work_rank_id');
    }

    public function lastUnitPosition()
    {
        return $this->hasOne(PersonalWorkUnit::class)->where('period_id',session('period_id'))->latest();
    }
}
