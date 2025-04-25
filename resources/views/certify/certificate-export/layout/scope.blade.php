<div class="white-box">
    <h4>รายละเอียด</h4>

    <div class="form-group">
        @php $name = 'certificate_no_scope' ; $text = ($lang == "th" ? 'รายละเอียดแนบท้ายใบรับรองที่': 'Scope of Certificate No') @endphp
        <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{isset($certificate->certificate_no) ? $certificate->certificate_no : null}}">
            {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        @php $name = 'accereditatio_no_scope' ; $text = ($lang == "th" ? 'หมายเลขการรับรองที่': 'Accereditatio No') @endphp
        <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{isset($certificate) ? $certificate->accereditatio_no ?? null : null}}">
            {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div id="lab_statusDiv" class="form-group {{ $errors->has('lab_status') ? 'has-error' : ''}} m-b-20">
        {!! Form::label('lab_status', $lang == 'th' ? 'สถานภาพห้องปฏิบัติการ :':'Laboratory Status :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <div class="row">
                <div class="col-6 col-md-3" style="margin-top: 7px;">
                    <input class="checkbox-info"  type="checkbox" name="scope_permanent" {{isset($certificate) ? $certificate->scope_permanent == 1 ? 'checked': null : null}}>  {{$lang == 'th' ? 'ถาวร':'Permanent'}}
                </div>
                <div class="col-6 col-md-3" style="margin-top: 7px;">
                    <input class="checkbox-info"  type="checkbox" name="scope_site" {{isset($certificate) ? $certificate->scope_site == 1 ? 'checked': null : null}}> &nbsp;{{$lang == 'th' ? 'นอกสถานที่':'Site'}}
                </div>
                <div class="col-6 col-md-3" style="margin-top: 7px;">
                    <input class="checkbox-info"  type="checkbox" name="scope_temporary" {{isset($certificate) ? $certificate->scope_temporary == 1 ? 'checked': null : null}}> &nbsp;{{$lang == 'th' ? 'ชั่วคราว':'Temporary'}}
                </div>
                <div class="col-6 col-md-3" style="margin-top: 7px;">
                    <input class="checkbox-info"  type="checkbox" name="scope_mobile" {{isset($certificate) ? $certificate->scope_mobile == 1 ? 'checked': null : null}}> &nbsp;{{$lang == 'th' ? 'เคลื่อนที่':'Mobile'}}
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        @php $name = 'issue_no' ; $text = ($lang == "th" ? 'ฉบับที่': 'Issue No') @endphp
        <label for="requestNumber" class="col-md-4 control-label">{{$text}} :</label>
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="{{$text}}" name="{{$name}}" id="{{$name}}" value="{{isset($certificate) ? $certificate->issue_no ?? null : null}}">
            {!! $errors->first($name, '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <hr>

    <table class="table table-borderless" id="tableScope" style="width: 100%;">

    </table>

</div>