@extends('layouts.master')

@push('css')

<style>
  .item{
    margin: 5px 0px;
  }
</style>

@endpush

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">ค้นหาข้อมูลผู้ประกอบการ VAT กรมสรรพากร (RD)</h3>

        <a class="btn btn-success pull-right" href="{{ url()->previous() }}">
            <i class="icon-arrow-left-circle"></i> กลับ
        </a>

        <div class="clearfix"></div>

        <hr>

        @if(Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message') }}</p>
            @php Session::forget('message'); @endphp
        @endif

        {!! Form::model($data, ['url' => '/ws/rd-vat', 'method' => 'post', 'class' => 'form-horizontal']) !!}

        <div class="col-md-12">

            <div class="form-group">
                {!! Form::label('JuristicID', 'ค้นจากเลขประจำตัวผู้เสียภาษี:', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('JuristicID', null, ['class' => 'form-control', 'placeholder'=>'กรอกเลขประจำตัวผู้เสียภาษี 13 หลัก', 'minlength' => 13, 'maxlength' => 13, 'required' => true]) !!}
                </div>
            </div>

          <div class="form-group">
              <div class="col-md-5"></div>
              <div class="col-md-4">
                  <button type="submit" class="btn waves-effect waves-light btn-info">
                    <i class="fa fa-search"></i> ค้นหา
                  </button>
              </div>
          </div>

        </div>

        <div class="form-group"></div>

        @if(array_key_exists("result", $data))

            @php $juristic = $data['result']; @endphp

            @if(!is_null($juristic) && $juristic->status=='success')

                <div class="row">

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เลขประจำตัวผู้เสียภาษี:</div>
                        <div class="col-md-7">{{ $juristic->vNID }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ประเภท:</div>
                        <div class="col-md-7">{{ $juristic->vTitleName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อ:</div>
                        <div class="col-md-7">{{ $juristic->vName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สกุล:</div>
                        <div class="col-md-7">{{ $juristic->vSurName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ประเภทสาขา:</div>
                        <div class="col-md-7">{{ $juristic->vBranchTitleName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อสาขา:</div>
                        <div class="col-md-7">{{ $juristic->vBranchName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสสาขา:</div>
                        <div class="col-md-7">{{ $juristic->vBranchNumber }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">อาคาร:</div>
                        <div class="col-md-7">{{ $juristic->vBuildingName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชั้นที่:</div>
                        <div class="col-md-7">{{ $juristic->vFloorNumber }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อหมู่บ้าน:</div>
                        <div class="col-md-7">{{ $juristic->vVillageName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ห้องเลขที่:</div>
                        <div class="col-md-7">{{ $juristic->vRoomNumber }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">บ้านเลขที่:</div>
                        <div class="col-md-7">{{ $juristic->vHouseNumber }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ซอย:</div>
                        <div class="col-md-7">{{ $juristic->vSoiName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ถนน:</div>
                        <div class="col-md-7">{{ $juristic->vStreetName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ตำบล/แขวง:</div>
                        <div class="col-md-7">{{ $juristic->vThambol }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">อำเภอ/เขต:</div>
                        <div class="col-md-7">{{ $juristic->vAmphur }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">จังหวัด:</div>
                        <div class="col-md-7">{{ $juristic->vProvince }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสไปรษณีย์:</div>
                        <div class="col-md-7">{{ $juristic->vPostCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">วันที่จดทะเบียน:</div>
                        <div class="col-md-7">{{ $juristic->vBusinessFirstDate }}</div>
                    </div>

                </div>
			@elseif(!empty($juristic->vMessageErr))
                <div class="alert alert-warning"> {!! $juristic->vMessageErr !!} </div>
            @else
                <div class="alert alert-danger"> ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้ </div>
            @endif


        @endif

        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>
@endsection

@push('js')

@endpush
