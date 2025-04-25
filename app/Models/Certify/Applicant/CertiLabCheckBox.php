<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;

class CertiLabCheckBox extends Model
{
    //

    protected $table = "app_certi_lab_check_box";


    public function checkboximage()
    {
        $this->hasMany(CertiLabCheckBoxImage::class,'app_certi_lab_check_box_id');
    }

    public function certi_lab()
    {
        $this->belongsTo(CertiLab::class ,'app_certi_lab_id' );
    }
}
