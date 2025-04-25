<div class="modal fade" id="SendShowModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="SendShowModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > 
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 <h4 class="modal-title" id="SendShowModalLabel1">แนบหลักฐานแล้ว</h4>
             </div>
             <div class="modal-body form-horizontal">
    
                    <div class="form-group m-0" id="div_attach">
                        <label class="control-label col-md-4  font-medium-6 ">ไฟล์แนบ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static  font-medium-6 " id="p_attach"></p>
                        </div>
                    </div>
 
                    <div class="form-group m-0">
                        <label class="control-label col-md-4  font-medium-6 ">หมายเหตุ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static  font-medium-6 " id="remark"></p>
                        </div>
                    </div>
 
                    <div class="form-group m-0">
                        <label class="control-label col-md-4  font-medium-6 ">ผู้บันทึก :</label>
                        <div class="col-md-8">
                            <p class="form-control-static  font-medium-6 " id="fullname"></p>
                        </div>
                    </div>
                    <div class="form-group m-0">
                        <label class="control-label col-md-4  font-medium-6 ">วันที่บันทึก :</label>
                        <div class="col-md-8">
                            <p class="form-control-static  font-medium-6 " id="send_date"></p>
                        </div>
                    </div>
 

                     <div class="text-right ">
                         <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                             {!! __('ยกเลิก') !!}
                         </button>
                     </div>
               
             </div>
         </div>
     </div>
 </div>

 