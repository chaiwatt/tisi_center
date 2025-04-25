
<div class="form-group {{ $errors->has('plan_id') ? 'has-error' : ''}}">
    {!! HTML::decode( Form::label('plan_id', 'แผน :'.'<span class="text-danger select-label">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('plan_id', App\Models\Tis\TisiEstandardDraftPlan::pluck('tis_name', 'id'), null, ['class' => 'form-control', 'required' => true, 'placeholder'=>'- เลือกแผน -']) !!}
        {!! $errors->first('plan_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

      <div class="request-form">
                        <div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-9">
                                {!! Form::text('tis_name_eng',null, ['class' => 'form-control ', 'required' => true]) !!}
                                {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        @php
                            $standard_types = App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');//ข้อมูลประเภทมาตรฐาน
                        @endphp       
                        <div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-9">
                                {!! Form::select('std_type',
                                                 $standard_types,
                                                 null,
                                                 ['class' => 'form-control',
                                                  'required'=> true,
                                                  'placeholder'=>'- เลือกประเภทมาตรฐาน -',
                                                  'id'=>'std_type'
                                                 ])
                                !!}
                                {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
                            </div>        
                        </div>

                        <div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-3">
                                {!! Form::text('tis_number',null, ['class' => 'form-control ','required'=>true]) !!}
                                {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::text('tis_book',null, ['class' => 'form-control ','id'=>'tis_book','required'=>true]) !!}
                                {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-3">
                                {!! Form::select('tis_year',
                                                HP::Years(),
                                                  null,
                                                ['class' => 'form-control',
                                                 'required' => true,
                                                 'placeholder' => '- เลือกปีมาตรฐาน -',
                                                 'id'=>'tis_year'
                                                ])
                                !!}
                                {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-9">
                                {!! Form::select('method_id',
                                                 App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                                  null,
                                                 ['class' => 'form-control',
                                                  'required'=> true,
                                                  'placeholder'=>'- เลือกวิธีการ -'
                                                 ])
                                !!}
                                {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('set_date', 'กำหนด:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-9">
                              <div class="input-daterange input-group" id="date-range">
                                {!! Form::text('plan_startdate', null, ['class' => 'form-control ','id'=>'plan_startdate']); !!}
                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                {!! Form::text('plan_enddate', null, ['class' => 'form-control','id'=>'plan_enddate']); !!}
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('period', 'ระยะเวลาที่กำหนด:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-5">
                              <div class="input-group">
                                {!! Form::text('period', null, ['class' => 'form-control text-right','id'=>'period']); !!}
                                <span class="input-group-addon bg-info b-0 text-white"> เดือน </span>
                              </div>
                            </div>
                        </div>
             </div>

@push('js')

<script>
        jQuery(document).ready(function() {

            $("#plan_id").on('change', function () {
                var plan_id = $(this).val()
                    load_data_plan(plan_id)

            });
            load_plan();

        });

        function load_plan() {
            var plan_id = $("#plan_id").val();   
                if(plan_id != '' ){
                   load_data_plan(plan_id)

            }
        }

        function load_data_plan(plan_id) {
            $.ajax({
                    url: "{!! url('/certify/set-standards/get-estandard-plan/') !!}" + "/" + plan_id
                }).done(function( item ) {
                
                    if(item != ''){

                        $('#tis_name_eng').val(item.tis_name_eng);
                        $('#std_type').val(item.std_type).change();
                        $('#tis_number').val(item.tis_number);
                        $('#tis_book').val(item.tis_book);
                        $('#tis_year').val(item.tis_year).change();
                        $('#method_id').val(item.method_id).change();
                        $('#plan_startdate').val((item.plan_startdate));
                        $('#plan_enddate').val((item.plan_enddate));
                        $('#period').val(item.period);
                        
                    }
                });
            }
        

</script>

@endpush
  
              



