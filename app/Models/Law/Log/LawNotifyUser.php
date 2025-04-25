<?php

namespace App\Models\Law\Log;

use Illuminate\Database\Eloquent\Model;
use App\User;
class LawNotifyUser extends Model
{
           /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_notify_user';

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
    protected $fillable = ['law_notify_id','user_register','name','read_type','marked'];
    
    public function user_created(){
        return $this->belongsTo(User::class, 'user_register');
    }
}
