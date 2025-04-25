<?php

namespace App\Models\Law\File;

use Illuminate\Database\Eloquent\Model;

use App\Models\Law\Cases\LawCasesForm;
use App\User;
class AttachFileLaw extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_attach_files';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *W
     * @var array
     */
    protected $fillable = ['tax_number', 'username', 'systems', 'ref_table', 'ref_id','url', 'filename', 'new_filename', 'size', 'caption','section', 'setting_file_id', 'file_properties', 'created_by', 'updated_by'];

    public function law_cases(){

        $ref_table = (new LawCasesForm )->getTable();
        $ref_id    = $this->ref_id;

        return $this->belongsTo(LawCasesForm::class,'ref_id','id')
                        ->where('id',  $ref_id)
                        ->whereHas('attach_files', function($query) use($ref_table,$ref_id){
                            $query->where('ref_table', $ref_table )
                                ->where('ref_id', $ref_id );
                        });
    }

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
      }
}
