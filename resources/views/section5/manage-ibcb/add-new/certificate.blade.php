

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box box_audit_type_1">
            <legend class="legend"><h5>ใบรับรองระบบงานตามมาตรฐาน</h5></legend>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_cerno_export', 'เลขที่ได้รับการรับรอง'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('certificate_cerno_export', null, ['class' => 'form-control certificate_cerno_export', 'id' => 'certificate_cerno_export', 'placeholder'=>'กรอกเลขที่ได้รับการรับรอง']); !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="button" id="btn_std_export" value="1"><i class="fa fa-database"></i> ดึงจากฐานของ สมอ.</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_issue_date', 'วันที่ออกใบรับรอง'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('certificate_issue_date', null, ['class' => 'form-control mydatepicker', 'placeholder'=>'dd/mm/yyyy']); !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_expire_date', 'วันที่หมดอายุใบรับรอง'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('certificate_expire_date', null, ['class' => 'form-control mydatepicker', 'placeholder'=>'dd/mm/yyyy']); !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('certificate_std_export', 'มอก. รับรองระบบงาน'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::select('certificate_std_export', App\Models\Bsection5\Standard::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'placeholder'=>'เลือกมอก. รับรองระบบงาน']); !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success show_tag_a" type="button" id="btn_cer_add"><i class="icon-plus"></i> เพิ่ม</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
        
                    <div class="table-responsive">
                        <table class="table table-bordered certificate-repeater" id="table-certificate">
                            <thead>
                                <tr>
                                    <th class="text-center" width="25%">ใบรับรองเลขที่</th>
                                    <th class="text-center" width="20%">วันที่ออก</th>
                                    <th class="text-center" width="20%">วันที่หมด</th>
                                    <th class="text-center" width="30%">มอก.</th>
                                    <th class="text-center" width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-certificate" class="text-center">
        
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
            
            $('.certificate-repeater').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

        });
    </script>
@endpush