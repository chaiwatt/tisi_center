
   <!-- Modal -->
   <div class="modal fade" id="exampleModalPay" tabindex="-1" role="dialog" aria-labelledby="exampleModalPayLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalPayLabel">แนบใบ Pay-in ครั้งที่ 2
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
         </h4>
        </div>
        
           @if (!is_null($costcerti) && (is_null($costcerti->invoice) || $costcerti->invoice == ''))     
        {!! Form::open(['url' => 'certify/check_certificate/update/status/cost_certificate', 'class' => 'form-horizontal', 'files' => true]) !!}
        <div class="modal-body">
                @php 
                  $amount  =  !empty($costcerti->amount) ? $costcerti->amount :  '0';
                  $amount_fee  =  !empty($costcerti->amount_fee) ?$costcerti->amount_fee :  '0';
                  $sum =   ((string)$amount +   (string)$amount_fee);
                @endphp
        <div id="costcerti_amount">
              <div class="row">
                <div class="col-sm-12">
                   <div class="form-group {{ $errors->has('amount_file') ? 'has-error' : ''}}">
                       {!! HTML::decode(Form::label('amount_file', '<span class="text-danger">*</span> ค่าธรรมเนียมคำขอ'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                                 
                        <div class="col-md-4 "> 
                            <input type="text" name="amount" class="form-control text-right  costcerti_amount css_input"  required
                             value="{{ !empty($amount) ? number_format($amount,2) :  '0.00' }}" >
                       </div>
                       <div class="col-md-5 text-left">
                        @if(!is_null($costcerti) && $costcerti->amount_file != '' && !is_null($costcerti->amount_file))
                          <a href="{{ url('certify/check/files/'. $costcerti->amount_file) }}"> 
                             {!! HP::FileExtension($costcerti->amount_file)  ?? '' !!}
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
                                    <input type="file" name="amount_file" required >
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif
                       </div>
                   </div>
                 </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                   <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                       {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> ค่าธรรมเนียมใบรับรอง'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                       <div class="col-md-4 "> 
                          <input type="text" name="amount_fee" class="form-control text-right  costcerti_amount css_input"  required
                             value="{{ !empty($amount_fee) ? number_format($amount_fee,2) : '0.00' }}" >
                       </div>
                       <div class="col-md-5 text-left">
                        @if(!is_null($costcerti) && $costcerti->attach != '' && !is_null($costcerti->attach))
                        <a href="{{ url('certify/check/files/'. $costcerti->attach) }}"> 
                            {!! HP::FileExtension($costcerti->attach)  ?? '' !!}
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
                                    <input type="file" name="attach" required>
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif
                       
                       </div>
                   </div>
                 </div>
              </div>
         </div>

              <div class="row">
                <div class="col-sm-12">
                   <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                       {!! HTML::decode(Form::label('attach', 'รวม (บาท)'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                       <div class="col-md-4 "> 
                          <input type="text"  class="form-control text-right " id="sum_amount"  disabled
                             value="{{ !empty($sum) ? number_format($sum,2) : '0.00' }}" >
                       </div>
                   </div>
                 </div>
              </div>
 
              <div class="row">
                <div class="col-sm-12">
                   <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                       {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> วันที่แจ้งชำระ'.':', ['class' => 'col-md-3 control-label text-right'])) !!}
                       <div class="col-md-4 "> 
                        <div class="input-group">
                        <input type="text"  value="{{  !empty($costcerti->notification_date) ? HP::revertDate($costcerti->notification_date,true) :  null }}"   name="notification_date"
                            class="form-control {{  (!is_null($costcerti) && $costcerti->status_confirmed != 1) ?  'datepicker' : '' }}   text-right" id="notification_date">
                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                        </div>
                       </div>
                   </div>
                 </div>
              </div>
        </div>
        @if(!is_null($costcerti) && $costcerti->attach != '' && !is_null($costcerti->attach))
        @else 
            <div class="modal-footer"> 
                <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึก</button>
            </div>
        @endif
        {!! Form::close() !!}
        @else 
        {!! Form::open(['url' => 'certify/check_certificate/update/status/receive_certificate', 'class' => 'form-horizontal', 'files' => true]) !!}
                <div class="modal-body">
                        @php 
                            $amount  =  !empty($costcerti->amount) ? $costcerti->amount :  '0';
                            $amount_fee  =  !empty($costcerti->amount_fee) ?$costcerti->amount_fee :  '0';
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
                                    {{  !empty($costcerti->notification_date) ? HP::DateThai($costcerti->notification_date) :  null }}
                                </p>
                            </div>
                        </div>
                       @if(!is_null($costcerti) && $costcerti->amount_file != '')
                         <div class="row">
                            <div class="col-sm-5 text-right"> <b>ใบแจ้งหนี้ค่าธรรมเนียมใบคำขอ :</b></div>
                            <div class="col-sm-6">
                                <p>  
                                    <a href="{{ url('certify/check/files/'. $costcerti->amount_file) }}"> 
                                         {!! HP::FileExtension($costcerti->amount_file)  ?? '' !!}
                                    </a>
                                 </p>
                            </div>
                          </div>
                        @endif
                        @if(!is_null($costcerti) && $costcerti->attach != '')
                            <div class="row">
                                <div class="col-sm-5 text-right"> <b>ใบแจ้งหนี้ค่าธรรมเนียมใบรับรอง :</b></div>
                                <div class="col-sm-6">
                                    <p>  
                                        <a href="{{ url('certify/check/files/'. $costcerti->attach) }}"> 
                                            {!! HP::FileExtension($costcerti->attach)  ?? '' !!}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        <h4 class="modal-title" >หลักฐานการชำระเงิน </h4>
                        <hr>

                        @if(!is_null($costcerti) && $costcerti->invoice != '')
                        <div class="row">
                            <div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าใบคำขอ :</b></div>
                            <div class="col-sm-6">
                                <p>  
                                    <a href="{{ url('certify/check/files/'. $costcerti->invoice) }}">
                                        {!! HP::FileExtension($costcerti->invoice)  ?? '' !!}
                                     </a>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(!is_null($costcerti) && $costcerti->attach_certification != '')
                        <div class="row">
                            <div class="col-sm-5 text-right"> <b>หลักฐานการชำระเงินค่าใบรับรอง :</b></div>
                            <div class="col-sm-6">
                                <p>  
                                    <a href="{{ url('certify/check/files/'. $costcerti->attach_certification) }}">
                                        {!! HP::FileExtension($costcerti->attach_certification)  ?? '' !!}
                                     </a>
                                </p>
                            </div>
                        </div>
                        @endif
                        <input type="hidden" name="costcerti_id" value="{{ $costcerti->id ?? null}}">
                        <div class="row">
                            <div class="col-sm-5 text-right"> <b>ตรวจสอบการชำระ :</b></div>
                            <div class="col-sm-6">
                                    <label><input type="radio" name="status_confirmed" value="1" {{ ($costcerti->status_confirmed==1 || $costcerti->status_confirmed==null) ? 'checked':'' }}   class="check costcerti-readonly'" data-radio="iradio_square-green"> &nbsp;รับชำระเงินเรียบร้อยแล้ว &nbsp;</label>
                                    <label><input type="radio" name="status_confirmed" value="2" {{ $costcerti->status_confirmed==2 ? 'checked':'' }}    class="check costcerti-readonly" data-radio="iradio_square-red"  > &nbsp;ยังไม่ชำระเงิน &nbsp;</label>
                            </div>
                        </div>
                         <div class="row show_status_costcerti">
                            <div class="col-sm-5 text-right">หมายเหตุ : </div>
                              <div class="col-sm-7">
                                    {!! Form::textarea('detail', null, ['class' => 'form-control detail_costcerti', 'rows'=>'3']); !!}
                            </div>
                        </div>

                </div>
                @if(!is_null($costcerti) && $costcerti->status_confirmed != 1)
                <div class="modal-footer"> 
                    <input type="hidden" name="id" value="{{ $cc->id ?? null}}">
                    <input type="hidden" name="app_certi_lab_id" value="{{ $cc->app_certi_lab_id ?? null}}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                @endif
          {!! Form::close() !!}
        @endif
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
    jQuery(document).ready(function() {

        $('.datepicker').datepicker({
            language:'th-th',
            format:'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
        sum_amount();
       
        var notification = '{{  !empty($costcerti->notification_date) && !is_null($costcerti->notification_date) ? 1 : null  }}';
         if(notification == 1){
                $('.costcerti_amount').prop('disabled', true);
                $('.costcerti_amount_fee').prop('disabled', true);
                $('#notification_date').prop('disabled', true);
          }


        var costcerti = '{{  !empty($costcerti) &&  ($costcerti->status_confirmed == 1) ? 1 : null  }}';
            if(costcerti == 1){
                $('.costcerti-readonly').prop('disabled', true);//checkbox ความคิดเห็น
                $('.costcerti-readonly').parent().removeClass('disabled');
                $('.costcerti-readonly').parent().css('margin-top', '8px');//checkbox ความคิดเห็น
            }
     });
     function sum_amount() {
             $('.costcerti_amount').keyup(function(event) {
                    var rows = $('#costcerti_amount').children(); //แถวทั้งหมด
                    var total_all = 0.00;
                    rows.each(function(index, el) {
                        if($(el).children().find("input.costcerti_amount").val() != ''){
                            var number = parseFloat(RemoveCommas($(el).children().find("input.costcerti_amount").val()));
                            total_all  += number;
                        }
                    });
                    $('#sum_amount').val(addCommas(total_all.toFixed(2), 2));
             });
         }

           function RemoveCommas(str) {
                  var res = str.replace(/[^\d\.\-\ ]/g, '');
                   return   res;
             }
             
            function  addCommas(nStr, decimal){
                    var tmp='';
                    var zero = '0';

                    nStr += '';
                    x = nStr.split('.');

                    if((x.length-1) >= 1){//ถ้ามีทศนิยม
                        if(x[1].length > decimal){//ถ้าหากหลักของทศนิยมเกินที่กำหนดไว้ ตัดให้เหลือเท่าที่กำหนดไว้
                            x[1] = x[1].substring(0, decimal);
                        }else if(x[1].length < decimal){//ถ้าหากหลักของทศนิยมน้อยกว่าที่กำหนดไว้ เพิ่ม 0
                            x[1] = x[1] + zero.repeat(decimal-x[1].length);
                        }
                        tmp = '.'+x[1];
                    }else{//ถ้าไม่มีทศนิยม
                        if(parseInt(decimal)>0){//ถ้ามีการกำหนดให้มี ทศนิยม
                                tmp = '.'+zero.repeat(decimal);
                            }
                    }
                    x1 = x[0];
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1+tmp;
          }

    </script>
@endpush