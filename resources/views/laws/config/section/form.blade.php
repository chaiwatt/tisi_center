@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush


<div class="form-group  required{{ $errors->has('section_id') ? 'has-error' : ''}}">
    {!! Form::label('section_id', 'อัตราโทษ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('section_id', App\Models\Law\Basic\LawSection::Where('state',1)->where('section_type',2)->orderbyRaw('CONVERT(title USING tis620)')->pluck('number', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือก -', 'required' => true, 'id' => 'section_id']) !!}
        {!! $errors->first('section_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group{{ $errors->has('section_title') ? 'has-error' : ''}}">
    {!! Form::label('section_title', 'คำอธิบาย', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 ">
        {!! Form::textarea('section_title', null , ['class' => 'form-control ', 'disabled' => true, 'rows'=>'4','id'=>'section_title' ]) !!}
        {!! $errors->first('section_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  required{{ $errors->has('power') ? 'has-error' : ''}}">
    {!! Form::label('power', 'อำนาจเปรียบเทียบปรับ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('power',['1'=>'เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม (สมอ)','2'=>'คณะกรรมการเปรียบเทียบ','3'=>'ปรับเป็นพินัย'], null, ['class' => 'form-control ', 'placeholder'=>'- เลือกมาตรา -', 'required' => true]) !!}
        {!! $errors->first('power', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('section_relation') ? 'has-error' : ''}}" >
    {!! Form::label('section_relation', 'มาตราที่เกี่ยวข้อง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-5">
        {!! Form::select('section_relation[]',
                        App\Models\Law\Basic\LawSection::Where('state',1)->where('section_type',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('number', 'id'),
                        null, 
                        ['class' => 'select2-multiple ',
                        'multiple'=>'multiple', 
                        'required'=>'required', 
                        'id'=>'section_relation', 
                        'data-placeholder' => '-เลือกมาตราที่เกี่ยวข้อง-']); !!}
        {!! $errors->first('section_relation', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-primary btn-sm btn-outline waves-effect waves-light" id="btn_section_relation">
            <i class="fa fa-search btn_search"></i> ดูคำอธิบาย
        </button>
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}" id="box_myTable_section_relation" style="display:none;">
    <div class="col-md-4"></div>
    <div class="col-md-6">
        <table class="table table-bordered" id="myTable_section_relation">
            <thead class="bg-primary">
            <tr>
                <th class="text-center text-white" width="5%">#</th>
                <th class="text-center text-white" width="15%">มาตรา</th>
                <th class="text-center text-white" width="80%">คำอธิบาย</th>
            </tr>
            </thead>
            <tbody id="tbody_section_relation">
               
            </tbody>
        </table>
    </div>
</div>

<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($config_section->created_by)? $config_section->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($config_section->created_at)? HP::revertDate($config_section->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>



<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-config-sections'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/config/sections')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function() {

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

        $('#section_id').change(function(){ 
            
            if($(this).val() != ''){
                $.ajax({
                    url: "{!! url('law/config/sections/basic_section') !!}" + "/" +  $(this).val()
                }).done(function( object ) {
                    $('#section_title').val(object.title);
                });
            }else{
                $('#section_title').val('-');
            }
        });

        $('#btn_section_relation').click(function(){  
            $("#box_myTable_section_relation").toggle(400);
            LoadSectionRelation();
        });

        $('#section_relation').change(function(){  
            LoadSectionRelation();
        });

    });

    function LoadSectionRelation(){
            var ids   = $('#section_relation').val();
            var table = $('#tbody_section_relation');
           
            if (ids.length > 0) {
                    table.html('');
                    $.ajax({
                        method: "POST",
                        url: "{{ url('law/config/sections/section-relation') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "section_id": ids
                        }
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {

                                var html_add_item = '<tr>';
                                    html_add_item += '<td class="text-center">' + (index+1) + '</td>';
                                    html_add_item += '<td class="text-center">' + data.number + '</td>';
                                    html_add_item += '<td>' + data.title + '</td>';
                                    html_add_item += '</tr>';
                                    
                                table.append(html_add_item);

                        });

                    });

            }
        }
</script>

@endpush