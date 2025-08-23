{{-- StandardDraftsController --}}
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
<style type="text/css">
.bootstrap-tagsinput {
    width: 100% !important;
  }
    .font-16{
        font-size:16px;
    }
    .font-14{
        font-size:10px;
    }
    
</style>
@endpush



{{-- <button type="button" id="btn-add" class="btn btn-sm btn-success pull-right m-b-10"> <i class="fa fa-plus"></i> เพิ่ม </button> --}}

@php
    //Query ข้อมูลที่ซ้ำในลูป
    $standard_types  = App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');//ข้อมูลประเภทมาตรฐาน
    $methods         = App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');//วิธีการ
    $industry_targets= App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');//อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต
    $standard_offers = App\Models\Tis\EstandardOffers::selectRaw('*, CONCAT_WS(" : ", refno, title) AS titles')->whereNotNull('standard_name')->where('state',2)->get();//ความเห็นการกำหนดมาตรฐานการตรวจสอบและรับรอง
    $assign_ids      = App\User::select(DB::raw("CONCAT(IF(reg_intital=1, 'นาย', IF(reg_intital=2, 'นางสาว', IF(reg_intital=3, 'นาง', ''))), '' , reg_fname, ' ', reg_lname) AS title"),'runrecno AS id')
                                ->where('reg_subdepart', 1801)
                               ->orderbyRaw('CONVERT(title USING tis620)')
                               ->pluck('title', 'id');//เจ้าหน้าที่ที่รับมอบหมาย
@endphp

<div class="row" id="box">
    @foreach ($estandard_draft_plans as $key => $offers)
        @php
            $start_std_condition = (empty($offers->start_std) || $offers->start_std == 1);
        @endphp
        <div class="col-md-12 item">
            <div class="panel block4">
                <div class="panel-group accordion-id" id="accordion{{ $key }}">
                    <div class="panel panel-info">
                        {{-- <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-parent" data-toggle="collapse" data-parent="#accordion{{ $key }}" href="#collapse{{ $key }}"> <dd> รายการมาตรฐาน # <span class="text-order">{{ $key+1 }}</span> </dd> </a>
                            </h4>
                        </div> --}}

                        <div >
                        {{-- <div id="collapse{{$key}}" class="panel-collapse collapse accordion-collapse {{ $key==0 ? 'in' : '' }} "> --}}
                            <div class="row form-group">
                                <div class="container-fluid">

                                    {{-- @php
                                        dd($standarddraft);
                                    @endphp --}}
                                    <div class="form-group {{ $errors->has('list.board.' . $key) ? 'has-error' : '' }}">
                                        <label for="board_{{ $key }}" class="col-md-3 control-label">
                                            คำขอ : <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-5">
                                            <select class="form-control" id="input-request" required>
                                                <option value="">-เลือกคำขอ-</option>
                                                @foreach ($standard_offers->reverse() as $standard_offer)
                                                    <option value="{{ $standard_offer->id }}"
                                                            data-standard_types="{{ $standard_offer->standard_types }}"
                                                            data-objectve="{{ $standard_offer->objectve }}"
                                                            data-iso_number="{{ $standard_offer->iso_number }}"
                                                            data-standard_name="{{ $standard_offer->standard_name }}"
                                                            data-standard_name_en="{{ $standard_offer->standard_name_en }}"
                                                            data-proposer_type="{{ $standard_offer->proposer_type }}"
                                                            data-standard_name_en="{{ $standard_offer->standard_name_en }}"
                                                            {{ isset($offers) && $offers->offer_id == $standard_offer->id ? 'selected' : '' }}>
                                                        {{ $standard_offer->titles }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                            @if ($errors->has('list.board.' . $key))
                                                <p class="help-block">{{ $errors->first('list.board.' . $key) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('draft_year') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('draft_year', 'ร่างแผนปี'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-5">
                                            {!! Form::select('draft_year',
                                        HP::Years(),
                                        null,
                                        ['class' => 'form-control',
                                        'id'=>'draft_year',
                                        'required'=> true,
                                        'placeholder'=>'- เลือกปี -']) !!}
                                            {!! $errors->first('draft_year', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('board') ? 'has-error' : ''}}" hidden>
                                        {!! Html::decode(Form::label('board', 'คณะกรรมการเฉพาะด้าน'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('board[]',
                                            App\Models\Tis\CommitteeSpecials::orderbyRaw('CONVERT(committee_group USING tis620)')->pluck('committee_group', 'id'),
                                            null,
                                            ['class' => 'select2-multiple',
                                                'multiple' => 'multiple',
                                            'data-placeholder'=>'- เลือกคณะกรรมการเฉพาะด้าน -']) !!}
                                            {!! $errors->first('board', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::text('list[tis_name][]', $offers->tis_name, ['class' => 'form-control ', 'required' => true, 'id' => 'tis_name_input']) !!}
                                            {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::text('list[tis_name_eng][]', $offers->tis_name_eng, ['class' => 'form-control ', 'required' => true,'id' => 'tis_name_eng_input']) !!}
                                            {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                       



                                    {!! Form::hidden('list[estandard_draft_plan_id][]', $offers->id); !!}
                                    <div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
                                        <label for="std_type" class="col-md-3 control-label">
                                            ประเภทมาตรฐาน : <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="list[std_type][]" required id="std_type_select">
                                                <option value="" disabled selected>- เลือกประเภทมาตรฐาน -</option>
                                                @foreach($standard_types as $key => $value)
                                                    <option value="{{ $key }}" {{ (string)$key === (string)$offers->std_type ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            
                                            @if ($errors->has('std_type'))
                                                <p class="help-block">{{ $errors->first('std_type') }}</p>
                                            @endif
                                        </div>
                                    </div>


                                    {{-- {{
                                        $standard_offers 
                                    }} --}}

                                                  {{-- <select class="form-control input-board" name="list[board][{{ $key }}][]">
                                                                    <option value="">-เลือกความเห็นการกำหนดมาตรฐาน-</option>
                                                                    @foreach ($standard_offers as $standard_offer)
                                                                        <option value="{{ $standard_offer->id }}"
                                                                                data-name="{{ $standard_offer->name }}"
                                                                                data-telephone="{{ $standard_offer->telephone }}"
                                                                                data-email="{{ $standard_offer->email }}"
                                                                                data-department="{{ $standard_offer->department }}"
                                                                                {{ $board->offer_id==$standard_offer->id ? 'selected' : '' }}>
                                                                            {{ $standard_offer->titles }}
                                                                        </option>
                                                                    @endforeach
                                                                </select> --}}
                                    
                                    <div class="form-group required {{ $errors->has('list[start_std]') ? 'has-error' : ''}}">
                                        {!! HTML::decode(Form::label('list[start_std]', 'การกำหนดมาตรฐาน :', ['class' => 'col-md-3  control-label'])) !!}
                                        <div class="col-md-9">

                                            <label>{!! Form::radio('list[start_std]['.$key.']', '1', $offers->start_std == 1 ? true:false, ['class'=> "check start_std_check", 'data-id' => "#start_std{$key}", 'data-radio'=>'iradio_square-green']) !!} จัดทำครั้งแรก &nbsp;&nbsp;</label>
                                            <label>{!! Form::radio('list[start_std]['.$key.']', '2', $offers->start_std == 2  ? true:false, ['class'=> "check start_std_check", 'data-id' => "#start_std{$key}", 'data-radio'=>'iradio_square-green']) !!} ปรับปรุงมาตรฐาน &nbsp;&nbsp;</label>
                                        </div>
                                    </div>
          
                                    <div class="form-group start_std {{ $errors->has('list[ref_std]') ? 'has-error' : ''}}" id="start_std{{ $key }}"  style="@if($start_std_condition) display: none; @endif"> 
                                        {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('list[ref_std][]',
                                                App\Models\Certify\Standard::where('publish_state', 2)->selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id'),
                                                 $offers->ref_std ?? null,
                                                ['class' => 'form-control ref_std',
                                                'disabled' => $start_std_condition,
                                                'placeholder'=>'- เลือกมาตรฐาน -']) !!}
                                            {!! $errors->first('list[ref_std]', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-3">
                                            {!! Form::text('list[tis_number][]', $offers->tis_number, ['class' => 'form-control ','required'=>false, 'id' => 'tis_number_input']) !!}
                                            {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-md-3">
                                            {!! Form::text('list[tis_book][]', $offers->tis_book, ['class' => 'form-control ','required'=>false ,'placeholder' => 'เล่ม ถ้ามี']) !!}
                                            {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-md-3">
                                            {!! Form::select('list[tis_year][]',
                                                            HP::Years(),
                                                            $offers->tis_year,
                                                            ['class' => 'form-control',
                                                             'required' => false,
                                                             'placeholder' => '- เลือกปีมาตรฐาน -'
                                                            ])
                                            !!}
                                            {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>


{{-- @php
    dd($methods)
@endphp --}}
                                    <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}" hidden>
                                        {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            <select class="form-control" name="list[method_id][]" id="method_id_select" required>
                                                <option value="" disabled selected>- เลือกวิธีการ -</option>
                                                
                                                {{-- วนลูปตัวแปร $methods ด้วยตัวเอง --}}
                                                @foreach ($methods as $key => $value)
                                                    
                                                    {{-- สร้างตัวแปรสำหรับเก็บข้อความที่จะแสดงผล --}}
                                                    @php
                                                        $displayText = $value; // กำหนดค่าเริ่มต้นเป็นค่าเดิม
                                                    @endphp

                                                    {{-- ตรวจสอบเงื่อนไขเพื่อปรับแก้ข้อความ --}}
                                                    @if (str_contains($value, 'Adopt'))
                                                        @php
                                                            $displayText = $value . ' (sdo ขั้นสูง)';
                                                        @endphp
                                                    @elseif ($value === 'ยกร่าง')
                                                        @php
                                                            $displayText = $value . ' (sdo ขั้นต้น /หน่วยงานที่ไม่ใช่ SDO )';
                                                        @endphp
                                                    @endif
                                                    
                                                    {{-- สร้าง <option> โดยใช้ค่าที่ปรับแก้แล้ว --}}
                                                    <option value="{{ $key }}" {{ (string)$key === (string)$offers->method_id ? 'selected' : '' }}>
                                                        {{ $displayText }}
                                                    </option>

                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    {{-- ลบ Form::select เดิมออก แล้วใช้โค้ดนี้แทน --}}

                              
                                    <div class="form-group {{ $errors->has('list[ref_document]') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('list[ref_document]', 'เอกสารอ้างอิง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::text('list[ref_document][]', $offers->ref_document, ['class' => 'form-control ', 'required' => false]) !!}
                                            {!! $errors->first('list[ref_document]', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group required{{ $errors->has('list[reason]') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('list[reason]', 'เหตุผลและความจำเป็น'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('list[reason][]',
                                            App\Models\Bcertify\Reason::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                             $offers->reason ?? null,
                                            ['class' => 'form-control reason',
                                            'required' => true,
                                            'placeholder'=>'- เลือกเหตุผลและความจำเป็น -']) !!}
                                            {{-- {!! Form::text('list[reason][]', $offers->reason, ['class' => 'form-control reason', 'required' => true]) !!} --}}
                                            {!! $errors->first('list[reason]', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group  div_bcertify_reason {{ $errors->has('list[bcertify_reason]') ? 'has-error' : ''}}" >
                                        {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-9">
                                                {!! Form::text('list[bcertify_reason][]', !empty($offers->reason_draft_plan_to->title) ? $offers->reason_draft_plan_to->title : null   , ['class' => 'form-control bcertify_reason',  'placeholder'=>'(เหตุผลและความจำเป็น อื่นๆ)', 'required' => false]) !!}
                                                {!! $errors->first('list[bcertify_reason]', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>


                                    <div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}" hidden>
                                        {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('list[confirm_time][]', $offers->confirm_time,  ['class' => 'form-control ']) !!}
                                            {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}" hidden>
                                        {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('list[industry_target][]',
                                                            $industry_targets,
                                                            $offers->industry_target,
                                                            ['class' => 'form-control',
                                                             'required' => false,
                                                             'placeholder' => '- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -'
                                                            ])
                                            !!}
                                            {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    @php
                                        $attach = $offers->AttachFileAttachTo;
                                        $attach = !is_null($attach) ? $attach : new App\AttachFile;
                                    @endphp
                                    <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-4 text-light">
                                            {!! Form::text('list[document_details][]', $attach->caption, ['class' => 'form-control ', 'placeholder' => 'รายละเอียดเอกสาร']) !!}
                                        </div>
                                        <div class="col-md-5">
                                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="list[attach][{{$key}}]" class="attach check_max_size_file" >
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                            </div>
                                            {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}

                                            @if(!is_null($attach->url))
                                                <a href="{{ HP::getFileStorage($attach->url) }}" target="_blank" class="pull-right attach-link"><i class="mdi mdi-file-pdf text-danger"></i> คลิกดูเอกสาร</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group" hidden>

                                        {!! Html::decode(Form::label('name', 'ความเห็นการกำหนดมาตรฐาน : ', ['class' => 'col-md-3 control-label'])) !!}

                                        @php
                                            $boards = $offers->boards;
                                            $boards = count($boards)==0 ? collect([new App\Models\Tis\TisiEstandardDraftBoard]) : $boards ;
                                        @endphp

                                        <div class="table-responsive col-md-9">
                                            <table class="table color-bordered-table primary-bordered-table">
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-1">#</th>
                                                        <th class="col-md-6">ความเห็นการกำหนดมาตรฐาน</th>
                                                        <th class="col-md-4">หน่วยงาน</th>
                                                        <th class="col-md-1 text-center"><i class="fa fa-wrench"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="box-board">
                                                    @foreach ($boards as $board)
                                                        <tr class="item-board">
                                                            <td class="text-top order-board">1</td>
                                                            <td class="text-top">
                                                                <input type="hidden" name="list[board_id][{{ $key }}][]" value="{{ $board->id }}" />
                                                                <select class="form-control input-board" name="list[board][{{ $key }}][]">
                                                                    <option value="">-เลือกความเห็นการกำหนดมาตรฐาน-</option>
                                                                    @foreach ($standard_offers->reverse() as $standard_offer)
                                                                        <option value="{{ $standard_offer->id }}"
                                                                                data-name="{{ $standard_offer->name }}"
                                                                                data-telephone="{{ $standard_offer->telephone }}"
                                                                                data-email="{{ $standard_offer->email }}"
                                                                                data-department="{{ $standard_offer->department }}"
                                                                                {{ $board->offer_id==$standard_offer->id ? 'selected' : '' }}>
                                                                            {{ $standard_offer->titles }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-top data-board">
                                                                <i class="text-muted">แสดงอัตโนมัติเมื่อเลือกความเห็น</i>
                                                            </td>
                                                            <td class="col-md-1 text-center text-top tool-board">
                                                                <button type="button" class="btn btn-success btn-xs add-board"><i class="fa fa-plus"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Html::decode(Form::label('name', 'เจ้าหน้าที่ที่รับมอบหมาย'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label offers_assign_label'])) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('list[assign_id][]',
                                                             $assign_ids,
                                                             $offers->assign_id,
                                                            ['class' => 'form-control offers_assign',
                                                             'required' => true,
                                                             'placeholder' => '- เลือกเจ้าหน้าที่ที่รับมอบหมาย -'
                                                            ])
                                            !!}
                                            {!! $errors->first('assign_id', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('status_id') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('status_id', 'สถานะ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('status_id',
                                        ['1'=>'ร่างมาตรฐาน','2'=>'เห็นชอบร่างมาตรฐาน','3'=>'ไม่เห็นชอบร่างมาตรฐาน'],
                                        null,
                                        ['class' => 'form-control',
                                        'id'=>'status_id',
                                        'required'=> true,
                                        'placeholder'=>'- เลือกสถานะ -']) !!}
                                            {!! $errors->first('status_id', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('state', 'ผู้จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-6 m-t-10">
                                            {{ !empty($standarddraft->user_created->FullName) ?  $standarddraft->user_created->FullName : auth()->user()->FullName }}
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('state', 'วันที่จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                                        <div class="col-md-6 m-t-10">
                                            {{ !empty($standarddraft->user_created->FullName) ?  HP::DateTimeFullThai($standarddraft->created_at)  : HP::DateTimeFullThai(date('Y-m-d H:i:s')) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="form-group div_hide">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standarddrafts'))
            <a class="btn btn-default" href="{{url('/certify/standard-drafts')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
               
            //เพิ่ม
            $('#btn-add').click(function(event) {
                $('.item:last').clone().appendTo('#box');

                var last = $('.item:last');

                last.find('input[type=text], input[type=hidden],select').val('');
                last.find('.attach-link').remove();
                last.find('.btn-remove').closest('.form-group').remove();
                last.find('.accordion-collapse').collapse('show');

                //rebuid select2
                last.find('select').prev().remove();
                last.find('select').removeAttr('style');
                last.find('select').select2();
                last.find('.div_bcertify_reason').hide();
                //เพิ่มปุ่มลบ
                last.find('.container-fluid').prepend('<div class="form-group"><div class="col-md-12"><button type="button" class="btn btn-sm btn-danger pull-right m-b-10 btn-remove"> <i class="fa fa-times-circle"></i> ลบ </button></div></div>');


                 //Clear Radio
                 $(last).find('.check').each(function(index, el) {
                    $(el).prependTo($(el).parent().parent());
                    $(el).removeAttr('style');
                    $(el).parent().find('div').remove();
                    $(el).iCheck();
                    $(el).parent().addClass($(el).attr('data-radio'));
                });
    
                $(".start_std_check").on("ifChanged", function(event) {;
                    // start_std_check($(this));
                });
                set_order();

                check_max_size_file();
            });

            //ลบ
            $(document).on('click', '.btn-remove', function(){
                $(this).closest('.item').remove();
                set_order();
            });
            set_order();


            //เมื่อเลือกความเห็นการกำหนดมาตรฐาน แสดงข้อมูลเพิ่มเติม
            $(document).on('change', '.input-board', function(){

                var data_board = '';
                if($(this).val()!=''){
                    var option = $(this).find('option:selected');
                    data_board += '<p>ผู้ประสานงาน : '+$(option).data('name')+'</p>';
                    data_board += '<p>เบอร์โทร : '+$(option).data('telephone')+'</p>';
                    data_board += '<p>อีเมล : '+$(option).data('email')+'</p>';
                    data_board += '<p>หน่วยงาน : '+$(option).data('department')+'</p>';
                }else{
                    data_board += '<i class="text-muted">แสดงอัตโนมัติเมื่อเลือกความเห็น</i>';
                }
                $(this).closest('tr').find('.data-board').html(data_board);
            });

            //เพิ่มความเห็น
            $(document).on('click', '.add-board', function(){

                $(this).closest('.item-board').clone().appendTo($(this).closest('.box-board'));

                var last = $(this).closest('.box-board').find('.item-board:last');

                //Clear Value
                last.find('input[name*="board_id"]').val('');

                //rebuid select2
                last.find('select').val('');
                last.find('select').prev().remove();
                last.find('select').removeAttr('style');
                last.find('select').select2();

                last.find('.data-board').html('');

                reset_board();
            });

            //ลบความเห็น
            $(document).on('click', '.remove-board', function(){
                $(this).closest('.item-board').remove();
                reset_board();
            });

            // $('.start_std_check').on('ifChanged', function(){
            //     let id = $(this).data('id');
            //     if($(this).val() == 2){
            //         $(id).show();
            //         $(id).find('select').prop('disabled', false);
            //     }else{
            //         $(id).hide();
            //         $(id).find('select').prop('disabled', true);
            //     }
            // });

            $('#status_id').change(function(){
                let status = $(this).val();
                if(status=='1'){
                    $('.offers_assign').attr('required',false);
                    $('label.offers_assign_label').find('span.text-danger').text('');
                }else{
                    $('.offers_assign').attr('required',true);
                    $('label.offers_assign_label').find('span.text-danger').text('*');
                }
            });

            $('#status_id').change();

            $(".start_std_check").on("ifChanged", function(event) {;
                // start_std_check($(this));
            });
     
            function start_std_check($this){
                         let id = $($this).data('id');
           
                    if($($this).is(':checked') && $($this).val() == 2){
                          $(id).show();
                         $(id).find('select').prop('disabled', false);
                    }else{
                        $(id).hide();
                        $(id).find('select').prop('disabled', true);
                    }
 
                }  

             //เหตุผลและความจำเป็น
            $(document).on('change', '.reason', function(){
               var row  =  $(this).parent().parent().parent();
                if(checkNone($(this).val())){
                            $.ajax({
                                type: 'get',
                                url: "{!! url('certify/standard-drafts/get_bcertify_reason') !!}" ,
                                data:{id:  $(this).val()}
                            }).done(function( object ) { 
                                if(object.message == true){
                                    row.find('.div_bcertify_reason').show();
                                }else{
                                    row.find('.bcertify_reason').val('');
                                    row.find('.div_bcertify_reason').hide();
                                }
                            }); 
                 }else{
                            row.find('.bcertify_reason').val('');
                            row.find('.div_bcertify_reason').hide();
                 }
     
            });     

 
            //สั่งความเห็นการกำหนดมาตรฐานให้ทำงาน
            $('.input-board').change();
            $('.reason').change();
            reset_board();

        });

        //แก้ไขชื่อไฟล์ใหม่ และการเรียงลำดับ
        function set_order(){
            $('.item').each(function(index, item) {
                $(item).find('.accordion-id').attr('id', 'accordion'+index);
                $(item).find('.accordion-parent').attr('data-parent', '#accordion'+index);
                $(item).find('.accordion-parent').attr('href', '#collapse'+index);
                $(item).find('.accordion-collapse').attr('id', 'collapse'+index);
                $(item).find('.text-order').text(index+1);
                //แก้ไขชื่อ input
                $(item).find('.check_max_size_file').prop('name', 'list[attach]['+index+']');
                $(item).find('input[name*="board_id"]').prop('name', 'list[board_id]['+index+'][]');
                $(item).find('select[name*="board"]').prop('name', 'list[board]['+index+'][]');
                $(item).find('.start_std_check').prop('name', 'list[start_std]['+index+']');
                $(item).find('.start_std_check').attr('data-id', '#start_std'+index);
                $(item).find('.start_std').attr('id', 'start_std'+index);
                $(item).find('.reason').prop('name', 'list[reason]['+index+']');
                $(item).find('.ref_std').prop('name', 'list[ref_std]['+index+']');
                $(item).find('.bcertify_reason').prop('name', 'list[bcertify_reason]['+index+']');
            });
        }
        
        //รีเซตลำดับความเห็นการกำหนดมาตรฐาน
        function reset_board(){
            $('.box-board').each(function(index, item) {
                $(this).find('.item-board').each(function(index, item) {
                    $(item).find('.order-board').text(index+1);
                    if(index > 0){//ใส่ปุ่มลบแทนปุ่มบวก
                        $(item).find('.tool-board').html('<button type="button" class="btn btn-danger btn-xs remove-board"><i class="fa fa-times"></i></button>');
                    }
                });
            });
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
             }


               // 1. ดักจับ event 'change' เมื่อมีการเลือกค่าใน select#input-request
        $('#input-request').on('change', function() {

            var selectedOption = $(this).find('option:selected');
            var selectedValue  = $(this).val(); // ดึงค่า value ที่เลือกมาเก็บไว้

            // --- ดึงข้อมูลจาก data-* attributes ---
            var standardTypes     = selectedOption.data('standard_types');
            var objectve          = selectedOption.data('objectve');
            var isoNumber         = selectedOption.data('iso_number');
            var standardName      = selectedOption.data('standard_name');
            var standardNameEn    = selectedOption.data('standard_name_en');
            var proposerType      = selectedOption.data('proposer_type');

            console.log(standardTypes)
            
            // --- โค้ดเดิมที่ทำงานกับ select, radio, และ text inputs ---
            // $('#std_type_select').val( $('#std_type_select option').eq(standardTypes).val() ).trigger('change');
            $('#std_type_select').val(standardTypes).trigger('change');
            

            console.log(objectve)

            if (objectve === 'first_creation') {
                $('.start_std_check[value="1"]').iCheck('check');
            } else if (objectve === 'standard_revision') {
                $('.start_std_check[value="2"]').iCheck('check');
            }

            $('#tis_number_input').val(isoNumber);
            $('#tis_name_input').val(standardName);
            $('#tis_name_eng_input').val(standardNameEn);

            //  console.log(proposerType)
            if (proposerType === 'sdo_advanced') {
                $('#method_id_select').val('1').trigger('change');
            } else if (proposerType === 'sdo_basic_or_non_sdo') {
                $('#method_id_select').val('3').trigger('change');
            }

            // ================================================================
            // ===== ส่วนที่เพิ่มเข้ามา: อัปเดต Select ตัวแรกในตาราง =====
            // ================================================================

             
            if (selectedValue) { 
                // ปรับ Selector โดยการระบุแท็ก "select" เข้าไปตรงๆ
                $('select.input-board:first').val(selectedValue).trigger('change');
            }

            $.ajax({
                    type: 'post',
                    url: "{!! url('certify/standard-drafts/get_examminer') !!}" ,
                    data:{
                        id:  selectedValue,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(responseArray) { // เปลี่ยนชื่อตัวแปรเป็น responseArray เพื่อความชัดเจน

                    console.log("ข้อมูล Array ที่ได้รับจาก Server:", responseArray);

                    // 1. ตรวจสอบว่าข้อมูลที่ได้เป็น Array และมีข้อมูลอย่างน้อย 1 ตัว
                    if (Array.isArray(responseArray) && responseArray.length > 0) {

                        // 2. ดึงค่า ID ตัวแรกจาก Array (index 0)
                        var firstAssignId = responseArray[0];

                        // 3. ตั้งค่า select .offers_assign ให้เลือก ID นั้น และ trigger change
                        $('.offers_assign').val(firstAssignId).trigger('change');

                        console.log("เลือกเจ้าหน้าที่จาก ID แรกใน Array:", firstAssignId);

                    } else {
                        // 4. ถ้าไม่มีข้อมูล หรือเป็น Array ว่าง, ให้ reset dropdown กลับไปที่ placeholder
                        $('.offers_assign').val('').trigger('change');
                        console.log("ไม่ได้รับข้อมูลเจ้าหน้าที่ หรือได้รับ Array ว่าง");
                    }
                });
        });

    </script>
@endpush
 