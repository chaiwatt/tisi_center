@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>

    </style>
@endpush

<table class="table table-borderless" id="myTable">
    <thead>
        <tr>
            <th >#</th>
            <th class="text-center"  width="25%">ชื่อ-สกุล</th>
            <th class="text-center"  width="25%">เลขประจำตัวประชาชน</th>
            <th class="text-center"  width="25%">อีเมล</th>
            <th width="20%">กลุ่มงานย่อย</th>
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
                    url: '{!! url('/report/roles/data_staff_list') !!}',
                    data: function (d) {
                        d.filter_search  = $('#filter_search').val();
                        d.filter_role_id = '{!! $roles->id !!}';
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'reg_fname', name: 'reg_fname' },
                    { data: 'reg_13ID', name: 'reg_13ID' },
                    { data: 'reg_email', name: 'reg_email' },
                    { data: 'sub_departname', name: 'sub_departname' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                            
                }
            });
        });
    </script>
@endpush