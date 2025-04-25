<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>8. เอกสารอื่นๆ (Others)</h4></legend>

            <div class="clearfix"></div>
            @if (isset($certi_ib) && $certi_ib->FileAttach7->count() > 0)
            <div class="row">
                @foreach($certi_ib->FileAttach7 as $data)
                  @if ($data->file)
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-4 text-light"> </div>
                            <div class="col-md-6 text-light">
                                {{  @$data->file_desc }}
                                <a href="{{url('certify/check/file_ib_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                    {!! HP::FileExtension($data->file)  ?? '' !!}
                                    {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                 @endforeach
              </div>
            @endif

      </div>
   </div>
</div>


@if (isset($certi_ib) && !is_null($certi_ib->desc_delete))
<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>ยกเลิกคำขอ</h4></legend>

            <div class="col-md-12">
                <div class="col-md-4 text-right"> สาเหตุ :</div>
                <div class="col-md-6 text-light">
                        <p> {{  !empty($certi_ib->desc_delete)  ? $certi_ib->desc_delete : '-' }}</p>
                </div>
            </div>

            <div class="clearfix"></div>
            @if (isset($certi_ib) && $certi_ib->FileAttach8->count() > 0)
            <div class="row">
                @foreach($certi_ib->FileAttach8 as $data)
                  @if ($data->file)
                    <div class="col-md-12 form-group">
                        <div class="col-md-4 text-light"> </div>
                        <div class="col-md-6 text-light">
                                {{  @$data->file_desc }}
                                <a href="{{url('certify/check/file_ib_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                    {!! HP::FileExtension($data->file)  ?? '' !!}
                                    {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                </a>
                        </div>
                    </div>
                    @endif
                 @endforeach
              </div>
            @endif

      </div>
   </div>
</div>
@endif 

<div class="row form-group">
  <div class="col-md-12">
      <div class="checkbox checkbox-success">
          <input id="checkbox_confirm" class="checkbox_confirm" type="checkbox" name="checkbox_confirm"  disabled
                 value="1"  {{ (isset($certi_ib) && $certi_ib->checkbox_confirm  == 1) ? 'checked': '' }}>
          <label for="checkbox_confirm"> &nbsp;  หน่วยตรวจขอรับรองว่า (IB hereby affirms certify that)
              <button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal"><b>คลิก</b> </button>
          </label>
      </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <p>
          {{-- - ข้อมูลตามที่ระบุไว้ในคำขอรวมทั้งเอกสารและหลักฐานที่แนบประกอบการพิจารณาทั้งหมดเป็นความจริง
          (All information as specified in the application forms, including the documents and evidences attached are true) --}}
          (1) ข้าพเจ้ารับทราบและให้คำมั่นจะปฏิบัติตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. 2551 รวมถึงกฎกระทรวง ประกาศ หลักเกณฑ์ วิธีการ และเงื่อนไข มาตรฐานข้อกำหนดสำหรับการรับรองระบบงาน ข้อกำหนดอื่น ๆ และ/หรือ ที่จะมีการกำหนด แก้ไขเพิ่มเติมในภายหลังด้วย 
          <br>
          I have acknowledged and committed to continually fulfil the requirements for accreditation and the other obligations of the conformity assessment body, and to comply with National Standardization Act, B.E.2551 (2008) including ministerial regulations, notification, criteria methods and conditions according to the act, standard requirement, conditions determined by TISI and/or any changes in future
        </p>
        <p>
          {{-- - จะปฏิบัติตามหลักเกณฑ์ วิธีการ และเงื่อนไขในการรับรองระบบงานที่เกี่ยวข้องรวมทั้งที่อาจมีการแก้ไขหรือกำหนดเพิ่มเติมในภายหลัง
          (IB shall perform according to the criteria methods and conditions relevant for accreditation including those that may be corrected or added afterwards) --}}
          (2) ข้าพเจ้าจะชำระค่าธรรมเนียมคำขอรับใบรับรองและใบรับรองทันทีที่ได้รับใบแจ้งการชำระเงินจากสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม 
          <br>
           I will pay application fee, and certificate document fee upon receiving the Pay-in Slip from TISI without delays.
        </p>
      </div>
    </div>
  </div>
</div>