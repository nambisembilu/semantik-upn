<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\EmploymentAgreementIndicator;

class EmploymentAgreement extends Model
{
    protected $fillable = array('no','title','get_task_from','personal_id','period_id');

    use HasFactory;

    public function employmentAgreementIndicators()
    {
        return $this->hasMany(EmploymentAgreementIndicator::class);
    }
}
