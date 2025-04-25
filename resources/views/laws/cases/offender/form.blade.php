@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/components/summernote/summernote.css') }}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link rel="stylesheet" href="{{asset('plugins/components/box-icons/boxicons.min.css')}}" />
    {{-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> --}}
    <style>


    </style>
@endpush
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h3 class="m-t-0">ข้อมูลผู้กระทำความผิด</h3></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button type="button" class="btn btn-sm btn-info mr-1 mb-1 " data-toggle="modal" data-target="#OffenderHistoryModal" ><i class='bx bx-history'></i></button>
                        <button type="button" class="btn btn-sm btn-warning mr-1 mb-1 " data-toggle="modal"data-target="#OffenderInfomationModal" ><i class='bx bxs-edit-alt' ></i></button>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">ประเภท :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !empty($offender->ApplicantTypeTitle)?$offender->ApplicantTypeTitle:'-' !!} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-5">วันที่พบการกระทำผิดครั้งแรก :</label>
                        <div class="col-md-7">
                            <p class="form-control-static div_dotted"> {!! !empty($offender->date_offender)?HP::DateThai($offender->date_offender):'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">ชื่อผู้ประกอบการ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !empty($offender->name)?$offender->name:'-' !!} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-5">เลขประตัวผู้เสียภาษี :</label>
                        <div class="col-md-7">
                            <p class="form-control-static div_dotted"> {!! !empty($offender->taxid)?$offender->taxid:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-2">ที่ตั้งสำนักงานใหญ่ :</label>
                        <div class="col-md-10">
                            <p class="form-control-static div_dotted"> {!!  !empty($offender->AddressFull)?$offender->AddressFull:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">อีเมล :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !empty($offender->email)?$offender->email:'-' !!} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-5">เบอร์โทรศัพท์ :</label>
                        <div class="col-md-7">
                            <p class="form-control-static div_dotted"> {!! !empty($offender->tel)?$offender->tel:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">เบอร์แฟกซ์ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !empty($offender->fax)?$offender->fax:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-6 col-md-6">
                    <div class="col-md-5 col-sm-12">
                        <p class="text-right"><span class="control-label">ปรับปรุงล่าสุดโดย :</span></p>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <p class=""><span>{!! (!empty($offender->updated_by)?$offender->UpdatedName:(!empty($offender->created_by)?$offender->CreatedName:'-')) !!}</span></p>
                    </div>

                </div>
                <div class="col-md-offset-6 col-md-6">
                    <div class="col-md-5 col-sm-12">
                        <p class="text-right"><span class="control-label">ปรับปรุงล่าสุดเมื่อ :</span></p>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <p class=""><span>{!! (!empty($offender->updated_at)?HP::DateThaiFull($offender->updated_at):(!empty($offender->created_at)?HP::DateThaiFull($offender->created_at):'-') ) !!}</span></p>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">

            <ul class="nav nav-pills m-b-30 m-t-15 ">
                <li class="active"> <a href="#taps-case" data-toggle="tab" aria-expanded="true"><i class="fa fa-history"></i> ประวัติการกระทำความผิด</a> </li>
                <li class=""> <a href="#taps-certify" data-toggle="tab" aria-expanded="false"><i class="fa fa-certificate"></i> ใบอนุญาตที่ได้รับ</a> </li>
                <li class=""> <a href="#taps-contact" data-toggle="tab" aria-expanded="false"><i class="fa fa-user"></i> ข้อมูลผู้ประสานงาน</a> </li>
                <li class=""> <a href="#taps-files" data-toggle="tab" aria-expanded="false"><i class="fa fa-file"></i> ข้อมูลไฟล์</a> </li>
            </ul>

            <div class="tab-content br-n pn">
                <div id="taps-case" class="tab-pane active">
                    @include('laws.cases.offender.tabs.cases')
                </div>
                <div id="taps-certify" class="tab-pane">
                    @include('laws.cases.offender.tabs.certify')
                </div>
                <div id="taps-contact" class="tab-pane">
                    @include('laws.cases.offender.tabs.contact')
                </div>
                <div id="taps-files" class="tab-pane">
                    @include('laws.cases.offender.tabs.files')
                </div>
            </div>

        </fieldset>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>


    <script type="text/javascript">

        var table_cases   = '';
        var table_certify = '';
        var table_files   = '';
        var table_history = '';

        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

        });

        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('.show_time_tabs').text(object);
                }
            });
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }
    </script>
@endpush
