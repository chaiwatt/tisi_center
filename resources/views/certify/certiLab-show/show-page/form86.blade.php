

<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>1. ข้อมูลทั่วไป (General information)</h3></legend>
    </div>
    <div class="row">
    <div class="col-md-10 col-md-offset-1">

        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-2 text-right">
                       ผู้ยื่นคำขอ : 
                 </div>
                 <div class="col-md-10 text-left">
                 <label for="purpose_type">
                    {{ $certi_lab_info->petitioner_name }}
                </label>
                 </div>
            </div>
        </div>

        @if ($certi_lab_info->petitioner == 2)
        <div class="col-md-12">
            <div class="row">
                 <div class="col-md-2 text-right"> </div>
                 <div class="col-md-10 text-left">
                    (1) มีกิจกรรมที่นอกเหนือจากกิจกรรมทดสอบ/สอบเทียบ เป็นกิจกรรมหลัก
                    <br>
             
                    @if ($certi_lab_info->lab_type_other == 0)
                        <label><input type="radio" class="check check-readonly" data-radio="iradio_square-green" disabled checked> &nbsp;มี &nbsp;</label>
                        <label><input type="radio" class="check check-readonly" data-radio="iradio_square-red" disabled > &nbsp;ไม่มี &nbsp;</label>
                    @else
                        <label><input type="radio" class="check check-readonly" data-radio="iradio_square-green" disabled > &nbsp;มี &nbsp;</label>
                        <label><input type="radio" class="check check-readonly" data-radio="iradio_square-red" disabled checked> &nbsp;ไม่มี &nbsp;</label>
                    @endif
                    <br>
                    (2) อธิบายรายละเอียดกิจกรรมหลัก (โปรดแนบเอกสาร)
                    <br>
                    @if ($certi_lab_info->desc_main_file)
                        {{-- <small class="text-danger">* อัพโหลดไฟล์ใหม่ หากต้องการเปลี่ยนไฟล์</small> --}}
                        <div style="margin-top: 1.1rem;margin-left: 0.5rem;">
                            <a href="{{url('certify/check/file_client/'.$certi_lab_info->desc_main_file.'/'.( !is_null($certi_lab_info->activity_client_name) ? $certi_lab_info->activity_client_name :  basename($certi_lab_info->desc_main_file) ))}}" target="_blank">
                                {!! HP::FileExtension($certi_lab_info->desc_main_file)  ?? '' !!}
                                {{basename($certi_lab_info->desc_main_file)}}
                            </a>
                        </div>
                    @else
                        <span class="badge badge-danger" style="padding: 8px">ยังไม่มีไฟล์</span>
                    @endif

                    <br>
                    (3) ทดสอบ/สอบเทียบให้หน่วยงานของตนเองเท่านั้น
                    <br>
                    @if ($certi_lab_info->only_own_depart == 0)
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled checked> &nbsp;ใช่ &nbsp;</label>
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled > &nbsp;ไม่ใช่ &nbsp;</label>
                    @else
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled > &nbsp;ใช่ &nbsp;</label>
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled checked> &nbsp;ไม่ใช่ &nbsp;</label>
                    @endif
   
                    <br>
                    (4) ทดสอบ/สอบเทียบให้หน่วยงานอื่นด้วย
                    <br>
                    @if ($certi_lab_info->depart_other == 0)
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled checked> &nbsp;ใช่ &nbsp;</label>
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled > &nbsp;ไม่ใช่ &nbsp;</label>
                    @else
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled > &nbsp;ใช่ &nbsp;</label>
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled checked> &nbsp;ไม่ใช่ &nbsp;</label>
                    @endif

                 </div>
            </div>
        </div>
         @endif

         @if ($certi_lab_info->petitioner == 7)
         <div class="col-md-12">
            <div class="row">
                 <div class="col-md-2 text-right"> </div>
                 <div class="col-md-10 text-left">

                     <div class="checkbox checkbox-success">
                        <input  type="checkbox"  disabled {{ ($certi_lab_info->over_twenty === 0) ? 'checked' : ''  }}>
                        <label for="#">     &nbsp;อายุไม่ต่ำกว่า 20 ปี &nbsp; </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input  type="checkbox"  disabled {{ ($certi_lab_info->not_bankrupt === 0) ? 'checked' : ''  }}>
                        <label for="#">     &nbsp;ไม่เป็นบุคคลล้มละลาย&nbsp; </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input  type="checkbox"  disabled {{ ($certi_lab_info->not_being_incompetent === 0) ? 'checked' : ''  }}>
                        <label for="#">     &nbsp;ไม่เป็นคนไร้ความสามารถหรือคนเสมือนไร้ความสามารถ&nbsp; </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input  type="checkbox"  disabled {{ ($certi_lab_info->suspended_using_a_certificate === 0) ? 'checked' : ''  }}>
                        <label for="#">     &nbsp;ไม่เป็นผู้อยู่ในระหว่างถูกสั่งพักใช้ใบรับรอง&nbsp; </label>
                    </div>
                    <div class="checkbox checkbox-success">
                        <input  type="checkbox"  disabled {{ ($certi_lab_info->never_revoke_a_certificate === 0) ? 'checked' : ''  }}>
                        <label for="#">     &nbsp;ไม่เคยถูกเพิกถอนใบรับรองหรือเคยถูกเพิกถอนใบรับรอง แต่เวลาได้ล่วงพ้นมาแล้วไม่น้อยกว่า 6 เดือน &nbsp; </label>
                    </div>
                </div>
            </div>
        </div>
        @endif


         </div>
    </div>
</div>       





