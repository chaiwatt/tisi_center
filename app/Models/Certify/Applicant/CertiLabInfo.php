<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;

class CertiLabInfo extends Model
{
    protected $table = "app_certi_lab_info";
    protected $fillable = ['activity_client_name', 'file_client_name' ];
    public function certi_lab()
    {
        $this->belongsTo(CertiLab::class ,'app_certi_lab_id' );
    }
}
 