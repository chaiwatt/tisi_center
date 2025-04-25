@php
    $tbl_tradeName = collect($change_details)->where('change_field', 'tbl_tradeName')->first();
@endphp
<div>
    เปลี่ยนแปลงชื่อผู้รับใบอนุญาต
    <ul style="margin: 0;">
        <li>จาก {{ @$tbl_tradeName->change_from }}</li>
        <li>เป็น {{ @$tbl_tradeName->change_to }}</li>
    </ul>
</div>