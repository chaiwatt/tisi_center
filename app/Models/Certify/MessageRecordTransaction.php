<?php

namespace App\Models\Certify;

use App\Models\Besurv\Signer;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\BoardAuditor;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBAuditors;

class MessageRecordTransaction extends Model
{
    use Sortable;
    protected $table = 'message_record_transactions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'board_auditor_id',
        'certificate_type',
        'app_id',
        'view_url',
        'signer_id',
        'signature_id',
        'is_enable',
        'show_name',
        'show_position',
        'signer_name',
        'signer_position',
        'signer_order',
        'file_path',
        'page_no',
        'pos_x',
        'pos_y',
        'linesapce',
        'approval',
    ];

    public function signer()
    {
        return $this->belongsTo(Signer::class, 'signer_id');
    }

    public function boardAuditor()
    {
        if($this->certificate_type == 0){
            return $this->belongsTo(CertiCBAuditors::class, 'board_auditor_id', 'id');
        }else if($this->certificate_type == 1){
            return $this->belongsTo(CertiIBAuditors::class, 'board_auditor_id', 'id');
        }else if($this->certificate_type == 2){
            return $this->belongsTo(BoardAuditor::class, 'board_auditor_id', 'id');
        }
        
    }


}
