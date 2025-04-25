

@extends('layouts.print')
@push('styles')
    <style type="text/css">

        .hr {
            border: 1px solid black;
        }

        .font_pending{
            padding-top:-1px !important;
            /* font-weight:100; */
        }

        .font_span{
            border-bottom: 1px dotted black;
            padding-bottom: 0px;
        }
        .font-14 {
            font-size: 14pt;
            letter-spacing: 0px !important;
        }


        .font-16 {
            font-size: 16pt;
        }



        .font-bold {
            font-weight: bold;
        }

        @page {
            /* size: auto;
            padding-top: 35px;
            padding-bottom: 30px;
            margin-left: 10%;
            margin-right:  10%; */
        }

    </style>

@endpush
@section('content')

    @php
        $inspectors = $application->inspectors;
        $agreement = $application->inspector_agreement;
    @endphp
    <div class="row">
        <table style="width:100%;">
            <tbody>
                <tr>
                    <td align="center" class="font-16 font-bold">เงื่อนไขการขึ้นทะเบียนผู้ตรวจและผู้ประเมิน</td>
                </tr>
                <tr>
                    <td align="center" class="font-16 font-bold">สำหรับผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</td>
                </tr>
                <tr>
                    <td align="center" class="font-bold"><hr></td>
                </tr>
            </tbody>
        </table>

        <table style="width:100%;">
            <tbody>
                <tr>
                    <td class="font-14 font_pending">
                        {!! str_repeat("&nbsp;", 25) !!} ตามที่สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ได้ประกาศ เกณฑ์การประเมินความสามารถ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        ผู้ตรวจและผู้ประเมิน และเงื่อนไขการขึ้นทะเบียน เมื่อวันที่ 30 กันยายน พ.ศ. 2564 ซึ่งประกาศดังกล่าวได้กำหนด
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        เงื่อนไขผู้ตรวจและผู้ประเมิน ที่ได้รับการขึ้นทะเบียนต้องปฏิบัติตาม นั้น
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        {!! str_repeat("&nbsp;", 25) !!} สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม จึงแจ้งเงื่อนไขสำหรับผู้ตรวจและผู้ประเมินที่ได้รับ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        การขึ้นทะเบียน สำหรับผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        ชื่อผู้ตรวจ/ผู้ประเมิน ที่ได้รับการขึ้นทะเบียน: {!! !empty($application->applicant_full_name)?$application->applicant_full_name:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        สาขาที่ขึ้นทะเบียน: รายละเอียดดังเอกสารแนบท้าย
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        รายสาขาที่ขึ้นทะเบียน: รายละเอียดดังเอกสารแนบท้าย
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        ชื่อหน่วยงานผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม: {!! !empty($application->agency_name)?$application->agency_name:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        สถานที่ประกอบการตั้งอยู่เลขที่: {!! !empty($application->AgencyDataAdress)?$application->AgencyDataAdress:'-' !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        ต้องปฏิบัติตามเงื่อนไข ดังนี้
                    </td>
                </tr>

                <tr>
                    <td class="font-14 font_pending">
                        1. ปฏิบัติตามประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เรื่อง เกณฑ์การประเมินความสามารถผู้ตรวจ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        และผู้ประเมิน และเงื่อนไขการขึ้นทะเบียน ประกาศ ณ วันที่ 30 กันยายน พ.ศ.2564 ตลอดเวลาที่ได้รับ การขึ้นทะเบียน
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        2. มีนิติสัมพันธ์กับหน่วยตรวจ หรือหน่วยรับรอง พร้อมแสดงหลักฐาน ในลักษณะใดลักษณะหนึ่ง ดังต่อไปนี้
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        {!! str_repeat("&nbsp;", 4) !!}(1) การเป็นลูกจ้างประจำ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        {!! str_repeat("&nbsp;", 4) !!}(2) การเป็นลูกจ้างภายนอก (ผู้ตรวจอิสระ)
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        3. ไม่นำการได้รับการขึ้นทะเบียนไปใช้ในทางที่ทำให้เกิดความเสื่อมเสียต่อ สมอ. หรืออ้างถึงการขึ้นทะเบียน ซึ่ง
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        สมอ. อาจพิจารณาได้ว่าทำให้เกิดการเข้าใจผิดหรือไม่ได้รับอนุญาต
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        4. ส่งมอบเอกสารหลักฐานต่าง ๆ ทั้งหมดให้ สมอ. เมื่อได้รับการร้องขอ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        5. ในกรณีที่ผู้ตรวจ หรือผู้ประเมิน ที่ได้รับการขึ้นทะเบียนถูกร้องเรียนไม่ว่ากรณีใด ให้แจ้งเป็นลายลักษณ์อักษรพร้อมทั้ง
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        หลักฐานเอกสารต่าง ๆ แก่ สมอ. ทันที และให้ส่งผลการดำเนินการต่อข้อร้องเรียนเมื่อได้ดำเนินการเสร็จสิ้นแล้วในภายหลัง
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        6. ผู้ตรวจ และผู้ประเมินที่ได้รับการขึ้นทะเบียนต้องลงนามรับรองเพื่อฏิบัติตามหลักเกณฑ์และเงื่อนไข
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        และจรรยาบรรณในข้อ 7 และกรณีมีข้อร้องเรียนเกี่ยวกับสมรรถนะของผู้ตรวจ หรือผู้ประเมินที่ได้รับการขึ้น
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                       ทะเบียนจะต้องดำเนินการต่อข้อร้องเรียน และป้องกันการเกิดซ้ำ พร้อมทั้งเก็บบันทึกผลการดำเนินการ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                        7. กรณีที่มีการเปลี่ยนแปลงที่มีนัยสำคัญ ซึ่งอาจกระทบต่อความสามารถในการตรวจ ต้องแจ้งให้ สมอ. ทราบเป็นหนังสือ
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                       ทั้งนี้มีผลตั้งแต่วันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->start_date)?HP::formatDateThaiFullPoint( $agreement->start_date):null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>จนถึงวันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->end_date)?HP::formatDateThaiFullPoint( $agreement->end_date):null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                </tr>
                <tr>
                    <td class="font-14 font_pending">
                       ขึ้นทะเบียนครั้งแรกเมื่อวันที่ <span class="font_span">&nbsp;&nbsp;&nbsp;&nbsp;{!! !is_null($agreement) && !empty($agreement->first_date)? HP::formatDateThaiFullPoint( $agreement->first_date) : null !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width:100%;">
            <tbody>
                <tr>
                    <td width="60%"></td>
                    <td class="font-14 font_pending">
                        ลงนาม
                    </td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td align="center" class="font-14 font_pending">
                        ( {!! !empty($application->applicant_full_name)?$application->applicant_full_name:'-' !!} )
                    </td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td align="center" class="font-14 font_pending">
                        ผู้ขอรับการขึ้นทะเบียน
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
