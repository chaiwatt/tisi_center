@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
        
    <style>
 
        .not-allowed {
           cursor: not-allowed
       }
    
       .btn-light-info {
           background-color: #ccf5f8;
           color: #00CFDD !important;
       }
       .btn-light-info:hover, .btn-light-info.hover {
           background-color: #00CFDD;
           color: #fff !important;
       }
    
       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
               padding: 10px 10px;
               vertical-align: middle;
       }
   
   
       </style>
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อกลุ่มที่กำหนด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'ชื่อกลุ่มที่กำหนด', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
 
<div class="form-group required {{ $errors->has('arrest_id') ? 'has-error' : ''}}">
    {!! Form::label('arrest_id', 'มีการจับกุม', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('arrest_id',
         App\Models\Law\Basic\LawArrest::Where('state', 1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
         null, 
         ['class' => 'form-control ',
         'placeholder'=>'- เลือกกรณีมี/ไม่มีการจับกุม -',
          'required' => true]) !!}
        {!! $errors->first('arrest_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('person') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
            <div class="checkbox checkbox-success">
                    <input id="operation_id1" name="operation_id[]" type="checkbox" value="1"  {!! isset($reward->operations) && in_array('1',$reward->operations) ? 'checked' : '' !!}    >
                    <label for="operation_id1"> ทุกกรณี </label>
            </div>
            <div class="checkbox checkbox-success">
                    <input id="operation_id2" name="operation_id[]"   type="checkbox"  value="2"   {!! isset($reward->operations) && in_array('2',$reward->operations) ? 'checked' : '' !!}    >
                    <label for="operation_id2"> เปรียบเทียบปรับ </label>
            </div>
                <div class="checkbox checkbox-success">
                    <input id="operation_id3" name="operation_id[]"   type="checkbox"  value="3"  {!! isset($reward->operations) && in_array('3',$reward->operations) ? 'checked' : '' !!}    >
                    <label for="operation_id3"> ส่งดำเนินคดี </label>
            </div>
    </div>
</div>

<div class="form-group required {{ $errors->has('unit_type') ? 'has-error' : ''}}">
    {!! Form::label('unit_type', 'สัดส่วนคิดเป็น', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('unit_type',
         ['1'=>'ร้อยละ (%)','2'=>'จำนวนเงิน'],
         null, 
         ['class' => 'form-control ',
         'placeholder'=>'- เลือกสัดส่วนคิดเป็น -',
          'required' => true]) !!}
        {!! $errors->first('unit_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>



<div class="form-group   {{ $errors->has('table_reward') ? 'has-error' : ''}}">
    <div class="col-md-2"> </div>
    <div class="col-md-9">
        <table class="table table-striped table-bordered table-sm"  >
            <thead>
            <tr>
                <th class="text-center" width="2%">ลำดับ</th>
                <th class="text-center" width="50%">กลุ่มผู้สิทธิ์ได้รับเงินรางวัล</th>
                <th class="text-center" width="33%" id="show_unit_type">ร้อยละ (%)</th>
                <th class="text-center" width="15%">จัดการ</th>
            </tr>
            </thead>
            <tbody id="table_tbody_reward">
                @if (count($reward_subs) > 0)
                    @foreach ($reward_subs as $sub)
                        <tr>
                            <td class="text-center text-top">
        
                            </td>
                            <td class="text-top">
                                    {!! Form::select('sub[reward_group_id][]',
                                            App\Models\Law\Basic\LawRewardGroup::Where('state', 1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                            $sub->reward_group_id ?? null,
                                            ['class' => 'form-control  select2 reward_group_id',
                                            'placeholder'=>'- เลือกกลุ่มผู้มีสิทธิ์ได้รับเงิน -',
                                            'required' => true]) 
                                    !!}
                            </td>
                            <td class="text-top">
                                {!! Form::text('sub[amount][]', !empty($sub->amount) ?  number_format($sub->amount,2) : null, ['class' => 'form-control text-right  amount','id'=>'amount', 'required' => true]) !!}
                                <input type="hidden" name="sub[id][]"  class="deducted_id" value="{{ $sub->id }}">
                            </td>
                            <td class="text-center  text-top">
                                <button type="button" class=" btn btn-danger btn-outline manage  btn-sm remove-row">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
           
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-top">
                        {!! Form::text('total_amount', null, ['class' => 'form-control text-right','id'=>'total_amount', 'readonly'=>'true']) !!}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="form-group">
  <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
    @can('view-'.str_slug('law-reward-reward'))
        <a class="btn btn-default" href="{{url('/law/reward/reward')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    @endcan
  </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    
    <script>

        $(document).ready(function() {

            total_amount();

            $('#unit_type').change(function(){
                let show_unit = $(this).val();
                if(show_unit=='1'){
                    $('#show_unit_type').text('ร้อยละ (%)');
                }else{
                    $('#show_unit_type').text('จำนวนเงิน');
                }
            });

            $('#unit_type').change();

             //เพิ่มแถว
            $('body').on('click', '.add-row', function(){

                var data_list = $('.reward_group_id').find('option[value!=""]:not(:selected):not(:disabled)').length;
                 if(data_list == 0){
                    Swal.fire('หมดรายการกลุ่มผู้มีสิทธิ์ได้รับเงินรางวัล !!')
                    return false;
                }
                 $(this).removeClass('add-row').addClass('remove-row');
                 $(this).removeClass('btn-success').addClass('btn-danger');
                 $(this).parent().find('i').removeClass('fa-plus').addClass('fa-close');

                 //Clone
                $('#table_tbody_reward').children('tr:first()').clone().appendTo('#table_tbody_reward');
                 //Clear value
                var row = $('#table_tbody_reward').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"], input[type="hidden"]').val('');
                    // row.find('.manage').removeClass('remove-row').addClass('add-row');
                    // row.find('.manage').removeClass('btn-danger').addClass('btn-success');
                    // row.find('.manage > i').removeClass('fa-close').addClass('fa-plus');
                    row.find('ul.parsley-errors-list').remove();
                    row.find('.parsley-success').remove();
                 ResetTableNumber();
                 data_list_disabled();
                 IsInputNumber() ;
            });
            //ลบแถว
           $('body').on('click', '.remove-row', function(){
                 $(this).parent().parent().remove();
                 ResetTableNumber();
                 data_list_disabled();
            });

            // กลุ่มผู้สิทธิ์ได้รับเงินรางวัล
          $('body').on('change', '.reward_group_id', function(){
                   data_list_disabled();
          });

            ResetTableNumber() ;
            IsInputNumber() ;
            data_list_disabled();

        });

        
        function ResetTableNumber(){
                var rows = $('#table_tbody_reward').children(); //แถวทั้งหมด
                    rows.each(function(index, el) {
                        //เลขรัน
                        $(el).children().first().html(index+1);
                    });
                var row = $('#table_tbody_reward').children('tr:last()');
                    row.find('.manage').removeClass('remove-row').addClass('add-row');
                    row.find('.manage').removeClass('btn-danger').addClass('btn-success');
                    row.find('.manage > i').removeClass('fa-close').addClass('fa-plus');
             

         }
         function data_list_disabled(){
               $('.reward_group_id').children('option').prop('disabled',false);
                $('.reward_group_id').each(function(index , item){
                    var data_list = $(item).val();
                    $('.reward_group_id').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }
 


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
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
                   $(".amount").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   }); 
                   
                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
                   $(".amount").on("change",function(){
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
                        total_amount();
                   });

                 
         }

        function total_amount(){
              var rows = $('#table_tbody_reward').children(); //แถวทั้งหมด
                 let cal_price_all = 0;
                    rows.each(function(index, el) {
                       
                    let cal_price = 0;
                    cal_price += $(el).find('.amount').val();
                    cal_price_all += parseFloat(cal_price);
                    });

                    $.isNumeric(addCommas(cal_price_all.toFixed(2)))?
                    $('#total_amount').val(addCommas(cal_price_all.toFixed(2))):
                    $('#total_amount').val('0.00');
              
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

        function IsNumber() {
            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".amount").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 
        }

        function RemoveCommas(str) {
            var res = str.replace(/[^\d\.\-\ ]/g, '');
            return   res;
        }

    </script>
@endpush
