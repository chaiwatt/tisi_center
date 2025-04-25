<!-- Modal เลข 3 -->
<div class="modal fade text-left" id="actionFour" tabindex="-1" role="dialog" aria-labelledby="addBrand">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">ยกเลิกแจ้งงานเข้ากองกฏหมาย</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <h6>เหตุผล : <span id="show_status_modal" style="color:black;"></span> </h6>
                    <h6>วันที่ยกเลิก : <span id="show_date_modal" style="color:black;"></span> </h6>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')

    <script>
    
        $(document).ready(function () {


        });

    </script>

@endpush


