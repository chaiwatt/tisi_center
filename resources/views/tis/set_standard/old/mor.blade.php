@php
    $labelSize = 'col-md-4';
    $formSize = 'col-md-8';
    $formSize7 = 'col-md-7';
    $labelSize2 = 'col-md-3';
    $formSize2 = 'col-md-9';
    $labelSize3 = 'col-md-6';
    $formSize3 = 'col-md-6';

@endphp


<div class="clearfix"></div>
<div id="app_mor_container">
    <div class="row p-r-15">
        <div class="col-md-12">

            <div class="form-group {{ $errors->has('review_status') ? 'has-error' : ''}}">
                <label class="col-md-2 control-label"></label>
                <div class="col-md-10">
                    <label><input type="radio" name="review_status" v-model="review_status" @change="onChangeTisNo" value="2" required> {{-- class="check" data-radio="iradio_square-green" --}}
                        ทบทวน </label>
                    <label><input type="radio" name="review_status" v-model="review_status" @change="onChangeTisNo" value="1" required> {{-- class="check" data-radio="iradio_square-red" --}}
                        กำหนดใหม่ </label>
                </div>
            </div>

        </div>

         <div class="col-md-12 m-l-20">

            <div class="form-group {{ $errors->has('revise_status') ? 'has-error' : ''}}" style="display: none" id="show_revise">
                <label class="col-md-2 control-label"></label>
                <div class="col-md-10">
                    <label><input type="radio" name="revise_status" v-model="revise_status" @change="onChangeRevise" value="1">
                        เวียนทบทวน </label>
                    <label><input type="radio" name="revise_status" v-model="revise_status" @change="onChangeRevise" value="2">
                        ไม่เวียนทบทวน </label>
                </div>
            </div>

        </div>

    <div class="row p-r-15">

        <div class="col-md-6 m-l-15">

            <div class="form-group {{ $errors->has('plan_year') ? 'has-error' : ''}}">
                {!! Form::label('plan_year', 'ปีงบประมาณที่เสนอเข้าแผน : ', ['class' => "{$labelSize} control-label", 'style' => 'font-size:17px']) !!}
                <div class="{{ $formSize7 }}">
                    <select2 v-model="form.plan_year" name="plan_year" id="plan_year" class="form-control">
                        <option value="" selected>- เลือกปี -</option>
                        @foreach (HP::Years() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('plan_year', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>

    </div>

    <div class="row p-r-15">

                        <div class="col-md-6">


            <div class="form-group required {{ $errors->has('tis_no') ? 'has-error' : ''}}">

                {!! Form::label('tis_no', 'เลขที่ มอก. : ', ['class' => "{$labelSize} control-label m-l-15"]) !!}
                <div class="{{ $formSize7 }}" v-if="review_status == '1'">
                    {!! Form::text('tis_no', null, ['class' => 'form-control', 'required'=>'required', 'v-model' => 'tis_no_text']) !!}
                    {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="{{ $formSize7 }}" v-else>
                    <select name="tis_no" ref="tis_no" id="tis_no" v-model="tis_no" @change="onChangeNo" class="form-control not_select2" required>
                        <option value="">-เลือกหมายเลข มอก.-</option>
                        <option v-for="tis in tis_nos" :value="tis.id">@{{ tis.tis_no + '-' + tis.tis_year + ' : <small>' + tis.title + '</small>'}}</option>
                    </select>
                    {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
                </div>

            </div>

                        </div>

            <div class="col-md-3">

                <div class="form-group {{ $errors->has('tis_no') ? 'has-error' : ''}}">

                    {!! Form::label('tis_no', 'เล่ม : ', ['class' => "{$labelSize2} control-label"]) !!}
                    <div class="{{ $formSize2 }}" v-if="review_status == '1'">
                        {!! Form::text('tis_book', null, ['class' => 'form-control', 'v-model' => 'tis_book']) !!}
                        {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="{{ $formSize2 }}" v-else>
                        <select name="tis_book" ref="tis_book" v-model="tis_book" class="form-control not_select2" >
                            <option value="">- เลือกเล่ม -</option>
                            <option v-for="book in getBooks" :value="book.value">@{{ book.value }}</option>
                        </select>
                        {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
                    </div>

                </div>

            </div>

                        <div class="col-md-3">
                    <div class="form-group {{ $errors->has('start_year') ? 'has-error' : ''}}">
                {!! Form::label('start_year', 'ปี มอก. : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.start_year" name="start_year" id="start_year" class="form-control">
                        <option value="25xx" selected>25xx</option>
                        @foreach (HP::Years() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('start_year', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

                        </div>



        </div>

        {{--  NAME FORM  --}}
        <div class="col-md-12">

            <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title', 'ชื่อมาตรฐาน (TH) : ', ['class' => "col-md-2 control-label"]) !!}
                <div class="col-md-10">
                    {!! Form::text('title', null, ['class' => 'form-control', 'v-model' => 'form.title', 'required' => 'required']) !!}
                    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('title_en') ? 'has-error' : ''}}">
                {!! Form::label('title_en', 'ชื่อมาตรฐาน (EN) : ', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-10">
                    {!! Form::text('title_en', null, ['class' => 'form-control', 'required' => 'required', 'v-model' => 'form.title_en']) !!}
                    {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>

    </div>

    <div class="row p-r-15">

        {{--  LEFT FORM  --}}
        <div class="col-md-6">

            <div class="form-group required {{ $errors->has('made_by') ? 'has-error' : ''}}">
                {!! Form::label('made_by', 'จัดทำโดย : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select name="made_by" id="made_by" class="form-control not_select2" required v-model="made_by">
                        <option value="" selected>- เลือก -</option>
                        @foreach (HP::Mades() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('made_by', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('sdo_name') ? 'has-error' : ''}}">
                {!! Form::label('sdo_name', 'ชื่อหน่วยงาน (SDO): ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}" v-if="selectSDO">
                    {!! Form::text('sdo_name', null, ['class' => 'form-control', 'required' => 'required', 'v-model' => 'sdo_name']) !!}
                    {!! $errors->first('sdo_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
                {!! Form::label('product_group_id', 'สาขา : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.product_group_id" name="product_group_id" id="product_group_id" class="form-control" required>
                        <option value="" selected>- เลือกกลุ่มผลิตภัณฑ์/สาขา -</option>
                        @foreach (App\Models\Basic\ProductGroup::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') as $key => $value)
                            @if($value=='อื่นๆ')
                                @php $temp_other[$key] = $value; continue; @endphp
                            @endif
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                            <option value="{{ array_keys($temp_other,'อื่นๆ')[0] }}">{{ $temp_other[array_keys($temp_other,'อื่นๆ')[0]] }}</option>
                    </select2>
                    {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            {{--      ข้อมูลไม่มีให้เลือก      --}}
            <div class="form-group required {{ $errors->has('appoint_id') ? 'has-error' : ''}}">
                {!! Form::label('appoint_id', 'คณะกรรมการ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.appoint_id" name="appoint_id" id="appoint_id" class="form-control" required>
                        <option value="" selected>- เลือกคณะกรรมการ -</option>
                        @foreach (App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->where('state',1)->orderbyRaw('CONVERT(board_position USING tis620)')->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('appoint_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('standard_type_id') ? 'has-error' : ''}}">
                {!! Form::label('standard_type_id', 'ประเภท มอก. : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.standard_type_id" name="standard_type_id" id="standard_type_id" class="form-control" required>
                        <option value="" selected>- เลือกประเภท มอก. -</option>
                        @foreach (App\Models\Basic\StandardType::selectRaw("CONCAT(acronym,' - ',title,' (',title_en,')') as title, id")->where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('standard_type_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('standard_format_id') ? 'has-error' : ''}}">
                {!! Form::label('standard_format_id', 'ทั่วไป/บังคับ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.standard_format_id" name="standard_format_id" id="standard_format_id" class="form-control" required>
                        <option value="" selected>- เลือกรูปแบบ มอก. -</option>
                        @foreach (App\Models\Basic\StandardFormat::Where('state',1)->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('standard_format_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
                {!! Form::label('remark', 'หมายเหตุ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 4, 'v-model' => 'form.remark']) !!}
                    {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                {!! Form::label('state', 'สถานะ:', ['class' =>  "{$labelSize} control-label", 'style' => 'padding-top: 0px;']) !!}
                <div class="{{ $formSize }}">
                    <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
                        ใช้งาน </label>
                    <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!}
                        ยกเลิก </label>

                    {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

        </div>


        {{--  RIGHT FORM  --}}
        <div class="col-md-6">

            <div class="form-group required {{ $errors->has('set_format_id') ? 'has-error' : ''}}">
                {!! Form::label('set_format_id', 'ใหม่/ทบทวน : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.set_format_id" name="set_format_id" id="set_format_id" class="form-control" required>
                        <option value="" selected>- เลือกรูปแบบการกำหนด มอก. -</option>
                        @foreach (App\Models\Basic\SetFormat::Where('state',1)->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('method_id') ? 'has-error' : ''}}">
                {!! Form::label('method_id', 'วิธีจัดทำ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select v-model="form.method_id" name="method_id" id="method_id" @change="onChangeMethodDetail" class="form-control not_select2" required>
                        <option value="" selected>- เลือกวิธีจัดทำ -</option>
                        @foreach (App\Models\Basic\Method::Where('state',1)->get() as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->title }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('method_id_detail') ? 'has-error' : ''}}">
                {!! Form::label('method_id_detail', 'รายละเอียดย่อยวิธีจัดทำ :', ['class' => 'col-md-4 control-label', 'style'=>'font-size:16px']) !!}
                <div class="col-md-8">
                    <select name="method_id_detail" ref="method_id_detail" id="method_id_detail" v-model="method_id_detail" class="form-control not_select2">
                        <option value="">-เลือกรายละเอียดย่อยวิธีจัดทำ-</option>
                        <option v-for="(method, index) in method_details" :value="index">@{{ method }}</option>
                    </select>
                    {!! $errors->first('method_id_detail', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group required {{ $errors->has('industry_target_id') ? 'has-error' : ''}}">
                {!! Form::label('industry_target_id', 'อุตสาหกรรมเป้าหมาย :', ['class' => "{$labelSize} control-label", 'style'=>'font-size:16px']) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.industry_target_id" name="industry_target_id" id="industry_target_id" class="form-control" required>
                        <option value="" selected>- เลือกอุตสาหกรรมเป้าหมาย -</option>
                        @foreach (App\Models\Basic\IndustryTarget::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('industry_target_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
                {!! Form::label('cluster_id', 'หมวดหมู่ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    <select2 v-model="form.cluster_id" name="cluster_id" id="cluster_id" class="form-control">
                        <option value="" selected>- เลือกหมวดหมู่ -</option>
                        @foreach (App\Models\Basic\Cluster::Where('state',1)->get() as $key => $value)
                            <option value="{{ $key }}">{{ $value->title }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('cluster_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('refer') ? 'has-error' : ''}}">
                {!! Form::label('refer', 'มาตรฐานอ้างอิง : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">

                    <div id="refer-box" v-for="(item, index) in form.refer">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="refer[]" v-model="item.value">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-success pull-right" @click="onClickReferAdd" v-if="index==0">
                                    <i class="icon-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger pull-right remove-refer" @click="onClickReferRemove(index)" v-else>
                                    <i class="icon-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {!! $errors->first('refer', '<p class="help-block">:message</p>') !!}

                </div>
            </div>

            <div class="form-group {{ $errors->has('staff_group') ? 'has-error' : ''}}">
                {!! Form::label('staff_group', 'กลุ่มที่ :', ['class' => "{$labelSize} control-label"]) !!}
                 <div class="{{ $formSize }}">
                    {{-- {!! Form::select('staff_group', App\Models\Basic\StaffGroup::selectRaw('CONCAT(`order`," ",title) As title, id')->where('state',1)->pluck('title', 'id'), null, ['class' => 'form-control', 'id'=>'staff_group', 'placeholder'=>'-เลือกกลุ่มที่-']); !!} --}}

                    <select2 v-model="form.staff_group" name="staff_group" id="staff_group" class="form-control">
                        <option value="" selected>- เลือกกลุ่มที่ -</option>
                        @foreach (App\Models\Basic\StaffGroup::selectRaw('CONCAT(`order`," ",title) As title, id')->where('state',1)->pluck('title', 'id') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select2>
                    {!! $errors->first('staff_group', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('secretary') ? 'has-error' : ''}}">
                {!! Form::label('secretary', 'เลขานุการ : ', ['class' => "{$labelSize} control-label"]) !!}
                <div class="{{ $formSize }}">
                    {!! Form::text('secretary', null, ['class' => 'form-control', 'readonly'=>'readonly']) !!}
                    {!! $errors->first('secretary', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('attach', 'ไฟล์แนบ : ', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    <button type="button" class="btn btn-sm btn-success" @click="onClickAttachAdd">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                    </button>
                </div>
            </div>

            <div id="other_attach-box" v-for="(attach, index) in form.attaches">

                <div class="form-group other_attach_item">
                    {{-- <div class="col-md-2">
                        <input type="hidden" name="attach_filenames[]" :value="attach.file_name">
                    </div> --}}
                    <input type="hidden" name="attach_filenames[]" :value="attach.file_name">
                    <div class="col-md-6">
                        <template v-if="attach.file_note === ''">
                            <input type="text" name="attach_notes[]" class="form-control" placeholder="คำอธิบาย(ถ้ามี)">
                        </template>
                        <template v-else>
                            <input type="text" name="attach_notes[]" :value="attach.file_note" class="form-control" placeholder="คำอธิบาย(ถ้ามี)">
                        </template>
                    </div>
                    <div class="col-md-5">

                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename" style="max-width: 100px; overflow-x: hidden;">@{{ attach.file_client_name }}</span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file" v-if="!(attach.file_name!='' && attach.check)">
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    {{-- {!! Form::file('attachs[]', ['class'=>'check_max_size_file']) !!} --}}
                                      <input type="file" name="attachs[]"  @change="check_max_size_file" multiple tabindex="-1">
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                               data-dismiss="fileinput">ลบ</a>
                        </div>
                        {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="col-md-1">

                        <a :href="attach.href" target="_blank"
                           class="view-attach btn btn-info btn-sm" v-if="attach.file_name!='' && attach.check"><i class="fa fa-search"></i></a>

                        <button class="btn btn-danger btn-sm attach-remove" type="button">
                            <i class="icon-close"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>
    </div>


    <div class="row m-t-15 p-r-15">
        <div class="col-md-12">
            <div class="form-group text-center">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('set_standard'))
                    <a class="btn btn-default" href="{{url('/tis/set_standard')}}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>


@push('js')

@endpush
