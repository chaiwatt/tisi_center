@extends('layouts.master')
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left"> Pay-In ครั้งที่ 2 </h3>
                    @can('view-'.str_slug('estimatedcostcb'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
               
 
  {!! Form::open(['url' => 'certificate/tracking-cb/update_pay_in2/'.$pay_in->id,
                'class' => 'form-horizontal', 
                'files' => true,
                'method' => 'POST',
                'id'=>"pay_in2_form"]) 
!!}
    
    <div class="form-group  {{ $errors->has('conditional_type') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('conditional_type', '<span class="text-danger">*</span>  เงื่อนไขการชำระเงิน :', ['class' => 'col-md-3  control-label'])) !!}
        <div class="col-md-9">
            <label>{!! Form::radio('conditional_type', '1',($pay_in->conditional_type == 1 || $pay_in->conditional == 1) ? true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บค่าธรรมเนียม &nbsp;&nbsp;</label>
            <label>{!! Form::radio('conditional_type', '2',($pay_in->conditional_type == 2 || $pay_in->conditional == 2) ? true : false  , ['class'=>'check check-readonly conditional_type', 'data-radio'=>'iradio_square-green']) !!} ยกเว้นค่าธรรมเนียม &nbsp;&nbsp;</label>
            <label>{!! Form::radio('conditional_type', '3', $pay_in->conditional_type == 3 ? true :  false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ </label>
        </div>
    </div>


 <div class="form-group {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'คำขอเลขที่'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 m-t-10">
      <label for=""> {!! $pay_in->reference_refno ?? null  !!} </label> 
    </div>
</div>

  <!-- Start เรียกเก็บค่าธรรมเนียม  -->
  <div class="form-group div-collect {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'วันที่แจ้งชำระ'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <div class="input-group"> <!-- mydatepicker -->
            {!! Form::text('report_date', 
                !empty($pay_in->report_date) ?  HP::revertDate($pay_in->report_date,true)  :  HP::revertDate(date('Y-m-d'),true) ,  
                ['class' => 'form-control  text-center','placeholder'=>'dd/mm/yyyy','readonly' => true])
            !!}
            <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
        {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

 <div class="form-group  div-collect {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'รายการ'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6 m-t-10">
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table table-bordered" >
                <thead>
                        <tr>
                            <th class="text-center text-white" width="2%">ลำดับ</th>
                            <th class="text-center text-white" width="60%">รายการ</th>
                            <th class="text-center text-white" width="38%">จำนวนเงิน</th>
                        </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="text-center">1.</td>
                            <td class="text-left">ค่าธรรมเนียมคำขอการใบรับรอง สก.</td>
                            <td class="text-right">1,000.00</td>
                        </tr>
                        <tr>
                            <td class="text-center">2.</td>
                            <td class="text-left">ค่าตรวจสอบคำขอ</td>
                            <td class="text-right">30,000.00</td>
                        </tr>
                        <tr>
                            <td class="text-center">3.</td>
                            <td class="text-left">ค่าธรรมเนียมใบรับรอง สก.</td>
                            <td class="text-right">5,000.00</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">รวม</td>
                            <td class="text-right">36,000.00</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (!is_null($pay_in->FileAttachPayInTwo1To))
<div class="form-group div-collect {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
    {!! HTML::decode(Form::label('other_attach', ' ใบแจ้งหนี้ค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInTwo1To->url.'/'.( !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename :  basename($pay_in->FileAttachPayInTwo1To->url)  ))}}" 
            title="{{  !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename : basename($pay_in->FileAttachPayInTwo1To->url) }}" target="_blank">
            {!! HP::FileExtension($pay_in->FileAttachPayInTwo1To->url)  ?? '' !!}
        </a> 
    </div>
</div>   
@endif
<!-- End เรียกเก็บค่าธรรมเนียม  -->

 
<!-- Start ยกเว้นค่าธรรมเนียม  -->
<div class="form-group div-except {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'ช่วงเวลาการยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3">
        <label class="control-label">    {{ !empty($pay_in->DateFeewaiver)  ? $pay_in->DateFeewaiver : (!empty($feewaiver->Datepay_in)  ? $feewaiver->Datepay_in : null)   }}</label>  
    </div>
</div>

@if (!is_null($pay_in->FileAttachPayInTwo1To))
    <div class="form-group div-except {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
        {!! HTML::decode(Form::label('other_attach', ' เอกสารยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInTwo1To->url.'/'.( !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename :  basename($pay_in->FileAttachPayInTwo1To->url)  ))}}" 
                title="{{  !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename : basename($pay_in->FileAttachPayInTwo1To->url) }}" target="_blank">
                {!! HP::FileExtension($pay_in->FileAttachPayInTwo1To->url)  ?? '' !!}
            </a> 
        </div>
    </div>   
@else
    @if(!empty($feewaiver->pay_in_file) && HP::checkFileStorage($feewaiver->pay_in_file)) 
    <div class="form-group div-except {{ $errors->has('report_date') ? 'has-error' : ''}}">
        {!! HTML::decode(Form::label('report_date', 'เอกสารยกเว้นค่าธรรมเนียม :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-4">
              <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver->pay_in_file).'/'.( !empty($feewaiver->pay_in_file_client_name) ? $feewaiver->pay_in_file_client_name :  basename($feewaiver->pay_in_file)  ))}}" target="_blank">
                {!! HP::FileExtension($feewaiver->pay_in_file)  ?? '' !!}
             </a>
        </div>
    </div>
    @endif

@endif
<!-- End ยกเว้นค่าธรรมเนียม  -->

<!-- Start ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->
<div class="form-group div-other_cases {{ $errors->has('report_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('report_date', '<span class="text-danger">*</span> หมายเหตุ :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        @if (!is_null($pay_in->detail))
        <p class="text-left">{{!empty($pay_in->detail) ? $pay_in->detail: null}} </p>
        @else
        {!! Form::textarea('detail', null, ['class' => 'form-control', 'rows'=>'3','id'=>'detail']); !!}
        @endif
      
    </div>
</div>
@if (isset($pay_in) && is_null($pay_in->conditional_type))
<div class="form-group div-other_cases {{ $errors->has('attach') ? 'has-error' : ''}}" id="div-attach">
    {!! HTML::decode(Form::label('attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
         @if (!is_null($pay_in->FileAttachPayInTwo1To))
            <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInTwo1To->url.'/'.( !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename :  basename($pay_in->FileAttachPayInTwo1To->url)  ))}}" 
                title="{{  !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename : basename($pay_in->FileAttachPayInTwo1To->url) }}" target="_blank">
                {!! HP::FileExtension($pay_in->FileAttachPayInTwo1To->url)  ?? '' !!}
            </a> 
         @else 
             <div class="fileinput fileinput-new input-group div_amount_file" data-provides="fileinput">
                 <div class="form-control" data-trigger="fileinput">
                     <i class="glyphicon glyphicon-file fileinput-exists"></i>
                     <span class="fileinput-filename"></span>
                 </div>
                 <span class="input-group-addon btn btn-default btn-file">
                     <span class="fileinput-new">เลือกไฟล์</span>
                     <span class="fileinput-exists">เปลี่ยน</span>
                     <input type="file" name="attach"  id="attach" class="check_max_size_file">
                 </span>
                 <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
             </div>
         @endif
    </div>
</div> 
@else
    @if (!is_null($pay_in->FileAttachPayInTwo1To))
    <div class="form-group div-other_cases {{ $errors->has('other_attach') ? 'has-error' : ''}}" id="div-attach">
        {!! HTML::decode(Form::label('other_attach', ' ไฟล์แนบ (ถ้ามี) :', ['class' => 'col-md-3 control-label'])) !!}
        <div class="col-md-7">
            <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInTwo1To->url.'/'.( !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename :  basename($pay_in->FileAttachPayInTwo1To->url)  ))}}" 
                title="{{  !empty($pay_in->FileAttachPayInTwo1To->filename) ? $pay_in->FileAttachPayInTwo1To->filename : basename($pay_in->FileAttachPayInTwo1To->url) }}" target="_blank">
                {!! HP::FileExtension($pay_in->FileAttachPayInTwo1To->url)  ?? '' !!}
            </a> 
        </div>
    </div>   
    @endif
@endif
<!-- End ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ  -->

@if (!is_null($pay_in->FileAttachPayInTwo2To))
<div class="form-group {{ $errors->has('list') ? 'has-error' : ''}}">
    {!! Form::label('list', 'หลักฐานค่าธรรมเนียม'.' :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-3 m-t-9">
            <a href="{{url('funtions/get-view/'.$pay_in->FileAttachPayInTwo2To->url.'/'.( !empty($pay_in->FileAttachPayInTwo2To->filename) ? $pay_in->FileAttachPayInTwo2To->filename :   basename($pay_in->FileAttachPayInTwo2To->url) ))}}" 
                title="{{  !empty($pay_in->FileAttachPayInTwo2To->filename) ? $pay_in->FileAttachPayInTwo2To->filename : basename($pay_in->FileAttachPayInTwo2To->url) }}" target="_blank">
                {!! HP::FileExtension($pay_in->FileAttachPayInTwo2To->url)  ?? '' !!}
            </a>
    </div>
</div>
@endif
@if(!is_null($pay_in) && $pay_in->state == 2)
    <div class="row">
        <div class="col-sm-3 text-right"> <b>ตรวจสอบการชำระ :</b></div>
        <div class="col-sm-6">
                <label><input type="radio" name="status_confirmed" value="1" {{ ($pay_in->status==1 || $pay_in->status==null) ? 'checked':'' }} 
                    class="check pay_in-readonly'" data-radio="iradio_square-green"> &nbsp;รับชำระเงินเรียบร้อยแล้ว &nbsp;
                </label>
                <label>
                    <input type="radio" name="status_confirmed" value="2" {{ $pay_in->status==2 ? 'checked':'' }}   
                    class="check pay_in-readonly" data-radio="iradio_square-red"  > &nbsp;ยังไม่ชำระเงิน &nbsp;
                </label>
        </div>
    </div>
    <div class="row show_status_pay_in">
        <div class="col-sm-3 text-right">หมายเหตุ : </div>
        <div class="col-sm-7">
                {!! Form::textarea('detail', null, ['class' => 'form-control detail_pay_in', 'rows'=>'3']); !!}
        </div>
    </div>
 @endif

    @if(!is_null($pay_in) && ($pay_in->state == 2 || is_null($pay_in->state)))
    <input type="hidden" name="previousUrl" id="previousUrl" value="{{   app('url')->previous() }}">
            <div class="row form-group">
                <div class="col-md-offset-4 col-md-4 m-t-15  text-center">
                    @if (!is_null($pay_in->conditional_type))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                    @else 
                        <button type="button" class="btn btn-primary"  id="save_pay_in">
                            <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                    @endif
                    <a class="btn btn-default" href="{{  app('url')->previous() }}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                </div>
            </div>
    @else 
            <a class="btn btn-lg btn-block  btn-default" href="{{  app('url')->previous() }}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
    @endif
{!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <!-- Data Table -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/function.js')}}"></script>
  <!-- เริ่ม แนบใบ Pay-in ครั้งที่ 2 -->
 <script type="text/javascript">
      jQuery(document).ready(function() {
        var feewaiver = '{{  !empty($feewaiver)  ? 1 : 2  }}';
            if (feewaiver == '2') {
                $('.check-readonly[value="2"]').prop('disabled', true); 
                $('.check-readonly[value="2"]').parent().removeClass('disabled');
                $('.check-readonly[value="2"]').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
            }
          var conditional_type = '{{  !empty($pay_in->conditional_type)  ? 1 : 2  }}';
          if (conditional_type == '1') {
            $('.check-readonly').prop('disabled', true); 
             $('.check-readonly').parent().removeClass('disabled');
             $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
          }

            conditional();
        $("input[name=conditional_type]").on("ifChanged",function(){
            conditional();
        });

                    var check = '{{  !empty($pay_in) &&  ($pay_in->status_confirmed == 1) ? 1 : null  }}';
                    if(check == 1){
                        $('.check_readonly_1').prop('disabled', true); 
                        $('.check_readonly_1').parent().removeClass('disabled');
                        $('.check_readonly_1').parent().css('margin-top', '8px').css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
                    }
        
                 $("input[name=status_confirmed]").on("ifChanged", function(event) {;
                    show_status_pay_in();
                  });
                  show_status_pay_in();
                function show_status_pay_in(){
                      var row = $("input[name=status_confirmed]:checked").val();
                      if(row != "1"){ 
                        $('.show_status_pay_in').show(200);
                        $('#detail').prop('required' ,true);
                      } else{
                        $('.show_status_pay_in').hide(400);
                        $('#detail').prop('required' ,false);
                      }
                  }


         $('#save_pay_in').click(function () { 
            var row =  $("input[name=conditional_type]:checked").val();
            if(row == '1'){ // เรียกเก็บค่าธรรมเนียม

                        $.ajax({
                            type:"get",
                            url:  "{{ url('/certificate/tracking-cb/check/pay_in') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                id:  "{{ $pay_in->id ?? null }}",
                                payin : '2'
                            },
                            success:function(data){
                                if(data.message === true){
                                  $('#pay_in2_form').submit();
                                }else{
                                    Swal.fire(data.status_error,'','warning');
                                }
                            }
                    });

            }else if(row == '2' || row == '3'){ // ยกเว้นค่าธรรมเนียม และ ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#pay_in2_form').submit();
             }  
          });
          
          
    $('#pay_in2_form').parsley().on('field:validated', function() {
         var ok = $('.parsley-error').length === 0;
         $('.bs-callout-info').toggleClass('hidden', !ok);
         $('.bs-callout-warning').toggleClass('hidden', ok);
     }) .on('form:submit', function() {
             $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังบันทึก กรุณารอสักครู่..."
             });
            return true; // Don't submit form for this demo
     });
         });

         

     function conditional(){
           var status = $("input[name=conditional_type]:checked").val();
           if(status == '1'){ // เรียกเก็บค่าธรรมเนียม
                $('#remark').prop('required' ,false);  
                $('.div-collect').show();  
                $('.div-except').hide();    
                $('.div-other_cases').hide();   
           }else if(status == '2'){ // ยกเว้นค่าธรรมเนียม
                $('#remark').prop('required' ,false);  
                $('.div-collect').hide();  
                $('.div-except').show();   
                $('.div-other_cases').hide();   
           }else if(status == '3'){  //  ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                $('#remark').prop('required' ,true);  
                $('.div-collect').hide();  
                $('.div-except').hide();   
                $('.div-other_cases').show(); 
           }
      }
   
    function RemoveCommas(nstr){
        return nstr.replace(/[^\d\.\-\ ]/g, '');
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


  </script>
  <!-- จบ แนบใบ Pay-in ครั้งที่ 2 -->

@endpush
