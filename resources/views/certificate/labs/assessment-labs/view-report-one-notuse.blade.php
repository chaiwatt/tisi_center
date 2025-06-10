<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>รายงานการตรวจประเมิน</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 20px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .wrapper {
            max-width: 850px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .checkbox-section {
            display: flex; /* ใช้ flexbox */
            flex-wrap: wrap; /* อนุญาตให้ย่อมาหลายแถว */
            gap: 10px; /* ระยะห่างระหว่าง checkbox */
            max-width: 800px; /* จำกัดความกว้างรวมของ div */
        }

        .checkbox-section label {
            display: flex;
            align-items: center; /* จัด checkbox และข้อความให้อยู่ตรงกลางแนวตั้ง */
            margin-right: 20px; /* ระยะห่างระหว่าง label */
            white-space: nowrap; /* ป้องกันข้อความยาวเกินหลุดบรรทัด */
        }

        .checkbox-section label:nth-child(4) {
            flex-basis: 100%; /* บังคับให้ label ตัวที่ 4 ย้ายลงไปแถวใหม่ */
        }


        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .input-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: calc(100% - 20px);
            margin: 0 10px;
            height: 24px;
        }

        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-section td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .table-section .underline {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 80%;
        }
        input[type="checkbox"] {
            width: 15px; /* ความกว้าง */
            height: 15px; /* ความสูง */
            transform: scale(1.5); /* ขยายขนาด */
            margin-right: 10px; /* เว้นระยะห่างระหว่าง checkbox และข้อความ */
            vertical-align: middle; /* จัดตำแหน่งให้อยู่ตรงกลางกับข้อความ */
            appearance: none; /* ลบการปรับแต่งพื้นฐานของ browser */
            border: 1px solid #000; /* เพิ่มเส้นขอบสีดำ */
            background-color: #fff; /* กำหนดพื้นหลังเป็นสีขาว */
            cursor: pointer; /* เพิ่ม cursor แบบ pointer */
            position: relative; /* ใช้ position เพื่อจัดการกับ ::after */
        }

        input[type="checkbox"]:checked::after {
            content: '\2714'; /* แสดงเครื่องหมายถูก */
            font-size: 14px; /* ขนาดของเครื่องหมาย */
            color: #000; /* สีของเครื่องหมายถูก */
            position: absolute; /* ใช้ absolute เพื่อจัดให้อยู่กลาง */
            top: 50%; /* จัดให้อยู่ตรงกลางตามแนวตั้ง */
            left: 50%; /* จัดให้อยู่ตรงกลางตามแนวนอน */
            transform: translate(-46%, -54%); /* ดันกลับเพื่อให้อยู่กลางช่อง */
        }

        input[type="radio"] {
            width: 15px; /* ความกว้าง */
            height: 15px; /* ความสูง */
            transform: scale(1.5); /* ขยายขนาด */
            margin-right: 10px; /* เว้นระยะห่างระหว่าง checkbox และข้อความ */
            vertical-align: middle; /* จัดตำแหน่งให้อยู่ตรงกลางกับข้อความ */
            appearance: none; /* ลบการปรับแต่งพื้นฐานของ browser */
            border: 1px solid #000; /* เพิ่มเส้นขอบสีดำ */
            background-color: #fff; /* กำหนดพื้นหลังเป็นสีขาว */
            cursor: pointer; /* เพิ่ม cursor แบบ pointer */
            position: relative; /* ใช้ position เพื่อจัดการกับ ::after */
        }

        input[type="radio"]:checked::after {
            content: '\2714'; /* แสดงเครื่องหมายถูก */
            font-size: 14px; /* ขนาดของเครื่องหมาย */
            color: #000; /* สีของเครื่องหมายถูก */
            position: absolute; /* ใช้ absolute เพื่อจัดให้อยู่กลาง */
            top: 50%; /* จัดให้อยู่ตรงกลางตามแนวตั้ง */
            left: 50%; /* จัดให้อยู่ตรงกลางตามแนวนอน */
            transform: translate(-46%, -54%); /* ดันกลับเพื่อให้อยู่กลางช่อง */
        }

        .form-section {
            font-size: 19px;
            line-height: 1.5;
            margin: 20px 0;
        }

        .row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .full-line {
            justify-content: space-between; /* กระจายช่องว่างให้จุดไข่ปลาชิดขอบขวา */
        }

        .full-line .dotted-line {
            flex-grow: 1; /* ขยายจุดไข่ปลาเต็มพื้นที่ */
            border-bottom: 1px dotted #000;
            white-space: nowrap; /* ป้องกันข้อความในจุดไข่ปลาตัด */
            margin-left: 10px;
        }

        .flexible-dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            white-space: nowrap; /* ป้องกันข้อความในจุดไข่ปลาตัด */
            padding-left: 5px; /* เพิ่มช่องว่างเล็กน้อย */
        }

        .input-no-border {
            width: 100%;
            font-size: 20px;
            font-family: 'Sarabun', sans-serif;
            border: none;
            outline: none;
            /* background-color: #fffdcc;  */
            border-bottom: 1px dotted #000;
            color: #000;
            padding: 2px 0;
            transition: background-color 0.3s ease; /* เปลี่ยนสีอย่าง Smooth */
        }

        .input-no-border.has-value,
        .input-no-border:focus {
            background-color: #ffffff; /* พื้นหลังสีขาว */
        }

        .input-border {
            width: 100%;
            font-size: 18px;
            font-family: 'Sarabun', sans-serif;
            outline: none;
            border: 1px solid #000; /* เพิ่มกรอบเส้นสีดำ */
            color: #000;
            padding: 5px; /* เพิ่มพื้นที่ด้านในกรอบ */
            transition: background-color 0.3s ease, border-color 0.3s ease; /* เพิ่มการเปลี่ยนสีกรอบอย่าง Smooth */
        }

        /* สไตล์เมื่อโฟกัส */
        .input-border:focus {
            background-color: #fffdcc; /* เปลี่ยนสีพื้นหลังเมื่อโฟกัส */
            border-color: #ff9900; /* เปลี่ยนสีกรอบเมื่อโฟกัส */
        }

        .container {
            display: flex; /* เปิดใช้งาน Flexbox */
            justify-content: space-between; /* กระจายองค์ประกอบซ้ายและขวา */
            align-items: center; /* จัดให้อยู่ตรงกลางตามแนวตั้ง */
            padding: 10px; /* ระยะห่างภายในกรอบ */
            width: 95%; /* ความกว้างเต็มพื้นที่ */
            margin-bottom: 15px    

        }

        .left-text {
            font-size: 20px;
        }

        .right-box {
            border: 1px solid #000; /* เส้นขอบ */
            padding: 5px 10px; /* ระยะห่างภายในกล่อง */
            font-size: 20px;
        }

        /* ปุ่มหลัก */
        .btn {
            display: inline-block; /* ปุ่มขยายตามเนื้อหา */
            font-size: 16px; /* ขนาดตัวอักษร */
            font-family: 'Sarabun', sans-serif; /* ฟอนต์ */
            color: #fff; /* สีตัวอักษร */
            border: none; /* ไม่มีเส้นขอบ */
            border-radius: 5px; /* มุมโค้งมน */
            padding: 10px 20px; /* ระยะห่างภายในปุ่ม */
            cursor: pointer; /* เปลี่ยนเมาส์เป็น pointer */
            text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
            text-decoration: none; /* ลบขีดเส้นใต้ */
            transition: all 0.3s ease; /* เอฟเฟกต์นุ่มนวล */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
            margin-top: 10px;
        }

        /* ปุ่มสีเขียว */
        .btn-green {
            background-color: #4CAF50; /* สีพื้นหลังสีเขียว */
        }

        .btn-green:hover {
            background-color: #45a049; /* เปลี่ยนสีเมื่อ hover */
            box-shadow: 0px 3px 4px rgba(0, 0, 0, 0.3); /* เพิ่มเงาเข้มขึ้น */
        }

        .btn-green:active {
            background-color: #3e8e41; /* เปลี่ยนสีเมื่อกดปุ่ม */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); /* ลดเงาเมื่อกด */
        }

        /* ปุ่มสีแดง */
        .btn-red {
            background-color: #f44336; /* สีพื้นหลังสีแดง */
        }

        .btn-red:hover {
            background-color: #e53935; /* เปลี่ยนสีเมื่อ hover */
            box-shadow: 0px 3px 4px rgba(0, 0, 0, 0.3); /* เพิ่มเงาเข้มขึ้น */
        }

        .btn-red:active {
            background-color: #d32f2f; /* เปลี่ยนสีเมื่อกดปุ่ม */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2); /* ลดเงาเมื่อกด */
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

        .section {
            margin-left:10px;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .inline-group {
            display: flex;
            align-items: flex-start;
            gap: 5px;
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

        
        .label {
            white-space: nowrap;
            font-weight: bold;
            width: 200px;
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
</head>
<body>
    <div id="modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid black; z-index:1000">
        <h4>ผลการตรวจประเมิน: (1) ข้อกำหนดทั่วไป</h4>
            <textarea id="modal-input" style="width: 800px; height: 500px; resize: none; ;font-family: 'Sarabun'; font-size: 20px;"></textarea>
        </textarea>
        <br>
        <button onclick="addTextBlock()" class="btn btn-green">เพิ่ม</button>
        <button onclick="closeModal()" class="btn btn-red">ยกเลิก</button>
    </div>
    <div id="modal-add-person" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px 30px; border:1px solid black; border-radius:8px; z-index:1000; width:400px; box-shadow:0px 4px 6px rgba(0,0,0,0.1);">
        <h4 style="text-align:center; font-size:22px; margin-bottom:20px;">บุคคลที่พบ:</h4>
        <form id="person-form">
            <div style="margin-bottom:15px;">
                <label for="person-name" style="display:block; font-size:18px; margin-bottom:5px;">ชื่อ:</label>
                <input type="text" class="input-border" id="person-name" name="name" required>
            </div>
            <div style="margin-bottom:15px;">
                <label for="person-position" style="display:block; font-size:18px; margin-bottom:5px;">ตำแหน่ง:</label>
                <input type="text" class="input-border" id="person-position" name="position" required>
            </div>
        </form>
        <div style="text-align:center; margin-top:20px;">
            <button onclick="addTextPerson()" class="btn btn-green" style="padding:8px 16px; font-size:16px; margin-right:10px; border:none; background-color:#4CAF50; color:white; border-radius:4px; cursor:pointer;">เพิ่ม</button>
            <button onclick="closeAddPersonModal()" class="btn btn-red" style="padding:8px 16px; font-size:16px; border:none; background-color:#f44336; color:white; border-radius:4px; cursor:pointer;">ยกเลิก</button>
        </div>
    </div>

    <div id="uploadReferenceModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px 30px; border:1px solid black; border-radius:8px; z-index:1000; width:400px; box-shadow:0px 4px 6px rgba(0,0,0,0.1);">
        <h4 style="text-align:center; font-size:22px; margin-bottom:20px;">เอกสารที่อ้างอิง:</h4>
        <form id="person-form">
            <div style="margin-bottom:15px;">
                 <label for="referenceFile" style="font-size: 18px">เลือกไฟล์:</label>
                                <input type="file" id="referenceFile" name="file" class="input-field" required>
            </div>
        </form>
        <div style="text-align:center; margin-top:20px;">
            <button onclick="uploadReference()" class="btn btn-green" style="padding:8px 16px; font-size:16px; margin-right:10px; border:none; background-color:#4CAF50; color:white; border-radius:4px; cursor:pointer;">เลือก</button>
            <button onclick="closeUploadReferenceModal()" class="btn btn-red" style="padding:8px 16px; font-size:16px; border:none; background-color:#f44336; color:white; border-radius:4px; cursor:pointer;">ยกเลิก</button>
        </div>
    </div>
    
    <div id="loadingStatus" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <div class="loading-text">กำลังบันทึก...</div>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="left-text">คำขอที่ {{$tracking->reference_refno}}</div>
            <div class="right-box">รายงานที่ 1</div>
        </div>
        <div class="header">
            รายงานการตรวจติดตามผลการรับรอง
        </div>


        <div class="section">
            <div class="inline-group">
                <div class="label">ชื่อห้องปฏิบัติการ : </div>
                <div class="content">
                    {{$certi_lab->lab_name}}
                </div>
            </div>
        </div>

        <div class="section">
            <div class="inline-group">
                <div class="label">ที่ตั้งสำนักงานใหญ่ : </div>
                <div class="content">
                    <div class="inline-block float-left">
                        @if ($certi_lab->hq_address !== null) เลขที่ {{$certi_lab->hq_address}} @endif 
                        @if ($certi_lab->hq_moo !== null) หมู่{{$certi_lab->hq_moo}} @endif
                        @if ($certi_lab->hq_soi !== null) ซอย{{$certi_lab->hq_soi}} @endif
                        @if ($certi_lab->hq_road !== null) ถนน{{$certi_lab->hq_road}}  @endif
        
                            @if (strpos($certi_lab->HqProvinceName, 'กรุงเทพ') !== false)
                                <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                แขวง {{$certi_lab->HqSubdistrictName}} เขต{{$certi_lab->HqDistrictName }} {{$certi_lab->HqProvinceName}}
                            @else
                                <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                ตำบล{{$certi_lab->HqSubdistrictName}}  อำเภอ{{$certi_lab->HqDistrictName }}  จังหวัด{{$certi_lab->HqProvinceName}}
                            @endif
                        @if ($certi_lab->hq_zipcode !== null) {{$certi_lab->hq_zipcode}}  @endif
                            
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="inline-group">
                <div class="label"></div>
                <div class="content spaced">
                    <span>โทรศัพท์: <span>@if ($certi_lab->hq_telephone !== null) {{$certi_lab->hq_telephone}}  @endif</span> </span>   <span>โทรสาร: <span>@if ($certi_lab->hq_fax !== null) {{$certi_lab->hq_fax}}  @endif</span></span>
                </div>
            </div>
        </div>

        
            <div class="section">
                <div class="inline-group">
                    <div class="label">&nbsp;&nbsp;&nbsp;ที่ตั้งห้องปฏิบัติการ : </div>
                    <div class="content">
                        @if ($certi_lab->address_number !== null) เลขที่ {{$certi_lab->address_number}} @endif 
                        @if ($certi_lab->allay !== null) หมู่{{$certi_lab->allay}} @endif
                        @if ($certi_lab->address_soi !== null) ซอย{{$certi_lab->address_soi}} @endif
                        @if ($certi_lab->address_street !== null) ถนน{{$certi_lab->address_street}}  @endif
        
                            @if (strpos($certi_lab->basic_province->PROVINCE_NAME, 'กรุงเทพ') !== false)
                                <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                แขวง {{$certi_lab->district_id}} เขต{{$certi_lab->amphur_id }} {{$certi_lab->basic_province->PROVINCE_NAME}}
                            @else
                                <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                ตำบล{{$certi_lab->district_id}}  อำเภอ{{$certi_lab->amphur_id }}  จังหวัด{{$certi_lab->basic_province->PROVINCE_NAME}}
                            @endif
                        @if ($certi_lab->postcode !== null) {{$certi_lab->postcode}}  @endif
                    </div>
                </div>
            </div>
            <div class="section">
                <div class="inline-group">
                    <div class="label"></div>
                    <div class="content spaced">
                        <span>โทรศัพท์: <span>@if ($certi_lab->tel !== null){{$certi_lab->tel}}  @endif</span> </span>   <span>โทรสาร: <span>@if ($certi_lab->tel_fax !== null) {{$certi_lab->tel_fax}}  @endif</span></span>
                    </div>
                </div>
            </div>
        

        <div class="section">
            <div class="inline-group">
                <div class="label">วันที่ตรวจติดตาม : </div>
                <div class="content">
                    {{HP::formatDateThaiFullPoint($assessment->created_at)}}
                </div>
            </div>
        </div>

        <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
            <span>เจ้าหน้าที่ผู้ตรวจประเมิน</span>
        </div>

        <div style="margin-left: 25px;margin-top:10px">

            @php
                $index = 0;
            @endphp
            @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                @php
                    $index++;
                @endphp

                @foreach ($auditorIds as $auditorId)
                    @php
                        $info = HP::getExpertTrackingInfo($statusId, $auditorId);
                    @endphp
                    <div style="display: flex; gap: 10px;">
                        <span style="flex: 0 0 250px;">{{$index}}. {{HP::toThaiNumber($info->trackingAuditorsList->temp_users)}}</span>
                        <span style="flex: 1 0 200px;">{{$info->statusAuditor->title}}</span>
                    </div>

                @endforeach
            @endforeach
        </div>


        <div class="section">
            <div class="label">บุคคลที่พบ : <button onclick="openAddPersonModal()" class="btn btn-green " >เพิ่ม</button></div>
                <div class="content">
                    <div style="margin-left: 25px;margin-top:10px" id="person_wrapper">
                        {{-- <div style="display: flex; gap: 10px;">
                            <span style="flex: 0 0 20px;">1.</span>
                            <span style="flex: 1 0 150px;">นายจอร์น วิลเลียม</span>
                            <span style="flex: 1 0 50px;">ตำแหน่ง</span>
                            <span style="flex: 1 0 300px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                        </div> --}}
                        
                        <p>และเจ้าหน้าที่ที่เกี่ยวข้อง</p>
                    </div>
            </div>
        </div>

        <div class="section">
            <div class="label">เอกสารที่อ้างอิง : <button onclick="openUploadReferenceModal()" class="btn btn-green " >เพิ่ม</button></div>
                <div class="content">
                    <div style="margin-left: 25px;margin-top:10px" id="doc_ref_wrapper">
                        {{-- <div style="display: flex; gap: 10px;">
                            <span style="flex: 0 0 20px;">1.</span>
                            <span style="flex: 1 0 350px;">นายจอร์น วิลเลียม</span>
                        </div> --}}
                    </div>
            </div>
        </div>

        <div class="section">
            <div class="label">ขอบข่ายที่ได้รับการรับรอง : </div>
            <div class="content">
                <div style="margin-left: 25px;margin-top:10px" id="person_wrapper">
                    <span style="display:block;margin-left:30px;">-	ใบรับรองเลขที่ {{$tracking->certificate_export_to->certificate_no}} หมายเลขการรับรองที่ {{$tracking->certificate_export_to->accereditatio_no}} ฉบับที่ <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="book_no_text" id="book_no_text"> ออกให้ตั้งแต่วันที่ {{HP::formatDateThaiFullPoint($tracking->certificate_export_to->certificate_date_start)}} ถึงวันที่ {{HP::formatDateThaiFullPoint($tracking->certificate_export_to->certificate_date_end)}} </span>  
                </div>
            </div>
        </div>

        <div class="section">
            <div class="label">ผลการตรวจประเมิน : </div>
            <div class="content">
               <p style="text-indent: 100px">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามผลการรับรองความสามารถห้องปฏิบัติการตามมาตรฐานเลขที่ มอก. 17025-2561 สำหรับหมายเลขการรับรองที่ {{$tracking->certificate_export_to->certificate_no}} คณะผู้ตรวจประเมินพบว่า <input type="text" class="input-no-border" placeholder="" style="width: 600px;text-align:center" name="audit_observation_text" id="audit_observation_text">
		 โดยมีประเด็นสำคัญดังนี้</p> 

            </div>
        </div>

        <div class="label">การตรวจติดตามผลการรับรอง </div> 

                <div class="content" style="margin-left: 0px">
            <div style="margin-top: 10px">
                <div class="report-table">
                    <table>
                        <thead>
                            <tr>
                                <th >รายการ</th>
                                <th style="width: 250px">ผลการตรวจ</th>
                                <th  >หมายเหตุ</th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr class="group-header">
                                <td colspan="4" style="font-weight: bold">ส่วนที่ 1 ข้อกำหนด ตามมาตรฐานเลขที่ มอก. 17025-2561</td>
                            </tr>
                            <tr class="group-header">
                                <td colspan="4">4. ข้อกำหนดทั่วไป</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">4.1 ความเป็นกลาง</td>
                                <td>
                                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox"  id="chk_impartiality_no" name="chk_impartiality_no">ไม่พบ</label>
                                            <label><input type="checkbox"  id="chk_impartiality_yes" name="chk_impartiality_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="impartiality_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">4.2 การรักษาความลับ</td>
                                <td>
                                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox"  id="chk_confidentiality_no" name="chk_confidentiality_no">ไม่พบ</label>
                                            <label><input type="checkbox"  id="chk_confidentiality_yes" name="chk_confidentiality_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="confidentiality_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                                <tr>
                                <td>5. ข้อกำหนดด้านโครงสร้าง</td>
                                <td>
                                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox"  id="chk_structure_no" name="chk_structure_no">ไม่พบ</label>
                                            <label><input type="checkbox"  id="chk_structure_yes" name="chk_structure_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="structure_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                                <tr class="group-header">
                                <td colspan="4">6. ข้อกำหนดด้านทรัพยากร</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">6.1 ทั่วไป</td>
                                <td>
                                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox"  id="chk_res_general_no" name="chk_res_general_no">ไม่พบ</label>
                                            <label><input type="checkbox"  id="chk_res_general_yes" name="chk_res_general_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_general_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">6.2 บุคลากร</td>
                                <td>
                                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox"  id="chk_res_personnel_no" name="chk_res_personnel_no">ไม่พบ</label>
                                            <label><input type="checkbox"  id="chk_res_personnel_yes" name="chk_res_personnel_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_personnel_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>




                            <tr>
                                <td style="padding-left: 15px">6.3 สิ่งอำนวยความสะดวกและภาวะแวดล้อม</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_facility_no" name="chk_res_facility_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_facility_yes" name="chk_res_facility_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_facility_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">6.4 เครื่องมือ</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_equipment_no" name="chk_res_equipment_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_equipment_yes" name="chk_res_equipment_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_equipment_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">6.5 ความสอบกลับได้ทางมาตรวิทยา</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_traceability_no" name="chk_res_traceability_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_traceability_yes" name="chk_res_traceability_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_traceability_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">6.6 ผลิตภัณฑ์และบริการจากภายนอก</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_external_no" name="chk_res_external_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_external_yes" name="chk_res_external_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_external_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>

                            <tr class="group-header">
                                <td colspan="4">7. ข้อกำหนดด้านกระบวนการ</td>
                            </tr>

                            <tr>
                                <td style="padding-left: 15px">7.1 การทบทวนคำขอ ข้อเสนอการประมูล และข้อสัญญา</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_review_no" name="chk_proc_review_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_review_yes" name="chk_proc_review_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_review_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.2 การเลือก การทวนสอบ และการตรวจสอบความใช้ได้ของวิธี</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_method_no" name="chk_proc_method_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_method_yes" name="chk_proc_method_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_method_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.3 การชักตัวอย่าง</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_sampling_no" name="chk_proc_sampling_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_sampling_yes" name="chk_proc_sampling_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_sampling_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.4 การจัดการตัวอย่างทดสอบหรือสอบเทียบ</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_sample_handling_no" name="chk_proc_sample_handling_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_sample_handling_yes" name="chk_proc_sample_handling_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_sample_handling_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.5 บันทึกทางด้านวิชาการ</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_tech_record_no" name="chk_proc_tech_record_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_tech_record_yes" name="chk_proc_tech_record_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_tech_record_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.6 การประเมินค่าความไม่แน่นอนของการวัด</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_uncertainty_no" name="chk_proc_uncertainty_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_uncertainty_yes" name="chk_proc_uncertainty_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_uncertainty_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.7 การสร้างความมั่นใจในความใช้ได้ของผล</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_validity_no" name="chk_proc_validity_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_validity_yes" name="chk_proc_validity_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_validity_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.8 การรายงานผล</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_reporting_no" name="chk_proc_reporting_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_reporting_yes" name="chk_proc_reporting_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_reporting_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.9 ข้อร้องเรียน</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_complaint_no" name="chk_proc_complaint_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_complaint_yes" name="chk_proc_complaint_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_complaint_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.10 งานที่ไม่เป็นไปตามข้อกำหนด</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_nonconformity_no" name="chk_proc_nonconformity_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_nonconformity_yes" name="chk_proc_nonconformity_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_nonconformity_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">7.11 การควบคุมข้อมูลและการจัดการสารสนเทศ</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_proc_data_control_no" name="chk_proc_data_control_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_proc_data_control_yes" name="chk_proc_data_control_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="proc_data_control_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>

                            <tr class="group-header">
                                <td colspan="4">8. ข้อกำหนดด้านระบบการบริหารงาน</td>
                            </tr>


                            <tr>
                                <td style="padding-left: 15px">8.1 การเลือก</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_selection_no" name="chk_res_selection_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_selection_yes" name="chk_res_selection_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_selection_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.2 เอกสารระบบการบริหารงาน (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_docsystem_no" name="chk_res_docsystem_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_docsystem_yes" name="chk_res_docsystem_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_docsystem_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.3 การควบคุมเอกสารระบบการบริหารงาน (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_doccontrol_no" name="chk_res_doccontrol_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_doccontrol_yes" name="chk_res_doccontrol_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_doccontrol_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.4 การควบคุมบันทึก (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_recordcontrol_no" name="chk_res_recordcontrol_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_recordcontrol_yes" name="chk_res_recordcontrol_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_recordcontrol_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.5 การปฏิบัติการเพื่อระบุความเสี่ยงและโอกาส (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_riskopportunity_no" name="chk_res_riskopportunity_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_riskopportunity_yes" name="chk_res_riskopportunity_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_riskopportunity_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.6 การปรับปรุง (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_improvement_no" name="chk_res_improvement_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_improvement_yes" name="chk_res_improvement_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_improvement_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.7 การปฏิบัติการแก้ไข (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_corrective_no" name="chk_res_corrective_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_corrective_yes" name="chk_res_corrective_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_corrective_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.8 การตรวจติดตามภายใน (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_audit_no" name="chk_res_audit_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_audit_yes" name="chk_res_audit_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_audit_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15px">8.9 การทบทวนการบริหาร (ทางเลือก ก)</td>
                                <td>
                                    <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                                        <label><input type="checkbox" id="chk_res_review_no" name="chk_res_review_no">ไม่พบ</label>
                                        <label><input type="checkbox" id="chk_res_review_yes" name="chk_res_review_yes">พบข้อบกพร่อง</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea class="text-area-no-border" id="res_review_text" cols="30" rows="2"></textarea>
                                </td>
                            </tr>


                            
        


                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="section">
            <div class="label" style="font-weight: bold">ส่วนที่ 2 การเฝ้าระวังการฝ่าฝืนหลักเกณฑ์ วิธีการและเงื่อนไขการรับรองห้องปฏิบัติการ ตาม </div>
            <div class="content" style="margin-left: 50px">
                <p> (1)  กฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 <br>
                (2)	หลักเกณฑ์ วิธีการและเงื่อนไขการโฆษณาของผู้ประกอบการตรวจสอบและรับรองและ ผู้ประกอบกิจการ <br>
                (3)	เอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง  สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</p>
             


            </div>
        </div>

         <div class="section">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">2.1 การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
    
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_display_certification_none" id="report_display_certification_none" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_display_certification_yes" id="report_display_certification_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_scope_certified_only" id="report_scope_certified_only" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_scope_certified_all" id="report_scope_certified_all" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_activities_not_certified_yes" id="report_activities_not_certified_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_activities_not_certified_no" id="report_activities_not_certified_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_accuracy_correct" id="report_accuracy_correct" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="report_accuracy_incorrect" id="report_accuracy_incorrect" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="report_accuracy_detail" id="report_accuracy_detail">
                    </span>
                </div>
            </div>
         </div>


         <div class="section">
            <span style="display:block; margin-left:30px;margin-top:15px"><span style="font-weight: 600;">2.2 กรณีได้รับการรับรองห้องปฏิบัติการหลายสถานที่ (Multi-site)</span> การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ </span> 
    
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_display_certification_none" id="multisite_display_certification_none" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_display_certification_yes" id="multisite_display_certification_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_scope_certified_only" id="multisite_scope_certified_only" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_scope_certified_all" id="multisite_scope_certified_all" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_activities_not_certified_yes" id="multisite_activities_not_certified_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_activities_not_certified_no" id="multisite_activities_not_certified_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_accuracy_correct" id="multisite_accuracy_correct" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="multisite_accuracy_incorrect" id="multisite_accuracy_incorrect" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="multisite_accuracy_detail" id="multisite_accuracy_detail">
                    </span>
                </div>
            </div>
         </div>

        <div class="section">
             <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">2.3 กรณีห้องปฏิบัติการสอบเทียบ ป้ายแสดงสถานะการสอบเทียบ </span> 
                <div style="margin-left: 135px;padding-top:10px">
                    <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ส่วนที่ 2 (1) – (3) ข้างต้น</span> 
                </div>
                <div style="margin-top: 10px;margin-left:165px">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="radio" name="certification_status_correct" id="certification_status_correct" >ถูกต้อง</label>
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="radio" name="certification_status_incorrect" id="certification_status_incorrect" >ไม่ถูกต้อง ระบุ</label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="certification_status_details" id="certification_status_details">
                        </span>
                    </div>
                </div>
        </div>
        <div class="section">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">2.4 การแสดงการได้รับการรับรองที่อื่น นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ </span> 
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ส่วนที่ 2 (1) – (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="other_certification_status_correct" id="other_certification_status_correct" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="other_certification_status_incorrect" id="other_certification_status_incorrect" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="other_certification_status_details" id="other_certification_status_details">
                    </span>
                </div>
            </div>
        </div>


        <div class="section">

            
            
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">ส่วนที่ 3 การปฏิบัติตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่างประเทศว่าด้วยการรับรองห้องปฏิบัติการ (ILAC) และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง  สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) </span> 
         
        

            <div style="display: flex; gap: 10px;margin-top:10px;margin-left:70px ">
                <span>ห้องปฏิบัติการ</span>
                <span style="flex: 1 0 5px;"><label><input type="radio" name="lab_availability_yes" id="lab_availability_yes" >มี</label></span>
                <span style="flex: 1 0 10px;"><label><input type="radio" name="lab_availability_no" id="lab_availability_no" >ไม่มี</label></span>
                <span style="flex: 1 0 300px;"><label></span>
            </div>
            <div style="margin-left: 70px;padding-top:10px">
                <span>การลงนามในข้อตกลงการใช้เครื่องหมาย ILAC MRA ร่วมกับเครื่องหมายมาตรฐานทั่วไปสำหรับผู้รับใบรับรอง ร่วมกับสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</span> 
            </div>
            <div style="margin-left: 70px;padding-top:10px">
                <span style="font-weight: bold"> <u>กรณีห้องปฏิบัติการและสำนักงานมีข้อตกลงร่วมกัน</u> </span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">3.1 การแสดงเครื่องหมายร่วม ILAC MRA ในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_display_no" id="ilac_mra_display_no" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_display_yes" id="ilac_mra_display_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_scope_no" id="ilac_mra_scope_no" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_scope_yes" id="ilac_mra_scope_yes" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_disclosure_yes" id="ilac_mra_disclosure_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_disclosure_no" id="ilac_mra_disclosure_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น </span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_compliance_correct" id="ilac_mra_compliance_correct" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="ilac_mra_compliance_incorrect" id="ilac_mra_compliance_incorrect" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="ilac_mra_compliance_details" id="ilac_mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">3.2 การแสดงเครื่องหมายร่วม ILAC MRA นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="other_ilac_mra_compliance_no" id="other_ilac_mra_compliance_no" >ไม่มี</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="other_ilac_mra_compliance_yes" id="other_ilac_mra_compliance_yes" >มี ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="other_ilac_mra_compliance_details" id="other_ilac_mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น </span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="mra_compliance_correct" id="mra_compliance_correct" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="mra_compliance_incorrect" id="mra_compliance_incorrect" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="mra_compliance_details" id="mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
                <span>ผลการตรวจติดตามผลการรับรอง</span>
            </div>
            <div style="margin-left:30px; line-height:36px">


                <div style="margin-left: 30px">
                    <span>การตรวจติดตามผลการรับรองความสามารถห้องปฏิบัติการครั้งนี้ เป็นการตรวจติดตามผลการรับรองฯ ครั้งที่ X นับจากห้องปฏิบัติการได้รับการรับรองเมื่อวันที่ XXX พบว่าห้องปฏิบัติการได้ปฏิบัติตามเอกสารระบบคุณภาพที่กำหนดไว้เป็นอย่างดี สอดคล้องตามมาตรฐานเลขที่ มอก. 17025-2561  ดังนี้</span>
                    <p>(อย่างน้อยควรประกอบด้วย <br>
                        1. ภาพรวมของระบบคุณภาพของห้องปฏิบัติการและประสิทธิผลของการนำไปใช้ <br>
                        2. การเข้าร่วมกิจกรรมเปรียบเทียบผลระหว่างห้องปฏิบัติการหรือการทดสอบความชำนาญ <br>
                        3. ผลการตรวจติดตามการวัด (measurement audit) (ถ้ามี))
                        </p>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="evidence_mra_compliance_details_1" id="evidence_mra_compliance_details_1">
                    </span>
                      <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="evidence_mra_compliance_details_2" id="evidence_mra_compliance_details_2">
                    </span>
                      <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="evidence_mra_compliance_details_3" id="evidence_mra_compliance_details_3">
                    </span>
                      <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="evidence_mra_compliance_details_4" id="evidence_mra_compliance_details_4">
                    </span>
                </div>
            </div>

            <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
                <span>การแสดงเครื่องหมายการรับรองบนรายงานผล</span>
            </div>

            <div style="margin-left:30px; line-height:36px">
                <table>
                    <tr>
                        <td>
                            <span >
                               <input type="checkbox" name="offer_agreement_yes" id="offer_agreement_yes"> ไม่เป็น <input type="checkbox" name="offer_agreement_no" id="offer_agreement_no"> เป็น ไปตามกฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง  สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) 	
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
                <span>กรณีห้องปฏิบัติการและสำนักงานมีข้อตกลงร่วมกันสำหรับการแสดงเครื่องหมายร่วม ILAC MRA 
                การแสดงเครื่องหมายร่วม ILAC 
                </span>
            </div>

            <div style="margin-left:30px; line-height:36px">
                <table>
                    <tr>
                        <td>
                            <span >
                               <input type="checkbox" name="offer_ilac_agreement_yes" id="offer_ilac_agreement_yes"> ไม่เป็น <input type="checkbox" name="offer_ilac_agreement_no" id="offer_ilac_agreement_no"> เป็น ไปตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่างประเทศด้วยการรับรองห้องปฏิบัติการสำหรับห้องปฏิบัติการและหน่วยตรวจ และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง  สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) (ถ้ามี)  	
                            </span>
                        </td>
                    </tr>
                </table>
            </div>


            {{-- ======================================= --}}

            <div class="signature-section" style="margin-top: 20px">
                <div style="line-height: 40px">
                   <div>
                       <select class="signature-select" id="signer-1">
                           <option value="">- ผู้ลงนาม -</option>
                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-1" value="หัวหน้าคณะผู้ตรวจประเมิน" />
                   </div>
                </div>
   
                <div style="line-height: 40px;margin-top:30px">
                   <div>
                       <select class="signature-select" id="signer-2">
                           <option value="">- ผู้ลงนาม -</option>

                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-2" value="นักวิชาการมาตรฐานชำนาญการพิเศษ" />
                   </div>
                </div>
   
                <div style="line-height: 40px;margin-top:30px">
                   <div>
                       <select class="signature-select" id="signer-3">
                           <option value="">- ผู้ลงนาม -</option>
                       </select>
                   </div>
                   <div>
                       <input type="text" class="input-no-border" style="text-align: center" id="position-3" value="ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ" />
                   </div>
                </div>
             
            </div>
               
            <div style="text-align: center;margin-bottom:20px;margin-top:20px" id="button_wrapper">
                <button  type="button" id="btn_draft_submit" class="btn btn-red" >ฉบับร่าง</button>
                <button  type="button" id="btn_submit" class="btn btn-green" >บันทึก</button>
            </div>
            
        </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const _token = $('meta[name="csrf-token"]').attr('content'); // หรือค่าที่คุณกำหนดไว้
        let blockId = null
        const maxWidth = 800;
        const testContainer = document.getElementById('data_container_2_5_1');
        const tableContainer = document.getElementById('table_container_2_5_1');
        let persons = []; // อาร์เรย์สำหรับเก็บข้อมูล
        let references = []; // อาร์เรย์สำหรับเก็บข้อมูลไฟล์
        let notice;
        let assessment;
        let boardAuditor;
        let certi_lab;
        let labRequest;
        let labReportOne;
        let data;
        let signAssessmentReportTransactions;
        let totalPendingTransactions;
        let totalTransactions;
        let tracking;
        const defectBlock = [
            { id: "2_5_1", defect_info: [] },
            { id: "2_5_2", defect_info: [] },
            { id: "2_5_3", defect_info: [] },
            { id: "2_5_4", defect_info: [] },
            { id: "2_5_5", defect_info: [] }
        ];

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
        $(function () {

            let lastChecked = null;
            labReportOne = @json($labReportOne ?? []);
            tracking = @json($tracking ?? []);
            // notice = @json($notice ?? []);
            assessment = @json($assessment ?? []);
            boardAuditor = @json($boardAuditor ?? []);
            certi_lab = @json($app_certi_lab ?? []);
            labRequest = @json($labRequest ?? []);
            signAssessmentReportTransactions = @json($signAssessmentReportTransactions ?? []);

            totalPendingTransactions = @json($totalPendingTransactions ?? null);
            totalTransactions = @json($totalTransactions ?? null);

            console.log('assessment',assessment)

            // console.log('totalPendingTransactions',signAssessmentReportTransactions.length);

        //    if(signAssessmentReportTransactions.length != 0){
        //          $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
        //          $('.wrapper').css('pointer-events', 'none'); // ปิดการคลิกและโต้ตอบทุกอย่างใน div.wrapper
        //          $('.wrapper').css('opacity', '0.7'); // เพิ่มความโปร่งใสเพื่อแสดงว่าถูกปิดใช้งาน (ไม่บังคับ)
        //    }

        if(totalTransactions !== null)
        {
            // if(totalTransactions != 0 && totalPendingTransactions == 0)
            // {
            //     $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
            //     $('.wrapper').css({
            //         'pointer-events': 'none', // ปิดการคลิกทั้งหมด
            //         'opacity': '0.8' // เพิ่มความโปร่งใส
            //     });
            //     $('#files_wrapper').css('pointer-events', 'auto');
            //     $('.wrapper button').not('#files_wrapper button').hide();
            // }
        }
        
        if (labReportOne.status == "2") {
            console.log("labReportOne status",labReportOne.status)
            $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery

            // ปิดการคลิกและโต้ตอบทุกอย่างใน div.wrapper ยกเว้น #files_wrapper
            $('.wrapper').css({
                'pointer-events': 'none', // ปิดการคลิกทั้งหมด
                'opacity': '0.7' // เพิ่มความโปร่งใส
            });

            // เปิดการคลิกสำหรับ #files_wrapper และเนื้อหาภายใน
            $('#files_wrapper').css('pointer-events', 'auto');

            // ซ่อนทุกปุ่มใน .wrapper ยกเว้นปุ่มที่อยู่ใน #files_wrapper
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

            loadSigners();

            $('input[type="radio"]').on('click', function () {
                console.log(this)
                if (this === lastChecked) {
                    // ถ้าเป็นตัวเดียวกันที่คลิกซ้ำ ให้ยกเลิกการเลือก
                    $(this).prop('checked', false);
                    lastChecked = null;
                } else {
                    // บันทึกตัวที่เลือกล่าสุด
                    lastChecked = this;
                }
            });

            populateForm();

            if (labReportOne.persons) {
                try {
                    persons = JSON.parse(labReportOne.persons);
                    renderPersons();
                } catch (error) {
                    console.error('Failed to parse persons data:', error);
                    persons = [];
                }
            }

            if (labReportOne.attachments && Array.isArray(labReportOne.attachments) && labReportOne.attachments.length > 0) {
                references = labReportOne.attachments.map(attachment => ({
                    name: attachment.filename,
                    path: attachment.url,
                    size: attachment.size,
                    mime: attachment.file_properties === 'pdf' ? 'application/pdf' : attachment.file_properties
                }));
                
                renderReferences(references);
            }


        });

                
        function populateForm() {
                if (!labReportOne || Object.keys(labReportOne).length === 0) {
                    console.log('No labReportOne data available');
                    return;
                }

                // ฟังก์ชันช่วยแปลงค่าเป็น boolean หรือ false ถ้า undefined
                const toBoolean = (value) => value !== undefined ? value === "true" : false;
                const toString = (value) => value !== undefined ? value : '';

                // ส่วนที่ 1: ข้อกำหนดตามมาตรฐาน มอก. 17025-2561
                $('#chk_impartiality_yes').prop('checked', toBoolean(labReportOne.chk_impartiality_yes));
                $('#chk_impartiality_no').prop('checked', toBoolean(labReportOne.chk_impartiality_no));
                $('#impartiality_text').val(toString(labReportOne.impartiality_text));

                $('#chk_confidentiality_yes').prop('checked', toBoolean(labReportOne.chk_confidentiality_yes));
                $('#chk_confidentiality_no').prop('checked', toBoolean(labReportOne.chk_confidentiality_no));
                $('#confidentiality_text').val(toString(labReportOne.confidentiality_text));

                $('#chk_structure_yes').prop('checked', toBoolean(labReportOne.chk_structure_yes));
                $('#chk_structure_no').prop('checked', toBoolean(labReportOne.chk_structure_no));
                $('#structure_text').val(toString(labReportOne.structure_text));

                $('#chk_res_general_yes').prop('checked', toBoolean(labReportOne.chk_res_general_yes));
                $('#chk_res_general_no').prop('checked', toBoolean(labReportOne.chk_res_general_no));
                $('#res_general_text').val(toString(labReportOne.res_general_text));

                $('#chk_res_personnel_yes').prop('checked', toBoolean(labReportOne.chk_res_personnel_yes));
                $('#chk_res_personnel_no').prop('checked', toBoolean(labReportOne.chk_res_personnel_no));
                $('#res_personnel_text').val(toString(labReportOne.res_personnel_text));

                $('#chk_res_facility_yes').prop('checked', toBoolean(labReportOne.chk_res_facility_yes));
                $('#chk_res_facility_no').prop('checked', toBoolean(labReportOne.chk_res_facility_no));
                $('#res_facility_text').val(toString(labReportOne.res_facility_text));

                $('#chk_res_equipment_yes').prop('checked', toBoolean(labReportOne.chk_res_equipment_yes));
                $('#chk_res_equipment_no').prop('checked', toBoolean(labReportOne.chk_res_equipment_no));
                $('#res_equipment_text').val(toString(labReportOne.res_equipment_text));

                $('#chk_res_traceability_yes').prop('checked', toBoolean(labReportOne.chk_res_traceability_yes));
                $('#chk_res_traceability_no').prop('checked', toBoolean(labReportOne.chk_res_traceability_no));
                $('#res_traceability_text').val(toString(labReportOne.res_traceability_text));

                $('#chk_res_external_yes').prop('checked', toBoolean(labReportOne.chk_res_external_yes));
                $('#chk_res_external_no').prop('checked', toBoolean(labReportOne.chk_res_external_no));
                $('#res_external_text').val(toString(labReportOne.res_external_text));

                $('#chk_proc_review_yes').prop('checked', toBoolean(labReportOne.chk_proc_review_yes));
                $('#chk_proc_review_no').prop('checked', toBoolean(labReportOne.chk_proc_review_no));
                $('#proc_review_text').val(toString(labReportOne.proc_review_text));

                $('#chk_proc_method_yes').prop('checked', toBoolean(labReportOne.chk_proc_method_yes));
                $('#chk_proc_method_no').prop('checked', toBoolean(labReportOne.chk_proc_method_no));
                $('#proc_method_text').val(toString(labReportOne.proc_method_text));

                $('#chk_proc_sampling_yes').prop('checked', toBoolean(labReportOne.chk_proc_sampling_yes));
                $('#chk_proc_sampling_no').prop('checked', toBoolean(labReportOne.chk_proc_sampling_no));
                $('#proc_sampling_text').val(toString(labReportOne.proc_sampling_text));

                $('#chk_proc_sample_handling_yes').prop('checked', toBoolean(labReportOne.chk_proc_sample_handling_yes));
                $('#chk_proc_sample_handling_no').prop('checked', toBoolean(labReportOne.chk_proc_sample_handling_no));
                $('#proc_sample_handling_text').val(toString(labReportOne.proc_sample_handling_text));

                $('#chk_proc_tech_record_yes').prop('checked', toBoolean(labReportOne.chk_proc_tech_record_yes));
                $('#chk_proc_tech_record_no').prop('checked', toBoolean(labReportOne.chk_proc_tech_record_no));
                $('#proc_tech_record_text').val(toString(labReportOne.proc_tech_record_text));

                $('#chk_proc_uncertainty_yes').prop('checked', toBoolean(labReportOne.chk_proc_uncertainty_yes));
                $('#chk_proc_uncertainty_no').prop('checked', toBoolean(labReportOne.chk_proc_uncertainty_no));
                $('#proc_uncertainty_text').val(toString(labReportOne.proc_uncertainty_text));

                $('#chk_proc_validity_yes').prop('checked', toBoolean(labReportOne.chk_proc_validity_yes));
                $('#chk_proc_validity_no').prop('checked', toBoolean(labReportOne.chk_proc_validity_no));
                $('#proc_validity_text').val(toString(labReportOne.proc_validity_text));

                $('#chk_proc_reporting_yes').prop('checked', toBoolean(labReportOne.chk_proc_reporting_yes));
                $('#chk_proc_reporting_no').prop('checked', toBoolean(labReportOne.chk_proc_reporting_no));
                $('#proc_reporting_text').val(toString(labReportOne.proc_reporting_text));

                $('#chk_proc_complaint_yes').prop('checked', toBoolean(labReportOne.chk_proc_complaint_yes));
                $('#chk_proc_complaint_no').prop('checked', toBoolean(labReportOne.chk_proc_complaint_no));
                $('#proc_complaint_text').val(toString(labReportOne.proc_complaint_text));

                $('#chk_proc_nonconformity_yes').prop('checked', toBoolean(labReportOne.chk_proc_nonconformity_yes));
                $('#chk_proc_nonconformity_no').prop('checked', toBoolean(labReportOne.chk_proc_nonconformity_no));
                $('#proc_nonconformity_text').val(toString(labReportOne.proc_nonconformity_text));

                $('#chk_proc_data_control_yes').prop('checked', toBoolean(labReportOne.chk_proc_data_control_yes));
                $('#chk_proc_data_control_no').prop('checked', toBoolean(labReportOne.chk_proc_data_control_no));
                $('#proc_data_control_text').val(toString(labReportOne.proc_data_control_text));

                $('#chk_res_selection_yes').prop('checked', toBoolean(labReportOne.chk_res_selection_yes));
                $('#chk_res_selection_no').prop('checked', toBoolean(labReportOne.chk_res_selection_no));
                $('#res_selection_text').val(toString(labReportOne.res_selection_text));

                $('#chk_res_docsystem_yes').prop('checked', toBoolean(labReportOne.chk_res_docsystem_yes));
                $('#chk_res_docsystem_no').prop('checked', toBoolean(labReportOne.chk_res_docsystem_no));
                $('#res_docsystem_text').val(toString(labReportOne.res_docsystem_text));

                $('#chk_res_doccontrol_yes').prop('checked', toBoolean(labReportOne.chk_res_doccontrol_yes));
                $('#chk_res_doccontrol_no').prop('checked', toBoolean(labReportOne.chk_res_doccontrol_no));
                $('#res_doccontrol_text').val(toString(labReportOne.res_doccontrol_text));

                $('#chk_res_recordcontrol_yes').prop('checked', toBoolean(labReportOne.chk_res_recordcontrol_yes));
                $('#chk_res_recordcontrol_no').prop('checked', toBoolean(labReportOne.chk_res_recordcontrol_no));
                $('#res_recordcontrol_text').val(toString(labReportOne.res_recordcontrol_text));

                $('#chk_res_riskopportunity_yes').prop('checked', toBoolean(labReportOne.chk_res_riskopportunity_yes));
                $('#chk_res_riskopportunity_no').prop('checked', toBoolean(labReportOne.chk_res_riskopportunity_no));
                $('#res_riskopportunity_text').val(toString(labReportOne.res_riskopportunity_text));

                $('#chk_res_improvement_yes').prop('checked', toBoolean(labReportOne.chk_res_improvement_yes));
                $('#chk_res_improvement_no').prop('checked', toBoolean(labReportOne.chk_res_improvement_no));
                $('#res_improvement_text').val(toString(labReportOne.res_improvement_text));

                $('#chk_res_corrective_yes').prop('checked', toBoolean(labReportOne.chk_res_corrective_yes));
                $('#chk_res_corrective_no').prop('checked', toBoolean(labReportOne.chk_res_corrective_no));
                $('#res_corrective_text').val(toString(labReportOne.res_corrective_text));

                $('#chk_res_audit_yes').prop('checked', toBoolean(labReportOne.chk_res_audit_yes));
                $('#chk_res_audit_no').prop('checked', toBoolean(labReportOne.chk_res_audit_no));
                $('#res_audit_text').val(toString(labReportOne.res_audit_text));

                $('#chk_res_review_yes').prop('checked', toBoolean(labReportOne.chk_res_review_yes));
                $('#chk_res_review_no').prop('checked', toBoolean(labReportOne.chk_res_review_no));
                $('#res_review_text').val(toString(labReportOne.res_review_text));

                // ส่วนที่ 2: การเฝ้าระวัง
                $('#report_display_certification_none').prop('checked', toBoolean(labReportOne.report_display_certification_none));
                $('#report_display_certification_yes').prop('checked', toBoolean(labReportOne.report_display_certification_yes));
                $('#report_scope_certified_only').prop('checked', toBoolean(labReportOne.report_scope_certified_only));
                $('#report_scope_certified_all').prop('checked', toBoolean(labReportOne.report_scope_certified_all));
                $('#report_activities_not_certified_yes').prop('checked', toBoolean(labReportOne.report_activities_not_certified_yes));
                $('#report_activities_not_certified_no').prop('checked', toBoolean(labReportOne.report_activities_not_certified_no));
                $('#report_accuracy_correct').prop('checked', toBoolean(labReportOne.report_accuracy_correct));
                $('#report_accuracy_incorrect').prop('checked', toBoolean(labReportOne.report_accuracy_incorrect));
                $('#report_accuracy_detail').val(toString(labReportOne.report_accuracy_detail));

                $('#multisite_display_certification_none').prop('checked', toBoolean(labReportOne.multisite_display_certification_none));
                $('#multisite_display_certification_yes').prop('checked', toBoolean(labReportOne.multisite_display_certification_yes));
                $('#multisite_scope_certified_only').prop('checked', toBoolean(labReportOne.multisite_scope_certified_only));
                $('#multisite_scope_certified_all').prop('checked', toBoolean(labReportOne.multisite_scope_certified_all));
                $('#multisite_activities_not_certified_yes').prop('checked', toBoolean(labReportOne.multisite_activities_not_certified_yes));
                $('#multisite_activities_not_certified_no').prop('checked', toBoolean(labReportOne.multisite_activities_not_certified_no));
                $('#multisite_accuracy_correct').prop('checked', toBoolean(labReportOne.multisite_accuracy_correct));
                $('#multisite_accuracy_incorrect').prop('checked', toBoolean(labReportOne.multisite_accuracy_incorrect));
                $('#multisite_accuracy_detail').val(toString(labReportOne.multisite_accuracy_detail));

                $('#certification_status_correct').prop('checked', toBoolean(labReportOne.certification_status_correct));
                $('#certification_status_incorrect').prop('checked', toBoolean(labReportOne.certification_status_incorrect));
                $('#certification_status_details').val(toString(labReportOne.certification_status_details));

                $('#other_certification_status_correct').prop('checked', toBoolean(labReportOne.other_certification_status_correct));
                $('#other_certification_status_incorrect').prop('checked', toBoolean(labReportOne.other_certification_status_incorrect));
                $('#other_certification_status_details').val(toString(labReportOne.other_certification_status_details));

                // ส่วนที่ 3: ILAC MRA
                $('#lab_availability_yes').prop('checked', toBoolean(labReportOne.lab_availability_yes));
                $('#lab_availability_no').prop('checked', toBoolean(labReportOne.lab_availability_no));

                $('#ilac_mra_display_no').prop('checked', toBoolean(labReportOne.ilac_mra_display_no));
                $('#ilac_mra_display_yes').prop('checked', toBoolean(labReportOne.ilac_mra_display_yes));
                $('#ilac_mra_scope_no').prop('checked', toBoolean(labReportOne.ilac_mra_scope_no));
                $('#ilac_mra_scope_yes').prop('checked', toBoolean(labReportOne.ilac_mra_scope_yes));
                $('#ilac_mra_disclosure_yes').prop('checked', toBoolean(labReportOne.ilac_mra_disclosure_yes));
                $('#ilac_mra_disclosure_no').prop('checked', toBoolean(labReportOne.ilac_mra_disclosure_no));
                $('#ilac_mra_compliance_correct').prop('checked', toBoolean(labReportOne.ilac_mra_compliance_correct));
                $('#ilac_mra_compliance_incorrect').prop('checked', toBoolean(labReportOne.ilac_mra_compliance_incorrect));
                $('#ilac_mra_compliance_details').val(toString(labReportOne.ilac_mra_compliance_details));

                $('#other_ilac_mra_compliance_no').prop('checked', toBoolean(labReportOne.other_ilac_mra_compliance_no));
                $('#other_ilac_mra_compliance_yes').prop('checked', toBoolean(labReportOne.other_ilac_mra_compliance_yes));
                $('#other_ilac_mra_compliance_details').val(toString(labReportOne.other_ilac_mra_compliance_details));

                $('#mra_compliance_correct').prop('checked', toBoolean(labReportOne.mra_compliance_correct));
                $('#mra_compliance_incorrect').prop('checked', toBoolean(labReportOne.mra_compliance_incorrect));
                $('#mra_compliance_details').val(toString(labReportOne.mra_compliance_details));

                $('#evidence_mra_compliance_details_1').val(toString(labReportOne.evidence_mra_compliance_details_1));
                $('#evidence_mra_compliance_details_2').val(toString(labReportOne.evidence_mra_compliance_details_2));
                $('#evidence_mra_compliance_details_3').val(toString(labReportOne.evidence_mra_compliance_details_3));
                $('#evidence_mra_compliance_details_4').val(toString(labReportOne.evidence_mra_compliance_details_4));

                $('#offer_agreement_yes').prop('checked', toBoolean(labReportOne.offer_agreement_yes));
                $('#offer_agreement_no').prop('checked', toBoolean(labReportOne.offer_agreement_no));

                $('#offer_ilac_agreement_yes').prop('checked', toBoolean(labReportOne.offer_ilac_agreement_yes));
                $('#offer_ilac_agreement_no').prop('checked', toBoolean(labReportOne.offer_ilac_agreement_no));

                // ฟิลด์เพิ่มเติม
                $('#book_no_text').val(toString(labReportOne.book_no_text));
                $('#audit_observation_text').val(toString(labReportOne.audit_observation_text));
            }

        function loadSigners() {
            $.ajax({
                url: "{{ route('assessment_report_assignment.api.get_signers') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // ส่ง CSRF token สำหรับ Laravel
                },
                success: function(response) {
                    console.log('signers', response);

                    if (response.signers && Array.isArray(response.signers)) {
                        // ลบ option เก่าใน <select>
                        $('.signature-select').empty().append('<option value="">- ผู้ลงนาม -</option>');
                        
                        // เติม option ใหม่
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


        $('#btn_draft_submit').on('click', function() {
            submit(1);
        });

        $('#btn_submit').on('click', function() {
            submit(2);
        });

        function submit(submit_type){

            var data = {
                book_no_text: $('#book_no_text').val(),
                audit_observation_text: $('#audit_observation_text').val(),
                chk_impartiality_yes: $('#chk_impartiality_yes').is(':checked'),
                chk_impartiality_no: $('#chk_impartiality_no').is(':checked'),
                impartiality_text: $('#impartiality_text').val(),

                chk_confidentiality_yes: $('#chk_confidentiality_yes').is(':checked'),
                chk_confidentiality_no: $('#chk_confidentiality_no').is(':checked'),
                confidentiality_text: $('#confidentiality_text').val(),

                chk_structure_yes: $('#chk_structure_yes').is(':checked'),
                chk_structure_no: $('#chk_structure_no').is(':checked'),
                structure_text: $('#structure_text').val(),

                chk_res_general_yes: $('#chk_res_general_yes').is(':checked'),
                chk_res_general_no: $('#chk_res_general_no').is(':checked'),
                res_general_text: $('#res_general_text').val(),

                chk_res_personnel_yes: $('#chk_res_personnel_yes').is(':checked'),
                chk_res_personnel_no: $('#chk_res_personnel_no').is(':checked'),
                res_personnel_text: $('#res_personnel_text').val(),

                chk_res_facility_yes: $('#chk_res_facility_yes').is(':checked'),
                chk_res_facility_no: $('#chk_res_facility_no').is(':checked'),
                res_facility_text: $('#res_facility_text').val(),

                chk_res_equipment_yes: $('#chk_res_equipment_yes').is(':checked'),
                chk_res_equipment_no: $('#chk_res_equipment_no').is(':checked'),
                res_equipment_text: $('#res_equipment_text').val(),

                chk_res_traceability_yes: $('#chk_res_traceability_yes').is(':checked'),
                chk_res_traceability_no: $('#chk_res_traceability_no').is(':checked'),
                res_traceability_text: $('#res_traceability_text').val(),

                chk_res_external_yes: $('#chk_res_external_yes').is(':checked'),
                chk_res_external_no: $('#chk_res_external_no').is(':checked'),
                res_external_text: $('#res_external_text').val(),

                chk_proc_review_yes: $('#chk_proc_review_yes').is(':checked'),
                chk_proc_review_no: $('#chk_proc_review_no').is(':checked'),
                proc_review_text: $('#proc_review_text').val(),

                chk_proc_method_yes: $('#chk_proc_method_yes').is(':checked'),
                chk_proc_method_no: $('#chk_proc_method_no').is(':checked'),
                proc_method_text: $('#proc_method_text').val(),

                chk_proc_sampling_yes: $('#chk_proc_sampling_yes').is(':checked'),
                chk_proc_sampling_no: $('#chk_proc_sampling_no').is(':checked'),
                proc_sampling_text: $('#proc_sampling_text').val(),

                chk_proc_sample_handling_yes: $('#chk_proc_sample_handling_yes').is(':checked'),
                chk_proc_sample_handling_no: $('#chk_proc_sample_handling_no').is(':checked'),
                proc_sample_handling_text: $('#proc_sample_handling_text').val(),

                chk_proc_tech_record_yes: $('#chk_proc_tech_record_yes').is(':checked'),
                chk_proc_tech_record_no: $('#chk_proc_tech_record_no').is(':checked'),
                proc_tech_record_text: $('#proc_tech_record_text').val(),

                chk_proc_uncertainty_yes: $('#chk_proc_uncertainty_yes').is(':checked'),
                chk_proc_uncertainty_no: $('#chk_proc_uncertainty_no').is(':checked'),
                proc_uncertainty_text: $('#proc_uncertainty_text').val(),

                chk_proc_validity_yes: $('#chk_proc_validity_yes').is(':checked'),
                chk_proc_validity_no: $('#chk_proc_validity_no').is(':checked'),
                proc_validity_text: $('#proc_validity_text').val(),

                chk_proc_reporting_yes: $('#chk_proc_reporting_yes').is(':checked'),
                chk_proc_reporting_no: $('#chk_proc_reporting_no').is(':checked'),
                proc_reporting_text: $('#proc_reporting_text').val(),

                chk_proc_complaint_yes: $('#chk_proc_complaint_yes').is(':checked'),
                chk_proc_complaint_no: $('#chk_proc_complaint_no').is(':checked'),
                proc_complaint_text: $('#proc_complaint_text').val(),

                chk_proc_nonconformity_yes: $('#chk_proc_nonconformity_yes').is(':checked'),
                chk_proc_nonconformity_no: $('#chk_proc_nonconformity_no').is(':checked'),
                proc_nonconformity_text: $('#proc_nonconformity_text').val(),

                chk_proc_data_control_yes: $('#chk_proc_data_control_yes').is(':checked'),
                chk_proc_data_control_no: $('#chk_proc_data_control_no').is(':checked'),
                proc_data_control_text: $('#proc_data_control_text').val(),

                chk_res_selection_yes: $('#chk_res_selection_yes').is(':checked'),
                chk_res_selection_no: $('#chk_res_selection_no').is(':checked'),
                res_selection_text: $('#res_selection_text').val(),

                chk_res_docsystem_yes: $('#chk_res_docsystem_yes').is(':checked'),
                chk_res_docsystem_no: $('#chk_res_docsystem_no').is(':checked'),
                res_docsystem_text: $('#res_docsystem_text').val(),

                chk_res_doccontrol_yes: $('#chk_res_doccontrol_yes').is(':checked'),
                chk_res_doccontrol_no: $('#chk_res_doccontrol_no').is(':checked'),
                res_doccontrol_text: $('#res_doccontrol_text').val(),

                chk_res_recordcontrol_yes: $('#chk_res_recordcontrol_yes').is(':checked'),
                chk_res_recordcontrol_no: $('#chk_res_recordcontrol_no').is(':checked'),
                res_recordcontrol_text: $('#res_recordcontrol_text').val(),

                chk_res_riskopportunity_yes: $('#chk_res_riskopportunity_yes').is(':checked'),
                chk_res_riskopportunity_no: $('#chk_res_riskopportunity_no').is(':checked'),
                res_riskopportunity_text: $('#res_riskopportunity_text').val(),

                chk_res_improvement_yes: $('#chk_res_improvement_yes').is(':checked'),
                chk_res_improvement_no: $('#chk_res_improvement_no').is(':checked'),
                res_improvement_text: $('#res_improvement_text').val(),

                chk_res_corrective_yes: $('#chk_res_corrective_yes').is(':checked'),
                chk_res_corrective_no: $('#chk_res_corrective_no').is(':checked'),
                res_corrective_text: $('#res_corrective_text').val(),

                chk_res_audit_yes: $('#chk_res_audit_yes').is(':checked'),
                chk_res_audit_no: $('#chk_res_audit_no').is(':checked'),
                res_audit_text: $('#res_audit_text').val(),

                chk_res_review_yes: $('#chk_res_review_yes').is(':checked'),
                chk_res_review_no: $('#chk_res_review_no').is(':checked'),
                res_review_text: $('#res_review_text').val(),

                report_display_certification_none: $('#report_display_certification_none').is(':checked'),
                report_display_certification_yes: $('#report_display_certification_yes').is(':checked'),
                report_scope_certified_only: $('#report_scope_certified_only').is(':checked'),
                report_scope_certified_all: $('#report_scope_certified_all').is(':checked'),
                report_activities_not_certified_yes: $('#report_activities_not_certified_yes').is(':checked'),
                report_activities_not_certified_no: $('#report_activities_not_certified_no').is(':checked'),
                report_accuracy_correct: $('#report_accuracy_correct').is(':checked'),
                report_accuracy_incorrect: $('#report_accuracy_incorrect').is(':checked'),
                report_accuracy_detail: $('#report_accuracy_detail').val(),
                multisite_display_certification_none: $('#multisite_display_certification_none').is(':checked'),
                multisite_display_certification_yes: $('#multisite_display_certification_yes').is(':checked'),
                multisite_scope_certified_only: $('#multisite_scope_certified_only').is(':checked'),
                multisite_scope_certified_all: $('#multisite_scope_certified_all').is(':checked'),
                multisite_activities_not_certified_yes: $('#multisite_activities_not_certified_yes').is(':checked'),
                multisite_activities_not_certified_no: $('#multisite_activities_not_certified_no').is(':checked'),
                multisite_accuracy_correct: $('#multisite_accuracy_correct').is(':checked'),
                multisite_accuracy_incorrect: $('#multisite_accuracy_incorrect').is(':checked'),
                multisite_accuracy_detail: $('#multisite_accuracy_detail').val(),
                certification_status_correct: $('#certification_status_correct').is(':checked'),
                certification_status_incorrect: $('#certification_status_incorrect').is(':checked'),
                certification_status_details: $('#certification_status_details').val(),
                other_certification_status_correct: $('#other_certification_status_correct').is(':checked'),
                other_certification_status_incorrect: $('#other_certification_status_incorrect').is(':checked'),
                other_certification_status_details: $('#other_certification_status_details').val(),
                lab_availability_yes: $('#lab_availability_yes').is(':checked'),
                lab_availability_no: $('#lab_availability_no').is(':checked'),
                ilac_mra_display_no: $('#ilac_mra_display_no').is(':checked'),
                ilac_mra_display_yes: $('#ilac_mra_display_yes').is(':checked'),
                ilac_mra_scope_no: $('#ilac_mra_scope_no').is(':checked'),
                ilac_mra_scope_yes: $('#ilac_mra_scope_yes').is(':checked'),
                ilac_mra_disclosure_yes: $('#ilac_mra_disclosure_yes').is(':checked'),
                ilac_mra_disclosure_no: $('#ilac_mra_disclosure_no').is(':checked'),
                ilac_mra_compliance_correct: $('#ilac_mra_compliance_correct').is(':checked'),
                ilac_mra_compliance_incorrect: $('#ilac_mra_compliance_incorrect').is(':checked'),
                ilac_mra_compliance_details: $('#ilac_mra_compliance_details').val(),
                other_ilac_mra_compliance_no: $('#other_ilac_mra_compliance_no').is(':checked'),
                other_ilac_mra_compliance_yes: $('#other_ilac_mra_compliance_yes').is(':checked'),
                other_ilac_mra_compliance_details: $('#other_ilac_mra_compliance_details').val(),
                mra_compliance_correct: $('#mra_compliance_correct').is(':checked'),
                mra_compliance_incorrect: $('#mra_compliance_incorrect').is(':checked'),
                mra_compliance_details: $('#mra_compliance_details').val(),
                evidence_mra_compliance_details_1: $('#evidence_mra_compliance_details_1').val(),
                evidence_mra_compliance_details_2: $('#evidence_mra_compliance_details_2').val(),
                evidence_mra_compliance_details_3: $('#evidence_mra_compliance_details_3').val(),
                evidence_mra_compliance_details_4: $('#evidence_mra_compliance_details_4').val(),
                offer_agreement_yes: $('#offer_agreement_yes').is(':checked'),
                offer_agreement_no: $('#offer_agreement_no').is(':checked'),
                offer_ilac_agreement_yes: $('#offer_ilac_agreement_yes').is(':checked'),
                offer_ilac_agreement_no: $('#offer_ilac_agreement_no').is(':checked'),
            };

            signer.forEach(function(item, index) {
                const positionInput = $(`#position-${index + 1}`); // ดึงค่าจาก input
                if (positionInput.length) {
                    item.signer_position = positionInput.val(); // อัปเดต signer_position
                }
            });

            // const isComplete = signer.every(item => item.signer_name && item.signer_position);

                const isComplete = signer.every(item => 
                    item.signer_name && 
                    item.signer_name !== '- ผู้ลงนาม -' && 
                    item.signer_id && 
                    item.signer_position
                );
                    

            console.log(signer);
            if (!isComplete) {
                console.warn('กรุณาเลือกผู้ลงนามให้ครบและตำแหน่ง');
                alert('กรุณาเลือกผู้ลงนามให้ครบและตำแหน่ง');
                return;
            }
            
            const formData = new FormData();

            formData.append('_token', _token);

            const payload = {
                data: data,
                persons: persons,
                assessment:assessment,
                signer:signer,
                submit_type:submit_type
            };

                // เพิ่ม payload (ยกเว้นไฟล์) เข้า FormData
            formData.append('payload', JSON.stringify(payload));
            
            // เพิ่มไฟล์จาก references
            references.forEach((reference, index) => {
                formData.append(`references[${index}]`, reference.file);
            });

            $('#loadingStatus').show();

            $.ajax({
                url: "{{ route('certificate.assessment-labs.update_lab_report_one') }}",
                method: "POST",
                data: formData,
                processData: false, // ป้องกัน jQuery จากการแปลง FormData
                contentType: false, // ให้ browser จัดการ contentType (multipart/form-data)
                success: function(response) {
                    $('#loadingStatus').hide();
                    const baseUrl = "{{ url('/certificate/tracking-labs') }}";
                    window.location.href = baseUrl+`/`+tracking.id+`/edit`;
                }
            });

            // AJAX
            // $.ajax({
            //     url: "{{ route('save_assessment.update_lab_report2_info') }}",
            //     method: "POST",
            //     data: JSON.stringify(payload), // แปลงเป็น JSON
            //     contentType: 'application/json', // ระบุว่าเป็น JSON
            //     success: function(response) {
                   

            //         const baseUrl = "{{ url('/certify/save_assessment') }}";

            //         window.location.href = `${baseUrl}/${notice.id}/assess_edit/${notice.app_certi_lab_id}`;
            //         // console.log('สำเร็จ:', `${baseUrl}/${notice.id}/assess_edit/${notice.app_certi_lab_id}`);
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('เกิดข้อผิดพลาด:', error);
            //         $('#loadingStatus').hide();
            //     }
            // });


        }

        function showModal(id) {
            blockId = id
            document.getElementById('modal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            document.getElementById('modal-input').value = '';
        }

        function openAddPersonModal() {
            document.getElementById('modal-add-person').style.display = 'block';
        }

        function closeAddPersonModal() {
            document.getElementById('modal-add-person').style.display = 'none';
        }

        
        function addTextPerson() {
            const name = document.getElementById('person-name').value;
            const position = document.getElementById('person-position').value;

            if (name && position) {
                // เพิ่มข้อมูลเข้าไปในอาร์เรย์
                persons.push({ name, position });
                
                // เรียกฟังก์ชัน render เพื่อแสดงข้อมูลใหม่
                renderPersons();
                
                // ปิด modal
                closeAddPersonModal();
                
                // ล้างค่าฟอร์ม
                document.getElementById('person-name').value = '';
                document.getElementById('person-position').value = '';
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
                    <span style="flex: 1 0 300px;">
                        ตำแหน่ง ${person.position}
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
        function openUploadReferenceModal() {
            document.getElementById('uploadReferenceModal').style.display = 'block';
        }

        function closeUploadReferenceModal() {
            document.getElementById('uploadReferenceModal').style.display = 'none';
        }

   

        function uploadReference() {
            const fileInput = document.getElementById('referenceFile');
            const file = fileInput.files[0];

            if (file) {
                references.push({ name: file.name, file: file });
                renderReferences();
                closeUploadReferenceModal();
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
</body>
</html>
