<?php

namespace App\Models\Law\Cases;

use HP;
use App\User;
use Carbon\Carbon;
use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;

use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
class LawCasesLevelApprove extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_level_approves';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $appends  = ['status_text'];
        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'law_cases_id',
                            'level',
                            'role',
                            'send_department',
                            'authorize_name',
                            'position',
                            'acting',
                            'authorize_userid',
                            'remark',
                            'status',
                            'created_by',
                            'updated_by',
                            'causes', 
                            'return_to'
                            
                        ];
                        
    public function law_cases(){
      return $this->belongsTo(LawCasesForm::class, 'law_cases_id');
    }

    public function department(){
      return $this->belongsTo(Department::class, 'send_department', 'did');
    }

    // public function getCreatedAtAttribute(){
    //   return @HP::DateTimeThai($this->created_at);
    // }

    public function getFormatCreateAtTimeAttribute(){
        if(Carbon::hasFormat($this->updated_at, 'Y-m-d H:i:s')){
            $carbon = Carbon::parse($this->updated_at);
            $time = $carbon->format('H:i น.');
            $day_month = $carbon->isoFormat('D MMM');
            $year = $carbon->addYear(543)->format('Y');
            return sprintf('%s %s %s', $day_month, $year, $time);
        }
    }

    public static function status_list() {
      return [ 
                  '1' => 'รอดำเนินการ',
                  '2' => 'รอพิจารณา',
                  '3' => 'เห็นควร',
                  '4' => 'ไม่เห็นควร',
              ];
    }

    public function getStatusTextAttribute() {
        $list = self::status_list();
        $text = array_key_exists($this->status,$list)?$list[$this->status]:null;
      return $text;
    }

    public function user_authorize_userid(){
        return $this->belongsTo(User::class, 'authorize_userid');
    }

    // แนบไฟล์
    public function file_law_cases_approves_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','forms_approved')->orderby('id','desc');
    }

}

