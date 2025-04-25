<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลขอบข่ายที่ขอรับการตั้งแต่ง</h5></legend>

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('scope_branches_group', 'สาขาผลิตภัณฑ์'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::select('scope_branches_group', App\Models\Basic\BranchGroup::pluck('title', 'id')->all(), null, ['class' => 'form-control', 'placeholder'=>'เลือกสาขาผลิตภัณฑ์']); !!}
                        </div>
                    </div>
                </div>
        
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('scope_branches', 'รายสาขา'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::select('scope_branches[]', [] , null, ['class' => 'select2-multiple scope_branches_multiple', 'id' => 'scope_branches_multiple',  'data-placeholder'=>'เลือกรายสาขา', 'multiple' => 'multiple']); !!}
                        </div>
                    </div>
                </div>
        
                <div class="col-md-12">
                    <div class="form-group required">
                        {!! Form::label('scope_branches_tis', 'เลขที่ มอก.'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::select('scope_branches_tis[]', [] , null, ['class' => 'select2-multiple scope_branches_tis', 'id' => 'scope_branches_tis',  'data-placeholder'=>'เลือกมาตรฐาน', 'multiple' => 'multiple', 'disabled' => false]); !!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" style="padding-top: 10px;">
                                {!! Form::checkbox('check_all_scope_branches_tis', '1', null, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'check_all_scope_branches_tis','required' => false]) !!}
                                <label for="check_all_scope_branches_tis" class="">&nbsp;&nbsp; All</label>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('scope_isic_no', 'ISIC NO'.' :', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('scope_isic_no', null, ['class' => 'form-control', 'id' => 'scope_isic_no',]); !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success show_tag_a" type="button" id="btn_branche_add"><i class="icon-plus"></i> เพิ่ม</button>
                        </div>
                    </div>
                </div>
        
            </div>
            <hr>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered scope-repeater" id="table-scope">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">รายการที่</th>
                                    <th class="text-center" width="32%">สาขาผลิตภัณฑ์</th>
                                    <th class="text-center" width="32%">รายสาขา</th>
                                    <th class="text-center" width="15%">ISIC NO</th>
                                    <th class="text-center" width="15%">มาตรฐาน มอก. เลขที่</th>
                                    <th class="text-center" width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-scope" class="text-left" id="box_list_scpoe">

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


        });
    </script>
@endpush