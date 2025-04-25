<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\UnitCode;
use App\Models\Bsection5\TestItem;

class Tis extends Model
{
    use Sortable;

    //table name
    protected $table = 'tb3_tis';

    //primary
    protected $primaryKey = 'tb3_TisAutono';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['unitcode_id', 'id_unit', 'updated_at', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['tb3_Tisno', 'tb3_TisThainame', 'unitcode_id', 'id_unit', 'updated_at', 'updated_by'];

    /*
      User Relation
    */
    public function user_updated(){
      	return $this->belongsTo(User::class, 'updated_by');
    }

    public function getUpdatedNameAttribute() {
      	return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    /*
      Unit Code Relation
    */
    public function unit_code(){
      	return $this->belongsTo(UnitCode::class, 'id_unit', 'id_unit');
    }

    public function test_item_data()
    {
        return $this->hasMany(TestItem::class, 'tis_id', 'tb3_TisAutono');
    }

	static function standard_format_list(){
		return ['บ' => 'มาตรฐานบังคับ', 'ท' => 'มาตรฐานทั่วไป', 'อ' => 'มาตรฐานบังคับแต่ไม่นับรวม'];
	}

	public function getStandardFormatAttribute(){
		$standard_format_list = self::standard_format_list();
		return array_key_exists($this->tb3_Tisforce, $standard_format_list) ? $standard_format_list[$this->tb3_Tisforce] : '-' ;
	}

	static function standard_status_list(){
		return [
				'-1' => 'ออกประกาศกระทรวง', 
				'0' => 'ประกาศฯ ลงราชกิจจานุเบกษา', 
				'1' => 'มอก.มิผลใช้งาน/มิผลบังคับ',
				'2' => 'ออกกฎกระทรวงเป็น มอก.บังคับ',
				'3' => 'กฎกระทรวงลงราชกิจจานุเบกษา',
				'4' => 'มอก.ยกเลิกแต่มีผู้ขอขยายตาม ม.27(3)',
				'5' => 'มอก.ยกเลิก'
			   ];
	}

	public function getStandardStatusAttribute(){
		$standard_status_list = self::standard_status_list();
		return array_key_exists($this->status, $standard_status_list) ? $standard_status_list[$this->status] : '-' ;
	}

}
