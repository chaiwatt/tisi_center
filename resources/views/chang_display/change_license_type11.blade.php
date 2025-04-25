@php
    $tbl_factoryName = collect($change_details)->where('change_field', 'tbl_factoryName')->first();
@endphp
<div>
    เปลี่ยนแปลงชื่อโรงงานผู้ทำผลิตภัณฑ์อุตสาหกรรมในต่างประเทศ
    <ul style="margin: 0;">
        <li>จาก {{ @$tbl_factoryName->change_from }}</li>
        <li>เป็น {{ @$tbl_factoryName->change_to }}</li>
    </ul>
</div>