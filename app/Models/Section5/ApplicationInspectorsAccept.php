<?php

namespace App\Models\Section5;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ApplicationInspectorsAccept extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_application_inspectors_accept';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
                            'application_id',
                            'application_no',
                            'application_status',
                            'description',
                            'send_mail_status',
                            'noti_email',
                            'created_by',
                            'updated_by'
                        ];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // ผู้รับรับคำขอ
    public function getRequestRecipientAttribute(){
        return $this->user_created->FullName;
    }

    // วันที่รับคำขอครั้งแรก รูปแบบ 31/01/2565
    public function getDateOfFirstRequestAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->created_at, 'Y-m-d H:i:s')){
            $date = Carbon::parse($this->created_at)->addYear(543)->format('d/m/Y');
        }
        return $date;
    }

    // วันที่รับคำขอครั้งแรก รูปแบบ 31 มกราคม 2565
    public function getDateOfFirstRequestFullAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->created_at, 'Y-m-d H:i:s')){
            $date = Carbon::parse($this->created_at)->addYear(543)->isoFormat('D MMMM YYYY');
        }
        return $date;
    }

    
    public function section5_application_inspectors_status(){
        return $this->belongsTo(ApplicationInspectorStatus::class, 'application_status')->withDefault();
    }  

    public function getAppStatusAttribute(){
        $status = @$this->section5_application_inspectors_status->title;
        if($this->application_status == 11 && !empty($this->remarks_delete)){
            $status .= "<br>({$this->remarks_delete})";
        }
        return $status;
    }

}
