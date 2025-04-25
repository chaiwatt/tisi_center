<div class="row">
    <div class="col-md-12">
        <div class="panel block4">
            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapse"> <dd style="color:Black"> <i class='fa fa-home' style="font-size:20px"></i> ข้อมูลการติดต่อเบื้องต้น </dd>  </a>
                    </h4>
                </div>
<div id="collapse" class="panel-collapse collapse in">
 
    
<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('address_show', 'ที่อยู่ตามทะเบียนบ้าน', ['class' => 'col-md-2  control-label label_height']) !!}
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group  {{ $errors->has('head_address_no') ? 'has-error' : ''}}">
            {!! Form::label('head_address_no', 'เลขที่:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_address_no', null,  ['class' => 'form-control ','id'=>"head_address_no", 'disabled' => true]) !!}
                {!! $errors->first('head_address_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('head_soi') ? 'has-error' : ''}}">
            {!! Form::label('head_soi', 'ตรอก/ซอย:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_soi', null , ['class' => 'form-control ','id'=>"head_soi", 'disabled' => true]) !!}
                {!! $errors->first('head_soi', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group  {{ $errors->has('head_subdistrict') ? 'has-error' : ''}}">
            {!! Form::label('head_subdistrict', 'แขวง/ตำบล:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_subdistrict',null,  ['class' => 'form-control ','id'=>"head_subdistrict", 'disabled' => true ]) !!}
                {!! $errors->first('head_subdistrict', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group  {{ $errors->has('head_province') ? 'has-error' : ''}}">
            {!! Form::label('head_province', 'จังหวัด:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_province', null,  ['class' => 'form-control ','id'=>"head_province", 'disabled' => true]) !!}
                {!! $errors->first('head_province', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('head_village') ? 'has-error' : ''}}">
            {!! Form::label('head_village', 'อาคาร/หมู่บ้าน:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_village', null,  ['class' => 'form-control ','id'=>"head_village", 'disabled' => true]) !!}
                {!! $errors->first('head_village', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('head_moo') ? 'has-error' : ''}}">
            {!! Form::label('head_moo', 'หมู่:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_moo',null,  ['class' => 'form-control ','id'=>"head_moo", 'disabled' => true]) !!}
                {!! $errors->first('head_moo', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group  {{ $errors->has('head_district') ? 'has-error' : ''}}">
            {!! Form::label('head_district', 'เขต/อำเภอ:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_district', null,  ['class' => 'form-control ','id'=>"head_district", 'disabled' => true]) !!}
                {!! $errors->first('head_district', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group  {{ $errors->has('head_zipcode') ? 'has-error' : ''}}">
            {!! Form::label('head_zipcode', 'รหัสไปรษณีย์:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('head_zipcode',null,  ['class' => 'form-control ','id'=>"head_zipcode", 'disabled' => true ]) !!}
                {!! $errors->first('head_zipcode', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
     {!! Form::label('address_show', 'ที่อยู่ที่สามารถติดต่อได้', ['class' => 'col-md-2  control-label label_height']) !!}
</div>

<div class="row">
<div class="col-md-6">
            <div class="form-group  {{ $errors->has('contact_address_no') ? 'has-error' : ''}}">
            {!! Form::label('contact_address_no', 'เลขที่:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_address_no', null,  ['class' => 'form-control ','id'=>"contact_address_no" , 'maxlength' => 255 , 'disabled' => true]) !!}
            {!! $errors->first('contact_address_no', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group {{ $errors->has('contact_soi') ? 'has-error' : ''}}">
            {!! Form::label('contact_soi', 'ตรอก/ซอย:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_soi', null,  ['class' => 'form-control ','id'=>"contact_soi", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_soi', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group  {{ $errors->has('contact_subdistrict') ? 'has-error' : ''}}">
            {!! Form::label('contact_subdistrict', 'แขวง/ตำบล:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_subdistrict', null,  ['class' => 'form-control ','id'=>"contact_subdistrict", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_subdistrict', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group  {{ $errors->has('contact_province') ? 'has-error' : ''}}">
            {!! Form::label('contact_province', 'จังหวัด:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_province', null,  ['class' => 'form-control ','id'=>"contact_province", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_province', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
</div>
<div class="col-md-6">
            <div class="form-group {{ $errors->has('contact_village') ? 'has-error' : ''}}">
            {!! Form::label('contact_village', 'อาคาร/หมู่บ้าน:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_village', null,  ['class' => 'form-control ','id'=>"contact_village", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_village', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group {{ $errors->has('contact_moo') ? 'has-error' : ''}}">
            {!! Form::label('contact_moo', 'หมู่:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_moo', null,  ['class' => 'form-control ','id'=>"contact_moo", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_moo', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group  {{ $errors->has('contact_district') ? 'has-error' : ''}}">
            {!! Form::label('contact_district', 'เขต/อำเภอ:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_district', null,  ['class' => 'form-control ','id'=>"contact_district", 'maxlength' => 255, 'disabled' => true]) !!}
            {!! $errors->first('contact_district', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
            <div class="form-group  {{ $errors->has('contact_zipcode') ? 'has-error' : ''}}">
            {!! Form::label('contact_zipcode', 'รหัสไปรษณีย์:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
            {!! Form::text('contact_zipcode', null,  ['class' => 'form-control ','id'=>"contact_zipcode", 'maxlength' => 5, 'disabled' => true]) !!}
            {!! $errors->first('contact_zipcode', '<p class="help-block">:message</p>') !!}
            </div>
            </div>
</div>
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    {!! Form::label('address_show', 'ข้อมูลบัญชีธนาคาร', ['class' => 'col-md-2  control-label label_height']) !!}
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group {{ $errors->has('bank_name') ? 'has-error' : ''}}">
            {!! Form::label('bank_name', 'ชื่อธนาคาร:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('bank_name', null, ['class' => 'form-control ','id'=>"bank_name", 'maxlength' => 255, 'placeholder' => 'ชื่อธนาคาร', 'disabled' => true]) !!}
                {!! $errors->first('bank_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('bank_title') ? 'has-error' : ''}}">
            {!! Form::label('bank_title', 'ชื่อบัญชีธนาคาร:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('bank_title', null,  ['class' => 'form-control ','id'=>"bank_title", 'maxlength' => 255, 'placeholder' => 'ชื่อบัญชีธนาคาร', 'disabled' => true]) !!}
                {!! $errors->first('bank_title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('bank_number') ? 'has-error' : ''}}">
            {!! Form::label('bank_number', 'เลขบัญชีธนาคาร:', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('bank_number', null,  ['class' => 'form-control ','id'=>"bank_number", 'maxlength' => 255, 'placeholder' => 'เลขบัญชีธนาคาร', 'disabled' => true]) !!}
                {!! $errors->first('bank_number', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-12 ">
                <div class="form-group {{ $errors->has('bank_file') ? 'has-error' : ''}}">

                    {!! Form::label('bank_file', 'เอกสารหน้าบัญชี:', ['class' => 'col-md-3 control-label']) !!}
                    <span id="span_bank_file">
                       @if (isset($registerexperts) && !empty($registerexperts->AttachFileBankFileTo))
                            @php
                            $attach = $registerexperts->AttachFileBankFileTo;
                            @endphp
                            
                            <div class="col-md-9">
                                        <div class="form-group">
                                                    <div class="col-md-12" style="padding-top: 7px; margin-bottom: 0; text-align: left">
                                                                {!! !empty($attach->caption) ? $attach->caption : '' !!}
                                                                <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                                                                            title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                                                                            {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                                                </a>
                                                    </div>
                                        </div>
                            </div>
                           @endif
                      </span>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">

    </div>
</div>
 

 </div>
                 </div>
            </div>
        </div> 
    </div> 
</div>