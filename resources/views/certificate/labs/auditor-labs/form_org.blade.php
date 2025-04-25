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
        .no-drop {cursor: no-drop;}
    </style>
@endpush

<div class="row">
     <div class="col-md-12">
        <div class="col-md-9">
          <div class="form-group {{ $errors->has('reference_refno') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('reference_refno', 'เลขที่อ้างอิง'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        @if(!empty($tracking->reference_refno))
                            {!! Form::text('reference_refno',$tracking->reference_refno ?? null, ['id' => 'reference_refno', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                            {!! Form::hidden('tracking_id', (!empty($tracking->tracking_id) ? $tracking->tracking_id  : null) , ['id' => 'tracking_id', 'class' => 'form-control', 'placeholder'=>'' ]); !!}
                        @else 
                             {!! Form::text('reference_refno',  null, ['id' => 'reference_refno', 'class' => 'form-control no-drop', 'placeholder'=>'', 'readonly' => true]); !!}
                        @endif
                        {!! Form::hidden('certificate_for', (!empty($tracking->certificate_for) ? $tracking->certificate_for  : null) , ['id' => 'certificate_for', 'class' => 'form-control',  'disabled' => true]); !!}
                    </div>
           </div>
           <div class="form-group {{ $errors->has('org_name') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('org_name', 'ชื่อผู้ยื่นคำขอ'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                     {!! Form::text('no',  (!empty($tracking->name) ? $tracking->name  : null)  , ['id' => 'org_name', 'class' => 'form-control no-drop',  'readonly' => true]); !!}
                    </div>
           </div>
           <div class="form-group {{ $errors->has('lab_name') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('lab_name', 'ชื่อห้องปฏิบัติการ'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                     {!! Form::text('lab_name', (!empty($tracking->lab_name) ? $tracking->lab_name  : null) , ['id' => 'lab_name', 'class' => 'form-control',  'disabled' => true]); !!}
                    </div>
           </div>
           <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span>  ชื่อคณะผู้ตรวจประเมิน'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                           {!! Form::text('auditor',null, ['id' => 'auditor', 'class' => 'form-control' , 'maxlength' => '255', 'required' => true]); !!}
                    </div>
           </div>
            @if(!empty($auditor) && count($auditor->auditors_date_many) > 0)
                @foreach ($auditor->auditors_date_many as $key => $itme)
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
                        <button type="button" class="btn btn-success btn-sm pull-right add_date   {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}" id="add_date">
                            <i class="icon-plus" aria-hidden="true"></i>
                            เพิ่ม
                        </button> 
                        @else
                    <button type="button" class="btn btn-danger btn-sm pull-right date_edit_remove  {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}"><i class="fa fa-close" aria-hidden="true"></i> ลบ </button>
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
                        @if (!empty($auditor->FileAuditors1) &&  $auditor->FileAuditors1 != '')
                            <p id="deleteFlieOtherAttach">
                                <a href="{{url('funtions/get-view/'.$auditor->FileAuditors1->url.'/'.( !empty($auditor->FileAuditors1->filename) ? $auditor->FileAuditors1->filename :  basename($auditor->FileAuditors1->new_filename) ))}}" target="_blank">
                                    {!! HP::FileExtension($auditor->FileAuditors1->filename)  ?? '' !!}
                                </a>
                                <button class="btn btn-danger btn-xs deleteFlie   {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}" type="button" onclick="deleteFlieOtherAttach({{ $auditor->FileAuditors1->id}})">
                                    <i class="icon-close"></i>
                                </button>   
                            </p> 
                            <div id="AddOtherAttach"></div>           
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
                        @endif
                    </div>
                </div>
                <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> กำหนดการตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        @if (!empty($auditor->FileAuditors2) &&  $auditor->FileAuditors2 != '')
                         <p  id="deleteFlieAttach">
                                <a href="{{url('funtions/get-view/'.$auditor->FileAuditors2->url.'/'.( !empty($auditor->FileAuditors2->filename) ? $auditor->FileAuditors2->filename :  basename($auditor->FileAuditors2->new_filename)   ))}}" target="_blank">
                                    {!! HP::FileExtension($auditor->FileAuditors2->filename)  ?? '' !!}
                                </a>
                            <button class="btn btn-danger btn-xs deleteFlie  {{ ($auditor->vehicle ==  1 || $auditor->status_cancel == 1) ? 'hide' : ''}}" type="button" onclick="deleteFlieAttach({{ $auditor->FileAuditors2->id}})">
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

         </div>
    </div>
</div>
<div class="row">
<div class="col-md-12 repeater">
    <button type="button" class="btn btn-success btn-sm pull-right clearfi  {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}" id="plus-row">
        <i class="icon-plus" aria-hidden="true"></i>
        เพิ่ม
    </button>
    <div class="clearfix"></div>
    <br/>

    <table class="table color-bordered-table primary-bordered-table">
        <thead>
        <tr>
            <th class="text-center">สถานะผู้ตรวจประเมิน</th>
            <th class="text-center">ชื่อผู้ตรวจประเมิน</th>
            <th class="text-center"></th>
            <th class="text-center">หน่วยงาน</th>
            <th class="text-center  {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}"> ลบรายการ</th>
        </tr>
        </thead>
        <tbody id="table-body">
      @if (!empty($auditors_status))
            @foreach($auditors_status as $key => $item)    
        <tr class="repeater-item">
            <td class="text-center text-top">
                <div class="form-group {{ $errors->has('taxid') ? 'has-error' : ''}}">
                    <div class="col-md-9">
                        {!! Form::select('list[status][]',    
                          App\Models\Bcertify\StatusAuditor::pluck('title', 'id'),
                          $item->status_id ??  null,
                           ['class' => 'form-control   status select2', 
                          'placeholder'=>'-เลือกสถานะผู้ตรวจประเมิน-',
                           'data-key'=> $key, 
                           'required'=>true]); !!}
                    </div>
                </div>
            </td>

            {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
            <td class="align-right text-top ">
                <div class="td-users">
                    @if(!empty($item->auditors_list_many) && count($item->auditors_list_many) > 0)
                        @foreach($item->auditors_list_many as $key1 => $item1) 
                        {!! Form::text('filter_search',
                            $item1->temp_users ?? null, 
                            ['class' => 'form-control item', 
                            'readonly' => true])
                         !!}
                          <input type="hidden" class="temp_users" name="list[temp_users][{{$item->status_id}}][]"  value="{{$item1->temp_users}}">
                         <input type="hidden"  class="user_id"  name="list[user_id][{{$item->status_id}}][]"  value="{{$item1->user_id}}">
                         <input type="hidden" class="temp_departments"  name="list[temp_departments][{{$item->status_id}}][]" value="{{$item1->temp_departments}}">
                        @endforeach
                     @else 
                      {!! Form::text('filter_search', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'filter_search','required' => true]); !!}
                     @endif
                </div>
                <div class="div-users"></div>
            </td>
            {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
            <td class="text-top">
                <button type="button" class="btn btn-primary repeater-modal-open exampleModal div_hide" data-toggle="modal" data-target="#exampleModal"  {{ !empty($item->status_id) ? '' : 'disabled' }} 
                        data-whatever="@mdo"> select
                </button>
                <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                <div class="modal fade repeater-modal exampleModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
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
                                                    <tbody class="tbody-auditor">
                                                        @if(!empty($item->AuditorExpertiseTitle) && count($item->AuditorExpertiseTitle) > 0)
                                                            @foreach($item->AuditorExpertiseTitle as $key2 => $item2) 
                                                            <tr>
                                                                <td> {{ $key2 +1 }}</td>
                                                                <td  class="text-center"> 
                                                                <input type="checkbox"
                                                                    value="{{$item2->id}}"  
                                                                    data-value="{{$item2->NameTh}}"  
                                                                    data-department="{{$item2->department}}" 

                                                                    data-status="{{$item->status_id}}"
                                                                >
                                                                </td>
                                                                <td> {{ $item2->NameTh}}</td>
                                                                <td> {{ $item2->department}}</td>
                                                                <td> {{ $item2->position}}</td>
                                                                <td> {{ $item2->branchable}}</td>
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
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
            <td class="align-top text-top  ">
                <div class="td-departments">
                @if(!empty($item->auditors_list_many) && count($item->auditors_list_many) > 0)
                    @foreach($item->auditors_list_many as $key1 => $item1) 
                        <input type="text" class="form-control item" readonly value="{{ $item1->temp_departments }}">
                    @endforeach
                 @else 
                     {!! Form::text('department', 
                        null,
                        ('' == 'required') ?
                        ['class' => 'form-control item', 'required' => 'required'] :
                        ['class' => 'form-control item','readonly'=>'readonly',
                        'data-name'=>'department']) 
                    !!}
                 @endif
              
                </div>
                <div class="div-departments"></div>
         
            </td>
            <td align="center" class="text-top   {{ (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1)) ? 'hide' : ''}}">
                <button type="button" class="btn btn-danger btn-xs repeater-remove">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </td>
          </tr>
          @endforeach
       @endif
        </tbody>
    </table>
</div>

<div class="row form-group" id="table_cost">
          <div class="col-md-12">
              <div class="white-box" style="border: 2px solid #e5ebec;">
                  <legend><h4>ค่าใช้จ่าย</h4></legend>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
 
                              <div class="col-sm-12 m-t-15">
                                  <table class="table color-bordered-table primary-bordered-table">
                                      <thead>
                                          <tr>
                                              <th class="text-center" width="2%">#</th>
                                              <th class="text-center" width="38%">รายละเอียด</th>
                                              <th class="text-center" width="20%">จำนวนเงิน</th>
                                              <th class="text-center" width="10%">จำนวนวัน</th>
                                              <th class="text-center" width="20%">รวม (บาท)</th>
           
                                          </tr>
                                      </thead>
                                      <tbody id="table_body">
                                     @if (!empty($auditors_status))
                                        @foreach($auditors_status as $key => $item)    
                                          <tr>
                                              <td  class="text-center">
                                                  {{ $key +1}}
                                              </td>
                                              <td>
                                                   {!! Form::text('',$item->StatusAuditorTitle ?? null, ['id'=>'detail'.$key, 'class' => 'form-control detail', 'placeholder'=>'', 'disabled' => true]); !!}
                                              </td>
                                              <td>
                                                  {!! Form::text('list[amount][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                                              </td>
                                              <td>
                                                  {!! Form::text('list[amount_date][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                                              </td>
                                              <td>
                                                  {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                                              </td>
                                             
                                          </tr>
                                           @endforeach  
                                      @endif
                                      </tbody>
                                      <footer>
                                          <tr>
                                              <td colspan="4" class="text-right">รวม(บาท)</td>
                                              <td>
                                                  {!! Form::text('costs_total',
                                                      null,
                                                      ['class' => 'form-control text-right costs_total',
                                                          'id'=>'costs_total',
                                                          'disabled'=>true
                                                      ])
                                                  !!}
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

</div>

@if (!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1))
<div class="clearfix"></div>
<a  href="{{   app('url')->previous() }}"  class="btn btn-default btn-lg btn-block">
   <i class="fa fa-rotate-left"></i>
  <b>กลับ</b>
</a>
@else
<input type="hidden" name="previousUrl" id="previousUrl" value="{{  app('url')->previous() }}">
<div class="form-group">
   <div class="col-md-offset-4 col-md-4">
       <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
       <label for="vehicle1">ขอความเห็นการแต่งตั้ง</label>
       <br>
       <button class="btn btn-primary" type="submit"  >
           <i class="fa fa-paper-plane"></i> บันทึก
       </button>

       <a class="btn btn-default" href="{{  app('url')->previous() }}">
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
  <script type="text/javascript">
 
          $(document).ready(function () {

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
                          return true; 
                    });

            @if(!empty($auditor) && ($auditor->vehicle == 1 || $auditor->status_cancel == 1))
                $('#box-readonly').find('input').prop('disabled', true);
                $('#box-readonly').find('select').prop('disabled', true);
                $('#box-readonly').find('.div_hide').hide();
            @endif
  
              ResetTableNumber1();
              AuditorStatus();
            //   DataListDisabled();
              IsInputNumber();
              IsNumber();
          //เพิ่มแถว
          $('#plus-row').click(function(event) {
                    //  var data = $('.status').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    //   if(data == 0){
                    //       Swal.fire('หมดรายการรายสถานะผู้ตรวจประเมิน !!')
                    //       return false;
                    //   }
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
                    // DataListDisabled();
        
                    row.find('.btn-user-select').on('click', function () {
                              modalHiding($(this).closest('.modal'));
                    });
                    row.find('.select-all').on('change', function () {
                              checkedAll($(this));
                    });

                     //Clone
                   $('#table_body').children('tr:first()').clone().appendTo('#table_body');
                    //Clear value
                    var row1 = $('#table_body').children('tr:last()');
                    row1.find('input[type="text"]').val('');
                    IsInputNumber();
                    ResetTableNumber();
                    IsNumber();
                    cost_rate();
                    check_max_size_file();
          
            });
             //ลบแถว
             $('body').on('click', '.repeater-remove', function(){
                var key =    $(this).parent().parent().find('select.select2').data('key');
                console.log(key);
                $('#detail'+key).parent().parent().remove();
               $(this).parent().parent().remove();
                ResetTableNumber1();
                IsInputNumber();
                ResetTableNumber();
                IsNumber();
                cost_rate();
                // DataListDisabled();
                setRepeaterIndex();
              });
                 setRepeaterIndex();
            //   $('body').on('change', '.status', function(){
            //        DataListDisabled();
            //   });


              function ResetTableNumber1(){
                  var rows = $('#table-body').children(); //แถวทั้งหมด
                  (rows.length==1)?$('.repeater-remove').hide():$('.repeater-remove').show();
                    rows.each(function(index, el) {
                        $(el).find('button.exampleModal').attr('data-target','#exampleModal'+index);
                        $(el).find('div.exampleModal').prop('id','exampleModal'+index);
                        $(el).find('select.select2').attr('data-key', index);
                  });
             }
  

             function ResetTableNumber(){
                var rows = $('#table_body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                    $(el).find('.detail').attr('id', 'detail'+index);
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
                    var expenses = $(this).data('key');
                      if($(this).val() != ''){
                   
                          let status = $(this).val();
                          auditor.html('');  
                          exampleModal.prop('disabled',false);
                          
                        //   let url = "{!! url('certify/auditor/status/ib_and_cb') !!}" + "/" +  $(this).val()  + "/1" ;
                          let url = '{{url('/certify/auditor/status')}}'+'/'+ $(this).val() +'/'+  $('#certificate_for').val();
                          $.ajax({
                             url: url
                          }).done(function( object ) { 
                 
                              if(object.auditors.length > 0){
                                  $.each(object.auditors, function( index, item ) {
                                      html += '<tr>';
  
                                      html += '<td>';
                                          html +=  (index +1);
                                      html += '</td>';
                                      html += '<td class="text-center">';
                                          html +=   '<input type="checkbox" id="master"   value="'+item.id+'"   data-status="'+status+'"   data-value="'+item.name_th+'"  data-department="'+item.department+'" >';
                                      html += '</td>';
  
                                      html += '<td>';
                                          html +=  item.name_th;
                                      html += '</td>';
  
                                      html += '<td>';
                                          html +=  item.department;
                                      html += '</td>';
  
                                      html += '<td>';
                                          html +=  item.position;
                                      html += '</td>';
  
                                      html += '<td>';
                                          html +=  item.branch;
                                      html += '</td>';
  
                                      html += '</tr>';
                                  });  
                                  auditor.append(html);
                              }
                              
                           });
                           filter_tbody_auditor();
                      
                                 var text =     $(this).children("option:selected").text();
                              $('#detail'+expenses).val(text);
                      }else{
                          auditor.html('');  
                          exampleModal.prop('disabled',true);
                          $('#detail'+expenses).val('');
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
                      let input = $('<input type="hidden" class="form-control user_id"  name="list[user_id]['+status+'][]" value="'+user_id+'"><input type="text" class="form-control temp_users" name="list[temp_users]['+status+'][]" value="'+val+'" readonly>');
                      input.appendTo(tdUsers);
                      let inputDepart = $('<input type="text" class="form-control temp_departments" name="list[temp_departments]['+status+'][]" value="'+depart+'" readonly>');
                      inputDepart.appendTo(tdDepartments);
                      tempCheckboxes.push($(this));
  
                      Users.children().remove();
                      Departments.children().remove();
                  }
              });
              $(that).modal('hide');

              setRepeaterIndex();
          }
          function checkedAll(that) {
              let checkboxes = $(that).closest('.modal').find('.tbody-auditor').find('input[type=checkbox]');
              checkboxes.each(function() {
                  $(this).prop('checked', $(that).is(':checked'));
              });
          }
        //   function DataListDisabled(){
        //           $('.status').children('option').prop('disabled',false);
        //           $('.status').each(function(index , item){
        //               var data_list = $(item).val();
        //               $('.status').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
        //           });
        //    }
  
                  TotalValue();
                  cost_rate();
 
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

        function setRepeaterIndex() {
            
            let n = 0;
            $('#table-body').find('tr.repeater-item').each(function (index , item){
                $(item).find('.user_id').each(function () {
                     $(this).attr('name',  "list[user_id][" + n + "][]");
                });
                $(item).find('.temp_users').each(function () {
                     $(this).attr('name',  "list[temp_users][" + n + "][]");
                });
                $(item).find('.temp_departments').each(function () {
                     $(this).attr('name',  "list[temp_departments][" + n + "][]");
                });
                n++;
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
                              url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
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
                              url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
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
   
  