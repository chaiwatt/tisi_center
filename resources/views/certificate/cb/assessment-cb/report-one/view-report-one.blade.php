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
        font-size: 18px !important;
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
        font-size: 16px;
        font-family: 'Sarabun', sans-serif;
        border: none;
        outline: none;
        background-color: #fffdcc; /* พื้นหลังสีเหลืองเริ่มต้น */
        border-bottom: 1px dotted #000;
        color: #000;
        padding: 2px;
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
            width: 740px;
            min-height: 200px;
            max-height: 600px;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ TH Sarabun */
            font-size: 17px;
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

        /* พื้นหลัง Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* กล่อง Modal */
        .modal-dialog {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        /* หัวข้อ Modal */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* border-bottom: 1px solid #ddd; */
            padding-bottom: 5px;
        }

        .modal-title
        {
            font-size: 20px;
        }

        .modal-footer {
            margin-top: 15px;
            text-align: right;
        }
        /* ปิด Modal */
        .close {
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .close:hover {
            color: red;
        }

        /* ปรับสไตล์ Input */
        .input-field {
            width: 95%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }

        .input-field:focus {
            border-color: #007bff;
        }

        /* ปุ่ม */
        .btn {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #1f794f;
            color: white;
        }

        .btn-secondary {
            background-color: #ccc;
        }

        .btn:hover {
            opacity: 0.8;
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
            <div class="loading-text">กำลังทำรายการ...</div>
        </div>
        
        {{-- <div id="uploadReferenceModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">อัปโหลดเอกสารอ้างอิง</h2>
                        <span class="close" onclick="$('#uploadReferenceModal').css('display', 'none');">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="uploadReferenceForm" enctype="multipart/form-data">
                            <input type="hidden" name="app_certi_cb_id" value="{{ $assessment->app_certi_cb_id ?? '' }}">
                            <input type="hidden" name="ref_id" value="{{ $assessment->id ?? '' }}">
                            <div class="form-group">
                                <label for="referenceFile" style="font-size: 18px">เลือกไฟล์:</label>
                                <input type="file" id="referenceFile" name="file" class="input-field" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="$('#uploadReferenceModal').css('display', 'none');">ปิด</button>
                        <button class="btn btn-primary" id="uploadReferenceBtn">อัปโหลด</button>
                    </div>
                </div>
            </div>
        </div> --}}

        <div id="uploadReferenceModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">อัปโหลดเอกสารอ้างอิง</h2>
                            <span class="close" onclick="$('#uploadReferenceModal').css('display', 'none');">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="uploadReferenceForm" enctype="multipart/form-data">
                                <input type="hidden" name="app_certi_cb_id" value="{{ $assessment->app_certi_cb_id ?? '' }}">
                                <input type="hidden" name="ref_id" value="{{ $assessment->id ?? '' }}">
                                <div class="form-group">
                                    <label for="referenceFile" style="font-size: 18px">เลือกไฟล์:</label>
                                    <input type="file" id="referenceFile" name="file" class="input-field" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" onclick="$('#uploadReferenceModal').css('display', 'none');">ปิด</button>
                            <button class="btn btn-primary" onclick="uploadReference()">อัปโหลด</button>
                            {{-- <button class="btn btn-primary" id="uploadReferenceBtn">อัปโหลด</button> --}}
                            
                            
                            {{-- <button onclick="uploadReference()" class="btn btn-secondary" style="padding:8px 16px; font-size:16px; margin-right:10px; border:none; background-color:#4CAF50; color:white; border-radius:4px; cursor:pointer;">เลือก</button>
                            <button onclick="closeUploadReferenceModal()" class="btn btn-primary" style="padding:8px 16px; font-size:16px; border:none; background-color:#f44336; color:white; border-radius:4px; cursor:pointer;">ยกเลิก</button> --}}
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
        

<div id="modal-add-person" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px 30px; border:1px solid black; border-radius:8px; z-index:1000; width:400px; box-shadow:0px 4px 6px rgba(0,0,0,0.1);">
    <h4 style="text-align:center; font-size:22px; margin-bottom:20px;">บุคคลที่พบ:</h4>
    {{-- <form id="person-form">
        <div style="margin-bottom:15px;">
            <label for="person-name" style="display:block; font-size:18px; margin-bottom:5px;">ชื่อ:</label>
            <input type="text" class="input-border" id="person-name" name="name" required>
        </div>
        <div style="margin-bottom:15px;">
            <label for="person-position" style="display:block; font-size:18px; margin-bottom:5px;">ตำแหน่ง:</label>
            <input type="text" class="input-border" id="person-position" name="position" required>
        </div>
    </form> --}}

        <form id="person-form">
            <div class="form-group">
                <label for="auditorName" style="font-size: 18px">ชื่อ:</label>
                <input type="text" id="person-name" name="name" class="input-field" >
            </div>
            <div class="form-group">
                <label for="auditorPosition" style="font-size: 18px">ตำแหน่ง:</label>
                <input type="text" id="person-position" name="position" class="input-field" >
            </div>
              <div class="form-group">
                <label for="auditorOrg" style="font-size: 18px">หน่วยงาน:</label>
                <input type="text" id="person-org" name="org" class="input-field" >
            </div>
        </form>
    <div style="text-align:center; margin-top:20px;">
        <button onclick="addTextPerson()" class="btn btn-green" style="padding:8px 16px; font-size:16px; margin-right:10px; border:none; background-color:#4CAF50; color:white; border-radius:4px; cursor:pointer;">เพิ่ม</button>
        <button onclick="closeAddPersonModal()" class="btn btn-red" style="padding:8px 16px; font-size:16px; border:none; background-color:#f44336; color:white; border-radius:4px; cursor:pointer;">ยกเลิก</button>
    </div>
</div>

<!-- Modal -->
<div id="auditorModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">เพิ่มผู้แทนหน่วยตรวจ</h2>
                <span class="close">&times;</span>
                
            </div>
            <div class="modal-body">
                <form id="auditorForm">
                    <div class="form-group">
                        <label for="auditorName" style="font-size: 18px">ชื่อ:</label>
                        <input type="text" id="person-name" name="name" class="input-field" >
                    </div>
                    <div class="form-group">
                        <label for="auditorPosition" style="font-size: 18px">ตำแหน่ง:</label>
                        <input type="text" id="person-position" name="position" class="input-field" >
                    </div>
                </form>
            </div>
            <div style="text-align:center; margin-top:20px;">
                <button onclick="addTextPerson()" class="btn btn-green" style="padding:8px 16px; font-size:16px; margin-right:10px; border:none; background-color:#4CAF50; color:white; border-radius:4px; cursor:pointer;">เพิ่ม</button>
                <button onclick="closeAddPersonModal()" class="btn btn-red" style="padding:8px 16px; font-size:16px; border:none; background-color:#f44336; color:white; border-radius:4px; cursor:pointer;">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

        <div class="header">
            <div class="header-request">
                <span>เลขที่คำขอ : {{$data->tracking->reference_refno}}</span>
            </div>
            <div class="header-title">
                <span>รายงานการตรวจประเมิน ณ สถานประกอบการ (แก้ไข)</span>
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
                        {{$certi_cb->name_standard}}
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">2. ที่ตั้งสำนักงานใหญ่ : </div>
                    <div class="content">
                        <div class="inline-block w-78 float-left">
                            @if ($certi_cb->hq_address !== null) เลขที่ {{$certi_cb->hq_address}} @endif 
                            @if ($certi_cb->hq_moo !== null) หมู่{{$certi_cb->hq_moo}} @endif
                            @if ($certi_cb->hq_soi !== null) ซอย{{$certi_cb->hq_soi}} @endif
                            @if ($certi_cb->hq_road !== null) ถนน{{$certi_cb->hq_road}}  @endif
            
                                @if (strpos($certi_cb->HqProvinceName, 'กรุงเทพ') !== false)
                                    <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                    แขวง {{$certi_cb->HqSubdistrictName}} เขต{{$certi_cb->HqDistrictName }} {{$certi_cb->HqProvinceName}}
                                @else
                                    <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                    ตำบล{{$certi_cb->HqSubdistrictName}}  อำเภอ{{$certi_cb->HqDistrictName }}  จังหวัด{{$certi_cb->HqProvinceName}}
                                @endif
                            @if ($certi_cb->hq_zipcode !== null) {{$certi_cb->hq_zipcode}}  @endif
                                
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label"></div>
                    <div class="content spaced">
                       <span>โทรศัพท์: <span>@if ($certi_cb->hq_telephone !== null) {{$certi_cb->hq_telephone}}  @endif</span> </span>   <span>โทรสาร: <span>@if ($certi_cb->hq_fax !== null) {{$certi_cb->hq_fax}}  @endif</span></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">&nbsp;&nbsp;&nbsp;ที่ตั้งสำนักงานสาขา : </div>
                    <div class="content">
                        @if ($certi_cb->address_number !== null) เลขที่ {{$certi_cb->address_number}} @endif 
                        @if ($certi_cb->allay !== null) หมู่{{$certi_cb->allay}} @endif
                        @if ($certi_cb->address_soi !== null) ซอย{{$certi_cb->address_soi}} @endif
                        @if ($certi_cb->address_street !== null) ถนน{{$certi_cb->address_street}}  @endif
        
                            @if (strpos($certi_cb->basic_province->PROVINCE_NAME, 'กรุงเทพ') !== false)
                                <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                แขวง {{$certi_cb->district_id}} เขต{{$certi_cb->amphur_id }} {{$certi_cb->basic_province->PROVINCE_NAME}}
                            @else
                                <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                ตำบล{{$certi_cb->district_id}}  อำเภอ{{$certi_cb->amphur_id }}  จังหวัด{{$certi_cb->basic_province->PROVINCE_NAME}}
                            @endif
                        @if ($certi_cb->postcode !== null) {{$certi_cb->postcode}}  @endif
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="inline-group">
                    <div class="label"></div>
                    <div class="content spaced">
                        <span>โทรศัพท์: <span>@if ($certi_cb->tel !== null){{$certi_cb->tel}}  @endif</span> </span>   <span>โทรสาร: <span>@if ($certi_cb->tel_fax !== null) {{$certi_cb->tel_fax}}  @endif</span></span>
                    </div>
                </div>
            </div>

            @php
                $standard_changes     =  ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
                $standard_change_id = $certi_cb->standard_change

            @endphp 
            <div class="section">
                <div class="label">3. ประเภทการตรวจประเมิน</div>
                <div class="content">
                    <div class="info">
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" disabled @if ($certi_cb->standard_change == 1)
                                    checked
                                @endif>
                                <label for="chk1">การตรวจประเมินรับรองครั้งแรก</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" disabled>
                                <label for="chk2">การตรวจติดตาม</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" disabled @if ($certi_cb->standard_change == 2)
                                    checked
                                @endif>
                                <label for="chk3">การตรวจประเมินเพื่อต่ออายุการรับรอง</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" disabled @if ($certi_cb->standard_change != 1 && $certi_cb->standard_change != 2)
                                    checked
                                @endif>
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
                        @if(count($assessment->FileAttachAssessment2Many) > 0 ) 
                            @foreach($assessment->FileAttachAssessment2Many as  $key => $item)
                                <p>
                                   {{$key+1}}. <a style="text-decoration: none" href="{{url('certify/check/file_cb_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                                    title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                                        {!! HP::FileExtension($item->file)  ?? '' !!} {{$item->file_client_name}}
                                    </a>  
                                </p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">5. เกณฑ์ที่ใช้ในการตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px;margin-left:20px">
                        @include('certify.cb.save_assessment_cb.report.editor', ['id' => '1'])
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="inline-group">
                    <div class="label">6. วันที่ตรวจประเมิน : </div>
                    @php
                        use Carbon\Carbon;
                        
                        $startDate = Carbon::parse($trackingAuditorsDate->start_date);
                        $endDate = Carbon::parse($trackingAuditorsDate->end_date);

                        // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
                        function getThaiMonth($month) {
                            $months = [
                                'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                                'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                                'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                                'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                            ];
                            return $months[$month] ?? $month;
                        }

                        // ดึงวัน เดือน และปี
                        $startDay = $startDate->day;
                        $startMonth = getThaiMonth($startDate->format('F'));
                        $startYear = $startDate->year + 543; // แปลงเป็นปี พ.ศ.

                        $endDay = $endDate->day;
                        $endMonth = getThaiMonth($endDate->format('F'));
                        $endYear = $endDate->year + 543; // แปลงเป็นปี พ.ศ.

                        // ตรวจสอบว่าเป็นวันเดียวกันหรือไม่
                        if ($startDate->equalTo($endDate)) {
                            $formattedDate = "{$startDay} {$startMonth} {$startYear}";
                        } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                            $formattedDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
                        } else {
                            $formattedDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
                        }
                    @endphp
                
                <div class="content">
                    {{ $formattedDate }}
                </div>
                
                </div>
            </div>

            <div class="section">
                <div class="label">7. คณะผู้ตรวจประเมิน : </div>
                <div class="content">
                    @php
                        $auditors_statuses= $trackingAuditor->auditors_status_many;
                    @endphp
                    <div class="info">
                        {{-- @foreach ($certi_cb->CertiCBAuditorsTo->CertiCBAuditorsLists  as $key => $auditor)
                            <p><span style="display: inline-block;width: 300px;">{{$key+1}}. {{$auditor->temp_users}}</span> <span>{{$auditor->StatusAuditorTo->title}}</span> </p>
                        @endforeach --}}

                        @foreach ($auditors_statuses as $auditors_status)
                          @php
                               $auditors = $auditors_status->auditors_list_many; 
                          @endphp

                          @foreach ($auditors as $key => $auditor)
                               <p><span style="display: inline-block;width: 300px;">{{$key+1}}. {{$auditor->temp_users}}</span> <span>{{$auditor->temp_departments}}</span> </p>
                          @endforeach
                            
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">8. ผู้แทนหน่วยตรวจ : <button type="button" onclick="openAddPersonModal()" class="toolbar" ><i class="fas fa-plus"></i></button></div>
                <div class="content">
                    <div class="info">
                        <div id="person_wrapper">
                            {{-- @foreach ($assessment->auditorRepresentatives as $key => $auditorRepresentative)
                                <p>
                                    <span style="display: inline-block;width: 300px;">{{ $key+1 }}. {{ $auditorRepresentative->name }}</span>
                                    <span>{{ $auditorRepresentative->position }}</span>
                                    <span>
                                        <i style="font-size: 12px;color:red" class="fas fa-trash-alt delete-auditor" data-id="{{ $auditorRepresentative->id }}" style="cursor: pointer;"></i>
                                    </span>
                                </p>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">9. เอกสารอ้างอิงที่ใช้ในการตรวจประเมิน : <button id="addReferenceDocument" class="toolbar" type="button" onclick=""><i class="fas fa-plus"></i></button></div>
                <div class="content">
                    <div class="info">
                        <div id="doc_ref_wrapper">
                            {{-- @foreach ($referenceDocuments as $key => $item)
                                <p style="margin-left: 10px">
                                    {{$key+1}}. <a style="text-decoration: none" href="{{url('certify/check/file_cb_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                                        title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                                            {!! HP::FileExtension($item->file)  ?? '' !!} {{$item->file_client_name}}
                                        </a>  
                                    <span>
                                        <i style="font-size: 12px;color:red" class="fas fa-trash-alt delete-reference-document" data-id="{{ $item->id }}" style="cursor: pointer;"></i>
                                    </span>
                                </p>  
                            @endforeach --}}
                        </div>
                    </div>
                    
                    {{-- @if(count($assessment->CertiCBBugMany) > 0)
                        <div class="info">
                            <p style="font-weight: 600">เอกสารแก้ข้อบกพร่อง :</p>
                            @foreach($assessment->CertiCBBugMany as $key => $item)
                                @if (!empty($item->attach_client_name))
                                    @if ($item->file_status == 1)
                                        <p style="margin-left: 10px">
                                            {{$key+1}}. <a style="text-decoration: none" href="{{url('certify/check/file_cb_client/'.$item->attachs.'/'.( !empty($item->attach_client_name) ? $item->attach_client_name :   basename($item->attachs) ))}}" 
                                                title="{{ !empty($item->attach_client_name) ? $item->attach_client_name :  basename($item->attachs) }}" target="_blank">
                                                {{$item->attach_client_name}}
                                            </a>
                                        </p>     
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif  --}}
                </div>
            </div>

            <div class="section">
                <div class="label">10. รายละเอียดการตรวจประเมิน : </div>
                <div class="content">
                    <div class="info">
                        <div class="label">10.1 ความเป็นมา </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px;margin-left:20px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '2'])
                            </div>
                        </div>
                        <div class="label">10.2 กระบวนการตรวจประเมิน </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px;margin-left:20px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '3'])
                            </div>
                        </div>
                        <div class="label">10.3 ประเด็นสำคัญจากการตรวจประเมิน </div> 
                        <div class="content" style="margin-left: -20px">
                            <div style="margin-top: 10px;margin-left:20px">
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
                                                <th rowspan="2" style="width: 150px">หมายเหตุ</th>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                                                        <option value="1">สอดคล้อง</option>
                                                        <option value="2">ไม่สอดคล้อง</option>
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
                            <div style="margin-top: 10px;margin-left:20px">
                                @include('certify.cb.save_assessment_cb.report.editor', ['id' => '5'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">11. สรุปผลการตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px;margin-left:20px">
                        @include('certify.cb.save_assessment_cb.report.editor', ['id' => '6'])
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="label">12. ความเห็น/ข้อเสนอแนะของคณะผู้ตรวจประเมิน: </div>
                <div class="content">
                    <div style="margin-top: 10px;margin-left:20px">
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

            <div class="submit-section" id="button_wrapper">
                <button type="button" id="btn_draft_submit" class="btn-draft" >ฉบับร่าง</button>
                {{-- {{$assessment->bug_report }} --}}
                {{-- @if ($assessment->bug_report == 2 || ($assessment->bug_report == 1 && $assessment->degree == 3)) --}}
                    <button type="button" id="btn_submit" class="btn-submit" >บันทึก</button>
                {{-- @endif --}}
            </div>
        </form>
    </div>
    

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

// var cbReportOne = @json($cbReportOne);
let signAssessmentReportTransactions;
var cbReportOne = @json($cbReportOne ?? []);
var assessment = @json($assessment);


var tracking = @json($tracking ?? []);
signAssessmentReportTransactions = @json($signAssessmentReportTransactions ?? []);

var certi_cb = @json($certi_cb);
let persons = [];
let references = [];


// console.log(assessment);
// console.log('cbReportOne',cbReportOne.item_401_chk);
let data;
const signer = [
    {
        "id": "1",
        "code": "signer-1",
        "signer_id": "",
        "signer_name": "",
        "signer_position": ""
    },
    {
        "id": "2",
        "code": "signer-2",
        "signer_id": "",
        "signer_name": "",
        "signer_position": ""
    },
    {
        "id": "3",
        "code": "signer-3",
        "signer_id": "",
        "signer_name": "",
        "signer_position": ""
    }
]
$(document).ready(function() 
{


    if (cbReportOne.status !== "1") {
        $('#button_wrapper').hide(); 
        $('.wrapper').css({
            'pointer-events': 'none', 
            'opacity': '0.7' 
        });
        $('#files_wrapper').css('pointer-events', 'auto');
        $('.wrapper button').not('#files_wrapper button').hide();
    }

    $('.signature-select').select2({
        allowClear: false
    });


            signAssessmentReportTransactions.forEach((transaction, index) => {
                console.log('index',index)
                if (signer[index]) {
                    signer[index].signer_id = transaction.signer_id || "";
                    signer[index].signer_name = transaction.signer_name || "";
                    signer[index].signer_position = transaction.signer_position || "";
                    const inputElement = $(`#position-${index + 1}`);
                    if (inputElement.length) {
                        inputElement.val(transaction.signer_position || '');
                    }
                }
            });


    let data = [
    {
        "eval_riteria_text": cbReportOne.eval_riteria_text,
        "background_history": cbReportOne.background_history,
        "insp_proc": cbReportOne.insp_proc,
        "evaluation_key_point": cbReportOne.evaluation_key_point,
        "observation": cbReportOne.observation,
        "evaluation_result": cbReportOne.evaluation_result,
        "auditor_suggestion": cbReportOne.auditor_suggestion,
        "evaluation_detail": {
            "item_401": {
                "chk": cbReportOne.item_401_chk === "1",     
                "eval_select": cbReportOne.item_401_eval_select,  
                "comment": cbReportOne.item_401_comment      
            },
            "item_402": {
                "chk": cbReportOne.item_402_chk === "1",      
                "eval_select": cbReportOne.item_402_eval_select,
                "comment": cbReportOne.item_402_comment         
            },
            "item_501": {
                "chk": cbReportOne.item_501_chk === "1",      
                "eval_select": cbReportOne.item_501_eval_select,
                "comment": cbReportOne.item_501_comment         
            },
            "item_601": {
                "chk": cbReportOne.item_601_chk === "1",      
                "eval_select": cbReportOne.item_601_eval_select,
                "comment": cbReportOne.item_601_comment         
            },
            "item_602": {
                "chk": cbReportOne.item_602_chk === "1",      
                "eval_select": cbReportOne.item_602_eval_select,
                "comment": cbReportOne.item_602_comment         
            },
            "item_603": {
                "chk": cbReportOne.item_603_chk === "1",      
                "eval_select": cbReportOne.item_603_eval_select,
                "comment": cbReportOne.item_603_comment         
            },
            "item_701": {
                "chk": cbReportOne.item_701_chk === "1",      
                "eval_select": cbReportOne.item_701_eval_select,
                "comment": cbReportOne.item_701_comment         
            },
            "item_702": {
                "chk": cbReportOne.item_702_chk === "1",      
                "eval_select": cbReportOne.item_702_eval_select,
                "comment": cbReportOne.item_702_comment         
            },
            "item_703": {
                "chk": cbReportOne.item_703_chk === "1",      
                "eval_select": cbReportOne.item_703_eval_select,
                "comment": cbReportOne.item_703_comment         
            },
            "item_704": {
                "chk": cbReportOne.item_704_chk === "1",      
                "eval_select": cbReportOne.item_704_eval_select,
                "comment": cbReportOne.item_704_comment         
            },
            "item_705": {
                "chk": cbReportOne.item_705_chk === "1",      
                "eval_select": cbReportOne.item_705_eval_select,
                "comment": cbReportOne.item_705_comment         
            },
            "item_706": {
                "chk": cbReportOne.item_706_chk === "1",      
                "eval_select": cbReportOne.item_706_eval_select,
                "comment": cbReportOne.item_706_comment         
            },
            "item_801": {
                "chk": cbReportOne.item_801_chk === "1",      
                "eval_select": cbReportOne.item_801_eval_select,
                "comment": cbReportOne.item_801_comment         
            },
            "item_802": {
                "chk": cbReportOne.item_802_chk === "1",      
                "eval_select": cbReportOne.item_802_eval_select,
                "comment": cbReportOne.item_802_comment         
            },
            "item_803": {
                "chk": cbReportOne.item_803_chk === "1",      
                "eval_select": cbReportOne.item_803_eval_select,
                "comment": cbReportOne.item_803_comment         
            },
            "item_804": {
                "chk": cbReportOne.item_804_chk === "1",      
                "eval_select": cbReportOne.item_804_eval_select,
                "comment": cbReportOne.item_804_comment         
            },
            "item_805": {
                "chk": cbReportOne.item_805_chk === "1",      
                "eval_select": cbReportOne.item_805_eval_select,
                "comment": cbReportOne.item_805_comment         
            },
            "item_806": {
                "chk": cbReportOne.item_806_chk === "1",      
                "eval_select": cbReportOne.item_806_eval_select,
                "comment": cbReportOne.item_806_comment         
            },
            "item_807": {
                "chk": cbReportOne.item_807_chk === "1",      
                "eval_select": cbReportOne.item_807_eval_select,
                "comment": cbReportOne.item_807_comment         
            },
            "item_808": {
                "chk": cbReportOne.item_808_chk === "1",      
                "eval_select": cbReportOne.item_808_eval_select,
                "comment": cbReportOne.item_808_comment         
            },
            "insp_cert": {
                "chk": cbReportOne.insp_cert_chk === "1",      
                "eval_select": cbReportOne.insp_cert_eval_select,
                "comment": cbReportOne.insp_cert_comment         
            },
            "reg_std_mark": {
                "chk": cbReportOne.reg_std_mark_chk === "1",      
                "eval_select": cbReportOne.reg_std_mark_eval_select,
                "comment": cbReportOne.reg_std_mark_comment         
            }
        }
    }
];


renderEditorHTML('1',data[0]['eval_riteria_text'])
renderEditorHTML('2',data[0]['background_history'])
renderEditorHTML('3',data[0]['insp_proc'])
renderEditorHTML('4',data[0]['evaluation_key_point'])
renderEditorHTML('5',data[0]['observation'])
renderEditorHTML('6',data[0]['evaluation_result'])
renderEditorHTML('7',data[0]['auditor_suggestion'])

makeTableResizable();

console.log('cbReportOne',cbReportOne)
if (cbReportOne.persons) {
    try {
        persons = JSON.parse(cbReportOne.persons);
        renderPersons();
    } catch (error) {
        console.error('Failed to parse persons data:', error);
        persons = [];
    }
}

if (cbReportOne.attachments && Array.isArray(cbReportOne.attachments) && cbReportOne.attachments.length > 0) {
    references = cbReportOne.attachments.map(attachment => ({
        name: attachment.filename,
        path: attachment.url,
        size: attachment.size,
        mime: attachment.file_properties === 'pdf' ? 'application/pdf' : attachment.file_properties
    }));
    
    renderReferences(references);
}


loadUI(data)


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

    getSigners();
            
    // เพิ่ม event change ให้กับ select แต่ละตัว
    $('.signature-select').on('change', function() {
        const codeId = $(this).attr('id'); // ดึง ID ของ select (เช่น signer-1)
        const selectedValue = $(this).val(); // ดึงค่า (id ของผู้ลงนาม)
        const selectedText = $(this).find('option:selected').text(); // ดึงชื่อผู้ลงนาม
        
        // หาตำแหน่งจาก textbox โดยอ้างอิง ID
        const positionId = codeId.replace('signer', 'position'); // แทนที่ signer ด้วย position
        const positionText = $('#' + positionId).val(); // ดึงค่าจาก textbox

        // อัปเดต signer array
        const signerItem = signer.find(item => item.code === codeId);
        if (signerItem) {
            signerItem.signer_id = selectedValue; // ID ของผู้ลงนามที่ถูกเลือก
            signerItem.signer_name = selectedText; // ชื่อผู้ลงนาม
            signerItem.signer_position = positionText; // ตำแหน่งจาก textbox
        }

        console.log(signer);
    });


});

function getSigners() {
    $.ajax({
        url: "{{ route('assessment_report_assignment.api.get_signers') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}' // ส่ง CSRF token สำหรับ Laravel
        },
        success: function(response) {
            console.log('signers', response);

            // if (response.signers && Array.isArray(response.signers)) {
            //     // ลบ option เก่าใน <select>
            //     $('.signature-select').empty().append('<option value="">- ผู้ลงนาม -</option>');

            //     // เติม option ใหม่
            //     response.signers.forEach(function(signer) {
            //         const option = `<option value="${signer.id}">${signer.name}</option>`;
            //         $('.signature-select').append(option);
            //     });
            // } 

            if (response.signers && Array.isArray(response.signers)) {
                // ลบ option เก่าใน <select>
                $('.signature-select').empty().append('<option value="">- ผู้ลงนาม -</option>');
                
                response.signers.forEach(function(signer) {
                    const option = `<option value="${signer.id}">${signer.name}</option>`;
                    $('.signature-select').append(option);
                });

                // เทียบ signer_id กับ select และเลือก option ให้ตรงกัน
                signer.forEach(function(signerItem) {
                    
                    const selectElement = $(`#${signerItem.code}`);
                    if (selectElement.length) {
                        selectElement.val(signerItem.signer_id).trigger('change');
                    }

                });

            } 
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        }
    });
}

function loadUI(data)
{
    let evaluationData = data[0]['evaluation_detail']; // ดึงข้อมูลจาก data[0]['evaluation_detail']

    // วนลูปทุก key ใน evaluationData
    $.each(evaluationData, function(key, value) {
        // ตั้งค่า checkbox
        $('#' + key + '_chk').prop('checked', value['chk'] == true);

        // ตั้งค่า select
        $('#' + key + '_eval_select').val(value['eval_select']);

        // ตั้งค่า input comment
        $('#' + key + '_comment').val(value['comment']);
    });
}

function renderEditorHTML(editorId,html) {
    let editorElement = document.getElementById("editor-" + editorId);
    if (!editorElement) {
        console.warn("Editor not found for ID:", editorId);
        return "";
    }
    editorElement.innerHTML = html;
}

// function getEditorHTML(editorId) {
//     let editorElement = document.getElementById("editor-" + editorId);
//     if (!editorElement) {
//         console.warn("Editor not found for ID:", editorId);
//         return "";
//     }

//     let editorContent = editorElement.innerHTML;
//     return editorContent;
// }
function getEditorHTML2(editorId) 
{
    let editorElement = document.getElementById(editorId);
    let output = document.getElementById("output");
 

    let range = document.createRange();
    let lines = [];
    let lastTop = null;

    function processNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            let text = node.textContent;
            if (text === "") return;

            let tempLine = "";
            for (let i = 0; i < text.length; i++) {
                range.setStart(node, i);
                range.setEnd(node, i + 1);
                let rects = range.getClientRects();
                if (rects.length > 0) {
                    let top = rects[0].top;

                    if (lastTop === null || Math.abs(top - lastTop) > 5) {
                        if (tempLine !== "") {
                            lines.push(tempLine);
                            tempLine = "";
                        }
                    }

                    tempLine += text[i];
                    lastTop = top;
                }
            }
            if (tempLine !== "") lines.push(tempLine);
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            if (node.tagName === "TABLE" || node.tagName === "UL" || node.tagName === "OL" || node.tagName === "B" ) {
                lines.push(node.outerHTML);
                return;
            }
            else if (node.tagName === "BR") {
                lines.push("<br>");
                return;
            }
            else if (node.tagName === "DIV" && node.innerHTML.trim() === "<br>") {
                lines.push("<br>");
                return;
            }
            else if (node.tagName === "DIV") {
                let childLines = [];
                node.childNodes.forEach(child => {
                    processNode(child);
                });
                lines.push("<br>"); // ขึ้นบรรทัดใหม่เมื่อจบ <div>
                return;
            }
            node.childNodes.forEach(child => processNode(child));
        }
    }

    editorElement.childNodes.forEach(node => processNode(node));

    let result = lines.map((line, index) => {

        line = line.replace(/ /g, '&nbsp;');
        
        if (line.startsWith("<table") || line.startsWith("<ul") || line.startsWith("<ol") || line === "<br>") {
            return line;
        }
        return line + "<br>";
    }).join("");

    result = result.replace(/(<br>\s*){2,}/g, "<br>");

    return result
  
}

function getEditorHTML(editorId) 
{
    let editorElement = document.getElementById("editor-" + editorId);

    let range = document.createRange();
    let lines = [];
    let lastTop = null;

    function processNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            let text = node.textContent;
            if (text === "") return;

            let tempLine = "";
            for (let i = 0; i < text.length; i++) {
                range.setStart(node, i);
                range.setEnd(node, i + 1);
                let rects = range.getClientRects();
                if (rects.length > 0) {
                    let top = rects[0].top;

                    if (lastTop === null || Math.abs(top - lastTop) > 5) {
                        if (tempLine !== "") {
                            lines.push(tempLine);
                            tempLine = "";
                        }
                    }

                    tempLine += text[i];
                    lastTop = top;
                }
            }
            if (tempLine !== "") lines.push(tempLine);
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            if (node.tagName === "TABLE" || node.tagName === "UL" || node.tagName === "OL" || node.tagName === "B") {
                lines.push(node.outerHTML);
                return;
            }
            else if (node.tagName === "BR") {
                lines.push("<br>");
                return;
            }
            else if (node.tagName === "DIV" && node.innerHTML.trim() === "<br>") {
                lines.push("<br>");
                return;
            }
            else if (node.tagName === "DIV") {
                node.childNodes.forEach(child => {
                    processNode(child);
                });
                lines.push("<br>"); // ขึ้นบรรทัดใหม่เมื่อจบ <div>
                return;
            }
            node.childNodes.forEach(child => processNode(child));
        }
    }

    editorElement.childNodes.forEach(node => processNode(node));

    while (lines.length > 0 && lines[lines.length - 1] === "<br>") {
        lines.pop();
    }

    return lines.join("");
}


$('#btn_draft_submit').on('click', function() 
{
    submit_form("1");
});

$('#btn_submit').on('click', function() 
{
    submit_form("2");
});



function submit_form(status) 
{

    let isComplete = signer.every(item => item.signer_name);
        console.log(signer);
    if (!isComplete) {
        console.warn('กรุณาเลือกผู้ลงนาม');
        alert('กรุณาเลือกผู้ลงนาม');
        return;
    }

    signer.forEach(function(item, index) {
    let positionInput = $(`#position-${index + 1}`); // ดึงค่าจาก input
            if (positionInput.length) {
                item.signer_position = positionInput.val(); // อัปเดต signer_position
            }
        });

    isComplete = signer.every(item => item.signer_position);
    // console.log(signer);
    if (!isComplete) {
        alert('กรุณากรอกข้อมูลตำแหน่งผู้ลงนาม');
        return;
    }

    eval_riteria_text = getEditorHTML2("editor-1");
    background_history = getEditorHTML2("editor-2");
    insp_proc = getEditorHTML2("editor-3");
    evaluation_key_point = getEditorHTML2("editor-4");
    observation = getEditorHTML2("editor-5");
    evaluation_result = getEditorHTML2("editor-6");
    auditor_suggestion = getEditorHTML2("editor-1");
    


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
    formData.append('signer', JSON.stringify(signer));
    formData.append('persons', JSON.stringify(persons));
    formData.append('status', status);
    //  formData.append('assessment', assessment);

    references.forEach((reference, index) => {
            formData.append(`references[${index}]`, reference.file);
        });

    

    $('#loadingStatus').show();
    $('#btn_draft_submit, #btn_submit').prop('disabled', true);
    $.ajax({
        type: "POST",
        // url: "{{url('/certify/save_assessment-cb/cb-report-store')}}",
        url: "{{ route('certificate.assessment-cb.update_cb_report_one') }}",
        datatype: "script",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {  
            const baseUrl = "{{ url('/certificate/tracking-cb') }}";

            const token = certi_cb.token;
            const appId = certi_cb.id;
            // window.location.href = `${baseUrl}/${token}/show/${appId}`;
            window.location.href = baseUrl+`/`+tracking.id+`/edit`;
            $('#loadingStatus').hide();
        }
    });
};

// เปิด Modal
$("#addAuditorRepresentative").click(function() {
    $("#auditorModal").css("display", "flex");
});

// ปิด Modal
$(".close, #closeModal").click(function() {
    $("#auditorModal").css("display", "none");
});

        // บันทึกข้อมูลเมื่อกดปุ่ม "บันทึก"
$("#saveAuditor").click(function() {
    let name = $("#auditorName").val().trim();
    let position = $("#auditorPosition").val().trim();
    let assessment_id = $("#assessment_id").val().trim();

    

    if (name !== "" && position !== "") {
        $('#loadingStatus').show();
        $.ajax({
            type: "post",
            url: "{{ url('/certify/save_assessment-cb/add-auditor-representative') }}",
            data: {
                _token: "{{ csrf_token() }}",
                assessment_id: assessment_id,
                name: name,
                position: position
            },
            success: function(response) {
                $('#loadingStatus').hide();
                 // ล้างค่าฟอร์ม
                $("#auditorName").val('');
                $("#auditorPosition").val('');

                // อัปเดตรายการ
                let html = '';
                console.log(response.auditorRepresentatives)
                response.auditorRepresentatives.forEach((auditor, index) => {
                    html += `<p><span style="display: inline-block;width: 300px;">${index + 1}. ${auditor.name}</span> <span>${auditor.position}</span></p>`;
                });

                $("#auditorRepresentativeWrapper").html(html);

                // ปิด Modal
                $("#auditorModal").css("display", "none");

            }
        });
    } else {
        alert("กรุณากรอกชื่อและตำแหน่ง");
    }
});

$(document).on("click", ".delete-auditor", function() {
    let auditorId = $(this).data("id");
    let assessment_id = $("#assessment_id").val().trim();

    if (confirm("คุณต้องการลบรายการนี้ใช่หรือไม่?")) {
        $('#loadingStatus').show();
        $.ajax({
            type: "POST",
            url: "{{ url('/certify/save_assessment-cb/delete-auditor-representative') }}",
            data: {
                _token: "{{ csrf_token() }}",
                auditor_id: auditorId,
                assessment_id: assessment_id
            },
            success: function(response) {
                $('#loadingStatus').hide();

                // อัปเดตรายการ
                let html = '';
                response.auditorRepresentatives.forEach((auditor, index) => {
                    html += `<p>
                                <span style="display: inline-block;width: 300px;">${index + 1}. ${auditor.name}</span>
                                <span>${auditor.position}</span>
                                <span>
                                    <i style="font-size:12px; color:red" class="fas fa-trash-alt delete-auditor" data-id="${auditor.id}" style="cursor: pointer;"></i>
                                </span>
                            </p>`;
                });

                $("#auditorRepresentativeWrapper").html(html);
            },
            error: function() {
                alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
            }
        });
    }
});


$("#addReferenceDocument").click(function () {
    $("#uploadReferenceModal").css("display", "flex");
});

$("#uploadReferenceBtn").click(function (e) {
    e.preventDefault();
    
    let formData = new FormData($("#uploadReferenceForm")[0]);
    formData.append('_token', "{{ csrf_token() }}");
    $('#loadingStatus').show();
    $.ajax({
        url: "{{ url('/certify/save_assessment-cb/add-reference-document') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#loadingStatus').hide();
            $("#uploadReferenceModal").css("display", "none");
            $("#uploadReferenceForm")[0].reset();
             // เรนเดอร์ข้อมูลไฟล์อ้างอิงใหม่
            let referenceDocuments = response.referenceDocuments;
            let html = "";  // ตัวแปรที่เก็บ HTML ทั้งหมด

            referenceDocuments.forEach(function (referenceDocument, index) {
                let clientName = referenceDocument.file_client_name || 'null';
                let fileName = referenceDocument.file ? referenceDocument.file.split('/').pop() : '';  // ดึงชื่อไฟล์จากพาธ

                let fileUrl = '/certify/check/file_cb_client/' + referenceDocument.file + '/' + clientName;

                let documentHtml = `
                    <p style="margin-left: 10px">
                        ${index + 1}. <a style="text-decoration: none" href="${fileUrl}" 
                            title="${clientName ? clientName : fileName}" target="_blank">
                            ${clientName}
                        </a>
                        <span>
                            <i style="font-size: 12px;color:red" class="fas fa-trash-alt delete-reference-document" data-id="${referenceDocument.id}" style="cursor: pointer;"></i>
                        </span>
                    </p>
                `;

                html += documentHtml;  // เพิ่ม HTML ลงในตัวแปร
            });

            // หลังจากวนลูปเสร็จแล้ว นำ HTML ไปใส่ใน #addReferenceDocumentWrapper
            $("#addReferenceDocumentWrapper").html(html);

        },
        error: function (xhr) {
            // alert("เกิดข้อผิดพลาด: " + xhr.responseText);
        }
    });
});

$(document).on('click', '.delete-reference-document', function () {
    let referenceDocumentId = $(this).data('id');  // ดึง ID ของเอกสารที่ต้องการลบ
    let assessment_id = $("#assessment_id").val().trim();

    let confirmDelete = confirm("ต้องการลบหรือไม่?");
    
    if (confirmDelete) {
        $('#loadingStatus').show();
        $.ajax({
            url: '{{ url("/certify/save_assessment-cb/delete-reference-document") }}',  // URL ที่จะส่งคำขอ
            method: 'post',  // ใช้ DELETE method
            data: {
                _token: '{{ csrf_token() }}',  // ส่ง CSRF Token ด้วย
                id: referenceDocumentId,  // ส่ง ID ที่ต้องการลบ
                assessment_id: assessment_id,
            },
            success: function (response) {
                $('#loadingStatus').hide();
                let referenceDocuments = response.referenceDocuments;
                let html = "";  // ตัวแปรที่เก็บ HTML ทั้งหมด

                referenceDocuments.forEach(function (referenceDocument, index) {
                    let clientName = referenceDocument.file_client_name || 'null';
                    let fileName = referenceDocument.file ? referenceDocument.file.split('/').pop() : '';  // ดึงชื่อไฟล์จากพาธ

                    let fileUrl = '/certify/check/file_cb_client/' + referenceDocument.file + '/' + clientName;

                    let documentHtml = `
                        <p style="margin-left: 10px">
                            ${index + 1}. <a style="text-decoration: none" href="${fileUrl}" 
                                title="${clientName ? clientName : fileName}" target="_blank">
                                ${clientName}
                            </a>
                            <span>
                                <i style="font-size: 12px;color:red" class="fas fa-trash-alt delete-reference-document" data-id="${referenceDocument.id}" style="cursor: pointer;"></i>
                            </span>
                        </p>
                    `;
                    html += documentHtml;  // เพิ่ม HTML ลงในตัวแปร
                });

                $("#addReferenceDocumentWrapper").html(html);
            }
        });
    }
});


        function openAddPersonModal() {
            document.getElementById('modal-add-person').style.display = 'block';
        }

        function closeAddPersonModal() {
            document.getElementById('modal-add-person').style.display = 'none';
        }

        function addTextPerson() {
            const name = document.getElementById('person-name').value;
            const position = document.getElementById('person-position').value;
            const org = document.getElementById('person-org').value;

            if (name && position) {
                // เพิ่มข้อมูลเข้าไปในอาร์เรย์
                persons.push({ name, position,org });
                
                // เรียกฟังก์ชัน render เพื่อแสดงข้อมูลใหม่
                renderPersons();
                
                // ปิด modal
                closeAddPersonModal();
                
                // ล้างค่าฟอร์ม
                document.getElementById('person-name').value = '';
                document.getElementById('person-position').value = '';
                document.getElementById('person-org').value = '';
            } else {
                alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
        }


        function renderPersons() {
            const personWrapper = document.getElementById('person_wrapper');
            personWrapper.innerHTML = ''; // เคลียร์ข้อมูลเก่าก่อน render ใหม่

            const table = document.createElement('table');
            table.style.width = '100%';
            table.style.borderCollapse = 'collapse';

            persons.forEach((person, index) => {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.style.padding = '10px';
                cell.style.display = 'flex';
                cell.style.gap = '10px';
                
                cell.innerHTML = `
                    <span style="flex: 0 0 20px;">${index + 1}.</span>
                    <span style="flex: 1 0 150px;">${person.name}</span>
                    <span style="flex: 1 0 150px;">
                        ตำแหน่ง ${person.position}
                    </span>
                    <span style="flex: 1 0 200px;">
                        ${person.org}
                        <span style="padding-left:10px; cursor:pointer;" onclick="deletePerson(${index})">
                            <i class="fa-solid fa-trash-can" style="color: red;font-size:16px"></i>
                        </span>
                    </span>
                `;
                
                row.appendChild(cell);
                table.appendChild(row);
            });

            personWrapper.appendChild(table);
        }

        // ฟังก์ชันลบข้อมูล
        function deletePerson(index) {
            persons.splice(index, 1); // ลบข้อมูลจากอาร์เรย์
            renderPersons(); // render ใหม่หลังจากลบข้อมูล
        }

        
        function uploadReference() {
            const fileInput = document.getElementById('referenceFile');
            const file = fileInput.files[0];

            if (file) {
                references.push({ name: file.name, file: file });
                renderReferences();
                // closeUploadReferenceModal();
                 $("#uploadReferenceModal").css("display", "none");
            } else {
                alert('กรุณาเลือกไฟล์');
            }
        }

        function renderReferences() {
            const docRefWrapper = document.getElementById('doc_ref_wrapper');
            docRefWrapper.innerHTML = ''; // เคลียร์ข้อมูลเก่าก่อน render ใหม่

            const table = document.createElement('table');
            table.style.width = '100%';
            table.style.borderCollapse = 'collapse';

            references.forEach((reference, index) => {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.style.padding = '10px';
                cell.style.display = 'flex';
                cell.style.gap = '10px';
                
                cell.innerHTML = `
                    <span style="flex: 0 0 20px;">${index + 1}.</span>
                    <span style="flex: 1 0 350px;">${reference.name}</span>
                    <span style="flex: 0 0 auto;">
                        <span style="cursor:pointer;" onclick="deleteReference(${index})">
                            <i class="fa-solid fa-trash-can" style="color: red; font-size:16px"></i>
                        </span>
                    </span>
                `;
                
                row.appendChild(cell);
                table.appendChild(row);
            });

            docRefWrapper.appendChild(table);
        }


        function deleteReference(index) {
            references.splice(index, 1); // ลบข้อมูลจากอาร์เรย์
            renderReferences(); // render ใหม่หลังจากลบข้อมูล
        }


</script>

</html>
