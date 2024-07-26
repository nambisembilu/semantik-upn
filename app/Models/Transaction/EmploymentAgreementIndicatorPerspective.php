<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\EmploymentAgreementIndicator;
use App\Models\Master\PerspectiveIndicator;

class EmploymentAgreementIndicatorPerspective extends Model
{
    protected $fillable = array(
        'perspective_indicator_id',
        'employment_agreement_indicator_id'
    );

    use HasFactory;

    public function employmentAgreementIndicator()
    {
        return $this->belongsTo(EmploymentAgreementIndicator::class, 'employment_agreement_indicator_id');
    }

    public function perspectiveIndicator()
    {
        return $this->belongsTo(PerspectiveIndicator::class, 'perspective_indicator_id');
    }
}
