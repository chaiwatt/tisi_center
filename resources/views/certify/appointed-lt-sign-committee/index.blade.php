{{-- work on Certify\\SendCertificatesController --}}
{{-- AppointedLtSignCommitteeController --}}
@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<style>
.pointer {cursor: pointer;}
</style>
@endpush


@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ลงนามหนังสือเชิญประชุม ลท</h3>

                    <div class="pull-right">
                        {{-- <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/appointed-academic-sub-committee/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a> --}}

                      {{-- @can('add-'.str_slug('sendcertificates'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/send-certificates/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan

                      @can('delete-'.str_slug('sendcertificates'))
                        <button class="btn btn-danger btn-sm waves-effect waves-light"  type="button"
                        id="bulk_delete">
                            <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ปิด</b>
                        </button>
                      @endcan --}}

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {{-- <div class="row ">
                        <div class="col-md-4 form-group">
                            <select name="filter_standard_type" id="filter_standard_type" class="form-control">
                                <option value="" disabled selected>-- เลือกประเภท --</option>
                                <option value="0">ข้อตกลงร่วม</option>
                                <option value="1">มตช.</option>
                                <option value="2">มตช./ ข้อกำหนดเผยแพร่</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="filter_state" id="filter_state" class="form-control">
                                <option value="" disabled selected>-- เลือกสถานะ --</option>
                                <option value="0">คณะอนุกรรมการวิชาการ</option>
                                <option value="1">คณะกำหนด</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="filter_state" id="filter_state" class="form-control">
                                <option value="" disabled selected>-- เลือกสถานะ --</option>
                                <option value="0">รอดำเนินการ</option>
                                <option value="1">ลงนามเรียบร้อย</option>
                            </select>
                        </div>

                      <div class="col-md-2">
                            <div class="  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                      </div>
                  </div> --}}

                <div class="clearfix"></div>

                <div class="row">
                   <div class="col-md-12">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">#</th>
                                    <th width="15%" class="text-center">ชื่อมาตรฐาน</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="15%" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                             @foreach ($meetingInvitations as $index => $meetingInvitation)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td >{{ $meetingInvitation->subject }}</td>
                                        <td class="text-center"><span class="label label-warning">ส่งลงนาม</span></td>
                                        <td class="text-center">
                                             <a href="{{ route('certify.appointed-lt-committee.view', $meetingInvitation->id) }}" class="btn btn-sm btn-info" title="ดู">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @can('view-' . str_slug('appointed-committee'))
                                                <a  class="btn btn-sm btn-warning btn_sign" data-id="{{$meetingInvitation->signer_id}}" data-meetinginvitation="{{$meetingInvitation->id}}" >
                                                <i class="fa fa-check"></i>  ลงนาม
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div id="signerModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อมูลผู้ลงนาม</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Textboxes สำหรับข้อมูล -->
                <input type="hidden" id="signerId">
                <input type="hidden" id="meetinginvitation_id">
                <div class="form-group">
                    <label>ชื่อ</label>
                    <input type="text" class="form-control" id="signerName" readonly>
                </div>
                <div class="form-group">
                    <label>ตำแหน่ง</label>
                    <input type="text" class="form-control" id="signerPosition" readonly>
                </div>

                <!-- แสดงรูปภาพลายเซ็นต์ถ้ามี -->
                <div class="form-group" id="signatureContainer" style="display: none;">
                    <label>ลายเซ็นต์</label>
                    <img id="signatureImg" src="" alt="Signature Image" style="width: 100%; max-width: 200px;">
                </div>
                <div class="form-group text-center" id="error_no_signature" style="display: none;">
                    <h2 class="text-danger">ไม่พบลายเซนต์ กรุณาเพิ่มลายเซนต์เข้าระบบ</h2>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="signDocument" >ลงนาม</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>


@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
 

    <script>
        $(document).ready(function () {
       
            if ($('#loadingOverlay').length === 0) {
                    $('body').append(`
                        <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                            <div style="color: white; font-size: 24px; font-family: Arial, sans-serif; text-align: center;">Loading ...</div>
                        </div>
                    `);
                    // ยืนยันว่า overlay ซ่อนตั้งแต่เริ่มต้น
                    $('#loadingOverlay').hide();
                }
            // jQuery คลิกที่ปุ่มและทำการดึงข้อมูลผ่าน AJAX
            $(document).on('click', '.btn_sign', function() {
                $('#meetinginvitation_id').val($(this).data('meetinginvitation')); 
                var signer_id = $(this).data('id'); // ดึง data-id จากปุ่ม
                // $('#signerModal').modal('show');
                $('#loadingOverlay').show();
                $.ajax({
                    url: "{{ route('certificate.assessment_report_assignment.get_signer') }}", // URL ของ route
                    type: 'GET',
                    data: { signer_id: signer_id },
                    success: function(response) {

                        if (response.success) {
                            $('#signerId').val(response.data.id);
                            $('#signerName').val(response.data.name);
                            $('#signerPosition').val(response.data.position);

                            // แสดงลายเซ็นต์ถ้ามี
                            if (response.data.sign_url) 
                            {
                                $('#signatureContainer').show();
                                $('#signatureImg').attr('src', response.data.sign_url); 
                                $('#error_no_signature').hide();  // ซ่อนข้อความ error_no_signature หากพบลายเซ็นต์
                                $('#signDocument').show();  // แสดงปุ่มลงนาม
                            } else {
                                $('#signatureContainer').hide();  // ซ่อน container ลายเซ็นต์
                                $('#error_no_signature').show();  // แสดงข้อความ error_no_signature
                                $('#signDocument').hide();  // ซ่อนปุ่มลงนาม
                            }

                            $('#signerModal').modal('show');
                        }

                        $('#signerModal').modal('show'); // แสดงโมดัล
                        $('#loadingOverlay').hide();
                    },
                    error: function(xhr, status, error) {
                        console.log(error); // แสดง error ใน console (ถ้ามี)
                        $('#signerInfo').html("เกิดข้อผิดพลาดในการดึงข้อมูล");
                        $('#signerModal').modal('show');
                    },
                    complete: function() {
                        // ซ่อน overlay เมื่อ AJAX เสร็จสิ้น
                        $('#loadingOverlay').hide();
                    }
                });
            });


            $(document).on('click', '#signDocument', function() {
                    var meetinginvitation_id = $('#meetinginvitation_id').val().trim(); // ดึง data-id จากปุ่ม
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    
                    // แสดงข้อความระหว่างที่รอกระบวนการ
                    $.LoadingOverlay("show", {
                        image: "",
                        text: "กำลังบันทึก กรุณารอสักครู่..."
                    });

                    $.ajax({
                        url: "{{ route('certify.appointed-lt-sign-committee.sign-document') }}",
                        type: 'POST',
                        data: { 
                            _token: csrfToken, // เพิ่ม CSRF token
                            id: meetinginvitation_id
                        },
                        success: function(response) {
                            // ซ่อนข้อความระหว่างรอเมื่อเสร็จ
                            $.LoadingOverlay("hide");
                            
                            $('#signerModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 300);
                    
                        },
                        error: function(xhr, status, error) {
                            // ซ่อนข้อความระหว่างรอเมื่อมีข้อผิดพลาด
                            $.LoadingOverlay("hide");
                            
                            console.log(error);
                        }
                    });
                });
            });

            // เมื่อคลิกปุ่มเพื่อเปิดโมดัลและดึงข้อมูล
            $(document).on('click', '.sign-document', function() {
                var signer_id = $(this).data('id'); // ดึง data-id จากปุ่ม
                var transaction_id = $(this).data('transaction_id').trim(); // ดึง data-id จากปุ่ม

                // ส่ง AJAX request เพื่อดึงข้อมูล signer
                $.ajax({
                    url: "{{ route('auditor_assignment.get_signer') }}",
                    type: 'GET',
                    data: { 
                        signer_id: signer_id
                    },
                    success: function(response) {
                        if (response.success) {
                            // แสดงข้อมูลในฟอร์ม
                            $('#messageRecordTransactionId').val(transaction_id);
                            $('#signerId').val(response.data.id);
                            $('#signerName').val(response.data.name);
                            $('#signerPosition').val(response.data.position);

                            // แสดงลายเซ็นต์ถ้ามี
                            if (response.data.sign_url) 
                            {
                                $('#signatureContainer').show();
                                $('#signatureImg').attr('src', response.data.sign_url); 
                                $('#error_no_signature').hide();  // ซ่อนข้อความ error_no_signature หากพบลายเซ็นต์
                                $('#signDocument').show();  // แสดงปุ่มลงนาม
                            } else {
                                $('#signatureContainer').hide();  // ซ่อน container ลายเซ็นต์
                                $('#error_no_signature').show();  // แสดงข้อความ error_no_signature
                                $('#signDocument').hide();  // ซ่อนปุ่มลงนาม
                            }

                            $('#signerModal').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
    </script>
@endpush
