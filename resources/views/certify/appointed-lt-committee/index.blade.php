{{-- work on Certify\\SendCertificatesController --}}
{{-- AppointedLtCommitteeController --}}
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
                    <h3 class="box-title pull-left">หนังสือเชิญประชุม (ลท)</h3>

                    <div class="pull-right">
                        <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/appointed-lt-committee/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>

                    </div>

                    <div class="clearfix"></div>
                    <hr>
                <div class="clearfix"></div>

                {{-- <div class="row">
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
                                @foreach ($meetingInvitations as $meetingInvitation)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td >
                                            {{$meetingInvitation->subject}}
                                        </td>
                                        <td class="text-center">
                                            @if ($meetingInvitation->status == 1)
                                                    ร่าง
                                                @elseif ($meetingInvitation->status == 2)
                                                    ส่งลงนาม
                                                @elseif ($meetingInvitation->status == 3)
                                                    ลงนามแล้ว
                                                @else
                                                    ไม่ทราบสถานะ
                                                @endif
                                        </td>
                                        <td class="text-center">
                                            @can('view-' . str_slug('appointed-lt-committee'))
                                                <a href="{{ route('certify.appointed-lt-committee.view', $meetingInvitation->id) }}" class="btn btn-sm btn-info" title="ดู">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                            @if ($meetingInvitation->status == 1)
                                                <a href="{{ url('certify/appointed-lt-committee/' . $meetingInvitation->id . '/edit') }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> --}}

                {{-- <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">#</th>
                                    <th width="15%" class="text-center">หัวข้อหนังสือเชิญประชุม</th>
                                      <th width="10%" class="text-center">วันที่สร้าง</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="15%" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetingInvitations as $meetingInvitation)
                          
                                    <tr>
                                        <td class="text-center">{{ $loop->index + $meetingInvitations->firstItem() }}</td>

                                        <td>
                                            {{$meetingInvitation->subject}}
                                        </td>
                                        <td class="text-center">
                                            {{ $meetingInvitation->created_at->addYears(543)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($meetingInvitation->status == 1)
                                                ร่าง
                                            @elseif ($meetingInvitation->status == 2)
                                                ส่งลงนาม
                                            @elseif ($meetingInvitation->status == 3)
                                                ลงนามแล้ว
                                            @else
                                                ไม่ทราบสถานะ
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @can('view-' . str_slug('appointed-lt-committee'))
                                                <a href="{{ route('certify.appointed-lt-committee.view', $meetingInvitation->id) }}" class="btn btn-sm btn-info" title="ดู">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                            @if ($meetingInvitation->status == 1)
                                                <a href="{{ url('certify/appointed-lt-committee/' . $meetingInvitation->id . '/edit') }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper text-left">
                            {!! $meetingInvitations->links() !!}
                        </div>

                    </div>
                </div> --}}


                <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#lt_invitations"><strong>หนังสือเชิญประชุมพิจารณาคำขอ</strong></a></li>
    <li><a data-toggle="tab" href="#std_invitations"><strong>หนังสือเชิญประชุมพิจารณามาตรฐาน</strong></a></li>
</ul>

<div class="tab-content">

    <div id="lt_invitations" class="tab-pane fade in active">
        <h3>รายการหนังสือเชิญประชุมพิจารณาคำขอ</h3>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%" class="text-center">#</th>
                            <th width="15%" class="text-center">หัวข้อหนังสือเชิญประชุม</th>
                            <th width="10%" class="text-center">วันที่สร้าง</th>
                            <th width="10%" class="text-center">สถานะ</th>
                            <th width="15%" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ltMeetingInvitations as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + $ltMeetingInvitations->firstItem() }}</td>
                                <td>{{ $item->subject }}</td>
                                <td class="text-center">
                                    {{ $item->created_at->addYears(543)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                        <span class="badge bg-secondary">ร่าง</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge bg-warning text-dark">ส่งลงนาม</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge bg-success">ลงนามแล้ว</span>
                                    @else
                                        <span class="badge bg-danger">ไม่ทราบสถานะ</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('view-' . str_slug('appointed-lt-committee'))
                                        <a href="{{ route('certify.appointed-lt-committee.view', $item->id) }}" class="btn btn-sm btn-info" title="ดู">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @if ($item->status == 1)
                                        <a href="{{ url('certify/appointed-lt-committee/' . $item->id . '/edit') }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper text-left">
                    {{-- สำคัญ: ต้องใช้ links() จากตัวแปรที่ถูกต้อง --}}
                    {!! $ltMeetingInvitations->links() !!}
                </div>
            </div>
        </div>
    </div>

    <div id="std_invitations" class="tab-pane fade">
        <h3>รายการหนังสือเชิญประชุมมาตรฐาน</h3>
        <div class="row">
            <div class="col-md-12">
                {{-- เราจะใช้โครงสร้างตารางเดียวกัน แต่ใช้ข้อมูลจาก $meetingInvitations --}}
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%" class="text-center">#</th>
                            <th width="15%" class="text-center">หัวข้อหนังสือเชิญประชุม</th>
                            <th width="10%" class="text-center">วันที่สร้าง</th>
                            <th width="10%" class="text-center">สถานะ</th>
                            <th width="15%" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meetingInvitations as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + $meetingInvitations->firstItem() }}</td>
                                <td>{{ $item->subject }}</td>
                                <td class="text-center">
                                    {{ $item->created_at->addYears(543)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                        <span class="badge bg-secondary">ร่าง</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge bg-warning text-dark">ส่งลงนาม</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge bg-success">ลงนามแล้ว</span>
                                    @else
                                        <span class="badge bg-danger">ไม่ทราบสถานะ</span>
                                    @endif
                                </td>
                                {{-- <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-info" title="ดู"><i class="fa fa-eye"></i></a>
                                    @if ($item->status == 1)
                                        <a href="#" class="btn btn-sm btn-warning">แก้ไข</a>
                                    @endif
                                </td> --}}
                                <td class="text-center">

                                    {{-- {{$item->type}} --}}
                                    @can('view-' . str_slug('appointed-academic-sub-committee'))
                                        <a href="{{ route('certify.appointed-academic-sub-committee.view', $item->id) }}" class="btn btn-sm btn-info" title="ดู">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @if ($item->status == 1)
                                        <a href="{{ url('certify/appointed-academic-sub-committee/' . $item->id . '/edit') }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper text-left">
                     {{-- สำคัญ: ต้องใช้ links() จากตัวแปรที่ถูกต้อง --}}
                    {!! $meetingInvitations->links() !!}
                </div>
            </div>
        </div>
    </div>

</div>

                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
{{-- <div id="signerModal" class="modal fade" tabindex="-1" role="dialog">
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
                <input type="hidden" id="messageRecordTransactionId">
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
</div> --}}


@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
 

    <script>
        $(document).ready(function () {
       

   

 



            



        });

        

      
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
