@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-pills m-b-30 ">
                    <li class="">
                        <a href="#navpills-infomation" data-toggle="tab" aria-expanded="false">ข้อมูลที่อยู่</a>
                    </li>
                    <li class="active">
                        <a href="#navpills-scope" data-toggle="tab" aria-expanded="false">หน่วยงาน/ขอบข่าย</a>
                    </li>
                    <li>
                        <a href="#navpills-std" data-toggle="tab" aria-expanded="false">มอก. ที่ตรวจสอบได้</a>
                    </li>
                </ul>
                <div class="tab-content br-n pn">
                    <div id="navpills-infomation" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <p><span class="text-bold-400"></span></p>
                                    </div>
                                    <div class="col-md-2  col-sm-12">
                                        @can('view-'.str_slug('manage-inspector'))
                                            <button type="button" class="btn btn-sm btn-warning glow mr-1 mb-1 pull-right" data-toggle="modal" data-target="#MdAddress"  @if( !isset($inspector->id) ) disabled @endif><i class="fa fa-pencil"></i></button>
                                        @endcan
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ที่อยู่ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_address)?$inspector->inspectors_address:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">หมู่ที่ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_moo)?$inspector->inspectors_moo:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ตรอก/ซอย :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_soi)?$inspector->inspectors_soi:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ถนน :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_road)?$inspector->inspectors_road:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ตำบล/แขวง :</span></p>
                                    </div>
                                     <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->InspectorSubdistrictName)?$inspector->InspectorSubdistrictName:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">อำเภอ/เขต :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->InspectorDistrictName)?$inspector->InspectorDistrictName:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">จังหวัด :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->InspectorProvinceName)?$inspector->InspectorProvinceName:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">รหัสไปรษณีย์ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_zipcode)?$inspector->inspectors_zipcode:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">โทรศัพท์ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_phone)?$inspector->inspectors_phone: '-') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">โทรศัพท์มือถือ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_mobile)?$inspector->inspectors_mobile: '-') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">โทรสาร :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_fax)?$inspector->inspectors_fax: '-') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">E-mail :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->inspectors_email)?$inspector->inspectors_email: '-') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ปรับปรุงล่าสุดโดย :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->updated_by)?$inspector->UpdatedName:(!empty($inspector->created_by)?$inspector->CreatedName:'-')) !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ปรับปรุงล่าสุดเมื่อ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($inspector->updated_at)?HP::DateThaiFull($inspector->updated_at):(!empty($inspector->created_at)?HP::DateThaiFull($inspector->created_at):'-') ) !!}</span></p>
                                    </div>
                                </div>

                                @include ('section5.manage-inspectors.modal-infomation')


                            </div>
                        </div>
                    </div>
                    <div id="navpills-scope" class="tab-pane active">

                        <div class="col-md-12 col-sm-12">
                            <div class="pull-right">
                        
                                @can('poko_approve-'.str_slug('manage-inspector'))
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#PlusScopeModal"><span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มขอบข่าย</b></button>
                                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#MinusScopeModal"><span class="btn-label"><i class="fa fa-minus"></i></span><b>ลดขอบข่าย</b></button>
                                @endcan
                        
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" id="box-show-scope">
                                    @include ('section5.manage-inspectors.show-scope')
                                </div>

                                @include('section5.manage-inspectors.scopes.plus')
                                @include('section5.manage-inspectors.scopes.minus')
                                @include('section5.manage-inspectors.scopes.details')

                            </div>
                        </div>
                    </div>
                    <div id="navpills-std" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" id="box-show-std">
                                    @include ('section5.manage-inspectors.show-std')
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

    <script>
        jQuery(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });

            @if(\Session::has('success_message'))
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    // html: '<p class="h4"></p>',
                    width: 500
                });
            @endif

            var table = $('#myTable2').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('section5/inspectors/data_std_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.inspectors_code = '<?php echo $inspector->inspectors_code; ?>';
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch       = $('#filter_branch').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'scope_tis_std', name: 'scope_tis_std' },
                    { data: 'tis_no', name: 'tis_no' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'branch_title', name: 'branch_title' },
                    { data: 'branch_group_title', name: 'branch_group_title' }
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2,-3] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });


            $('#filter_search').keyup(function (e) { 
                table.draw();
            });


            $('#filter_branch_group,#filter_branch').change(function (e) { 
                table.draw();
            });

            $(document).on('click', '.modal_scope_detail', function (e) {

                var id = $(this).data('id');

                $('#show_box_scope_deatil').html('');

                $.LoadingOverlay("show", {
                    image       : "",
                    text        : "Loading..."
                });

                if( id != '' ){
                    $.ajax({
                        url: "{!! url('/section5/inspectors/get-scope-detail') !!}" + "/" + id
                    }).done(function( object ) {
                        $('#show_box_scope_deatil').html(object);
                        $('#MdScopeDtail').modal('show');
                        $.LoadingOverlay("hide", true);

                    });
                }else{
                    $.LoadingOverlay("hide", true);
                }

            });

        });
    </script>
@endpush
