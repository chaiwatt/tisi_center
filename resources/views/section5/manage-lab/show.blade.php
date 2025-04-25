@extends('layouts.master')

@section('content')

@isset($labs)

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หน่วยตรวจสอบ (LAB) #{!! $labs->lab_code !!}</h3>

                        <a class="btn btn-success pull-right" href="{{ url('/section5/labs') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>

                        @can('sync_to_elicense-'.str_slug('manage-lab'))
                            <button id="sync_to_elicense" class="btn btn-primary pull-right m-r-10" type="button">
                                <i class="mdi mdi-cloud-sync"></i> อัพเดทข้อมูลไป e-License
                            </button>
                        @endcan

                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">

                        <div class="col-md-7 col-sm-12">

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ชื่อห้องปฏิบัติการ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($labs->lab_name)?$labs->lab_name: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ชื่อหน่วยงาน :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($labs->name)?$labs->name: '-') !!}</span></p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่เริ่มเป็นหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($labs->lab_start_date)?HP::DateThaiFull($labs->lab_start_date): '-') !!}</span></p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่สิ้นสุดเป็นหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    @php
                                        $max_data = $labs->scope_standard()->whereNotNull('end_date')->orderBy('end_date','desc')->first();
                                    @endphp
                                    <p><span class="text-bold-400">{!! !empty( $labs->lab_end_date )?HP::DateThaiFull($labs->lab_end_date):(!empty($max_data->end_date)?HP::DateThaiFull($max_data->end_date): '-') !!}</span></p>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-5 col-sm-12">

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">รหัสปฏิบัติการ :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($labs->lab_code)?$labs->lab_code: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">เลขนิติบุคคล :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($labs->taxid)?$labs->taxid: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">สถานะ :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p>{!! $labs->StateText !!}</p>
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
                        @include ('section5.manage-lab.form')
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

        @isset($labs)

            //เมื่อคลิกปุ่ม อัพเดทข้อมูลไป e-License
            $('#sync_to_elicense').click(function(event) {

                Swal.fire({
                    title: 'ยืนยันอัพเดทข้อมูล',
                    text: "คุณต้องอัพเดทข้อมูลไปยังระบบ e-License หรือไม่?",
                    icon: 'question',
                    width: 300,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger m-l-10',
                    buttonsStyling: false,
                }).then(function (result) {

                    if (result.value) {//กดยืนยัน

                        $.LoadingOverlay("show", {
                            image: "",
                            text: "กำลังบันทึกข้อมูล กรุณารอสักครู่..."
                        });

                        $.post("{!! url('/section5/labs/sync_to_elicense') !!}", {lab_id: '{{ $labs->id }}', _token: '{{ csrf_token() }}'})
                         .done(function( data ) {
                            if(data=='success'){
                                toastr.success('บันทึกข้อมูลสำเร็จ !');
                            }else{
                                toastr.error('บันทึกข้อมูลล้มเหลว !');
                            }

                            $.LoadingOverlay("hide", true);
                        });

                    }

                });

            });

        @endisset

        });
    </script>

@endpush
