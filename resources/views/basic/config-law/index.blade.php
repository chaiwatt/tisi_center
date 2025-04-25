@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .note-group-select-from-files {
            display: none;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">ตั้งค่าระบบงานคดี</h3>

        <div class="clearfix"></div>
        <hr>

        <!-- Nav tabs -->
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                  <span class="visible-xs">
                    <i class="ti-home"></i>
                  </span>
                  <span class="hidden-xs">ข้อมูลติดต่อในอีเมล</span>
                </a>
            </li>
        </ul>

        {!! Form::model($config, ['url' => '/basic/config-law', 'class' => 'form-horizontal']) !!}

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade active in" id="home">
                <div class="col-md-12">


              
                    <div class="form-group required{{ $errors->has('check_contact_mail_footer') ? 'has-error' : ''}}">
                        {!! Form::label('check_contact_mail_footer', 'เงื่อนไข:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            <label>{!! Form::radio('check_contact_mail_footer', '1',(!empty($config->check_contact_mail_footer)  && $config->check_contact_mail_footer == 1) ? true : false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} แสดงข้อมูลติดต่อกลาง &nbsp;&nbsp;</label>
                            <label>{!! Form::radio('check_contact_mail_footer', '2',(!empty($config->check_contact_mail_footer)  && $config->check_contact_mail_footer == 2) ? true : false  , ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} แสดงข้อมูลติดต่อผู้บันทึก &nbsp;&nbsp;</label>
                            <label>{!! Form::radio('check_contact_mail_footer', '3', (!empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 3) ? true :  false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} ไม่แสดง </label>

                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('contact_mail_footer') ? 'has-error' : ''}}">
                        <div class="col-md-4">
                            {!! Form::label('contact_mail_footer', 'ข้อมูลติดต่อสอบถาม:', ['class' => 'control-label pull-right']) !!}
                        </div>
                        <div class="col-md-8">
                            {!! Form::textarea('contact_mail_footer', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('contact_mail_footer', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                </div>
            </div>

          
        </div>

        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">

                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('config-law'))
                    <a class="btn btn-default" href="{{ url()->previous() }}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<!-- icheck -->
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- summernote -->
<script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<!-- tagsinput -->
<script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function() {

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


        check_contact_mail_footer();
        $("input[name=check_contact_mail_footer]").on("ifChanged",function(){
            check_contact_mail_footer();
        });

        // Switchery
        $(".switch").each(function() {
        new Switchery($(this)[0], {color: '#13dafe'})
        });

        //ข้อมูลการติดต่อ
        $('#contact_mail_footer').summernote({
            placeholder: 'กรอกข้อมูลที่นี่.....',
            fontNames: ['Lato', 'Arial', 'Courier New'],
            height: 300
        });

    });
    function check_contact_mail_footer(){
           var status = $("input[name=check_contact_mail_footer]:checked").val();
           if(status == '1'){ // แสดงข้อมูลติดต่อกลาง
                $('#contact_mail_footer').summernote('enable');
                $('#contact_mail_footer').summernote('code','<p><b>สอบถามข้อมูลเพิ่มเติมได้ที่ : กองกฏหมาย</b><br>&nbsp; -Tel. : 0-2430-6830 ต่อ 2000 <br>&nbsp; -E-mail. : law2022@tisi.go.th <br> &nbsp; -Line. : @law2022</p>');
           }else if(status == '2'){ // แสดงข้อมูลติดต่อผู้บันทึก
                $("#contact_mail_footer").summernote('disable');
                var name = '{{ auth()->user()->FullName ?? '' }}';
                var reg_wphone = '{{ auth()->user()->reg_wphone ?? '' }}';
                var reg_email = '{{ auth()->user()->reg_email ?? '' }}';
                $('#contact_mail_footer').summernote('code','<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'+name+'<br>โทร. '+reg_wphone+'<br>อีเมล '+reg_email+'</p>');
           }else if(status == '3'){  //  ไม่แสดง
                $('#contact_mail_footer').summernote('enable');
           }
      }
   



</script>

@endpush
