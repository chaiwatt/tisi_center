<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
class CostDetails extends Model
{
    use Sortable;

    protected $table = "cost_details";
    protected $primaryKey = 'id';
    protected $fillable = [
        'title','lab','ib','cb', 'created_by', 'updated_by'
    ];
   
    public function UserCreateTo()
    {
        return $this->belongsTo(User::class,'created_by','runrecno');
    }   
    public function UserUpdateTo()
    {
        return $this->belongsTo(User::class,'updated_by','runrecno');
    }   

    public function getAgencyGroupAttribute() {
        $details =   [];
        if(!is_null($this->lab)){
           $details[] =  "LAB";
        }
        if(!is_null($this->ib)){
            $details[] =  "IB";
         }
         if(!is_null($this->cb)){
            $details[] = "CB";
         }
        return  implode(", ",$details) ?? null;
    }
}
