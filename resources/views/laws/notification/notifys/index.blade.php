@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .font-weight-bold {
            font-weight: 500 !important;
        }
    </style>
    <style type="text/css" id="css-after-load">

    </style>
    <div id="tmp-after-load" class="hide">
            
    </div>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Left sidebar -->
            <div class="col-md-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">แจ้งเตือน</h3>
                    <div class="clearfix"></div>
                    <hr class="m-t-0">

                    @php
                        $category = HP_Law::CategoryNotify();
                    @endphp

                    <div class="row">
                        <div class="col-lg-2 col-md-3  col-sm-4 col-xs-12 inbox-panel">
                            <div>
                                <h3 class="panel-title m-t-0 m-b-0">ระบบงาน</h3>
                                <div class="list-group mail-list m-t-20">

                                    <a href="javascript:void(0);" class="list-group-item h5" onclick="SetCategory('all');" >
                                        ทั้งหมด @if( $category->sum('law_notify_count') >= 1)<span class="label label-rouded label-danger pull-right">{!! $category->sum('law_notify_count')  !!}</span>@endif 
                                    </a>
                                    @foreach ( $category as $item )
                                        <a href="javascript:void(0);" class="list-group-item h5" onclick="SetCategory({!! $item->id !!});" >
                                            {!! $item->name !!}
                                            <span class="label label-rouded {!! !empty($item->color)?$item->color:'label-default' !!} pull-right">{!! $item->law_notify_count !!}</span> 
                                        </a>
                                    @endforeach

                                </div>
                
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 mail_listing">
                      
                            <div class="row">
                                <div class="col-md-12  col-xs-12">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            {!! Form::select('filter_condition_search', [ '1' => 'ชื่อเรื่อง', '2' => 'ผู้บันทึก'  ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <div class="inputWithIcon">
                                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'คำค้นหา']); !!}
                                                <i class="fa fa-search btn_search"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-info waves-effect waves-light" id="btn_search">ค้นหา</button>
                                                    <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean"> ล้าง </button>
                                            <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </button>
                                        </div>
                                        <div class="form-group col-md-2">
                                            {!! Form::select('filter_state', [ '1' => 'อ่านแล้ว', '2' => 'ยังไม่อ่าน', '3' => 'ติดดาว'  ],null,['class' => 'form-control','placeholder' => '- ตัวกรอง -','id' => 'filter_state']) !!}
                                        </div>
                                        <div class="col-md-1">
                                            <div class="pull-right has-dropdown">
                                                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info  btn-outline" type="button"> Action <span class="caret"></span> </button>
                                                <ul role="menu" class="dropdown-menu">
                                                    <li>
                                                        <a class="" href="javascript:void(0);" onclick="UpdateState(1);">
                                                            อ่านแล้ว
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="" href="javascript:void(0);" onclick="UpdateState(2);">
                                                            ยังไม่อ่าน
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="" href="javascript:void(0);" onclick="UpdateState(3);">
                                                            ติดดาว
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                                <div class="row">
                                    {!! Form::hidden('filter_category_id', null, ['class' => 'form-control', 'id' => 'filter_category_id']); !!}
                                </div>
                            </div>
                            
                            <br>

                            <div class="row">
                                <div class="col-md-12  col-xs-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box box_filter" style="display: flex; flex-direction: column;">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    {!! Form::label('filter_date_created_at', 'วันที่'.':', ['class' => 'col-md-12 control-label ']) !!}
                                                    <div class="col-md-12">
                                                        <div class="input-daterange input-group" id="date-range">
                                                            {!! Form::text('filter_created_at_start', null, ['class' => 'form-control','id'=>'filter_created_at_start']) !!}
                                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                            {!! Form::text('filter_created_at_end', null, ['class' => 'form-control','id'=>'filter_created_at_end']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12  col-xs-12">
                                    <table class="table table-hover" id="myTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="2%"><input type="checkbox" id="checkall"></th>
                                                <th width="2%"></th>
                                                <th width="35%">ชื่อเรื่อง</th>
                                                <th width="18%">หมวดหมู่ระบบ</th>
                                                <th width="20%">ผู้บันทึก</th>
                                                <th width="15%">วันที่</th>
                                                <th width="10%">รายละเอียด</th>

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
        </div>
 
    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {
            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                autoclose: true,
            });


            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[5, 'asc']],
                ajax: {
                    url: '{!! url('/law/notifys/data_list') !!}',
                    data: function (d) {

                        d.filter_condition_search   = $('#filter_condition_search').val();
                        d.filter_search             = $('#filter_search').val();
                        d.filter_category_id        = $('#filter_category_id').val();
                        d.filter_state              = $('#filter_state').val();

                        d.filter_created_at_start   = $('#filter_created_at_start').val();
                        d.filter_created_at_end     = $('#filter_created_at_end').val();

                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'star', name: 'star', searchable: false, orderable: false },
                    { data: 'title', name: 'title' },
                    { data: 'system', name: 'system'},
                    { data: 'created_by', name: 'created_by', searchable: false, orderable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', searchable: false, orderable: false },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[-1] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();

                    $(rows).find(".item_read_type").each(function( index, rowId ) {
                        var read_type = $(rowId).val();
                        var rows = $(rowId).parent().parent();

                        if( read_type == 1){
                            rows.find("td").addClass('text-muted');
                            rows.find("td").eq(2).removeClass('font-weight-bold');
                        }else{
                            rows.find("td").removeClass('text-muted');
                            rows.find("td").eq(2).addClass('font-weight-bold');
                        }
           
                    });

                    $(rows).find('.bg-color').each(function(index, el) {
                        $('#tmp-after-load').append('<div class="'+$(el).data('color')+'"></div>');
                        var css =  $('#tmp-after-load').find('.'+$(el).data('color')).css('background-color');
                        $(el).removeClass( $(el).data('color') );
                        $(el).css("color", css+ ' !important;');
                        $(el).css("background-color", 'transparent !important;');
                    });

                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);

                }
            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#filter_state').change(function (e) { 
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('.box_filter').find('input').val('');
                $('.box_filter').find('select').val('').trigger('change.select2');
                table.draw();
            });

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            
            $(document).on('click', '.btn_update_marked', function(e) {

                var id = $(this).data('id');
                var state = $(this).data('state');

                var ids = [];
                    ids.push(id);

                $.ajax({
                    method: "put",
                    url: "{{ url('law/notifys/update-marked') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id_publish": ids,
                        "state": state
                    }
                }).success(function (msg) {
                    table.draw();
                });


            });

            $(document).on('click', '.btn_action_link', function(e) {
                let row      = $(this).closest( "tr" );
                var url      = row.find('a.action_link').attr('href');
                window.location.href = url;
            });


            $('#filter_created_at_start').change(function (e) {          
                if(  $('#filter_created_at_end').val() == '' ){
                    $('#filter_created_at_end').val( $(this).val() );
                }
            });
            LoadBoxFilter();
        });

        function LoadBoxFilter(){

            // let input  = $('.box_filter').find('input').val();
            // let select = $('.box_filter').find('select').val();

            let input  = false;
            $('.box_filter').find('input').each(function (index, rowId) {

                if( checkNone(rowId.value) ){
                    input = true;
                }

            });

            if( input === true ){
                $('#search-btn').addClass('in');
            }

        }

        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('#show_time').text(object);
                }
            });
        }

        function UpdateState(state){

            if( state == '1'){
                var text_alert = 'อ่านแล้ว';
            }else if( state == '2'){
                var text_alert = 'ยังไม่อ่าน';
            }else if( state == '3'){
                var text_alert = 'ติดดาว';
            }
            var ids = [];

            //Iterate over all checkboxes in the table
            table.$('.item_checkbox:checked').each(function (index, rowId) {
                ids.push(rowId.value);
            });

            if (ids.length > 0) {

                if (confirm("ยืนยันการ"+text_alert+" " + ids.length + " แถว นี้ ?")) {

                    $.ajax({
                        method: "put",
                        url: "{{ url('law/notifys/update-state') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": ids,
                            "state": state
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            table.draw();

                            $('#checkall').prop('checked',false );
                        }
                    });
                }

            }else {
                alert("โปรดเลือกอย่างน้อย 1 รายการ");
            }

        }

        function SetCategory(id){
            $('#filter_category_id').val(id);
            table.draw();
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
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