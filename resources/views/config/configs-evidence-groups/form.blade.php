@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

@endpush


<div class="form-group {{ $errors->has('system') ? 'has-error' : ''}}">
    {!! Form::label('system', 'ระบบงานหลัก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('system', App\Models\Config\ConfigsEvidenceSystem::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกระบบงานหลัก -', 'required' => 'required']) !!}
        {!! $errors->first('system', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ระบบที่นำไปใช้', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('short_title') ? 'has-error' : ''}}">
    {!! Form::label('short_title', 'ชื่อย่อระบบที่นำไปใช้', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('short_title', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('short_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('url') ? 'has-error' : ''}}">
    {!! Form::label('url', 'Url', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('url', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('url', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('remarks') ? 'has-error' : ''}}">
    {!! Form::label('remarks', 'รายละเอียด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('remarks', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'rows' => 3]) !!}
        {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('condition') ? 'has-error' : ''}}">
    {!! Form::label('condition', 'ชุดไฟล์แนบ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('condition', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'condition_1']) !!} แบบหลัก</label>
        <label>{!! Form::radio('condition', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'condition_2']) !!} แบบย่อย</label>
        {!! $errors->first('condition', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<hr>

@php
$file_properties_list =   [ 
                            'pdf' => 'pdf', 
                            'doc' => 'doc', 
                            'docx' => 'docx', 
                            'ppt' => 'ppt', 
                            'pptx' => 'pptx', 
                            'txt' => 'txt', 
                            'xls' => 'xls', 
                            'xlsx' => 'xlsx',
                            'png' => 'png',
                            'jpg' => 'jpg', 
                            'gif' => 'gif', 
                            'jpeg' => 'jpeg' 
                        ];
@endphp
<div class="row repeater-form box_file_sigle">
    <div class="table-responsive">
        <table class="table table-borderless" id="myTable">
            <thead>
                <tr>
                    <th width="30%" class="text-center">ชื่อไฟล์แนบ</th>
                    <th width="15%" class="text-center">ประเภทไฟล์</th>
                    <th width="15%" class="text-center">ขนาดไฟล์(MB)</th>
                    <th width="15%" class="text-center">ลำดับ</th>
                    <th width="5%" class="text-center">สำคัญ <br>(บังคับ)</th>
                    <th width="5%" class="text-center">สถานะ</th>
                    <th width="5%" class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody data-repeater-list="repeater-file">
                @if( isset($result->id) && count(App\Models\Config\ConfigsEvidence::where('evidence_group_id', $result->id )->whereNull('section')->get()) != 0 )
                    @php
                        $evidence = App\Models\Config\ConfigsEvidence::where('evidence_group_id', $result->id )->whereNull('section')->get();
                    @endphp

                    @foreach ( $evidence as $item )
                        @php
                            $file_properties = !empty($item->file_properties)?json_decode($item->file_properties):null
                        @endphp
                        <tr  data-repeater-item>
                            <td>
                                {!! Form::text('file_title', ( !empty($item->title)?$item->title:null ), ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                                {!! Form::hidden('file_id', ( !empty($item->id)?$item->id:null ), ['class' => 'form-control']) !!}
                            </td>
                            <td>
                                {!! Form::select('file_properties',$file_properties_list, $file_properties, ['class' => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}
                            </td>
                            <td>
                                {!! Form::text('size', ( !empty($item->size)?$item->size:null ), ['class' => 'form-control number_only', 'maxlength' => '3']) !!}
                            </td>
                            <td>
                                {!! Form::select('ordering',HP::RangeData(1,99),( !empty($item->ordering)?$item->ordering:null ), ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}
                            </td>
                            <td>
                                <div class="checkbox">
                                    {!! Form::checkbox('required', '1', (!empty($item->required)?$item->required:null), ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                </div>
                            </td>
                            <td>
                                <div class="checkbox">
                                    {!! Form::checkbox('state', '1', (!empty($item->state)?$item->state:null), ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-xs" type="button" data-repeater-delete>
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                @else

                    <tr  data-repeater-item>
                        <td>
                            {!! Form::text('file_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! Form::hidden('file_id', null, ['class' => 'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::select('file_properties',$file_properties_list,null, ['class' => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}
                        </td>
                        <td>
                            {!! Form::text('size', null, ['class' => 'form-control number_only', 'maxlength' => '3']) !!}
                        </td>
                        <td>
                            {!! Form::select('ordering',HP::RangeData(1,99),null, ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}
                        </td>
                        <td>
                            <div class="checkbox">
                                {!! Form::checkbox('required', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="checkbox">
                                {!! Form::checkbox('state', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-xs" type="button" data-repeater-delete>
                                <i class="fa fa-close"></i>
                            </button>
                        </td>
                    </tr>

                    
                @endif

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"></td>
                    <td>
                        <button class="btn btn-success btn-xs pull-right button_remove_detail" data-repeater-create type="button"><i class="fa fa-plus"></i>
                            เพิ่ม
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="row box_file_multile">
    <div class="col-md-12">
        <div class="row gen_html_multi">

            @if( isset($result->id) && count(App\Models\Config\ConfigsEvidence::where('evidence_group_id', $result->id )->whereNotNull('section')->select('section')->groupBy('section')->get()) != 0 )

                @php
                    $section_box = App\Models\Config\ConfigsEvidence::where('evidence_group_id', $result->id )->whereNotNull('section')->select('section')->groupBy('section')->get();
                @endphp

                @foreach ( $section_box as $box )

                    <div class="table-responsive repeater-multi white-box">
                        <h4>
                            ไฟล์ชุดที่ {!! $box->section !!}
                            <button class="pull-right btn btn-danger btn_section_remove" type="button">ลบชุดไฟล์</button>
                        </h4>

                        <div class="clearfix"></div>
                        {!! Form::hidden('section_box[]',  $box->section  , ['class' => 'form-control']) !!}

                        <table class="table table-borderless table_multiples inner-repeater" id="table-group-{!! $box->section !!}">
                            <thead>
                                <tr>
                                    <th width="30%" class="text-center">ชื่อไฟล์แนบ</th>
                                    <th width="15%" class="text-center">ประเภทไฟล์</th>
                                    <th width="15%" class="text-center">ขนาดไฟล์(MB)</th>
                                    <th width="15%" class="text-center">ลำดับ</th>
                                    <th width="5%" class="text-center">สำคัญ <br>(บังคับ)</th>
                                    <th width="5%" class="text-center">สถานะ</th>
                                    <th width="5%" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="repeater-group-{!! $box->section !!}">
                                @php
                                    $evidence_section = App\Models\Config\ConfigsEvidence::where('evidence_group_id', $result->id )->where('section', $box->section )->whereNotNull('section')->get();
                                @endphp

                                @foreach ( $evidence_section as $item )
                                    @php
                                        $file_properties = !empty($item->file_properties)?json_decode($item->file_properties):null
                                    @endphp
                                    <tr data-repeater-item>
                                        <td>
                                            {!! Form::text('file_title', ( !empty($item->title)?$item->title:null ), ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                                            {!! Form::hidden('file_id', ( !empty($item->id)?$item->id:null ), ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('file_properties',$file_properties_list, $file_properties, ['class' => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('size', ( !empty($item->size)?$item->size:null ), ['class' => 'form-control number_only', 'maxlength' => '3']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('ordering',HP::RangeData(1,99),( !empty($item->ordering)?$item->ordering:null ), ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                {!! Form::checkbox('required', '1', (!empty($item->required)?$item->required:null), ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                {!! Form::checkbox('state', '1', (!empty($item->state)?$item->state:null), ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button" data-repeater-delete>
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                   
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right"></td>
                                    <td>
                                        <button class="btn btn-success btn-xs pull-right btn_repeater_add" type="button" data-table="table-group-{!! $box->section !!}"><i class="fa fa-plus"></i>
                                            เพิ่ม
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                @endforeach

            @else

                <div class="table-responsive repeater-multi white-box">
                    <h4>ไฟล์ชุดที่ 1 <button class="pull-right btn btn-danger btn_section_remove" type="button">ลบชุดไฟล์</button></h4>
                    {!! Form::hidden('section_box[]', '1' , ['class' => 'form-control']) !!}
                    <table class="table table-borderless table_multiples inner-repeater" id="table-group-1">
                        <thead>
                            <tr>
                                <th width="30%" class="text-center">ชื่อไฟล์แนบ</th>
                                <th width="15%" class="text-center">ประเภทไฟล์</th>
                                <th width="15%" class="text-center">ขนาดไฟล์(MB)</th>
                                <th width="15%" class="text-center">ลำดับ</th>
                                <th width="5%" class="text-center">สำคัญ <br>(บังคับ)</th>
                                <th width="5%" class="text-center">สถานะ</th>
                                <th width="5%" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody data-repeater-list="repeater-group-1">
                            <tr  data-repeater-item>
                                <td>
                                    {!! Form::text('file_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                                    {!! Form::hidden('file_id', null, ['class' => 'form-control']) !!}
                                </td>
                                <td>
                                    {!! Form::select('file_properties',$file_properties_list,null, ['class' => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}
                                </td>
                                <td>
                                    {!! Form::text('size', null, ['class' => 'form-control number_only', 'maxlength' => '3']) !!}
                                </td>
                                <td>
                                    {!! Form::select('ordering',HP::RangeData(1,99),null, ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}
                                </td>
                                <td>
                                    <div class="checkbox">
                                        {!! Form::checkbox('required', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox">
                                        {!! Form::checkbox('state', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state' ]) !!}
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-xs btn_repeater_delete" type="button" >
                                        <i class="fa fa-close"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"></td>
                                <td>
                                    <button class="btn btn-success btn-xs pull-right btn_repeater_add" type="button" data-table="table-group-1"><i class="fa fa-plus"></i>
                                        เพิ่ม
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            @endif

        </div>
        <div class="row">
            <button class="btn btn-success pull-right btn_gen_multiple"type="button"><i class="fa fa-plus"></i>
                เพิ่มชุดไฟล์แนบ
            </button>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('configs-evidence-groups'))
            <a class="btn btn-default show_tag_a" href="{{url('/config/evidence/group')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script>

        $(document).ready(function () {

            $('.repeater-form').repeater({
                show: function () {
                    $(this).slideDown();
           
                    reBuiltSelect2($(this).find('select'));
                    data_list_disabled();
                    $(this).find('.input_state').each(function() {
                        new Switchery($(this)[0], $(this).data());
                    });
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                       
                        }, 400);
                    }
                }
            });

            $(".input_state").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $(".number_only").on("keypress keyup blur",function (event) {    
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $('#condition_1').on('ifChecked', function(event){
                BoxCondition();
            });
            $('#condition_2').on('ifChecked', function(event){
                BoxCondition();
            });

            BoxCondition();

            $('.btn_gen_multiple').click(function (e) { 
                GenTableHtml();
            });

            $('.repeater-multi').repeater();

            $('body').on( 'click', '.btn_repeater_add',function (e) { 
                var tb = $(this).data('table');

                if( tb != '' ){

                    var html  = '<tr data-repeater-item>';
                        html += '<td>{!! Form::text('file_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} {!! Form::hidden('file_id', null, ['class' => 'form-control']) !!}</td>';
                        html += '<td>{!! Form::select('file_properties',$file_properties_list,null, ["class" => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}</td>';
                        html += '<td>{!! Form::text('size', null, ['class' => 'form-control number_only', 'maxlength' => '3']) !!}</td>';
                        html += '<td>{!! Form::select('ordering',HP::RangeData(1,99),null, ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}</td>';
                        html += '<td><div class="checkbox">{!! Form::checkbox('required', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state_multi' ]) !!}</div></td>';
                        html += '<td><div class="checkbox">{!! Form::checkbox('state', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state_multi' ]) !!}</div></td>';
                        html += '<td><button class="btn btn-danger btn-xs btn_repeater_delete" type="button"><i class="fa fa-close"></i></button></td>';
                        html += '</tr>';


                    $('#'+ tb +' tbody').append(html);   

                    var last_row = $('#'+ tb +' tbody').children('tr:last');

                        last_row.find('input, hidden').val(''); 

                        last_row.find('select').val('');  
                        last_row.find('select').prev().remove();
                        last_row.find('select').removeAttr('style');
                        last_row.find('select').select2();

                        last_row.find(".input_state_multi").each(function() {
                            new Switchery($(this)[0], $(this).data());
                        });

                    $('.repeater-multi').repeater();

                }
                
            });

            $('body').on( 'click', '.btn_repeater_delete',function (e) { 
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).parent().parent().remove();
                    setTimeout(function(){
                        $('.repeater-multi').repeater();
                    }, 400);
                }
            });

            $('body').on( 'click', '.btn_section_remove',function (e) { 
                if (confirm('คุณต้องการลบไฟล์ชุดนี้ ?')) {
                    $(this).parent().parent().remove();
                    setTimeout(function(){
                        $('.repeater-multi').repeater();
                        BtnRemoveSection();
                    }, 400);
                }
            });

            BtnRemoveSection();

        });

        function BtnRemoveSection(){
            $('.table_multiples').length>1?$('.btn_section_remove').show():$('.btn_section_remove').hide();
        }

        function GenTableHtml(){

            var length =  $('.table_multiples').length;

            var number = length > 0 ?(length+1):1;
            var html = '<div class="table-responsive repeater-multi white-box">';
                html += '<h4>ไฟล์ชุดที่ '+( length > 0 ?(length+1):1 )+'<button class="pull-right btn btn-danger btn_section_remove" type="button">ลบชุดไฟล์</button></h4>';
                html += '<input type="hidden" name="section_box[]" value="'+ number +'" class="form-control" >';
                html += '<table class="table table-borderless table_multiples inner-repeater"  id="table-group-'+( length > 0 ?(length+1):1 )+'">';
                html += '<thead>';
                html += '<tr>';
                html += '<th width="30%" class="text-center">ชื่อไฟล์แนบ</th>';
                html += '<th width="15%" class="text-center">ประเภทไฟล์</th>';
                html += '<th width="15%" class="text-center">ขนาดไฟล์(MB)</th>';
                html += '<th width="15%" class="text-center">ลำดับ</th>';
                html += '<th width="5%" class="text-center">สำคัญ <br>(บังคับ)</th>';
                html += '<th width="5%" class="text-center">สถานะ</th>';
                html += '<th width="5%" class="text-center">จัดการ</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody data-repeater-list="repeater-group-'+( length > 0 ?(length+1):1 )+'">';
                html += '<tr data-repeater-item>';
                html += '<td>{!! Form::text('file_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!} {!! Form::hidden('file_id', null, ['class' => 'form-control']) !!}</td>';
                html += '<td>{!! Form::select('file_properties',$file_properties_list,null, ["class" => 'select2-multiple','data-placeholder' => '-ประเภทไฟล์-', 'multiple' => 'multiple', 'required' => 'required']) !!}</td>';
                html += '<td>{!! Form::text('size', null, ['class' => 'form-control number_only', 'maxlength' => '3']) !!}</td>';
                html += '<td>{!! Form::select('ordering',HP::RangeData(1,99),null, ['class' => 'form-control ordering_list','placeholder' => '-ลำดับ-', 'required' => 'required']) !!}</td>';
                html += '<td><div class="checkbox">{!! Form::checkbox('required', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state_multi' ]) !!}</div></td>';
                html += '<td><div class="checkbox">{!! Form::checkbox('state', '1', '1', ['data-color'=>'#13dafe','class' => 'input_state_multi' ]) !!}</div></td>';
                html += '<td><button class="btn btn-danger btn-xs btn_repeater_delete" type="button"><i class="fa fa-close"></i></button></td>';
                html += '</tr>';
                html += '</tbody>';
                html += '<tfoot>';
                html += '<tr>';
                html += '<td colspan="6" class="text-right"></td>';
                html += '<td><button class="btn btn-success btn-xs pull-right btn_repeater_add"  data-table="table-group-'+( length > 0 ?(length+1):1  )+'" type="button"><i class="fa fa-plus"></i>เพิ่ม </button></td>';
                html += '</tr>';
                html += '</tfoot>';
                html += '</table>';
                html += '</div>';

            $('.gen_html_multi').append(html);

            $('.table_multiples:last').find('select').select2();

            $('.table_multiples:last').find(".input_state_multi").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $('.repeater-multi').repeater();
            BtnRemoveSection();
        }

        function BoxCondition(){
           var condition_1 = $('#condition_1:checked').val();
           var condition_2 = $('#condition_2:checked').val();

            if( $('#condition_1').is(':checked',true) ){

                $('.box_file_sigle').show();
                $('.box_file_sigle').find('input, select, hidden, checkbox').prop('disabled', false);
                // $(".input_state").prop('checked', false).trigger("click");

                $('.box_file_multile').hide();
                $('.box_file_multile').find('input, select, hidden, checkbox').prop('disabled', true);
                $('.box_file_multile').find('input, select, hidden, checkbox').prop('required', false);

            }else if( $('#condition_2').is(':checked',true) ){

                $('.box_file_sigle').hide();
                $('.box_file_sigle').find('input, select, hidden, checkbox').prop('disabled', true);
                $('.box_file_sigle').find('input, select, hidden, checkbox').prop('required', false);
                // $(".input_state").prop('checked', true).trigger("click");

                $('.box_file_multile').show();
                $('.box_file_multile').find('input, select, hidden, checkbox').prop('disabled', false);

            }
        }

        function data_list_disabled(){
            $('.ordering_list').children('option').prop('disabled',false);
            $('.ordering_list').each(function(index , item){
                var data_list = $(item).val();
                $('.ordering_list').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
            });
        }

        function reBuiltSelect2(select){
            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }
    </script>
@endpush