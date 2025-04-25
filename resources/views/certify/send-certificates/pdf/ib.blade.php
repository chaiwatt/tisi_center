<style>
        @page {
            margin:2%;padding:0;
        }
        @page {
        header: page-header;
        footer: page-footer;
    }
        body {
            /* font-family: 'THSarabunNew', sans-serif; */
            font-family: 'thiasarabun', sans-serif;
        }
        .content{
            padding-top: 5%;
            padding-left: 2%;
            padding-right: 2%;
            margin: 0px;
            height: 100%;
            top: 10%;
            position: relative;
    
        }
        .tc{
            text-align: center;
        }
        .tl{
            text-align: left;
        }
     
        h1,h2,h3,h4,h5,h6,p{
            padding: 0px;
            margin: 0px;
            line-height: 2em;
        }
        .space{
            height: 20px;
        }
        .space-mini{
            height: 10px;
        }
        b{
            font-weight: bold;
        }
        h1{
            margin-bottom: 10px;
        }
        .w-100{
            width: 100%;
        }
        .tab {
            display:inline-block;
            margin-left: 40px;
        }
        .tr{
            text-align: right;
        }
        .w-66{
            width: 66%;
        }
        .w-70{
            width: 70%;
        }
        .w-33{
            width: 33%;
        }
        .w-15{
            width: 15%;
        }
        .w-50{
            width: 50%;
        }
        table{
            line-height: 2em;
            font-size: 1.2em;
        }
        
 
 
        .font-10{
            font-size: 10pt;
        }
        .font-11{
            font-size: 11pt;
        }
        .font-12{
            font-size: 12pt;
        }
        .font-13{
            font-size: 13pt;
        }
        .font-14{
            font-size: 14pt;
        }
        .font-15{
            font-size: 15pt;
        }
        .font-16{
            font-size: 16pt;
        }
        .font-18{
            font-size: 18pt;
        }
        .font-19{
            font-size: 19pt;
        }
        .font-20{
            font-size: 20pt;
        }
        .font-25{
            font-size: 25pt;
        }
      .free-dot {
                border-bottom: thin dotted #000000; 
                padding-bottom: 0px !important;
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
        .line-height30  {
            line-height:30px;
            padding-top: -5px;
        }
        .line-height35  {
            line-height:35px;
            padding-top: -5px;
        }

    </style>
    
 
<div class="content">
 
    <table width="100%"    style="padding-top: 25px;"    > 
        <tr>
        <td width="33%"  class="font-11"   >  </td>
        <td width="33%"  align="center">   </td>
        <td width="33%" class="font-11"  align="right"  style="padding-top: -75px;line-height:15px;">
            <p>แบบ กมช./สมอ.๒</p> 
            <p>Form NSC/TISI 2</p>
        </td>
        </tr>
    </table>
    

    <table width="100%"    style="padding-top: -40px;"    > 
        <tr>
        <td width="33%"  class="font-12"  style="padding-top: 50px;line-height:15px;" > 
            <p>ใบรับรองเลขที่ &nbsp;&nbsp;&nbsp;&nbsp;<span class="free-dot">&nbsp;&nbsp;{{ $certificate }}&nbsp;&nbsp;</span></p> 
            <p   >(Certificate No.) </p> 
        </td>
        <td width="33%"  align="center">   </td>
        <td width="33%" class="font-11" > </td>
        </tr>
    </table>


    

<table width="100%"  style="padding-top: 10px;"   >
    <tr>
        <td align="center" class="font-25"  > 
                <b>ใบรับรองระบบงาน</b>    
        </td>
    </tr>
    <tr>
        <td align="center"  style="padding-top: -35px;" >
            <p class="font-10">(Certificate of Accreditation)</p> 
        </td>
    </tr>

    <tr>
        <td align="center"  class="font-18" style="padding-top: -20px;">
                <b>อาศัยอำนาจตามความในพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. {{ HP::toThaiNumber('2551')}} </b> 
        </td>
    </tr>
    <tr>
        <td align="center"   style="padding-top: -35px;" >
            <p class="font-10">(By Virtue of National Standardization Act B.E. 2551 (2008))</p> 
        </td>
    </tr>

    <tr>
        <td align="center"   class="font-18"  style="padding-top: -20px;">
            <b>เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</b> 
        </td>
    </tr>
    <tr>
        <td align="center"  style="padding-top: -35px;" >
            <p class="font-10">(Secretary-General, Thai Industrial Standards Institute)</p> 
        </td>
    </tr>

    <tr>
        <td align="center"  class="font-18"  style="padding-top: -20px;">
                <b>ออกใบรับรองฉบับนี้ให้</b> 
        </td>
    </tr>
    <tr>
        <td align="center"  style="padding-top: -35px;" >
            <p class="font-10">(Issues this certificate to)</p> 
        </td>
    </tr>
</table>

<!-- start ชื่อภาษาไทย -->
@if (count($names) > 0)
<table width="100%"  style="padding-top: -10px;">
    @foreach ($names as $name)
         <tr>
            <td align="center" class="line-height25  {{ $name->font }}"  >
                {!! $name->title !!}  
            </td>
        </tr>
    @endforeach
</table>
@endif 
<!-- end ชื่อภาษาไทย -->

 <!-- start ชื่อภาษาอังกฤษ -->
@if (count($names_en) > 0)
<table width="100%" style="padding-top: 3px;">
    @foreach ($names_en as $en)
    <tr>
        <td align="center" style="padding-top: -20px;"  class="line-height30   {{ $en->font }}"  >
            {!! $en->title !!}  
        </td>
    </tr>
    @endforeach
</table>
@endif 
<!-- end ชื่อภาษาอังกฤษ -->   
    
 
 <!-- start ตั้งอยู่เลขที่ Th -->
<table width="100%"   style="padding-top: -10px;">
    <tr>
        <td align="center" class="font-18" style="padding-top: -10px;">
              <b>ตั้งอยู่เลขที่</b>
        </td>
    </tr>
    <tr>
        <td align="center"  style="padding-top: -35px;" >
            <p class="font-10">(Address)</p> 
       </td>
    </tr>
</table>
<!-- end ตั้งอยู่เลขที่ Th --> 


<!-- start  ตั้งอยู่เลขที่ Th -->
@if (count($address_ths) > 0)
<table width="100%"  style="padding-top: -10px;">
    @foreach ($address_ths as $address)
         <tr>
            <td align="center" class="line-height25  {{ $address->font }}"  >
                {!!  HP::toThaiNumber($address->title) !!}  
            </td>
        </tr>
    @endforeach
</table>
@endif 
<!-- end  ตั้งอยู่เลขที่ Th -->

 <!-- start  ตั้งอยู่เลขที่ en -->
 @if (count($address_ens) > 0)
 <table width="100%">
    @foreach ($address_ens as $address)
    
    <tr>
       <td align="center" style="padding-top: -12px;"   class="line-height25  font-10"   >
              {{ $address->title }}
       </td>
   </tr>
   @endforeach
</table>
@endif 
 <!-- end  ตั้งอยู่เลขที่ en -->   
     
 <table width="100%"   >
    <tr>
        <td align="center" class="font-18" style="padding-top: -20px;"  >
             <b>ได้รับการรับรองความสามารถ</b>
        </td>
    </tr>
    <tr>
        <td align="center" class="font-10" style="padding-top: -35px;" >
         (Certificate of competence)
       </td>
    </tr>
    <tr>
        <td   align="center" class="font-16"  style="padding-top: -25px;"   >
              ตามมาตรฐานเลขที่&nbsp;&nbsp;&nbsp;{!! HP::toThaiNumber($formula)    !!} 
        </td>
    </tr>
    <tr>
        <td   align="center"  style="padding-top: -35px;"  class="font-10">
             {!! !empty($formula_en) ?  '(Standard No. '.$formula_en.')' :  '' !!}  
       </td>
    </tr>
</table>
 

 
@if (count($condition_th) > 0)
<table width="100%"  style="padding-top: -10px;">
    @foreach ($condition_th as $item)
         <tr>
            <td align="center" class="line-height25  {{ $item->font }}"  >
                {!! $item->title !!}  
            </td>
        </tr>
    @endforeach
</table>
@endif 

 <table width="100%"  style="padding-top: -10px;">
          <tr>
             <td align="center" style="padding-top: -15px;"  class="font-10"  >
                  {{ $condition_en }}
             </td>
         </tr>
 </table>
 

 <table width="100%"    >
    <tr>
        <td  align="center"  colspan="3"   class="font-18"   style="padding-top: -25px;"      >
             <b>หมายเลขการรับรองที่ </b>&nbsp;&nbsp;&nbsp;{{ $accereditatio_no }}     
       </td>
    </tr>
     <tr>
        <td  align="center"   colspan="3"   class="font-10"   style="padding-top: -35px;" >
           {!! !empty($accereditatio_no_en) ?  '(Accreditation No. '.$accereditatio_no_en.')' :  '' !!}
       </td>
    </tr>

    <tr>
        <td  align="center"   class="font-16"  style="padding-top: -20px;"  colspan="3">
             โดยมีรายละเอียดสาขาและขอบข่ายที่ได้ใบรับรอง แสดงไว้ใน QR CODE และ www.tisi.go.th   
       </td>
    </tr>
     <tr>
        <td  align="center" style="padding-top: -33px;" class="font-10"  colspan="3" >
          (Details of the scheme and scope of the certificate are shown in QR CODE and www.tisi.go.th)
       </td>
    </tr>
</table>


<table width="100%" style="padding-top: -25px;"  >
    @php
        if(is_null($date_start) ||  empty($date_start) ){
            $date_start ='';
        }else{
            $date_start = HP::toThaiNumber( HP::formatDateThaiFullPoint($date_start) );
        }
    @endphp 
    <tr>
        <td  align="right"  width="30%"  class="font-11">    </td>
        <td   align="center"   width="70%"  class="font-16">  ออกให้ ณ วันที่   {!! $date_start !!}   </td>
    </tr>
    <tr>
        <td  align="right"    class="font-11">    </td>
        <td   align="center"  style="padding-top: -35px;"  class="font-10">  {!! !empty($date_start_en) ?  '(Issue date : '.$date_start_en.')' :  '' !!}   </td>
    </tr>
</table>

 


</div>


