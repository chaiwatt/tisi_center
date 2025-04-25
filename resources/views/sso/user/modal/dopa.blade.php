<div id="modal-dopa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">เปรียบเทียบข้อมูลผู้ใช้งานกับกรมการปกครอง</h4>
            </div>
            <div class="modal-body">
                <div id="show-dopa">
                    <!-- ส่วนแสดงข้อมูล -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            ShowLoading();
            $('#modal-dopa').on('shown.bs.modal', function () {
                ShowLoading();
                $.ajax({
                    method: "POST",
                    url: "{{ url('sso/user-sso/compare-personal') }}",
                    data: { user_id: "{{ $user->id }}", _token: "{{ csrf_token() }}" }
                }).done(function( res ) {
                    $('#show-dopa').html(res.msg);
                });
            })
        });

        function ShowLoading(){
            $('#show-dopa').html('<div class="text-center text-dark"><i class="fa fa-spinner fa-spin"></i> กำลังเชื่อมโยงข้อมูล</div>');
        }

    </script>
@endpush
