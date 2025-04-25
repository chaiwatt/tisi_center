@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group">
    {!! Form::label('user_no', 'กลุ่มงานหลัก:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
      <p class="form-control-static"> {{ $tisuseresurv->department->depart_name }} </p>
    </div>
</div>

<div class="form-group">
    {!! Form::label('user_no', 'กลุ่มงานย่อย:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
      <p class="form-control-static"> {{ $tisuseresurv->sub_departname }} </p>
    </div>
</div>

<div class="form-group {{ $errors->has('tb3_Tisno') ? 'has-error' : ''}}">
    {!! Form::label('tb3_Tisno', 'มาตรฐานที่รับแจ้งได้:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">

        <input type="checkbox" name="tisno_all" class="check" id="tisno_all" data-checkbox="icheckbox_square-green" @if($tb3_tisnos->first()=='All') checked @endif>
        <label for="tisno_all">มาตรฐานทั้งหมด</label>

        {!! Form::select('tb3_Tisno[]',
                         HP::TisList([4, 5]),
                         $tb3_tisnos,
                         ['class' => 'select2 select2-multiple',
                          'multiple'=>'multiple',
                          'data-placeholder' => '-เลือกมาตรฐาน-'
                         ]
            )
        !!}
        {!! $errors->first('tb3_Tisno', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('tisuseresurvs'))
            <a class="btn btn-default" href="{{url('/besurv/tis-user-esurvs')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script type="text/javascript">

      $(document).ready(function() {

        //เลือกมาตรฐานทั้งหมด
        $('#tisno_all').on('ifChecked', function(event){
            changeAll();
        });

        //ไม่เลือกมาตรฐานทั้งหมด
        $('#tisno_all').on('ifUnchecked', function(event){
            changeAll();
        });

        changeAll();

      });

      function changeAll(){

        if($('#tisno_all').prop('checked')){
            $('select[name*="tb3_Tisno"]').prop('disabled', true);
            $('select[name*="tb3_Tisno"]').children().removeAttr('selected');
            $('select[name*="tb3_Tisno"]').trigger('change');
        }else{
            $('select[name*="tb3_Tisno"]').prop('disabled', false);
        }

      }

  </script>
@endpush
