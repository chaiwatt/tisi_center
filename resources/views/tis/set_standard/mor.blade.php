@push('css')
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
<style type="text/css">
.bootstrap-tagsinput {
    width: 100% !important;
  }
</style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('review_status') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-7">
                <label>{!! Form::radio('review_status', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-yellow']) !!}
                    ทบทวน
                 </label>
                <label>{!! Form::radio('review_status', '1',true  , ['class'=>'check', 'data-radio'=>'iradio_square-yellow']) !!}
                    กำหนดใหม่ 
                </label>
                {!! $errors->first('review_status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row" id="show_revise">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('revise_status') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-7">
                <label>{!! Form::radio('revise_status', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue']) !!}
                    เวียนทบทวน
                 </label>
                <label>{!! Form::radio('revise_status', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-blue']) !!}
                    ไม่เวียนทบทวน 
                </label>
                {!! $errors->first('revise_status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('plan_year') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('plan_year', 'ปีงบประมาณที่เสนอเข้าแผน'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-4">
                        {!! Form::select('plan_year', 
                           HP::Years() , 
                            null,
                        ['class' => 'form-control',
                        'id' => 'plan_year',
                        'placeholder'=>'- เลือกปีงบประมาณที่เสนอเข้าแผน -', 
                        'required' => true]); !!}
           
                {!! $errors->first('plan_year', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('tis_no') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('tis_no', 'เลขที่ มอก.'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                     <div id="div_tis_no1">
                             {!! Form::text('tis_no', null, ['id'=>'tis_no1', 'class' => 'form-control', 'required' =>  true]) !!}     
                   </div>
                    <div id="div_tis_no2">
                        {!! Form::select('tis_no', 
                              [], 
                              null,
                            ['class' => 'form-control',
                            'id' => 'tis_no2',
                            'placeholder'=>'- เลือกเลขที่ มอก.-', 
                            'required' => true]); !!}
                    </div>
                   {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('tis_book') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('tis_book', 'เล่ม'.' :', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
                <div id="div_tis_book1">
                    {!! Form::text('tis_book', null, ['id'=>'tis_book1', 'class' => 'form-control', 'required' =>  false]) !!}     
          </div>
           <div id="div_tis_book2">
               {!! Form::select('tis_book', 
                     [], 
                     null,
                   ['class' => 'form-control',
                   'id' => 'tis_book2',
                   'placeholder'=>'- เลือกเล่ม-', 
                   'required' => false]); !!}
           </div>
       {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
         <div class="form-group {{ $errors->has('tis_book') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('start_year', 'ปี มอก.'.' :', ['class' => 'col-md-4 control-label'])) !!}
            <div class="col-md-8">
               {!! Form::select('start_year', 
                 App\Models\Tis\Standard::orderBy('tis_year','desc')->pluck('tis_year', 'tis_year') , 
               null,
              ['class' => 'form-control',
              'id' => 'start_year',
              'placeholder'=>'-เลือกปีมอก.-', 
              'required' => false]); !!}
               {!! $errors->first('start_year', '<p class="help-block">:message</p>') !!}
           </div>
        </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('tis_tisno') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('tis_tisno', 'เลข มอก. (แบบเดิม)'.' :', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-8">
                {!! Form::text('tis_tisno', null, [ 'class' => 'form-control', 'required' =>  true]) !!}     
                {!! $errors->first('tis_tisno', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
         <div class="form-group required{{ $errors->has('tis_tisshortno') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('tis_tisshortno', 'เลข มอก. (แบบย่อ)'.' :', ['class' => 'col-md-4 control-label'])) !!}
            <div class="col-md-8">
                {!! Form::text('tis_tisshortno', null, [ 'class' => 'form-control', 'required' =>  true]) !!}     
               {!! $errors->first('tis_tisshortno', '<p class="help-block">:message</p>') !!}
           </div>
        </div>
    </div>
  </div> --}}


  <div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('title', 'ชื่อมาตรฐาน (TH)'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                {!! Form::text('title', null, ['class' => 'form-control', 'required' =>  true]) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('title_en') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('title_en', 'ชื่อมาตรฐาน (EN)'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                {!! Form::text('title_en', null, ['class' => 'form-control', 'required' =>  true]) !!}
                {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('made_by') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('made_by', 'จัดทำโดย'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('made_by', 
                 HP::Mades() , 
               null,
              ['class' => 'form-control',
              'id' => 'made_by',
              'placeholder'=>'-เลือกจัดทำโดย-', 
              'required' => true]); !!}
                {!! $errors->first('made_by', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('set_format_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('set_format_id', 'ใหม่/ทบทวน'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('set_format_id', 
                 App\Models\Basic\SetFormat::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'set_format_id',
              'placeholder'=>'-เลือกใหม่/ทบทวน-', 
              'required' => true]); !!}
                {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group required sdo_name{{ $errors->has('sdo_name') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('sdo_name', 'ชื่อหน่วยงาน (SDO)'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::text('sdo_name', null, ['class' => 'form-control', 'required' =>  false]) !!}
                {!! $errors->first('sdo_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('method_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('method_id', 'วิธีจัดทำ'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('method_id', 
                 App\Models\Basic\Method::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'method_id',
              'placeholder'=>'-เลือกวิธีจัดทำ-', 
              'required' => true]); !!}
                {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('product_group_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('product_group_id', 'สาขา'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('product_group_id', 
                App\Models\Basic\ProductGroup::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'product_group_id',
              'placeholder'=>'-เลือกสาขา-', 
              'required' => true]); !!}
                {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('method_id_detail') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('method_id_detail', 'รายละเอียดย่อยวิธีจัดทำ'.':', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('method_id_detail', 
                [], 
               null,
              ['class' => 'form-control',
              'id' => 'method_id_detail',
              'placeholder'=>'-เลือกรายละเอียดย่อยวิธีจัดทำ-', 
              'required' => true]); !!}
                {!! $errors->first('method_id_detail', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('appoint_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('appoint_id', 'คณะกรรมการ'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
             {!! Form::select('appoint_id', 
               App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->where('state',1)->orderbyRaw('CONVERT(board_position USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'appoint_id',
              'placeholder'=>'-เลือกคณะกรรมการ-', 
              'required' => true]); !!}
                {!! $errors->first('appoint_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('industry_target_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('industry_target_id', 'อุตสาหกรรมเป้าหมาย'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('industry_target_id', 
                 App\Models\Basic\Method::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'industry_target_id',
              'placeholder'=>'-เลือกอุตสาหกรรมเป้าหมาย-', 
              'required' => true]); !!}
                {!! $errors->first('industry_target_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  
  <div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('standard_type_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('standard_type_id', 'ประเภท มอก.'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
             {!! Form::select('standard_type_id', 
                App\Models\Basic\StandardType::selectRaw("CONCAT(acronym,' - ',title,' (',title_en,')') as title, id")->where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') , 
               null,
              ['class' => 'form-control',
              'id' => 'standard_type_id',
              'placeholder'=>'-เลือกประเภท มอก.-', 
              'required' => true]); !!}
                {!! $errors->first('standard_type_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cluster_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('cluster_id', 'หมวดหมู่'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('set_format_id', 
                 App\Models\Basic\Cluster::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'cluster_id', 
              'placeholder'=>'-เลือกหมวดหมู่-', 
              'required' => false]); !!}
                {!! $errors->first('cluster_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('standard_format_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'ทั่วไป/บังคับ'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
             {!! Form::select('standard_format_id', 
                 App\Models\Basic\Method::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'standard_format_id',
              'placeholder'=>'-เลือกทั่วไป/บังคับ-', 
              'required' => true]); !!}
                {!! $errors->first('standard_format_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('staff_group') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('staff_group', 'กลุ่มที่'.' :', ['class' => 'col-md-4 control-label'])) !!}
             <div class="col-md-8">
                {!! Form::select('staff_group', 
                  App\Models\Basic\StaffGroup::selectRaw('CONCAT(`order`," ",title) As title, id')->where('state',1)->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'staff_group',
              'placeholder'=>'-เลือกกลุ่มที่-', 
              'required' => true]); !!}
                {!! $errors->first('staff_group', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group  {{ $errors->has('secretary') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'เลขานุการ'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                {!! Form::text('secretary', null, ['id'=>'secretary', 'class' => 'form-control', 'required' =>  false]) !!}
                {!! $errors->first('secretary', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="  {{ $errors->has('refer') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'มาตรฐานอ้างอิง'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                <div id="refer-box">
                    @if(!empty($set_standard->refer))  
                    @php
                        $refers = json_decode($set_standard->refer);
                      
                    @endphp
                       @if(!is_null($refers) && count($refers) > 0)  
                            @foreach($refers as $key => $item)
                                <div class=" row refer_item form-group">
                                    <div class="col-md-11">
                                        {!! Form::text('refer[]', $item, ['class' => 'form-control', 'required' =>  false]) !!}
                                    </div>
                                    <div class="col-md-1">
                                        @if ($key == 0 )
                                            <button type="button" class="btn btn-sm btn-success pull-right refer-add"  id="refer-add" >
                                                <i class="icon-plus"></i>
                                            </button>
                                            <div class="button_remove"></div>
                                        @else
                                         <button class="btn btn-danger btn-sm pull-right  remove" type="button"> <i class="icon-close"></i></button>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <div class=" row refer_item form-group">
                                <div class="col-md-11">
                                    {!! Form::text('refer[]', null, ['class' => 'form-control', 'required' =>  false]) !!}
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-success pull-right refer-add"  id="refer-add" >
                                        <i class="icon-plus"></i>
                                    </button>
                                    <div class="button_remove"></div>
                                </div>
                            </div>
                            
                        @endif 
                    @else 
                      <div class=" row refer_item form-group">
                        <div class="col-md-11">
                            {!! Form::text('refer[]', null, ['class' => 'form-control', 'required' =>  false]) !!}
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-success pull-right refer-add"  id="refer-add" >
                                <i class="icon-plus"></i>
                            </button>
                            <div class="button_remove"></div>
                        </div>
                     </div>
                     @endif   


                </div>
            </div>
        </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
        <div class="form-group  {{ $errors->has('remark') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'หมายเหตุ'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 4, 'v-model' => 'form.remark']) !!}
                {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group  {{ $errors->has('secretary') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'ไฟล์แนบ'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                <div id="attach-box">
                    <div class="row attach_item">
                        <div class="col-md-5  ">
                             <input type="text" name="attach_notes[]" class="form-control" placeholder="คำอธิบาย(ถ้ามี)">
                        </div>
                        <div class="col-md-5">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>  
                                    <input type="file" name="attachs[]" class="attachs check_max_size_file"   >
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        </div>
                        <div class="col-md-2" >
                            <button type="button" class="btn btn-sm btn-success attach-add {{isset($cost)  && ($cost->vehicle == 1) ? 'hide' : ''}}"  id="attach-add">
                                <i class="icon-plus"></i>&nbsp;เพิ่ม
                            </button>
                            <div class="button_remove"></div>
                        </div>
                    </div>
                @if(!empty($set_standard->attach))  
                    @php
                        $attachs = json_decode($set_standard->attach);
                         $attach_path = 'tis_attach/set_standard/';
                    @endphp
                       @if(!is_null($attachs))  
                    @foreach($attachs as $key => $item)
                        <div class="row attach_item ">
                            <div class="col-md-5  ">
                                <input type="text" name="attach_notes[]" value="{{ $item->file_note }}"  class="form-control" placeholder="คำอธิบาย(ถ้ามี)">
                                <input type="hidden" name="attach_filenames[]" value="{{ $item->file_name }}"  class="form-control" >
                            </div>
                            <div class="col-md-5">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>  
                                        <input type="file" name="attachs[]" class="attachs check_max_size_file"   >
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2" >
                                @if ($item->file_name !='' &&  HP::checkFileStorage($attach_path.$item->file_name))
                                    <a href="{{  HP::getFileStorage($attach_path.$item->file_name) }}" class="view-attach btn btn-info btn-sm" target="_blank">
                                        <i class="fa fa-search"></i>
                                    </a> 
                                @endif   
                                 <button class="btn btn-danger btn-sm   remove" type="button"> <i class="icon-close"></i></button>
                            </div>
                        </div>
                        
                    @endforeach
                    @endif 
                 @endif   

                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="form-group  {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'สถานะ'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                   <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
                        ใช้งาน </label>
                    <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!}
                        ยกเลิก </label>
            </div>
        </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-md-12">
        <div class="form-group  {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('', 'สถานะเผยแพร่'.' :', ['class' => 'col-md-2 control-label'])) !!}
             <div class="col-md-10">
                   <label>{!! Form::radio('publishing_status', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!}
                        เปิด </label>
                    <label>{!! Form::radio('publishing_status', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!}
                        ปิด </label>
            </div>
        </div>
    </div>
  </div> --}}

 
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




 
  @push('js')
  <!-- tag input -->
<script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
  <script type="text/javascript">
        $(document).ready(function() {

            //เมื่อเลือกจัดทำโดย
            $('#made_by').change(function(event) {
            if($(this).val()=='SDO'){ 
                $('.sdo_name').show();
                $('#sdo_name').prop('required', true);
            }else{
                $('.sdo_name').hide();
                $('#sdo_name').val('');
                $('#sdo_name').prop('required', false);
            }
            });
            $('#made_by').change();
             

            //วิธีจัดทำ
            $('#method_id').change(function(event) {
                var method_id_detail = '<?php  echo !empty($set_standard->method_id_detail) ? $set_standard->method_id_detail:null ?>';
        
                $('#method_id_detail').html('<option value="">-เลือกรายละเอียดย่อยวิธีจัดทำ-</option>');
                if($(this).val()!=''){
                    const url = "{{ url('api/tis/get_method_detail') }}/"+$(this).val();
                     $.ajax({
                        type: "GET",
                        url: url,
                        success: function (datas) {
                            var i = 0;
                        $.each(datas,function (index,value) {
                              var selected = (i == method_id_detail)?'selected="selected"':'';
                              $('#method_id_detail').append('<option value='+i+' '+selected+' >'+value+'</option>');
                              i++
                          });
                          $('#method_id_detail').select2();
                        }
                    });
                }
         
            });
            $('#method_id').change();

            // เลขานุการ
            $('#secretary').tagsinput({
                onTagExists: function(item, $tag) {
                        $tag.hide().fadeIn();
            },
            maxTags: 5,
            });

            //เพิ่มมาตรฐานอ้างอิง
            $('#refer-add').click(function(event) {
                $('.refer_item:first').clone().appendTo('#refer-box');
                $('.refer_item:last').find('input').val('');
                $('.refer_item:last').find('button.refer-add').remove();
                $('.refer_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm pull-right refer-remove" type="button"> <i class="icon-close"></i></button>');
            });
            //ลบมาตรฐานอ้างอิง
            $('body').on('click', '.refer-remove', function(event) {
                $(this).parent().parent().parent().remove();
             });



               //เพิ่มไฟล์แนบ
              $('#attach-add').click(function(event) {
                $('.attach_item:first').clone().appendTo('#attach-box');
                $('.attach_item:last').find('input').val('');
                $('.attach_item:last').find('a.fileinput-exists').click();
                $('.attach_item:last').find('a.view-attach').remove(); 
                $('.attach_item:last').find('.attach-span').remove();
                $('.attach_item:last').find('button.attach-add').remove();
                $('.attach_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm  attach-remove" type="button"> <i class="icon-close"></i>  </button>');
                check_max_size_file();
            });
            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().parent().remove();
             });
             $('body').on('click', '.remove', function(event) {
                $(this).parent().parent().remove();
             });
       

             $('#tis_no2').change(function(event) {
 
                if($(this).val()!=''){
                    const url = "{{ url('api/tis/set_standard/standard-first') }}/"+$(this).val();
                     $.ajax({
                        type: "GET",
                        url: url,
                        success: function (datas) {
                            if( datas.standards){
                                var data =  datas.standards;
                                $('#title').val(data.title);
                                $('#title_en').val(data.title_en);               
                            }
                        }
                    });
                }else{

                }
         
            });


        });
  
  </script>
  @endpush
  