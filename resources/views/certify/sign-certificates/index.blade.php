@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('plugins/components/dropify/dist/css/dropify.min.css')}}">
<style>
.pointer {cursor: pointer;}

.dev-circle {
    width: 30px; 
    height: 30px;
    background-color: #b6985b;
    border-radius: 50%;
    color: #ffffff;
}

  
</style>
@endpush


@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบลงนามใบรับรองระบบงาน</h3>

                    <div class="pull-right">


                      @can('edit-'.str_slug('signcertificates'))
                          <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" id="example-modal">ลงนามใบรับรอง</button>
                      @endcan

 

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row ">
                      <div class="col-md-4 form-group">
                        <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                            <div class="col-md-9">
                                {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                            </div>
                         </div>
                      </div><!-- /.col-md-4 -->
                      <div class="col-md-3">
                         {!! Form::select('filter_certificate_type',
                             ['1'=>'CB','2'=>'IB','3'=>'LAB'], 
                            null, 
                            ['class' => 'form-control', 
                            'id'=>'filter_certificate_type',
                            'placeholder' => '-- เลือกการรับรอง --']);
                         !!}
                      </div><!-- /.col-md-1 -->
                    <div class="col-md-3">
                               {!! Form::select('filter_state',
                                ['1'=>'รอดำเนินการ','2'=>'อยู่ระหว่างลงนาม','3'=>'ลงนามใบรับรองเรียบร้อย','4'=>'ไม่อนุมัติการลงนาม'], 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_state',
                                'placeholder' => '-- เลือกสถานะ --']); !!}
                      </div><!-- /.col-md-1 -->
                      <div class="col-md-2">
                            <div class="  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="  pull-left m-l-15">
                                <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                    ล้าง
                                </button>
                            </div>
                      </div><!-- /.col-md-1 -->
                  </div><!-- /.row -->

  
                <div class="row ">
                    <div class="col-md-5  form-group">
                         <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'วันที่นำส่ง'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                            <div class="col-md-9">
                                <div class="input-daterange input-group date-range">
                                    {!! Form::text('start_date', null, ['id'=>'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                    {!! Form::text('end_date', null, ['id'=>'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                </div>
                            </div>
                         </div>
                    </div><!-- /.col-md-1 -->
                </div><!-- /.row -->

              
    
                    <div class="clearfix"></div>

                <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                            <tr>
                                <th width="1%" class="text-center">#</th>
                                <th  width="1%" ><input type="checkbox" id="checkall"></th>
                                <th width="12%" class="text-center">เลขที่ใบรับรอง</th>
                                <th width="15%" class="text-center">ผู้รับใบรับรอง</th>
                                <th width="10%" class="text-center">การรับรอง</th>
                                <th width="10%" class="text-center">วันที่นำส่ง</th>
                                <th width="15%" class="text-center">วันที่ยืนยันตัวตน</th>
                                <th width="15%" class="text-center">เจ้าหน้าที่รับผิดชอบ</th>
                                <th width="13%" class="text-center">สถานะ</th>
                                <th width="10%" class="text-center">เรีบกดู</th>
                                <th width="10%" class="text-center">preview</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
           

<!--   popup มอบหมาย   -->

<div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel1">ยืนยันการลงนามใบรับรองระบบงาน</h4>
            </div>
            <div class="modal-body ">
                
                <div class="row">
                    <div class="col-md-12  form-group text-center" id="div_during_sign">
                             <h2 class="text-warning"> <i class="fa fa-spin fa-spinner"></i> อยู่ระหว่างลงนามใบรับรองระบบงาน <span  id="span_count" class="dev-circle"> &nbsp;0&nbsp;</span>  </h2>
                    </div>


                    <div class="col-md-12  form-group div_sign">
                        {!! Form::label('sign_name', 'ผู้ลงนาม'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sign_name', null,   ['class' => 'form-control','id'=>'sign_name' ,'disabled' => true]) !!}
                            {!! $errors->first('sign_name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12 form-group div_sign">
                        {!! Form::label('sign_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-6">
                            {!! Form::text('sign_position', null,   ['class' => 'form-control','id'=>'sign_position' ,'disabled' => true]) !!}
                            {!! $errors->first('sign_position', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12 form-group div_sign">
                        {!! HTML::decode(Form::label('status', 'ลายเซ็น <span class="text-danger" style="font-size:10px;">(.jpg)</span>'.' :'.'<span class="text-danger">* </span>', ['class' => 'col-md-4 control-label text-right'])) !!}
                        <div class="col-md-6">
                            {{-- data-allowed-file-extensions="jpg"   data-default-file="{{url('uploads/files/signers/1459900251169/ReSlw8U5ci-date_time20220611_010638.png')}}"--}}
                               <input type="file" id="attach_sign"   name="attach_sign"   data-height="100px" data-width="100px"   class="dropify attachs_sec61"  /> 
                        </div>
                    </div>
                    <div class="col-md-12  ">
                        {!! Form::label('labs', 'จำนวนใบรับรอง'.':', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-8">
                              <div class="col-md-12 form-group ">
                                {!! Form::label('labs', 'ห้องปฏิบัติการ'.':', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('labs', null,   ['class' => 'form-control','id'=>'labs' ,'disabled' => true]) !!}
                                    {!! $errors->first('labs', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group ">
                                {!! Form::label('ibs', 'หน่วยรับรอง'.':', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('ibs', null,   ['class' => 'form-control','id'=>'ibs' ,'disabled' => true]) !!}
                                    {!! $errors->first('ibs', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group ">
                                {!! Form::label('cbs', 'หน่วยตรวจสอบ'.':', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('cbs', null,   ['class' => 'form-control','id'=>'cbs' ,'disabled' => true]) !!}
                                    {!! $errors->first('cbs', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 form-group ">
                                {!! Form::label('sum', 'รวม'.':', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('sum', null,   ['class' => 'form-control','id'=>'sum' ,'disabled' => true]) !!}
                                    {!! $errors->first('sum', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 form-group div_sign">
                        <label class="col-md-4 control-label text-right"></label>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" id="again_otp">ขอรหัส OTP</button>
                        </div>
                        <div class="col-md-6">
                            <span id="span_time">กรุณาลองใหม่อีกครั้งใน <span id="time">03:00</span> นาที</span>
                        </div>
                    </div>
                    
                    {{-- <div class="col-md-12 form-group div_sign">
                        {!! Form::label('', '', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary"  id="again_otp"> ขอรหัส OTP </button>
                        </div>
                        <div class="col-md-6">
                            <span id="span_time" >กรุณาลองใหม่อีดครั้ง ใน  <span id="time" >03:00</span>นาที</span> 
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-12 form-group div_sign">
                        {!! Form::label('Ref_otp', 'OTP Ref'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-6">
                            {!! Form::text('Ref_otp', null,   ['class' => 'form-control','id'=>'Ref_otp' ,'disabled' => true ]) !!}
                            {!! $errors->first('Ref_otp', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12 form-group div_sign">
                        {!! Form::label('otp', 'กรอกรหัส OTP'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
                        <div class="col-md-6">
                            {!! Form::text('otp', null,   ['class' => 'form-control','id'=>'otp']) !!}
                            {!! $errors->first('otp', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div> --}}

                    <div class="col-md-12 form-group div_sign">
                        <label for="Ref_otp" class="col-md-4 control-label text-right">OTP Ref :</label>
                        <div class="col-md-6">
                            <input type="text" name="Ref_otp" id="Ref_otp" class="form-control" disabled>
                            @if ($errors->has('Ref_otp'))
                                <p class="help-block">{{ $errors->first('Ref_otp') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 form-group div_sign">
                        <label for="otp" class="col-md-4 control-label text-right">กรอกรหัส OTP :</label>
                        <div class="col-md-6">
                            <input type="text" name="otp" id="otp" class="form-control">
                            @if ($errors->has('otp'))
                                <p class="help-block">{{ $errors->first('otp') }}</p>
                            @endif
                        </div>
                    </div>
                    
                </div>



            </div>
            <div class=" text-center  form-group">
                     <button type="button" class="btn btn-success div_sign"    id="assign">ยืนยัน</button>
                     <button type="button" class="btn btn-secondary div_sign"   id="modal_close" >ยกเลิก</button>
                
             </div>

        </div>
    </div>
</div>

<form method="POST" enctype="multipart/form-data" id="laravel-ajax-file-upload" action="javascript:void(0)" >
</form>



 




                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('plugins/components/dropify/dist/js/dropify.min.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
     


 
    <script>
        $(document).ready(function () {
                // Basic
                // $('.dropify').dropify();
                // $('#exampleModal').modal('show');
                $('.attachs_sec61').change( function () {
                    var fileExtension = ['jpg'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .jpg',
                        '',
                        'info'
                        )
                    this.value = '';
                    return false;
                    }
                }); 


            //ช่วงวันที่
            $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
            
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/certify/sign-certificates/data_list') !!}',
                    data: function (d) {
                      
                        d.filter_search = $('#filter_search').val();
                        d.filter_state = $('#filter_state').val();
                        d.filter_certificate_type = $('#filter_certificate_type').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'app_no', name: 'app_no' }, 
                    { data: 'name', name: 'name' },
                    { data: 'lab_type', name: 'lab_type' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'confirm_date', name: 'confirm_date' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                    { data: 'preview', name: 'preview' }
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-2,-1] }
                ],
                fnDrawCallback: function() {
                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);

                    $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
                }
            });

 

            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
              $('#filter_search').val('');
              $('#filter_state').val('').select2();
              $('#filter_certificate_type').val('').select2();
              $('#filter_start_date').val('');
              $('#filter_end_date').val('');
              table.draw();
           });
 
           $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
                }

         });
 
         $('#exampleModal').modal('hide');
              var funky;
  
  
             $(document).on('click', '#example-modal', function(){
                var ids = [];
                var cbs = 0;
                var ibs = 0;
                var labs = 0;
                $('.item_checkbox:checked').each(function(index, element){
                    ids.push($(element).val());
                    var  certificate_type   = $(element).data('certificate_type');
 
                     if(certificate_type == 1){
                         (cbs ++);
                     }else  if(certificate_type == 2){
                          (ibs ++);
                     }else  if(certificate_type == 3){
                         (labs ++);
                     }
                });
                   
                if(ids.length > 0){

                        $('#cbs').val(cbs);
                         $('#ibs').val(ibs);
                        $('#labs').val(labs);
                        $('#sum').val(ids.length);

                        $('.div_sign').show(); 
                        $('#div_during_sign').hide(); 
                        $.LoadingOverlay("show", {  image: "",   text: "กำลังดาวน์โหลดข้อมูล"});
                    if($('#Ref_otp').val() == ''){
                        console.log(ids);
                        $.ajax({
                                type:"get",
                                url:  "{{ url('/certify/sign-certificates/getsign') }}",
                                data:{
                                    ids: ids
                                },
                                success:function(object ){
                                        if(object .message == true){
                                            $('#exampleModal').modal('show');
                                            $.LoadingOverlay("hide");
                                            var send = object .send;
                                            // console.log(send);
                                                $('#sign_name').val(send.sign_name);
                                                $('#sign_position').val(send.sign_position);
                                                $("#attach_sign").attr("data-allowed-file-extensions",'jpg');
                                                $("#attach_sign").attr("data-default-file", send.attach_sign);
                                                 $('#attach_sign').dropify();

                                                 if(funky == undefined){
                                                        $('#span_time').hide();
                                                 }else{
                                                        $('#span_time').show();
                                                 }
                                                // $('#Ref_otp').val(send.Ref_otp);
                                               
                                                // var duration = 60 * 3;
                                                // var timer = duration, minutes, seconds;
                                                // funky = setInterval(function() {
                                                //     minutes = parseInt(timer / 60, 10);
                                                //         seconds = parseInt(timer % 60, 10);
                                                //         minutes = minutes < 10 ? "0" + minutes : minutes;
                                                //         seconds = seconds < 10 ? "0" + seconds : seconds;
                                                //             if (--timer < 0) {
                                                //                   $.ajax({
                                                //                         type:"get",
                                                //                         url:  "{{ url('/certify/sign-certificates/getOtpTimeOut') }}",
                                                //                         data:{
                                                //                             _token: "{{ csrf_token() }}",
                                                //                             ref_otp:$('#Ref_otp').val()
                                                //                         },
                                                //                         success:function(obj){
                                                //                             if(obj.message == true ){
                                                //                                 $('#exampleModal').modal('hide');
                                                //                                 Swal.fire({
                                                //                                     position: 'center',
                                                //                                     icon: 'warning',
                                                //                                     title: 'หมดเวลาการยืนยันตัวตน',
                                                //                                     showConfirmButton: false,
                                                //                                     timer: 1500
                                                //                                 });
                                                //                                 $('#time').html('03:00');
                                                //                             }
                                                //                         }
                                                //                     }); 
                                                //                 clearInterval(funky);
                                                //             }else{
                                                //                 $('#time').html(minutes + ":" + seconds);  
                                                //             }
                                                // }, 1000);
                                        }
                                }
                        }); 
                    }

               
                }else{
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }
            });


            $(document).on('click', '#modal_close', function(){
                  $('#exampleModal').modal('hide');
            });
            // ขอรหัส otp ใหม่
            $(document).on('click', '#again_otp', function(){
                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                    // แสดง Loading Overlay
                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังส่ง OTP ไปทางอีเมล กรุณารอสักครู่..."
                });
// return;
               $.ajax({
                    type:"get",
                    url:  "{{ url('/certify/sign-certificates/getOtp') }}",
                    data:{
                        _token: "{{ csrf_token() }}",
                        id:id,
                        ref_otp:$('#Ref_otp').val()
                    },
                    success: function (data) {
                        $.LoadingOverlay("hide");
                        console.log(data)
                        $('#Ref_otp').val(data.Ref_otp);
                        $('#span_time').show();
                          clearInterval(funky);
                            var duration = 60 * 3;
                            var timer = duration, minutes, seconds;
                            funky = setInterval(function() {
                                minutes = parseInt(timer / 60, 10);
                                seconds = parseInt(timer % 60, 10);

                                minutes = minutes < 10 ? "0" + minutes : minutes;
                                seconds = seconds < 10 ? "0" + seconds : seconds;
                                    if (--timer < 0) {
                                            $.ajax({
                                            type:"get",
                                            url:  "{{ url('/certify/sign-certificates/getOtpTimeOut') }}",
                                            data:{
                                                _token: "{{ csrf_token() }}",
                                                ref_otp:$('#Ref_otp').val()
                                            },
                                            success:function(obj){
                                                // console.log(obj)
                                                if(obj.message == true ){
                                                    $('#exampleModal').modal('hide');
                                                    Swal.fire({
                                                        position: 'center',
                                                        icon: 'warning',
                                                        title: 'หมดเวลาการยืนยันตัวตน',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                    $('#time').html('03:00');
                                                }
                                            }
                                        }); 
                                        clearInterval(funky);
                                    }else{
                                        $('#time').html(minutes + ":" + seconds);  
                                    }
                            }, 1000);
                          }
                    });

                });

                $(document).on('click', '#assign', function(){

                    // if($('#attach_sign').val() == ''){ 
                    //           Swal.fire({
                    //                         position: 'center',
                    //                         icon: 'warning',
                    //                         title: 'กรุณาแนบไฟล์ลายเซ็นลายเซ็น',
                    //                         showConfirmButton: false,
                    //                         timer: 1500
                    //                    });
                    // }else 
                    if($('#otp').val() != '')
                    {
                        var ids = [];
                        $('.item_checkbox:checked').each(function(index, element){
                            ids.push($(element).val());
                        });

                    //     $.ajax({
                    //         type:"get",
                    //         url:  "{{ url('/certify/sign-certificates/getCheckOtp') }}",
                    //         data:{
                    //             _token: "{{ csrf_token() }}",
                    //             ref_otp:$('#Ref_otp').val(),
                    //             otp:$('#otp').val(),
                    //             ids: ids
                    //         },
                    //         success:function(obj){
                    //             if(obj.message == true ){

                    //                     clearInterval(funky);
                    //                     //  $.LoadingOverlay("show", {  image: "",   text: ""});
                                        
                    //                     $('#laravel-ajax-file-upload').submit();
                    //                     $('.div_sign').hide(); 
                    //                     $('#div_during_sign').show(); 
                    //                     var funky2;
                    //                     var duration2 = 60 * 60;
                    //                     var timer2 = duration2, minutes2, seconds2;
                    //                     funky2 = setInterval(function() {
                    //                                 if (--timer2 < 0) {
                    //                                         table.draw();
                    //                                         Swal.fire({
                    //                                                     position: 'center',
                    //                                                     icon: 'warning',
                    //                                                     title: 'หมดเวลาการยืนยันตัวตน',
                    //                                                     showConfirmButton: false,
                    //                                                     timer: 1500
                    //                                         });
                    //                                         // $.LoadingOverlay("hide");
                    //                                         $('#exampleModal').modal('hide');
                    //                                         clearInterval(funky2);
                                                            
                    //                                         $('#span_time').hide();
                    //                                 }else{
                    //                                     $.ajax({
                    //                                             type:"get",
                    //                                             url:  "{{ url('/certify/sign-certificates/check_update_sign') }}",
                    //                                             data:{
                    //                                                 _token: "{{ csrf_token() }}",
                    //                                                 ids:ids
                    //                                             },
                    //                                             success:function(obj1){
                    //                                                 if(obj1.count == ids.length){
                                                                        
                    //                                                         $('#span_count').html(obj1.count);  
                    //                                                         Swal.fire({
                    //                                                             position: 'center',
                    //                                                             icon: 'success',
                    //                                                             title: 'ลงนามใบรับรองระบบงานเรียบร้อยแล้ว',
                    //                                                             showConfirmButton: false,
                    //                                                             timer: 1500
                    //                                                         });
                    //                                                         // $.LoadingOverlay("hide");
                    //                                                         $('#exampleModal').modal('hide');
                    //                                                         clearInterval(funky2);
                    //                                                         $('#span_time').hide();
                                                                            
                    //                                                             table.draw();
                                                                            
                    //                                                 }else{
                    //                                                     $('#span_count').html(obj1.count);  
                    //                                                 }
                    //                                             }
                    //                                     }); 
                    //                                 }
                    //                         }, 1000);

                    //             }else{
                    //                 $('#otp').val('');
                    //                 Swal.fire({
                    //                         position: 'center',
                    //                         icon: 'warning',
                    //                         title: 'รหัส otp ไม่ถูกต้องกรุณากรอกใหม่',
                    //                         showConfirmButton: false,
                    //                         timer: 1500
                    //                 });
                    //             }
                    //         }
                    // }); 

                    $.ajax({
                        type: "get",
                        url: "{{ url('/certify/sign-certificates/getCheckOtp') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ref_otp: $('#Ref_otp').val(),
                            otp: $('#otp').val(),
                            ids: ids
                        },
                        success: function (obj) {
                            if (obj.message === true) {
                                clearInterval(funky);

                                $('#laravel-ajax-file-upload').submit();
                                $('.div_sign').hide();
                                $('#div_during_sign').show();

                                var funky2;
                                var duration2 = 60 * 60;
                                var timer2 = duration2, minutes2, seconds2;
                                var isSigned = false; // สถานะการลงนาม

                                funky2 = setInterval(function () {
                                    if (--timer2 < 0) {
                                        table.draw();
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'หมดเวลาการยืนยันตัวตน',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $('#exampleModal').modal('hide');
                                        clearInterval(funky2);
                                        $('#span_time').hide();
                                    } else {
                                        $.ajax({
                                            type: "get",
                                            url: "{{ url('/certify/sign-certificates/check_update_sign') }}",
                                            data: {
                                                _token: "{{ csrf_token() }}",
                                                ids: ids
                                            },
                                            success: function (obj1) {
                                                $('#span_count').html(obj1.count);
                                                if (!isSigned && obj1.count === ids.length) {
                                                    isSigned = true; // อัปเดตสถานะว่าลงนามเสร็จแล้ว
                                                    Swal.fire({
                                                        position: 'center',
                                                        icon: 'success',
                                                        title: 'ลงนามใบรับรองระบบงานเรียบร้อยแล้ว',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                    $('#exampleModal').modal('hide');
                                                    clearInterval(funky2);
                                                    $('#span_time').hide();
                                                    table.draw();
                                                }
                                            }
                                        });
                                    }
                                }, 1000);

                            } else {
                                $('#otp').val('');
                                Swal.fire({
                                    position: 'center',
                                    icon: 'warning',
                                    title: 'รหัส otp ไม่ถูกต้องกรุณากรอกใหม่',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        }
                    });

                    }else{
                        Swal.fire({
                                    position: 'center',
                                    icon: 'warning',
                                    title: 'กรุณากรอก otp?',
                                    showConfirmButton: false,
                                    timer: 1500
                            });
                    }

                });
                  
        });

        
        function confirm_delete() {
                    return confirm("ยืนยันการลบข้อมูล?");
        }

      
        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
 <script type="text/javascript">
    $(document).ready(function (e) {
        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
           });
        $('#laravel-ajax-file-upload').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                    formData.append('otp_id',$('#otp').val());
                let file = $('input[name=attach_sign]')[0].files[0];
                if(file !== '' && file !== undefined){
                    formData.append('attach_sign', file, file.name);
                }
                $('.item_checkbox:checked').each(function(index, element){
                    formData.append('ids[]', $(element).val());
                    console.log($(element).val());
                });
             

             $.ajax({
                type:'POST',
                url:"{{ url('/certify/sign-certificates/save_sign') }}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    this.reset();
              
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
    });
    </script>
@endpush
