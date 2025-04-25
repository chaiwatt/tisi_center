@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title pull-left">รายละเอียดการแจ้งผลการสอบเทียบเครื่องมือวัด #{{ $receive_calibrate->id }}</h3>
      @can('view-'.str_slug('receive_calibrate'))
      <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
        <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
      </a>
      @endcan
      <div class="clearfix"></div>
      <hr>

      @if ($errors->any())
      <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
      @endif

      <div class="form-horizontal" role="form">
        <div class="form-body">

          @if(!empty($receive_calibrate->CreatedName))
          <div class="form-group">
            {!! Form::label('tb3_Tisno', 'ผู้ประกอบการ:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">{{ $receive_calibrate->CreatedName }}</p>
            </div>
            </div>
            <div class="form-group">
            {!! Form::label('tb3_Tisno', 'เลขนิติบุคคล:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">{{ $receive_calibrate->TraderIdName }}</p>
            </div>
            </div>
          @endif

          <div class="form-group">
              {!! Form::label('tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-4 control-label']) !!}
              <div class="col-md-6">
                <p class="form-control-static">มอก.{{ $receive_calibrate->tis->tb3_Tisno }} {{ $receive_calibrate->tis->tb3_TisThainame }}</p>
              </div>
          </div>

          <div class="form-group">
            {!! Form::label('license', 'ใบอนุญาต:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">

              <div class="row license-list">
                <!-- แสดงเลขที่ใบอนุญาต -->
                @foreach ($own_licenses as $key => $own_license)

                <div class="col-md-4">
                  <div class="checkbox checkbox-success checkbox-active">
                    <input name="tbl_licenseNo[]"
                           id="license{{ $own_license->Autono }}"
                           data-license="{{ $own_license->Autono }}"
                           class="license-item" type="checkbox"
                           value="{{ $own_license->tbl_licenseNo }}"
                           disabled="disabled"
                           @if(array_search(trim($own_license->tbl_licenseNo), $inform_change_licenses))
                             checked="checked"
                           @endif

                           >
                          @if(array_search($own_license->tbl_licenseNo, $inform_change_licenses))
                           <label for="license{{ $own_license->Autono }}" > </label>
                            <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/{{ HP::ConvertLicenseNoToFileName($own_license->tbl_licenseNo) }}.pdf"
                              target="_blank">
                              <b class="form-control-static">{{ $own_license->tbl_licenseNo }}</b>
                              </a>
                           @else
                               <label for="license{{ $own_license->Autono }}" > </label>
                               <b class="form-control-static">{{ $own_license->tbl_licenseNo }}</b>
                         @endif
                  </div>
                </div>

                @endforeach

              </div>

            </div>
          </div>

          <div class="table-responsive">

            <table class="table color-bordered-table info-bordered-table" id="table-detail">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="col-md-2 text-center">ชื่อเครื่องมือ</th>
                  <th class="col-md-4 text-center">รายการที่ใช้วัด</th>
                  <th class="col-md-2 text-center">วันที่สอบเทียบ</th>
                  <th class="col-md-3 text-center">
                    ผลการสอบเทียบ
                    <div class="text-warning">รองรับไฟล์ .pdf และ .jpg ขนาดไม่เกิน 10 MB</div>
                  </th>
                </tr>
              </thead>
              <tbody>

                @foreach ($inform_details as $key => $inform_detail)
                  <tr>
                    <td>{{ $key+1 }}.</td>
                    <td>
                      {{ $inform_detail->tool }}
                    </td>
                    <td class="td-detail">
                      @foreach (json_decode($inform_detail->detail) as $key_detail => $detail)

                        <div class="col-md-12">
                          {{ $key_detail+1 }}.&nbsp;{{ $detail }}
                        </div>

                      @endforeach

                    </td>
                    <td class="text-center">
                      {{ HP::DateThai($inform_detail->exam_date) }}
                    </td>
                    <td class="text-center">

                      {{-- ดูไฟล์ --}}
                        @foreach (json_decode($inform_detail->attach_result) as $attach_result)
                          @if(HP::checkFileStorage($attach_path.$attach_result->file_name))
                              <a href="{{ HP::getFileStorage($attach_path.$attach_result->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                              <input type="hidden" name="attach_file[{{ $key }}][]" value="{{ $attach_result->file_name }}" />
                          @endif
                        @endforeach

                    </td>
                  </tr>
                @endforeach

              </tbody>
            </table>

          </div>

          <div class="form-group">
            {!! Form::label('applicant_name', 'ชื่อผู้บันทึก:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_calibrate->applicant_name }}
              </p>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('tel', 'เบอร์โทร:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_calibrate->tel }}
              </p>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('email', 'E-mail:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_calibrate->email }}
              </p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title pull-left">บันทึกรับแจ้งผลการสอบเทียบเครื่องมือวัด</h3>

      <div class="clearfix"></div>
      <hr>

      {!! Form::model($receive_calibrate, [
                                        'method' => 'PATCH',
                                        'url' => ['/esurv/receive_calibrate', $receive_calibrate->id],
                                        'class' => 'form-horizontal',
                                        'files' => true
                                      ])
      !!}

          <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label required']) !!}
            <div class="col-md-6">
              {!! Form::select('state', HP::StatusReceiveVolumes(), null, ['class' => 'form-control', 'required'=>'required', 'placeholder'=>'- เลือกสถานะเอกสาร -', 'disabled'=>$set_state]) !!}
              {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </div>
          </div>

          <div class="form-group {{ $errors->has('consider_comment') ? 'has-error' : ''}}">
            {!! Form::label('consider_comment', 'ความคิดเห็นเพิ่มเติม:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              {!! Form::textarea('consider_comment', null, ['class' => 'form-control', 'rows'=>'2', 'disabled'=>$set_state]) !!}
              {!! $errors->first('consider_comment', '<p class="help-block">:message</p>') !!}
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('consider', 'ผู้พิจารณา:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              {!! Form::text('consider', null, ['class' => 'form-control', 'disabled'=>'disabled']) !!}
              {!! $errors->first('consider', '<p class="help-block">:message</p>') !!}
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
              <input type="hidden" name="previousUrl" value="{{$previousUrl}}">
              <button class="btn btn-primary" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
              </button>
              @can('view-'.str_slug('receive_calibrate'))
                <a class="btn btn-default button_hide" href="{{url("$previousUrl")}}">
                  <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
              @endcan

            </div>
          </div>

      {!! Form::close() !!}

      <div class="clearfix"></div>

    </div>
  </div>

</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush
