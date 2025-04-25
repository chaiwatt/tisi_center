@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>

    </style>
@endpush

<table class="table table-borderless" id="myTable">
    <thead>
        <tr>
            <th>#</th>
            <th>ชื่อผู้ประกอบการ</th>
            <th>เลขผู้เสียภาษี</th>
            <th>รหัสสาขา</th>
            <th>อีเมล</th>
            <th>ประเภท</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>


@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/report/roles/data_trader_list') !!}',
                    data: function (d) {
                        d.filter_search  = $('#filter_search').val();
                        d.filter_role_id = '{!! $roles->id !!}';
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'tax_number', name: 'tax_number' },
                    { data: 'branch_code', name: 'branch_code' },
                    { data: 'email', name: 'email' },
                    { data: 'applicant_types', name: 'applicant_types' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                            
                }
            });
        });
    </script>
@endpush