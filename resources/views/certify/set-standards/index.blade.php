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
                    <h3 class="box-title pull-left">กำหนดมาตรฐานการตรวจสอบและรับรอง #STD</h3>

                    <div class="pull-right">


                    </div>

                    <div class="clearfix"></div>
                    <hr>

               
                    {{-- <div class="row ">
                        <div class="col-md-5 form-group">
                          <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                              {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                              <div class="col-md-9">
                                  {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-md-5">
                          <div class=" {{ $errors->has('filter_year') ? 'has-error' : ''}}">
                            {!! Form::label('filter_year', 'ร่างแผนปี'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                            <div class="col-md-9">
                                {!! Form::select('filter_year',
                                    HP::Years(), 
                                  null, 
                                  ['class' => 'form-control', 
                                  'id'=>'filter_year',
                                  'placeholder' => '-- เลือกร่างแผนปี --']); !!}
                            </div>
                         </div>
                      </div> 
                    </div> --}}
  
{{--     
                    <div class="row ">
                      <div class="col-md-5 form-group ">
                        <div class=" {{ $errors->has('filter_standard_type') ? 'has-error' : ''}}">
                            {!! Form::label('filter_standard_type', 'ประเภท'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                            <div class="col-md-9">
                                    {!! Form::select('filter_standard_type',
                                    App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                    null,
                                    ['class' => 'form-control',
                                    'id'=>'filter_standard_type',
                                    'placeholder'=>'-- เลือกประเภท --']) 
                                !!}
                            </div>
                         </div>
                      </div>
                      <div class="col-md-5">
                        <div class=" {{ $errors->has('filter_method_id') ? 'has-error' : ''}}">
                          {!! Form::label('filter_method_id', 'วิธีการ'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                          <div class="col-md-9">
                                {!! Form::select('filter_method_id',
                                    App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
                                    null,
                                    ['class' => 'form-control',
                                    'id'=>'filter_method_id',
                                     'placeholder'=>'-- เลือกวิธีการ --']) 
                                !!}
                          </div>
                       </div> 
                    </div>
                  </div> --}}
  
    
                  {{-- <div class="row  ">
                    <div class="col-md-5 form-group ">
                      <div class=" {{ $errors->has('filter_status') ? 'has-error' : ''}}">
                          {!! Form::label('filter_status', 'สถานะ'.' :', ['class' => 'col-md-3 control-label text-right ']) !!}
                          <div class="col-md-9">
                              {!! Form::select('filter_status',
                              ['-1'=>'รอกำหนดมาตรฐาน',
                                '1'=>'อยู่ระหว่างดำเนินการ',
                               '2'=>'อยู่ระหว่างการประชุม', 
                               '3'=>'อยู่ระหว่างสรุปรายงานการประชุม', 
                               '4'=>'อยู่ระหว่างจัดทำมาตรฐาน', 
                               '5'=>'สรุปวาระการประชุมเรียบร้อย'], 
                            null, 
                            ['class' => 'form-control', 
                            'id'=>'filter_status',
                            'placeholder' => '-- เลือกสถานะ --']); !!}
                          </div>
                       </div>
                    </div>
                    <div class="col-md-5 ">  </div>
                    <div class="col-md-2">
                        <div class="  pull-left">
                            <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                        </div>
                        <div class="  pull-left m-l-15">
                            <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                ล้าง
                            </button>
                        </div>
                    </div>
                </div> --}}

                {!! Form::open(['route' => 'set-standards.search_data_list', 'method' => 'GET', 'id' => 'search_form']) !!}
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_search') ? 'has-error' : '' }}">
                                {!! Form::label('filter_search', 'คำค้น :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('filter_search', request('filter_search'), ['id' => 'filter_search', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="{{ $errors->has('filter_year') ? 'has-error' : '' }}">
                                {!! Form::label('filter_year', 'ร่างแผนปี :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_year', HP::Years(), request('filter_year'), ['class' => 'form-control', 'id' => 'filter_year', 'placeholder' => '-- เลือกร่างแผนปี --']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_standard_type') ? 'has-error' : '' }}">
                                {!! Form::label('filter_standard_type', 'ประเภท :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_standard_type', App\Models\Bcertify\Standardtype::orderByRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), request('filter_standard_type'), ['class' => 'form-control', 'id' => 'filter_standard_type', 'placeholder' => '-- เลือกประเภท --']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="{{ $errors->has('filter_method_id') ? 'has-error' : '' }}">
                                {!! Form::label('filter_method_id', 'วิธีการ :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_method_id', App\Models\Basic\Method::orderByRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), request('filter_method_id'), ['class' => 'form-control', 'id' => 'filter_method_id', 'placeholder' => '-- เลือกวิธีการ --']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_status') ? 'has-error' : '' }}">
                                {!! Form::label('filter_status', 'สถานะ :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_status', [
                                        '-1' => 'รอกำหนดมาตรฐาน',
                                        '1' => 'อยู่ระหว่างดำเนินการ',
                                        '2' => 'อยู่ระหว่างการประชุม',
                                        '3' => 'อยู่ระหว่างสรุปรายงานการประชุม',
                                        '4' => 'อยู่ระหว่างจัดทำมาตรฐาน',
                                        '5' => 'สรุปวาระการประชุมเรียบร้อย'
                                    ], request('filter_status'), ['class' => 'form-control', 'id' => 'filter_status', 'placeholder' => '-- เลือกสถานะ --']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <div class="pull-left">
                                <button type="submit" class="btn btn-info waves-effect waves-light" id="button_search" style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="pull-left m-l-15">
                                <a href="{{ route('set-standards.search_data_list') }}" class="btn btn-warning waves-effect waves-light" id="filter_clear">ล้าง</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
    
                <div class="clearfix"></div>
                <div class="row">
                        <div class="col-md-12">
                            {{-- <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th width="1%" class="text-center">#</th>
                                    <th  width="1%" ><input type="checkbox" id="checkall"></th>
                                    <th width="10%" class="text-center">รหัสโครงการ</th>
                                    <th width="25%" class="text-center">ชื่อมาตรฐาน</th>
                                    <th width="15%" class="text-center">ประเภท</th>
                                    <th width="10%" class="text-center">วิธีการ</th>
                                    <th width="10%" class="text-center">บรรจุแผนปี</th>
                                    <th width="10%" class="text-center">ระยะเวลาจัดทำ</th>
                                    <th width="15%" class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">จัดการ</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> --}}

                             <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%" class="text-center">#</th>
                                        {{-- <th width="1%"></th> --}}
                                        <th width="15%" >รหัสโครงการ</th>
                                        <th width="20%" >ชื่อมาตรฐาน</th>
                                        <th width="10%" >ประเภท</th>
                                        <th width="10%" class="text-center">วิธีการ</th>
                                        <th width="10%" class="text-center">บรรจุแผนปี</th>
                                        <th width="10%" class="text-center">ระยะเวลาจัดทำ</th>
                                        <th width="10%" >สถานะ</th>
                                        <th width="20%" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($setStandards as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $setStandards->firstItem() + $index }}</td>
                                            {{-- <td>
                                                @if($item->state == 99)
                                                    <input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="{{ $item->id }}">
                                                @else
                                                    <!-- Empty checkbox cell if state is not 99 -->
                                                @endif
                                            </td> --}}
                                            <td >{{ $item->projectid ?? 'อยู่ระหว่างกำหนดมาตรฐาน' }}</td>
                                            <td >{{ $item->TisName ?? '' }}</td>
                                            <td >{{ $item->StdTypeName ?? '' }}</td>
                                            <td class="text-center">{{ $item->estandard_plan_to->method_to->title ?? '' }}</td>
                                            <td class="text-center">{{ $item->TisYear ?? '' }}</td>
                                            <td class="text-center">{{ $item->Period ? $item->Period . ' เดือน' : '' }}</td>
                                            <td >{{ $item->StatusText ?? '' }}</td>
                                            <td class="text-center">
                                                
                                                {{-- {{$item->estandard_plan_to->method_id}}
                                                @if ($item->estandard_plan_to->method_id == 1 || $item->estandard_plan_to->method_id == 2)
                                                    สูง
                                                    @elseif($item->estandard_plan_to->method_id == 3)
                                                    ต่ำ/ร่าง
                                                @endif --}}

                                                {{-- <div class="dropdown">
                                                    <button class="btn btn-warning btn-xs dropdown-toggle" title="View setstandard" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">รายการ1</a></li>
                                                        <li><a href="#">รายการ2</a></li>
                                                    </ul>
                                                </div>

                                                <a href="/certify/set-standards/{{$item->id}}" title="View setstandar" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="/certify/set-standards/{{$item->id}}/edit" title="Edit setstandar" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a> --}}

                                                <div style="display: inline-flex; align-items: center; gap: 5px;">
                                                    <a href="" title="View setstandard" class="btn btn-danger btn-xs" style="display: inline-block;">
                                                        <i class="fa fa-cog"></i>
                                                    </a>
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-warning btn-xs dropdown-toggle" title="View setstandard" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="#">คณะกรรมธิการ</a></li>
                                                            <li><a href="#">เวียนรมาตรฐาน</a></li>
                                                            <li><a href="#">คณะกรรมหนด</a></li>
                                                        </ul>
                                                    </div>
                                                    <a href="/certify/set-standards/{{$item->id}}" title="View setstandard" class="btn btn-info btn-xs" style="display: inline-block;">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                      <a href="/certify/set-standards/{{$item->id}}/edit_sub_appointment" title="Edit setstandard" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                        อนุ
                                                    </a>
                                                    {{-- certify/set-standards/{id?}/edit_sub_appointment --}}
                                                    <a href="/certify/set-standards/{{$item->id}}/edit" title="Edit setstandard" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div id="myTable_length">
                                    <span class="totalrec" style="color:green;">
                                        <b>(ทั้งหมด {{ $setStandards->total() }} รายการ)</b>
                                    </span>
                                </div>
                                <div>
                                    {{ $setStandards->links() }}
                                </div>
                            </div>

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
            
            // var table = $('#myTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     searching: false,
            //     ajax: {
            //         url: '{!! url('/certify/set-standards/data_list') !!}',
            //         data: function (d) {
            //             d.filter_search = $('#filter_search').val();
            //             d.filter_year = $('#filter_year').val();
            //             d.filter_standard_type = $('#filter_standard_type').val();
            //             d.filter_method_id = $('#filter_method_id').val();
            //             d.filter_status = $('#filter_status').val();
            //         }
            //     },
            //     columns: [
            //         { data: 'DT_Row_Index', searchable: false, orderable: false},
            //         { data: 'checkbox', searchable: false, orderable: false},
            //         { data: 'projectid', name: 'projectid' },
            //         { data: 'tis_name', name: 'tis_name' }, 
            //         { data: 'std_type', name: 'std_type' },
            //         { data: 'method_id', name: 'method_id' },
            //         { data: 'tis_year', name: 'tis_year' },
            //         { data: 'period', name: 'period' },
            //         { data: 'status', name: 'status' },
            //         { data: 'action', name: 'action' },
            //     ],
            //     columnDefs: [
            //         { className: "text-center", targets:[0,-1] }
            //     ],
            //     fnDrawCallback: function() {
            //         $('#myTable_length').find('.totalrec').remove();
            //         var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
            //         $('#myTable_length').append(el);

            //         $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
            //     }
            // });



            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
                $('#filter_search').val('');
                $('#filter_year').val('').select2();
                $('#filter_standard_type').val('').select2();
                $('#filter_method_id').val('').select2();
                $('#filter_status').val('').select2();
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
