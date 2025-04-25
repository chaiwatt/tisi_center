<div class="row">
    <div class="col-md-10 col-sm-12">
        <p><span class="text-bold-400">บัญชีผู้ใช้งาน</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        @can('edit-'.str_slug('manage-lab'))
            <button type="button" class="btn btn-sm btn-warning glow mr-1 mb-1 pull-right" data-toggle="modal" data-target="#MdAccount" @if( !isset($labs->id) ) disabled @endif><i class="mdi mdi-account-edit"></i></button>
        @endcan
        @can('view-'.str_slug('manage-lab'))
            <button type="button" class="btn btn-sm btn-info glow mr-1 mb-1 pull-right m-r-10" data-toggle="modal" data-target="#MdAccount-History" @if( !isset($labs->id) ) disabled @endif><i class="mdi mdi-timetable"></i></button>
        @endcan
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">ชื่อผู้ใช้งาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!is_null($user_sso) ? $user_sso->username : ' - ') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">อีเมลแอดเดรส :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!is_null($user_sso) ? $user_sso->email : ' - ') !!}</span></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">ชื่อหน่วยงาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!is_null($user_sso) ? $user_sso->name : ' - ') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">รหัสสาขา :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!is_null($user_sso) ? $user_sso->branch_code : ' - ') !!}</span></p>
    </div>
</div>