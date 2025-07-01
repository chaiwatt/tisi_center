<?php

namespace App\Models\Certify;

use App\Models\Certify\Standard;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class IsbnRequest extends Model
{
    use Sortable;

    protected $table = 'isbn_requests';

    protected $primaryKey = 'id';


    protected $fillable = ['standard_id',
                           'request_no',
                           'tistype',
                           'tisno',
                           'tisname',
                           'page',
                           'cover_file',
                           'status'
                        ];

    public function standard(){
        return $this->belongsTo(Standard::class, 'standard_id', 'id');
    }
}
