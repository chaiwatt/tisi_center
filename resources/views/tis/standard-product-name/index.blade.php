@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

    <style>
        .label-filter{
            margin-top: 7px;
        }
        .border_white {
            border-color: rgb(255, 254, 254);
        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบจัดการชื่อผลิตภัณฑ์อุตสาหกรรม</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('standardproduct-name'))

                        @endcan

                        @can('add-'.str_slug('standardproduct-name'))

                        @endcan

                        @can('delete-'.str_slug('standardproduct-name'))

                        @endcan

                    </div>
                    <div class="clearfix"></div>
                    <hr>


                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อมาตรฐาน, เลขที่, เล่ม, ปี']); !!}
                            </div><!-- /form-group -->
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                {!! Form::select('filter_standard_type', App\Models\Basic\StandardType::pluck('title','id')->all(), null, ['class' => 'form-control', 'id' => 'filter_standard_type', 'placeholder'=>'-เลือกประเภท-']); !!}
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                {!! Form::select('filter_state', ['1'=>'ใช้งาน', '0'=>'ยกเลิก'], null, ['class' => 'form-control', 'id' => 'filter_state', 'placeholder'=>'-เลือกสถานะ-']); !!}
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-group  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                            </div>
                            <div class="form-group  pull-left m-l-15">
                                <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                    ล้าง
                                </button>
                            </div>
                        </div><!-- /.col-lg-1 -->
      
                    </div><!-- /.row -->

                    <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">
                         
                        </div>
                    </div>


                    <div class="clearfix"></div>
                    <div class="table-responsive">

                        <table width="100%" class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">#</th>
                                    <th width="15%" class="text-center">เลขที่มอก.</th>
                                    <th width="20%" class="text-center">ชื่อมาตรฐาน (TH)</th>
                                    <th width="20%" class="text-center">ชื่อมาตรฐาน (EN)</th>
                                    <th width="18%" class="text-center">ประเภท</th>
                                    <th width="30%" class="text-center">ชื่อผลิตภัณฑ์</th>
                                    <th width="10%" class="text-center">สถานะ</th>
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

    @include ('tis.standard.modals')
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    {{-- <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script> --}}
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script>
        $(document).ready(function () {

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24,
                ajax: {
                    "url": '{!! url('/tis/product_name/data_list') !!}',
                    "dataType": "json",
                    "data": function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_state = $('#filter_state').val();
                        d.filter_standard_type = $('#filter_standard_type').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tis_no', name: 'tis_no' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'tis_name_en', name: 'tis_name_en' },
                    { data: 'standard_type', name: 'standard_type' },
                    { data: 'tis_product_name', name: 'tis_product_name' },
                    { data: 'state', name: 'state' },
                ],
                columnDefs: [
                    // { className: "text-center", targets: [0,-1,-2] },
                    // { className: "text-left", targets: [1,2] }
                ],
                fnDrawCallback: function() {
                    $('#myTable_length').find('.totalrec').remove();
                    var el = '<label class="ml-1 totalrec" style="color:green;">&nbsp;&nbsp;(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</label>';
                    $('#myTable_length').append(el);
                },
                stateSaveParams: function (settings, data) {
                    data.search.filter_search = $('#filter_search').val();
                    data.search.filter_state = $('#filter_state').val();
                    data.search.filter_standard_type = $('#filter_standard_type').val();
                    // data.search.filter_publish_date_end = $('#filter_publish_date_end').val();
                    // data.search.filter_refer = $('#filter_refer').val();
                    // data.search.filter_set_format = $('#filter_set_format').val();
                    // data.search.filter_review_status = $('#filter_review_status').val();
                    // data.search.filter_product_group = $('#filter_product_group').val();
                    // data.search.filter_board_type = $('#filter_board_type').val();
                    // data.search.filter_staff_group = $('#filter_staff_group').val();
                    // data.search.filter_staff_responsible = $('#filter_staff_responsible').val();
                    // data.search.filter_gazette = $('#filter_gazette').val();
                },
                stateLoadParams: function (settings, data) {
                    $('#filter_search').val(data.search.filter_search);
                    $('#filter_state').val(data.search.filter_state).trigger('change.select2');
                    $('#filter_standard_type').val(data.search.filter_standard_type).trigger('change.select2');

                }
            });

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_state').val('').select2();
                $('#filter_standard_type').val('').select2();

                table.draw();

            });

            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
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

            @if(\Session::has('error_message'))
                $.toast({
                    heading: 'Sorry!',
                    position: 'top-center',
                    text: '{{session()->get('error_message')}}',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 3000,
                    stack: 6
                });
            @endif

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked', false);
                }
            });

            
            $('#btn_search').click(function () {
                table.draw();
            });

            
            $('#myTable tbody').on('blur', '.description', function(){
                var id = $(this).data('id');
                var description = $(this).val();
                $.ajax({
                    method: "POST",
                        url: "{{ url('/tis/product_name/update_description') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "description": description
                    },
                    success : function (msg){
                        if (msg == "success") {
                        // table.draw();
                            $.toast({
                                heading: 'Success!',
                                position: 'top-center',
                                text: 'บันทึกสำเร็จ !',
                                loaderBg: '#70b7d6',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 6
                            });
                        }
                    }
                });
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
