<div class="col-md-12 col-sm-12">
  
    <table width="100%" class="table table-bordered table-striped" id="MyTable-government-gazette">
    
        <thead>
            <tr>
                <th class="text-center" width="5%">ลำดับ</th>
                <th class="text-center" width="20%">ฉบับที่</th>
                <th class="text-center" width="25%">วันที่ประกาศราชกิจจา</th>
                <th class="text-center" width="25%">ประเภท</th>
                <th class="text-center" width="25%">เอกสารประกาศราชกิจจาฯ</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>

</div>

@push('js')

    <script>
        jQuery(document).ready(function() {
            var table_gazette = $('#MyTable-government-gazette').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/ibcb/data_government_gazette') !!}',
                    data: function (d) {

                        d.id = '{!! $ibcb->id !!}'
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'government_gazette', name: 'government_gazette' },
                    { data: 'government_gazette_date', name: 'government_gazette_date' },
                    { data: 'type', name: 'type' },
                    { data: 'url', name: 'url' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });

            $('#form_gen_gazette').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#form_gen_gazette")[0]);
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', "{{ $ibcb->id }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/ibcb/update_ibcb_gazette') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");

                            table_gazette.draw();

                        }
                    }
                });
            });

        });
    </script>

@endpush