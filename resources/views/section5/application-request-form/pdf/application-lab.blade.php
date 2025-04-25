<table style="width:100%; border-collapse: collapse;overflow: wrap;">
    <tr>
        <td width="100%" style="text-align: center; font-size: 18px;">
            <b>คำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)</b>
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;">
    <tr>
        <td width="85%" style="text-align: right; font-size: 14px;">
            <b>เลขที่คำขอ :</b>
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            {!! $applicationlab->application_no !!}
        </td>
    </tr>
    <tr>
        <td width="85%" style="text-align: right; font-size: 14px;">
            <b>วันที่ยื่นขอ :</b>
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->application_date )?HP::revertDate($applicationlab->application_date, true):null !!}
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;">
    <tr>
        <td width="100%" style="text-align: left; font-size: 14px;">
            <b>1.ข้อมูลผู้ยื่นคำขอ</b>
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="25%" style="text-align: left; font-size: 14px;">
            ประเภทคำขอ
        </td>
        <td width="75%" style="text-align: left; font-size: 14px;">
            {!! in_array( $applicationlab->applicant_type , [1])?'ขอขึ้นทะเบียนใหม่':'ขอเพิ่มเติมขอบข่าย' !!}
        </td>
    </tr>
    @if( in_array( $applicationlab->applicant_type , [2]) )
        <tr>
            <td width="25%" style="text-align: left; font-size: 14px;">
                ห้องปฏิบัติการ
            </td>
            <td width="75%" style="text-align: left; font-size: 14px;">
                {!! !empty($applicationlab->section5_labs)?( $applicationlab->section5_labs->lab_code.' : '.$applicationlab->section5_labs->lab_name):null !!}
            </td>
        </tr>
    @endif
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="25%" style="text-align: left; font-size: 14px;">
            ชื่อนิติบุคคล
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->applicant_name )?$applicationlab->applicant_name:null !!}
        </td>
        <td width="25%" style="text-align: left; font-size: 14px;">
            เลขประจำตัวผู้เสียภาษีอากร
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->applicant_taxid )?$applicationlab->applicant_taxid:null !!}
        </td>
    </tr>
    <tr>
        <td width="25%" style="text-align: left; font-size: 14px;">
            วันเกิด/วันที่จดทะเบียนนิติบุคคล
        </td>
        <td width="75%" style="text-align: left; font-size: 14px;" colspan="3">
            {!! !empty( $applicationlab->applicant_date_niti )?HP::revertDate($applicationlab->applicant_date_niti, true):null !!}
        </td>
    </tr>
</table>
<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td style="text-align: left; font-size: 14px;" colspan="4">
            <b>ข้อมูลที่ตั้งสำนักงานใหญ่</b>
        </td>
    </tr>
</table>
<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            เลขที่
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->hq_address )?$applicationlab->hq_address:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            อาคาร/หมู่บ้าน
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->hq_building )?$applicationlab->hq_building:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            หมู่ที่
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->hq_moo )?$applicationlab->hq_moo:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ตรอก/ซอย
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->hq_soi )?$applicationlab->hq_soi:null !!}
        </td>
    </tr>
    
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ถนน
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->hq_road )?$applicationlab->hq_road:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ตำบล/แขวง
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->HqSubDistrictName )?$applicationlab->HqSubDistrictName:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            อำเภอ/เขต
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->HqDistrictName )?$applicationlab->HqDistrictName:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            จังหวัด
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->HqProvinceName )?$applicationlab->HqProvinceName:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            รหัสไปรษณีย์
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->hq_zipcode )?$applicationlab->hq_zipcode:null !!}
        </td>
        <td style="text-align: left; font-size: 14px;" colspan="3"></td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td style="text-align: left; font-size: 14px;" colspan="4">
            <b>ข้อมูลห้องปฏิบัติการ</b>
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="25%" style="text-align: left; font-size: 14px;">
            ชื่อห้องปฏิบัติการขอรับการแต่งตั้ง
        </td>
        <td width="75%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->lab_name )?$applicationlab->lab_name:null !!}
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            เลขที่
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->lab_address )?$applicationlab->lab_address:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            อาคาร/หมู่บ้าน
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->lab_building )?$applicationlab->lab_building:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            หมู่ที่
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->lab_moo )?$applicationlab->lab_moo:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ตรอก/ซอย
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->lab_soi )?$applicationlab->lab_soi:null !!}
        </td>
    </tr>
    
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ถนน
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->lab_road )?$applicationlab->lab_road:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ตำบล/แขวง
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->LabSubDistrictName )?$applicationlab->LabSubDistrictName:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            อำเภอ/เขต
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->LabDistrictName )?$applicationlab->LabDistrictName:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            จังหวัด
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->LabProvinceName )?$applicationlab->LabProvinceName:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            รหัสไปรษณีย์
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->lab_zipcode )?$applicationlab->lab_zipcode:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            เบอร์โทรศัพท์
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->lab_phone )?$applicationlab->lab_phone:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            เบอร์โทรสาร
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->lab_fax )?$applicationlab->lab_fax:null !!}
        </td>
        <td style="text-align: left; font-size: 14px;" colspan="3"></td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="25%" style="text-align: left; font-size: 14px;" colspan="4">
            <b>ข้อมูลผู้ประสานงาน</b>
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ชื่อผู้ประสานงาน
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->co_name )?$applicationlab->co_name:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            ตำแหน่ง
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->co_position )?$applicationlab->co_position:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            โทรศัพท์มือถือ
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->co_mobile )?$applicationlab->co_mobile:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            โทรศัพท์
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->co_phone )?$applicationlab->co_phone:null !!}
        </td>
    </tr>
    <tr>
        <td width="15%" style="text-align: left; font-size: 14px;">
            โทรสาร
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!!  !empty( $applicationlab->co_fax )?$applicationlab->co_fax:null !!}
        </td>
        <td width="15%" style="text-align: left; font-size: 14px;">
            E-mail
        </td>
        <td width="35%" style="text-align: left; font-size: 14px;">
            {!! !empty( $applicationlab->co_email )?$applicationlab->co_email:null !!}
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;">
    <tr>
        <td width="100%" style="text-align: left; font-size: 14px;">
            <b>2.ข้อมูลขอรับบริการ</b>
        </td>
    </tr>
</table>

<table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
    <tr>
        <td width="100%" style="text-align: left; font-size: 14px;">
            &nbsp; &nbsp;&nbsp; &nbsp;ยื่นคำขอต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรมเพื่อรับการแต่งตั้งเป็นผู้ตรวจสอบผลิตภัณฑ์อุตสาหกรรม ตามมาตรา 5 แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม ดังนี้
        </td>
    </tr>
</table>

@php
    $application_labs_scope = App\Models\Section5\ApplicationLabScope::where('application_lab_id', $applicationlab->id)
                                                                        ->with(['test_item' => function ($q){
                                                                            $orderby  = "CAST(SUBSTRING_INDEX(no,'.',1) as UNSIGNED),";
                                                                            $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',2),'.',-1) as UNSIGNED),";
                                                                            $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',3),'.',-1) as UNSIGNED),";
                                                                            $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',4),'.',-1) as UNSIGNED),";
                                                                            $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',5),'.',-1) as UNSIGNED)";
                                                                            $q->orderBy(DB::raw( $orderby ));
                                                                        }]);
    $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

    $sql_select = "CONCAT(tb3_Tisno, ' : ', tb3_TisThainame) AS standard_title";

    $standards  = App\Models\Basic\Tis::select('tb3_TisAutono', DB::Raw($sql_select))->whereIn('tb3_TisAutono', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'tb3_TisAutono')->toArray();
@endphp

@foreach($application_labs_scope_groups as $tis_id => $application_labs_scope_group)

    <table style="width:100%; border-collapse: collapse;overflow: wrap;  margin-left:10px;">
        <tr>
            <td width="100%" style="text-align: left; font-size: 14px;">
                รายการทดสอบ ตามมาตรฐานเลขที่ <u>{{ array_key_exists($tis_id, $standards)?$standards[$tis_id]:null }}</u>
            </td>
        </tr>
    </table>

    <table style="width:100%; border-collapse: collapse;overflow: wrap;margin-top:10px; margin-bottom:10px; margin-left:10px;">
        <thead>
            <tr style="border: 1px solid black" valign="top">
                <th width="7%"  style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">#</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">รายการทดสอบ</th>
                <th width="15%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">เครื่องมือที่ใช้</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">รหัส/หมายเลข</th>
                <th width="15%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ขีดความสามารถ</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ช่วงการ<br>ใช้งาน</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ความละเอียดที่อ่านได้</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ความคลาดเคลื่อนที่ยอมรับ</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ระยะการทดสอบ(วัน)</th>
                <th width="10%" style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">ค่าใช้จ่ายในการทดสอบ/ชุดละ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($application_labs_scope_group as $key=>$application_labs_scope)
                <tr>
                    <td style="text-align: center; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $key+1 }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{!! $application_labs_scope->TestItemFullName !!}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->TestToolTitle }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->test_tools_no }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->capacity }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->range }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->true_value }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->fault_value }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->test_duration }}</td>
                    <td style="text-align: left; vertical-align: top; font-size: 14px;border: 1px solid black">{{ $application_labs_scope->test_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

<table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px;">
    <tr>
        <td width="35%" style="text-align: left; font-size: 14px;">
            ได้รับใบรับรองระบบงานตามมาตรฐาน 17025
        </td>
        <td width="3%" style="text-align: left; font-size: 14px;">
            <img src="{{ ($applicationlab->audit_type == 1)?public_path('icon/checkbox-circle-checked.png'):public_path('icon/checkbox-circle.png') }}" alt=""  width="13"> 
        </td>
        <td width="27%" style="text-align: left; font-size: 14px;">
            ได้รับ พร้อมแนบหลักฐาน
        </td>
        <td width="3%" style="text-align: left; font-size: 14px;">
            <img src="{{ ($applicationlab->audit_type == 2)?public_path('icon/checkbox-circle-checked.png'):public_path('icon/checkbox-circle.png') }}" alt=""  width="13"> 
        </td>
        <td width="32%" style="text-align: left; font-size: 14px;">
            ไม่ได้รับทำการตรวจประเมินตาม ภาคผนวก ก.
        </td>
    </tr>
</table>

@if($applicationlab->audit_type == 1)

    @if(  isset($applicationlab->id) && count( App\Models\Section5\ApplicationLabCertificate::where('application_lab_id', $applicationlab->id )->get() ) > 0 )

        @php
            $list_cer = App\Models\Section5\ApplicationLabCertificate::where('application_lab_id', $applicationlab->id )->get();
        @endphp

        @foreach ( $list_cer as $keyC => $cer )

            <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px;">
                <tr>
                    <td width="15%" style="text-align: left; font-size: 14px;">
                        ใบรับรองเลขที่
                    </td>
                    <td width="30%" style="text-align: left; font-size: 14px;">
                        {!! !empty($cer->certificate_no)?$cer->certificate_no:null !!}
                    </td>
                    <td width="15%" style="text-align: left; font-size: 14px;">
                        วันที่ได้รับ  
                    </td>
                    <td width="15%" style="text-align: left; font-size: 14px;">
                        {!! !empty($cer->certificate_start_date)?HP::revertDate($cer->certificate_start_date,true):null !!}
                    </td>
                    <td width="10%" style="text-align: left; font-size: 14px;">
                        ถึง
                    </td>
                    <td width="15%" style="text-align: left; font-size: 14px;">
                        {!! !empty($cer->certificate_end_date)?HP::revertDate($cer->certificate_end_date,true):null !!}
                    </td>
                </tr>
            </table>

            <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px; margin-bottom:10px;">
                <tr>
                    <td width="15%" style="text-align: left; font-size: 14px;">
                        หมายเลขการรับรอง
                    </td>
                    <td width="30%" style="text-align: left; font-size: 14px;">
                        {!! !empty($certificate_export->accereditatio_no)?$certificate_export->accereditatio_no:(!empty($cer->accereditatio_no)?$cer->accereditatio_no:null) !!}
                    </td>
                    <td width="15%" style="text-align: left; font-size: 14px;">ไฟล์ใบรับรอง</td>
                    <td width="40%" style="text-align: left; font-size: 14px;">
                        @if( !empty($cer->certificate_file) && empty($cer->certificate_id) )
                            <a href="{!! HP::getFileStorage($cer->certificate_file->url) !!}" target="_blank">ไฟล์ใบรับรอง</a>
                        @elseif( !empty($cer->certificate_id)  )
                            <a href="{!! url('/api/v1/certificate?cer='.(!empty($cer->certificate_no)?$cer->certificate_no:null)) !!}">ไฟล์ใบรับรอง</a>
                        @else
                            ไม่พบไฟล์ใบรับรอง
                        @endif
                    </td>
                </tr>
            </table>

        @endforeach     

    @else
        <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px;">
            <tr>
                <td width="15%" style="text-align: left; font-size: 14px;">ใบรับรองเลขที่</td>
                <td width="30%" style="text-align: left; font-size: 14px;"></td>
                <td width="15%" style="text-align: left; font-size: 14px;">วันที่ได้รับ</td>
                <td width="15%" style="text-align: left; font-size: 14px;"></td>
                <td width="10%" style="text-align: left; font-size: 14px;">ถึง</td>
                <td width="15%" style="text-align: left; font-size: 14px;"></td>
            </tr>
        </table>

        <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px; margin-bottom:10px;">
            <tr>
                <td width="15%" style="text-align: left; font-size: 14px;">หมายเลขการรับรอง</td>
                <td width="30%" style="text-align: left; font-size: 14px;"></td>
                <td width="55%" style="text-align: left; font-size: 14px;"></td>
            </tr>
        </table>
    @endif

@endif

@if($applicationlab->audit_type == 2)
    @if(  isset($applicationlab->id) && !empty($applicationlab->audit_date) )
        @php
            $audit_date = json_decode($applicationlab->audit_date);
        @endphp

        @foreach (  $audit_date as $audit_dates )
            <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px; margin-bottom:10px;">
                <tr>
                    <td width="25%" style="text-align: left; font-size: 14px;">ช่วงวันที่พร้อมให้เข้าตรวจประเมิน</td>
                    <td width="15%" style="text-align: left; font-size: 14px;">{!! !empty($audit_dates->audit_date_start)?HP::revertDate($audit_dates->audit_date_start,true):null !!}</td>
                    <td width="5%" style="text-align: left; font-size: 14px;">ถึง</td>
                    <td width="15%" style="text-align: left; font-size: 14px;">{!! !empty($audit_dates->audit_date_end)?HP::revertDate($audit_dates->audit_date_end, true):null !!}</td>
                    <td width="40%" style="text-align: left; font-size: 14px;"></td>
                </tr>
            </table>

        @endforeach
    @else
        <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px; margin-bottom:10px;">
            <tr>
                <td width="25%" style="text-align: left; font-size: 14px;">ช่วงวันที่พร้อมให้เข้าตรวจประเมิน</td>
                <td width="15%" style="text-align: left; font-size: 14px;"></td>
                <td width="5%" style="text-align: left; font-size: 14px;">ถึง</td>
                <td width="15%" style="text-align: left; font-size: 14px;"></td>
                <td width="40%" style="text-align: left; font-size: 14px;"></td>
            </tr>
        </table>
    @endif

@endif

<table style="width:100%; border-collapse: collapse;overflow: wrap;">
    <tr>
        <td width="100%" style="text-align: left; font-size: 14px;">
            <b>3. เอกสารแนบ</b>
        </td>
    </tr>
</table>



@php
    if( isset($applicationlab->id) && !empty($applicationlab->config_evidencce) ){
        $configs_evidences = json_decode($applicationlab->config_evidencce);
    }else{
        $configs_evidences = DB::table((new App\Models\Config\ConfigsEvidence)->getTable().' AS evidences')
                                ->leftjoin((new App\Models\Config\ConfigsEvidenceGroup)->getTable().' AS groups', 'groups.id', '=', 'evidences.evidence_group_id')
                                ->where('groups.id', 3)
                                ->where('evidences.state', 1)
                                ->select('evidences.*')
                                ->orderBy('evidences.ordering')
                                ->get();

    }
@endphp

@foreach ( $configs_evidences as $evidences )
    @php
        $attachment = null;
        if( isset($applicationlab->id) ){
            $attachment = App\AttachFile::where('ref_table', (new App\Models\Section5\Applicationlab )->getTable() )
                            ->where('tax_number', $applicationlab->applicant_taxid)
                            ->where('ref_id', $applicationlab->id )
                            ->when($evidences->id, function ($query, $setting_file_id){
                                return $query->where('setting_file_id', $setting_file_id);
                            })
                            ->first();
        }
    @endphp
    <table style="width:100%; border-collapse: collapse;overflow: wrap; margin-left:10px; margin-bottom:5px;">
        <tr>
            <td width="80%" style="text-align: left; font-size: 14px;">{!! !empty($evidences->title)?$evidences->title:null !!}</td>
            <td width="20%" style="text-align: left; font-size: 14px;">
                @if( is_null($attachment) )
                    ไม่พบไฟล์แนบ
                @else
                    <a name="{!! $evidences->id !!}" href="{!! HP::getFileStorage($attachment->url) !!}" target="_blank">
                        ไฟล์แนบ
                    </a>
                @endif
            </td>
        </tr>
    </table>
@endforeach


