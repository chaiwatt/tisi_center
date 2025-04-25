@php
    $tbl_tradeAddress = collect($change_details)->where('change_field', 'tbl_tradeAddress')->first();
@endphp
<div>
    เปลี่ยนแปลงที่ตั้งสำนักงานแห่งใหญ่
    <ul style="margin: 0;">
        <li>ที่ตั้งสำนักงานใหญ่เดิม {{ @$tbl_tradeAddress->change_from }}</li>
        <li>ที่ตั้งสำนักงานใหญ่ใหม่ {{ @$tbl_tradeAddress->change_to }}</li>
    </ul>
</div>