@extends('layouts.master')

@push('css')

<style>

  .label-filter{
    margin-top: 7px;
  }
  /*
	Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
	*/
	@media
	  only screen
    and (max-width: 760px), (min-device-width: 768px)
    and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
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
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		td:before {
			/* Now like a table header */
			/*position: absolute;*/
			/* Top/left values mimic padding */
			top: 0;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}

		/*
		Label the data
    You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
		*/
		/*td:nth-of-type(1):before { content: "Column Name"; }*/

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ประวัติการเรียกใช้งานเว็บเซอร์วิสออกไประบบอื่นๆ</h3>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/ws/moi_log', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">
                            <div class="col-md-5 form-group">
                                  {!! Form::label('search', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                  <div class="col-md-10">
                                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจาก เลขผู้เสียภาษีและ IP เครื่องที่ใช้']); !!}
                                  </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group pull-left">
                                    <button type="submit" class="btn btn-success pull-right">ค้นหา</button>
                                </div>

                                <div class="form-group pull-left m-l-15">
                                    <button type="button" class="btn btn-danger" id="btn-clear">ล้าง</button>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group pull-left">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" href="#advance-box" data-toggle="collapse" id="advance-btn">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-down"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-3 form-group">
    							  {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
    							  <div class="col-md-9">
    									{!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
    							  </div>
    						</div>
                        </div>

                        <div id="advance-box" class="panel-collapse collapse row">
                            <div class="white-box" style="display:flow-root;">

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        {!! Form::label('destination_type', 'ประเภทข้อมูล:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                        <div class="col-md-10 p-l-10">
                                            {!! Form::select('destination_type', App\Models\WS\MOILog::destination_types(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภทข้อมูล-']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        {!! Form::label('data_status', 'สถานะข้อมูล:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('data_status', ['1' => 'พบข้อมูล', '2' => 'ไม่ได้ข้อมูล', '3' => 'เชื่อมต่อไม่ได้'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะข้อมูล-']); !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        {!! Form::label('source_site', 'ไซต์ที่ใช้:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('source_site', ['sso.tisi.go.th' => 'sso.tisi.go.th', 'center.tisi.go.th' => 'center.tisi.go.th'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกไซต์ที่ใช้-']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-8 form-group">
                                        {!! Form::label('request_date_start', 'วันที่เรียกใช้:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            <div class="input-daterange input-group date-range">
                                                <div class="input-group">
                                                    {!! Form::text('request_date_start', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                                <label class="input-group-addon bg-white b-0 control-label">ถึง</label>
                                                <div class="input-group">
                                                    {!! Form::text('request_date_end', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        <table class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@sortablelink('source_url', 'ไซต์ที่ใช้')</th>
                                    <th>@sortablelink('input_number', 'เลขผู้เสียภาษี')</th>
    								<th>@sortablelink('destination_type', 'ประเภทข้อมูล')</th>
    							    <th>@sortablelink('client_ip', 'IP เครื่องที่ใช้')</th>
                                    <th>@sortablelink('request_start', 'เวลาเรียกใช้')</th>
    								<th>@sortablelink('response_http', 'สถานะ/เวลาสิ้นสุด')</th>
    								<th>@sortablelink('response_http', 'สถานะข้อมูล')</th>
    								<th>@sortablelink('detail', 'รายละเอียด')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log as $item)
                                    <tr>
                                        <td class="text-top">{{ $loop->iteration }}</td>
                                        <td class="text-top">
                                            @php
                                                $source_urls = parse_url($item->source_url);
                                            @endphp
                                            {{ $source_urls['host'] }}
                                        </td>
                                        <td class="text-top">{{ $item->input_number }}</td>
                                        <td class="text-top">{{ $item->DestinationTypeText }}</td>
                                        <td class="text-top">{{ $item->client_ip }}</td>
                                        <td class="text-top">{{ HP::DateTimeThaiAndTime($item->request_start) }}</td>
                                        <td class="text-top">
                                            {!! $item->ResponseHttpHtml !!}
                                            {{ HP::DateTimeThaiAndTime($item->request_end) }}
                                        </td>
                                        <td class="text-top text-center">
                                            
                                            @php
                                                if (!empty($item->response_http) && empty($item->response_error)){
                                                    $data_status = '<span class="label label-success">พบข้อมูล</span>';
                                                }elseif(!empty($item->response_http) && !empty($item->response_error)){
                                                    $data_status = '<span class="label label-warning">ไม่ได้ข้อมูล</span>';
                                                }else{
                                                    $data_status = '<span class="label label-danger">เชื่อมต่อไม่ได้</span>';
                                                }
                                            @endphp

                                            {!! $data_status !!}
                                        </td>
                                        <td class="text-top text-center">
                                            <button title="ดูรายละเอียด" 
                                                    class="btn btn-info btn-xs btn-detail"
                                                    data-source_site="{{ $source_urls['host'] }}"
                                                    data-source_url="{{ $item->source_url }}"
                                                    data-input_number="{{ $item->input_number }}"
                                                    data-destination_type="{{ $item->DestinationTypeText }}"
                                                    data-destination_url="{{ $item->destination_url }}"
                                                    data-client_ip="{{ $item->client_ip }}"
                                                    data-request_start="{{ HP::DateTimeThaiAndTime($item->request_start) }}"
                                                    data-response_http="{{ $item->ResponseHttpHtml }}"
                                                    data-request_end="{{ HP::DateTimeThaiAndTime($item->request_end) }}"
                                                    data-data_status="{{ $data_status }}"
                                                    data-response_error="{{ $item->response_error }}"
                                                    >
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $log->appends($filter)->render()
                          !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('ws.moi_log.modal.detail')

@endsection

@push('js')

    <script>

        $(document).ready(function() {

            $('#btn-clear').click(function(event) {
                $('#myFilter').find('input').val('');
                $('#myFilter').find('select').val('');
                $('#myFilter').submit();
            });

            //เมื่อแสดง ค้นหาชั้นสูง
            $('#advance-box').on('show.bs.collapse', function () {
                $("#advance-btn").addClass('btn-success').removeClass('btn-primary');
                $("#advance-btn > span").addClass('glyphicon-menu-up').removeClass('glyphicon-menu-down');
            });

            //เมื่อซ่อน ค้นหาชั้นสูง
            $('#advance-box').on('hidden.bs.collapse', function () {
                $("#advance-btn").addClass('btn-primary').removeClass('btn-success');
                $("#advance-btn > span").addClass('glyphicon-menu-down').removeClass('glyphicon-menu-up');
            });

            //เซตค่าแสดง/ซ่อน ค้นหาชั้นสูง ตอนโหลด
            $('#advance-box').find('select, input').each(function(index, el) {
                if($(el).val()!=''){
                    $('#advance-box').collapse('show');
                    return false;
                }
            });

            /* ไอคอน */
            function format(option) {
                if (!option.id) return option.text; // optgroup
                // return "<span class=\"label label-success\">"+option.id+"</span> " + option.text;
                return option.text;
            }

            $("#status").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });

            $('.btn-detail').click(function () { 
                $('#detailModal').modal('show');

                //ใส่รายละเอียด
                $('.source_site').html($(this).data('source_site'));
                $('.source_url').html($(this).data('source_url'));
                $('.input_number').html($(this).data('input_number'));
                $('.destination_type').html($(this).data('destination_type'));
                $('.destination_url').html($(this).data('destination_url'));
                $('.client_ip').html($(this).data('client_ip'));
                $('.request_start').html($(this).data('request_start'));
                $('.response_http').html($(this).data('response_http'));
                $('.request_end').html($(this).data('request_end'));
                $('.data_status').html($(this).data('data_status'));

                let response_error = $(this).data('response_error') !== "" ? '<pre>'+JSON.stringify($(this).data('response_error'), undefined, 2)+'</pre>' : null ;
                $('.response_error').html(response_error);

            });

        });

    </script>

@endpush
