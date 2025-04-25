<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\Applicant\CostHistory;
use App\Models\Certify\CertificateHistory;
use App\User;
use HP;
class Cost extends Model
{
    use Sortable;

    protected $table = "app_certi_lab_costs";
    protected $fillable = [
        'app_certi_assessment_id', 'app_certi_lab_id', 'checker_id', 'draft', 'remark', 'agree', 'status_scope',
        'attachs','check_status','remark_scope','amount','date','vehicle'
    ];
    public function assessment() {
        return $this->belongsTo(Assessment::class, 'app_certi_assessment_id');
    }

    public function applicant() {
        return $this->belongsTo(CertiLab::class, 'app_certi_lab_id');
    }

    public function checker() {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function dates() {
        return $this->hasMany(CostDate::class, 'app_certi_cost_id');
    }

    public function items() {
        return $this->hasMany(CostItem::class, 'app_certi_cost_id');
    }

    public function files() {
        return $this->hasMany(CostFile::class, 'app_certi_cost_id');
    }
    public function CertificateHistorys() {
        $ao = new Cost;
        return $this->hasMany(CertificateHistory::class,'ref_id', 'id')->where('table_name',$ao->getTable())->whereNotNull('check_status');
    }

    public function CostHistory() {
        return $this->hasMany(CostHistory::class, 'app_certi_cost_id');
    }
    public function getStatus() {
        $draft = ['0', '3'];
        $agree = ['1', '2'];
        if(in_array($this->agree, $agree)){
            if ($this->agree == 1) {
                return "เห็นชอบ";
            } else if ($this->agree == 2) {
                return "ไม่เห็นชอบ";
            }
        }else if(in_array($this->draft, $draft)){
            if ($this->draft == 0) {
                return "ฉบับร่าง";
            }else if ($this->draft == 3) {
                return "ยกเลิกประมาณค่าใชจ่าย";
            }
        }
        return "ขอความเห็นประมาณค่าใชจ่าย";
    }
    public function getMaxAmountDateAttribute() {
        $details =    HP::getArrayFormSecondLevel($this->items->toArray(), 'amount_date');
        $count_date = [0];
        if(count($details) > 0) {
        foreach($details  as $item){
            $amount_date = !empty($item) ? $item : 0 ;
            $count_date[] = $amount_date;
        }
        }
        return  max($count_date) ?? '-';
      }

      public function getSumAmountAttribute() {
        $data =   HP::getArrayFormSecondLevel($this->items->toArray(), 'id');
        $details = CostItem::select('amount_date','amount')->whereIn('id',$data)->get();
        $countItem = 0;
        if(count($details) > 0) {
            foreach($details  as $item){
                $amount_date = !empty($item->amount_date) ? $item->amount_date : 0 ;
                $amount = !empty($item->amount) ? $item->amount : 0 ;
                $countItem += $amount*$amount_date;
            }
        }
        return  number_format($countItem,2) ?? '-';
      }

       public function getCheckerContactAttribute() {

        $html = $this->checker->reg_fname ?? '-'." ".$this->checker->reg_lname ?? '-';
        $html .= '<br>';
        $html .= 'มือถือ : '.$this->checker->reg_phone ?? '-';
        $html .= '<br>';
        $html .= 'โทรศัพท์ : '.$this->checker->reg_wphone ?? '-';
        $html .= '<br>';
        $html .= 'E-mail : '.$this->checker->reg_email ?? '-';
        $html .= '<br>';
        $html .= 'ตำแหน่งงาน : '.$this->checker->subdepart->sub_departname ?? '-';

        return  $html ?? '-';
      }
}
