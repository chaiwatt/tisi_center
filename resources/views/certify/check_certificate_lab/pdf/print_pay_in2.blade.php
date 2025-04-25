<style>
    @page {
        margin:2%;padding:0;
    }
    body {
        font-family: 'THSarabunNew', sans-serif;
        font-size:12px;
    }
    .content{
        border: 5px solid #d4af37;
        padding: 5%;
        margin: 0px;
        height: 100%;
        top: 10%;
        position: relative;

    }
    .center{
        text-align: center;
    }
    .left{
        text-align: left;
    }
    .right{
        text-align: right;
    }
    div{
        width: 100%;
    }
    /* h1,h2,h3,h4,h5,h6,p{
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    } */
 
    .FramelineTable {
        border: 1px solid black;
        font-size:12px;
        padding: 6;
    }
 
</style>




{{-- HTML--}}
<body >

{{-- <div class="content"> --}}
 <hr>
{{-- <div  style="width:100%; float:left;">
    <img src="{!! asset('plugins/images/BG05OK.jpg') !!}" width="100%"  height="100px"  />
</div> --}}

<div  style="width:100%; float:left;">
    <table width="100%" >
        <tr >
            <td class="left" rowspan="3"  width="13%" >
                <img src="{!! asset('plugins/images/logo_tisi.png') !!}"  height="80px" width="80px"/>
            </td>
            <td class="left"  width="53%">
                สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สมอ.) กระทรวงอุตสาหกรรม
            </td>
            <td class="center"  width="33%" >
                <b>ค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง</b>
            </td> 
        </tr>
        <tr>
            <td class="left"  width="53%">
                Thai Industrial Standards Institute (TISI)   
            </td>
            <td class="center"  width="33%" >
                 <b>ส่วนของผู้ยื่นคำขอ</b>  
            </td>
        </tr>
        <tr>
            <td class="left"  width="53%">
                เลขที่ 75/42 ถนนพระรามที่ 6 เขตราชเทวี กรุงเทพฯ 10400
            </td>
            <td class="left"  width="33%" ></td>
        </tr>
    </table>
</div>

 <br>
<div  style="width:100%; float:left;">
    <table  style="border-collapse: collapse;border: 1px solid blac"   width="100%">
        <tr  >
            <td class="FramelineTable ">
                <b>Compapany Cond : {!! $CompanyCode !!}</b>
            </td>
            <td class="FramelineTable ">
                <b>วันที่/Date : {{ $start_date ?? null  }}</b>
            </td>
        </tr>
        <tr  >
            <td  colspan="2"  class="FramelineTable "  style=" line-height:24px;">
                <p>
                    <b>
                       REF.1 :  {{ $ref1  ?? null}}
                    </b>
                   
                </p>
                <p>
                    <b>
                       REF.2 :  {{ $ref2 ?? null }}
                    </b>
                </p>
                <p>
                    <b>
                        ชื่อลูกค้า/Customer Name : {{ $name ?? null }}
                    </b>
                </p>
                <p>
                    <b>
                         เลขประจำตัวผู้เสียภาษี : {{$trader ?? null}}
                    </b>
                </p>
                <p>
                    <b>
                        เลขที่ มอก. : {{$tis ?? null}}
                    </b>
                </p>
            </td>
        </tr>
        <tr >
            <td  colspan="2"  class="FramelineTable center" style=" line-height:20px;">
                <p>
                    <b>
                      จำนวนเงินเงินที่ต้องชำระ/Amount : {{ $amount ?? null }} {{ $amountTh ?? null }}
                    </b>
                   
                </p>
            </td>
        </tr>
  </table> 
</div>
 
<div  style="width:80%; float:left;">
    {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$barcode", 'C128') . '" alt="barcode"  height="40px"  width="500px"  /> '!!}
    <br>
    {{ $barcode ?? null }}
</div>
<div  style="width:20%; float:left;" class="right">
    <img src="{!! asset('plugins/images/ktb.jpg') !!}"  height="60px" width="250px"/>
</div>

.................................................................................................................................................................................................................................................................
<div  style="width:100%; float:left;">
    <table width="100%" >
        <tr >
            <td class="left" rowspan="3"  width="13%" >
                <img src="{!! asset('plugins/images/logo_tisi.png') !!}"  height="80px" width="80px"/>
            </td>
            <td class="left"  width="53%">
                สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สมอ.) กระทรวงอุตสาหกรรม
            </td>
            <td class="center"  width="33%" >
                <b>ค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง</b>
            </td> 
        </tr>
        <tr>
            <td class="left"  width="53%">
                Thai Industrial Standards Institute (TISI)   
            </td>
            <td class="center"  width="33%" >
                 <b>ส่วนของผู้รับชำระ</b>  
            </td>
        </tr>
        <tr>
            <td class="left"  width="53%">
                เลขที่ 75/42 ถนนพระรามที่ 6 เขตราชเทวี กรุงเทพฯ 10400
            </td>
            <td class="left"  width="33%" ></td>
        </tr>
    </table>
</div>

 <br>
<div  style="width:100%; float:left;">
    <table  style="border-collapse: collapse;border: 1px solid blac"   width="100%">
        <tr  >
            <td class="FramelineTable ">
                <b>Compapany Cond : {!! $CompanyCode !!}</b>
            </td>
            <td class="FramelineTable ">
                <b>วันที่/Date : {{ $start_date ?? null }}</b>
            </td>
        </tr>
        <tr  >
            <td  colspan="2"  class="FramelineTable "  style=" line-height:24px;">
                <p>
                    <b>
                       REF.1 :  {{ $ref1  ?? null}}
                    </b>
                   
                </p>
                <p>
                    <b>
                       REF.2 :  {{ $ref2 ?? null }}
                    </b>
                </p>
                <p>
                    <b>
                        ชื่อลูกค้า/Customer Name : {{ $name ?? null }}
                    </b>
                </p>
                <p>
                    <b>
                         เลขประจำตัวผู้เสียภาษี : {{$trader ?? null}}
                    </b>
                </p>
                <p>
                    <b>
                        เลขที่ มอก. :  {{$tis ?? null}}
                    </b>
                </p>
            </td>
        </tr>
        <tr >
            <td  colspan="2"  class="FramelineTable center" style=" line-height:20px;">
                <p>
                    <b>
                      จำนวนเงินเงินที่ต้องชำระ/Amount : {{ $amount ?? null }} {{ $amountTh ?? null }}
                    </b>
                   
                </p>
            </td>
        </tr>
  </table> 
</div>
 
<div  style="width:80%; float:left;">
{!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$barcode", 'C128') . '" alt="barcode"  height="40px"  width="500px"  /> '!!}
<br>
{{ $barcode ?? null }}
</div>
<div  style="width:20%; float:left;" class="right">
    <img src="{!! asset('plugins/images/ktb.jpg') !!}"  height="60px" width="250px"/>
</div>

<div  style="width:100%; float:left;">
   <p class="center"><b>กรุณากรอกเอกสารนี้ให้ครบถ้วน แล้วนำไปชำระได้ที่ธนาคารกรุงไทย ได้ทุกสาขา</b></p>
</div>

<div  style="width:100%; float:left;">
    <table  style="border-collapse: collapse;border: 1px solid blac"   width="100%">
        <tr>
            <td class="left FramelineTable" colspan="4"  width="25%" >
                สาขา ธนาคารกรุงไทยที่รับฝาก 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span>วันที่ชำระเงิน</span>
                 
            </td>
        </tr>
        <tr>
            <td class="left FramelineTable" colspan="3"  width="25%" >
                เงินสด / Cash
            </td>
            <td class="center FramelineTable"  width="25%" >
               จำนวนเงิน / Amount
            </td>
        </tr>
        <tr>
            <td class="left FramelineTable"   width="25%" >
              <p>เลขที่เช็ค /  Chq</p>
              <p>No.</p>
            </td>
            <td class="left FramelineTable"  width="25%" >
                <p> ธนาคาร / สาขา</p>
                <p>Bank/Branch</p> 
            </td>
            <td class="left FramelineTable"  width="25%" >
                <p>เช็คลงวันที่ / Chq Due Date</p>
                <p>&nbsp;</p>  
            </td>
            <td class="center FramelineTable"  width="25%" >
                <p> จำนวนเงิน / Amount</p>
                <p>&nbsp;</p>    
            </td>
        </tr>
        <tr>
            <td class="left FramelineTable" colspan="3"  width="25%" >
              ยอดรวม จำนวนเงินที่ชำระ / Total Payment (ตัวอักษร)
            </td>
            <td class="center FramelineTable"  width="25%" >
                จำนวนเงิน / Amount
            </td>
        </tr>
    </table>
</div>
 
{{-- </div>  --}}
       
</body>
