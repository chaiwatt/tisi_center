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
        <h3 class="box-title pull-left">ค้นหาข้อมูลบุคคลธรรมดา กรมการปกครอง (DOPA)</h3>

        <a class="btn btn-success pull-right" href="{{ url()->previous() }}">
            <i class="icon-arrow-left-circle"></i> กลับ
        </a>

        <div class="clearfix"></div>

        <hr>

        @if(Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message') }}</p>
            @php Session::forget('message'); @endphp
        @endif

        {!! Form::model($data, ['url' => '/ws/personal', 'method' => 'post', 'class' => 'form-horizontal']) !!}

        <div class="col-md-12">

            <div class="form-group">
                {!! Form::label('PersonalID', 'ค้นจากเลขประจำตัวประชาชน:', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('PersonalID', null, ['class' => 'form-control', 'minlength' => 13, 'maxlength' => 13, 'placeholder'=>'กรอกเลขประจำตัวประชาชน 13 หลัก', 'required'=>true]) !!}
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

        <!-- ถ้าค้นข้อมูล -->
        @if(array_key_exists("result", $data))

            @php $result = $data['result']; @endphp

            @if($result->status=='success' && !array_key_exists('Message', $result))

                <div class="row">

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">คํานําหน้านาม/ยศ ชื่อตัว-สกุล:</div>
                        <div class="col-md-7">{{ $result->fullnameAndRank }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">คํานําหน้านาม:</div>
                        <div class="col-md-7">{{ $result->titleDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อ:</div>
                        <div class="col-md-7">{{ $result->firstName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อกลาง:</div>
                        <div class="col-md-7">{{ $result->middleName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สกุล:</div>
                        <div class="col-md-7">{{ $result->lastName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">อายุ:</div>
                        <div class="col-md-7">{{ $result->age }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">วันเดือนปีเกิด:</div>
                        <div class="col-md-7">{{ $result->dateOfBirth }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">วันเดือนปีที่ย้ายเข้ามาในบ้าน:</div>
                        <div class="col-md-7">{{ $result->dateOfMoveIn }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสเพศ:</div>
                        <div class="col-md-7">{{ $result->genderCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เพศ:</div>
                        <div class="col-md-7">{{ $result->genderDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสสัญชาติ:</div>
                        <div class="col-md-7">{{ $result->nationalityCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สัญชาติ:</div>
                        <div class="col-md-7">{{ $result->nationalityDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สถานะภาพเจ้าบ้าน:</div>
                        <div class="col-md-7">{{ $result->ownerStatusDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสสถานะภาพบุคคล:</div>
                        <div class="col-md-7">{{ $result->statusOfPersonCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สถานะภาพบุคคล:</div>
                        <div class="col-md-7">{{ $result->statusOfPersonDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสคํานําหน้านาม:</div>
                        <div class="col-md-7">{{ $result->titleCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">คํานําหน้านามแบบเต็ม:</div>
                        <div class="col-md-7">{{ $result->titleName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสตรวจสอบคํานําหน้านาม:</div>
                        <div class="col-md-7">{{ $result->titleSex }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">คํานําหน้านาม (ภาษาอังกฤษ):</div>
                        <div class="col-md-7">{{ $result->englishTitleDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อ (ภาษาอังกฤษ):</div>
                        <div class="col-md-7">{{ $result->englishFirstName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อกลาง (ภาษาอังกฤษ):</div>
                        <div class="col-md-7">{{ $result->englishMiddleName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สกุล (ภาษาอังกฤษ)::</div>
                        <div class="col-md-7">{{ $result->englishLastName }}</div>
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <!-- บิดา -->
                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อบิดา:</div>
                        <div class="col-md-7">{{ $result->fatherName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสสัญชาติบิดา:</div>
                        <div class="col-md-7">{{ $result->fatherNationalityCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สัญชาติบิดา:</div>
                        <div class="col-md-7">{{ $result->fatherNationalityDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เลขประจําตัวประชาชนบิดา:</div>
                        <div class="col-md-7">{{ $result->fatherPersonalID }}</div>
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <!-- มารดา -->
                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อมารดา:</div>
                        <div class="col-md-7">{{ $result->motherName }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">รหัสสัญชาติมารดา:</div>
                        <div class="col-md-7">{{ $result->motherNationalityCode }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สัญชาติมารดา:</div>
                        <div class="col-md-7">{{ $result->motherNationalityDesc }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เลขประจําตัวประชาชนมารดา:</div>
                        <div class="col-md-7">{{ $result->motherPersonalID }}</div>
                    </div>

                </div>
            @elseif (array_key_exists('Message', $result))
                @if(array_key_exists('Code', $result) && $result->Code=='00404')
                    <div class="alert alert-warning"> ไม่พบข้อมูลที่ค้นหา </div>
                @elseif(array_key_exists('Code', $result) && $result->Code=='90001')
                    <div class="alert alert-warning"> ไม่มีผู้ล็อคอิน API ของกรมการปกครอง กรุณาลองอีกครั้งในเวลาทำการ </div>
                @elseif($result->Message=='CitizenID is not specify')
                    <div class="alert alert-warning"> รูปแบบเลขประจำตัวประชาชนไม่ถูกต้อง </div>
                @else
                    <div class="alert alert-warning"> {{ $result->Message }} </div>
                @endif
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
