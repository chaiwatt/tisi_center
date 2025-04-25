<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Section5\ApplicationLabGazette;
use App\Models\Section5\ApplicationLab;

class ApplicationLabGazetteDetail extends Model
{
    protected $table = 'section5_application_labs_gazette_details';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'app_lab_id',
        'application_no',
        'app_gazette_id'  
    ];

    public function app_gazette(){
        return $this->belongsTo(ApplicationLabGazette::class, 'app_gazette_id', 'id');
    }  

    public function app_lab(){
        return $this->belongsTo(ApplicationLab::class, 'app_lab_id', 'id');
    }  
    
}
