<?php

namespace App\Models\Bcertify;

use Kyslik\ColumnSortable\Sortable;
use App\Models\Bcertify\LabCalRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bcertify\LabCalMeasurement;

class LabCalTransaction extends Model
{
    use Sortable;
    protected $table = 'lab_cal_transactions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lab_cal_request_id','key', 'index', 'category',
        'category_th', 'instrument', 'instrument_text',
        'instrument_two', 'instrument_two_text', 'description',
        'standard', 'code'
    ];

    public function labCalRequest()
    {
        return $this->belongsTo(LabCalRequest::class, 'lab_cal_request_id', 'id');
    }

    public function labCalMeasurements()
    {
        return $this->hasMany(LabCalMeasurement::class, 'lab_cal_transaction_id', 'id');
    }
}
