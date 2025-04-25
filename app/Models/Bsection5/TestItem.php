<?php

namespace App\Models\Bsection5;

use App\Models\Basic\Tis;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Bsection5\TestMethod;
use App\Models\Bsection5\Unit;
use App\Models\Bsection5\TestTool;
use App\Models\Bsection5\TestItemTools;

use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\LabsScope;
use Illuminate\Support\Facades\DB;

class TestItem extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_test_item';

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
    protected $fillable = [ 'tis_id', 'tis_tisno', 'title', 'type', 'no',  'unit_id', 'parent_id', 'test_method_id',  'test_tools_id', 'input_result', 'test_summary', 'state', 'main_topic_id', 'level', 'criteria', 'amount_test_list', 'format_result', 'format_result_detail', 'created_by', 'updated_by' ];

    /*
      Sorting
    */
    public $sortable = [ 'tis_id', 'tis_tisno', 'title', 'type', 'no',  'unit_id', 'parent_id', 'test_method_id',  'test_tools_id', 'input_result', 'test_summary', 'state', 'main_topic_id', 'level', 'criteria', 'amount_test_list', 'format_result', 'format_result_detail', 'created_by', 'updated_by'];

        /*
      User Relation
    */
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function getTypeNameAttribute() {
        $type_arr = [ '1' => 'หัวข้อทดสอบ', '2' => 'หัวข้อทดสอบย่อย', '3' => 'รายการทดสอบ' ];
        return array_key_exists( $this->type,  $type_arr )? $type_arr[ $this->type ]:null;
    }

    public function standard(){
        return $this->belongsTo(Tis::class, 'tis_id');
    }

    public function test_method(){
        return $this->belongsTo(TestMethod::class, 'test_method_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function test_tools(){
        return $this->belongsToMany(TestTool::class, (new TestItemTools)->getTable(), 'bsection5_test_item_id', 'test_tools_id');
    }


    /* Btn Switch Input*/
    public function getStateIconAttribute(){

        $btn = '';
        if ($this->state == 1) {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'" checked></div>';
        }else {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'"></div>';
        }
        return $btn;

  	}

    public function TestItemToolsData()
    {
        return $this->hasMany(TestItemTools::class,'bsection5_test_item_id');
    }

    public function getToolsNameAttribute() {

        $tools = $this->TestItemToolsData;

        $txt = '';
        $i =0;
        foreach(  $tools as $k => $item ){
            if(!empty($item->ToolsName)){
                $i++;
                $txt .= '<div>'.($i).'. '.(!empty($item->ToolsName)?$item->ToolsName:null).'</div>';
            }
        }

        return @$txt;
    }

    public function test_item_parent(){
        return $this->belongsTo(TestItem::class, 'parent_id');
    }

    public function test_item_main(){
        return $this->belongsTo(TestItem::class, 'main_topic_id');
    }

    public function TestItemParentData()
    {
        return $this->hasMany(TestItem::class,'parent_id', 'id')->orderby(DB::raw("CAST( replace(no,'.','') AS UNSIGNED )"));
    }

    public function main_test_item_parent_data()
    {
        return $this->hasMany(TestItem::class,'main_topic_id', 'main_topic_id');
    }

    public function getItemHtmlAttribute(){

        $html = '';
        $parent = $this->test_item_parent;
        if(is_null($parent)){
            $html .= '<div>'.$this->no.' '.$this->title.'</div>';
        }else{
            $html .= '<div>'.
                        $this->no.' '.$this->title.
                        ' (ภายใต้ ' . $parent->no.' '.$parent->title .')'.
                     '</div>';
        }
        return $html;
    }

    public function getTestItemHtmlAttribute(){

        $html = '';
        $parent = $this->test_item_parent;
        if(is_null($parent)){
            $html .= $this->no.' '.$this->title;
        }else{
            $html .=  $this->no.' '.$this->title. ' (ภายใต้ ' . $parent->no.' '.$parent->title .')';
        }
        return $html;
    }

    public function app_lab_scope(){
        return $this->belongsTo(ApplicationLabScope::class,'id', 'test_item_id');
    }

    public function lab_scope(){
        return $this->belongsTo(LabsScope::class,'id', 'test_item_id');
    }

    public function getLabScopeRemarkAttribute(){
        return (!empty($this->app_lab_scope->remark) && $this->app_lab_scope->remark!='-')? '(หมายเหตุ : '.$this->app_lab_scope->remark.')' : '';
    }

    static function format_result_list(){
        return [
                'integer' => 'เลขจำนวนเต็ม',
                'integer_range' => 'เลขจำนวนเต็ม (เป็นช่วง)',
                'decimal' => 'เลขทศนิยม',
                'decimal_range' => 'เลขทศนิยม (เป็นช่วง)',
                'select' => 'ตัวเลือก(เลือกได้ค่าเดียว)',
                'select_multiple' => 'ตัวเลือก (เลือกได้หลายค่า)',
                'text' => 'ข้อความ',
                'mix' => 'รวมหลายรูปแบบ'
               ];
    }

}
