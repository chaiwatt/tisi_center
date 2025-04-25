<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Sso\User AS SSO_User;

class ApplicantCB extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applicant_cb';

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
    protected $fillable = ['title', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['title', 'state', 'created_by', 'updated_by'];



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
}
