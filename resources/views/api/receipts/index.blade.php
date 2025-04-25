@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แนบหลักฐานใบสำคัญรับ</h3>
                    <div class="clearfix"></div>
                    <hr>
 
  <form id="form_sends" class="form-horizontal"  method="post" >
    {{ csrf_field() }}
 
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3 required">   หลักฐานใบสำคัญรับ (ลงชื่อ) </label>
            <div class="col-md-4">
                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                          <div class="form-control " data-trigger="fileinput" >
                              <span class="fileinput-filename"></span>
                          </div>
                          <span class="input-group-addon btn btn-default btn-file">
                              <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                              <span class="input-group-text btn-file">
                                  <span class="fileinput-new">เลือกไฟล์</span>
                                  <span class="fileinput-exists">เปลี่ยน</span>
                                  <input type="file" name="attach" id="attach" accept=".pdf"  required class="check_max_size_file">
                              </span>
                          </span>
                      </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3">หมายเหตุ</label>
            <div class="col-md-4">
                 <textarea  name="send_remark" class="form-control" rows="3"></textarea> 
            </div>
        </div>
    </div>
</div>       
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3"></label>
            <div class="col-md-4">
                  <button  type="button" id="button_save"   class="btn btn-primary btn-lg btn-block">
                          <i class="fa fa-paper-plane"></i> บันทึก
                  </button>
            </div>
        </div>
    </div>
</div>    
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2 text-muted">
            หมายเหตุ : ท่านจะได้รับเงินรางวัลภายหลังเมื่อมีการอนุมัติเบิกจ่ายเรียบร้อยแล้ว โดยสามารถติดตามผลผ่านทางเมลของท่าน
        </div>
    </div>
</div>    
<hr>
@php
    $config = HP::getConfig(false);
@endphp
@if (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 1)  <!-- แสดงข้อมูลติดต่อกลาง -->
    {!! $config->contact_mail_footer  !!}
@elseif (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 2) <!-- แสดงข้อมูลติดต่อผู้บันทึก -->
@php
$name       =  auth()->user()->FullName ?? '';
$reg_wphone =   auth()->user()->reg_wphone ?? '';
$reg_email  =  auth()->user()->reg_email ?? '';
@endphp
   {!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}
@endif           
                  </form>         

                </div>
            </div>
        </div>
    </div>

    
@endsection




{{-- @extends('layouts.app')

 
@section('content')

<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <a class="navbar-toggle font-20 hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
           data-target=".navbar-collapse">
            <i class="fa fa-bars"></i>
        </a>
        <div class="top-left-part">
  
                <a class="logo" href="{{ url('/home') }}">
                    <b>
                        <img src="{{asset('images/logo01.png')}}"  width="35px" alt="home"/>
                    </b>
                    <span>
                        บริการอิเล็กทรอนิกส์ สมอ.
                    </span>
                </a>
 

        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li>
                <div role="search" class="app-search hidden-xs">
                    <i class="icon-magnifier"></i>
                    <input type="text" placeholder="ค้นหาเมนู..." class="form-control" id="search-menu">
                </div>
            </li>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">
        </ul>
    </div>
</nav>

<section id="wrapper" class="login-register">

       <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                </div>
            </div>
        </div>
    </div>          
 
</section>

@endsection --}}

@push('js')
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2@11.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>

  $(document).ready(function () {
    $("body").on("click", "#button_save", function() {
            $('#form_sends').submit();
       });
    $('#form_sends').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
                })  .on('form:submit', function() {

             
                    var formData = new FormData($("#form_sends")[0]);
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('id', '{{$id}}');

                        var attach = $('#attach').prop('files')[0];
                        if( checkNone(attach) ){
                            formData.append('attach', $('#attach')[0].files[0]); 
                        }
                    
                    $.ajax({
                        type: "POST",
                        url: "{{ url('api/v1/reward/receipts/update') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                            $('#form_sends').find('ul.parsley-errors-list').remove();
                            $('#form_sends').find('input,textarea').removeClass('parsley-success');
                            $('#form_sends').find('input,textarea').removeClass('parsley-error');
                            $('#form_sends').find('input,textarea').val('');
                            $('#attach').parent().parent().find('.fileinput-exists').click();
                            if (msg != "") {
                    

                            if (msg.message == true) {
                                   Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'บันทึกข้อมูลสำเร็จ',
                                        html:'ท่านจะได้รับเงินรางวัลภายหลังเมื่อมีการอนุมัติเบิกจ่ายเรียบร้อยแล้ว โดยสามารถติดตามผลผ่านทางอีเมลของท่าน',
                                        confirmButtonText: 'รับทราบ',
                                        showConfirmButton: true,
                                        width: 600
                                    });
                            }else{ 
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                 $('#SendAddModals').modal('hide');
                                 $('#receipts_id,#send_remark').val('');
                            }


                            }   
                        }
                    });
                   return false;
            });

            function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

  });
</script>
@endpush
