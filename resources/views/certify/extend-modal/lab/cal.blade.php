
{{-- modal test scope --}}

<div class="modal fade" id="modal-add-cal-scope">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">
                    <span id="scope-modal-title">เพิ่มขอบข่ายห้องปฏิบัติการสอบเทียบ</span>  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
                <h5>
                  <span class="text-danger">*โปรดทราบ!! ถ้าไม่พบขอบข่ายที่ต้องการ โปรดติดต่อเจ้าหน้าที่เพื่อเพิ่มเติมขอบข่าย ==></span> <span><a href="{{url('certify/scope-request/lab-scope-request')}}" target="_blank">ขอเพิ่มขอบข่าย</a></span>
                </h5>
            </div>
            <div class="modal-body">
                <fieldset class="white-box">
                    <div class="row" id="select_wrapper">
                        <div class="col-md-4 form-group ">
                            <label for="">สาขาการทดสอบ</label>
                            <select  class="form-control" name="" id="cal_main_branch">
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">หมวดหมู่เครื่องมือ</label>
                            <select  class="form-control" name="" id="cal_instrumentgroup">
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="cal_instrument_wrapper">
                            <label for="">เครื่องมือ</label>
                            <select  class="form-control" name="" id="cal_instrument">
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="cal_parameter_one_wrapper">
                            <label for="">พารามิเตอร์1</label>
                            <select  class="form-control" name="" id="cal_parameter_one">
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="cal_parameter_two_wrapper">
                            <label for="">พารามิเตอร์2</label>
                            <select  class="form-control" name="" id="cal_parameter_two">
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                       
                        <div class="col-md-12 form-group">
                            <button type="button" class="btn btn-success pull-right ml-2" id="button_add_cal_scope">
                                <span aria-hidden="true">เพิ่ม</span>
                            </button>
                           
                        </div>
                        <table class="table table-bordered" id="myTable_lab_cal_scope">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-center text-white "  width="15%">สาขาทดสอบ</th>
                                    <th class="text-center text-white "  width="15%">หมวดหมู่เครื่องมือ</th>
                                    <th class="text-center text-white "  width="15%">เครื่องมือ</th>
                                    <th class="text-center text-white "  width="15%">พารามิเตอร์1</th>
                                    <th class="text-center text-white "  width="15%">พารามิเตอร์2</th>
                                    <th class="text-center text-white "  width="20%">วิธีสอบเทียบ</th>
                                    <th class="text-center text-white "  width="5%">ลบ</th>
                                </tr>
                            </thead>
                            <tbody id="lab_cal_scope_body">
                        
                            </tbody>
                        </table>
                    </div>
                       
                </fieldset>
            </div>
        </div>
    </div>
</div>
{{-- modal scope --}}


{{-- modal parameter one --}}
<div class="modal fade" id="modal-add-parameter-one">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="scope-modal-title">ช่วงการสอบเทียบของพารามิเตอร์1:</span>  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <label for="how_test_detail">ช่วงการสอบเทียบของพารามิเตอร์1: </label> --}}
                        <textarea id="parameter_one_textarea" class="form-control"></textarea>
                    </div>         
                </div>
                <div class="row">
                    <div class="col-md-9 ">
                        <small class="text-danger">* กรอกบรรทัดละ 1 รายการ เช่น 
                            <ul>
                                <li>20V to 50V</li>
                                <li>10A to 30A</li>
                            </ul>
                           </small>
                    </div>
                    <div class="col-md-3 ">
                        <button type="button" class="btn btn-success pull-right " id="button_add_parameter_one">
                            <span aria-hidden="true">เพิ่ม</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal parameter one --}}

{{-- modal parameter two --}}
<div class="modal fade" id="modal-add-parameter-two">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="scope-modal-title">ช่วงการสอบเทียบของพารามิเตอร์2:</span>  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <label for="how_test_detail">ช่วงการสอบเทียบของพารามิเตอร์2: </label> --}}
                        <textarea id="parameter_two_textarea" class="form-control"></textarea>
                    </div>         
                </div>
                <div class="row">
                    <div class="col-md-9 ">
                        <small class="text-danger">* กรอกบรรทัดละ 1 รายการ เช่น 
                            <ul>
                                <li>20V to 50V</li>
                                <li>10A to 30A</li>
                            </ul>
                           </small>
                    </div>
                    <div class="col-md-3 ">
                        <button type="button" class="btn btn-success pull-right " id="button_add_parameter_two">
                            <span aria-hidden="true">เพิ่ม</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal parameter two --}}

{{-- modal cal method --}}
<div class="modal fade" id="modal-add-cal-method">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="scope-modal-title">เพิ่มวิธีการสอบเทียบ:</span>  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <label for="how_test_detail">ช่วงการสอบเทียบของพารามิเตอร์2: </label> --}}
                        <textarea id="cal_method_textarea" class="form-control"></textarea>
                    </div>         
                </div>
                <div class="row">
                    <div class="col-md-9 ">
                        <small class="text-danger">* กรุณาอธิบายวิธีสอบเทียบ
                           </small>
                    </div>
                    <div class="col-md-3 ">
                        <button type="button" class="btn btn-success pull-right " id="button_add_cal_method">
                            <span aria-hidden="true">เพิ่ม</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal cal method --}}

{{-- modal show cal scope --}}

<div class="modal fade" id="modal-show-cal-scope">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">
                    <span id="scope-modal-title">รายการขอบข่ายปรับปรุง <span id="created_at"></span>  </span>  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                    <div class="col-md-12 text-left" id="show_cal_scope_wrapper">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal show cal scope --}}