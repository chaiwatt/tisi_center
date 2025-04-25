<div class="row">

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เลขที่ทะเบียนโรงงาน:</div>
        <div class="col-md-7">{{ $factory->FID }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เลขทะเบียนโรงงาน(เดิม):</div>
        <div class="col-md-7">{{ $factory->DISPFACREG }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ชื่อโรงงาน:</div>
        <div class="col-md-7">{{ $factory->FNAME }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">สถานะ:</div>
        <div class="col-md-7">
            @php
                $FFLAGS = [
                            0 => 'อนุญาต',
                            1 => 'ประกอบกิจการ',
                            2 => 'เลิกกิจการ',
                            3 => 'หยุดชั่วคราว',
                          ];
                $FFLAG_STATUS = [
                            0 => 'success',
                            1 => 'success',
                            2 => 'danger',
                            3 => 'danger',
                          ];
            @endphp

            @if (array_key_exists($factory->FFLAG, $FFLAGS))
                {!! '<span class="label label-rounded label-'.$FFLAG_STATUS[$factory->FFLAG].'">'.$FFLAGS[$factory->FFLAG].'</span>' !!}
            @else
                {!! '<span class="label label-rounded label-warning">ไม่ทราบ</span>' !!}
            @endif
        </div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">รหัสประเภทโรงงาน:</div>
        <div class="col-md-7">{!! property_exists($factory, 'CLASS') ? $factory->CLASS : '<i class="text-muted">ไม่มีข้อมูล</i>' !!}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ประเภทโรงงาน:</div>
        <div class="col-md-7">{{ $factory->FACTYPE }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เครื่องจักร 01:</div>
        <div class="col-md-7">{{ number_format($factory->HP, 2) }} HP</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เครื่องจักร 02:</div>
        <div class="col-md-7">{!!  property_exists($factory, 'NUI_HP') ? number_format($factory->NUI_HP, 2).' HP' : '<i class="text-muted">ไม่มีข้อมูล</i>' !!} </div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เงินทุน:</div>
        <div class="col-md-7">{{ number_format($factory->TOTAL_CAP, 2) }} บาท</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">จำนวนคนงาน:</div>
        <div class="col-md-7">{{ number_format($factory->TOTAL_WORKER) }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ขนาดอุตสาหกรรม:</div>
        <div class="col-md-7">{!! property_exists($factory, 'industrial_zone_name') ? $factory->industrial_zone_name : '<i class="text-muted">ไม่มีข้อมูล</i>' !!}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ประเภทอุตสาหกรรม:</div>
        <div class="col-md-7">{{ isset($factory->INDUST_TYPE)?$factory->INDUST_TYPE:null }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">วัตถุประสงค์:</div>
        <div class="col-md-7">{{ $factory->OBJECT }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ชื่อบริษัท:</div>
        <div class="col-md-7">{{ $factory->ONAME }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">วันที่เปิดใช้งาน:</div>
        <div class="col-md-7">{{ $factory->POKDATE }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">วันที่จดทะเบียน:</div>
        <div class="col-md-7">{{ $factory->STARTDATE }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เลขประจำตัวผู้เสียภาษี:</div>
        <div class="col-md-7">{!! property_exists($factory, 'TAX') ? $factory->TAX : '<i class="text-muted">ไม่มีข้อมูล</i>' !!}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เลขประจำตัวผู้นิติบุคคล:</div>
        <div class="col-md-7">{{ $factory->TRADE }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">นิคมอุตสาหกรรม:</div>
        <div class="col-md-7">{{ $factory->estate_name_TH }}</div>
    </div>


    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">เลขที่:</div>
        <div class="col-md-7">{{ $factory->FADDR }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">หมู่:</div>
        <div class="col-md-7">{{ $factory->FMOO }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ซอย:</div>
        <div class="col-md-7">{{ $factory->FSOI }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ถนน:</div>
        <div class="col-md-7">{{ $factory->FROAD }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ตำบล:</div>
        <div class="col-md-7">{{ $factory->FTUMNAME }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">อำเภอ:</div>
        <div class="col-md-7">{{ $factory->FAMPNAME }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">จังหวัด:</div>
        <div class="col-md-7">{{ $factory->FPROVNAME }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">รหัสไปรษณีย์:</div>
        <div class="col-md-7">{{ $factory->FZIPCODE }}</div>
    </div>

    <div class="col-md-12 item">
        <div class="col-md-2"></div>
        <div class="col-md-3">ละติจูด, ลองจิจูด:</div>
        <div class="col-md-7">
            @if (!is_null($factory->LAT) && !is_null($factory->LNG))
                <a data-toggle="modal" data-target="#modal-map-show" style="cursor: pointer;">{{ $factory->LAT }}, {{ $factory->LNG }}</a>
            @else
                <i class="text-muted">ไม่มีข้อมูล</i>
            @endif
        </div>
    </div>

</div>

@push('js')
    @if(isset($factory) && !is_null($factory->LAT) && !is_null($factory->LNG))
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkwr5rmzY9btU08sQlU9N0qfmo8YmE91Y&libraries=places&callback=initAutocomplete" async defer></script>
        <script>
            // This example adds a search box to a map, using the Google Place Autocomplete
            // feature. People can enter geographical searches. The search box will return a
            // pick list containing a mix of places and predicted search terms.
            var markers   = [];
            var latitude  = {{ $factory->LAT }};
            var longitude = {{ $factory->LNG }};
            if(latitude===0 && longitude===0){
                latitude  = 13.765058723286717;
                longitude = 100.52727361839142;
            }

            function initAutocomplete() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: latitude, lng: longitude },
                    zoom: 15,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                markers = new google.maps.Marker({
                    position: { lat: latitude, lng: longitude },
                    map: map,
                });

            }

        </script>
    @endif
@endpush