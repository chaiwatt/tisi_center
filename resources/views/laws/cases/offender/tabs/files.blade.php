
<div class="row box_filter_file">
    <div class="col-md-4"> 
        <p class="h4 text-bold-300 text-left show_time_tabs">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา', 'id' => 'filter_search']); !!}
            </div>
        </div>
    </div><!-- /.col-lg-5 -->
</div>

<div class="row">
    <div class="pull-right">
        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean_file">
            ล้างค่า
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped" id="myTable-Files">
            <thead>
                <tr>
                    <th class="text-center" width="2%">#</th>
                    <th class="text-center" width="18%">เลขคดี</th>
                    <th class="text-center" width="25%">ชื่อไฟล์</th>
                    <th class="text-center" width="25%">คำอธิบายไฟล์</th>
                    <th class="text-center" width="15%">วันที่บันทึก</th>
                    <th class="text-center" width="15%">จัดการ</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            table_files = $('#myTable-Files').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/cases/offender/data_offender_files') !!}',
                    data: function (d) {
                        d.law_offender_id  = '{!! $offender->id !!}';
                        d.filter_search    = $('#filter_search').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'filename', name: 'filename' },
                    { data: 'caption', name: 'caption' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                ],  
                columnDefs: [
                    { className: "text-top", targets: "_all" }
                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });

            
            $('#filter_search').keyup(function (e) { 
                table_files.draw();
            });

            $('#btn_clean_file').click(function () {
                $('#box_filter_file').find('input').val('').change();
                $('#box_filter_file').find('select').select2('val','');
                table_files.draw();
            });

        });

    </script>
@endpush