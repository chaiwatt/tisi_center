@push('css')
 
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <style>
        .vertical {
            float: left;
            border-right: 2px solid #eee;
        }
        input[type="checkbox"]:disabled {
            cursor: not-allowed;
        }
        .alert-secondary {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }
        .fileinput-filename{
            display:none;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend><b>ข้อมูลผู้กระทำความผิด</b>  </legend>

            <div class="row">
                <div class="col-md-5">
                    {!! HTML::decode(Form::label('created_by_show', 'ชื่อผู้ประกอบการ', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5 ">
                        <p class="font-medium-6"> {!!   !empty($case->offend_name) ? $case->offend_name : null !!}</p>
                    </div>
                </div>
                <div class="col-md-7">
                    {!! HTML::decode(Form::label('created_by_show', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5">
                        <p class="font-medium-6"> {!!   !empty($case->offend_taxid) ? $case->offend_taxid : null !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    {!! HTML::decode(Form::label('created_by_show', 'มอก.', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5 ">
                        <p class="font-medium-6"> {!!   !empty($case->StandardNo) ? $case->StandardNo : null !!}</p>
                    </div>
                </div>
                <div class="col-md-7">
                    {!! HTML::decode(Form::label('created_by_show', 'ผลิตภัณฑ์', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5">
                        <p class="font-medium-6"> {!!   !empty($case->StandardName) ? $case->StandardName : null !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    {!! HTML::decode(Form::label('created_by_show', 'ผ่าฝืนมาตรา', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5 ">
                        <p class="font-medium-6"> {!! !empty($case->section_list)?$case->SectionListName:'-' !!}</p>
                    </div>
                </div>
                <div class="col-md-7">
                    {!! HTML::decode(Form::label('created_by_show', 'การจับกุม', ['class' => 'col-md-5 control-label  text-right'])) !!}
                    <div class="col-md-7 p-t-5">
                        <p class="font-medium-6"> {!! !empty($case->law_basic_arrest_to->title) ? $case->law_basic_arrest_to->title : null !!}</p>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>
</div>

@php
    $option_status     = App\Models\Law\Cases\LawCasesForm::status_list();
    $option_section1   = App\Models\Law\Basic\LawSection::where('section_type','1')->select(DB::Raw('CONCAT(number," : ",title) AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    $option_section2   = App\Models\Law\Basic\LawSection::where('section_type','2')->select(DB::Raw('CONCAT(number," : ",title) AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');

    $log               = App\Models\Law\Log\LawLogWorking::where(function($query) use($case){
                                                            $query->where('ref_table', (new App\Models\Law\Cases\LawCasesForm)->getTable() )
                                                                    ->where('ref_id', $case->id )
                                                                    ->where('ref_system', "ผลพิจารณางานคดี" )
                                                                    ->where('title', "บันทึกผลพิจารณางานคดี" );
                                                        })
                                                        ->orderByDesc('created_at')
                                                        ->first();                                    
    //เลขรัน
    $ref_type          =( $case->owner_depart_type == 1)? 'I':'O';
    $running_no        =  HP::ConfigFormat('LawCasesNumber', (new App\Models\Law\Cases\LawCasesForm)->getTable(), 'case_number', $ref_type, null, null);
    $check             = App\Models\Law\Cases\LawCasesForm::where('case_number', $running_no)->first();
    if(!is_null($check)){
        $running_no    =  HP::ConfigFormat('LawCasesNumber', (new App\Models\Law\Cases\LawCasesForm)->getTable(), 'case_number', $ref_type, null, null);
    }
    // $case->case_number = !empty($case->case_number)?$case->case_number:null;
@endphp

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend><b>ข้อมูลการกระทำความผิด</b></legend>

            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend> มาตราความผิด/บทกำหนดลงโทษ </legend>

                        <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                            {!! Form::label('status', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-4">
                                {!! Form::select('status', [  '5'=> 'พบการกระทำความผิด','6'=> 'ไม่พบการกระทำความผิด','7'=> 'ส่งเรื่องดำเนินคดี'],  !empty($log->status)?array_search ($log->status, $option_status):null , ['class' => 'form-control',  'id' => 'status', 'required' => true,'placeholder'=>'-เลือกสถานะ-']) !!}
                                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="box_status">

                            @php

                            @endphp     

                            <div class="form-group {{ $errors->has('section') ? 'has-error' : ''}}" >
                                {!! Form::label('case_number', 'เลขคดี', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('case_number' ,  !empty($case->case_number)?$case->case_number:null,  ['class' => 'form-control', 'readonly' => true, 'required' => false,  'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึก',  'id' => 'case_number']) !!}
                                    {!! $errors->first('case_number', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group" >
                                {!! Form::label('section', 'มาตราความผิด', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('section',$option_section1 ,  null,  ['class' => 'form-control',  'placeholder'=>'- เลือกมาตราความผิด -',  'id' => 'section']) !!}
                                </div>
                            </div>

                            <div class="form-group" >
                                {!! Form::label('punish', 'บทกำหนดลงโทษตามมาตรา', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('punish', $option_section2,  null, ['class' => 'form-control punish', 'placeholder'=>'- เลือกบทกำหนดลงโทษตามมาตรา -', 'id' => 'punish'])!!}
                                </div>
                            </div>

                            <center>
                                <button type="button" class="btn btn-info" id="choose_section">เลือก</button>
                            </center>

                            <div class="col-md-12 m-t-30">
                                <div class="table-responsive">
                                    <table class="table color-bordered-table info-bordered-table table-bordered table-sm">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="2%">#</th>
                                            <th class="text-center" width="30%">มาตราความผิด</th>
                                            <th class="text-center" width="30%">บทกำหนดลงโทษ</th>
                                            <th class="text-center" width="30%">อำนาจพิจารณาเปรียบเทียบปรับ</th>
                                            <th class="text-center" width="8%">ลบ</th>
                                        </tr>
                                        </thead>
                                        <tbody id="table_tbody_section">
                                            @if (!empty($result) && count($result->law_case_result_section_many) > 0)
                                                @foreach ($result->law_case_result_section_many as $key => $item )
                                                    <tr>
                                                        <td class="text-center text-top"> {{ ($key+1)}}</td>
                                                        <td class="text-top">
                                                            {{ !empty($item->section_to->number)  &&  !empty($item->section_to->title) ?  $item->section_to->number.' : '.$item->section_to->title : ''  }}
                                                        </td>
                                                        <td class="text-top">
                                                            {{ !empty($item->punish_to->number)  &&  !empty($item->punish_to->title) ?  $item->punish_to->number.' : '.$item->punish_to->title : ''  }}
                                                        </td>
                                                        <td class="text-top">
                                                            {{ !empty($item->PowerName) ?  $item->PowerName : ''  }}     
                                                        </td>
                                                        <td class="text-center text-top">
                                                            <button type="button" class="btn btn-link  remove-row"><i class="fa fa-close text-danger"></i></button>
                                                            <input type="hidden" name="section[id][]"   value="{{$item->id}}">
                                                            <input type="hidden" name="section[section_id][]" class="section_id" value="{{$item->section}}">
                                                            <input type="hidden" name="section[punish_id][]" class="punish_id" value="{{$item->punish}}">
                                                            <input type="hidden" name="section[power_id][]"  value="{{$item->power}}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend> การดำเนินการงานคดี </legend>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-md-0 mb-4 m-t-5 vertical">

                                <div class="form-group {{ $errors->has('person') ? 'has-error' : ''}}">
                                    {!! Form::label('person', 'การดำเนินการงานคดี', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        @if ($case->offend_license_type != 1) <!-- ไม่มีเลขใบอนุญาต -->
                                            <div class="checkbox checkbox-warning">
                                                <input id="person" name="person" type="checkbox" value="1"  checked  disabled>
                                                <label for="person"> ดำเนินการทางอาญา </label>
                                            </div>
                                            <div class="checkbox checkbox-warning">
                                                <input id="license" name="license"   type="checkbox"  value="1"   disabled>
                                                <label for="license"> ดำเนินการปกครอง (ใบอนุญาต) </label>
                                            </div>
                                                <div class="checkbox checkbox-warning">
                                                <input id="product" name="product"   type="checkbox"  value="1" checked disabled>
                                                <label for="product"> ดำเนินการของกลาง (ผลิตภัณฑ์) </label>
                                            </div>
                                        @elseif(!empty($result))    <!-- ข้อมูลการดำเนินการงานคดี --> 
                                            <div class="checkbox checkbox-warning">
                                                <input id="person" name="person" type="checkbox" value="1" {{  !empty($result->person) ? 'checked' : '' }}>
                                                <label for="person"> ดำเนินการทางอาญา </label>
                                            </div>
                                            <div class="checkbox checkbox-warning">
                                                <input id="license" name="license"   type="checkbox"  value="1" {{  !empty($result->license) ? 'checked' : '' }}>
                                                <label for="license"> ดำเนินการปกครอง (ใบอนุญาต) </label>
                                            </div>
                                            <div class="checkbox checkbox-warning">
                                                <input id="product" name="product"   type="checkbox"  value="1" {{  !empty($result->product) ? 'checked' : '' }}>
                                                <label for="product"> ดำเนินการของกลาง (ผลิตภัณฑ์) </label>
                                            </div>
                                        @else    <!-- ไม่มีข้อมูลการดำเนินการงานคดี --> 
                                            <div class="checkbox checkbox-warning">
                                                <input id="person" name="person" type="checkbox" value="1" checked>
                                                <label for="person"> ดำเนินการทางอาญา </label>
                                            </div>
                                            <div class="checkbox checkbox-warning">
                                                <input id="license" name="license"   type="checkbox"  value="1" >
                                                <label for="license"> ดำเนินการปกครอง (ใบอนุญาต) </label>
                                            </div>
                                            <div class="checkbox checkbox-warning">
                                                <input id="product" name="product"   type="checkbox"  value="1" checked>
                                                <label for="product"> ดำเนินการของกลาง (ผลิตภัณฑ์) </label>
                                            </div>
                                
                                        @endif
                                
                                    </div>
                                </div>

                                <p class="text-muted text-right"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>

                                <div class="form-group {{ $errors->has('file_consider') ? 'has-error' : ''}}">
                                    {!! Form::label('file_consider', 'หลักฐานผลพิจารณา(ถ้ามี)', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        @if (!empty($result->AttachFileConsider))
                                            @php
                                                $attachs_consider = $result->AttachFileConsider;
                                            @endphp
                                            <a href="{!! HP::getFileStorage($attachs_consider->url) !!}" target="_blank">
                                                {!! !empty($attachs_consider->filename) ? $attachs_consider->filename : '' !!}
                                                {!! HP::FileExtension($attachs_consider->url) ?? '' !!}
                                            </a>
                                            <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_consider->id).'/'.base64_encode('law/cases/results/'.$case->id.'/consider') ) !!}" title="ลบไฟล์">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        @else
                                           <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_consider" id="file_consider"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                           </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('file_consider_result') ? 'has-error' : ''}}">
                                    {!! Form::label('file_consider_result', 'บันทึกพิจารณาคดี', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        @if (!empty($result->AttachFileConsiderResult))
                                            @php
                                                $attachs_consider_result = $result->AttachFileConsiderResult;
                                            @endphp
                                            <a href="{!! HP::getFileStorage($attachs_consider_result->url) !!}" target="_blank">
                                                {!! !empty($attachs_consider_result->filename) ? $attachs_consider_result->filename : '' !!}
                                                {!! HP::FileExtension($attachs_consider_result->url) ?? '' !!}
                                            </a>
                                            <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_consider_result->id).'/'.base64_encode('law/cases/results/'.$case->id.'/consider') ) !!}" title="ลบไฟล์">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        @else
                                           <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_consider_result" id="file_consider_result"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                           </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('file_consider_compares') ? 'has-error' : ''}}">
                                    {!! Form::label('file_consider_compares', 'เปรียบเทียบปรับ', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        @if (!empty($result->AttachFileConsiderCompares))
                                            @php
                                                $attachs_consider_compares = $result->AttachFileConsiderCompares;
                                            @endphp
                                            <a href="{!! HP::getFileStorage($attachs_consider_compares->url) !!}" target="_blank">
                                                {!! !empty($attachs_consider_compares->filename) ? $attachs_consider_compares->filename : '' !!}
                                                {!! HP::FileExtension($attachs_consider_compares->url) ?? '' !!}
                                            </a>
                                            <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_consider_compares->id).'/'.base64_encode('law/cases/results/'.$case->id.'/consider') ) !!}" title="ลบไฟล์">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        @else
                                           <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_consider_compares" id="file_consider_compares"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                           </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('file_consider_comparison_facts') ? 'has-error' : ''}}">
                                    {!! Form::label('file_consider_comparison_facts', 'ข้อเท็จจริงการเปรียบเทียบปรับ', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        @if (!empty($result->AttachFileConsiderComparisonFacts))
                                            @php
                                                $attachs_consider_comparison_facts = $result->AttachFileConsiderComparisonFacts;
                                            @endphp
                                            <a href="{!! HP::getFileStorage($attachs_consider_comparison_facts->url) !!}" target="_blank">
                                                {!! !empty($attachs_consider_comparison_facts->filename) ? $attachs_consider_comparison_facts->filename : '' !!}
                                                {!! HP::FileExtension($attachs_consider_comparison_facts->url) ?? '' !!}
                                            </a>
                                            <a class="btn btn-danger btn-xs show_tag_a m-l-15" href="{!! url('law/delete-files/'.($attachs_consider_comparison_facts->id).'/'.base64_encode('law/cases/results/'.$case->id.'/consider') ) !!}" title="ลบไฟล์">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        @else
                                           <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_consider_comparison_facts" id="file_consider_comparison_facts"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                           </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('file_other') ? 'has-error' : ''}}">
                                    {!! Html::decode(Form::label('file_other', 'ไฟล์เเนบ'.'<div><span class="text-muted m-b-30 font-14"><i>(เพิ่มได้ไม่เกิน 3 ไฟล์)</i></span></div>', ['class' => 'col-md-4 control-label'])) !!}
                                    <div class="col-md-8 repeater-form-file">
                                        @php
                                            //ไฟล์เเนบ
                                            $attachs_result_others   = !empty($result->AttachFileOther)? $result->AttachFileOther:[];
                                        @endphp
                                        @if (count($attachs_result_others) > 0)
                                  
                                            @foreach ($attachs_result_others as $attachs_result_other)
                                                <p>     
                                                    <a href="{!! HP::getFileStorage($attachs_result_other->url) !!}" target="_blank" class="file_max">
                                                        {!! !empty($attachs_result_other->filename) ? $attachs_result_other->filename : '' !!}
                                                        {!! HP::FileExtension($attachs_result_other->filename)  ?? '' !!}
                                                    </a>
                                                    <a class="btn btn-danger btn-xs show_tag_a m-l-10" href="{!! url('law/delete-files/'.($attachs_result_other->id).'/'.base64_encode('law/cases/results/'.$case->id.'/consider') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                </p>
                                            @endforeach
                                        @endif
                                        @if ( count($attachs_result_others) < 3)
                                        <div class="row" data-repeater-list="repeater-attach">
                                            <div class="repeater_form_file4" data-repeater-item>
                                                <div class="col-md-10">
                                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename"></span>
                                                        </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                                            <span class="input-group-text btn-file">
                                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                                <input type="file" name="file_other" class="check_max_size_file file_max">
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-success btn-sm btn-outline btn_file_add" data-repeater-create>
                                                        <i class="fa fa-plus"></i>
                                                    </button>  
                                                    <button class="btn btn-danger btn-sm btn_file_remove btn-outline" data-repeater-delete type="button">
                                                        <i class="fa fa-times"></i>
                                                    </button>              
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}" >
                                    {!! Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                        {!! Form::textarea('remark', !empty($result->remark) ? $result->remark : null , ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3']); !!}
                                        {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                
                                <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                                    {!! Form::label('', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                         {!! Form::text('', !empty($result->CreatedName) ? $result->CreatedName :   auth()->user()->FullName, ['class' => 'form-control ', 'disabled' => true ]) !!}
                                        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                
                                <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                                    {!! Form::label('', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-7">
                                         {!! Form::text('',!empty($result->created_at) ?HP::DateTimeThai($result->created_at) : HP::DateTimeThai(date('Y-m-d H:i:s')), ['class' => 'form-control ', 'disabled' => true ]) !!}
                                        {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 col-12 mb-md-0 mb-4 m-t-5">

                                @php
                                    $email_results = [];
                                    if(!is_null($law_notify)){

                                        // อีเมล
                                        $emails =  $law_notify->email;
                                        if(!is_null($emails)){
                                            $emails = json_decode($emails,true);
                                            if(!empty($emails) && count($emails) > 0){ 
                                                $email_results = $emails; 
                                            }
                                        }
                                    }else{ // ครั้งแรกแจ้งเตือน
                                           // เจ้าของคดี
                                        $owner_email =  (!empty($case->owner_email)  && filter_var($case->owner_email, FILTER_VALIDATE_EMAIL) ? $case->owner_email : null) ;
                                        if(!is_null($owner_email)){
                                            $email_results[] =  $owner_email;
                                        }
                                        // อีเมลผู้ประสานงาน (เจ้าของคดี)
                                        // $owner_contact_email =  (!empty($case->owner_contact_email)  && filter_var($case->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $case->owner_contact_email : null) ;
                                        // if(!is_null($owner_contact_email)){
                                        //     $email_results[] =  $owner_contact_email;
                                        // }

                                        // อีเมลผู้ประสานงาน (กระทำความผิด)
                                        // $offend_contact_email =  (!empty($case->offend_contact_email)  && filter_var($case->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $case->offend_contact_email : null) ;
                                        // if(!is_null($offend_contact_email)){
                                        //     $email_results[] =  $offend_contact_email;
                                        // }
                                    }

                                @endphp

                                <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                                    {!! Form::label('', 'ช่องทางแจ้งเตือน', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-3">
                                        <div class="checkbox checkbox-primary">
                                            <input id="checkbox1" type="checkbox" value="1" name="funnel_system" {!! !empty($law_notify->channel) && in_array( 1 ,  json_decode($law_notify->channel,true))?'checked':( empty($law_notify)?'checked':null ) !!} >
                                            <label for="checkbox1"> ผ่านระบบ </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="checkbox checkbox-primary">
                                            <input id="checkbox2" type="checkbox" value="2" name="funnel_email"  {!! !empty($law_notify->channel) && in_array( 2 ,json_decode($law_notify->channel,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                            <label for="checkbox2"> ผ่านอีเมล </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                                    {!! Form::label('', 'แจ้งเตือนไปยัง', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        <div class="checkbox checkbox-info">
                                            <input id="checkbox3" type="checkbox" value="1" name="owner_email" {!! !empty( $law_notify->notify_type ) && in_array( 1, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                            <label for="checkbox3"> เจ้าของคดี </label>
                                        </div>
                                        {{-- <div class="checkbox checkbox-info">
                                            <input id="checkbox4" type="checkbox" value="2" name="owner_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 2, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                            <label for="checkbox4"> ผู้ประสานงาน (เจ้าของคดี) </label>
                                        </div>
                                        <div class="checkbox checkbox-info">
                                            <input id="checkbox5" type="checkbox" value="3" name="offend_contact_email" {!! !empty( $law_notify->notify_type ) && in_array( 3, json_decode($law_notify->notify_type,true) )?'checked':( empty($law_notify)?'checked':null ) !!} >
                                            <label for="checkbox5">  ผู้ประสานงาน (กระทำความผิด) </label>
                                        </div> --}}
                                        <div class="checkbox checkbox-info">
                                            <input id="checkbox6" type="checkbox" value="4" name="reg_email"  {!! !empty( $law_notify->notify_type ) && in_array( 4,json_decode($law_notify->notify_type,true) )?'checked':null !!} >
                                            <label for="checkbox6"> ผู้มอบหมายงาน </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                                    <div class="col-md-offset-4 col-md-8">
                                        <input type="text" value="{{ count($email_results) > 0 ?  implode(",",$email_results) : '' }}" data-role="tagsinput"  name="email_results"  id="email_results"  /> 
                                    </div>
                                </div>
                                
                                <div class="form-group" >
                                    <div class="col-md-offset-4 col-md-8">     
                                        <div class="alert alert-bg-secondary font-15">
                                            <b>หมายเหตุ : กรณีที่ผู้รับแจ้งเตือนไม่ใช่สมาชิกในระบบจะไม่สามารถรับแจ้งเตือนผ่านระบบได้</b>
                                        </div>   
                                    </div>
                                </div>

                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>

        </fieldset>
    </div>
</div>

@if( in_array( $case->status , [4,5,6,7] ) )
    <div class="form-group">
        <div class="col-md-offset-5 col-md-4">

            <button class="btn btn-primary" type="button" id="submit_form_consider">
                <i class="fa fa-save"></i> บันทึก
            </button>
    
            @can('view-'.str_slug('law-cases-result'))
                <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/results') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@endif
            

@push('js')
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>

        var owner_email =  '{!!  (!empty($case->owner_email)  && filter_var($case->owner_email, FILTER_VALIDATE_EMAIL) ? $case->owner_email : '') !!}';
        var owner_contact_email =  '{!!  (!empty($case->owner_contact_email)  && filter_var($case->owner_contact_email, FILTER_VALIDATE_EMAIL) ? $case->owner_contact_email : '') !!}';
        var offend_contact_email =  '{!!  (!empty($case->offend_contact_email)  && filter_var($case->offend_contact_email, FILTER_VALIDATE_EMAIL) ? $case->offend_contact_email : '') !!}';
        var reg_email =  '{!!  (!empty($case->reg_email)  && filter_var($case->reg_email, FILTER_VALIDATE_EMAIL) ? $case->reg_email : '') !!}';

        $(document).ready(function () {

            $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 500);
                    }
                }
            });
            BtnDeleteFile();
            ResetTableNumberOther();

            @if ( !in_array( $case->status , [4,5,6,7] ))
                $('.form-consider').find('button[type="submit"]').remove();
                $('.form-consider').find('.icon-close').parent().remove();
                $('.form-consider').find('.fa-copy').parent().remove();
                $('.form-consider').find('input').prop('disabled', true);
                $('.form-consider').find('textarea').prop('disabled', true);
                $('.form-consider').find('select').prop('disabled', true);
                $('.form-consider').find('.bootstrap-tagsinput').prop('disabled', true);
                $('.form-consider').find('span.tag').children('span[data-role="remove"]').remove();
                $('.form-consider').find('button').prop('disabled', true);
                $('.form-consider').find('button').remove();
                $('.form-consider').find('.btn-remove-file').parent().remove();
                $('.form-consider').find('.show_tag_a').hide();
                $('.form-consider').find('.input_show_file').hide();
            @endif

            $("#form_consider" ).submit(function( event ) {
                
                var status      = $('#status').val();

                var tb_section  = $('#table_tbody_section > tr').length;

                if( status == 5 || status == 7){

                    if( tb_section == 0){
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'เลือกมาตราความผิด/บทกำหนดลงโทษอย่างน้อย 1 รายการ',
                            showConfirmButton: false,
                            // timer: 1500
                        });

                        event.preventDefault();
                    }

                }

                                    
            });

            $( "#submit_form_consider" ).click(function( event ) {
                var case_number = $('#case_number').val();
                if( case_number != '' ){
                    $.ajax({
                        method: "get",
                        url: "{{ url('law/cases/results/check_case_number') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id":  "{{ $case->id }}",
                            "case_number": case_number
                        }
                    }).success(function (msg) {
                        if (msg.message == true) {
                            Swal.fire({
                                        position: 'center',
                                        icon: 'warning',
                                        title: 'เลขคดี ' + case_number + ' นี้ถูกใช้ไปแล้ว!',
                                        showConfirmButton: false,
                                    });
                                
                        }else{
                            $("#form_consider" ).submit();
                        }
                    });  
                }else{
                    $("#form_consider" ).submit();
                }

                                    
            });

            // อีเมลเจ้าของคดี 
            var owner_email =  '{!!  (!empty($case->owner_email)  && filter_var($case->owner_email, FILTER_VALIDATE_EMAIL) ? $case->owner_email : '') !!}';
            $('#checkbox3').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_email != ''){
                    $('#email_results').tagsinput('add', owner_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_email);
                }
            });

            // อีเมลผู้ประสานงาน (เจ้าของคดี)
            $('#checkbox4').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && owner_contact_email != ''){
                    $('#email_results').tagsinput('add', owner_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', owner_contact_email);
                }
            });

            // อีเมลผู้ประสานงาน (กระทำความผิด)
            $('#checkbox5').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && offend_contact_email != ''){
                    $('#email_results').tagsinput('add', offend_contact_email); 
                }else{
                    $('#email_results').tagsinput('remove', offend_contact_email);
                }
            });

            // อีเมลผู้มอบหมายงาน
            $('#checkbox6').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && reg_email != ''){
                    $('#email_results').tagsinput('add', reg_email); 
                }else{
                    $('#email_results').tagsinput('remove', reg_email);
                }
            });

            
            $('#section').change(function(){

                $("#punish option").removeClass('show').addClass('hide');
                $("#punish option").attr('data-power','');
                $("#punish").val('').select2();
                if($(this).val() !== ''){
                    data_list_disabled();
                    $.ajax({
                        method: "get",
                        url: "{{ url('law/cases/results/consider_punish') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id":  $(this).val() 
                        }
                    }).success(function (msg) {
                        if (msg.message == true && msg.config_section.length > 0) {
                            $.each(msg.config_section,function (index,value) {
                                $("#punish option[value='" + value.section_id + "']").removeClass('hide').addClass('show');
                                $("#punish option[value='" + value.section_id + "']").attr('data-power', value.power);
                            });
                       
                        }
                    });     
                }
            }); 
            $('#section').change();  

            $("#choose_section").click(function() {

                if($('#section').val() == '' && $('#punish').val() == ''){
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกมาตราความผิดและบทกำหนดลงโทษตามมาตรา',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }else if($('#section').val() == ''){
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกมาตราความผิด',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }else if($('#punish').val() == ''){
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกบทกำหนดลงโทษตามมาตรา',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }else{
                    let section_id = $('#section').val();
                    let section_text = $('#section').find('option:selected').text();
                    let punish_id = $('#punish').val();
                    let punish_text = $('#punish').find('option:selected').text();
                    let power_id = $('#punish').find('option:selected').data('power');
                    let power_text = '';
                    if(power_id == 1){
                        power_text  = 'เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม (สมอ)';
                    }else if(power_id == 2){
                        power_text = 'คณะกรรมการเปรียบเทียบ';
                    }

                    var $tr = '';
                        $tr += '<tr>';
                        $tr += '<td class="text-center text-top"></td>';
                        $tr += '<td class="text-top">' +(section_text)+ '</td>';
                        $tr += '<td class="text-top">' +(punish_text)+'</td>';
                        $tr += '<td class="text-top">' +(power_text)+'</td>';
                        $tr += '<td class="text-center text-top">';
                        $tr += '<button type="button" class="btn btn-link  remove-row"><i class="fa fa-close text-danger"></i></button>';
                        $tr += '<input type="hidden" name="section[id][]"  >';
                        $tr += '<input type="hidden" name="section[section_id][]" class="section_id" value="'+section_id+'">';
                        $tr += '<input type="hidden" name="section[punish_id][]" class="punish_id" value="'+punish_id+'">';
                        $tr += '<input type="hidden" name="section[power_id][]" value="'+power_id+'">';
                        $tr += '</td>';
                        $tr += '</tr>';
                    $('#table_tbody_section').append($tr);
                    ResetTableNumber();
                    data_list_disabled();
                    $('#section, #punish').val('').select2();
                    $('#section').change();  
                }    
            });
            //ลบแถว
            $('body').on('click', '.remove-row', function(){
                $(this).parent().parent().remove();
                ResetTableNumber();
                data_list_disabled();
            });

            ResetTableNumber();
            data_list_disabled();
        
            $('#file_document').change( function () {
                var fileExtension = ['jpg','png' ,'pdf'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .jpg .png หรือ .pdf',
                        '',
                        'info'
                    );
                    this.value = '';
                    return false;
                }
            });

            $('#status').change(function (e) { 
              
                var status = $(this).val();
                if( status == 5 || status == 7){

                    $('.box_status').show();
                    $('.box_status').find('input').prop('disabled', false);
                    $('.box_status').find('textarea').prop('disabled', false);
                    $('.box_status').find('select').prop('disabled', false);

                    
                    // $('.box_status').find('#case_number').prop('required', true);

                }else{
                    $('.box_status').hide();

                    $('.box_status').find('input').prop('disabled', true);
                    $('.box_status').find('textarea').prop('disabled', true);
                    $('.box_status').find('select').prop('disabled', true);

                    $('.box_status').find('#case_number').prop('required', false);

                }
                
            });
            $('#status').change();
        });

        function ResetTableNumber(){
            var rows = $('#table_tbody_section').children(); //แถวทั้งหมด
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
         }

         function data_list_disabled(){
            $('.punish').children('option').prop('disabled',false);
            var rows = $('#table_tbody_section').children(); //แถวทั้งหมด
            $(rows).each(function(index , item){
                var section_id = $(item).find('.section_id').val();
                var punish_id = $(item).find('.punish_id').val();
                if(section_id == $('#section').val()){
                    $('.punish').children('option[value="'+punish_id+'"]').prop('disabled',true);
                }
                
            });
        }

        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            } 
              $('.btn_file_remove:first').hide();   
              $('.btn_file_add:first').show();   
              check_max_size_file();

            if( $('.file_max').length >= 3 ){//เพิ่มได้ไม่เกิน 3 ไฟล์
                $('.btn_file_add:first').prop('disabled', true); 
            }else{
                $('.btn_file_add:first').prop('disabled', false); 
            }
         }

        function ResetTableNumberOther(){
            var rows = $('#table_body').children(); //แถวทั้งหมด
            (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
            rows.each(function(index, el) {
                //เลขรัน
                $(el).children().first().html(index+1);
            });
        }    

    </script>
@endpush 