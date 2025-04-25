
<style>
 
    body {
        /* font-family: 'THSarabunNew', sans-serif; */
        font-family: 'thiasarabun', sans-serif;
    }
    .content{
            padding-top: 5%;
            padding-left: 0%;
            padding-right: 0%;
            margin: 0px;
            height: 100%;
      
            position: relative;
    
        }

  
    .font-16{
        font-size: 16pt;
    } 
    .font-20{
        font-size: 20pt;
    } 
 
    .padding-bottom-6{
        padding-bottom: -6px;
    } 
  
   .custom-label{
        background: #ffffff;
        border-bottom: thin dotted #ffffff; 
        padding-bottom: 5px;
      }

    .line-height25  {
        line-height:25px;
        padding-top: -5px;
    }
    .box-border {
        border: 1px solid #000000;
    }
    .box-border-right {
        border-right: 1px solid #000000;
    }
    .box-border-left  {
        border-left : 1px solid #000000;
    }
    .box-border-top  {
        border-top : 1px solid #000000;
    }
    .box-border-bottom  {
        border-bottom : 1px solid #000000;
    }

    .box-white  {
        border : 1px solid #ffffff;
    }
    .box-bottom-white  {
        border-bottom : 1px solid #ffffff;
    }

    .free-dot {
			border-bottom: thin dotted #000000;
            padding-bottom: -5px !important;
		}

 

</style>
<div class="content">

<table width="100%"  style="padding-top: -50px;" > 
    <tr>
    <td width="33%">  </td>
    <td  width="30%"> 
             
    </td>
     <td class="font-10" width="36%"  align="right"> 
          <p>ที่ สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</p>      
          <p>(ส่วนราชการเป็นผู้ออกให้)</p>      
     </td>
    </tr>
</table>


 

 <table width="100%"   > 
    <tr>
    <td width="33%">  </td>
    <td class="font-20" width="33%"  align="center"> 
             <b>ใบสำคัญรับเงิน</b>
    </td>
     <td width="33%"> </td>
    </tr>
</table>
 
<table width="100%" > 
    <tr>
    <td width="33%">  </td>
    <td class="font-20" width="33%" > 
           
    </td>
     <td width="10%" class="font-16 padding-bottom-6" valign="top"  align="right">
          วันที่
    </td>
      <td width="25%" class="font-16 free-dot" valign="top" align="center">
            
     </td>
     <td width="5%" class="font-16 padding-bottom-6" valign="top" >
       
     </td>
    </tr>
</table>

@if (count($items) > 0)
        @foreach ($items as $key => $item)
            @if ($key == 0)
            <table width="100%"   > 
                <tr>
                    <td width="10%" class="font-16 padding-bottom-6"   valign="top"  align="right">
                        ข้าพเจ้า
                    </td>
                     <td width="35%" class="font-16  free-dot "  valign="top"   align="center" >
                        {!! !empty($item->name) ? $item->name : '' !!}
                    </td>
                    <td width="5%" class="font-16  padding-bottom-6  "  valign="top"  align="center" >
                        ที่อยู่
                    </td>
                    <td width="50%" class="font-16 free-dot"  valign="top" >
                        {!! !empty($item->address) ? $item->address : '' !!}
                    </td>
                </tr>
            </table>
            @else 
            <table width="100%"   > 
                <tr>
                    <td width="100%" class="font-16 free-dot"  valign="top" >
                         {!! !empty($item->address) ? $item->address : '' !!}
                    </td>
                </tr>
            </table>
            @endif
        @endforeach
@endif

<table width="100%"   > 
    <tr>
        <td width="100%" class="font-16 padding-bottom-6" >
            ได้รับเงินจากสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรม ดังรายการต่อไปนี้
        </td>
    </tr>
</table>
 
<table width="100%"   style="padding-top: -15px;" >
    <tr>
        <td width="100%" class="font-16 padding-bottom-6" >
             &nbsp;
        </td>
    </tr>
</table>

<table width="100%" class="detail " style="border-collapse: collapse;border: 1px solid blac;" > 
 <tr>
    <td class="font-16 box-border-right box-border-bottom "  valign="top"  width="10%"  align="center"> 
        <b>ลำดับที่</b>
    </td>
    <td class="font-16 box-border-right box-border-bottom "   valign="top" width="80%" align="center"> 
        <b>รายการ</b>
     </td>
    <td class="font-16 box-border-bottom " width="20%" valign="top" align="center">
        <b>จำนวนเงิน</b>
    </td>
 </tr>

 <tr>
    <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
        {{ $x }}
    </td>
    <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" >  
         &nbsp;{!! $recepts_text  !!}
     </td>
    <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="center">
        
    </td>
 </tr>

 @php
     $config = HP::getConfig();
 
     $totals = 0;
     $i = 0;

 @endphp

@if (count($datas) > 0)
    @foreach ($datas as  $key => $data)
@php
    $i  += 1;
@endphp
<tr>
    <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
        
    </td>
    <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" > 
        &nbsp;{!! $data->text ?? '' !!}
    </td>
    <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="right"> 
        @php
            $totals += !empty($data->total) ? $data->total  : '0.00' ;
        @endphp
        {!!  !empty($data->total) ? number_format($data->total,2)  : ''   !!}
    </td>
</tr>   
 
    @endforeach
@endif
 
@if ($i < $set)
@for ($i; $i < $set; $i++) 
<tr>
    <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
        
    </td>
    <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" > 
        &nbsp;
    </td>
    <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="right"> 
        &nbsp;
         @if (($set-1) == $i && ($deduct != '' || $deduct_vat != ''))
           {!!  number_format($totals,2)  !!}
         @endif
    </td>
</tr>
@endfor
@else
<tr>
    <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
        
    </td>
    <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" > 
        &nbsp;
    </td>
    <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="right"> 
        &nbsp;
         @if ($deduct != '' || $deduct_vat != '')
           {!!  number_format($totals,2)  !!}
         @endif
    </td>
</tr>
@endif
 
@if ($count == $x)

@if ($deduct != '')
    <tr>
        <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
         
        </td>
        <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" > 
            &nbsp;<b>หัก</b> เก็บเป็นเงินสวัสดิการ สมอ. {!! $config->number_deduct_money  !!} %
        </td>
        <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="right"> 
            &nbsp;     {!!  number_format($deduct,2)  !!}
        </td>
    </tr>   
@endif

@if ($deduct_vat != '')
    <tr>
        <td class="font-16 box-border-right box-border-bottom" valign="top"  width="10%"  align="center"> 
         
        </td>
        <td class="font-16 box-border-right box-border-bottom"   valign="top" width="80%" > 
            &nbsp;<b>หัก</b> ภาษีมูลค่าเพิ่ม VAT {!! $config->number_deduct_vat  !!} %
        </td>
        <td class="font-16 box-border-right box-border-bottom" width="20%" valign="top" align="right"> 
            &nbsp;     {!!  number_format($deduct_vat,2)  !!}
        </td>
    </tr>   
@endif

<tr>
    <td class="font-16 box-white " valign="top"  width="10%"  align="center"> 

    </td>
    <td class="font-16 box-border-right box-bottom-white"   valign="top" width="80%" > 

     </td>
    <td class="font-16  " width="20%" valign="top" align="right" style="background-color: #eee;">
             {!!  number_format($sums,2)  !!}
    </td>
 </tr>
 
@endif
</table>

@if ($count == $x)
<br>
<table width="100%"> 
    <tr>
        <td width="100%" class="font-16" valign="top" >
           <b> {!! 'จำนวนเงิน '.HP::bahtText($sums,'บาท') !!} </b> 
        </td>
    </tr>
</table>
<br><br>
<table width="100%"> 
    <tr>
        <td width="60%" class="font-16" valign="top" >
        </td>
        <td width="5%" class="font-16 padding-bottom-6" valign="top" >
            {!! '(ลงชื่อ)' !!}  
       </td>
       <td width="30%" class="font-16 free-dot " valign="top" >
            
        </td>
        <td width="5%" class="font-16 padding-bottom-6" valign="top" >
            {!! '(ผู้รับเงิน)' !!}  
       </td>
    </tr>
</table>
@endif

</div>