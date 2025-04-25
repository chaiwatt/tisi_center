<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bcertify\Formula;
use App\User;
use App\Models\Sso\User AS SSO_User;

class CertiCBFormulas  extends Model
{
    protected $table = 'app_certi_cb_formulas';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'title', 'formulas_id','image','imagery','created_by','updated_by'
                          ];

 public function FormulaTo()
 {
     return $this->belongsTo(Formula::class,'formulas_id');
 }
 public function user_created(){
    return $this->belongsTo(SSO_User::class, 'created_by');
  }

  public function user_updated(){
    return $this->belongsTo(SSO_User::class, 'updated_by');
  }

    public function getCreatedNameAttribute() {
        return @$this->user_created->name;
    }

}
