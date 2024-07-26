<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\EmploymentAgreement;
use App\Models\Transaction\EmploymentAgreementIndicatorPerspective;

class EmploymentAgreementIndicator extends Model
{
    protected $fillable = array(
        'code',
        'title',
        'target',
        'employment_agreement_id'
    );

    use HasFactory;
    
    public function employmentAgreement()
    {
        return $this->belongsTo(EmploymentAgreement::class, 'employment_agreement_id');
    }

    public function employmentAgreementIndicatorPerspectives()
    {
        return $this->hasMany(EmploymentAgreementIndicatorPerspective::class);
    }

}
