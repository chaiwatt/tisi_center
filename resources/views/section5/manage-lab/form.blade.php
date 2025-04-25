
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css')}}" rel="stylesheet" />
    <style>
        #show_box_scope_deatil input[type="text"]:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-pills m-b-30 ">
                    <li class="{{ !is_null(Request::get('tab_active')) && Request::get('tab_active')==1  ? 'active' : '' }}">
                        <a href="#navpills-infomation" data-toggle="tab" aria-expanded="false">ข้อมูลห้องปฏิบัติการ</a>
                    </li>
                    <li class="{{ is_null(Request::get('tab_active')) || Request::get('tab_active')==2  ? 'active' : '' }}">
                        <a href="#navpills-scope" data-toggle="tab" aria-expanded="false">ขอบข่ายที่ตรวจสอบได้</a>
                    </li>
                    <li class="">
                        <a href="#navpills-contact" data-toggle="tab" aria-expanded="false">ข้อมูลผู้ประสานงาน</a>
                    </li>
                    <li class="">
                        <a href="#navpills-account" data-toggle="tab" aria-expanded="false">บัญชีผู้ใช้งาน</a>
                    </li>
                    <li class="">
                        <a href="#navpills-certify" data-toggle="tab" aria-expanded="false">ข้อมูลใบรับรอง</a>
                    </li>
                </ul>
                <div class="tab-content br-n pn">
                    <div id="navpills-infomation" class="tab-pane {{ !is_null(Request::get('tab_active')) && Request::get('tab_active')==1  ? 'active' : '' }}">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <p><span class="text-bold-400">ห้องปฏิบัติการ</span></p>
                                    </div>
                                    <div class="col-md-2  col-sm-12">
                                        @can('edit-'.str_slug('manage-lab'))
                                            <button type="button" class="btn btn-sm btn-warning glow mr-1 mb-1 pull-right" data-toggle="modal" data-target="#MdAddress"  @if( !isset($labs->id) ) disabled @endif><i class="fa fa-pencil"></i></button>
                                        @endcan

                                        @can('view-'.str_slug('manage-lab'))
                                            <button type="button" class="btn btn-sm btn-info glow mr-1 mb-1 pull-right m-r-10" data-toggle="modal" data-target="#MInfomation-History" @if( !isset($labs->id) ) disabled @endif><i class="mdi mdi-timetable"></i></button>
                                        @endcan
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ที่อยู่ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_address)?$labs->lab_address:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">หมู่ที่ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_moo)?$labs->lab_moo:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">หมู่บ้าน/อาคาร :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_building)?$labs->lab_building:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ตรอก/ซอย :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_soi)?$labs->lab_soi:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ถนน :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_road)?$labs->lab_road:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">ตำบล/แขวง :</span></p>
                                    </div>
                                     <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->LabSubdistrictName)?$labs->LabSubdistrictName:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">อำเภอ/เขต :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->LabDistrictName)?$labs->LabDistrictName:' - ') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">จังหวัด :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->LabProvinceName)?$labs->LabProvinceName:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">รหัสไปรษณีย์ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_zipcode)?$labs->lab_zipcode:' - ') !!}</span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">โทรศัพท์ :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_phone)?$labs->lab_phone: '-') !!}</span></p>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="text-right"><span class="text-bold-400">โทรสาร :</span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <p class=""><span class="text-bold-400">{!! (!empty($labs->lab_fax)?$labs->lab_fax: '-') !!}</span></p>
                                    </div>
                                </div>

                                @include ('section5.manage-lab.modals.modal-infomation')
                                @include ('section5.manage-lab.modals.modal-infomation-history')

                            </div>
                        </div>
                    </div>
                    <div id="navpills-scope" class="tab-pane {{ is_null(Request::get('tab_active')) || Request::get('tab_active')==2  ? 'active' : '' }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-11">
                                        <div class="pull-right">

                                            @can('poko_approve-'.str_slug('manage-lab'))
                                                <button class="btn btn-success" type="button" id="btn_plus_scope"><span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มขอบข่าย</b></button>
                                                <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#MinusScopeModal"><span class="btn-label"><i class="fa fa-minus"></i></span><b>ลดขอบข่าย</b></button>
                                            @endcan

                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="box-show-scope">
                                    @isset($list_scope)
                                        @include ('section5.manage-lab.form-scopes-show')
                                    @endisset
                                </div>
                            </div>
                        </div>

                        @include ('section5.manage-lab.modals.modal-tools')
                        @include ('section5.manage-lab.modals.modal-gen-scope')
                        @include ('section5.manage-lab.modals.modal-minus-scope')

                    </div>
                    <div id="navpills-contact" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                @include ('section5.manage-lab.show.show-contact')
   
                                @include ('section5.manage-lab.modals.modal-contact')

                            </div>
                        </div>
                    </div>
                    <div id="navpills-account" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                @php
                                    $user_sso = $labs->user;
                                @endphp

                                @include ('section5.manage-lab.show.show-account')

                                @include ('section5.manage-lab.modals.modal-account')
                                @include ('section5.manage-lab.modals.modal-account-history')

                            </div>
                        </div>
                    </div>
                    <div id="navpills-certify" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="box_certify">
                                    @include ('section5.manage-lab.show.show-certify')
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
    <script src="{{asset('plugins/components/bootstrap-treeview/js/bootstrap-treeview.min.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script>
        jQuery(document).ready(function() {

            $('#btn_plus_scope').click(function (e) { 
                $('#ScopeModal').find('select').val('').select2();
                $('#ScopeModal').find('input, textarea').val('');

                $('#myTableScope tbody').html('');
                            
                $('#ScopeModal').modal('show');
            });
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

            $(document).on('click', '.modal_tools', function (e) {

                var id = $(this).data('id');

                $('#show_box_scope_deatil').html('');

                $.LoadingOverlay("show", {
                    image       : "",
                    text        : "Loading..."
                });

                if( id != '' ){
                    $.ajax({
                        url: "{!! url('/section5/labs/get-scope-detail') !!}" + "/" + id
                    }).done(function( object ) {
                        $('#show_box_scope_deatil').html(object);
                        $('#MdScopeDtail').modal('show');
                        $.LoadingOverlay("hide", true);

                    });
                }else{
                    $.LoadingOverlay("hide", true);
                }

            });
            LoadScopeShow();
        });

        function LoadGroupScope(){
            var id =  '{{ $labs->id }}';

            $.LoadingOverlay("show", {
                image: "",
                text: "กำลังโหลด กรุณารอสักครู่..."
            });

            $('#box-show-scope').html('');
            $.ajax({
                url: "{!! url('/section5/labs/html_scope') !!}" + "/" + id
            }).done(function( object ) {
                $('#box-show-scope').html(object);

                LoadScopeShow();
                $.LoadingOverlay("hide", true);
            });
        }

        function LoadScopeShow() {

            $('.scope_input_std').each(function(index, element){

                var tis_id = $(element).val();
                var lab_id = $(element).data('lab_id');

                if( tis_id != '' ){

                    $.ajax({
                        url: "{!! url('/section5/labs/treeview_scope') !!}" + "?lab_id=" + lab_id + "&tis_id=" + tis_id
                    }).done(function( object ) {
                        $('.scope_show_std_'+tis_id).treeview({
                            data: object,
                            collapseIcon:'fa fa-minus',
                            expandIcon:'fa fa-plus',
                            showBorder: false,
                            showTags: false,
                            highlightSelected: false,

                        });
                        $('.scope_show_std_'+tis_id).treeview('expandAll', { levels: 3, silent: true });
                    });
                }

            });

        }

        // Check Empty
        function empty(variable){
            switch (variable) {
                case "":
                case 0:
                case "0":
                case null:
                case false:
                case typeof(variable) == "undefined":
                return true;
                default:
                return false;
            }
        }
    </script>
@endpush
