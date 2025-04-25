<div class="modal fade" id="OffenderHistoryModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="OffenderHistoryModalLabel1" aria-hidden="true">
    <div  class="modal-dialog  modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><i class="bx bx-x"></i></button>
                <h4 class="modal-title" id="OffenderHistoryModalLabel1">ประวัติการแก้ไขข้อมูล</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-striped" id="myTableHistory">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">#</th>
                                    <th class="text-center" width="29%">ชื่อข้อมูล</th>
                                    <th class="text-center" width="24%">ข้อมูลเดิม</th>
                                    <th class="text-center" width="24%">ข้อมูลใหม่</th>
                                    <th class="text-center" width="15%">วันที่แก้ไข</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
         </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            table_history = $('#myTableHistory').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/cases/offender/data_offender_history') !!}',
                    data: function (d) {
                        d.law_offender_id       = '{!! $offender->id !!}';
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'column', name: 'column' },
                    { data: 'data_old', name: 'data_old' },
                    { data: 'data_new', name: 'data_new' },
                    { data: 'created_at', name: 'created_at' },

                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0, -1] },
                    { className: "text-top", targets: "_all" }
                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });

        });

    </script>
@endpush