<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Tis\Standard;

class ApplicationInspectionUnitStandard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sso_application_inspection_units_std';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'tis_standards_id',
                            'app_units_id'
                        ];

    public function tis_standards(){
        return $this->belongsTo(Standard::class, 'tis_standards_id');
    }   

    public function getStandardTisNoAttribute() {
        return !empty($this->tis_standards)?$this->tis_standards->tis_tisno:null;
    }

    public function getStandardTisTitleAttribute() {
        return !empty($this->tis_standards)?$this->tis_standards->title:null;
    }
}
