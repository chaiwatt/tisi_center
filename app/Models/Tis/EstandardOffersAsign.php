<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;
 
class EstandardOffersAsign extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_offers_asign';

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
    protected $fillable = ['comment_id', 'user_id', 'ordering', 'status', 'assign_by', 'assign_date'];


    public function user_assigns(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tisi_estandard_offers(){
        return $this->belongsTo(EstandardOffers::class, 'comment_id');
    }

    public function getEstandardOffersAsignNameAttribute(){
        return @$this->user_assigns->FullName;
    }

}
