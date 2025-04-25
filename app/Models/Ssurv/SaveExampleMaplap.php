<?php

namespace App\Models\Ssurv;

use App\Models\Section5\Labs;
use App\Models\Bsection5\TestItem;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Ssurv\SaveExampleFile;
use App\Models\Esurv\TisiLicenseDetail;
use Illuminate\Database\Eloquent\Model;

class SaveExampleMaplap extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'save_example_map_lap';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['num_row', 'name_lap', 'detail_product', 'detail_product_maplap', 'no_example_id', 'status', 'example_id','tis_standard',
                            'user_create','licensee','remark'];

    /*
      Sorting
    */
    public $sortable = ['num_row', 'name_lap', 'detail_product', 'detail_product_maplap', 'no_example_id', 'status', 'example_id','tis_standard',
                        'user_create','licensee' ,'remark'];

    public function example(){
        return $this->belongsTo(SaveExample::class, 'example_id', 'id');
    }

    public function tis(){
        return $this->belongsTo(Tis::class, 'tis_standard','tb3_Tisno');
    }

    public function lab(){
        return $this->belongsTo(Labs::class, 'detail_product'); //detail_product = id Lab
    }

    //ไฟล์แนบผลทดสอบ
    public function example_file(){
        return $this->belongsTo(SaveExampleFile::class, 'id', 'example_id_no');
    }

    public function details(){
        return $this->hasMany(SaveExampleMapLapDetail::class, 'maplap_id');
    }

    public function license_detail(){
        return $this->belongsTo(TisiLicenseDetail::class, 'detail_product_maplap');
    }

    public function save_example_map_lap_self(){
        return $this->hasMany(self::class, 'no_example_id', 'no_example_id');
    }

    public function getDetailItemHtmlAttribute(){
        $details = $this->details;
        $html = '';
        foreach($details as $detail){
            $test_item = $detail->test_item;
            if(!is_null($test_item)){
                $parent = $test_item->test_item_parent;
                if(is_null($parent)){
                    $html .= '<div>'.$test_item->no.' '.$test_item->title.'</div>';
                }else{
                    $html .='<div>'.
                                 $test_item->no.' '.$test_item->title.
                                ' (ภายใต้ ' . $parent->no.' '.$parent->title .')'.
                            '</div>';
                }
            }
        }
        return $html;
    }

    public function labs_test_item()
    {
        return $this->belongsToMany(TestItem::class, (new SaveExampleMapLapDetail)->getTable() , 'maplap_id', 'test_item_id');
    }

    public function status_list(){
        return [
                '1' => 'นำส่งตัวอย่าง',
                '2' => 'อยู่ระหว่างดำเนินการ',
                '3' => 'ส่งผลการทดสอบ',
                '4' => 'ไม่รับเรื่อง',
                'ยกเลิก' => 'ยกเลิก',
                '-' => '-',
               ];
    }

    public function getSizeDetialAttribute(){
        return @$this->license_detail->sizeDetial;
    }

    public function getDetailItemExportWordAttribute(){
        $resault = null;
        foreach($this->details as $detail){
            $test_item = $detail->test_item;
            if(!empty($test_item)){
                $parent = $test_item->test_item_parent;
                if(empty($parent)){
                    $resault .= $test_item->no.' '.$test_item->title;
                }else{
                    $resault .= $test_item->no.' '.$test_item->title.'<w:br/>( ภายใต้' .$parent->no.' '.$parent->title .')';
                }
            }
        }
        return $resault;
    }

    public function getSelfSizeDetialAttribute(){
        $resault = null;
        foreach($this->save_example_map_lap_self as $self){
            $resault .= $self->SizeDetial;
        }
        return $resault;
    }

    public function getSelfSizeDetialExportWordAttribute(){
        $resault = null;
        $count = $this->save_example_map_lap_self->count();
        foreach($this->save_example_map_lap_self as $key=>$self){
            $br = (($key++) && $key==$count)?'':'<w:br/>';
            $resault .= !empty($self->SizeDetial)?'<w:sym w:font="Wingdings" w:char="F09F"/> '.$self->SizeDetial.$br:null;
        }
        return str_replace('<br/>', '', $resault);
    }

    public function getSelfSizeDetialExportWord2Attribute(){
        $result = null;
        foreach($this->save_example_map_lap_self as $self){
            $result .= !empty($self->SizeDetial)?$self->SizeDetial:null;
        }
        return $result;
    }

    public function getSelfDetailItemExportWordAttribute(){
        $resault = null;
        foreach($this->save_example_map_lap_self as $self){
            $resault .= $self->DetailItemExportWord;
        }
        return $resault;
    }

}
