<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTemplate extends Model
{
    use HasFactory;

    protected $casts = [
        'content' => 'array',
    ];
}
