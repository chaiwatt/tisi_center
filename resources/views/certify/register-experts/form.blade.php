@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">

    .free-dot {
        border-bottom: thin dotted #000000;
        padding-bottom: 0px !important;
    }

    .detail-result {
        display: block;
        padding: 6px 12px;
    }

    .detail-result-underline {
        display: block;
        padding: 6px 12px;
        /* border-top: #000000 solid 1px; */
        border-bottom: #000000 solid 1px;
    }
    
    .label-height{
        line-height: 25px;
        font-size: 16px;
        font-weight: 600 !important;
        color: black !important;
    }

    .font_size{
        font-size: 10px;
    }

 .autofill {
    border-right-width: 0px !important;
    border-left-width: 0px !important;
    border-top-width: 0px !important;
    border-bottom: 1px !important;
    border-style: dotted !important;
    border-color: #585858 !important;
    background-color: #fff !important;
    /* cursor: no-drop; */
}
.label-height{
        line-height: 25px;
        font-size: 20px;
        font-weight: 600 !important;
        color: black !important;
    }

.label-height-font10{
      line-height: 25px;
      font-size: 16px;
      font-weight: 600 !important;
      color: black !important;
  }
  .label_height{
        line-height: 25px;
        font-size: 16px;
        font-weight: 600 !important;
        color: black !important;
        text-align:left;
  }
</style>
@endpush



<div id="box-readonly">


    <div class="row">
        <div class="col-md-6">
            <div class="form-group required {{ $errors->has('head_name') ? 'has-error' : ''}}">
                {!! Form::label('head_name', 'ชื่อ - นามสกุล:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                     {!! Form::text('head_name', null ,  ['class' => 'form-control autofill','id'=>"head_name", 'placeholder' => 'ชื่อ-สกุล', 'disabled' => true]) !!}
                    {!! $errors->first('head_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group  required{{ $errors->has('taxid') ? 'has-error' : ''}}">
                {!! Form::label('taxid', 'เลขประจำตัวประชาชน:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('taxid', null, ['class' => 'form-control autofill','id'=>"taxid", 'placeholder' => 'เลขประจำตัวผู้เสียภาษี', 'disabled' => true]) !!}
                    {!! $errors->first('taxid', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group  required{{ $errors->has('mobile_phone') ? 'has-error' : ''}}">
                {!! Form::label('mobile_phone', 'เบอร์โทรศัพท์:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('mobile_phone', null,  ['class' => 'form-control autofill','id'=>"mobile_phone", 'maxlength' => 25, 'disabled' => true ]) !!}
                    {!! $errors->first('mobile_phone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group required {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'E-Mail:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('email', null,  ['class' => 'form-control autofill','id'=>"email", 'maxlength' => 255, 'disabled' => true ]) !!}
                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group  required{{ $errors->has('department') ? 'has-error' : ''}}">
                {!! Form::label('department', 'หน่วยงาน/สังกัด:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('department',  !empty($registerexperts->appoint_department_to->title) ? $registerexperts->appoint_department_to->title :  null  ,  ['class' => 'form-control autofill','id'=>"department", 'maxlength' => 255, 'disabled' => true]) !!}
                    {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group  required{{ $errors->has('position') ? 'has-error' : ''}}">
                {!! Form::label('position', 'ตำแหน่ง:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('position', null,  ['class' => 'form-control autofill','id'=>"position", 'maxlength' => 255, 'disabled' => true]) !!}
                    {!! $errors->first('position', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->first('image', 'has-error') }}">
               {!! Form::label('', ''.'', ['class' => 'col-md-3 control-label']) !!}
               <div class="col-sm-8 text-center">
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                     <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                     
                        @if(!empty($registerexperts->pic_profile))
                            <img src="{{ HP::getFileStorage($registerexperts->pic_profile) }}" alt="profile pic"  id="pic_profile">    
                        @else
                            <img src="{{ asset('/images/user-placeholder.jpg') }}"  alt="profile pic" id="pic_profile" >       
                         @endif
                        
                     </div>
                 </div>
               </div>
             </div>
      </div>
    </div>
     
    <!-- ข้อมูลการติดต่อเบื้องต้น -->
    @include ('certify.register-experts.address')

    <!-- ข้อมูลกด้านการศึกษา -->
    @include ('certify.register-experts.education')

     <!-- ข้อมูลประสบการณ์ -->
     @include ('certify.register-experts.experience')

     <!-- ข้อมูลความเชี่ยวชาญ -->
     @include ('certify.register-experts.information')

</div>


<div class="row">
    <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend style="padding: 3px 10px; background-color: #20B2AA;">
            <h4 style="color: white;">พิจารณาคำขอผู้เชี่ยวชาญ</h4>
        </legend>

        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
            {!! Form::label('status', 'สถานะ:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::select('status',
                 $status,
                null,
                ['class' => 'form-control',
                'id'=>'status',
                'placeholder' =>'- เลือกสถานะ -']) !!}
                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        
        {{-- <div class="form-group {{ $errors->has('committee_specials_id') ? 'has-error' : ''}}">
            {!! Form::label('committee_specials_id', 'อ้างอิงคำสั่งที่:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::text('committee_specials_id', null,  ['class' => 'form-control autofill','id'=>"committee_specials_id", 'maxlength' => 255, 'placeholder' => 'อ้างอิงคำสั่งที่', 'disabled' => true]) !!}
                {!! $errors->first('committee_specials_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div> --}}
  

        <div class="form-group required {{ $errors->has('expert_type') ? 'has-error' : ''}}">
            {!! Form::label('expert_type', 'ความเชี่ยวชาญ:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::select('expert_type[]', 
                App\Models\Basic\ExpertGroup::pluck('title', 'id'), 
                $expert_type,
                 ['class' => 'select2-multiple',
                  'multiple'=>'multiple', 
                  'required' => true,
                  'id'=>'expert_type',
                   'data-placeholder'=>'- เลือกความเชี่ยวชาญ -']) !!}
                {!! $errors->first('expert_type', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

 

        <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
            {!! Form::label('detail', 'หมายเหตุ:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::textarea('detail', null, ['class' => 'form-control requiredDesc', 'placeholder'=>'ระบุรายละเอียดที่นี่(ถ้ามี)', 'rows'=>'5']); !!}
                {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}

            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">

                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('registerexperts'))
                <a class="btn btn-default" href="{{url('/certify/register-experts')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
                @endcan
            </div>
        </div>

    </div>

</div>

{{-- <div class="row">
    <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend style="padding: 3px 10px; background-color: #20B2AA;">
            <h4 style="color: white;">พิจารณาอนุมัติการกำหนดความเชี่ยวชาญ</h4>
        </legend>

        <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::select('state', $status,
                null,
                ['class' => 'form-control',
                'id'=>'status',
                'placeholder' =>'- เลือกสถานะ -']) !!}
                {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
            {!! Form::label('detail', 'ความเห็น:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-5">
                {!! Form::textarea('detail', null, ['class' => 'form-control requiredDesc', 'placeholder'=>'ระบุความเห็นที่นี่(ถ้ามี)', 'rows'=>'5']); !!}
                {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}

            </div>
        </div>

        <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('address_check', 'แจ้งเตือนผ่านอีเมลไปยัง <span class="text-danger"> *</span>', ['class' => 'col-md-3 control-label'])) !!}
            <div class="col-md-5">
                <div class="checkbox checkbox-success">
                    <input id="address_same_head" class="address_same_head" type="checkbox" name="address_same_head">
                    <label for="address_same_head"> &nbsp;ผู้เชี่ยวชาญ&nbsp;</label>
                </div>
                <div class="checkbox checkbox-success">
                    <input id="address_same_head" class="address_same_head" type="checkbox" name="address_same_head">
                    <label for="address_same_head"> &nbsp;ผู้รับผิดชอบคำขอ&nbsp;</label>
                </div>
            </div>

            <div class="col-md-4" style="text-align:right; margin-top:32px;">

                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('registerexperts'))
                <a class="btn btn-default" href="{{url('/certify/register-experts')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
                @endcan
            </div>
        </div>
    </div>
</div> --}}


@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>

<script>
    $(document).ready(function() {

            // จัดการข้อมูลในกล่องคำขอ false
    $('#box-readonly').find('button[type="submit"]').remove();
    $('#box-readonly').find('.icon-close').parent().remove();
    $('#box-readonly').find('.fa-copy').parent().remove();
    $('#box-readonly').find('.hide_attach').hide();
    $('#box-readonly').find('input').prop('disabled', true);
    $('#box-readonly').find('input').prop('disabled', true);
    $('#box-readonly').find('textarea').prop('disabled', true);
    $('#box-readonly').find('select').prop('disabled', true);
    $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
    $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
    $('#box-readonly').find('button').prop('disabled', true);
    $('#box-readonly').find('button').remove();
    $('#box-readonly').find('button').remove();
    $('body').on('click', '.attach-remove', function() {
      $(this).parent().parent().parent().find('input[type=hidden]').val('');
      $(this).parent().remove();
    });

        $('#address_same_head').on('change', function() {
            if ($(this).prop('checked')) {
                $('#contact_address_no').val($('#head_address_no').val());
                $('#contact_soi').val($('#head_soi').val());
                $('#contact_subdistrict').val($('#head_subdistrict').val());
                $('#contact_province').val($('#head_province').val());
                $('#contact_village').val($('#head_village').val());
                $('#contact_moo').val($('#head_moo').val());
                $('#contact_district').val($('#head_district').val());
                $('#contact_zipcode').val($('#head_zipcode').val());
            } else {
                $('#contact_address_no').val('');
                $('#contact_soi').val('');
                $('#contact_subdistrict').val('');
                $('#contact_province').val('');
                $('#contact_village').val('');
                $('#contact_moo').val('');
                $('#contact_district').val('');
                $('#contact_zipcode').val('');
            }
        });

    });
</script>

@endpush