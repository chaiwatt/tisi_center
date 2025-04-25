<div class="modal fade" id="LogModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 <h4 class="modal-title" id="CloseCaseModalLabel1"><span id="span_title"></span></h4>
             </div>
             <div class="modal-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="table">
                                <table class="table table-striped"  >
                                    <thead>
                                    <tr>
                                        <th class="text-center"  width="2%">#</th>
                                        <th class="text-center"  width="20%">ไฟล์เดิม</th>
                                        <th class="text-center"  width="20%">ไฟล์ใหม่</th>
                                        <th class="text-center"  width="20%">ผู้บันทึก</th>
                                        <th class="text-center"  width="20%">วันที่/เวลา</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_tbody_close">

                                    </tbody>
                                </table>
                            </div>
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

 