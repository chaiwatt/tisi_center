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



        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: rgb(253,242,208);
        }


        /* for buttton */


        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }



        .onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-inner {
            margin-left: 0;
        }

        .onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-switch {
            right: 0px;
        }



        .onoffswitch2-checkbox:checked + .onoffswitch2-label .onoffswitch2-inner {
            margin-left: 0;
        }

        .onoffswitch2-checkbox:checked + .onoffswitch2-label .onoffswitch2-switch {
            right: 0px;
        }



        .onoffswitch3-inner > span {
            display: block; float: left; position: relative; width: 50%; height: 30px; padding: 0; line-height: 30px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
        }

        .onoffswitch3-inner .onoffswitch3-active {
            padding-left: 10px;
            background-color: #EEEEEE; color: #FFFFFF;
        }

        .onoffswitch3-inner .onoffswitch3-inactive {
            padding-right: 10px;
            background-color: #EEEEEE; color: #FFFFFF;
            text-align: right;
        }


        .onoffswitch3-active .onoffswitch3-switch {
            background: #27A1CA; left: 0;
        }
        .onoffswitch3-inactive .onoffswitch3-switch {
            background: #A1A1A1; right: 0;
        }

        .onoffswitch3-active .onoffswitch3-switch:before {
            content: " "; position: absolute; top: 0; left: 18px;
            border-style: solid; border-color: #27A1CA transparent transparent #27A1CA; border-width: 15px 9px;
        }


        .onoffswitch3-inactive .onoffswitch3-switch:before {
            content: " "; position: absolute; top: 0; right: 18px;
            border-style: solid; border-color: transparent #A1A1A1 #A1A1A1 transparent; border-width: 15px 9px;
        }


        .onoffswitch3-checkbox:checked + .onoffswitch3-label .onoffswitch3-inner {
            margin-left: 0;
        }


        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-inner {
            margin-left: 0;
        }

        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-switch {
            right: 0px;
        }



        .cmn-toggle
        {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }

        .cmn-toggle + label
        {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        input.cmn-toggle-round-flat + label
        {
            padding: 2px;
            width: 75px;
            height: 30px;
            background-color: #dddddd;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:before, input.cmn-toggle-round-flat + label:after
        {
            display: block;
            position: absolute;
            content: "";
        }

        input.cmn-toggle-round-flat + label:before
        {
            top: 2px;
            left: 2px;
            bottom: 2px;
            right: 2px;
            background-color: #fff;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:after
        {
            top: 4px;
            left: 4px;
            bottom: 4px;
            width: 22px;
            background-color: #dddddd;
            -webkit-border-radius: 52px;
            -moz-border-radius: 52px;
            -ms-border-radius: 52px;
            -o-border-radius: 52px;
            border-radius: 52px;
            -webkit-transition: margin 0.4s, background 0.4s;
            -moz-transition: margin 0.4s, background 0.4s;
            -o-transition: margin 0.4s, background 0.4s;
            transition: margin 0.4s, background 0.4s;
        }

        input.cmn-toggle-round-flat:checked + label
        {
            background-color: #27A1CA;
        }

        input.cmn-toggle-round-flat:checked + label:after
        {
            margin-left: 45px;
            background-color: #27A1CA;
        }

        div.switch5 { clear: both; margin: 0px 0px; }
        div.switch5 > input.switch:empty { margin-left: -999px; }
        div.switch5 > input.switch:empty ~ label { position: relative; float: left; line-height: 1.6em; text-indent: 4em; margin: 0.2em 0px; cursor: pointer; -moz-user-select: none; }
        div.switch5 > input.switch:empty ~ label:before, input.switch:empty ~ label:after { position: absolute; display: block; top: 0px; bottom: 0px; left: 0px; content: "off"; width: 3.6em; height: 1.5em; text-indent: 2.4em; color: rgb(153, 0, 0); background-color: rgb(204, 51, 51); border-radius: 0.3em; box-shadow: 0px 0.2em 0px rgba(0, 0, 0, 0.3) inset; }
        div.switch5 > input.switch:empty ~ label:after { content: " "; width: 1.4em; height: 1.5em; top: 0.1em; bottom: 0.1em; text-align: center; text-indent: 0px; margin-left: 0.1em; color: rgb(255, 136, 136); background-color: rgb(255, 255, 255); border-radius: 0.15em; box-shadow: 0px -0.2em 0px rgba(0, 0, 0, 0.2) inset; transition: all 100ms ease-in 0s; }
        div.switch5 > input.switch:checked ~ label:before { content: "on"; text-indent: 0.5em; color: rgb(102, 255, 102); background-color: rgb(51, 153, 51); }
        div.switch5 > input.switch:checked ~ label:after { margin-left: 2.1em; color: rgb(102, 204, 102); }
        div.switch5 > input.switch:focus ~ label { color: rgb(0, 0, 0); }
        div.switch5 > input.switch:focus ~ label:before { box-shadow: 0px 0px 0px 3px rgb(153, 153, 153); }







        .switch6 {  max-width: 17em;  margin: 0 auto; }
        .switch6-light > span, .switch-toggle > span {  color: #000000; }
        .switch6-light span span, .switch6-light label, .switch-toggle span span, .switch-toggle label {  color: #2b2b2b; }

        .switch-toggle a,
        .switch6-light span span { display: none; }

        .switch6-light { display: block; height: 30px; position: relative; overflow: visible; padding: 0px; margin-left:0px; }
        .switch6-light * { box-sizing: border-box; }
        .switch6-light a { display: block; transition: all 0.3s ease-out 0s; }

        .switch6-light label,
        .switch6-light > span { line-height: 30px; vertical-align: middle;}

        .switch6-light label {font-weight: 700; margin-bottom: px; max-width: 100%;}

        .switch6-light input:focus ~ a, .switch6-light input:focus + label { outline: 1px dotted rgb(136, 136, 136); }
        .switch6-light input { position: absolute; opacity: 0; z-index: 5; }
        .switch6-light input:checked ~ a { right: 0%; }
        .switch6-light > span { position: absolute; left: -100px; width: 100%; margin: 0px; padding-right: 100px; text-align: left; }
        .switch6-light > span span { position: absolute; top: 0px; left: 0px; z-index: 5; display: block; width: 50%; margin-left: 100px; text-align: center; }
        .switch6-light > span span:last-child { left: 50%; }
        .switch6-light a { position: absolute; right: 50%; top: 0px; z-index: 4; display: block; width: 50%; height: 100%; padding: 0px; }





    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="class=col-sm-12">
            <div class="white-box">
                <h3 class="box-title pull-left">ข้อมูลผู้ตรวจประเมิน</h3>
                <div class="pull-right">
                    <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ route('bcertify.auditor.create') }}">
                        <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                    </a>
                </div>

                <div class="clearfix"></div>
                <hr>

                <div>
                    <div class="col-md-12" >

                        {!! Form::model($filter, ['url' => 'bcertify/auditor', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="col-md-4">
                            {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-9">
                                {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>


                        <div class="col-md-4 ">
                            {!! Form::label('filter_search', 'search', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-9">
                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'search', 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('filter_department', 'หน่วยงาน:', ['class' => 'col-md-3 control-label label-filter text-nowrap']) !!}
                            <div class="col-md-9">
                                {!! Form::select('filter_department', $departments,
                                  null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกหน่วยงาน-', 'data-name'=>'status', 'required'=>true , 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>

                        <div class="col-md-6 m-t-15">
                            {!! Form::label('filter_formulas', 'มาตราฐานเชี่ยวชาญ:', ['class' => 'col-md-3 control-label label-filter text-nowrap']) !!}
                            <div class="col-md-9">
                                {!! Form::select('filter_formulas', $formulas,
                                  null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกมาตราฐานเชี่ยวชาญ-', 'data-name'=>'status', 'required'=>true , 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>

                        <div class="col-md-6 m-t-15">
                            {!! Form::label('filter_status', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-9">
                                {!! Form::select('filter_status', ['0'=>'ปิด', '1'=>'เปิด'], null, ['class' => 'form-control','placeholder'=>'-เลือกสถานะ-', 'onchange'=>'this.form.submit()']); !!}
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>



                    <div class="clearfix"></div>


                    <div class="table-responsive" style="margin-top: 20px">
                        <table class="table table-striped" >
                            <thead>
                            <tr>
                                <th>No. <input type="checkbox" id="checkall"></th>
                                <th>ชื่อ - สกุล</th>
                                <th>หน่วยงาน</th>
                                <th>เบอร์โทร</th>
                                <th width="300px">มาตราฐานที่เชี่ยวชาญ</th>
                                <th>วันที่บันทึก</th>
                                <th>ผู้บันทึก</th>
                                <th>สถานะ</th>
                                <th width="100px">เครื่องมือ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $number = 1 @endphp
                            @foreach($auditors as $auditor)
                            <tr>
                                <td>{{$number}}. <input type="checkbox" id="checkall"></td>
                                <td>{{$auditor->title_th}}{{$auditor->fname_th}} {{$auditor->lname_th}}</td>
                                <td>
                                  {{ !is_null($auditor->department) ? $auditor->department->title : '-' }}
                                </td>
                                <td>{{$auditor->tel}}</td>
                                @php
                                    $standard = array();
                                    $expertises = \App\Models\Bcertify\AuditorExpertise::where('auditor_id',$auditor->id)->get();
                                    foreach ($expertises as $expertise){
                                        if (!in_array($expertise->formula->title,$standard)){
                                            array_push($standard,$expertise->formula->title);
                                        }
                                    }
                                @endphp
                                <td>
                                    {{implode(",",$standard)}}
                                </td>
                                <td>{{\Carbon\Carbon::parse($auditor->created_at)->format('d/m/Y')}}</td>
                                <td>{{@$auditor->user->reg_fname}} {{@$auditor->user->reg_lname}}</td>
                                <td >
                                    @if ($auditor->status == 1)
                                        <a href="{{ route('bcertify.auditor.update',['id'=>$auditor->id]) }}"><i class="mdi mdi-checkbox-marked-circle" style="color: green ; font-size: 20px" data-toggle="tooltip" title="Click to close"></i></a>
                                    @else
                                        <a href="{{ route('bcertify.auditor.update',['id'=>$auditor->id]) }}"><i class="mdi mdi-close-circle" style="color: red ; font-size: 20px"  data-toggle="tooltip" title="Click to open"></i></a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-xs" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}"><i class="fa fa-eye " aria-hidden="true" data-toggle="tooltip" title="Information"></i></a>
                                    <a class="btn btn-primary btn-xs" href="{{route('bcertify.auditor.edit',['token'=>$auditor->token])}}"><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></a>
                                    <a class="btn btn-danger btn-xs" href="{{route('bcertify.auditor.destroy',['token'=>$auditor->token])}}"><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></a>
                                </td>
                            </tr>
                            @php $number++ @endphp
                            @endforeach

                            </tbody>
                        </table>

                        <div class="pull-right">
                            {{$auditors->links()}}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection


@push('js')

    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script>
        $(document).ready(function () {
            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Error!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 3000,
                stack: 6
            });
            @endif


            @if(\Session::has('success_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('success_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        })
    </script>
@endpush
