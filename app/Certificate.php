<?php

namespace App;

use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Bcertify\InspectBranch;
use App\Models\Bcertify\TestBranch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certificate extends Model
{
    // assessment type = 1=CB,2=IB,3=LABทดสอบ,4=LABสอบเทียบ

    // certificate_option = option1=ออกใบรับรองในระบบ,option2=ออกใบรับรองนอกระบบ
    // '0=ถาวร,1=ชั่วคราว,2=เคลื่อนที่,3=นอกสถานที่'
    protected $fillable = [
        'certificate_option',
        'request_number',
        'unit_name',
        'lab_status',
        'assessment_type',
        'certificate_file_number',
        'certificate_number',
        'formula_id',
        'certified_date',
        'certified_exp',
        'certificate_file',
        'user_id',
        'state',
        'token'
    ];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function get_formula()
    {
        return $this->hasOne('App\Models\Bcertify\Formula','id','formula_id');
    }

    public function get_branch(){
        $standardNumber = $this->assessment_type;
        $branchValue = array();
        $branches = DB::table('certificate_branches')->select("*")->where('certificate_id',$this->id)->get();
        foreach ($branches as $bb){
            if ($standardNumber == '2'){
                $branch = InspectBranch::whereId($bb->branch_id)->first();
            }elseif ($standardNumber == '1'){
                $branch = CertificationBranch::whereId($bb->branch_id)->first();
            }elseif ($standardNumber == '3'){
                $branch = TestBranch::whereId($bb->branch_id)->first();
            }elseif ($standardNumber == '4'){
                $branch = CalibrationBranch::whereId($bb->branch_id)->first();
            }
            array_push($branchValue,$branch);
        }

        return $branchValue;
    }

    public function getLabStatus()
    {
        $meaning = ['ถาวร','ชั่วคราว','เคลื่อนที่','นอกสถานที่'];
        $status = [];
        if ($this->lab_status){
            $answer = unserialize($this->lab_status);
            foreach ($answer as $put){
                array_push($status,$meaning[$put]);
            }
        }
        return $status;
    }

    public function get_formulaTH_EN()
    {
        $formula = $this->get_formula;
        $name = $formula->title.' ('.$formula->title_en.')';
        return $name;
    }

    public function get_user()
    {
        return $this->hasOne('App\User','runrecno','user_id');
    }

    public function user_FullName()
    {
        $user = $this->get_user()->first();
        $name = $user->reg_fname.' '.$user->reg_lname;
        return $name;
    }

    public function get_certificateOption()
    {
        $option = ['ในระบบ','นอกระบบ'];
        $certificate_option = null;
        if ($this->certificate_option == 'option1'){
            $certificate_option = $option[0];
        }elseif ($this->certificate_option == 'option2'){
            $certificate_option = $option[1];
        }
        return $certificate_option;
    }

    public function assessment_type()
    {
        $assessment_list = ['CB','IB','LAB','LAB'];
        $assessment = $this->assessment_type-1;
        return $assessment_list[$assessment];
    }

    public function checkExpire()
    {
        $certificate = $this;
        $exp = Carbon::parse($certificate->certified_exp);
        $now = Carbon::today();
        $toShow = null;
        $color = '';
        if ($exp == $now){
            $toShow = $exp->diffInDays($now);;
        }elseif($exp > $now){
            $far = $exp->diffInDays($now);
            $toShow = $far;
            $color = $this->getColorEXP($toShow);
        }else{
            $toShow = '-'.$exp->diffInDays($now);
        }
        return (int)$toShow.'$'.$color;
    }

    public function getColorEXP($between_date)
    {
        $color = '';
        $under = DB::table('certificate_alert')->select('*')->where('condition','under')->first();
        $between = DB::table('certificate_alert')->select('*')->where('condition','between')->first();
        $over = DB::table('certificate_alert')->select('*')->where('condition','over')->first();
        if ($under){
            if ($between_date < $under->date_start) {
                if ($under->status != 'off'){
                    $color = $this->getBG($under->color);
                }
            }
        }
        if ($between){
            if ($between_date >= $between->date_start && $between_date <= $between->date_end) {
                if ($between->status != 'off'){
                    $color = $this->getBG($between->color);
                }
            }
        }
        if ($over){
            if ($between_date > $over->date_start) {
                if ($over->status != 'off'){
                    $color = $this->getBG($over->color);
                }
            }
        }
        return $color;
    }

    public function getBG($word)
    {
        if ($word == 'red'){
            return 'bg-danger';
        }
        if ($word == 'yellow'){
            return 'bg-warning';
        }
        return 'bg-success';
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($certificate) {
            DB::table('certificate_branches')->where('certificate_id',$certificate->id)->delete();
            DB::table('other_certificate_files')->where('certificate_id',$certificate->id)->delete();
        });
    }
}
