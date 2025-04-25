<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\ApplicationLabGazetteDetail;

class ApplicationLabGazette extends Model
{
    protected $table = 'section5_application_labs_gazette';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'issue',
        'year',
        'announcement_date',
        'sign_id',
        'sign_name',
        'sign_position',
        'created_by',
        'updated_by'
        
    ];

    public function gazette_detail(){
        return $this->hasMany(ApplicationLabGazetteDetail::class, 'app_gazette_id');
    } 
}
