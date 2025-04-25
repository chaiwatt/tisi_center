<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="control-label">ชื่อผู้ประสานงาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_name)?$offender->contact_name:' - ') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span>ตำแหน่งผู้ประสานงาน :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_position)?$offender->contact_position:' - ') !!}</span></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="control-label">โทรศัพท์มือถือ :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_mobile)?$offender->contact_mobile: '-') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="control-label">โทรศัพท์ :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_phone)?$offender->contact_phone: '-') !!}</span></p>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="control-label">โทรสาร :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_fax)?$offender->contact_fax: '-') !!}</span></p>
    </div>
    <div class="col-md-2 col-sm-12">
        <p class="text-right"><span class="control-label">อีเมล :</span></p>
    </div>
    <div class="col-md-4 col-sm-12">
        <p class=""><span>{!! (!empty($offender->contact_email)?$offender->contact_email: '-') !!}</span></p>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {


        });

    </script>
@endpush