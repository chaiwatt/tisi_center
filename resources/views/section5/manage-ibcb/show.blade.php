@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@endpush

@section('content')

@isset($ibcb)
    @php
        $type_arr = [1 => 'IB', 2 => 'CB'];
    @endphp
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หน่วยตรวจสอบ IB/CB #{!! $ibcb->ibcb_code !!}</h3>

                    <a class="btn btn-success pull-right" href="{{ url('/section5/ibcb') }}">
                        <i class="icon-arrow-left-circle" aria-hidden="true"></i>กลับ
                    </a>

                    @if(auth()->user()->can('sync_to_elicense-'.str_slug('manage-ibcb')) && $ibcb->ibcb_type==1)
                        <button id="sync_to_elicense" class="btn btn-primary pull-right m-r-10" type="button">
                            <i class="mdi mdi-cloud-sync"></i> อัพเดทข้อมูลไป e-License
                        </button>
                    @endif

                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">

                        <div class="col-md-7 col-sm-12">

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ชื่อหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($ibcb->ibcb_name)?$ibcb->ibcb_name: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ผู้ยื่นขอ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($ibcb->name)?$ibcb->name: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">ประเภท :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400">{!! (array_key_exists( $ibcb->ibcb_type,  $type_arr )?$type_arr [ $ibcb->ibcb_type ]:'-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">สถานะหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <p><span class="text-bold-400 {!! (!empty($ibcb->state) && $ibcb->state == 1 ?'text-success': 'text-danger') !!}">{!! (!empty($ibcb->state) && $ibcb->state == 1 ?'Active': 'Not Active') !!}</span></p>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-5 col-sm-12">

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">รหัสหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($ibcb->ibcb_code)?$ibcb->ibcb_code: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">เลขนิติบุคคล :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($ibcb->taxid)?$ibcb->taxid: '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่เริ่มเป็นหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <p><span class="text-bold-400">{!! (!empty($ibcb->ibcb_start_date)?HP::DateThaiFull($ibcb->ibcb_start_date): '-') !!}</span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <p class="text-right"><span class="text-bold-600">วันที่สิ้นสุดเป็นหน่วยตรวจสอบ :</span></p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    @php
                                        $end_date = $ibcb->scopes_group()->select('end_date')->max('end_date');
                                    @endphp
                                    <p><span class="text-bold-400">{!! !empty( $ibcb->ibcb_end_date )?HP::DateThaiFull($ibcb->ibcb_end_date):(!empty($end_date)?HP::DateThaiFull($end_date): '-') !!}</span></p>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                @can('edit-'.str_slug('manage-ibcb'))
                                    <button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#Medit"  @if( !isset($ibcb->id) ) disabled @endif><i class="fa fa-pencil"></i></button>


                                    @include ('section5.manage-ibcb.modals.modal-edit')

                                @endcan
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
                        @include ('section5.manage-ibcb.form')
                    </div>
                </div>
            </div>
        </div>

    </div>

@endisset

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script>
        jQuery(document).ready(function() {

            @isset($ibcb)

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

                            $.post("{!! url('/section5/ibcb/sync_to_elicense') !!}", {ibcb_id: '{{ $ibcb->id }}', _token: '{{ csrf_token() }}'})
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

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

        });
    </script>

@endpush
