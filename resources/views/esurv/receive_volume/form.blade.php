@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
  .panel-body-info {
      border: #00bbd9 1px solid;
  }
</style>

@endpush

<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title pull-left">รายละเอียดการแจ้งปริมาณการผลิตตามเงื่อนไข #{{ $receive_volume->id }}</h3>
      @can('view-'.str_slug('receive_volume'))
      <a class="btn btn-success pull-right"href="{{url("$previousUrl")}}">
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

          @if(!empty($receive_volume->CreatedName))
          <div class="row">
            <div class="col-md-6">
                <div class="">
                    <label class="control-label col-md-3">ผู้ประกอบการ :</label>
                    <div class="col-md-9">
                        <p class="form-control-static">  {{ $receive_volume->CreatedName  }} </p>
                    </div>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="">
                    <label class="control-label col-md-3">เลขนิติบุคคล :</label>
                    <div class="col-md-9">
                        <p class="form-control-static">  {{ $receive_volume->TraderIdName  }} </p>
                    </div>
                </div>
            </div>
          </div>
          @endif

          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="control-label col-md-3">เดือน:</label>
                      <div class="col-md-9">
                          <p class="form-control-static"> {{ HP::MonthList()[$receive_volume->inform_month] }} </p>
                      </div>
                  </div>
              </div>

              <div class="col-md-6">
                  <div class="form-group">
                      <label class="control-label col-md-3">ปี:</label>
                      <div class="col-md-9">
                        <p class="form-control-static"> {{ $receive_volume->inform_year+543 }} </p>
                      </div>
                  </div>
              </div>
          </div>
          {{-- CreatedAndTradeName --}}
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-heading">ข้อมูลปริมาณการผลิต</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                  <div class="panel-body panel-body-info">

                    <div class="form-group">
                      {!! Form::label('tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-2 control-label']) !!}
                      <div class="col-md-8">
                        <p class="form-control-static">มอก.{{ $receive_volume->tis->tb3_Tisno  ?? null }} {{ $receive_volume->tis->tb3_TisThainame  ?? null  }}</p>
                      </div>
                    </div>

                    <div class="form-group">
                      {!! Form::label('license', 'ใบอนุญาต:', ['class' => 'col-md-2 control-label']) !!}
                      <div class="col-md-8">

                        <div class="row license-list">

                          <!-- แสดงเลขที่ใบอนุญาต -->
                          @foreach ($own_licenses as $key => $own_license)

                          <div class="col-md-4">
                            <div class="checkbox checkbox-success checkbox-active">
                              <input name="tbl_licenseNo[]"
                                     id="license{{ $own_license->Autono }}"
                                     data-license="{{ $own_license->Autono }}"
                                     class="license-item"
                                     type="checkbox"
                                     value="{{ $own_license->tbl_licenseNo }}"
                                     disabled="disabled"
                                     @if(array_search($own_license->tbl_licenseNo, $inform_volume_licenses))
                                       checked="checked"
                                     @endif>
                                <label for="license{{ $own_license->Autono }}"> {{ $own_license->tbl_licenseNo }} </label>
                            </div>
                          </div>

                          @endforeach

                        </div>

                      </div>
                    </div>

                    <div class="form-group">

                      <div class="row license-detail col-md-12">
                        <!-- แสดงรายละเอียดรายการในใบอนุญาต -->

                        @if(!empty($receive_volume->starndard->tb3_Tisforce) &&$receive_volume->starndard->tb3_Tisforce!='บ')

                          @foreach ($own_licenses as $key => $own_license)

                          @if(array_search($own_license->tbl_licenseNo, $inform_volume_licenses)===false)
                            @continue
                          @endif

                            <div class="col-md-12 detail-item" id="detail{{ $own_license->Autono }}">
                              <h5>
                                <span class="order">{{ $key+1 }}</span>. ใบอนุญาตเลขที่
                                @if(!empty($own_license->Is_Check_PDF_File==1))
                                <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/{{ HP::ConvertLicenseNoToFileName($own_license->tbl_licenseNo) }}.pdf"
                                    target="_blank">
                                  <span class="text-info">{{ $own_license->tbl_licenseNo }} </span>
                                </a>
                                @else
                                     <span class="text-danger">{{ $own_license->tbl_licenseNo }} </span>
                                @endif
                                <span class="text-success">(มาตรฐานทั่วไป)</span>
                              </h5>
                              <div class="table-responsive">
                                <table class="table color-bordered-table info-bordered-table" id="table-detail{{ $own_license->Autono }}">
                                  <thead>
                                    <tr>
                                      <th class="col-md-1 text-center" rowspan="2">รายการที่</th>
                                      <th class="col-md-5 text-center" rowspan="2">รายละเอียดของผลิตภัณฑ์อุตสาหกรรมที่ได้รับอนุญาต</th>
                                      <th class="col-md-4 text-center" colspan="2">ปริมาณการผลิต</th>
                                      <th class="col-md-2 text-center" rowspan="2">หน่วย</th>
                                    </tr>
                                    <tr>
                                      <th class="text-center">แสดง</th>
                                      <th class="text-center">ไม่แสดง</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($details[$own_license->tbl_licenseNo] as $key => $detail)

                                    <tr>
                                      <td class="text-center">{{ $key+1 }}.</td>
                                      <td>{{ $detail->standard_detail }}</td>
                                      <td>
                                        <div class="col-md-12">
                                          <div class="checkbox checkbox-success checkbox-active">
                                            <input id="license-detail1-{{ $detail->id }}"
                                                   name="license_detail_checked[{{ $detail->id }}][2]"
                                                   class="license-detail-item"
                                                   type="checkbox"
                                                   disabled="disabled"
                                                   value="{{ $detail->id }}"
                                                   @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume2))
                                                     checked="checked"
                                                   @endif
                                            />
                                            <label for="license-detail1-{{ $detail->id }}">
                                              ผลิต
                                              @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume2))
                                                {{ $inform_details[$detail->id]->volume2 }}
                                              @endif
                                            </label>
                                          </div>
                                        </div>
                                      </td>
                                      <td>
                                        <div class="col-md-5">
                                          <div class="checkbox checkbox-success">
                                            <input id="license-detail2-{{ $detail->id }}"
                                                   name="license_detail_checked[{{ $detail->id }}][3]"
                                                   class="license-detail-item"
                                                   type="checkbox"
                                                   value="{{ $detail->id }}"
                                                   disabled="disabled"
                                                   @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume3))
                                                      checked="checked"
                                                   @endif
                                            />
                                            <label for="license-detail2-{{ $detail->id }}">
                                              ผลิต
                                              @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume3))
                                                {{ $inform_details[$detail->id]->volume3 }}
                                              @endif
                                            </label>
                                          </div>
                                        </div>
                                      </td>
                                      <td class="text-center">
                                        @if(array_key_exists($detail->id, $inform_details) && (!is_null($inform_details[$detail->id]->volume2) || !is_null($inform_details[$detail->id]->volume3)))
                                          <p class="form-control-static">{{ App\Models\Basic\UnitCode::where('id_unit',$inform_details[$detail->id]->id_unit)->first()->name_unit ?? 'n/a' }}</p>
                                        @endif
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>

                            @endforeach

                            @else

                                @foreach ($own_licenses as $key => $own_license)

                                  @if(array_search($own_license->tbl_licenseNo, $inform_volume_licenses)===false)
                                    @continue
                                  @endif

                                  <div class="col-md-12 detail-item" id="detail{{ $own_license->Autono }}">
                                    <h5>
                                      <span class="order">{{ $key+1 }}</span>. ใบอนุญาตเลขที่
                                      @if(!empty($own_license->Is_Check_PDF_File==1))
                                      <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/{{ HP::ConvertLicenseNoToFileName($own_license->tbl_licenseNo) }}.pdf"
                                          target="_blank">
                                        <span class="text-info">{{ $own_license->tbl_licenseNo }} </span>
                                      </a>
                                      @else
                                           <span class="text-danger">{{ $own_license->tbl_licenseNo }} </span>
                                      @endif
                                      <span class="text-danger">(มาตรฐานบังคับ)</span>
                                    </h5>

                                    <div class="table-responsive">

                                      <table class="table color-bordered-table info-bordered-table" id="table-detail{{ $own_license->Autono }}">
                                        <thead>
                                          <tr>
                                            <th class="col-md-1 text-center">รายการที่</th>
                                            <th class="col-md-6 text-center">รายละเอียดของผลิตภัณฑ์อุตสาหกรรมที่ได้รับอนุญาต</th>
                                            <th class="col-md-3 text-center">ปริมาณการผลิต</th>
                                            <th class="col-md-2 text-center">หน่วย</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($details[$own_license->tbl_licenseNo] as $key => $detail)

                                            <tr>
                                              <td class="text-center">{{ $key+1 }}.</td>
                                              <td>{{ $detail->standard_detail }}</td>
                                              <td>
                                                <div class="col-md-5">
                                                  <div class="checkbox checkbox-success checkbox-active">
                                                    <input id="license-detail{{ $detail->id }}"
                                                           name="license_detail_checked[{{ $detail->id }}][1]"
                                                           class="license-detail-item"
                                                           type="checkbox"
                                                           disabled="disabled"
                                                           value="{{ $detail->id }}"
                                                           @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume1))
                                                             checked="checked"
                                                           @endif
                                                    />
                                                    <label for="license-detail{{ $detail->id }}"> ผลิต </label>
                                                  </div>
                                                </div>
                                                <div class="col-md-7">
                                                    @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume1))
                                                      <p class="form-control-static">{{ $inform_details[$detail->id]->volume1 }}</p>
                                                    @endif
                                                </div>
                                              </td>
                                              <td class="text-center">
                                                @if(array_key_exists($detail->id, $inform_details) && !is_null($inform_details[$detail->id]->volume1))
                                                  <p class="form-control-static">{{ App\Models\Basic\UnitCode::where('id_unit',$inform_details[$detail->id]->id_unit)->first()->name_unit ?? 'n/a' }}</p>
                                                @endif
                                              </td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                      </table>

                                    </div>

                                  </div>

                                  @endforeach

                              @endif
                      </div>

                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="form-group">
            {!! Form::label('remark', 'หมายเหตุ:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">{{ $receive_volume->remark }}</p>
            </div>
          </div>

          <div class="form-group">
            {!! Html::decode(Form::label('attach', 'ไฟล์แนบเอกสารที่เกี่ยวข้อง:<br><span class="text-muted">รองรับไฟล์ .pdf และ .jpg ขนาดไม่เกิน 10 MB</span>', ['class' => 'col-md-4 control-label'])) !!}
            <div class="col-md-8">

                @foreach ($attachs as $key => $attach)

                <div class="form-group">
                  <div class="col-md-12">
                    <p class="form-control-static">
                      {{ !is_null($attach->file_note)?$attach->file_note:$attach->file_client_name }}
                      @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                        <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                      @endif
                    </p>
                  </div>
                </div>

                @endforeach

            </div>
          </div>

          <div class="form-group">
            {!! Form::label('applicant_name', 'ชื่อผู้บันทึก:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_volume->applicant_name }}
              </p>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('tel', 'เบอร์โทร:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_volume->tel }}
              </p>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('email', 'E-mail:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              <p class="form-control-static">
                {{ $receive_volume->email }}
              </p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title pull-left">บันทึกรับแจ้งปริมาณการผลิตตามเงื่อนไข</h3>

      <div class="clearfix"></div>
      <hr>

      {!! Form::model($receive_volume, [
                                        'method' => 'PATCH',
                                        'url' => ['/esurv/receive_volume', $receive_volume->id],
                                        'class' => 'form-horizontal',
                                        'files' => true
                                      ])
      !!}
          <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
            {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label required']) !!}
            <div class="col-md-6">
              {!! Form::select('state', HP::StatusReceiveVolumes(), null, ['class' => 'form-control', 'required'=>'required', 'placeholder'=>'- เลือกสถานะเอกสาร -', 'disabled'=> $set_state]) !!}
              {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
            </div>
          </div>

          <div class="form-group {{ $errors->has('consider_comment') ? 'has-error' : ''}}">
            {!! Form::label('consider_comment', 'ความคิดเห็นเพิ่มเติม:', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
              {!! Form::textarea('consider_comment', null, ['class' => 'form-control', 'rows'=>'2', 'disabled'=> $set_state]) !!}
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
              @can('view-'.str_slug('receive_volume'))
                <a class="btn btn-default button_hide"href="{{url("$previousUrl")}}">
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

@endpush
