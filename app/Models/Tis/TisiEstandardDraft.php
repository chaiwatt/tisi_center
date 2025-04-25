<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
use App\User;
class TisiEstandardDraft extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft';

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
    protected $fillable = ['draft_year', 'status_id', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable =   ['draft_year', 'status_id', 'created_by', 'updated_by'];

    public function user_created(){
       return $this->belongsTo(User::class, 'created_by');
     }


    public function user_updated(){
       return $this->belongsTo(User::class, 'updated_by');
     }

     public function TisiEstandardDraftBoardMany()
     {
         return $this->hasMany(TisiEstandardDraftBoard::class, 'draft_id');
     }

    public function TisiEstandardDraftCommitteeMany()
    {
        return $this->hasMany(TisiEstandardDraftCommittee::class, 'draft_id');
    }

     public function TisiEstandardDraftPlanMany()
     {
         return $this->hasMany(TisiEstandardDraftPlan::class, 'draft_id');
     }

    public function getCommitteeTitleAttribute() {
        return $this->TisiEstandardDraftCommitteeMany->pluck('CommitteeName')->implode(', ');
    }

    public function getAssignNameAttribute() {
        return $this->TisiEstandardDraftPlanMany->pluck('AssignName')->implode(', ');
    }

    public function getCommitteeNameAttribute() {
      $data = HP::getArrayFormSecondLevel($this->TisiEstandardDraftBoardMany->toArray(), 'committee_id');
      foreach ($data as $key => $list) {
            $datas[$key] = $list ;
      }
      return  $datas ?? [];
    }

     public function getStatusNameAttribute(){
      $arr = ['1'=>'ร่างมาตรฐาน', '2'=>'เห็นชอบร่างมาตรฐาน', '3'=>'ไม่เห็นชอบร่างมาตรฐาน'];
      return array_key_exists( $this->status_id,$arr) ?  $arr[$this->status_id] : 'n/a'  ;
    }


}
