
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
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
</style>
@endpush

<fieldset class="white-box">

    <div class="clearfix" style="margin-top:50px"></div>

    <div class="row">
        <div class="col-md-offset-1  col-md-10 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="text-center">
                        <h3 style="color: black">คำขอรับการขึ้นทะเบียน</h3>
                        <h3 style="color: black">ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>
                        <h3 style="color: black">ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511 และที่แก้ไขเพิ่มเติม</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:25px"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right" >เลขที่คำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty( $applicationInspector->application_no )?$applicationInspector->application_no:null !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($applicationInspector->application_date)?HP::DateThaiFull($applicationInspector->application_date):null !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($applicationInspector->accept_date) ? HP::DateThaiFull($applicationInspector->accept_date) : '-' !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 col-sm-12" style="font-size: 16px;">
            <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
            <div class="col-md-2 div_dotted">
                <p>{!! !empty($applicationInspector->accept_by) && !is_null($applicationInspector->accepter) ? $applicationInspector->accepter->FullName : '-' !!}</p>
            </div>
        </div>
    </div>
    <div class="clearfix" style="margin-top:10px"></div>
    @php
        $application_inspectors = $applicationInspector;
    @endphp

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ข้อมูลคำขอ # {!! $application_inspectors->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body" id="box-request">
                        @include ('section5.application-request-form.application-inspectors')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( ( (isset( $applicationInspector->edited ) && $applicationInspector->edited == true  || isset( $applicationInspector->show ) && $applicationInspector->show == true ) ) && ($applicationInspector->inspectors_accepts()->count() > 0) )
        @include ('section5.application-request-form.history.application-inspectors')
    @endif

    @if( isset( $applicationInspector->edited ) && $applicationInspector->edited == true  || isset( $applicationInspector->show ) && $applicationInspector->show == true )

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    ตรวจสอบคำขอ # {!! $application_inspectors->application_no !!}
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body" id="box-request">

                        <fieldset class="white-box">
                            <legend class="legend"><h3>ตรวจสอบคำขอ</h3></legend>

                            @php
                                $accepts = $applicationInspector->inspectors_accepts->last();
                            @endphp

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group required{{ $errors->has('application_status') ? 'has-error' : ''}}">
                                        {!! Form::label('application_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            @php
                                                $status_labs = App\Models\Section5\ApplicationInspectorStatus::whereIn('id', [2, 3, 4, 6])->orWhere('id', $applicationInspector->application_status)->pluck('title', 'id')->toArray();
                                                if(array_key_exists(6, $status_labs)){//เปลี่ยนข้อความ
                                                    $status_labs[6] = 'เอกสารครบถ้วน อยู่ระหว่างการพิจารณาอนุมัติ';
                                                }
                                            @endphp
                                            {!! Form::select('application_status', $status_labs, !empty( $applicationInspector->application_status )?$applicationInspector->application_status:null,['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true ]) !!}
                                            {!! $errors->first('application_status', '<p class="help-block">:message</p>') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row box_scope">
                                <div class="col-md-10 col-md-offset-1">
                                    <table class="table table-bordered" id="myTableScope">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">รายการ</th>
                                                <th width="40%" class="text-center">สาขา</th>
                                                <th width="30%" class="text-center">รายสาขา</th>
                                                <th width="25%" class="text-center">หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @php
                                                $scope_group = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id )->select('branch_group_id')->groupBy('branch_group_id')->get();
                                            @endphp

                                            @foreach ($scope_group as $key => $group)
                                                @php
                                                    $bs_branch_group = $group->bs_branch_group;
                                                @endphp
                                                <tr class="repeater-scope">
                                                    <td class="text-center">{!! $key+1 !!}</td>
                                                    <td>
                                                        {!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $scope = App\Models\Section5\ApplicationInspectorScope::where('application_id', $application_inspectors->id )->where('branch_group_id', $group->branch_group_id )->get();
                                                            $remark = !empty($scope->first())?$scope->first()->remark:null;
                                                        @endphp


                                                        <ul class="list-group list-unstyled" data-repeater-list="repeater-group-{!!  $group->branch_group_id !!}">

                                                            @foreach ( $scope as $scopes )
                                                                @php
                                                                    $bs_branch = $scopes->bs_branch;
                                                                @endphp

                                                                <li data-repeater-item>
                                                                    <input type="hidden" name="scope_id" value="{!!  $scopes->id !!}">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input check" type="checkbox" value="1" id="audit_result-{!!  $scopes->id !!}" name="audit_result" {!! $scopes->audit_result == 1?'checked':''   !!} >
                                                                        <label class="form-check-label" for="audit_result-{!!  $scopes->id !!}">
                                                                            {!! !empty( $bs_branch->title )? $bs_branch->title:null !!}
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        {!! Form::textarea("remark[{$group->branch_group_id}]", $remark, ['class' => 'form-control', 'rows'=>'2', 'cols' => "30"]) !!}
                                                    </td>
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                                        {!! Form::label('description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('description', !empty( $accepts->description )?$accepts->description:null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {!! Form::label('send_mail_status', ' ', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <label  class="col-md-6" >
                                                {!! Form::radio('send_mail_status', '0', !is_null( $accepts ) &&  $accepts->send_mail_status == 0 ?true:( is_null( $accepts )?true:false ), ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                                <label for="">ไม่ส่งอีเมลแจ้งผล</label>
                                                </label>
                                                <label  class="col-md-6">
                                                {!! Form::radio('send_mail_status', '1', !is_null( $accepts ) &&  $accepts->send_mail_status == 1 ?true:false, ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
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
                                            {!! Form::text('noti_email',   !empty( $accepts->noti_email )?implode(',',json_decode($accepts->noti_email,true)):( !empty( $applicationInspector->applicant_email )?$applicationInspector->applicant_email:null) ,  ['class' => 'form-control noti_email', 'data-role' => "tagsinput", 'disabled' => true ]) !!}
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


    <div class="form-group">
        <center>
            @can('view-'.str_slug('application-inspectors-accept'))
                <button class="btn btn-primary show_tag_a" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
            @endcan
            <a class="btn btn-default show_tag_a" href="{{url('/section5/application_inspectors_accept')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        </center>
    </div>

    @endif

</fieldset>


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

        $('.repeater-scope').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });

        $('.send_mail_status').on('ifChecked', function(event){
            noti_email();
        });noti_email();

        $('.iradio_flat-blue').removeClass('disabled');

        $('#box-readonly').find('button[type="submit"]').remove();
        $('#box-readonly').find('.icon-close').parent().remove();
        $('#box-readonly').find('.fa-copy').parent().remove();
        $('#box-readonly').find('input').prop('disabled', true);
        $('#box-readonly').find('input[type="file"]').prop('required', false);
        $('#box-readonly').find('textarea').prop('disabled', true);
        $('#box-readonly').find('select').prop('disabled', true);
        $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
        $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
        $('#box-readonly').find('button').prop('disabled', true);
        $('#box-readonly').find('button').remove();
        $('#box-readonly').find('.btn-remove-file').parent().remove();
        $('#box-readonly').find('.show_tag_a').hide();
        $('#box-readonly').find('.input_show_file').hide();

        resetOrderNo();

        $('#application_status').change(function(){
            box_scope();
        });
        box_scope();

    });

    function noti_email(){
        let send_mail_status = $('.send_mail_status:checked').val();
        if(send_mail_status == 1){
            let box_noti_email = $('.box_noti_email');
            box_noti_email.find('.noti_email').prop('disabled', false);
            box_noti_email.find('.noti_email').prop('required', true);
            box_noti_email.show(300);
        }else{
            let box_noti_email = $('.box_noti_email');
            box_noti_email.find('.noti_email').prop('disabled', true);
            box_noti_email.find('.noti_email').prop('required', false);
            box_noti_email.hide(300);
        }
    }

    function resetOrderNo(){
        $('.branch_no').each(function(index, el) {
            $(el).text(index+1+'.');
        });
    }

    function box_scope(){
        if($('#application_status').val() == 6){ //อยู่ระหว่างการพิจารณาอนุมัติ
            var box_scope = $('.box_scope');
            box_scope.show();
            box_scope.find('input, select, textarea').prop('disabled', false);
        }else{
            var box_scope = $('.box_scope');
            box_scope.hide();
            box_scope.find('input, select, textarea').prop('disabled', true);
        }
    }

</script>
@endpush
