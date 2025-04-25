@php
    $tbl_tradeAddress = collect($change_details)->where('change_field', 'tbl_tradeAddress')->first();
@endphp
<div>
    ย้ายสำนักงานแห่งใหญ่ที่ระบุไว้ในใบอนุญาต 
    <ul style="margin: 0;">
        <li>จากเลขที่ {{ @$tbl_tradeAddress->change_from }}</li>
        <li>ไปยังเลขที่ {{ @$tbl_tradeAddress->change_to }}</li>
    </ul>
</div>