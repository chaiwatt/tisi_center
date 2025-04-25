<style>
    #style{

        padding: 5px;
        border: 5px solid gray;
        margin: 0;
        font-size:15px !important; 
    }    
    #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #66ccff;
        color: #000000;
    }   
    .indent50 {
        text-indent: 50px;
    } 
    .indent150 {
        text-indent: 150px;
    } 

    .text-right{
        text-align: right !important;
    }
</style>
 
@php
    $result_section = $result->law_section()->select('number', 'title')->get();    
    $result_punish  = $result->law_punish()->select('number', 'title')->get();    
    $config         = HP::getConfig(false);

    //หลักฐานผลพิจารณา
    // $attachs_consider = $result->AttachFileConsider;
    // //บันทึกพิจารณาคดี
    // $attachs_consider_result = $result->AttachFileConsiderResult;
    // //เปรียบเทียบปรับ
    // $attachs_consider_compares = $result->AttachFileConsiderCompares;
    // //ข้อเท็จจริงการเปรียบเทียบปรับ
    // $attachs_consider_comparison_facts = $result->AttachFileConsiderComparisonFacts;
    // //ไฟล์เเนบ
    // $attachs_result_others   = !empty($result->AttachFileOther)? $result->AttachFileOther:[];
@endphp


<div id="style">

    <table width="150%">
        <tr>
            <td colspan="2" valign="top">
                <b>เรียน :  {{$case->owner_contact_name ?? ''}}</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top">
                <b>เรื่อง  : </b>แจ้งเตือนบันทึกผลพิจารณางานคดี ของ {{$case->offend_name ?? ''}} เลขอ้างอิง {{$case->ref_no ?? ''}}
            </td>
        </tr>
 
        <tr>
            <td colspan="2" valign="top">
                ตามที่คุณได้แจ้งงานคดีผลิตภัณฑ์อุตสาหกรรมผ่านระบบ ของ {{$case->offend_name ?? ''}}  {{ !empty($case->created_at) ?  'เมื่อวันที่ '.HP::DateThaiFormal($case->created_at) : '' }} เลขที่อ้างอิง {{$case->ref_no ?? ''}} นั้น
            </td>
        </tr>

        <tr>
            <td colspan="2" valign="top">
                นิติกรได้ตรวจสอบข้อมูลงานคดีดังกล่าวแล้ว และขอแจ้งผลการตรวจสอบ ดังนี้
            </td>
        </tr>
        <tr>
            <td width="15%" valign="top" align="right">
                สถานะ : 
            </td>
            <td width="85%" valign="top">
                {{ !empty($case->StatusText) ? $case->StatusText : ' ' }}
            </td>
        </tr>
      
    
 
        @if( !empty($case->case_number))
            <tr>
                <td width="15%" valign="top" align="right">
                    เลขคดี : 
                </td>
                <td width="85%" valign="top">
                    {!! $case->case_number !!}
                </td>
            </tr>
        @endif

        {{-- @if( count( $result_section) >= 0 )

            @foreach ( $result_section  as $key => $section )

                @if( $key == 0 )
                    <tr>
                        <td width="15%" valign="top" align="right">
                            มาตรความผิด : 
                        </td>
                        <td width="85%" valign="top">
                            มาตรา {!! $section->number !!} {!! $section->title !!}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td width="15%" valign="top" align="right"></td>
                        <td width="85%" valign="top">
                            มาตรา {!! $section->number !!} {!! $section->title !!}
                        </td>
                    </tr>
                @endif
        
                @php
                    $key++;
                @endphp
                
            @endforeach
            
        @endif --}}

{{--         
        @if( count( $result_punish) >= 0 )

            @foreach ( $result_punish  as $key => $punish )

                @if( $key == 0 )
                    <tr>
                        <td width="15%" valign="top" align="right">
                            บทกำหนดลงโทษ : 
                        </td>
                        <td width="85%" valign="top">
                            มาตรา {!! $punish->number !!} {!! $punish->title !!}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td width="15%" valign="top" align="right"></td>
                        <td width="85%" valign="top">
                            มาตรา {!! $punish->number !!} {!! $punish->title !!}
                        </td>
                    </tr>
                @endif

                @php
                    $key++;
                @endphp
                
            @endforeach
            
        @endif --}}

        {{-- @if( !empty($result) )
            <tr>
                <td width="15%" valign="top" align="right">
                    การดำเนินการคดี : 
                </td>
                <td width="85%" valign="top">
                    <label><input type="checkbox" name="person"  value="1" disabled @if( !empty($result) && in_array(  $result->person, [1]) ) checked @endif> &nbsp;ดำเนินการทางอาญา&nbsp;</label>
                </td>
            </tr>
            <tr>
                <td width="15%" valign="top" align="right"></td>
                <td width="85%" valign="top">
                    <label> <input type="checkbox" name="license" value="1" disabled @if( !empty($result) && in_array(  $result->license, [1]) ) checked @endif> &nbsp;ดำเนินการปกครอง(ใบอนุญาต)&nbsp;</label>
                </td>
            </tr>
            <tr>
                <td width="15%" valign="top" align="right"></td>
                <td width="85%" valign="top">
                    <label> <input type="checkbox" name="product" value="1" disabled @if( !empty($result) && in_array(  $result->product, [1]) ) checked @endif> &nbsp;ดำเนินการของกลาง (ผลิตภัณฑ์)&nbsp;</label>
                </td>
            </tr>
        @endif --}}
{{-- 
        @if( !empty($attachs_consider))
            <tr>
                <td width="15%" valign="top" align="right">
                    หลักฐานผลพิจารณา : 
                </td>
                <td width="85%" valign="top">
                    <a href="{!! HP::getFileStorage($attachs_consider->url) !!}" target="_blank">{!! !empty($attachs_consider->filename) ? $attachs_consider->filename : '' !!}</a>
                </td>
            </tr>
        @endif

        @if( !empty($attachs_consider_result))
            <tr>
                <td width="15%" valign="top" align="right">
                    บันทึกพิจารณาคดี : 
                </td>
                <td width="85%" valign="top">
                    <a href="{!! HP::getFileStorage($attachs_consider_result->url) !!}" target="_blank">{!! !empty($attachs_consider_result->filename) ? $attachs_consider_result->filename : '' !!}</a>
                </td>
            </tr>
        @endif

        @if( !empty($attachs_consider_compares))
            <tr>
                <td width="15%" valign="top" align="right">
                    เปรียบเทียบปรับ : 
                </td>
                <td width="85%" valign="top">
                    <a href="{!! HP::getFileStorage($attachs_consider_compares->url) !!}" target="_blank">{!! !empty($attachs_consider_compares->filename) ? $attachs_consider_compares->filename : '' !!}</a>
                </td>
            </tr>
        @endif

        @if( !empty($attachs_consider_comparison_facts))
            <tr>
                <td width="15%" valign="top" align="right">
                    ข้อเท็จจริงการเปรียบเทียบปรับ : 
                </td>
                <td width="85%" valign="top">
                    <a href="{!! HP::getFileStorage($attachs_consider_comparison_facts->url) !!}" target="_blank">{!! !empty($attachs_consider_comparison_facts->filename) ? $attachs_consider_comparison_facts->filename : '' !!}</a>
                </td>
            </tr>
        @endif

        @if (count($attachs_result_others) > 0)
            <tr>
                <td width="15%" valign="top" align="right">
                    ไฟล์เเนบ(อื่นๆ) : 
                </td>
                <td width="85%" valign="top">
                    @foreach ($attachs_result_others as $attachs_result_other)
                        <p>     
                            <a href="{!! HP::getFileStorage($attachs_result_other->url) !!}" target="_blank">
                                {!! !empty($attachs_result_other->filename) ? $attachs_result_other->filename : '' !!}
                        </p>
                    @endforeach
                </td>
            </tr>
        @endif --}}

        @if( !empty( $result->remark))
            <tr>
                <td width="15%" valign="top" align="right">
                    หมายเหตุ : 
                </td>
                <td width="85%" valign="top">
                    {!! $result->remark !!}
                </td>
            </tr>
        @endif

        {{-- @if( !empty( $case->user_lawyer_to))
            <tr>
                <td width="15%" valign="top" align="right">
                    นิติกรเจ้าของสำนวน : 
                </td>
                <td width="85%" valign="top">
                    {!! $case->user_lawyer_to->FullName !!}
                </td>
            </tr>
        @endif --}}

        <tr>
            <td colspan="2" valign="top">
                จึงเรียนมาเพื่อโปรดทราบ <br> TISI-LAW <br>
                <hr style="border-top: 1px solid black;">
                e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
            </td>
        </tr>

        <tr>
            <td colspan="2" valign="top">
                @if (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 1)  <!-- แสดงข้อมูลติดต่อกลาง -->
                    {!! $config->contact_mail_footer  !!}
                @elseif (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 2) <!-- แสดงข้อมูลติดต่อผู้บันทึก -->
                    @php
                        $name       =  auth()->user()->FullName ?? '';
                        $reg_wphone =   auth()->user()->reg_wphone ?? '';
                        $reg_email  =  auth()->user()->reg_email ?? '';
                    @endphp
                    {!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}
                @endif
            </td>
        </tr>

    </table>

</div> 

 
 