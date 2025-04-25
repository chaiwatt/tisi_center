<div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!!  !empty($cases->StatusText)   ? $cases->StatusText : null  !!} </div>

<fieldset class="white-box">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-2">
                <label class="control-label col-md-5">เลขคดี :</label>
                <div class="col-md-7">
                    {!! Form::text('',  !empty($cases->case_number)   ? $cases->case_number : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                </div>
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-2">
                <label class="control-label col-md-5">นิติกร :</label>
                <div class="col-md-7">
                     {!! Form::text('',  !empty($cases->user_lawyer_to->FullName)   ? $cases->user_lawyer_to->FullName : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                </div>
            </div>
        </div>
    </div>  

    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-2">
                <label class="control-label col-md-5">เลขที่อ้างอิงแจ้ง :</label>
                <div class="col-md-7">
                    {!! Form::text('',  !empty($cases->ref_no)   ? $cases->ref_no : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-2">
                <label class="control-label col-md-5">วันที่แจ้ง :<div><span class="text-muted  font-15"><i>(ผ่านระบบ)</i></span></div></label>
                <div class="col-md-7">
                    {!! Form::text('',  !empty($cases->created_at) ?  HP::DateThaiFull($cases->created_at) : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                </div>
            </div>
        </div>
    </div>
</fieldset>