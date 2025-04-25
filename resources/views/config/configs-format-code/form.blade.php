@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endpush


<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('system', 'ระบบงาน', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('system',  HP::ApplicationSytemConfig(), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'disabled' => (isset($result->id)?true:false  ) , 'placeholder'=>'- เลือกระบบงานหลัก -'] : ['class' => 'form-control']) !!}
        {!! $errors->first('system', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@if( isset($result->id) )
    {!! Form::hidden('system', $result->system , ['class' => 'form-control']) !!}

    <div class="form-group ">
        {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-6">
            <button class="btn btn-link" type="button" data-toggle="modal" data-target="#Mhistrory">ดูประวัติเปลี่ยนแปลงเลขรัน</button>
        </div>
    </div>

    @include ('config.configs-format-code.modal')
@endif


@php
    $list_type = [ 
                    'character' => 'อักษรนำ', 
                    'separator' => 'อักษรคั่น', 
                    'month' => 'เดือน', 
                    'year-be' => 'ปี พ.ศ.', 
                    'year-bf' => 'ปี พ.ศ.ตามปีงบประมาณ', 
                    'year-ac' => 'ปี ค.ศ.', 
                    'no' => 'เลขรัน',  
                    'tisi_shortnumber' => 'เลขที่มอก.', 
                    'tisi_number' => 'รหัสมาตรฐาน', 
                    'application_type' => 'ประเภทใบสมัคร'
                ];
@endphp

<fieldset class="white-box">
    <legend class="legend"><h4>รูปแบบเลขรัน</h4></legend>

    <div class="form-group form-fotmat">
        <div class="col-md-12 box_format_html" data-repeater-list="form-fotmat">

            @if( isset($result->id) && count( App\Models\Config\ConfigsFormatCodeSub::where('format_id', $result->id)->get() ) > 0 )

                @php
                    $sub_data = App\Models\Config\ConfigsFormatCodeSub::where('format_id', $result->id)->get();
                @endphp

                @foreach ( $sub_data as $item )

                    <div class="form-group required" data-repeater-item >
                        
                        {!! Html::decode(Form::label('format', 'รูปแบบลำดับ '.'<span class="format_on">1</span>', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                {!! Form::select('format', $list_type  , !empty($item->format)?$item->format:null ,  ['class' => 'form-control select_gen_input', 'required' => 'required', 'placeholder'=>'-เลือกรูปแบบ-'] ) !!}
                            </div>
                            <div class="col-md-2 col-sm-12 gen_input_1">
                                @if( $item->format == 'character')
                                    {!! Form::text("data", !empty($item->data)?$item->data:null, ["class" => "form-control character", "required" => "required", "placeholder"=>"ระบุ"] ) !!}
                                @elseif (  $item->format == 'separator' )
                                    {!! Form::text("data", !empty($item->data)?$item->data:null, ["class" => "form-control separator", "required" => "required", "placeholder"=>"-ระบุ-"] ) !!}
                                @elseif (  $item->format == 'month' )
                                    
                                @elseif (  $item->format == 'year-be'  ||  $item->format == 'year-bf' || $item->format == 'year-ac' )
                                    {!! Form::select("data", [ "2" => "2 หลัก", "4" => "4 หลัก" ], !empty($item->data)?$item->data:null, ["class" => "form-control select_year_type", "required" => "required"] ) !!}
                                @elseif (  $item->format == 'no' )
                                    {!! Form::text("data", !empty($item->data)?$item->data:null ,["class" => "vertical-spin text-right input_number", "required" => "required", "data-bts-button-down-class" => "btn btn-default btn-outline","data-bts-button-up-class" => "btn btn-default btn-outline"])!!}
                                @elseif (  $item->format == 'tisi_shortnumber' )
                                    <span>EX : XXX</span>
                                @elseif (  $item->format == 'tisi_number' )
                                    <span>EX : XXX-XXXX</span>
                                @elseif (  $item->format == 'application_type' )
                                    <span>EX : APPTYPE</span>
                                @endif
            
                            </div>
                            <div class="col-md-2 col-sm-12 gen_input_2">
                                @if( $item->format == 'no')
                                    {!! Form::select("sub_data", [ "o" => "รันต่อเนื่องจากเดิม", "m" => "เริ่มรันใหม่ทุกเดือน", "y" => "เริ่มรันใหม่ทุกปี" ], !empty($item->sub_data)?$item->sub_data:null  , ["class" => "form-control select_number_type", "required" => "required"] ) !!}
                                @endif
                            </div>
                            <div class="col-md-1 col-sm-12">
                                <button class="btn btn-danger btn_remove" type="button">ลบ</button>
                            </div>
                        </div>
                    </div>
                    
                @endforeach
                
            @else

                <div class="form-group required" data-repeater-item >
                    
                    {!! Html::decode(Form::label('format', 'รูปแบบลำดับ '.'<span class="format_on">1</span>', ['class' => 'col-md-2 control-label'])) !!}
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            {!! Form::select('format', $list_type  , null,  ['class' => 'form-control select_gen_input', 'required' => 'required', 'placeholder'=>'-เลือกรูปแบบ-'] ) !!}
                        </div>
                        <div class="col-md-2 col-sm-12 gen_input_1">
        
                        </div>
                        <div class="col-md-2 col-sm-12 gen_input_2">
        
                        </div>
                        <div class="col-md-1 col-sm-12">
                            <button class="btn btn-danger btn_remove" type="button">ลบ</button>
                        </div>
                    </div>
                </div>

            @endif
    

            
    
        </div>
        <div class="col-md-offset-2 col-md-10">
            <button type="button" class="btn btn-success pull-left btn_gen_item"><i class="icon-plus"></i>เพิ่ม</button>
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::label('example', 'ตัวอย่าง', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('example', null, ['class' => 'form-control', 'disabled' => true ]) !!}
        </div>
    </div>

</fieldset>




<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('configs-format-code'))
            <a class="btn btn-default show_tag_a" href="{{url('/config/format-code')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>

    <script>
        jQuery(document).ready(function() { 

            @if(\Session::has('error_message'))
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกไม่สำเร็จ',
                    text: 'ระบบงานที่บันทึกยังเปิดใช้งานไม่สามารถบันทึกซ้ำได้',
                    confirmButtonText:'รับทราบ'
                });
            @endif

            $(".vertical-spin").TouchSpin({
                verticalbuttons: true,
                verticalupclass: 'ti-plus',
                verticaldownclass: 'ti-minus',
                postfix: "หลัก"
            });

            $('.form-fotmat').repeater();


            $('body').on('change', '.select_gen_input', function () {

                var row = $(this).parent().parent();
                var type = $(this).val();

                row.find('.gen_input_1').html('');
                row.find('.gen_input_2').html('');

                if( type != ''){

                    $(this).removeClass('new_select');

                    var character = '{!! Form::text("data", null, ["class" => "form-control character", "required" => "required", "placeholder"=>"ระบุ"] ) !!}';
                    var separator = '{!! Form::text("data", null, ["class" => "form-control separator", "required" => "required", "placeholder"=>"-ระบุ-"] ) !!}';

                    var year_type = '{!! Form::select("data", [ "2" => "2 หลัก", "4" => "4 หลัก" ], 2, ["class" => "form-control select_year_type", "required" => "required"] ) !!}';

                    var number = '{!! Form::text("data",null,["class" => "vertical-spin text-right input_number", "required" => "required", "data-bts-button-down-class" => "btn btn-default btn-outline","data-bts-button-up-class" => "btn btn-default btn-outline"])!!}';

                    var number_type = '{!! Form::select("sub_data", [ "o" => "รันต่อเนื่องจากเดิม", "m" => "เริ่มรันใหม่ทุกเดือน", "y" => "เริ่มรันใหม่ทุกปี" ], 2, ["class" => "form-control select_number_type", "required" => "required"] ) !!}';

                    if( type == 'character' ){
                        row.find('.gen_input_1').append(character);
                    }else if( type == 'separator' ){
                        row.find('.gen_input_1').append(separator);
                    }else if( type == 'year-be' ||  type == 'year-bf' ||  type == 'year-ac'  ){
                        row.find('.gen_input_1').append(year_type);

                        //set 2 หลัก
                        reBuiltSelect2(row.find('select.select_year_type'), 2);

                    }else if( type == 'no' ){
                        row.find('.gen_input_1').append(number);
                        row.find('.gen_input_2').append(number_type);

                        reBuiltVertical(  row.find('input.vertical-spin') );

                        //set รันต่อเนื่องจากเดิม
                        reBuiltSelect2(row.find('select.select_number_type'), "o");
                    }else if( type == 'tisi_shortnumber'  ){
                        var ex = '<span>EX : XXX</span>';
                        row.find('.gen_input_1').append(ex);
                    }else if( type == 'tisi_number'  ){
                        var ex = '<span>EX : XXX-XXXX</span>';
                        row.find('.gen_input_1').append(ex);
                    }else if( type == 'application_type'  ){
                        var ex = '<span>EX : APPTYPE</span>';
                        row.find('.gen_input_1').append(ex);
                    }
                    
                    $('.form-fotmat').repeater();

                }
                resetOrderNo();
                LoadExample();
                SelectDisabled();
            });

            $('.btn_gen_item').click(function (e) { 

                $('body').find('select').removeClass('new_select');

                var input_type = '{!! Form::select("format", $list_type  , null,  ["class" => "form-control select_gen_input new_select", "required" => "required", "placeholder" => "-เลือกรูปแบบ-"] ) !!}';

                var btn = '<button class="btn btn-danger btn_remove" type="button">ลบ</button>';

                var class_span = '';

                var html = '<div class="form-group required" data-repeater-item >';
                    html += '<label for="format" class="col-md-2 control-label">รูปแบบลำดับ <span class="format_on">1</span></label>';
                    html += '<div class="row">';

                    html += '<div class="col-md-3 col-sm-12">'+(input_type)+'</div>';
                    html += '<div class="col-md-2 col-sm-12 gen_input_1"></div>';
                    html += '<div class="col-md-2 col-sm-12 gen_input_2"></div>';
                    html += '<div class="col-md-1 col-sm-12">'+(btn)+'</div>';

                    html += '</div>';
                    html += '</div>';

                $('.box_format_html').append(html);

                reBuiltSelect2( $('.box_format_html').find('select.new_select') );

                $('.form-fotmat').repeater();

                resetOrderNo();

                LoadExample();
                SelectDisabled();
                
            });

            $('body').on('click', '.btn_remove', function () {

                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).parent().parent().parent().remove();
                    $('.form-fotmat').repeater();
                    LoadExample();
                    SelectDisabled();
                }

            });

            $('body').on('keyup', '.character, .separator, .input_number', function () {
                LoadExample();
            });


            $('body').on('change', '.select_year_type, .select_number_type, .input_number', function () {
                LoadExample();
            });
            

            $('body').on('blur', '.character, .separator, .input_number', function () {
                LoadExample();
            });

            

            resetOrderNo();
            LoadExample();
            SelectDisabled();
        });

        //รีเซตเลขลำดับ
        function resetOrderNo(){

            $('.format_on').each(function(index, el) {
                $(el).text(index+1);
            });

            if($('.btn_remove').length > 1){
                $('button.btn_remove').show();
            }else{
                $('button.btn_remove').hide();
            }

        }

        function reBuiltVertical( input ){

            input.TouchSpin({
                verticalbuttons: true,
                verticalupclass: 'ti-plus',
                verticaldownclass: 'ti-minus',
                postfix: "หลัก",
            });

        }

        function reBuiltSelect2(select, value ){

            var val = checkNone(value)?value:'';

            //Clear value select
            $(select).val('');
            $(select).next().remove();
            $(select).removeClass('select2-hidden-accessible');

            $(select).removeAttr('data-select2-id');
            $(select).removeAttr('tabindex');
            $(select).removeAttr('aria-hidden');
            $(select).children().removeAttr('data-select2-id');

            $(select).val(val);
            $(select).select2({
                dropdownAutoWidth: true,
                width: '100%'
            });


        }

        function LoadExample(){

            var date = new Date();

            var yearThai = date.getFullYear() + 543;
            var yearEn = date.getFullYear();
            var month = date.getMonth();

            var example = '';
            $('.box_format_html').find('.select_gen_input').each(function( index, element ) {

                var format = $(element).val();

                if( checkNone(format) ){
                    var row  = $(element).parent().parent();

                    if( checkNone( format ) && format == "character" ){
                        var txt = row.find('.character').val();
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "separator" ){
                        var txt = row.find('.separator').val();
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "month" ){
                        var txt = '0'+ ( month + 1 );
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "year-be" ){
                        var type = row.find('select.select_year_type').val();
                        var txt = checkNone(type )&& (type == "4")?String(yearThai):( String(yearThai).substring(2) );
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "year-bf" ){
                        var type = row.find('select.select_year_type').val();
                        var yaer_bf = ( ( month + 1 ) >= 10)?( yearThai + 1 ):yearThai;
                        var txt = checkNone(type )&& (type == "4")?String(yaer_bf):( String(yaer_bf).substring(2) );
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "year-ac" ){
                        var type = row.find('select.select_year_type').val();
                        var txt = checkNone(type )&& (type == "4")?String(yearEn):( String(yearEn).substring(2) );
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "no" ){
                        var input_number = row.find('input.input_number').val();
                        var number = checkNone(input_number) && (input_number > 0 )? (input_number - 1 ):0;
                        var run = "0";
                        var txt = run.repeat( number )+'1';
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "application_type" ){
                        var txt = "APPTYPE";
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "tisi_shortnumber" ){
                        var txt = "XXX";
                        example += checkNone(txt)?txt:'';
                    }

                    if( checkNone( format ) && format == "tisi_number" ){
                        var txt = "XXX-XXXX";
                        example += checkNone(txt)?txt:'';
                    }

                }


            });

            $('#example').val(example);

        }

        function SelectDisabled(){

            $('.select_gen_input').children('option').prop('disabled',false);
            $('.select_gen_input').each(function(index , item){
                var data_list = $(item).val();

                if( data_list != 'separator' ){
                    $('.select_gen_input').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                }
                
            });
        }
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>
@endpush