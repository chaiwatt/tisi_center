<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Agent extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sso_agent';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'user_id',
                            'user_taxid',
                            'agent_id',
                            'agent_taxid',
                            'select_all',
                            'issue_type',
                            'start_date',
                            'end_date',
                            'state' ,
                            'head_name','head_address_no','head_village','head_moo','head_soi','head_subdistrict','head_district','head_province','head_telephone',
                            'agent_name','agent_address_no','agent_village','agent_moo','agent_soi','agent_subdistrict','agent_district','agent_province','agent_telephone',
                            'confirm_status','confirm_date','revoke_date','revoke_detail','revoke_by', 'head_street', 'agent_street','remarks_delete', 'delete_by', 'delete_at','head_mobile', 'agent_mobile'
                        ];


}
