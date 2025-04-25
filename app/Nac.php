<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Storage;
 
class Nac extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tr_nac';
    public $timestamps = false;
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'Nac_Autono';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['Nac_shortNo', 'Nac_no', 'Nac_Thainame', 'Nac_Engname', 'Nac_Gazbook','Nac_Gazno', 'Nac_Gazspace', 'Nac_Gazdate', 'Nac_Govnotifbook', 'Nac_Govnotifdate','Nac_force', 'Nac_file', 'Nac_thai_abstract', 'Nac_eng_abstract', 'Nac_enforce',
    'Nac_productgroup', 'Nac_type', 'Nac_tsic', 'Nac_isic', 'Nac_udc','Nac_ics', 'Nac_isbn', 'Nac_tc', 'Nac_historyRemark', 'Nac_std_equivalent','Nac_std_inter', 'Nac_price', 'Nac_page', 'Quality', 'in_catalog', 'add_p_by', 'bcg', 'sustainable'];

 
}
