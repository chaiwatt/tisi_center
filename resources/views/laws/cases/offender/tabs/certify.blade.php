<div class="row box_filter_certify">
    <div class="col-md-4"> 
        <p class="h4 text-bold-300 text-left show_time_tabs">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::text('filter_standard_certify', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา มอก.-', 'id' => 'filter_standard_certify']); !!}
            </div>
        </div>
    </div><!-- /.col-lg-5 -->
    <div class="col-lg-4">
        <div class="form-group">
            <div class="col-md-12">
                {!! Form::text('filter_license_number_certify', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา เลขที่ใบอนุญาต-', 'id' => 'filter_license_number_certify']); !!}
            </div>
        </div>
    </div><!-- /.col-lg-5 -->
</div>

<div class="row">
    <div class="pull-right">
        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean_certify">
            ล้างค่า
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped" id="myTable-Certify">
            <thead>
                <tr>
                    <th class="text-center" width="2%">#</th>
                    <th class="text-center" width="19%">เลขใบอนุญาต</th>
                    <th class="text-center" width="15%">ประเภทใบอนุญาต</th>
                    <th class="text-center" width="15%">วันที่ออกใบอนุญาต</th>
                    <th class="text-center" width="20%">มอก.</th>
                    <th class="text-center" width="14%">ใบอนุญาต</th>
                    <th class="text-center" width="15%">สถานะ</th>
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

            table_certify = $('#myTable-Certify').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/cases/offender/data_offender_certify') !!}',
                    data: function (d) {
                        d.law_offender_id       = '{!! $offender->id !!}';
                        d.filter_standard       = $('#filter_standard_certify').val();
                        d.filter_license_number = $('#filter_license_number_certify').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'license_number', name: 'license_number' },
                    { data: 'license_type', name: 'license_type' },
                    { data: 'license_date', name: 'license_date' },
                    { data: 'license_tisi', name: 'license_tisi' },
                    { data: 'license_file', name: 'license_file' },
                    { data: 'license_status', name: 'license_status' },
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0, 1, 2, 3, -1, -2] },
                    { className: "text-top", targets: "_all" }
                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });

            $('#filter_standard_certify,#data_offender_certify').change(function (e) { 
                table_certify.draw();                
            });

            $("#filter_standard_certify").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                            return {
                                searchTerm: params // search term
                            };
                    },
                    results: function (response) {
                        return {
                                results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });
                
            $("#filter_license_number_certify").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-license-tb4') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $('#btn_clean_certify').click(function () {
                $('.box_filter_certify').find('input').select2('val','');
                table_certify.draw();
            });
        });

    </script>
@endpush