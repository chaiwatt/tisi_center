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

        <input type="hidden" id="tracking_id" value="{{$tracking->id}}">

        <form id="cbMessageForm" method="POST" action="{{ route('certificate.auditor-cbs.save-tracking-cb-message-record') }}">
            @csrf
            <!-- ส่วนราชการ -->
            <input type="hidden" name="id" value="{{$id}}">
            <table class="table-section">
                <tr>
                    <td>ส่วนราชการ</td>
                    <td style="width: 300px;">
                        <input type="text" class="input-no-border" id="header_text1" name="header_text1" value="สก รป." >
                    </td>
                    <td style="font-size:22px">โทรศัพท์</td>
                    <td style="width: 235px">
                        <input type="text" class="input-no-border" id="header_text2" name="header_text2" value="{{ old('header_text2') }}" >
                    </td>
                </tr>
            </table>

            <!-- ที่และวันที่ -->
            <table class="table-section">
                <tr>
                    <td>ที่</td>
                    <td style="width: 380px;">
                        <input type="text" class="input-no-border" id="header_text3" name="header_text3" value="อก ๐๗๑๔/" >
                    </td>
                    <td>วันที่</td>
                    <td style="width: 300px;">
                        <input type="text" class="input-no-border" id="header_text4" name="header_text4" value="{{ HP::formatDateThaiFullNumThai(\Carbon\Carbon::now()->format('Y-m-d')) }}" >
                    </td>
                </tr>
            </table>

            <!-- เรื่อง -->
            <table class="table-section" >
                <tr>
                    <td>เรื่อง</td>
                    <td style="width: 700px;font-size:22px" class="under-line">การแต่งตั้งคณะผู้ตรวจหน่วยตรวจ {{$data->name_standard}} (คำขอเลขที่ {{$tracking->reference_refno}}) </td>
                </tr>
            </table>

            <!-- Main Content -->
            <div class="section">
                <div>เรียน ผอ.สก. ผ่าน ผก.รต.<input type="text" class="input-no-border" id="body_text1" name="body_text1" value="{{ old('body_text1') }}" style="width:30px" ></div>
                <div class="section-title" >๑. เรื่องเดิม</div>
                <div class="indent" style="text-indent: 125px;" >
                    วันที่ {{$data->register_date}} {{$data->name_standard}} ได้ยื่นคำขอรับใบรับรองหน่วยตรวจ ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ {{$data->get_date}}
                </div>
            </div>
            

            <div class="section">
                {!!$data->fix_text1!!}
            </div>

            <div class="section">
                {!!$data->fix_text2!!}

            <!-- การดำเนินการ -->
            <div class="section">
                <div class="section-title">๔. การดำเนินการ</div>
                <div style="text-indent: 137px;margin-top:10px;line-height:34px">
                    รต.<input type="text" class="input-no-border" id="body_text2" name="body_text2" value="" style="width:30px" required > สก. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินและผู้เชี่ยวชาญ
                    เพื่อดำเนินการตรวจประเมินสถานประกอบการ ของ {{$data->name_standard}} ในวันที่ {{$data->date_range}} ดังนี้
                </div>
                <div style="margin-top:15px">
                    <table style="margin-left:100px">
                        @foreach ($auditors as $auditor)
                            @foreach ($auditor->auditors_status_many as $auditorStatus)
                                @foreach ($auditorStatus->auditors_list_many as $key => $item)
                                    <tr>
                                        <td style="width: 300px">({{$key+1}}). {{$item->temp_users}}</td>
                                        <td style="width: 300px">{{$item->temp_departments}}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </table>

                </div>

                <div  style="margin-left: 110px; margin-top:15px">
                    เอกสารกำหนดการตรวจประเมิน ณ สถานประกอบการ
                        @if (!is_null($trackingAuditor->FileAuditors2) &&  $trackingAuditor->FileAuditors2 != '')
                            <p style="margin-left:15px">
                                (1).  <a style="text-decoration: none" href="{{url('funtions/get-view/'.$trackingAuditor->FileAuditors2->url.'/'.( !empty($trackingAuditor->FileAuditors2->filename) ? $trackingAuditor->FileAuditors2->filename :  basename($trackingAuditor->FileAuditors2->new_filename)   ))}}" target="_blank">
                                        {!! HP::FileExtension($trackingAuditor->FileAuditors2->filename)  ?? '' !!} {{$trackingAuditor->FileAuditors2->filename}}
                                    </a>
                            </p>  

                        @endif     
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
                <div style="text-indent: 137px;margin-top:10px;line-height:34px">จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ. เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินสถานประกอบการ{{$data->name_standard}} ในวันที่ {{$data->date_range}} รายละเอียดดังข้างต้น</div>      
            </div>
            <div class="submit-section">
                <button type="submit" class="btn-submit" >บันทึก</button>
            </div>
        </form>
    </div>
    

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    $('#cbMessageForm').on('submit', function(event) {
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
                // window.location.href = "{{ route('certify.auditor.index') }}";
                var tracking_id = $('#tracking_id').val();


                window.location.href = '/certificate/tracking-cb/' + tracking_id + '/edit';


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





</script>

</html>
