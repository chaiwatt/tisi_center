<div class="row">
    <div class="col-md-10 col-sm-12">
        <p><span class="text-bold-400">ผู้ประสานงาน</span></p>
    </div>
    <div class="col-md-2  col-sm-12">
        @can('edit-'.str_slug('manage-lab'))
            <button type="button" class="btn btn-sm btn-warning glow mr-1 mb-1 pull-right" data-toggle="modal" data-target="#MdContact" @if( !isset($labs->id) ) disabled @endif><i class="fa fa-pencil"></i></button>
        @endcan
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">ชื่อผู้ประสานงาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_name)?$labs->co_name:' - ') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">ตำแหน่งผู้ประสานงาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_position)?$labs->co_position:' - ') !!}</span></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">โทรศัพท์มือถือ :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_mobile)?$labs->co_mobile: '-') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">โทรศัพท์ :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_phone)?$labs->co_phone: '-') !!}</span></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">โทรสาร :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_fax)?$labs->co_fax: '-') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="text-bold-400">อีเมล :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span class="text-bold-400">{!! (!empty($labs->co_email)?$labs->co_email: '-') !!}</span></p>
    </div>
</div>
