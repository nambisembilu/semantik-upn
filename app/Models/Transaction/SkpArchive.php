<?php

namespace App\Models\Transaction;

use App\Models\Master\Personal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkpArchive extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
}

?>