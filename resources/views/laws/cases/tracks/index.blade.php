@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .has-dropdown {
            position: relative;
        }
        .show_status {
            border: 2px solid #00BFFF;
            padding: 0px 7px;
            -webkit-padding: 0px 7px;
            -moz-padding: 0px 7px;
            border-radius: 25px;
            -webkit-border-radius: 25px;
            -moz-border-radius: 25px;
            width: auto;
        }
        .circle {
            border-radius: 50%;
        }
        .not-allowed {
            cursor: not-allowed
        }
        .rounded-circle {
            border-radius: 50% !important;
        }
        .btn-light-info {
        background-color: #ccf5f8;
        color: #00CFDD !important;
        }
        .btn-light-info:hover, .btn-light-info.hover {
        background-color: #00CFDD;
        color: #fff !important;
        }
        table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
            opacity: 1;
        }
    </style>
@endpush

@section('content')

    @php
        $option_condition  = [ '1' => 'เลขที่อ้างอิง', '2' => 'เลขคดี', '3' => 'ผู้รับผิดชอบ', '4' => 'นิติกร', '5' => 'ผู้ประกอบการ/TAXID', '6' => 'เลขที่ใบอนุญาต'];
        $option_tis        = App\Models\Basic\Tis::select(DB::Raw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) AS title, tb3_TisAutono'))->pluck('title', 'tb3_TisAutono');
        $option_license_no = App\Models\Basic\TisiLicense::orderbyRaw('CONVERT(tbl_licenseNo USING tis620)')->pluck('tbl_licenseNo', 'tbl_licenseNo');
        // $sql = "(CASE 
        //             WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
        //             ELSE  department.depart_nameShort
        //             END) AS sub_depart_shortname";

        // $option_sub_department = App\Models\Basic\SubDepartment::leftjoin((new App\Models\Besurv\Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
        //                                                         ->select( DB::raw($sql), 'sub_id' )
        //                                                         ->orderbyRaw('CONVERT(sub_depart_shortname USING tis620)')
        //                                                         ->pluck('sub_depart_shortname', 'sub_id');

        $option_sub_department  = App\Models\Basic\SubDepartment::where('did',06)->orderbyRaw('CONVERT(sub_depart_shortname USING tis620)')->pluck('sub_depart_shortname', 'sub_id');
        $option_status     = App\Models\Law\Cases\LawCasesForm::status_list();
        $arr_unset         = [ 0,13, 14, 15, 99 ];
        foreach ($arr_unset as $value) {
            unset(  $option_status[$value] );
        }
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">ติดตามงานคดี</h3>

                    <div class="pull-right"> </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-5 form-group">
                                    <div class="form-group col-md-6">
                                        {!! Form::select('filter_condition_search', $option_condition, null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขที่อ้างอิง, ผู้ประกอบการ/TAXID, เลขที่ใบอนุญาต']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                            ล้างค่า
                                        </button>
                                    </div>
                                    <div class="form-group pull-left m-l-15">
                                        <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status', $option_status , null,  ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']) !!}
                                    </div>
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_tisi_no',  $option_tis, null,  ['class' => 'form-control','placeholder'=>'- เลือก มอก. -','id'=>'filter_tisi_no']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_tbl_license_no', 'ค้นหาเลขที่ใบอนุญาต', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_tbl_license_no', $option_license_no  , null,  ['class' => 'form-control', 'placeholder'=>'-เลือกเลขที่ใบอนุญาต-','id'=>'filter_tbl_license_no']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_sub_department', 'กลุ่มงานผู้รับผิดชอบ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_sub_department', $option_sub_department  , null,  ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานผู้รับผิดชอบ-','id'=>'filter_sub_department']) !!}
                                           </div>
                                        </div>
                                         <div class="form-group col-md-4">
                                            {!! Form::label('filter_close_status', 'สถานะปิดงาน', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_close_status', ['-1'=>'แจ้งปิดงาน','1'=>'ปิดงาน'], null, ['class' => 'form-control', 'id'=>'filter_close_status', 'placeholder'=>'-ทั้งหมด-']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_status_pay', 'สถานะการชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_status_pay',  ['2'=>'ชำระแล้ว','1'=>'ยังไม่ชำระ'],  null, ['class' => 'form-control ','id' => 'filter_status_pay', 'placeholder'=>'- เลือกสถานะการชำระ -']); !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_created_at', 'ช่วงมูลค่าผลิตภัณฑ์', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="input-daterange input-group ">
                                                    {!! Form::text('filter_start_money', null, ['id' => 'filter_start_money','class' => 'form-control input_amount', 'required' => true]) !!}
                                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                    {!! Form::text('filter_end_money', null, ['id' => 'filter_end_money','class' => 'form-control input_amount', 'required' => true]) !!}
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="row">
                        <div class="col-md-12"> 
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="8%">เลขคดี</th>
                                        <th class="text-center" width="10%">ผู้ประกอบการ</th>
                                        <th class="text-center" width="10%">ผลิตภัณฑ์</th>
                                        <th class="text-center" width="10%">มาตราความผิด</th>
                                        <th class="text-center" width="10%">มูลค่าผลิตภัณฑ์</th>
                                        <th class="text-center" width="10%">ค่าปรับ</th>
                                        <th class="text-center" width="10%">สถานะ</th> 
                                        <th class="text-center" width="10%">ผู้รับผิดชอบ/กลุ่ม</th>
                                        <th class="text-center" width="10%">นิติกร</th>
                                        <th class="text-center" width="8%">ติดตาม</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot style="background-color: rgb(245, 245, 245)">
                                    <tr>
    
                                    </tr>
                                 </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
    
    <script>
        var table = '';
        $(document).ready(function () {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/cases/tracks/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tisi_no = $('#filter_tisi_no').val();
                        d.filter_tbl_license_no = $('#filter_tbl_license_no').val(); 
                        d.filter_sub_department = $('#filter_sub_department').val();
                        d.filter_close_status = $('#filter_close_status').val();
                        d.filter_status_pay = $('#filter_status_pay').val(); 
                        d.filter_start_money = $('#filter_start_money').val();
                        d.filter_end_money = $('#filter_end_money').val();
                        
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'tb3_TisThainame', name: 'tb3_TisThainame' },
                    { data: 'offense_section_number', name: 'offense_section_number' },
                    { data: 'total', name: 'total' }, 
                    { data: 'payin', name: 'payin' }, 
                    { data: 'status', name: 'status' }, 
                    { data: 'assign_name', name: 'assign_name' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1, -2, -3, -4]},
                    { className: "text-right  text-top", targets:[-5,-6] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var amount6  =  api.column( 6, {page:'current'} ).data();
                    var amount  = 0;
                        if(amount6.length > 0)  {
                            $.each(amount6, function( index, data ) {
                               var row =   amount6[index];
                               if(checkNone(row)){
                                  row = parseFloat(RemoveCommas(evitamos_script(row)));
                                  if(parseInt(row)){
                                    amount += row;
                                  }
                       
                               }
                            
                            });                   
                        }
                    html += '<td class="text-right" colspan="5"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+   addCommas(amount.toFixed(2), 2)  +'</b></td>'+
                         '<td class="text-top text-right" colspan="4"></td>'
                    );
                }
            });


            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

 ;
            $('#filter_start_money,#filter_end_money').change(function(event) {
                filter_money();
            });
            $('#filter_start_money,#filter_end_money').blur(function(event) {
                filter_money();
            });
            IsInputNumber();
        });

        function filter_money(){
            var start  =  $('#filter_start_money').val();
            var end    =  $('#filter_end_money').val();
            if(checkNone(start) && checkNone(end)){
                start  =    parseFloat(RemoveCommas(start));
                end    =    parseFloat(RemoveCommas(end));
                 if(start > end){
                    $('#filter_end_money').val(addCommas(start.toFixed(2), 2));
                 }  
                 
            }else if(checkNone(end)){
                  end    =    parseFloat(RemoveCommas(end));
                $('#filter_start_money').val(addCommas(end.toFixed(2), 2));
            }
         }
    
         function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function evitamos_script($texto) {
             const strippedString = $texto.replace(/(<([^>]+)>)/gi, "");
             return strippedString;
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
                $(".input_amount").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                return false;
                }
                }); 
                
                // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
                $(".input_amount").on("change",function(){
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

 