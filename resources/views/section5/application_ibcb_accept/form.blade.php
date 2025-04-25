@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
<link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .div_dotted {
        border-top: none ;
        border-right: none ;
        border-bottom: 1px dotted;
        border-left: none ;
    }
    .bootstrap-tagsinput > .label {
        line-height: 2.3;
    }
    .bootstrap-tagsinput {
        min-height: 42px;
        border-radius: 0;
        width: 100% !important;
    }
    .bootstrap-tagsinput input {
        padding: 6px 6px;
    }
    .table td.text-ellipsis {
        max-width: 177px;
    }
    .table td.text-ellipsis a {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 90%;
    }
</style>
@endpush

    <div class="row">
        <div class="col-md-offset-1  col-md-10 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="text-center">
                        <h3 style="color: black">คำขอรับการแต่งตั้ง</h3>
                        <h3 style="color: black">ผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>
                        <h3 style="color: black">ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511 และที่แก้ไขเพิ่มเติม</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:25px"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! $applicationIbcb->application_no !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationIbcb->ApplicationDateFull)?$applicationIbcb->ApplicationDateFull:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationIbcb->AcceptDateFull)?$applicationIbcb->AcceptDateFull:'-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationIbcb->accept_by)?$applicationIbcb->AcceptName:'-' !!}</p>
        </div>
    </div>
</div>
<br>
<div class="clearfix"></div>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationIbcb->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @include ('section5/application-request-form.application-ibcb')
                </div>
            </div>
        </div>
    </div>
</div>

@if( ( (isset( $applicationIbcb->edited ) && $applicationIbcb->edited == true  || isset( $applicationIbcb->show ) && $applicationIbcb->show == true ) ) && ($applicationIbcb->application_ibcb_accepts()->count() > 0) )
    @include ('section5.application-request-form.history.application-ibcb')
@endif

@if( isset( $applicationIbcb->edited ) && $applicationIbcb->edited == true  || isset( $applicationIbcb->show ) && $applicationIbcb->show == true )
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ตรวจสอบคำขอ # {!! $applicationIbcb->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">

                        <fieldset class="white-box">
                            <legend class="legend"><h3>ตรวจสอบคำขอ</h3></legend>

                            @php
                                $ibcb_accept = $applicationIbcb->application_ibcb_accepts->last();
                            @endphp

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group required{{ $errors->has('application_status') ? 'has-error' : ''}}">
                                        {!! Form::label('application_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            @php
                                                $status_ibcbs = App\Models\Section5\ApplicationIbcbStatus::whereIn('id', [2,3,4,6])->pluck('title', 'id');
                                            @endphp
                                            {!! Form::select('application_status', $status_ibcbs, !empty( $ibcb_accept->application_status )?$ibcb_accept->application_status:null,['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                                            {!! $errors->first('application_status', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered scope-results" id="table-scope">
                                        <caption>เลือกสาขา/รายสาขาที่ผ่านการตรวจ</caption>
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5%">รายการที่</th>
                                                    <th class="text-center" width="25%">สาขาผลิตภัณฑ์</th>
                                                    <th class="text-center" width="25%">รายสาขา</th>
                                                    <th class="text-center" width="20%">ISIC NO</th>
                                                    <th class="text-center" width="25%">มาตรฐาน มอก. เลขที่</th>
                                                </tr>
                                            </thead>
                                            <tbody data-repeater-list="repeater-scope" class="text-left text-top">
                                                @if( isset($applicationIbcb->id) )
                                                    @php
                                                        $scope = App\Models\Section5\ApplicationIbcbScope::where('application_id', $applicationIbcb->id)
                                                                                                         ->with('ibcb_scopes_tis')
                                                                                                         ->get();
                                                    @endphp

                                                    @foreach ( $scope as $ks => $Iscope )
                                                        @php
                                                            $bs_branch_group = $Iscope->bs_branch_group;
                                                            $scopes_ties =  $Iscope->ibcb_scopes_tis;

                                                            $tis_details = [];
                                                            if(count($scopes_ties) > 0){
                                                                foreach ($scopes_ties as $scopes_tie) {
                                                                    $branch_title             = !empty($scopes_tie->application_ibcb_scope_detail->bs_branch->title) ? $scopes_tie->application_ibcb_scope_detail->bs_branch->title : '' ;
                                                                    $scopes_tie->branch_title = $branch_title;
                                                                    $tis_details[] = $scopes_tie;
                                                                }
                                                            }
                                                            $tis_details = json_encode($tis_details);

                                                            $scopes_details = $Iscope->scopes_details()->select('branch_id', 'id', 'audit_result')->get();
                                                        @endphp
                                                        <tr data-repeater-item>
                                                            <td class="no text-center">{!! $ks+1 !!}</td>
                                                            <td>
                                                                {!! !is_null($bs_branch_group)?$bs_branch_group->title:null !!}
                                                                <input type="hidden" class="branch_group_id" name="branch_group_id" value="{!! !empty($Iscope->branch_group_id)?$Iscope->branch_group_id:null  !!}">
                                                                <input type="hidden" name="scope_id" value="{!! !empty($Iscope->id)?$Iscope->id:null  !!}">
                                                            </td>
                                                            <td>
                                                                <ul class="list-unstyled">
                                                                    @foreach (  $scopes_details as $branchs )
                                                                        @php
                                                                                $bs_branch = $branchs->bs_branch;
                                                                        @endphp
                                                                        <li data-repeater-item>
                                                                            <input type="hidden" name="detail_id" value="{!!  $branchs->id  !!}">
                                                                            <input type="hidden" name="branch_id" value="{!!  $branchs->branch_id  !!}">
                                                                            <label for="checkbox_branch_{!!  $branchs->branch_id  !!}"><input class="form-control check" data-checkbox="icheckbox_flat-green" type="checkbox" id="checkbox_branch_{!!  $branchs->branch_id  !!}" name="audit_result" value="1" {!! ($branchs->audit_result == 1)?'checked':'' !!}> {!! !is_null($bs_branch)?$bs_branch->title:null !!}</label>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                {!! !empty($Iscope->isic_no)?$Iscope->isic_no:'-'  !!}
                                                                <input type="hidden" name="isic_no" value="{!! !empty($Iscope->isic_no)?$Iscope->isic_no:null  !!}">
                                                            </td>
                                                            <td class="text-ellipsis">
                                                                <a class="open_scope_branches_tis_details" href="javascript:void(0)" title="คลิกดูรายละเอียด">{{ $Iscope->TisNumberComma }}</a>
                                                                <input type="hidden" class="tis_details" value="{!! base64_encode($tis_details)  !!}">
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                                        {!! Form::label('description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('description', !empty( $ibcb_accept->description )?$ibcb_accept->description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $style_show = (!empty($ibcb_accept) && $ibcb_accept->application_status==3)?'':'display:none';
                            @endphp

                            <div class="row repeater-form-date">
                                <div class="col-md-9" data-repeater-list="repeater-date">

                                    @if( !is_null($ibcb_accept) && !empty($ibcb_accept->appointment_date) )
                                        @php
                                            $appointment_date = json_decode($ibcb_accept->appointment_date,true);
                                        @endphp
                                        @foreach ( $appointment_date as $item )
                                            <div class="form-group" data-repeater-item>
                                                {!! Form::label('appointment_date', 'วันที่นัดเข้าตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::text('appointment_date', !empty($item)?HP::revertDate($item,true):null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
                                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                        {!! $errors->first('appointment_date', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger btn_date_remove pull-right" data-repeater-delete type="button">
                                                        ลบ
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-group" data-repeater-item>
                                            {!! Form::label('appointment_date', 'วันที่นัดเข้าตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    {!! Form::text('appointment_date', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
                                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                    {!! $errors->first('appointment_date', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger btn_date_remove pull-right" data-repeater-delete type="button">
                                                    ลบ
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success pull-left" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {!! Form::label('send_mail_status', ' ', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <label  class="col-md-6" >
                                                {!! Form::radio('send_mail_status', '0', !is_null( $ibcb_accept ) &&  $ibcb_accept->send_mail_status == 0 ?true:( empty( $ibcb_accept->send_mail_status )?true:false ), ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                                <label for="">ไม่ส่งอีเมลแจ้งผล</label>
                                                </label>
                                                <label  class="col-md-6">
                                                {!! Form::radio('send_mail_status', '1', !is_null( $ibcb_accept ) &&  $ibcb_accept->send_mail_status == 1 ?true:false , ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                                <label for="">ส่งอีเมลแจ้งผล</label>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row box_noti_email">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {!! Form::label('noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('noti_email', !empty( $ibcb_accept->noti_email )?implode(',',json_decode($ibcb_accept->noti_email,true)):(!empty( $applicationIbcb->co_email )?$applicationIbcb->co_email:null) ,  ['class' => 'form-control noti_email', 'data-role' => "tagsinput", 'disabled' => true ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif




<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        @can('view-'.str_slug('application-ibcb-accept'))
            <button class="btn btn-primary show_tag_a" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
        @endcan
        <a class="btn btn-default show_tag_a" href="{{url('/section5/application_ibcb_accept')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>

@include ('section5.application_ibcb_accept.modal-scope-branches-tis-details')

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<!-- tagsinput -->
<script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script>
    jQuery(document).ready(function() {

        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy'
        });

        $('.repeater-form').repeater({
            show: function () {
                $(this).slideDown();
                // resetOrderNoFile();
            },
            hide: function (deleteElement) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).slideUp(deleteElement);

                    // setTimeout(function(){
                        // resetOrderNoFile();
                    // }, 400);
                }
            }
        });

        $('.repeater-form-date').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });

        $('.scope-results').repeater();

        $('.send_mail_status').on('ifChecked', function(event){
            noti_email();
        });
        noti_email();

        $('.iradio_flat-blue').removeClass('disabled');

        $('#box-readonly').find('button[type="submit"]').remove();
        $('#box-readonly').find('.icon-close').parent().remove();
        $('#box-readonly').find('.fa-copy').parent().remove();
        $('#box-readonly').find('input').prop('disabled', true);
        $('#box-readonly').find('textarea').prop('disabled', true);
        $('#box-readonly').find('select').prop('disabled', true);
        $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
        $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
        $('#box-readonly').find('button:not(.not_remove)').prop('disabled', true);
        $('#box-readonly').find('button:not(.not_remove)').remove();
        $('#box-readonly').find('.btn-remove-file').parent().remove();
        $('#box-readonly').find('.show_tag_a').hide();
        $('#box-readonly').find('.input_show_file').hide();
        $('#box-readonly').find('input').prop('required', false);
        $('#box-readonly').find('textarea').prop('required', false);
        $('#box-readonly').find('select').prop('required', false);

        resetOrderNo();

        $('#application_status').change(function(){
            application_status_change();
        });
        application_status_change();

    });

    function noti_email(){
        let send_mail_status = $('.send_mail_status:checked').val();
            let box_noti_email = $('.box_noti_email');
        if(send_mail_status == 1){
            box_noti_email.find('.noti_email').prop('disabled', false);
            box_noti_email.find('.noti_email').prop('required', true);
            box_noti_email.show();
        }else{
            box_noti_email.find('.noti_email').prop('disabled', true);
            box_noti_email.find('.noti_email').prop('required', false);
            box_noti_email.hide();
        }
    }

    function application_status_change(){

        var box_date = $('.repeater-form-date');
        var box_scope = $('.scope-results');

        if($('#application_status').val() == 3){//เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน
            box_date.show();
            box_date.find('input').prop('disabled', false);
        }else{
            box_date.hide();
            box_date.find('input').prop('disabled', true);
        }

        if($('#application_status').val() == 4){//เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน
            box_scope.show();
            box_scope.find('input').prop('disabled', false);
        }else{
            box_scope.hide();
            box_scope.find('input').prop('disabled', true);
        }

    }

    function resetOrderNo(){
        $('.branch_no').each(function(index, el) {
            $(el).text(index+1+'.');
        });
    }

</script>
@endpush
