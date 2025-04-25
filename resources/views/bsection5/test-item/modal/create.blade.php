@php
    $format_result_list = App\Models\Bsection5\TestItem::format_result_list();
@endphp
<!--form Modal -->
<div class="modal fade text-left" tabindex="10" id="AddForm" role="dialog" aria-labelledby="AddFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="AddFormLabel">รายการทดสอบ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">

                <form enctype="multipart/form-data" class="form-horizontal" id="from_test_item" onsubmit="return false">

                    <input name="tis_id" id="tis_id" type="hidden" value="" class="modal_input">
                    <input name="id" id="id" type="hidden" value="" class="modal_input">
                    <input name="copy_and_save" id="copy_and_save" type="hidden" value="" class="modal_input">

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('type', 'ประเภท', ['class' => 'col-md-4 control-label text-right']) !!}
                            <div class="col-md-6">
                                <label id="label_condition_1">{!! Form::radio('type', '1', true, ['class'=>'check input_condition', 'data-radio'=>'iradio_flat-green', 'id' => 'condition_1', 'required' => 'required']) !!} หัวข้อทดสอบ</label>
                                <label id="label_condition_2">{!! Form::radio('type', '2', false, ['class'=>'check input_condition', 'data-radio'=>'iradio_flat-green', 'id' => 'condition_2', 'required' => 'required']) !!} หัวข้อทดสอบย่อย</label>
                                <label id="label_condition_3">{!! Form::radio('type', '3', false, ['class'=>'check input_condition', 'data-radio'=>'iradio_flat-green', 'id' => 'condition_3']) !!} รายการทดสอบ</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('no', 'ข้อ', ['class' => 'col-md-4 control-label text-right']) !!}
                            <div class="col-md-6">
                                {!! Form::text('no', null, ['class' => 'form-control modal_input']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('title', 'หัวข้อ/รายการทดสอบ', ['class' => 'col-md-4 control-label text-right']) !!}
                            <div class="col-md-6">
                                {!! Form::text('title', null, ['class' => 'form-control modal_input', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group ">
                            {!! Form::label('unit_id', 'หน่วย', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('unit_id', App\Models\Bsection5\Unit::pluck('title', 'id'), null, ['class' => 'form-control modal_select', 'placeholder'=>'- เลือกหน่วย -']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('criteria', 'เกณฑ์กำหนด', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::textarea('criteria', null, ['class' => 'form-control modal_input', 'rows' => 2]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group box_parent">
                            {!! Form::label('parent_id', 'อยู่ภายใต้หัวข้อทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('parent_id', [], null, ['class' => 'form-control modal_select', 'placeholder'=>'- เลือกภายใต้หัวข้อทดสอบ -']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('test_method_id', 'วิธีทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('test_method_id', App\Models\Bsection5\TestMethod::pluck('title', 'id'), null, ['class' => 'form-control modal_select', 'placeholder'=>'- เลือกวิธีทดสอบ -']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('test_tools_ids', 'เครื่องมือทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('test_tools_ids[]', App\Models\Bsection5\TestTool::pluck('title', 'id'), null, ['class' => 'modal_select test_tools_ids', 'data-placeholder'=>'- เลือกเครื่องมือทดสอบ -', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('input_result', 'กรอกผลการทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <label>{!! Form::radio('input_result', '1', true, ['class'=>'check input_result' ,'id' => 'input_result_1', 'data-radio'=>'icheckbox_flat-green', 'required' => 'required']) !!} ได้</label>
                                <label>{!! Form::radio('input_result', '2', false, ['class'=>'check input_result','id' => 'input_result_2', 'data-radio'=>'icheckbox_flat-green']) !!} ไม่ได้</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('amount_test_list', 'จำนวนครั้งในการทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('amount_test_list', HP::RangeData(1,10), null, ['class' => 'form-control modal_select', 'placeholder'=>'- เลือกจำนวนครั้งในการทดสอบ -']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('test_summary', 'สรุปผลทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <label>{!! Form::radio('test_summary', '1', true, ['class'=>'check test_summary' ,'id' => 'test_summary_1', 'data-radio'=>'icheckbox_flat-green', 'required' => 'required']) !!} มี</label>
                                <label>{!! Form::radio('test_summary', '2', false, ['class'=>'check test_summary','id' => 'test_summary_2', 'data-radio'=>'icheckbox_flat-red']) !!} ไม่มี</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <label>{!! Form::radio('state', '1', true, ['class'=>'check input_state','id' => 'input_state_1', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
                                <label>{!! Form::radio('state', '0', false, ['class'=>'check input_state','id' => 'input_state_2', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="box-format_result">
                        <div class="form-group">
                            {!! Form::label('format_result', 'รูปแบบข้อมูลผลทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select('format_result', $format_result_list, null, ['class' => 'form-control modal_select', 'placeholder'=>'- เลือกรูปแบบข้อมูลผลทดสอบ -']) !!}
                            </div>
                        </div>
                    </div>

                    <div id="box-format_result_detail">

                    </div>

                    <div id="box-format_result_preview">
                        <div class="form-group">
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-6">
                                <button id="btn-format_result_preview" type="button" class="btn btn-info"><i class="fa fa-search"></i> ดูตัวอย่างช่องกรอกรูปแบบข้อมูลผลทดสอบ</button>
                            </div>
                        </div>
                    </div>

                </form>

                {{-- input ต้นแบบสำหรับ รูปแบบข้อมูลผลทดสอบ --}}
                <div class="prototype-format_result hide">

                    <div data-format_result="integer">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_required', '1', false, ['id' => 'min_required', 'class' => 'config_text']) !!}
                                        <label for="min_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_required', '1', false, ['id' => 'max_required', 'class' => 'config_text']) !!}
                                        <label for="max_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('unit', 'หน่วย', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('unit_required', '1', false, ['id' => 'unit_required', 'class' => 'config_text']) !!}
                                        <label for="unit_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('unit', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div data-format_result="integer_range">

                        <div class="row">
                            <div class="form-group">
                                {!! Html::decode(Form::label('label_start', '<b>ค่าเริ่มต้น</b>', ['class' => 'col-md-4 control-label text-right'])) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min_start', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_start_required', '1', false, ['id' => 'min_start_required', 'class' => 'config_text']) !!}
                                        <label for="min_start_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min_start', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max_start', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_start_required', '1', false, ['id' => 'max_start_required', 'class' => 'config_text']) !!}
                                        <label for="max_start_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max_start', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Html::decode(Form::label('label_end', '<b>ค่าสิ้นสุด</b>', ['class' => 'col-md-4 control-label text-right'])) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min_end', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_end_required', '1', false, ['id' => 'min_end_required', 'class' => 'config_text']) !!}
                                        <label for="min_end_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min_end', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max_end', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_end_required', '1', false, ['id' => 'max_end_required', 'class' => 'config_text']) !!}
                                        <label for="max_end_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max_end', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('unit', 'หน่วย', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('unit_required', '1', false, ['id' => 'unit_required', 'class' => 'config_text']) !!}
                                        <label for="unit_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('unit', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-format_result="decimal">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('digit', 'จำนวนหลักทศนิยมสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::number('digit', null, ['class' => 'form-control modal_input', 'min' => 1, 'step' => 1]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_required', '1', false, ['id' => 'min_required', 'class' => 'config_text']) !!}
                                        <label for="min_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_required', '1', false, ['id' => 'max_required', 'class' => 'config_text']) !!}
                                        <label for="max_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('unit', 'หน่วย', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('unit_required', '1', false, ['id' => 'unit_required', 'class' => 'config_text']) !!}
                                        <label for="unit_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('unit', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-format_result="decimal_range">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('digit', 'จำนวนหลักทศนิยมสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::number('digit', null, ['class' => 'form-control modal_input', 'min' => 1, 'step' => 1]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Html::decode(Form::label('label_start', '<b>ค่าเริ่มต้น</b>', ['class' => 'col-md-4 control-label text-right'])) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min_start', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_start_required', '1', false, ['id' => 'min_start_required', 'class' => 'config_text']) !!}
                                        <label for="min_start_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min_start', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max_start_required', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_start_required', '1', false, ['id' => 'max_start_required', 'class' => 'config_text']) !!}
                                        <label for="max_start_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max_start', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Html::decode(Form::label('label_end', '<b>ค่าสิ้นสุด</b>', ['class' => 'col-md-4 control-label text-right'])) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('min_end', 'ค่าต่ำสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('min_end_required', '1', false, ['id' => 'min_end_required', 'class' => 'config_text']) !!}
                                        <label for="min_end_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('min_end', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('max_end', 'ค่าสูงสุด', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('max_end_required', '1', false, ['id' => 'max_end_required', 'class' => 'config_text']) !!}
                                        <label for="max_end_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('max_end', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('unit', 'หน่วย', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('unit_required', '1', false, ['id' => 'unit_required', 'class' => 'config_text']) !!}
                                        <label for="unit_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::text('unit', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-format_result="select">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('option_list', 'ตัวเลือก', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('option_list', null, ['data-role' => 'tagsinput', 'placeholder' => 'เพิ่มตัวเลือก']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('option_blank', 'คำอธิบาย (ตัวเลือกว่าง)', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-4">
                                    {!! Form::text('option_blank', null, ['class' => 'form-control modal_input']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-format_result="select_multiple">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('option_list', 'ตัวเลือก', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('option_list', null, ['data-role' => 'tagsinput', 'placeholder' => 'เพิ่มตัวเลือก']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('option_blank', 'คำอธิบาย (ตัวเลือกว่าง)', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-4">
                                    {!! Form::text('option_blank', null, ['class' => 'form-control modal_input']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('select_limit', 'จำกัดจำนวนที่เลือกได้', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-2">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('select_limit_required', '1', false, ['id' => 'select_limit_required', 'class' => 'config_text']) !!}
                                        <label for="select_limit_required">&nbsp;กำหนด</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {!! Form::number('select_limit', null, ['class' => 'form-control modal_input', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-format_result="text">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('placeholder', 'คำอธิบายพื้นหลัง (placeholder)', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-4">
                                    {!! Form::text('placeholder', null, ['class' => 'form-control modal_input']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-format_result="mix">

                        <div class="row">
                            <div class="col-md-10 p-r-0">
                                <button id="format_result_mix-plus" type="button" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> เพิ่ม</button>
                            </div>
                        </div>

                        <div class="row box-format_result-mix"><!-- กรอบเก็บประเภท mix ทั้งหมด -->

                            <div class="white-box col-md-offset-2 col-md-8 item-format_result-mix"><!-- กรอบ mix แต่ละชุด -->

                                <div class="row p-r-10">
                                    <button type="button" class="btn btn-sm btn-danger pull-right format_result_mix-remove"><i class="fa fa-minus"></i> ลบ</button>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        {!! Form::label('format_result_mix', 'รูปแบบข้อมูลผลทดสอบ', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            @php
                                                $format_result_mix_list = $format_result_list;
                                                unset($format_result_mix_list['mix']);
                                                $format_result_mix_list['label'] = 'Label (ป้าย)';
                                            @endphp
                                            {!! Form::select('format_result_mix', $format_result_mix_list, null, ['class' => 'form-control modal_select format_result_mix', 'placeholder'=>'- เลือกรูปแบบข้อมูลผลทดสอบ -']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="box-format_result-mix_detail">

                                </div>

                            </div>
                        </div>

                    </div>

                    <div data-format_result="label">
                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('label', 'ข้อความ Label (ป้าย)', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-4">
                                    {!! Form::text('label', null, ['class' => 'form-control modal_input']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary ml-1" id="btn_save"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">บันทึก</span></button>
                <button type="button" class="btn btn-info ml-1" id="btn_copy_save"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">บันทึกและคัดลอก</span></button>
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ยกเลิก</span></button>
            </div>
        </div>
    </div>
</div>
