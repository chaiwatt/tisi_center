<?php

namespace App\Models\Tis;

use App\Models\Basic\SetFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Kyslik\ColumnSortable\Sortable;

class PublicDraft extends Model
{
    use Sortable;

//    status == 1 คือ เปิด

    protected $table = "tis_public_draft";

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'public_draft_type',
        'set_format_id',
        'tis_no',
        'set_standard_id',
        'product_group_id',
        'title',
        'number_book',
        'mask_date',
        'anniversary_date',
        'lock_qr',
        'basic_staff_groups_id',
        'result_draft',
        'status',
        'created_by',
        'token',
        'updated_by'
    ];

    /*
      Sorting
    */
    public $sortable = [
        'public_draft_type',
        'set_format_id',
        'tis_no',
        'set_standard_id',
        'product_group_id',
        'title',
        'number_book',
        'mask_date',
        'anniversary_date',
        'lock_qr',
        'basic_staff_groups_id',
        'result_draft',
        'status',
        'created_by',
        'updated_by'
    ];

    public function getSetFormat()
    {
        return $this->belongsTo(SetFormat::class,'set_format_id');
    }

    public function getDraft_type(){
        $type = ['เวียนร่าง','เวียนทบทวน'];
        return $type[$this->public_draft_type];
    }

    public function getNumberStandard()
    {
        $draft_type = $this->public_draft_type;
        $draft_standard = $this->set_standard_id;
        $number_formula = null;
        if ($draft_type == 0){
            $number_formula = SetStandard::where('id',$draft_standard)->first();
        }elseif ($draft_type == 1){
            $number_formula = Standard::where('id', $draft_standard)->first();
        }
        return $number_formula;
    }

    public function getStandard_Name()
    {
        $draft_type = $this->public_draft_type;
        $tis_no = $this->tis_no;
        $name_standard = null;
        if ($draft_type == 0){
            $name_standard = SetStandard::where('tis_no',$tis_no)->first();
        }elseif ($draft_type == 1){
            $name_standard = Standard::where('tis_no',$tis_no)->first();
        }
        return @$name_standard;
    }

    public function getStandardNameAttribute()
    {
        $draft_type = $this->public_draft_type;
        $set_standard_id = $this->set_standard_id;
        $name_standard = null;
        
        if ($draft_type == 0){
            $name_standard = SetStandard::where('id',$set_standard_id)->first();
        }elseif ($draft_type == 1){
            $name_standard = Standard::where('id',$set_standard_id)->first();
        }
        return $name_standard->title ?? 'n/a';
    }

    public function getStand_Branch()
    {
        $stand_branch = null;
        $name_stand = $this->getStandard_Name();
        $product_group = $name_stand->product_group ?? null;
        return @$product_group;
    }

    public function getStaff()
    {
        $product_group = $this->getStand_Branch();
        $staff = @$product_group->getStaff_ProductGroup->getStaffGroup ?? null;
        return @$staff;
    }

    public function get_user_create()
    {
        return $this->hasOne('App\User','runrecno','created_by');
    }

    public function user_FullName()
    {
        $user = $this->get_user_create;
        $name = @$user->reg_fname.' '.@$user->reg_lname;
        return $name;
    }

    public function user_FullName_update()
    {
        $user = $this->get_user_update;
        if ($user){
            $name = $user->reg_fname.' '.$user->reg_lname;
        }else{
            $name = null;
        }
        return $name;
    }

    public function get_user_update()
    {
        return $this->hasOne('App\User','runrecno','updated_by');
    }

    public function getFiles()
    {
        return DB::table('tis_public_draft_attaches')->where('public_draft_id',$this->id)->get();
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($public_draft) {
            $public_draft->removeFiles();
            DB::table('tis_public_draft_attaches')->where('public_draft_id',$public_draft->id)->delete();
        });
    }

    public function removeFiles()
    {
        $files = $this->getFiles();
        if ($files->count() > 0){
            foreach ($files as $file){
                $this->removeFromStorage($file->file_path);
            }
        }
        return true;
    }

    public function getResult_draft()
    {
        $arr = ['ใช้มาตรฐานเดิม','ทบทวนมาตรฐาน'];
        if (!is_null($this->result_draft)){
            return $arr[$this->result_draft];
        }
        return null;
    }

    public function removeFromStorage($path){
        try{
            $file = storage_path().$this->attach_path.$path;
            if (!File::exists($file)) {
                return Response::make("File does not exist.", 404);
            }
            if(is_file($file)){
                File::delete($file);
            }else {
                echo "File does not exist";
            }
            return true;
        }catch (\Exception $x){
            return false;
        }
    }

    public function  getStandardBranchTitleAttribute()
    {
        $name_stand = $this->getStandard_Name();
        return !empty($name_stand->product_group->title) ? $name_stand->product_group->title : "n/a";
    }

}
