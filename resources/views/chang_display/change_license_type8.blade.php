@php
    $tbl_factoryID = collect($change_details)->where('change_field', 'tbl_factoryID')->first();
@endphp
<div>
    เปลี่ยนแปลงเลขทะเบียนโรงงาน
    <ul style="margin: 0;">
        <li>จาก {{ @$tbl_factoryID->change_from }}</li>
        <li>เป็น {{ @$tbl_factoryID->change_to }}</li>
    </ul>
</div>