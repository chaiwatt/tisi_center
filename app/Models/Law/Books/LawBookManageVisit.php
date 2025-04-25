<?php

namespace App\Models\Law\Books;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Law\Books\LawBookManage;

class LawBookManageVisit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_book_manage_visit';
  
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
    protected $fillable = ['section_id','law_book_manage_id', 'system_type', 'action', 'visit_at'];
       /*
      User Relation
    */

    
  }