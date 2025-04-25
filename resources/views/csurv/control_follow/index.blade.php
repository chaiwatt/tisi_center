@extends('layouts.master')

@push('css')

    <style>

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #FFF2CC;
        }

        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px) {

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
        fieldset {
            padding: 20px;
        }

    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบการทำแผนตรวจติดตาม</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <div class="pull-right">
                        @can('add-'.str_slug('control_follow'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/csurv/control_follow/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('control_follow'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan
                    </div>

                    <div class="clearfix"></div>

                    <fieldset class="row">
                        <div class="white-box">
                            <div class="form-group">
                                {!! Form::model($filter, ['url' => '/csurv/control_follow', 'method' => 'get', 'id' => 'myFilter']) !!}
                                <div class="col-md-3" style="margin-bottom: 20px">
                                    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    {!! Form::label('filter_year', 'ทำแผนประจำปี:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                    <div class="col-md-6">
                                        <select class="form-control" name="filter_year" onchange="this.form.submit();">
                                            <option>-เลือกปี-</option>
                                            @foreach(HP::YearList() as $list)
                                                <option>{{$list}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>

                            <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th style="width: 6%;">No.</th>
                                    <th style="width: 40%;">รายละเอียด</th>
                                    <th style="width: 8%;">วันที่บันทึก</th>
                                    <th style="width: 8%;">ผู้บันทึก</th>
                                    <th style="width: 8%;">เครื่องมือ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                ?>
                                @foreach($control_follow as $item)
                                    <tr>
                                        <td>{{$temp_num++}}.</td>
                                        <td>ทำแผนการตรวจติดตามประจำปีพ.ศ. {{$item->make_annual}} </td>
                                        <td>{{HP::DateThai($item->created_at)}}</td>
                                        <td>{{$item->check_officer}}</td>
                                        <td>
                                            @can('edit-'.str_slug('control_follow'))
                                                <a href="{{url("/csurv/control_follow/$item->id/edit")}}"
                                                class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                </a>
                                            @endcan
                                            @can('delete-'.str_slug('control_follow'))
                                                <button class="btn btn-danger btn-xs" onclick="remove_data({{$item->id}});">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </td>
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
                                    $control_follow->appends($page)->links()
                                !!}
                            </div>
                        </div>
                    </fieldset>

                </div>

            </div>
        </div>
    </div>
    </div>
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        function remove_data(id) {
            if (confirm('ต้องการลบรายการนี้?')) {
                window.location.href = "{{url('/csurv/control_follow/del')}}" + '/' + id
            }
        }
    </script>

@endpush
