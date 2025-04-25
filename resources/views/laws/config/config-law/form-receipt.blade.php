

{!! Form::model($config, ['url' => '/law/config/config-receipt', 'method' => 'POST', 'class' => 'form-horizontal','id'=>'form-deduct']) !!}

 
 <div class="row">           
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('check_deduct_money') ? 'has-error' : ''}}">
                {!! Form::label('check_deduct_money', 'หักเงินเก็บเป็นสวัสดีการ สมอ.', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    <label>{!! Form::radio('check_deduct_money', '1', empty($config->check_deduct_money)  || (!empty($config->check_deduct_money)  && $config->check_deduct_money == '1') ? true : false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บ &nbsp;&nbsp;</label>
                    <label>{!! Form::radio('check_deduct_money', '0',(!empty($config->check_deduct_money)  && $config->check_deduct_money == '0') ? true : false  , ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} ไม่เรียกเก็บ &nbsp;&nbsp;</label>
                </div>
            </div>

            <div class="form-group div_deduct_money {{ $errors->has('number_deduct_money') ? 'has-error' : ''}}">
                <div class="col-md-3"></div>
                {!! HTML::decode(Form::label('number_deduct_money', 'อันตรา'.'<span class="text-danger">*</span>', ['class' => 'col-md-1 control-label text-right '])) !!}
                <div class="col-md-3">
                        <div class=" input-group " >
                            {!! Form::number('number_deduct_money', !empty($config->number_deduct_money)  ?  $config->number_deduct_money : null  ,  ['class' => 'form-control text-center' ]) !!}
                            <span class="input-group-addon " style='background-color:#e5ebec;' >  % </span>
                        </div>
                </div>
                {!! HTML::decode(Form::label('agency_deduct_money', 'หน่วยงาน', ['class' => 'col-md-2 control-label '])) !!}
                <div class="col-md-3">
                           {!! Form::select('agency_deduct_money[]', 
                                 ['1'=>'หน่วยงานภายใน สมอ.','2'=>'หน่วยงานภายนอก สมอ.'], 
                                 !empty($config->agency_deduct_money)  ?  json_decode($config->agency_deduct_money,true) : null,
                               ['class' => 'select2-multiple',
                                "multiple"=>"multiple", 
                                "id" => "agency_deduct_money",
                                 'required' => false])
                            !!}
                </div>
            </div>
        </div>      
</div> 

 <div class="row">           
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('check_deduct_vat') ? 'has-error' : ''}}">
                {!! Form::label('check_deduct_vat', 'หักภาษีมูลค่าเพิ่ม VAT', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    <label>{!! Form::radio('check_deduct_vat', '1', empty($config->check_deduct_vat)  ||  (!empty($config->check_deduct_vat)  && $config->check_deduct_vat == 1) ? true : false, ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บ &nbsp;&nbsp;</label>
                    <label>{!! Form::radio('check_deduct_vat', '0',(!empty($config->check_deduct_vat)  && $config->check_deduct_vat == 2) ? true : false  , ['class'=>'check', 'required'=>true, 'data-radio'=>'iradio_square-green']) !!} ไม่เรียกเก็บ &nbsp;&nbsp;</label>
                </div>
            </div>

            <div class="form-group div_deduct_vat {{ $errors->has('number_deduct_vat') ? 'has-error' : ''}}">
                <div class="col-md-3"></div>
                {!! HTML::decode(Form::label('number_deduct_vat', 'อันตรา'.'<span class="text-danger">*</span>', ['class' => 'col-md-1 control-label text-right '])) !!}
                <div class="col-md-3">
                        <div class=" input-group " >
                            {!! Form::number('number_deduct_vat', !empty($config->number_deduct_vat)  ?  $config->number_deduct_vat : null  ,  ['class' => 'form-control text-center  ' ]) !!}
                            <span class="input-group-addon " style='background-color:#e5ebec;' >  % </span>
                        </div>
                </div>
                {!! HTML::decode(Form::label('agency_deduct_vat', 'หน่วยงาน', ['class' => 'col-md-2 control-label '])) !!}
                <div class="col-md-3">
                           {!! Form::select('agency_deduct_vat[]', 
                                 ['1'=>'หน่วยงานภายใน สมอ.','2'=>'หน่วยงานภายนอก สมอ.'], 
                                 !empty($config->agency_deduct_vat)  ?   json_decode($config->agency_deduct_vat,true): null,
                               ['class' => 'select2-multiple',
                                "multiple"=>"multiple", 
                                "id" => "agency_deduct_vat",
                                 'required' => false])
                            !!}
                </div>
            </div>
      </div>
</div> 
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="button" id="form_deduct_save">
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


@push('js')
    
    <script>
        $(document).ready(function () {

    
       
            $('#form_deduct_save').click(function(event) {
                $('#form-deduct').submit();
            });
       
              
            $('#form-deduct').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {
                  return true;
            });
 
        });

    </script>
@endpush
 