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
                    <h3 class="box-title pull-left">นัดหมายการประชุม</h3>

                    <div class="pull-right">


                      @can('add-'.str_slug('appointed-committee-lt'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/appointed-committee-lt/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      {{-- @can('delete-'.str_slug('appointed-committee-lt'))
                        <button class="btn btn-danger btn-sm waves-effect waves-light"  type="button"
                        id="bulk_delete">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                        </button>
                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>


                    {{-- <div class="row ">
                        <div class="col-md-6 form-group">
                            <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                                {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-4 control-label text-right ']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::select('filter_state',
                                            ['1'=>'นัดหมายการประชุม','2'=>'บันทึกผลการประชุม','3'=>'อยู่ระหว่างดำเนินการประชุม'], 
                                            null, 
                                            ['class' => 'form-control', 
                                            'id'=>'filter_state',
                                            'placeholder' => '- เลือกสถานะ -']); 
                            !!}
                        </div>
                        <div class="col-md-2">
                            <div class="  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="  pull-left m-l-15">
                                <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">ล้าง</button>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6 form-group">
                                {!! Form::label('', '', ['class' => 'col-md-4 control-label text-right ']) !!}
                            <div class="col-md-8">
                                {!! Form::select('filter_meeting_type_id',App\Models\Bcertify\Meetingtype::where('state',1)->pluck('title', 'id'),null, ['class' => 'form-control', 'placeholder' => '-เลือกวาระการประชุม-', 'required' => true]); !!}
                            </div>
                        </div>
          
                    </div>    --}}
    

                <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%" class="text-center">#</th>
                                        <th width="1%"><input type="checkbox" id="checkall"></th>
                                        <th width="15%" class="text-center">หัวข้อการประชุม</th>
                                        {{-- <th width="15%" class="text-center">วาระการประชุม</th> --}}
                                        <th width="15%" class="text-center">วันที่นัดหมาย</th>
                                        <th width="15%" class="text-center">สถานที่นัดหมาย</th>
                                        <th width="15%" class="text-center">สถานะ</th>
                                        {{-- <th width="10%" class="text-center">จัดการ</th> --}}
                                        <th width="19%" class="text-center">ผลการประชุม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ใช้ @forelse เพื่อวนลูปข้อมูล และจัดการกรณีไม่มีข้อมูล --}}
                                    @forelse ($meetingLtTransactions as $item)
                                    <tr>

                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        
                                        <td><input type="checkbox" name="ids[]" class="checkbox_child" value="{{ $item->id }}"></td>
                                        
                                        <td>{{ $item->title }}</td>
                                        
                                        <td>{{ $item->start_date }} {{ $item->start_time }}</td>


                                        <td>{{ $item->meeting_place }}</td>


                                        <td class="text-center">
                                            @if ($item->finish == null)
                                            <span class="badge badge-primary">อยู่ระหว่างดำเนินการ</span>
                                              @else  
                                              <span class="badge badge-danger">ปิด</span>
                                            @endif
                                            
                                        </td>

                                        {{-- <td class="text-center">
                                            <a href="#" class="btn btn-warning btn-sm">แก้ไข</a>
                                            <a href="#" class="btn btn-danger btn-sm">ลบ</a>
                                        </td> --}}

                                        <td class="text-center">
                                            <a href="{{route('certify.meeting-standards.lt.show',['id' => $item->id ])}}" class="btn btn-info btn-sm">บันทึกผล</a>
                                        </td>
                                    </tr>
                                    @empty

                                    <tr>
                                        <td colspan="9" class="text-center">ไม่พบข้อมูลการประชุม</td>
                                    </tr>
                                    @endforelse
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
