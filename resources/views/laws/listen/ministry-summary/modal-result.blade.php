<div class="modal fade" id="ResultModals">
    <div  class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="AssignModalLabel1">แจ้งผลวินิจฉัย</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => '/law/listen/ministry-summary/save_result', 'class' => 'form-horizontal', 'files' => true]) !!}
                    {{ csrf_field() }}
                    <div class="white-box">
                         <div class="row form-group">
                                    {!! HTML::decode(Form::label('status_diagnosis', 'ผลวินิจฉัย', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                              <div class="col-md-7">
                                    {!! Form::select('status_diagnosis',App\Models\Law\Listen\LawListenMinistry::list_status_diagnosis(), null, ['class' => 'form-control  text-center', 'id' => 'status_diagnosis']); !!}
                             </div>
                         </div>
                         <div class="form-group required{{ $errors->has('date_diagnosis') ? 'has-error' : ''}}">
                            {!! Form::label('date_diagnosis', 'วันที่วินิจฉัย'.':', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-4">
                                <div class="inputWithIcon">
                                    {!! Form::text('date_diagnosis', null, ('required' == 'required') ? ['class' => 'form-control date-range',
                                    'required' => 'required', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] : ['class' => 'form-control date-range', 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off']) !!}
                                    {!! $errors->first('date_diagnosis', '<p class="help-block">:message</p>') !!}
                                     <i class="icon-calender"></i>
                                </div>
                            </div>
                        </div>
                    <div class="row form-group required">
                        {!! Form::label('file_result', 'หนังสือแจ้งผล'.':', ['class' => 'col-md-3 control-label text-right']) !!}
                      <div class="col-md-7">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="file_result" class="check_max_size_file" required>
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                     </div>
                    </div>
                    <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                        {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            <input type="checkbox" class="check send_mail" id="send_mail"  value="1" name="mail_status_diagnosis" data-checkbox="icheckbox_square-green">
                                <label for="send_mail">แจ้งเตือนไปยังอีเมลผู้แสดงความคิดเห็น</label>      
                                <button type="button" class="btn btn-link " id="btn_send_mail">
                                 <small style = "position:absolute; left:400px; top:20px; "> ซ่อน | ดูทั้งหมด</small>
                                </button>
                        </div>
                    </div> 
                    <div class="form-group box_mail_list{{ $errors->has('mail_list') ? 'has-error' : ''}}"id="box_mail_list" style="display:none;">
                        {!! HTML::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-6">
                                {!! Form::text('mail_list_diagnosis', null,  ['class' => 'form-control tag', 'id'=>'mail_list', 'data-role' => "tagsinput"]) !!}
                            </div>
                    </div>
                </div>
                    <input type="hidden" name="listen_id"  id="result_ids" value="">
                    <div class="text-center">
                        <button type="submit"class="btn btn-primary" ><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>