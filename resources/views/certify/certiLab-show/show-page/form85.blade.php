
@php
$labRequestBranchs = [];
  if ($labCalRequest->count() != 0) {
      $labRequestBranchs = $labCalRequest->where('type',2);
  }else if($labTestRequest->count() != 0)
  {
      $labRequestBranchs = $labTestRequest->where('type',2);
  }
@endphp

<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>รายละเอียดที่อยู่ห้องปฏิบัติการ landing
        
        {{-- @if ($labRequestBranchs->count()  != 0)
         (สถานปฏิบัติการหลายสถานที่)
        @endif --}}

        @if (!empty($labRequestBranchs) && $labRequestBranchs->count() != 0)
            (สถานปฏิบัติการหลายสถานที่)
        @endif
    </h3>
        </legend>    
    </div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">

        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                        วัตถุประสงค์ในการยื่นคำขอ : 
                 </div>
                 <div class="col-md-5 text-left">
                  <label for="purpose_type">
                    @if ($certi_lab->purpose_type == 1)
                            <label for="">ขอใบรับรอง</label>
                     @elseif ($certi_lab->purpose_type == 2)
                            <label for="">ต่ออายุใบรับรอง</label>
                    @elseif ($certi_lab->purpose_type == 3)
                            <label for="">ขยายขอบข่าย</label>
                     @endif
                </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    อ้างอิงเลขที่คำขอ : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name">
                        {{ @$certi_lab->certificate_exports_id }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    ใบรับรองเลขที่ : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name">
                        {{ $certi_lab->certificate_exports_id }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    หมายเลขการรับรอง : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name">
                        {{ $certi_lab->accereditation_no }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                       ความสามารถห้องปฏิบัติการ : 
                 </div>
                 <div class="col-md-5 text-left">
                    <label for="lab_type">
                        @if ($certi_lab->lab_type == 3)
                        <label for="">ทดสอบ</label>
                        @elseif($certi_lab->lab_type == 4)
                            <label for="">สอบเทียบ</label>
                        @endif
                    </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                      ชื่อห้องปฏิบัติการ : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name">
                        {{ $certi_lab->lab_name }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                     ชื่อห้องปฏิบัติการ (eng) : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name_en">
                        {{ $certi_lab->lab_name_en ?? null }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                      ชื่อย่อห้องปฏิบัติการ  : 
                 </div>
                 <div class="col-md-8 text-left">
                    <label for="lab_name_short">
                        {{ $certi_lab->lab_name_short ?? null }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    เลขที่ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="lab_name">
                        {{ $certi_lab->address_no }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    หมู่ที่ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="allay">
                        {{ $certi_lab->allay }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    ตรอก/ซอย : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="village_no">
                        {{ $certi_lab->village_no }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    ถนน : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="road">
                        {{ $certi_lab->road }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    จังหวัด : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="province">
                        {{ $certi_lab->basic_province->PROVINCE_NAME  ?? '-' }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    เขต/อำเภอ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="amphur">
                        {{-- {{ $certi_lab->basic_amphur->AMPHUR_NAME  ?? '-' }} --}}
                        {{ $certi_lab->amphur  ?? '-' }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    แขวง/ตำบล : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="district">
                        {{-- {{ $certi_lab->basic_district->DISTRICT_NAME  ?? '-' }} --}}
                        {{ $certi_lab->district  ?? '-' }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    รหัสไปรษณีย์ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="postcode">
                        {{ $certi_lab->postcode }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    โทรศัพท์ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="tel">
                        {{ $certi_lab->tel }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    โทรสาร : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="tel_fax">
                        {{ $certi_lab->tel_fax }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    บุคคลติดต่อ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="contactor_name">
                        {{ $certi_lab->contactor_name }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    Email : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="email">
                        {{ $certi_lab->email }}
                   </label>
                 </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-4 text-right">
                    โทรศัพท์ผู้ติดต่อ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="contact_tel">
                        {{ $certi_lab->contact_tel }}
                   </label>
                 </div>
                 <div class="col-md-2 text-right">
                    โทรศัพท์มือถือ : 
                 </div>
                 <div class="col-md-3 text-left">
                    <label for="telephone">
                        {{ $certi_lab->telephone }}
                   </label>
                 </div>
            </div>
        </div>

    </div>
  </div>

    <div class="box-title">
        <legend><h3>รายละเอียดที่อยู่ห้องปฏิบัติการ EN</h3></legend>    
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        เลขที่ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="lab_name">
                            {{ $certi_lab->lab_address_no_eng }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        หมู่ที่ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="allay">
                            {{ $certi_lab->lab_moo_eng }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        ตรอก/ซอย : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="village_no">
                            {{ $certi_lab->lab_soi_eng }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        ถนน : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="road">
                            {{ $certi_lab->lab_street_eng }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        จังหวัด : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="province">
                            {{ $certi_lab->lab_province_eng->PROVINCE_NAME  ?? '-' }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        เขต/อำเภอ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="amphur">
                            {{-- {{ $certi_lab->basic_amphur->AMPHUR_NAME  ?? '-' }} --}}
                            {{ $certi_lab->lab_amphur_eng  ?? '-' }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        แขวง/ตำบล : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="district">
                            {{-- {{ $certi_lab->basic_district->DISTRICT_NAME  ?? '-' }} --}}
                            {{ $certi_lab->lab_district_eng  ?? '-' }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        รหัสไปรษณีย์ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="postcode">
                            {{ $certi_lab->lab_postcode_eng }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        พิกัดที่ตั้ง (ละติจูด) : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="tel">
                            {{ $certi_lab->lab_latitude }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        พิกัดที่ตั้ง (ลองจิจูด) : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="tel_fax">
                            {{ $certi_lab->lab_longitude }}
                    </label>
                    </div>
                </div>
            </div>
        </div>
    </div>



@if (!empty($labRequestBranchs) && $labRequestBranchs->count() != 0)
<hr>
    @foreach ($labRequestBranchs as $labRequestBranch)
    <div class="box-title">
        <legend><h3>รายละเอียดที่อยู่ห้องปฏิบัติการ (สาขา{{trim($labRequestBranch->tambol_name)}}: {{trim($labRequestBranch->province_name)}})</h3></legend>    
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        เลขที่ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="lab_name">
                            {{ $labRequestBranch->no }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        หมู่ที่ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="allay">
                            {{ $labRequestBranch->moo }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        ตรอก/ซอย : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="village_no">
                            {{ $labRequestBranch->soi }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        ถนน : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="road">
                            {{ $labRequestBranch->street }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        จังหวัด : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="province">
                            {{ $labRequestBranch->province_name  ?? '-' }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        เขต/อำเภอ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="amphur">

                            {{ $labRequestBranch->amphur_name  ?? '-' }}
                    </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 text-right">
                        แขวง/ตำบล : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="district">

                            {{ $labRequestBranch->tambol_name  ?? '-' }}
                    </label>
                    </div>
                    <div class="col-md-2 text-right">
                        รหัสไปรษณีย์ : 
                    </div>
                    <div class="col-md-3 text-left">
                        <label for="postcode">
                            {{ $labRequestBranch->postal_code }}
                    </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif



</div>