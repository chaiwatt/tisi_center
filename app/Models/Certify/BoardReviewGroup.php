<?php

namespace App\Models\Certify;

use App\Models\Bcertify\StatusAuditor;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class BoardReviewGroup extends Model
{
    use Sortable;

    protected $fillable = ['board_review_id', 'status_auditor_id'];

    public function reviewers() {
        return $this->hasMany(BoardReviewInformation::class, 'group_id');
    }

    public function sa() {
        return $this->belongsTo(StatusAuditor::class, 'status_auditor_id');
    }
}
