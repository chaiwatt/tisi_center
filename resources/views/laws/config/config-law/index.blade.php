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
    <div class="col-md-3">
      <div class="white-box">
        <ul class="nav tabs-vertical">
            <li class="tab active">
                <a data-toggle="tab" href="#contact" aria-expanded="true"> 
                    <span>
                        <i class="mdi mdi-account-card-details"></i>
                         ข้อมูลติดต่อในอีเมล
                    </span>
                </a>
            </li>
            <li class="tab">
                <a data-toggle="tab" href="#mail" aria-expanded="false"> 
                    <span>
                        <i class="mdi mdi-email"></i>
                        ตั้งค่าการส่งอีเมล
                    </span>
                </a>
            </li>
            <li class="tab ">
                <a data-toggle="tab" href="#receipt" aria-expanded="false"> 
                    <span>
                        <i class="mdi mdi-cash"></i>
                        ตั้งค่าใบสำคัญรับเงิน
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
    <div class="col-md-9 white-box">
        <div class="tab-content">

            <div id="contact" class="tab-pane active">
                @include('laws.config.config-law.form-contact')
            </div>

            <div id="mail" class="tab-pane">
                @include('laws.config.config-law.form-mail')
            </div>

            <div id="receipt" class="tab-pane ">
                @include('laws.config.config-law.form-receipt')
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


  
        $("input[name=check_contact_mail_footer]").on("ifChanged",function(){
            check_contact_mail_footer();
        });

           check_deduct_money();
        $("input[name=check_deduct_money]").on("ifChanged",function(){
            check_deduct_money();
        });

           check_deduct_vat();
        $("input[name=check_deduct_vat]").on("ifChanged",function(){
            check_deduct_vat();
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


        $('#btn_add_form').click(function (e) {
            var title = $('#email_title').val();
            if( title != '' ){
                $.ajax({
                    method: "POST",
                    url: "{{ url('/law/config/config-law/sendemail') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "title": title,
                    },
                success : function (msg){
                    if (msg == "success") {
                        toastr.success('บันทึกสำเร็จ !');
                        location.reload();
                    }
                    }
                });
            }

        });

    });
    function check_contact_mail_footer(){
           var status = $("input[name=check_contact_mail_footer]:checked").val();
           if(status == '1'){ // แสดงข้อมูลติดต่อกลาง
                $('#contact_mail_footer').summernote('enable');
                var contact_mail_footer = '{!! $config->contact_mail_footer ?? '' !!}';
                $('#contact_mail_footer').summernote('code',contact_mail_footer);
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
   

      function check_deduct_money(){
           var status = $("input[name=check_deduct_money]:checked").val();
           if(status == '1'){ 
               $('.div_deduct_money').show();
               $('#number_deduct_money, #agency_deduct_money').prop('required', true);
           }else { 
                $('.div_deduct_money').hide(); 
                $('#number_deduct_money, #agency_deduct_money').prop('required', false);
           }
      }
      function check_deduct_vat(){
           var status = $("input[name=check_deduct_vat]:checked").val();
           if(status == '1'){ 
               $('.div_deduct_vat').show();
               $('#number_deduct_vat, #agency_deduct_vat').prop('required', true);
           }else { 
                $('.div_deduct_vat').hide(); 
                $('#number_deduct_vat, #agency_deduct_vat').prop('required', false);
           }
      }

</script>

@endpush
