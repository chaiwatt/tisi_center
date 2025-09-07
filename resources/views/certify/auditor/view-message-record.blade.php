<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>บันทึกข้อความ</title>
    
    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Load Sarabun font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
     body {
        font-family: 'Sarabun', sans-serif;
        font-size: 20px;
        line-height: 1.5;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5; /* สีพื้นหลัง */
    }

    .wrapper {
        max-width: 800px; /* ขนาดความกว้าง */
        margin: 0 auto; /* จัดให้อยู่กึ่งกลางแนวนอน */
        padding: 20px; /* เพิ่มช่องว่างรอบเนื้อหา */
        background-color: #fff; /* สีพื้นหลังของเนื้อหา */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        /* border-radius: 8px;  */
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .header {
        display: flex;
        align-items: center;
    }

    .header img {
        width: 150px;
        height: 120px;
    }

    .header-title {
        padding-top:50px;
        flex: 1;
        text-align: center;
        font-weight: bold;
        font-size: 36px;
    }

    .header-title span{
        margin-left: -100px;
    }
    .section {
        margin-top: 15px;
        margin-bottom: 10px;
    }

    .section-title {
        font-weight: bold;
        margin-left: 110px;
        margin-top:20px;
    }

    .indent {
        margin-left: 5px;
        margin-top:5px;
    }

    .table-section {
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .table-section td {
        font-size: 28px;
        vertical-align: bottom;
    }

    .table-section td:first-child {
        
        font-weight: 500;
    }

    .table-section td:last-child {
        
        /* padding-left: 20px; */
    }

    .under-line {
        border-bottom: 1px dotted #000;
    }

    .input-no-border {
        width: 100%;
        font-size: 22px;
        font-family: 'Sarabun', sans-serif;
        border: none;
        outline: none;
        background-color: #fffdcc; /* พื้นหลังสีเหลืองเริ่มต้น */
        border-bottom: 1px dotted #000;
        color: #000;
        padding: 2px 0;
        transition: background-color 0.3s ease; /* เปลี่ยนสีอย่าง Smooth */
    }

    .input-no-border.has-value,
    .input-no-border:focus {
        background-color: #ffffff; /* พื้นหลังสีขาว */
    }


    /* เพิ่มการตั้งค่าการจัดตำแหน่ง */
    .submit-section {
        text-align: center;  /* ทำให้ปุ่มอยู่ตรงกลาง */
        margin-top: 50px;     /* เว้นระยะห่างจากเนื้อหาด้านบน */
        margin-bottom: 30px;
    }

    .btn-submit {
        font-family: 'Sarabun', sans-serif;
        padding: 10px 20px;  /* เพิ่มขนาดของปุ่ม */
        background-color: #4CAF50; /* สีพื้นหลัง */
        color: white;         /* สีตัวอักษร */
        border: none;         /* ไม่ให้มีขอบ */
        border-radius: 5px;   /* ทำมุมโค้ง */
        font-size: 22px;      /* ขนาดตัวอักษร */
        cursor: pointer;     /* เปลี่ยนรูปแบบเมาส์เมื่อชี้ที่ปุ่ม */
        transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีเมื่อ hover */
    }

    .btn-submit:hover {
        background-color: #45a049;  /* เปลี่ยนสีปุ่มเมื่อ hover */
    }

    /* Custom Alert Styles */
    .custom-alert {
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        border: 1px solid transparent;
    }

    .custom-alert strong {
        font-weight: bold;
    }

    .custom-alert.error {
        background-color: #f8d7da;
        color: #842029;
        border-color: #f5c2c7;
    }

    .custom-alert.success {
        background-color: #d1e7dd;
        color: #0f5132;
        border-color: #badbcc;
    }

    </style>
</head>

<body>
    <div class="wrapper">

        @if ($errors->any())
            <div class="custom-alert error">
                <strong>เกิดข้อผิดพลาด!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Header Section -->
        <div class="header">
            <div>
                {{-- <img src="https://www.thailibrary.in.th/wp-content/uploads/2013/04/krut.jpg" alt="Logo"> --}}
                <img src="{{ asset('images/krut.jpg') }}" alt="Logo">
            </div>
            <div class="header-title">
              <span>บันทึกข้อความ</span>  
            </div>
        </div>

        {{-- <form method="POST" action="{{ route('save.create_lab_message_record') }}">
            @csrf --}}
            <!-- ส่วนราชการ -->
            <input type="hidden" name="id" value="{{$id}}">
            <table class="table-section">
                <tr>
                    <td>ส่วนราชการ</td>
                    <td style="width: 300px;">
                        <input type="text" class="input-no-border" id="header_text1" name="header_text1" value="{{$boardAuditorMsRecordInfo->header_text1}}" required readonly>
                    </td>
                    <td style="font-size:22px">โทรศัพท์</td>
                    {{-- <td style="width: 200px;font-size:22px" class="under-line">{{$data->header_text3}}</td> --}}
                    <td style="width: 235px">
                        <input type="text" class="input-no-border" id="header_text2" name="header_text2" value="{{$boardAuditorMsRecordInfo->header_text2}}" required readonly>
                    </td>
                </tr>
            </table>

            <!-- ที่และวันที่ -->
            <table class="table-section">
                <tr>
                    <td>ที่</td>
                    <td style="width: 380px;">
                        <input type="text" class="input-no-border" id="header_text3" name="header_text3" value="{{$boardAuditorMsRecordInfo->header_text3}}" required readonly>
                    </td>
                    <td>วันที่</td>
                    {{-- <td style="width: 300px;font-size:22px" class="under-line">{{$data->header_text3}}</td> --}}
                    <td style="width: 300px;">
                        <input type="text" class="input-no-border" id="header_text4" name="header_text4" value="{{$boardAuditorMsRecordInfo->header_text4}}" required readonly>
                    </td>
                </tr>
            </table>

            <!-- เรื่อง -->
            <table class="table-section" >
                <tr>
                    <td>เรื่อง</td>
                    <td style="width: 700px;font-size:22px" class="under-line">การแต่งตั้งคณะผู้ตรวจประเมินห้องปฏิบัติการ (คำขอเลขที่ {{$data->app_no}})</td>
                </tr>
            </table>

            <!-- Main Content -->
            <div class="section">
                <div>เรียน ผอ.สก. ผ่าน ผก.รป.<input type="text" class="input-no-border" id="body_text1" name="body_text1" value="{{$boardAuditorMsRecordInfo->body_text1}}" style="width:30px" required readonly></div>
                <div class="section-title" >๑. เรื่องเดิม</div>
       
                {{-- <div class="indent" style="text-indent: 120px;">
                    วันที่ {{$data->register_date}} ชื่อห้องปฏิบัติการ{{$data->lab_name}} ได้ยื่นคำขอรับใบรับรองห้องปฏิบัติการ{{$data->lab_type}} สาขา{{$data->scope_branch}} ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ {{$data->get_date}}
                </div> --}}

                  @if ($certiLab->purpose_type == 1)
                     <div class="indent" style="text-indent: 125px;" >
                        วันที่ {{$data->register_date}} ชื่อห้องปฏิบัติการ{{$data->lab_name}} ได้ยื่นคำขอรับใบรับรองห้องปฏิบัติการ{{$data->lab_type}} สาขา{{$data->scope_branch}} ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ {{$data->get_date}}
                    </div>
                @else
                    <div class="indent" style="text-indent: 125px;" >
                        ๑.๑ ห้องปฏิบัติการ{{$data->lab_name}} ได้รับการรับรองความสามารถห้องปฏิบัติการ{{$data->lab_type}} สาขา{{$data->scope_branch}} ตามมาตรฐานเลขที่ มอก. 17025–2561 หมายเลขการรับรองที่ {{$accereditatioNo}} ใบรับรองเลขที่ {{$certificateNo}} ออกให้ ณ วันที่ {{$startDate}} และสิ้นอายุวันที่ {{$endDate}}
                    </div>
                     <div class="indent" style="text-indent: 125px;" >
                        ๑.๒ เมื่อวันที่ {{$data->register_date}} ชื่อห้องปฏิบัติการ{{$data->lab_name}} ได้ยื่นคำขอรับใบรับรองห้องปฏิบัติการ{{$data->lab_type}} สาขา{{$data->scope_branch}} ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ {{$data->get_date}}


                @endif
            </div>

            <div class="section">
                {!!$data->fix_text1!!}
            </div>

            <div class="section">
                {!!$data->fix_text2!!}
            </div>

            <!-- การดำเนินการ -->
            <div class="section">
                <div class="section-title">๔. การดำเนินการ</div>
                <div style="text-indent: 137px;margin-top:10px;line-height:34px">
                    รป.<input type="text" class="input-no-border" id="body_text2" name="body_text2" value="{{$boardAuditorMsRecordInfo->body_text2}}" style="width:30px" required readonly> สก. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย {{$data->experts}}
                    เพื่อดำเนินการตรวจประเมินให้การรับรองห้องปฏิบัติการ และกำหนดการตรวจประเมินห้องปฏิบัติการ ห้องปฏิบัติการ{{$data->lab_name}} {{$data->date_range}} ซึ่งเห็นสมควรเสนอแต่งตั้งคณะผู้ตรวจประเมินห้องปฏิบัติการ ดังนี้
                </div>
                <div style="margin-top:15px">
                    @php
                        $index = 0;
                    @endphp
                    <table style="margin-left: 110px">
                        @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                        
                            @php
                                $index++;
                            @endphp
    
                            @foreach ($auditorIds as $auditorId)
                                @php
                                    $info = HP::getExpertInfo($statusId, $auditorId);
                                    
                                @endphp
                                <tr>
                                    <td style="width: 200px">{{HP::toThaiNumber($index)}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</td>
                                    <td style="width: 100px">{{$info->auditorInformation->number_auditor}}</td>
                                    <td style="padding-left:20px">{{$info->statusAuditor->title}}</td>
                                </tr>
                            @endforeach
                        
                        @endforeach
                    </table>

                </div>
        
            </div>

            <!-- ข้อปัญหาอุปสรรค -->
            <div class="section">
                <div class="section-title">๕. ข้อปัญหาอุปสรรค</div>
                <div class="indent" style="margin-left:135px">- </div>            
            </div>

            <!-- ข้อพิจารณา -->
            <div class="section">
                <div class="section-title">๖. ข้อพิจารณา</div>
                <div class="indent" style="margin-left:135px">เพื่อโปรดนำเรียน ลมอ. พิจารณาลงนามอนุมัติการแต่งตั้งคณะผู้ตรวจประเมิน</div>           
            </div>

            <!-- ข้อเสนอ -->
            <div class="section">
                <div class="section-title">๗. ข้อเสนอ</div>
                <div class="indent" style="margin-left:135px">จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ</div>  
                <div class="indent">เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเพื่อดำเนินการตรวจประเมินให้การรับรองห้องปฏิบัติการ</div>
                <div class="indent">{{$data->lab_type}} ห้องปฏิบัติการ{{$data->lab_name}} รายละเอียดดังข้างต้น</div>         
            </div>
            {{-- <div class="submit-section">
                <button type="submit" class="btn-submit" >บันทึก</button>
            </div> --}}
        {{-- </form> --}}
    </div>
    

</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const inputFields = document.querySelectorAll("#header_text1, #header_text2, #header_text3, #header_text4, #body_text1, #body_text2");

    // ตรวจสอบสถานะเมื่อเริ่มต้น
    inputFields.forEach(function (input) {
        toggleBackground(input);
    });

    // ตรวจสอบสถานะเมื่อมีการพิมพ์
    inputFields.forEach(function (input) {
        input.addEventListener("input", function () {
            toggleBackground(input);
        });

        // เมื่อโฟกัสหรือหลุดโฟกัส
        input.addEventListener("focus", function () {
            toggleBackground(input);
        });
        input.addEventListener("blur", function () {
            toggleBackground(input);
        });
    });

    function toggleBackground(input) {
        if (input.value.trim() === "") {
            input.classList.remove("has-value");
        } else {
            input.classList.add("has-value");
        }
    }
});



</script>

</html>
