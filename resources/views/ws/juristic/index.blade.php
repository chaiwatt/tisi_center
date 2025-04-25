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
        <h3 class="box-title pull-left">ค้นหาข้อมูลนิติบุคคล กรมพัฒนาธุรกิจการค้า (DBD)</h3>

        <a class="btn btn-success pull-right" href="{{ url()->previous() }}">
            <i class="icon-arrow-left-circle"></i> กลับ
        </a>

        <div class="clearfix"></div>

        <hr>

        @if(Session::has('message'))
            <p class="alert alert-danger">{{ Session::get('message') }}</p>
            @php Session::forget('message'); @endphp
        @endif

        {!! Form::model($data, ['url' => '/ws/juristic', 'method' => 'post', 'class' => 'form-horizontal']) !!}

        <div class="col-md-12">

            <div class="form-group">
                {!! Form::label('JuristicID', 'ค้นจากเลขนิติบุคคล:', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-4">
                    {!! Form::text('JuristicID', null, ['class' => 'form-control', 'placeholder'=>'กรอกเลขที่นิติบุคคล 13 หลัก', 'minlength' => 13, 'maxlength' => 13, 'required' => true]) !!}
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

            @if(isset($juristic->status) && $juristic->status=='success' && (isset($juristic->JuristicID) && !is_null(@$juristic->JuristicID)) )

                <div class="row">

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ประเภทนิติบุคคล:</div>
                        <div class="col-md-7">{{ $juristic->JuristicType }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เลขทะเบียนนิติบุคคล:</div>
                        <div class="col-md-7">{{ $juristic->JuristicID }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">วันที่ขึ้นทะเบียนนิติบุคคล:</div>
                        <div class="col-md-7">{{ $juristic->RegisterDate }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">เลขทะเบียนนิติบุคคลเดิม:</div>
                        <div class="col-md-7">{{ $juristic->OldJuristicID }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อนิติบุคคล (ภาษาไทย):</div>
                        <div class="col-md-7">{{ $juristic->JuristicName_TH }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ชื่อนิติบุคคล (ภาษาอังกฤษ):</div>
                        <div class="col-md-7">{{ $juristic->JuristicName_EN }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">จำนวนคณะกรรมการ:</div>
                        <div class="col-md-7">{{ count($juristic->CommitteeInformations) }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ทุนจดทะเบียนนิติบุคคล:</div>
                        <div class="col-md-7">{{ number_format($juristic->RegisterCapital) }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">ทุนจดทะเบียนนิติบุคคลที่ชำระแล้ว:</div>
                        <div class="col-md-7">{{ number_format($juristic->PaidRegisterCapital) }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">จำนวนข้อจุดประสงค์:</div>
                        <div class="col-md-7">{{ $juristic->NumberOfObjective }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">จำนวนหน้าจุดประสงค์:</div>
                        <div class="col-md-7">{{ $juristic->NumberOfPageOfObjective }}</div>
                    </div>

                    <div class="col-md-12 item">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">สถานะปัจจุบันของนิติบุคคล:</div>
                        <div class="col-md-7">
                            @if($juristic->JuristicStatus=='ยังดำเนินกิจการอยู่')
                                <span class="label label-success"><b>{{ $juristic->JuristicStatus }}</b></span>
                            @else
                                {{ $juristic->JuristicStatus }}
                            @endif
                        </div>
                    </div>

                    @if(array_key_exists('CommitteeInformations', $juristic))
                        <div class="col-md-12 item">
                            <div class="col-md-2"></div>
                            <div class="col-md-10 item">ข้อมูลคณะกรรมการจดทะเบียนนิติบุคคล:</div>

                            @foreach ($juristic->CommitteeInformations as $key => $committee)

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">ลำดับที่:</div>
                                    <div class="col-md-6">{{ $committee->Sequence }}</div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">ชื่อ-สกุล:</div>
                                    <div class="col-md-6">
                                        {{ $committee->Title.$committee->FirstName.' '.$committee->LastName }}
                                    </div>
                                </div>

                                <div class="col-md-12 row"><div class="col-md-2"></div><div class="col-md-8"><hr></div></div>

                            @endforeach

                        </div>
                    @endif

                    @if(array_key_exists('AuthorizeDescriptions', $juristic))
                        <div class="col-md-12 item">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">อำนาจกรรมการ:</div>
                            <div class="col-md-7">
                                @foreach ($juristic->AuthorizeDescriptions as $key => $authorizeDescription)
                                    <div>{{ $authorizeDescription->AuthorizeDescription }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(array_key_exists('StandardObjectives', $juristic))
                        <div class="col-md-12 item">
                            <div class="col-md-2"></div>
                            <div class="col-md-3">วัตถุประสงค์:</div>
                            <div class="col-md-7">
                                @foreach ($juristic->StandardObjectives as $key => $StandardObjective)
                                    <div>{{ $StandardObjective->ObjectiveDescription }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(array_key_exists('AddressInformations', $juristic))
                        <div class="col-md-12 item">

                            <div class="col-md-2"></div>
                            <div class="col-md-10">ที่ตั้ง:</div>

                            @foreach ($juristic->AddressInformations as $key => $address)

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">ชื่อที่ตั้ง:</div>
                                    <div class="col-md-6">{{ $address->AddressName }}</div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ที่อยู่เต็ม:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->FullAddress }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        อาคาร:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Building }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ห้อง:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->RoomNo }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ชั้น:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Floor }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        หมู่บ้าน:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->VillageName }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        เลขที่:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->AddressNo }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        หมู่ที่:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Moo }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ซอย:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Soi }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ถนน:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Road }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        ตำบล:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Tumbol }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        อำเภอ:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Ampur }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        จังหวัด:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Province }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        เบอร์โทร:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Phone }}
                                    </div>
                                </div>

                                <div class="col-md-12 row item">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">
                                        อีเมล:
                                    </div>
                                    <div class="col-md-6">
                                        {{ $address->Email }}
                                    </div>
                                </div>

                                <div class="col-md-12 row"><div class="col-md-2"></div><div class="col-md-8"><hr></div></div>

                            @endforeach

                        </div>
                    @endif

                </div>
			@elseif(isset($juristic->result))
                @if($juristic->result=='Bad Request')
                    <div class="alert alert-warning"> ไม่พบข้อมูลนิติบุคคล </div>
                @else
                    <div class="alert alert-warning"> {!! $juristic->result !!} </div>
                @endif
            @elseif(isset($juristic->Result))
                @if($juristic->Result=='Unauthorized')
                    <div class="alert alert-warning"> ไม่มีสิทธิ์ให้ใช้งาน </div>
                @else
                    <div class="alert alert-warning"> {!! $juristic->Result !!} </div>
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
