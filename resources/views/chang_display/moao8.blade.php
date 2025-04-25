@php
    $tbl_factoryID = collect($change_details)->where('change_field', 'tbl_factoryID')->first();
    $tbl_factoryAddress = collect($change_details)->where('change_field', 'tbl_factoryAddress')->first();
@endphp
<div>
    ขอย้ายสถานที่ที่ระบุไว้ในใบอนุญาต 
    <ul style="margin: 0;">
        <li>จากเลขที่ {{ @$tbl_factoryAddress->change_from }}</li>
        <li>จากทะเบียนโรงงานเลขที่ {{ @$tbl_factoryID->change_from }}</li>
        <li>ไปยังเลขที่ {{ @$tbl_factoryAddress->change_to }}</li>
        <li>ไปยังทะเบียนโรงงานเลขที่ {{ @$tbl_factoryID->change_to }}</li>
    </ul>
</div>