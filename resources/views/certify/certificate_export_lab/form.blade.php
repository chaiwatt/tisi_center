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
        .not-allowed{
            cursor: not-allowed;
        }
    </style>
@endpush

@php

    $controller = new App\Http\Controllers\Certify\CertificateExportLABController;
    $certilab_file_all =  isset($certilab_file_all)?$certilab_file_all:collect(new App\Models\Certify\Applicant\CertLabsFileAll );
    if ( !isset($export_lab->id) ) {
            $export_lab  = new stdClass;

        if( !empty( $app_token  ) && !is_null(App\Models\Certify\Applicant\CertiLab::where('token', $app_token)->select('id')->first())  ){
            $app = App\Models\Certify\Applicant\CertiLab::where('token', $app_token)->select('id','app_no')->first();

            $appData = $controller->apiGetAddress($app->id)->getData('certi_lab');

            $appData = isset($appData['certi_lab'])? (object)$appData['certi_lab']:null;


            $export_lab->app_certi_lab_id = $app->id;
            $export_lab->app_no = $app->app_no;
            $export_lab->request_number = !empty($appData->app_no)?$appData->app_no:null;
            $export_lab->certificate_no = !empty($appData->certificate)?$appData->certificate:null;

            $export_lab->lab_name = !empty($appData->lab_name)?$appData->lab_name:null;
            $export_lab->lab_name_en = !empty($appData->lab_name_en)?$appData->lab_name_en:null;

            //ที่อยู่
            $export_lab->address_no = !empty($appData->address_no)?$appData->address_no:null;
            $export_lab->address_no_en = !empty($appData->lab_address_no_eng)?$appData->lab_address_no_eng:null;

            $export_lab->address_moo = !empty($appData->allay)?$appData->allay:null;
            $export_lab->address_moo_en = !empty($appData->lab_moo_eng)?$appData->lab_moo_eng:null;

            $export_lab->address_soi = !empty($appData->village_no)?$appData->village_no:null;
            $export_lab->address_soi_en = !empty($appData->lab_soi_eng)?$appData->lab_soi_eng:null;

            $export_lab->address_road = !empty($appData->road)?$appData->road:null;
            $export_lab->address_road_en = !empty($appData->lab_street_eng)?$appData->lab_street_eng:null;

            $export_lab->address_province = !empty($appData->province_name)?$appData->province_name:null;
            $export_lab->address_province_en = !empty($appData->province_name_en)?$appData->province_name_en:null;

            $export_lab->address_district = !empty($appData->amphur_name)?$appData->amphur_name:null;
            $export_lab->address_district_en = !empty($appData->lab_amphur_eng)?$appData->lab_amphur_eng:null;

            $export_lab->address_subdistrict = !empty($appData->district_name)?$appData->district_name:null;
            $export_lab->address_subdistrict_en = !empty($appData->lab_district_eng)?$appData->lab_district_eng:null;

            $export_lab->address_postcode = !empty($appData->postcode)?$appData->postcode:null;
            // // END ที่อยู่

            $export_lab->formula = !empty($appData->formula)?$appData->formula:null;
            $export_lab->formula_en = !empty($appData->formula_en)?$appData->formula_en:null;

            $export_lab->accereditatio_no = !empty($appData->accereditatio_no)?$appData->accereditatio_no:null;
            $export_lab->accereditatio_no_en = !empty($appData->accereditatio_no_en)?$appData->accereditatio_no_en:null;

            $export_lab->certificate_date_start = !empty($appData->date_start)?$appData->date_start:null;
            $export_lab->certificate_date_end = !empty($appData->date_end)?$appData->date_end:null;
            $export_lab->purpose_type = !empty($appData->purpose_type)?$appData->purpose_type:null;

            $cert_labs_file_all = $app->cert_labs_file_all;
  
        }
    }

   
    // เงื่อนไขแสดงแท็บ
    $active_tab = empty($export_lab->id);
    
@endphp
 <br>
<div class="row">
    {{-- {{$appData}} --}}
    <div class="col-lg-12">
        {{-- <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span> ออกใบรับรองฉบับนี้ให้'.':'.'<br/><span class=" font_size">(Applicant)</span>', ['class' => 'col-md-3 control-label  label-height'])) !!}
            <div class="col-md-5">
                @if(isset($app_no))
                    {!! Form::select('app_certi_lab_id',   $app_no,  !empty($export_lab->app_certi_lab_id) ?$export_lab->app_certi_lab_id:null, ['class' => 'form-control','id' => 'app_certi_lab_id','placeholder'=>'- เลขคำขอ -',  'required' => true]); !!}
                    {!! $errors->first('app_certi_lab_id', '<p class="help-block">:message</p>') !!}
                @else 
                    {!! Form::hidden('app_certi_lab_id', !empty($export_lab->app_certi_lab_id) ?$export_lab->app_certi_lab_id:null, ['class' => 'form-control','id'=>'app_certi_lab_id']) !!} 
                    {!! Form::text('title', !empty($export_lab->request_number) ?$export_lab->request_number:null, ['class' => 'form-control','id'=>'title','disabled' => true]) !!} 
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                @endif
                {!! Form::hidden('id', null, ['class' => 'form-control','id'=>'id']) !!} 
            </div>
        </div> --}}

        <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
            <label for="certi_no" class="col-md-3 control-label label-height">
                <span class="text-danger">*</span> ออกใบรับรองฉบับนี้ให้: <br />
                <span class="font_size">(Applicant)</span>
            </label>
            <div class="col-md-5">
                @if(isset($app_no))
                    <select name="app_certi_lab_id" id="app_certi_lab_id" class="form-control" required>
                        <option value="">- เลขคำขอ -</option>
                        @foreach($app_no as $key => $value)
                            <option value="{{ $key }}" {{ !empty($export_lab->app_certi_lab_id) && $export_lab->app_certi_lab_id == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('app_certi_lab_id'))
                        <p class="help-block">{{ $errors->first('app_certi_lab_id') }}</p>
                    @endif
                @else
                    <input type="hidden" name="app_certi_lab_id" id="app_certi_lab_id" value="{{ !empty($export_lab->app_certi_lab_id) ? $export_lab->app_certi_lab_id : '' }}">
                    <input type="text" name="title" id="title" class="form-control" value="{{ !empty($export_lab->request_number) ? $export_lab->request_number : '' }}" disabled>
                    @if($errors->has('title'))
                        <p class="help-block">{{ $errors->first('title') }}</p>
                    @endif
                @endif
                <input type="hidden" name="id" id="id" class="form-control" value="">
            </div>
        </div>
        

        {{-- <div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-3 control-label  label-height'])) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('title_en', !empty($export_lab->title_en) ?$export_lab->title_en:null, ['class' => 'form-control','id'=>'title_en','required' => false,'disabled' => true]) !!}
                    <span class="input-group-addon bg-secondary "> EN </span>
                  </div>
                {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ'.' :', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                {!! Form::text('app_no', !empty($export_lab->request_number) ?$export_lab->request_number:null, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly' => true]) !!} 
                {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div> --}}

        <div class="form-group {{ $errors->has('title_en') ? 'has-error' : '' }}">
            <label for="title_en" class="col-md-3 control-label label-height"> </label>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="title_en" id="title_en" class="form-control" 
                           value="{{ !empty($export_lab->title_en) ? $export_lab->title_en : '' }}" 
                           disabled>
                    <span class="input-group-addon bg-secondary">EN</span>
                </div>
                @if($errors->has('title_en'))
                    <p class="help-block">{{ $errors->first('title_en') }}</p>
                @endif
            </div>
        </div>
        
        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : '' }}">
            <label for="app_no" class="col-md-3 control-label">
                <span class="text-danger">*</span> เลขที่คำขอ :
            </label>
            <div class="col-md-5">
                <input type="text" name="app_no" id="app_no" class="form-control" 
                       value="{{ !empty($export_lab->request_number) ? $export_lab->request_number : '' }}" 
                       required readonly>
                @if($errors->has('app_no'))
                    <p class="help-block">{{ $errors->first('app_no') }}</p>
                @endif
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
                    <div role="tabpanel" class="tab-pane fade @if($active_tab)  in active @endif" id="Section0">
                        @include('certify/certificate_export_lab.form_attachment')
                    </div>
                    <div role="tabpanel" class="tab-pane fade @if(!$active_tab) in active @endif" id="Section1">
                        @include('certify/certificate_export_lab.form_certificate')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="submit"  id="certificate_export">
 
@if(isset($export_lab->status) && $export_lab->status >= 3)
{{-- {{$app}} --}}
{{-- {{$certilab_file}} --}}
{{-- {{$export_lab->applications->status}} --}}
    <div class="form-group">
        @if ($export_lab->applications->status !== 28)
            <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
        @endif
        <div class="col-md-offset-5 col-md-4">

            @can('view-'.str_slug('certificateexportlab'))
                <a class="btn btn-default" href="{{url('/certify/certificate-export-lab')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@else 
    <div class="form-group">
        <div class="col-md-offset-5 col-md-4">
            <button class="btn btn-success" name="submit" type="submit" value="print" id="print"    onclick="submit_form('print')">
                <i class="fa fa-print"></i>  พิมพ์
            </button>
            <button class="btn btn-primary" name="submit" type="submit" value="submit"    onclick="submit_form('submit')">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('certificateexportlab'))
                <a class="btn btn-default" href="{{url('/certify/certificate-export-lab')}}">
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
        var report = @json($export_lab);
        $(document).ready(function () {


            $(document).ready(function() {
                $('#app_certi_lab_id').change(function() {
                    // ใส่โค้ดที่ต้องการทำงานเมื่อเปลี่ยนค่า
                    console.log('Change event triggered!');
                    // ตัวอย่าง: อ่านค่าปัจจุบัน
                    let value = $(this).val();
                    console.log('Current value:', value);
                }).trigger('change'); // เรียกใช้งาน change ทันทีหลังจากกำหนด event handler
            });

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
                        new Switchery($(this)[0], {  size: 'small' });
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
                        url: "{!! url('certify/api/certificate-export-lab/date') !!}" + "/" + date
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

            check_max_size_file();

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

            $('#app_certi_lab_id').change(function(){

                

                $('.box_sign').find('input,select').prop('disabled', false);

                if($(this).val()!=""){
              
                    $('.radio_address[value=2]').prop('checked', true);
                    $('.radio_address').iCheck('update');
                    $.ajax({
                        url: "{!! url('certify/api/certificate-export-lab') !!}" + "/" + $(this).val()
                    }).done(function( object ) {
                        if(object.certi_lab != '-'){
                            let certi_lab = object.certi_lab;
                            console.log('certi_lab',certi_lab);
                            $('#app_no').val(certi_lab.app_no);
                            // $('#certificate').val(certi_lab.certificate);
                            // $('#name_standard').val(certi_lab.lab_name);
                            // $('#name_standard_en').val(certi_lab.lab_name_en);
                            //   $('#name_standard').val(certi_lab.lab_name);
                            $('#address_no').val(certi_lab.address_no);
                            $('#allay').val(certi_lab.allay);
                            $('#village_no').val(certi_lab.village_no);
                            $('#road').val(certi_lab.road);
                            $('#province_name').val(certi_lab.province_name);
                            $('#amphur').val(certi_lab.amphur);
                            $('#district').val(certi_lab.district);
                            $('#postcode').val(certi_lab.postcode);
                            $('#formula').val(certi_lab.formula);
                            $('#accereditatio_no').val(certi_lab.accereditatio_no);
                            $('#accereditatio_no_en').val(certi_lab.accereditatio_no_en);
                            $('#date_start').val(certi_lab.date_start);
                            $('#date_end').val(certi_lab.date_end);
                            $('#formula_en').val(certi_lab.formula_en);

                            if(certi_lab.purpose_type != 1){
                                $('.box_sign').find('input,select').prop('disabled', true);
                            }
                        }else{
                            $('.radio_address[value=1]').prop('checked', true);
                            $('.radio_address').iCheck('update');
                            $('#app_no').val('');
                            $('#certificate').val('');
                            $('#name_standard').val('');
                            $('#address_no').val('');
                            $('#allay').val('');
                            $('#village_no').val('');
                            $('#road').val('');
                            $('#province_name').val('');
                            $('#amphur').val('');
                            $('#district').val('');
                            $('#postcode').val('');
                            $('#formula').val('');
                            $('#accereditatio_no').val('');
                            $('#accereditatio_no_en').val('');
                            $('#date_start').val('');
                            $('#date_end').val('');
                            $('#formula_en').val('');
                        }
                          
                    });
                }else{
                   
                    $('.radio_address[value=1]').prop('checked', true);
                    $('.radio_address').iCheck('update');
                    $('#app_no').val('');
                    $('#certificate').val('');
                    $('#name_standard').val('');
                    $('#address_no').val('');
                    $('#allay').val('');
                    $('#village_no').val('');
                    $('#road').val('');
                    $('#province_name').val('');
                    $('#amphur').val('');
                    $('#district').val('');
                    $('#postcode').val('');
                    $('#formula').val('');
                    $('#accereditatio_no').val('');
                    $('#accereditatio_no_en').val('');
                    $('#date_start').val('');
                    $('#date_end').val('');
                    $('#formula_en').val('');
                }
           });
           
            $('.tab_toggle').on('shown.bs.tab', function (e) {
                tab_toggle();
            });
            tab_toggle();
      
        });

        function radio_address(){
            let row = $("input[name=radio_address]:checked").val();
            let id = $("#app_certi_lab_id").val();
                
            if((row == 1 || row == 2) &&  id != ''){  
                $.ajax({
                    url: "{!! url('certify/api/certificate-export-lab/address') !!}" + "/" + id    + "/" +   row
                }).done(function( object ) {
                    if(object.data != '-'){
                        let data =  object.data;
                        $('#address_no').val(data.address_no);
                        $('#allay').val(data.allay);
                        $('#village_no').val(data.village_no);
                        $('#road').val(data.road);
                        $('#province_name').val(data.province_name);
                        $('#amphur').val(data.amphur);
                        $('#district').val(data.district);
                        $('#postcode').val(data.postcode);
                    }
                }); 
            }else{
                $('#address_no').val('');
                $('#allay').val('');
                $('#village_no').val('');
                $('#road').val('');
                $('#province_name').val('');
                $('#amphur').val('');
                $('#district').val('');
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
