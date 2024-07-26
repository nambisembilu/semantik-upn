<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Skp;
use App\Models\Master\AttachmentCategory;

class SkpWorkAttachment extends Model
{
    protected $fillable = array('attachment_category_id','skp_id','description');
    protected $table = 'skp_work_attachments';
    use HasFactory;

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }

    public function attachmentCategory()
    {
        return $this->belongsTo(AttachmentCategory::class, 'attachment_category_id');
    }
}
