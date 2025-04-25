<?php

namespace App;

use App\Models\Bcertify\AuditorInformation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisUserEsurv;
use App\RoleUser;
use App\Models\Elicense\RosUsers;

use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertifyLabCalibrate;
use App\Models\Certify\SetStandardUser;
use App\Models\Certify\SetStandardUserSub;
use HP;
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_register';

    protected $primaryKey = 'runrecno';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reg_13ID', 'reg_fname', 'reg_lname', 'reg_email', 'reg_phone', 'reg_wphone', 'reg_pword', 'reg_unmd5', 'reg_subdepart','position', 'reg_uname', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'reg_pword', 'remember_token',
    ];


    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function permissionsList(){
        $roles = $this->roles;
        $permissions = [];
        foreach ($roles as $role){
            $permissions[] = $role->permissions()->pluck('name')->implode(',');
        }
       return collect($permissions);
    }

    public function permissions(){
        $permissions = [];
        $role = $this->roles->first();
        $permissions = $role->permissions()->get();
        return $permissions;
    }

    public function isAdmin(){
       $is_admin =$this->roles()->where('name','admin')->first();
       if($is_admin != null){
           $is_admin = true;
       }else{
           $is_admin = false;
       }
       return $is_admin;
    }

    public function blogs(){
        return $this->hasMany(Blog::class, 'user_id');
    }

    public function getEmailForPasswordReset(){
       return $this->reg_email;
    }

    public function getPasswordForPasswordReset(){
       return $this->reg_pword;
    }

    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->reg_email;
            case 'nexmo':
                return $this->phone_number;
        }
    }


    public function IsGetIdProposal(){ // id คำขอใบรับรอง   ผู้อำนวยการกอง ของ สก.
        $is_roles =$this->roles()->where('role_id',22)->first();
        $list = [];
        if(!is_null($is_roles)){
            $Set = SetStandardUser::where('sub_department_id',$this->reg_subdepart)->first();
            $list = [];
            if(!is_null($Set)){
              $SetSub =  SetStandardUserSub::select('test_branch_id','items_id')->where('standard_user_id',$Set->id) ->get();
              if(count($SetSub) > 0){
                  if(!is_null($SetSub[0]['test_branch_id'])){
                       $CertifyTestScope = CertifyTestScope::select('app_certi_lab_id')
                                                            ->whereIn('branch_id',$SetSub->pluck('test_branch_id'))
                                                            ->groupBy('app_certi_lab_id')
                                                            ->pluck('app_certi_lab_id');
                        if(count($CertifyTestScope) > 0){
                            $list = $CertifyTestScope;
                        }
                   }
                  if(!is_null($SetSub[0]['items_id'])){
                    $CertifyLabCalibrate = CertifyLabCalibrate::select('app_certi_lab_id')
                                                                ->whereIn('branch_id',$SetSub->pluck('items_id'))
                                                                ->groupBy('app_certi_lab_id')
                                                                ->pluck('app_certi_lab_id');
                     if(count($CertifyLabCalibrate) > 0){
                         $list = $CertifyLabCalibrate;
                     }
                  }
              }
            }
        }
        return $list;
     }

     public function IsGetIdRoles(){ // id คำขอใบรับรอง
        $is_roles =$this->roles()->whereIN('role_id',[1,18,26,25])->first();   // admin , ผอ , ลท , ผู้อำนวยการกอง ของ สก
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }

     public function IsGetRolesAdmin(){ // id คำขอใบรับรอง
        $is_roles =$this->roles()->whereIN('role_id',[1,18,22])->first();  // admin , ผอ , ผก
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }

     public function IsGetIdLathRoles(){ // id คำขอใบรับรอง
        $is_roles =$this->roles()->whereIN('role_id',[26])->first();  // ลท
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }

     //  start roles IN/CB
     public function SetRolesAdminCertify(){ //  Controllers
        $is_roles =$this->roles()->whereIN('role_id',[1,11,22,25,26,30,31])->first();  // admin , Admin กอง สก. , ผู้อำนวยการกลุ่ม ของ สก. , ผู้อำนวยการกอง ของ สก.
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }
     public function SetRolesLicenseCertify(){ //  view
        $is_roles =$this->roles()->whereIN('role_id',[1,11,22,25,30,31])->first();
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }
     //  end roles IN.CB


     public function IsGetRolesDirector(){ //
        $is_roles =$this->roles()->whereIN('role_id',[1,11,18,22,25,26])->first();  //  admin , admin สก,  ผู้อำนวยการกลุ่ม ของ สก,  ผู้อำนวยการกอง ของ สก., ผอ ,ลท
        if(!is_null($is_roles)){
            return 'true';
        }else{
            return 'false';
        }
     }

     public function getUserContactAttribute() {
           $html = 'ข้อมูลติดต่อ ';
           $html .= '<br>';
           $html .=  @$this->reg_fname." ".@$this->reg_lname;
           $html .= '<br>';
           $html .= 'มือถือ : '.$this->reg_phone ?? '-';
           $html .= '<br>';
           $html .= 'โทรศัพท์ : '.$this->reg_wphone ?? '-';
           $html .= '<br>';
           $html .= 'E-mail : '.$this->reg_email ?? '-';
           $html .= '<br>';
           $html .= 'ตำแหน่งงาน : '.( !empty($this->subdepart->sub_departname) ? $this->subdepart->sub_departname : '-');
          return  $html ?? '-';
     }

    /* DataSetStandardUserList
      Sub Department Relation
    */
    public function subdepart(){
      return $this->belongsTo(SubDepartment::class, 'reg_subdepart');
    }

    public function auditor()
    {
        return $this->hasMany(AuditorInformation::class,'user_id');
    }

    public function getFullNameAttribute() {
        return "{$this->reg_fname} {$this->reg_lname}";
    }

    public function getDepartmentNameAttribute() {
        return @$this->subdepart->DepartmentName;
    }

    public function getDepartNameAttribute() {
        return @$this->subdepart->sub_departname;
    }

    public function getdidNameAttribute() {
        return @$this->subdepart->did;
    }

     public function ManyRoleUser()
    {
        return $this->hasMany(RoleUser::class,'user_runrecno','runrecno');
     }
     public function getBasicRoleUserAttribute() {
        $data = HP::getArrayFormSecondLevel($this->ManyRoleUser->toArray(), 'role_id');
        return $data;
      }
      public function data_list_role(){
        return $this->belongsTo(RoleUser::class, 'runrecno', 'user_runrecno');
        }

    public function data_list_roles(){
        return $this->hasMany(RoleUser::class, 'user_runrecno', 'runrecno');
    }

    public function getRoleListNameAttribute() {
        return @$this->data_list_role->role_id;
    }

    public function getRoleListIdAttribute() {

         $role_ids = [];
        foreach ($this->data_list_roles as $data_list_role) {
            $role_ids[] = $data_list_role->role_id;
        }

        return $role_ids;
    }

    public function getRoleIdsAttribute() {
        return $this->data_list_roles->pluck('role_id')->toArray();
    }

    //มาตรฐานที่รับผิดชอบตามกลุ่มงานย่อย
    public function getTisAttribute() {
        return !is_null($this->subdepart)?$this->subdepart->tis_users:collect([new SubDepartment]);
    }
    public function SetStandardUsers(){
        return $this->belongsTo(SetStandardUser::class, 'reg_subdepart','sub_department_id');
      }

      public function getDataSetStandardUsersAttribute() {
        $Set = SetStandardUser::where('sub_department_id',$this->reg_subdepart)->first();
        $list = [];
        if(!is_null($Set)){
          $SetSub =  SetStandardUserSub::where('standard_user_id',$Set->id)->get();
          if(count($SetSub) > 0){
            foreach ($SetSub as $item) {
                if(!is_null($item->test_branch_id)){  // ทดสอบ
                    $list[] = $item->subdepartment->title  ?? '-';
                }elseif(!is_null($item->items_id)){  // สอบเทียบ
                    $list[] = $item->TestItem->title  ?? '-';
                }
            }
          }
        }
        return  implode(",",$list) ?? '-';
    }

    public function role_users()
    {
        return $this->belongsToMany(Role::class, (new RoleUser)->getTable() , 'user_runrecno', 'role_id');
    }

    public function getGroupRoleNameAttribute()
    {
        $role =  $this->roles()->pluck('name')->toArray();
        $html = '';
        $list = [];
        foreach( $role AS $key => $item ){

            $list[  $item ] = $item;
            if( $key == 3 ){
                break;
            }
        }

        $html .= implode(', ', $list );

        if( count($role) > 4 ){
            $collapse_id = uniqid('collapse_');

            $html .=', ';

            $list_r = [];
            foreach( $role AS $key => $item ){
                if( !array_key_exists(  $item  , $list ) ){
                    $list_r[  $item ] = $item;
                }
            }

            $html .= '<span class="collapse collapse_show_span" id="'.($collapse_id).'">';
            $html .= implode(', ', $list_r );
            $html .= '</span>';

            $html .= '<a class="btn btn-link m-r-10 modal_show_role" data-name="'.( ($this->reg_fname.' '.$this->reg_lname).' <em>('.($this->reg_13ID).')</em>' ).'" data-id="'.($this->runrecno).'" data-toggle="modal" ><em>เพิ่มเติม</em></a>';
        }


        return  $html;

    }

    public function ros_user(){
        return $this->belongsTo(RosUsers::class, 'reg_uname', 'username');
    }

    public function getDepartmentIdAttribute() {
        return @$this->subdepart->DepartmentId;
    }

    public function getRoleTitleAttribute() {
        $role_arr = ['1' => 'ลมอ.', '2' => 'รมอ.', '4' => 'ทป.', '5' => 'ผอ.', '6' => 'ผก.', '7' => 'จนท.'];
        return array_key_exists($this->role, $role_arr)?$role_arr[$this->role]:null;
    }

}
