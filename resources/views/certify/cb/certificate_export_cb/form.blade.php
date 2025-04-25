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
    $certicb_file_all =  isset($certicb_file_all)?$certicb_file_all:collect(new App\Models\Certify\ApplicantCB\CertiCBFileAll );

    $controller = new App\Http\Controllers\Certify\CB\CertificateExportCBController;


    if( !isset($export_cb) || !isset($export_cb->id) ){

        if( !empty( $app_token  ) && !is_null(App\Models\Certify\ApplicantCB\CertiCb::where('token', $app_token)->select('id')->first())  ){
            $app = App\Models\Certify\ApplicantCB\CertiCb::where('token', $app_token)->select('id','app_no')->first();

            $appData = $controller->apiGetAddress($app->id)->getData('certi_cb');

            $appData = isset($appData['certi_cb'])? (object)$appData['certi_cb']:null;

            // dd( $appData );

            $export_cb  = new stdClass;
            $export_cb->app_certi_cb_id = $app->id;
            $export_cb->request_number = !empty($appData->app_no)?$appData->app_no:null;

            $export_cb->app_no = !empty($appData->app_no)?$appData->app_no:null;
            $export_cb->name_standard = !empty($appData->name_standard)?$appData->name_standard:null;
            $export_cb->name_en = !empty($appData->name_en_standard)?$appData->name_en_standard:null;
            $export_cb->certificate = !empty($appData->certificate)?$appData->certificate:null;

            //ที่อยู่
            $export_cb->address = !empty($appData->address)?$appData->address:null;
            $export_cb->address_en = !empty($appData->cb_address_no_eng)?$appData->cb_address_no_eng:null;

            $export_cb->allay = !empty($appData->allay)?$appData->allay:null;
            $export_cb->allay_en = !empty($appData->cb_moo_eng)?$appData->cb_moo_eng:null;

            $export_cb->village_no = !empty($appData->village_no)?$appData->village_no:null;
            $export_cb->village_no_en = !empty($appData->cb_soi_eng)?$appData->cb_soi_eng:null;

            $export_cb->road = !empty($appData->road)?$appData->road:null;
            $export_cb->road_en = !empty($appData->cb_street_eng)?$appData->cb_street_eng:null;

            $export_cb->province_name = !empty($appData->province_name)?$appData->province_name:null;
            $export_cb->province_name_en = !empty($appData->province_name_en)?$appData->province_name_en:null;

            $export_cb->amphur_name = !empty($appData->amphur_name)?$appData->amphur_name:null;
            $export_cb->amphur_name_en = !empty($appData->cb_amphur_eng)?$appData->cb_amphur_eng:null;

            $export_cb->district_name = !empty($appData->district_name)?$appData->district_name:null;
            $export_cb->district_name_eng = !empty($appData->cb_district_eng)?$appData->cb_district_eng:null;

            $export_cb->postcode = !empty($appData->postcode)?$appData->postcode:null;
            // END ที่อยู่

            $export_cb->formula = !empty($appData->formula)?$appData->formula:null;
            $export_cb->formula_en = !empty($appData->formula_en)?$appData->formula_en:null;

            $export_cb->accereditatio_no = !empty($appData->accereditatio_no)?$appData->accereditatio_no:null;

            $export_cb->date_start = !empty($appData->date_start)?$appData->date_start:null;
            $export_cb->date_end = !empty($appData->date_end)?$appData->date_end:null;

            $certicb_file_all = $app->cert_cbs_file_all;
        }

        
    }

    // เงื่อนไขแสดงแท็บ
    $active_tab = empty($export_cb->id);

@endphp
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  ออกใบรับรองฉบับนี้ให้'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                @if(isset($app_no))
                    {!! Form::select('app_certi_cb_id', $app_no,  !empty($export_cb->app_certi_cb_id)? $export_cb->app_certi_cb_id:null,  ['class' => 'form-control', 'id' => 'app_certi_cb_id','placeholder'=>'- เลขคำขอ -','required' => true]); !!}
                    {!! $errors->first('app_certi_cb_id', '<p class="help-block">:message</p>') !!}
                @else
                    {!! Form::text('title', null, ['class' => 'form-control','id'=>'title','disabled' => true]) !!}
                    {!! Form::hidden('cb_name', null, ['id'=>'cb_name']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                @endif
            </div>
        </div>
        
        <div class="form-group {{ $errors->has('name_en') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
            <div class="col-md-5">
                   <div class=" input-group">
                    {!! Form::text('name_en', !empty($export_cb->name_en)? $export_cb->name_en:null, ['class' => 'form-control','id'=>'name_en','required' => false,'disabled' => true ]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                  </div>
                {!! $errors->first('name_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                {!! Form::text('app_no', !empty($export_cb->app_no)? $export_cb->app_no:null, ['class' => 'form-control','id'=>'app_no','required' => true]) !!}
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
                    {{-- <li role="presentation">
                        <a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab">เพิ่มเติม / Other</a>
                    </li> --}}
                </ul>
                <!-- Tab panes -->  

                <div class="tab-content tabs">
                    <div role="tabpanel" class="tab-pane fade @if($active_tab)  in active @endif" id="Section0">
                        @include ('certify/cb/certificate_export_cb.form_attachment')
                    </div>
                    <div role="tabpanel" class="tab-pane fade @if(!$active_tab) in active @endif" id="Section1">
                        @include ('certify/cb/certificate_export_cb.form_certificate')
                    </div>
                    {{-- <div role="tabpanel" class="tab-pane fade" id="Section2">
                        @include('certify/cb/certificate_export_cb.form_other')
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="submit"  id="certificate_export">

@if(isset($export_cb->status) && $export_cb->status >= 3)
<div class="form-group">
    <div class="col-md-offset-5 col-md-4">
        <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
            @can('view-'.str_slug('certificateexportcb'))
                <a class="btn btn-default" href="{{url('/certify/certificate-export-cb')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
    </div>
</div>
 
@else 
    <div class="form-group">

        <div class="col-md-offset-3 col-md-6 text-center">

            {{-- <label>{!! Form::checkbox('check_badge', '1', (isset($export_cb->id) && ($export_cb->check_badge == 1) ) ? true : false, ['class'=>'check','data-checkbox'=>"icheckbox_flat-red"]) !!} 
                    &nbsp;Logo Ilac-MRA&nbsp;
           </label>
           <br> --}}
            <button class="btn btn-success" name="submit" type="submit" value="print" id="print"    onclick="submit_form('print')">
                <i class="fa fa-print"></i>  พิมพ์
            </button>
            <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('certificateexportcb'))
                <a class="btn btn-default" href="{{url('/certify/certificate-export-cb')}}">
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

            $('.repeater-file').repeater();

            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});

            add_class_default();

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
                        new Switchery($(this)[0], { size: 'small' });
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


            check_max_size_file();
            $("input[name=radio_address]").on("ifChanged", function(event) {
                radio_address();
            });

            //ช่วงวันที่
            $('.mydatepicker').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            $('#app_certi_cb_id').change(function(){

                if($(this).val()!=""){
    
                    $('.radio_address[value=2]').prop('checked', true);
                    $('.radio_address').iCheck('update');

                    $.ajax({
                        url: "{!! url('certify/api/certificate-export-cb') !!}" + "/" + $(this).val()
                    }).done(function( object ) {

                        if(object.certi_cb != '-'){
                            let certi_cb = object.certi_cb;
                            $('#app_no').val(certi_cb.app_no);
                            $('#request_number').val(certi_cb.app_no);
                            $('#certificate').val(certi_cb.certificate);
                            // $('#name_standard').val(certi_cb.name_standard);
                            $('#name_standard').val(certi_cb.trader_operater_name);
                            $('#address').val(certi_cb.address);
                            $('#allay').val(certi_cb.allay);
                            $('#village_no').val(certi_cb.village_no);
                            $('#road').val(certi_cb.road);
                            $('#province_name').val(certi_cb.province_name);
                            $('#amphur_name').val(certi_cb.amphur_name);
                            $('#district_name').val(certi_cb.district_name);
                            $('#postcode').val(certi_cb.postcode);
                            $('#formula').val(certi_cb.formula);
                            $('#formula_en').val(certi_cb.formula_en);
                            $('#accereditatio_no').val(certi_cb.accereditatio_no);
                            $('#date_start').val(certi_cb.date_start);  
                            $('#date_end').val(certi_cb.date_end);
                        }else{
                            $('.radio_address[value=1]').prop('checked', true);
                            $('.radio_address').iCheck('update');
                            $('#request_number').val('');
                            $('#app_no').val('');
                            $('#certificate').val('');
                            $('#name_standard').val('');
                            $('#address').val('');
                            $('#allay').val('');
                            $('#village_no').val('');
                            $('#road').val('');
                            $('#province_name').val('');
                            $('#amphur_name').val('');
                            $('#district_name').val('');
                            $('#postcode').val('');
                            $('#formula').val('');
                            $('#formula_en').val('');
                            $('#accereditatio_no').val('');
                            $('#date_start').val('');
                            $('#date_end').val('');
                        }
                                
                    });
                }else{
                    $('.radio_address[value=1]').prop('checked', true);
                    $('.radio_address').iCheck('update');
                    $('#app_no').val('');
                    $('#certificate').val('');
                    $('#name_standard').val('');
                    $('#address').val('');
                    $('#allay').val('');
                    $('#village_no').val('');
                    $('#road').val('');
                    $('#province_name').val('');
                    $('#amphur_name').val('');
                    $('#district_name').val('');
                    $('#postcode').val('');
                    $('#formula').val('');
                    $('#formula_en').val('');
                    $('#accereditatio_no').val('');
                    $('#date_start').val('');
                    $('#date_end').val('');
                }
            });

            $('#date_start').change(function(){
                if($(this).val()!=""){
                    let date =   DateFormate($(this).val());
                    $.ajax({
                        url: "{!! url('certify/api/certificate-export-cb/date') !!}" + "/" + date
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
            let id = $("#app_certi_cb_id").val();
                
            if((row == 1 || row == 2) &&  id != ''){  
                $.ajax({
                    url: "{!! url('certify/api/certificate-export-cb/address') !!}" + "/" + id    + "/" +   row
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
