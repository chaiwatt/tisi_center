@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .label-height{
            line-height: 16px;
        }
    
        .font_size{
            font-size: 10px;
        }
    </style>
@endpush

@php
    $certiib_file_all =  isset($certiib_file_all )?$certiib_file_all :collect(new App\Models\Certify\ApplicantIB\CertiIBFileAll );
    
    $controller = new App\Http\Controllers\Certify\IB\CertificateExportIBController;

    if( !isset($export_ib) || !isset($export_ib->id) ){

        
        if( !empty( $app_token  ) && !is_null(App\Models\Certify\ApplicantIB\CertiIB::where('token', $app_token)->select('id')->first())  ){
            $app = App\Models\Certify\ApplicantIB\CertiIB::where('token', $app_token)->select('id')->first();

            $appData = $controller->apiGetAddress($app->id)->getData('certi_ib');

            $appData = isset($appData['certi_ib'])? (object)$appData['certi_ib']:null;

            // dd( $appData );

            $export_ib = new stdClass;
            $export_ib->app_certi_ib_id = $app->id;
            $export_ib->app_no = !empty($appData->app_no)?$appData->app_no:null;

            $export_ib->name_unit = !empty($appData->name_unit)?$appData->name_unit:null;
            $export_ib->name_en_unit = !empty($appData->name_en_unit)?$appData->name_en_unit:null;
            $export_ib->certificate = !empty($appData->certificate)?$appData->certificate:null;

            //ที่อยู่
            $export_ib->address = !empty($appData->address)?$appData->address:null;
            $export_ib->address_en = !empty($appData->ib_address_no_eng)?$appData->ib_address_no_eng:null;

            $export_ib->allay = !empty($appData->allay)?$appData->allay:null;
            $export_ib->allay_en = !empty($appData->ib_moo_eng)?$appData->ib_moo_eng:null;

            $export_ib->village_no = !empty($appData->village_no)?$appData->village_no:null;
            $export_ib->village_no_en = !empty($appData->ib_soi_eng)?$appData->ib_soi_eng:null;

            $export_ib->road = !empty($appData->road)?$appData->road:null;
            $export_ib->road_en = !empty($appData->ib_street_eng)?$appData->ib_street_eng:null;

            $export_ib->province_name = !empty($appData->province_name)?$appData->province_name:null;
            $export_ib->province_name_en = !empty($appData->province_name_en)?$appData->province_name_en:null;

            $export_ib->amphur_name = !empty($appData->amphur_name)?$appData->amphur_name:null;
            $export_ib->amphur_name_en = !empty($appData->ib_amphur_eng)?$appData->ib_amphur_eng:null;

            $export_ib->district_name = !empty($appData->district_name)?$appData->district_name:null;
            $export_ib->district_name_eng = !empty($appData->ib_district_eng)?$appData->ib_district_eng:null;

            $export_ib->postcode = !empty($appData->postcode)?$appData->postcode:null;
            // END ที่อยู่

            $export_ib->formula = !empty($appData->formula)?$appData->formula:null;
            $export_ib->formula_en = !empty($appData->formula_en)?$appData->formula_en:null;

            $export_ib->accereditatio_no = !empty($appData->accereditatio_no)?$appData->accereditatio_no:null;

            $export_ib->date_start = !empty($appData->date_start)?$appData->date_start:null;
            $export_ib->date_end = !empty($appData->date_end)?$appData->date_end:null;

            $certiib_file_all  = $app->cert_ibs_file_all;
        }

    }

    // เงื่อนไขแสดงแท็บ
    $active_tab = empty($export_ib->id);

@endphp
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  ออกใบรับรองฉบับนี้ให้'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                @if(isset($app_no))
                {!! Form::select('app_certi_ib_id', $app_no,  !empty( $export_ib->app_certi_ib_id)? $export_ib->app_certi_ib_id:null, ['class' => 'form-control', 'id' => 'app_certi_ib_id', 'placeholder'=>'- เลขคำขอ -', 'required' => true]); !!}
                {!! $errors->first('app_certi_ib_id', '<p class="help-block">:message</p>') !!}
                @else 
                    {!! Form::hidden('app_certi_ib_id', !empty( $export_ib->app_certi_ib_id)? $export_ib->app_certi_ib_id:null, ['class' => 'form-control','id'=>'app_certi_ib_id','disabled' => true]) !!}  
                    {!! Form::text('title', !empty( $export_ib->app_no)? $export_ib->app_no:null, ['class' => 'form-control','id'=>'title','disabled' => true ]) !!} 
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                @endif
                
                {!! Form::hidden('id', null, ['class' => 'form-control','id'=>'id']) !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('name_en') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
            <div class="col-md-5">
                <div class="  input-group">
                    {!! Form::text('name_en', !empty( $export_ib->name_en)? $export_ib->name_en:null, ['class' => 'form-control','id'=>'name_en','required' => false ,'disabled' => true]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                </div>
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                {!! Form::text('app_no', !empty( $export_ib->app_no)? $export_ib->app_no:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly'=>true]) !!} 
                {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="tab" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="@if($active_tab) active @endif tab_toggle">
                        <a href="#Section0" aria-controls="attachment" role="tab" data-toggle="tab">ไฟล์แนบท้าย</a>
                    </li>
                    <li role="presentation" class="@if(!$active_tab) active @endif tab_toggle">
                        <a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">ใบรับรอง / Certificate</a>
                    </li>
                </ul>
                <!-- Tab panes -->  

                <div class="tab-content tabs">
                    <div role="tabpanel" class="tab-pane fade @if($active_tab) in active @endif" id="Section0">
                        @include ('certify/ib/certificate_export_ib.form_attachment')
                    </div>
                    <div role="tabpanel" class="tab-pane fade @if(!$active_tab) in active @endif" id="Section1">
                        @include ('certify/ib/certificate_export_ib.form_certificate')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="submit"  id="certificate_export">

@if(isset($export_ib->status) && $export_ib->status >= 3)

<div class="form-group">
    <div class="col-md-offset-5 col-md-4">
        <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('certificateexportib'))
        <a class="btn btn-default" href="{{url('/certify/certificate-export-ib')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
        @endcan
    </div>
</div>
 
@else 
    <div class="form-group">
        <div class="col-md-offset-5 col-md-4">
            <button class="btn btn-success" name="button" type="submit" value="print" id="print"    onclick="submit_form('print')">
                <i class="fa fa-print"></i>  พิมพ์
            </button>
            <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
            <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('certificateexportib'))
                <a class="btn btn-default" href="{{url('/certify/certificate-export-ib')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script>
        $(document).ready(function () {

            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});

            add_class_default();

            $('.repeater-file').repeater();

    
            $(".js-switch").each(function() {
                if($(this).parent().find('span').html() == undefined){
                    new Switchery($(this)[0], {  size: 'small' });
                 }
            });

            $(".js-switch").change( function () {
                if($(this).prop('checked')){
                    $('.js-switch').prop('checked',false)
                    $(this).prop('checked',true)
                    $('.switchery-small').remove();
                    $(".js-switch").each(function( index, data) {
                        new Switchery($(this)[0], {size: 'small' });
                    });
                    button_file_all();
                     var rows  =   $(this).parent().parent().parent();
                        $(rows).find("button").removeClass("del-attach");
                        $(rows).find("button").removeClass("btn-danger");
                        $(rows).find("button > i").removeClass("fa-trash-o");

                        $(rows).find("button").addClass("edit_modal");
                        $(rows).find("button").addClass("btn-warning");
                        $(rows).find("button > i").addClass("fa-pencil-square-o");
                } 
            });


            //ช่วงวันที่
            $('.date-range').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });



            $("input[name=radio_address]").on("ifChanged", function(event) {
                radio_address();
            });

            //ช่วงวันที่
            $('.mydatepicker').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            $('#date_start').change(function(){
                if($(this).val()!=""){
                let date =   DateFormate($(this).val());
                    $.ajax({
                        url: "{!! url('certify/certificate-export-ib/api/date') !!}" + "/" +date
                    }).done(function( object ) {
                        if(object.date != '-'){
                            $('#date_end').val(object.date);
                        }else{
                            $('#date_end').val('');
                        } 
                    });
                }else{
                    $('#date_end').val('');
                }
            });

            $('#certificate_export_form').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
                if( $('#certificate_export').val() ==  "submit"  ){
                     // Text
                    $.LoadingOverlay("show", {
                        image       : "",
                        // text        :  $('#certificate_export').val() ==  "submit"   ? "กำลังบันทึก กรุณารอสักครู่..." : "กำลังดาวน์โหลดไฟล์pdf กรุณารอสักครู่..."
                        text        :   "กำลังบันทึก กรุณารอสักครู่..." 
                    });
                    return true; // Don't submit form for this demo
                }
            });
           
           $('.tab_toggle').on('shown.bs.tab', function (e) {
               tab_toggle();
           });
           tab_toggle();
     
        });

        function radio_address(){
            let row = $("input[name=radio_address]:checked").val();
            let id = $("#app_certi_ib_id").val();
                
            if((row == 1 || row == 2) &&  id != ''){  
                $.ajax({
                    url: "{!! url('certify/certificate-export-ib/api/address') !!}" + "/" + id    + "/" +   row
                }).done(function( object ) {
                    if(object.data != '-'){
                    let data =  object.data;
                        $('#address').val(data.address);
                        $('#allay').val(data.allay);
                        $('#village_no').val(data.village_no);
                        $('#road').val(data.road);
                        $('#province_name').val(data.province_name);
                        $('#amphur_name').val(data.amphur_name);
                        $('#district_name').val(data.district_name);
                        $('#postcode').val(data.postcode);
                    }
                }); 
            }else{
                $('#address').val('');
                $('#allay').val('');
                $('#village_no').val('');
                $('#road').val('');
                $('#province_name').val('');
                $('#amphur_name').val('');
                $('#district_name').val('');
                $('#postcode').val('');
            }
        }

        function submit_form(status) {
            $('#certificate_export').val(status);

            if(status  == 'print'){
                $('#certificate_export_form').attr('target', '_blank');
                $('#certificate_export_form').submit();
            }else{
                $('#certificate_export_form').attr('target', '');
                $('#certificate_export_form').submit();
            }
        }

        function DateFormate(str){
            var appoint_date=str;  
            var getdayBirth=appoint_date.split("/");  
            var YB=getdayBirth[2]-543;  
            var MB=getdayBirth[1];  
            var DB=getdayBirth[0];  
            var date = YB+'-'+MB+'-'+DB;
            return date;
        }
        function DateFormateThai(str){
            var arr_mount = {} ;
                arr_mount['01']  = 'ม.ค.';
                arr_mount['02']  = 'ก.พ.';
                arr_mount['03']  = 'มี.ค.';
                arr_mount['04']  = 'เม.ษ.';
                arr_mount['05']  = 'พ.ค.';
                arr_mount['06']  = 'มิ.ย.';
                arr_mount['07']  = 'ก.ค.';
                arr_mount['08']  = 'ส.ค.';
                arr_mount['09']  = 'ก.ย.';
                arr_mount['10']  = 'ต.ค.';
                arr_mount['11']  = 'พ.ย.';
                arr_mount['12']  = 'ธ.ค.';
              var appoint_date=str;
              var getdayBirth=appoint_date.split("/");
              var YB=getdayBirth[2];
              var MB=getdayBirth[1];
              var DB=getdayBirth[0];
              var date = DB+' '+arr_mount[MB]+' '+YB ;
              return date;
        }

    
        function button_file_all(){ 
     
            var rows  = $('#myTable tbody').children();
               rows.each(function(index, el) {
                   $(el).find("button").removeClass("edit_modal");
                   $(el).find("button").removeClass("btn-warning");
                   $(el).find("button > i").removeClass("fa-pencil-square-o");

                   $(el).find("button").addClass("del-attach");
                   $(el).find("button").addClass("btn-danger");
                   $(el).find("button > i").addClass("fa-trash-o");
              });
            }
         function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        //รีเซตเลขลำดับ
        function resetAttachmentNo(){
            $('.no-attach').each(function(index, el) {
                $(el).text(index+1);
            });

        }

        function add_class_default(){
            let tabpanel = $('div[role="tabpanel"]');
            console.log($('#Section1').find('input:is(:required), select:is(:required), textarea:is(:required)'));
            tabpanel.find('input:is(:required), select:is(:required), textarea:is(:required)').addClass('required');
            tabpanel.find('input:is(:disabled), select:is(:disabled), textarea:is(:disabled)').addClass('disabled');
        }

        function tab_toggle(){
            let tab = $('.tab_toggle.active > a').attr('href');
            let tab1 = $('#Section0');
            let tab2 = $('#Section1');
            if(tab == '#Section0'){
                tab1.find('input:not(.disabled), select:not(.disabled), textarea:not(.disabled)').prop('disabled', false);
                tab1.find('input.required, select.required, textarea.required').prop('required', true);
                tab1.find('.disabled').removeClass('disabled');
                tab1.find('.required').removeClass('required');
                tab2.find('input:is(:required), select:is(:required), textarea:is(:required)').addClass('required');
                tab2.find('input:is(:disabled), select:is(:disabled), textarea:is(:disabled)').addClass('disabled');
                tab2.find('input, select, textarea').prop('disabled', true);
                tab2.find('input, select, textarea').prop('required', false);
                $('#print').hide();
            }else{
                tab2.find('input:not(.disabled), select:not(.disabled), textarea:not(.disabled)').prop('disabled', false);
                tab2.find('input.required, select.required, textarea.required').prop('required', true);
                tab2.find('.disabled').removeClass('disabled');
                tab2.find('.required').removeClass('required');
                tab1.find('input:is(:required), select:is(:required), textarea:is(:required)').addClass('required');
                tab1.find('input:is(:disabled), select:is(:disabled), textarea:is(:disabled)').addClass('disabled');
                tab1.find('input, select, textarea').prop('disabled', true);
                tab1.find('input, select, textarea').prop('required', false);
                $('#print').show();
            }
        }

    </script>  
@endpush
