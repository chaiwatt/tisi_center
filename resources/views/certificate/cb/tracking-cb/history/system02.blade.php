 
@if(!is_null($history->details_one))
@php 
$details_one = json_decode($history->details_one);
@endphp 
<div class="row">
<div class="col-md-3 text-right">
<p class="text-nowrap">เจ้าหน้าที่ได้รับมอบหมาย :</p>
</div>
<div class="col-md-9 text-left">
@foreach($details_one as $item)
     <p> 
        {!! $item->reg_fname !!}
    </p>
@endforeach
</div>
</div>
@endif

@if(!is_null($history->created_at)) 
<div class="row">
<div class="col-md-3 text-right">
    <p >วันที่บันทึก :</p>
</div>
<div class="col-md-9 text-left">
    {{ @HP::DateThai($history->created_at) ?? '-' }}
</div>
</div>
@endif