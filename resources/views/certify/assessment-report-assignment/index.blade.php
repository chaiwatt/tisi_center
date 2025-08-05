{{-- work on Certify\\SendCertificatesController --}}
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
                    <h3 class="box-title pull-left">ระบบลงนามรายงานการตรวจประเมิน check</h3>

                    <div class="pull-right">

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
                      <div class="col-md-6 form-group">
                     
                            {!! Form::select('filter_certificate_type',
                                 ['0'=>'CB','1'=>'IB','2'=>'LAB'], 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_certificate_type',
                                'placeholder' => '-- เลือกการรับรอง --']);
                             !!}
                     
                      </div>
                      <div class="col-md-4">
                               {!! Form::select('filter_state',
                                ['0'=>'รอดำเนินการ','1'=>'ลงนามเรียบร้อย'], 
                                null, 
                                ['class' => 'form-control', 
                                'id'=>'filter_state',
                                'placeholder' => '-- เลือกสถานะ --']); !!}
                    </div>
                      <div class="col-md-2">
                            <div class="  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                      </div>
                  </div> --}}

  
                  <div class="row ">
                    <div class="col-md-1"> </div>
                   

                    {{-- <div class="col-md-4">
                          {!! Form::select('filter_signer_id',
                                 App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name', 'id'), 
                              null,
                              ['class' => 'form-control',
                              'id'=>'filter_signer_id',
                                'placeholder'=>'-- เลือกผู้ลงนาม --']) 
                          !!} 
                    </div> --}}
                    <div class="col-md-3"> </div>
                </div>

              
    
                    <div class="clearfix"></div>

                <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                            <thead>
                            <tr>
                                <th width="1%" class="text-center">#</th>
								<th width="15%" class="text-center">คำขอ</th>
                                <th width="15%" class="text-center">การรับรองงาน</th>
                                <th width="15%" class="text-center">ผู้ลงนาม</th>
                                <th width="15%" class="text-center">ตำแหน่งลงนาม</th>
                                <th width="13%" class="text-center">สถานะ</th>
                                <th width="10%" class="text-center">จัดการ</th>
                            </tr>
                            </thead>
                            <tbody>
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
                <input type="hidden" id="signAssessmentReportTransactionId">
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


            //ช่วงวันที่
            $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
            $.fn.dataTable.ext.errMode = 'none';
                
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/certify/assessment-report-assignment/data-list') !!}',
                    data: function (d) {
                        d.filter_state = $('#filter_state').val();
                        d.filter_certificate_type = $('#filter_certificate_type').val();
                    }
                },
                dataSrc: function(json) {
                    console.log("Response:", json); // ตรวจสอบ response ที่ได้จากเซิร์ฟเวอร์
                    return json.data; // ต้องแน่ใจว่า DataTable รับข้อมูลในฟอร์แมต `{ data: [...] }`
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'app_id', name: 'app_id' },
                    { data: 'certificate_type', name: 'certificate_type' },
                    { data: 'signer_name', name: 'signer_name' }, 
                    { data: 'signer_position', name: 'signer_position' },
                    { data: 'approval', name: 'approval' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,1,2,5,6] }
                ],
                fnDrawCallback: function() {

                }
            });
            
            // var table = $('#myTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     searching: false,
            //     ajax: {
            //         url: '{!! url('/certify/send-certificates/data_list') !!}',
            //         data: function (d) {
                      
            //             d.filter_search = $('#filter_search').val();
            //             d.filter_state = $('#filter_state').val();
            //             d.filter_certificate_type = $('#filter_certificate_type').val();
            //             d.filter_signer_id = $('#filter_signer_id').val();
            //         }
            //     },
            //     columns: [
            //         { data: 'DT_Row_Index', searchable: false, orderable: false},
            //         // { data: 'checkbox', searchable: false, orderable: false},
            //         { data: 'certificate_type', name: 'certificate_type' },
            //         { data: 'number', name: 'number' }, 
            //         { data: 'sign_name', name: 'sign_name' },
            //         { data: 'created_at', name: 'created_at' },
            //         { data: 'updated_at', name: 'updated_at' },
            //         { data: 'status', name: 'status' },
            //         { data: 'action', name: 'action' },
            //     ],
            //     columnDefs: [
            //         { className: "text-center", targets:[0,-1] }
            //     ],
            //     fnDrawCallback: function() {
            //         $('#myTable_length').find('.totalrec').remove();
            //         var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
            //         $('#myTable_length').append(el);

            //         $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
            //     }
            // });



            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
              $('#filter_search').val('');
              $('#filter_state').val('').select2();
              $('#filter_certificate_type').val('').select2();
              $('#filter_signer_id').val('').select2();
              table.draw();
           });
 
        //    $('#checkall').change(function (event) {

        //         if ($(this).prop('checked')) {//เลือกทั้งหมด
        //             $('#myTable').find('input.item_checkbox').prop('checked', true);
        //         } else {
        //             $('#myTable').find('input.item_checkbox').prop('checked', false);
        //         }

        //  });

 

            $(document).on('click', '#bulk_delete', function(){

                var id = [];
                // $('.item_checkbox:checked').each(function(index, element){
                //     id.push($(element).val());
                // });

                if(id.length > 0){

                    if (confirm("ยืนยันการลบข้อมูล " + id.length + " แถว นี้ ?")) {
                        $.ajax({
                                type:"POST",
                                url:  "{{ url('/certify/send-certificates/delete') }}",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success:function(data){
                                    table.draw();
                                    $.toast({
                                        heading: 'Success!',
                                        position: 'top-center',
                                        text: 'ลบสำเร็จ !',
                                        loaderBg: '#70b7d6',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 6
                                    });
                                    $('#checkall').prop('checked', false);
                                }
                        });
                    }

                }else{
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }
            });

 
 
        // jQuery คลิกที่ปุ่มและทำการดึงข้อมูลผ่าน AJAX
        // $(document).on('click', '.btn-warning', function() {
        //     var signer_id = $(this).data('id'); // ดึง data-id จากปุ่ม
            
        //     $.ajax({
        //         url: "{{ route('assessment_report_assignment.get_signer') }}", // URL ของ route
        //         type: 'GET',
        //         data: { signer_id: signer_id },
        //         success: function(response) {
        //             console.log(response)
        //             $('#signerInfo').html(response.data); // สมมติว่า response มีข้อมูลใน response.data
        //             $('#signerModal').modal('show'); // แสดงโมดัล
        //         },
        //         error: function(xhr, status, error) {
        //             console.log(error); // แสดง error ใน console (ถ้ามี)
        //             $('#signerInfo').html("เกิดข้อผิดพลาดในการดึงข้อมูล");
        //             $('#signerModal').modal('show');
        //         }
        //     });
        // });

        if ($('#loadingOverlay').length === 0) {
            $('body').append(`
                <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                    <div style="color: white; font-size: 24px; font-family: Arial, sans-serif; text-align: center;">Loading ...</div>
                </div>
            `);
            // ยืนยันว่า overlay ซ่อนตั้งแต่เริ่มต้น
            $('#loadingOverlay').hide();
        }
        $(document).on('click', '.btn-warning', function() {
            var signer_id = $(this).data('id'); // ดึง data-id จากปุ่ม
            // แสดง overlay
            $('#loadingOverlay').show();

            $.ajax({
                url: "{{ route('assessment_report_assignment.get_signer') }}", // URL ของ route
                type: 'GET',
                data: { signer_id: signer_id },
                success: function(response) {
                    console.log(response);
                    $('#signerInfo').html(response.data); // แสดงผลใน #signerInfo
                    $('#signerModal').modal('show'); // แสดง modal
                },
                error: function(xhr, status, error) {
                    console.log(error);
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

            var transaction_id = $('#signAssessmentReportTransactionId').val().trim(); // ดึง data-id จากปุ่ม
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            // แสดงข้อความระหว่างที่รอกระบวนการ
            $.LoadingOverlay("show", {
                image: "",
                text: "กำลังบันทึก กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{{ route('assessment_report_assignment.signDocument') }}",
                type: 'POST',
                data: { 
                    _token: csrfToken, // เพิ่ม CSRF token
                    id: transaction_id
                },
                success: function(response) {
                    console.log(response);
                    // ซ่อนข้อความระหว่างรอเมื่อเสร็จ
                    $.LoadingOverlay("hide");

                                // --- ส่วนที่แก้ไข ---
                    // ตรวจสอบสถานะจาก response
                    if (response.success) {
                        // กรณีสำเร็จ
                        // แสดงข้อความว่าสำเร็จ (แนะนำใช้ SweetAlert2 จะสวยกว่า)
                        // alert('ลงนามเอกสารเรียบร้อยแล้ว');

                        // ปิด Modal และวาดตารางใหม่
                        $('#signerModal').modal('hide');
                        table.draw();
                    } else {
                        // กรณีไม่สำเร็จ (เช่น ต้องรอคนอื่นลงนามก่อน)
                        // แสดงข้อความ error ที่ได้จาก Server โดยตรง
                        alert(response.message);
                    }
                    
                    // $('#signerModal').modal('hide');
                    // location.reload(); // ทำการ reload หน้าเว็บ
                    // table.draw();
                },
                error: function(xhr, status, error) {
                    // ซ่อนข้อความระหว่างรอเมื่อมีข้อผิดพลาด
                    $.LoadingOverlay("hide");
                    
                    console.log(error);
                }
            });
        });


        });

        
        function confirm_delete() {
                    return confirm("ยืนยันการลบข้อมูล?");
        }

      
        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }


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
                $('#signAssessmentReportTransactionId').val(transaction_id);
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
