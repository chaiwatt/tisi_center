@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <style>
        .div_dotted {
            border-bottom: 1px dotted #000;
            padding: 0 0 5px 0;
            cursor: not-allowed;
        }
    
        .input_dotted {
            border: none;
            border-bottom: 1px dotted #000;
            cursor: not-allowed;
        }
    
        legend {
            margin-bottom: 0px;
        }
     
        .pointer {
            cursor: pointer;
        }
     
    
        .div-show{
            display: block;
        }
        .div-hide{
            display: none;
        }
        .input_dotted[disabled] {
            background-color: #ffffff;
            opacity: 1;
        }
 
        .record {
            color: blue;
        }
        /* mouse over link */
        .record:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }

        .input-xs {
        height: 30px;
        padding-left: 10px;
        font-size: 15px;
        line-height: 1.5;
        border-radius: 3px;
        }
 
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
            font-size: 16px;
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


@media screen and (max-width: 560px) {
  .more-icon-preocess.process-model li span {
        font-size: 23px;
        height: 50px;
        line-height: 46px;
        width: 50px;
    }
    .more-icon-preocess.process-model li::after {
        top: 30px;
    }
}
@media screen and (max-width: 380px) { 
    .process-model.more-icon-preocess li {
        width: 16%;
    }
    .more-icon-preocess.process-model li span {
        font-size: 16px;
        height: 35px;
        line-height: 32px;
        width: 35px;
    }
    .more-icon-preocess.process-model li p {
        font-size: 8px;
    }
    .more-icon-preocess.process-model li::after {
        top: 18px;
    }
    .process-model.more-icon-preocess {
        text-align: center;
    }
}

.btn-outline-danger {
    color: #dc3545;
    background-color: transparent;
    background-image: none;
    border-color: #dc3545;
}
 
.free-dot {
             border-bottom: thin dotted #000000;
            padding-bottom: -5px !important;
   }
     
    </style>
@endpush

<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ข้อมูลผู้กระทำความผิด</h5>
            </legend>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">ชื่อผู้ประกอบการ/TAXID :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->offend_name) &&  !empty($cases->offend_taxid)   ? $cases->offend_name .' | '.$cases->offend_taxid: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มอก./ผลิตภัณฑ์ :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->tis->tb3_Tisno) &&  !empty($cases->tis->tb3_TisThainame)   ? $cases->tis->tb3_Tisno .' | '.$cases->tis->tb3_TisThainame: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มาตราความผิด :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$cases->law_cases_result_to->OffenseSectionNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                        <label class="control-label col-md-3">อัตราโทษ :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->PunishNumber)   ?  implode(", ",$cases->law_cases_result_to->PunishNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            
 
            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">การจับกุม :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_basic_arrest)  ? $cases->law_basic_arrest : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                        <label class="control-label col-md-3">การฟ้องดำเนินคดี :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->prosecute) && $payments->law_cases_result_to->prosecute == '1'   ? 'มี' : 'ไม่มี'  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">ค่าปรับ :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($payments->law_cases_payments_detail_to->amount)   ?  number_format($payments->law_cases_payments_detail_to->amount,2)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                        <label class="control-label col-md-3">สถานะชำระ :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($payments->paid_status) && $payments->paid_status == '2'   ? 'ชำระเงินเรียบร้อย' : 'ยังไม่ชำระ'  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
        </fieldset>
    </div>

    <div class="col-md-4">

        <div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!!   !empty($cases->law_reward_to->StatusText) ?   $cases->law_reward_to->StatusText  :  'รอคำนวณเงิน'   !!} </div>

        <fieldset class="white-box">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">เลขคดี :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->case_number)   ? $cases->case_number : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div> 
            </div>

            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">นิติกร :</label>
                        <div class="col-md-7">
                             {!! Form::text('',  !empty($cases->user_lawyer_to->FullName)   ? $cases->user_lawyer_to->FullName : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>  

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">ผู้คำนวณ :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->law_reward_to->user_created->FullName)  ? $cases->law_reward_to->user_created->FullName : auth()->user()->FullName  ,  ['class' => 'form-control input_dotted', 'disabled'=> true,  'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึก' ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">วันที่คำนวณ :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->law_reward_to->user_created->created_at) ?  HP::DateThaiFull($cases->law_reward_to->created_at) :  HP::DateThaiFull(date("Y-m-d"))  ,  ['class' => 'form-control input_dotted', 'disabled'=> true,  'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึก']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-md-12 text-center">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs process-model more-icon-preocess"  >
        <li role="presentation"    >
            <a id="1"  href="#paid" aria-controls="paid" role="tab" data-toggle="tab"
            @if(isset($cases) && $cases->step_froms == '1') class="active"@endif
            >
                <span>1</span>
                <p>รายชื่อผู้มีสิทธิ์ได้รับเงิน</p>
            </a>
        </li>
        <li role="presentation">
            <a  id="2"  href="#calculate" aria-controls="calculate" role="tab" data-toggle="tab"
            @if(isset($cases) && $cases->step_froms == '2') class="active"@endif 
            >
                <span>2</span>
                <p>คำนวณ</p>
            </a>
        </li>
        <li role="presentation"  >
            <a  id="3"  href="#print" aria-controls="print" role="tab" data-toggle="tab"
             @if(isset($cases) && $cases->step_froms == '3') class="active"@endif 
            >
                <span>3</span>
                <p>พิมพ์ใบสรุปการคำนวณ</p>
            </a>
        </li> 
        </ul>

    </div>
</div>

 
<div class="col-md-12" id="boxs_readonly"   >
    {!! Form::hidden('step_froms', $cases->step_froms ?? '1',['id'=>'step_froms']) !!}
    {!! Form::hidden('status', $cases->status ?? '1',['id'=>'status']) !!}
    <!-- Tab panes -->
    <div class="tab-content">

    <div role="tabpanel" class="tab-pane " id="paid">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="semi-bold">รายชื่อผู้มีสิทธิ์ได้รับเงิน</h3>
                        </div>
                        <div class="col-sm-6">
                            @can('add-'.str_slug('law-reward-calculations'))
                            <button type="button" class="btn btn-success waves-effect waves-light pull-right" id="ButtonModal">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มรายการใหม่</b>
                            </button>
                            @endcan
                        </div>
                    </div>
                        @include ('laws.reward.calculations.form-paid',['staff_lists'=> $cases->staff_lists])
              </div>
         </div>
        </div>
    </div>
           
    <div role="tabpanel" class="tab-pane" id="calculate">
        <div class="white-box">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="semi-bold">คำนวณ</h3>
                    @include ('laws.reward.calculations.form-calculate')
              </div>
         </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="print">
        <div class="white-box">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="semi-bold">พิมพ์ใบสรุปการคำนวณ</h3>
                </div>
                <div class="col-sm-6">
                     <a type="button"  class="btn  btn-outline btn-danger pull-right" href="{{ url('/law/reward/calculations/print_pdf/'. base64_encode($cases->id) )}}"  target="_blank">
                          <b>พิมพ์ .PDF</b>
                    </a>
                </div>
            </div>
             @include ('laws.reward.calculations.form-print')
        </div>
    </div>
    
    </div>
</div>



@push('js')
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/function.js') }}"></script>
    <script>

        $(document).ready(function() {

            
                @if(\Session::has('flash_message'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session()->get('flash_message')}}',
                    showConfirmButton: false,
                    timer: 1500
                    });
                @endif
            
                ShowTabActive('a.active');
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    ShowTabActive(this);
                });
                
                @if ($cases->step_froms == '1')
                    $('#2').removeAttr('href');
                    $('#2').prop('disabled', true);
                    $('#3').removeAttr('href');
                    $('#3').prop('disabled', true);
                    $('#2,#3').css({"cursor": "not-allowed"});
                    $('#div_save_paid').show();
                    $('#div_save_calculate').hide();
                      
                @elseif ($cases->step_froms == '2')
                    $('#3').removeAttr('href');
                    $('#3').prop('disabled', true);
                    $('#3').css({"cursor": "not-allowed"});
                    $('#div_save_paid').hide();
                    $('#div_save_calculate').show();
                @endif

                // $('#PaidModals').modal('show'); 
                $('body').on('click', '#save_paid', function(){
                      $('#status').val('1');
                      $('#myForm').submit();
                  });
                  $('body').on('click', '#save_calculate', function(){
                      $('#status').val('2');
                      Swal.fire({
                        title: 'ยืนยันคำนวณ !',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                                $('#cal_type1,#cal_type2,#cal_type3,#law_config_reward_id').prop('disabled' ,false );
                              $('#myForm').submit();
                            }
                        })
                    
                  });
                  $('body').on('click', '#not_complete_calculate', function(){
                       Swal.fire({
                                    position: 'center',
                                    icon: 'warning',
                                    title: 'ข้อมูลสัดส่วนเงินคำนวณยังไม่ครบ 100 %',
                                    width:600,
                                    showConfirmButton: true
                                });
                    
                  });

                  
                  $('body').on('click', '#save_draft_calculate', function(){
                      $('#status').val('99');
                      $('#cal_type1,#cal_type2,#cal_type3,#law_config_reward_id').prop('disabled' ,false );
                      $('#calculate').find('ul.parsley-errors-list').remove();
                      $('#calculate').find('.input_required').prop('required', false);
                      $('#myForm').submit();
                  });
                  $('body').on('click', '#save_print', function(){
                      $('#status').val('3');
                      $('#myForm').submit();
                  });
                  $('#myForm').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                    })  .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กำลังบันทึก กรุณารอสักครู่..."
                                    });
                            return true;
                    });

                
        });



        function ShowTabActive(element){
         
         var href = $(element).attr('href');
         var $curr = $(".process-model  a[href='" + href + "']").parent();

             $('.process-model li').removeClass();

             $curr.addClass("active");
             $curr.prevAll().addClass("visited");
 
             $(href).addClass('active');
             var id = $(element).attr('id');
             $('#step_froms').val(id);
             if(id == '1'){
                $('#div_save_paid').show();
                $('#div_save_calculate,#div_save_print').hide();
                $('#calculate').find('.input_required').prop('required', false);
             }else if(id == '2'){
                $('#div_save_calculate').show();
                $('#div_save_paid,#div_save_print').hide();
                $('#calculate').find('.input_required').prop('required', true);
             }else if(id == '3'){
                $('#div_save_print').show();
                $('#div_save_paid,#div_save_calculate').hide();
                $('#calculate').find('.input_required').prop('required', false);
             } 
     }

        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


    </script>
@endpush
