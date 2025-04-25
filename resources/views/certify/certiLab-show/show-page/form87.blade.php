<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>2. ประเภทสถานปฏิบัติการของห้องปฏิบัติการ (Types of laboratory’s facilities)</h3></legend>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
              <div class="col-md-12">
                <div class="row">
                     <div class="col-md-2 text-right"> </div>
                     <div class="col-md-10 text-left">

                         <div class="checkbox checkbox-danger">
                            <input  type="checkbox"  disabled {{ ($certi_lab_place->permanent_operating_site === 0) ? 'checked' : ''  }}>
                            <label for="#">     &nbsp; สถานปฏิบัติการถาวร &nbsp; </label>
                        </div>
                        <div class="checkbox checkbox-danger">
                            <input  type="checkbox"  disabled {{ ($certi_lab_place->off_site_operations === 0) ? 'checked' : ''  }}>
                            <label for="#">     &nbsp; สถานปฏิบัติการนอกสถานที่   &nbsp; </label>
                        </div>
                        <div class="checkbox checkbox-danger">
                            <input  type="checkbox"  disabled {{ ($certi_lab_place->temporary_operating_site === 0) ? 'checked' : ''  }}>
                            <label for="#">     &nbsp; สถานปฏิบัติการชั่วคราว   &nbsp; </label>
                        </div>
                        <div class="checkbox checkbox-danger">
                            <input  type="checkbox"  disabled {{ ($certi_lab_place->mobile_operating_facility === 0) ? 'checked' : ''  }}>
                            <label for="#">     &nbsp; สถานปฏิบัติการเคลื่อนที่   &nbsp; </label>
                        </div>
                        @if ($labTestRequest->count() == 0 && $labCalRequest->count() == 0)
                            <div class="checkbox checkbox-danger">
                                <input  type="checkbox"  disabled {{ ($certi_lab_place->multi_site_facility === 0) ? 'checked' : ''  }}>
                                <label for="#">     &nbsp; สถานปฏิบัติการหลายสถานที่   &nbsp; </label>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

         </div>
    </div>
</div>

<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>3. ระบบบริหารงานของห้องปฏิบัติการ (Management System of Laboratory)</h3></legend>
    </div>
      <div class="row">
        <div class="col-md-11 col-md-offset-1">

              <div class="col-md-12">
                <div class="row">
                     <div class="col-md-2 text-right"> </div>
                     <div class="col-md-10 text-left">
                        @if ($certi_lab->management_lab == 1)
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled> &nbsp;ทางเลือก ก - ระบบบริหารงานตามข้อกำหนดมาตรฐานเลขที่ มอก. 17025 - 2561 (ISO/IEC 17025 : 2017) &nbsp;</label>
                        <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled checked> &nbsp;ทางเลือก ข - ระบบบริหารงานตามข้อกำหนดมาตรฐานเลขที่ มอก. 9001 – 2559 หรือ ISO 9001 : 2015  &nbsp;</label>
                        @else
                            <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-green" disabled checked> &nbsp;ทางเลือก ก - ระบบบริหารงานตามข้อกำหนดมาตรฐานเลขที่ มอก. 17025 - 2561 (ISO/IEC 17025 : 2017) &nbsp;</label>
                            <label><input type="radio" class="check  check-readonly" data-radio="iradio_square-red" disabled > &nbsp;ทางเลือก ข - ระบบบริหารงานตามข้อกำหนดมาตรฐานเลขที่ มอก. 9001 – 2559 หรือ ISO 9001 : 2015  &nbsp;</label>
                        @endif
                    </div>
                </div>
            </div>

         </div>
    </div>
</div>

