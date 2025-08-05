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
    
    <div id="loadingStatus" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <div class="loading-text">กำลังบันทึก...</div>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="left-text">คำขอที่ {{$certi_lab->app_no}}</div>
            <div class="right-box">รายงานที่ 1</div>
        </div>
        <div class="header">
            รายงานการตรวจประเมินความสามารถของห้องปฏิบัติการทดสอบ/สอบเทียบ<br>
            ตามมาตรฐานเลขที่ มอก. 17025-2561
        </div>

        <div class="checkbox-section">
            {{-- {{$certi_lab->purpose_type}} --}}
            <label>
                <input type="checkbox" 
                    @if ($certi_lab->purpose_type == 1)
                        checked
                    @endif
                    disabled>
                การขอรับใบรับรองใหม่
            </label>
            <label>
                <input type="checkbox" 
                    @if ($certi_lab->purpose_type == 3)
                        checked
                    @endif
                    disabled
                >
                
                การขยาย/ปรับขอบข่ายใบรับรอง
            </label>
            <label>
                <input type="checkbox" 
                    @if ($certi_lab->purpose_type == 2)
                        checked
                    @endif
                    disabled
                >
                ต่ออายุใบรับรอง
                
            </label>
        
            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                <span>
                    <label>
                        <input type="checkbox" 
                            @if ($certi_lab->purpose_type == null || !in_array($certi_lab->purpose_type, [1, 2, 3]))
                                checked
                            @endif
                            disabled
                        >
                        อื่น ๆ
                    </label>
                </span>
                <span>
                    <input type="text" class="input-no-border" placeholder="" style="width:500px" disabled>
                </span>
            </div>
        </div>
        


       <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
        <span>1. ข้อมูลทั่วไป</span>
       </div>
        <div style="margin-top: 10px;margin-left:20px; line-height:36px">
            <div>
                <span>ชื่อห้องปฏิบัติการ :</span>
                <span>{{$certi_lab->lab_name}}</span>
            </div>
            <div>
                <span>ตั้งอยู่เลขที่ :</span>
                <span>


                            {{$labScopeTransaction->address_number}} หมู่ที่ {{$labScopeTransaction->village_no}} 
                            @if(\Illuminate\Support\Str::contains($labScopeTransaction->address_city_text, 'กรุงเทพ'))
                                แขวง{{$labScopeTransaction->sub_district}} เขต{{$labScopeTransaction->address_district}} {{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                            @else
                                ตำบล{{$labScopeTransaction->sub_district}} อำเภอ{{$labScopeTransaction->address_district}} จังหวัด{{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                            @endif
             
                 
      

                </span>
                
            </div>
            <div>
                <span>วันที่ยื่นคำขอ :</span>
                <span>{{HP::formatDateThaiFullPoint($certi_lab->start_date)}}</span>
            </div>
            <div>
                <span>สาขาและขอบข่าย :</span>
                <span>ตามเอกสารประกอบคำขอของ ห้องปฏิบัติการ ลงวันที่ {{HP::formatDateThaiFullPoint($certi_lab->get_date)}} และ/หรือหนังสือขอแก้ไขขอบข่ายของ ห้องปฏิบัติการ ลงวันที่ {{HP::formatDateThaiFullPoint($notice->date_car)}} (ถ้ามี)/ ขอบข่าย ดังแนบ</span>
            </div>
        </div>

        <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
            <span>2. การตรวจประเมิน</span>
        </div>
        <div style="margin-left:20px; line-height:36px">
            <div>
                <div><span style="font-weight: 600">2.1 คณะผู้ตรวจประเมิน</span> ประกอบด้วย</div>
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
                            $info = HP::getExpertInfo($statusId, $auditorId);
                        @endphp
                        <div style="display: flex; gap: 10px;">
                            <span style="flex: 0 0 250px;">{{$index}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</span>
                            <span style="flex: 1 0 200px;">{!!$info->statusAuditor->title!!}</span>
                        </div>

                    @endforeach
                @endforeach
            </div>
            <div style="margin-top:10px">
                <span style="font-weight: 600">2.2 รูปแบบการตรวจประเมิน</span>  
            </div>
            <div style="margin-left: 25px">
                <!-- แถว 1 -->
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <label><input type="checkbox"  id="2_2_assessment_on_site" name="2_2_assessment_on_site"> ณ ห้องปฏิบัติการ</label>
                </div>
                
                <!-- แถว 2 -->
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <span style="flex: 1 0 300px;">
                        <label><input type="checkbox" id="2_2_assessment_at_tisi" name="2_2_assessment_at_tisi" > ตรวจประเมิน ณ สมอ. โดยวิธี</label>
                    </span>
                    <span style="flex: 1 0 500px;">
                        <label><input type="checkbox" id="2_2_remote_assessment" name="2_2_remote_assessment"> ตรวจประเมินทางไกล (remote assessment)</label>
                    </span>
                </div>
                
                <!-- แถว 3 -->
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <span style="flex: 1 0 300px;"></span> <!-- ช่องว่าง -->
                    <span style="flex: 1 0 550px;">
                        <label><input type="checkbox" id="2_2_self_declaration" name="2_2_self_declaration"> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</label>
                    </span>
                </div>
            </div>
            <div style="margin-top:10px;">
                    <span style="font-weight: 600">2.3 วันที่ตรวจประเมิน :</span>  
                    {{-- <span >{{HP::formatDateThaiFullPoint($boardAuditor->board_auditors_date->start_date)}}</span>   --}}
                    <span>
                        @php
                            // ฟังก์ชันจัดการวันที่ใน Blade
                            $start = new \DateTime($boardAuditor->board_auditors_date->start_date);
                            $end = new \DateTime($boardAuditor->board_auditors_date->end_date);

                            // ชื่อเดือนภาษาไทย
                            $thaiMonths = [
                                1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                                5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                                9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                            ];

                            // ปี พ.ศ.
                            $startYearBE = $start->format('Y') + 543;
                            $endYearBE = $end->format('Y') + 543;

                            // วันและเดือน
                            $startDay = (int)$start->format('j');
                            $endDay = (int)$end->format('j');
                            $startMonth = (int)$start->format('n');
                            $endMonth = (int)$end->format('n');

                            // ตรวจสอบเงื่อนไข
                            if ($startMonth === $endMonth && $startYearBE === $endYearBE) {
                                $formattedDate = "$startDay - $endDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE";
                            } else {
                                $formattedDate = "$startDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE - $endDay {$thaiMonths[$endMonth]} พ.ศ. $endYearBE";
                            }
                        @endphp

                        {{ $formattedDate }}
                    </span>

            </div>
            <div style="margin-top:10px;">
                <span style="font-weight: 600">2.4 บุคคลที่พบ :</span>
                <button onclick="openAddPersonModal()" class="btn btn-green " >เพิ่ม</button>
            </div>
            <div style="margin-left: 25px;margin-top:10px" id="person_wrapper">
                {{-- <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">1.</span>
                    <span style="flex: 1 0 150px;">นายจอร์น วิลเลียม</span>
                    <span style="flex: 1 0 50px;">ตำแหน่ง</span>
                    <span style="flex: 1 0 300px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div> --}}
            </div>
            <div style="margin-top:10px;">
                <span style="display:block; font-weight: 600">2.5 ผลการตรวจประเมิน</span> 
                <span style="display:block;margin-left:30px;">คณะผู้ตรวจประเมินตรวจประเมินความสามารถของห้องปฏิบัติการตาม มาตรฐานเลขที่ มอก. 17025-2561 ดังนี้</span>  
                <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(1) ข้อกำหนดทั่วไป</span> 
            </div>
        </div>

    {{-- </div> --}}

    {{-- <div  class="wrapper" style="line-height: 36px"> --}}
        <div style="margin-left: 80px;padding-top:10px">
            <label><input type="checkbox" id="2_5_1_structure_compliance" name="standard_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 4.1 – 4.2 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมีประสิทธิผล ดังนี้</label>
        </div>
        <div style="margin-left: 110px;margin-top:20px">
            <div style="display: flex; gap: 10px;margin-top:10px">
                <span style="flex: 1 0 5px;"><label><input type="radio" id="2_5_1_central_management_yes" name="2_5_1_central_management" >
                    <label for="2_5_1_central_management_yes">มี</label></span>
                <span style="flex: 1 0 10px;"><input type="radio" id="2_5_1_central_management_no" name="2_5_1_central_management" >
                    <label for="2_5_1_central_management_no">ไม่มี</label></span>
                <span style="flex: 1 0 450px;">การดำเนินการอย่างเป็นกลาง โดยมีโครงสร้างและการบริหารจัดการที่ก่อให้เกิดความเป็นกลาง</span>
            </div>
            <div style="display: flex; gap: 10px;margin-top:10px">
                <span style="flex: 1 0 5px;"><label><input type="radio" id="2_5_1_quality_policy_yes" name="2_5_1_quality_policy" >
                    <label for="2_5_1_quality_policy_yes">มี</label></span>
                <span style="flex: 1 0 10px;"><input type="radio" id="2_5_1_quality_policy_no" name="2_5_1_quality_policy" >
                    <label for="2_5_1_quality_policy_no">ไม่มี</label></span>
                <span style="flex: 1 0 450px;">ผู้บริหารมีนโยบายคุณภาพที่มุ่งเน้นเรื่องความเป็นกลาง</span>
            </div>
            <div style="display: flex; gap: 10px;margin-top:10px">
                <span style="flex: 1 0 5px;"><input type="radio" id="2_5_1_risk_assessment_yes" name="2_5_1_risk_assessment" >
                    <label for="2_5_1_risk_assessment_yes">มี</label></span>
                <span style="flex: 1 0 10px;"><input type="radio" id="2_5_1_risk_assessment_no" name="2_5_1_risk_assessment" >
                    <label for="2_5_1_risk_assessment_no">ไม่มี</label></span>
                <span style="flex: 1 0 450px;">การประเมินความเสี่ยงต่อความเป็นกลาง ครอบคลุมถึงความเสี่ยงที่เกิดขึ้นจากกิจกรรมของห้องปฏิบัติการ และความเสี่ยงเรื่องความสัมพันธ์ตามขอบเขตหน้าที่ความรับผิดชอบของบุคลากรแต่ละตำแหน่งและความสัมพันธ์ในระดับบุคคลสำหรับกิจกรรมต่าง ๆ ของห้องปฏิบัติการ</span>
            </div>
            <div style="margin-top: 10px;">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="checkbox" id="2_5_1_other" name="2_5_1_other" ></label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" id="2_5_1_text_other1" name="2_5_1_text_other1">
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                  
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" id="2_5_1_text_other2" name="2_5_1_text_other2">
                    </span>
                </div>
            </div>
        </div>


        <div style="margin-top: 10px;margin-left:80px">
            
            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                <span style="flex: 0 0 auto;">
                    <label><input type="checkbox" name="2_5_1_issue_found" id="2_5_1_issue_found" >พบว่า</label>
                </span>
                {{-- <span style="flex: 1 0 auto;">
                    <input type="text" class="input-no-border" placeholder="">
                </span> --}}
            </div>
            <button onclick="showModal('2_5_1')" class="btn btn-green" data-id="2_5_1">เพิ่มรายละเอียด</button>
            <div id="wrapper_2_5_1" style="width: 800px;" hidden>
            </div>

            <div id="data_container_2_5_1" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
            <div id="table_container_2_5_1"></div>

            <div style="margin-top:10px">
                <span>ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</span> 
            </div>
        </div>


        <div style="margin-left:20px">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(2) ข้อกำหนดด้านโครงสร้าง</span> 
            <div style="margin-left: 60px;padding-top:10px">
                <label><input type="checkbox" name="2_5_2_structure_compliance" id="2_5_2_structure_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 5.1 – 5.7 ของมาตรฐานเลขที่ มอก. 17025-2561 
                    ได้อย่างมีประสิทธิผล ดังนี้
                </label>
            </div>
            <div style="margin-top: 10px;margin-left:90px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span>
                        <label><input type="checkbox" id="2_5_2_lab_management" name="2_5_2_lab_management" >มี</label>
                    </span>
                    <span >
                        <input type="text" class="input-no-border" placeholder="" style="width: 300px;"  id="2_5_2_lab_management_details" name="2_5_2_lab_management_details" >
                    </span>
                    <span>
                        เป็นผู้บริหารของห้องปฏิบัติการ และ
                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label for="2_5_2_staff_assignment_yes"><input type="radio" id="2_5_2_staff_assignment_yes" name="2_5_2_staff_assignment" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label for="2_5_2_staff_assignment_no"><input type="radio" id="2_5_2_staff_assignment_no" name="2_5_2_staff_assignment" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การมอบหมายบุคลากรเพื่อปฏิบัติงานเฉพาะของห้องปฏิบัติการ</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label for="2_5_2_responsibility_yes"><input type="radio" id="2_5_2_responsibility_yes" name="2_5_2_responsibility" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label for="2_5_2_responsibility_no"><input type="radio" id="2_5_2_responsibility_no" name="2_5_2_responsibility" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การระบุความรับผิดชอบ อำนาจหน้าที่ ความสัมพันธ์ของบุคลากรในห้องปฏิบัติการ ใน Job Description ของแต่ละตำแหน่งงาน มีการสื่อสารถึงประสิทธิผลของการปฏิบัติงานผ่านการประชุมทบทวนการบริหารงาน การประชาสัมพันธ์อื่น ๆ</span>
                </div>
                <div style="margin-top: 10px;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox"  id="2_5_2_other" name="2_5_2_other"></label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_2_text_other1" name="2_5_2_text_other1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                      
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_2_text_other2" name="2_5_2_text_other2">
                        </span>
                    </div>
                </div>
                
            </div>
            <div style="margin-top: 10px;margin-left:60px">
            
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="checkbox"  name="2_5_2_issue_found" id="2_5_2_issue_found" >พบว่า</label>
                    </span>
                </div>
                <button onclick="showModal('2_5_2')" class="btn btn-green" data-id="2_5_2">เพิ่มรายละเอียด</button>
                <div id="wrapper_2_5_2" style="width: 800px;" hidden>
                </div>
    
                <div id="data_container_2_5_2" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table_container_2_5_2"></div>
    
                <div style="margin-top:10px">
                    <span>ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</span> 
                </div>
            </div>
        </div>


        <div style="margin-left:20px">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(3) ข้อกำหนดด้านทรัพยากร</span> 
            <div style="margin-left: 60px;padding-top:10px">
                <label><input type="checkbox" id="2_5_3_structure_compliance" name="2_5_3_structure_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 6.1 – 6.6 ของมาตรฐานเลขที่ มอก. 17025-2561 
                    ได้อย่างมีประสิทธิผล ดังนี้                    
                </label>
            </div>
            <div style="margin-top: 10px;margin-left:90px">
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_personnel_qualification" id="2_5_3_personnel_qualification_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_personnel_qualification" id="2_5_3_personnel_qualification_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การกำหนดคุณสมบัติ หน้าที่ความรับผิดชอบของบุคลากร เพื่อให้มั่นใจว่ามีความสามารถในการใช้เครื่องมือ ดำเนินการทดสอบ/สอบเทียบ ควบคุมงาน ประเมินผล ทบทวน และอนุมัติผลการทดสอบ/สอบเทียบ ได้อย่างถูกต้อง</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_assign_personnel_appropriately" id="2_5_3_assign_personnel_appropriately_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_assign_personnel_appropriately" id="2_5_3_assign_personnel_appropriately_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การมอบหมายบุคลากรเพื่อปฏิบัติงานเฉพาะต่าง ๆ ของห้องปฏิบัติการอย่างเหมาะสม รวมถึงบุคลากรที่ระบุการเป็นไปตามข้อกำหนด (ถ้ามี)</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_training_need_assessment" id="2_5_3_training_need_assessment_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_training_need_assessment" id="2_5_3_training_need_assessment_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การกำหนดความจำเป็นในการฝึกอบรมของแต่ละตำแหน่งงานใน Training Need และมีการจัดเก็บบันทึกการประเมินผลการฝึกอบรม</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_facility_and_environment_control" id="2_5_3_facility_and_environment_control_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_facility_and_environment_control" id="2_5_3_facility_and_environment_control_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การจัดการสิ่งอำนวยความสะดวกและควบคุมสภาวะแวดล้อมห้องปฏิบัติการ ได้อย่างเหมาะสม</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_equipment_maintenance_calibration" id="2_5_3_equipment_maintenance_calibration_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_equipment_maintenance_calibration" id="2_5_3_equipment_maintenance_calibration_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การควบคุมการใช้เครื่องมือ บำรุงรักษา สอบเทียบ และทวนสอบเครื่องมือที่มีผลกระทบต่อผลการทดสอบ/สอบเทียบ ตามขอบข่ายที่ขอรับการรับรอง และมีการระบุสถานะสอบเทียบเครื่องมือชัดเจน</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_metrology_traceability" id="2_5_3_metrology_traceability_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_metrology_traceability" id="2_5_3_metrology_traceability_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">ความสามารถสอบกลับได้ทางมาตรวิทยาได้อย่างเหมาะสม</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_3_external_product_service_control" id="2_5_3_external_product_service_control_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_3_external_product_service_control" id="2_5_3_external_product_service_control_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การควบคุมผลิตภัณฑ์และบริการจากภายนอกได้อย่างเหมาะสมและมีประสิทธิภาพ (ถ้ามี)</span>
                </div>
                <div style="margin-top: 10px;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox"  id="2_5_3_other" name="2_5_3_other"></label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_3_text_other1" name="2_5_3_text_other1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                      
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_3_text_other2" name="2_5_3_text_other2">
                        </span>
                    </div>
                </div>
                
            </div>
            <div style="margin-top: 10px;margin-left:60px">
            
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="checkbox"  name="2_5_3_issue_found" id="2_5_3_issue_found" >พบว่า</label>
                    </span>
                    {{-- <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="">
                    </span> --}}
                </div>
                <button onclick="showModal('2_5_3')" class="btn btn-green" data-id="2_5_3">เพิ่มรายละเอียด</button>
                <div id="wrapper_2_5_3" style="width: 800px;" hidden>
                </div>
    
                <div id="data_container_2_5_3" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table_container_2_5_3"></div>
    
                <div style="margin-top:10px">
                    <span>ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</span> 
                </div>
            </div>
        </div>
        
        <div style="margin-left:20px">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(4) ข้อกำหนดด้านกระบวนการ</span> 
            <div style="margin-left: 60px;padding-top:10px">
                <label><input type="checkbox" id="2_5_4_structure_compliance" name="2_5_4_structure_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 7.1 – 7.11 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมีประสิทธิผล ดังนี้                    
                </label>
            </div>
            <div style="margin-top: 10px;margin-left:90px">
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_policy_compliance" id="2_5_4_policy_compliance_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_policy_compliance" id="2_5_4_policy_compliance_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">นโยบายระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule)</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_metrology_sampling_activity" id="2_5_4_metrology_sampling_activity_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_metrology_sampling_activity" id="2_5_4_metrology_sampling_activity_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">กิจกรรมการชักตัวอย่าง</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_procedure_review_request" id="2_5_4_procedure_review_request_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_procedure_review_request" id="2_5_4_procedure_review_request_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">ขั้นตอนการดำเนินงานทบทวนคำขอ และสัญญาของห้องปฏิบัติการ สามารถตอบสนองความต้องการของลูกค้า และเพื่อให้เกิดความเข้าใจตรงกัน ในเรื่องของข้อกำหนดต่าง ๆ ขีดความสามารถและทรัพยากรที่เพียงพอต่อการปฏิบัติงาน และวิธีทดสอบ/สอบเทียบ ที่เหมาะสม รวมถึงเมื่อมีการเปลี่ยนแปลง หรือเบี่ยงเบนจากที่ลูกค้าร้องขอ จะดำเนินการแจ้งลูกค้าทราบทันที</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_decision_rule" id="2_5_4_decision_rule_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_decision_rule" id="2_5_4_decision_rule_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">กรณีลูกค้าขอให้ระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule)
                        <div style="display: flex; gap: 10px;margin-top:10px">
                            <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_agreement_customer" id="2_5_4_agreement_customer_yes" >มี</label></span>
                            <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_agreement_customer" id="2_5_4_agreement_customer_no" >ไม่มี</label></span>
                            <span style="flex: 1 0 300px;">การตกลงและแจ้งกับลูกค้าอย่างชัดเจน</span>
                        </div>
                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_method_verification" id="2_5_4_method_verification_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_method_verification" id="2_5_4_method_verification_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">ขั้นตอนการดำเนินงานสำหรับการเลือก การทวนสอบและการตรวจสอบความใช้ได้ของวิธีที่เหมาะสมและเป็นไปตามข้อกำหนด</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_sample_management" id="2_5_4_sample_management_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_sample_management" id="2_5_4_sample_management_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">ขั้นตอนการดำเนินงานการจัดการตัวอย่าง เพื่อควบคุมการขนส่ง รับ จัดการ ป้องกัน เก็บรักษา ส่งคืนตัวอย่าง รวมทั้งมีระบบการชี้บ่งตัวอย่าง รักษาความสมบูรณ์ของตัวอย่างตลอดช่วงเวลาที่อยู่ในการทดสอบ/สอบเทียบ มีการบันทึกลักษณะของตัวอย่างก่อนการทดสอบ/สอบเทียบ</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_record_management" id="2_5_4_record_management_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_record_management" id="2_5_4_record_management_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การจัดการบันทึกทางด้านวิชาการได้อย่างเหมาะสมและเป็นไปตามข้อกำหนด</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_uncertainty_evaluation" id="2_5_4_uncertainty_evaluation_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_uncertainty_evaluation" id="2_5_4_uncertainty_evaluation_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การประเมินค่าความไม่แน่นอนของการวัด โดยชี้บ่งปัจจัยที่มีผลต่อค่าความไม่แน่นอนของการวัด</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_result_surveillance" id="2_5_4_result_surveillance_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_result_surveillance" id="2_5_4_result_surveillance_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">ขั้นตอนการดำเนินงานในการเฝ้าระวังความใช้ได้ของผล ได้อย่างเหมาะสม</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_proficiency_testing" id="2_5_4_proficiency_testing_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_proficiency_testing" id="2_5_4_proficiency_testing_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;font-weight:bold">การเข้าร่วมการทดสอบความชำนาญ/ การเปรียบเทียบระหว่างห้องปฏิบัติการ</span>
                </div>
                <div style="margin-top: 10px;margin-left:50px">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox" name="2_5_4_test_participation" id="2_5_4_test_participation" >เข้าร่วมการทดสอบความชำนาญซึ่งจัดโดย</label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_test_participation_details1" id="2_5_4_test_participation_details1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_test_participation_details2" id="2_5_4_test_participation_details2">
                        </span>
                    </div>
                    <div style="margin-left:30px;margin-top:15px">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                            <span style="flex: 0 0 auto;">
                                <label><input type="checkbox" name="2_5_4_test_calibration" id="2_5_4_test_calibration" >สำหรับการทดสอบ/สอบเทียบ</label>
                            </span>
                            <span style="flex: 1 0 auto;">
                                <input type="text" class="input-no-border" placeholder="" name="2_5_4_calibration_details" id="2_5_4_calibration_details">
                            </span>
                        </div>
                        <div>

                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                                <span>ผล</span>
                                <span style="padding-left:10px">
                                    <label><input type="radio" name="2_5_4_acceptance_criteria" id="2_5_4_acceptance_criteria_yes" ></label>
                                </span>
                                <span>
                                    อยู่ในเกณฑ์การยอมรับ
                                </span>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;margin-left:44px">
                                <span style="flex: 0 0 auto;">
                                    <label><input type="radio" name="2_5_4_acceptance_criteria" id="2_5_4_acceptance_criteria_no" >ไม่อยู่ในเกณฑ์ยอมรับ</label>
                                </span>
                                <span style="flex: 1 0 auto;">
                                    <input type="text" class="input-no-border" placeholder="" name="2_5_4_acceptance_criteria1" id="2_5_4_acceptance_criteria1">
                                </span>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                                <span style="flex: 1 0 auto;">
                                    <input type="text" class="input-no-border" placeholder="" name="2_5_4_acceptance_criteria2" id="2_5_4_acceptance_criteria2">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px;margin-left:50px">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox" name="2_5_4_lab_comparison" id="2_5_4_lab_comparison" >มีการเปรียบเทียบผลกับห้องปฏิบัติการ</label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_lab_comparison_details1" id="2_5_4_lab_comparison_details1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_lab_comparison_details2" id="2_5_4_lab_comparison_details2">
                        </span>
                    </div>
                    <div style="margin-left:30px;margin-top:15px">
                        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                            <span style="flex: 0 0 auto;">
                                <label><input type="checkbox" name="2_5_4_lab_comparison_test" id="2_5_4_lab_comparison_test">สำหรับการทดสอบ/สอบเทียบ</label>
                            </span>
                            <span style="flex: 1 0 auto;">
                                <input type="text" class="input-no-border" placeholder="" name="2_5_4_lab_comparison_test_details" id="2_5_4_lab_comparison_test_details">
                            </span>
                        </div>
                        <div>
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                                <span>ผล</span>
                                <span style="padding-left:10px">
                                    <label><input type="radio" name="2_5_4_lab_comparison_test_is_accept" id="2_5_4_lab_comparison_test_is_accept_yes" ></label>
                                </span>
                                <span>
                                    อยู่ในเกณฑ์การยอมรับ
                                </span>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;margin-left:44px">
                                <span style="flex: 0 0 auto;">
                                    <label><input type="radio" name="2_5_4_lab_comparison_test_is_accept" id="2_5_4_lab_comparison_test_is_accept_no" >ไม่อยู่ในเกณฑ์ยอมรับ</label>
                                </span>
                                <span style="flex: 1 0 auto;">
                                    <input type="text" class="input-no-border" placeholder="" name="2_5_4_lab_comparison_test_is_accept_details1" id="2_5_4_lab_comparison_test_is_accept_details1">
                                </span>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                                <span style="flex: 1 0 auto;">
                                    <input type="text" class="input-no-border" placeholder="" name="2_5_4_lab_comparison_test_is_accept_details2" id="2_5_4_lab_comparison_test_is_accept_details2">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 10px;margin-left:50px">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span>
                            <label><input type="checkbox" name="2_5_4_test_participation2" id="2_5_4_test_participation2" >เข้าร่วมการทดสอบความชำนาญ /มีการเปรียบเทียบผลระหว่างห้องปฏิบัติการ 
                                ดังแนบ (กรณีเปรียบเทียบจำนวนมาก)
                                </label>
                        </span>
                    </div>
                </div>
                <div style="margin-top: 10px;margin-left:50px">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox" name="2_5_4_other_methods" id="2_5_4_other_methods" >วิธีการอื่น ๆ</label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_other_methods_details1" id="2_5_4_other_methods_details1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" name="2_5_4_other_methods_details2" id="2_5_4_other_methods_details2">
                        </span>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_report_approval_review" id="2_5_4_report_approval_review_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_report_approval_review" id="2_5_4_report_approval_review_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การทบทวนและอนุมัติรายงานผลการทดสอบ/สอบเทียบของห้องปฏิบัติการก่อนส่งรายงาน รายงานผลจัดทำได้ถูกต้อง ไม่คลุมเครือ และตรงตามวัตถุประสงค์ตามข้อกำหนด โดยห้องปฏิบัติการจะรับผิดชอบข้อมูลต่าง ๆ ที่อยู่ในรายงาน ยกเว้นข้อมูลที่จัดเตรียมโดยลูกค้า และชี้บ่งชัดเจนว่าเป็นข้อมูลจากลูกค้า</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_decision_rule2" id="2_5_4_decision_rule2_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_decision_rule2" id="2_5_4_decision_rule2_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">กรณีระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule) 
                        <div style="display: flex; gap: 10px;margin-top:10px">
                            <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_document_for_criteria" id="2_5_4_document_for_criteria_yes" >มี</label></span>
                            <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_document_for_criteria" id="2_5_4_document_for_criteria_no" >ไม่มี</label></span>
                            <span style="flex: 1 0 300px;">มีเอกสารเกี่ยวกับเกณฑ์ตัดสินที่ใช้อย่างเหมาะสม  และรายงานมีการระบุการเป็นไปตามข้อกำหนดอย่างชัดเจนและเป็นไปตามข้อกำหนด</span>
                        </div>
                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_complaint_process" id="2_5_4_complaint_process_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_complaint_process" id="2_5_4_complaint_process_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px">กระบวนการจัดการกับข้อร้องเรียนที่เหมาะสม ปีที่ผ่านมามีข้อร้องเรียนจำนวน<span >
                            <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="2_5_4_complaint_number" id="2_5_4_complaint_number">
                        </span>รายการ และมีการดำเนินการเรียบร้อยแล้ว

                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_non_conformance_process" id="2_5_4_non_conformance_process_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_non_conformance_process" id="2_5_4_non_conformance_process_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px">ขั้นตอนการดำเนินงานสำหรับงานที่ไม่เป็นไปตามข้อกำหนด ได้อย่างเหมาะสม ปีที่ผ่านมามีงานที่ไม่เป็นไปตามข้อกำหนด 
                        จำนวน<span >
                            <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="2_5_4_non_conformance_number" id="2_5_4_non_conformance_number">
                        </span>รายการ และมีการดำเนินการเรียบร้อยแล้ว

                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_data_control" id="2_5_4_data_control_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_data_control" id="2_5_4_data_control_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การควบคุมข้อมูลและการจัดการระบบสารสนเทศที่จำเป็นต่อการปฏิบัติกิจกรรมต่าง ๆ ของห้องปฏิบัติการได้อย่างเหมาะสม</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_data_transfer_control" id="2_5_4_data_transfer_control_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_data_transfer_control" id="2_5_4_data_transfer_control_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px">การคำนวณและการถ่ายโอนข้อมูลได้รับการตรวจสอบอย่างเหมาะสมและเป็นระบบ</span>
                </div>
                <div style="margin-top: 10px;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox" id="2_5_4_other" name="2_5_4_other" ></label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_4_text_other1" name="2_5_4_text_other1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px">
                      
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_4_text_other2" name="2_5_4_text_other2">
                        </span>
                    </div>
                </div>
     
            </div>
            <div style="margin-left:60px">
            
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="checkbox" name="2_5_4_issue_found" id="2_5_4_issue_found" >พบว่า</label>
                    </span>
                    {{-- <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="">
                    </span> --}}
                </div>
                <button onclick="showModal('2_5_4')" class="btn btn-green" data-id="2_5_4">เพิ่มรายละเอียด</button>
                <div id="wrapper_2_5_4" style="width: 800px;" hidden>
                </div>
    
                <div id="data_container_2_5_4" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table_container_2_5_4"></div>
    
                <div style="margin-top:10px">
                    <span>ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</span> 
                </div>
            </div>
        </div>
        
        <div style="margin-left: 20px">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(5) ข้อกำหนดระบบการบริหารงาน</span> 
            <div style="margin-left: 60px;padding-top:10px">
                <label><input type="checkbox" id="2_5_5_structure_compliance" name="2_5_5_structure_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 8.1 – 8.9 ของมาตรฐานเลขที่ มอก. 17025-2561 
                    ได้อย่างมีประสิทธิผล ดังนี้                                        
                </label>
            </div>
            <div style="margin-top: 10px;margin-left:90px">
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="checkbox" name="2_5_5_data_control_option_a" id="2_5_5_data_control_option_a" >มี</label></span>
                    <span style="flex: 1 0 450px;">มีระบบการบริหารงานตามทางเลือก A</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="checkbox" name="2_5_5_data_control_option_b" id="2_5_5_data_control_option_b" >มี</label></span>
                    <span style="flex: 1 0 450px;" >มีระบบการบริหารงานตามทางเลือก B</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_data_control_policy" id="2_5_5_data_control_policy_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_data_control_policy" id="2_5_5_data_control_policy_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การกำหนดนโยบายเกี่ยวกับระบบบริหารงานคุณภาพไว้เป็นเอกสาร ครอบคลุมถึงความสามารถ ความเป็นกลาง และการปฏิบัติงานอย่างสม่ำเสมอ และสามารถนำไปปฏิบัติได้อย่างมีประสิทธิผล</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_document_control" id="2_5_5_document_control_yes"  >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_document_control" id="2_5_5_document_control_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การควบคุมเอกสารระบบการบริหารงานทั้งภายในและภายนอกอย่างเหมาะสม และเป็นไปตามข้อกำหนด</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_record_keeping" id="2_5_5_record_keeping_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_record_keeping" id="2_5_5_record_keeping_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การจัดทำและจัดเก็บบันทึกต่าง ๆ ที่ชัดเจน เหมาะสม และเป็นไปตามข้อกำหนด</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_risk_management" id="2_5_5_risk_management_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_risk_management" id="2_5_5_risk_management_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การพิจารณาจัดการความเสี่ยงและโอกาสที่เกี่ยวข้องกับกิจกรรมของห้องปฏิบัติการ ซึ่งเป็นสัดส่วนกับผลกระทบที่อาจเกิดขึ้น โดยมีการวางแผนการปฏิบัติการและมีวิธีการเพื่อบูรณาการและนำปฏิบัติการไปใช้ในระบบการบริหารงานและประเมินประสิทธิผลของการปฏิบัติการ</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_risk_opportunity" id="2_5_5_risk_opportunity_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_risk_opportunity" id="2_5_5_risk_opportunity_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การปฏิบัติการเพื่อจัดการความเสี่ยงและโอกาส</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_improvement_opportunity" id="2_5_5_improvement_opportunity_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_improvement_opportunity" id="2_5_5_improvement_opportunity_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">การระบุและเลือกโอกาสในการปรับปรุงและนำไปใช้ปฏิบัติ  รวมถึงมีการแสวงหา feedback ทั้งทางบวกและทางลบจากลูกค้า และนำไปวิเคราะห์และใช้ในการปรับปรุงระบบการบริหารงาน กิจกรรมต่าง ๆ ของห้องปฏิบัติการ และการบริการลูกค้า</span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_non_conformance" id="2_5_5_non_conformance_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_non_conformance" id="2_5_5_non_conformance_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px;">กรณีงานที่ไม่เป็นไปตามข้อกำหนด มีการตอบสนองและดำเนินการเพื่อปฏิบัติการควบคุม แก้ไข และ/หรือทบทวนประสิทธิผลของการแก้ไข ปรับความเสี่ยงและโอกาสให้เป็นปัจจุบัน รวมถึงการเปลี่ยนแปลงระบบการบริหารงานเมื่อมีผลกระทบ </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_internal_audit" id="2_5_5_internal_audit_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_internal_audit" id="2_5_5_internal_audit_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px">การกำหนดแผนการตรวจติดตามคุณภาพภายใน ปีละ<input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="2_5_5_audit_frequency" id="2_5_5_audit_frequency">ครั้ง ดำเนินการตรวจติดตามคุณภาพภายในครั้งล่าสุด เมื่อวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 180px;text-align:center" name="2_5_5_last_audit_date" id="2_5_5_last_audit_date" >  พบข้อบกพร่อง จำนวน<input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="2_5_5_audit_issues" id="2_5_5_audit_issues">รายการ 
                        โดยมีการวางแผนและตรวจติดตามคุณภาพเป็นไปตามที่กำหนด ครอบคลุมทุกกิจกรรม
                        <span >
                    </span>
                </div>
                <div style="display: flex; gap: 10px;margin-top:10px">
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_management_review" id="2_5_5_management_review_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_management_review" id="2_5_5_management_review_no" >ไม่มี</label></span>
                    <span style="flex: 1 0 450px">การกำหนดแผนการทบทวนการบริหารงานอย่างน้อยปีละครั้ง ดำเนินการประชุมทบทวนการบริหารครั้งล่าสุดเมื่อวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 180px;text-align:center" name="2_5_5_last_review_date" id="2_5_5_last_review_date" > โดยมีการทบทวนการบริหารให้เป็นไปตามที่กำหนดครอบคลุมทุกกิจกรรม
                        <span >
                    </span>
                </div>
                <div style="margin-top: 10px;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                        <span style="flex: 0 0 auto;">
                            <label><input type="checkbox" id="2_5_5_other" name="2_5_5_other" ></label>
                        </span>
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_5_text_other1" name="2_5_5_text_other1">
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;margin-left:40px" >
                      
                        <span style="flex: 1 0 auto;">
                            <input type="text" class="input-no-border" placeholder="" id="2_5_5_text_other2" name="2_5_5_text_other2" >
                        </span>
                    </div>
                </div>
                
            </div>
            <div style="margin-top: 10px;margin-left:60px">
            
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="checkbox" type="checkbox" name="2_5_5_issue_found" id="2_5_5_issue_found" >พบว่า</label>
                    </span>
                    {{-- <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="">
                    </span> --}}
                </div>
                <button onclick="showModal('2_5_5')" class="btn btn-green" data-id="2_5_5">เพิ่มรายละเอียด</button>
                <div id="wrapper_2_5_5" style="width: 800px;" hidden>
                </div>
    
                <div id="data_container_2_5_5" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table_container_2_5_5"></div>
    
                <div style="margin-top:10px">
                    <span>ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</span> 
                </div>
            </div>
        </div>

        <div style="margin-left: 20px">
            <span style="display:block; font-weight: 600;margin-left:30px;margin-top:15px">(6) กรณีคำขอต่ออายุใบรับรอง</span> 
            <div style="margin-left: 60px;padding-top:10px">
                <span style="font-weight: bold">6.1 การเฝ้าระวังการฝ่าฝืนหลักเกณฑ์ วิธีการและเงื่อนไขการรับรองห้องปฏิบัติการ ตาม</span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span>(1) กฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 </span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span>(2) หลักเกณฑ์ วิธีการและเงื่อนไขการโฆษณาของผู้ประกอบการตรวจสอบและรับรองและผู้ประกอบกิจการ </span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span>(3) เอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง  สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) </span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.1.1 การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_management_review" id="2_5_6_1_1_management_review_no" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_management_review" id="2_5_6_1_1_management_review_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_scope_certified" id="2_5_6_1_1_scope_certified_no" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_scope_certified" id="2_5_6_1_1_scope_certified_yes" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_activities_not_certified" id="2_5_6_1_1_activities_not_certified_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_activities_not_certified" id="2_5_6_1_1_activities_not_certified_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_accuracy" id="2_5_6_1_1_accuracy_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_1_accuracy" id="2_5_6_1_1_accuracy_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_1_1_accuracy_detail" id="2_5_6_1_1_accuracy_detail">
                    </span>
                </div>
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.1.2 กรณีได้รับการรับรองห้องปฏิบัติการหลายสถานที่ (Multi-site)</span> 
                <span >การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_display" id="2_5_6_1_2_multi_site_display_no" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_display" id="2_5_6_1_2_multi_site_display_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_scope" id="2_5_6_1_2_multi_site_scope_no" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_scope" id="2_5_6_1_2_multi_site_scope_yes" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_activities_not_certified" id="2_5_6_1_2_multi_site_activities_not_certified_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_activities_not_certified" id="2_5_6_1_2_multi_site_activities_not_certified_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_accuracy" id="2_5_6_1_2_multi_site_accuracy_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_2_multi_site_accuracy" id="2_5_6_1_2_multi_site_accuracy_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_1_2_multi_site_accuracy_details" id="2_5_6_1_2_multi_site_accuracy_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.1.3 กรณีห้องปฏิบัติการสอบเทียบ  ป้ายแสดงสถานะการสอบเทียบ </span> 
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_3_certification_status" id="2_5_6_1_3_certification_status_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_3_certification_status" id="2_5_6_1_3_certification_status_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_1_3_certification_status_details" id="2_5_6_1_3_certification_status_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.1.4 การแสดงการได้รับการรับรองที่อื่น นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_4_display_other" id="2_5_6_1_4_display_other_no" >ไม่มี</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_4_display_other" id="2_5_6_1_4_display_other_yes" >มี ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_1_4_display_other_details" id="2_5_6_1_4_display_other_details" >
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_4_certification_status" id="2_5_6_1_4_certification_status_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_1_4_certification_status" id="2_5_6_1_4_certification_status_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_1_4_certification_status_details" id="2_5_6_1_4_certification_status_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 60px;padding-top:10px">
                <span style="font-weight: bold">6.2 การปฏิบัติตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่างประเทศว่าด้วยการรับรองห้องปฏิบัติการ (ILAC) และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</span> 
            </div>
            <div style="display: flex; gap: 10px;margin-top:10px;margin-left:70px ">
                <span>ห้องปฏิบัติการ</span>
                <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_6_2_lab_availability" id="2_5_6_2_lab_availability_yes" >มี</label></span>
                <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_6_2_lab_availability" id="2_5_6_2_lab_availability_no" >ไม่มี</label></span>
                <span style="flex: 1 0 300px;"><label></span>
            </div>
            <div style="margin-left: 70px;padding-top:10px">
                <span>การลงนามในข้อตกลงการใช้เครื่องหมาย ILAC MRA ร่วมกับเครื่องหมายมาตรฐานทั่วไปสำหรับผู้รับใบรับรอง ร่วมกับสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</span> 
            </div>
            <div style="margin-left: 70px;padding-top:10px">
                <span style="font-weight: bold"> <u>กรณีห้องปฏิบัติการและสำนักงานมีข้อตกลงร่วมกัน</u> </span> 
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.2.1 การแสดงเครื่องหมายร่วม ILAC MRA ในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_display" id="2_5_6_2_1_ilac_mra_display_no" >ไม่มีการแสดง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_display" id="2_5_6_2_1_ilac_mra_display_yes" >มีการแสดง ดังนี้</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_scope" id="2_5_6_2_1_ilac_mra_scope_no" >เฉพาะขอบข่ายที่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_scope" id="2_5_6_2_1_ilac_mra_scope_yes" >ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top: 10px;margin-left:200px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_disclosure" id="2_5_6_2_1_ilac_mra_disclosure_yes" >มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_disclosure" id="2_5_6_2_1_ilac_mra_disclosure_no" >ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</label>
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น </span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_ilac_mra_compliance" id="2_5_6_2_1_ilac_mra_compliance_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_1_ilac_mra_compliance" id="2_5_6_2_1_ilac_mra_compliance_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_2_1_ilac_mra_compliance_details" id="2_5_6_2_1_ilac_mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 90px;padding-top:10px">
                <span style="font-weight: bold">6.2.2 การแสดงเครื่องหมายร่วม ILAC MRA นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</span> 
            </div>
            <div style="margin-top: 10px;margin-left:135px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_2_ilac_mra_compliance" id="2_5_6_2_2_ilac_mra_compliance_no" >ไม่มี</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_2_ilac_mra_compliance" id="2_5_6_2_2_ilac_mra_compliance_yes" >มี ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_2_2_ilac_mra_compliance_details" id="2_5_6_2_2_ilac_mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-left: 135px;padding-top:10px">
                <span>แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น </span> 
            </div>
            <div style="margin-top: 10px;margin-left:165px">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_2_mra_compliance" id="2_5_6_2_2_mra_compliance_yes" >ถูกต้อง</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                    <span style="flex: 0 0 auto;">
                        <label><input type="radio" name="2_5_6_2_2_mra_compliance" id="2_5_6_2_2_mra_compliance_no" >ไม่ถูกต้อง ระบุ</label>
                    </span>
                    <span style="flex: 1 0 auto;">
                        <input type="text" class="input-no-border" placeholder="" name="2_5_6_2_2_mra_compliance_details" id="2_5_6_2_2_mra_compliance_details">
                    </span>
                </div>
            </div>
            <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
                <span>3. สรุปผลการตรวจประเมิน</span>
            </div>
            <div style="margin-left:30px; line-height:36px">
                <table>
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="3_0_assessment_results" id="3_0_assessment_results"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                พบข้อบกพร่อง จำนวน<input type="text" class="input-no-border" placeholder="" style="width: 100px;text-align:center" name="3_0_issue_count" id="3_0_issue_count">รายการ และข้อสังเกต จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 100px;text-align:center" name="3_0_remarks_count" id="3_0_remarks_count">  <input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="3_0_deficiencies_details" id="3_0_deficiencies_details" hidden> ดังสำเนารายงานข้อบกพร่องที่แนบ 
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="margin-left: 30px">
                    <span>ห้องปฏิบัติการต้องส่งแนวทาง และแผนการดำเนินการปฏิบัติการแก้ไขข้อบกพร่อง ให้สำนักงานพิจารณาภายใน 30 วันนับแต่วันที่ออกรายงานข้อบกพร่อง และต้องส่งหลักฐานการแก้ไขข้อบกพร่องอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน ภายในวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 180px;text-align:center" name="3_0_deficiency_resolution_date" id="3_0_deficiency_resolution_date"> (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่อง) 
                        หากพ้นกำหนดระยะเวลาดังกล่าว ห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้สำนักงานพิจารณายกเลิกคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการของท่านต่อไป
                        กรณีห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 ภายในระยะเวลาที่กำหนด คณะผู้ตรวจประเมินจะนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} เพื่อพิจารณาให้การรับรองต่อไป
                    </span>
                </div>
            </div>
            <div style="margin-left:30px; line-height:36px">
                <table>
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="3_0_offer_agreement" id="3_0_offer_agreement"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                ห้องปฏิบัติการมีระบบการบริหารงานและการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก. 17025-2561 ในขอบข่ายที่ขอรับการรับรอง คณะผู้ตรวจประเมินเห็นควรนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}}. เพื่อพิจารณาให้การรับรองต่อไป 
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            @if ($approveNoticeItems->count() != 0)
                <div style="margin-left:70px; margin-bottom:50px; line-height:36px" id="files_wrapper">
                    <p><u>รายการไฟล์แนบ</u> </p>
                    @foreach($approveNoticeItems  as $key => $item)
                        <p >{{$key+1}})
                            <a style="text-decoration:none" href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) ))}}"
                                title="{{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) }}" target="_blank">
                                {!! HP::FileExtension($item->attachs)  ?? '' !!} {{basename($item->attachs_client_name)}}
                            </a>
                        </p>
                    @endforeach
                    <hr>
                </div>
            @endif
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
                       <input type="text" class="input-no-border" style="text-align: center" id="position-3" value="ผู้อำนวยการสำนักงานคณะกรรมการมาตรฐานแห่งชาติ" />
                   </div>
                </div>
            </div>


            
            <div style="text-align: center;margin-bottom:20px;margin-top:20px" id="button_wrapper">
                {{-- {{$notice->report_status}}
                {{$notice->degree}} --}}

                <button  type="button" id="btn_draft_submit" class="btn btn-red" >ฉบับร่าง</button>
                {{-- @if ($notice->report_status == 2 || ($notice->report_status == 1 && $notice->degree == 4)) --}}
                <button  type="button" id="btn_submit" class="btn btn-green" >ส่งข้อมูล</button>
                {{-- @endif --}}
                
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
        let notice;
        let assessment;
        let boardAuditor;
        let certi_lab;
        let labRequest;
        let labReportInfo;
        let data;
        let signAssessmentReportTransactions;
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
            labReportInfo = @json($labReportInfo ?? []);
            notice = @json($notice ?? []);
            assessment = @json($assessment ?? []);
            boardAuditor = @json($boardAuditor ?? []);
            certi_lab = @json($app_certi_lab ?? []);
            labRequest = @json($labRequest ?? []);
            signAssessmentReportTransactions = @json($signAssessmentReportTransactions ?? []);
            defaultSignerIds = @json($defaultSignerIds ?? []);

            // console.log('boardAuditor',boardAuditor);

        //    if(labReportInfo.status !=="1"){
        //          $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
        //          $('.wrapper').css('pointer-events', 'none'); // ปิดการคลิกและโต้ตอบทุกอย่างใน div.wrapper
        //          $('.wrapper').css('opacity', '0.7'); // เพิ่มความโปร่งใสเพื่อแสดงว่าถูกปิดใช้งาน (ไม่บังคับ)
        //    }

        if (labReportInfo.status !== "1") {
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
                console.log('index',index,transaction.signer_id)
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

            console.log('signer',signer)

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

            data = [
                {
                    "id": "2_2",
                    "2_2_assessment_on_site": labReportInfo.inp_2_2_assessment_on_site === "1",
                    "2_2_assessment_at_tisi": labReportInfo.inp_2_2_assessment_at_tisi === "1",
                    "2_2_remote_assessment": labReportInfo.inp_2_2_remote_assessment === "1",
                    "2_2_self_declaration": labReportInfo.inp_2_2_self_declaration === "1"
                },
                {
                    "id": "2_5_1",
                    "2_5_1_structure_compliance": {
                        "value": labReportInfo.inp_2_5_1_structure_compliance === "1",
                        "2_5_1_central_management_yes": labReportInfo.inp_2_5_1_central_management_yes === "1",
                        "2_5_1_central_management_no": labReportInfo.inp_2_5_1_central_management_no === "1",
                        "2_5_1_quality_policy_yes": labReportInfo.inp_2_5_1_quality_policy_yes === "1",
                        "2_5_1_quality_policy_no": labReportInfo.inp_2_5_1_quality_policy_no === "1",
                        "2_5_1_risk_assessment_yes": labReportInfo.inp_2_5_1_risk_assessment_yes === "1",
                        "2_5_1_risk_assessment_no": labReportInfo.inp_2_5_1_risk_assessment_no === "1",
                        "2_5_1_other": {
                            "value": labReportInfo.inp_2_5_1_other === "1",
                            "2_5_1_text_other1": labReportInfo.inp_2_5_1_text_other1,
                            "2_5_1_text_other2": labReportInfo.inp_2_5_1_text_other2
                        }
                    },
                    "2_5_1_issue_found": {
                        "value": labReportInfo.inp_2_5_1_issue_found === "1",
                        "2_5_1_detail": []
                    }
                },
                {
                    "id": "2_5_2",
                    "2_5_2_structure_compliance": {
                        "value": labReportInfo.inp_2_5_2_structure_compliance === "1",
                        "2_5_2_lab_management": {
                            "value": labReportInfo.inp_2_5_2_lab_management === "1",
                            "2_5_2_lab_management_details": labReportInfo.inp_2_5_2_lab_management_details
                        },
                        "2_5_2_staff_assignment_yes": labReportInfo.inp_2_5_2_staff_assignment_yes === "1",
                        "2_5_2_staff_assignment_no": labReportInfo.inp_2_5_2_staff_assignment_no === "1",
                        "2_5_2_responsibility_yes": labReportInfo.inp_2_5_2_responsibility_yes === "1",
                        "2_5_2_responsibility_no": labReportInfo.inp_2_5_2_responsibility_no === "1",
                        "2_5_2_other": {
                            "value": labReportInfo.inp_2_5_2_other === "1",
                            "2_5_2_text_other1": labReportInfo.inp_2_5_2_text_other1,
                            "2_5_2_text_other2": labReportInfo.inp_2_5_2_text_other1
                        }
                    },
                    "2_5_2_issue_found": {
                        "value": labReportInfo.inp_2_5_2_issue_found === "1",
                        "2_5_2_detail": []
                    }
                },
                {
                    "id": "2_5_3",
                    "2_5_3_structure_compliance": {
                        "value": labReportInfo.inp_2_5_3_structure_compliance === "1",
                        "2_5_3_personnel_qualification_yes": labReportInfo.inp_2_5_3_personnel_qualification_yes === "1",
                        "2_5_3_personnel_qualification_no": labReportInfo.inp_2_5_3_personnel_qualification_no === "1",
                        "2_5_3_assign_personnel_appropriately_yes": labReportInfo.inp_2_5_3_assign_personnel_appropriately_yes === "1",
                        "2_5_3_assign_personnel_appropriately_no": labReportInfo.inp_2_5_3_structure_compliance === "1",
                        "2_5_3_training_need_assessment_yes": labReportInfo.inp_2_5_3_assign_personnel_appropriately_no === "1",
                        "2_5_3_training_need_assessment_no": labReportInfo.inp_2_5_3_training_need_assessment_no === "1",
                        "2_5_3_facility_and_environment_control_yes": labReportInfo.inp_2_5_3_facility_and_environment_control_yes === "1",
                        "2_5_3_facility_and_environment_control_no": labReportInfo.inp_2_5_3_facility_and_environment_control_no === "1",
                        "2_5_3_equipment_maintenance_calibration_yes": labReportInfo.inp_2_5_3_equipment_maintenance_calibration_yes === "1",
                        "2_5_3_equipment_maintenance_calibration_no": labReportInfo.inp_2_5_3_equipment_maintenance_calibration_no === "1",
                        "2_5_3_metrology_traceability_yes": labReportInfo.inp_2_5_3_metrology_traceability_yes === "1",
                        "2_5_3_metrology_traceability_no": labReportInfo.inp_2_5_3_metrology_traceability_no === "1",
                        "2_5_3_external_product_service_control_yes": labReportInfo.inp_2_5_3_external_product_service_control_yes === "1",
                        "2_5_3_external_product_service_control_no": labReportInfo.inp_2_5_3_external_product_service_control_no === "1",
                        "2_5_3_other": {
                            "value": labReportInfo.inp_2_5_3_other === "1",
                            "2_5_3_text_other1": labReportInfo.inp_2_5_3_text_other1,
                            "2_5_3_text_other2": labReportInfo.inp_2_5_3_text_other2
                        }
                    },
                    "2_5_3_issue_found": {
                        "value": labReportInfo.inp_2_5_3_issue_found === "1",
                        "2_5_3_detail": []
                    }
                },
                {
                    "id": "2_5_4",
                    "2_5_4_structure_compliance": {
                        "value": labReportInfo.inp_2_5_4_structure_compliance === "1",
                        "2_5_4_policy_compliance_yes": labReportInfo.inp_2_5_4_policy_compliance_yes === "1",
                        "2_5_4_policy_compliance_no": labReportInfo.inp_2_5_4_policy_compliance_no === "1",
                        "2_5_4_metrology_sampling_activity_yes": labReportInfo.inp_2_5_4_metrology_sampling_activity_yes === "1",
                        "2_5_4_metrology_sampling_activity_no": labReportInfo.inp_2_5_4_metrology_sampling_activity_no === "1",
                        "2_5_4_procedure_review_request_yes": labReportInfo.inp_2_5_4_procedure_review_request_yes === "1",
                        "2_5_4_procedure_review_request_no": labReportInfo.inp_2_5_4_procedure_review_request_no === "1",
                        "2_5_4_decision_rule_yes": {
                            "value": labReportInfo.inp_2_5_4_decision_rule_yes === "1",
                            "2_5_4_agreement_customer_yes": labReportInfo.inp_2_5_4_agreement_customer_yes === "1",
                            "2_5_4_agreement_customer_no": labReportInfo.inp_2_5_4_agreement_customer_no === "1"
                        },
                        "2_5_4_decision_rule_no": labReportInfo.inp_2_5_4_decision_rule_no === "1",

                        "2_5_4_method_verification_yes": labReportInfo.inp_2_5_4_method_verification_yes === "1",
                        "2_5_4_method_verification_no": labReportInfo.inp_2_5_4_method_verification_no === "1",
                        "2_5_4_sample_management_yes": labReportInfo.inp_2_5_4_sample_management_yes === "1",
                        "2_5_4_sample_management_no": labReportInfo.inp_2_5_4_sample_management_no === "1",
                        "2_5_4_record_management_yes": labReportInfo.inp_2_5_4_record_management_yes === "1",
                        "2_5_4_record_management_no": labReportInfo.inp_2_5_4_record_management_no === "1",
                        "2_5_4_uncertainty_evaluation_yes": labReportInfo.inp_2_5_4_uncertainty_evaluation_yes === "1",
                        "2_5_4_uncertainty_evaluation_no": labReportInfo.inp_2_5_4_uncertainty_evaluation_no === "1",
                        "2_5_4_result_surveillance_yes": labReportInfo.inp_2_5_4_result_surveillance_yes === "1",
                        "2_5_4_result_surveillance_no": labReportInfo.inp_2_5_4_result_surveillance_no === "1",

                        "2_5_4_proficiency_testing_no": labReportInfo.inp_2_5_4_proficiency_testing_no === "1",
                        "2_5_4_proficiency_testing_yes": {
                            "value": labReportInfo.inp_2_5_4_proficiency_testing_yes === "1",
                            "2_5_4_test_participation": {
                                "value": labReportInfo.inp_2_5_4_test_participation === "1",
                                "2_5_4_test_participation_details1": labReportInfo.inp_2_5_4_test_participation_details1 ,
                                "2_5_4_test_participation_details2": labReportInfo.inp_2_5_4_test_participation_details2 ,
                                "2_5_4_test_calibration": {
                                    "value": labReportInfo.inp_2_5_4_test_calibration === "1",
                                    "2_5_4_calibration_details": labReportInfo.inp_2_5_4_calibration_details ,
                                    "2_5_4_acceptance_criteria_yes": labReportInfo.inp_2_5_4_acceptance_criteria_yes === "1",
                                    "2_5_4_acceptance_criteria_no": {
                                        "value": labReportInfo.inp_2_5_4_acceptance_criteria_no === "1",
                                        "2_5_4_acceptance_criteria1": labReportInfo.inp_2_5_4_acceptance_criteria1 ,
                                        "2_5_4_acceptance_criteria2": labReportInfo.inp_2_5_4_acceptance_criteria2 
                                    }
                                }
                            },
                            "2_5_4_lab_comparison": {
                                "value": labReportInfo.inp_2_5_4_lab_comparison === "1",
                                "2_5_4_lab_comparison_details1": labReportInfo.inp_2_5_4_lab_comparison_details1 ,
                                "2_5_4_lab_comparison_details2": labReportInfo.inp_2_5_4_lab_comparison_details2,
                                "2_5_4_lab_comparison_test": {
                                    "value": labReportInfo.inp_2_5_4_lab_comparison_test === "1",
                                    "2_5_4_lab_comparison_test_details":labReportInfo.inp_2_5_4_lab_comparison_test_details ,
                                    "2_5_4_lab_comparison_test_is_accept_yes": labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_yes === "1",
                                    "2_5_4_lab_comparison_test_is_accept_no": {
                                        "value": labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_no === "1",
                                        "2_5_4_lab_comparison_test_is_accept_details1": labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_details1 ,
                                        "2_5_4_lab_comparison_test_is_accept_details2": labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_details2 
                                    }
                                },
                            },
                            "2_5_4_test_participation2": labReportInfo.inp_2_5_4_test_participation2 === "1",
                            "2_5_4_other_methods": {
                                "value": labReportInfo.inp_2_5_4_other_methods === "1",
                                "2_5_4_other_methods_details1": labReportInfo.inp_2_5_4_other_methods_details1 ,
                                "2_5_4_other_methods_details2": labReportInfo.inp_2_5_4_other_methods_details2 
                            }
                        },
                        "2_5_4_report_approval_review_yes": labReportInfo.inp_2_5_4_report_approval_review_yes === "1",
                        "2_5_4_report_approval_review_no": labReportInfo.inp_2_5_4_report_approval_review_no === "1",
                        "2_5_4_decision_rule2_no": labReportInfo.inp_2_5_4_decision_rule2_no === "1",
                        "2_5_4_decision_rule2_yes": {
                            "value": labReportInfo.inp_2_5_4_decision_rule2_yes === "1",
                            "2_5_4_document_for_criteria_yes": labReportInfo.inp_2_5_4_document_for_criteria_yes === "1",
                            "2_5_4_document_for_criteria_no": labReportInfo.inp_2_5_4_document_for_criteria_no === "1"
                        },
                        "2_5_4_complaint_process_no": labReportInfo.inp_2_5_4_complaint_process_no === "1",
                        "2_5_4_complaint_process_yes": {
                            "value": labReportInfo.inp_2_5_4_complaint_process_yes === "1",
                            "2_5_4_complaint_number": labReportInfo.inp_2_5_4_complaint_number 
                        },
                        "2_5_4_non_conformance_process_no": labReportInfo.inp_2_5_4_non_conformance_process_no === "1",
                        "2_5_4_non_conformance_process_yes": {
                            "value": labReportInfo.inp_2_5_4_non_conformance_process_yes === "1",
                            "2_5_4_non_conformance_number": labReportInfo.inp_2_5_4_non_conformance_number 
                        },
                        "2_5_4_data_control_yes": labReportInfo.inp_2_5_4_data_control_yes === "1",
                        "2_5_4_data_control_no": labReportInfo.inp_2_5_4_data_control_no === "1",
                        "2_5_4_data_transfer_control_yes": labReportInfo.inp_2_5_4_data_transfer_control_yes === "1",
                        "2_5_4_data_transfer_control_no": labReportInfo.inp_2_5_4_data_transfer_control_no === "1",
                        "2_5_4_other": {
                            "value": labReportInfo.inp_2_5_4_other === "1",
                            "2_5_4_text_other1": labReportInfo.inp_2_5_4_text_other1 ,
                            "2_5_4_text_other2": labReportInfo.inp_2_5_4_text_other2 
                        }
                    },
                    "2_5_4_issue_found": {
                        "value": labReportInfo.inp_2_5_4_issue_found === "1",
                        "2_5_4_detail": []
                    }
                },
                {
                    "id": "2_5_5",
                    "2_5_5_structure_compliance": {
                        "value": labReportInfo.inp_2_5_5_structure_compliance === "1",
                        "2_5_5_data_control_option_a": labReportInfo.inp_2_5_5_data_control_option_a === "1",
                        "2_5_5_data_control_option_b": labReportInfo.inp_2_5_5_data_control_option_b === "1",

                        "2_5_5_data_control_policy_yes": labReportInfo.inp_2_5_5_data_control_policy_yes === "1",
                        "2_5_5_data_control_policy_no": labReportInfo.inp_2_5_5_data_control_policy_no === "1",
                        "2_5_5_document_control_yes": labReportInfo.inp_2_5_5_document_control_yes === "1",
                        "2_5_5_document_control_no": labReportInfo.inp_2_5_5_document_control_no === "1",
                        "2_5_5_record_keeping_yes": labReportInfo.inp_2_5_5_record_keeping_yes === "1",
                        "2_5_5_record_keeping_no": labReportInfo.inp_2_5_5_record_keeping_no === "1",
                        "2_5_5_risk_management_yes": labReportInfo.inp_2_5_5_risk_management_yes === "1",
                        "2_5_5_risk_management_no": labReportInfo.inp_2_5_5_risk_management_no === "1",
                        "2_5_5_risk_opportunity_yes": labReportInfo.inp_2_5_5_risk_opportunity_yes === "1",
                        "2_5_5_risk_opportunity_no": labReportInfo.inp_2_5_5_risk_opportunity_no === "1",                   

                        "2_5_5_improvement_opportunity_yes": labReportInfo.inp_2_5_5_improvement_opportunity_yes === "1",
                        "2_5_5_improvement_opportunity_no": labReportInfo.inp_2_5_5_improvement_opportunity_no === "1",
                        "2_5_5_non_conformance_yes": labReportInfo.inp_2_5_5_non_conformance_yes === "1",
                        "2_5_5_non_conformance_no": labReportInfo.inp_2_5_5_non_conformance_no === "1",

                        "2_5_5_internal_audit_no": labReportInfo.inp_2_5_5_internal_audit_no === "1",
                        "2_5_5_internal_audit_yes": {
                            "value": labReportInfo.inp_2_5_5_internal_audit_yes === "1",
                            "2_5_5_audit_frequency":  labReportInfo.inp_2_5_5_audit_frequency,
                            "2_5_5_last_audit_date":  labReportInfo.inp_2_5_5_last_audit_date,
                            "2_5_5_audit_issues":  labReportInfo.inp_2_5_5_audit_issues
                        },
                        "2_5_5_management_review_no": labReportInfo.inp_2_5_5_management_review_no === "1",
                        "2_5_5_management_review_yes": {
                            "value": labReportInfo.inp_2_5_5_management_review_yes === "1",
                            "2_5_5_last_review_date":  labReportInfo.inp_2_5_5_last_review_date
                        },
                        "2_5_5_other": {
                            "value": labReportInfo.inp_2_5_5_other === "1",
                            "2_5_5_text_other1":  labReportInfo.inp_2_5_5_text_other1,
                            "2_5_5_text_other2":  labReportInfo.inp_2_5_5_text_other2
                        }
                    },
                    "2_5_5_issue_found": {
                        "value": labReportInfo.inp_2_5_5_issue_found === "1",
                        "2_5_5_detail": []
                    }
                },
                {
                    "id": "2_5_6_1_1",
                    "2_5_6_1_1_management_review_no": labReportInfo.inp_2_5_6_1_1_management_review_no === "1",
                    "2_5_6_1_1_management_review_yes": {
                        "value": labReportInfo.inp_2_5_6_1_1_management_review_yes === "1",
                        "2_5_6_1_1_scope_certified_no":labReportInfo.inp_2_5_6_1_1_scope_certified_no === "1",
                        "2_5_6_1_1_scope_certified_yes":{
                            "value":labReportInfo.inp_2_5_6_1_1_scope_certified_yes === "1",
                            "2_5_6_1_1_activities_not_certified_yes":labReportInfo.inp_2_5_6_1_1_activities_not_certified_yes === "1",
                            "2_5_6_1_1_activities_not_certified_no":labReportInfo.inp_2_5_6_1_1_activities_not_certified_no === "1",
                        },
                        "2_5_6_1_1_accuracy_yes":labReportInfo.inp_2_5_6_1_1_accuracy_yes === "1",
                        "2_5_6_1_1_accuracy_no":{
                            "value": labReportInfo.inp_2_5_6_1_1_accuracy_no === "1",
                            "2_5_6_1_1_accuracy_detail": labReportInfo.inp_2_5_6_1_1_accuracy_detail
                        },
                    }  
                },
                {
                    "id": "2_5_6_1_2",
                    "2_5_6_1_2_multi_site_display_no": labReportInfo.inp_2_5_6_1_2_multi_site_display_no === "1",
                    "2_5_6_1_2_multi_site_display_yes": {
                        "value": labReportInfo.inp_2_5_6_1_2_multi_site_display_yes === "1",
                        "2_5_6_1_2_multi_site_scope_no":labReportInfo.inp_2_5_6_1_2_multi_site_scope_no === "1",
                        "2_5_6_1_2_multi_site_scope_yes":{
                            "value":labReportInfo.inp_2_5_6_1_2_multi_site_scope_yes === "1",
                            "2_5_6_1_2_multi_site_activities_not_certified_yes":labReportInfo.inp_2_5_6_1_2_multi_site_activities_not_certified_yes === "1",
                            "2_5_6_1_2_multi_site_activities_not_certified_no":labReportInfo.inp_2_5_6_1_2_multi_site_activities_not_certified_no === "1",
                        },
                        "2_5_6_1_2_multi_site_accuracy_yes":labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_yes === "1",
                        "2_5_6_1_2_multi_site_accuracy_no":{
                            "value": labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_no === "1",
                            "2_5_6_1_2_multi_site_accuracy_details": labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_details
                        },
                    }    
                },
                {
                    "id": "2_5_6_1_3",
                    "2_5_6_1_3_certification_status_yes": labReportInfo.inp_2_5_6_1_3_certification_status_yes === "1",
                    "2_5_6_1_3_certification_status_no": {
                        "value": labReportInfo.inp_2_5_6_1_3_certification_status_no === "1",
                        "2_5_6_1_3_certification_status_details": labReportInfo.inp_2_5_6_1_3_certification_status_details
                    }     
                },
                {
                    "id": "2_5_6_1_4",
                    "2_5_6_1_4_display_other_no": labReportInfo.inp_2_5_6_1_4_display_other_no === "1",
                    "2_5_6_1_4_display_other_yes": {
                        "value": labReportInfo.inp_2_5_6_1_4_display_other_yes === "1",
                        "2_5_6_1_4_display_other_details": labReportInfo.inp_2_5_6_1_4_display_other_details,
                        "2_5_6_1_4_certification_status_yes":labReportInfo.inp_2_5_6_1_4_certification_status_yes === "1",
                        "2_5_6_1_4_certification_status_no":{
                            "value": labReportInfo.inp_2_5_6_1_4_certification_status_no === "1",
                            "2_5_6_1_4_certification_status_details": labReportInfo.inp_2_5_6_1_4_certification_status_details
                        }
                    }     
                },
                {
                    "id": "2_5_6_2",
                    "2_5_6_2_lab_availability_yes": labReportInfo.inp_2_5_6_2_lab_availability_yes === "1",    
                    "2_5_6_2_lab_availability_no": labReportInfo.inp_2_5_6_2_lab_availability_no === "1",
                },
                {
                    "id": "2_5_6_2_1",
                    "2_5_6_2_1_ilac_mra_display_no": labReportInfo.inp_2_5_6_2_1_ilac_mra_display_no === "1",
                    "2_5_6_2_1_ilac_mra_display_yes": {
                        "value": labReportInfo.inp_2_5_6_2_1_ilac_mra_display_yes === "1",
                        "2_5_6_2_1_ilac_mra_scope_no":labReportInfo.inp_2_5_6_2_1_ilac_mra_scope_no === "1",
                        "2_5_6_2_1_ilac_mra_scope_yes":{
                            "value":labReportInfo.inp_2_5_6_2_1_ilac_mra_scope_yes === "1",
                            "2_5_6_2_1_ilac_mra_disclosure_yes":labReportInfo.inp_2_5_6_2_1_ilac_mra_disclosure_yes === "1",
                            "2_5_6_2_1_ilac_mra_disclosure_no":labReportInfo.inp_2_5_6_2_1_ilac_mra_disclosure_no === "1",
                        },
                        "2_5_6_2_1_ilac_mra_compliance_yes":labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_yes === "1",
                        "2_5_6_2_1_ilac_mra_compliance_no":{
                            "value": labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_no === "1",
                            "2_5_6_2_1_ilac_mra_compliance_details": labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_details
                        },
                    }    
                },
                {
                    "id": "2_5_6_2_2",
                    "2_5_6_2_2_ilac_mra_compliance_no": labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_no === "1",
                    "2_5_6_2_2_ilac_mra_compliance_yes": {
                        "value": labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_yes === "1",
                        "2_5_6_2_2_ilac_mra_compliance_details":labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_details,
                        "2_5_6_2_2_mra_compliance_yes":labReportInfo.inp_2_5_6_2_2_mra_compliance_yes === "1",
                        "2_5_6_2_2_mra_compliance_no":{
                            "value":labReportInfo.inp_2_5_6_2_2_mra_compliance_no === "1",
                            "2_5_6_2_2_mra_compliance_details":labReportInfo.inp_2_5_6_2_2_mra_compliance_details
                        }
                    }    
                },
                {
                    "id": "3_0",
                    "3_0_assessment_results": {
                        "value": labReportInfo.inp_3_0_assessment_results === "1",
                        "3_0_issue_count":labReportInfo.inp_3_0_issue_count,
                        "3_0_remarks_count":labReportInfo.inp_3_0_remarks_count,
                        "3_0_deficiencies_details":labReportInfo.inp_3_0_deficiencies_details,
                        "3_0_deficiency_resolution_date":labReportInfo.inp_3_0_deficiency_resolution_date,
                    },
                    "3_0_offer_agreement": labReportInfo.inp_3_0_offer_agreement === "1",
                },
            ];

            if (labReportInfo.persons) {
                try {
                    persons = JSON.parse(labReportInfo.persons);
                    renderPersons();
                } catch (error) {
                    console.error('Failed to parse persons data:', error);
                    persons = [];
                }
            }

            console.log(labReportInfo);

            $('#2_2_assessment_on_site').prop('checked', labReportInfo.inp_2_2_assessment_on_site === "1");
            $('#2_2_assessment_at_tisi').prop('checked', labReportInfo.inp_2_2_assessment_at_tisi === "1");
            $('#2_2_remote_assessment').prop('checked', labReportInfo.inp_2_2_remote_assessment === "1");
            $('#2_2_self_declaration').prop('checked', labReportInfo.inp_2_2_self_declaration === "1");

            $('#2_5_1_structure_compliance').prop('checked', labReportInfo.inp_2_5_1_structure_compliance === "1");
            
            $('#2_5_1_central_management_yes').prop('checked', labReportInfo.inp_2_5_1_central_management_yes === "1");
            $('#2_5_1_central_management_no').prop('checked', labReportInfo.inp_2_5_1_central_management_no === "1");
            $('#2_5_1_quality_policy_yes').prop('checked', labReportInfo.inp_2_5_1_quality_policy_yes === "1");
            $('#2_5_1_quality_policy_no').prop('checked', labReportInfo.inp_2_5_1_quality_policy_no === "1");
            $('#2_5_1_risk_assessment_yes').prop('checked', labReportInfo.inp_2_5_1_risk_assessment_yes === "1");
            $('#2_5_1_risk_assessment_no').prop('checked', labReportInfo.inp_2_5_1_risk_assessment_no === "1");
            $('#2_5_1_other').prop('checked', labReportInfo.inp_2_5_1_other === "1");
            $('#2_5_1_text_other1').val(labReportInfo.inp_2_5_1_text_other1 || '');
            $('#2_5_1_text_other2').val(labReportInfo.inp_2_5_1_text_other2 || '');
            $('#2_5_1_issue_found').prop('checked', labReportInfo.inp_2_5_1_issue_found === "1");

            $('#2_5_2_structure_compliance').prop('checked', labReportInfo.inp_2_5_2_structure_compliance === "1");
            $('#2_5_2_lab_management').prop('checked', labReportInfo.inp_2_5_2_lab_management === "1");
            $('#2_5_2_lab_management_details').val(labReportInfo.inp_2_5_2_lab_management_details);
            $('#2_5_2_staff_assignment_yes').prop('checked', labReportInfo.inp_2_5_2_staff_assignment_yes === "1");
            $('#2_5_2_staff_assignment_no').prop('checked', labReportInfo.inp_2_5_2_staff_assignment_no === "1");
            $('#2_5_2_responsibility_yes').prop('checked', labReportInfo.inp_2_5_2_responsibility_yes === "1");
            $('#2_5_2_responsibility_no').prop('checked', labReportInfo.inp_2_5_2_responsibility_no === "1");

            $('#2_5_2_other').prop('checked', labReportInfo.inp_2_5_2_other === "1");
            $('#2_5_2_text_other1').val(labReportInfo.inp_2_5_2_text_other1);
            $('#2_5_2_text_other2').val(labReportInfo.inp_2_5_2_text_other2);
            $('#2_5_2_issue_found').prop('checked', labReportInfo.inp_2_5_2_issue_found === "1");

            $('#2_5_3_structure_compliance').prop('checked', labReportInfo.inp_2_5_3_structure_compliance === "1");
            $('#2_5_3_personnel_qualification_yes').prop('checked', labReportInfo.inp_2_5_3_personnel_qualification_yes === "1");
            $('#2_5_3_personnel_qualification_no').prop('checked', labReportInfo.inp_2_5_3_personnel_qualification_no === "1");
            $('#2_5_3_assign_personnel_appropriately_yes').prop('checked', labReportInfo.inp_2_5_3_assign_personnel_appropriately_yes === "1");
            $('#2_5_3_assign_personnel_appropriately_no').prop('checked', labReportInfo.inp_2_5_3_assign_personnel_appropriately_no === "1");
            $('#2_5_3_training_need_assessment_yes').prop('checked', labReportInfo.inp_2_5_3_training_need_assessment_yes === "1");
            $('#2_5_3_training_need_assessment_no').prop('checked', labReportInfo.inp_2_5_3_training_need_assessment_no === "1");
            $('#2_5_3_facility_and_environment_control_yes').prop('checked', labReportInfo.inp_2_5_3_facility_and_environment_control_yes === "1");
            $('#2_5_3_facility_and_environment_control_no').prop('checked', labReportInfo.inp_2_5_3_facility_and_environment_control_no === "1");
            $('#2_5_3_equipment_maintenance_calibration_yes').prop('checked', labReportInfo.inp_2_5_3_equipment_maintenance_calibration_yes === "1");
            $('#2_5_3_equipment_maintenance_calibration_no').prop('checked', labReportInfo.inp_2_5_3_equipment_maintenance_calibration_no === "1");
            $('#2_5_3_metrology_traceability_yes').prop('checked', labReportInfo.inp_2_5_3_metrology_traceability_yes === "1");
            $('#2_5_3_metrology_traceability_no').prop('checked', labReportInfo.inp_2_5_3_metrology_traceability_no === "1");
            $('#2_5_3_external_product_service_control_yes').prop('checked', labReportInfo.inp_2_5_3_external_product_service_control_yes === "1");
            $('#2_5_3_external_product_service_control_no').prop('checked', labReportInfo.inp_2_5_3_external_product_service_control_no === "1");
            $('#2_5_3_other').prop('checked', labReportInfo.inp_2_5_3_other === "1");
            $('#2_5_3_text_other1').val(labReportInfo.inp_2_5_3_text_other1);
            $('#2_5_3_text_other2').val(labReportInfo.inp_2_5_3_text_other2);
            $('#2_5_3_issue_found').prop('checked', labReportInfo.inp_2_5_3_issue_found === "1");


            $('#2_5_4_structure_compliance').prop('checked', labReportInfo.inp_2_5_4_structure_compliance === "1");
            $('#2_5_4_policy_compliance_yes').prop('checked', labReportInfo.inp_2_5_4_policy_compliance_yes === "1");
            $('#2_5_4_policy_compliance_no').prop('checked', labReportInfo.inp_2_5_4_policy_compliance_no === "1");
            $('#2_5_4_metrology_sampling_activity_yes').prop('checked', labReportInfo.inp_2_5_4_metrology_sampling_activity_yes === "1");
            $('#2_5_4_metrology_sampling_activity_no').prop('checked', labReportInfo.inp_2_5_4_metrology_sampling_activity_no === "1");
            $('#2_5_4_procedure_review_request_yes').prop('checked', labReportInfo.inp_2_5_4_procedure_review_request_yes === "1");
            $('#2_5_4_procedure_review_request_no').prop('checked', labReportInfo.inp_2_5_4_procedure_review_request_no === "1");
            $('#2_5_4_decision_rule_yes').prop('checked', labReportInfo.inp_2_5_4_decision_rule_yes === "1");
            $('#2_5_4_agreement_customer_yes').prop('checked', labReportInfo.inp_2_5_4_agreement_customer_yes === "1");
            $('#2_5_4_agreement_customer_no').prop('checked', labReportInfo.inp_2_5_4_agreement_customer_no === "1");
            $('#2_5_4_decision_rule_no').prop('checked', labReportInfo.inp_2_5_4_decision_rule_no === "1");
            $('#2_5_4_method_verification_yes').prop('checked', labReportInfo.inp_2_5_4_method_verification_yes === "1");
            $('#2_5_4_method_verification_no').prop('checked', labReportInfo.inp_2_5_4_method_verification_no === "1");
            $('#2_5_4_sample_management_yes').prop('checked', labReportInfo.inp_2_5_4_sample_management_yes === "1");
            $('#2_5_4_sample_management_no').prop('checked', labReportInfo.inp_2_5_4_sample_management_no === "1");
            $('#2_5_4_record_management_yes').prop('checked', labReportInfo.inp_2_5_4_record_management_yes === "1");
            $('#2_5_4_record_management_no').prop('checked', labReportInfo.inp_2_5_4_record_management_no === "1");
            $('#2_5_4_uncertainty_evaluation_yes').prop('checked', labReportInfo.inp_2_5_4_uncertainty_evaluation_yes === "1");
            $('#2_5_4_uncertainty_evaluation_no').prop('checked', labReportInfo.inp_2_5_4_uncertainty_evaluation_no === "1");
            $('#2_5_4_result_surveillance_yes').prop('checked', labReportInfo.inp_2_5_4_result_surveillance_yes === "1");
            $('#2_5_4_result_surveillance_no').prop('checked', labReportInfo.inp_2_5_4_result_surveillance_no === "1");
            $('#2_5_4_proficiency_testing_no').prop('checked', labReportInfo.inp_2_5_4_proficiency_testing_no === "1");
            $('#2_5_4_proficiency_testing_yes').prop('checked', labReportInfo.inp_2_5_4_proficiency_testing_yes === "1");
            $('#2_5_4_test_participation').prop('checked', labReportInfo.inp_2_5_4_test_participation === "1");
            $('#2_5_4_test_participation_details1').val(labReportInfo.inp_2_5_4_test_participation_details1);
            $('#2_5_4_test_participation_details2').val(labReportInfo.inp_2_5_4_test_participation_details2);
            $('#2_5_4_test_calibration').prop('checked', labReportInfo.inp_2_5_4_test_calibration === "1");
            $('#2_5_4_calibration_details').val(labReportInfo.inp_2_5_4_calibration_details);
            $('#2_5_4_acceptance_criteria_yes').prop('checked', labReportInfo.inp_2_5_4_acceptance_criteria_yes === "1");
            $('#2_5_4_acceptance_criteria_no').prop('checked', labReportInfo.inp_2_5_4_acceptance_criteria_no === "1");
            $('#2_5_4_acceptance_criteria1').val(labReportInfo.inp_2_5_4_acceptance_criteria1);
            $('#2_5_4_acceptance_criteria2').val(labReportInfo.inp_2_5_4_acceptance_criteria2);
            $('#2_5_4_lab_comparison').prop('checked', labReportInfo.inp_2_5_4_lab_comparison === "1");
            $('#2_5_4_lab_comparison_details1').val(labReportInfo.inp_2_5_4_lab_comparison_details1);
            $('#2_5_4_lab_comparison_details2').val(labReportInfo.inp_2_5_4_lab_comparison_details2);
            $('#2_5_4_lab_comparison_test').prop('checked', labReportInfo.inp_2_5_4_lab_comparison_test === "1");
            $('#2_5_4_lab_comparison_test_details').val(labReportInfo.inp_2_5_4_lab_comparison_test_details);
            $('#2_5_4_lab_comparison_test_is_accept_yes').prop('checked', labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_yes === "1");
            $('#2_5_4_lab_comparison_test_is_accept_no').prop('checked', labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_no === "1");
            $('#2_5_4_lab_comparison_test_is_accept_details1').val(labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_details1);
            $('#2_5_4_lab_comparison_test_is_accept_details2').val(labReportInfo.inp_2_5_4_lab_comparison_test_is_accept_details2);
            $('#2_5_4_test_participation2').prop('checked', labReportInfo.inp_2_5_4_test_participation2 === "1");
            $('#2_5_4_other_methods').prop('checked', labReportInfo.inp_2_5_4_other_methods === "1");
            $('#2_5_4_other_methods_details1').val(labReportInfo.inp_2_5_4_other_methods_details1);
            $('#2_5_4_other_methods_details2').val(labReportInfo.inp_2_5_4_other_methods_details2);
            $('#2_5_4_report_approval_review_yes').prop('checked', labReportInfo.inp_2_5_4_report_approval_review_yes === "1");
            $('#2_5_4_report_approval_review_no').prop('checked', labReportInfo.inp_2_5_4_report_approval_review_no === "1");
            $('#2_5_4_decision_rule2_no').prop('checked', labReportInfo.inp_2_5_4_decision_rule2_no === "1");
            $('#2_5_4_decision_rule2_yes').prop('checked', labReportInfo.inp_2_5_4_decision_rule2_yes === "1");
            $('#2_5_4_document_for_criteria_yes').prop('checked', labReportInfo.inp_2_5_4_document_for_criteria_yes === "1");
            $('#2_5_4_document_for_criteria_no').prop('checked', labReportInfo.inp_2_5_4_document_for_criteria_no === "1");
            $('#2_5_4_complaint_process_no').prop('checked', labReportInfo.inp_2_5_4_complaint_process_no === "1");
            $('#2_5_4_complaint_process_yes').prop('checked', labReportInfo.inp_2_5_4_complaint_process_yes === "1");
            $('#2_5_4_complaint_number').val(labReportInfo.inp_2_5_4_complaint_number);
            $('#2_5_4_non_conformance_process_no').prop('checked', labReportInfo.inp_2_5_4_non_conformance_process_no === "1");
            $('#2_5_4_non_conformance_process_yes').prop('checked', labReportInfo.inp_2_5_4_non_conformance_process_yes === "1");
            $('#2_5_4_non_conformance_number').val(labReportInfo.inp_2_5_4_non_conformance_number);
            $('#2_5_4_data_control_yes').prop('checked', labReportInfo.inp_2_5_4_data_control_yes === "1");
            $('#2_5_4_data_control_no').prop('checked', labReportInfo.inp_2_5_4_data_control_no === "1");
            $('#2_5_4_data_transfer_control_yes').prop('checked', labReportInfo.inp_2_5_4_data_transfer_control_yes === "1");
            $('#2_5_4_data_transfer_control_no').prop('checked', labReportInfo.inp_2_5_4_data_transfer_control_no === "1");
            $('#2_5_4_other').prop('checked', labReportInfo.inp_2_5_4_other === "1");
            $('#2_5_4_text_other1').val(labReportInfo.inp_2_5_4_text_other1);
            $('#2_5_4_text_other2').val(labReportInfo.inp_2_5_4_text_other2);
            $('#2_5_4_issue_found').prop('checked', labReportInfo.inp_2_5_4_issue_found === "1");
            $('#2_5_5_structure_compliance').prop('checked', labReportInfo.inp_2_5_5_structure_compliance === "1");
            $('#2_5_5_data_control_option_a').prop('checked', labReportInfo.inp_2_5_5_data_control_option_a === "1");
            $('#2_5_5_data_control_option_b').prop('checked', labReportInfo.inp_2_5_5_data_control_option_b === "1");
            $('#2_5_5_data_control_policy_yes').prop('checked', labReportInfo.inp_2_5_5_data_control_policy_yes === "1");
            $('#2_5_5_data_control_policy_no').prop('checked', labReportInfo.inp_2_5_5_data_control_policy_no === "1");
            $('#2_5_5_document_control_yes').prop('checked', labReportInfo.inp_2_5_5_document_control_yes === "1");
            $('#2_5_5_document_control_no').prop('checked', labReportInfo.inp_2_5_5_document_control_no === "1");
            $('#2_5_5_record_keeping_yes').prop('checked', labReportInfo.inp_2_5_5_record_keeping_yes === "1");
            $('#2_5_5_record_keeping_no').prop('checked', labReportInfo.inp_2_5_5_record_keeping_no === "1");
            $('#2_5_5_risk_management_yes').prop('checked', labReportInfo.inp_2_5_5_risk_management_yes === "1");
            $('#2_5_5_risk_management_no').prop('checked', labReportInfo.inp_2_5_5_risk_management_no === "1");
            $('#2_5_5_risk_opportunity_yes').prop('checked', labReportInfo.inp_2_5_5_risk_opportunity_yes === "1");
            $('#2_5_5_risk_opportunity_no').prop('checked', labReportInfo.inp_2_5_5_risk_opportunity_no === "1");
            $('#2_5_5_improvement_opportunity_yes').prop('checked', labReportInfo.inp_2_5_5_improvement_opportunity_yes === "1");
            $('#2_5_5_improvement_opportunity_no').prop('checked', labReportInfo.inp_2_5_5_improvement_opportunity_no === "1");
            $('#2_5_5_non_conformance_yes').prop('checked', labReportInfo.inp_2_5_5_non_conformance_yes === "1");
            $('#2_5_5_non_conformance_no').prop('checked', labReportInfo.inp_2_5_5_non_conformance_no === "1");
            $('#2_5_5_internal_audit_no').prop('checked', labReportInfo.inp_2_5_5_internal_audit_no === "1");
            $('#2_5_5_internal_audit_yes').prop('checked', labReportInfo.inp_2_5_5_internal_audit_yes === "1");
            $('#2_5_5_audit_frequency').val(labReportInfo.inp_2_5_5_audit_frequency);
            $('#2_5_5_last_audit_date').val(labReportInfo.inp_2_5_5_last_audit_date);
            $('#2_5_5_audit_issues').val(labReportInfo.inp_2_5_5_audit_issues);
            $('#2_5_5_management_review_no').prop('checked', labReportInfo.inp_2_5_5_management_review_no === "1");
            $('#2_5_5_management_review_yes').prop('checked', labReportInfo.inp_2_5_5_management_review_yes === "1");
            $('#2_5_5_last_review_date').val(labReportInfo.inp_2_5_5_last_review_date);
            $('#2_5_5_other').prop('checked', labReportInfo.inp_2_5_5_other === "1");
            $('#2_5_5_text_other1').val(labReportInfo.inp_2_5_5_text_other1);
            $('#2_5_5_text_other2').val(labReportInfo.inp_2_5_5_text_other2);
            $('#2_5_5_issue_found').prop('checked', labReportInfo.inp_2_5_5_issue_found === "1");
          
          
            $('#2_5_6_1_2_multi_site_display_no').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_display_no === "1");
            $('#2_5_6_1_2_multi_site_display_yes').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_display_yes === "1");
            $('#2_5_6_1_2_multi_site_scope_no').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_scope_no === "1");
            $('#2_5_6_1_2_multi_site_scope_yes').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_scope_yes === "1");
            $('#2_5_6_1_2_multi_site_activities_not_certified_yes').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_activities_not_certified_yes === "1");
            $('#2_5_6_1_2_multi_site_activities_not_certified_no').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_activities_not_certified_no === "1");
            $('#2_5_6_1_2_multi_site_accuracy_yes').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_yes === "1");
            $('#2_5_6_1_2_multi_site_accuracy_no').prop('checked', labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_no === "1");
            $('#2_5_6_1_2_multi_site_accuracy_details').val(labReportInfo.inp_2_5_6_1_2_multi_site_accuracy_details);
            $('#2_5_6_1_3_certification_status_yes').prop('checked', labReportInfo.inp_2_5_6_1_3_certification_status_yes === "1");
            $('#2_5_6_1_3_certification_status_no').prop('checked', labReportInfo.inp_2_5_6_1_3_certification_status_no === "1");
            $('#2_5_6_1_3_certification_status_details').val(labReportInfo.inp_2_5_6_1_3_certification_status_details);
            $('#2_5_6_1_4_display_other_no').prop('checked', labReportInfo.inp_2_5_6_1_4_display_other_no === "1");
            $('#2_5_6_1_4_display_other_yes').prop('checked', labReportInfo.inp_2_5_6_1_4_display_other_yes === "1");
            $('#2_5_6_1_4_display_other_details').val(labReportInfo.inp_2_5_6_1_4_display_other_details);
            $('#2_5_6_1_4_certification_status_yes').prop('checked', labReportInfo.inp_2_5_6_1_4_certification_status_yes === "1");
            $('#2_5_6_1_4_certification_status_no').prop('checked', labReportInfo.inp_2_5_6_1_4_certification_status_no === "1");
            $('#2_5_6_1_4_certification_status_details').val(labReportInfo.inp_2_5_6_1_4_certification_status_details);
            $('#2_5_6_2_lab_availability_yes').prop('checked', labReportInfo.inp_2_5_6_2_lab_availability_yes === "1");
            $('#2_5_6_2_lab_availability_no').prop('checked', labReportInfo.inp_2_5_6_2_lab_availability_no === "1");
            $('#2_5_6_2_1_ilac_mra_display_no').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_display_no === "1");
            $('#2_5_6_2_1_ilac_mra_display_yes').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_display_yes === "1");
            $('#2_5_6_2_1_ilac_mra_scope_no').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_scope_no === "1");
            $('#2_5_6_2_1_ilac_mra_scope_yes').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_scope_yes === "1");
            $('#2_5_6_2_1_ilac_mra_disclosure_yes').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_disclosure_yes === "1");
            $('#2_5_6_2_1_ilac_mra_disclosure_no').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_disclosure_no === "1");
            $('#2_5_6_2_1_ilac_mra_compliance_yes').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_yes === "1");
            $('#2_5_6_2_1_ilac_mra_compliance_no').prop('checked', labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_no === "1");
            $('#2_5_6_2_1_ilac_mra_compliance_details').val(labReportInfo.inp_2_5_6_2_1_ilac_mra_compliance_details);

           
            $('#2_5_6_2_2_ilac_mra_compliance_no').prop('checked', labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_no === "1");
            $('#2_5_6_2_2_ilac_mra_compliance_yes').prop('checked', labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_yes === "1");
            $('#2_5_6_2_2_ilac_mra_compliance_details').val(labReportInfo.inp_2_5_6_2_2_ilac_mra_compliance_details);
            $('#2_5_6_2_2_mra_compliance_yes').prop('checked', labReportInfo.inp_2_5_6_2_2_mra_compliance_yes === "1");
            $('#2_5_6_2_2_mra_compliance_no').prop('checked', labReportInfo.inp_2_5_6_2_2_mra_compliance_no === "1");
            $('#2_5_6_2_2_mra_compliance_details').val(labReportInfo.inp_2_5_6_2_2_mra_compliance_details); 

            $('#3_0_assessment_results').prop('checked', labReportInfo.inp_3_0_assessment_results === "1");
            $('#3_0_issue_count').val(labReportInfo.inp_3_0_issue_count); 
            $('#3_0_remarks_count').val(labReportInfo.inp_3_0_remarks_count); 
            $('#3_0_deficiencies_details').val(labReportInfo.inp_3_0_deficiencies_details); 
            $('#3_0_deficiency_resolution_date').val(labReportInfo.inp_3_0_deficiency_resolution_date); 
            $('#3_0_offer_agreement').prop('checked', labReportInfo.inp_3_0_offer_agreement === "1");
            // $('#xx').prop('checked', labReportInfo.xx === "1");
           

            for (let i = 1; i <= 5; i++) {
                const detailKey = `inp_2_5_${i}_detail`; // ชื่อคีย์ใน labReportInfo เช่น inp_2_5_1_detail
                if (labReportInfo[detailKey]) {
                    try {
                        // แปลง JSON string ให้เป็น Array
                        const defectDetails = JSON.parse(labReportInfo[detailKey]);
                        blockId = `2_5_${i}`
                        // ค้นหา object ที่มี id ตรงกับ "2_5_X"
                        const targetBlock = defectBlock.find(block => block.id === blockId);
                        
                        if (targetBlock) {
                            // อัปเดต defect_info ด้วยข้อมูลจาก labReportInfo
                            targetBlock.defect_info = defectDetails;
                            findBlockById(blockId,targetBlock.defect_info);
                            renderTextBlocks(blockId);
                            renderTableFromLines(blockId);
                        }
                    } catch (error) {
                        console.error(`Error parsing ${detailKey}:`, error);
                    }
                }
            }
            // console.log(data);
        });

 

        // console.log(defectBlock);

        // console.log(data);
        
        let textBlocks = {
            defect_info: [] // เก็บเป็น JSON ในคีย์ "defect_info"
        };


        
        function loadSigners() {
            $.ajax({
                url: "{{ route('assessment_report_assignment.api.get_signers') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // ส่ง CSRF token สำหรับ Laravel
                },
                success: function(response) {
                    if (response.signers && Array.isArray(response.signers)) {
                        // ลบ option เก่าใน <select>
                        $('.signature-select').empty().append('<option value="">- ผู้ลงนาม -</option>');
                        
                        // response.signers.forEach(function(signer) {
                        //     const option = `<option value="${signer.id}">${signer.id} ${signer.name}</option>`;
                        //     $('.signature-select').append(option);
                        // });

                        response.signers.forEach(function(signer) {
                            if (defaultSignerIds.includes(signer.id)) {
                                const option = `<option value="${signer.id}">${signer.name}</option>`;
                                $('.signature-select').append(option);
                            }
                        });

                        // เทียบ signer_id กับ select และเลือก option ให้ตรงกัน
                        signer.forEach(function(signerItem) {
                            const selectElement = $(`#${signerItem.code}`);
                            if (selectElement.length) {
                                selectElement.val(signerItem.signer_id).trigger('change');
                            }
                        });

                    } else {
                        alert('ไม่มีข้อมูลผู้ลงนาม');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                }
            });
        }

        //  function loadSigners() {
        //     $.ajax({
        //         url: "{{ route('assessment_report_assignment.api.get_signers') }}",
        //         method: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.signers && Array.isArray(response.signers)) {
                        
        //                 // 1. ล้างและเพิ่ม option "- ผู้ลงนาม -" เริ่มต้น (เหมือนเดิม)
        //                 $('.signature-select').empty().append('<option value="">- ผู้ลงนาม -</option>');
                        
        //                 // 2. วนลูปสร้าง <option> จากข้อมูลที่ได้จาก API (เหมือนเดิม)
        //                 // response.signers.forEach(function(signer) {
        //                 //     const option = `<option value="${signer.id}">${signer.id} ${signer.name}</option>`;
        //                 //     $('.signature-select').append(option);
        //                 // });

                        

        //                 // console.log(defaultSignerIds)

        //                 response.signers.forEach(function(signer) {
        //                     if (defaultSignerIds.includes(signer.id)) {
        //                         const option = `<option value="${signer.id}">${signer.name}</option>`;
        //                         $('.signature-select').append(option);
        //                     }
        //                 });



        //             } else {
        //                 alert('ไม่มีข้อมูลผู้ลงนาม');
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', error);
        //             alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        //         }
        //     });
        // }

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

        // ฟังก์ชัน render ข้อมูล
        function renderPersons() {
            const personWrapper = document.getElementById('person_wrapper');
            personWrapper.innerHTML = ''; // เคลียร์ข้อมูลเก่าก่อน render ใหม่

            // ลูปแสดงข้อมูลในอาร์เรย์
            persons.forEach((person, index) => {
                const personDiv = document.createElement('div');
                personDiv.style.display = 'flex';
                personDiv.style.gap = '10px';
                
                personDiv.innerHTML = `
                    <span style="flex: 0 0 20px;">${index + 1}.</span>
                    <span style="flex: 1 0 150px;">${person.name}</span>
                    <span style="flex: 1 0 300px;">ตำแหน่ง ${person.position} <span style="padding-left:10px; cursor:pointer;" onclick="deletePerson(${index})">
                        <i class="fa-solid fa-trash-can" style="color: red;font-size:16px"></i>
                    </span></span>
                   
                `;
                
                personWrapper.appendChild(personDiv);
            });
        }

        // ฟังก์ชันลบข้อมูล
        function deletePerson(index) {
            persons.splice(index, 1); // ลบข้อมูลจากอาร์เรย์
            renderPersons(); // render ใหม่หลังจากลบข้อมูล
        }
        // function addTextBlock() {
        //     const inputText = document.getElementById('modal-input').value.trim();

        //     if (inputText) {
        //         const newBlock = {
        //             id: `wrapper_2_5_1-${textBlocks.defect_info.length + 1}`, // สร้าง id อัตโนมัติ
        //             raw: inputText, // ข้อความที่รับมา
        //             lines: []       // คีย์ lines เป็น array เริ่มต้นว่างเปล่า
        //         };

        //         // เพิ่มข้อมูลใหม่เข้า defect_info
        //         textBlocks.defect_info.push(newBlock);

        //         // เรียก render ใหม่เพื่อแสดงผล
        //         renderTextBlocks();
        //     }

        //     var formData = $(this).serialize(); // ดึงข้อมูลฟอร์มทั้งหมด
        //     $.ajax({
        //         url: "{{route('certify.api.test_splitter')}}",
        //         method: 'POST', // วิธีการส่งข้อมูล
        //         data: {
        //             inputText: inputText, // ค่าที่ส่งไปยัง Controller
        //             _token: '{{ csrf_token() }}' // ส่ง CSRF token สำหรับ Laravel
        //         },
        //         success: function(response) {

        //             if (response.success) {
        //                 const words = response.data; // สมมติ response.data เป็น array คำ
        //                 const lines = getLines(words, 720); // หาค่า lines โดยใช้ maxWidth = 720

        //                 // อัปเดต lines ใน textBlocks.defect_info
        //                 const lastBlock = textBlocks.defect_info[textBlocks.defect_info.length - 1];
        //                 lastBlock.lines = lines;

        //                 console.log('Updated textBlocks:', textBlocks); // ตรวจสอบข้อมูลที่อัปเดต

        //                  // เรียก renderTableFromLines เพื่อแสดงตารางจาก lines ที่ได้
        //                 renderTableFromLines();
        //             } else {
        //                 alert('เกิดข้อผิดพลาด: ไม่สามารถประมวลผลข้อความได้');
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', error);
        //             alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
        //         }
        //     });
        //     closeModal();
        // }

        function updateDataDetail()
        {
            defectBlock.forEach(block => {
                const matchingData = data.find(item => item.id === block.id);
                if (matchingData) {
                    // สร้างคีย์สำหรับ issue_found และ detail แบบไดนามิก
                    const issueFoundKey = `${block.id}_issue_found`;
                    const detailKey = `${block.id}_detail`;

                    // ตรวจสอบว่ามีคีย์ issue_found และ detail หรือไม่
                    if (matchingData[issueFoundKey] && matchingData[issueFoundKey][detailKey]) {
                        block.defect_info = matchingData[issueFoundKey][detailKey];
                    }
                }
            });
        }

        function addTextBlock() {

            const inputText = document.getElementById('modal-input').value.trim();

            // AJAX เพื่อส่งข้อมูล
            $.ajax({
                url: "{{route('certify.api.test_splitter')}}",
                method: 'POST',
                data: {
                    inputText: inputText,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const words = response.data;
                        const dataContainer = document.getElementById(`data_container_${blockId}`);
                        
                        const lines = getLines(words, 800, dataContainer);
                        // อัปเดต lines ใน defect_info
                        // console.log('defectBlock:', defectBlock);
                        // console.log('lines:', lines);
                        const targetBlock = defectBlock.find(block => block.id === blockId);


                        if (inputText) {
                            const targetBlock = defectBlock.find(block => block.id === blockId);
                            if (targetBlock) {
                                const newBlock = {
                                    id: `wrapper_${blockId}-${targetBlock.defect_info.length + 1}`,
                                    raw: inputText,
                                    lines: lines // เริ่มต้นว่างเปล่า
                                    
                                };
                                targetBlock.defect_info.push(newBlock);
                                // เรียก render ใหม่เฉพาะ block ที่อัปเดต
                                // console.log('aha',defectBlock)
                            
                            }

                        }

                        // console.log('Updated defectBlock:', defectBlock);
                        // console.log('Updated updateDataDetail:', data);

                        findBlockById(blockId,targetBlock.defect_info);
                        renderTextBlocks(blockId);
                        renderTableFromLines(blockId); // อัปเดตตาราง
                        
                    } else {
                        alert('เกิดข้อผิดพลาด: ไม่สามารถประมวลผลข้อความได้');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                }
            });

            closeModal();
            }

        // CSS สำหรับขนาดฟอนต์ที่ใช้วัด
        testContainer.style.fontSize = "20px";
        testContainer.style.visibility = "hidden"; // ซ่อน container ที่ใช้สำหรับการทดสอบความกว้าง

        function getLines(words, maxWidth, dataContainer) {
            const lines = [];
            let currentLine = '';

            words.forEach(word => {
                const testWord = currentLine ? currentLine + word : word;
                dataContainer.textContent = testWord;

                const width = dataContainer.getBoundingClientRect().width;

                if (width <= maxWidth) {
                    currentLine = testWord;
                } else {
                    lines.push(currentLine);
                    currentLine = word;
                }
            });

            if (currentLine) {
                lines.push(currentLine);
            }
            console.log(lines);
            return lines;
        }

        function renderTableFromLines(blockId) {
            const targetBlock = defectBlock.find(block => block.id === blockId);
            
            if (targetBlock) {
                const tableContainer = document.getElementById(`table_container_${blockId}`);
                tableContainer.innerHTML = ''; // ล้างข้อมูลเดิม
                

                // เรียงข้อมูล defect_info ตาม id
                const sortedBlocks = targetBlock.defect_info.sort((a, b) => {
                    const idA = parseInt(a.id.split('-')[1]); // แยกตัวเลขจาก id เช่น block-1
                    const idB = parseInt(b.id.split('-')[1]);
                    return idA - idB;
                });
                // console.log(sortedBlocks);
                // สร้างตารางใหม่
                const table = document.createElement('table');
                table.style.width = "800px";
                table.style.borderCollapse = "collapse";
                table.style.marginTop = "20px";
                table.style.marginLeft = "-50px";

                // วนลูปสร้างแถวในตาราง
                sortedBlocks.forEach(block => {
                    const lines = block.lines;
                    lines.forEach((line, index) => {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');

                        // เพิ่มข้อความในเซลล์
                        td.innerHTML = line;
                        td.style.border = "none";
                        td.style.borderBottom = "1px dotted #000";
                        td.style.padding = "0";
                        td.style.margin = "0";
                        td.style.lineHeight = "1.5";
                        td.style.fontSize = "20px";

                        // เพิ่มปุ่มลบสำหรับข้อความสุดท้ายใน block
                        if (index === lines.length - 1) {
                            const deleteButton = document.createElement('button');
                            deleteButton.style.marginLeft = "2px";
                            deleteButton.style.fontSize = "16px";
                            deleteButton.style.border = "none";
                            deleteButton.style.background = "transparent";
                            deleteButton.style.color = "red";

                            // ใช้ไอคอนถังขยะจาก Font Awesome
                            deleteButton.innerHTML = '<i class="fa-solid fa-trash-can"></i>';

                            // กำหนดฟังก์ชันลบ
                            deleteButton.onclick = function () {
                                deleteBlock(blockId, block.id); // ลบ block ที่เกี่ยวข้อง
                            };

                            // เพิ่มปุ่มลบเข้าใน td
                            td.appendChild(deleteButton);
                        }

                        // เพิ่ม td เข้าในแถว
                        tr.appendChild(td);

                        // เพิ่มแถวเข้าในตาราง
                        table.appendChild(tr);
                    });
                });

                // เพิ่มตารางเข้า tableContainer
                tableContainer.appendChild(table);
            }
        }

        // function deleteBlock(blockId, defectId) {
        //     // หา block ที่ต้องการใน defectBlock
        //     const targetBlock = defectBlock.find(block => block.id === blockId);
        //     if (targetBlock) {
        //         // หา index ของ defect_info ที่ตรงกับ defectId
        //         const index = targetBlock.defect_info.findIndex(block => block.id === defectId);
        //         if (index !== -1) {
        //             // ลบ block ออกจาก defect_info
        //             targetBlock.defect_info.splice(index, 1);

        //             // อัปเดต ID ใหม่ให้ defect_info (เรียง ID ต่อเนื่อง)
        //             targetBlock.defect_info.forEach((block, idx) => {
        //                 block.id = `wrapper_${blockId}-${idx + 1}`;
        //             });

        //             // Render ใหม่ทั้งข้อความและตาราง
        //             renderTextBlocks(blockId);
        //             renderTableFromLines(blockId);
        //         }
        //     }
        //     updateDataDetail();
        //     console.log('Updated defectBlock:', defectBlock);
        //     console.log('Updated updateDataDetail:', data);
        // }


        function renderTextToTable(words) 
        {
            const lines = [];
            let currentLine = '';
        
            // วนลูปแต่ละคำเพื่อจัดข้อความตามความกว้างที่กำหนด
            words.forEach(word => {
                const testWord = `<span>${word}</span>`;
                testContainer.innerHTML = currentLine + testWord;

                const width = testContainer.getBoundingClientRect().width;

                if (width <= maxWidth) {
                    currentLine += testWord;
                } else {
                    lines.push(currentLine);
                    currentLine = testWord;
                }
            });

            if (currentLine) {
                lines.push(currentLine);
            }

            // ล้าง table เดิมก่อน render ใหม่
            tableContainer.innerHTML = '';

            // สร้าง table จาก lines
            const table = document.createElement('table');
            table.style.width = "800px";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            lines.forEach(line => {
                const tr = document.createElement('tr');
                const td = document.createElement('td');

                // ใส่ข้อความใน td
                td.innerHTML = line;

                // เพิ่ม style ให้ td
                td.style.border = "none";
                td.style.borderBottom = "1px dotted #000";
                td.style.padding = "0";
                td.style.margin = "0";
                td.style.lineHeight = "1.5";
                td.style.fontSize = "20px";

                // เพิ่ม td เข้า tr และเพิ่ม tr เข้า table
                tr.appendChild(td);
                table.appendChild(tr);
            });

            // เพิ่ม table เข้า tableContainer
            tableContainer.appendChild(table);
        }


        // ฟังก์ชันสำหรับการ render ข้อความเป็นตาราง
        function renderTextToTable_(words) 
        {
            const lines = [];
            let currentLine = '';

            // วนลูปแต่ละคำเพื่อจัดข้อความตามความกว้างที่กำหนด
            words.forEach(word => {
                const testWord = `<span>${word}</span>`;
                testContainer.innerHTML = currentLine + testWord;

                const width = testContainer.getBoundingClientRect().width;

                if (width <= maxWidth) {
                    currentLine += testWord;
                } else {
                    lines.push(currentLine);
                    currentLine = testWord;
                }
            });

            if (currentLine) {
                lines.push(currentLine);
            }

            // ล้าง table เดิมก่อน render ใหม่
            tableContainer.innerHTML = '';

            // สร้าง table จาก lines
            const table = document.createElement('table');
            table.style.width = "800px";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            lines.forEach(line => {
                const tr = document.createElement('tr');
                const td = document.createElement('td');

                // ใส่ข้อความใน td
                td.innerHTML = line;

                // เพิ่ม style ให้ td
                td.style.border = "none";
                td.style.borderBottom = "1px dotted #000";
                td.style.padding = "0";
                td.style.margin = "0";
                td.style.lineHeight = "1.5";
                td.style.fontSize = "20px";

                // เพิ่ม td เข้า tr และเพิ่ม tr เข้า table
                tr.appendChild(td);
                table.appendChild(tr);
            });

            // เพิ่ม table เข้า tableContainer
            tableContainer.appendChild(table);
        }


        function deleteBlock(blockId, defectId) {
            // หา block ที่ต้องการใน defectBlock
            const targetBlock = defectBlock.find(block => block.id === blockId);
            if (targetBlock) {
                // หา index ของ defect_info ที่ตรงกับ defectId
                const index = targetBlock.defect_info.findIndex(block => block.id === defectId);
                if (index !== -1) {
                    // ลบ block ออกจาก defect_info
                    targetBlock.defect_info.splice(index, 1);

                    // อัปเดต ID ใหม่ให้ defect_info (เรียง ID ต่อเนื่อง)
                    targetBlock.defect_info.forEach((block, idx) => {
                        block.id = `wrapper_${blockId}-${idx + 1}`;
                    });

                    // Render ใหม่ทั้งข้อความและตาราง

                    findBlockById(blockId,targetBlock.defect_info);

                    renderTextBlocks(blockId);
                    renderTableFromLines(blockId);
                }
            }
            // updateDataDetail();
            console.log('Updated defectBlock:', defectBlock);
            console.log('Updated updateDataDetail:', data);
        }

        
        function findBlockById(blockId, defect_info) {
            // ใช้ find เพื่อค้นหา block
            const block = data.find(item => item.id === blockId);

            if (block) {
                // แทนที่ defect_info ใน issueArray
                block[`${blockId}_issue_found`][`${blockId}_detail`] = defect_info;

                console.log('อัปเดต block:', block);
                console.log('อัปเดต data:', data);
                return block;
            } else {
                console.log(`ไม่พบ block ที่มี id: ${blockId}`);
                return null;
            }
        }


        function renderTextBlocks(blockId) {
            const targetBlock = defectBlock.find(block => block.id === blockId);
            // console.log(targetBlock);
            if (targetBlock) {
                const container = document.getElementById(`wrapper_${blockId}`);
                container.innerHTML = ''; // ล้างข้อมูลเดิมออก

                targetBlock.defect_info.forEach(block => {
                    const div = document.createElement('div');
                    div.id = block.id;
                    div.style.fontFamily = 'Sarabun, sans-serif';
                    div.style.fontSize = '20px';
                    div.textContent = block.raw; // แสดงข้อความดิบ

                    container.appendChild(div);
                });

                container.hidden = true; 
                // container.hidden = targetBlock.defect_info.length === 0; // ซ่อน container หากไม่มีข้อมูล
            }
        }



        function getSelectedAssessmentValues() {
            let selectedValues = [];
            const checkboxes = document.querySelectorAll('input[name="assessment"]:checked');
            checkboxes.forEach((checkbox) => {
                selectedValues.push(checkbox.value);
            });
            return selectedValues;
        }

        function getGeneralInfo()
        {
            const standard = document.querySelector('input[name="standard"]:checked')?.value || "not_followed";
            const centralManagement = document.querySelector('input[name="central_management"]:checked')?.value || "no_answer";
            const qualityPolicy = document.querySelector('input[name="quality_policy"]:checked')?.value || "no_answer";
            const riskAssessment = document.querySelector('input[name="risk_assessment"]:checked')?.value || "no_answer";

            const issuesFound = document.querySelector('input[name="issues_found"]:checked') ? true : false;
            const issuesDetails = document.querySelector('textarea[name="issues_details"]').value;

            // เก็บค่าในรูปแบบ Object
            const formData = {
                standard_followed: standard,
                central_management: centralManagement,
                quality_policy: qualityPolicy,
                risk_assessment: riskAssessment,
                issues_found: issuesFound,
                issues_details: issuesDetails
            };
        }

        $('#btn_draft_submit').on('click', function() {
            submit(1);
        });

        $('#btn_submit').on('click', function() {
            submit(2);
        });

        function submit(submit_type){

            // console.log(notice.report_status);
            // if(notice.report_status == 2 && submit_type == 2)
            // {
            //     if(notice.degree != 4 || notice.degree != 7)
            //     {
            //         alert('ไม่พบรายงานการปิดข้อบกพร้อง โปรดบันทึกเป็นฉบับร่าง') 
            //         return;
            //     }
            // }
            // return;
            data[0]["2_2_assessment_on_site"] = $('#2_2_assessment_on_site').is(':checked');
            data[0]["2_2_assessment_at_tisi"] = $('#2_2_assessment_at_tisi').is(':checked');
            data[0]["2_2_remote_assessment"] = $('#2_2_remote_assessment').is(':checked');
            data[0]["2_2_self_declaration"] = $('#2_2_self_declaration').is(':checked');

            data[1]["2_5_1_structure_compliance"]["value"] = $('#2_5_1_structure_compliance').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_central_management_yes"] = $('#2_5_1_central_management_yes').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_central_management_no"] = $('#2_5_1_central_management_no').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_quality_policy_yes"] = $('#2_5_1_quality_policy_yes').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_quality_policy_no"] = $('#2_5_1_quality_policy_no').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_risk_assessment_yes"] = $('#2_5_1_risk_assessment_yes').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_risk_assessment_no"] = $('#2_5_1_risk_assessment_no').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_other"]["value"] = $('#2_5_1_other').is(':checked');
            data[1]["2_5_1_structure_compliance"]["2_5_1_other"]["2_5_1_text_other1"] = $('#2_5_1_text_other1').val();
            data[1]["2_5_1_structure_compliance"]["2_5_1_other"]["2_5_1_text_other2"] = $('#2_5_1_text_other2').val();
            data[1]["2_5_1_issue_found"]["value"] = $('#2_5_1_issue_found').is(':checked');
            data[1]["2_5_1_issue_found"]["detail"] = [];

            // console.log($('#2_5_4_decision_rule_no').is(':checked'));
            // return;
             
            data[2]["2_5_2_structure_compliance"]["value"] = $('#2_5_2_structure_compliance').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_lab_management"]["value"] = $('#2_5_2_lab_management').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_lab_management"]["2_5_2_lab_management_details"] = $('#2_5_2_lab_management_details').val();
            data[2]["2_5_2_structure_compliance"]["2_5_2_staff_assignment_yes"] = $('#2_5_2_staff_assignment_yes').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_staff_assignment_no"] = $('#2_5_2_staff_assignment_no').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_responsibility_yes"] = $('#2_5_2_responsibility_yes').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_responsibility_no"] = $('#2_5_2_responsibility_no').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_other"]["value"] = $('#2_5_2_other').is(':checked');
            data[2]["2_5_2_structure_compliance"]["2_5_2_other"]["2_5_2_text_other1"] = $('#2_5_2_text_other1').val();
            data[2]["2_5_2_structure_compliance"]["2_5_2_other"]["2_5_2_text_other2"] = $('#2_5_2_text_other2').val();
            data[2]["2_5_2_issue_found"]["value"] = $('#2_5_2_issue_found').is(':checked');
            data[2]["2_5_2_issue_found"]["detail"] = [];

            data[3]["2_5_3_structure_compliance"]["value"] = $('#2_5_3_structure_compliance').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_personnel_qualification_yes"] = $('#2_5_3_personnel_qualification_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_personnel_qualification_no"] = $('#2_5_3_personnel_qualification_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_assign_personnel_appropriately_yes"] = $('#2_5_3_assign_personnel_appropriately_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_assign_personnel_appropriately_no"] = $('#2_5_3_assign_personnel_appropriately_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_training_need_assessment_yes"] = $('#2_5_3_training_need_assessment_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_training_need_assessment_no"] = $('#2_5_3_training_need_assessment_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_facility_and_environment_control_yes"] = $('#2_5_3_facility_and_environment_control_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_facility_and_environment_control_no"] = $('#2_5_3_facility_and_environment_control_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_equipment_maintenance_calibration_yes"] = $('#2_5_3_equipment_maintenance_calibration_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_equipment_maintenance_calibration_no"] = $('#2_5_3_equipment_maintenance_calibration_nos').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_metrology_traceability_yes"] = $('#2_5_3_metrology_traceability_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_metrology_traceability_no"] = $('#2_5_3_metrology_traceability_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_external_product_service_control_yes"] = $('#2_5_3_external_product_service_control_yes').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_external_product_service_control_no"] = $('#2_5_3_external_product_service_control_no').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_other"]["value"] = $('#2_5_3_other').is(':checked');
            data[3]["2_5_3_structure_compliance"]["2_5_3_other"]["2_5_3_text_other1"] = $('#2_5_3_text_other1').val();
            data[3]["2_5_3_structure_compliance"]["2_5_3_other"]["2_5_3_text_other2"] = $('#2_5_3_text_other2').val();
            data[3]["2_5_3_issue_found"]["value"] = $('#2_5_3_issue_found').is(':checked');
            data[3]["2_5_3_issue_found"]["detail"] = [];

            data[4]["2_5_4_structure_compliance"]["value"] = $('#2_5_4_structure_compliance').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_policy_compliance_yes"] =  $('#2_5_4_policy_compliance_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_policy_compliance_no"] =  $('#2_5_4_policy_compliance_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_metrology_sampling_activity_yes"] =  $('#2_5_4_metrology_sampling_activity_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_metrology_sampling_activity_no"] =  $('#2_5_4_metrology_sampling_activity_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_procedure_review_request_yes"] =  $('#2_5_4_procedure_review_request_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_procedure_review_request_no"] =  $('#2_5_4_procedure_review_request_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_yes"]["value"] = $('#2_5_4_decision_rule_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_no"] = $('#2_5_4_decision_rule_no').is(':checked');


            // console.log(data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_no"],$('#2_5_4_decision_rule_no').is(':checked'));

            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_yes"]["2_5_4_agreement_customer_yes"] = $('#2_5_4_agreement_customer_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_yes"]["2_5_4_agreement_customer_no"] = $('#2_5_4_agreement_customer_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_method_verification_yes"] = $('#2_5_4_method_verification_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_method_verification_no"] = $('#2_5_4_method_verification_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_sample_management_yes"] = $('#2_5_4_sample_management_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_sample_management_no"] = $('#2_5_4_sample_management_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_record_management_yes"] = $('#2_5_4_record_management_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_record_management_no"] = $('#2_5_4_record_management_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_uncertainty_evaluation_yes"] = $('#2_5_4_uncertainty_evaluation_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_uncertainty_evaluation_no"] = $('#2_5_4_uncertainty_evaluation_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_result_surveillance_yes"] = $('#2_5_4_result_surveillance_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_result_surveillance_no"] = $('#2_5_4_result_surveillance_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_no"] = $('#2_5_4_proficiency_testing_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["value"] = $('#2_5_4_proficiency_testing_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["value"] = $('#2_5_4_test_participation').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_participation_details1"] = $('#2_5_4_test_participation_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_participation_details2"] = $('#2_5_4_test_participation_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["value"] = $('#2_5_4_test_calibration').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_calibration_details"] = $('#2_5_4_calibration_details').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_yes"] = $('#2_5_4_acceptance_criteria_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["value"] = $('#2_5_4_acceptance_criteria_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["2_5_4_acceptance_criteria1"] = $('#2_5_4_acceptance_criteria1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["2_5_4_acceptance_criteria2"] = $('#2_5_4_acceptance_criteria2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["value"] = $('#2_5_4_lab_comparison').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_details1"] = $('#2_5_4_lab_comparison_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_details2"] = $('#2_5_4_lab_comparison_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["value"] = $('#2_5_4_lab_comparison_test').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_details"] = $('#2_5_4_lab_comparison_test_details').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_yes"] = $('#2_5_4_lab_comparison_test_is_accept_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["value"] = $('#2_5_4_lab_comparison_test_is_accept_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["2_5_4_lab_comparison_test_is_accept_details1"] = $('#2_5_4_lab_comparison_test_is_accept_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["2_5_4_lab_comparison_test_is_accept_details2"] = $('#2_5_4_lab_comparison_test_is_accept_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation2"] = $('#2_5_4_test_participation2').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["value"] = $('#2_5_4_other_methods').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["2_5_4_other_methods_details1"] = $('#2_5_4_other_methods_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["2_5_4_other_methods_details2"] = $('#2_5_4_other_methods_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_report_approval_review_yes"] = $('#2_5_4_report_approval_review_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_report_approval_review_no"] = $('#2_5_4_report_approval_review_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule2_no"] = $('#2_5_4_decision_rule2_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule2_yes"]["value"] = $('#2_5_4_decision_rule2_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule2_yes"]["2_5_4_document_for_criteria_yes"] = $('#2_5_4_document_for_criteria_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule2_yes"]["2_5_4_document_for_criteria_no"] = $('#2_5_4_document_for_criteria_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_complaint_process_no"] = $('#2_5_4_complaint_process_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_complaint_process_yes"]["value"] = $('#2_5_4_complaint_process_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_complaint_process_yes"]["2_5_4_complaint_number"] =  $('#2_5_4_complaint_number').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_non_conformance_process_no"] = $('#2_5_4_non_conformance_process_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_non_conformance_process_yes"]["value"] = $('#2_5_4_non_conformance_process_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_non_conformance_process_yes"]["2_5_4_non_conformance_number"] =  $('#2_5_4_non_conformance_number').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_data_control_yes"] = $('#2_5_4_data_control_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_data_control_no"] = $('#2_5_4_data_control_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_data_transfer_control_yes"] = $('#2_5_4_data_transfer_control_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_data_transfer_control_no"] = $('#2_5_4_data_transfer_control_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["value"] = $('#2_5_4_other').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["2_5_4_text_other1"] = $('#2_5_4_text_other1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["2_5_4_text_other2"] = $('#2_5_4_text_other2').val();
            data[4]["2_5_4_issue_found"]["value"] = $('#2_5_4_issue_found').is(':checked');
            data[4]["2_5_4_issue_found"]["detail"] = [];

            data[5]["2_5_5_structure_compliance"]["value"] = $('#2_5_5_structure_compliance').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_option_a"] = $('#2_5_5_data_control_option_a').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_option_b"] = $('#2_5_5_data_control_option_b').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_policy_yes"] = $('#2_5_5_data_control_policy_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_policy_no"] = $('#2_5_5_data_control_policy_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_document_control_yes"] = $('#2_5_5_document_control_yes').is(':checked');

            data[5]["2_5_5_structure_compliance"]["2_5_5_document_control_no"] = $('#2_5_5_document_control_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_record_keeping_yes"] = $('#2_5_5_record_keeping_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_record_keeping_no"] = $('#2_5_5_record_keeping_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_risk_management_yes"] = $('#2_5_5_risk_management_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_risk_management_no"] = $('#2_5_5_risk_management_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_risk_opportunity_yes"] = $('#2_5_5_risk_opportunity_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_risk_opportunity_no"] = $('#2_5_5_risk_opportunity_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_improvement_opportunity_yes"] = $('#2_5_5_improvement_opportunity_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_improvement_opportunity_no"] = $('#2_5_5_improvement_opportunity_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_non_conformance_yes"] = $('#2_5_5_non_conformance_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_non_conformance_no"] = $('#2_5_5_non_conformance_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_no"] = $('#2_5_5_internal_audit_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_yes"]["value"] = $('#2_5_5_internal_audit_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_yes"]["2_5_5_audit_frequency"] = $('#2_5_5_audit_frequency').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_yes"]["2_5_5_last_audit_date"] = $('#2_5_5_last_audit_date').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_yes"]["2_5_5_audit_issues"] = $('#2_5_5_audit_issues').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_management_review_no"] = $('#2_5_5_management_review_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_management_review_yes"]["value"] = $('#2_5_5_management_review_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_management_review_yes"]["2_5_5_last_review_date"] = $('#2_5_5_last_review_date').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["value"] = $('#2_5_5_other').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["2_5_5_text_other1"] = $('#2_5_5_text_other1').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["2_5_5_text_other2"] = $('#2_5_5_text_other2').val();
            data[5]["2_5_5_issue_found"]["value"] = $('#2_5_5_issue_found').is(':checked');
            data[5]["2_5_5_issue_found"]["detail"] = [];


            // console.log(data[5]["2_5_5_structure_compliance"]["2_5_5_internal_audit_yes"]["value"] ,$('#2_5_5_internal_audit_yes').is(':checked'));


            data[6]["2_5_6_1_1_management_review_no"] = $('#2_5_6_1_1_management_review_no').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["value"] = $('#2_5_6_1_1_management_review_yes').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_scope_certified_no"] = $('#2_5_6_1_1_scope_certified_no').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_scope_certified_yes"]["value"] = $('#2_5_6_1_1_scope_certified_yes').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_scope_certified_yes"]["2_5_6_1_1_activities_not_certified_yes"] = $('#2_5_6_1_1_activities_not_certified_yes').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_scope_certified_yes"]["2_5_6_1_1_activities_not_certified_no"] = $('#2_5_6_1_1_activities_not_certified_no').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_accuracy_yes"] = $('#2_5_6_1_1_accuracy_yes').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_accuracy_no"]["value"] = $('#2_5_6_1_1_accuracy_no').is(':checked');
            data[6]["2_5_6_1_1_management_review_yes"]["2_5_6_1_1_accuracy_no"]["2_5_6_1_1_accuracy_detail"] = $('#2_5_6_1_1_accuracy_detail').val();

            data[7]["2_5_6_1_2_multi_site_display_no"] = $('#2_5_6_1_2_multi_site_display_no').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["value"] = $('#2_5_6_1_2_multi_site_display_yes').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_scope_no"] = $('#2_5_6_1_2_multi_site_scope_no').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_scope_yes"]["value"] = $('#2_5_6_1_2_multi_site_scope_yes').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_scope_yes"]["2_5_6_1_2_multi_site_activities_not_certified_yes"] = $('#2_5_6_1_2_multi_site_activities_not_certified_yes').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_scope_yes"]["2_5_6_1_2_multi_site_activities_not_certified_no"] = $('#2_5_6_1_2_multi_site_activities_not_certified_no').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_accuracy_yes"] = $('#2_5_6_1_2_multi_site_accuracy_yes').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_accuracy_no"]["value"] = $('#2_5_6_1_2_multi_site_accuracy_no').is(':checked');
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_accuracy_no"]["2_5_6_1_2_multi_site_accuracy_details"] = $('#2_5_6_1_2_multi_site_accuracy_details').val();

            data[8]["2_5_6_1_3_certification_status_yes"] = $('#2_5_6_1_3_certification_status_yes').is(':checked');
            data[8]["2_5_6_1_3_certification_status_no"]["value"] = $('#2_5_6_1_3_certification_status_no').is(':checked');
            data[8]["2_5_6_1_3_certification_status_no"]["2_5_6_1_3_certification_status_details"] = $('#2_5_6_1_3_certification_status_details').val();

            data[9]["2_5_6_1_4_display_other_no"] = $('#2_5_6_1_4_display_other_no').is(':checked');
            data[9]["2_5_6_1_4_display_other_yes"]["value"] = $('#2_5_6_1_4_display_other_yes').is(':checked');
            data[9]["2_5_6_1_4_display_other_yes"]["2_5_6_1_4_display_other_details"] = $('#2_5_6_1_4_display_other_details').val();
            data[9]["2_5_6_1_4_display_other_yes"]["2_5_6_1_4_certification_status_yes"] = $('#2_5_6_1_4_certification_status_yes').is(':checked');
            data[9]["2_5_6_1_4_display_other_yes"]["2_5_6_1_4_certification_status_no"]["value"] = $('#2_5_6_1_4_certification_status_no').is(':checked');
            data[9]["2_5_6_1_4_display_other_yes"]["2_5_6_1_4_certification_status_no"]["2_5_6_1_4_certification_status_details"] = $('#2_5_6_1_4_certification_status_details').val();
    
            data[10]["2_5_6_2_lab_availability_yes"] = $('#2_5_6_2_lab_availability_yes').is(':checked');
            data[10]["2_5_6_2_lab_availability_no"] = $('#2_5_6_2_lab_availability_no').is(':checked');

            data[11]["2_5_6_2_1_ilac_mra_display_no"] = $('#2_5_6_2_1_ilac_mra_display_no').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["value"] = $('#2_5_6_2_1_ilac_mra_display_yes').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_scope_no"] = $('#2_5_6_2_1_ilac_mra_scope_no').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_scope_yes"]["value"] = $('#2_5_6_2_1_ilac_mra_scope_yes').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_scope_yes"]["2_5_6_2_1_ilac_mra_disclosure_yes"] = $('#2_5_6_2_1_ilac_mra_disclosure_yes').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_scope_yes"]["2_5_6_2_1_ilac_mra_disclosure_no"] = $('#2_5_6_2_1_ilac_mra_disclosure_no').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_compliance_yes"] = $('#2_5_6_2_1_ilac_mra_compliance_yes').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_compliance_no"]["value"] = $('#2_5_6_2_1_ilac_mra_compliance_no').is(':checked');
            data[11]["2_5_6_2_1_ilac_mra_display_yes"]["2_5_6_2_1_ilac_mra_compliance_no"]["2_5_6_2_1_ilac_mra_compliance_details"] = $('#2_5_6_2_1_ilac_mra_compliance_details').val();
   

            data[12]["2_5_6_2_2_ilac_mra_compliance_no"] = $('#2_5_6_2_2_ilac_mra_compliance_no').is(':checked');
            data[12]["2_5_6_2_2_ilac_mra_compliance_yes"]["value"] = $('#2_5_6_2_2_ilac_mra_compliance_yes').is(':checked');
            data[12]["2_5_6_2_2_ilac_mra_compliance_yes"]["2_5_6_2_2_ilac_mra_compliance_details"] = $('#2_5_6_2_2_ilac_mra_compliance_details').val();
            data[12]["2_5_6_2_2_ilac_mra_compliance_yes"]["2_5_6_2_2_mra_compliance_yes"] = $('#2_5_6_2_2_mra_compliance_yes').is(':checked');
            data[12]["2_5_6_2_2_ilac_mra_compliance_yes"]["2_5_6_2_2_mra_compliance_no"]["value"] = $('#2_5_6_2_2_mra_compliance_no').is(':checked');
            data[12]["2_5_6_2_2_ilac_mra_compliance_yes"]["2_5_6_2_2_mra_compliance_no"]["2_5_6_2_2_mra_compliance_details"] = $('#2_5_6_2_2_mra_compliance_details').val();

            data[13]["3_0_assessment_results"]["value"] = $('#3_0_assessment_results').is(':checked');
            data[13]["3_0_assessment_results"]["3_0_issue_count"] = $('#3_0_issue_count').val();
            data[13]["3_0_assessment_results"]["3_0_remarks_count"] = $('#3_0_remarks_count').val();
            data[13]["3_0_assessment_results"]["3_0_deficiencies_details"] = $('#3_0_deficiencies_details').val();
            data[13]["3_0_assessment_results"]["3_0_deficiency_resolution_date"] = $('#3_0_deficiency_resolution_date').val();
            data[13]["3_0_offer_agreement"] = $('#3_0_offer_agreement').is(':checked');

           
            // แสดงผลข้อมูลที่เลือก
            //    console.log(data[12]);

            //     return;

            // เพิ่ม _token ลงใน object

            signer.forEach(function(item, index) {
                const positionInput = $(`#position-${index + 1}`); // ดึงค่าจาก input
                if (positionInput.length) {
                    item.signer_position = positionInput.val(); // อัปเดต signer_position
                }
            });

            const isComplete = signer.every(item => item.signer_name && item.signer_position);
        

            console.log(signer);
            if (!isComplete) {
                console.warn('กรุณาเลือกผู้ลงนามให้ครบและตำแหน่ง');
                alert('กรุณาเลือกผู้ลงนามให้ครบและตำแหน่ง');
                return;
            }
            


            console.log(signer)

            // return;

            const payload = {
                id:labReportInfo.id,
                data: data,
                persons: persons,
                notice_id:notice.id,
                signer:signer,
                submit_type:submit_type,
                _token: _token
            };

            $('#loadingStatus').show();
            // AJAX
            $.ajax({
                url: "{{ route('save_assessment.update_lab_info') }}",
                method: "POST",
                data: JSON.stringify(payload), // แปลงเป็น JSON
                contentType: 'application/json', // ระบุว่าเป็น JSON
                success: function(response) {
                    console.log('สำเร็จ:', response);
                    // window.location.href = "{{ route('save_assessment.index') }}";
                    const baseUrl = "{{ url('/certify/save_assessment/create') }}";
                    // http://127.0.0.1:8081/certify/save_assessment/create/1856

                    window.location.href = `${baseUrl}/${boardAuditor.id}`;
                },
                error: function(xhr, status, error) {
                    console.error('เกิดข้อผิดพลาด:', error);
                    $('#loadingStatus').hide();
                }
            });

            // return;

            // // ส่งข้อมูลด้วย AJAX
            // $.ajax({
            //     url: "{{ route('bcertify.api.standard') }}", // URL สำหรับส่งข้อมูล
            //     method: "POST", // วิธีส่งข้อมูล
            //     data: {
            //         _token: "{{ csrf_token() }}", // CSRF Token เพื่อความปลอดภัย
            //         id1: id1,
            //         id2: id2,
            //         id3: id3,
            //     },
            //     success: function(response) {
            //         // จัดการเมื่อส่งข้อมูลสำเร็จ
            //         alert("ส่งข้อมูลสำเร็จ");
            //         console.log(response); // แสดงข้อมูลที่ตอบกลับมาใน console
            //     },
            //     error: function(xhr, status, error) {
            //         // จัดการข้อผิดพลาด
            //         alert("เกิดข้อผิดพลาด: " + error);
            //         console.error(xhr.responseText); // แสดงข้อความผิดพลาดใน console
            //     }
            // });
        }

    // let lastChecked = null;

    // function validate_2_5_1_radio(event) {
    //     const standardCheckbox = document.getElementById('2_5_1_structure_compliance');

    //     // ตรวจสอบว่า standardCheckbox ถูกเลือกหรือไม่
    //     if (!standardCheckbox.checked) {
    //         // ยกเลิกการเลือกตัว radio ที่เกิด event
    //         event.target.checked = false;

    //         // แจ้งเตือนผู้ใช้
    //         alert('คุณต้องเลือก Standard Compliance ก่อน');
    //         return;
    //     }

    //     // ตรวจสอบว่าผู้ใช้คลิกซ้ำบน radio เดิมหรือไม่
    //     if (event.target === lastChecked) {
    //         // ยกเลิกการเลือก radio เดิม
    //         event.target.checked = false;
    //         lastChecked = null;
    //     } else {
    //         // บันทึก radio ตัวที่คลิกล่าสุด
    //         lastChecked = event.target;
    //     }
    // }

    // function handleStandardComplianceChange() {
    //     const standardCheckbox = document.getElementById('2_5_1_structure_compliance');

    //     // ถ้า standardCheckbox ถูกยกเลิกการเลือก
    //     if (!standardCheckbox.checked) {
    //         // ยกเลิกการเลือก radio ทั้งหมด
    //         relatedRadios.forEach(id => {
    //             const radio = document.getElementById(id);
    //             if (radio) {
    //                 radio.checked = false;
    //             }
    //         });

    //         // รีเซ็ต lastChecked
    //         lastChecked = null;
    //     }
    // }

    // // รายการ ID ของ radio ที่ต้องตรวจสอบ
    // const relatedRadios = [
    //     '2_5_1_central_management_yes',
    //     '2_5_1_central_management_no',
    //     '2_5_1_quality_policy_yes',
    //     '2_5_1_quality_policy_no',
    //     '2_5_1_risk_assessment_yes',
    //     '2_5_1_risk_assessment_no',
    //     '2_5_1_other'
    // ];

    // // เพิ่ม event listener ให้กับ radio ทั้งหมด
    // relatedRadios.forEach(id => {
    //     const radio = document.getElementById(id);
    //     if (radio) {
    //         radio.addEventListener('click', validate_2_5_1_radio);
    //     }
    // });

    // // เพิ่ม event listener ให้กับ 2_5_1_structure_compliance
    // const standardCheckbox = document.getElementById('2_5_1_structure_compliance');
    // standardCheckbox.addEventListener('change', handleStandardComplianceChange);


    </script>
</body>
</html>
