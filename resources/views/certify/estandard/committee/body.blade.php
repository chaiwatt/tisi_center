

      <div style="position: absolute; width: 300px; top: 55px; left: 12%; transform: translateX(-50%); z-index: 10;">
                <div style="margin: 0; padding: 0;margin-top:50px">
                    ที่ {{$meetingInvitation->reference_no}}
                    </div>
        </div>

          <div style="position: absolute; width: 300px; top: 55px; left: 60%; transform: translateX(-50%); z-index: 10;">
                <div style="margin: 0; padding: 0;margin-top:50px">
                        คณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบ
                        และรับรอง สำนักงานมาตรฐานผลิตภัณฑ์ อุตสาหกรรม
                        ถนนพระรามที่ ๖ แขวงทุ่งพญาไท
                        เขตราชเทวี กรุงเทพฯ ๑๐๔๐๐
                    </div>
        </div>

      <div style="position: absolute; width: 100px; text-align: center; top: 50px; left: 44%; transform: translateX(-50%); z-index: 10;">
                <img src="{{public_path('images/certificate-header.jpg')}}" alt="ตราครุฑ" style="width: 100px; margin-top: -20px;">
        </div>



<div style="padding-top: 180px;">
    <div style="text-align: center">
        {{$meetingInvitation->date}}
    </div>
      <table>
      <tr>
        <td>
          เรื่อง {{$meetingInvitation->subject}}
        </td>
      </tr>
      <tr>
        <td>
          เรียน รองผู้อำนวยการฯ
        </td>
      </tr>
      <tr>
        <td>
          สิ่งที่ส่งมาด้วย <br>
          <table>
            <tr>
              <td style="width: 9%"></td>
              <td>{!! nl2br(e($meetingInvitation->attachments)) !!}</td>
            </tr>
          </table>
            
        </td>
      </tr>
      <tr>
    <tr>
    <td style="padding-top: 10px; text-indent: 250px;">

        @php
            
            $info = "=======" . $meetingInvitation->details;
            $textResult = TextHelper::callLonganTokenizePost($info);
            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);
            $textResult = str_replace('=======', '<span style="color:#fff;">=======</span>', $textResult);

        @endphp
            <span><span style="padding-left:10px;word-spacing: -0.2em;text-indent:50px">{!!$textResult!!}</span></span> <br><br>
            @php
                  $info = "=======" . $meetingInvitation->ps_text;
                  $textResult = TextHelper::callLonganTokenizePost($info);
                  $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);
                  $textResult = str_replace('=======', '<span style="color:#fff;">=======</span>', $textResult);
            @endphp
            <span><span style="padding-left:10px;word-spacing: -0.2em;text-indent:50px">{!!$textResult!!}</span></span>

        </td>
      </tr>
    </table>
      
  <table style="width: 100%; margin-top:35px;">
    <tr>
      <td style="width: 33%;">&nbsp;</td>
      <td style="width: 60%; text-align: center;">
        <div style="margin: 0;">ขอแสดงความนับถือ</div>
        <div style="margin: 20px 0; height:150px">
          {{-- {{public_path($signature)}} --}}
            <img src="{{public_path($signature)}}" alt="ตราครุฑ" style="width: 100px; margin-top: 10px;">
        </div>
        <p style="margin: 0;">{{$meetingInvitation->signer->name}}</p>
        <p style="margin: 0;">{!! nl2br(e($meetingInvitation->signer_position)) !!}</p>
      </td>
    </tr>
  </table>
</div>