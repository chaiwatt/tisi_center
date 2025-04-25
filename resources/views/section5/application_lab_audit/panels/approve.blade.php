<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                พิจารณาอนุมัติ # {!! $applicationlabaudit->application_no !!}
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body" id="box-report_approve">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="white-box">
                                <legend class="legend"><h5>พิจารณาอนุมัติ</h5></legend>
                                @php
                                    $application_report = $applicationlabaudit->app_report;
                                @endphp
                    
                                <div class="row">
                                    <div class="col-md-12">
                    
                                        <div class="form-group required{{ $errors->has('report_approve') ? 'has-error' : ''}}">
                                            {!! Form::label('report_approve', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::select('report_approve',  App\Models\Section5\ApplicationLabStatus::whereIn('id', [9,10])->pluck('title', 'id'), @$application_report->report_approve, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true]) !!}
                                                {!! $errors->first('report_approve', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                    
                                        <div class="form-group {{ $errors->has('report_approve_description') ? 'has-error' : ''}}">
                                            {!! Form::label('report_approve_description', 'รายละเอียด'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::textarea('report_approve_description', @$application_report->report_approve_description,  ['class' => 'form-control', 'rows' => 2]) !!}
                                                {!! $errors->first('report_approve_description', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {!! Form::label('report_approve_send_mail_status', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                    <div class="col-md-8">
                                                        <div class="col-md-3">
                                                            <label>
                                                                {!! Form::radio('report_approve_send_mail_status', '2',( !is_null($application_report) && empty($application_report->noti_email) ? true:( is_null($application_report)?true:null ) ), ['class'=>'check', 'id' => 'report_approve_send_mail_status_2', 'data-radio'=>'iradio_flat-blue']) !!}
                                                                ไม่ส่งอีเมลแจ้งผล
                                                            </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>
                                                                {!! Form::radio('report_approve_send_mail_status', '1',( !is_null($application_report) && !empty($application_report->noti_email) ? true:( is_null($application_report)?false:null )), ['class'=>'check', 'id' => 'report_approve_send_mail_status_1', 'data-radio'=>'iradio_flat-blue']) !!}
                                                                ส่งอีเมลแจ้งผล
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row noti_email_report_approve">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {!! Form::label('report_approve_noti_email', 'อีเมลแจ้งผล'.' :', ['class' => 'col-md-4 control-label']) !!}
                                                    <div class="col-md-4">
                                                        {!! Form::text('report_approve_noti_email', !empty( $application_report->noti_email )?implode(',',json_decode($application_report->noti_email,true)):(!empty( $applicationlabaudit->co_email )?$applicationlabaudit->co_email:null),  ['class' => 'form-control', 'id'=> 'report_approve_noti_email', 'disabled' => true ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
                                            {!! Form::label('', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('', auth()->user()->FullName, ['class' => 'form-control', 'disabled' => true]) !!}
                                                {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                    
                                        <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}">
                                            {!! Form::label('', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('', HP::formatDateThaiFull(date('Y-m-d')), ['class' => 'form-control', 'disabled' => true]) !!}
                                                {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                    
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


@push('js')

    <script>
        jQuery(document).ready(function() { 
        

            $(document).on('ifChecked','#report_approve_send_mail_status_1', function(event){
                box_re_approve_send_mail_status();
            });

            
            $(document).on('ifChecked','#report_approve_send_mail_status_2', function(event){
                box_re_approve_send_mail_status();
            });

 
            box_re_approve_send_mail_status();

            $('#report_approve_noti_email').tagsinput({
                // itemText: 'label'
            });

        });
        function box_re_approve_send_mail_status(){
            var noti_email =  $(document).find('.noti_email_report_approve');

            if( $(document).find('#report_approve_send_mail_status_1').is(':checked',true) ){

                noti_email.show();
                noti_email.find('input').prop('disabled', false);
                // noti_email.find('input').prop('required', true);
            }else{
                noti_email.hide();
                noti_email.find('input').prop('disabled', true);
                // noti_email.find('input').prop('required', false);
            }
        }

        
    </script>
@endpush