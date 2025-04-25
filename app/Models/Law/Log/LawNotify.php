<?php

namespace App\Models\Law\Log;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Law\Log\LawNotifyUser;
use App\Models\Law\Log\LawSystemCategory;
use Illuminate\Support\Facades\Auth;

class LawNotify extends Model
{
           /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_notify';

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
    protected $fillable = ['law_system_category_id', 'ref_table', 'ref_id', 'name_system', 'title', 'content', 'channel', 'notify_type', 'email', 'created_by'];
    
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }

    public function notify_user_by_user(){
        return $this->belongsTo(LawNotifyUser::class, 'id', 'law_notify_id')->where('user_register', Auth::user()->getkey() );
    }

    public function getMarkedIconAttribute() {
        $btn = '';
  
        $notify_user = $this->notify_user_by_user;

        if( !is_null($notify_user) && $notify_user->marked == 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_marked" data-id="'.($this->id).'"  data-state="0" title="Starred"><i class="fa fa-star text-warning"></i></a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_marked" data-id="'.($this->id).'"  data-state="1" title="Not starred"><i class="fa fa-star-o"></i></a>';
        }
        return $btn;
    }

    public function category(){
        return $this->belongsTo(LawSystemCategory::class, 'law_system_category_id');
    }

    public function getChannellistAttribute() {
        return !empty( $this->channel)?json_decode( $this->channel, true):[];
    }

    public function getNotifyTypelistAttribute() {
        return !empty( $this->notify_type)?json_decode( $this->notify_type, true):[];
    }

    public function getEmaillistAttribute() {
        return !empty( $this->email)?json_decode( $this->email, true):[];
    }
}
