<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>รายงานการตรวจประเมิน</title>
    
    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Load Sarabun font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
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
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .header-request {
        width: 100%;
        text-align: left;
        margin-bottom: -10px;
        padding-left: 10px;
    }

    .header-request span {
        font-size: 18px;
    }

    .header-title {
        padding-top: 20px;
        text-align: center;
        font-weight: bold;
        font-size: 24px;
    }


    /* .header-title span{
        margin-left: -100px;
    } */
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

    .text-area-no-border {
        width: 100%;
        font-size: 18px;
        font-family: 'Sarabun', sans-serif;
        border: none;
        outline: none;
        background-color: #fffdcc; /* พื้นหลังสีเหลืองเริ่มต้น */
        border-bottom: 1px dotted #000;
        color: #000;
        padding: 2px 0;
        transition: background-color 0.3s ease; /* เปลี่ยนสีอย่าง Smooth */
    }

    .text-area-no-border.has-value,
    .text-area-no-border:focus {
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

    .btn-draft {
        font-family: 'Sarabun', sans-serif;
        padding: 10px 20px;  /* เพิ่มขนาดของปุ่ม */
        background-color: #f75e06; /* สีพื้นหลัง */
        color: white;         /* สีตัวอักษร */
        border: none;         /* ไม่ให้มีขอบ */
        border-radius: 5px;   /* ทำมุมโค้ง */
        font-size: 22px;      /* ขนาดตัวอักษร */
        cursor: pointer;     /* เปลี่ยนรูปแบบเมาส์เมื่อชี้ที่ปุ่ม */
        transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีเมื่อ hover */
    }

    .btn-draft:hover {
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

    /* สไตล์สำหรับ overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* แสงสีดำ */
        display: flex;
        flex-direction: column; /* ทำให้ลูกอยู่ในแนวตั้ง */
        justify-content: center;
        align-items: center;
        z-index: 9999; /* อยู่ด้านหน้า */
    }

    /* สไตล์สำหรับสปินเนอร์ */
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #ffffff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    /* Animation สำหรับการหมุน */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* สไตล์สำหรับข้อความ "กำลังบันทึก..." */
    .loading-text {
        color: white;
        font-size: 26px; /* ขนาดข้อความใหญ่ขึ้น */
        margin-top: 15px; /* ให้ข้อความห่างจาก spinner */
        text-align: center;
    }

    /* body {
            font-family: Arial, sans-serif;
            padding: 20px;
        } */

        #toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        button.toolbar {
            padding: 6px 14px;
            font-size: 12px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        button.toolbar:hover {
            background-color: #007bff;
            color: white;
        }

        button.toolbar:active {
            background-color: #0056b3;
            color: white;
        }

        


        .editor {
            width: 780px;
            min-height: 200px;
            max-height: 600px;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ TH Sarabun */
            font-size: 18px;
            padding: 10px;
            border: 1px solid #ccc;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .editor-table {
            border-collapse: collapse;
            min-width: 200px;
            width: auto;
            max-width: 100%;
            table-layout: auto;
        }

        .editor-table, .editor-table th, .editor-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            position: relative;
        }


        .resizer {
            position: absolute;
            right: 0;
            top: 0;
            width: 5px;
            height: 100%;
            cursor: col-resize;
            background: transparent;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            z-index: 1000;
            font-family: Arial, sans-serif;
        }

        .popup label {
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-right: 10px;
        }

        .popup input {
            display: inline-block;
            width: 40px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }

        .popup button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin: 5px;
        }

        .popup button:first-of-type {
            background: #28a745;
            color: white;
        }

        .popup button:last-of-type {
            background: #dc3545;
            color: white;
        }

        .popup button:hover {
            opacity: 0.85;
        }

        #contextMenu {
            position: absolute;
            display: none;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.2);
            padding: 5px;
            z-index: 1000;
        }

        #contextMenu button {
            display: block;
            width: 100%;
            background: none;
            border: none;
            padding: 5px 10px;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }

        #contextMenu button:hover {
            background-color: #007bff;
            color: white;
        }
        .inline-group {
            display: flex;
            align-items: flex-start;
            gap: 5px;
        }

        .label {
            white-space: nowrap;
            font-weight: bold;
            width: 200px;
        }

        .content {
            flex: 1;
            word-wrap: break-word;
        }
        .content .info{
            margin-left:20px
        }
        .spaced {
            display: flex;
            gap: 100px;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: auto auto;
            gap: 10px;
            margin-top:10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
            user-select: none;
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            transform: scale(1.2); /* ปรับขนาด Checkbox */
        }

        .checkbox-item label {
            cursor: pointer;
        }

        .report-table {
            width: 100%;
            overflow-x: auto;
        }

        .report-table table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-family: 'Sarabun', sans-serif;
            font-size: 18px;
            border: 1px solid #000;
        }


        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .report-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .report-table td {
            vertical-align: top;
            text-align: left;
        }

        .report-table td:nth-child(3) {
            text-align: center;
        }

        .report-table td:nth-child(4) {
            text-align: left;
        }

        .report-table tbody tr td {
            padding: 5px;
        }

        .report-table tbody tr:nth-child(odd) {
            background-color: #fafafa;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #fff;
        }

        .evaluation-select {
            font-family: 'Sarabun', sans-serif;
            padding: 3px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px
        }

        .evaluation-checkbox-item {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .evaluation-checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            transform: scale(1.2); /* ปรับขนาด Checkbox */
        }

        .evaluation-checkbox-item label {
            cursor: pointer;
        }

        /* CSS */
        .signature-section {
            display: flex;
            flex-direction: column;
            gap: 15px; /* ระยะห่างระหว่างแต่ละแถว */
            width: 70%; /* กำหนดความกว้าง */
            margin-left: auto; /* ดันให้ติดขวา */
            margin-right: 0;
            text-align: left; /* ตัวอักษรชิดซ้ายใน input */
        }

        .signature-select {
            width: 100%; /* กำหนด select ให้กว้างเต็ม container */
            font-size: 16px;
            font-family: 'Sarabun', sans-serif;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
        }

        /* ปรับขนาด Select2 */
        .select2-container .select2-selection--single {
            height: 38px; /* ปรับความสูง */
            font-size: 18px; /* ปรับขนาดฟอนต์ */
            line-height: 38px; /* จัดข้อความให้อยู่ตรงกลาง */
            display: flex; /* ใช้ Flexbox */
            align-items: center; /* จัดให้อยู่กลางแนวตั้ง */
        }

        .select2-container .select2-selection__rendered {
            font-family: 'Sarabun', sans-serif;
            font-size: 18px; /* ขนาดฟอนต์ที่แสดงผล */
            padding-left: 10px; /* ระยะห่างด้านซ้าย */
        }

        .select2-container .select2-selection__arrow {
            height: 36px; /* ความสูงของลูกศร */
        }

        .select2-container .select2-dropdown {
            font-size: 18px; /* ปรับขนาดตัวเลือกใน dropdown */
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #ccc; /* สีขอบ */
            border-radius: 4px; /* มุมโค้ง */
            display: flex; /* ใช้ Flexbox */
            align-items: center; /* จัดให้อยู่กลางแนวตั้ง */
        }


        .select2-container .select2-search--dropdown .select2-search__field {
            height: 40px; /* ความสูงของช่องค้นหา */
            font-size: 18px; /* ขนาดฟอนต์ */
            padding: 5px 10px; /* ระยะห่างภายใน */
            border: 1px solid #ccc; /* ขอบสีเทา */
            border-radius: 4px; /* มุมโค้ง */
            outline: none; /* เอาเส้นขอบ Highlight ออก */
            box-shadow: none; /* ป้องกันเงาเวลาคลิก */
        }

        .select2-container .select2-search--dropdown .select2-search__field:focus {
            border-color: #999; /* เปลี่ยนสีขอบเมื่อโฟกัส */
        }

        /* สไตล์สำหรับ overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* แสงสีดำ */
            display: flex;
            flex-direction: column; /* ทำให้ลูกอยู่ในแนวตั้ง */
            justify-content: center;
            align-items: center;
            z-index: 9999; /* อยู่ด้านหน้า */
        }

        /* สไตล์สำหรับสปินเนอร์ */
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #ffffff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        /* Animation สำหรับการหมุน */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* สไตล์สำหรับข้อความ "กำลังบันทึก..." */
        .loading-text {
            color: white;
            font-size: 26px; /* ขนาดข้อความใหญ่ขึ้น */
            margin-top: 15px; /* ให้ข้อความห่างจาก spinner */
            text-align: center;
        }

    </style>
    <script>
        let startX, startWidth, resizingCol;
        let editorId;
        let currentEditorId = '';
     </script>
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

        <!-- Div สำหรับสถานะการโหลด -->
        <div id="loadingStatus" class="loading-overlay" style="display: none;">
            <div class="spinner"></div>
            <div class="loading-text">กำลังบันทึก...</div>
        </div>
        

        <div class="header">
            <div class="header-request">
                <span>เลขที่คำขอ : CB-65-001</span>
            </div>
            <div class="header-title">
                <span>รายงานการตรวจประเมิน ณ สถานประกอบการ</span>
            </div>
        </div>
        <form id="labMessageForm" method="POST" action="{{ route('save.create_lab_message_record') }}">
            @csrf
            <!-- ส่วนราชการ -->
            <input type="hidden" id="assessment_id" value="{{$assessment->id}}">
            <div class="section">
                <div class="inline-group">
                    <div class="label">1. หน่วยตรวจ : </div>
                    <div class="content">
                        บริษัท เอสจีที เซอร์วิส (ประเทศไทย) จำกัด
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">2. ที่ตั้งสำนักงานใหญ่ : </div>
                    <div class="content">
                        161/113 หมู่บ้านสวนเก้าแสน หมู่ที่ 9 ซอย 8 ถนนเทพารักษ์ ก.ม. 17.5 ต.บางปลา อ.บางพลี จ.สมุทรปราการ
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label"></div>
                    <div class="content spaced">
                       <span>โทรศัพท์: <span>-</span> </span>   <span>โทรสาร: <span>-</span></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">&nbsp;&nbsp;&nbsp;ที่ตั้งสำนักงานสาขา : </div>
                    <div class="content">
                        718/54 ถนนลาซาน (สุขุมวิท 105) แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="inline-group">
                    <div class="label"></div>
                    <div class="content spaced">
                       <span>โทรศัพท์: <span>02-348-3355-59</span> </span>   <span>โทรสาร: <span>-</span></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">3. ประเภทการตรวจประเมิน</div>
                <div class="content">
                    <div class="info">
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="chk1">
                                <label for="chk1">การตรวจประเมินรับรองครั้งแรก</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="chk2" checked>
                                <label for="chk2">การตรวจติดตามผลครั้งที่ <span>1</span></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="chk3">
                                <label for="chk3">การตรวจประเมินเพื่อต่ออายุการรับรอง</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="chk4">
                                <label for="chk4">อื่น ๆ</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">4. สาขาและขอบข่ายการรับรองระบบงาน : </div>
                <div class="content">
                    <div class="info">
                        1. scope.pdf
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">5. เกณฑ์ที่ใช้ในการตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px">
                        @include('certify.cb.save_assessment_cb.report.editor', ['id' => '1'])
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">6. วันที่ตรวจประเมิน : </div>
                    <div class="content">
                        23 - 24 ธันวาคม 2567
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">7. คณะผู้ตรวจประเมิน : </div>
                <div class="content">
                    <div class="info">
                        1. นายเอ <br>
                        2. นายบี
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">8. ผู้แทนหน่วยตรวจ : <button class="toolbar" type="button" onclick=""><i class="fas fa-plus"></i></button></div>
                <div class="content">
                    <div class="info">
                        1. นายซี <br>
                        2. นายดี
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">9. เอกสารอ้างอิงที่ใช้ในการตรวจประเมิน : <button class="toolbar" type="button" onclick=""><i class="fas fa-plus"></i></button></div>
                <div class="content">
                    <div class="info">
                        1. aa.pdf <br>
                        2. bb.pdf
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">10. รายละเอียดการตรวจประเมิน : </div>
                <div class="content">
                    <div class="info">
                        <div class="label">10.1 ความเป็นมา </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '2'])
                            </div>
                        </div>
                        <div class="label">10.2 กระบวนการตรวจประเมิน </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '3'])
                            </div>
                        </div>
                        <div class="label">10.3 ประเด็นสำคัญจากการตรวจประเมิน </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '4'])
                            </div>
                        </div>
                        <div class="label">10.4 รายละเอียดการตรวจประเมิน </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px">
                                <div class="report-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th rowspan="2">เกณฑ์ที่ใช้ในการตรวจประเมิน</th>
                                                <th colspan="2">รายการที่ตรวจ</th>
                                                <th rowspan="2">หมายเหตุ</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 110px">ผลการตรวจประเมิน</th>
                                                <th>รายการที่ตรวจ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="group-header">
                                                <td colspan="4">มอก. 17020-2556 และ ILAC-P15: 05/2020</td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 4.1 ความเป็นกลางและความเป็นอิสระ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_401_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_401_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_401_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 4.2 การรักษาความลับ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_402_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_402_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" cols="30" rows="2" id="item_402_comment"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 5.1 คุณลักษณะที่ข้อกำหนดการบริหาร</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_501_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_501_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_501_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 6.1 บุคลากร</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_601_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_601_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_601_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 6.2 สิ่งอำนวยความสะดวกและเครื่องมือ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_602_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_602_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_602_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 6.3 การจ้างเหมาช่วง</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_603_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_603_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_603_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.1 ขั้นตอนการดำเนินงาน และวิธีการตรวจ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_701_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_701_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_701_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.2 การจัดการตัวอย่างและรายงานที่ตรวจ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_702_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_702_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_702_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.3 บันทึกผลการตรวจ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_703_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_703_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_703_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.4 ใบรายงานผลการตรวจและใบรับรองการตรวจ</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_704_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_704_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_704_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.5 การร้องเรียนและการอุทธรณ์</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_705_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_705_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_705_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 7.6 กระบวนการร้องเรียนและการอุทธรณ์</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_706_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_706_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_706_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.1 ทางเลือก</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_801_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_801_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_801_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.2 เอกสารระบบการบริหารงาน (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_802_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_802_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_802_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.3 การควบคุมเอกสาร (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_803_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_803_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_803_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.4 การควบคุมบันทึก (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_804_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_804_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_804_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.5 การทบทวนระบบการบริหารงาน (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_805_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_805_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_805_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.6 การประเมินภายใน (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_806_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_806_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_806_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.7 การฟฎิบัติการแก้ไข (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_807_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_807_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_807_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>ข้อ 8.8 การฟฎิบัติการป้องกัน (ทางเลือก A)</td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="item_808_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="item_808_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="item_808_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 500">หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองหน่วยตรวจ พ.ศ.2564 </td>
                                                <td>
                                                    <div class="evaluation-checkbox-item">
                                                        <input type="checkbox" id="insp_cert_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="insp_cert_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="insp_cert_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 500">กฏกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน </td>
                                                <td>
                                                    <div class="evaluation-checkbox-item" >
                                                        <input type="checkbox" id="reg_std_mark_chk">
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="evaluation-select" id="reg_std_mark_eval_select">
                                                        <option value="">ผลการประเมิน</option>
                                                        <option value="conform">สอดคล้อง</option>
                                                        <option value="nonconform">ไม่สอดคล้อง</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="text-area-no-border" id="reg_std_mark_comment" cols="30" rows="2"></textarea>
                                                </td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="label" style="margin-top: 10px">10.5 ข้อสังเกต </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '5'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">11. สรุปผลการตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px">
                        @include('certify.cb.save_assessment_cb.report.editor', ['id' => '6'])
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">12. ความเห็น/ข้อเสนอแนะของคณะผู้ตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px">
                        @include('certify.cb.save_assessment_cb.report.editor', ['id' => '7'])
                    </div>
                </div>
            </div>
        
            <div class="signature-section" style="margin-top: 30px">
                <legend>ผู้ลงนาม</legend>
                <div style="line-height: 40px">
                   <div>
                       <select class="signature-select" id="signer-1">
                           <option value="">- ผู้ลงนาม -</option>
                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-1" value="" />
                   </div>
                </div>
   
                <div style="line-height: 40px;margin-top:30px">
                   <div>
                       <select class="signature-select" id="signer-2">
                           <option value="">- ผู้ลงนาม -</option>

                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-2" value="" />
                   </div>
                </div>
   
                <div style="line-height: 40px;margin-top:30px">
                   <div>
                       <select class="signature-select" id="signer-3">
                           <option value="">- ผู้ลงนาม -</option>
                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-3" value="" />
                   </div>
                </div>
            </div>

            <div class="submit-section">
                <button type="button" class="btn-draft" >ฉบับร่าง</button>
                <button type="button" id="btn_submit" class="btn-submit" >บันทึก</button>
            </div>
        </form>
    </div>
    

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

$(document).ready(function() {
    const inputFields = $("#header_text1, #header_text2, #header_text3, #header_text4, #body_text1, #body_text2");

    // ตรวจสอบสถานะเมื่อเริ่มต้น
    inputFields.each(function() {
        toggleBackground($(this));
    });

    // ตรวจสอบสถานะเมื่อมีการพิมพ์, focus หรือ blur
    inputFields.on("input focus blur", function() {
        toggleBackground($(this));
    });

    function toggleBackground($input) {
        if ($input.val().trim() === "") {
            $input.removeClass("has-value");
        } else {
            $input.addClass("has-value");
        }
    }

    $('#labMessageForm').on('submit', function(event) {
        event.preventDefault(); // หยุดการส่งฟอร์มตามปกติ


        var isValid = true;
        var errorField = null; // ตัวแปรเก็บฟิลด์ที่พบว่าเป็นข้อผิดพลาด

        // ตรวจสอบแต่ละฟิลด์
        $('#header_text1, #header_text2, #header_text3, #header_text4, #body_text1, #body_text2').each(function() {
            if ($(this).val().trim() === "") {
                isValid = false; // ถ้าพบฟิลด์ที่ว่าง
                if (!errorField) { // ถ้าไม่มีฟิลด์ผิดพลาดที่ถูกเก็บไว้
                    errorField = $(this); // เก็บฟิลด์แรกที่พบข้อผิดพลาด
                }
            }
        });

        if (!isValid) {
            // แสดง alert เมื่อพบข้อผิดพลาด
            alert('กรุณากรอกข้อมูลให้ครบ');
            
            // Scroll ไปที่ฟิลด์แรกที่พบข้อผิดพลาด
            $('html, body').animate({
                scrollTop: errorField.offset().top - 100 // เพิ่ม -100 เพื่อไม่ให้ฟิลด์ซ้อนกับหัว
            }, 500);

            return; // หยุดการทำงานของฟอร์ม
        } 



        // แสดงสถานะการกำลังบันทึก
        $('#loadingStatus').show();

        var formData = $(this).serialize(); // ดึงข้อมูลฟอร์มทั้งหมด

        $.ajax({
            url: $(this).attr('action'), // URL ที่จะส่งข้อมูลไป
            method: 'POST', // วิธีการส่ง
            data: formData, // ข้อมูลที่ส่งไป
            success: function(response) {
                // ลบสถานะการกำลังบันทึก
                window.location.href = "{{ route('certify.auditor.index') }}";
                // $('#loadingStatus').hide();
            },
            error: function(xhr, status, error) {
                // ลบสถานะการกำลังบันทึก
                $('#loadingStatus').hide();
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        });
    });
});


let data = [
    {
        "eval_riteria_text": "",
        "background_history": "",
        "insp_proc": "",
        "evaluation_key_point": "",
        "observation": "",
        "evaluation_result": "",
        "auditor_suggestion": "",
        "evaluation_detail": {
            "item_401": {
                "chk": $('#item_401_chk').is(':checked'),     
                "eval_select": $('#item_401_eval_select').val(),  // ดึงค่าที่เลือกใน <select>
                "comment": $('#item_401_comment').val()  // ดึงค่าจาก <textarea>      
            },
            "item_402": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_501": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_601": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_602": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_603": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_701": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_702": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_703": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_704": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_705": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_706": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_801": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_802": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_803": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_804": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_805": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_806": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_807": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "item_808": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "insp_cert": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            },
            "reg_std_mark ": {
                "chk": true,      
                "eval_select": 1,
                "comment": ""         
            }
        }
    }
];

function getEditorHTML(editorId) {
    let editorElement = document.getElementById("editor-" + editorId);
    if (!editorElement) {
        console.warn("Editor not found for ID:", editorId);
        return "";
    }

    let editorContent = editorElement.innerHTML;
    return editorContent; // ✅ Return ค่า HTML กลับไป
}

$('#btn_submit').on('click', function() {

   console.log('ok')
    eval_riteria_text = getEditorHTML(1);
    background_history = getEditorHTML(2);
    insp_proc = getEditorHTML(3);
    evaluation_key_point = getEditorHTML(4);
    observation = getEditorHTML(5);
    evaluation_result = getEditorHTML(6);
    auditor_suggestion = getEditorHTML(7);
    
    // console.log(eval_riteria_text)
    // console.log(history)
    // console.log(insp_proc)
    // console.log(evaluation_key_point)
    // console.log(observation)
    // console.log(evaluation_result)
    // console.log(auditor_suggestion)

    data = [
        {
            "eval_riteria_text": eval_riteria_text,
            "background_history": background_history,
            "insp_proc": insp_proc,
            "evaluation_key_point": evaluation_key_point,
            "observation": observation,
            "evaluation_result": evaluation_result,
            "auditor_suggestion": auditor_suggestion,
            "evaluation_detail": {
                "item_401": {
                    "chk": $('#item_401_chk').is(':checked'),
                    "eval_select": $('#item_401_eval_select').val() || null,
                    "comment": $('#item_401_comment').val() || null
                },
                "item_402": {
                    "chk": $('#item_402_chk').is(':checked'),
                    "eval_select": $('#item_402_eval_select').val() || null,
                    "comment": $('#item_402_comment').val() || null
                },
                "item_501": {
                    "chk": $('#item_501_chk').is(':checked'),
                    "eval_select": $('#item_501_eval_select').val() || null,
                    "comment": $('#item_501_comment').val() || null
                },
                "item_601": {
                    "chk": $('#item_601_chk').is(':checked'),
                    "eval_select": $('#item_601_eval_select').val() || null,
                    "comment": $('#item_601_comment').val() || null
                },
                "item_602": {
                    "chk": $('#item_602_chk').is(':checked'),
                    "eval_select": $('#item_602_eval_select').val() || null,
                    "comment": $('#item_602_comment').val() || null
                },
                "item_603": {
                    "chk": $('#item_603_chk').is(':checked'),
                    "eval_select": $('#item_603_eval_select').val() || null,
                    "comment": $('#item_603_comment').val() || null
                },
                "item_701": {
                    "chk": $('#item_701_chk').is(':checked'),
                    "eval_select": $('#item_701_eval_select').val() || null,
                    "comment": $('#item_701_comment').val() || null
                },
                "item_702": {
                    "chk": $('#item_702_chk').is(':checked'),
                    "eval_select": $('#item_702_eval_select').val() || null,
                    "comment": $('#item_702_comment').val() || null
                },
                "item_703": {
                    "chk": $('#item_703_chk').is(':checked'),
                    "eval_select": $('#item_703_eval_select').val() || null,
                    "comment": $('#item_703_comment').val() || null
                },
                "item_704": {
                    "chk": $('#item_704_chk').is(':checked'),
                    "eval_select": $('#item_704_eval_select').val() || null,
                    "comment": $('#item_704_comment').val() || null
                },
                "item_705": {
                    "chk": $('#item_705_chk').is(':checked'),
                    "eval_select": $('#item_705_eval_select').val() || null,
                    "comment": $('#item_705_comment').val() || null
                },
                "item_706": {
                    "chk": $('#item_706_chk').is(':checked'),
                    "eval_select": $('#item_706_eval_select').val() || null,
                    "comment": $('#item_706_comment').val() || null
                },
                "item_801": {
                    "chk": $('#item_801_chk').is(':checked'),
                    "eval_select": $('#item_801_eval_select').val() || null,
                    "comment": $('#item_801_comment').val() || null
                },
                "item_802": {
                    "chk": $('#item_802_chk').is(':checked'),
                    "eval_select": $('#item_802_eval_select').val() || null,
                    "comment": $('#item_802_comment').val() || null
                },
                "item_803": {
                    "chk": $('#item_803_chk').is(':checked'),
                    "eval_select": $('#item_803_eval_select').val() || null,
                    "comment": $('#item_803_comment').val() || null
                },
                "item_804": {
                    "chk": $('#item_804_chk').is(':checked'),
                    "eval_select": $('#item_804_eval_select').val() || null,
                    "comment": $('#item_804_comment').val() || null
                },
                "item_805": {
                    "chk": $('#item_805_chk').is(':checked'),
                    "eval_select": $('#item_805_eval_select').val() || null,
                    "comment": $('#item_805_comment').val() || null
                },
                "item_806": {
                    "chk": $('#item_806_chk').is(':checked'),
                    "eval_select": $('#item_806_eval_select').val() || null,
                    "comment": $('#item_806_comment').val() || null
                },
                "item_807": {
                    "chk": $('#item_807_chk').is(':checked'),
                    "eval_select": $('#item_807_eval_select').val() || null,
                    "comment": $('#item_807_comment').val() || null
                },
                "item_808": {
                    "chk": $('#item_808_chk').is(':checked'),
                    "eval_select": $('#item_808_eval_select').val() || null,
                    "comment": $('#item_808_comment').val() || null
                },
                "insp_cert": {
                    "chk": $('#insp_cert_chk').is(':checked'),
                    "eval_select": $('#insp_cert_eval_select').val() || null,
                    "comment": $('#insp_cert_comment').val() || null
                },
                "reg_std_mark": {
                    "chk": $('#reg_std_mark_chk').is(':checked'),
                    "eval_select": $('#reg_std_mark_eval_select').val() || null,
                    "comment": $('#reg_std_mark_comment').val() || null
                }
            }
        }
    ];

    var formData = new FormData();
    formData.append('_token', "{{ csrf_token() }}");
    formData.append('data', JSON.stringify(data));  // แปลง data เป็น JSON String
    formData.append('id', $('#assessment_id').val()); 
    

    $.ajax({
        type: "POST",
        url: "{{url('/certify/save_assessment-cb/cb-report-store')}}",
        datatype: "script",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {

        }
    });
});




</script>

</html>
