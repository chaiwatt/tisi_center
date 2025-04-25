@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<style>
.pointer {cursor: pointer;}
</style>
@endpush


@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ความเห็นการกำหนดมาตรฐานการตรวจสอบและรับรอง</h3>

                    <div class="pull-right">

                     @can('assign_work-'.str_slug('standardsoffers'))
                            <button type="button" class="btn btn-warning" id="button_assign">มอบหมาย</button>

                       <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                       <div class="modal fade" id="modal_assign">
                        <div class="modal-dialog modal-xl"  role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" > มอบหมายผู้รับผิดชอบคำขอ</h4>
                                </div>
                                <div class="modal-body">
                                        <div class="white-box">
                                            <div class="row form-group">
                                                <div class="col-md-12">
                                                        <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                                            {!! Form::label('assign', 'เจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                                            <div class="col-md-6">
                                                                {!! Form::select('',
                                                                  $select_users,
                                                                  null,
                                                                 ['class' => 'form-control',
                                                                 'id'=>"assign",
                                                                 'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-']); !!}
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_items">&nbsp; เลือก</button>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row " id="div_assign">
                                                <div class="col-md-12">
                                                        <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-8">
                                                                <div class="table-responsive">
                                                                    <table class="table color-bordered-table info-bordered-table">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center" width="2%">#</th>
                                                                            <th class="text-center" width="88%">เจ้าหน้าที่ตรวจสอบคำขอ</th>
                                                                            <th class="text-center" width="10%">ลบ</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="table_tbody">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button"class="btn btn-primary"   id="save_assign"><i class="icon-check"></i> บันทึก</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                {!! __('ยกเลิก') !!}
                                            </button>
                                        </div>
                              
                                </div>
                            </div>
                        </div>
                    </div>
                     @endcan  

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                      <div class="col-lg-7 col-md-3 col-sm-3">
                        <div class="form-group {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-2 control-label text-right ']) !!}
                            <div class="col-md-10">
                                {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control', 'placeholder' => 'ค้นหา ชื่อเรื่อง,ชื่อเรื่องEN,ขอบข่าย,จุดประสงค์และเหตุผล,ผู้มีส่วนได้เสียที่เกี่ยวข้อง,ชื่อ-สกุลผู้เสนอ']) !!}
                            </div>
                         </div>
                      </div><!-- /.col-lg-4 -->
                      <div class="col-lg-3 col-md-2 col-sm-2">
                        {!! Form::select('filter_state',
                          HP::StateEstandardOffers(), 
                         null, 
                         ['class' => 'form-control', 
                         'id'=>'filter_state',
                         'placeholder' => '-- เลือกสถานะ --']); !!}
                    </div><!-- /.col-lg-1 -->
                      <div class="col-lg-2 col-md-2 col-sm-2">
                          <div class="form-group  pull-left">
                              <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                          </div>
                          <div class="form-group  pull-left m-l-15">
                              <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                  ล้าง
                              </button>
                          </div>
                      </div><!-- /.col-lg-1 -->
                  </div><!-- /.row -->

                  <div class="row">
                    <div class="col-lg-7">
                      <div class="form-group {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                          {!! Form::label('filter_start_date', 'วันที่เสนอ'.' :', ['class' => 'col-md-2 control-label text-right ']) !!}
                          <div class="col-md-6 form-group">
                            <div class="input-daterange input-group date-range">
                              {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                              <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                              {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                          </div>
                          </div>
                       </div>
                    </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->

    
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                            <tr>
                                <th width="1%" class="text-center">#</th>
                                <th  width="1%" ><input type="checkbox" id="checkall"></th>
                                <th width="10%" class="text-center">รหัสความเห็น</th>
                                <th width="10%" class="text-center">วันที่เสนอ</th>
                                <th  width="15%" class="text-center">ชื่อเรื่อง</th>
                                <th width="15%" class="text-center">จุดประสงค์และเหตุผล</th>
                                <th width="15%" class="text-center">ผู้เสนอความเห็น</th>
                                {{-- <th  width="15%" class="text-center">ประเภทมาตรฐาน</th> --}}
                                <th width="10%" class="text-center">สถานะ</th>
                                <th width="10%" class="text-center">ผู้ตรวจสอบคำขอ</th>
                                <th width="10%" class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
           

                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
   <!-- input calendar thai -->
   <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
   <!-- thai extension -->
   <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
   <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>
        $(document).ready(function () {


            //ช่วงวันที่
            $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
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
            
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/bcertify/standards-offers/data_list') !!}',
                    data: function (d) {
                      
                        d.filter_search = $('#filter_search').val();
                        d.filter_state = $('#filter_state').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'refno', name: 'refno' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'title', name: 'title' },
                    { data: 'objectve', name: 'objectve' },
                    { data: 'name', name: 'name' },
                    // { data: 'standard_type', name: 'standard_type' },
                    { data: 'state', name: 'state' },
                    { data: 'asigns', name: 'asigns' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1] }
                ],
                fnDrawCallback: function() {
                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);
                    $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
                }

            });



            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
              $('#filter_search').val('');
              $('#filter_start_date').val('');
              $('#filter_end_date').val('');
              $('#filter_state').val('').select2();
              table.draw();
            // window.location.assign("{{url('/bcertify/standards-offers')}}");
           });
 
           $('#checkall').change(function (event) {

        if ($(this).prop('checked')) {//เลือกทั้งหมด
            $('#myTable').find('input.item_checkbox').prop('checked', true);
        } else {
            $('#myTable').find('input.item_checkbox').prop('checked', false);
        }

        });

            $( "#button_assign" ).click(function() {
                if(table.$('.item_checkbox:checked').length > 0){
                    $('#modal_assign').modal('show');
                }else{
                    Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'โปรดเลือกอย่างน้อย 1 รายการ',
                            showConfirmButton: false,
                            timer: 1500
                            });
                }
                  
            });


            $('#add_items').on('click',function () {
                 let row =$('#assign').val();
                if(row != ''){
                    $('#div_assign').show();
                    $('#save_assign').prop('disabled',false);    
                    let assign = $('#assign').find('option[value="'+row+'"]').text();
                    let table_tbody = $('#table_tbody');
                        table_tbody.append('<tr>\n' +
                    '                    <td class="text-center">1</td>\n' +
                    '                    <td class="text-left">'+assign+'</td>\n' +
                    '                    <td class="text-center">' +
                    '                    <input type="hidden" name="assign[]"  class="assign"  value="'+ row+'">\n' +
                    '                    <button type="button" class="btn btn-danger btn-xs inTypeDelete" data-types="'+row+'" ><i class="fa fa-remove"></i></button></td>\n' +
                    '                </tr>');
                    $("#assign option[value=" + row + "]").prop('disabled', true); //  เปิดรายการ
                    ResetTableNumber();
                    $('#assign').val('').select2();
                }else{
                    Swal.fire('กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!');
                }

            });
             ResetTableNumber();
            $(document).on('click','.inTypeDelete',function () {
                let types = $(this).attr('data-types');
                $("#assign option[value=" + types + "]").prop('disabled', false); //  เปิดรายการ
                $(this).parent().parent().remove();
                ResetTableNumber();
            });

          //รีเซตเลขลำดับ
            function ResetTableNumber(){
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                if(rows.length==0){
                    $('#div_assign').hide()
                    $('#save_assign').prop('disabled',true);    
                }else{
                    $('#div_assign').show()
                    $('#save_assign').prop('disabled',false);    
                }
 
                rows.each(function(index, el) {
                    $(el).children().first().html(index+1);
                });
          }



                        //เลือกเปิด-ปิด
            $('#save_assign').on('click', function () {
                   
                var assigns = []; 
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                    rows.each(function(index, el) {
                        assigns.push($(el).find('.assign').val());
                    });

                    var arrRowId = [];
                    table.$('.item_checkbox:checked').each(function (index, rowId) {
                        arrRowId.push(rowId.value);
                    });

                    if (arrRowId.length > 0) {
                            $.ajax({
                                method: "POST",
                                url: "{{ url('/bcertify/standards-offers/save_assign') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id_publish": arrRowId,
                                    "assigns": assigns
                                },
                                success : function (msg){
                                        if(msg.message ==true){
                                            Swal.fire({
                                                    position: 'center',
                                                    icon: 'success',
                                                    title: 'มอบหมายเรียบร้อยแล้ว',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                             });
                                        }else{
                                            Swal.fire({
                                                    position: 'center',
                                                    icon: 'error',
                                                    title: 'เกิดข้อผิดพลาดมอบหมาย',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                            });
                                        }
                                        
                                        table.draw(); 
                                        $('#table_tbody').html('');
                                        $("#assign option").prop('disabled', false); //  เปิดรายการ
                                        $('#assign').val('').select2();
                                        $('#checkall').prop('checked',false );
                                        $('#modal_assign').modal('hide');
                                     
                                    
                                }
                            });
                       

                    }else {
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'โปรดเลือกอย่างน้อย 1 รายการ',
                            showConfirmButton: false,
                            timer: 1500
                            });
                    }
        });

 



        });

        
        function confirm_delete() {
                    return confirm("ยืนยันการลบข้อมูล?");
     }

      
        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }

    </script>

@endpush
