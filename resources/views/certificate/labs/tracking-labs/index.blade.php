@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
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
                    <h3 class="box-title pull-left">ระบบตรวจติดตามใบรับรองระบบงานห้องปฏิบัติการ (LAB)</h3>

                    <div class="pull-right">

                      @if(isset($select_users) && count($select_users) > 0) 
                      @can('edit-'.str_slug('trackinglabs'))

                        @can('follow_up_before-'.str_slug('trackinglabs'))
                            <button type="button" class="btn btn-success"  id="button_check"> ตรวจติดตามก่อนกำหนด  </button>
                        @endcan
                        @can('receive_inspection-'.str_slug('trackinglabs'))
                            <button type="button" class="btn btn-info"  id="button_receiver"> รับเรื่องตรวจติดตาม  </button>
                        @endcan
                        @can('assign_work-'.str_slug('trackinglabs'))
                            <button type="button" class="btn btn-primary"  id="button_checker"> มอบหมาย  </button>
                        @endcan
                      <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                      <div class="modal fade" id="exampleModal">
                          <div class="modal-dialog modal-xl" role="document">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                                      </button>
                                      <h4 class="modal-title" id="exampleModalLabel1">ระบบตรวจติดตามใบรับรองระบบงานห้องปฏิบัติการ (LAB)</h4>
                                  </div>
                                  <div class="modal-body">
                                      <form id="form_assign"  method="post" >
                                          {{ csrf_field() }}
                                          <div class="white-box">
                                              <div class="row form-group">
                                                  <div class="col-md-12">
                                                          <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                                              {!! Form::label('checker', 'เลือกผู้ที่ต้องการมอบหมายงาน', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                                              <div class="col-md-6">
                                                                  {!! Form::select('', 
                                                                    $select_users, 
                                                                    null, 
                                                                   ['class' => 'form-control',
                                                                   'id'=>"checker",
                                                                   'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-']); !!}
                                                              </div>
                                                              <div class="col-md-2">
                                                                  <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_items">&nbsp; เลือก</button>
                                                              </div>
                                                          </div>
                                                  </div>
                                              </div>
                                              <div class="row " id="div_checker">
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
                                          <div class="text-center submit_form">
                                              <button type="button"class="btn btn-primary"   id="submit_form"><i class="icon-check"></i> บันทึก</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                  {!! __('ยกเลิก') !!}
                                              </button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                      @endcan
                  @endif

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row ">
                      <div class="col-md-5 form-group">
                        <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-4 control-label text-right ']) !!}
                            <div class="col-md-8">
                                {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                            </div>
                         </div>
                      </div><!-- /.col-md-6 -->
                      <div class="col-md-2">
                              {!! Form::select('filter_certificate_no',
                                   App\CertificateExport::groupBy('certificate_no')->pluck('certificate_no','certificate_no'), 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_certificate_no',
                                'placeholder' => '- เลือกการรับรอง -']); !!}
                      </div><!-- /.col-md-2 -->
                       <div class="col-md-3">
                              {!! Form::select('filter_status_id',
                                   App\Models\Certificate\TrackingStatus::pluck('title','id'), 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_status_id',
                                'placeholder' => '- เลือกสถานะ -']); !!}
                      </div><!-- /.col-md-2 -->
                      <div class="col-md-2">
                          <div class="  pull-left">
                            <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                        </div>
                        <div class="  pull-left m-l-15">
                            <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                ล้าง
                            </button>
                        </div>
                      </div><!-- /.col-md-2 -->
                  </div><!-- /.row -->

                  <div class="row ">
                      <div class="col-md-5 form-group ">
                        <div class=" {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                            {!! Form::label('filter_start_date', 'วันที่ครบกำหนด'.' :', ['class' => 'col-md-4 control-label text-right ']) !!}
                            <div class="col-md-8">
                              <div class="input-daterange input-group" id="date-range">
                                {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                              </div>
                            </div>
                        </div>
                      </div><!-- /.col-md-4  -->
                  </div><!-- /.row -->


    
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                            <tr>
                              <th width="1%" class="text-center">#</th>
                              <th  width="1%" ><input type="checkbox" id="checkall"></th> 
                              <th width="10%" class="text-center">เลขอ้างอิง</th>
                              <th  width="15%" class="text-center">เลขที่ใบรับรอง</th>
                              <th width="20%" class="text-center">ผู้รับใบรับรอง</th>
                              <th width="10%" class="text-center">ชื่อห้องปฏิบัติการ</th>
                              <th width="15%" class="text-center">เจ้าหน้าที่รับผิดชอบ</th>
                              <th width="15%" class="text-center">วันที่ครบกำหนด</th>
                              <th width="10%" class="text-center">สถานะ</th>
                              <th width="10%" class="text-center">เรียกดู</th>
                              <th width="10%" class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
           
 <!-- Modal เลข 3 -->
<div class="modal fade text-left" id="TakeAction" tabindex="-1" role="dialog" aria-labelledby="addBrand">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"> อยู่ระหว่างดำเนินการ
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>
<div class="modal-body">
    <legend><h3>คณะผู้ตรวจประเมิน </h3></legend>
   
    <div class="row">
        <div class="col-md-12">
         <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center text-white" width="2%">ลำดับ</th>
                    <th class="text-center text-white" width="25%">วันที่/เวลาบันทึก</th>
                    <th class="text-center text-white" width="40%">คณะผู้ตรวจประเมิน</th>
                    <th class="text-center text-white" width="33%">สถานะ</th>
                </tr>
            </thead>
             <tbody id="tbody-auditor">
             
            </tbody>
        </table>
    </div>
</div>
 
</div>
 
        </div>
    </div>
</div>






                </div>
            </div>
        </div>
    </div>
    @include('certificate/labs/tracking-labs/modal.check')
    @include('certificate/labs/tracking-labs/modal.receiver')
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
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script>
        $(document).ready(function () {
            //ช่วงวันที่
            jQuery('#date-range').datepicker({
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
                    url: '{!! url('/certificate/tracking-labs/data_list') !!}',
                    data: function (d) {
                      
                        d.filter_search = $('#filter_search').val();
                        d.filter_certificate_no = $('#filter_certificate_no').val();
                        d.filter_status_id = $('#filter_status_id').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'reference_refno', name: 'reference_refno' },
                    { data: 'certificate_no', name: 'certificate_no' }, 
                    { data: 'org_name', name: 'org_name' },
                    { data: 'lab_name', name: 'lab_name' },
                    { data: 'assign', name: 'assign' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'status', name: 'status' },
                    { data: 'certificate_newfile', name: 'certificate_newfile' },
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
              $('#filter_certificate_no').val('').select2();
              $('#filter_status_id').val('').select2();
              $('#filter_start_date').val('');
              $('#filter_end_date').val('');
              table.draw();
 
           });
           
 
            $(document).on('click', '.modal_status', function(){
                      var id = $(this).data('id');
                      var refno = $(this).data('refno');
                      $('#tbody-auditor').html('');
                      $('#TakeAction').modal('hide');
                           // Text
                        $.LoadingOverlay("show", {
                            image       : "",
                            text  : "กําลังดาวน์โหลด กรุณารอสักครู่..."
                        });
                    $.ajax({
                                type:"GET",
                                url:  "{{ url('/certificate/tracking-labs/modal_status_auditor') }}",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    id: id,
                                    refno: refno
                                },
                                success:function(data){
                                    if(data.message == true){
                                        $.each(data.datas, function( index, item ) {
                                            var html = '<tr>';
                                               html += '<td class="text-center">';
                                                html +=  (index +1);
                                               html += '</td>';
                                
                                               html += '<td>';
                                                 html +=  (item.created_at);
                                               html += '</td>';

                                               html += '<td>';
                                                 html +=  (item.auditor);
                                               html += '</td>';

                                               html += '<td>';
                                                html +=  (item.status);
                                               html += '</td>';
                                               html += '</tr>';
                                            $('#tbody-auditor').append(html);
                                        });
                                        $.LoadingOverlay("hide");
                                        $('#TakeAction').modal('show');
                                    }else{
                                        $.LoadingOverlay("hide");
                                        $('#TakeAction').modal('hide');
                                           Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'เกิดข้อผิดพลาดข้อมูล?',
                                            showConfirmButton: false,
                                            timer: 1500
                                            })
                                    }
                                 
                                
                                }
                        });

                   

            });

           
            //เลือกทั้งหมด
            $('#checkall').change(function(event) {
                if($(this).prop('checked')){//เลือกทั้งหมด
                  $('#myTable').find('input.item_checkbox').prop('checked', true);
                }else{
                  $('#myTable').find('input.item_checkbox').prop('checked', false);
                }
            });


            $('#button_checker').click(function(event) {
                var tracking_ids = [];
                var check_id = false;

                $('.item_checkbox:checked').each(function(index, element) {
                    tracking_ids.push($(element).val());
                    if($(element).data('tracking_id') == ''){//ยังไม่มีไอดี
                        check_id = true;
                    }
                });

                var length = tracking_ids.length;
                if(check_id){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'มีบางรายการที่ยังไม่รับเรื่อง',
                        showConfirmButton: true,

                    });
                }else if (length > 0 ) {
                    $('#exampleModal').modal('show');
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กระกรุณาเลือกรายการ',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
            

            $('#add_items').on('click',function () {
                 let row =$('#checker').val();
                if(row != ''){
                    $('#div_checker').show();
                    let checker = $('#checker').find('option[value="'+row+'"]').text();
                    let table_tbody = $('#table_tbody');
                        // table_tbody.empty();
                        table_tbody.append('<tr>\n' +
                    '                    <td class="text-center">1</td>\n' +
                    '                    <td class="text-left">'+checker+'</td>\n' +
                    '                    <td class="text-center">' +
                    '                    <input type="hidden" name="checker[]"   class="data_checker" value="'+ row+'">\n' +
                    '                    <button type="button" class="btn btn-danger btn-xs inTypeDelete" data-types="'+row+'" ><i class="fa fa-remove"></i></button></td>\n' +
                    '                </tr>');
                    $("#checker option[value=" + row + "]").prop('disabled', true); //  เปิดรายการ 
                    ResetTableNumber();
                    $('#checker').val('').select2();
                }else{
                    Swal.fire('กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!');
                }
            
            });
             ResetTableNumber();
            $(document).on('click','.inTypeDelete',function () { 
                let types = $(this).attr('data-types');
                $("#checker option[value=" + types + "]").prop('disabled', false); //  เปิดรายการ 
                $(this).parent().parent().remove();
                ResetTableNumber();
            });



            //เลือกลบ
            $(document).on('click', '#submit_form', function(){


                  var id = [];
                  $('.item_checkbox:checked').each(function(index, element){
                      id.push($(element).data('tracking_id'));
                  });
                  console.log(id);

                  var checker = [];
                  $('.data_checker').each(function(index, element){
                       checker.push($(element).val());
                  });
                  if(id.length > 0 && checker.length > 0){
                                // Text
                                $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กําลังมอบหมาย กรุณารอสักครู่..."
                                });
                          $.ajax({
                                  type:"GET",
                                  url:  "{{ url('/certificate/tracking-labs/assign_labs') }}",
                                  data:{
                                      _token: "{{ csrf_token() }}",
                                      ids: id,
                                      checker: checker
                                  },
                                  success:function(data){
                                      table.draw();
                                      $('#exampleModal').modal('hide');
                                      $('#table_tbody').html(''); 
                                      $('#checkall').prop('checked', false);
                                      $("#checker option").prop('disabled', false); //  เปิดรายการ 
                                      $.LoadingOverlay("hide");
                                      Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'มอบหมายเรียบร้อย',
                                        showConfirmButton: false,
                                        timer: 1500
                                        })
                                      ResetTableNumber();
                                  }
                          });
               
                  }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กระกรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ',
                        showConfirmButton: false,
                        timer: 1500
                      });
                  }
            });



        });

         //รีเซตเลขลำดับ
         function ResetTableNumber(){
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                (rows.length==0)?$('#div_checker').hide():$('#div_checker').show();
                (rows.length==0)?$('.submit_form').hide():$('.submit_form').show();
                rows.each(function(index, el) {
                    $(el).children().first().html(index+1);
                });
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
