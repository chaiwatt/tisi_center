@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                {{-- <div class="white-box"> --}}

                    
                    <div class="row ">
                      <div class="col-md-12 text-center ">
                                <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="90px" width="90px"/>
                                <b style="font-size: 20pt;"> รายการใบรับรองระบบงาน</b>   
                      </div><!-- /.col-md-12 -->
                      
                   </div>
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row ">
                      <div class="col-md-4 form-group">
                        <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                            {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                            <div class="col-md-9">
                                {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                            </div>
                         </div>
                      </div><!-- /.col-md-6 -->
                      <div class="col-md-3">
                              {!! Form::select('filter_type_unit',
                                 ['1'=>'หน่วยรับรอง','2'=>'หน่วยตรวจ','3'=>'ห้องปฏิบัติการทดสอบ','4'=>'ห้องปฏิบัติการสอบเทียบ'], 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_type_unit',
                                'placeholder' => '-- ค้นหาประเภทสถานประกอบการ --'])
                              !!}
                      </div><!-- /.col-md-2 -->
                       <div class="col-md-3">
                              {!! Form::select('filter_province',
                                   App\Models\Basic\Province::whereNull('state')->pluck('PROVINCE_NAME','PROVINCE_NAME'), 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_province',
                                'placeholder' => '- เลือกจังหวัดสถานประกอบการ -']); !!}
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

 
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                <th width="1%" class="text-center">#</th>
                                <th  width="15%" class="text-center">เลขที่ใบรับรอง</th>
                                <th width="15%" class="text-center">ประเภทสถานประกอบการ</th>
                                <th width="44%" class="text-center">ชื่อสถานประกอบการ</th>
                                <th width="15%" class="text-center">หมายเลขการรับรอง</th>
                                <th width="10%" class="text-center">เรียกดู</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
           
 


                {{-- </div> --}}
            </div>
        </div>
    </div>
@endsection


@push('js')
 
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script>
        $(document).ready(function () {
 

         var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/report/certificate/data_list') !!}',
                    data: function (d) {  
                        d.sort = '{{ $sort }}';
                        d.filter_search = $('#filter_search').val();
                        d.filter_type_unit = $('#filter_type_unit').val();
                        d.filter_province = $('#filter_province').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'certificate_no', name: 'certificate_no' },
                    { data: 'certify_type', name: 'certify_type' },
                    { data: 'name', name: 'name' }, 
                    { data: 'accereditatio_no', name: 'accereditatio_no' },
                    { data: 'action', name: 'action' }
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1] }
                ],
                fnDrawCallback: function() {
                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(จำนวนใบรับรองทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);
                    $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
                }
            });



            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
              $('#filter_search').val('');
              $('#filter_type_unit').val('').select2();
              $('#filter_province').val('').select2();
              table.draw();
           });
           

        });

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
