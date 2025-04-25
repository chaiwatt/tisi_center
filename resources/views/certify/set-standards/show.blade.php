@extends('layouts.master')
@section('content')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .design-process-section .text-align-center {
            line-height: 25px;
            margin-bottom: 12px;
        }
        .design-process-content {
            border: 1px solid #e9e9e9;
            position: relative;
            padding: 16px 34% 30px 30px;
        }
        .design-process-content img {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            max-height: 100%;
        }
        .design-process-content h3 {
            margin-bottom: 16px;
        }
        .design-process-content p {
            line-height: 26px;
            margin-bottom: 12px;
        } 
        .process-model {
            list-style: none;
            padding: 0;
            position: relative;
            /* max-width: 800px; */
            margin: 20px auto 26px;
            border: none;
            z-index: 0;
        }
        .process-model li::after {
            background: #e5e5e5 none repeat scroll 0 0;
            bottom: 0;
            content: "";
            display: block;
            height: 4px;
            margin: 0 auto;
            position: absolute;
            right: -150px;
            top: 33px;
            width: 100%;
            z-index: -1;
        }
        .process-model li.visited::after {
            background: #0283cc;
        }
        .process-model li:last-child::after {
            width: 0;
        }
        .process-model li {
            display: inline-block;
            width: 20%;
            text-align: center;
            float: none;
        }
        .nav-tabs.process-model > li.active > a, .nav-tabs.process-model > li.active > a:hover, .nav-tabs.process-model > li.active > a:focus, .process-model li a:hover, .process-model li a:focus {
            border: none;
            background: transparent;

        }
        .process-model li a {
            padding: 0;
            border: none;
            color: #606060;
        }
        .process-model li.active,
        .process-model li.visited {
            color: #0283cc;
        }
        .process-model li.active a,
        .process-model li.active a:hover,
        .process-model li.active a:focus,
        .process-model li.visited a,
        .process-model li.visited a:hover,
        .process-model li.visited a:focus {
            color: #0283cc;
        }
        .process-model li.active p,
        .process-model li.visited p {
            font-weight: 600;
        }
        .process-model li span {
            display: block;
            height: 68px;
            width: 68px;
            text-align: center;
            margin: 0 auto;
            background: #f5f6f7;
            border: 2px solid #e5e5e5;
            line-height: 65px;
            font-size: 30px;
            border-radius: 50%;
        }
        .process-model li.active span, .process-model li.visited span  {
            color: #fff;
            background-color: #0283cc;
            border-color: #0283cc;
            border: 2px solid #0283cc;
        }
        .process-model li p {
            font-size: 14px;
            margin-top: 11px;
        }
        .process-model.contact-us-tab li.visited a, .process-model.contact-us-tab li.visited p {
            color: #606060!important;
            font-weight: normal
        }
        .process-model.contact-us-tab li::after  {
            display: none;
        }
        .process-model.contact-us-tab li.visited i {
            border-color: #e5e5e5;
        }
 

        .input-show {
            height: 27px;
            padding: 3px 7px;
           /*font-size: 15px;*/
            line-height: 1.5;
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
        }
        .input-show[disabled]{
            background-color: #FFFFFF;
        }
        .text-top{
            vertical-align: top !important;
        }
 
 
    </style>
@endpush
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกำหนดมาตรฐานการตรวจสอบและรับรอง</h3>
                    @can('view-'.str_slug('setstandard'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/set-standards') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($setstandard, [
                        'method' => 'PATCH',
                        'url' => ['/certify/set-standards', $setstandard->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

     
<div class="row">
  <div class="col-md-12 text-center">

    <ul class="nav nav-tabs process-model more-icon-preocess" role="tablist">
      <li role="presentation">
         <a id="1" href="#plan_standards" aria-controls="plan_standards" role="tab" data-toggle="tab" 
          @if(isset($setstandard) && $setstandard->status_id == 0) class="active"@endif
          >
               <span>1</span>
               <p>รายละเอียดการกำหนดมาตรฐาน</p>
           </a>
      </li>
      <li role="presentation" >
          <a id="2" href="#plan_time"  aria-controls="plan_time"   role="tab" data-toggle="tab" 
           @if(isset($setstandard) && $setstandard->status_id == 1) class="active"@endif
          >
              <span>2</span>
              <p>แผนการกำหนดมาตรฐาน</p>
        </a>
      </li>
      <li role="presentation">
          <a  id="3" href="#meet_topic" aria-controls="meet_topic" role="tab" data-toggle="tab" 
          @if(isset($setstandard) &&  in_array($setstandard->status_id,[2,3])) class="active"@endif
          >
              <span>3</span>
              <p>วาระการประชุม</p>
          </a>
      </li>   
      <li role="presentation">
          <a  id="4" href="#summarize" aria-controls="summarize" role="tab" data-toggle="tab" 
          @if(isset($setstandard) && in_array($setstandard->status_id,[4,5])) class="active"@endif
          >
              <span>4</span>
              <p>สรุปวาระการประชุม</p>
          </a>
      </li>
    </ul>

</div>
<div class="col-md-12" id="boxs_readonly"   >
 
  <!-- Tab panes -->
  <div class="tab-content">

  <div role="tabpanel" class="tab-pane " id="plan_standards">
      <div class="white-box">
          <div class="row">
              <div class="col-md-12 request-form">
                 @include ('certify.set-standards.form-plan-standards')
            </div>
       </div>
      </div>
  </div>
         
  <div role="tabpanel" class="tab-pane" id="plan_time">
      <div class="white-box">
          <div class="row">
              <div class="col-md-12 request-form">
                  @include ('certify.set-standards.form-plan-time')
            </div>
       </div>
      </div>
  </div>

  <div role="tabpanel" class="tab-pane" id="meet_topic">
      <div class="white-box">
          <div class="row">
              <div class="col-md-12 request-form">
                  @include ('certify.set-standards.form-meet-topic')
            </div>
       </div>
      </div>
  </div>

  <div role="tabpanel" class="tab-pane" id="summarize">
      <div class="white-box">
          <div class="row">
              <div class="col-md-12 request-form">
                  @include ('certify.set-standards.form-report-meet')
            </div>
       </div>
      </div>
  </div>

  
  </div>
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
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
 
  <script>
    $(document).ready(function () {

        @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
            });
       @endif


          $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 400);
                    }
                }
            });
            BtnDeleteFile();
            $('.repeater-form-file4').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add4').remove();
                    BtnDeleteFile4();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                            BtnDeleteFile4();
                        }, 500);
                    }
                }
            });
            BtnDeleteFile4();
 
 
                
            $('.start_std_check').prop('disabled', true);
            $('.start_std_check').parent().removeClass('disabled');
            $('.start_std_check').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});

           var status =    "{{!empty($setstandard->status_id) ? $setstandard->status_id : 0 }}";
                $('.repeater_form_file').remove();
                $('.repeater_form_file4').remove();
            if(status == 0){
                $('#2').removeAttr('href');
                $('#2').prop('disabled', true);
                $('#3').removeAttr('href');
                $('#3').prop('disabled', true);
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#2,#3,#4').css({"cursor": "not-allowed"});
           }else if(status == 1){
                $('#3').removeAttr('href');
                $('#3').prop('disabled', true);
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#3,#4').css({"cursor": "not-allowed"});
           }else if(status == 2 || status == 3){
                $('#4').removeAttr('href');
                $('#4').prop('disabled', true);
                $('#4').css({"cursor": "not-allowed"});
           }
     
        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }
              $('.btn_file_remove:first').hide();   
              $('.btn_file_add:first').show();
              check_name_file();
        }
        function BtnDeleteFile4(){
            if( $('.btn_file_remove4').length >= 2 ){
                $('.btn_file_remove4').show();
            } 
              $('.btn_file_remove4:first').hide();   
              $('.btn_file_add4:first').show();   
              check_name_file();

        }
        
             check_name_file();

 
            //จัดการข้อมูลในฟอร์ม
            $('.request-form').find('input, select, textarea').attr('disabled', true);
            $('.request-form').find('button,.form-action, #cancel').remove();
            $('.request-form').find('input, select, textarea').attr('required', false);

                ShowTabActive('a.active');
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                ShowTabActive(this);
             });

              function check_name_file(){
 
                $('input[type="file"]').change(function(){
                    var file_event = this;
                    setTimeout(function(){
                        var spantext = $(file_event).parent().parent().parent().find('span.fileinput-filename');
                        var result = spantext.text().slice(0,10);
                        result = result + "...";
                        spantext.text(result);
                    }, 500)
                });

            }
          



    });

    function ShowTabActive(element){
         
         var href = $(element).attr('href');
         var $curr = $(".process-model  a[href='" + href + "']").parent();

             $('.process-model li').removeClass();

             $curr.addClass("active");
             $curr.prevAll().addClass("visited");
            //  $('#plan_standards, #plan_time, #meet_topic').find('input, select ,hidden').prop('disabled',true);
            //  $(href).find('input, select ,hidden').prop('disabled',false);
             $(href).addClass('active');
     }

  

</script>
@endpush
