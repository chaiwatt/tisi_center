<div class="modal fade" id="CloseModals">
    <div  class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="AssignModalLabel1">ปิดประกาศรับฟังความคิดเห็น</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => '/law/listen/ministry-summary/save_close', 'class' => 'form-horizontal', 'files' => true]) !!}
                    {{ csrf_field() }}
                    <div class="white-box">
                         <div class="row form-group">
                                    {!! HTML::decode(Form::label('status_id_show', 'สถานะ', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                              <div class="col-md-7">
                                    {!! Form::select('status_id', App\Models\Law\Listen\LawListenMinistry::list_status(),3, ['class' => 'form-control  text-center','disabled'=>true, 'id' => 'status_id']); !!}
                             </div>
                         </div>
                         <div class="form-group">
                            {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('created_by_show',auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
                            </div>
                        </div>                        
                        <div class="alert bg-rgba-warning alert-dismissible mb-2" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-alert-circle"></i>
                                <span  class="text-bold-400 text-muted">
                                    หมายเหตุ : หากปิดประกาศรับฟังความคิดเห็นแล้ว จะไม่สามารถบันทึกความคิดเห็นได้
                                </span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="listen_id"  id="close_ids" value="">
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