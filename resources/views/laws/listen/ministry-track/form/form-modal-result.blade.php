@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="row form-group">
    {!! HTML::decode(Form::label('status_diagnosis', 'ผลวินิจฉัย', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
    <div class="col-md-7">
        {!! Form::select('status_diagnosis',App\Models\Law\Listen\LawListenMinistry::list_status_diagnosis(), null, ['class' => 'form-control  text-center', 'id' => 'status_diagnosis']); !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('date_diagnosis') ? 'has-error' : ''}}">
    {!! Form::label('date_diagnosis', 'วันที่วินิจฉัย'.':', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        <div class="inputWithIcon">
            {!! Form::text('date_diagnosis', null, ('required' == 'required') ? ['class' => 'form-control date-range',
            'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control date-range', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
            {!! $errors->first('date_diagnosis', '<p class="help-block">:message</p>') !!}
             <i class="icon-calender"></i>
        </div>
    </div>
</div>
<div class="row form-group required">
    {!! Form::label('file_result', 'หนังสือแจ้งผล'.':', ['class' => 'col-md-3 control-label text-right']) !!}
  <div class="col-md-7">
    @if (!empty($lawlistministry->AttachFileResult))
    @php
        $attachs_result= $lawlistministry->AttachFileResult;
    @endphp
   <a href="{!! HP::getFileStorage($attachs_result->url) !!}" target="_blank">{!! !empty($attachs_result->filename) ? $attachs_result->filename : '' !!}</a>
   {!! HP::FileExtension($attachs_result->url) ?? '' !!}
    @endif
  </div>
</div>
 <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-8">
        <input type="checkbox" class="check send_mail" id="send_mail"  value="1" name="send_mail" data-checkbox="icheckbox_square-green">
            <label for="send_mail">แจ้งเตือนไปยังอีเมลผู้แสดงความคิดเห็น</label>
    </div>
</div> 

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script>
     $(document).ready(function() {
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

        $(".amount").on("keypress",function(e){
            var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                }
          });

        $('#sign_id').change(function(){ 
            if($(this).val() != ''){
                $.ajax({
                    url: "{!! url('certify/certificate-export-cb/sign_position') !!}" + "/" +  $(this).val()
                }).done(function( object ) {
                    $('#sign_position').val(object.sign_position);
                });
            }else{
                $('#sign_position').val('-');
            }
        });
    });
  </script>
@endpush