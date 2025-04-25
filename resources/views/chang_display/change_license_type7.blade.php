@php
    $tbl_factoryAddress = collect($change_details)->where('change_field', 'tbl_factoryAddress')->first();
@endphp
<div>
    เปลี่ยนแปลงที่ตั้งโรงงานที่ทำที่ไม่ใช่การย้ายสถานที่ในประเทศ
    <ul style="margin: 0;">
        <li>ที่ตั้งโรงงานเดิม {{ @$tbl_factoryAddress->change_from }}</li>
        <li>ที่ตั้งโรงงานใหม่ {{ @$tbl_factoryAddress->change_to }}</li>
    </ul>
</div>