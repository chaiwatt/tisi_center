<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\AttachmentType;
use Storage;

use App\Models\Section5\ApplicationIbcbBoardApprove;
use App\Models\Section5\IbcbsGazette;


class AttachFile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attach_files';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['tax_number', 'username', 'systems', 'ref_table', 'ref_id','url', 'filename', 'new_filename', 'size', 'caption','section', 'setting_file_id', 'file_properties', 'created_by', 'updated_by'];


    public function application_ibcb_board_approve()
    {
        return $this->belongsTo(ApplicationIbcbBoardApprove::class, 'ref_id', 'id'  );
    }

    public function ibcbs_gazette()
    {
        return $this->belongsTo(IbcbsGazette::class, 'ref_id', 'id'  );
    }

}
