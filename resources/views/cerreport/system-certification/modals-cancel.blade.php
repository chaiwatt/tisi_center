
<div class="modal fade" id="cancelModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
             <div class="modal-header">
             
                 <h4 class="modal-title" id="CloseCaseModalLabel1">ยกเลิกเลขที่ใบรับรอง <span id="certificate_no"></span>
                            <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 </h4>
             </div>
             {!! Form::open(['url' => '/certify/sign-certificates/save_cancel', 'method' => 'POST',  'class' => 'form-horizontal', 'id' => 'form-cancel', 'files' => true]) !!}
 
             <div class="modal-body">
                <div class="row">
                      <div class="col-md-12">
                              <div class="form-group ">
                                 <label class="col-sm-4 text-right required">หมายเหตุยกเลิก : </label>
                                 <div class="col-sm-7">
                                         {!! Form::textarea('remark', null, ['class' => 'form-control','required' => true, 'rows'=>'3','id'=>'remark']) !!}
                                  </div>
                              </div>
                     </div>
    
                 </div> 
                 {!! Form::hidden('certificate_type', null, ['id'=>'certificate_type'] ) !!}
                 {!! Form::hidden('certificate_id', null, ['id'=>'certificate_id'] ) !!}
             </div>
             <div class="modal-footer ">
                <div class="text-center">
                            <button type="submit" id="edit_save_evidence" class="btn btn-primary">ยืนยัน</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                            </button>
                </div>
        
             </div>
             {!! Form::close() !!}
         </div>
     </div>
 </div>
 
