{{-- AuditorCBController --}}
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        /* ปิด hover สำหรับตารางนี้ */
        .table.color-bordered-table tbody tr:hover,
        .table.primary-bordered-table tbody tr:hover {
            background-color: transparent !important;
        }
    </style>
@endpush


<div class="row">

    <input type="hidden" name="signaturesJson" id="signaturesJson">
    <div class="col-md-12">
        <div class="col-md-9">
            <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}" hidden>
                {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  เลขคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    @if(isset($app_no))
                    {!! Form::select('app_certi_cb_id', 
                      $app_no, 
                      !empty($auditorcb->app_certi_cb_id) ? $auditorcb->app_certi_cb_id : null,
                     ['class' => 'form-control',
                     'id' => 'app_certi_cb_id',
                     'placeholder'=>'- เลขคำขอ -', 
                     'required' => true]); !!}
                    {!! $errors->first('app_certi_cb_id', '<p class="help-block">:message</p>') !!}
                    @else 
                        <input type="text" class="form-control" value="{{ $auditorcb->CertiCBCostTo->app_no ?? null }}"   disabled >
                    @endif
                </div>
            </div>
            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('no', '<span class="text-danger">*</span>  ชื่อผู้ยื่นคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('no', null, ['class' => 'form-control', 'placeholder'=>'', 'required' => true]); !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span>  ชื่อคณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('auditor', null, ['class' => 'form-control', 'placeholder'=>'', 'required' => true]); !!}
                </div>
            </div>
            @if(count($auditorcb->CertiCBAuditorsDates) > 0)
                @foreach ($auditorcb->CertiCBAuditorsDates as $key => $itme)
                <div class="form-group dev_form_date {{ $errors->has('judgement_date') ? 'has-error' : ''}}">
                        @if($key == 0)
                        {!! HTML::decode(Form::label('judgement_date', '<span class="text-danger">*</span>  วันที่ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                        @else
                        {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-5 control-label'])) !!}
                        @endif
                    <div class="col-md-6">
                        <div class="input-daterange input-group  date-range">
                            {!! Form::text('start_date[]',  !empty($itme->start_date) ?  HP::revertDate($itme->start_date,true) : null, ['class' => 'form-control date' , 'required' => true]) !!}
                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                            {!! Form::text('end_date[]', !empty($itme->end_date) ?  HP::revertDate($itme->end_date,true) : null, ['class' => 'form-control date', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="col-md-1">
                        @if($key == 0)
                        <button type="button" class="btn btn-success btn-sm pull-right add_date  {{ ($auditorcb->vehicle == 1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}" id="add_date">
                            <i class="icon-plus" aria-hidden="true"></i>
                            เพิ่ม
                        </button>
                        @else
                    <button type="button" class="btn btn-danger btn-sm pull-right date_edit_remove {{ ($auditorcb->vehicle == 1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}"><i class="fa fa-close" aria-hidden="true"></i> ลบ </button>
                        @endif
                        <div class="add_button_delete"></div>
                    </div>
                </div>
                @endforeach
            @else

            <div class="form-group dev_form_date {{ $errors->has('judgement_date') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('judgement_date', '<span class="text-danger">*</span>  วันที่ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-6">
                       <div class="input-daterange input-group date-range">
                        {!! Form::text('start_date[]', null, ['class' => 'form-control date', 'required' => true]) !!}
                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                        {!! Form::text('end_date[]', null, ['class' => 'form-control date', 'required' => true]) !!}
                      </div>
                </div>
                <div class="col-md-1">
                      <button type="button" class="btn btn-success btn-sm pull-right add_date" id="add_date">
                        <i class="icon-plus" aria-hidden="true"></i>
                        เพิ่ม
                    </button>
                    <div class="add_button_delete"></div>
                </div>
            </div>

            @endif

            <div class="form-group {{ $errors->has('other_attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('other_attach', '<span class="text-danger">*</span> บันทึก ลมอ. แต่งตั้งคณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    @if (!is_null($auditorcb->FileAuditors1) &&  $auditorcb->FileAuditors1 != '')
                        <p id="deleteFlieOtherAttach">
                          
                            <a href="{{url('certify/check/file_cb_client/'.$auditorcb->FileAuditors1->file.'/'.( !empty($auditorcb->FileAuditors1->file_client_name) ? $auditorcb->FileAuditors1->file_client_name :  basename($auditorcb->FileAuditors1->file) ))}}" target="_blank">
                                {!! HP::FileExtension($auditorcb->FileAuditors1->file)  ?? '' !!}
                            </a>
                            
                            {{-- <button class="btn btn-danger btn-xs deleteFlie  {{ ($auditorcb->vehicle == 1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}" type="button" onclick="deleteFlieOtherAttach({{ $auditorcb->FileAuditors1->id}})">
                                <i class="icon-close"></i>
                            </button>    --}}
                        </p> 
                        {{-- <div id="AddOtherAttach"></div>            --}}
                    @else
                        {{-- <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                            </div>
                            <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                <input type="file" name="other_attach" required class="check_max_size_file">
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div> --}}
                    @endif
                </div>
            </div>

            @if (!isset($messageRecordTransactions))

                <div class="form-group">
                    {!! HTML::decode(Form::label('select_user_id', '<span class="text-danger">*</span> ผู้ลงนามท้ายขอบข่าย', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        <select name="select_user_id" id="select_user_id" class="form-control" required>
                            <option value="" selected>- ผู้ลงนามท้ายขอบข่าย -</option>
                            @foreach ($signers as $id => $signer)
                                <option value="{{ $signer->id }}" data-position="{{$signer->position}}">{{ $signer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    {!! HTML::decode(Form::label('signer_1', '<span class="text-danger">*</span> เจ้าหน้าที่ผู้รับผิดชอบ', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        <select name="signer_1" id="signer_1" class="form-control" required>
                            <option value="" selected>- เจ้าหน้าที่ผู้รับผิดชอบ -</option>
                            @foreach ($signers as $signer)
                                <option value="{{ $signer->id }}" data-position="{{$signer->position}}">{{ $signer->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    {!! HTML::decode(Form::label('signer_2', '<span class="text-danger">*</span> ผู้ลงนาม (ผก.)', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        <select name="signer_2" id="signer_2" class="form-control" required>
                            <option value="" selected>- ผู้ลงนาม -</option>
                            @foreach ($signers as $signer)
                                <option value="{{ $signer->id }}" data-position="{{$signer->position}}">{{ $signer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    {!! HTML::decode(Form::label('signer_3', '<span class="text-danger">*</span> ผู้ลงนาม (ผอ. สก.)', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        <select name="signer_3" id="signer_3" class="form-control" required>
                            <option value="" selected>- ผู้ลงนาม -</option>
                            @foreach ($signers as $signer)
                                <option value="{{ $signer->id }}" data-position="{{$signer->position}}">{{ $signer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    {!! HTML::decode(Form::label('signer_4', '<span class="text-danger">*</span> ผู้ลงนาม (ลมอ. / ผอ. สก.)', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        <select name="signer_4" id="signer_4" class="form-control" required>
                            <option value="" selected>- ผู้ลงนาม -</option>
                            @foreach ($signers as $signer)
                            <option value="{{ $signer->id }}" data-position="{{$signer->position}}">{{ $signer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    
                        {!! HTML::decode(Form::label('cbAuditorTeam', '<span class="text-danger">*</span> คณะตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                        <div class="col-md-7">
                            <select name="cbAuditorTeam" id="cbAuditorTeam" class="form-control" required>
                                <option value="" selected>- คณะตรวจประเมิน -</option>
                                @foreach ($cbAuditorTeams as $cbAuditorTeam)
                                <option value="{{ $cbAuditorTeam->id }}" >{{ $cbAuditorTeam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                
    
                </div>

            @endif

            <div class="form-group">
                @if (!is_null($auditorcb->id) != null)
                    @if ($auditorcb->cb_auditor_team_id)
                        {!! HTML::decode(Form::label('cbAuditorTeam', '<span class="text-danger">*</span> คณะตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                        <div class="col-md-7">
                            <select name="cbAuditorTeam" id="cbAuditorTeam" class="form-control" required>
                                <option value="" selected>- คณะตรวจประเมิน -</option>
                                @foreach ($cbAuditorTeams as $cbAuditorTeam)
                                <option value="{{ $cbAuditorTeam->id }}" @if ($auditorcb->cb_auditor_team_id == $cbAuditorTeam->id )
                                    selected
                                @endif >{{ $cbAuditorTeam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <label for="cbAuditorTeam" class="col-md-5 control-label"><span class="text-danger">*</span> รายการ</label>
                            <div class="col-md-7 offset-md-2" > <!-- เพิ่ม offset-md-2 -->
                                <table class="table color-bordered-table primary-bordered-table"  style="margin-top: 15px">
                                    <thead>
                                        <tr>
                                            <td>ชื่อ-สกุล</td>
                                            <td>ตำแหน่ง</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($auditorcb->CertiCBAuditorsLists as $CertiCBAuditorsList)
                                            <tr>
                                                <td>{{$CertiCBAuditorsList->auditorInformation->title_th}}{{$CertiCBAuditorsList->auditorInformation->fname_th}} {{$CertiCBAuditorsList->auditorInformation->lname_th}}</td>
                                                <td>{{$CertiCBAuditorsList->auditorInformation->position}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif

            </div>
            
             
            

            <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> กำหนดการตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    @if (!is_null($auditorcb->FileAuditors2) &&  $auditorcb->FileAuditors2 != '')
                     <p  id="deleteFlieAttach">
                         {{-- @if($auditorcb->FileAuditors2->file !='' && HP::checkFileStorage($attach_path.$auditorcb->FileAuditors2->file)) --}}
                            <a href="{{url('certify/check/file_cb_client/'.$auditorcb->FileAuditors2->file.'/'.( !empty($auditorcb->FileAuditors2->file_client_name) ? $auditorcb->FileAuditors2->file_client_name :  basename($auditorcb->FileAuditors2->file)   ))}}" target="_blank">
                                {!! HP::FileExtension($auditorcb->FileAuditors2->file)  ?? '' !!}
                            </a>
                        {{-- @endif --}}
                        <button class="btn btn-danger btn-xs deleteFlie  {{ ($auditorcb->vehicle ==  1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}" type="button" onclick="deleteFlieAttach({{ $auditorcb->FileAuditors2->id}})">
                            <i class="icon-close"></i>
                        </button>   
                    </p>  
                      <div id="AddAttach"></div>                     
                   @else
                   
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="attach" required class="check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                     </div>
                  @endif
                </div>
            </div>


            <div class="form-group {{ $errors->has('auditor') ? 'has-error' : '' }}">
                <label for="auditor" class="col-md-5 control-label">
                    <span class="text-danger">*</span> บันทึกแต่งตั้ง
                </label>
                <div class="col-md-7">
                    {{-- <a href="{{route('certify.create_cb_message_record',['id' => $auditorcb->id])}}"
                        title="บันทึกแต่งตั้ง" class="btn btn-warning ">
                        <i class="fa fa-book" aria-hidden="true"> </i>
                    </a> --}}
                    @if ($auditorcb->messageRecordTransactions()->count() != 0)
                        @if ($auditorcb->message_record_status == 1)
                                <a href="{{route('certify.create_cb_message_record',['id' => $auditorcb->id])}}"
                                    title="บันทึกแต่งตั้ง" class="btn btn-warning ">
                                    <i class="fa fa-book" aria-hidden="true"> </i>
                                </a>
                            @elseif($auditorcb->message_record_status == 2)

                                <a href="{{route('view.create_cb_message_record',['id' => $auditorcb->id])}}"
                                    title="บันทึกแต่งตั้ง" class="btn btn-info ">
                                    <i class="fa fa-book" aria-hidden="true"> </i>
                                </a>
                        @endif
                        
                    @endif
                </div>
            </div>


            <div class="form-group {{ $errors->has('other_attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('other_attach', '<span class="text-danger">*</span> คณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">



                    

                    {{-- @if (!is_null($ba->file) &&  $ba->file != '')

                    @php
                        $allApproved = $messageRecordTransactions->every(function ($item) {
                            return $item->approval == 1;
                        });
                    @endphp

                    @if ($allApproved)
                        <a href="{{url('certify/check/file_client/'.$ba->file.'/'.( !empty($ba->file_client_name) ? $ba->file_client_name : basename($ba->file) ))}}" title="{{ !empty($ba->file_client_name) ? $ba->file_client_name :  basename($ba->file) }}" target="_blank">
                            {!! HP::FileExtension($ba->file)  ?? '' !!}
                        </a>
                    @else  
                        -  
                    @endif
                    @else
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="other_attach" required class="check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                    @endif --}}
{{-- {{$messageRecordTransactions}} --}}
                    @if (isset($messageRecordTransactions))
                        @if ($messageRecordTransactions->count() != 0)
                        <table class="table color-bordered-table primary-bordered-table" style="margin-top: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 35%">ชื่อ-สกุล</th>
                                    <th style="width: 45%">ตำแหน่ง</th>
                                    <th style="width: 20%">สถานะ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messageRecordTransactions as $key => $item)
                                        <tr>
                                        
                                            <td>{{ $item->signer_name }}</td>
                                            <td>{{ $item->signer_position }}</td>
                                            <td>
                                                <span class="badge {{ $item->approval == 1 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->approval == 1 ? 'ลงนามแล้ว' : 'รอดำเนินการ' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                        @endif
                    @endif

                   
                </div>
            </div>

        </div>
    </div>
</div>



<div class="row form-group" id="table_cost">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>ค่าใช้จ่าย</h4></legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-8"> </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-success btn-sm  {{ ($auditorcb->vehicle == 1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
                        </div>
                        <div class="col-sm-12 m-t-15">
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="38%">รายละเอียด</th>
                                        <th class="text-center" width="20%">จำนวนเงิน</th>
                                        <th class="text-center" width="10%">จำนวนวัน</th>
                                        <th class="text-center" width="20%">รวม (บาท)</th>
                                        <th class="text-center" width="5%">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">
                                @if(count($auditorcb->CertiCBAuditorsCosts) > 0 )
                                    @foreach($auditorcb->CertiCBAuditorsCosts as $item)
                                    <tr>
                                        <td  class="text-center">
                                            1
                                        </td>
                                        <td>
                                            {!! Form::select('detail[detail][]',
                                            App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                            $item->detail ?? null, 
                                            ['class' => 'form-control select2 detail', 
                                            'required'=>true,
                                            'placeholder'=>'- เลือกรายละเอียดประมาณค่าใช้จ่าย -']); !!}
                                        </td>
                                        <td>
                                            {!! Form::text('detail[amount][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                                        </td>
                                        <td>
                                            {!! Form::text('detail[amount_date][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                                        </td>
                                        <td>
                                            {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                                        </td>
                                        <td  >
                                            <button type="button" class="btn btn-danger btn-xs remove-row {{ ($auditorcb->vehicle == 1 || $auditorcb->status_cancel == 1) ? 'hide' : ''}}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                     @endforeach  
                                @endif
                                </tbody>
                                <footer>
                                    <tr>
                                        <td colspan="4" class="text-right">รวม</td>
                                        <td>
                                            {!! Form::text('costs_total',
                                                null,
                                                ['class' => 'form-control text-right costs_total',
                                                    'id'=>'costs_total',
                                                    'disabled'=>true
                                                ])
                                            !!}
                                        </td>
                                        <td>
                                             บาท
                                        </td>
                                    </tr>
                                </footer>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($auditorcb->CertiCbHistorys) > 0 && !is_null($auditorcb->status)) 
@include ('certify/cb.auditor_cb.log')
@endif


 @if($auditorcb->vehicle != 1 && $auditorcb->status_cancel != 1) 
 <input type="hidden" name="previousUrl" id="previousUrl" value="{{ $previousUrl ?? null}}">
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
        <label for="vehicle1">ขอความเห็นการแต่งตั้ง</label>
        <br>
        <button class="btn btn-primary" type="submit" id="form-save"  onclick="submit_form();return false;">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>

        <a class="btn btn-default" href="{{url("$previousUrl")}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    </div>
</div>

 @else 

<div class="clearfix"></div>
   <a  href="{{ url("$previousUrl") }}"  class="btn btn-default btn-lg btn-block">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
@endif 

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
  <script>
    function submit_form() {
        
        var selectUserId = $('#select_user_id').val();
            var signer1 = $('#signer_1').val();
            var signer2 = $('#signer_2').val();
            var signer3 = $('#signer_3').val();
            var signer4 = $('#signer_4').val();

            if (selectUserId === "") {
                alert('กรุณาเลือกผู้ลงนามท้ายขอบข่าย');
                return
            }

            if (![signer1, signer2, signer3, signer4].every(function(signer) { return signer !== ""; })) {
                alert('กรุณาเลือกเจ้าหน้าที่ผู้ลงนาม');
                return;
            }

            const signatures = [
            {
                id: 'Signature1',
                enable: false,
                show_name: false,
                show_position: false,
                signer_name: "",
                signer_id: "",
                signer_position: "ตำแหน่ง ผู้จัดการทั่วไป",    
                line_space: 20
            },
            {
                id: 'Signature2',
                enable: false,
                show_name: true,
                show_position: false,
                signer_name: "",
                signer_id: "",
                signer_position: "ตำแหน่ง ปฏิบัติราชการแทน",
                line_space: 5
            },
            {
                id: 'Signature3',
                enable: false,
                show_name: true,
                show_position: true,
                signer_name: "",
                signer_id: "",
                signer_position: "ตำแหน่ง นักเรียนโอลิมปิกเคมี",
                line_space: 20
            },
            {
                id: 'Signature4',
                enable: false,
                show_name: true,
                show_position: true,
                signer_name: "",
                signer_id: "",
                signer_position: "ตำแหน่ง กำลังจะสอบ ม.1",
                line_space: 20
            }
        ];

            // ดึงค่าที่ถูกเลือกและอัปเดต signatures
            for (let i = 1; i <= 4; i++) {
                const selectElement = $(`#signer_${i}`);
                const selectedId = selectElement.val();
                const selectedName = selectElement.find('option:selected').text();
                const selectedPosition = selectElement.find('option:selected').data('position');

                // อัปเดตใน signatures
                signatures[i - 1].signer_id = selectedId || "";
                signatures[i - 1].signer_name = selectedName || "";
                signatures[i - 1].signer_position = selectedPosition || "";
            }

            console.log("Updated signatures:", signatures);

        $('#signaturesJson').val(JSON.stringify(signatures));
         Swal.fire({
              title: 'ยืนยันทำรายการ !',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'บันทึก',
              cancelButtonText: 'ยกเลิก'
              }).then((result) => {
                  if (result.value) {
                    $('#form_auditor').submit();
                  }
              })
      
      }
      
      jQuery(document).ready(function() {
        check_max_size_file();
         $('#form_auditor').parsley().on('field:validated', function() {
                  var ok = $('.parsley-error').length === 0;
                  $('.bs-callout-info').toggleClass('hidden', !ok);
                  $('.bs-callout-warning').toggleClass('hidden', ok);
              })  .on('form:submit', function() {
                      // Text
                      $.LoadingOverlay("show", {
                      image       : "",
                      text  : "กำลังบันทึก กรุณารอสักครู่..."
                      });
                  return true; // Don't submit form for this demo
              });
      });
</script>
  <script type="text/javascript">
    jQuery(document).ready(function() {
         $('.check-readonly').prop('disabled', true); 
        $('.check-readonly').parent().removeClass('disabled');
        $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
  });               
</script>
    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script type="text/javascript">
 
        $(document).ready(function () {

            ResetTableNumber1();
            AuditorStatus();
            DataListDisabled();
        //เพิ่มแถว
        $('#plus-row').click(function(event) {
                   var data = $('.status').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data == 0){
                        Swal.fire('หมดรายการรายสถานะผู้ตรวจประเมิน !!')
                        return false;
                    }
          //Clone
          $('#table-body').children('tr:first()').clone().appendTo('#table-body');
          //Clear value
            var row = $('#table-body').children('tr:last()');
            row.find('.myInput').val('');
            row.find('select.select2').val('');
            row.find('select.select2').prev().remove();
            row.find('select.select2').removeAttr('style');
            row.find('select.select2').select2();
            row.find('.exampleModal').prop('disabled',true);

            row.find('.td-users').remove();
            row.find('.div-users').html('<input type="text" name="filter_search" class="form-control item">');

            row.find('.td-departments').remove();
            row.find('.div-departments').html('<input type="text" name="filter_search" class="form-control item" readonly>');
            
            row.find('.tbody-auditor').html('');
            row.find('input[type=checkbox]').prop('checked',false);

            ResetTableNumber1(); 
            AuditorStatus();
            DataListDisabled();
            check_max_size_file();
            row.find('.btn-user-select').on('click', function () {
                    modalHiding($(this).closest('.modal'));
             });
             row.find('.select-all').on('change', function () {
                    checkedAll($(this));
             });

          });
           //ลบแถว
           $('body').on('click', '.repeater-remove', function(){
              $(this).parent().parent().remove();
              ResetTableNumber1();
              DataListDisabled();
            });

            function ResetTableNumber1(){
                var rows = $('#table-body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.repeater-remove').hide():$('.repeater-remove').show();
                  rows.each(function(index, el) {
                      $(el).find('button.exampleModal').attr('data-target','#exampleModal'+index);
                      $(el).find('div.exampleModal').prop('id','exampleModal'+index);
                  
                });
           }

           function AuditorStatus(){

              $('.status').change(function(){
                      $('.myInput').val('');
                  let  exampleModal =  $(this).parent().parent().parent().parent().find('.exampleModal');
                  let  auditor =   $(this).parent().parent().parent().parent().find('.tbody-auditor');
                  let  row =   $(this).parent().parent().parent().parent();
                       row.find('.td-users').remove();
                       row.find('.div-users').html('<input type="text" name="filter_search" class="form-control item">');
                       row.find('.td-departments').remove();
                       row.find('.div-departments').html('<input type="text" name="filter_search" class="form-control item" readonly>');
                  let html = [];
                    if($(this).val() != ''){
                        let status = $(this).val();
                        auditor.html('');  
                        exampleModal.prop('disabled',false);
                     
                     
                        $.ajax({
                           url: "{!! url('certify/auditor/status/ib_and_cb') !!}" + "/" +  $(this).val()  + "/1" 
                        }).done(function( object ) { 
               
                            if(object.expertise != '-'){
                                $.each(object.expertise, function( index, item ) {
                                    html += '<tr>';

                                    html += '<td>';
                                        html +=  (index +1);
                                    html += '</td>';
                                    html += '<td class="text-center">';
                                        html +=   '<input type="checkbox" id="master"   value="'+item.id+'"   data-status="'+status+'"   data-value="'+item.NameTh+'"  data-department="'+item.department+'" >';
                                    html += '</td>';

                                    html += '<td>';
                                        html +=  item.NameTh;
                                    html += '</td>';

                                    html += '<td>';
                                        html +=  item.department;
                                    html += '</td>';

                                    html += '<td>';
                                        html +=  item.position;
                                    html += '</td>';

                                    html += '<td>';
                                        html +=  item.branchable;
                                    html += '</td>';

                                    html += '</tr>';
                                });  
                                auditor.append(html);
                            }
                            
                         });
                         filter_tbody_auditor();
                    
                    }else{
                        auditor.html('');  
                        exampleModal.prop('disabled',true);
                    }
             });    
           }

           $('.btn-user-select').on('click', function () {
            let auditor= $(this).parent().parent().parent().parent().find('.tbody-auditor');
               modalHiding($(this).closest('.modal'));
            });

            $('.select-all').change(function () {
                checkedAll($(this));
            });

            var tempCheckboxes = [];
        function modalHiding(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('input[type=checkbox]');
            let Users = $(that).closest('.repeater-item').find('.td-users');

            let Departments = $(that).closest('.repeater-item').find('.td-departments');
            let tdUsers = $(that).closest('.repeater-item').find('.div-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.div-departments');
                tdUsers.children().remove();
                tdDepartments.children().remove();
            checkboxes.each(function () {
                if ($(this).val() !== 'on' && $(this).is(':checked')) {
                    let val = $(this).data('value');
                    let depart = $(this).data('department');
                    let user_id = $(this).val();
                    let status = $(this).data('status');
                    let input = $('<input type="hidden" name="list[user_id]['+status+'][]" value="'+user_id+'"><input type="text" class="form-control item" name="list[temp_users]['+status+'][]" value="'+val+'" readonly>');
                    input.appendTo(tdUsers);
                    let inputDepart = $('<input type="text" class="form-control item" name="list[temp_departments]['+status+'][]" value="'+depart+'" readonly>');
                    inputDepart.appendTo(tdDepartments);
                    tempCheckboxes.push($(this));

                    Users.children().remove();
                    Departments.children().remove();
                }
            });
            $(that).modal('hide');
        }
        function checkedAll(that) {
            let checkboxes = $(that).closest('.modal').find('.tbody-auditor').find('input[type=checkbox]');
            checkboxes.each(function() {
                $(this).prop('checked', $(that).is(':checked'));
            });
        }
        function DataListDisabled(){
                $('.status').children('option').prop('disabled',false);
                $('.status').each(function(index , item){
                    var data_list = $(item).val();
                    $('.status').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
         }

                TotalValue();
                ResetTableNumber();
                data_list_disabled();
                cost_rate();
                let Costs = '{{ count($auditorcb->CertiCBAuditorsCosts) > 0 ? 1 : 0  }}';
                if(Costs == 1){
                    $('#table_cost').show();
                }else{
                    $('#table_cost').hide();
                }

                 $('#app_certi_cb_id').change(function(){
                    let html = [];
                  $('#table_body').children().remove();
                    if($(this).val()!=""){
                        $('#table_cost').show();
                        $.ajax({
                           url: "{!! url('certify/auditor-cb/app_no') !!}" + "/" +  $(this).val()
                       }).done(function( object ) { 
                  
                           $('#no').val(object.name);

                           if(object.cost_item  != '-'){
                              $.each(object.cost_item, function( index, item ) {
                                html += '<tr>';
                                html += '<td>';
                                    html +=  (index +1);
                                html += '</td>';
                                html += '<td>';
 
                                    html +=  ' <select name="detail[detail][]" class="form-control select2 detail">' ; 
                                        html+=  '<option value="">- เลือกรายละเอียดประมาณค่าใช้จ่าย -</option>';
                                        $.each(object.cost_details, function( index1, item1 ) {
                                        var selected = (index1 == item.detail )?'selected="selected"':'';
                                         html+=  '<option value="'+index1+'"  '+selected+'>'+ item1 +'</option>';
                                        });  
                                    html +=  '</select>' ; 
                                html += '</td>';
                                html += '<td>';
                                    html +=   '<input type="text" name="detail[amount][]" class="form-control input_number cost_rate  text-right" required value="'+ addCommas(item.amount, 2)   +'"> ';
                                html += '</td>';
                                html += '<td>';
                                    html +=   '<input type="text" name="detail[amount_date][]" class="form-control amount_date  text-right" required value="'+ item.amount_date +'"> '; 
                                html += '</td>';
                                html += '<td>';
                                    html +=  '<input type="text" name="number[]" class="form-control number  text-right" readonly  value="'+ addCommas((item.amount * item.amount_date), 2)  +'"> '; 
                                html += '</td>';
                                html += '<td>';
                                     html +=  ' <button type="button" class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button>';
                                html += '</td>';
                                html += '</tr>';
                               
                               });  
        
                               $('#table_body').append(html);
                               TotalValue();
                               cost_rate();
                               IsNumber();
                               IsInputNumber();
                               var row = $('#table_body').children('tr');
                                   row.find('select.select2').prev().remove();
                                   row.find('select.select2').removeAttr('style');
                                   row.find('select.select2').select2();
                               data_list_disabled();
                           }
                       }); 

                    }else{
                            $('#no').val('');

                            $('#table_cost').hide();
                            $('#no').val('');
                            $('#app_id').val('');
                    }
                });

                var certi_cb_change = '{{  !empty($auditorcb->certi_cb_change)  ? 1 : null  }}';
                if(certi_cb_change == 1){
                    $('#app_certi_cb_id').change();
                }


            //เพิ่มวันที่ตรวจประเมิน
            $("#add_date").click(function() {
                $('.dev_form_date:first').clone().insertAfter(".dev_form_date:last");
                var row = $(".dev_form_date:last");
                $('.dev_form_date:last > label').text(''); 
                row.find('input.date').val('');
                row.find('button.add_date').remove();
                row.find('div.add_button_delete').html('<button type="button" class="btn btn-danger btn-sm pull-right date_remove"><i class="fa fa-close" aria-hidden="true"></i> ลบ </button>');
               //ช่วงวันที่
                $('.date-range').datepicker({
                toggleActive: true, 
                language:'th-th',
                format: 'dd/mm/yyyy',
                });
            });
            //ช่วงวันที่
           $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
            //ลบตำแหน่ง
            $('body').on('click', '.date_remove', function() {
                    $(this).parent().parent().parent().remove();
            });

 
            //เพิ่มแถว
            $('#addCostInput').click(function(event) {
                var data_list = $('.detail').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data_list == 0){
                        Swal.fire('หมดรายการรายละเอียดประมาณค่าใช้จ่าย !!')
                        return false;
                }
              //Clone
                $('#table_body').children('tr:first()').clone().appendTo('#table_body');
                //Clear value
                    var row = $('#table_body').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"]').val('');
                ResetTableNumber();
                IsInputNumber();
                IsNumber();
                cost_rate();
                data_list_disabled();
                check_max_size_file();
            });


           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
            });

         //ลบตำแหน่ง
         $('body').on('click', '.date_edit_remove', function() {
                    $(this).parent().parent().remove();
            });

        function ResetTableNumber(){
                var rows = $('#table_body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                });
         }
         function   filter_tbody_auditor() {
               $(".myInput").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            var row =   $(this).parent().parent().parent().parent();
                            $(row).find(".tbody-auditor tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                });   
        }

        function  TotalValue() {
            var rows = $('#table_body').children(); //แถวทั้งหมด
            var total_all = 0.00;
            rows.each(function(index, el) {
                if($(el).children().find("input.number").val() != ''){
                    var number = parseFloat(RemoveCommas($(el).children().find("input.number").val()));
                    total_all  += number;
                }
            });
            $('#costs_total').val(addCommas(total_all.toFixed(2), 2));
           }

           function RemoveCommas(str) {
                   var res = str.replace(/[^\d\.\-\ ]/g, '');
                   return   res;
             }

           function  addCommas(nStr, decimal){
                    var tmp='';
                    var zero = '0';

                    nStr += '';
                    x = nStr.split('.');

                    if((x.length-1) >= 1){//ถ้ามีทศนิยม
                        if(x[1].length > decimal){//ถ้าหากหลักของทศนิยมเกินที่กำหนดไว้ ตัดให้เหลือเท่าที่กำหนดไว้
                            x[1] = x[1].substring(0, decimal);
                        }else if(x[1].length < decimal){//ถ้าหากหลักของทศนิยมน้อยกว่าที่กำหนดไว้ เพิ่ม 0
                            x[1] = x[1] + zero.repeat(decimal-x[1].length);
                        }
                        tmp = '.'+x[1];
                    }else{//ถ้าไม่มีทศนิยม
                        if(parseInt(decimal)>0){//ถ้ามีการกำหนดให้มี ทศนิยม
                                tmp = '.'+zero.repeat(decimal);
                            }
                    }
                    x1 = x[0];
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1+tmp;
           }

           function IsNumber() {
              // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
                    $(".amount_date").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                    }); 
          }

 
          function IsInputNumber() {
                 // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                 String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                   }; 
                    
                   var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน 
                    var s_inum=new String(inum); 
                    var num2=s_inum.split("."); 
                    var n_inum=""; 
                    if(num2[0]!=undefined){
                   var l_inum=num2[0].length; 
                   for(i=0;i<l_inum;i++){ 
                    if(parseInt(l_inum-i)%3==0){ 
                   if(i==0){ 
                    n_inum+=s_inum.charAt(i); 
                   }else{ 
                    n_inum+=","+s_inum.charAt(i); 
                   } 
                    }else{ 
                   n_inum+=s_inum.charAt(i); 
                    } 
                   } 
                    }else{
                   n_inum=inum;
                    }
                    if(num2[1]!=undefined){ 
                   n_inum+="."+num2[1]; 
                    }
                    return n_inum; 
                   } 
                   // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
                   $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   }); 
                   
                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
                   $(".input_number").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                               if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                           thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                            }else{ // ถ้าไม่มีคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                            } 
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                   });
          }

           function cost_rate() {
             $('.cost_rate,.amount_date').keyup(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();
           
                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else if(cost_rate == '' || amount_date == ''){
                      row.find('.number').val('');
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });


         }

         function data_list_disabled(){
                $('.detail').children('option').prop('disabled',false);
                $('.detail').each(function(index , item){
                    var data_list = $(item).val();
                    $('.detail').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }

        });

   function  deleteFlieOtherAttach(id,$attachs){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="other_attach" required class="check_max_size_file">';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
        Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบไฟล์แนบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                       $.ajax({
                            url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#deleteFlieOtherAttach').remove();
                               $("#AddOtherAttach").append(html);
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
                check_max_size_file();
         }

         function  deleteFlieAttach(id,$attachs){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="attach" required class="check_max_size_file">';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
        Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบไฟล์แนบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                       $.ajax({
                            url: "{!! url('/certify/check_certificate-cb/delete_file') !!}"  + "/" + id
                        }).done(function( object ) {
                            if(object == 'true'){
                                $('#deleteFlieAttach').remove();
                               $("#AddAttach").append(html);
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
                check_max_size_file();
         }
    </script>
@endpush
 
