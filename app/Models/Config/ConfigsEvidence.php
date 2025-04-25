<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Config\ConfigsEvidenceGroup;

class ConfigsEvidence extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'configs_evidences';

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
    protected $fillable = [
                            'evidence_group_id',
                            'title',
                            'required',
                            'caption',
                            'file_properties',
                            'size',
                            'state',
                            'ordering',
                            'bytes',
                            'created_by',
                            'updated_by',
                            'section'

                        ];
                        
    public function configs_evidence_groups(){
        return $this->belongsTo(ConfigsEvidenceGroup::class, 'evidence_group_id');
    }

}
