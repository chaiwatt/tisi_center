

{!! Form::model($config, ['url' => '/law/config/config-law', 'class' => 'form-horizontal']) !!}

<!-- Tab panes -->
<div class="tab-content">

    <div role="tabpanel" class="tab-pane fade active in" id="home">
        <div class="col-md-12">

            <div class="form-group required{{ $errors->has('check_contact_mail_footer') ? 'has-error' : ''}}">
                {!! Form::label('check_contact_mail_footer', 'เงื่อนไข:', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    <label>{!! Form::radio('check_contact_mail_footer', '1',(!empty($config->check_contact_mail_footer)  && $config->check_contact_mail_footer == 1) ? true : false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} แสดงข้อมูลติดต่อกลาง &nbsp;&nbsp;</label>
                    <label>{!! Form::radio('check_contact_mail_footer', '2',(!empty($config->check_contact_mail_footer)  && $config->check_contact_mail_footer == 2) ? true : false  , ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} แสดงข้อมูลติดต่อผู้บันทึก &nbsp;&nbsp;</label>
                    <label>{!! Form::radio('check_contact_mail_footer', '3', (!empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 3) ? true :  false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} ไม่แสดง </label>

                </div>
            </div>

            <div class="form-group {{ $errors->has('contact_mail_footer') ? 'has-error' : ''}}">
                <div class="col-md-3">
                    {!! Form::label('contact_mail_footer', 'ข้อมูลติดต่อสอบถาม:', ['class' => 'control-label pull-right']) !!}
                </div>
                <div class="col-md-8">
                    {!! Form::textarea('contact_mail_footer', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('contact_mail_footer', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>         
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('config-law'))
            <a class="btn btn-default" href="{{ url()->previous() }}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

{!! Form::close() !!}
