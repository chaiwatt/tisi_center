@extends('layouts.master')

@push('css')
 
<style>
.border-dot-bottom {
    border-bottom: 1px dotted #000000;
}
.not-allowed {cursor: not-allowed;}
</style>

@endpush
 
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เอกสารแนบท้าย (ขอบข่าย)</h3>
              
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                
    <div class="clearfix"></div>
    <hr>
    
@php

    $attach_path          =  'files/applicants/check_files/';
    $purposes            =  ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
@endphp 

        <!-- .row -->
<div class="row">
    <div class="col-sm-12">
          <div class="white-box">
          <h3 class="box-title">ข้อมูลใบรับรอง (คำขอหลัก: {{$export->CertiLabTo->app_no}})</h3>
          <hr>
<div class="row">
    <div class="col-sm-12">
        <label class="col-md-3 text-right control-label">หมายเลขการรับรองที่ : </label> 
        <p class="col-md-8 border-dot-bottom"> {!! !empty($export->accereditatio_no)?  $export->accereditatio_no:'-'  !!} </p>
    </div>
    <div class="col-sm-12">
        <label class="col-md-3 text-right control-label">เลขที่ใบรับรอง : </label> 
        <p class="col-md-8 border-dot-bottom"> {!! !empty($export->certificate_no)?  $export->certificate_no:'-'  !!} </p>
    </div>
    <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">ชื่อห้องปฏิบัติการ : </label> 
          <p class="col-md-8 border-dot-bottom"> {!! !empty($export->lab_name)?  $export->lab_name:'-'  !!} </p>
   </div>
   <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">สถานที่ตั้งห้องปฏิบัติการ : </label> 
          <p class="col-md-8 border-dot-bottom"> {!! !empty($export->FormatAddress)?  $export->FormatAddress:'-'  !!} </p>
   </div>
   <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">วันที่ได้รับการรับรองครั้งแรก : </label> 
          <p class="col-md-8 border-dot-bottom"> {!! !empty($export->certificate_date_start)?  HP::DateThai($export->certificate_date_start) :'-'  !!} </p>
   </div>
   <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">วันที่ได้รับการรับรองล่าสุด : </label> 
          <p class="col-md-8 border-dot-bottom"> {!! !empty($export->certificate_date_end)?   HP::DateThai($export->certificate_date_end) :'-'  !!} </p>
   </div>
   <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">สถานะของห้องปฏิบัติการ : </label> 
          <p class="col-md-8 border-dot-bottom"> {!!  !empty($certi_lab->certi_lab_status_to->title)   ? $certi_lab->certi_lab_status_to->title : '-'  !!} </p>
   </div>
   <div class="col-sm-12">
          <label class="col-md-3 text-right control-label">ไฟล์ใบรับรองระบบงาน : </label> 
          <p class="col-md-8">
            @php
                $text = '';        
            if(!empty($export->certificate_newfile)){
                            $text =   '<a href="'. ( url('funtions/get-view').'/'.$export->certificate_path.'/'.$export->certificate_newfile.'/'.$export->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                            <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                            </a> ';
            }else if(!empty($export->attachs)){
                            $text =   '<a href="'. ( url('certify/check/file_client').'/'.$export->attachs.'/'. ( !empty($export->attachs_client_name) ? $export->attachs_client_name :  basename($export->attachs)  )).'" target="_blank">
                                            '. HP::FileExtension($export->attachs).' 
                            </a> ';
            }else if(!is_null($certi_lab)){
                            $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_lab->id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
            }
            @endphp
              {!!  $text !!} 
          </p>
   </div>
</div>
<br>
<h3 class="box-title">เอกสารแนบท้าย (ขอบข่าย)    
    {{-- <button type="button" class=" pull-right btn btn-primary " data-toggle="modal" data-target="#exampleModalExport">เพิ่มไฟล์แนบท้าย</button> --}}
</h3>
<hr>
<div class="row">
    <div class="col-sm-12">

        <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">

            <div class="table-responsive repeater-file">

                <table class="table color-bordered-table info-bordered-table" id="myTable">
                    <thead>
                        <tr>
                            <th width="1%" class="text-center">#</th>
                            <th width="18%" class="text-center">เลขที่คำขอ</th>
                            <th width="10%" class="text-center">ไฟล์แนบท้าย</th>
                            <th width="10%" class="text-center">วันที่ออกให้</th>
                            <th width="10%" class="text-center">วันที่หมดอายุ</th>
                            <th width="10%" class="text-center">สถานะ</th>
                            <th width="10%" class="text-center">วันที่บันทึก</th>
                            {{-- <th width="7%" class="text-center" width="100px">จัดการ</th> --}}
                        </tr>
                    </thead>
                    <tbody data-repeater-list="detail">
                        @if ($certilab_file_all and $certilab_file_all->count() > 0)
                            @foreach ($certilab_file_all as $key => $certilab_file)
                                <tr id="deleteFlie{{$certilab_file->id}}">
                                    <td class="no-attach">
                                        {{$loop->iteration}}
                                       
                                    </td>
                                    <td class="text-center">
                                        {{$certilab_file->app_no ?? '-'}}
                                            @php
                                                $purpose = '';
                                                if(!empty($certilab_file->app_no)){
                                                    $purpose_id =   App\Models\Certify\Applicant\CertiLab::where('app_no',$certilab_file->app_no)->value('purpose_type');
                                                    // {{$certilab_file->CertiLabTo}}
                                                    // if(!empty($purpose_id) &&  array_key_exists($purpose_id,$purposes)  ){
                                                    if($certilab_file->ref_table == "app_certi_tracking")
                                                    {
                                                        $purpose = '<p class="text-muted"><i>ตรวจติดตาม</i></p>';
                                                    }else{
                                                        $purpose = '<p class="text-muted"><i>'.$certilab_file->CertiLabTo->purposeType->name.'</i></p>';
                                                    }
                                                        
                                                    // }
                                                }
                                            @endphp
                                           {!! $purpose !!}
                                    </td>
                                    <td class="text-center">
                                        <p class="text-center">
                                            {{-- {{$certilab_file->attach_pdf}} --}}
                                            @if(!is_null($certilab_file->attach))
                                                <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach) !!}" class="attach" target="_blank">
                                                    {!! HP::FileExtension($certilab_file->attach) ?? '' !!}
                                                </a>
                                            @endif
                                            @if(!is_null($certilab_file->attach_pdf))
                 
                                                <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach_pdf) !!}" class="attach_pdf"  target="_blank">
                                                    {!! HP::FileExtension($certilab_file->attach_pdf) ?? '' !!}
                                                </a>
                                            @endif
                                        </p>
                                    </td>
                                    <td class="text-center ">{{ HP::DateThai($certilab_file->start_date) ?? '-' }}</td>
                                    <td class="text-center ">{{ HP::DateThai($certilab_file->end_date) ?? '-' }}</td>
                                    <td class="text-center ">
                                        <div class="checkbox">
                                            {!! Form::checkbox('state', $certilab_file->id , !empty($certilab_file->state) && $certilab_file->state == '1' ? true : false , ['class' => 'js-switch', 'data-color'=>'#13dafe', 'data-certilab_file_id'=>$certilab_file->id]) !!}
                                        </div>
                                    </td>  
                                    <td class="text-center created_at">{{ HP::DateThai($certilab_file->created_at  ) }}</td>
                                    {{-- <td class="text-center">
                                        @if ($certilab_file->state == 1)
                                            <button class="hide_attach btn btn-warning btn-xs edit_modal" type="button"  
                                                    data-id="{{$certilab_file->id}}"  data-app_no="{{$certilab_file->app_no}}"
                                                    data-start_date="{{  !empty($certilab_file->start_date)   ?  HP::revertDate($certilab_file->start_date,true)    : '' }}" data-end_date="{{!empty($certilab_file->end_date)   ?  HP::revertDate($certilab_file->end_date,true)    : '' }}">
                                               <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        @else
                                            <button class="hide_attach btn btn-danger btn-xs del-attach" type="button"  
                                                    data-id="{{$certilab_file->id}}" data-app_no="{{$certilab_file->app_no}}"  
                                                    data-start_date="{{  !empty($certilab_file->start_date)   ?  HP::revertDate($certilab_file->start_date,true)    : '' }}" data-end_date="{{!empty($certilab_file->end_date)   ?  HP::revertDate($certilab_file->end_date,true)    : '' }}">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
          
                <div class="pagination-wrapper">

                </div>
            </div>
        </div>
    </div>
</div>
@include ('certify/check_certificate_lab/modal.add_attachment')
@include ('certify/check_certificate_lab/modal.edit_modle')
          </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
@endsection
 
@push('js')
 
<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
 

        $('.check-readonly').prop('disabled', true);
        $('.check-readonly').parent().removeClass('disabled');
        $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});


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



        //เลือกสถานะ เปิด-ปิด
        $('#myTable tbody').on('change', '.js-switch', function(){
            var certilab_file_id =  $(this).data('certilab_file_id');
            var state = $("input[name=state]:checked").val()==1?1:0;


                            // สร้างอาร์เรย์เก็บข้อมูลทั้งหมด
            var switches = [];

            // วนลูปอ่านค่าจาก .js-switch ทุกตัวใน #myTable tbody
            $('#myTable tbody .js-switch').each(function() {
                var certilab_file_id = $(this).data('certilab_file_id'); // ดึงค่า certilab_file_id
                var state = $(this).is(':checked') ? 1 : 0; // ดึงสถานะ (checked หรือไม่)

                // เก็บค่าที่อ่านได้ในรูปของ object
                switches.push({
                    certilab_file_id: certilab_file_id,
                    state: state,
                   
                });
            });

            console.log(switches);

            if(checkNone(certilab_file_id)){
                $.ajax({
                    method: "POST",
                        url: "{{ url('certify/certificate-export-lab/update_status') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "certilab_file_id": certilab_file_id,
                            "state": state,
                            "switches":switches
                    },
                    success : function (msg){
                        if (msg == "success") {
                            $.toast({
                                heading: 'Success!',
                                position: 'top-center',
                                text: 'อัพเดท active ไฟล์สำเร็จ',
                                loaderBg: '#70b7d6',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 6
                            });
                        } 
                    }
                });
            }  
        });

 


       $("body").on("click", ".edit_modal", function() {
 
                var id = $(this).data('id');
                var app_no = $(this).data('app_no');
                $('#edit_modal_app_no').val(app_no);
                $('#edit_modal_id').val(id);
                $('#EditModalExport').modal('show');

                var start_date = $(this).data("start_date");
                if(checkNone(start_date)){
                    $('#edit_modal_start_date').val(start_date);
                }

                var end_date = $(this).data("end_date");
                if(checkNone(end_date)){
                    $('#edit_modal_end_date').val(end_date);
                }
        });
     
       $("body").on("click", ".del-attach", function() {
                var id = $(this).data('id');
                Swal.fire({
                    icon: 'error'
                    , title: 'ยืนยันการลบแถวและไฟล์แนบ !'
                    , showCancelButton: true
                    , confirmButtonColor: '#3085d6'
                    , cancelButtonColor: '#d33'
                    , confirmButtonText: 'บันทึก'
                    , cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                    $.ajax({
                            method: "POST",
                                url: "{{ url('certify/certificate_detail/del_attach') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": id
                            },
                            success : function (msg){
                                    Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'เรียกร้อยแล้ว',
                                            showConfirmButton: false,
                                            timer: 1500
                                            });
                                    $('#myTable tbody').find('#deleteFlie' + id).remove();
                                    resetAttachmentNo();
                               
                            }
                        });
                    
 

                    }
                })
              
        });


    
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

    });


   


</script>


@endpush
