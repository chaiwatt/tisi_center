<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>รายชื่อผู้ตรวจที่ผ่านการแต่งตั้ง</h5></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-primary show_tag_a" type="button" id="btn_modal_inspectors"><i class="fa fa-search"></i> ค้นหาผู้ตรวจ</button>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered inspectors-repeater" id="table-inspectors">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th width="25%" class="text-center">ชื่อผู้ตรวจ</th>
                                    <th width="15%" class="text-center">เลขบัตร</th>
                                    <th width="45%" class="text-center">สาขาผลิตภัณฑ์</th>
                                    <th width="10%" class="text-center">ประเภทผู้ตรวจ</th>
                                    <th class="text-center" width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-inspectors" class="text-left">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </fieldset>

    </div>
</div>

@push('js')
    <script>
        jQuery(document).ready(function() {

            $('.inspectors-repeater').repeater({
                show: function () {
                    $(this).slideDown();
                    resetInsNo2();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                        resetInsNo2();
                    }
                }
            });
            
        });
    </script>
@endpush