@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานการทดสอบผลิตภัณฑ์ (กค.)</h3>

                    <div class="pull-right">
                        @can('view-'.str_slug('report-product-lab'))
                            <a class="btn btn-success pull-right" href="{{ url('section5/report-product-lab') }}">
                                <i class="icon-arrow-left-circle"></i> กลับ
                            </a>
                        @endcan
                    </div>

                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <form class="form-horizontal" role="form">
                        <h2 class="text-center">ใบรับ-นำส่งตัวอย่าง</h2>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-3 col-md-offset-6 text-right">เลขที่ใบรับ-นำส่งตัวอย่าง :</div>
                                <div class="col-md-3 div_dotted">
                                    <p>{{ $sample->refno }}</p>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-3 col-md-offset-6 text-right">วันที่ :</div>
                                <div class="col-md-3 div_dotted">
                                    <p>{{ HP::formatDateThai($sample->document_date) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- หัวข้อรายงาน --}}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">ได้รับตัวอย่างผลิตภัณฑ์ :</div>
                                <div class="col-md-8">
                                    <p>{{ $standard->tis_name }}</p>
                                </div>
                            </div>

                            <div class="col-lg-612 col-sm-12 font-16">
                                <div class="col-md-4 text-right">เพื่อตรวจสอบตามมาตรฐานเลขที่ มอก. :</div>
                                <div class="col-md-8">
                                    <p>{{ $product->tis_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">จาก :</div>
                                <div class="col-md-8">
                                    <p>{{ $user_created->name }}</p>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-12 font-16">
                                <div class="col-md-4 text-right">เพื่อส่งให้กับห้องปฎิบัติการ :</div>
                                <div class="col-md-8">
                                    <p>{{ $lab->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="myTable">
                                    <thead>
                                        <tr>
                                            <th width="2%" class="text-center text-middle">ลำดับ</th>
                                            <th width="32%" class="text-center text-middle">รายละเอียดตัวอย่าง<br>ชนิด แบบ ประเภท ขนาด ขั้นคุณภาพ และอื่นๆ</th>
                                            <th width="14%" class="text-center text-middle">รายการทดสอบ</th>
                                            <th width="14%" class="text-center text-middle">จำนวนส่ง</th>
                                            <th width="10%" class="text-center text-middle">จำนวนที่ได้รับ</th>
                                            <th width="14%" class="text-center text-middle">หมายเลขตัวอย่าง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sum = 0; @endphp
                                        @foreach ($sample->sample_details as $key => $sample_detail)
                                            @php $sum += (int)$sample_detail->amount_delivered; @endphp

                                            <tr>
                                                <td class="text-top text-center">{{ $key+1 }}</td>
                                                <td class="text-top">{{ $sample_detail->ProductDetail }}</td>
                                                <td class="text-top">{{ $sample_detail->TestItemDetail }}</td>
                                                <td class="text-right text-top">{{ $sample_detail->amount_delivered }}</td>
                                                <td class="text-right text-top">{{ $sample_detail->amount_received }}</td>
                                                <td class="text-top">{{ $sample_detail->sample_no }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6 col-sm-6 font-16">
                                <div class="col-md-2 text-right">รวม :</div>
                                <div class="col-md-2 text-center">{{ $sum }}</div>
                                <div class="col-md-2">ชุด</div>
                            </div>
                        </div>

                        <br>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-2">การตรวจสอบ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">
                                            <i class="fa fa-check-square-o"></i> ตรวจสอบที่หน่วยตรวจสอบ
                                            <i class="fa fa-square-o"></i> ตรวจสอบที่โรงงาน
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
                                            <i class="fa fa-check-square-o"></i> ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง
                                            <i class="fa fa-square-o"></i> กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">วันที่นำส่งตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ HP::formatDateThai($sample->document_date) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ผู้จ่ายตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ตำแหน่ง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_position }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">เบอร์โทรศัพท์ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_tel }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_email }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">วันที่รับตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ HP::formatDateThai($sample->document_date) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ผู้รับตัวอย่าง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">ตำแหน่ง :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_position }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">เบอร์โทรศัพท์ :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_tel }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email :</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ $sample->sample_sender_email }}</p>
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
                                            @if ($sample->sample_return==1)
                                                <i class="fa fa-check-square-o"></i>
                                            @else
                                                <i class="fa fa-square-o"></i>
                                            @endif
                                            ไม่รับคืน

                                            @if ($sample->sample_return==2)
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

                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')

    <script>

        $(function () {

        });

    </script>
@endpush
