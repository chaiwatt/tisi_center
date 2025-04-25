
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
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

    .form-body input[type="text"]:disabled {
        border-right:  medium none;
        border-top: medium none;
        border-left: medium none;
        border-bottom: 1px dotted;
        background-color: #FFFFFF;
    }
</style>
@endpush
@php
    $application_labs_scope = App\Models\Section5\ApplicationLabScope::where('application_lab_id', $applicationlab->id);
    $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $applicationlab->application_no )?$applicationlab->application_no:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->application_date)?HP::DateThaiFull($applicationlab->application_date):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->accept_date) ? HP::DateThaiFull($applicationlab->accept_date) : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlab->accept_by) && !is_null($applicationlab->accepter) ? $applicationlab->accepter->FullName : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ข้อมูลคำขอ # {!! $applicationlab->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    @include ('section5/application-request-form.application-lab')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

@if( ( (isset( $applicationlab->edited ) && $applicationlab->edited == true  || isset( $applicationlab->show ) && $applicationlab->show == true ) ) && ($applicationlab->app_accept()->count() > 0) )
    @include ('section5.application-request-form.history.application-lab')
@endif

<div class="clearfix"></div>


<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ตรวจสอบคำขอ # {!! $applicationlab->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">

                @if( isset( $applicationlab->edited ) && $applicationlab->edited == true  || isset( $applicationlab->show ) && $applicationlab->show == true )

                    @php
                        $accept_data = $applicationlab->app_accept->last();
                    @endphp

                    <fieldset class="white-box">
                        <legend class="legend"><h5>ตรวจสอบคำขอ</h5></legend>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required{{ $errors->has('application_status') ? 'has-error' : ''}}">
                                    {!! Form::label('application_status', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        @php
                                            $application_status_attr = $applicationlab->application_status > 6 ? ['disabled' => true] : ['required' => true] ;
                                            $id_status = ($applicationlab->audit_type == 2)?[2, 3, 6]:[2, 4,  6];
                                            $status_labs = App\Models\Section5\ApplicationLabStatus::whereIn('id',  $id_status )->pluck('title', 'id');
                                        @endphp
                                        {!! Form::select('application_status', $status_labs, !empty( $accept_data->application_status )?$accept_data->application_status:null, array_merge(['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-'], $application_status_attr)) !!}
                                        {!! $errors->first('application_status', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                                    {!! Form::label('description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('description', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box_scope">
                            <div class="box_table mb-2">
                                @foreach($application_labs_scope_groups as $tis_id=>$application_labs_scope_group)
                                    @include('section5/application_lab_accept.table-result', ['application_labs_scope_group' => $application_labs_scope_group, 'standards' => $standards])
                                @endforeach
                            </div>
                        </div>

                        <div class="row repeater-form-date">
                            <div class="col-md-6" data-repeater-list="repeater-date">

                                @if( !is_null($accept_data) && !empty($accept_data->appointment_date) )
                                    @php
                                        $appointment_date = json_decode($accept_data->appointment_date,true);
                                    @endphp
                                    @foreach ((array) $appointment_date as $item )
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('send_mail_status', ' ', ['class' => 'col-md-2 control-label']) !!}
                                    <div class="col-md-8">
                                        <label class="col-md-4">
                                            {!! Form::radio('send_mail_status', '0', !empty($accept_data->send_mail_status) && $accept_data->send_mail_status == 2 ?true:null, ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                            <label for="">ไม่ส่งอีเมลแจ้งผล</label>
                                        </label>
                                        <label class="col-md-4">
                                            {!! Form::radio('send_mail_status', '1', !empty($accept_data->send_mail_status) && $accept_data->send_mail_status == 1 ?true:null, ['class'=>'check send_mail_status', 'data-radio'=>'iradio_flat-blue']) !!}
                                            <label for="">ส่งอีเมลแจ้งผล</label>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row box_noti_email">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('noti_email', !empty( $accept_data->noti_email )?implode(',',json_decode($accept_data->noti_email,true)):(!empty( $applicationlab->co_email )?$applicationlab->co_email:null) ,  ['class' => 'form-control noti_email', 'data-role' => "tagsinput", 'disabled' => true ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                @endif

                </div>
            </div>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        @can('view-'.str_slug('application-lab-accept'))
            <button class="btn btn-primary show_tag_a" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
            </button>
        @endcan
        <a class="btn btn-default show_tag_a" href="{{url('/section5/application_lab_accept')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>


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
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
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

            $('.send_mail_status').on('ifChecked', function(event){
                noti_email();
            });noti_email();

            $('.iradio_flat-blue').removeClass('disabled');

            $('#box-readonly').find('button[type="submit"]').remove();
            $('#box-readonly').find('.icon-close').parent().remove();
            $('#box-readonly').find('.fa-copy').parent().remove();
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true);
            $('#box-readonly').find('select').prop('disabled', true);
            $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
            $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
            $('#box-readonly').find('button').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.btn-remove-file').parent().remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.input_show_file').hide();

            $('#application_status').change(function(){
                box_scope();
            });
            box_scope();

            $('#application_status').change(function (e) { 
                SelectedStatus();
            });
            SelectedStatus();
        });

        function SelectedStatus(){
            var seleted = $('#application_status').val();
            var description = $('#description');
                description.prop('required', false );
            var Div = description.closest( "div.form-group" );
                Div.removeClass('required');
            if( $.inArray( seleted , [ "2", "6" ] ) !== -1 ){
                description.prop('required', true );
                Div.addClass('required');
            }  

        }

        function box_scope(){
            var box_date = $('.repeater-form-date');
            var box_scope = $('.box_scope');

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
    </script>
@endpush
