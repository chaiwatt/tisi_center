
@php
    $test_item = $scope->test_item;
    $tis_standards = $scope->tis_standards;

    $option_tools = App\Models\Bsection5\TestTool::where(function($query) use($test_item){
                                                    $ids = DB::table((new App\Models\Bsection5\TestItemTools)->getTable().' AS item')
                                                                ->leftJoin((new App\Models\Bsection5\TestTool)->getTable().' AS tools', 'tools.id', '=', 'item.test_tools_id')
                                                                ->where( function($query) use($test_item ) {
                                                                    $query->where('item.bsection5_test_item_id',  $test_item->id);
                                                                })
                                                                ->select('tools.id');

                                                    $query->whereNotIn('id',  $ids);
                                                })
                                                ->select('title', 'id')
                                                ->pluck('title', 'id');
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('tis_standards_tis_tisno', 'เลขที่มอก.'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                {!! Form::text('tis_standards_tis_tisno', !empty( $tis_standards->tb3_Tisno )?$tis_standards->tb3_Tisno:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('tis_standards_title', 'ชื่อมอก.'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                {!! Form::text('tis_standards_title', !empty( $tis_standards->tb3_TisThainame )?$tis_standards->tb3_TisThainame:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('test_title', 'รายการทดสอบ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                {!! Form::text('test_title', !empty( $test_item->title )?$test_item->title:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<!-- เป็นข้อมูลนำเข้าผ่านระบบ ใบสสมัคร -->
@if(  !empty( $scope->ref_lab_application_no ) )
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('ref_lab_application_no', 'เลขที่อ้างอิงใบสมัคร'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-10">
                    {!! Form::text('ref_lab_application_no', !empty( $scope->ref_lab_application_no )?$scope->ref_lab_application_no:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>

@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('start_date', 'วันที่มีผล'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                {!! Form::text('start_date', !empty( $scope->start_date )?HP::revertDate($scope->start_date,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('end_date', 'วันที่สิ้นสุด'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                {!! Form::text('end_date', !empty( $scope->end_date )?HP::revertDate($scope->end_date,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<!-- เป็นข้อมูลนำเข้าผ่านระบบ Labs -->
@if( $scope->type == 2 )
    @php
        $file_labs_scopes = App\AttachFile::where('ref_table', (new App\Models\Section5\LabsScope )->getTable() )
                                        ->where('ref_id', $scope->id )
                                        ->where('section', 'file_labs_scopes')
                                        ->first();
    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('end_date', 'นำเข้าข้อมูลเมื่อ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-4">
                    {!! Form::text('end_date', !empty( $scope->created_at )?HP::revertDate($scope->created_at,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('remarks', 'หมายเหตุ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-10">
                    {!! Form::textarea('remarks', !empty( $scope->remarks )?$scope->remarks:null,['class' => 'form-control co_input_show', 'rows' => 4, 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>

    @if( !is_null($file_labs_scopes) )
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('test_title', 'เอกสารแนบ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                    <div class="col-md-10">
                        <a href="{!! HP::getFileStorage($file_labs_scopes->url) !!}" target="_blank">
                            {!! HP::FileExtension($file_labs_scopes->filename)  ?? '' !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br> 
    @endif
@endif

<input  type="hidden" value="{!! $scope->id !!}" id="input_labs_scopes_id">

<!-- Box Input Tools -->
<div class="row box_edit_tools">

    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลเครื่องมือ</h5></legend>

            <input type="hidden" value="" name="mt_id" id="mt_id">

            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_test_tools', 'เครื่องมือที่ใช้', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::select('mt_test_tools',  $option_tools, null, ['class' => 'form-control', 'placeholder'=>'- เลือกเครื่องมือที่ใช้ -', 'id' => 'mt_test_tools', 'required' => true]) !!}
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_test_tools_no', 'รหัส/หมายเลข', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_test_tools_no', null, ['class' => 'form-control', 'id' => 'mt_test_tools_no', 'required' => true]) !!}
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_capacity', 'ขีดความสามารถ', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_capacity', null, ['class' => 'form-control', 'id' => 'mt_capacity', 'required' => true]) !!}
                    </div>
                </div>
            </div>  

            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_range', 'ช่วงการใช้งาน', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_range', null, ['class' => 'form-control', 'id' => 'mt_range', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_true_value', 'ความละเอียดที่อ่านได้', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_true_value', null, ['class' => 'form-control', 'id' => 'mt_true_value', 'required' => true]) !!}
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_fault_value', 'ความคลาดเคลื่อนที่ยอมรับ', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_fault_value', null, ['class' => 'form-control', 'id' => 'mt_fault_value', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_test_duration', 'ระยะการทดสอบ(วัน)', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_test_duration', null, ['class' => 'form-control input_number', 'id' => 'mt_test_duration', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group required">
                    {!! Form::label('mt_test_price', 'ค่าใช้จ่ายในการทดสอบ/ชุดละ', ['class' => 'col-md-3 control-label text-right']) !!}
                    <div class="col-md-8">
                        {!! Form::text('mt_test_price', null, ['class' => 'form-control mt_number_only', 'id' => 'mt_test_price', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info waves-effect text-left" id="btn_save_tools">บันทึก</button>
                    <button type="button" class="btn btn-danger waves-effect text-left" id="btn_cancel_tools">ยกเลิก</button>
                </div>
            </div>

        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <button type="button" class="btn btn-success waves-effect text-left" value="1" id="btn_add_tools">เพิ่ม</button>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="15%" class="text-center align-top">เครื่องมือที่ใช้</th>
                        <th width="10%" class="text-center">รหัส/หมายเลข</th>
                        <th width="7%" class="text-center">ขีดความสามารถ</th>
                        <th width="7%" class="text-center">ช่วงการ<br>ใช้งาน</th>
                        <th width="7%" class="text-center">ความละเอียดที่อ่านได้</th>
                        <th width="10%" class="text-center">ความคลาดเคลื่อนที่ยอมรับ</th>
                        <th width="10%" class="text-center">ระยะการทดสอบ(วัน)</th>
                        <th width="10%" class="text-center">ค่าใช้จ่ายในการทดสอบ/ชุดละ</th>
                        <th width="10%" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $detail as $key => $item )

                        @php
                            $test_tools = $item->test_tools;
                        @endphp
                        <tr>
                            <td class="text-top text-center">{!! $key+1 !!}</td>
                            <td class="text-top">{!! !empty($item->TestToolTitle)?$item->TestToolTitle:null !!}</td>
                            <td class="text-top">{!! !empty($item->test_tools_no)?$item->test_tools_no:null !!}</td>
                            <td class="text-top">{!! !empty($item->capacity)?$item->capacity:null !!}</td>
                            <td class="text-top">{!! !empty($item->range)?$item->range:null !!}</td>
                            <td class="text-top">{!! !empty($item->true_value)?$item->true_value:null !!}</td>
                            <td class="text-top">{!! !empty($item->fault_value)?$item->fault_value:null !!}</td>
                            <td class="text-top">{!! !empty($item->test_duration)?$item->test_duration:null !!}</td>
                            <td class="text-top">{!! !empty($item->test_price)?$item->test_price:null !!}</td>
                            <td class="text-top text-center">
                                @if( $item->type == 2 )
                                    <button class="btn btn-warning btn-xs mt_edit_tools"  type="button"
                                        data-id="{!! $item->id !!}" 
                                        data-test_tools_id="{!! $item->test_tools_id !!}" 
                                        data-test_tools_no="{!! $item->test_tools_no !!}" 
                                        data-capacity="{!! $item->capacity !!}" 
                                        data-range="{!! $item->range !!}" 
                                        data-true_value="{!! $item->true_value !!}" 
                                        data-fault_value="{!! $item->fault_value !!}" 
                                        data-test_duration="{!! $item->test_duration !!}" 
                                        data-test_price="{!! $item->test_price !!}" 
                                    ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-danger btn-xs mt_delete_tools" data-id="{!! $item->id !!}" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                @endif
                            </td>
                        </tr>
                        
                    @endforeach

                    @if( count($detail) == 0 )
                        <tr>
                            <td colspan="10" class="text-center">ไม่พบข้อมูล</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>