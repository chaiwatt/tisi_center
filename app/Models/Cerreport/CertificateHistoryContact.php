<?php


namespace App\Models\Cerreport;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class CertificateHistoryContact extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'app_certi_certificate_history_contact';

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
    protected $fillable = ['ref_id', 'ref_table', 'contact_name', 'contact_tel', 'contact_mobile', 'contact_email', 'created_by'];

    /*
      Sorting
    */
    public $sortable = ['ref_id', 'ref_table', 'contact_name', 'contact_tel', 'contact_mobile', 'contact_email', 'created_by'];

    

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
    
}
