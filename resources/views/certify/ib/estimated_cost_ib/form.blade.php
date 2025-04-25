@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

@if($cost->check_status != 1   ||  $cost->status_scope  != 1 )
<div class="row form-group">
    <div class="col-md-12">
     <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>การประมาณค่าใช้จ่าย</h3></legend>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-5">
            <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
            <div class="col-md-8">
            @if(isset($app_no))
                {!! Form::select('app_certi_ib_id',
                  $app_no,
                   null,
               ['class' => 'form-control',
               'required'=>true,
               'id'=>'app_no',
               'placeholder'=>'-เลือกคำขอ-'])
                !!}
            @else
            <input type="text" class="form-control" value="{{ $cost->CertiIBCostTo->app_no ?? null }}"   disabled >
            @endif
            </div>
            </div>
            <div class="col-md-7">
            <label class="col-md-3 text-right">หน่วยงาน : </label>
            <div class="col-md-7">
                <input type="text" class="form-control" value="{{ $cost->CertiIBCostTo->EsurvTrader->name ?? null }}" name="department" disabled id="department">
            </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-8"> </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-success btn-sm {{isset($cost)  && ($cost->vehicle == 1) ? 'hide' : ''}}" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
            </div>
            <div class="col-sm-12 m-t-15">
                <table class="table color-bordered-table primary-bordered-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="2%">#</th>
                            <th class="text-center" width="38%">รายละเอียด</th>
                            <th class="text-center" width="20%">จำนวนเงิน</th>
                            <th class="text-center" width="10%">จำนวนวัน</th>
                            <th class="text-center" width="20%">รวม (บาท)</th>
                            <th class="text-center" width="5%">ลบ</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @foreach($cost_item as $item)
                        <tr>
                            <td  class="text-center">
                                1
                            </td>
                            <td>
                                {!! Form::select('detail[detail][]',
                                  App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                 $item->detail ?? null,
                                 ['class' => 'form-control select2 desc',
                                  'required'=>true,
                                 'placeholder'=>'- เลือกรายละเอียดประมาณค่าใช้จ่าย -']); !!}
                            </td>
                            <td>
                                {!! Form::text('detail[amount][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                            </td>
                            <td>
                                {!! Form::text('detail[amount_date][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                            </td>
                            <td>
                                {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row" {{isset($cost)  && ($cost->vehicle == 1) ? 'disabled' : ''}}>
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <footer>
                        <tr>
                            <td colspan="4" class="text-right">รวม</td>
                            <td>
                                {!! Form::text('costs_total',
                                    null,
                                    ['class' => 'form-control text-right costs_total',
                                        'id'=>'costs_total',
                                        'disabled'=>true
                                    ])
                                !!}
                            </td>
                            <td>
                                 บาท
                            </td>
                        </tr>
                    </footer>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

        </div>
    </div>
</div>


<div class="clearfix"></div>
<div class="row form-group">
    <div class="col-md-12">
        {{-- <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>
                    <div id="attach-box">
                        <div class="form-group other_attach_item">
                            <div class="col-md-4  text-light">
                                <label for="" class="col-md-12 label_attach text-light  control-label"> <span class="text-danger">*</span> ขอบข่าย </label>
                            </div>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="attachs[]" class="attachs check_max_size_file"  {{ (isset($cost) && $cost->FileAttachCost1->count() == 0) ? 'required' : '' }} >
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2" >
                                <button type="button" class="btn btn-sm btn-success attach-add {{isset($cost)  && ($cost->vehicle == 1) ? 'hide' : ''}}"  id="attach-add">
                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                </button>
                                <span class="text-danger attach-span">(.pdf)</span>
                                <div class="button_remove"></div>
                            </div>
                        </div>

                    @if(count($cost->FileAttachCost1) > 0)
                        @foreach($cost->FileAttachCost1 as $key => $item)
                        <div class="row form-group deleteFlie{{$item->id}}">
                            <div class="col-md-4  text-light">
                            </div>
                             <div class="col-md-6 text-light">
                                 <a href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file)  ))}}" target="_blank">
                                     {!! HP::FileExtension($item->file)  ?? '' !!}
                                     {{   !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file)}}
                                 </a>
                             </div>
                             <div class="col-md-1 text-left">
                             <button class="btn btn-danger btn-sm deleteFlie {{isset($cost)  && ($cost->vehicle == 1) ? 'hide' : ''}}" type="button" onclick="deleteFlie({{ $item->id }})">
                                     <i class="icon-close"></i>
                                 </button>
                             </div>
                         </div>
                        @endforeach
                     @endif

                    </div>

           <div class="clearfix"></div>
        </div> --}}
    </div>
</div>
@endif


@if(count($cost->CertiIbHistorys) > 0 && !is_null($cost->date) )
@include ('certify/ib/estimated_cost_ib.log')
@endif

@if(isset($cost)  &&  $cost->vehicle != 1)
<div class="form-group">
        <div id="status_btn"></div>
    <div class="col-md-18 text-center">
        <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
        <label for="vehicle1">ส่ง e-mail แจ้งผู้ประกอบการเพื่อยืนยันข้อมูล</label>
        <br>
        @if($cost->draft == 0)
           <button   type="submit"
                    class="btn btn-success "
                    name="draft" value="0">
                     <i class="fa fa-file-o"></i> ฉบับร่าง
            </button>
        @endif
            <button class="btn btn-primary "
                    type="submit" id="form-save"
                    value="1"
                    onclick="submit_form('1');return false">
            <i class="fa fa-paper-plane"></i> บันทึก
           </button>

         <a href="{{ url('certify/estimated_cost-ib') }}"
            class="btn btn-default ">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    </div>
</div>
@else
<div class="clearfix"></div>
   <a  href="{{ url("certify/estimated_cost-ib") }}"  class="btn btn-default btn-lg btn-block">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
@endif

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
  <script>

    $(document).ready(function () {
        ResetTableNumber();
        IsInputNumber();
        IsNumber();
        cost_rate();
        data_list_disabled();
        TotalValue();
        check_max_size_file();
        AttachFile();

        $('.check-readonly').prop('disabled', true);
        $('.check-readonly').parent().removeClass('disabled');
        $('.check-readonly').parent().css('margin-top', '8px');


            $('#app_no').change(function(){

                    if($(this).val()!=""){
                        $.ajax({
                            url: "{!! url('certify/estimated_cost-ib/app_no') !!}" + "/" + $(this).val()
                        }).done(function( object ) {
                            $('#department').val(object.app);
                        });
                    }else{
                            $('#department').val('');
                    }
              });



        //เพิ่มแถว
        $('#addCostInput').click(function(event) {
            var data_list = $('.desc').find('option[value!=""]:not(:selected):not(:disabled)').length;
                if(data_list == 0){
                    Swal.fire('หมอรายการรายละเอียดประมาณค่าใช้จ่าย !!')
                    return false;
            }
          //Clone
          $('#table-body').children('tr:first()').clone().appendTo('#table-body');
          //Clear value
            var row = $('#table-body').children('tr:last()');
            row.find('select.select2').val('');
            row.find('select.select2').prev().remove();
            row.find('select.select2').removeAttr('style');
            row.find('select.select2').select2();
            row.find('input[type="text"]').val('');
              ResetTableNumber();
              IsInputNumber();
              IsNumber();
              cost_rate();
              data_list_disabled();
        });

          //ลบแถว
          $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
          });


            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#attach-box');
                $('.other_attach_item:last').find('input').val('');
                $('.other_attach_item:last').find('a.fileinput-exists').click();
                $('.other_attach_item:last').find('a.view-attach').remove();
                $('.other_attach_item:last').find('.label_attach').remove();
                $('.other_attach_item:last').find('.attach-span').remove();
                $('.other_attach_item:last').find('button.attach-add').remove();
                $('.other_attach_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i>  </button>');

                AttachFile();
                check_max_size_file();
            });
            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().parent().remove();
             });

    });
    function ResetTableNumber(){
                var rows = $('#table-body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                });
     }
     function  TotalValue() {
            var rows = $('#table-body').children(); //แถวทั้งหมด
            var total_all = 0.00;
            rows.each(function(index, el) {
                if($(el).children().find("input.number").val() != ''){
                    var number = parseFloat(RemoveCommas($(el).children().find("input.number").val()));
                    total_all  += number;
                }
            });
            $('#costs_total').val(addCommas(total_all.toFixed(2), 2));
           }
     function cost_rate() {
             $('.cost_rate,.amount_date').keyup(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();

                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });
         }
         function data_list_disabled(){
                $('.desc').children('option').prop('disabled',false);
                $('.desc').each(function(index , item){
                    var data_list = $(item).val();
                    $('.desc').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }

     function IsNumber() {
            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
            $(".amount_date").on("keypress",function(e){
               var eKey = e.which || e.keyCode;
              if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
             return false;
              }
          });
      }

      function IsInputNumber() {
                   // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                   String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                   };

                   var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน
                    var s_inum=new String(inum);
                    var num2=s_inum.split(".");
                    var n_inum="";
                    if(num2[0]!=undefined){
                   var l_inum=num2[0].length;
                   for(i=0;i<l_inum;i++){
                    if(parseInt(l_inum-i)%3==0){
                   if(i==0){
                    n_inum+=s_inum.charAt(i);
                   }else{
                    n_inum+=","+s_inum.charAt(i);
                   }
                    }else{
                   n_inum+=s_inum.charAt(i);
                    }
                   }
                    }else{
                   n_inum=inum;
                    }
                    if(num2[1]!=undefined){
                   n_inum+="."+num2[1];
                    }
                    return n_inum;
                   }
                   // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                   $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   });

                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ
                   $(".input_number").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                               if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                           thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }else{ // ถ้าไม่มีคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                   });
         }
         function  AttachFile(){
            $('.attachs').change( function () {
                    var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf',
                        '',
                        'info'
                        )
                    // this.value = '';
                    $(this).parent().parent().find('.fileinput-exists').click();
                    return false;
                    }
                });
        }
        function submit_form(status) {
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
                            $('#status_btn').html('<input type="text" name="draft" value="' + status + '" hidden>');
                            $('#cost_form').submit();
                        }
                    })
            }
       function  deleteFlie(id){

            Swal.fire({
                    icon: 'error',
                    title: 'ยื่นยันการลบไฟล์แนบ !',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {

                           $.ajax({
                                url: "{!! url('/certify/estimated_cost-ib/delete_file') !!}"  + "/" + id
                            }).done(function( object ) {
                                console.log(object);
                                if(object == 'true'){
                                        $('#attach-box').find('.deleteFlie'+id).remove();
                                     let row =  $('#attach-box').find('.deleteFlie').length;
                                     if(row == 0){
                                        $('#attach-box').find('.attachs').prop('required',true);
                                     }else{
                                        $('#attach-box').find('.attachs').prop('required',false);
                                     }
                                }else{
                                    Swal.fire('ข้อมูลผิดพลาด');
                                }
                            });

                        }
                    })
        }


</script>
<script type="text/javascript">

    $(document).ready(function() {
      //Validate
         $('#cost_form').parsley().on('field:validated', function() {
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
