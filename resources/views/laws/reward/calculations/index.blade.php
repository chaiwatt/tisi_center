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
 
     .not-allowed {
        cursor: not-allowed
    }
 
    .rounded-circle {
        border-radius: 50% !important;
    }
 


    </style>
@endpush

@section('content')

@php
 
    $option_section = App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
@endphp
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">คำนวณสินบน</h3>

                    <div class="pull-right">
                 
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขคดี', '2' => 'ผู้ประกอบการ/TAXID'), null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
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
                                    {{-- {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!} --}}
                                    <div class="col-md-12">
                                                {!! Form::select('filter_status',
                                                 ['null' => 'รอคำนวณเงิน',
                                                 '1' => 'อยู่ระหว่างคำนวณ', 
                                                 '2' => 'ยืนยันการคำนวณ', 
                                                 '3' => 'อยู่ระหว่างรวบรวมหลักฐานเพื่อเบิกจ่าย', 
                                                 '4' => 'อยู่ระหว่างขอเบิกจ่าย', 
                                                 '5' => 'เบิกจ่ายเรียบร้อย', 
                                                 '99' => 'ฉบับร่าง'] , 
                                                 null,
                                                  ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_basic_section_id', 'มาตราความผิด', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_basic_section_id',  $option_section,  null,   ['class' => 'select2 select2-multiple',  "multiple"=>"multiple",  'id'=>'filter_basic_section_id']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_created_at', 'วันที่ชำระเงิน', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                             <div class="input-daterange input-group date-range">
                                                {!! Form::text('filter_calculate_start_date', null, ['id' => 'filter_calculate_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                {!! Form::text('filter_calculate_end_date', null, ['id' => 'filter_calculate_end_date','class' => 'form-control date', 'required' => true]) !!}
                                              </div>
                                          </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_created_at', 'วันที่คำนวณ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                             <div class="input-daterange input-group date-range">
                                                {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                              </div>
                                          </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_users', 'ผู้คำนวณ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                @php
                                                    $user_ids =   App\Models\Law\Reward\LawRewards::select('created_by')->groupBy('created_by')->pluck('created_by');
                                                    $users    =   App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                                                                ->whereIn('runrecno',$user_ids)
                                                                                ->orderbyRaw('CONVERT(title USING tis620)')
                                                                                ->pluck('title', 'id');
                                                 
                             
                                                @endphp
                                                <select name="filter_users" id="filter_users" class="form-control">
                                                    <option value="">- เลือกผู้คำนวณ-</option>
                                                    @if (count($users))
                                                            @foreach ($users as $key => $user)
                                                            <option value="{!!$key!!}">{!! $user !!}</option>
                                                            @endforeach
                                                    @endif
                                                  </select>
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
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="15%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="10%">มาตราความผิด/การจับกุม</th>
                                        <th class="text-center" width="10%">ค่าปรับ</th>
                                        <th class="text-center" width="10%">สถานะชำระ</th> 
                                        <th class="text-center" width="10%">สถานะคำนวณ</th>
                                        <th class="text-center" width="10%">ผู้คำนวณ</th>
                                        <th class="text-center" width="10%">คำนวณ/พิมพ์</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
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
    <script>
        var table = '';
        $(document).ready(function () {
           //ปฎิทิน
           $('.date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

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
                    url: '{!! url('/law/reward/calculations/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();

                        d.filter_basic_section_id = $('#filter_basic_section_id').val();
                        d.filter_calculate_start_date = $('#filter_calculate_start_date').val();
                        d.filter_calculate_end_date = $('#filter_calculate_end_date').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                        d.filter_users = $('#filter_users').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'amount', name: 'amount' },
                    { data: 'status', name: 'status' },
                    { data: 'status_reward', name: 'status_reward' }, 
                    { data: 'user_created', name: 'user_created' },
                    { data: 'action', name: 'action' }
                ],  
                columnDefs: [ 
                    { className: "text-center text-top", targets:[0,-1] },
                    { className: "text-right  text-top", targets:[4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
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


        });



    </script>

@endpush

 