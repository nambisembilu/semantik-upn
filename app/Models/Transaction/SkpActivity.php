<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkpActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}

?>