<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;

class CertiLabPlace extends Model
{
    //
    protected $table = "app_certi_lab_place";

    public function certi_lab()
    {
        $this->belongsTo(CertiLab::class ,'app_certi_lab_id' );
    }
}
