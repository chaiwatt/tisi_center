<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabSummary;

class ApplicationLabSummaryDetail extends Model
{
            /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_application_labs_summary_details';

    protected $primaryKey = 'id';

    protected $fillable = [
                        
                            'app_summary_id',
                            'application_lab_id',
                            'application_no',
                            'meeting_no',
                            'agenda_no',
                            'state'

                        ];

    public function app_lab(){
        return $this->belongsTo(ApplicationLab::class, 'application_lab_id', 'id');
    }  

    public function app_summary(){
        return $this->belongsTo(ApplicationLabSummary::class, 'app_summary_id', 'id');
    }  

}
