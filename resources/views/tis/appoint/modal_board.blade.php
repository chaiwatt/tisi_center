@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<!-- Modal -->
   <div class="modal fade bd-example-modal-lg" id="exampleModalBoard" tabindex="-1" role="dialog" aria-labelledby="exampleModalBoardLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="exampleModalBoardLabel">เพิ่มคณะกรรมการ
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times; Close</span>
        </button>
         </h4>
        </div>
    {!! Form::open(['url' => '/tis/board/save_board', 'class' => 'form-horizontal', 'files' => true, 'method'=> 'POST', 'id' => 'form_board']) !!}
        <div class="modal-body">

              <div class="form-group required {{ $errors->has('prefix_name') ? 'has-error' : ''}}">
                {!! Form::label('prefix_name', 'คำนำหน้าชื่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('prefix_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('prefix_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group required {{ $errors->has('first_name') ? 'has-error' : ''}}">
                {!! Form::label('first_name', 'ชื่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('first_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group required {{ $errors->has('last_name') ? 'has-error' : ''}}">
                {!! Form::label('last_name', 'นามสกุล :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('last_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                {!! Form::label('birth_date', 'วันเกิด :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('birth_date', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]) !!}
                  {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('identity_number') ? 'has-error' : ''}}">
                {!! Form::label('identity_number', 'เลขบัตรประชาชน :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('identity_number', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('identity_number', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('qualification') ? 'has-error' : ''}}">
                {!! Form::label('qualification', 'คุณวุฒิ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('qualification', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('qualification', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('institute') ? 'has-error' : ''}}">
                {!! Form::label('institute', 'สถาบันศึกษา :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('institute', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('institute', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('contact') ? 'has-error' : ''}}">
                {!! Form::label('contact', 'สถานที่ติดต่อ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                  {!! Form::textarea('contact', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=>'5'] : ['class' => 'form-control', 'rows'=>'5']) !!}
                  {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('tel') ? 'has-error' : ''}}">
                {!! Form::label('tel', 'เบอร์โทรศัพท์มือถือ :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::text('tel', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                {!! Form::label('email', 'E-mail :', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                  {!! Form::email('email', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                  {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>
              </div>



        </div>

        <div class="modal-footer">
          <div class="form-group">
  <div class="col-md-offset-4 col-md-4">

    <button class="btn btn-primary" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
   <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true" class="fa fa-rotate-left"> ยกเลิก</span>
  </button>
  </div>
</div>
        </div>
  {!! Form::close() !!}
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<script type="text/javascript">

  jQuery(document).ready(function($) {

  });
</script>

@endpush
