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
            <div class="right-box">รายงานที่ 2</div>
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
                <p style="text-indent: 80px">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกต จากการตรวจติดตามผลการรับรองความสามารถของห้องปฏิบัติการ เมื่อวันที่ {{$audit_date}} ซึ่งพบข้อบกพร่องและข้อสังเกตทั้งสิ้น <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="observation_count_text" id="observation_count_text"> รายการ คณะผู้ตรวจประเมินได้พิจารณาหลักฐานการปฏิบัติการแก้ไขข้อบกพร่องที่ห้องปฏิบัติการส่งให้สำนักงานพิจารณา ได้แก่ หนังสือของห้องปฏิบัติการ{{$certi_lab->lab_name}} ลงรับวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 150px;text-align:center" name="lab_letter_received_date_text" id="lab_letter_received_date_text"> /ไปรษณีย์อิเล็กทรอนิกส์ วันที่ <input type="text" class="input-no-border" placeholder="" style="width: 150px;text-align:center" name="email_sent_date_tertiary_text" id="email_sent_date_tertiary_text"> (ถ้ามี) และ <input type="text" class="input-no-border" placeholder="" style="width: 150px;text-align:center" name="email_sent_date_secondary_text" id="email_sent_date_secondary_text"> (ถ้ามี) </p> 
                <p style="text-indent: 80px">โดยมีรายละเอียดดังสรุปการแก้ไขข้อบกพร่องของห้องปฏิบัติการที่แนบ</p> 

            </div>
            <div style="margin-left: 120px">
                <input type="checkbox" name="checkbox_corrective_action_completed" id="checkbox_corrective_action_completed"> คณะผู้ตรวจประเมินพบว่าห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องได้แล้วเสร็จสอดคล้องตามมาตรฐานเลขที่ มอก. 17025-2561
            </div>
               <div style="margin-left: 120px">
                <input type="checkbox" name="checkbox_corrective_action_incomplete" id="checkbox_corrective_action_incomplete"> คณะผู้ตรวจประเมินมีความเห็นว่า ห้องปฏิบัติการได้ดำเนินการแก้ไขข้อบกพร่องแล้วเสร็จ จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="remaining_nonconformities_count_text" id="remaining_nonconformities_count_text"> รายการ ยังคงเหลือข้อบกพร่อง <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="remaining_nonconformities_list_text" id="remaining_nonconformities_list_text"> รายการ  
            </div>
        </div>

        <div class="section">
            <div class="label">สรุปผลการตรวจติดตามผลการรับรอง : </div>
            <div class="content">
                
                <p style="text-indent: 60px">จากผลการตรวจประเมินและผลการปฏิบัติการแก้ไขข้อบกพร่อง คณะผู้ตรวจประเมินเห็นว่า</p> 

            </div>
            <div style="margin-left: 80px">
                <input type="checkbox" name="checkbox_extend_certification" id="checkbox_extend_certification"> ห้องปฏิบัติการยังคงรักษาระบบการบริหารงาน และการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก. 17025-2561 เห็นควรให้คงสถานะการรับรองต่อไป ทั้งนี้ หากห้องปฏิบัติการประสงค์จะต่ออายุใบรับรอง จะต้องยื่นคำขอต่ออายุล่วงหน้าไม่น้อยกว่า 120 วัน ก่อนวันที่ใบรับรองสิ้นอายุ
            </div>
               <div style="margin-left: 80px">
                <input type="checkbox" name="checkbox_reject_extend_certification" id="checkbox_reject_extend_certification"> ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน แต่ผลจากการแก้ไขส่งผลกระทบต่อขอบข่ายที่ได้รับการรับรอง จึงเห็นควรนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="reason_for_extension_decision_text" id="reason_for_extension_decision_text"> เพื่อพิจารณาลดสาขาและขอบข่ายการรับรองต่อไป
            </div>
            <div style="margin-left: 80px">
                <input type="checkbox" name="checkbox_submit_remaining_evidence" id="checkbox_submit_remaining_evidence"> ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องที่เหลืออยู่ <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="remaining_evidence_items_text" id="remaining_evidence_items_text"> รายการ  ให้คณะผู้ตรวจประเมินพิจารณาภายในวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 150px;text-align:center" name="remaining_evidence_due_date_text" id="remaining_evidence_due_date_text"> (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่องครั้งแรก) เมื่อพ้นกำหนดระยะเวลาดังกล่าว หากห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้คณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} พิจารณาให้ห้องปฏิบัติการลดขอบข่าย/พักใช้ใบรับรองต่อไป
            </div>
            <div style="margin-left: 80px">
                <input type="checkbox" name="checkbox_unresolved_nonconformities" id="checkbox_unresolved_nonconformities"> ห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน  สมควรนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} พิจารณา
            </div>

             <div style="margin-left: 100px">
                <input type="checkbox" name="checkbox_reduce_scope" id="checkbox_reduce_scope"> ลดสาขาและขอบข่ายการรับรอง (กรณีข้อบกพร่องที่ไม่สามารถแก้ไขได้กระทบความสามารถบางสาขาการรับรอง)
            </div>

             <div style="margin-left: 100px">
                <input type="checkbox" name="checkbox_suspend_certificate" id="checkbox_suspend_certificate"> พักใช้ใบรับรอง (กรณีข้อบกพร่องที่ไม่สามารถแก้ไขได้กระทบความสามารถต่อการรับรองทั้งหมดที่ได้รับใบรับรอง)
            </div>
        </div>





   

        <div class="section">

          
      


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
        let labReportTwo;
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
            labReportTwo = @json($labReportTwo ?? []);
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
        
        if (labReportTwo.status == "2") {
            console.log("labReportTwo status",labReportTwo.status)
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

            if (labReportTwo.persons) {
                try {
                    persons = JSON.parse(labReportTwo.persons);
                    renderPersons();
                } catch (error) {
                    console.error('Failed to parse persons data:', error);
                    persons = [];
                }
            }

            if (labReportTwo.attachments && Array.isArray(labReportTwo.attachments) && labReportTwo.attachments.length > 0) {
                references = labReportTwo.attachments.map(attachment => ({
                    name: attachment.filename,
                    path: attachment.url,
                    size: attachment.size,
                    mime: attachment.file_properties === 'pdf' ? 'application/pdf' : attachment.file_properties
                }));
                
                renderReferences(references);
            }


        });

                
      function populateForm() {
    if (!labReportTwo || Object.keys(labReportTwo).length === 0) {
        console.log('No labReportTwo data available');
        return;
    }

    // Helper functions to convert values
    const toBoolean = (value) => value !== undefined ? value === "true" : false;
    const toString = (value) => value !== undefined ? value : '';

    // Populate form fields
    $('#book_no_text').val(toString(labReportTwo.book_no_text));
    $('#observation_count_text').val(toString(labReportTwo.observation_count_text));
    $('#lab_letter_received_date_text').val(toString(labReportTwo.lab_letter_received_date_text));
    $('#email_sent_date_secondary_text').val(toString(labReportTwo.email_sent_date_secondary_text));
    $('#email_sent_date_tertiary_text').val(toString(labReportTwo.email_sent_date_tertiary_text));
    $('#checkbox_corrective_action_completed').prop('checked', toBoolean(labReportTwo.checkbox_corrective_action_completed));
    $('#checkbox_corrective_action_incomplete').prop('checked', toBoolean(labReportTwo.checkbox_corrective_action_incomplete));
    $('#remaining_nonconformities_count_text').val(toString(labReportTwo.remaining_nonconformities_count_text));
    $('#remaining_nonconformities_list_text').val(toString(labReportTwo.remaining_nonconformities_list_text));
    $('#checkbox_extend_certification').prop('checked', toBoolean(labReportTwo.checkbox_extend_certification));
    $('#checkbox_reject_extend_certification').prop('checked', toBoolean(labReportTwo.checkbox_reject_extend_certification));
    $('#reason_for_extension_decision_text').val(toString(labReportTwo.reason_for_extension_decision_text));
    $('#checkbox_submit_remaining_evidence').prop('checked', toBoolean(labReportTwo.checkbox_submit_remaining_evidence));
    $('#remaining_evidence_items_text').val(toString(labReportTwo.remaining_evidence_items_text));
    $('#checkbox_unresolved_nonconformities').prop('checked', toBoolean(labReportTwo.checkbox_unresolved_nonconformities));
    $('#remaining_evidence_due_date_text').val(toString(labReportTwo.remaining_evidence_due_date_text));
    $('#subcommittee_consideration_action_text').val(toString(labReportTwo.subcommittee_consideration_action_text));
    $('#checkbox_reduce_scope').prop('checked', toBoolean(labReportTwo.checkbox_reduce_scope));
    $('#checkbox_suspend_certificate').prop('checked', toBoolean(labReportTwo.checkbox_suspend_certificate));
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
                observation_count_text: $('#observation_count_text').val(),
                lab_letter_received_date_text: $('#lab_letter_received_date_text').val(),
                email_sent_date_tertiary_text: $('#email_sent_date_tertiary_text').val(),
                email_sent_date_secondary_text: $('#email_sent_date_secondary_text').val(),
                checkbox_corrective_action_completed: $('#checkbox_corrective_action_completed').is(':checked'),
                checkbox_corrective_action_incomplete: $('#checkbox_corrective_action_incomplete').is(':checked'),
                remaining_nonconformities_count_text: $('#remaining_nonconformities_count_text').val(),
                remaining_nonconformities_list_text: $('#remaining_nonconformities_list_text').val(),
                checkbox_extend_certification: $('#checkbox_extend_certification').is(':checked'),
                checkbox_reject_extend_certification: $('#checkbox_reject_extend_certification').is(':checked'),
                reason_for_extension_decision_text: $('#reason_for_extension_decision_text').val(),
                checkbox_submit_remaining_evidence: $('#checkbox_submit_remaining_evidence').is(':checked'),
                remaining_evidence_items_text: $('#remaining_evidence_items_text').val(),
                remaining_evidence_due_date_text: $('#remaining_evidence_due_date_text').val(),
                checkbox_unresolved_nonconformities: $('#checkbox_unresolved_nonconformities').is(':checked'),
                checkbox_reduce_scope: $('#checkbox_reduce_scope').is(':checked'),
                checkbox_suspend_certificate: $('#checkbox_suspend_certificate').is(':checked')
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
                url: "{{ route('certificate.assessment-labs.update_lab_report_two') }}",
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
