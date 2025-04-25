
   <!-- Modal -->
   <div class="modal fade" id="exampleModalPay" tabindex="-1" role="dialog" aria-labelledby="exampleModalPayLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalPayLabel">แนบใบ Pay-in ครั้งที่ 2
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
         </h4>
        </div>

@if(is_null($payin2->degree) || (!is_null($payin2) && $payin2->degree == 1))    
@php 
$amount  =  !empty($payin2->amount) ? $payin2->amount :  '0';
$amount_fee  =  !empty($payin2->amount_fee) ?$payin2->amount_fee :  '0';
$sum =   ((string)$amount +   (string)$amount_fee);
@endphp       
 {!! Form::open(['url' => 'certify/check_certificate-cb/create/pay-in2', 'class' => 'form-horizontal pay_in2_form', 'files' => true]) !!}
<div class="modal-body">
    <div id="payin2_amount">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('amount_file') ? 'has-error' : ''}}">
                     {!! HTML::decode(Form::label('amount_file', '<span class="text-danger">*</span> ค่าธรรมเนียมคำขอ'.':', ['class' => 'col-md-3 control-label text-right'])) !!}         
                    <div class="col-md-4 "> 
                         <input type="text" name="amount" class="form-control text-right  payin2_amount input_number"  required
                             value="{{ !empty($payin2->amount) ? number_format($payin2->amount,2) : null }}" >
                    </div>
                    <div class="col-md-5 text-left">
                          @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo1To))
                            <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo1To->file.'/'.( !empty($payin2->FileAttachPayInTwo1To->file_client_name) ? $payin2->FileAttachPayInTwo1To->file_client_name :   basename($payin2->FileAttachPayInTwo1To->file) ))}}" target="_blank">
                               {!! HP::FileExtension($payin2->FileAttachPayInTwo1To->file)  ?? '' !!}
                           </a>
                          @else 
                              <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                  <div class="form-control" data-trigger="fileinput">
                                      <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                      <span class="fileinput-filename"></span>
                                  </div>
                                  <span class="input-group-addon btn btn-default btn-file">
                                      <span class="fileinput-new">เลือกไฟล์</span>
                                      <span class="fileinput-exists">เปลี่ยน</span>
                                      <input type="file" name="amount_file" required  class="check_max_size_file">
                                  </span>
                                  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                              </div>
                          @endif
                    </div>
                 </div>
            </div>
         </div>
        <div class="row ">
            <div class="col-sm-12">
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                 {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> ค่าธรรมเนียมใบรับรอง'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                <div class="col-md-4 "> 
                    <input type="text" name="amount_fee" class="form-control text-right  payin2_amount input_number"  required
                    value="{{ !empty($payin2->amount_fee) ? number_format($payin2->amount_fee,2) : null }}" >
                </div>
                 <div class="col-md-5 text-left">
                         @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo2To))
                         <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo2To->file.'/'.( !empty($payin2->FileAttachPayInTwo2To->file_client_name) ? $payin2->FileAttachPayInTwo2To->file_client_name :   basename($payin2->FileAttachPayInTwo2To->file) ))}}" target="_blank">
                              {!! HP::FileExtension($payin2->FileAttachPayInTwo2To->file)  ?? '' !!}
                           </a>
                         @else 
                              <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                  <div class="form-control" data-trigger="fileinput">
                                      <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                      <span class="fileinput-filename"></span>
                                  </div>
                                  <span class="input-group-addon btn btn-default btn-file">
                                      <span class="fileinput-new">เลือกไฟล์</span>
                                      <span class="fileinput-exists">เปลี่ยน</span>
                                      <input type="file" name="attach" required class="check_max_size_file">
                                  </span>
                                  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                              </div>
                        @endif
                         
                </div>
                </div>
             </div>
         </div>
              <div class="row ">
              <div class="col-sm-12 form-group">
               <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('attach', 'รวม (บาท)'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                   <div class="col-md-4 "> 
                      <input type="text"  class="form-control text-right " id="sum_amount"  disabled
                        value="{{ !empty($sum) ? number_format($sum,2) : null }}" >
                   </div>
               </div>
             </div>
 
            <div class="col-sm-12  form-group">
               <div class="{{ $errors->has('attach') ? 'has-error' : ''}}">
                   {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> วันที่แจ้งชำระ'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                   <div class="col-md-4 "> 
                    <div class="input-group">
                    <input type="text"  value="{{  !empty($payin2->report_date) ? HP::revertDate($payin2->report_date,true) : null}}"   name="report_date"
                        class="form-control mydatepicker   text-right" id="report_date" placeholder="dd/mm/yyyy">  
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                   </div>
               </div>
             </div>
          </div>
    </div>
 </div>
<div class="modal-footer submit_remove"> 
    <input type="hidden" name="app_certi_cb_id" value="{{ $certi_cb->id ?? null}}">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
    <button type="submit" class="btn btn-primary" onclick="submit_form_pay_in2();return false">บันทึก</button>
</div>
{!! Form::close() !!}

@else 
{!! Form::open(['url' => 'certify/check_certificate-cb/update/pay-in2/'.@$payin2->id, 'class' => 'form-horizontal pay_in2_form', 'files' => true]) !!}
<div class="modal-body">
        @php 
            $amount  =  !empty($payin2->amount) ? $payin2->amount :  '0';
            $amount_fee  =  !empty($payin2->amount_fee) ?$payin2->amount_fee :  '0';
            $sum =   ((string)$amount +   (string)$amount_fee);
        @endphp
        <div class="row">
            <div class="col-sm-5 text-right"> <b>จำนวนเงิน :</b></div>
            <div class="col-sm-6">
                <p>  
                    {{ !empty($sum) ? number_format($sum,2) : '0.00' }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5 text-right"> <b>วันที่แจ้งชำระ :</b></div>
            <div class="col-sm-6">
                <p>  
                    {{  !empty($payin2->report_date) ? HP::DateThai($payin2->report_date) :  null }}
                </p>
            </div>
        </div>

       @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo1To))
         <div class="row">
            <div class="col-sm-5 text-right"> <b>ใบแจ้งหนี้ค่าธรรมเนียมใบคำขอ :</b></div>
            <div class="col-sm-6">
                <p>  
                    <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo1To->file.'/'.( !empty($payin2->FileAttachPayInTwo1To->file_client_name) ? $payin2->FileAttachPayInTwo1To->file_client_name :   basename($payin2->FileAttachPayInTwo1To->file) ))}}" target="_blank">
                        {!! HP::FileExtension($payin2->FileAttachPayInTwo1To->file)  ?? '' !!}
                    </a>
                 </p>
            </div>
          </div>
        @endif
         @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo2To))
            <div class="row">
                <div class="col-sm-5 text-right"> <b>ใบแจ้งหนี้ค่าธรรมเนียมใบรับรอง :</b></div>
                <div class="col-sm-6">
                    <p>  
                        <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo2To->file.'/'.( !empty($payin2->FileAttachPayInTwo2To->file_client_name) ? $payin2->FileAttachPayInTwo2To->file_client_name :   basename($payin2->FileAttachPayInTwo2To->file) ))}}" target="_blank">
                            {!! HP::FileExtension($payin2->FileAttachPayInTwo2To->file)  ?? '' !!}
                        </a>
                    </p>
                </div>
            </div>
        @endif

        <h4 class="modal-title" >หลักฐานการชำระเงิน </h4>
        <hr>

        @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo3To))
        <div class="row">
            <div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าใบคำขอ :</b></div>
            <div class="col-sm-6">
                <p>  
                    <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo3To->file.'/'.( !empty($payin2->FileAttachPayInTwo3To->file_client_name) ? $payin2->FileAttachPayInTwo3To->file_client_name :   basename($payin2->FileAttachPayInTwo3To->file) ))}}" target="_blank">
                        {!! HP::FileExtension($payin2->FileAttachPayInTwo3To->file)  ?? '' !!}
                    </a>
                </p>
            </div>
        </div>
        @endif

        @if(!is_null($payin2)  && !is_null($payin2->FileAttachPayInTwo4To))
        <div class="row">
            <div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าใบรับรอง :</b></div>
            <div class="col-sm-6">
                <p>  
                    <a href="{{url('certify/check/file_cb_client/'.$payin2->FileAttachPayInTwo4To->file.'/'.( !empty($payin2->FileAttachPayInTwo4To->file_client_name) ? $payin2->FileAttachPayInTwo4To->file_client_name :   basename($payin2->FileAttachPayInTwo4To->file) ))}}" target="_blank">
                        {!! HP::FileExtension($payin2->FileAttachPayInTwo4To->file)  ?? '' !!}
                    </a>
                </p>
            </div>
        </div>
        @endif
        <input type="hidden" name="payin2_id" value="{{ $payin2->id ?? null}}">
        <div class="row">
            <div class="col-sm-5 text-right"> <b>ตรวจสอบการชำระ :</b></div>
            <div class="col-sm-6">
                    <label><input type="radio" name="status_confirmed" value="1" {{ ($payin2->status==1 || $payin2->status==null) ? 'checked':'' }} 
                          class="check payin2-readonly'" data-radio="iradio_square-green"> &nbsp;รับชำระเงินเรียบร้อยแล้ว &nbsp;
                    </label>
                    <label>
                          <input type="radio" name="status_confirmed" value="2" {{ $payin2->status==2 ? 'checked':'' }}   
                           class="check payin2-readonly" data-radio="iradio_square-red"  > &nbsp;ยังไม่ชำระเงิน &nbsp;
                    </label>
            </div>
        </div>
         <div class="row show_status_payin2">
            <div class="col-sm-5 text-right">หมายเหตุ : </div>
              <div class="col-sm-7">
                    {!! Form::textarea('detail', null, ['class' => 'form-control detail_payin2', 'rows'=>'3']); !!}
            </div>
        </div>

</div>
@if(!is_null($payin2) && $payin2->status != 1)
<div class="modal-footer "> 
    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
    <button type="submit" class="btn btn-primary" onclick="submit_form_pay_in2();return false">บันทึก</button>
</div>
@endif
{!! Form::close() !!}




@endif



        </div>
    </div>
</div>

@push('js')
<script src="{{asset('js/function.js')}}"></script>
    <script type="text/javascript">
    jQuery(document).ready(function() {
        sum_amount();

        var degree = '{{  !empty($payin2->degree) && !is_null($payin2->degree) ? 1 : null  }}';
         if(degree == 1){
               $('.submit_remove').remove();
                $('.payin2_amount').prop('disabled', true);
                $('#report_date').prop('disabled', true);
          }
     });
     function sum_amount() {
             $('.payin2_amount').keyup(function(event) {
                    var rows = $('#payin2_amount').children(); 
                    var total_all = 0.00;
                    rows.each(function(index, el) {
                        if(checkNone($(el).find("input.payin2_amount").val())){
                          
                            var number = parseFloat(RemoveCommas( $(el).find("input.payin2_amount").val() ) );
                            total_all  += number;
                        }
                    });
                $('#sum_amount').val(addCommas(total_all.toFixed(2), 2));
             });
         }

           function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
             }
 
             function submit_form_pay_in2() {
            Swal.fire({  
                    title: 'ยืนยันทำรายการ !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            $('.pay_in2_form').submit();
                        }
                    })
           }
    </script>
      <script type="text/javascript">
        $(document).ready(function() {
            check_max_size_file();
          //Validate
             $('.pay_in2_form').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
              })
              .on('form:submit', function() {
                  // Text
                $.LoadingOverlay("show", {
                     image       : "",
                     text        : "กำลังบันทึก กรุณารอสักครู่..."
               });
                return true; // Don't submit form for this demo
              });
        });
    </script>  
@endpush