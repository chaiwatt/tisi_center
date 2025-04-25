<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>รายงานการตรวจประเมิน</title>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            max-width: 800px;
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

    </style>
</head>
<body>
    <div id="modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid black; z-index:1000">
        <h4>ผลการตรวจประเมิน: (1) ข้อกำหนดทั่วไป</h4>
            <textarea id="modal-input" style="width: 720px; height: 500px; resize: none; ;font-family: 'Sarabun'; font-size: 20px;"></textarea>
        </textarea>
        <br>
        <button onclick="addTextBlock()" class="btn btn-green">เพิ่ม</button>
        <button onclick="closeModal()" class="btn btn-red">ยกเลิก</button>
    </div>
    <div class="wrapper">
        <div class="container">
            <div class="left-text">คำขอที่ TEST-1234</div>
            <div class="right-box">รายงานที่ 1</div>
        </div>
        <div class="header">
            รายงานการตรวจประเมินความสามารถของห้องปฏิบัติการทดสอบ/สอบเทียบ<br>
            ตามมาตรฐานเลขที่ มอก. 17025-2561
        </div>

        <div class="checkbox-section">
            <label><input type="checkbox"> การขอรับใบรับรองใหม่</label>
            <label><input type="checkbox"> การขยาย/ปรับขอบข่ายใบรับรอง</label>
            <label><input type="checkbox"> ต่ออายุใบรับรอง</label>

            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 5px;">
                <span>
                    <label><input type="checkbox">อื่น ๆ</label>
                </span>
                <span>
                    <input type="text" class="input-no-border" placeholder="" style="width:500px">
                </span>
            </div>
        </div>

       <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
        <span>1. ข้อมูลทั่วไป</span>
       </div>
        <div style="margin-top: 10px;margin-left:20px; line-height:36px">
            <div>
                <span>ชื่อห้องปฏิบัติการ :</span>
                <span>ห้องปฏิบัติการทดสอบ</span>
            </div>
            <div>
                <span>ตั้งอยู่เลขที่ :</span>
                <span>105 หมู่ที่ 8</span>
            </div>
            <div>
                <span>วันที่ยื่นคำขอ :</span>
                <span>10 ธันวาคม 2567</span>
            </div>
            <div>
                <span>สาขาและขอบข่าย :</span>
                <span>ตามเอกสารประกอบคำขอของ ห้องปฏิบัติการ ลงวันที่ 10 ธันวาคม 2567 และ/หรือหนังสือขอแก้ไขขอบข่ายของ ห้องปฏิบัติการ ลงวันที่ 10 ธันวาคม 2567 (ถ้ามี)/ ขอบข่าย ดังแนบ</span>
            </div>
        </div>

        <div style="margin-top: 15px;margin-left:10px; line-height:36px;font-weight:600">
            <span>2. การตรวจประเมิน</span>
        </div>
        <div style="margin-left:20px; line-height:36px">
            <div>
                <div><span style="font-weight: 600">2.1 คณะผู้ตรวจประเมิน</span> ประกอบด้วย</div>
            </div>
            <div style="margin-left: 25px">
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">1.</span>
                    <span style="flex: 1 0 150px;">นายจอร์น วิลเลียม</span>
                    <span style="flex: 1 0 200px;">หัวหน้าคณะผู้ตรวจประเมิน</span>
                    <span style="flex: 1 0 200px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">2.</span>
                    <span style="flex: 1 0 150px;">อนุธิน วิลเลียม</span>
                    <span style="flex: 1 0 200px;">หัวหน้าคณะผู้ตรวจประเมิน</span>
                    <span style="flex: 1 0 200px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">3.</span>
                    <span style="flex: 1 0 150px;">สมาน วิลเลียม</span>
                    <span style="flex: 1 0 200px;">หัวหน้าคณะผู้ตรวจประเมิน</span>
                    <span style="flex: 1 0 200px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
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
                    <span style="flex: 1 0 450px;">
                        <label><input type="checkbox" id="2_2_self_declaration" name="2_2_self_declaration"> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</label>
                    </span>
                </div>
            </div>
            <div style="margin-top:10px;">
                    <span style="font-weight: 600">2.3 วันที่ตรวจประเมิน :</span>  
                    <span >10 มกราคม 2568</span>  
            </div>
            <div style="margin-top:10px;">
                <span style="font-weight: 600">2.4 บุคคลที่พบ :</span>
            </div>
            <div style="margin-left: 25px">
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">1.</span>
                    <span style="flex: 1 0 150px;">นายจอร์น วิลเลียม</span>
                    <span style="flex: 1 0 50px;">ตำแหน่ง</span>
                    <span style="flex: 1 0 300px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">2.</span>
                    <span style="flex: 1 0 150px;">อนุธิน วิลเลียม</span>
                    <span style="flex: 1 0 50px;">ตำแหน่ง</span>
                    <span style="flex: 1 0 300px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <span style="flex: 0 0 20px;">3.</span>
                    <span style="flex: 1 0 150px;">สมาน วิลเลียม</span>
                    <span style="flex: 1 0 50px;">ตำแหน่ง</span>
                    <span style="flex: 1 0 300px;">ผู้ทรงคุณวุฒิ/หน่วยงาน</span>
                </div>
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
            <label><input type="checkbox" id="standard_17025_compliance" name="standard_compliance" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 4.1 – 4.2 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมีประสิทธิผล ดังนี้</label>
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
                        <label><input type="checkbox" id="2_5_1_other" name="2_5_1_other" value="other"></label>
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
                    <label><input type="checkbox" name="2_5_1_issue_found" id="2_5_1_issue_found" value="found">พบว่า</label>
                </span>
                {{-- <span style="flex: 1 0 auto;">
                    <input type="text" class="input-no-border" placeholder="">
                </span> --}}
            </div>
            <button onclick="showModal()" class="btn btn-green" data-id="2_5_1">เพิ่มรายละเอียด</button>
            <div id="wrapper_2_5_1" style="width: 720px;" hidden>
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
                <button onclick="showModal()" class="btn btn-green" data-id="2_5_2">เพิ่มรายละเอียด</button>
                <div id="text-block-twofivetwo" style="width: 720px;" hidden>
                </div>
    
                <div id="test-container-twofivetwo" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table-container-twofivetwo"></div>
    
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
                <button onclick="showModal()" class="btn btn-green" data-id="2_5_3">เพิ่มรายละเอียด</button>
                <div id="text-block-twofivethree" style="width: 720px;" hidden>
                </div>
    
                <div id="test-container-twofivethree" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table-container-twofivethree"></div>
    
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
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_4_sampling_activity" id="2_5_4_metrology_sampling_activity_yes" >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_4_sampling_activity" id="2_5_4_metrology_sampling_activity_yes" >ไม่มี</label></span>
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
                <button onclick="showModal()" class="btn btn-green" data-id="2_5_4">เพิ่มรายละเอียด</button>
                <div id="text-block-twofivefour" style="width: 720px;" hidden>
                </div>
    
                <div id="test-container-twofivefour" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table-container-twofivefour"></div>
    
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
                    <span style="flex: 1 0 5px;"><label><input type="radio" name="2_5_5_data_document_control" id="2_5_5_document_control_yes"  >มี</label></span>
                    <span style="flex: 1 0 10px;"><label><input type="radio" name="2_5_5_data_document_control" id="2_5_5_document_control_no" >ไม่มี</label></span>
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
                <button onclick="showModal()" class="btn btn-green" data-id="2_5_5">เพิ่มรายละเอียด</button>
                <div id="text-block-twofivefive" style="width: 720px;" hidden>
                </div>
    
                <div id="test-container-twofivefive" style="visibility: hidden; position: absolute; white-space: nowrap;"></div>
                <div id="table-container-twofivefive"></div>
    
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
                                พบข้อบกพร่อง จำนวน<input type="text" class="input-no-border" placeholder="" style="width: 100px;text-align:center" name="3_0_issue_count" id="3_0_issue_count">รายการ และข้อสังเกต จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 100px;text-align:center" name="3_0_remarks_count" id="3_0_remarks_count">  พบข้อบกพร่อง จำนวน<input type="text" class="input-no-border" placeholder="" style="width: 50px;text-align:center" name="3_0_deficiencies_details" id="3_0_deficiencies_details">รายการ ดังสำเนารายงานข้อบกพร่องที่แนบ 
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="margin-left: 30px">
                    <span>ห้องปฏิบัติการต้องส่งแนวทาง และแผนการดำเนินการปฏิบัติการแก้ไขข้อบกพร่อง ให้สำนักงานพิจารณาภายใน 30 วันนับแต่วันที่ออกรายงานข้อบกพร่อง และต้องส่งหลักฐานการแก้ไขข้อบกพร่องอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน ภายในวันที่ <input type="text" class="input-no-border" placeholder="" style="width: 180px;text-align:center" name="3_0_deficiency_resolution_date" id="3_0_deficiency_resolution_date"> (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่อง) 
                        หากพ้นกำหนดระยะเวลาดังกล่าว ห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้สำนักงานพิจารณายกเลิกคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการของท่านต่อไป
                        กรณีห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 ภายในระยะเวลาที่กำหนด คณะผู้ตรวจประเมินจะนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ XXXXXX เพื่อพิจารณาให้การรับรองต่อไป
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
                                ห้องปฏิบัติการมีระบบการบริหารงานและการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก. 17025-2561 ในขอบข่ายที่ขอรับการรับรอง คณะผู้ตรวจประเมินเห็นควรนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ XXXXXX. เพื่อพิจารณาให้การรับรองต่อไป 
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="text-align: center;margin-bottom:20px;margin-top:20px">
                <button  type="button" id="btn_submit" class="btn btn-green" >บันทึก</button>
            </div>
            
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const maxWidth = 720;
        const testContainer = document.getElementById('data_container_2_5_1');
        const tableContainer = document.getElementById('table_container_2_5_1');
        // if ($('#2_2_assessment_on_site').is(':checked')) {
        //         selectedValues.push('ณ ห้องปฏิบัติการ');
        //     }
        //     if ($('#2_2_assessment_at_tisi').is(':checked')) {
        //         selectedValues.push('ตรวจประเมิน ณ สมอ. โดยวิธี');
        //     }
        //     if ($('#2_2_remote_assessment').is(':checked')) {
        //         selectedValues.push('ตรวจประเมินทางไกล (remote assessment)');
        //     }
        //     if ($('#2_2_self_declaration').is(':checked')) {
        //         selectedValues.push('เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)');
        //     }

 
        $(function () {
            let lastChecked = null;

            $('input[type="radio"]').on('click', function () {
                if (this === lastChecked) {
                    // ถ้าเป็นตัวเดียวกันที่คลิกซ้ำ ให้ยกเลิกการเลือก
                    $(this).prop('checked', false);
                    lastChecked = null;
                } else {
                    // บันทึกตัวที่เลือกล่าสุด
                    lastChecked = this;
                }
            });
        });


        const data = [
            {
                "id": "2_2",
                "2_2_assessment_on_site": false,
                "2_2_assessment_at_tisi": false,
                "2_2_remote_assessment": false,
                "2_2_self_declaration": false
            },
            {
                "id": "2_5_1",
                "standard_17025_compliance": {
                    "value": false,
                    "2_5_1_central_management_yes": false,
                    "2_5_1_central_management_no": false,
                    "2_5_1_quality_policy_yes": false,
                    "2_5_1_quality_policy_no": false,
                    "2_5_1_risk_assessment_yes": false,
                    "2_5_1_risk_assessment_no": false,
                    "2_5_1_other": {
                        "value": false,
                        "2_5_1_text_other1": "",
                        "2_5_1_text_other2": ""
                    }
                },
                "2_5_1_issue_found": {
                    "value": false,
                    "2_5_1_detail": []
                }
            },
            {
                "id": "2_5_2",
                "2_5_2_structure_compliance": {
                    "value": false,
                    "2_5_2_lab_management": {
                        "value": false,
                        "2_5_2_lab_management_details": ""
                    },
                    "2_5_2_staff_assignment_yes": false,
                    "2_5_2_staff_assignment_no": false,
                    "2_5_2_responsibility_yes": false,
                    "2_5_2_responsibility_no": false,
                    "2_5_2_other": {
                        "value": false,
                        "2_5_2_text_other1": "",
                        "2_5_2_text_other2": ""
                    }
                },
                "2_5_2_issue_found": {
                    "value": false,
                    "2_5_2_detail": []
                }
            },
            {
                "id": "2_5_3",
                "2_5_3_structure_compliance": {
                    "value": false,
                    "2_5_3_personnel_qualification_yes": false,
                    "2_5_3_personnel_qualification_no": false,
                    "2_5_3_assign_personnel_appropriately_yes": false,
                    "2_5_3_assign_personnel_appropriately_no": false,
                    "2_5_3_training_need_assessment_yes": false,
                    "2_5_3_training_need_assessment_no": false,
                    "2_5_3_facility_and_environment_control_yes": false,
                    "2_5_3_facility_and_environment_control_no": false,
                    "2_5_3_equipment_maintenance_calibration_yes": false,
                    "2_5_3_equipment_maintenance_calibration_no": false,
                    "2_5_3_metrology_traceability_yes": false,
                    "2_5_3_metrology_traceability_no": false,
                    "2_5_3_external_product_service_control_yes": false,
                    "2_5_3_external_product_service_control_no": false,
                    "2_5_3_other": {
                        "value": false,
                        "2_5_3_text_other1": "",
                        "2_5_3_text_other2": ""
                    }
                },
                "2_5_3_issue_found": {
                    "value": false,
                    "2_5_3_detail": []
                }
            },
            {
                "id": "2_5_4",
                "2_5_4_structure_compliance": {
                    "value": false,
                    "2_5_4_policy_compliance_yes": false,
                    "2_5_4_policy_compliance_no": false,
                    "2_5_4_sampling_activity_yes": false,
                    "2_5_4_sampling_activity_no": false,
                    "2_5_4_procedure_review_request_yes": false,
                    "2_5_4_procedure_review_request_no": false,
                    "2_5_4_decision_rule_yes": {
                        "value": false,
                        "2_5_4_agreement_customer_yes": false,
                        "2_5_4_agreement_customer_no": false
                    },
                    "2_5_4_decision_rule_no": false,

                    "2_5_4_method_verification_yes": false,
                    "2_5_4_method_verification_no": false,
                    "2_5_4_sample_management_yes": false,
                    "2_5_4_sample_management_no": false,
                    "2_5_4_record_management_yes": false,
                    "2_5_4_record_management_no": false,
                    "2_5_4_uncertainty_evaluation_yes": false,
                    "2_5_4_uncertainty_evaluation_no": false,
                    "2_5_4_result_surveillance_yes": false,
                    "2_5_4_result_surveillance_no": false,

                    "2_5_4_proficiency_testing_no": false,
                    "2_5_4_proficiency_testing_yes": {
                        "value": false,
                        "2_5_4_test_participation": {
                            "value": false,
                            "2_5_4_test_participation_details1": "",
                            "2_5_4_test_participation_details2": "",
                            "2_5_4_test_calibration": {
                                "value": false,
                                "2_5_4_calibration_details": "",
                                "2_5_4_acceptance_criteria_yes": false,
                                "2_5_4_acceptance_criteria_no": {
                                    "value": false,
                                    "2_5_4_acceptance_criteria1": "",
                                    "2_5_4_acceptance_criteria2": ""
                                }
                            }
                        },
                        "2_5_4_lab_comparison": {
                            "value": false,
                            "2_5_4_lab_comparison_details1": "",
                            "2_5_4_lab_comparison_details2": "",
                            "2_5_4_lab_comparison_test": {
                                "value": false,
                                "2_5_4_lab_comparison_test_details":"",
                                "2_5_4_lab_comparison_test_is_accept_yes": false,
                                "2_5_4_lab_comparison_test_is_accept_no": {
                                    "value": false,
                                    "2_5_4_lab_comparison_test_is_accept_details1": "",
                                    "2_5_4_lab_comparison_test_is_accept_details2": ""
                                }
                            },
                        },
                        "2_5_4_test_participation2": false,
                        "2_5_4_other_methods": {
                            "value": false,
                            "2_5_4_other_methods_details1": "",
                            "2_5_4_other_methods_details2": ""
                        }
                    },
                    "2_5_4_report_approval_review": false,
                    "2_5_4_decision_rule2_no": false,
                    "2_5_4_decision_rule2_yes": {
                        "value": false,
                        "2_5_4_document_for_criteria_yes": false,
                        "2_5_4_document_for_criteria_no": false
                    },
                    "2_5_4_complaint_process_no": false,
                    "2_5_4_complaint_process_yes": {
                        "value": false,
                        "2_5_4_complaint_number": ""
                    },
                    "2_5_4_non_conformance_process_no": false,
                    "2_5_4_non_conformance_process_yes": {
                        "value": false,
                        "2_5_4_non_conformance_number": ""
                    },
                    "2_5_4_data_control_yes": false,
                    "2_5_4_data_control_no": false,
                    "2_5_4_data_transfer_control_yes": false,
                    "2_5_4_data_transfer_control_no": false,
                    "2_5_4_other": {
                        "value": false,
                        "2_5_4_text_other1": "",
                        "2_5_4_text_other2": ""
                    }
                },
                "2_5_4_issue_found": {
                    "value": false,
                    "2_5_4_detail": []
                }
            },
            {
                "id": "2_5_5",
                "2_5_5_structure_compliance": {
                    "value": false,
                    "2_5_5_data_control_option_a": false,
                    "2_5_5_data_control_option_b": false,

                    "2_5_5_data_control_policy_yes": false,
                    "2_5_5_data_control_policy_no": false,
                    "2_5_5_data_document_control_yes": false,
                    "2_5_5_data_document_control_no": false,
                    "2_5_5_record_keeping_yes": false,
                    "2_5_5_record_keeping_no": false,
                    "2_5_5_risk_management_yes": false,
                    "2_5_5_risk_management_no": false,
                    "2_5_5_risk_opportunity_yes": false,
                    "2_5_5_risk_opportunity_no": false,                   

                    "2_5_5_improvement_opportunity_yes": false,
                    "2_5_5_improvement_opportunity_no": false,
                    "2_5_5_non_conformance_yes": false,
                    "2_5_5_non_conformance_no": false,

                    "2_5_5_internal_audit_no": false,
                    "2_5_5_internal_audit_yes": {
                        "value": false,
                        "2_5_5_audit_frequency": "",
                        "2_5_5_last_audit_date": "",
                        "2_5_5_audit_issues": ""
                    },
                    "2_5_5_management_review_no": false,
                    "2_5_5_management_review_yes": {
                        "value": false,
                        "2_5_5_last_review_date": ""
                    },
                    "2_5_5_other": {
                        "value": false,
                        "2_5_5_text_other1": "",
                        "2_5_5_text_other2": ""
                    }
                },
                "2_5_5_issue_found": {
                    "value": false,
                    "2_5_5_detail": []
                }
            },
            {
                "id": "2_5_6_1_1",
                "2_5_6_1_1_management_review_no": false,
                "2_5_6_1_1_management_review_yes": {
                    "value": false,
                    "2_5_6_1_1_scope_certified_no":false,
                    "2_5_6_1_1_scope_certified_yes":{
                        "value":false,
                        "2_5_6_1_1_activities_not_certified_yes":false,
                        "2_5_6_1_1_activities_not_certified_no":false,
                    },
                    "2_5_6_1_1_accuracy_yes":false,
                    "2_5_6_1_1_accuracy_no":{
                        "value": false,
                        "2_5_6_1_1_accuracy_detail": ""
                    },
                }  
            },
            {
                "id": "2_5_6_1_2",
                "2_5_6_1_2_multi_site_display_no": false,
                "2_5_6_1_2_multi_site_display_yes": {
                    "value": false,
                    "2_5_6_1_2_multi_site_scope_no":false,
                    "2_5_6_1_2_multi_site_scope_yes":{
                        "value":false,
                        "2_5_6_1_2_multi_site_activities_not_certified_yes":false,
                        "2_5_6_1_2_multi_site_activities_not_certified_no":false,
                    },
                    "2_5_6_1_2_multi_site_accuracy_yes":false,
                    "2_5_6_1_2_multi_site_accuracy_no":{
                        "value": false,
                        "2_5_6_1_3_certification_status_details": ""
                    },
                }    
            },
            {
                "id": "2_5_6_1_3",
                "2_5_6_1_3_certification_status_yes": false,
                "2_5_6_1_3_certification_status_no": {
                    "value": false,
                    "2_5_6_1_3_certification_status_details":""
                }     
            },
            {
                "id": "2_5_6_1_4",
                "2_5_6_1_4_display_other_no": false,
                "2_5_6_1_4_display_other_yes": {
                    "value": false,
                    "2_5_6_1_4_display_other_details":"",
                    "2_5_6_1_4_certification_status_yes":false,
                    "2_5_6_1_4_certification_status_no":{
                        "value": false,
                        "2_5_6_1_4_certification_status_details":""
                    }
                }     
            },
            {
                "id": "2_5_6_2",
                "2_5_6_2_lab_availability_yes": false,    
                "2_5_6_2_lab_availability_no": false, 
            },
            {
                "id": "2_5_6_2_1",
                "2_5_6_2_1_ilac_mra_display_no": false,
                "2_5_6_2_1_ilac_mra_display_yes": {
                    "value": false,
                    "2_5_6_2_1_ilac_mra_scope_no":false,
                    "2_5_6_2_1_ilac_mra_scope_yes":{
                        "value":false,
                        "2_5_6_2_1_ilac_mra_disclosure_yes":false,
                        "2_5_6_2_1_ilac_mra_disclosure_no":false,
                    },
                    "2_5_6_2_1_ilac_mra_compliance_yes":false,
                    "2_5_6_2_1_ilac_mra_compliance_no":{
                        "value": false,
                        "2_5_6_2_1_ilac_mra_compliance_details": ""
                    },
                }    
            },
            {
                "id": "2_5_6_2_2",
                "2_5_6_2_2_ilac_mra_compliance_no": false,
                "2_5_6_2_2_ilac_mra_compliance_yes": {
                    "value": false,
                    "2_5_6_2_2_ilac_mra_compliance_details":"",
                    "2_5_6_2_2_mra_compliance_yes":false,
                    "2_5_6_2_2_mra_compliance_no":{
                        "value":false,
                        "2_5_6_2_2_mra_compliance_details":""
                    }
                }    
            },
            {
                "id": "3_0",
                "3_0_assessment_results": {
                    "value": false,
                    "3_0_issue_count":"",
                    "3_0_remarks_count":"",
                    "3_0_deficiencies_details":"",
                    "3_0_deficiency_resolution_date":"",
                },
                "3_0_offer_agreement": false,
            },
        ];


        const defectBlock = [
            {
                "id":"2_5_1",
                "defect_info": []
            },
            {
                "id":"2_5_2",
                "defect_info": []
            },
            {
                "id":"2_5_3",
                "defect_info": []
            },
            {
                "id":"2_5_4",
                "defect_info": []
            },
            {
                "id":"2_5_5",
                "defect_info": []
            }
        ]

        let textBlocks = {
            defect_info: [] // เก็บเป็น JSON ในคีย์ "defect_info"
        };
        function showModal() {
            document.getElementById('modal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            document.getElementById('modal-input').value = '';
        }
        function addTextBlock() {
            const inputText = document.getElementById('modal-input').value.trim();

            if (inputText) {
                const newBlock = {
                    id: `wrapper_2_5_1-${textBlocks.defect_info.length + 1}`, // สร้าง id อัตโนมัติ
                    raw: inputText, // ข้อความที่รับมา
                    lines: []       // คีย์ lines เป็น array เริ่มต้นว่างเปล่า
                };

                // เพิ่มข้อมูลใหม่เข้า defect_info
                textBlocks.defect_info.push(newBlock);

                // เรียก render ใหม่เพื่อแสดงผล
                renderTextBlocks();
            }

            var formData = $(this).serialize(); // ดึงข้อมูลฟอร์มทั้งหมด
            $.ajax({
                url: "{{route('certify.api.test_splitter')}}",
                method: 'POST', // วิธีการส่งข้อมูล
                data: {
                    inputText: inputText, // ค่าที่ส่งไปยัง Controller
                    _token: '{{ csrf_token() }}' // ส่ง CSRF token สำหรับ Laravel
                },
                success: function(response) {

                    if (response.success) {
                        const words = response.data; // สมมติ response.data เป็น array คำ
                        const lines = getLines(words, 720); // หาค่า lines โดยใช้ maxWidth = 720

                        // อัปเดต lines ใน textBlocks.defect_info
                        const lastBlock = textBlocks.defect_info[textBlocks.defect_info.length - 1];
                        lastBlock.lines = lines;

                        console.log('Updated textBlocks:', textBlocks); // ตรวจสอบข้อมูลที่อัปเดต

                         // เรียก renderTableFromLines เพื่อแสดงตารางจาก lines ที่ได้
                        renderTableFromLines();
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


        function getLines(words, maxWidth) {
            const lines = [];
            let currentLine = '';

            // testContainer ใช้สำหรับวัดความกว้างของข้อความ
            const testContainer = document.createElement('div');
            testContainer.style.position = 'absolute';
            testContainer.style.visibility = 'hidden';
            testContainer.style.whiteSpace = 'nowrap';
            testContainer.style.fontSize = '20px';
            document.body.appendChild(testContainer);

            // วนลูปแต่ละคำ
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

            document.body.removeChild(testContainer); // ลบ testContainer ออกจาก DOM
            return lines;
        }

        function renderTableFromLines() {
            // ล้างตารางเดิมก่อน render ใหม่
            const tableContainer = document.getElementById('table_container_2_5_1');
            tableContainer.innerHTML = '';

            // เรียงข้อมูลใน defect_info ตาม id
            const sortedBlocks = textBlocks.defect_info.sort((a, b) => {
                const idA = parseInt(a.id.split('-')[2]); // แยกตัวเลขจาก id เช่น wrapper_2_5_1-1
                const idB = parseInt(b.id.split('-')[2]);
                return idA - idB;
            });

            // สร้างตารางใหม่
            const table = document.createElement('table');
            table.style.width = "720px";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            // วนลูปผ่าน sortedBlocks และสร้างแถวของตารางสำหรับแต่ละ block
            sortedBlocks.forEach(block => {
                const lines = block.lines;
                lines.forEach((line, index) => {
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

                    // ถ้าเป็น tr สุดท้ายของ block ให้เพิ่มปุ่มลบต่อกับข้อความสุดท้าย
                    if (index === lines.length - 1) {
                        const deleteButton = document.createElement('button');
                        deleteButton.style.marginLeft = "10px";
                        deleteButton.style.fontSize = "16px";
                        deleteButton.style.border = "none";
                        deleteButton.style.background = "transparent";
                        deleteButton.style.color = "red"; // กำหนดสีแดง

                        // ใช้ Font Awesome ไอคอนถังขยะ
                        deleteButton.innerHTML = '<i class="fa-solid fa-trash-can"></i>';

                        // เพิ่มการทำงานเมื่อคลิกปุ่มลบ
                        deleteButton.onclick = function() {
                            deleteBlock(block.id);
                        };

                        // เพิ่มปุ่มลบเข้าใน td เดียวกันกับข้อความสุดท้าย
                        td.appendChild(deleteButton);
                    }


                    // เพิ่ม td เข้า tr
                    tr.appendChild(td);

                    // เพิ่ม tr เข้า table
                    table.appendChild(tr);
                });
            });

            // เพิ่มตารางเข้า tableContainer
            tableContainer.appendChild(table);
        }

        function deleteBlock(blockId) {
            // ค้นหา index ของ block ที่ต้องการลบ
            const index = textBlocks.defect_info.findIndex(block => block.id === blockId);

            if (index !== -1) {
                // ลบ block ออกจาก defect_info
                textBlocks.defect_info.splice(index, 1);

                // อัปเดตการ render ใหม่
                renderTextBlocks() 
                renderTableFromLines();
            }
        }


        // ฟังก์ชันสำหรับการ render ข้อความเป็นตาราง
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
            table.style.width = "720px";
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

            
        function renderTextBlocks() {
            const container = document.getElementById('wrapper_2_5_1');
            container.innerHTML = ''; // ล้างข้อมูลเดิมออก

            textBlocks.defect_info.forEach(block => {
                const div = document.createElement('div');
                div.id = block.id;
                div.style.fontFamily = 'Sarabun, sans-serif';
                div.style.fontSize = '20px';
                div.textContent = block.raw; // ใช้ข้อมูลจาก raw key

                container.appendChild(div);
            });
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

        function getStructureInfo()
        {

        }

        $('#btn_submit').on('click', function() {
            data[0]["2_2_assessment_on_site"] = $('#2_2_assessment_on_site').is(':checked');
            data[0]["2_2_assessment_at_tisi"] = $('#2_2_assessment_at_tisi').is(':checked');
            data[0]["2_2_remote_assessment"] = $('#2_2_remote_assessment').is(':checked');
            data[0]["2_2_self_declaration"] = $('#2_2_self_declaration').is(':checked');

            data[1]["standard_17025_compliance"]["value"] = $('#standard_17025_compliance').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_central_management_yes"] = $('#2_5_1_central_management_yes').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_central_management_no"] = $('#2_5_1_central_management_no').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_quality_policy_yes"] = $('#2_5_1_quality_policy_yes').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_quality_policy_no"] = $('#2_5_1_quality_policy_no').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_risk_assessment_yes"] = $('#2_5_1_risk_assessment_yes').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_risk_assessment_no"] = $('#2_5_1_risk_assessment_no').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_other"]["value"] = $('#2_5_1_other').is(':checked');
            data[1]["standard_17025_compliance"]["2_5_1_other"]["2_5_1_text_other1"] = $('#2_5_1_text_other1').val();
            data[1]["standard_17025_compliance"]["2_5_1_other"]["2_5_1_text_other2"] = $('#2_5_1_text_other2').val();
             
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
            data[4]["2_5_4_structure_compliance"]["2_5_4_sampling_activity_yes"] =  $('#2_5_4_sampling_activity_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_sampling_activity_no"] =  $('#2_5_4_sampling_activity_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_procedure_review_request_yes"] =  $('#2_5_4_procedure_review_request_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_procedure_review_request_no"] =  $('#2_5_4_procedure_review_request_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_yes"]["value"] = $('#2_5_4_decision_rule_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule_no"]["value"] = $('#2_5_4_decision_rule_no').is(':checked');
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
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["value"] = $('#2_5_4_proficiency_testing_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["value"] = $('input[name="2_5_4_test_participation"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_participation_details1"] = $('#2_5_4_test_participation_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_participation_details2"] = $('#2_5_4_test_participation_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["value"] = $('input[name="2_5_4_test_calibration"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_calibration_details"] = $('#2_5_4_calibration_details').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_yes"] = $('#2_5_4_acceptance_criteria_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["value"] = $('#2_5_4_acceptance_criteria_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["2_5_4_acceptance_criteria1"] = $('#2_5_4_acceptance_criteria1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation"]["2_5_4_test_calibration"]["2_5_4_acceptance_criteria_no"]["2_5_4_acceptance_criteria2"] = $('#2_5_4_acceptance_criteria2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["value"] = $('input[name="2_5_4_lab_comparison"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_details1"] = $('#2_5_4_lab_comparison_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_details2"] = $('#2_5_4_lab_comparison_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["value"] = $('input[name="2_5_4_lab_comparison_test"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_details"] = $('#2_5_4_lab_comparison_test_details').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_yes"] = $('#2_5_4_lab_comparison_test_is_accept_yes').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["value"] = $('#2_5_4_lab_comparison_test_is_accept_no').is(':checked');
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["2_5_4_lab_comparison_test_is_accept_details1"] = $('#2_5_4_lab_comparison_test_is_accept_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_lab_comparison"]["2_5_4_lab_comparison_test"]["2_5_4_lab_comparison_test_is_accept_no"]["2_5_4_lab_comparison_test_is_accept_details2"] = $('#2_5_4_lab_comparison_test_is_accept_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_test_participation2"] = $('input[name="2_5_4_test_participation2"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["value"] = $('input[name="2_5_4_other_methods"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["2_5_4_other_methods_details1"] = $('#2_5_4_other_methods_details1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_proficiency_testing_yes"]["2_5_4_other_methods"]["2_5_4_other_methods_details2"] = $('#2_5_4_other_methods_details2').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_report_approval_review"] = $('input[name="2_5_4_report_approval_review"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_decision_rule2_no"]["value"] = $('#2_5_4_decision_rule2_no').is(':checked');
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
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["value"] = $('input[name="2_5_4_other"]:checked').length > 0 ? true : false;
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["2_5_4_text_other1"] = $('#2_5_4_text_other1').val();
            data[4]["2_5_4_structure_compliance"]["2_5_4_other"]["2_5_4_text_other2"] = $('#2_5_4_text_other2').val();
            data[4]["2_5_4_issue_found"]["value"] = $('#2_5_4_issue_found').is(':checked');
            data[4]["2_5_4_issue_found"]["detail"] = [];

            data[5]["2_5_5_structure_compliance"]["value"] = $('#2_5_5_structure_compliance').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_option_a"] = $('input[name="2_5_5_data_control_option_a"]:checked').length > 0 ? true : false;
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_option_b"] = $('input[name="2_5_5_data_control_option_b"]:checked').length > 0 ? true : false;
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_policy_yes"] = $('#2_5_5_data_control_policy_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_control_policy_no"] = $('#2_5_5_data_control_policy_no').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_document_control_yes"] = $('#2_5_5_data_document_control_yes').is(':checked');
            data[5]["2_5_5_structure_compliance"]["2_5_5_data_document_control_no"] = $('#2_5_5_data_document_control_no').is(':checked');
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
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["value"] = $('input[name="2_5_5_other"]:checked').length > 0 ? true : false;
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["2_5_5_text_other1"] = $('#2_5_5_text_other1').val();
            data[5]["2_5_5_structure_compliance"]["2_5_5_other"]["2_5_5_text_other2"] = $('#2_5_5_text_other2').val();
            data[5]["2_5_5_issue_found"]["value"] = $('#2_5_5_issue_found').is(':checked');
            data[5]["2_5_5_issue_found"]["detail"] = [];


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
            data[7]["2_5_6_1_2_multi_site_display_yes"]["2_5_6_1_2_multi_site_accuracy_no"]["2_5_6_1_3_certification_status_details"] = $('#2_5_6_1_3_certification_status_details').val();


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
            console.log(data);

    });
    </script>
</body>
</html>
