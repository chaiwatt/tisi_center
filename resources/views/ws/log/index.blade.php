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
                    <h3 class="box-title pull-left">ประวัติการใช้งานเว็บเซอร์วิส</h3>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/ws/log', 'method' => 'get', 'id' => 'myFilter']) !!}

                        <div class="row">
                            <div class="col-md-5 form-group">
                                  {!! Form::label('search', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                  <div class="col-md-10">
                                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจาก ชื่อผู้ใช้งานหรือที่อยู่ IP']); !!}
                                  </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" href="#advance-box" data-toggle="collapse" id="advance-btn">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-down"></span>
                                    </button>
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
                                        {!! Form::label('api_name', 'ชื่อบริการ:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                        <div class="col-md-10 p-l-10">
                                            @php
                                            $api_lists = collect(HP_API::APILists());
                                            $api_lists = $api_lists->mapWithKeys(function ($item, $key) {
                                                return [$key => $item['detail'].' - ('.$item['name'].')'];
                                            });
                                            @endphp
                                            {!! Form::select('api_name', $api_lists, null, ['class' => 'form-control', 'placeholder'=>'-เลือกชื่อบริการ-']); !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        {!! Form::label('status', 'สถานะ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('status', $status_list, null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        {!! Form::label('app_name', 'ผู้ใช้งาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('app_name', $clients, null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ใช้งาน-']); !!}
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
                                    <th>@sortablelink('client_title', 'ชื่อผู้ใช้งาน/app-name')</th>
    								<th>@sortablelink('api_name', 'ชื่อบริการ')</th>
    							    <th>@sortablelink('ip', 'ที่อยู่ IP')</th>
    								<th>@sortablelink('status', 'สถานะ')</th>
    								<th>@sortablelink('message', 'ข้อความตอบกลับ')</th>
                                    <th>@sortablelink('request_time', 'วันเวลาเรียกใช้')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log as $item)
                                    <tr>
                                        <td class="text-top">{{ $loop->iteration }}</td>
                                        <td class="text-top">
                                            {{ $item->client_title }}
                                            <div class="text-muted">{{ $item->app_name }}</div>
                                        </td>
                                        <td class="text-top">{{ $item->api_name }}</td>
                                        <td class="text-top">{{ $item->ip }}</td>
                                        <td class="text-top">

                                            <span class="label label-{{ array_key_exists($item->status, $status_css) ? $status_css[$item->status] : 'info' }}">{{ $item->status }}</span>
                                        </td>
                                        <td class="text-top">{{ $item->message }}</td>
                                        <td class="text-top">{{ HP::DateTimeFullThai($item->request_time) }}</td>
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

        });

    </script>

@endpush
