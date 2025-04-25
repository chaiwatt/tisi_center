@extends('layouts.master')
@push('css')
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
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

        .modal-header {
            padding: 9px 15px;
            border-bottom: 1px solid #eee;
            background-color: #317CC1;
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

        .wrapper-detail {
            border: solid 1px silver;
            margin-left: 20px;
            margin-right: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        legend {
            width: auto; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
            font-size: 14px;
        }

        fieldset {
            padding: 20px;
        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบรับแจ้งปริมาณการนำเข้าเพื่อนำเข้ามาใช้เอง (21)</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <fieldset class="row ">
                        <div style="display: flex; flex-direction: column;" class="white-box">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right"> เลขที่คำขออ้างอิง</div>
                                    <div class="col-sm-8 ">
                                        <input type="text" class="form-control" disabled
                                               value="{{HP::get_ref_no2_6($data->applicant_21own_id)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right">ชื่อผลิตภัณฑ์</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" disabled
                                               value="{{HP::get_title2_6($data->applicant_21own_id)}}">
                                    </div>
                                    <div class="col-sm-2 text-right">ระยะเวลาที่แจ้งผลิต</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" disabled
                                               value="{{ HP::DateThai(HP::get_date_start2_6($data->applicant_21own_id)) }} - {{ HP::DateThai(HP::get_date_end2_6($data->applicant_21own_id)) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right"> วันที่ผลิต</div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input value="{{$data->start_date}}" type="text" class="form-control datepicker"
                                                   id="datepicker-time" disabled/>
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 text-right"> ถึง</div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input value="{{$data->end_date}}" type="text" class="form-control datepicker"
                                                   id="datepicker-time2" disabled/>
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 2%;">รายการที่</th>
                                        <th style="width: 15%;">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                        <th style="width: 4%;">รวมปริมาณการผลิต</th>
                                        <th style="width: 8%;">ปริมาณการผลิต</th>
                                        <th style="width: 2%;">หน่วย</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $row1 = 1;
                                    ?>
                                    @foreach($data_detail as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td><input type="text" class="form-control text-center" disabled
                                                       value="{{HP::get_detail2_6($item->detail_id)}}"></td>
                                            <td><input type="hidden" id="table1_{{$row1}}"
                                                       class="form-control text-center" disabled>
                                                    <input type="text" class="form-control text-center" value="{{$item->quantity_old}}" disabled>
                                                    </td>
                                            <td>
                                                <div class="col-md-4 text-right"><input type="checkbox" checked disabled>
                                                    <label>ผลิต</label></div>
                                                <div class="col-md-8"><input type="text"
                                                                             class="form-control text-center"
                                                                             value="{{$item->quantity}}"
                                                                             id="table11_{{$row1++}}"
                                                                             disabled></div>
                                            </td>
                                            <td> {{HP::get_unit_name(HP::get_unit2_6($item->detail_id))}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($attachs!=null)
                                <div id="other_attach_box">
                                    @foreach ($attachs as $key => $attach)
                                        <div class="row form-group">
                                            <div class="other_attach_item">

                                                @if($key==0)
                                                    <div class="col-md-2 text-right">ไฟล์แนบ</div>
                                                @else
                                                    <div class="col-md-2"></div>
                                                @endif
                                                <div class="col-md-3">
                                                    <div class="fileinput fileinput-new input-group pull-left col-md-10" data-provides="fileinput">
                                                        <div >
                                                            {{-- <a href="{{url('/asurv/report21own_import/download/'.$attach->file_name)}}">{{$attach->file_client_name}}</a> --}}
                                                            @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" >
                                                                    {{$attach->file_client_name}}
                                                                </a>
                                                           @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    {{-- <a href="{{url('/asurv/report21own_import/preview/'.$attach->file_name)}}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right">ขอปิดการแจ้งปริมาณ</div>
                                    <div class="col-sm-3">
                                        @if($data->inform_close == '0')
                                            <input type="checkbox" class="col-md-2" checked disabled>
                                        @else
                                            <input type="checkbox" class="col-md-2" disabled>
                                        @endif
                                        <label>ไม่ปิด</label>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right"></div>
                                    <div class="col-sm-3">
                                        @if($data->inform_close == '1')
                                            <input type="checkbox" class="col-md-2" checked disabled>
                                        @else
                                            <input type="checkbox" class="col-md-2" disabled>
                                        @endif
                                        <label>ปิดการแจ้งปริมาณ เพราะ</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" disabled
                                               value="{{$data->because_close}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right">ชื่อผู้บันทึก</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" disabled
                                               value="{{$data->applicant_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right">เบอร์โทร</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" disabled
                                               value="{{$data->tel}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div class="col-sm-2 text-right">E-mail</div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" disabled
                                               value="{{$data->email}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row form-group">
                        <div class="col-md-12" id="">
                            <fieldset style="border: solid 0.1em #e5ebec; border-radius: 4px" class="fieldset-cus">
                                <legend><h4>สรุปปริมาณการแจ้ง</h4></legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table color-bordered-table primary-bordered-table"
                                                   id="myTable">
                                                <thead>
                                                <tr>
                                                    <th style="width: 2%;">รายการที่</th>
                                                    <th style="width: 15%;">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                                    <th style="width: 8%;">ปริมาณที่ขอผลิต</th>
                                                    @foreach($data_volume_main as $list)
                                                        <th style="width: 4%;">แจ้งครั้งที่ {{$loop->iteration}}
                                                            <div></div>
                                                            ({{ HP::DateThai($list->created_at) }})
                                                        </th>
                                                    @endforeach
                                                    <th style="width: 4%;">รวม</th>
                                                    <th style="width: 2%;">หน่วย</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $a = 0;
                                                $b = array();
                                                $c = array();
                                                ?>
                                                @foreach ($data_volume_detail as $list)
                                                    <input hidden id="{{$list->detail_id}}_{{$list->volume_21own_id}}" value="{{$list->quantity}}">
                                                    <?php
                                                    $a++;
                                                    ?>
                                                    <input hidden value="{{$b[] = $list->detail_id}}">
                                                    <input hidden value="{{$c[] = $list->volume_21own_id}}">
                                                @endforeach
                                                <?php
                                                $i = 0;
                                                $k = 0;
                                                $sum = 0;
                                                $row = 1;
                                                $row_sub = 1;
                                                ?>
                                                @foreach($data_detail as $key => $item)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td><input type="text" class="form-control text-center"
                                                                   disabled value="{{HP::get_detail2_6($item->detail_id)}}"></td>
                                                        <td><input type="text" class="form-control text-center"
                                                                   value="{{HP::get_quantity2_6($item->detail_id)}}" disabled></td>
                                                        @foreach($data_volume_main as $key => $list)
                                                            <td>
                                                                <input type="text"
                                                                       class="form-control text-center"
                                                                       id="table2_Q_{{$item->detail_id}}_{{$data_detail_ck[$key]->volume_21own_id}}"
                                                                       disabled
                                                                       value="0">
                                                            </td>
                                                        @endforeach
                                                        <td><input type="text" id="table2_{{$row++}}"
                                                                   class="form-control text-center"
                                                                   disabled value="{{HP::get_sum_quantity2_6($data->applicant_21own_id,$item->detail_id)}}">
                                                        </td>
                                                        <td><label>{{HP::get_unit_name(HP::get_unit2_6($item->detail_id))}}</label></td>
                                                    </tr>
                                                    <input value="{{$i++}}" hidden>
                                                    <input value="{{$sum=0}}" hidden>
                                                    <input value="{{$k=0}}" hidden>
                                                    <input value="{{$row_sub=1}}" hidden>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    @if($data->inform_close == '1')
                        <form id="form_data" method="post" enctype="multipart/form-data">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <input value="{{$data->id}}" name="id" hidden>

                            <div class="row form-group">
                                <div class="col-md-12" id="">
                                    <fieldset style="border: solid 0.1em #e5ebec; border-radius: 4px" class="fieldset-cus">
                                        <legend><h4>ผลการพิจารณา</h4></legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <div class="col-sm-4 control-label text-right"> สถานะ :</div>
                                                    <div class="col-sm-6 m-b-10">
                                                        @php
                                                            $selected_option = 'selected=selected';
                                                        @endphp
                                                        <select class=" form-control" style="text-align: -webkit-center;"
                                                                name="state_notify_report" id="state_notify_report" {{ ($data->state_notify_report === 1)?'disabled':'' }}>
                                                            <option value="" {{ ($data->state_notify_report === "")?$selected_option:'' }}>-เลือกสถานะ-</option>
                                                            <option value="0" {{ ($data->state_notify_report === 0)?$selected_option:'' }}> ไม่อนุมัติ</option>
                                                            <option value="1" {{ ($data->state_notify_report === 1)?$selected_option:'' }}> อนุมัติ</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group ">
                                                    <div class="col-sm-4 control-label text-right"> ความคิดเห็นเพิ่มเติม :
                                                    </div>
                                                    <div class="col-sm-6 m-b-10">
                                                    <textarea name="remark_officer_report" rows="4" cols="50"
                                                              class="form-control">{{$data->remark_officer_report}} </textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group ">
                                                    <div class="col-sm-4 control-label" align="right"> ผู้พิจารณา :</div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" type="text" disabled
                                                               value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"/>
                                                        <input name="officer_report" hidden
                                                               value="{{auth()->user()->runrecno}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="col-sm-12" style="margin-bottom: 5px;"></div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-info btn-sm waves-effect waves-light"
                                                type="submit">บันทึก
                                        </button>
                                        <a class="btn btn-default btn-sm waves-effect waves-light"
                                           href="{{ url('/asurv/report21own_import') }}">
                                            <i class="fa fa-undo"></i><b> ยกเลิก</b>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="row form-group">
                            <div class="col-md-12" id="">
                                <div class="col-sm-12" style="margin-bottom: 5px;"></div>
                                <div class="form-group text-center">
                                    <a class="btn btn-default btn-sm waves-effect waves-light"
                                       href="{{ url('/asurv/report21own_import') }}">
                                        <i class="fa fa-undo"></i><b> กลับ</b>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>

    $(document).ready(function(){
        // setTimeout(function(){
        $('select#state_notify_report').select2().select2("val", '<?= $data->state_notify_report ?>');
        // $('select#signer_id').select2().select2("val", '<?= $data->signer_id ?>');
        //         $('#signer_id').change(function(){
        //             var signer_id = $(this).val();
        //             if(signer_id){
        //                 var url = '{{ url('/asurv/report_export/get_signer_position') }}/'+signer_id;
        //                 $.ajax({
        //                     'type': 'GET',
        //                     'url': url,
        //                     'success': function (data) {
        //                         console.log(data);
        //                         $('#signer_name').val(data.name);
        //                         $('#signer_position').html(data.position);
        //                     }
        //                 });
        //             }
        //         });
        //  }, 1000);

	});


    $('.datepicker').datepicker({
        language:'th-th',
        format:'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
	});
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            // if($('#signer_id').val()=="" || $('#signer_position').html()==""){
            //         return false;
            //     }
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/asurv/report21own_import/save')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/asurv/report21own_import')}}"
                    } else if (data.status == "error") {
                        // $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ' + data.message + ' <br></div>');
                        alert(data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });

        });
        if ('<?= $row ?>' != 0) {
            var count_row = '<?php echo $row;?>';
            for (var i = 1; i < count_row; i++) {
                document.getElementById('table1_' + i).value = document.getElementById('table2_' + i).value - document.getElementById('table11_' + i).value
            }
        }
        if ('<?= $a ?>' != 0) {
            var count_row_a = '<?php echo $a;?>';
            var count_row_b = new Array();
            var count_row_c = new Array();
            <?php foreach($b as $key => $val){ ?>
            count_row_b.push('<?php echo $val; ?>');
            <?php } ?>
            <?php foreach($c as $key => $val){ ?>
            count_row_c.push('<?php echo $val; ?>');
                <?php } ?>

            for (var i = 0; i < count_row_a; i++) {
                document.getElementById('table2_Q_' +count_row_b[i]+'_'+count_row_c[i]).value = document.getElementById(count_row_b[i]+'_'+count_row_c[i]).value
            }
        }

    </script>
@endpush
