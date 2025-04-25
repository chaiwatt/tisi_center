@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img{
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขใบรับรองงาน {{$certificate->certificate_no}}</h3>
{{--                    @can('view-'.str_slug('board'))--}}
                        <a class="btn btn-success pull-right" href="{{url('certify/certificate-export')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    {{--@endcan--}}

                    {!! Form::open(['route' => ['certificate-export.edit.store','cer'=>$certificate],'method'=>'POST','id'=>'addForm', 'class' => 'form-horizontal', 'files' => true]) !!}


                    <div class="clearfix"></div>
                    <hr>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#certificate-th">ใบรับรอง / Certificate</a></li>
                        {{-- <li><a data-toggle="tab" href="#scope-th">รายละเอียด / Scope</a></li> --}}
                        <li><a data-toggle="tab" href="#other-th">เพิ่มเติม / Other</a></li>
                    </ul>

                    <div class="tab-content">

                        {{--  SECTION 1--}}
                        <div id="certificate-th" class="tab-pane fade in active">

                            <div class="white-box">
                                <h4>ข้อมูลใบรับรอง</h4>

                                <div class="form-group">
                                    @php $name = 'certificate_for' ;
                                        $text = ($lang == "th" ? 'ออกใบรับรองฉบับนี้ให้': 'Issue this certificate for') ;
                                        $option_text = ($lang == "th" ? '- เลือกออกใบรับรองฉบับนี้ให้ -': '- Select issue this certificate for -') ;@endphp
                                    <label for="requestNumber" class="col-md-4 control-label"><span class="text-danger">*</span> {{$text}} :</label>
                                    <div class="col-md-6">
        
                                         <input type="hidden"  name="{{$name}}" value="{{ $certificate->certificate_for  ?? null}}">
                                        {!! Form::text('title', $requests->trader_name ??null, ['class' => 'form-control','disabled' => true]) !!} 
                                        {{-- <select name="{{$name}}" id="{{$name}}" class="form-control">
                                               <option value="" disabled selected>{{$option_text}}</option>
                                            @foreach($requests as $request)
                                                <option value="{{$request->id}}" {{$certificate->certificate_for == $request->id ? "selected" : null}}>{{$request->trader_name}}</option>
                                            @endforeach
                                        </select> --}}
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'request_number' ; $text = ($lang == "th" ? 'เลขที่คำขอ': 'Certificate No') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->request_number}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    @php $name = 'certificate_no' ; $text = ($lang == "th" ? 'ใบรับรองเลขที่': 'Certificate No') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->certificate_no}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <input type="hidden" name="lab_type" id="lab_type" class="lab_type form-control" value="{{$certificate->lab_type}}">





                                <div class="form-group">
                                    @php $name = 'lab_name' ; $text = ($lang == "th" ? 'ห้องปฏิบัติการ': 'Laboratory Name') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->lab_name}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'address_no' ; $text = ($lang == "th" ? 'ตั้งอยู่เลขที่': 'Laboratory Address') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_no}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>

                                    @php $name = 'address_moo' ; $text = ($lang == "th" ? 'หมู่ที่': 'Moo')@endphp
                                    <label for="requestNumber" class="col-md-2 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_moo}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'address_soi' ; $text = ($lang == "th" ? 'ตรอก/ซอย': 'Trok/Soi') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_soi}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>

                                    @php $name = 'address_road' ; $text = ($lang == "th" ? 'ถนน': 'Street/Road') @endphp
                                    <label for="requestNumber" class="col-md-2 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_road}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'address_province' ; $text = ($lang == "th" ? 'จังหวัด': 'Province') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_province}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>

                                    @php $name = 'address_district' ; $text = ($lang == "th" ? 'เขต/อำเภอ': 'Khet/Amphoe')@endphp
                                    <label for="requestNumber" class="col-md-2 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_district}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'address_subdistrict' ; $text = ($lang == "th" ? 'แขวง/ตำบล': 'Khwaeng/Tambon') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_subdistrict}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>

                                    @php $name = 'address_postcode' ; $text = ($lang == "th" ? 'รหัสไปรษณีย': 'Postal Code') @endphp
                                    <label for="requestNumber" class="col-md-2 control-label">{{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->address_postcode}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'formula' ; $text = ($lang == "th" ? 'ตามมาตราฐานเลขที่': 'Thai Industrail Standard') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->formula}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    @php $name = 'accereditatio_no' ; $text = ($lang == "th" ? 'หมายเลขการรับรองที่': 'Accereditation No.') @endphp
                                    <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate->accereditatio_no}}">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    @php $name = 'certificate_date_start' ; $text = ($lang == "th" ? 'ออกให้ ณ วันที่': 'Issue date');  $certificate_date_start = ($lang == "th") ? \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_start)->addYear(543)->formatLocalized('%d/%m/%Y'): \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_start)->addYear(-543)->formatLocalized('%d/%m/%Y'); @endphp
                                    <label for="requestNumber" class="col-md-4 control-label"> <span class="text-danger">*</span> {{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control datepicker" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate_date_start}}" autocomplete="off">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                      {{-- {{ dd($certificate->certificate_date_end) }} --}}
                                    @php $name = 'certificate_date_end' ; $text = ($lang == "th" ? 'สิ้นสุดวันที่': 'Valid date'); $certificate_date_end = ($lang == "th") ? \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_end)->addYear(543)->formatLocalized('%d/%m/%Y'): \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_end)->addYear(-543)->formatLocalized('%d/%m/%Y'); @endphp
                                      {{-- {{ dd($certificate_date_end) }} --}}
                                    <label for="requestNumber" class="col-md-2 control-label"> <span class="text-danger">*</span> {{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control datepicker" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate_date_end}}" autocomplete="off">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    @php $name = 'certificate_date_first' ; $text = ($lang == "th" ? 'ออกให้ครั้งแรก ณ วันที่': 'Date of initial issue'); $certificate_date_first = ($lang == "th") ? \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_first)->addYear(543)->formatLocalized('%d/%m/%Y'): \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$certificate->certificate_date_first)->addYear(-543)->formatLocalized('%d/%m/%Y'); @endphp
                                    <label for="requestNumber" class="col-md-4 control-label"><span class="text-danger">*</span> {{$text}} :</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control datepicker" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{$certificate_date_first}}" autocomplete="off">
                                        {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- <div id="scope-th" class="tab-pane fade">

                            @include('certify.certificate-export.layout.scope')

                        </div> --}}
                        <div id="other-th" class="tab-pane fade">
                            @include('certify.certificate-export.layout.other-edit')
                        </div>
                    </div>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="hidden"  id="certificate_export">
                            <button class="btn btn-success" name="submit" type="submit" value="print">
                                <i class="fa fa-print"></i> {{$lang == "th" ? "พิมพ์" : "Print"}}
                            </button>

                            <button class="btn btn-success" name="submit" type="submit" value="printscope" onclick="checkprintscope()">
                                <i class="fa fa-print"></i> {{$lang == "th" ? "พิมพ์รายละเอียดแนบท้าย" : "Print Scope"}}
                            </button>

                            <button class="btn btn-primary" name="submit" type="submit" value="submit" onclick="checkOption()">
                                <i class="fa fa-paper-plane"></i>  {{$lang == "th" ? "บันทึก" : "Save"}}
                            </button>
{{--                            @can('view-'.str_slug('committee'))--}}
                                <a class="btn btn-default" href="{{url('/certify/certificate-export')}}">
                                    <i class="fa fa-rotate-left"></i>  {{$lang == "th" ? "ยกเลิก" : "Back"}}
                                </a>
                            {{--@endcan--}}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#addForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
              })
              .on('form:submit', function() {
                let row =  $('#certificate_export').val();
                let label = '';
                if(row == 'submit'){
                    label ="กำลังบันทึก กรุณารอสักครู่..."
                }else if(row == 'printscope'){
                    label ="กำลังดาวน์โหลดไฟล์แนบท้าย กรุณารอสักครู่..."
                }else{
                    label ='กำลังดาวน์โหลดไฟล์pdf กรุณารอสักครู่...'
                }

                    // Text
                    $.LoadingOverlay("show", {
                            image       : "",
                            text        : label
                    });
                return true; // Don't submit form for this demo
              });
        });
      </script>  
    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>

    <script type="text/javascript">

        var $uploadCrop;
        var submitted = false;
        var assignment = null;

        $(document).ready(function() {

            //ปฎิทิน
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language: 'th-th',
                orientation: 'bottom'
            });

            // $("form").submit(function () {
            //     submitted = true;
            // });

            // window.onbeforeunload = function () {
            //     if (!submitted) {
            //         return 'คุณต้องการออกจากหน้านี้ใช่หรือไม่?';
            //     }
            // };

        });

        $('#certificate_for').on('change',function () {

           if($(this).val() != ''){
            $.ajax({
                url: '{!! url('certify/certificate-export/api/getAddress.api') !!}',
                method: "GET",
                data: {id: $(this).val(),_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                if (msg.status === true){
                    var data = msg.data
                    $('#lab_name').val(data.lab_name)
                    $('.lab_type').val(data.lab_type)
                    $('#address_no').val(data.address.address_no)
                    $('#address_moo').val(data.address.allay)
                    $('#address_soi').val(data.address.village_no)
                    $('#address_road').val(data.address.road)
                    $('#address_province').val(data.address.province)
                    $('#address_district').val(data.address.amphur)
                    $('#address_subdistrict').val(data.address.district) 
                    $('#address_postcode').val(data.address.postcode)
                    $('#formula').val(data.formula)
                    $('#accereditatio_no').val(data.accereditatio_no)
                    $('#certificate_no').val(data.certificate_no)
                    $('#request_number').val(data.request_number)
                    $('#certificate_no_scope').val(data.certificate_no)
                }else {
                    alert(msg.message);
                }
             });
           }else{
                    $('#lab_name').val('')
                    $('.lab_type').val('')
                    $('#address_no').val('')
                    $('#address_moo').val('')
                    $('#address_soi').val('')
                    $('#address_road').val('')
                    $('#address_province').val('')
                    $('#address_district').val('')
                    $('#address_subdistrict').val('')
                    $('#address_postcode').val('')
                    $('#formula').val('')
                    $('#accereditatio_no').val('')
                    $('#certificate_no').val('')
                    $('#request_number').val('')
                    $('#certificate_no_scope').val('')
            }
  
        });


        $('#certificate_date_start').on('change',function () {
            $.ajax({
                url: '{!! url('certify/certificate-export/api/getYear.api') !!}',
                method: "GET",
                data: {year: $(this).val(),_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                if (msg.status === true){
                    var data = msg.data
                    $('#certificate_date_end').val(data)
                    $('#certificate_date_first').val($(this).val())
                }else {
                    alert(msg.message);
                }
            });
        });

        $('#accereditatio_no,#accereditatio_no_scope').on('change',function () {
            const va = $(this).val();
            $('#accereditatio_no').val(va);
            $('#accereditatio_no_scope').val(va);
        });

        function getBranchAjax(assessment_type) {
            $.ajax({
                url: '{!! url('certificate/api/getBranch.api') !!}',
                method: "POST",
                data: {assessment_type: assessment_type,_token: '{!! csrf_token() !!}'}
            }).done(function (msg) {
                let data = JSON.parse(JSON.parse(JSON.stringify(msg)));
                let branch = $('#branch');
                if (data.status === true) {
                    branch.empty();
                    // branch.append('<option value="">-เลือกสาขา-</option>');
                    branch.attr('data-placeholder','  - เลือกสาขา -');
                    branch.val('').change();
                    $.each(data.branch, function (key,val) {
                        branch.append('<option value="'+val.id+'">'+val.title+" ("+val.title_en+")"+'</option>')
                    });
                    branch.prop('disabled',false);
                }else{
                    alert('ไม่พบข้อมูลสาขา');
                    clearBranch();
                }
            });
        }


        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 1) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }

        }

        function ShowHideForce(){

            if($('#tis_force1').prop('checked')){//ทั่วไป
                $('label[for="issue_date"]').text('วันที่ประกาศใช้');
                $('.tis_force').hide();
            }else{//บังคับ
                $('label[for="issue_date"]').text('วันที่มีผลบังคับใช้');
                $('.tis_force').show();
            }

        }

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#work-box').children().each(function(index, el) {
                $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
                $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
            });

        }

        function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

            var croppied = $uploadCrop.croppie('get');

            $('#top').val(croppied.points[1]);
            $('#left').val(croppied.points[0]);
            $('#bottom').val(croppied.points[3]);
            $('#right').val(croppied.points[2]);
            $('#zoom').val(croppied.zoom);

            $uploadCrop.croppie('result', {

                type: 'canvas',

                size: 'viewport'

            }).then(function (resp) {

                $('#croppied').val(resp);

            });
        }

        function checkprintscope() {
            $('#certificate_export').val('printscope');
            $('#addForm').submit();
        }
        function checkOption() {
            $('#certificate_export').val('submit');
            $('#addForm').submit();
        }
    </script>

@endpush
