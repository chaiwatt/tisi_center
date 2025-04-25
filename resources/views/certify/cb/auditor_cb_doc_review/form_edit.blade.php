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
          {{-- <div class="form-group {{ $errors->has('reference_refno') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('reference_refno', 'เลขที่อ้างอิง'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                        @if(!empty($tracking->reference_refno))
                              {!! Form::text('reference_refno',$tracking->reference_refno ?? null, ['id' => 'reference_refno', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                              {!! Form::hidden('tracking_id', (!empty($tracking->tracking_id) ? $tracking->tracking_id  : null) , ['id' => 'tracking_id', 'class' => 'form-control', 'placeholder'=>'' ]); !!}
                        @else 
                              {!! Form::text('reference_refno',  null, ['id' => 'reference_refno', 'class' => 'form-control no-drop', 'placeholder'=>'', 'readonly' => true]); !!}
                        @endif
                    </div>
           </div>
           <div class="form-group {{ $errors->has('cb_name') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('cb_name', 'ชื่อผู้ยื่นคำขอ'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                     {!! Form::text('no', (!empty($tracking->name) ? $tracking->name  : null) , ['id' => 'cb_name', 'class' => 'form-control no-drop', 'placeholder'=>'', 'readonly' => true]); !!}
                    </div>
           </div>
           <div class="form-group {{ $errors->has('name_standard') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('name_standard', 'ชื่อหน่วยรับรอง'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                     {!! Form::text('name_standard', (!empty($tracking->name_standard) ? $tracking->name_standard  : null) , ['id' => 'name_standard', 'class' => 'form-control', 'placeholder'=>'', 'disabled' => true]); !!}
                    </div>
           </div>
           <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
                    {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span>  ชื่อคณะผู้ตรวจประเมิน'.' :', ['class' => 'col-md-5 control-label'])) !!}
                    <div class="col-md-7">
                           {!! Form::text('auditor',null, ['id' => 'auditor', 'class' => 'form-control' , 'maxlength' => '255', 'placeholder'=>'', 'required' => true]); !!}
                    </div>
           </div> --}}
          
           <div class="form-group {{ $errors->has('reference_refno') ? 'has-error' : '' }}">
            <label for="reference_refno" class="col-md-5 control-label">เลขที่อ้างอิง :</label>
            <div class="col-md-7">
                @if(!empty($certiCb->app_no))
                    <input type="text" id="reference_refno" name="reference_refno" class="form-control" value="{{ $certiCb->app_no }}" disabled>
                    <input type="hidden" id="cb_id" name="cb_id" class="form-control" value="{{ $certiCb->id ?? '' }}">
                @else
                    <input type="text" id="reference_refno" name="reference_refno" class="form-control no-drop" readonly>
                @endif
            </div>
        </div>
        
        
        <div class="form-group">
            <label for="auditor" class="col-md-5 control-label">
                <span class="text-danger">*</span> ชื่อผู้ยื่นคำขอ : 
            </label>
            <label  class="col-md-7 control-label" style="text-align: left">
                <span class="text-danger"> </span> {{$certiCb->EsurvTrader->name}}
            </label>
     
        </div>

        
        <div class="form-group">
            <label for="auditor" class="col-md-5 control-label">
                <span class="text-danger">*</span> ชื่อหน่วยรับรอง : 
            </label>
            <label  class="col-md-7 control-label" style="text-align: left">
                <span class="text-danger"> </span> {{$certiCb->name_standard}}
            </label>
     
        </div>

        <div class="form-group">
            <label for="auditor" class="col-md-5 control-label">
                <span class="text-danger">*</span> ชื่อคณะผู้ตรวจประเมิน : 
            </label>
            <label  class="col-md-7 control-label" style="text-align: left">
                <span class="text-danger"> </span> {{$cbDocReviewAuditor->team_name}}
            </label>
     
        </div>
        
        <div class="form-group">
            <label for="auditor" class="col-md-5 control-label">
                <span class="text-danger">*</span> วันที่ตรวจประเมิน : 
            </label>
            <label  class="col-md-7 control-label" style="text-align: left">
                <span class="text-danger"> </span> {{ HP::DateThai($cbDocReviewAuditor->from_date) }} ถึง {{ HP::DateThai($cbDocReviewAuditor->to_date) }}
            </label>
     
        </div>



        <div class="form-group">
            <label for="auditor" class="col-md-5 control-label">
                <span class="text-danger">*</span> ผู้ตรวจประเมิน : 
            </label>
            <label  class="col-md-7 control-label" style="text-align: left">
                <span class="text-danger"> </span> @if ($cbDocReviewAuditor->type == 1)
                ตรวจประเมิน ณ สถานประกอบการ
                @elseif($cbDocReviewAuditor->type == 2)
                ตรวจประเมิน ณ สมอ.
                @endif
            </label>
     
        </div>
        
        



            
            <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}" id="attachement_wrapper" style="display: none;">
                <label for="attach" class="col-md-5 control-label">
                    <span class="text-danger">*</span> กำหนดการตรวจประเมิน
                </label>
                <div class="col-md-7">
                    @if (!empty($auditor->FileAuditors2) &&  $auditor->FileAuditors2 != '')
                        <p id="deleteFlieAttach">
                            <a href="{{ url('funtions/get-view/' . $auditor->FileAuditors2->url . '/' . (!empty($auditor->FileAuditors2->filename) ? $auditor->FileAuditors2->filename : basename($auditor->FileAuditors2->new_filename))) }}" target="_blank">
                                {{ HP::FileExtension($auditor->FileAuditors2->filename) ?? '' }}
                            </a>
                            <button class="btn btn-danger btn-xs deleteFlie {{ ($auditor->vehicle == 1 || $auditor->status_cancel == 1) ? 'hide' : '' }}" type="button" onclick="deleteFlieAttach({{ $auditor->FileAuditors2->id }})">
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
                                <input type="file" name="attach" class="check_max_size_file">
                            </span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                    @endif
                </div>
            </div>

         </div>
    </div>
</div>
<div class="row" style="margin-top: 20px">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">

        <table  class="table color-bordered-table primary-bordered-table">
            <thead>
                    <tr>
                        <th width="5%" >ลำดับ</th>
                        <th width="35%">ชื่อผู้ตรวจประเมิน</th>
                        <th width="25%">หน่วยงาน</th>
                        <th width="25%">สถานะผู้ตรวจประเมิน</th>
                    </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @foreach($doc_review_auditors as $doc_review_auditor)
                    @foreach($doc_review_auditor['temp_users'] as $index => $user)
                        <tr>
                            <td>{{ $count }}</td> 
                            <td>{{ $user }}</td>
                            <td>{{ $doc_review_auditor['temp_departments'][$index] !== 'ไม่มีรายละเอียดหน่วยงานโปรดแก้ไข' ? $doc_review_auditor['temp_departments'][$index] : '' }}</td>
                            <td>
                                @if (HP::cbDocAuditorStatus($doc_review_auditor['status']) != null)
                                    {{ HP::cbDocAuditorStatus($doc_review_auditor['status'])->title }}
                                @endif
                            </td>
                        </tr>
                        @php $count++; @endphp
                    @endforeach
                @endforeach

            
            </tbody>
        </table>

    </div>
</div>

@if ($certiCb->cbDocReviewAuditor->status == 2)
    <div class="row" style="margin-top: 20px">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <label>ผู้ประกอบการไม่เห็นชอบคณะตรวจประเมินเอกสาร เหตุผล:</label>
            <div class="form-group" style="padding:15px">
                <label  class="col-md-12 control-label" style="text-align: left">
                    <span class="text-danger">  {{ $certiCb->cbDocReviewAuditor->remark_text }} </span>
                </label>
            </div>

            <div class="form-group"  style="margin-top: 20px">
                <div class="col-md-offset-4 col-md-4">
                    <button class="btn btn-danger" type="button" id="cancel_doc_review_team" >
                        <i class="fa fa-paper-plane"></i> ยกเลิกและแต่งตั้งอีกครั้ง
                    </button>
                </div>
            </div>
        </div>
    </div>

@else
    <div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <legend><h4>  คู่มือคุณภาพและขั้นตอนการดำเนินงาน
                </h4>
                </legend>
                <div class="clearfix"></div>
                @if ($certiCb->FileAttach1->count() > 0)
                    <div class="row">
                        @foreach($certiCb->FileAttach1 as $data)
                            @if ($data->file)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12 text-light">  
                                            <a href="{{url('certify/check/file_cb_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                                {!! HP::FileExtension($data->file)  ?? '' !!}
                                                {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <legend><h4>  รายชื่อคุณวุฒิประสบการณ์และขอบข่ายความรับผิดชอบของเจ้าหน้าที่ (List of relevant personnel providing name, qualification, experience and responsibility)
                </h4>
                </legend>
                <div class="clearfix"></div>
                @if ($certiCb->FileAttach2->count() > 0)
                    <div class="row">
                        @foreach($certiCb->FileAttach2 as $data)
                            @if ($data->file)
                                <div class="col-md-12 form-group">
                                    <div class="col-md-4 text-light"> </div>
                                    <div class="col-md-6 text-light">
                                            <a href="{{url('certify/check/file_cb_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                                {!! HP::FileExtension($data->file)  ?? '' !!}
                                                {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                            </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <legend><h4>  ขอบข่ายที่ยื่นขอรับการรับรอง (Scope of Accreditation Sought)
                </h4>
                </legend>
                <div class="clearfix"></div>
                    @if ($certiCb->FileAttach3->count() > 0)
                    <div class="row">
                        @foreach($certiCb->FileAttach3 as $data)
                            @if ($data->file)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12 text-light">
                                            <a href="{{url('certify/check/file_cb_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file) ))}}" target="_blank">
                                                {!! HP::FileExtension($data->file)  ?? '' !!}
                                                {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                            </a> 
                                        </div> 
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <div class="row" style="margin-top: 20px">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <legend><h4>  เอกสารอื่นๆ (Others)
                </h4>
                </legend>
                <div class="clearfix"></div>
                    @if ($certiCb->FileAttach4->count() > 0)
                    <div class="row">
                        @foreach($certiCb->FileAttach4 as $data)
                            @if ($data->file)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12 text-light">
                                            {{-- {{  @$data->file_desc }} --}}
                                            <a href="{{url('certify/check/file_cb_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :   basename($data->file)  ))}}" target="_blank">
                                                {!! HP::FileExtension($data->file)  ?? '' !!}
                                                {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="form-group" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-offset-2  col-md-7" style="margin-top: 20px">
                <hr>
                <div class="col-md-6" >
                    <input type="radio" name="result" value="2" class="check" data-radio="iradio_square-blue" id="pass" checked>
                    <label for="offsite" class="control-label">ผ่านการประเมินเอกสาร</label>
                </div>
                <div class="col-md-6">
                    <input type="radio" name="result" value="1" class="check" data-radio="iradio_square-blue" id="reject">
                    <label for="onsite" class="control-label">ให้แก้ไข</label>
                </div>
                <div class="col-sm-12" id="text-area-wrapper" style="margin-top: 20px;display: none;">
                    <label> หมายเหตุ : </label>
                    <textarea class="form-control" name="doc_reject_detail" id="doc_reject_detail" rows="4" ></textarea>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 50px">
            
            <div class="col-md-offset-4 col-md-4">
                <button type="button" class="btn btn-primary" id="save_doc_review"  >
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
         
                <a class="btn btn-default" href="{{  app('url')->previous() }}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            </div>
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
 var certiCb = null;
          $(document).ready(function () {
            certiCb = @json($certiCb ?? []);
            function toggleAttachmentWrapper() {
                if ($("input[name='assessment_type']:checked").val() === "1") {
                    $("#attachement_wrapper").show();  // แสดงเมื่อเลือก onsite
                    $("input[name='attach']").prop("required", true);  // เพิ่ม required
                } else {
                    $("#attachement_wrapper").hide();  // ซ่อนเมื่อเลือก offsite
                    $("input[name='attach']").prop("required", false);  // ลบ required
                }
            }

            // ตรวจสอบสถานะเมื่อโหลดหน้า
            toggleAttachmentWrapper();

            // ใช้ "ifChanged" สำหรับ iCheck
            $("input[name='assessment_type']").on("ifChanged", function () {
                toggleAttachmentWrapper();
            });

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
                      let input = $('<input type="hidden" class="user_id" name="list[user_id]['+status+'][]" value="'+user_id+'"><input type="text" class="form-control temp_users" name="list[temp_users]['+status+'][]" value="'+val+'" readonly>');
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
                              url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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
                              url: "{!! url('/certificate/tracking-cb/delete_file') !!}"  + "/" + id
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


           
    $('#cancel_doc_review_team').click(function(){
   
        const _token = $('input[name="_token"]').val();
        let certiCbId = $('#cb_id').val();
        
        // ดึงค่าของ radio ที่ถูกเลือก
        let agreeValue = $("input[name='agree']:checked").val();

        // ดึงค่าของ textarea
        let remarkText = $("#remark").val();


        $.ajax({
            url: "{{route('cancel_doc_review_team')}}",
            method: "POST",
            data: {
                certiCbId: certiCbId,
                _token: _token
            },
            success: function(result) {
                let baseUrl = window.location.origin; // ดึง base URL ปัจจุบัน (เช่น https://example.com)
                let redirectUrl = baseUrl + "/certify/check_certificate-cb/" + certiCb.token + "/show/" + certiCb.id;
                window.location.href = redirectUrl; // เปลี่ยนเส้นทางไปยัง URL ที่สร้าง
            }

            });
    
    });



    // ตรวจสอบเมื่อมีการเปลี่ยนแปลงค่าของ radio
    $("input[name='result']").on("ifChanged", function() {
        toggleTextArea();
    });

    function toggleTextArea() {
        if ($("#reject").is(":checked")) {
         
            $("#text-area-wrapper").show(); // แสดง textarea ถ้าเลือก "แก้ไข"
        } else {
            console.log('aka')
            $("#text-area-wrapper").hide(); // ซ่อน textarea ถ้าเลือก "ผ่านการประเมิน"
        }
    }

    $('#save_doc_review').click(function(){
   
        const _token = $('input[name="_token"]').val();
        let certiCbId = $('#cb_id').val();
        // let certiCbId = $('#cb_id').val();
        
        // ดึงค่าของ radio ที่ถูกเลือก
        let agreeValue = $("input[name='result']:checked").val();
        console.log(agreeValue)
        // return;


        if(agreeValue == 1)
        {
            let rejectText = $("#doc_reject_detail").val();
            $.ajax({
                url: "{{route('reject_doc_review')}}",
                method: "POST",
                data: {
                    certiCbId: certiCbId,
                    rejectText: rejectText,
                    _token: _token
                },
                success: function(result) {
                    let baseUrl = window.location.origin; // ดึง base URL ปัจจุบัน (เช่น https://example.com)
                    let redirectUrl = baseUrl + "/certify/check_certificate-cb/" + certiCb.token + "/show/" + certiCb.id;
                    window.location.href = redirectUrl; // เปลี่ยนเส้นทางไปยัง URL ที่สร้าง
                }
            });
        }
        else if(agreeValue == 2)
        {
            $.ajax({
                url: "{{route('accept_doc_review')}}",
                method: "POST",
                data: {
                    certiCbId: certiCbId,
                    _token: _token
                },
                success: function(result) {
                    let baseUrl = window.location.origin; // ดึง base URL ปัจจุบัน (เช่น https://example.com)
                    let redirectUrl = baseUrl + "/certify/check_certificate-cb/" + certiCb.token + "/show/" + certiCb.id;
                    window.location.href = redirectUrl; // เปลี่ยนเส้นทางไปยัง URL ที่สร้าง
                }
            });
        }
        // ดึงค่าของ textarea


        });
           
      </script>
 
 
  @endpush
   
  