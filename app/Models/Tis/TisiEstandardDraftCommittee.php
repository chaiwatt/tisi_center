<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tis\CommitteeSpecials;

class TisiEstandardDraftCommittee extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft_committee';

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
    protected $fillable = ['draft_id', 'committee_id'];
    
    public function committee_specials(){
        return $this->belongsTo(CommitteeSpecials::class, 'committee_id');
    }
    
    public function getCommitteeNameAttribute() {
        return @$this->committee_specials->committee_group;
    }

}
