{{-- work on BoardAuditorController --}}
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        @font-face {
            font-family: 'ThSarabunNew';
            src: url('{{ asset('fonts/THSarabunNew.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        .img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        #pdfCanvas {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }
        .draggable-signature {
            position: absolute;
            border: 2px dashed #ffc107;
            border-radius: 5px;
            padding: 8px;
            cursor: move;
            color: #333;
            font-weight: bold;

        }
        .signature-info {
            width: 100px;              /* กำหนดความกว้าง */
            height: 24px;
            font-size: 20px;          
            font-family: 'ThSarabunNew', sans-serif;  
            text-align: center;        /* จัดข้อความให้อยู่กลาง */
            white-space: nowrap;       /* ป้องกันการตัดคำ */
            overflow: hidden;          /* ซ่อนข้อความที่ยาวเกิน */
            text-overflow: ellipsis;   /* ใช้ "..." แทนข้อความที่ยาวเกิน */
            font-weight: 600
        }
        #signatureDropdownMenu {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
            z-index: 1050; /* ทำให้ dropdown อยู่ด้านบน */
            margin-top: 5px; /* เพิ่มระยะห่างจากปุ่ม */
        }

        .pdfModalFooterWrapper {
            text-align: right;
        }

        .pdfModalFooterWrapper .btn,
        .pdfModalFooterWrapper .dropup {
            display: inline-block; /* ทำให้ปุ่มอยู่ในบรรทัดเดียว */
            margin-left: 5px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }
    </style>
@endpush

<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">ลงนาม</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pdfCanvasWrapper">
                <canvas id="pdfCanvas"></canvas>
            </div>
            <div class="modal-footer pdfModalFooterWrapper" style="text-align: right">
                <button type="button" class="btn btn-primary" onclick="prevPage()">ก่อนหน้า</button>
                <button type="button" class="btn btn-primary" onclick="nextPage()">ถัดไป</button>
                
                <!-- Dropdown for selecting signature -->
                {{-- <div class="dropup">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="signatureDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        เลือกผู้ลงนาม
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="signatureDropdown" id="signatureDropdownMenu">

                    </ul>
                </div> --}}

                {{-- <button type="button" class="btn btn-success" onclick="saveSignatures()">บันทึก</button> --}}
                <button type="button" class="btn btn-info" onclick="exportPdfWithSignatures(true)">ส่งออก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="col-md-8">
            <input type="hidden" name="app_id" value="{{ $app ? $app->id : '' }}" id="app_id">
            <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('certi_no', '<span class="text-danger">*</span>  เลขคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('certi_no', $ba->certi_no ?? '', ['class' => 'form-control', 'placeholder'=>'', 'required' => true,'readonly'=>true]); !!}
                </div>
            </div>
 
            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('no', '<span class="text-danger">*</span>  ชื่อผู้ยื่นคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('no',  $ba->no ?? null, ['class' => 'form-control', 'maxlength' => '255', 'required' => true]); !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span>   ชื่อคณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('auditor', $ba->auditor ?? null, ['class' => 'form-control',  'maxlength' => '255', 'required' => true]); !!}
                </div>
            </div>

            @if(count($ba->DataBoardAuditorDate) > 0)
                @foreach ($ba->DataBoardAuditorDate as $key => $itme)
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
                        <button type="button" class="btn btn-success btn-sm pull-right div_hide add_date" id="add_date">
                            <i class="icon-plus" aria-hidden="true"></i>
                            เพิ่ม
                        </button>
                        @else
                        <button type="button" class="btn btn-danger btn-sm pull-right  div_hide date_edit_remove"><i class="fa fa-close" aria-hidden="true"></i> ลบ </button>
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
                    @if ($messageRecordTransaction->file_path !== null)
                        <a href="{{url('certify/check/file_client/'.$messageRecordTransaction->file_path.'/'.( !empty($ba->file_client_name) ? $ba->file_client_name : basename($messageRecordTransaction->file_path) ))}}" title="{{ !empty($ba->file_client_name) ? $ba->file_client_name :  basename($messageRecordTransaction->file_path) }}" target="_blank">
                            {!! HP::FileExtension($messageRecordTransaction->file_path)  ?? '' !!}
                        </a>

                        <a  class="mb-1 mt-1 mr-1 btn btn-xs btn-warning"  
                        onclick="openPdfModal()">
                            <i class="fa fa-file-text"></i>
                       </a>  
                    @endif
                    {{-- @if (!is_null($ba->file) &&  $ba->file != '')
                            <a href="{{url('certify/check/file_client/'.$ba->file.'/'.( !empty($ba->file_client_name) ? $ba->file_client_name : basename($ba->file) ))}}" title="{{ !empty($ba->file_client_name) ? $ba->file_client_name :  basename($ba->file) }}" target="_blank">
                                {!! HP::FileExtension($ba->file)  ?? '' !!}
                            </a>
                            @if ($ba->status != 1)  
                            <a href="{{url('certify/auditor/delete-file/'. $ba->id )}}" class="mb-1 mt-1 mr-1 btn btn-xs btn-danger div_hide"  
                                 onclick="return confirm('ต้องการลบไฟล์นี้ใช่หรือไม่ ?')">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>                       
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
                    <table class="table color-bordered-table primary-bordered-table" style="margin-top: 10px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 8%">#</th> --}}
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
                </div>
            </div>

            <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> กำหนดการตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    @if (!is_null($ba->attach) &&  $ba->attach != '')
                            <a href="{{url('certify/check/file_client/'.$ba->attach.'/'.( !empty($ba->attach_client_name) ? $ba->attach_client_name : basename($ba->attach)  ))}}" title="{{ !empty($ba->attach_client_name) ? $ba->attach_client_name :  basename($ba->attach) }}" target="_blank">
                                {!! HP::FileExtension($ba->attach)  ?? '' !!}
                               {{-- {{ basename($ba->attach) }} --}}
                            </a>
                            @if ($ba->status != 1)  
                            <a href="{{url('certify/auditor/delete-attach/'. $ba->id )}}" class="mb-1 mt-1 mr-1 btn btn-xs btn-danger div_hide"  
                                 onclick="return confirm('ต้องการลบไฟล์นี้ใช่หรือไม่ ?')">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>                       
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
                            <input type="file" name="attach" required class="check_max_size_file">
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
</div>
<div class="col-md-12 repeater">
    <button type="button" class="btn btn-success btn-sm pull-right clearfix div_hide" id="plus-row">
        <i class="icon-plus" aria-hidden="true"></i>
        เพิ่ม
    </button>
    <div class="clearfix"></div>
    <br/>

    <table class="table color-bordered-table primary-bordered-table">
        <thead>
        <tr>
            {{-- <th class="text-center">ลำดับ</th>  --}}
            <th class="text-center">สถานะผู้ตรวจประเมิน</th>
            <th class="text-center">ชื่อผู้ตรวจประเมิน</th>
            <th class="text-center"></th>
            <th class="text-center">หน่วยงาน</th>
            <th class="text-center "> ลบรายการ</th>
        </tr>
        </thead>
        <tbody id="table-body">
        <tr class="repeater-item">
            <td class="text-center text-top">
                <div class="form-group {{ $errors->has('taxid') ? 'has-error' : ''}}">
                    <div class="col-md-9">
                        {!! Form::select('status', $status_auditor,
                          null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกสถานะผู้ตรวจประเมิน-', 'data-name'=>'status', 'required'=>true]); !!}
                    </div>
                </div>
            </td>
            {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
            <td class="align-right text-top td-users">
                {!! Form::text('filter_search', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'filter_search','required' => true]); !!}
            </td>
            {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
            <td class="text-top">
                <button type="button" class="btn btn-primary repeater-modal-open exampleModal" data-toggle="modal" data-target="#exampleModal"
                        data-whatever="@mdo"> select
                </button>
                <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                <div class="modal fade repeater-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="exampleModalLabel1">ผู้ตรวจประเมิน</h4>
                            </div>
                            <div class="modal-body">
                                {{-- ------------------------------------------------------------------------------------------------- --}}
                                <div class="white-box">
                                    <div class="row">
                                        <div class="form-group {{ $errors->has('myInput') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('myInput', 'ค้นหา', ['class' => 'col-md-2 control-label'])) !!}
                                            <div class="col-md-7">
                                                <input class="form-control myInput"  type="text" placeholder="ชื่อผู้ตรวจประเมิน,หน่วยงาน,ตำแหน่ง,สาขา">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 form-group ">
                                            <div class="table-responsive">
                                                <table class="table table-bordered color-table primary-table" id="myTable" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th  class="text-center" width="2%">#</th>
                                                            <th  class="text-center" width="2%">
                                                                <input type="checkbox" class="select-all">
                                                            </th>
                                                            <th class="text-center" width="10%">ชื่อผู้ตรวจประเมิน</th>
                                                            <th class="text-center" width="10%">หน่วยงาน</th>
                                                            <th class="text-center" width="10%">ตำแหน่ง</th>
                                                            <th class="text-center" width="10%">สาขา</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-auditor"></tbody>
                                                </table>
                                            </div>
                                      </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <div class="pull-right">
                                        {!! Form::button('<i class="icon-check"></i> เลือก', ['type' => 'button', 'class' => 'btn btn-primary btn-user-select']) !!}

                                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                            {!! __('ยกเลิก') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="align-top text-top td-departments">
                {!! Form::text('department', null, ('' == 'required') ? ['class' => 'form-control item', 'required' => 'required'] : ['class' => 'form-control item','readonly'=>'readonly','data-name'=>'department']) !!}
            </td>
            <td align="center" class="text-top">
                <button type="button" class="btn btn-danger btn-xs repeater-remove">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
        @forelse($ba->groups as $group)
            <tr class="repeater-item">
                <td class="text-center text-top">
                    <div class="form-group {{ $errors->has('taxid') ? 'has-error' : ''}}">
                        <div class="col-md-9">
                            {!! Form::select('status', $status_auditor,
                              $group->status_auditor_id, ['class' => 'form-control item status', 'placeholder'=>'-เลือกสถานะผู้ตรวจประเมิน-', 'data-name'=>'status', 'required'=>true]); !!}
                        </div>
                    </div>
                </td>
                {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
                <td class="align-right text-top td-users">
                    @php
                        $strUsers = "";
                    @endphp
                    @forelse ($group->auditors as $ai)
                        @php
                            $auditor = $ai->auditor;
                            $strUsers .= (!$loop->first ? ";" : '') . $auditor->id;
                        @endphp
                        @if ($loop->last)
                            {!! Form::hidden('users', $strUsers, ['class' => 'item', 'data-name'=>'users']); !!}
                        @endif
                        {!! Form::text('temp_users[]', $auditor->name_th, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'temp_users[]','required' => true, 'readonly']); !!}
                    @empty
                        {!! Form::text('temp_users[]', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'temp_users[]','required' => true, 'readonly']); !!}
                    @endforelse
                </td>
                {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
                <td class="text-top">
                    <button type="button" class="btn btn-primary repeater-modal-open exampleModal" data-toggle="modal" data-target="#exampleModal"
                            data-whatever="@mdo"> select
                    </button>
                    <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                    <div class="modal fade repeater-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="exampleModalLabel1">ผู้ตรวจประเมิน</h4>
                                </div>
                                <div class="modal-body">
                                    {{-- ------------------------------------------------------------------------------------------------- --}}
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="table-responsive">
                                                {{-- @php
                                                    $con = new App\Http\Controllers\BoardAuditorController();
                                                    $auditors = $con->getAuditors($group->sa);
                                                    $pluck = $group->auditors()->pluck('auditor_id');
                                                @endphp --}}
                                                <table class="table table-bordered color-table primary-table" id="myTable" width="100%">
                                                    <thead>

                                                        <tr>
                                                            <th  class="text-center" width="2%">#</th>
                                                            <th  class="text-center" width="2%">
                                                                <input type="checkbox" class="select-all">
                                                            </th>
                                                            <th class="text-center" width="10%">ชื่อผู้ตรวจประเมิน</th>
                                                            <th class="text-center" width="10%">หน่วยงาน</th>
                                                            <th class="text-center" width="10%">ตำแหน่ง</th>
                                                            <th class="text-center" width="10%">สาขา</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody class="tbody-auditor">
                                                    {{-- @foreach ($auditors as $auditor)
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1">{{ $loop->iteration }}</td>
                                                            <td>
                                                                <input type="checkbox" id="master" value="{{ $auditor->id }}"
                                                                       {{ in_array($auditor->id, $pluck->toArray()) ? 'checked' : '' }}
                                                                       data-value="{{ $auditor->name_th }}" data-department="{{ $auditor->department->title }}">
                                                            </td>
                                                            <td>{{ $auditor->name_th }}</td>
                                                            <td>{{ $auditor->department->title }}</td>
                                                            <td>{{ $auditor->position }}</td>
                                                            <td align ="center">
                                                                <button type="button" class="btn btn-primary" >
                                                                    <i class="glyphicon glyphicon-info-sign" aria-hidden="true" ></i>
                                                                </button> </i>
                                                            </td>
                                                        </tr>
                                                    @endforeach --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <div class="pull-right">
                                            {!! Form::button('<i class="icon-check"></i> เลือก', ['type' => 'button', 'class' => 'btn btn-primary btn-user-select']) !!}

                                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                {!! __('ยกเลิก') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="align-top text-top td-departments">
                    @forelse ($group->auditors as $ai)
                        @php
                            $auditor = $ai->auditor;
                        @endphp
                        {!! Form::text('temp_departments[]', $auditor->department->title, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'temp_departments[]','required' => true, 'readonly']); !!}
                    @empty
                        {!! Form::text('temp_departments[]', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'temp_departments[]','required' => true, 'readonly']); !!}
                    @endforelse
                </td>
                <td align="center" class="text-top">
                    <button type="button" class="btn btn-danger btn-xs repeater-remove">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr class="repeater-item">
                <td class="text-center text-top">
                    <div class="form-group {{ $errors->has('taxid') ? 'has-error' : ''}}">
                        <div class="col-md-9">
                            {!! Form::select('status', $status_auditor,
                              null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกสถานะผู้ตรวจประเมิน-', 'data-name'=>'status', 'required'=>true]); !!}
                        </div>
                    </div>
                </td>
                {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
                <td class="align-right text-top td-users">
                    {!! Form::text('filter_search', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'filter_search','required' => true]); !!}
                </td>
                {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
                <td class="text-top">
                    <button type="button" class="btn btn-primary repeater-modal-open exampleModal" data-toggle="modal" data-target="#exampleModal"
                            data-whatever="@mdo"> select
                    </button>
                    <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                    <div class="modal fade repeater-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="exampleModalLabel1">ผู้ตรวจประเมิน</h4>
                                </div>
                                <div class="modal-body">
                                    {{-- ------------------------------------------------------------------------------------------------- --}}
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="form-group {{ $errors->has('myInput') ? 'has-error' : ''}}">
                                                {!! HTML::decode(Form::label('myInput', 'ค้นหา', ['class' => 'col-md-2 control-label'])) !!}
                                                <div class="col-md-7">
                                                    <input class="form-control myInput"  type="text" placeholder="ชื่อผู้ตรวจประเมิน,หน่วยงาน,ตำแหน่ง,สาขา">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 form-group ">
                                            <div class="table-responsive">
                                                <table class="table table-bordered color-table primary-table" id="myTable" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <tr>
                                                            <th  class="text-center" width="2%">#</th>
                                                            <th  class="text-center" width="2%">
                                                                <input type="checkbox" class="select-all">
                                                            </th>
                                                            <th class="text-center" width="10%">ชื่อผู้ตรวจประเมิน</th>
                                                            <th class="text-center" width="10%">หน่วยงาน</th>
                                                            <th class="text-center" width="10%">ตำแหน่ง</th>
                                                            <th class="text-center" width="10%">สาขา</th>
                                                        </tr>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="tbody-auditor"></tbody>
                                                </table>
                                            </div>
                                           </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <div class="pull-right">
                                            {!! Form::button('<i class="icon-check"></i> เลือก', ['type' => 'button', 'class' => 'btn btn-primary btn-user-select']) !!}

                                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                {!! __('ยกเลิก') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="align-top text-top td-departments">
                    {!! Form::text('department', null, ('' == 'required') ? ['class' => 'form-control item', 'required' => 'required'] : ['class' => 'form-control item','readonly'=>'readonly','data-name'=>'department']) !!}
                </td>
                <td align="center" class="text-top">
                    <button type="button" class="btn btn-danger btn-xs repeater-remove">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>


<div class="row form-group" id="table_cost">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>ประมาณค่าใช้จ่าย</h4></legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-8"> </div>
                        <div class="col-md-4 text-right">
                            @if ($ba->status != 1)  
                            <button type="button" class="btn btn-success btn-sm div_hide" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>                   
                            @endif
                        
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
                                    @foreach($confirm as $item)
                                        <tr>
                                            <td  class="text-center">
                                                1
                                            </td>
                                            <td>
                                                {!! Form::select('detail[desc][]',
                                                App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                                $item->desc ?? null, 
                                                ['class' => 'form-control select2 desc', 
                                                'required'=>true,
                                                'placeholder'=>'- เลือกรายละเอียดประมาณค่าใช้จ่าย -']); !!}
                                            </td>
                                            <td>
                                                {!! Form::text('detail[cost][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                                            </td>
                                            <td>
                                                {!! Form::text('detail[nod][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                                            </td>
                                            <td>
                                                {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                   @endforeach    
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









@if(count($ba->CertificateHistorys))
<div class="row form-group">
    <div class="col-md-12">
     <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>เหตุผล ขอแก้ไข</h3></legend>
         <div class="row">
            <div class="col-md-12">
                <div class="panel block4">
                <div class="panel-group" id="accordion">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse"> <dd> คณะผู้ตรวจประเมิน </dd>  </a>
                            </h4>
                        </div>
                        @foreach($ba->CertificateHistorys  as $key1 => $item1)
                        <div id="collapse" class="panel-collapse collapse in">
                            <br>
                            <div class="row form-group">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="white-box" style="border: 2px solid #e5ebec;">
                                        <legend>
                                            <h3>
                                               @if($item1->status == 1)
                                                <i class="fa fa-check-square" style="color:rgb(8, 180, 2);font-size:30px;" aria-hidden="true"></i>
                                                @elseif($item1->status == null)
                                                <i class="fa fa-paper-plane" style="color:rgb(4, 0, 255);font-size:30px;" aria-hidden="true"></i>
                                               @else
                                                <i class="fa fa-exclamation-triangle" style="color:rgb(229, 255, 0); background-color: red;font-size:30px;" aria-hidden="true"></i>
                                               @endif
                                               ครั้งที่ {{ $key1 +1}}
                                           </h3>
                                        </legend>
                                        @if(!is_null($item1->details))
                                        <div class="row">
                                          <div class="col-md-4 text-right">
                                             <p class="text-nowrap">ชื่อผู้ยื่นคำขอ</p>
                                          </div>
                                          <div class="col-md-7">
                                            <span>{{$item1->details ?? '-'}}</span>
                                          </div>
                                         </div>
                                         @endif

                                         @if(!is_null($item1->details_one))
                                         <div class="row">
                                           <div class="col-md-4 text-right">
                                              <p class="text-nowrap">ชื่อคณะผู้ตรวจประเมิน</p>
                                           </div>
                                           <div class="col-md-7">
                                             <span>{{$item1->details_one ?? '-'}}</span>
                                           </div>
                                          </div>
                                          @endif

                                         @if(!is_null($item1->DataBoardAuditorDateTitle))
                                         <div class="row">
                                           <div class="col-md-4 text-right">
                                              <p class="text-nowrap">วันที่ตรวจประเมิน</p>
                                           </div>
                                           <div class="col-md-7">
                                             <span>   {!!  @$item1->DataBoardAuditorDateTitle  ?? '-' !!}  </span>
                                           </div>
                                          </div>
                                        @endif

                                        @if(!is_null($item1->file))
                                        <div class="row">
                                          <div class="col-md-4 text-right">
                                             <p class="text-nowrap">บันทึก ลมอ.  แต่งตั้งคณะผู้ตรวจประเมิน</p>
                                          </div>
                                          <div class="col-md-7">
                                            <span>
                                                <a href="{{url('certify/check/file_client/'.$item1->file.'/'.( !empty($item1->attach_client_name) ? $item1->attach_client_name :  basename($item1->file)  ))}}"
                                                   title="{{ !empty($item1->file_client_name) ? $item1->file_client_name :  basename($item1->file) }}" target="_blank">
                                                    {!! HP::FileExtension($item1->file)  ?? '' !!}
                                               </a>
                                           </span>
                                          </div>
                                         </div>
                                       @endif

                                       @if(!is_null($item1->attachs))
                                       <div class="row">
                                         <div class="col-md-4 text-right">
                                            <p class="text-nowrap">กำหนดการตรวจประเมิน</p>
                                         </div>
                                         <div class="col-md-7">
                                            <span>
                                             <a href="{{url('certify/check/file_client/'.$item1->attachs.'/'.( !empty($item1->attach_client_name) ? $item1->attach_client_name :  basename($item1->attachs)  ))}}"
                                                title="{{ !empty($item1->attach_client_name) ? $item1->attach_client_name :  basename($item1->attachs) }}" target="_blank">
                                                {!! HP::FileExtension($item1->attachs)  ?? '' !!}
                                              </a>
                                           </span>
                                         </div>
                                        </div>
                                      @endif
                                      <div class="col-md-12">
                                        <label>โดยคณะผู้ตรวจประเมิน มีรายนามดังต่อไปนี้</label>
                                     </div>
                                    @if(!is_null($item1->details_table))
                                    <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th class="text-center text-white" width="2%">ลำดับ</th>
                                            <th class="text-center text-white" width="30%">สถานะผู้ตรวจประเมิน</th>
                                            <th class="text-center text-white" width="40%">ชื่อผู้ตรวจประเมิน</th>
                                            <th class="text-center  text-white" width="26%">หน่วยงาน</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                             @php
                                               $groups = json_decode($item1->details_table);
                                             @endphp
                                              @foreach($groups as $key2 => $item2)
                                                @php
                                                     $status =  App\Models\Bcertify\StatusAuditor::where('id',$item2->status)->first();
                                                @endphp
                                              <tr>
                                                  <td  class="text-center">{{ $key2 +1 }}</td>
                                                  <td> {{ $status->title ?? '-'  }}</td>
                                                  <td>
                                                    @if(count($item2->temp_users) > 0) 
                                                        @foreach($item2->temp_users as $key3 => $item3)
                                                            {!!  $item3.'<br/>' ?? '-' !!}
                                                        @endforeach
                                                    @endif
                                                  </td>
                                                  <td>
                                                    @if(count($item2->temp_departments) > 0) 
                                                        @foreach($item2->temp_departments as $key4 => $item4)
                                                             {!!  $item4.'<br/>' ?? '-' !!}
                                                        @endforeach
                                                    @endif
                                                  </td>
                                              </tr>
                                              @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                    @endif
                                    @if(!is_null($item1->details_cost_confirm))
                                      @php
                                        $details_cost_confirm = json_decode($item1->details_cost_confirm);
                                     @endphp
                                    <div class="col-md-12">
                                        <label>ประมาณค่าใช้จ่าย</label>
                                     </div>
                                    <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th class="text-center text-white" width="2%">ลำดับ</th>
                                            <th class="text-center text-white" width="38%">รายละเอียด</th>
                                            <th class="text-center text-white" width="20%">จำนวนเงิน (บาท)</th>
                                            <th class="text-center text-white" width="20%">จำนวนวัน (วัน)</th>
                                            <th class="text-center text-white" width="20%">รวม (บาท)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                              @php    
                                              $SumAmount = 0;
                                              @endphp
                                            @foreach($details_cost_confirm as $key => $item)
                                                @php     
                                                $amount_date = !empty($item->amount_date) ? $item->amount_date : 0 ;
                                                $amount = !empty($item->amount) ? $item->amount : 0 ;
                                                $sum =   $amount*$amount_date;
                                                $SumAmount  +=  $sum;
                                                $details =  App\Models\Bcertify\StatusAuditor::where('id',$item->desc)->first();
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                    <td>{{ !is_null($details) ? $details->title : null  }}</td>
                                                    <td class="text-right">{{ number_format($amount, 2) }}</td>
                                                    <td class="text-right">{{ $amount_date }}</td>
                                                    <td class="text-right">{{ number_format($sum, 2) ?? '-'}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <footer>
                                            <tr>
                                                <td colspan="4" class="text-right">รวม</td>
                                                <td class="text-right">
                                                     {{ !empty($SumAmount) ?  number_format($SumAmount, 2) : '-' }} 
                                                </td>
                                            </tr>
                                        </footer>
                                    </table>
                                    </div>
                                    @endif



                                     <hr>
                                     @if(!is_null($item1->status))
                                     @php
                                             if($item1->status == 1){
                                                 $back = true; // กลับหน้า index
                                             }
                                     @endphp
                                    <div class="row">
                                      <div class="col-md-4 text-right">
                                         <p class="text-nowrap">กำหนดการตรวจประเมิน</p>
                                      </div>
                                      <div class="col-md-7">
                                         <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item1->status == 1 ) ? 'checked' : ' '  }}>  &nbsp;เห็นชอบดำเนินการแต่งตั้งคณะผู้ตรวจประเมินต่อไป &nbsp;</label>
                                         <br>
                                         <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item1->status == 2 ) ? 'checked' : ' '  }}>  &nbsp;ไม่เห็นชอบ เพราะ  &nbsp;</label>
                                      </div>
                                     </div>
                                   @endif
                                   @if(!is_null($item1->remark))
                                   <div class="row">
                                     <div class="col-md-4 text-right">
                                        <p class="text-nowrap">หมายเหตุ</p>
                                     </div>
                                     <div class="col-md-7">
                                         {{ @$item1->remark  ?? '-'}}
                                     </div>
                                    </div>
                                  @endif
                                  @if(!is_null($item1->attachs_file))
                                  @php
                                  $attachs_file = json_decode($item1->attachs_file);
                                  @endphp
                                  <div class="row">
                                    <div class="col-md-4 text-right">
                                       <p class="text-nowrap">หลักฐาน</p>
                                    </div>
                                    <div class="col-md-7">
                                       @foreach($attachs_file as $files)
                                         <p>
                                             {{  @$files->file_desc  }}
                                             <a href="{{url('certify/check/file_client/'.$files->file.'/'.( !empty($files->file_client_name) ? $files->file_client_name :   basename($files->file) ))}}" target="_blank">
                                                {!! HP::FileExtension($files->file)  ?? '' !!}
                                            </a>
                                         </p>
                                      @endforeach
                                    </div>
                                   </div>
                                 @endif
                                        @if(!is_null($item1->date))
                                            <div class="row">
                                            <div class="col-md-4 text-right">
                                                <p class="text-nowrap">วันที่บันทึก</p>
                                            </div>
                                            <div class="col-md-7">
                                                {{ HP::DateThai($item1->date)  ?? '-'}}
                                            </div>
                                            </div>
                                        @endif

                                     </div>
                               </div>
                              <div class="col-md-1"></div>
                            </div>
                           @endforeach
                         </div>
                 </div>
               </div>
            </div>
        </div>
     </div>
   </div>
</div>
@endif


@if($ba->status == 1  || $ba->reason_cancel == 1)
<div class="clearfix"></div>
   <a  href="{{ url("$previousUrl") }}"  class="btn btn-default btn-lg btn-block">
    <i class="fa fa-rotate-left"></i>
        <b>กลับ</b>
   </a>
@else
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
        <label for="vehicle1">ขอความเห็นการแต่งตั้ง</label>
        <br>
        <input type="hidden" name="previousUrl" id="previousUrl" value="{{ app('url')->previous() }}">
        <button class="btn btn-primary" type="submit" id="form-save" onclick="submit_form();return false;">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>

        <a class="btn btn-default" href="{{ url("$previousUrl") }}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.13.216/pdf.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@pdf-lib/fontkit@1.1.1/dist/fontkit.umd.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>




    <script type="text/javascript">

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.13.216/pdf.worker.min.js';

        let pdfDoc = null, pageNum = 1, scale = 2.0;
        // let signaturePositions = {};
        let storageId = 1;

        const offset = 16
        let loadedPdfData = null;  // ตัวแปร global เก็บข้อมูลไฟล์ PDF ที่โหลดไว้
        // const pdfUrl = '{{ asset("assets/pdfs/record.pdf") }}';
        const pdfUrl = "{{ url('certify/check/file_client/' . $messageRecordTransaction->file_path . '/' . (!empty($ba->file_client_name) ? $ba->file_client_name : basename($messageRecordTransaction->file_path))) }}";
        const pdfTitle = "{{ !empty($ba->file_client_name) ? $ba->file_client_name : basename($messageRecordTransaction->file_path) }}";

        // let signatures = [];

        let signatures = [
            @foreach($messageRecordTransactions as $transaction)
                {
                    id: '{{ $transaction->signature_id }}',
                    enable: {{ $transaction->is_enable == "1" ? 'true' : 'false' }},
                    show_name: {{ $transaction->show_name == "1" ? 'true' : 'false' }},
                    show_position: {{ $transaction->show_position == "1" ? 'true' : 'false' }},
                    signer_name: '{{ $transaction->signer_name }}',
                    signer_id: '{{ $transaction->signer_id }}',
                    signer_position: '{{ $transaction->signer_position }}',
                    line_space: {{ $transaction->linesapce }}
                },
            @endforeach
        ];

        let signaturePositions = {
            @foreach($messageRecordTransactions as $transaction)
                '{{ $transaction->signature_id }}': {
                    page: {{ $transaction->page_no }},
                    x: {{ $transaction->pos_x ?? 0 }},
                    y: {{ $transaction->pos_y ?? 0 }}
                },
            @endforeach
        };


        // console.log(pdfUrl)

        function  submit_form(){
            Swal.fire({
                title: 'ยืนยันการทำรายการ !',
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
        var $uploadCrop;
        $(document).ready(function () {
            console.log(signatures);
            console.log(signaturePositions);

            // localStorage.setItem(storageId, JSON.stringify(signaturePositions)); 
            check_max_size_file();
                //Validate
                $('#form_auditor').parsley().on('field:validated', function() {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                        })
                        .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                                image       : "",
                                text        : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                        return true; // Don't submit form for this demo
                });


            $('.check-readonly').prop('disabled', true);//checkbox ความคิดเห็น
            $('.check-readonly').parent().removeClass('disabled');
             $('.check-readonly').parent().css('margin-top', '8px');//checkbox ความคิดเห็น
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
           //ลบตำแหน่ง
            $('body').on('click', '.date_edit_remove', function() {
                    $(this).parent().parent().remove();
            });

            $('#certi_no').change(function () {

                    if($(this).val() != ''){
                        $.ajax({
                           url: "{!! url('certify/auditor/certi_no') !!}" + "/" +  $(this).val()
                       }).done(function( object ) {
                           $('#no').val(object.name);
                           $('#app_id').val(object.id);
                      });
                    }else{
                           $('#no').val('');
                           $('#app_id').val('');
                    }
            });

            let mock = $('.repeater-item:first').clone();
            $('.repeater-item:first').remove();
            setRepeaterIndex();
            $('.tbody-auditor').find('input[type=checkbox]').on('change', function () {
                changeSelectAll($(this));
            })

            //เพิ่มตำแหน่งงาน
            $('#plus-row').click(function () {

                let item = mock.clone();

                //Clear value select
                item.find('select').val('');
                item.find('select').prev().remove();
                item.find('select').removeAttr('style');
                item.find('select').select2();

                item.find('.repeater-remove').on('click', function () {
                    removeIndex(this)
                });

                item.find('.btn-user-select').on('click', function () {
                    modalHiding($(this).closest('.modal'));
                });
                item.find('.modal').on('show.bs.modal', function () {
                    modalOpening($(this));
                });
                item.find('.modal').on('hidden.bs.modal', function () {
                    modalClosing($(this));
                });

                item.find('.status').on('change', function () {
                    statusChange($(this));
                });

                item.find('.select-all').on('change', function () {
                    checkedAll($(this));
                });

                item.appendTo('#table-body');

                setRepeaterIndex();

            });

            $('.status').change(function () {
                statusChange($(this));
            });

            $('.repeater-remove').click(function () {
                removeIndex(this)
            });

            $('.btn-user-select').on('click', function () {
                modalHiding($(this).closest('.modal'));
            });

            $('.modal').on('show.bs.modal', function () {
                modalOpening($(this));
            });

            $('.modal').on('hidden.bs.modal', function () {
                modalClosing($(this));
            });

            $('.select-all').change(function () {
                checkedAll($(this));
            });

            //เพิ่มตำแหน่งงาน
            $('#work-add').click(function() {

                $('#work-box').children(':first').clone().appendTo('#work-box'); //Clone Element

                var last_new = $('#work-box').children(':last');

                //Clear value text
                $(last_new).find('input[type="text"]').val('');

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                //Clear Radio
                $(last_new).find('.check').each(function(index, el) {
                    $(el).prependTo($(el).parent().parent());
                    $(el).removeAttr('style');
                    $(el).parent().find('div').remove();
                    $(el).iCheck();
                    $(el).parent().addClass($(el).attr('data-radio'));
                });

                //Change Button
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger work-remove');
                $(last_new).find('button').html('<i class="icon-close"></i> ลบ');

                resetOrder();
                check_max_size_file();
            });

            //ลบตำแหน่ง
            $('body').on('click', '.work-remove', function() {

                $(this).parent().parent().parent().parent().remove();

                resetOrder();

            });

            //Crop image
            $uploadCrop = $('#upload-demo').croppie({

                enableExif: true,

                viewport: {

                    width: 140,

                    height: 140,

                },

                boundary: {

                    width: 200,

                    height: 200

                }

            });

            $('#upload').on('change', function () {

                $('#upload-demo').removeClass('hide');
                $('#image-show').addClass('hide');

                var reader = new FileReader();

                reader.onload = function (e) {

                    $uploadCrop.croppie('bind', {

                        url: e.target.result

                    }).then(function(){

                        console.log('jQuery bind complete');

                    });

                }

                reader.readAsDataURL(this.files[0]);

            });

            $('#form-save').click(function(event) {

                //เลื่อนมาแถบแรก
                $('.tab-pane').removeClass('active in');
                $('#home1').addClass('active in');

                //คัดลอกข้อมูลภาพที่ Crop
                CropFile();

            });
        });

        function checkedAll(that) {
            let checkboxes = $(that).closest('.modal').find('.tbody-auditor').find('input[type=checkbox]');
            checkboxes.each(function() {
                $(this).prop('checked', $(that).is(':checked'));
            });
        }

        function statusChange(that) {

            var app_id =   '{{ $ba->app_certi_lab_id ?? null }}';
            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            tdUsers.children().remove();
            tdDepartments.children().remove();
 
            let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
            input.appendTo(tdUsers);
            let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
            inputDepart.appendTo(tdDepartments);

            let tbody = $(that).closest('tr').find('.modal').find('tbody');
            let id = $(that).val();
            if (id !== "" && id !== undefined && checkNone(app_id)) {
                that.parent().parent().parent().parent().find('.exampleModal').prop('disabled',false);
                let url = '{{url('/certify/auditor/status/')}}'+'/'+id +'/'+ app_id;
                $.ajax({
                    type: 'get',
                    url: url,
                    success: function (resp) {
                        tbody.children().remove();
                        let auditors = resp.auditors;
                        let n = 1;
                        auditors.forEach(auditor => {
                            let tr = $('<tr rolw="row" class="odd">');
                            let td = $('<td class="sorting_1">');
                            td.text(n + '.');
                            td.appendTo(tr);

                            let td2 = $('<td>');
                            let input = $('<input type="checkbox" id="master" value="'+auditor.id+'">');
                            input.attr('data-value', auditor.name_th).attr('data-department', auditor.department);
                            input.on('change', function () {
                                changeSelectAll($(this));
                            });
                            input.appendTo(td2);
                            td2.appendTo(tr);

                            let td3 = $('<td>');
                            td3.text(auditor.name_th);
                            td3.appendTo(tr);

                            let td4 = $('<td>');
                            td4.text(auditor.department);
                            td4.appendTo(tr);

                            let td5 = $('<td>');
                            td5.text(auditor.position);
                            td5.appendTo(tr);

                            let td6 = $('<td>');
                            td6.text(auditor.branch);
                            td6.appendTo(tr);

                            // let td6 = $('<td>');
                            // let button = $('<button class="btn btn-primary">');
                            // let icon = $('<i class="glyphicon glyphicon-info-sign" aria-hidden="true">');
                            // icon.appendTo(button);
                            // button.appendTo(td6);
                            // td6.appendTo(tr);

                            tr.appendTo(tbody);
                        });
                    },
                    error: function (resp) {
                        console.log(resp);
                    },
                })
                filter_tbody_auditor();
            } else if (id === "") {
                that.parent().parent().parent().parent().find('.exampleModal').prop('disabled',true);
                tbody.children().remove();
            }
        }

        var tempCheckboxes = [];
        function modalHiding(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            let empty = true;
            let groupVal = "";
            tdUsers.children().remove();
            tdDepartments.children().remove();
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    let val = $(this).data('value');
                    let depart = $(this).data('department');
                    let input = $('<input type="text" class="form-control item" data-name="temp_users[]" value="'+val+'" readonly>');
                    input.appendTo(tdUsers);
                    let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" value="'+depart+'" readonly>');
                    inputDepart.appendTo(tdDepartments);
                    empty = false;

                    groupVal += groupVal !== "" ? ";" + $(this).val() : $(this).val();

                    tempCheckboxes.push($(this));
                }
            });

            let input = $('<input type="hidden" class="form-control item" data-name="users" value="'+groupVal+'">');
            input.appendTo(tdUsers);

            if (empty) {
                let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
                input.appendTo(tdUsers);
                let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
                inputDepart.appendTo(tdDepartments);
            }

            $(that).modal('hide');

            setRepeaterIndex();
        }

        function modalOpening(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    tempCheckboxes.push($(this));
                    checkedCount++;
                }
            });

            changeSelectAll(that);

        }

        function modalClosing(that) {
            let checkboxes = $(that).find('input[type=checkbox]');
            checkboxes.prop('checked', false);
            tempCheckboxes.forEach(function (checkbox) {
                checkboxes.each(function () {
                    if (checkbox.val() === $(this).val()) {
                        $(this).prop('checked', true);
                    }
                });
            });
            tempCheckboxes = [];
        }

        function changeSelectAll(that) {
            let modal = $(that).closest('.modal');
            let checkboxes = modal.find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    checkedCount++;
                }
            });

            if (checkedCount === checkboxes.length && checkboxes.length > 0) {
                modal.find('.select-all').prop('checked', true);
            } else {
                modal.find('.select-all').prop('checked', false);
            }
        }

        function setRepeaterIndex() {
            let group_name = "group";
            let n = 0;
            $('#table-body').find('tr.repeater-item').each(function () {
                $(this).find('.item').each(function () {
                    let dataName = $(this).data('name');
                    if (dataName !== undefined) {
                        let strArray = '';
                        if (dataName.includes('[]')) {
                            strArray = "[]";
                            dataName = dataName.substring(0, dataName.length - 2);
                        }

                        $(this).attr('name', group_name + "[" + n + "]" + "[" + dataName + "]" + strArray);
                    }
                });

                let newId = 'modal-' + n;
                $(this).find('.repeater-modal').attr('id', newId);
                $(this).find('.repeater-modal-open').attr('data-target', '#'+newId);
                n++;
            });
        }

        function removeIndex(that) {
            that.closest('tr').remove();

            setRepeaterIndex();
        }

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#work-box').children().each(function(index, el) {
                $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
                $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
            });

        }

        function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

            var croppied = $uploadCrop.croppie('get');

            $('#top').val(croppied.points[1]);
            $('#left').val(croppied.points[0]);
            $('#bottom').val(croppied.points[3]);
            $('#right').val(croppied.points[2]);
            $('#zoom').val(croppied.zoom);

            $uploadCrop.croppie('result', {

                type: 'canvas',

                size: 'viewport'

            }).then(function (resp) {

                $('#croppied').val(resp);

            });
        }
    </script>
    <script>
        $(document).ready(function () {
               ResetTableNumber();
                IsInputNumber();
                IsNumber();
                cost_rate();
                data_list_disabled();
                TotalValue();
   
        });
            //เพิ่มแถว
            $('#addCostInput').click(function(event) {
                var data_list = $('.desc').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data_list == 0){
                        Swal.fire('หมอรายการรายละเอียดประมาณค่าใช้จ่าย !!')
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
            });


           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
            });


            function   filter_tbody_auditor() {
               $(".myInput").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            var row =   $(this).parent().parent().parent().parent();
                            $(row).find(".tbody-auditor tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                });   
        }
        function ResetTableNumber(){
                var rows = $('#table_body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                });
         }


        function  TotalValue() {
            var rows = $('#table_body').children(); //แถวทั้งหมด
            var total_all = 0.00;
            rows.each(function(index, el) {
                if($(el).children().find("input.number").val() != ''){
                    var number = parseFloat(RemoveCommas($(el).children().find("input.number").val()));
                    console.log($(el).children().find("input.number").val());
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

             $('.cost_rate,.amount_date').change(function(event) {
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
                $('.desc').children('option').prop('disabled',false);
                $('.desc').each(function(index , item){
                    var data_list = $(item).val();
                    $('.desc').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }

       function checkNone(value) {
         return value !== '' && value !== null && value !== undefined;
       }


       function openPdfModal() {
        console.log(signaturePositions)
            loadedPdfData = null;
            // storageId = $('#storage_id').val().trim(); // อ่านค่า storageId ใหม่ทุกครั้ง
            if (!storageId) {
                alert('Please enter a valid Storage ID');
                return;
            }

            $('#pdfModal').modal('show');
            loadPdf(pdfUrl);

            // ตรวจสอบ localStorage
            // if (localStorage.getItem(storageId)) {
            //     signaturePositions = JSON.parse(localStorage.getItem(storageId));
            //     console.log('Loaded Signatures:', signaturePositions);
            // } else {
            //     signaturePositions = {};
            // }
            
            // รอให้ modal แสดงผลเต็มที่ก่อนโหลดลายเซ็น
            setTimeout(() => {
                loadSignatures();
            }, 200);
        }

        // ฟังก์ชันโหลด PDF
        async function loadPdf(url) {
            pdfDoc = await pdfjsLib.getDocument(url).promise;
            renderPage(pageNum);
        }

        // ฟังก์ชันแสดง PDF หน้า
        async function renderPage(num) {
            const page = await pdfDoc.getPage(num);
            const viewport = page.getViewport({ scale });
            
            const canvas = $('#pdfCanvas')[0];
            const context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = { canvasContext: context, viewport: viewport };
            await page.render(renderContext).promise;

            // ใช้ requestAnimationFrame เพื่อรอให้ canvas เรนเดอร์เสร็จ
            requestAnimationFrame(() => {
                loadSignatures();
            });
        }

        function nextPage() {
            if (pageNum < pdfDoc.numPages) {
                pageNum++;
                renderPage(pageNum);
            }
        }

        function prevPage() {
            if (pageNum > 1) {
                pageNum--;
                renderPage(pageNum);
            }
        }

        function addSignature(signature) 
        {
            // ถ้า div สำหรับ signature นี้มีอยู่แล้ว ให้ไม่ทำอะไร
            if ($('#' + signature.id).length) return;

            // สร้าง div สำหรับ signature
            const signatureDiv = $('<div>')
                .addClass('draggable-signature')
                .attr('id', signature.id)
                .css('min-height', '45px'); // เพิ่ม min-height

            // แสดงชื่อผู้เซ็นและตำแหน่ง
            signatureDiv.html(`
                <div class="signature-info">
                    <strong>${signature.signer_name}</strong>
                </div>
            `);

            // สร้าง div สำหรับเก็บไอคอนและตั้งค่า Flexbox
            const iconContainer = $('<div>')
                .css({ display: 'flex', justifyContent: 'space-between', marginTop: '5px' }); // ตั้งค่า space-between และเพิ่มระยะห่างด้านบน

            // สร้างไอคอน Font Awesome Trash
            const deleteIcon = $('<i>')
                .addClass('fas fa-trash-alt text-danger')
                .css({ cursor: 'pointer', fontSize: '18px' })
                .on('click', function() {
                    signatureDiv.remove();
                    delete signaturePositions[signature.id];  // ลบจาก signaturePositions โดยใช้ signature.id
                    localStorage.setItem(storageId, JSON.stringify(signaturePositions));  // เก็บค่าลง localStorage
                });

            // สร้างไอคอน Font Awesome Cog
            const gearIcon = $('<i>')
                .addClass('fas fa-cog text-primary')
                .css({ cursor: 'pointer', fontSize: '18px' })
                .on('click', function() {
                    const selectedSignature = signatures.find(sig => sig.id === signature.id);
                    if (selectedSignature) {
                        $('#signer-name').val(selectedSignature.signer_name);
                        $('#signer-position').val(selectedSignature.signer_position);
                        $('#line-space').val(selectedSignature.line_space);
                        $('#show-name').val(selectedSignature.show_name.toString());
                        $('#show-position').val(selectedSignature.show_position.toString());

                        console.log(selectedSignature.show_name.toString(), selectedSignature.show_position.toString());

                        $('#signatureModal').modal('show');
                        $('#save-signature').data('signature-id', selectedSignature.id);
                    } else {
                        console.log('Signature not found.');
                    }
                });

            // เพิ่มไอคอนลงใน iconContainer
            // iconContainer.append(deleteIcon);
            // iconContainer.append(gearIcon);

            // เพิ่ม iconContainer ไว้ใต้ชื่อ
            signatureDiv.append(iconContainer);

            $(".pdfCanvasWrapper").append(signatureDiv);

            // ทำให้ div ของ signature สามารถลากได้
            // $('#' + signature.id).draggable({
            //     containment: "#pdfCanvas"
            // });

            
            // เมื่อคลิกปุ่ม Save changes ในโมดัล
            $('#save-signature').click(function () {
                const signatureId = $(this).data('signature-id');
                const updatedSignature = {
                    id: signatureId,
                    enable: true,
                    signer_name: $('#signer-name').val(),
                    signer_position: $('#signer-position').val(),
                    line_space: parseInt($('#line-space').val(), 10),
                    show_name: $('#show-name').val() === 'true',
                    show_position: $('#show-position').val() === 'true'
                };
                // console.log("updatedSignature",updatedSignature)
                // อัพเดท signature ใน array signatures
                const index = signatures.findIndex(sig => sig.id === signatureId);
                if (index !== -1) {
                    
                    signatures[index] = updatedSignature;

                    console.log(signatures)

                }

                // ปิดโมดัล
                $('#signatureModal').modal('hide');
            });
        }

        function saveSignatures() 
        {
            if (!storageId) return;

            $('.draggable-signature').each(function() {
                const { left, top } = $(this)[0].getBoundingClientRect();
                const pdfRect = $('#pdfCanvas')[0].getBoundingClientRect();
                const x = (left - pdfRect.left) / pdfRect.width;
                const y = (top - pdfRect.top) / pdfRect.height;
                console.log(x,y)
                if (x > 0){
                    signaturePositions[$(this).attr('id')] = { page: pageNum, x, y };
                }  
            });

            localStorage.setItem(storageId, JSON.stringify(signaturePositions));
            console.log('Signatures Saved:', signaturePositions);
            alert('Signatures saved!');
        }

        function loadSignatures() 
        {
            $('.draggable-signature').remove(); // ลบลายเซ็นเก่าออก

            console.log(signaturePositions);
            
            // วนลูปผ่าน signaturePositions และดึงข้อมูล signature โดยใช้ id
            $.each(signaturePositions, function(id, { page, x, y }) {
                if (page === pageNum) {
                    // ค้นหาข้อมูล signature โดยใช้ id
                    const signature = signatures.find(sig => sig.id === id);

                    if (signature) {
                        // ส่งข้อมูล signature ทั้งหมดไปยังฟังก์ชัน addSignature
                        addSignature(signature);

                        const signatureDiv = $('#' + id);
                        const pdfRect = $('#pdfCanvas')[0].getBoundingClientRect();
                        
                        // ตำแหน่ง X และ Y ที่คำนวณจาก pdfRect
                        signatureDiv.css({
                            left: `${x * pdfRect.width + offset}px`,
                            top: `${y * pdfRect.height + offset}px`
                        });
                    }
                }
            });
        }

        function removeStorage() 
        {
            // storageId = $('#storage_id').val().trim(); // อ่านค่า storageId ใหม่ทุกครั้ง
            if (!storageId) {
                alert('Please enter a valid Storage ID');
                return;
            }

            if (storageId && localStorage.getItem(storageId)) {
                localStorage.removeItem(storageId);
                // alert(`Storage ID '${storageId}' removed.`);
            } else {
                // alert('No data found for the given Storage ID.');
            }
        }

        // เมื่อกดปุ่ม "เลือกไฟล์ PDF" ให้เปิด input[type="file"]
        $('#selectFileButton').on('click', function() 
        {
            // storageId = $('#storage_id').val().trim(); // อ่านค่า storageId ใหม่ทุกครั้ง
            if (!storageId) {
                alert('Please enter a valid Storage ID');
                return;
            }

            // เคลียร์ค่า input[type="file"] ก่อนการเลือกไฟล์ใหม่
            $('#fileInput').val(null);  // รีเซ็ตค่า file input

            // คลิก input[type="file"] เพื่อเลือกไฟล์
            $('#fileInput').click();
        });

        $('#fileInput').on('change', function(event) 
        {
            const file = event.target.files[0];  // อ่านไฟล์จาก input
            if (file && file.type === 'application/pdf') {
                const reader = new FileReader();

                // เมื่ออ่านไฟล์เสร็จแล้ว
                reader.onload = function(e) {
                    loadedPdfData = new Uint8Array(e.target.result);  // เก็บข้อมูล PDF ไว้ในตัวแปร global

                    // เปิด Modal
                    $('#pdfModal').modal('show');
                    
                    // แสดง PDF ใน Canvas
                    loadPdf(loadedPdfData);  // ส่งข้อมูล PDF ไปที่ฟังก์ชัน loadPdf

                    // ตรวจสอบว่า localStorage มีข้อมูล signature หรือไม่
                    if (localStorage.getItem(storageId)) {
                        signaturePositions = JSON.parse(localStorage.getItem(storageId));
                    } else {
                        signaturePositions = {};
                    }
                    console.log(signaturePositions);
                    // รอให้ modal แสดงผลเต็มที่ก่อนโหลดลายเซ็น
                    setTimeout(() => {
                        loadSignatures();
                    }, 200);
                };

                reader.readAsArrayBuffer(file);
            } else {
                alert('Please select a PDF file.');
            }
        });

        // กดปุ่ม "บันทึก"
        $('#saveButton').on('click', saveSignatures);

        // กดปุ่ม "ลบ Storage"
        $('#removeButton').on('click', removeStorage);

        async function exportPdfWithSignatures(isPreview = false) 
        {
            // const pdfUrl = '{{ asset("assets/pdfs/flow.pdf") }}';
            const signatureUrl = '{{ asset("assets/signatures/signature1.png") }}';
            const fontUrl = '{{ asset("fonts/THSarabunNew.ttf") }}';

            let sourcePdfBytes;

            if (loadedPdfData) {
                // ใช้ไฟล์ที่โหลดไว้ใน global
                sourcePdfBytes = loadedPdfData;
            } else {
                // หากไม่พบไฟล์ใน global ก็ให้โหลดไฟล์จาก path ที่กำหนด

                sourcePdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
            }

            // Load PDF and create PDF Document
            const pdfDoc = await PDFLib.PDFDocument.load(sourcePdfBytes);

            pdfDoc.registerFontkit(fontkit);
            const pages = pdfDoc.getPages();

            // หาค่าหน้า (page) ที่มากที่สุดจาก signaturePositions
            const signaturePageNumbers = Object.values(signaturePositions).map(position => position.page);
            const maxPageNumber = Math.max(...signaturePageNumbers);

            // เปรียบเทียบกับจำนวนหน้าของไฟล์ PDF ที่โหลด
            if (maxPageNumber > pages.length) {
                alert(`จำนวนหน้าของ PDF ที่โหลดน้อยกว่าหน้าสูงสุดที่ระบุในตำแหน่งลายเซ็น (page ${maxPageNumber})`);
                return;
            }

            // Load signature image
            const signatureImageBytes = await fetch(signatureUrl).then(res => res.arrayBuffer());
            const signatureImage = await pdfDoc.embedPng(signatureImageBytes);

            const sarabunFontBytes = await fetch(fontUrl).then(res => res.arrayBuffer());
            const sarabunFont = await pdfDoc.embedFont(sarabunFontBytes);

            // Embed the standard font for watermark
            const helveticaFont = await pdfDoc.embedFont(PDFLib.StandardFonts.Helvetica);

            // Add watermark to each page if isPreview is true
            if (isPreview) {
                pages.forEach(pdfPage => {
                    const { width, height } = pdfPage.getSize();
                    // Define watermark text
                    const watermarkText = 'Preview Only';
                    const fontSize = 60;
                    const opacity = 0.3;
                    const rotateAngle = 45;

                    // Get the width of the watermark text
                    const textWidth = helveticaFont.widthOfTextAtSize(watermarkText, fontSize);

                    // Calculate the position to center the text horizontally
                    const x = ((width - textWidth) / 2) * 1.5; // Center horizontally based on text width
                    const y = (height / 2) * 0.7; // Center vertically (you can adjust this for better positioning)

                    // Add watermark text, rotated and with opacity
                    pdfPage.drawText(watermarkText, {
                        x: x, // Centered horizontally based on text width
                        y: y, // Centered vertically
                        size: fontSize,
                        color: PDFLib.rgb(0.8, 0.0, 0.0), // Dark red color for emphasis
                        opacity: opacity, // Make the text lightly visible but still prominent
                        rotate: PDFLib.degrees(rotateAngle), // Rotate the text for a diagonal watermark
                        font: helveticaFont,
                    });
                });
            }

            Object.entries(signaturePositions).forEach(([id, { page, x, y }]) => {
                const signature = signatures.find(sig => sig.id === id);

                if (signature && signature.enable) {
                    const pdfPage = pages[page - 1]; // Select the page
                    const { width, height } = pdfPage.getSize();
                    const imgWidth = 60; // Set image size
                    const imgHeight = 30;

                    // Draw the signature image
                    pdfPage.drawImage(signatureImage, {
                        x: x * width,
                        y: height - (y * height) - imgHeight, // Adjust y position
                        width: imgWidth,
                        height: imgHeight
                    });

                    // Draw the first line of text (show_name)
                    const nameFontSize = 16;
                    const nameWidth = sarabunFont.widthOfTextAtSize(signature.signer_name, nameFontSize);
                    const nameX = x * width + (imgWidth / 2) - nameWidth / 2;

                    const nameY = height - (y * height) - imgHeight - signature.line_space;
                    // console.log(signature.show_name)
                    if (signature.show_name) {
                        pdfPage.drawText(signature.signer_name, {
                            x: nameX,
                            y: nameY,
                            size: nameFontSize,
                            font: sarabunFont,
                            color: PDFLib.rgb(0, 0, 0), // Black color for the name text
                        });
                    }

                    // Draw the second line of text (show_position)
                    const nameWidth2 = sarabunFont.widthOfTextAtSize(signature.signer_position, nameFontSize);
                    const nameX2 = x * width + (imgWidth / 2) - nameWidth2 / 2;

                    const nameY2 = nameY - (nameFontSize + 2); // Adjust space between lines

                    if (signature.show_position) {
                        pdfPage.drawText(signature.signer_position, {
                            x: nameX2,
                            y: nameY2-5,
                            size: nameFontSize,
                            font: sarabunFont,
                            color: PDFLib.rgb(0, 0, 0), // Black color for the name text
                        });
                    }

                }
            });

            // Save the new PDF with watermark and signatures
            const pdfBytes = await pdfDoc.save();
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'exported_with_signatures.pdf';
            link.click();
        }
    </script>
@endpush
