@push('css')
    <style>
        .bg-rgba-warning {
            background: #0566ac89 !important;
        }

        .bg-rgba-warning.alert {
            color: #ffffff;
        }

        .bg-rgba-warning.alert.alert-dismissible .close {
            color: #210aa3;
        }

        .alert {
            padding: 15px;
            margin-bottom: 5px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
    </style>
@endpush

<div class="modal fade" id="checkModals">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <p class="alert bg-rgba-warning alert-dismissible mb-2 h3 text-bold-300 text-center">
                    เลือกใบรับรองเพื่อตรวจติดตาม</p>
            </div>
                <div class="modal-body">
                    <div class="white-box">
                    <div class="row">
                        <div class="col-sm-12">
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-md-12" id="BoxCheckSearching">
                                                <div class="col-md-6">
                                                    {!! Form::text('filter_search_certificate', null, ['class' => 'form-control', 'placeholder'=>'ค้นหา : หมายเลขการรับรอง, ใบรับรอง, ห้องปฏิบัติการ', 'id' => 'filter_search_certificate']); !!}
                                                </div>
                                                <div class="col-md-4">
                                                <div class="form-group  pull-left">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search_check"> <i class="fa fa-search"></i> ค้นหา</button>
                                                </div>
                                                <div class="form-group  pull-left m-l-15">
                                                    <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean_check">
                                                        ล้างค่า
                                                    </button>
                                                </div>
    
                                            </div>
                                 
                                   
                                        </div>
                                    </div>
                                <div class="clearfix"></div>
            
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered color-bordered-table primary-bordered-table" id="myTableCheck">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="2%">#</th>
                                                    <th class="text-center" width="2%"><input type="checkbox" id="checkall_certificate"></th>
                                                    <th class="text-center" width="15%">หมายเลขการรับรอง</th>
                                                    <th class="text-center" width="15%">เลขที่ใบรับรอง</th>
                                                    <th class="text-center" width="20%">ห้องปฏิบัติการ</th>
                                                    <th class="text-center" width="15%">E-Mail ผู้ติดต่อ</th>
                                                    <th class="text-center" width="15%">วันที่ออกใบรับรอง</th>
                                                    <th class="text-center" width="15%">วันที่ตรวจล่าสุด</th>
                                                </tr>
                                            </thead>
                                            <tbody>
            
                                            </tbody>
                                        </table>
            
                                    </div>
                                </div>
            
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : '' }}" >
                            <div class="col-md-12">
                                <input type="checkbox" class="check send_mail" id="send_mail_check" value="1"
                                    name="mail_status_diagnosis" data-checkbox="icheckbox_square-blue" checked>
                                <label for="send_mail_check"> แจ้งเตือนผู้รับใบรับรอง</label>
    
                            </div>
                        </div>
                        <input type="hidden" name="tracking_id" id="tracking_ids" value="">
                    </div>
        
                <div class="text-center">
                    <button type="button"class="btn btn-success" id="submit_check"><i class="icon-check"></i>
                        ยืนยัน</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ยกเลิก') !!}
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {

            $("body").on("click", "#button_check", function() {
                $('#myTableCheck').DataTable().draw();
                $('#checkModals').modal('show');
            });
            $('#myTableCheck').DataTable({
                    processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/certificate/tracking-cb/data/certificate') !!}',
                    data: function (d) {
                        d.filter_search  = $('#filter_search_certificate').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false  },
                    { data: 'accereditatio_no', name: 'accereditatio_no' },
                    { data: 'certificate_no', name: 'certificate_no' },
                    { data: 'name_standard', name: 'name_standard' },
                    { data: 'email', name: 'email' },
                    { data: 'date_start', name: 'date_start' },
                    { data: 'date_end', name: 'date_end' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,1] }
                ]
            });

                       
            //เลือกทั้งหมด
            $('#checkall_certificate').change(function(event) {
                if($(this).prop('checked')){//เลือกทั้งหมด
                  $('#myTableCheck').find('input.item_certificate').prop('checked', true);
                }else{
                  $('#myTableCheck').find('input.item_certificate').prop('checked', false);
                }
            });

            $('#btn_search_check,.btn_search_check').click(function () {
                $('#myTableCheck').DataTable().draw();
            });

            $('#btn_clean_check').click(function () {
                $('#BoxCheckSearching').find('input').val('');
                $('#myTableCheck').DataTable().draw();
            });

            $("body").on('click', '#submit_check', function() {
                var ids = [];
                var send_mail_check = $('#send_mail_check').is(":checked") ? 1 : 0;

                $('.item_certificate:checked').each(function(index, element) {
                    ids.push($(element).val());
                });
                if (ids.length > 0) {
                    $.LoadingOverlay("show", {
                        image: "",
                        text: "กําลังบันทึก กรุณารอสักครู่..."
                    });
                    $.ajax({
                        type: "post",
                        url: "{{ url('/certificate/tracking-cb/save_check') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids,
                            send_mail: send_mail_check
                        },
                        success: function(data) {
                            $.LoadingOverlay("hide");
                            $('#checkModals').modal('hide');
                            $('#myTable').DataTable().draw();
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'บันทึกเรียบร้อย',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });

                }
            });


        });
    </script>
@endpush
