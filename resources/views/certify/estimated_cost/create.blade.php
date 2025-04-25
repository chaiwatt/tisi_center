@extends('layouts.master')

@push('style')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .a_custom {
            text-decoration: underline;
        }

        .center-block {
            display: block;
            margin-right: auto;
            margin-left: auto;
        }

   
                .modal-xl {
            width: 80%; /* กำหนดความกว้างตามที่คุณต้องการ */
            max-width: none; /* ยกเลิกค่า max-width เริ่มต้น */
        }
        .modal-xxl {
            width: 90%; /* กำหนดความกว้างตามที่คุณต้องการ */
            max-width: none; /* ยกเลิกค่า max-width เริ่มต้น */
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid" id="app_estimated_cost">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการประมาณการค่าใช้จ่าย</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ route('estimated_cost.index', ['app' => $app ? $app->id : '']) }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
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

                    {!! Form::open(['url' => route('estimated_cost.store', ['app' => $app ? $app->id : '']), 'method'=>'put','id'=>'cost_form', 'class' => 'form-horizontal', 'files' => true]) !!}

                    <div class="row form-group">
                        <div class="col-md-12">
                         <div class="white-box" style="border: 2px solid #e5ebec;">
                             <legend><h3>การประมาณการค่าใช้จ่าย</h3></legend>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-5">
                                            <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
                                            <div class="col-md-8">
                                                {!! Form::select('app_no',
                                                //  App\Models\Certify\Applicant\CertiLab::where('status',9)->orderby('id','desc')->pluck('app_no', 'app_no'),
                                                $app_no ?? [],
                                                 null,
                                               ['class' => 'form-control',
                                               'ref'=>'app_no',
                                               'id'=>'app_no',
                                               'placeholder'=>'-เลือกคำขอ-'])
                                                !!}

                                            </div>
                                            </div>
                                            <div class="col-md-7">
                                            <label class="col-md-3 text-right">หน่วยงาน : </label>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" v-model="appDepart" name="department" disabled id="department">
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
                                                <button type="button" class="btn btn-success btn-sm" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
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
                                                                {!! Form::select('detail[desc][]',
                                                                 App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                                                 $item->desc ?? null,
                                                                 ['class' => 'form-control select2 desc',
                                                                  'required'=>true,
                                                                 'placeholder'=>'- เลือกรายละเอียดการประมาณการค่าใช้จ่าย -']); !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('detail[cost][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('detail[nod][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
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
                            <div class="white-box" style="border: 2px solid #e5ebec;">
                            <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>
                                        <div id="attach-box">
                                            <div class="form-group other_attach_item">
                                                <div class="col-md-4  text-light">
                                                    <label for="" class="col-md-12 label_attach text-light  control-label"> <span class="text-danger">*</span> กรุณาแนบไฟล์ Scope</label>
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
                                                            <input type="file" name="attachs[]" class="check_max_size_file attachs "  required>
                                                        </span>
                                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" >
                                                    <button type="button" class="btn btn-sm btn-success attach-add"  id="attach-add">
                                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                    </button>
                                                    <span class="text-danger attach-span">(.pdf)</span>
                                                    <div class="button_remove"></div>
                                                </div>
                                            </div>
                                        </div>

                               <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                            <div id="status_btn"></div>
                        <div class="col-md-18 text-center">
                            <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
                            <label for="vehicle1">ส่ง e-mail แจ้งผู้ประกอบการเพื่อยืนยันข้อมูล</label>
                            <br>
                            <button v-if="isShowDraft"  type="submit" class="btn btn-success" name="draft" value="0"   ><i class="fa fa-file-o"></i> ฉบับร่าง</button>
                            <button class="btn btn-primary" type="submit" id="form-save" name="draft" value="1" onclick="submit_form('1');return false"><i class="fa fa-paper-plane"></i> บันทึก</button>
                            <a href="{{ url('certify/estimated_cost') }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
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
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script>

        $(document).ready(function () {
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

        });

           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
            });

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

             $('.cost_rate,.amount_date').change(function(event) {
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
            ResetTableNumber();
            TotalValue();
            IsInputNumber();
            IsNumber();
            cost_rate();
            data_list_disabled();

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
            // ลบ คอมมา
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
                     
                        var formatMoney = function(inum){  // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน 
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
                                        thisVal = parseFloat(thisVal);  // แปลงเป็นรูปแบบตัวเลข                    
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
          function data_list_disabled(){
                $('.desc').children('option').prop('disabled',false);
                $('.desc').each(function(index , item){
                    var data_list = $(item).val();
                    $('.desc').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }
        function submit_form(status) {
            Swal.fire({
                    title: 'ยืนยันการทำรายการ !',
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
    </script>
    <script>



        $(document).ready(function () {

            //เมื่อเลือกรัฐ
            $('#app_no').change(function(){

                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('certify/estimated_cost/data/app_no') !!}" + "/" + $(this).val()
                    }).done(function( object ) {
                        $('#department').val(object.app);
                    });
                }else{
                        $('#department').val('');
                }
            });


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

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

        });

        function alertSuccess() {
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: 'แต่งตั้งคณะผู้ตรวจประเมินเรียบร้อย',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        }

        function alertError() {
            $.toast({
                heading: 'Wrong!',
                position: 'top-center',
                text: 'พบข้อผิดพลาด',
                icon: 'error',
                hideAfter: 3000,
                stack: 6
            });
        }

    </script>
    <script>
        $(document).ready(function () {
               check_max_size_file();
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
                // ShowHideRemoveBtn94();
                AttachFile();
                check_max_size_file();
            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().parent().remove();
                // ShowHideRemoveBtn94();
             });

             AttachFile();
            // ShowHideRemoveBtn94();
        });
        function  AttachFile(){
            $('.attachs').change( function () {
                    var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf',
                        '',
                        'info'
                        )
                    this.value = '';
                    return false;
                    }
                });
        }
        // function ShowHideRemoveBtn94() { //ซ่อน-แสดงปุ่มลบ

        //     if ($('.other_attach_item').length > 1) {
        //         $('.attach-remove').show();
        //     } else {
        //         $('.attach-remove').hide();
        //     }

        // }
    </script>
@endpush
