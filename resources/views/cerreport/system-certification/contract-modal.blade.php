
<div class="modal fade" id="ContractModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ContractModal" aria-hidden="true">
    <div  class="modal-dialog modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="ContractModalTitle">แก้ไขข้อมูลติดต่อ</h4>
            </div>
            <div class="modal-body">
                
                @include('cerreport.system-certification.contract-form')

                <div class="text-right">
                    <button class="btn btn-primary" type="button" id="save-modal">
                        <i class="fa fa-save"></i> อัพเดท
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        ยกเลิก
                    </button>
                </div>
            
            </div>
        </div>
     </div>
 </div>

 