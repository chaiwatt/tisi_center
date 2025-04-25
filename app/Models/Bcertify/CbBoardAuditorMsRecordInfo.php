<?php

namespace App\Models\Bcertify;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;

class CbBoardAuditorMsRecordInfo extends Model
{
    use Sortable;
    protected $fillable = [
        'board_auditor_id',
        'header_text1',
        'header_text2',
        'header_text3',
        'header_text4',
        'body_text1',
        'body_text2'
    ];
    protected $table = 'cb_board_auditor_ms_record_infos';
    protected $primaryKey = 'id';

    public function certiCBAuditor()
    {
        return $this->belongsTo(CertiCBAuditors::class, 'board_auditor_id', 'id');
    }
}
