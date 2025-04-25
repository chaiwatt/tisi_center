@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/morrisjs/morris.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่าธรรมการขอรับใบรับรอง</h3>
 


    <div class="clearfix"></div>
    <hr>

{!! Form::open(['url' => '/bcertify/setting-fee/store',    'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
<div class="row">
   <div class="col-md-12">
          <table class="table  table-bordered color-bordered-table info-bordered-table" id="myTable">
                    <thead>
                              <tr>
                                        <th width="1%" class="text-center">ลำดับ</th>
                                        <th width="10%" class="text-center">ประเภทค่าธรรมเนียม</th>
                                        <th width="10%" class="text-center">ชื่ออ้างอิง</th>
                                        <th width="10%"  class="text-center">หน่วยตรวจ (IB)</th>
                                        <th width="10%" class="text-center">หน่วยรับรอง (CB)</th>     
                                        <th width="10%" class="text-center">ห้องปฏิบัติการ (LAB)</th>       
                                        <th width="10%" class="text-center">วันที่มีผลใช้งาน</th>             
                              </tr>
                    </thead>
                    <tbody>
                        @if (count($setting_fee) > 0)
                            @foreach ($setting_fee as $key => $item )
                                <tr>
                                    <td   class="text-center">{!! $key+1  !!}</td>
                                    <td> {!! $item->fee_name ?? null  !!}</td>
                                    <td>
                                        <span style="padding: 35px; 0px">&nbsp;</span>
                                         {!! Form::text('fee_ref['.$item->id.']', $item->fee_ref ?? null, ['class' => 'form-control',  'required' => true]); !!}
                                         {!! Form::hidden('fee_id['.$item->id.']',$item->id ) !!}
                                    </td>
                                    <td  valign="bottom">   
                                        <div class="checkbox checkbox-success">
                                              <input id="checkbox_fee_ib{{$key}}" type="checkbox"  class="checkbox_fee"  {{ !empty($item->fee_ib) ? 'checked' : '' }}   >
                                             <label for="checkbox_fee_ib{{$key}}"></label>
                                        </div>
                                        {!! Form::text('fee_ib['.$item->id.']', !empty($item->fee_ib) ?   number_format($item->fee_ib,2) : null, ['class' => 'form-control input_fee input_number text-right']) !!}
                                    </td>
                                    <td>   
                                        <div class="checkbox checkbox-success">
                                              <input id="checkbox_fee_cb{{$key}}" type="checkbox"  class="checkbox_fee"  {{ !empty($item->fee_cb) ? 'checked' : '' }}   >
                                             <label for="checkbox_fee_cb{{$key}}"></label>
                                        </div>
                                        {!! Form::text('fee_cb['.$item->id.']', !empty($item->fee_cb) ?   number_format($item->fee_cb,2) : null, ['class' => 'form-control input_fee input_number  text-right']) !!}
                                    </td>
                                    <td>   
                                        <div class="checkbox checkbox-success">
                                              <input id="checkbox_fee_lab{{$key}}" type="checkbox"  class="checkbox_fee"  {{ !empty($item->fee_lab) ? 'checked' : '' }}   >
                                             <label for="checkbox_fee_lab{{$key}}"></label>
                                        </div>
                                        {!! Form::text('fee_lab['.$item->id.']',  !empty($item->fee_lab) ?   number_format($item->fee_lab,2) : null , ['class' => 'form-control input_fee  input_number text-right']) !!}
                                    </td>
                                    <td valign="bottom" >
                                       <span style="padding: 35px; 0px">&nbsp;</span>
                                        <div class="input-group">
                                            {!! Form::text('fee_start['.$item->id.']', !empty($item->fee_start) ?   HP::revertDate($item->fee_start,true) : null   , ['class' => 'form-control mydatepicker  text-right']) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                
                            @endforeach
                        @endif
                    </tbody>
          </table>
  </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('bcertify-setting-fee'))
            <a class="btn btn-default" href="{{url('/home')}}">
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
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
       <!--Morris JavaScript -->
   <script src="{{asset('plugins/components/raphael/raphael-min.js')}}"></script>
   <script src="{{asset('plugins/components/morrisjs/morris.js')}}"></script>
 

    <script>
        $(document).ready(function () {

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


            checkbox_fee();
            IsInputNumber();
 
            $(".checkbox_fee").click(function() {
                checkbox_fee();
   
            });

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

        });
 
        function checkbox_fee(value) {
            $('.checkbox_fee').each(function( index, data) {
                    var $this  = $(data);
                     var row  =  $($this).parent().parent();
                   if($this.is(':checked') == true){
                        $(row).find('.input_fee').prop('required', true);
                        $(row).find('.input_fee').prop('disabled', false);
                        $(row).find('.input_fee').prop('disabled', false);
                   }else{
                        $(row).find('.input_fee').prop('required', false);
                        $(row).find('.input_fee').prop('disabled', true);
                   }
                  
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
    </script> 
@endpush
