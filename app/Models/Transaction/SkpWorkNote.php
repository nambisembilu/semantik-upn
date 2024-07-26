<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Skp;

class SkpWorkNote extends Model
{
    protected $table = 'skp_work_notes';
    use HasFactory;

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }
}
