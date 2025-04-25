<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Asurv\Applicant21BisProductDetail;
use App\Models\Sso\User AS SSO_User;

class Applicant21Bis extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_applicant_21biss';

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
    protected $fillable = ['state', 'consider', 'consider_comment','trader_id','agent_id'];

    /*
      Sorting
    */
    public $sortable = ['ref_no', 'title', 'different_no', 'reason', 'foreign_standard_ref', 'country_made', 'start_date', 'end_date', 'company_order', 'attach_product_plan', 'attach_hiring_book', 'attach_drawing', 'attach_enumerate', 'attach_other', 'remark', 'state', 'created_by', 'updated_by','trader_id','agent_id'];

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(SSO_User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(SSO_User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
  		return $this->user_created->name;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->name;
  	}

    /* License */
    public function detail_list(){
      return $this->hasMany(Applicant21BisProductDetail::class, 'applicant_21bis_id');
    }

}
