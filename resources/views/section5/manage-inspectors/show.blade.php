@extends('layouts.master')

@section('content')

@isset($inspector)

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายชื่อผู้ตรวจ/ผู้ประเมิน (IB) #{!! $inspector->inspectors_code !!}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/inspectors') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i>กลับ
                        </a>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">

                        <div class="col-md-10 col-sm-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ชื่อ-สกุล :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($inspector->AgencyFullName)?$inspector->AgencyFullName: '-') !!}</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">รหัสชื่อผู้ตรวจ/ผู้ประเมิน :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($inspector->inspectors_code)?$inspector->inspectors_code: '-') !!}</span></p>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">เลขบัตรประชาชน :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($inspector->inspectors_taxid)?$inspector->inspectors_taxid: '-') !!}</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่เริ่มเป็นผู้ตรวจ/ผู้ประเมิน :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($inspector->inspector_first_date)?HP::DateThaiFull($inspector->inspector_first_date): '-') !!}</span></p>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">สถานะ :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400 {!! (!empty($inspector->state) && $inspector->state == 1 ?'text-success': 'text-danger') !!}">{!! (!empty($inspector->state) && $inspector->state == 1 ?'Active': 'Not Active') !!}</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่ปรับปรุงล่าสุด :</span></p>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($inspector->inspectors_agreements->last()->file_updated_at)?HP::DateThaiFull($inspector->inspectors_agreements->last()->file_updated_at): '-') !!}</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row">
                        @include ('section5.manage-inspectors.form')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endisset

@endsection

@push('js')

    <script>
        jQuery(document).ready(function() {


        });
    </script>

@endpush