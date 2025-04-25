@extends('layouts.master')

@section('content')

    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
              <div class="white-box">

                <h3 class="box-title">รับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ)</h3>

                <div class="clearfix"></div>
                <hr>

        @include ('asurv.receive_applicant_21bis.form')

        <div class="col-sm-12" style="margin-bottom: 10px"></div>

        {!! Form::model($data, [
                                          'method' => 'PATCH',
                                          'url' => ['/asurv/receive_applicant_21bis', $data->id],
                                          'class' => 'form-horizontal',
                                          'files' => true
                                        ])
        !!}

            <fieldset class="row wrapper-detail">
                <legend> ผลการพิจารณา</legend>

                <div class="form-group ">
                    {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label required']) !!}
                    <div class="col-sm-6 m-b-10">
                      {!! Form::select('state', HP::StatusReceiveApplicants(), null, ['class' => 'form-control', 'required'=>'required', 'placeholder'=>'- เลือกสถานะคำขอ -']) !!}
                      {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group ">
                    {!! Form::label('consider_comment', 'ความคิดเห็นเพิ่มเติม:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-sm-6 m-b-10">
                      {!! Form::textarea('consider_comment', null, ['class' => 'form-control', 'rows' => 2]) !!}
                    </div>
                </div>

                <div class="form-group">
                  {!! Form::label('consider', 'ผู้พิจารณา:', ['class' => 'col-md-4 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::text('consider', null, ['class' => 'form-control', 'disabled'=>'disabled']) !!}
                    {!! $errors->first('consider', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

            </fieldset>

            <div class="col-sm-12" style="margin-bottom: 5px;"></div>
            <div class="form-group text-center">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <a class="btn btn-default waves-effect waves-light"
                   href="{{ url('/asurv/receive_applicant_21bis') }}">
                    <i class="fa fa-undo"></i> ยกเลิก
                </a>
            </div>

        {!! Form::close() !!}

    </div>

@endsection

@push('js')

@endpush
