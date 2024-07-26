<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpWorkNote;
use App\Models\Transaction\SkpWorkAttachment;
use App\Models\Transaction\SkpBehavior;

class Skp extends Model
{
    protected $fillable = array('realization_period_type_id','period_id','personal_id','application_status','application_date', 'work_unit_id');

    protected $table = 'skps';
    use HasFactory;

   public function skpWorkPlans()
   {
       return $this->hasMany(SkpWorkPlan::class);
   }

   public function skpBehaviors()
   {
       return $this->hasMany(SkpBehavior::class);
   }

   public function skpWorkNotes()
   {
       return $this->hasMany(SkpWorkNote::class);
   }

   public function skpWorkAttachments()
   {
       return $this->hasMany(SkpWorkAttachment::class);
   }
}
