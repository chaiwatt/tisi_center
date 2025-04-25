<?php

namespace App\Models\Law\Cases;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Basic\LawProcessProduct;
use App\Models\Law\Cases\LawCaseProductOperations;

class LawCasesProductResult extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_cases_product_results';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable =
    [
        'law_cases_id',
        'status_product',
        'result_process_product_id',
        'result_description',
        'result_start_date',
        'result_end_date',
        'result_amount',
        'result_remark',
        'result_by',
        'result_at',
        'created_at',
        'updated_at'
        
    ];

    public function file_law_result()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','law_cases_product_results');
    }

    public function bs_process_product()
    {
        return $this->belongsTo(LawProcessProduct::class,'result_process_product_id');
    }

    public function law_case_product_operations(){
        return $this->hasMany(LawCaseProductOperations::class, 'law_cases_product_results_id');
    }

    public function operations_to()
    {
        return $this->belongsTo(LawCaseProductOperations::class,'id','law_cases_product_results_id')->orderby('id','desc');
    }

    public function getProcessProductNameAttribute() {
        return @$this->bs_process_product->title;
    }
}
