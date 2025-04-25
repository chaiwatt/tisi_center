@php
    $tbl_tradeName = collect($change_details)->where('change_field', 'tbl_tradeName')->first();
    $tbl_taxpayer = collect($change_details)->where('change_field', 'tbl_taxpayer')->first();
    $tbl_tradeAddress = collect($change_details)->where('change_field', 'tbl_tradeAddress')->first();
@endphp
<div>
    โอนใบอนุญาต 
    <ul style="margin: 0;">
        <li>ผู้ขอโอนใบอนุญาต {{ @$tbl_tradeName->change_from }}</li>
        <li>สำนักงานแห่งใหญ่ตั้งอยู่เลขที่ {{ @$tbl_tradeAddress->change_from }}</li>
        <li>เลขประจำตัวผู้เสียภาษีอาการ {{ @$tbl_taxpayer->change_from }}</li>
        <li>ผู้ขอรับโอนใบอนุญาต {{ @$tbl_tradeName->change_to }}</li>
        <li>สำนักงานแห่งใหญ่ตั้งอยู่เลขที่ {{ @$tbl_tradeAddress->change_to }}</li>
        <li>เลขประจำตัวผู้เสียภาษีอาการ {{ @$tbl_taxpayer->change_to }}</li>
    </ul>
</div>