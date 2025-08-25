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
            <input type="hidden" id="certi_lab_id" value="{{$certi_lab->check->id}}">
            <div class="left-text">คำขอที่ {{$certi_lab->app_no}}</div>
            <div class="right-box">รายงานที่ 2</div>
        </div>
        <div class="header">
            รายงานการตรวจประเมินความสามารถของห้องปฏิบัติการทดสอบ/สอบเทียบ<br>
            ตามมาตรฐานเลขที่ มอก. 17025-2561
        </div>

        <div class="checkbox-section">
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
                    @if ($certi_lab->purpose_type == 2)
                        checked
                    @endif
                    disabled
                >
                การขยาย/ปรับขอบข่ายใบรับรอง
            </label>
            <label>
                <input type="checkbox" 
                    @if ($certi_lab->purpose_type == 3)
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
                    {{$labInformation->address_headquarters}} หมู่ที่ {{$labInformation->headquarters_alley}}

                    @if(\Illuminate\Support\Str::contains($labInformation->headquarters_province, 'กรุงเทพ'))
                        แขวง{{$labInformation->headquarters_district}} เขต{{$labInformation->headquarters_amphur}} {{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}} 
                    @else
                        ตำบล{{$labInformation->headquarters_district}} อำเภอ{{$labInformation->headquarters_amphur}} จังหวัด{{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}}  
                    @endif

                    {{-- {{$labRequest->no}} หมู่ที่ {{$labRequest->moo}} 
                    @if(\Illuminate\Support\Str::contains($labRequest->province_name, 'กรุงเทพ'))
                        แขวง{{$labRequest->tambol_name}} เขต{{$labRequest->amphur_name}} {{$labRequest->postal_code}} 
                    @else
                        ตำบล{{$labRequest->tambol_name}} อำเภอ{{$labRequest->amphur_name}} {{$labRequest->postal_code}} 
                    @endif --}}
                </span>
                
            </div>
        </div>


        {{-- <div><span style="font-weight:bold">ตั้งอยู่เลขที่ :</span> <span> {{$labInformation->address_headquarters}} หมู่ที่ {{$labInformation->headquarters_alley}}
            @if(\Illuminate\Support\Str::contains($labInformation->headquarters_province, 'กรุงเทพ'))
                แขวง{{$labInformation->headquarters_district}} เขต{{$labInformation->headquarters_amphur}} {{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}} 
            @else
                ตำบล{{$labInformation->headquarters_district}} อำเภอ{{$labInformation->headquarters_amphur}} จังหวัด{{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}}  
            @endif</span> </div> --}}

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
                    <label><input type="checkbox"  id="inp_2_2_assessment_on_site_chk" name="inp_2_2_assessment_on_site_chk"> ณ ห้องปฏิบัติการ</label>
                </div>
                
                <!-- แถว 2 -->
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <span style="flex: 1 0 300px;">
                        <label><input type="checkbox" id="inp_2_2_assessment_at_tisi_chk" name="inp_2_2_assessment_at_tisi_chk" > ตรวจประเมิน ณ สมอ. โดยวิธี</label>
                    </span>
                    <span style="flex: 1 0 500px;">
                        <label><input type="checkbox" id="inp_2_2_remote_assessment_chk" name="inp_2_2_remote_assessment_chk"> ตรวจประเมินทางไกล (remote assessment)</label>
                    </span>
                </div>
                
                <!-- แถว 3 -->
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <span style="flex: 1 0 300px;"></span> <!-- ช่องว่าง -->
                    <span style="flex: 1 0 550px;">
                        <label><input type="checkbox" id="inp_2_2_self_declaration_chk" name="inp_2_2_self_declaration_chk"> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</label>
                    </span>
                </div>
                <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                    <span style="flex: 1 0 300px;"></span> <!-- ช่องว่าง -->
                    <span style="flex: 1 0 550px;">
                        <label><input type="checkbox" id="inp_2_2_bug_fix_evidence_chk" name="inp_2_2_bug_fix_evidence_chk"> เอกสารหลักฐานการปฏิบัติการแก้ไขข้อบกพร่อง</label>
                    </span>
                </div>
            </div>
            <div style="margin-top:10px;">
                    <span style="font-weight: 600">2.3 วันที่ตรวจประเมิน :</span>  
                    <span >
                        
{{-- @php
    dd($notice);
@endphp --}}

                    {{HP::formatDateThaiFullPoint($notice->assessment_date)}}
                    
                    
                </span>  



            </div>
            <div style="margin-top:10px;">
                <span style="display:block; font-weight: 600">2.4 ผลการตรวจประเมิน</span> 

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

                {{-- {{ $formattedDate }} --}}

                <span style="display:block;margin-left:30px;">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามผลการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกต จากการตรวจประเมินเมื่อวันที่ {{$formattedDate}} ซึ่งพบข้อบกพร่องและข้อสังเกต จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 100px;text-align:center" name="inp_2_4_defects_and_remarks_text" id="inp_2_4_defects_and_remarks_text"> รายการ 
                    ห้องปฏิบัติการได้นำส่งหลักฐานผลการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกต ตามหนังสือ ({{$certi_lab->lab_name}}) ลงวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 200px;text-align:center" name="inp_2_4_doc_reference_date_text" id="inp_2_4_doc_reference_date_text"> /ไปรษณีย์อิเล็กทรอนิกส์ วันที่ <input type="text" class="input-no-border" placeholder="" style="width: 200px;text-align:center" name="inp_2_4_doc_sent_date1_text" id="inp_2_4_doc_sent_date1_text"> (ถ้ามี) และ <input type="text" class="input-no-border" placeholder="" style="width: 200px;text-align:center" name="inp_2_4_doc_sent_date2_text" id="inp_2_4_doc_sent_date2_text"> (ถ้ามี)</span>
            </div>
            <div style="margin-top:10px;">
                <span style="display:block;margin-left:30px;font-style:italic"> โดยมีรายละเอียดผลการตรวจสอบการปฏิบัติการแก้ไขข้อบกพร่องดังเอกสารแนบ</span>
            </div>


            <div style="margin-top:10px;">
                <span style="display:block;margin-left:30px"> คณะผู้ตรวจประเมินพบว่า</span>
                <table  style="display:block;margin-left:30px">
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="inp_2_4_lab_bug_fix_completed_chk" id="inp_2_4_lab_bug_fix_completed_chk"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน
                            </span>
                        </td>
                    </tr>
                </table>
                <table  style="display:block;margin-left:30px">
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="inp_2_4_fix_approved_chk" id="inp_2_4_fix_approved_chk"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_2_4_approved_text" id="inp_2_4_approved_text"> รายการ ยังคงเหลือข้อบกพร่อง <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_2_4_remain_text" id="inp_2_4_remain_text"> รายการ
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- {{$boardAuditor}} --}}

            <div style="margin-top:10px;">
                <span style="font-weight: 600">3. สรุปผลการตรวจประเมิน :</span>  
                <div style="margin-top:10px;">
                    <span style="display:block;margin-left:30px"> คณะผู้ตรวจประเมินพบว่า</span>
                </div>
                <table  style="display:block;margin-left:30px">
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="inp_3_lab_fix_all_issues_chk" id="inp_3_lab_fix_all_issues_chk"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                {{$certi_lab->lab_name}}ได้ดำเนินการแก้ไขข้อบกพร่องทั้งหมดแล้วอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน สมควรนำเสนอคณะอนุกรรมการพิจารณารับรอง{{$certi_lab->lab_name}} เพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการฯ ตามขอบข่ายที่ขอรับการรับรองต่อไป
                            </span>
                        </td>
                    </tr>
                </table>
                <table  style="display:block;margin-left:30px">
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="inp_3_lab_fix_some_issues_chk" id="inp_3_lab_fix_some_issues_chk"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                {{$certi_lab->lab_name}}ได้ดำเนินการแก้ไขข้อบกพร่องแล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_3_approved_text" id="inp_3_approved_text"> รายการ ยังคงเหลือข้อบกพร่อง <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_3_remain_text" id="inp_3_remain_text"> รายการ ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องให้คณะผู้ตรวจประเมินพิจารณาภายในวันที่ {{HP::formatDateThaiFullPoint($assessment->date_car)}} (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่องครั้งแรก) ณ สำนักงาน หากห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 คณะผู้ตรวจประเมินจะนำเสนอคณะอนุกรรมการพิจารณารับรอง{{$certi_lab->lab_name}} เพื่อพิจารณาให้การรับรองต่อไป
                            </span>
                            <br>
                            <span>
                                กรณีที่พ้นกำหนดระยะเวลาดังกล่าว หากห้องปฏิบัติการ<span><strong><u>ไม่</u></strong></span>สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้ สำนักงานพิจารณายกเลิกคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการของท่านต่อไป
                            </span>
                        </td>
                    </tr>
                </table>
                <table  style="display:block;margin-left:30px">
                    <tr>
                        <td style="vertical-align: top">  
                            <span >
                                <label><input type="checkbox" name="inp_3_lab_fix_failed_issues_chk" id="inp_3_lab_fix_failed_issues_chk"></label>
                            </span>
                        </td>
                        <td>
                            <span >
                                {{$certi_lab->lab_name}}ไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน
                            </span>
                        </td>
                    </tr>
                </table>
                <div style="display:block;margin-left:40px">
                    <table  style="display:block;margin-left:30px">
                        <tr>
                            <td style="vertical-align: top">  
                                <span >
                                    <label><input type="checkbox" name="inp_3_lab_fix_failed_issues_no_chk" id="inp_3_lab_fix_failed_issues_no_chk"></label>
                                </span>
                            </td>
                            <td>
                                <span >
                                    สมควรนำเสนอสำนักงานพิจารณายกเลิกคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการต่อไป
                                </span>
                            </td>
                        </tr>
                        <table  style="display:block;margin-left:30px">
                            <tr>
                                <td style="vertical-align: top">  
                                    <span >
                                        <label><input type="checkbox" name="inp_3_lab_fix_failed_issues_yes_chk" id="inp_3_lab_fix_failed_issues_yes_chk"></label>
                                    </span>
                                </td>
                                <td>
                                    <span >
                                        สมควรนำเสนอคณะอนุกรรมการพิจารณารับรอง{{$certi_lab->lab_name}} เพื่อพิจารณาไม่ต่ออายุใบรับรอง (กรณีคำขอต่ออายุ)
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </table>
                </div>
            </div>

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



            {{-- {{$signAssessmentReportTransactions->count()}} --}}
            @if ($signAssessmentReportTransactions->count() == 0)
                <div style="text-align: center;margin-bottom:20px;margin-top:20px" id="button_wrapper">

                    <button  type="button" id="btn_draft_submit" class="btn btn-red" >ฉบับร่าง</button>
                    <button  type="button" id="btn_submit" class="btn btn-green" >ส่งข้อมูล</button>
                    
                </div>

            @endif



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
        let totalPendingTransactions;
        let totalTransactions;
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

            totalPendingTransactions = @json($totalPendingTransactions ?? null);
            totalTransactions = @json($totalTransactions ?? null);
              defaultSignerIds = @json($defaultSignerIds ?? []);

            console.log('notice',notice)

            console.log('totalPendingTransactions',signAssessmentReportTransactions.length);

           if(signAssessmentReportTransactions.length != 0){
                 $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
                 $('.wrapper').css('pointer-events', 'none'); // ปิดการคลิกและโต้ตอบทุกอย่างใน div.wrapper
                 $('.wrapper').css('opacity', '0.7'); // เพิ่มความโปร่งใสเพื่อแสดงว่าถูกปิดใช้งาน (ไม่บังคับ)
           }

        if(totalTransactions !== null)
        {
            if(totalTransactions != 0 && totalPendingTransactions == 0)
            {
                $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
                $('.wrapper').css({
                    'pointer-events': 'none', // ปิดการคลิกทั้งหมด
                    'opacity': '0.8' // เพิ่มความโปร่งใส
                });
                $('#files_wrapper').css('pointer-events', 'auto');
                $('.wrapper button').not('#files_wrapper button').hide();
            }
        }
        // console.log('signAssessmentReportTransactions',signAssessmentReportTransactions)

        // if(totalPendingTransactions != null)
        // {
        //     if (totalPendingTransactions == 0) {
        //         $('#button_wrapper').hide(); // ซ่อน div ด้วย jQuery
        //         $('.wrapper').css({
        //             'pointer-events': 'none', // ปิดการคลิกทั้งหมด
        //             'opacity': '0.8' // เพิ่มความโปร่งใส
        //         });
        //         $('#files_wrapper').css('pointer-events', 'auto');
        //         $('.wrapper button').not('#files_wrapper button').hide();
        //     }
        // }


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

            // console.log('signAssessmentReportTransactions',signAssessmentReportTransactions)

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
                    "inp_2_2_assessment_on_site_chk": labReportInfo.inp_2_2_assessment_on_site_chk === "1",
                    "inp_2_2_assessment_at_tisi_chk": labReportInfo.inp_2_2_assessment_at_tisi_chk === "1",
                    "inp_2_2_remote_assessment_chk": labReportInfo.inp_2_2_remote_assessment_chk === "1",
                    "inp_2_2_self_declaration_chk": labReportInfo.inp_2_2_self_declaration_chk === "1",
                    "inp_2_2_bug_fix_evidence_chk": labReportInfo.inp_2_2_bug_fix_evidence_chk === "1",
                },
                {
                    "id": "2_4",
                    "inp_2_4_defects_and_remarks_text": labReportInfo.inp_2_4_defects_and_remarks_text,
                    "inp_2_4_doc_reference_date_text": labReportInfo.inp_2_4_doc_reference_date_text,
                    "inp_2_4_doc_sent_date1_text": labReportInfo.inp_2_4_doc_sent_date1_text,
                    "inp_2_4_doc_sent_date2_text": labReportInfo.inp_2_4_doc_sent_date2_text,
                    "inp_2_4_lab_bug_fix_completed_chk": labReportInfo.inp_2_4_lab_bug_fix_completed_chk === "1",
                    "inp_2_4_fix_approved_chk": labReportInfo.inp_2_4_fix_approved_chk === "1",
                    "inp_2_4_approved_text": labReportInfo.inp_2_4_approved_text,
                    "inp_2_4_remain_text": labReportInfo.inp_2_4_remain_text,
                },
                {
                    "id": "3",
                    "inp_3_lab_fix_all_issues_chk": labReportInfo.inp_3_lab_fix_all_issues_chk === "1",
                    "inp_3_lab_fix_some_issues_chk": labReportInfo.inp_3_lab_fix_some_issues_chk === "1",
                    "inp_3_approved_text": labReportInfo.inp_3_approved_text,
                    "inp_3_remain_text": labReportInfo.inp_3_remain_text,
                    "inp_3_lab_fix_failed_issues_chk": {
                        "value": labReportInfo.inp_3_lab_fix_failed_issues_chk === "1",
                        "inp_3_lab_fix_failed_issues_yes_chk": labReportInfo.inp_3_lab_fix_failed_issues_yes_chk === "1",
                        "inp_3_lab_fix_failed_issues_no_chk": labReportInfo.inp_3_lab_fix_failed_issues_no_chk === "1",
                    }
                }
            ];

            // console.log('data',data)

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
            // render 2.2
            $('#inp_2_2_assessment_on_site_chk').prop('checked', labReportInfo.inp_2_2_assessment_on_site_chk === "1");
            $('#inp_2_2_assessment_at_tisi_chk').prop('checked', labReportInfo.inp_2_2_assessment_at_tisi_chk === "1");
            $('#inp_2_2_remote_assessment_chk').prop('checked', labReportInfo.inp_2_2_remote_assessment_chk === "1");
            $('#inp_2_2_self_declaration_chk').prop('checked', labReportInfo.inp_2_2_self_declaration_chk === "1");
            $('#inp_2_2_bug_fix_evidence_chk').prop('checked', labReportInfo.inp_2_2_bug_fix_evidence_chk === "1");

            // render 2.4
            $('#inp_2_4_defects_and_remarks_text').val(labReportInfo.inp_2_4_defects_and_remarks_text || '');
            $('#inp_2_4_doc_reference_date_text').val(labReportInfo.inp_2_4_doc_reference_date_text || '');
            $('#inp_2_4_doc_sent_date1_text').val(labReportInfo.inp_2_4_doc_sent_date1_text || '');
            $('#inp_2_4_doc_sent_date2_text').val(labReportInfo.inp_2_4_doc_sent_date2_text || '');
            $('#inp_2_4_lab_bug_fix_completed_chk').prop('checked', labReportInfo.inp_2_4_lab_bug_fix_completed_chk === "1");
            $('#inp_2_4_fix_approved_chk').prop('checked', labReportInfo.inp_2_4_fix_approved_chk === "1");
            $('#inp_2_4_approved_text').val(labReportInfo.inp_2_4_approved_text || '');
            $('#inp_2_4_remain_text').val(labReportInfo.inp_2_4_remain_text || '');

            // render 3
            $('#inp_3_lab_fix_all_issues_chk').prop('checked', labReportInfo.inp_3_lab_fix_all_issues_chk === "1");
            $('#inp_3_lab_fix_some_issues_chk').prop('checked', labReportInfo.inp_3_lab_fix_some_issues_chk === "1");
            $('#inp_3_approved_text').val(labReportInfo.inp_3_approved_text || '');
            $('#inp_3_remain_text').val(labReportInfo.inp_3_remain_text || '');
            $('#inp_3_lab_fix_failed_issues_chk').prop('checked', labReportInfo.inp_3_lab_fix_failed_issues_chk === "1");
            $('#inp_3_lab_fix_failed_issues_yes_chk').prop('checked', labReportInfo.inp_3_lab_fix_failed_issues_yes_chk === "1");
            $('#inp_3_lab_fix_failed_issues_no_chk').prop('checked', labReportInfo.inp_3_lab_fix_failed_issues_no_chk === "1");

        });

        
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
                        // response.signers.forEach(function(signer) {
                        //     const option = `<option value="${signer.id}">${signer.name}</option>`;
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

            // get value 2.2
            data[0]["inp_2_2_assessment_on_site_chk"] = $('#inp_2_2_assessment_on_site_chk').is(':checked');
            data[0]["inp_2_2_assessment_at_tisi_chk"] = $('#inp_2_2_assessment_at_tisi_chk').is(':checked');
            data[0]["inp_2_2_remote_assessment_chk"] = $('#inp_2_2_remote_assessment_chk').is(':checked');
            data[0]["inp_2_2_self_declaration_chk"] = $('#inp_2_2_self_declaration_chk').is(':checked');
            data[0]["inp_2_2_bug_fix_evidence_chk"] = $('#inp_2_2_bug_fix_evidence_chk').is(':checked');

            // get value 2.4
            data[1]["inp_2_4_defects_and_remarks_text"]= $('#inp_2_4_defects_and_remarks_text').val();
            data[1]["inp_2_4_doc_reference_date_text"]= $('#inp_2_4_doc_reference_date_text').val();
            data[1]["inp_2_4_doc_sent_date1_text"]= $('#inp_2_4_doc_sent_date1_text').val();
            data[1]["inp_2_4_doc_sent_date2_text"]= $('#inp_2_4_doc_sent_date2_text').val();
            data[1]["inp_2_4_lab_bug_fix_completed_chk"] = $('#inp_2_4_lab_bug_fix_completed_chk').is(':checked');
            data[1]["inp_2_4_fix_approved_chk"] = $('#inp_2_4_fix_approved_chk').is(':checked');
            data[1]["inp_2_4_approved_text"]= $('#inp_2_4_approved_text').val();
            data[1]["inp_2_4_remain_text"]= $('#inp_2_4_remain_text').val();

            // get value 3.0
            data[2]["inp_3_lab_fix_all_issues_chk"] = $('#inp_3_lab_fix_all_issues_chk').is(':checked');
            data[2]["inp_3_lab_fix_some_issues_chk"] = $('#inp_3_lab_fix_some_issues_chk').is(':checked');
            data[2]["inp_3_approved_text"]= $('#inp_3_approved_text').val();
            data[2]["inp_3_remain_text"]= $('#inp_3_remain_text').val();
            data[2]["inp_3_lab_fix_failed_issues_chk"]["value"] = $('#inp_3_lab_fix_failed_issues_chk').is(':checked');
            data[2]["inp_3_lab_fix_failed_issues_chk"]["inp_3_lab_fix_failed_issues_yes_chk"] = $('#inp_3_lab_fix_failed_issues_yes_chk').is(':checked');
            data[2]["inp_3_lab_fix_failed_issues_chk"]["inp_3_lab_fix_failed_issues_no_chk"] = $('#inp_3_lab_fix_failed_issues_no_chk').is(':checked');


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
                url: "{{ route('save_assessment.update_lab_report2_info') }}",
                method: "POST",
                data: JSON.stringify(payload), // แปลงเป็น JSON
                contentType: 'application/json', // ระบุว่าเป็น JSON
                success: function(response) {
                   

                    // const baseUrl = "{{ url('/certify/check_certificate') }}";

                    // window.location.href = `${baseUrl}/${notice.id}/assess_edit/${notice.app_certi_lab_id}`;
                    // console.log('สำเร็จ:', `${baseUrl}/${notice.id}/assess_edit/${notice.app_certi_lab_id}`);
                    const baseUrl = "{{ url('/certify/check_certificate') }}";
                    window.location.href = `${baseUrl}/${$('#certi_lab_id').val()}/show`;

                    
                },
                error: function(xhr, status, error) {
                    console.error('เกิดข้อผิดพลาด:', error);
                    $('#loadingStatus').hide();
                }
            });


        }

    </script>
</body>
</html>
