<br>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-5">เลขที่อ้างอิง :</label>
            <div class="col-md-7">
                <p class="form-control-static div_dotted"> {!! !empty($lawcasesform->ref_no)?$lawcasesform->ref_no:'แสดงอัตโนมัติเมื่อบันทึก' !!} </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-5">วันที่แจ้ง :<div><span class="text-muted  font-15"><i>(ผ่านระบบ)</i></span></div></label>
            <div class="col-md-7">
                <p class="form-control-static div_dotted"> {!! !empty($lawcasesform->created_at)?HP::DateThaiFull($lawcasesform->created_at):HP::DateThaiFull( date('Y-m-d') ) !!} </p>
            </div>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-5">เลขคดี :</label>
            <div class="col-md-7">
                <p class="form-control-static div_dotted"> {!! !empty($lawcasesform->case_number)?$lawcasesform->case_number:'-' !!} </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-5">นิติกร :</label>
            <div class="col-md-7">

                <p class="form-control-static div_dotted">{!! !empty($lawcasesform->lawyer_by)?$lawcasesform->LawyerName:'-' !!}</p>

            </div>
        </div>
    </div>
</div>
<br>
