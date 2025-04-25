<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

class ApplicationLabSummary extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_application_labs_summary';

    protected $primaryKey = 'id';

    protected $fillable = [
                            'meeting_date',
                            'meeting_no',
                            'meeting_description',
                            'created_by',
                            'updated_by'
        
                        ];

}
