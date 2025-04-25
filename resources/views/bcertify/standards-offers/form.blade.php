@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    
<style type="text/css">
    .panel-body-info {
        border: #00bbd9 1px solid;
    }
    .not-allowed {cursor: not-allowed;}
  </style>
@endpush

<b>รายละเอียดมาตรฐาน</b>
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', 'ชื่อเรื่อง'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
        {!! Form::text('title', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('title_eng') ? 'has-error' : ''}}">
    {!! Form::label('title_eng', 'ชื่อเรื่อง (Eng)'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('title_eng', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('title_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>
{{-- <div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Form::label('std_type', 'ประเภทมาตรฐาน'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
         {!! Form::select('std_type',
               App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(offertype USING tis620)')->pluck('offertype', 'id'), 
              null,
              ['class' => 'form-control',
              'id'=>'std_type',
              'disabled'=> true,
              'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}
<div class="form-group {{ $errors->has('scope') ? 'has-error' : ''}}">
    {!! Form::label('scope', 'ขอบข่าย'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
       {{-- {!! Form::textarea('scope', null, [ 'rows' => 2,'cols'=>'110','disabled'=>true]) !!} --}}
       {!! Form::text('scope', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('scope', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('objectve') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('objectve', 'จุดประสงค์และเหตุผล'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
       {{-- {!! Form::textarea('objectve', null, [ 'rows' => 2,'cols'=>'110','disabled'=>true]) !!} --}}
       {!! Form::text('objectve', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('objectve', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('stakeholders') ? 'has-error' : ''}}">
    {!! Form::label('stakeholders', 'ผู้มีส่วนได้เสียที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('stakeholders', $estandardoffers->stakeholders ?? '(ไม่มี)',  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('stakeholders', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('stakeholders') ? 'has-error' : ''}}">
    {!! Form::label('stakeholders', 'เอกสารเพิ่มเติม'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        @php
            $attach = $estandardoffers->AttachFileAttachFileTo;
        @endphp
        @if (!empty($attach))
            {!! !empty($attach->caption) ? $attach->caption : '' !!}
              <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                 {!! !empty($attach->filename) ? $attach->filename : '' !!}
            </a>
         @else 
            {!! Form::label('stakeholders', '(ไม่มี)', ['class' => 'control-label', 'style' => 'text-align: left; color: black !important;']) !!}
         @endif 
        {{-- @if($estandardoffers->attach_new !='' && HP::checkFileStorage($estandardoffers->path.$estandardoffers->attach_new))
        <a class="btn btn-info btn-xs" href="{{ HP::getFileStorage($estandardoffers->path.$estandardoffers->attach_new) }}" target="_blank" >
                <i class="mdi mdi-download"></i>
        </a>
               {{$estandardoffers->caption}}
       @endif --}}
    </div>
</div>

<b>ผู้ยื่นข้อเสนอ (Proposer)</b>
<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('name', 'ผู้ประสานงาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
        {!! Form::text('name', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('department') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('department', 'ชื่อหน่วยงาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
        {!! Form::select('department_id',
        App\Models\Basic\Department::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
        null,
        ['class' => 'form-control',
        'id'=>'department_id',
        'disabled'=>true,
     'placeholder'=>'- เลือกชื่อหน่วยงาน -']) !!}
        {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('address', 'ที่อยู่หน่วยงาน'.' : ', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        {!! Form::text('address', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('telephone', 'เบอร์โทรศัพท์'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
        {!! Form::text('telephone', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('email', 'อีเมล'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-9">
        {!! Form::text('email', null,  ['class' => 'form-control','disabled'=>true]) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!--.row-->
<div class="row" id="input_disabled">
    <div class="col-md-12">
        <div class="panel panel-info ">
            <div class="panel-heading"> ส่วนพิจารณา</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body panel-body-info">

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'สถานะการพิจารณา'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        {!! Form::select('state',
         [
            '1' => 'เสนอความเห็น',
            '2' => 'สมควรบรรจุในแผน',
            '3' => 'ไม่สมควรบรรจุในแผน'
        ], 
        null,
        ['class' => 'form-control text-center',
        'id'=>'state',
        'required'=>true,
             'placeholder'=>'- เลือกเสนอความคิกเห็น -']) !!}
        {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('standard_types') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('standard_types', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        {!! Form::select('standard_types',
        // HP::StandardTypes(), 
        App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(offertype USING tis620)')->pluck('offertype', 'id'), 
        (!empty($estandardoffers->standard_types) ? $estandardoffers->standard_types : (!empty( $estandardoffers->std_type) ?  $estandardoffers->std_type : null)  ),
        ['class' => 'form-control text-center',
        'id'=>'standard_types',
        'required'=>true,
        'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('standard_types', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('refno') ? 'has-error' : ''}}" id="div_refno">
    {!! Html::decode(Form::label('refno', 'รหัสความเห็น'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        {!! Form::text('refno', null,  ['class' => 'form-control not-allowed','readonly'=>true]) !!}
        {!! $errors->first('refno', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('details', 'รายละเอียด'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
         {!! Form::textarea('details', null, [ 'rows' => 2,'cols'=>'40','required'=>false]) !!} 
        {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารแนบเพิ่มเติม'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        @php
            $attach = $estandardoffers->AttachFileAttachTo;
        @endphp
        @if (!empty($attach))
            {!! !empty($attach->caption) ? $attach->caption : '' !!}
              <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                 {!! !empty($attach->filename) ? $attach->filename : '' !!}
            </a>
        @else
            @if($estandardoffers->state == 1)
                <div class=" other_attach_item">
                    <div class="col-md-6 text-light">
                        {!! Form::text('document_details', null, ['class' => 'form-control ', 'placeholder' => 'รายละเอียดเอกสาร']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="attach" class="attach check_max_size_file" >
                                </span> 
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                        {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            @else
                {!! Form::label('stakeholders', '(ไม่มี)', ['class' => 'control-label', 'style' => 'text-align: left; color: black !important;']) !!}
            @endif
        @endif
    </div>
</div>


<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'วันที่พิจารณา'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-6">
        {{ !empty($estandardoffers->user_updated->FullName) ?  HP::DateTimeFullThai($estandardoffers->updated_at)  : HP::DateTimeFullThai(date('Y-m-d H:i:s')) }}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'ผู้พิจารณา'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-6">
        {{ !empty($estandardoffers->user_updated->FullName) ?  $estandardoffers->user_updated->FullName : auth()->user()->FullName }}
    </div>
</div>

 


                </div>
            </div>
        </div>
    </div>
</div>
<!--./row-->


@if( !empty($estandardoffers) && $estandardoffers->state != 1 && $estandardoffers->standard_types != '') 
<div class="clearfix"></div>
   <a  href="{{ url(app('url')->previous()) }}"  class="btn btn-default btn-lg btn-block">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
 @php
     $checkstate = 'true';
 @endphp
@else 
@php
    $checkstate = 'false';
@endphp
 <div class="form-group div_hide">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standardsoffers'))
            <a class="btn btn-default" href="{{url('/bcertify/standards-offers')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
@endif 

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#state').change(function(){ 
            checkrefnos();
        });
        $('#standard_types').change(function(){ 
            checkrefnos();
        });
        $('#div_refno').hide();
        var checkstate = "{{ $checkstate }}";
        var state          = $('#state').val(); 

        if(checkstate == 'true'){
            $('#input_disabled').find('input, textarea, select, hidden, fileinput').prop('disabled',true);
            if(state == '2'){
                $('#div_refno').show();
            }
        }
     
        // checkrefnos();
    });

    function  checkrefnos() {
         var state          = $('#state').val(); 
         var standard_types = $('#standard_types').val(); 
 
         if(state == '2' && standard_types != ''){
                $('#div_refno').show();
                const _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{url('bcertify/standards-offers/data_refno')}}",
                method:"POST",
                data:{state:state,standard_types:standard_types,_token:_token},
                success:function (result){
                    if(result.refno.length){
                            $('#refno').val(result.refno);
                    }
                }
            })
         }else{
                $('#div_refno').hide();
         }
    }

    
 </script>

@endpush
