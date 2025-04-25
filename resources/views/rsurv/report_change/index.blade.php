@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

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
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-weight: bold; font-weight: ;
        }
        .wrapper-label {
            font-size: 26px;
            color: #0a0a0a;
        }
        .wrapper-label-small {
            color: #6c757d;
        }
        .card-collaps {
            border: 1px solid;
            margin-top: 70px;
        }

        th {
            text-align: center;
        }
        td {
            text-align: center;
        }
        .panel .panel-body {
            padding: 10px !important;
        }

    </style>

@endpush

@section('content')

    @php
      $tis_list = HP::TisList();
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="col-sm-12" style="border-bottom: solid 2px royalblue;">
                        <h3 class="pull-left">รายงานแจ้งการเปลี่ยนแปลงที่มีผลกระทบต่อคุณภาพ</h3>
                    </div>

                    <div class="panel-group" id="accordion">
                        <div class="panel card-collaps">
                            <div class="clearfix"></div>
                            {!! Form::model($filter, ['url' => '/rsurv/report_change', 'method' => 'get', 'id' => 'myFilter']) !!}

                            <div class="panel-heading" style="border-bottom: solid 1px silver;">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="font-weight: 200px; font-size: 18px; "> เงื่อนไขการแสดงรายงาน </a>
                                </h4>
                            </div>

                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">

                                    <div class="col-md-12 row">
                                      <div class="col-md-4">
                                          {!! Form::label('filter_created_by', 'ผู้ประกอบการ:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                          <div class="col-md-8">
                                              {!! Form::select('filter_created_by', App\Models\Sso\User::pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ประกอบการ-']); !!}
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          {!! Form::label('filter_tb3_Tisno', 'มอก.:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                          <div class="col-md-9">
                                              {!! Form::select('filter_tb3_Tisno', $tis_list, null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          {!! Form::label('filter_license', 'ใบอนุญาต:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                          <div class="col-md-9">
                                              {!! Form::text('filter_license', null, ['class' => 'form-control', 'placeholder'=>'เลขที่ใบอนุญาต']); !!}
                                          </div>
                                      </div>
                                    </div>

                                    <div class="col-md-12 row">
                                      <div class="col-md-8">
                                        {!! Form::label('filter_start_date', 'วันที่แจ้ง:', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-10" style="padding-left: 10px;">
                                          <div class="input-daterange input-group" id="date-range">
                                            {!! Form::text('filter_start_date', null, ['class' => 'form-control']); !!}
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            {!! Form::text('filter_end_date', null, ['class' => 'form-control']); !!}
                                          </div>
                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                          {!! Form::label('filter_detail', 'รายละเอียด:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                          <div class="col-md-9">
                                            {!! Form::text('filter_detail', null, ['class' => 'form-control', 'placeholder'=>'ค้นจากรายละเอียดการเปลี่ยนแปลง']); !!}
                                          </div>
                                      </div>
                                    </div>

                                    <div class="col-md-12 row">
                                      <div class="col-md-12 row">

                                        <div class="col-md-4">
                                            {!! Form::label('perPage', 'Show:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                            </div>
                                        </div>

                                        <div class="pull-right">

                                          <button type="button" onclick="$('#myFilter').prop('action', '{{ url('rsurv/report_change/export_excel') }}'); this.form.submit();" class="btn btn-success waves-effect waves-light" formtarget="_blank">
                                            <i class="mdi mdi-file-excel"></i> Export Excel
                                          </button>

                                          <button type="submit" onclick="$('#myFilter').prop('action', '{{ url('rsurv/report_change') }}'); this.form.submit();" class="btn btn-primary waves-effect waves-light ">
                                            <i class="mdi mdi-search-web"></i> แสดงรายงาน
                                          </button>

                                        </div>

                                      </div>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                            <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                          {!! Form::close() !!}

                        </div>

                        <div class="clearfix"></div>

                        <hr>

                        <div class="wrapper">
                            <label class="wrapper-label">
                                รายงานการแจ้งการเปลี่ยนแปลงที่มีผลกระทบต่อคุณภาพ
                            </label>

                            <label class="wrapper-label-small">
                                @if($filter['filter_created_by']!='')<span> {{ App\Models\Sso\User::find($filter['filter_created_by'])->name }} </span>@endif
                                @if($filter['filter_tb3_Tisno']!='')<span> {{ $tis_list[$filter['filter_tb3_Tisno']] }} </span>@endif
                            </label>

                            <label class="wrapper-label-small">
                                ข้อมูล ณ วันที่ {{ HP::DateTimeFullThai(date('Y-m-d H:i')) }}
                            </label>

                        </div>

                        <hr>

                        <div class="table-responsive">

                            <table class="table color-bordered-table primary-bordered-table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th style="width: 13%;">ผู้ประกอบการ</th>
                                    <th style="width: 23%;">มอก.</th>
                                    <th style="width: 7%;">วันที่แจ้ง</th>
                                    <th style="width: 7%;">ใบอนุญาต</th>
                                    <th style="width: 8%;">รายละเอียดการเปลี่ยนแปลง</th>
                                    <th style="width: 15%;">หมายเหตุ</th>
                                    <th style="width: 7%;">ไฟล์แนบ</th>
                                    <th style="width: 6%;">ผู้บันทึก</th>
                                    <th style="width: 6%;">เบอร์โทร</th>
                                    <th style="width: 6%;">e-Mail</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach ($items as $key => $item)

                                    @php
                                      $attachs = json_decode($item['attach']);
                                      $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
                                    @endphp

                                    <tr>
                                      <td class="text-top">{{ $key+1 }}</td>
                                      <td class="text-top">{{ $item->CreatedName }}</td>
                                      <td class="text-top">{{ $item->tis->tb3_Tisno }} ({{ $item->tis->tb3_TisThainame }})</td>
                                      <td class="text-top">{{ HP::DateThai($item->created_at) }}</td>
                                      <td class="text-top">{{ implode(', ', $item->license_list->pluck('tbl_licenseNo', 'id')->toArray()) }}</td>
                                      <td class="text-top">{{ $item->detail }}</td>
                                      <td class="text-top">{{ $item->remark }}</td>
                                      <td class="text-top">
                                        @foreach ($attachs as $attach)
                                          @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                            <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="btn btn-info btn-sm" style="margin-bottom:2px;">
                                              @if($attach->file_note!='') {{ $attach->file_note }} @else {{ $attach->file_client_name }} @endif
                                            </a>
                                          @endif
                                        @endforeach
                                      </td>
                                      <td class="text-top">{{ $item->applicant_name }}</td>
                                      <td class="text-top">{{ $item->tel }}</td>
                                      <td class="text-top">{{ $item->email }}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>

                            <div class="pagination-wrapper">
                              @php
                                  $page = array_merge($filter, ['sort' => Request::get('sort'),
                                                                'direction' => Request::get('direction'),
                                                                'perPage' => Request::get('perPage')
                                                               ]);
                              @endphp
                              {!!
                                  $items->appends($page)->render()
                              !!}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            $('#date-range').datepicker({
              toggleActive: true,
              format: 'dd/mm/yyyy'
            });

        });

    </script>
@endpush
