<style>
    @page {
        margin:2%;padding:0;
    }
    body {
        font-family: 'THSarabunNew', sans-serif;
    }
    .content{
        border: 5px solid #d4af37;
        padding: 5%;
        margin: 0px;
        height: 100%;
        top: 10%;
        position: relative;

    }
    .tc{
        text-align: center;
    }
    div{
        width: 100%;
    }
    h1,h2,h3,h4,h5,h6,p{
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    }
    .space{
        height: 20px;
    }
    .space-mini{
        height: 10px;
    }
    b{
        font-weight: bold;
    }
    h1{
        margin-bottom: 10px;
    }
    .w-100{
        width: 100%;
    }
    .tab {
        display:inline-block;
        margin-left: 40px;
    }
    .tr{
        text-align: right;
    }
    .w-66{
        width: 66%;
    }
    .w-33{
        width: 33%;
    }
    .w-15{
        width: 15%;
    }
    .w-50{
        width: 50%;
    }
    table{
        line-height: 2em;
        font-size: 1.2em;
    }

</style>




{{-- HTML--}}
<body>
<div style="top: 40px;left: 82%;position: absolute">
    แบบ กมช./สมอ.๒
</div>
<div class="content">
    <div class="tc">
        <img src="{{ public_path('storage/uploads/certify/certificate-header (1).jpg') }}" width="100px"/>
    </div>
      ใบรับรองเลขที่  {{ $certificate_no }}

    <div class="tc">
        <div class="space"></div>
        <h1><b>ใบรับรองระบบงาน</b></h1>
        <h3><b>อาศัยอำนาจตามความในพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ.  {{ HP::toThaiNumber('2551')}}</b></h3>
        <h3><b>เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</b></h3>
        <h3><b>ออกใบรับรองฉบับนี้ให้</b></h3>
        <div class="space"></div>
        {{-- <h3 style="font-size:{{$certificate_for_font_size}}"><b>{{$certificate_for}}</b></h3> --}}
        <h3 style="font-size:{{$lab_name_font_size}}"><b>{{$app_information_name}}</b></h3>
        <h3 style="font-size:{{$lab_name_font_size}}"><b>{{(!empty($lab_name) || $lab_name!='-')??''}}</b></h3>
        <div class="space"></div>
        <p>ตั้งอยู่เลขที่333</p>
        <p>{{$address}}</p>
        {{-- <p>ได้รับการรับรองความสามารถห้องปฏิบัตการ{{$lab_type == "3" ? "ทดสอบ":"สอบเทียบ"}}</p> --}}
        <p>ได้รับการรับรองความสามารถตามมาตรฐาน</p>
        <p>เลขที่ {{$formula}}</p>
        <p>ข้อกำหนดทั่วไปว่าด้วยความสามารถห้องปฏิบัติการทดสอบและสอบเทียบ</p>
        <div class="space-mini"></div>
        <b>หมายเลขการรับรองที่{{@$laboratory}} {{$accereditatio_no}}</b>
        <div class="space-mini"></div>
        {{-- <p>โดยมีสาขาการรับรองตามรายละเอียดแนบท้ายใบรับรอง</p> --}}
    </div>
    <div class="space"></div>


    {{-- <div class="tl">
        <p>&emsp;&emsp;โดยมี รายละเอียดสาขาและขอบข่ายที่ได้ใบรับรอง แสดงไว้ในรหัสคิวอาร์</p>
        <p>&emsp;&emsp;และ www.tisi.go.th</p>
    </div> --}}

    <div  style="width:40%; float:left;">
           {{-- <p>&emsp;</p>
                <p>&emsp;</p>
                <p>สาขาและขอบข่าย</p>
                <p>ที่ได้ใบรับรอง แสดงไว้ใน</p>
                <p>รหัสคิวอาร์ และ</p>
                <p>www.tisi.go.th</p> --}}
        &emsp;&emsp;
        @if(!is_null($attach_pdf))
        <a href="{{ url('certify/check/files/'.$attach_pdf ) }}"  target="_blank">
            <img src="data:image/png;base64, {!! base64_encode($image_qr) !!} " width="100" height="100">
        </a>
        @endif
    </div>
    <div  style="width:60%; float:left;" class="w-70">
        <p >&emsp;&emsp;&emsp;&emsp;ออกให้ ณ วันที่ {{ HP::formatDateThaiFull($certificate_date_start) ?? null }}</p>
        {{-- <br>
        <p>ลงชื่อ&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</p>
        <p>&emsp;&emsp;&emsp;&emsp;(นายวันชัย พนมชัย)</p>
        <p>เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสากรรม</p> --}}
    </div>
    <div class="tl">
        <p>โดยมีรายละเอียดสาขาและขอบข่าย</p>
        <p>ที่ได้ใบรับรองแสดงไว้ในรหัสคิวอาร์</p>
        <p>และ www.tisi.go.th</p>
    </div>
    <div class="footer">
        <table style="margin-top: 20px;font-size: 1.3em">
            <tr>
                <td style="width: 80%">
                    <p>กระทรวงอุตสาหกรรม สำนักงานมาตรฐานผลิตภัณฑ์อุตสหากรรม</p>
                </td>
                <td class="tr" style="width:15%;">
                    <img src="{{ asset('storage/uploads/certify/ilac.png') }}"/>
                </td>
                <td class="tr" style="width:15%;">
                    <img src="{{ asset('storage/uploads/certify/nc.png') }}"/>
                </td>
            </tr>
        </table>
    </div>

</div>

</body>
