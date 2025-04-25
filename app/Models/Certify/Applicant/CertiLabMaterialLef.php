<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;

class CertiLabMaterialLef extends Model
{
    //
    protected $table = 'app_certi_lab_material_lef';

    public function certi_lab()
    {
        $this->belongsTo(CertiLab::class ,'app_certi_lab_id' );
    }
}
