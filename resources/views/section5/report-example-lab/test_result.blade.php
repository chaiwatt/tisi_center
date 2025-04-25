@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานการทดสอบผลิตภัณฑ์ (กต.)</h3>

                    <div class="pull-right">
                        @can('view-'.str_slug('report-example-lab'))
                            <a class="btn btn-success pull-right" href="{{ url('section5/report-example-lab') }}">
                                <i class="icon-arrow-left-circle"></i> กลับ
                            </a>
                        @endcan
                    </div>

                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <form class="form-horizontal" role="form">
                        <h2 class="text-center">ผลการทดสอบใบรับ-นำส่งตัวอย่าง</h2>

                        {{-- หัวข้อรายงาน --}}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">เลขที่อ้างอิง :</div>
                                <div class="col-md-8">
                                    <p>{{ $sample->no_example_id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">มาตรฐาน :</div>
                                <div class="col-md-8">
                                    <p>{{ $sample->tis_standard }} : {{ $standard->tb3_TisThainame }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">ผู้ได้รับใบอนุญาต :</div>
                                <div class="col-md-8">
                                    <p>{{ $sample->licensee }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="myTable">
                                    <thead>
                                        <tr>
                                            <th width="2%" class="text-center text-middle">รายการที่</th>
                                            <th width="22%" class="text-center text-middle">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                            <th width="18%" class="text-center text-middle">รายการทดสอบ</th>
                                            <th width="12%" class="text-center text-middle">จำนวนที่ส่ง</th>
                                            <th width="12%" class="text-center text-middle">จำนวนที่ได้รับ</th>
                                            <th width="12%" class="text-center text-middle">หมายเลขตัวอย่าง</th>
                                            <th width="10%" class="text-center text-middle">รายละเอียด</th>
                                            <th width="12%" class="text-center text-middle">ไฟล์ผลทดสอบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($map_lap_list as $key => $map_lap)
                                            <tr>
                                                <td class="text-top text-center">{{ $key+1 }}</td>
                                                <td class="text-top">{{ HP::map_lap_sizedetail($map_lap->detail_product_maplap) }}</td>
                                                <td class="text-top">{!! $map_lap->DetailItemHtml !!}</td>
                                                <td class="text-top text-right">{{ HP::map_lap_number3($map_lap->detail_product_maplap, $map_lap->example_id) }}</td>
                                                <td class="text-top text-right">{{ $map_lap->number_labget }}</td>
                                                <td class="text-top text-center">{{ HP::map_lap_num_ex3($map_lap->detail_product_maplap, $map_lap->example_id) }}</td>
                                                <td class="text-top text-center">
                                                    <button class="btn btn-sm text-info btn_form_result" type="button" data-id="{!! $map_lap->id !!}">รายละเอียด</button>
                                                </td>
                                                <td class="text-top">
                                                    @php
                                                        $data_detail = $map_lap->example_file;
                                                        $file_url    = !is_null($data_detail) ? HP::getFileStorage($attach_path.'/'.$data_detail->file) : '' ;
                                                    @endphp
                                                    <span>{!! !empty($file_url) ? '<a href="'.$file_url.'" target="_blank">'.$data_detail->file.'</a>' : '' !!}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <br>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2">การตรวจสอบ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">
                                            {!! $example->verification == 'ตรวจสอบที่หน่วยตรวจสอบ' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            ตรวจสอบที่หน่วยตรวจสอบ
                                            {!! $example->verification == 'ตรวจสอบที่โรงงาน' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            ตรวจสอบที่โรงงาน
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2">การนำส่งตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">
                                            {!! $example->sample_submission == 'ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง
                                            {!! $example->sample_submission == 'กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2"> </label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">
                                            โดยเก็บตัวอย่างไว้ที่ :
                                            {!! $example->stored_add == 'โรงงาน' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            โรงงาน
                                            {!! $example->stored_add == 'สมอ. ห้อง' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' !!}
                                            สมอ. ห้อง {{ $example->room_anchor }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row p-l-20 p-r-20">
                            <div class="p-l-10 p-r-10">
                                <p class="center" style="text-align: justify; padding: 10px; border: 2px solid black; line-height: 40px;">
                                                ตามเงื่อนไขที่ผู้รับใบอนุญาตต้องปฏิบัติ ตามมาตรา 25 ทวิ สำนักงานขอแจ้งให้ท่านนำส่งตัวอย่างพร้อมชำระค่าใช้จ่ายในการตรวจสอบ<br>
                                                ที่หน่วยตรวจสอบตามที่ระบุไว้ ในใบรับ-นำส่งตัวอย่างนี้ ภายใน 15 วัน นับจากวันที่เก็บตัวอย่าง
                                </p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">วันที่เก็บตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ HP::formatDateThai($example->sample_submission_date) }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ผู้จ่ายตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->sample_pay }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ผู้รับตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->sample_recipient }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ตำแหน่ง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->permission_submiss }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ตำแหน่ง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->permission_receive }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">เบอร์โทรศัพท์ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->tel_submiss }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">เบอร์โทรศัพท์ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->tel_receive }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">E-mail :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->email_submiss }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">E-mail :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $example->email_receive }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">การรับคืนตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">
                                            @if ($example->sample_return=='ไม่รับคืน')
                                                <i class="fa fa-check-square-o"></i>
                                            @else
                                                <i class="fa fa-square-o"></i>
                                            @endif
                                            ไม่รับคืน

                                            @if ($example->sample_return=='รับคืน')
                                                <i class="fa fa-check-square-o"></i>
                                            @else
                                                <i class="fa fa-square-o"></i>
                                            @endif
                                            รับคืน
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="row">

                            <div class="col-md-12">
                                <h4 class="col-md-12 text-left">ข้อมูลการส่งผลทดสอบ :</h4>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">สถานะ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ HP::map_lap_status($example->status) }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">หมายเหตุ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ count($map_lap_list) > 0 ? $map_lap_list[0]->remark : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ผู้บันทึก :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ count($map_lap_list) > 0 ? $map_lap_list[0]->user_lab : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">เบอร์โทรศัพท์ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ count($map_lap_list) > 0 ? $map_lap_list[0]->tel_lab : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">E-mail :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ count($map_lap_list) > 0 ? $map_lap_list[0]->email_lab : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    @include('section5/report-example-lab/modal/result')

@endsection

@push('js')

    <script>

        $(function () {

        });

    </script>
@endpush
