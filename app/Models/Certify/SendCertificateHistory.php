<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\User;
class SendCertificateHistory extends Model
{
    use Sortable;
    protected $table = "certify_send_certificate_history";
    protected $primaryKey = 'id';
    protected $fillable = [
        'send_certificate_list_id',
        'status',
        'certificate_type',
        'certificate_tb',
        'certificate_id',
        'certificate_path',
        'certificate_file',
        'certificate_newfile' ,
        'documentId' ,
        'signtureid' ,
        'app_no',
        'name' ,
        'tax_id' ,
        'sign_id' ,
        'certificate_no',
        'created_by',
        'updated_by',
        'status_revoke',
        'date_revoke',
        'reason_revoke',
        'user_revoke',
        'certificate_oldfile'
    ];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCertifyTitleAttribute() {
        $data =  ['1'=>'หน่วยรับรอง','2'=>'หน่วยตรวจสอบ','3'=>'ห้องปฏิบัติการ','4'=>'ห้องปฏิบัติการ(ต่อตาม)','5'=>'หน่วยตรวจสอบ(ต่อตาม)','6'=>'หน่วยรับรอง(ต่อตาม)'];
        return  array_key_exists($this->certificate_type,$data) ? $data[$this->certificate_type] : null;
    }

    public function status_list() {
        return [ '1' => 'สำเร็จ', '2' => 'ไม่สำเร็จ' ];
    }

    public function status_css() {
        return [ 1 => 'text-success', 2 => 'text-danger' ];
    }

    public function getStatusHtmlAttribute() {
        return array_key_exists($this->status, $this->status_list()) ? '<span class="'.$this->status_css()[$this->status].'">'.$this->status_list()[$this->status].'</span>' : '-';
    }

}
