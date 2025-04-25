<?php

namespace App\Models\Certify;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class BoardReview extends Model
{
    use Sortable;

    protected $fillable = ['taxid', 'judgement_date', 'type', 'branch', 'other_attach', 'token', 'created_by', 'updated_by'];
    protected $dates = ['judgement_date'];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function groups() {
        return $this->hasMany(BoardReviewGroup::class, 'board_review_id');
    }

}
