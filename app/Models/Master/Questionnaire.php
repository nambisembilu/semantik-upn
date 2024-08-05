<?php

namespace App\Models\Master;

use App\Models\Transaction\SkpWorkPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    public function question()
    {
        return $this->hasMany(Question::class);
    }
}
