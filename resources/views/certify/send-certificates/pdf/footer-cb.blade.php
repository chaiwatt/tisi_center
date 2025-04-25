   <table width="100%"    style="padding-bottom: -20px;"> 
        <tr>
            <td  width="30%" s align="right"    class="font-11">    </td>
            <td   width="70%"  align="center"  style="padding-top: -30px;" > {!! $sign_path !!} </td>
        </tr>
    
    </table>
    
    <table width="100%"   style="padding-bottom: -120px;" > 
        @if (!is_null($sign_name))
        <tr>
            <td    width="30%" align="right"    class="font-11">    </td>
            <td   width="70%"  align="center"  style="padding-top: -2px;"  class="font-16"> {!! $sign_name; !!} </td>
        </tr>
        @endif
        @if (!is_null($sign_position))
        <tr>
            <td    align="right"    class="font-11">    </td>
            <td   align="center"   style="padding-top: -30px;"  class="font-16"> {!! $sign_position; !!} </td>
        </tr>
        @endif
        @if (!is_null($sign_instead) && $sign_instead == 1)
        <tr>
            <td    align="right"    class="font-11">    </td>
            <td   align="center" style="padding-top: -30px;"   class="font-16"> ปฏิบัติราชการแทน </td>
        </tr>
        <tr>
            <td    align="right"    class="font-11">    </td>
            <td   align="center" style="padding-top: -30px;"   class="font-16"> เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม </td>
        </tr>
        @else

          <tr>
                <td    align="right"    class="font-11">    </td>
                <td   align="center" style="padding-top: -30px;"   class="font-16"> &nbsp; </td>
            </tr>
            <tr>
                <td    align="right"    class="font-11">    </td>
                <td   align="center" style="padding-top: -30px;"   class="font-16"> &nbsp; </td>
            </tr>
        @endif
</table>
<div style="padding-left: 3%;   padding-right: 3%;  ">   
        <table >
                <tr>
                        <td  colspan="3" style="padding-left: -4px;padding-bottom: -25px;"  >
                                @if(!is_null($url))
                                <a href="{{ $url }}"  target="_blank">
                                        <img src="data:image/png;base64, {!! base64_encode($image_qr) !!} " width="63px" >
                                </a>
                                @else
                                        <br><br><br><br><br>
                                @endif
                        </td>
                </tr>
        </table>
        <table  >
               <tr>
                       <td style="width: 80%;padding-top: 120px;" class="font-19" >
                         <p >กระทรวงอุตสาหกรรม สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</p>
                       </td>
                       <td class="tr" style="width:15%;padding-top: 75px; ">
                                @if($image != '-' && !is_null($check_badge) && $check_badge == 1)
                                        <img src="{!! public_path('plugins/formulas/'.$image) !!}"  width="3cm">
                                @endif
                       </td>
                       <td class="tr" style="width:15%;padding-top: 75x;">
                               <img src="{{ public_path('images/certify/nc.png') }}"/>
                       </td>
               </tr>
               <tr>
               <td  colspan="3"   style="padding-top: -30px;line-height:20px;" >
                 <p class="font-11">(Ministry of Industry Thailand, Thai Industrial Standards Institute)</p>
               </td>
               </tr>
       </table>
       <br>
 </div>