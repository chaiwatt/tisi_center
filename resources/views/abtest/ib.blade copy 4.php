<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Docs</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

@font-face {
    font-family: 'thsarabunnew';
    src: url('/fonts/THSarabunNew.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

 body {
	background-color: #f8f9fa;
	font-family: 'thsarabunnew', 'Arial', sans-serif;
	margin: 0;
	padding: 0;
	color: #333;
 }

/* Superscript and Subscript Styling */
sup, sub {
    font-size: 16px;
}

/* --- Menubar --- */
#menubar {
    background-color: #edf2fa;
    padding: 8px 16px;
    border-bottom: 1px solid #d4d4d4;
    position: sticky;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    align-items: center;
}

.vertical-align-top {
    vertical-align: top !important; /* Use !important to ensure it applies */
}


.menu-button {
    background: none;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 18px;
    width: 36px;
    height: 36px;
    cursor: pointer;
    color: #444;
    display: flex;
    align-items: center;
    justify-content: center;
}

.menu-button:hover {
    background-color: #dce1e6;
    border-color: #c9ced2;
}

.menu-button.active {
    background-color: #cce1ff;
    color: #0b57d0;
}

.separator {
    width: 1px;
    height: 20px;
    background-color: #ccc;
    margin: 0 8px;
}

/* --- [ใหม่] Font Size Dropdown Styling --- */
.font-size-container {
    display: flex;
    align-items: center;
    border: 1px solid transparent;
    border-radius: 4px;
    height: 36px;
    padding: 0 4px;
    cursor: pointer;
}
.font-size-container:hover {
    background-color: #dce1e6;
    border-color: #c9ced2;
}
#font-size-selector {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border: none;
    background: transparent;
    height: 100%;
    font-size: 18px;
    font-family: inherit;
    cursor: pointer;
    outline: none;
    text-align: center;
    min-width: 50px;
}

/* --- Dropdown Styling --- */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 4px;
    top: 100%; /* Position below the button */
    left: 0;
}

.dropdown-content a {
    color: black;
    padding: 3px 12px;
    text-decoration: none;
    display: block;
    cursor: pointer;
    font-size: 20px
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}
/* Show dropdown content when the button is clicked */
.dropdown.show .dropdown-content {
    display: block;
}

/* --- [CORRECTED] Unified Modal Styles --- */
.modal-overlay {
    display: none; /* This is the key fix: Hide modals by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 2000;
    padding: 20px;
    box-sizing: border-box;
}

.modal-content {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    width: 100%;
    max-width: 500px; /* Default max-width */
    font-family: 'thsarabunnew', 'Arial', sans-serif;
    max-height: 90vh; /* Max height */
    overflow-y: auto; /* Scroll if content overflows */
}

.modal-content h3 {
    margin-top: 0;
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.modal-input-group {
    margin-bottom: 15px;
}

.modal-input-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 18px;
    color: #555;
}

.modal-input-group input[type="number"],
.modal-input-group input[type="text"] {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

.modal-btn-confirm, .modal-btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.2s;
}

.modal-btn-confirm {
    background-color: #28a745;
    color: white;
}

.modal-btn-confirm:hover {
    background-color: #218838;
}

.modal-btn-cancel {
    background-color: #f0f0f0;
    color: #333;
    border: 1px solid #ccc;
}

.modal-btn-cancel:hover {
    background-color: #e0e0e0;
}

.editable-div {
    border: 1px solid #ccc;
    padding: 8px;
    min-height: 80px;
    border-radius: 4px;
    background-color: #fff;
    font-family: 'thsarabunnew', 'Arial', sans-serif;
    font-size: 18px;
    line-height: 1.4;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.editable-div:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

/* --- Editor Area --- */
#editor-container {
    width: 100%;
    display: flex;
    justify-content: center;
}

#document-editor {
    width: 20cm;
    margin-top: 2rem;
    margin-bottom: 2rem;
}

.page {
    background-color: white;
    height: 29.5cm;
    padding: 1cm 1cm 0 1cm;
    margin-bottom: 0.5cm;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    line-height: 1.1;
    font-size: 20px;
    overflow: hidden;
    outline: none;
    position: relative;
}

.page:focus {
    outline: none;
}

.page:empty::before {
    /* content: "พิมพ์ที่นี่..."; */
    color: #aaa;
    pointer-events: none;
}

/* --- Table Styling (FIXED) --- */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 0; /* Ensure no default table margin */
    table-layout: fixed;
}

/* Default border for all cells */
th, td {
    border: 1px solid #000;
    padding: 2px 5px; /* Add some default padding */
    vertical-align: top;
    word-wrap: break-word;
    line-height: 1.1;
}

/* Rule to remove borders for tables with .borderless class */
table.borderless,
table.borderless th,
table.borderless td {
    border: none !important;
    padding: 0; /* Reset padding for layout tables */
}


/* Specific styles for the new table-based layout */
.report-main-table {
    /* This will be borderless if it has the .borderless class */
    font-family: 'thsarabunnew', 'Arial', sans-serif;
    font-size: 20px;
}

.header-table {
    margin-bottom: 5px; /* Reduced space after header */
}

.header-request-cell {
    text-align: left;
    padding-left: 10px;
    font-size: 18px;
    padding-bottom: 0; /* Reduce space */
}

.header-title-cell {
    text-align: center;
    font-weight: bold;
    font-size: 24px;
    padding-top: 0; /* Reduce space */
}

.section-table {
    margin-top: 2px; /* Further reduced margin between sections */
    margin-bottom: 2px; /* Further reduced margin between sections */
    margin-left:-5px;
}

.label-cell {
    font-weight: bold;
    white-space: nowrap;
    width: 130px; /* Fixed width for label column */
    padding: 0 5px; /* Add minimal horizontal padding for readability */
}

.content-cell {
    padding: 0 5px; /* Add minimal horizontal padding for readability */
}

.phone-fax-table td {
    padding: 0; /* Remove padding from nested table cells to make them very tight */
}

.checkbox-item-cell {
    padding: 1px 0; /* Minimal vertical padding for checkbox cells */
    text-align: left;
}

.checkbox-item-cell input[type="checkbox"] {
    margin-right: 5px;
    vertical-align: middle;
}

.file-list-table td {
    padding: 1px 0; /* Minimal vertical padding for file list items */
    text-align: left;
}

/* Original CSS from here, adjusted where necessary */
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
        text-align: center;    /* ทำให้ปุ่มอยู่ตรงกลาง */
        margin-top: 50px;      /* เว้นระยะห่างจากเนื้อหาด้านบน */
        margin-bottom: 30px;
    }

    .btn-submit {
        font-family: 'Sarabun', sans-serif;
        padding: 10px 20px;    /* เพิ่มขนาดของปุ่ม */
        background-color: #4CAF50; /* สีพื้นหลัง */
        color: white;          /* สีตัวอักษร */
        border: none;          /* ไม่ให้มีขอบ */
        border-radius: 5px;    /* ทำมุมโค้ง */
        font-size: 22px;       /* ขนาดตัวอักษร */
        cursor: pointer;       /* เปลี่ยนรูปแบบเมาส์เมื่อชี้ที่ปุ่ม */
        transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีเมื่อ hover */
    }

    .btn-submit:hover {
        background-color: #45a049;    /* เปลี่ยนสีปุ่มเมื่อ hover */
    }

    .btn-draft {
        font-family: 'Sarabun', sans-serif;
        padding: 10px 20px;    /* เพิ่มขนาดของปุ่ม */
        background-color: #f75e06; /* สีพื้นหลัง */
        color: white;          /* สีตัวอักษร */
        border: none;          /* ไม่ให้มีขอบ */
        border-radius: 5px;    /* ทำมุมโค้ง */
        font-size: 22px;       /* ขนาดตัวอักษร */
        cursor: pointer;       /* เปลี่ยนรูปแบบเมาส์เมื่อชี้ที่ปุ่ม */
        transition: background-color 0.3s; /* เพิ่มการเปลี่ยนสีเมื่อ hover */
    }

    .btn-draft:hover {
        background-color: #45a049;    /* เปลี่ยนสีปุ่มเมื่อ hover */
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
        /* Removed .inline-group, .label, .content, .spaced as they are replaced by table structure */
        /* If these classes are used elsewhere and need to be preserved, they should be kept.
           For this specific conversion, they are replaced by table cells. */

        .checkbox-group { /* This class is now applied to the inner table for checkboxes */
            /* display: grid; */ /* Removed as we are using table */
            /* grid-template-columns: auto auto; */ /* Removed */
            /* gap: 10px; */ /* Removed, spacing handled by cell padding */
            margin-top:10px; /* Keep margin for the overall checkbox block */
        }

        .checkbox-item { /* This class is no longer used directly, its styles are moved to td */
            /* display: flex; */ /* Removed */
            /* align-items: center; */ /* Removed */
            /* gap: 5px; */ /* Removed */
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

/* Context Menu Styling */
#context-menu {
    position: absolute;
    display: none;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 3000;
    border-radius: 4px;
    padding: 5px 0;
    min-width: 250px; /* Wider for shortcuts */
}

.context-menu-item {
    padding: 8px 15px;
    cursor: pointer;
    font-size: 16px; /* Slightly larger font */
    color: #333;
}

.context-menu-item:hover {
    background-color: #007bff;
    color: white;
}

.context-menu-separator {
    height: 1px;
    background-color: #e0e0e0;
    margin: 5px 0;
}

/* Image Resizing Styles */
.image-container {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    line-height: 0; /* To remove space below image */
}

.image-container.active {
    outline: 2px dashed #007bff;
}

.resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background: #007bff;
    border: 1px solid white;
    border-radius: 50%;
    z-index: 10;
    cursor: nwse-resize;
}

.resize-handle.top-left { top: -5px; left: -5px; cursor: nwse-resize; }
.resize-handle.top-right { top: -5px; right: -5px; cursor: nesw-resize; }
.resize-handle.bottom-left { bottom: -5px; left: -5px; cursor: nesw-resize; }
.resize-handle.bottom-right { bottom: -5px; right: -5px; cursor: nwse-resize; }

.image-container:not(.active) .resize-handle {
    display: none;
}

    </style>
</head>
<body>
    <div id="menubar">
        <button class="menu-button" data-command="bold" title="ตัวหนา"><i class="fas fa-bold"></i></button>
        <button class="menu-button" data-command="italic" title="ตัวเอียง"><i class="fas fa-italic"></i></button>
        <button class="menu-button" data-command="superscript" title="ตัวยก"><i class="fas fa-superscript"></i></button>
        <button class="menu-button" data-command="subscript" title="ตัวห้อย"><i class="fas fa-subscript"></i></button>
        <div class="separator"></div>
        <div class="font-size-container">
            <select id="font-size-selector" title="ขนาดฟอนต์">
                <option value="8">8</option>
                <option value="10">10</option>
                <option value="12">12</option>
                <option value="14">14</option>
                <option value="16">16</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="28">28</option>
                <option value="32">32</option>
                <option value="36">36</option>
                <option value="48">48</option>
                <option value="72">72</option>
            </select>
        </div>
        <div class="separator"></div>
        <button class="menu-button" data-command="justifyLeft" title="จัดชิดซ้าย"><i class="fas fa-align-left"></i></button>
        <button class="menu-button" data-command="justifyCenter" title="จัดกึ่งกลาง"><i class="fas fa-align-center"></i></button>
        <button class="menu-button" data-command="justifyRight" title="จัดชิดขวา"><i class="fas fa-align-right"></i></button>
        <div class="separator"></div>
        <button class="menu-button" data-command="increaseLineHeight" title="เพิ่มระยะห่างบรรทัด"><i class="fas fa-arrows-up-to-line"></i></button>
        <button class="menu-button" data-command="decreaseLineHeight" title="ลดระยะห่างบรรทัด"><i class="fas fa-arrows-down-to-line"></i></button>
        <div class="separator"></div>
        <button class="menu-button" data-command="insertTable" title="แทรกตาราง"><i class="fas fa-table"></i></button>
        <button class="menu-button" data-command="insertImage" title="แทรกรูปภาพ"><i class="fas fa-image"></i></button>
        
        <div class="dropdown">
            <button class="menu-button" id="template-dropdown-button" title="แทรกเทมเพลต"><i class="fas fa-file-alt"></i></button>
            <div class="dropdown-content">
                @if ($templateType == "cb")
                        <a href="#" data-template="cb-template" >CB template</a>
                @elseif($templateType == "ib")
                         <a href="#" data-template="ib-template" >IB template</a>
                @elseif($templateType == "ib-report-one-template")
                         <a href="#" data-template="ib-report-one-template" >IB Report One template</a>
                @endif
            </div>
        </div>
        
        <button class="menu-button" id="export-pdf-button" title="ส่งออกเป็น PDF"><i class="fas fa-file-pdf"></i></button>
        <button class="menu-button" id="save-template-button"><i class="fas fa-save"></i></button>
        <button class="menu-button" id="load-template-button"><i class="fa fa-cloud-download" aria-hidden="true"></i></button>

        
    </div>

    <input type="file" id="image-input" accept="image/*" style="display: none;">

    <div id="editor-container">
        <div id="document-editor">
            <div class="page" contenteditable="true"></div>
        </div>
    </div>

    <!-- Table Creation Modal -->
    <div id="table-modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h3>แทรกตาราง</h3>
            <div class="modal-input-group">
                <label for="table-rows">จำนวนแถว:</label>
                <input type="number" id="table-rows" value="3" min="1">
            </div>
            <div class="modal-input-group">
                <label for="table-cols">จำนวนคอลัมน์:</label>
                <input type="number" id="table-cols" value="3" min="1">
            </div>
            <div class="modal-input-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" id="table-border-toggle" checked style="width: auto; margin: 0;">
                <label for="table-border-toggle" style="margin: 0; font-weight: normal; user-select: none; cursor: pointer;">แสดงเส้นขอบ</label>
            </div>
            <div class="modal-buttons">
                <button id="insert-table-btn" class="modal-btn-confirm">แทรก</button>
                <button class="modal-btn-cancel">ยกเลิก</button>
            </div>
        </div>
    </div>

    <!-- NEW: Modals for Adding Template Items -->
    <!-- === START: REVISED CB Item Modal === -->
    <div id="cb-item-modal" class="modal-overlay cb-modal">
        <div class="modal-content" style="width: 750px">
            <h3>เพิ่มรายการ (CB)</h3>
            

            <div class="modal-input-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="cb-code-editor">รหัส:</label>
                    <div id="cb-code-editor" class="editable-div code" contenteditable="true"></div>
                </div>
                <div style="flex: 1;">
                    <label for="cb-description-editor">รายละเอียด:</label>
                    <div id="cb-description-editor" class="editable-div detail" contenteditable="true"></div>
                </div>
            </div>

            <div class="modal-buttons">
                <button id="add-cb-item-btn" class="modal-btn-confirm">เพิ่ม</button>
                <button class="modal-btn-cancel">ยกเลิก</button>
            </div>
        </div>
    </div>
    <!-- === END: REVISED CB Item Modal === -->

    <!-- === START: REVISED IB Item Modal === -->
    <div id="ib-item-modal" class="modal-overlay ib-modal">
        <div class="modal-content" style="width: 540px">
            <h3>เพิ่มรายการ (IB)</h3>
            
            <div class="modal-input-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="ib-main-branch">สาขาการตรวจหลัก:</label>
                    <input type="text" id="ib-main-branch">
                </div>
                <div style="flex: 1;">
                    <label for="ib-sub-branch">สาขาการตรวจย่อย:</label>
                    <div id="ib-sub-branch" class="editable-div" contenteditable="true"></div>
                </div>
            </div>

            <div class="modal-input-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="ib-main-scope">ขอบข่ายหลัก:</label>
                    <div id="ib-main-scope" class="editable-div" contenteditable="true"></div>
                </div>
                <div style="flex: 1;">
                    <label for="ib-sub-scope">ขอบข่ายย่อย:</label>
                    <div id="ib-sub-scope" class="editable-div" contenteditable="true"></div>
                </div>
            </div>

            <div class="modal-input-group">
                <label for="ib-requirements-editor">ข้อกำหนดที่ใช้:</label>
                <div id="ib-requirements-editor" class="editable-div" contenteditable="true"></div>
            </div>

            <div class="modal-buttons">
                <button id="add-ib-item-btn" class="modal-btn-confirm">เพิ่ม</button>
                <button class="modal-btn-cancel">ยกเลิก</button>
            </div>
        </div>
    </div>
    <!-- === END: REVISED IB Item Modal === -->



    <!-- === START: MODIFICATION === -->
    <div id="context-menu">
        <div class="context-menu-item" data-action="add-item">เพิ่มรายการ</div>
        <div class="context-menu-separator" data-action="separator-add"></div>
        <div class="context-menu-item" data-action="insert-row-above">แทรกแถวด้านบน <span style="float: right; color: #888; margin-left: 20px;">Shift+F1</span></div>
        <div class="context-menu-item" data-action="insert-row-above-no-border">แทรกแถวด้านบน (ไม่มีขอบ) <span style="float: right; color: #888; margin-left: 20px;">Shift+F2</span></div>
        <div class="context-menu-item" data-action="insert-row-below">แทรกแถวด้านล่าง <span style="float: right; color: #888; margin-left: 20px;">Shift+F4</span></div>
        <div class="context-menu-item" data-action="insert-row-below-no-border">แทรกแถวด้านล่าง (ไม่มีขอบ) <span style="float: right; color: #888; margin-left: 20px;">Shift+F5</span></div>
        <div class="context-menu-item" data-action="insert-column-left">แทรกคอลัมน์ด้านซ้าย</div>
        <div class="context-menu-item" data-action="insert-column-right">แทรกคอลัมน์ด้านขวา</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" data-action="delete-row">ลบแถว</div>
        <div class="context-menu-item" data-action="delete-column">ลบคอลัมน์</div>
        <div class="context-menu-separator" data-action="separator-merge"></div>
        <div class="context-menu-item" data-action="merge-columns">รวมคอลัมน์</div>
    </div>
    <!-- === END: MODIFICATION === -->

    <script>
        document.execCommand('styleWithCSS', false, true);

        // --- DOM Elements ---
        const editor = document.getElementById('document-editor');
        const menubar = document.getElementById('menubar');
        const tableModalOverlay = document.getElementById('table-modal-overlay');
        const insertTableBtn = document.getElementById('insert-table-btn');
        const tableRowsInput = document.getElementById('table-rows');
        const tableColsInput = document.getElementById('table-cols');
        const tableBorderToggle = document.getElementById('table-border-toggle');
        const imageInput = document.getElementById('image-input');
        const contextMenu = document.getElementById('context-menu');
        const templateDropdownButton = document.getElementById('template-dropdown-button');
        const templateDropdownContent = document.querySelector('.dropdown-content');
        const exportPdfButton = document.getElementById('export-pdf-button');
        const fontSizeSelector = document.getElementById('font-size-selector');
        const saveTemplateButton = document.getElementById('save-template-button'); 
        const loadTemplateButton = document.getElementById('load-template-button');

        // --- NEW: Template Item Modals ---
        const cbItemModal = document.getElementById('cb-item-modal');
        const ibItemModal = document.getElementById('ib-item-modal');
    

        // 1. รับข้อมูลจาก Blade ที่ PHP ส่งมา
        const templateType = "{{ $templateType ?? '' }}"; 

        const cbDetailsFromBlade = @json($cbDetails ?? null);
        const ibDetailsFromBlade = @json($ibDetails ?? null);
        const certi_ib = @json($certi_ib ?? null);

        
                                                                
        let savedRange = null; // Used for image insertion
        let contextMenuTarget = null;
        let contextMenuTargetRow = null; // To store the target TR element for immediate context menu actions
        let selectedTableCellsForMerge = []; // Holds cells selected for merging
        let activeModalTargetRow = null; // **NEW**: Persists the target row for modal operations

        // --- Global Paste Handler for Main Editor ---
        // This listener is attached to the main editor container (#document-editor).
        // It catches all paste events within any ".page" element.
        // Its purpose is to strip all formatting and paste as plain text.
        editor.addEventListener('paste', (event) => {
            // Prevent the default paste action which might include rich text formatting.
            event.preventDefault();

            // Get the pasted content as plain text from the clipboard.
            const text = (event.clipboardData || window.clipboardData).getData('text/plain');

            // Insert the plain text at the current cursor position.
            document.execCommand('insertText', false, text);
        });
        
        // --- LineExtractor Class ---

        class LineExtractor {
            constructor(elementId) {
                this.editableDiv = document.getElementById(elementId);
                this.init();
            }

            init() {
                if (!this.editableDiv) {
                    console.error('Element not found:', this.elementId);
                    return;
                }

                // --- Specific Paste Handler for Modal's Div ---
                // This listener ONLY applies to the div managed by this class instance

                // Its purpose is to paste as plain text BUT apply specific styles.
                this.editableDiv.addEventListener('paste', (event) => {
                    // Prevent the default paste action
                    event.preventDefault();
                    console.log("Paste event fired in modal's editableDiv!");
                    
                    // Get pasted text as plain text
                    const text = (event.clipboardData || window.clipboardData).getData('text/plain');

                    // Insert the plain text. The browser will handle wrapping it in the current context.
                    // For a contenteditable div, this is usually sufficient.
                    document.execCommand('insertText', false, text);
                });
            }

            getLines() {
                if (!this.editableDiv) return [];
                
                const tempDiv = document.createElement('div');
                const computedStyle = window.getComputedStyle(this.editableDiv);
                
                tempDiv.style.width = computedStyle.width;
                tempDiv.style.position = 'absolute';
                tempDiv.style.visibility = 'hidden';
                tempDiv.style.whiteSpace = 'pre-wrap';
                tempDiv.style.overflowWrap = 'break-word';
                tempDiv.style.fontFamily = computedStyle.fontFamily;
                tempDiv.style.fontSize = computedStyle.fontSize;
                tempDiv.style.lineHeight = computedStyle.lineHeight;
                tempDiv.style.padding = computedStyle.padding;
                tempDiv.style.border = computedStyle.border;
                tempDiv.style.boxSizing = computedStyle.boxSizing;

                // Use innerText to respect user-entered newlines
                let text = this.editableDiv.innerText;
                document.body.appendChild(tempDiv);
                
                const lines = [];
                const range = document.createRange();
                let lastTop = null;
                
                // Split text by newlines first to handle manual line breaks
                const textLines = text.split('\n');
                
                for (let line of textLines) {
                    if (line.trim() === '') {
                        // Add empty lines from newlines
                        lines.push('');
                        continue;
                    }
                
                    // Put the text of this line into tempDiv to check for wrapping
                    tempDiv.textContent = line;
                
                    let subCurrentLine = '';
                    let tempNode = tempDiv.firstChild;
                    if (!tempNode || tempNode.nodeType !== Node.TEXT_NODE) {
                         if (line) lines.push(line);
                          continue;
                    }

                    for (let i = 0; i < line.length; i++) {
                        range.setStart(tempNode, i);
                        range.setEnd(tempNode, i + 1);
                        const rects = range.getClientRects();
                        
                        if (rects.length > 0) {
                            const rect = rects[0];
                            // If the top position changes, it's a new line
                            if (lastTop !== null && rect.top > lastTop) {
                                lines.push(subCurrentLine.trim());
                                subCurrentLine = line[i];
                            } else {
                                subCurrentLine += line[i];
                            }
                            lastTop = rect.top;
                        }
                
                        // Push the last part of the line
                        if (i === line.length - 1) {
                            lines.push(subCurrentLine.trim());
                        }
                    }
                    lastTop = null; // Reset for the next line from text.split('\n')
                }
                
                document.body.removeChild(tempDiv);
                
                return lines;
            }
        }


        // --- Core Styling Functions ---
        const applyStyleToSelectedSpans = (styleCallback) => {
            const selection = window.getSelection();
            if (!selection.rangeCount || selection.isCollapsed) return;

            const originalRange = selection.getRangeAt(0).cloneRange();
            
            let startElement = originalRange.startContainer;
            if (startElement.nodeType === Node.TEXT_NODE) {
                startElement = startElement.parentElement;
            }
            const parentPage = startElement.closest('.page');
            
            if (!parentPage) return;

            const tempMarkerColor = 'rgb(1, 2, 3)';
            document.execCommand('foreColor', false, tempMarkerColor);
            
            const spans = parentPage.querySelectorAll(`span[style*="color: ${tempMarkerColor}"]`);

            if (spans.length === 0) {
                document.execCommand('undo');
                selection.removeAllRanges();
                selection.addRange(originalRange);
                return;
            }

            spans.forEach(span => {
                span.style.color = '';
                styleCallback(span);
                if (!span.style.cssText.trim()) {
                    span.removeAttribute('style');
                }
            });

            parentPage.querySelectorAll('span:not([style])').forEach(emptySpan => {
                const parent = emptySpan.parentNode;
                while(emptySpan.firstChild){
                    parent.insertBefore(emptySpan.firstChild, emptySpan);
                }
                parent.removeChild(emptySpan);
            });
            
            selection.removeAllRanges();
            selection.addRange(originalRange);
        };

        // --- Menubar Functionality ---
        menubar.addEventListener('click', (event) => {
            const button = event.target.closest('.menu-button');
            if (!button) return;
            const command = button.dataset.command;
            
            if (button.id !== 'template-dropdown-button') {
                templateDropdownContent.parentElement.classList.remove('show');
            }

            if (command === 'increaseLineHeight' || command === 'decreaseLineHeight') {
                changeLineHeight(command);
            } else if (command === 'insertTable') {
                insertTable();
            } else if (command === 'insertImage') {
                // Save range for image insertion
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    savedRange = selection.getRangeAt(0).cloneRange();
                }
                imageInput.click();
            } else {
                document.execCommand(command, false, null);
            }
            
            if(command !== 'insertTable' && command !== 'insertImage') {
              const lastActivePage = editor.querySelector('.page:focus') || editor.querySelector('.page');
              lastActivePage?.focus();
            }
        });

        fontSizeSelector.addEventListener('change', (event) => {
            const newSize = event.target.value;
            if (!newSize) return;

            applyStyleToSelectedSpans((element) => {
                element.style.fontSize = newSize + 'px';
            });
            
            const lastActivePage = editor.querySelector('.page:focus') || editor.querySelector('.page');
            lastActivePage?.focus();
        });
        
        templateDropdownButton.addEventListener('click', (event) => {
            event.stopPropagation();
            templateDropdownContent.parentElement.classList.toggle('show');
        });

        templateDropdownContent.addEventListener('click', (event) => {
            const templateItem = event.target.closest('a[data-template]');
            if (templateItem) {
                event.preventDefault();
                const templateId = templateItem.dataset.template;
                if (templateId === 'cb-template') {
                    insertCbTemplate();
                } else if (templateId === 'ib-template') {
                    insertIbTemplate();
                }else if (templateId === 'ib-report-one-template') {
                    insertIbReportOneTemplate();
                }
                templateDropdownContent.parentElement.classList.remove('show');
            }
        });

        document.addEventListener('click', (event) => {
            if (!templateDropdownContent.parentElement.contains(event.target)) {
                templateDropdownContent.parentElement.classList.remove('show');
            }
        });
        
        const changeLineHeight = (direction) => {
            const selection = window.getSelection();
            if (!selection.rangeCount) return;

            let element = selection.getRangeAt(0).startContainer;
            if (element.nodeType === Node.TEXT_NODE) {
                element = element.parentElement;
            }
            
            while (element && window.getComputedStyle(element).display !== 'block' && !element.classList.contains('page')) {
                element = element.parentElement;
            }

            if (!element || element.classList.contains('page')) {
                document.execCommand('formatBlock', false, 'p');
                element = window.getSelection().getRangeAt(0).startContainer.closest('p');
            }
            
            if(!element) return;

            const computedStyle = window.getComputedStyle(element);
            let currentLineHeight = computedStyle.lineHeight;
            const fontSize = parseFloat(computedStyle.fontSize);
            let currentLineHeightValue;

            if (currentLineHeight === 'normal') {
                currentLineHeightValue = 1.6;
            } else if (currentLineHeight.endsWith('px')) {
                currentLineHeightValue = parseFloat(currentLineHeight) / fontSize;
            } else {
                currentLineHeightValue = parseFloat(currentLineHeight);
            }
            
            if (isNaN(currentLineHeightValue)) {
                currentLineHeightValue = 1.6;
            }

            const step = 0.2;
            let newLineHeight = (direction === 'increaseLineHeight')
                ? currentLineHeightValue + step
                : currentLineHeightValue - step;

            newLineHeight = Math.max(1.0, Math.min(4.0, newLineHeight));
            newLineHeight = Math.round(newLineHeight * 10) / 10;

            element.style.lineHeight = newLineHeight.toString();
        };

        const insertTable = () => {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) savedRange = selection.getRangeAt(0).cloneRange();
            tableModalOverlay.style.display = 'flex';
            tableRowsInput.focus();
        };

        // Helper function to insert template content into the correct page
        const insertTemplateAtCurrentOrLastPage = (templateHTML) => {
            let targetPage = editor.querySelector('.page:focus');

            if (!targetPage || (targetPage.textContent.trim() === '' && editor.children.length > 1)) {
                const pages = Array.from(editor.querySelectorAll('.page'));
                for (let i = pages.length - 1; i >= 0; i--) {
                    if (pages[i].textContent.trim() !== '' || i === pages.length - 1) {
                        targetPage = pages[i];
                        break;
                    }
                }
            }

            if (!targetPage) {
                targetPage = editor.querySelector('.page');
            }

            if (targetPage) {
                targetPage.focus();
                const range = document.createRange();
                const selection = window.getSelection();
                range.selectNodeContents(targetPage);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand('insertHTML', false, templateHTML);
            }
            setTimeout(managePages, 10);
        };
        
const insertCbTemplate = () => {
            const templateData = cbDetailsFromBlade;

            if (!templateData) {
                console.error("No cbDetails data available to render.");
                return;
            }

            let accreditationCriteriaHTML = '';
            if (templateData.accreditationCriteria && Array.isArray(templateData.accreditationCriteria)) {
                accreditationCriteriaHTML = templateData.accreditationCriteria.map(item =>
                    `${item.th}<br><span style="font-size: 15px;">${item.en}</span>`
                ).join('<br>');
            }

            let isicTableRows = '';
            if (templateData.isicCodes && Array.isArray(templateData.isicCodes)) {
                templateData.isicCodes.forEach(item => {
                    isicTableRows += `
                        <tr>
                            <td style="width: 20%;">${item.code}</td>
                            <td >${item.description_th}<br><span style="font-size: 15px;">(${item.description_en})</span></td>
                        </tr>
                    `;
                });
            }

            const templateHTML = `
                <div style="text-align: center;line-height: 1.0">
                    <b style="font-size: 1.17em;">${templateData.scopeOfAccreditation.th}</b><br>
                    <span style="font-size: 15px;">(${templateData.scopeOfAccreditation.en})</span><br>
                    ${templateData.attachmentToCertificate.th}<br>
                    <span style="font-size: 15px;">(${templateData.attachmentToCertificate.en})</span><br>
                    <b>ใบรับรองเลขที่ ${templateData.certificateNo}</b><br>
                    <span style="font-size: 15px;">(Certification No. ${templateData.certificateNo})</span>
                </div>
                <table class="borderless" style="width: 100%; margin-bottom: 1em;">
                    <tbody>
                        <tr>
                            <td class="vertical-align-top" style="width: 25%;"><b>หน่วยรับรอง</b><br><span style="font-size: 15px;">(Certification Body)</span></td>
                            <td class="vertical-align-top">${templateData.certificationBody.th}<br><span style="font-size: 15px;">(${templateData.certificationBody.en})</span></td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top"><b>ที่ตั้งสถานประกอบการ</b><br><span style="font-size: 15px;">(Premise)</span></td>
                            <td class="vertical-align-top">${templateData.premise.th}<br><span style="font-size: 15px;">(${templateData.premise.en})</span></td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top"><b>ข้อกำหนดที่ใช้ในการรับรอง</b><br><span style="font-size: 15px;">(Accreditation criteria)</span></td>
                            <td class="vertical-align-top">${accreditationCriteriaHTML}</td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top"><b>กิจกรรมที่ได้รับการรับรอง</b><br><span style="font-size: 15px;">(Certification Mark)</span></td>
                            <td class="vertical-align-top">${templateData.certificationMark.th}<br><span style="font-size: 15px;">(${templateData.certificationMark.en})</span></td>
                        </tr>
                    </tbody>
                </table>
                <table class="detail-table" style="width: 100%; margin-bottom: 1em;">
                    <thead>
                        <tr>
                            <th style="width: 25%;">รหัส ISIC<br><span style="font-size: 15px">(ISIC Codes)</span></th>
                            <th>กิจกรรม<br><span style="font-size: 15px;">(Description)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        ${isicTableRows}
                    </tbody>
                </table>
                <p><br></p>
            `;
            insertTemplateAtCurrentOrLastPage(templateHTML);
        };

        const insertIbTemplate = () => {
            const templateData = ibDetailsFromBlade;

            if (!templateData) {
                console.error("No ibDetails data available to render.");
                return;
            }

            let inspectionTableRows = '';
            templateData.inspectionItems.forEach(item => {
                inspectionTableRows += `
                    <tr>
                        <td>${item.category}</td>
                        <td>${item.procedure}</td>
                        <td>${item.requirements}</td>
                    </tr>
                `;
            });

            const templateHTML = `
                <div style="text-align: center; line-height: 1.1; margin-bottom: 1em;">
                    <b style="font-size: 20px;">${templateData.title}</b><br>
                    <b>ใบรับรองเลขที่ ${templateData.certificateNo}</b>
                </div>
                <table class="borderless" style="width: 100%; margin-bottom: 1em;">
                    <tbody>
                        <tr>
                            <td class="vertical-align-top" style="width: 22%;"><b>ชื่อหน่วยตรวจ</b></td>
                            <td class="vertical-align-top">: ${templateData.inspectionBodyName}</td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top" colspan="2">
                                <table class="borderless" style="width: 100%; margin: 0;">
                                    <tbody>
                                        <tr>
                                            <td class="vertical-align-top" style="width: 50%;">
                                                <b>ที่ตั้งสำนักงานใหญ่</b><br>
                                                ${templateData.headOfficeAddress}
                                            </td>
                                            <td class="vertical-align-top" style="width: 50%;">
                                                <b>ที่ตั้งสำนักงานสาขา (กรณีแตกต่างจากที่ตั้งสำนักงานใหญ่)</b><br>
                                                ${templateData.branchOfficeAddress}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top"><b>หมายเลขการรับรอง</b></td>
                            <td class="vertical-align-top">: ${templateData.accreditationNo}</td>
                        </tr>
                        <tr>
                            <td class="vertical-align-top"><b>ประเภทของหน่วยตรวจ</b></td>
                            <td class="vertical-align-top">: ${templateData.inspectionBodyType}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="detail-table" style="width: 100%; margin-bottom: 1em;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">หมวดหมู่ / สาขาการตรวจ</th>
                            <th style="text-align: center;">ขั้นตอนและช่วงการตรวจ</th>
                            <th style="text-align: center;">ข้อกำหนดที่ใช้</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${inspectionTableRows}
                    </tbody>
                </table>
                <p><br></p>
            `;

            insertTemplateAtCurrentOrLastPage(templateHTML);
        };

       const insertIbReportOneTemplate = () => {
            const templateData = ibDetailsFromBlade;

            if (!templateData) {
                console.error("No ibDetails data available to render.");
                return;
            }

            // let inspectionTableRows = '';
            // templateData.inspectionItems.forEach(item => {
            //     inspectionTableRows += `
            //         <tr>
            //             <td>${item.category}</td>
            //             <td>${item.procedure}</td>
            //             <td>${item.requirements}</td>
            //         </tr>
            //     `;
            // });
            // สร้างตัวแปร JavaScript สำหรับข้อมูลที่ตั้งสำนักงานใหญ่ (จากคำตอบก่อนหน้า)
            const certiIbDataForJs = {
                app_no: "{{ $certi_ib->app_no ?? 'CB-64-030' }}",
                name_unit: "{{ $certi_ib->name_unit ?? 'สถาบันพัฒนาบุคลากรการบิน มหาวิทยาลัยเกษมบัณฑิต' }}",
                hq_address: "{{ $certi_ib->hq_address ?? null }}",
                hq_moo: "{{ $certi_ib->hq_moo ?? null }}",
                hq_soi: "{{ $certi_ib->hq_soi ?? null }}",
                hq_road: "{{ $certi_ib->hq_road ?? null }}",
                hq_zipcode: "{{ $certi_ib->hq_zipcode ?? null }}",
                HqSubdistrictName: "{{ $certi_ib->HqSubdistrictName ?? null }}",
                HqDistrictName: "{{ $certi_ib->HqDistrictName ?? null }}",
                HqProvinceName: "{{ $certi_ib->HqProvinceName ?? null }}",
            };

            // สร้างตัวแปร JavaScript สำหรับข้อมูลสำนักงานสาขาและข้อมูลติดต่อ
            const branchDataForJs = {
                hq_telephone: "{{ $certi_ib->hq_telephone ?? null }}",
                hq_fax: "{{ $certi_ib->hq_fax ?? null }}",
                address_number: "{{ $certi_ib->address_number ?? null }}",
                allay: "{{ $certi_ib->allay ?? null }}",
                address_soi: "{{ $certi_ib->address_soi ?? null }}",
                address_street: "{{ $certi_ib->address_street ?? null }}",
                postcode: "{{ $certi_ib->postcode ?? null }}",
                district_id: "{{ $certi_ib->district_id ?? null }}", // ID ของตำบล
                amphur_id: "{{ $certi_ib->amphur_id ?? null }}",     // ID ของอำเภอ
                basic_province_name: "{{ $certi_ib->basic_province->PROVINCE_NAME ?? null }}",
                tel: "{{ $certi_ib->tel ?? null }}",
                tel_fax: "{{ $certi_ib->tel_fax ?? null }}",
            };

            // สร้างตัวแปร JavaScript สำหรับข้อมูลการตรวจประเมินและไฟล์แนบ
            const assessmentDataForJs = {
                standard_change_id: {{ $certi_ib->standard_change ?? 'null' }},
                files: []
            };

            // Loop ผ่าน FileAttachAssessment2Many และสร้าง Object สำหรับ JavaScript
            @if(isset($assessment) && $assessment->FileAttachAssessment2Many->count() > 0)
                @foreach($assessment->FileAttachAssessment2Many as $key => $item)
                    assessmentDataForJs.files.push({
                        index: {{ $key + 1 }},
                        url: "{{ url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' )) }}",
                        title: "{{ !empty($item->file_client_name) ? $item->file_client_name : basename($item->file) }}",
                        fileExtensionText: "{!! \App\Helpers\Helper::FileExtension($item->file) ?? '' !!}",
                        fileName: "{{ $item->file_client_name ?? '' }}"
                    });
                @endforeach
            @endif


            const templateHTML = `
                <table class="borderless header-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="header-request-cell">
                            <span>เลขที่คำขอ : ${certiIbDataForJs.app_no}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="header-title-cell">
                            <span>รายงานการตรวจประเมิน ณ สถานประกอบการ</span>
                        </td>
                    </tr>
                </table>
    

                <!-- Section 1: หน่วยตรวจ -->
 
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">1. หน่วยตรวจ : </td>
                        <td class="content-cell">
                            ${certiIbDataForJs.name_unit}
                        </td>
                    </tr>
                </table>
 

                <!-- Section 2: ที่ตั้งสำนักงานใหญ่ -->
 
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">2. ที่ตั้งสำนักงานใหญ่ : </td>
                        <td class="content-cell">
                            ${certiIbDataForJs.hq_address !== null && certiIbDataForJs.hq_address !== '' ? `เลขที่ ${certiIbDataForJs.hq_address}` : ''}
                            ${certiIbDataForJs.hq_moo !== null && certiIbDataForJs.hq_moo !== '' ? `หมู่${certiIbDataForJs.hq_moo}` : ''}
                            ${certiIbDataForJs.hq_soi !== null && certiIbDataForJs.hq_soi !== '' ? `ซอย${certiIbDataForJs.hq_soi}` : ''}
                            ${certiIbDataForJs.hq_road !== null && certiIbDataForJs.hq_road !== '' ? `ถนน${certiIbDataForJs.hq_road}` : ''}
                            ${certiIbDataForJs.HqProvinceName !== null && certiIbDataForJs.HqProvinceName.includes('กรุงเทพ') ?
                                `แขวง${certiIbDataForJs.HqSubdistrictName} เขต${certiIbDataForJs.HqDistrictName} ${certiIbDataForJs.HqProvinceName}` :
                                `ตำบล${certiIbDataForJs.HqSubdistrictName} อำเภอ${certiIbDataForJs.HqDistrictName} จังหวัด${certiIbDataForJs.HqProvinceName}`
                            }
                            ${certiIbDataForJs.hq_zipcode !== null && certiIbDataForJs.hq_zipcode !== '' ? certiIbDataForJs.hq_zipcode : ''}
                        </td>
                    </tr>
                </table>
 

                <!-- Section: โทรศัพท์/โทรสาร สำนักงานใหญ่ -->

                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;"></td>
                        <td class="content-cell">
                            <table class="borderless phone-fax-table" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 30%; padding: 0;">
                                        <span>โทรศัพท์: <span>${branchDataForJs.hq_telephone !== null ? branchDataForJs.hq_telephone : ''}</span> </span>
                                    </td>
                                    <td style="width: 50%; padding: 0;">
                                        <span>โทรสาร: <span>${branchDataForJs.hq_fax !== null ? branchDataForJs.hq_fax : ''}</span></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Section: ที่ตั้งสำนักงานสาขา -->

                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">&nbsp;&nbsp;&nbsp;ที่ตั้งสำนักงานสาขา : </td>
                        <td class="content-cell">
                            ${branchDataForJs.address_number !== null ? `เลขที่ ${branchDataForJs.address_number}` : ''}
                            ${branchDataForJs.allay !== null ? `หมู่${branchDataForJs.allay}` : ''}
                            ${branchDataForJs.address_soi !== null ? `ซอย${branchDataForJs.address_soi}` : ''}
                            ${branchDataForJs.address_street !== null ? `ถนน${branchDataForJs.address_street}` : ''}

                            ${branchDataForJs.basic_province_name !== null && branchDataForJs.basic_province_name.includes('กรุงเทพ') ?
                                `แขวง ${branchDataForJs.district_id} เขต${branchDataForJs.amphur_id} ${branchDataForJs.basic_province_name}` :
                                `ตำบล${branchDataForJs.district_id} อำเภอ${branchDataForJs.amphur_id} จังหวัด${branchDataForJs.basic_province_name}`
                            }
                            ${branchDataForJs.postcode !== null ? branchDataForJs.postcode : ''}
                        </td>
                    </tr>
                </table>
 

                <!-- Section: โทรศัพท์/โทรสาร สาขา -->
     
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;"></td>
                        <td class="content-cell">
                            <table class="borderless phone-fax-table" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 30%; padding: 0;">
                                        <span>โทรศัพท์: <span>${branchDataForJs.tel !== null ? branchDataForJs.tel : ''}</span> </span>
                                    </td>
                                    <td style="width: 50%; padding: 0;">
                                        <span>โทรสาร: <span>${branchDataForJs.tel_fax !== null ? branchDataForJs.tel_fax : ''}</span></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
 

                <!-- Section 3: ประเภทการตรวจประเมิน -->
 
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">3. ประเภทการตรวจประเมิน</td>
                        <td class="content-cell">
                            <table class="borderless checkbox-table" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td class="checkbox-item-cell">
                                        <input type="checkbox"  ${assessmentDataForJs.standard_change_id == 1 ? 'checked' : ''}>
                                        <label for="chk1">การตรวจประเมินรับรองครั้งแรก</label>
                                    </td>
                                    <td class="checkbox-item-cell">
                                        <input type="checkbox" >
                                        <label for="chk2">การตรวจติดตาม</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="checkbox-item-cell">
                                        <input type="checkbox"  ${assessmentDataForJs.standard_change_id == 2 ? 'checked' : ''}>
                                        <label for="chk3">การตรวจประเมินเพื่อต่ออายุการรับรอง</label>
                                    </td>
                                    <td class="checkbox-item-cell">
                                        <input type="checkbox"  ${assessmentDataForJs.standard_change_id !== 1 && assessmentDataForJs.standard_change_id !== 2 ? 'checked' : ''}>
                                        <label for="chk4">อื่น ๆ</label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>


                <!-- Section 4: สาขาและขอบข่ายการรับรองระบบงาน -->
 
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">4. สาขาและขอบข่ายการรับรองระบบงาน : </td>
                    </tr>
                    <tr>
                        <td class="content-cell">
                            หน่วยรับรองได้รับการรับรองระบบงาน สาขาหน่วยรับรองบุคลากร ในขอบข่าย บริการภาคพื้นและสนับสนุนการบิน ภาคพื้นในอาคาร อาชีพพนักงานต้อนรับผู้โดยสารภาคพื้น ระดับ 3 และสาขาพนักงานต้อนรับบนเครื่องบิน อาชีพพนักงานต้อนรับบนเครื่องบิน ระดับ 4 รายละเอียดขอบข่ายการรับรองดัง เอกสารแนบ 1
                        </td>
                    </tr>
                </table>

                <!-- Section 4: เกณฑIที่ใช[ในการตรวจประเมิน -->
 
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">5. เกณฑ์ที่ใช้ในการตรวจประเมิน : </td>
                    </tr>
                    <tr>
                        <td class="content-cell">
                            5.1 มอก. 17024-2556 การตรวจสอบและรับรอง – ข้อกำหนดทั่วไปสำหรับหนวยรับรองบุคลากร<br>
                            5.2 หลักเกณฑ์ วิธีการและเงื่อนไขการรับรองระบบงานของคณะกรรมการการมาตรฐานแห่งชาติ<br>
                            5.3 กฎกระทรวงกำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน<br>
                        </td>
                    </tr>
                </table>
                
                 <!-- Section 6: วันที่ตรวจประเมิน -->
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td  style="width: 30px;"><span class="label-cell">6. วันที่ตรวจประเมิน :</span> 24-25 มีนาคม 2568 </td>
                    </tr>
                </table>

                <!-- Section 7: คณะผู้ตรวจประเมิน -->
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">7. คณะผู้ตรวจประเมิน : </td>
                    </tr>
                    <tr>
                        <td class="content-cell">
                            (1) นางสาวราษี ศีลฑายุษย์ – หัวหน้าคณะผู้ตรวจประเมิน<br>
                            (2) นายสุพจน์ โปษยะจินดากาญจน์ – ผู้ตรวจประเมิน<br>
                            (3) นางสาวชบันท์ สิทธิภัณฑ์ – ผู้ตรวจประเมิน<br>
                            (4) นายชูสุ เลิศจุลิกานนท์ – ผู้เชี่ยวชาญ (เฉพาะวันที่ 25 มีนาคม 2568)
                        </td>
                    </tr>
                </table>

                <!-- Section 8: ผู้แทนหน่วยตรวจ -->
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label-cell" style="width: 150px;">8. คณะผู้ตรวจประเมิน : </td>
                    </tr>
                    <tr>
                        <td class="content-cell">
                            (1) นายภัทพงษ์ บุตรสมบูรณ์ – ผู้จัดการคุณภาพ<br>
                            (2) นางสาววรินทร อินตะนัย – ผู้ตรวจ/เจ้าหน้าที่ประสานงาน/เจ้าหน้าที่ควบคุมเอกสาร<br>
                        </td>
                    </tr>
                </table>
                <!-- Section 9: ผู้แทนหน่วยตรวจ -->
                <table class="borderless section-table" style="width: 100%; border-collapse: collapse;margin-left:-5px">
                    <tr>
                        <td  style="width: 30px;"><span class="label-cell">9. ผู้แทนหน่วยตรวจ :</span> รายละเอียดดัง เอกสารแบบ 2 </td>
                    </tr>
                </table>
                
                <b>10. รายระเอียดการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;<b> 10.1 ความเป็นมา</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บริษัท ....<br>
                    &nbsp;&nbsp;<b> 10.2 กระบวนการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คณะผู้ตรวจประเมินได้ตรวจ ....<br>
                    &nbsp;&nbsp;<b> 10.3 ประเด็นสำคัญจากการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;การเปลี่ยนแปลงที่ผ่านมา ....<br>
                    
                `;
            insertTemplateAtCurrentOrLastPage(templateHTML);
        };
        


        const updateMenubarState = () => {
             const commands = ['bold', 'italic', 'superscript', 'subscript', 'justifyLeft', 'justifyCenter', 'justifyRight'];
             commands.forEach(command => {
                 const button = menubar.querySelector(`[data-command="${command}"]`);
                 if (button) {
                     if (document.queryCommandState(command)) {
                         button.classList.add('active');
                     } else {
                         button.classList.remove('active');
                     }
                 }
             });

             const selection = window.getSelection();
             if (selection.rangeCount > 0 && selection.anchorNode) {
                 let element = selection.anchorNode;
                 if (element.nodeType !== Node.ELEMENT_NODE) {
                     element = element.parentNode;
                 }
                 if (element && element.closest('.page')) {
                     const size = window.getComputedStyle(element).getPropertyValue('font-size');
                     const sizeInPx = Math.round(parseFloat(size));
                     
                     const optionExists = [...fontSizeSelector.options].some(option => option.value == sizeInPx);
                     fontSizeSelector.value = optionExists ? sizeInPx : '';
                 }
             }
        };
        
        const managePages = () => {
            const pages = Array.from(editor.querySelectorAll('.page'));
            const selection = window.getSelection();
            let originalStartContainer = selection.rangeCount > 0 ? selection.getRangeAt(0).startContainer : null;
            let originalStartOffset = selection.rangeCount > 0 ? selection.getRangeAt(0).startOffset : 0;
            let cursorRelocated = false;

            pages.forEach((page) => {
                while (isOverflowing(page)) {
                    let nextPage = page.nextElementSibling;
                    if (!nextPage || !nextPage.classList.contains('page')) {
                        nextPage = createNewPage();
                        editor.insertBefore(nextPage, page.nextElementSibling);
                    }
                    
                    while (isOverflowing(page) && page.lastChild) {
                        const nodeToMove = page.lastChild;
                        if (originalStartContainer && nodeToMove.contains(originalStartContainer)) {
                            cursorRelocated = true;
                        }
                        nextPage.insertBefore(nodeToMove, nextPage.firstChild);
                    }
                }
            });

            let currentPages = Array.from(editor.querySelectorAll('.page'));
            if (currentPages.length > 1) {
                for (let i = currentPages.length - 1; i >= 0; i--) {
                    const page = currentPages[i];
                    const isEmpty = !page.textContent.trim() && (!page.firstElementChild || page.firstElementChild.tagName === 'BR');
                    if (isEmpty && currentPages.length > 1) {
                        if (originalStartContainer && page.contains(originalStartContainer)) {
                            const prevPage = page.previousElementSibling;
                            if (prevPage && prevPage.classList.contains('page')) {
                                moveCursorToEnd(prevPage);
                                cursorRelocated = true;
                            }
                        }
                        page.remove();
                    }
                }
            }

            currentPages = Array.from(editor.querySelectorAll('.page'));
            currentPages.forEach((page, index) => {
                const nextPage = currentPages[index + 1];
                if (nextPage) {
                    while (nextPage.firstChild && !isOverflowing(page)) {
                        const nodeToMove = nextPage.firstChild;
                        page.appendChild(nodeToMove);
                        if (isOverflowing(page)) {
                            nextPage.insertBefore(nodeToMove, nextPage.firstChild);
                            break;
                        }
                    }
                }
            });

            if (cursorRelocated) {
                const lastPage = editor.querySelector('.page:last-of-type');
                if (lastPage) {
                    moveCursorToEnd(lastPage);
                }
            } else if (originalStartContainer && originalStartContainer.isConnected) {
                try {
                    const range = document.createRange();
                    range.setStart(originalStartContainer, originalStartOffset);
                    range.collapse(true);
                    selection.removeAllRanges();
                    selection.addRange(range);
                } catch (e) {
                    console.warn("Failed to restore original cursor position:", e);
                    const lastPage = editor.querySelector('.page:last-of-type');
                    if (lastPage) {
                        moveCursorToEnd(lastPage);
                    }
                }
            } else {
                const lastPage = editor.querySelector('.page:last-of-type');
                if (lastPage) {
                    moveCursorToEnd(lastPage);
                }
            }
        };

        const moveCursorToEnd = (element) => {
            element.focus();
            const range = document.createRange();
            const selection = window.getSelection();
            range.selectNodeContents(element);
            range.collapse(false);
            selection.removeAllRanges();
            selection.addRange(range);
        };
        
        const isOverflowing = (element) => element.scrollHeight > element.clientHeight + 1;

        const createNewPage = () => {
            const newPage = document.createElement('div');
            newPage.className = 'page';
            newPage.setAttribute('contenteditable', 'true');
            return newPage;
        };

        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    insertImageAtCursor(e.target.result);
                };
                reader.readAsDataURL(file);
            }
            imageInput.value = '';
        });

        function insertImageAtCursor(src) {
            if (savedRange) {
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(savedRange);
            } else {
                (editor.querySelector('.page:focus') || editor.querySelector('.page'))?.focus();
            }

            const uniqueId = 'img-temp-' + Date.now();
            const handlesHTML = ['top-left', 'top-right', 'bottom-left', 'bottom-right']
                .map(pos => `<div class="resize-handle ${pos}"></div>`).join('');

            const imageHTML = `
                <span class="image-container" id="${uniqueId}" style="width: 200px;">
                    <img src="${src}" style="width: 100%; height: auto; display: block;" />
                    ${handlesHTML}
                </span>`;
            
            document.execCommand('insertHTML', false, imageHTML);
            
            const newImageContainer = document.getElementById(uniqueId);
            if (newImageContainer) {
                newImageContainer.removeAttribute('id');
                makeResizable(newImageContainer);
            }
            savedRange = null;
        }
        
        function makeResizable(element) {
            let activeHandle = null;
            let startX, startY, startWidth;

            function onMouseDown(e) {
                e.preventDefault();
                e.stopPropagation();

                document.querySelectorAll('.image-container.active').forEach(el => el.classList.remove('active'));
                element.classList.add('active');

                if (e.target.classList.contains('resize-handle')) {
                    activeHandle = e.target;
                } else {
                    activeHandle = null;
                    return;
                }
                
                startX = e.clientX;
                startY = e.clientY;
                startWidth = element.offsetWidth;

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            }

            function onMouseMove(e) {
                if (!activeHandle) return;

                const dx = e.clientX - startX;
                
                if (activeHandle.classList.contains('bottom-right') || activeHandle.classList.contains('top-right')) {
                    element.style.width = `${Math.max(20, startWidth + dx)}px`;
                } else if (activeHandle.classList.contains('bottom-left') || activeHandle.classList.contains('top-left')) {
                    element.style.width = `${Math.max(20, startWidth - dx)}px`;
                }
                element.style.height = 'auto';
            }

            function onMouseUp() {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
                activeHandle = null;
            }

            element.addEventListener('mousedown', onMouseDown);
        }
        
        // --- Table & Context Menu Functions ---

        // === START: MODIFICATION ===
        function insertTableRow(table, rowIndex, above, removeConnectingBorder) {
            const insertAt = above ? rowIndex : rowIndex + 1;
            const newRow = table.insertRow(insertAt);

            // Determine the correct number of columns to create
            let colCount = 0;
            const thead = table.querySelector('thead');
            if (thead && thead.rows.length > 0 && thead.rows[0].cells.length > 0) {
                colCount = thead.rows[0].cells.length;
            } else if (table.rows.length > 1) {
                // Find a row with the most cells to account for colspans
                for(let i = 0; i < table.rows.length; i++) {
                    let currentCellCount = 0;
                    for(let j = 0; j < table.rows[i].cells.length; j++) {
                        currentCellCount += table.rows[i].cells[j].colSpan;
                    }
                    if(currentCellCount > colCount) {
                        colCount = currentCellCount;
                    }
                }
            } else {
                colCount = table.rows[rowIndex].cells.length;
            }


            // Create cells for the new row
            for (let i = 0; i < colCount; i++) {
                const cell = newRow.insertCell();
                cell.style.verticalAlign = 'top';
                cell.style.textAlign = 'left';
                cell.innerHTML = '<br>';
            }

            // --- Conditional Border Logic ---
            if (removeConnectingBorder) {
                if (!above) { // Inserting BELOW
                    const rowAbove = table.rows[rowIndex];
                    for (const cell of newRow.cells) {
                        cell.style.borderTop = 'none';
                    }
                    if (rowAbove) {
                        for (const cell of rowAbove.cells) {
                            cell.style.borderBottom = 'none';
                        }
                    }
                } else { // Inserting ABOVE
                    const rowBelow = table.rows[insertAt];
                    for (const cell of newRow.cells) {
                        cell.style.borderBottom = 'none';
                    }
                    if (rowBelow) {
                        for (const cell of rowBelow.cells) {
                            cell.style.borderTop = 'none';
                        }
                    }
                }
            }
            // If removeConnectingBorder is false, do nothing to the borders.

            managePages();
        }
        // === END: MODIFICATION ===

        function insertTableColumn(table, colIndex, right = true) {
            const rows = table.rows;
            for (let i = 0; i < rows.length; i++) {
                const cell = rows[i].insertCell(right ? colIndex + 1 : colIndex);
                cell.style.verticalAlign = 'top';
                cell.style.textAlign = 'left';
                cell.innerHTML = '<br>';
            }
            managePages();
        }

        function deleteTableRow(table, rowIndex) {
            if (table.rows.length > 1) {
                table.deleteRow(rowIndex);
                managePages();
            }
        }

        function deleteTableColumn(table, colIndex) {
            if (table.rows[0].cells.length > 1) {
                for (let i = 0; i < table.rows.length; i++) {
                    table.rows[i].deleteCell(colIndex);
                }
                managePages();
            }
        }
        
        function getSelectedTableCells() {
            const selection = window.getSelection();
            if (!selection.rangeCount || selection.isCollapsed) return [];

            const range = selection.getRangeAt(0);
            let startCell = range.startContainer.closest('td, th');
            let endCell = range.endContainer.closest('td, th');

            if (!startCell || !endCell || startCell.closest('table') !== endCell.closest('table')) {
                return [];
            }
            
            if (startCell.compareDocumentPosition(endCell) & Node.DOCUMENT_POSITION_FOLLOWING) {
                // Correct order
            } else {
                [startCell, endCell] = [endCell, startCell]; // Swap
            }

            const row = startCell.closest('tr');
            if (!row || endCell.closest('tr') !== row) {
                return [];
            }

            const cellsInRow = Array.from(row.cells);
            const startIndex = cellsInRow.indexOf(startCell);
            const endIndex = cellsInRow.indexOf(endCell);

            if (startIndex === -1 || endIndex === -1) return [];

            return cellsInRow.slice(startIndex, endIndex + 1);
        }

        function mergeTableColumns(cellsToMerge) {
            if (cellsToMerge.length <= 1) return;

            const firstCell = cellsToMerge[0];
            const parentRow = firstCell.closest('tr');
            if (!parentRow) return;

            let totalColspan = 0;
            let combinedContent = '';

            cellsToMerge.forEach(cell => {
                const cellContent = cell.innerHTML.trim();
                if (cellContent !== '<br>' && cellContent !== '') {
                    if (combinedContent !== '') combinedContent += ' ';
                    combinedContent += cellContent;
                }
                totalColspan += cell.colSpan || 1;
            });

            firstCell.colSpan = totalColspan;
            firstCell.innerHTML = combinedContent || '<br>';

            for (let i = 1; i < cellsToMerge.length; i++) {
                parentRow.removeChild(cellsToMerge[i]);
            }
            
            const activePage = firstCell.closest('.page');
            activePage?.focus();
            managePages();
        }


        function showContextMenu(event, cell) {
            event.preventDefault();
            contextMenuTarget = cell;
            contextMenuTargetRow = cell.closest('tr'); // Store the target row immediately
            
            const addItemMenu = contextMenu.querySelector('[data-action="add-item"]');
            const addItemSeparator = contextMenu.querySelector('[data-action="separator-add"]');
            const mergeMenuItem = contextMenu.querySelector('[data-action="merge-columns"]');
            const mergeSeparator = contextMenu.querySelector('[data-action="separator-merge"]');

            const table = cell.closest('table');
            const isInTbody = cell.closest('tbody');

            // Show "Add Item" only for tables with 'detail-table' class and inside tbody
            if (table && table.classList.contains('detail-table') && isInTbody) {
                addItemMenu.style.display = 'block';
                addItemSeparator.style.display = 'block';
            } else {
                addItemMenu.style.display = 'none';
                addItemSeparator.style.display = 'none';
            }

            const selectedCells = getSelectedTableCells();
            if (selectedCells.length > 1) {
                selectedTableCellsForMerge = selectedCells;
                mergeMenuItem.style.display = 'block';
                mergeSeparator.style.display = 'block';
            } else {
                mergeMenuItem.style.display = 'none';
                mergeSeparator.style.display = 'none';
            }

            contextMenu.style.display = 'block';
            contextMenu.style.left = `${event.pageX}px`;
            contextMenu.style.top = `${event.pageY}px`;
        }

        function hideContextMenu() {
            contextMenu.style.display = 'none';
            contextMenuTarget = null;
            contextMenuTargetRow = null; // Reset the target row
            selectedTableCellsForMerge = [];
        }

        editor.addEventListener('contextmenu', (event) => {
            const cell = event.target.closest('td, th');
            if (cell) {
                showContextMenu(event, cell);
            } else {
                hideContextMenu();
            }
        });

        // --- FIXED: Context Menu Click Logic ---
        // === START: MODIFICATION ===
        contextMenu.addEventListener('click', (event) => {
            const actionTarget = event.target.closest('.context-menu-item');
            if (!actionTarget) return;
            const action = actionTarget.dataset.action;
            if (!action) return;

            const table = contextMenuTarget?.closest('table');

            // Special handling for actions that open modals
            if (action === 'add-item') {
                activeModalTargetRow = contextMenuTargetRow; // Persist the row for the modal
                contextMenu.style.display = 'none'; // Hide menu visually, but keep state
                switch (templateType) {
                    case 'cb':      cbItemModal.style.display = 'flex'; break;
                    case 'ib':      ibItemModal.style.display = 'flex'; break;
   
                    default:
                        alert('ไม่พบ Template ที่ใช้งานอยู่เพื่อเพิ่มรายการ');
                        hideContextMenu(); // Reset state fully if no template
                        activeModalTargetRow = null; // Clear persisted row if no modal shown
                }
                return; // Exit to prevent hideContextMenu() below
            }

            // Handle merge action
            if (action === 'merge-columns') {
                if (selectedTableCellsForMerge.length > 1) {
                    mergeTableColumns(selectedTableCellsForMerge);
                }
                hideContextMenu(); // Reset state fully
                return;
            }

            // For direct table manipulation actions
            if (!contextMenuTarget || !table) {
                hideContextMenu();
                return;
            }

            const row = contextMenuTargetRow;
            const rowIndex = row ? Array.from(table.rows).indexOf(row) : -1;
            const colIndex = row ? Array.from(row.cells).indexOf(contextMenuTarget) : -1;

            if (rowIndex === -1 || colIndex === -1) {
                hideContextMenu();
                return;
            }

            switch (action) {
                case 'insert-row-above':
                    insertTableRow(table, rowIndex, true, false); // above, with border
                    break;
                case 'insert-row-above-no-border':
                    insertTableRow(table, rowIndex, true, true); // above, no border
                    break;
                case 'insert-row-below':
                    insertTableRow(table, rowIndex, false, false); // below, with border
                    break;
                case 'insert-row-below-no-border':
                    insertTableRow(table, rowIndex, false, true); // below, no border
                    break;
                case 'insert-column-left':
                    insertTableColumn(table, colIndex, false);
                    break;
                case 'insert-column-right':
                    insertTableColumn(table, colIndex, true);
                    break;
                case 'delete-row':
                    deleteTableRow(table, rowIndex);
                    break;
                case 'delete-column':
                    deleteTableColumn(table, colIndex);
                    break;
            }

            hideContextMenu(); // Reset state fully after action
            const activePage = table?.closest('.page');
            activePage?.focus();
        });
        // === END: MODIFICATION ===


        document.addEventListener('click', (event) => {
            // Hide context menu if the click is outside of it AND not inside a modal overlay
            if (!contextMenu.contains(event.target) && !event.target.closest('.modal-overlay')) {
                hideContextMenu();
            }
        });

        editor.addEventListener('input', () => {
             setTimeout(() => { managePages(); updateMenubarState(); }, 10);
        });
        editor.addEventListener('keyup', updateMenubarState);
        editor.addEventListener('mouseup', updateMenubarState);
        document.addEventListener('selectionchange', updateMenubarState);
        editor.addEventListener('keydown', (event) => {
            const selection = window.getSelection();
            if (!selection.rangeCount) return;
            const range = selection.getRangeAt(0);
            if (selection.isCollapsed && range.startOffset === 0) {
                let currentPage = range.startContainer;
                while (currentPage && !currentPage.classList?.contains('page')) currentPage = currentPage.parentElement;
                if (currentPage?.classList.contains('page')) {
                    const prevPage = currentPage.previousElementSibling;
                    if (event.key === 'Backspace' && prevPage) {
                        event.preventDefault();
                        while (currentPage.firstChild) prevPage.appendChild(currentPage.firstChild);
                        moveCursorToEnd(prevPage);
                        setTimeout(managePages, 10);
                    }
                }
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Delete') {
                const activeImage = document.querySelector('.image-container.active');
                if (activeImage) {
                    event.preventDefault();
                    activeImage.remove();
                    const lastActivePage = editor.querySelector('.page:focus') || editor.querySelector('.page');
                    lastActivePage?.focus();
                    setTimeout(managePages, 10);
                }
            }
        });
        
        // === START: NEW KEYBOARD SHORTCUTS FOR TABLE ROWS ===
        document.addEventListener('keydown', (event) => {
            // Check for Shift key and F1, F2, F4, F5 keys
            if (event.shiftKey && ['F1', 'F2', 'F4', 'F5'].includes(event.key)) {
                const selection = window.getSelection();
                if (!selection.rangeCount) return;

                const range = selection.getRangeAt(0);
                const currentElement = range.startContainer;
                // Find the closest cell (td or th) from the current cursor position
                const cell = currentElement.nodeType === Node.ELEMENT_NODE 
                             ? currentElement.closest('td, th') 
                             : currentElement.parentElement.closest('td, th');

                if (cell) {
                    event.preventDefault(); // Prevent default browser actions (like opening help)

                    const table = cell.closest('table');
                    const row = cell.closest('tr');
                    const rowIndex = Array.from(table.rows).indexOf(row);

                    if (rowIndex === -1) return;

                    switch (event.key) {
                        case 'F1': // Shift+F1: Insert row above
                            insertTableRow(table, rowIndex, true, false);
                            break;
                        case 'F2': // Shift+F2: Insert row above (no border)
                            insertTableRow(table, rowIndex, true, true);
                            break;
                        case 'F4': // Shift+F4: Insert row below
                            insertTableRow(table, rowIndex, false, false);
                            break;
                        case 'F5': // Shift+F5: Insert row below (no border)
                            insertTableRow(table, rowIndex, false, true);
                            break;
                    }
                       const activePage = table?.closest('.page');
                         activePage?.focus();
                }
            }
        });
        // === END: NEW KEYBOARD SHORTCUTS FOR TABLE ROWS ===


        document.addEventListener('mousedown', (event) => {
            if (!event.target.closest('.image-container')) {
                document.querySelectorAll('.image-container.active').forEach(el => {
                    el.classList.remove('active');
                });
            }
        });

        insertTableBtn.addEventListener('click', () => {
            const rows = parseInt(tableRowsInput.value, 10);
            const cols = parseInt(tableColsInput.value, 10);
            const hasBorders = tableBorderToggle.checked;
            
            tableModalOverlay.style.display = 'none';

            if (isNaN(rows) || isNaN(cols) || rows <= 0 || cols <= 0) return;

            if (savedRange) {
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(savedRange);
            } else {
                (editor.querySelector('.page:focus') || editor.querySelector('.page'))?.focus();
            }

            const tableClass = hasBorders ? '' : 'class="borderless"';
            let tableHTML = `<table ${tableClass} style="width: 100%; margin-bottom: 1em;"><tbody>`;
            for (let i = 0; i < rows; i++) {
                tableHTML += '<tr>';
                for (let j = 0; j < cols; j++) {
                    tableHTML += '<td style="vertical-align: top; text-align: left;"><br></td>';
                }
                tableHTML += '</tr>';
            }
            tableHTML += '</tbody></table><p><br></p>';
            document.execCommand('insertHTML', false, tableHTML);
            
            savedRange = null;
            tableBorderToggle.checked = true;
        });

        // --- FIXED: Modal Cancel/Close Logic ---
        function closeModal(modal) {
            modal.style.display = 'none';
            // Clear inputs
            modal.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => input.value = '');
            // MODIFIED: Also clear contenteditable divs
            modal.querySelectorAll('.editable-div').forEach(div => div.innerHTML = '');
            // Also reset the context menu state since the modal action is complete.
            hideContextMenu();
            activeModalTargetRow = null; // **NEW**: Clear the persisted row
        }

        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });
            modal.querySelector('.modal-btn-cancel')?.addEventListener('click', () => {
                closeModal(modal);
            });
        });
        
        // --- [แก้ไข] Helper function to append text to a cell ---
        function appendToCell(cell, text) {
            // Do nothing if text is null, undefined, or empty
            if (!text) {
                return;
            }

            // Check if the cell has any visible content using textContent.
            // This is more reliable than manipulating innerHTML with regex.
            const hasContent = cell.textContent.trim() !== '';

            if (hasContent) {
                // If there's existing content, add a line break before the new text.
                cell.innerHTML += '<br>' + text;
            } else {
                // If the cell is empty, just set the new text, replacing any placeholder <br>.
                cell.innerHTML = text;
            }
        }


        // --- Add Item Button Logic for each Modal (Using activeModalTargetRow) ---
        
        // === START: REVISED CB Item Button Logic ===
        document.getElementById('add-cb-item-btn').addEventListener('click', () => {
            const targetRow = activeModalTargetRow;
            if (!targetRow) {
                alert("ไม่สามารถหาแถวเป้าหมายได้");
                closeModal(cbItemModal);
                return;
            }

            const cells = targetRow.cells;
            if (cells.length < 2) {
                alert("โครงสร้างตารางไม่ถูกต้อง (ต้องการอย่างน้อย 2 คอลัมน์)");
                closeModal(cbItemModal);
                return;
            }

            // Get values from editable divs using LineExtractor
            const codeLines = cbCodeEditorExtractor.getLines();
            const code = codeLines.join('<br>');

            const descriptionLines = cbDescriptionEditorExtractor.getLines();
            const description = descriptionLines.join('<br>');

            // Append to the first cell
            appendToCell(cells[0], code);

            // Append to the second cell
            appendToCell(cells[1], description);

            managePages();
            closeModal(cbItemModal);
        });
        // === END: REVISED CB Item Button Logic ===


        // === START: REVISED IB Item Button Logic ===
        document.getElementById('add-ib-item-btn').addEventListener('click', () => {
            const targetRow = activeModalTargetRow;
            if (!targetRow) {
                alert("ไม่สามารถหาแถวเป้าหมายได้");
                closeModal(ibItemModal);
                return;
            }

            const cells = targetRow.cells;
            if (cells.length < 3) {
                alert("โครงสร้างตารางไม่ถูกต้อง (ต้องการ 3 คอลัมน์)");
                closeModal(ibItemModal);
                return;
            }

            // Get values from modal
            const mainBranch = document.getElementById('ib-main-branch').value.trim();

            const subBranchLines = ibSubBranchExtractor.getLines();
            const subBranch = subBranchLines.map(line => line ? '&nbsp;&nbsp;&nbsp;' + line : '').join('<br>');

            const mainScopeLines = ibMainScopeExtractor.getLines();
            const mainScope = mainScopeLines.map(line => line ? '&nbsp;&nbsp;&nbsp;' + line : '').join('<br>');

            const subScopeLines = ibSubScopeExtractor.getLines();
            const subScope = subScopeLines.map(line => line ? '&nbsp;&nbsp;&nbsp;' + line : '').join('<br>');

            const requirementsLines = ibRequirementsExtractor.getLines();
            const requirements = requirementsLines.join('<br>');

            // --- Cell 1: Main/Sub Branch ---
            const cell1Parts = [];
            if (mainBranch) cell1Parts.push(mainBranch);
            if (subBranch) cell1Parts.push(subBranch);
            const cell1Content = cell1Parts.join('<br>');
            appendToCell(cells[0], cell1Content);

            // --- Cell 2: Main/Sub Scope ---
            const cell2Parts = [];
            if (mainScope) cell2Parts.push(mainScope);
            if (subScope) cell2Parts.push(subScope);
            const cell2Content = cell2Parts.join('<br>');
            appendToCell(cells[1], cell2Content);

            // --- Cell 3: Requirements ---
            if (requirements) {
                appendToCell(cells[2], requirements);
            }

            managePages();
            closeModal(ibItemModal);
        });
        // === END: REVISED IB Item Button Logic ===



        exportPdfButton.addEventListener('click', () => {
            const editorClone = editor.cloneNode(true);
            const pagesContent = [];

            editorClone.querySelectorAll('.page').forEach(page => {
                page.removeAttribute('contenteditable');
                
                page.querySelectorAll('.image-container').forEach(container => {
                    const containerWidth = container.style.width;
                    const img = container.querySelector('img');
                    if (img && containerWidth) {
                        img.style.width = containerWidth;
                        img.style.height = 'auto';
                    }
                    container.querySelectorAll('.resize-handle').forEach(handle => handle.remove());
                    container.classList.remove('active');
                    container.style.border = 'none';
                });

                // wrapSpecialCharactersInNode(page);
                
                pagesContent.push(page.innerHTML); 
            });

            fetch('/export-pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ html_pages: pagesContent })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Network response was not ok');
                    });
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                window.open(url);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert('เกิดข้อผิดพลาดในการสร้าง PDF: ' + error.message);
            });
        });

        if (saveTemplateButton) {
            saveTemplateButton.addEventListener('click', () => {

                const editorClone = editor.cloneNode(true);
                const pagesContent = [];

                editorClone.querySelectorAll('.page').forEach(page => {
                    page.removeAttribute('contenteditable');

                    page.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        if (checkbox.checked) {
                            checkbox.setAttribute('checked', 'checked');
                        } else {
                            checkbox.removeAttribute('checked');
                        }
                    });

                    page.querySelectorAll('.image-container').forEach(container => {
                        const containerWidth = container.style.width;
                        const img = container.querySelector('img');
                        if (img && containerWidth) {
                            img.style.width = containerWidth;
                            img.style.height = 'auto';
                        }
                        container.querySelectorAll('.resize-handle').forEach(handle => handle.remove());
                        container.classList.remove('active');
                        container.style.border = 'none';
                    });

                    wrapSpecialCharactersInNode(page);

                    pagesContent.push(page.innerHTML);
                });


                console.log(pagesContent);

                fetch('/save-html-template', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        html_pages: pagesContent,
                        template_type: templateType
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    console.log('Save successful:', data);
                })
                .catch(error => {
                    console.error('There was a problem with the save operation:', error);
                    alert('เกิดข้อผิดพลาดในการบันทึก: ' + error.message);
                });
            });
        }
        
        if (loadTemplateButton) {
            loadTemplateButton.addEventListener('click', () => {
                const templateIdentifier = prompt("โปรดระบุประเภทของเทมเพลตที่ต้องการโหลด (เช่น 'cb', 'ib'):");
                if (!templateIdentifier) {
                    alert("ไม่ได้ระบุประเภทของเทมเพลต");
                    return;
                }

                fetch('/download-html-template', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        template_type: templateIdentifier
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.html_pages && Array.isArray(data.html_pages)) {
                        while (editor.firstChild) {
                            editor.removeChild(editor.firstChild);
                        }

                        console.log(data.html_pages)
                        data.html_pages.forEach(pageHtml => {
                            const newPage = createNewPage();
                            newPage.innerHTML = pageHtml;
                            editor.appendChild(newPage);
                        });
                        console.log('Load successful:', data);
                    } else {
                        throw new Error(data.message || 'ไม่พบข้อมูลเทมเพลต หรือข้อมูลไม่ถูกต้อง');
                    }
                })
                .catch(error => {
                    console.error('มีปัญหาในการโหลดเทมเพลต:', error);
                    alert('เกิดข้อผิดพลาดในการโหลด: ' + error.message);
                });
            });
        }

        // === START: MODIFICATION FOR LineExtractor INSTANCES ===
        // Instantiate LineExtractor for all editable divs in the modals
        const cbCodeEditorExtractor = new LineExtractor('cb-code-editor');
        const cbDescriptionEditorExtractor = new LineExtractor('cb-description-editor');
        
        // New extractors for the modified IB modal
        const ibSubBranchExtractor = new LineExtractor('ib-sub-branch');
        const ibMainScopeExtractor = new LineExtractor('ib-main-scope');
        const ibSubScopeExtractor = new LineExtractor('ib-sub-scope');
        const ibRequirementsExtractor = new LineExtractor('ib-requirements-editor');
        // === END: MODIFICATION FOR LineExtractor INSTANCES ===

        if (editor.children.length === 0) {
            editor.appendChild(createNewPage());
        } else {
            Array.from(editor.children).forEach(page => page.setAttribute('contenteditable', 'true'));
        }

        fontSizeSelector.value = "20";

    </script>
</body>
</html>
