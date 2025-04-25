@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
@endpush


<div class="modal" tabindex="-1" role="dialog" id="Mhistrory">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ประวัติ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="myTableHistrory">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">No.</th>
                                    <th width="53%" class="text-center">รูปแบบ</th>
                                    <th width="30%" class="text-center">สร้างเมื่อ</th>
                                    <th width="15%" class="text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#myTableHistrory').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/config/format-code/data_histrory') !!}',
                    data: function (d) {

                        d.format_id = '{!! isset($result->id)?$result->id:'' !!}';
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'format', name: 'format' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1] }
                ],
                fnDrawCallback: function() {

                }
            });
        });
    </script>
@endpush