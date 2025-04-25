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
                    <h3 class="box-title pull-left">ระบบนำส่งใบรับรองระบบงาน</h3>

                    <div class="pull-right">


                      @can('add-'.str_slug('sendcertificates'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/send-certificates/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('sendcertificates'))
                        <button class="btn btn-danger btn-sm waves-effect waves-light"  type="button"
                        id="bulk_delete">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ปิด</b>
                        </button>
                      @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row ">
                      <div class="col-md-6 form-group">
                        <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-2 control-label text-right ']) !!}
                            <div class="col-md-10">
                                {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                            </div>
                         </div>
                      </div><!-- /.col-md-4 -->
                      <div class="col-md-4">
                               {!! Form::select('filter_state',
                                ['99'=>'ร่าง','1'=>'รอดำเนินการ','2'=>'อยู่ระหว่างยืนยันการลงนาม','3'=>'ลงนามใบรับรองเรียบร้อย'], 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_state',
                                'placeholder' => '-- เลือกสถานะ --']); !!}
                    </div><!-- /.col-md-1 -->
                      <div class="col-md-2">
                            <div class="  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="  pull-left m-l-15">
                                <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                    ล้าง
                                </button>
                            </div>
                      </div><!-- /.col-md-1 -->
                  </div><!-- /.row -->

  
                  <div class="row ">
                    <div class="col-md-1"> </div><!-- /.col-md-1 -->
                    <div class="col-md-4 form-group ">
                        {!! Form::select('filter_certificate_type',
                             ['1'=>'CB','2'=>'IB','3'=>'LAB'], 
                            null, 
                            ['class' => 'form-control', 
                            'id'=>'filter_certificate_type',
                            'placeholder' => '-- เลือกการรับรอง --']);
                         !!}
                    </div><!-- /.col-md-4 -->
                    <div class="col-md-4">
                          {!! Form::select('filter_signer_id',
                                 App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name', 'id'), 
                              null,
                              ['class' => 'form-control',
                              'id'=>'filter_signer_id',
                                'placeholder'=>'-- เลือกผู้ลงนาม --']) 
                          !!} 
                    </div><!-- /.col-md-1 -->
                    <div class="col-md-3"> </div><!-- /.col-md-1 -->
                </div><!-- /.row -->

              
    
                    <div class="clearfix"></div>

                <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                            <tr>
                                <th width="1%" class="text-center">#</th>
                                <th  width="1%" ><input type="checkbox" id="checkall"></th>
                                <th width="15%" class="text-center">การรับรองงาน</th>
                                <th width="15%" class="text-center">จำนวนใบรับรอง</th>
                                <th width="15%" class="text-center">ผู้ลงนาม</th>
                                <th width="15%" class="text-center">วันที่นับทึก</th>
                                <th width="15%" class="text-center">วันที่ยืนยันตัวตน</th>
                                <th width="13%" class="text-center">สถานะ</th>
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
                    url: '{!! url('/certify/send-certificates/data_list') !!}',
                    data: function (d) {
                      
                        d.filter_search = $('#filter_search').val();
                        d.filter_state = $('#filter_state').val();
                        d.filter_certificate_type = $('#filter_certificate_type').val();
                        d.filter_signer_id = $('#filter_signer_id').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'certificate_type', name: 'certificate_type' },
                    { data: 'number', name: 'number' }, 
                    { data: 'sign_name', name: 'sign_name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'status', name: 'status' },
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
              $('#filter_state').val('').select2();
              $('#filter_certificate_type').val('').select2();
              $('#filter_signer_id').val('').select2();
              table.draw();
           });
 
           $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
                }

         });

 

            $(document).on('click', '#bulk_delete', function(){

                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                if(id.length > 0){

                    if (confirm("ยืนยันการลบข้อมูล " + id.length + " แถว นี้ ?")) {
                        $.ajax({
                                type:"POST",
                                url:  "{{ url('/certify/send-certificates/delete') }}",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success:function(data){
                                    table.draw();
                                    $.toast({
                                        heading: 'Success!',
                                        position: 'top-center',
                                        text: 'ลบสำเร็จ !',
                                        loaderBg: '#70b7d6',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 6
                                    });
                                    $('#checkall').prop('checked', false);
                                }
                        });
                    }

                }else{
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
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
