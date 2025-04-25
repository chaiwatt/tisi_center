<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">ชื่อผู้ประกอบการ/TAXID :</label>
            <div class="col-md-9">
                {!! Form::text('',  !empty($cases->offend_name) &&  !empty($cases->offend_taxid)   ? $cases->offend_name .' | '.$cases->offend_taxid: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">มอก./ผลิตภัณฑ์ :</label>
            <div class="col-md-9">
                {!! Form::text('',  !empty($cases->StandardNo) ? $cases->StandardNo : '' ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">มาตราความผิด :</label>
            <div class="col-md-3">
                {!! Form::text('',  !empty($cases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$cases->law_cases_result_to->OffenseSectionNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
            <label class="control-label col-md-3">อัตราโทษ :</label>
            <div class="col-md-3">
                {!! Form::text('',  !empty($cases->law_cases_result_to->PunishNumber)   ?  implode(", ",$cases->law_cases_result_to->PunishNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">การจับกุม :</label>
            <div class="col-md-9">
                {!! Form::text('',  !empty($cases->law_basic_arrest)  ? $cases->law_basic_arrest : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row for-show">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">เจ้าของเรื่อง :</label>
            <div class="col-md-9">
                {!! Form::text('',  !empty($cases->owner_name)  ? $cases->owner_name : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
            </div>
        </div>
    </div>
</div>
