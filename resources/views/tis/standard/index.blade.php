@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

    <style>
        .label-filter{
            margin-top: 7px;
        }
    /*
        Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
        */
        /* @media
        only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px)  {

            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

        tr {
        margin: 0 0 1rem 0;
        }

        tr:nth-child(odd) {
        background: #eee;
        }

            td {

                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {

                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }


        td:nth-of-type(1):before { content: "No."; }
            td:nth-of-type(2):before { content: "เลือก"; }
            td:nth-of-type(3):before { content: "ชื่อมาตรฐาน"; }
            td:nth-of-type(4):before { content: "วันที่ประกาศใช้/มีผลบังคับใช้"; }
            td:nth-of-type(5):before { content: "เลข มอก. (มอก. อ้างอิง)"; }
            td:nth-of-type(6):before { content: "ประเภท/รูปแบบ"; }
            td:nth-of-type(7):before { content: "การกำหนด"; }
            td:nth-of-type(8):before { content: "วิธีจัดทำ"; }
            td:nth-of-type(9):before { content: "กลุ่มผลิตภัณฑ์"; }
            td:nth-of-type(10):before { content: "อุตสาหกรรมเป้าหมาย"; }
            td:nth-of-type(11):before { content: "สถานะ"; }
            td:nth-of-type(12):before { content: "เครื่องมือ"; }
            td:nth-of-type(13):before { content: "อายุของ มอก. (ปี)"; }

        } */
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลมาตรฐาน (มอก.)</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('standard'))

                        @endcan

                        @can('add-'.str_slug('standard'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/tis/standard/create') }}" target="_blank">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('standard'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/tis/standard/export_excel', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา ชื่อมาตรฐาน, เลขที่, เล่ม, ปี']); !!}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
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
                                <div class="form-group  pull-left m-l-15">
                                    {{-- {!! Form::model($filter, ['url' => '/tis/standard/export_excel', 'method' => 'get', 'id' => 'myFilter2']) !!} --}}
                                        <button type="submit" formtarget="_blank" class="btn btn-success btn-sm waves-effect waves-light">
                                            ข้อมูลดิบไฟล์ Excel
                                        </button>
                                    {{-- {!! Form::close() !!} --}}
                                </div>
                            </div><!-- /.col-lg-1 -->
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group  pull-right">
                                    {!! Form::select('filter_state', ['1'=>'ใช้งาน', '0'=>'ยกเลิก'], null, ['class' => 'form-control', 'id' => 'filter_state', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                </div>
                            </div>
                        </div><!-- /.row -->

                    	<div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                @include ('tis.standard.filters')
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    <div class="table-responsive">

                        {!! Form::open(['url' => '/tis/standard/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['url' => '/tis/standard/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                            <input type="hidden" name="state" id="state" />
                        {!! Form::close() !!}

                        <table width="100%" class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th width="2%"  class="text-center"><input type="checkbox" id="checkall"></th>
                                    <th width="2%"  class="text-center">#</th>
                                    <th width="6%"  class="text-center">เลขที่มอก.</th>
                                    <th width="14%"  class="text-center">ชื่อมาตรฐาน</th>
                                    <th width="6%"  class="text-center">ICS</th>
                                    <th width="6%"  class="text-center">วันที่มีผลใช้งาน</th>
                                    <th width="6%"  class="text-center">มาตรฐานอ้างอิง</th>
                                    <th width="5%"  class="text-center">ทั่วไป/บังคับ</th>
                                    <th width="7%"  class="text-center">กลุ่มผลิตภัณฑ์</th>
                                    <th width="7%"  class="text-center">กลุ่มเจ้าหน้าที่</th>
                                    <th width="5%" class="text-center">ทบทวน</th>
                                    <th width="5%"  class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">เครื่องมือ</th>
                                    <th width="5%" class="text-center">อายุ<br>(ปี)</th>
                                </tr>
                            </thead>
                            <tbody>
                            {{-- @foreach($standard as $item)
                                @php
                                    // if($item->announce_compulsory=='y'){
                                    //     $issue_date = $item->issue_date_compulsory;
                                    // } else if($item->announce_compulsory=='n') {
                                    //       $issue_date = $item->issue_date;
                                    // } else {
                                    //       $issue_date = date('Y-m-d');
                                    // }

                                    $issue_date = null;
                                    if( !is_null( $item->issue_date )  ){
                                        $issue_date = $item->issue_date;
                                    }

                                    $issue_date_compulsory = null;
                                    if( ($item->announce_compulsory == 'y') && !empty($item->issue_date_compulsory)  ){
                                        $issue_date_compulsory = $item->issue_date_compulsory;
                                    }

                                    $year_age = null;
                                    if( !is_null($issue_date) || !is_null($issue_date_compulsory)  ){

                                        if( !is_null($issue_date) && !is_null($issue_date_compulsory) ){
                                            $dates = $issue_date;
                                        }else if( !is_null($issue_date) && is_null($issue_date_compulsory) ){
                                            $dates = $issue_date;
                                        }else if( is_null($issue_date) && !is_null($issue_date_compulsory) ){
                                            $dates = $issue_date_compulsory;
                                        }

                                        $today = date("Y-m-d");

                                        if( $dates <= $today ){
                                            list($byear, $bmonth, $bday) = explode("-", $dates);
                                            list($tyear, $tmonth, $tday) = explode("-", $today);

                                            $mk_birthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
                                            $mk_now = mktime(0, 0, 0, $tmonth, $tday, $tyear);
                                            $mk_age = ($mk_now - $mk_birthday);

                                            $year_ages = date("Y", $mk_age) - 1970;
                                            $month_age = date("m", $mk_age) - 1;
                                            $day_age = date("d", $mk_age) - 1;

                                            if( $year_ages != 0 && $year_ages > 0  ){
                                                $year_age .= "{$year_ages} ปี ";
                                            }

                                            if( $month_age != 0 && $month_age > 0 ){
                                                $year_age .= "{$month_age} เดือน ";
                                            }

                                            if(  $day_age != 0 && $day_age > 0 ){
                                                $year_age .= "{$day_age} วัน";
                                            }else if( $year_ages == 0 && $month_age == 0 && $day_age == 0 ){
                                                $year_age .= "{$day_age} วัน";
                                            }
                                        }else{
                                            $year_age .= "N/A";
                                        }

                                    }else{
                                        $year_age .= "N/A";
                                    }

                                    $total_age = 0;
                                    if( !is_null($issue_date) && !is_null($issue_date_compulsory) ){
                                        $total_age = @HP::YearCal($issue_date);
                                    }else if( !is_null($issue_date) && is_null($issue_date_compulsory) ){
                                        $total_age = @HP::YearCal($issue_date);
                                    }else if( is_null($issue_date) && !is_null($issue_date_compulsory) ){
                                        $total_age = @HP::YearCal($issue_date_compulsory);
                                    }

                                    $title_en = !empty($item->title_en)?'<br>('.$item->title_en.')':'';
                                    $gazette_status = !empty($item->government_gazette) && $item->government_gazette=='w'?'<br><span style="color: red">('.$item->GovernmentGazetteName.')</span>':'';

                                @endphp
                                <tr>
                                  <td>{{ $loop->iteration or $item->id }}</td>
                                    <td class="text-center">{{ $standard->perPage()*($standard->currentPage()-1)+$loop->iteration }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td>{{ $item->tis_no }}{{ !empty($item->tis_book) ? ' เล่ม '.$item->tis_book : ''}}{{ '-'.$item->tis_year }}</td>
                                    <td style="font-size: small;">{!! $item->title.$title_en.$gazette_status !!}</td>
                                    <td>{!! $item->IsoCodeName !!}</td>
                                    <td>
                                        @if( !is_null($issue_date) && !is_null($issue_date_compulsory) )
                                            {!! !is_null($issue_date)?'ทั่วไป: '.HP::DateThai($issue_date):null !!}<br>
                                            {!! !is_null($issue_date_compulsory)?'บังคับ: '.HP::DateThai($issue_date_compulsory):null !!}
                                        @elseif ( !is_null($issue_date) && is_null($issue_date_compulsory) )
                                            {!! !is_null($issue_date)?'ทั่วไป: '.HP::DateThai($issue_date):null !!}
                                        @elseif ( is_null($issue_date) && !is_null($issue_date_compulsory) )
                                            {!! !is_null($issue_date_compulsory)?'บังคับ: '.HP::DateThai($issue_date_compulsory):null !!}
                                        @else
                                            N/A
                                        @endif

                                    </td>
                                    <td>{{ !empty(json_decode($item->refer)[0])?implode(', ', json_decode($item->refer)):'-' }}</td>
                                    <td >{{ $item->StandardFormatName }}</td>

                                    <td>{{ isset($item->product_group) ? $item->product_group->title : '' }}</td>
                                    <td>{{ isset($item->StaffGroupName) ? $item->StaffGroupName : '' }}</td>
                                    <td class="text-center">{{ $item->ReviewStatusName }}</td>
                                    <td>
                                        {{ $item->state=='1'?'ใช้งาน':'ยกเลิก' }}
                                    </td>
                                    <td class="text-center">
                                        @can('view-'.str_slug('standard'))
                                            <a href="{{ url('/tis/standard/download-filezip/' . $item->id) }}"
                                                title="Download PDF" class="btn btn-success btn-xs">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @can('view-'.str_slug('standard'))
                                            <a href="{{ url('/tis/standard/' . $item->id) }}"
                                                title="View standard" class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('edit-'.str_slug('standard')))
                                            <a href="{{ url('/tis/standard/' . $item->id . '/edit') }}"
                                                title="Edit standard" class="btn btn-primary btn-xs" target="_blank">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.str_slug('standard')))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/tis/standard', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete standard',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if( $total_age >= 0 )
                                            @if(!empty($year_age))
                                                @if( $year_age == 'N/A')
                                                    {{ $year_age }}
                                                @else
                                                    <span class="label @if($total_age>=5) label-danger @else label-success @endif">
                                                        {{ $year_age }}
                                                    </span>
                                                @endif

                                            @endif
                                        @endif
                                    </td>

                                </tr>
                              @endforeach --}}
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {{-- {!!
                              $standard->appends([
                                                  'search' => Request::get('search'),
                                                  'sort' => Request::get('sort'),
                                                  'direction' => Request::get('direction'),
                                                  'perPage' => Request::get('perPage'),
                                                  'filter_search' => Request::get('filter_search'),
                                                  'filter_state' => Request::get('filter_state'),
                                                  'filter_number_book_year' => Request::get('filter_number_book_year'),
                                                  'filter_publish_date_start' => Request::get('filter_publish_date_start'),
                                                  'filter_publish_date_end' => Request::get('filter_publish_date_end'),
                                                  'filter_refer' => Request::get('filter_refer'),
                                                  'filter_set_format' => Request::get('filter_set_format'),
                                                  'filter_review_status' => Request::get('filter_review_status'),
                                                  'filter_product_group' => Request::get('filter_product_group'),
                                                  'filter_board_type' => Request::get('filter_board_type'),
                                                  'filter_staff_group' => Request::get('filter_staff_group'),
                                                  'filter_staff_responsible' => Request::get('filter_staff_responsible'),
                                                  'filter_gazette' => Request::get('filter_gazette')
                                                ])->render()
                          !!} --}}

                        </div>
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
                    "url": '{!! url('/tis/standard/data_list') !!}',
                    "dataType": "json",
                    "data": function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_state = $('#filter_state').val();
                        d.filter_publish_date_start = $('#filter_publish_date_start').val();
                        d.filter_publish_date_end = $('#filter_publish_date_end').val();
                        d.filter_refer = $('#filter_refer').val();
                        d.filter_set_format = $('#filter_set_format').val();
                        d.filter_review_status = $('#filter_review_status').val();
                        d.filter_product_group = $('#filter_product_group').val();
                        d.filter_board_type = $('#filter_board_type').val();
                        d.filter_staff_group = $('#filter_staff_group').val();
                        d.filter_staff_responsible = $('#filter_staff_responsible').val();
                        d.filter_gazette = $('#filter_gazette').val();

                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tis_no', name: 'tis_no' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'IsoCodeName', name: 'IsoCodeName' },
                    { data: 'issue_date', name: 'issue_date' },
                    { data: 'refer', name: 'refer' },
                    { data: 'StandardFormatName', name: 'StandardFormatName' },
                    { data: 'product_group', name: 'product_group' },
                    { data: 'StaffGroupName', name: 'StaffGroupName' },
                    { data: 'ReviewStatusName', name: 'ReviewStatusName' },
                    { data: 'state', name: 'state' },
                    { data: 'action', name: 'action' },
                    { data: 'total_age', name: 'total_age' },
                ],
                columnDefs: [
                    { className: "text-center", targets: [0,-1,-2] },
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
                    data.search.filter_publish_date_start = $('#filter_publish_date_start').val();
                    data.search.filter_publish_date_end = $('#filter_publish_date_end').val();
                    data.search.filter_refer = $('#filter_refer').val();
                    data.search.filter_set_format = $('#filter_set_format').val();
                    data.search.filter_review_status = $('#filter_review_status').val();
                    data.search.filter_product_group = $('#filter_product_group').val();
                    data.search.filter_board_type = $('#filter_board_type').val();
                    data.search.filter_staff_group = $('#filter_staff_group').val();
                    data.search.filter_staff_responsible = $('#filter_staff_responsible').val();
                    data.search.filter_gazette = $('#filter_gazette').val();
                },
                stateLoadParams: function (settings, data) {
                    $('#filter_search').val(data.search.filter_search);
                    $('#filter_state').val(data.search.filter_state).trigger('change.select2');

                    $('#filter_publish_date_start').val(data.search.filter_publish_date_start);
                    $('#filter_publish_date_end').val(data.search.filter_publish_date_end);
                    $('#filter_refer').val(data.search.filter_refer);
                    $('#filter_set_format').val(data.search.filter_set_format);

                    $('#filter_review_status').val(data.search.filter_review_status);
                    $('#filter_product_group').val(data.search.filter_product_group);

                    $('#filter_board_type').val(data.search.filter_board_type);
                    $('#filter_staff_group').val(data.search.filter_staff_group);
                    $('#filter_staff_responsible').val(data.search.filter_staff_responsible);
                    $('#filter_gazette').val(data.search.filter_gazette);

                }
            });

            $( "#filter_clear" ).click(function() {

                $('#filter_search').val('');
                $('#filter_state').val('').select2();
                // $('#filter_number_book_year').val('');
                $('#filter_publish_date_start').val('');
                $('#filter_publish_date_end').val('');
                $('#filter_staff_responsible').val('');
                $('#filter_refer').val('');
                $('#filter_set_format').val('').select2();
                $('#filter_review_status').val('').select2();
                $('#filter_product_group').val('').select2();
                $('#filter_board_type').val('').select2();
                $('#filter_staff_group').val('').select2();
                $('#filter_gazette').val('').select2();
                table.draw();
                // window.location.assign("{{url('/tis/standard')}}");
            });

            if($('#filter_publish_date_start').val()!="" ||
                $('#filter_publish_date_end').val()!="" || $('#filter_staff_responsible').val()!="" ||
                $('#filter_refer').val()!="" || $('#filter_set_format').val()!="" ||
                $('#filter_review_status').val()!="" || $('#filter_product_group').select2('data').length>0 ||
                $('#filter_board_type').select2('data').length>0 || $('#filter_staff_group').select2('data').length>0 || $('#filter_gazette').val()!=""
            ){
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }

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

            // $('#filter_publish_date_start').datepicker().on('changeDate', function (ev) {
            //     $('#myFilter').submit();
            // });

            // $('#filter_publish_date_start').change( function () {
            //     if($(this).val()==''){
            //         $('#myFilter').submit();
            //     }
            // });

            // $('#filter_publish_date_end').datepicker().on('changeDate', function (ev) {
            //     $('#myFilter').submit();
            // });

            // $('#filter_publish_date_end').change( function () {
            //     if($(this).val()==''){
            //         $('#myFilter').submit();
            //     }
            // });

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

        function Delete(){

            if($('#myTable').find('input.item_checkbox:checked').length > 0){//ถ้าเลือกแล้ว
                if(confirm_delete()){
                $('#myTable').find('input.item_checkbox:checked').appendTo("#myForm");
                $('#myForm').submit();
                }
            }else{//ยังไม่ได้เลือก
                alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
            }

        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function UpdateState(state){

            if($('#myTable').find('input.item_checkbox:checked').length > 0){//ถ้าเลือกแล้ว
                $('#myTable').find('input.item_checkbox:checked').appendTo("#myFormState");
                $('#state').val(state);
                $('#myFormState').submit();
            }else{//ยังไม่ได้เลือก
                if(state=='1'){
                alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
                }else{
                alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
                }
            }

        }

    </script>

@endpush
