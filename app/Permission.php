<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label'];

    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * A permission list that can be used for static menu.
     */

    public static function permissionList($menu)
    {
        $permissionsList = Permission::where('name','LIKE','%'.str_slug($menu))->get();
        $permissions['add'] = $permissionsList->where('name','=','add-'.str_slug($menu))->pluck('id')->first();
        $permissions['edit'] = $permissionsList->where('name','=','edit-'.str_slug($menu))->pluck('id')->first();
        $permissions['view'] = $permissionsList->where('name','=','view-'.str_slug($menu))->pluck('id')->first();
        $permissions['delete'] = $permissionsList->where('name','=','delete-'.str_slug($menu))->pluck('id')->first();
        $permissions['other'] = $permissionsList->where('name','=','other-'.str_slug($menu))->pluck('id')->first();
        $permissions['poko_approve'] = $permissionsList->where('name','=','poko_approve-'.str_slug($menu))->pluck('id')->first();
        $permissions['poao_approve'] = $permissionsList->where('name','=','poao_approve-'.str_slug($menu))->pluck('id')->first();
        $permissions['assign_work'] = $permissionsList->where('name','=','assign_work-'.str_slug($menu))->pluck('id')->first();
        $permissions['view_all'] = $permissionsList->where('name', '=', 'view_all-'.str_slug($menu))->pluck('id')->first();
        return  $permissions;
    }
}
