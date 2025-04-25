@push('css')
  <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="col-md-8">
  <div class="white-box">

    <h3 class="box-title m-b-0">แก้ไขข้อมูลผู้ใช้งาน (สก.)</h3>
    <p class="text-muted m-b-30 font-13"> แก้ไขข้อมูลผู้ประกอบการผู้ใช้งาน (สก.)</p>

    <div class="form-group">
      <div class="col-xs-6">
          <label class="radio-inline">
              <input type="radio" name="trader_type" id="trader_type1" value="นิติบุคคล" @if($soko->trader_type=="นิติบุคคล" || $soko->trader_type=="")checked="checked" @endif> นิติบุคคล
          </label>
          <label class="radio-inline">
              <input type="radio" name="trader_type" id="trader_type2" value="บุคคลธรรมดา" @if($soko->trader_type=="บุคคลธรรมดา")checked="checked" @endif> บุคคลธรรมดา
          </label>
      </div>
      <div class="col-xs-6">
          <select id="trader_inti" class="form-control{{ $errors->has('trader_inti') ? ' is-invalid' : '' }}" name="trader_inti" value="{{ $soko->trader_inti??old('trader_inti') }}" required autofocus>
              <option value="">=== เลือกประเภทการจดทะเบียน ===</option>
              <option value="บริษัท จำกัด" @if($soko->trader_inti=="บริษัท จำกัด")selected="selected" @endif> บริษัท จำกัด </option>
              <option value="บริษัท จำกัด มหาชน" @if($soko->trader_inti=="บริษัท จำกัด มหาชน")selected="selected" @endif> บริษัท จำกัด มหาชน </option>
              <option value="ห้างหุ้นส่วนจำกัด" @if($soko->trader_inti=="ห้างหุ้นส่วนจำกัด")selected="selected" @endif> ห้างหุ้นส่วนจำกัด </option>
              <option value="รัฐวิสาหกิจ" @if($soko->trader_inti=="รัฐวิสาหกิจ")selected="selected" @endif> รัฐวิสาหกิจ </option>
              <option value="อื่น ๆ" @if($soko->trader_inti=="อื่น ๆ")selected="selected" @endif> อื่น ๆ </option>
          </select>
          @if ($errors->has('trader_inti'))
              <span class="invalid-feedback">
                      <strong>{{ $errors->first('trader_inti') }}</strong>
              </span>
          @endif
      </div>
    </div>

    <div class="form-group ">
      <div class="col-xs-6">
          <input id="trader_operater_name" type="text" class="form-control{{ $errors->has('trader_operater_name') ? ' is-invalid' : '' }}" placeholder="ชื่อสถานประกอบการ เช่น บริษัท ตัวอย่าง จำกัด" name="trader_operater_name" value="{{ $soko->trader_operater_name??old('trader_operater_name') }}" required autofocus>
          @if ($errors->has('trader_operater_name'))
              <span class="invalid-feedback">
                      <strong>{{ $errors->first('trader_operater_name') }}</strong>
              </span>
          @endif
      </div>
      <div class="col-xs-3">
          <input id="trader_id" type="text" class="form-control{{ $errors->has('trader_id') ? ' is-invalid' : '' }}" placeholder="เลขนิติบุคคล" name="trader_id" value="{{ $soko->trader_id??old('trader_id') }}" required autofocus>

          @if ($errors->has('trader_id'))
              <span class="invalid-feedback">
                      <strong>{{ $errors->first('trader_id') }}</strong>
              </span>
          @endif
      </div>
      <div class="col-xs-3" id="for_date_niti">
          <div class="input-group">
              <input id="trader_id_register" type="text" class="form-control {{ $errors->has('trader_id_register') ? ' is-invalid' : '' }} datepicker" placeholder=" วันที่จดทะเบียน" name="trader_id_register" value="{{ $soko->trader_id_register??old('trader_id_register') }}" required>
              <span class="input-group-addon"><i class="icon-calender"></i></span>
          @if ($errors->has('trader_id_register'))
              <span class="invalid-feedback">
                          <strong>{{ $errors->first('trader_id_register') }}</strong>
                      </span>
          @endif
          </div>
      </div>
    </div>

     <h5 class="box-title" style="font-size: 20px;">Email และ Password สำหรับเข้าสู่ระบบ</h5>

                <div class="form-group">
                    <div class="col-xs-6">
                        <input id="trader_username" type="email" class="form-control" name="trader_username" placeholder="Email" value="{{ $soko->trader_username }}" required onblur="checkmailexits(this.value)">
                    </div>
                    <div class="col-xs-6">
                    </div>
                </div>

                <h5 class="box-title" style="font-size: 20px;">ที่อยู่ ตามที่จดทะเบียน</h5>

                 <div class="form-group ">
                    <div class="col-xs-3">
                        <input id="trader_address" type="text" class="form-control{{ $errors->has('trader_address') ? ' is-invalid' : '' }}" placeholder="เลขที่ / อาคาร ชั้น" name="trader_address" value="{{ $soko->trader_address??old('trader_address') }}" required autofocus>

                        @if ($errors->has('trader_address'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-xs-3">
                        <input id="trader_address_soi" type="text" class="form-control{{ $errors->has('trader_address_soi') ? ' is-invalid' : '' }}" placeholder="ซอย/ตรอก" name="trader_address_soi" value="{{ $soko->trader_address_soi??old('trader_address_soi') }}" autofocus>

                        @if ($errors->has('trader_address_soi'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_soi') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-3">
                        <input id="trader_address_road" type="text" class="form-control{{ $errors->has('trader_address_road') ? ' is-invalid' : '' }}" name="trader_address_road" placeholder="ถนน" value="{{ $soko->trader_address_road??old('trader_address_road') }}">

                        @if ($errors->has('trader_address_road'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_road') }}</strong>
                                    </span>
                        @endif
                    </div>
                      <div class="col-xs-3">
                        <input id="trader_address_moo" type="text" class="form-control{{ $errors->has('trader_address_moo') ? ' is-invalid' : '' }}" name="trader_address_moo" placeholder="หมู่" value="{{ $soko->trader_address_moo??old('trader_address_moo') }}">

                        @if ($errors->has('trader_address_moo'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_moo') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                    <div class="form-group ">
                    <div class="col-xs-3">
                        <input id="trader_address_tumbol" type="text" class="form-control{{ $errors->has('trader_address_tumbol') ? ' is-invalid' : '' }}" name="trader_address_tumbol" placeholder="ตำบล/แขวง" value="{{ $soko->trader_address_tumbol??old('trader_address_tumbol') }}" required>

                        @if ($errors->has('trader_address_tumbol'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_tumbol') }}</strong>
                                    </span>
                        @endif
                    </div>
                      <div class="col-xs-3">
                        <input id="trader_address_amphur" type="text" class="form-control{{ $errors->has('trader_address_amphur') ? ' is-invalid' : '' }}" name="trader_address_amphur" placeholder="อำเภอ/เขต" value="{{ $soko->trader_address_amphur??old('trader_address_amphur') }}" required>

                        @if ($errors->has('trader_address_amphur'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_amphur') }}</strong>
                                    </span>
                        @endif
                    </div>

                <div class="col-xs-3">
                    {{-- {{ dd(trim($soko->trader_provinceID)) }} --}}

                        <select id="trader_provinceID" class="form-control{{ $errors->has('trader_provinceID') ? ' is-invalid' : '' }}" name="trader_provinceID" value="{{ $soko->trader_provinceID??old('trader_provinceID') }}" required autofocus>

                        <option value="">- เลือกจังหวัด -</option>
                                    <option value="กรุงเทพมหานคร   " @if(trim($soko->trader_provinceID)=="กรุงเทพมหานคร")selected="selected" @endif>กรุงเทพมหานคร   </option>
                                    <option value="สมุทรปราการ   " @if(trim($soko->trader_provinceID)=="สมุทรปราการ")selected="selected" @endif>สมุทรปราการ   </option>
                                    <option value="นนทบุรี   " @if(trim($soko->trader_provinceID)=="นนทบุรี")selected="selected" @endif>นนทบุรี   </option>
                                    <option value="ปทุมธานี   " @if(trim($soko->trader_provinceID)=="ปทุมธานี")selected="selected" @endif>ปทุมธานี   </option>
                                    <option value="พระนครศรีอยุธยา   " @if(trim($soko->trader_provinceID)=="พระนครศรีอยุธยา")selected="selected" @endif>พระนครศรีอยุธยา   </option>
                                    <option value="อ่างทอง   " @if(trim($soko->trader_provinceID)=="อ่างทอง")selected="selected" @endif>อ่างทอง   </option>
                                    <option value="ลพบุรี   " @if(trim($soko->trader_provinceID)=="ลพบุรี")selected="selected" @endif>ลพบุรี   </option>
                                    <option value="สิงห์บุรี   " @if(trim($soko->trader_provinceID)=="สิงห์บุรี")selected="selected" @endif>สิงห์บุรี   </option>
                                    <option value="ชัยนาท   " @if(trim($soko->trader_provinceID)=="ชัยนาท")selected="selected" @endif>ชัยนาท   </option>
                                    <option value="สระบุรี" @if(trim($soko->trader_provinceID)=="สระบุรี")selected="selected" @endif>สระบุรี</option>
                                    <option value="ชลบุรี   " @if(trim($soko->trader_provinceID)=="ชลบุรี")selected="selected" @endif>ชลบุรี   </option>
                                    <option value="ระยอง   " @if(trim($soko->trader_provinceID)=="ระยอง")selected="selected" @endif>ระยอง   </option>
                                    <option value="จันทบุรี   " @if(trim($soko->trader_provinceID)=="จันทบุรี")selected="selected" @endif>จันทบุรี   </option>
                                    <option value="ตราด   " @if(trim($soko->trader_provinceID)=="ตราด")selected="selected" @endif>ตราด   </option>
                                    <option value="ฉะเชิงเทรา   " @if(trim($soko->trader_provinceID)=="ฉะเชิงเทรา")selected="selected" @endif>ฉะเชิงเทรา   </option>
                                    <option value="ปราจีนบุรี   " @if(trim($soko->trader_provinceID)=="ปราจีนบุรี")selected="selected" @endif>ปราจีนบุรี   </option>
                                    <option value="นครนายก   " @if(trim($soko->trader_provinceID)=="นครนายก")selected="selected" @endif>นครนายก   </option>
                                    <option value="สระแก้ว   " @if(trim($soko->trader_provinceID)=="สระแก้ว")selected="selected" @endif>สระแก้ว   </option>
                                    <option value="นครราชสีมา   " @if(trim($soko->trader_provinceID)=="นครราชสีมา")selected="selected" @endif>นครราชสีมา   </option>
                                    <option value="บุรีรัมย์   " @if(trim($soko->trader_provinceID)=="บุรีรัมย์")selected="selected" @endif>บุรีรัมย์   </option>
                                    <option value="สุรินทร์   " @if(trim($soko->trader_provinceID)=="สุรินทร์")selected="selected" @endif>สุรินทร์   </option>
                                    <option value="ศรีสะเกษ   " @if(trim($soko->trader_provinceID)=="ศรีสะเกษ")selected="selected" @endif>ศรีสะเกษ   </option>
                                    <option value="อุบลราชธานี   " @if(trim($soko->trader_provinceID)=="อุบลราชธานี")selected="selected" @endif>อุบลราชธานี   </option>
                                    <option value="ยโสธร   " @if(trim($soko->trader_provinceID)=="ยโสธร")selected="selected" @endif>ยโสธร   </option>
                                    <option value="ชัยภูมิ   " @if(trim($soko->trader_provinceID)=="ชัยภูมิ")selected="selected" @endif>ชัยภูมิ   </option>
                                    <option value="อำนาจเจริญ   " @if(trim($soko->trader_provinceID)=="อำนาจเจริญ")selected="selected" @endif>อำนาจเจริญ   </option>
                                    <option value="หนองบัวลำภู   " @if(trim($soko->trader_provinceID)=="หนองบัวลำภู")selected="selected" @endif>หนองบัวลำภู   </option>
                                    <option value="ขอนแก่น   " @if(trim($soko->trader_provinceID)=="ขอนแก่น")selected="selected" @endif>ขอนแก่น   </option>
                                    <option value="อุดรธานี   " @if(trim($soko->trader_provinceID)=="อุดรธานี")selected="selected" @endif>อุดรธานี   </option>
                                    <option value="เลย   " @if(trim($soko->trader_provinceID)=="เลย")selected="selected" @endif>เลย   </option>
                                    <option value="หนองคาย   " @if(trim($soko->trader_provinceID)=="หนองคาย")selected="selected" @endif>หนองคาย   </option>
                                    <option value="มหาสารคาม   " @if(trim($soko->trader_provinceID)=="มหาสารคาม")selected="selected" @endif>มหาสารคาม   </option>
                                    <option value="ร้อยเอ็ด   " @if(trim($soko->trader_provinceID)=="ร้อยเอ็ด")selected="selected" @endif>ร้อยเอ็ด   </option>
                                    <option value="กาฬสินธุ์   " @if(trim($soko->trader_provinceID)=="กาฬสินธุ์")selected="selected" @endif>กาฬสินธุ์   </option>
                                    <option value="สกลนคร   " @if(trim($soko->trader_provinceID)=="สกลนคร")selected="selected" @endif>สกลนคร   </option>
                                    <option value="นครพนม   " @if(trim($soko->trader_provinceID)=="นครพนม")selected="selected" @endif>นครพนม   </option>
                                    <option value="มุกดาหาร   " @if(trim($soko->trader_provinceID)=="มุกดาหาร")selected="selected" @endif>มุกดาหาร   </option>
                                    <option value="เชียงใหม่   " @if(trim($soko->trader_provinceID)=="เชียงใหม")selected="selected" @endif>เชียงใหม่   </option>
                                    <option value="ลำพูน   " @if(trim($soko->trader_provinceID)=="ลำพูน")selected="selected" @endif>ลำพูน   </option>
                                    <option value="ลำปาง   " @if(trim($soko->trader_provinceID)=="ลำปาง")selected="selected" @endif>ลำปาง   </option>
                                    <option value="อุตรดิตถ์   " @if(trim($soko->trader_provinceID)=="อุตรดิตถ์")selected="selected" @endif>อุตรดิตถ์   </option>
                                    <option value="แพร่   " @if(trim($soko->trader_provinceID)=="แพร่")selected="selected" @endif>แพร่   </option>
                                    <option value="น่าน   " @if(trim($soko->trader_provinceID)=="น่าน")selected="selected" @endif>น่าน   </option>
                                    <option value="พะเยา   " @if(trim($soko->trader_provinceID)=="พะเยา")selected="selected" @endif>พะเยา   </option>
                                    <option value="เชียงราย   " @if(trim($soko->trader_provinceID)=="เชียงราย")selected="selected" @endif>เชียงราย   </option>
                                    <option value="แม่ฮ่องสอน   " @if(trim($soko->trader_provinceID)=="แม่ฮ่องสอน")selected="selected" @endif>แม่ฮ่องสอน   </option>
                                    <option value="นครสวรรค์   " @if(trim($soko->trader_provinceID)=="นครสวรรค์")selected="selected" @endif>นครสวรรค์   </option>
                                    <option value="อุทัยธานี   " @if(trim($soko->trader_provinceID)=="อุทัยธานี")selected="selected" @endif>อุทัยธานี   </option>
                                    <option value="กำแพงเพชร   " @if(trim($soko->trader_provinceID)=="กำแพงเพชร")selected="selected" @endif>กำแพงเพชร   </option>
                                    <option value="ตาก   " @if(trim($soko->trader_provinceID)=="ตาก")selected="selected" @endif>ตาก   </option>
                                    <option value="สุโขทัย   " @if(trim($soko->trader_provinceID)=="สุโขทัย")selected="selected" @endif>สุโขทัย   </option>
                                    <option value="พิษณุโลก   " @if(trim($soko->trader_provinceID)=="พิษณุโลก")selected="selected" @endif>พิษณุโลก   </option>
                                    <option value="พิจิตร   " @if(trim($soko->trader_provinceID)=="พิจิตร")selected="selected" @endif>พิจิตร   </option>
                                    <option value="เพชรบูรณ์   " @if(trim($soko->trader_provinceID)=="เพชรบูรณ์")selected="selected" @endif>เพชรบูรณ์   </option>
                                    <option value="ราชบุรี   " @if(trim($soko->trader_provinceID)=="ราชบุรี")selected="selected" @endif>ราชบุรี   </option>
                                    <option value="กาญจนบุรี   " @if(trim($soko->trader_provinceID)=="กาญจนบุรี")selected="selected" @endif>กาญจนบุรี   </option>
                                    <option value="สุพรรณบุรี   " @if(trim($soko->trader_provinceID)=="สุพรรณบุรี")selected="selected" @endif>สุพรรณบุรี   </option>
                                    <option value="นครปฐม   " @if(trim($soko->trader_provinceID)=="นครปฐม")selected="selected" @endif>นครปฐม   </option>
                                    <option value="สมุทรสาคร   " @if(trim($soko->trader_provinceID)=="สมุทรสาคร")selected="selected" @endif>สมุทรสาคร   </option>
                                    <option value="สมุทรสงคราม   " @if(trim($soko->trader_provinceID)=="สมุทรสงคราม")selected="selected" @endif>สมุทรสงคราม   </option>
                                    <option value="เพชรบุรี   " @if(trim($soko->trader_provinceID)=="เพชรบุรี")selected="selected" @endif>เพชรบุรี   </option>
                                    <option value="ประจวบคีรีขันธ์   " @if(trim($soko->trader_provinceID)=="ประจวบคีรีขันธ์")selected="selected" @endif>ประจวบคีรีขันธ์   </option>
                                    <option value="นครศรีธรรมราช   " @if(trim($soko->trader_provinceID)=="นครศรีธรรมราช")selected="selected" @endif>นครศรีธรรมราช   </option>
                                    <option value="กระบี่   " @if(trim($soko->trader_provinceID)=="กระบี่")selected="selected" @endif>กระบี่   </option>
                                    <option value="พังงา   " @if(trim($soko->trader_provinceID)=="พังงา")selected="selected" @endif>พังงา   </option>
                                    <option value="ภูเก็ต   " @if(trim($soko->trader_provinceID)=="ภูเก็ต")selected="selected" @endif>ภูเก็ต   </option>
                                    <option value="สุราษฎร์ธานี   " @if(trim($soko->trader_provinceID)=="สุราษฎร์ธานี")selected="selected" @endif>สุราษฎร์ธานี   </option>
                                    <option value="ระนอง   " @if(trim($soko->trader_provinceID)=="ระนอง")selected="selected" @endif>ระนอง   </option>
                                    <option value="ชุมพร   " @if(trim($soko->trader_provinceID)=="ชุมพร")selected="selected" @endif>ชุมพร   </option>
                                    <option value="สงขลา   " @if(trim($soko->trader_provinceID)=="สงขลา")selected="selected" @endif>สงขลา   </option>
                                    <option value="สตูล   " @if(trim($soko->trader_provinceID)=="สตูล")selected="selected" @endif>สตูล   </option>
                                    <option value="ตรัง   " @if(trim($soko->trader_provinceID)=="ตรัง")selected="selected" @endif>ตรัง   </option>
                                    <option value="พัทลุง   " @if(trim($soko->trader_provinceID)=="พัทลุง")selected="selected" @endif>พัทลุง   </option>
                                    <option value="ปัตตานี   " @if(trim($soko->trader_provinceID)=="ปัตตานี")selected="selected" @endif>ปัตตานี   </option>
                                    <option value="ยะลา   " @if(trim($soko->trader_provinceID)=="ยะลา")selected="selected" @endif>ยะลา   </option>
                                    <option value="นราธิวาส   " @if(trim($soko->trader_provinceID)=="นราธิวาส")selected="selected" @endif>นราธิวาส   </option>
                                    <option value="บึงกาฬ" @if(trim($soko->trader_provinceID)=="บึงกาฬ")selected="selected" @endif>บึงกาฬ</option>
                                </select>
                        @if ($errors->has('trader_provinceID'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_provinceID') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-3">
                        <input id="trader_address_poscode" type="text" class="form-control{{ $errors->has('trader_address_poscode') ? ' is-invalid' : '' }}" placeholder="รหัสไปรษณีย์" name="trader_address_poscode" value="{{ $soko->trader_address_poscode??old('trader_address_poscode')}}" required>

                        @if ($errors->has('trader_address_poscode'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_address_poscode') }}</strong>
                                    </span>
                        @endif
                    </div>

                </div>


                <div class="form-group">
                    <div class="col-xs-3">
                        <input id="trader_phone" type="text" class="form-control{{ $errors->has('trader_phone') ? ' is-invalid' : '' }}" placeholder="หมายเลขโทรศัพท์" name="trader_phone" value="{{ $soko->trader_phone??old('trader_phone')}}" required>

                        @if ($errors->has('trader_phone'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_phone') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-3">
                        <input id="trader_phone_to" type="text" class="form-control{{ $errors->has('trader_phone_to') ? ' is-invalid' : '' }}" placeholder="ต่อ" name="trader_phone_to" value="{{ $soko->trader_phone_to??old('trader_phone_to')}}">

                        @if ($errors->has('trader_phone_to'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_phone_to') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="col-xs-3">
                        <input id="trader_fax" type="text" class="form-control{{ $errors->has('trader_fax') ? ' is-invalid' : '' }}" placeholder="หมายเลขโทรสาร" name="trader_fax" value="{{ $soko->trader_fax??old('trader_fax')}}">

                        @if ($errors->has('trader_fax'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_fax') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-3">
                        <input id="trader_fax_to" type="text" class="form-control{{ $errors->has('trader_fax_to') ? ' is-invalid' : '' }}" placeholder="ต่อ" name="trader_fax_to" value="{{ $soko->trader_fax_to??old('trader_fax_to')}}">

                        @if ($errors->has('trader_fax_to'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_fax_to') }}</strong>
                                    </span>
                        @endif
                    </div>

                </div>

                   <div class="form-group ">
                    <div class="col-xs-3">
                        <input id="trader_mobile" type="text" class="form-control{{ $errors->has('trader_mobile') ? ' is-invalid' : '' }}" placeholder="โทรศัพท์มือถือ" name="trader_mobile" value="{{ $soko->trader_mobile??old('trader_mobile')}}">

                        @if ($errors->has('trader_mobile'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('trader_mobile') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-3"></div>

                    <div class="col-xs-6">
                        <input id="agent_email" type="agent_email" class="form-control{{ $errors->has('agent_email') ? ' is-invalid' : '' }}" placeholder="Email สำหรับติดต่อ" name="agent_email" value="{{ $soko->agent_email??old('agent_email')}}" required>

                        @if ($errors->has('agent_email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('agent_email') }}</strong>
                                    </span>
                        @endif
                        {{-- <span style="color: red; font-size: 16px;"> * ควรเป็น E-mail ที่ใช้งานได้จริง</span> --}}
                    </div>

                </div>

                  {{-- <span style="color: red;"> * กรุณากรอกหมายเลขโทรศัพท์ ประเภทใดประเภทหนึ่งหรือทั้งหมด</span> --}}

                <h5 class="box-title" style="font-size: 20px;">ข้อมูลสำหรับการติดต่อ (Contact information)</h5>

                  <div class="form-group">
                    <div class="col-xs-6">
                        <input id="agent_name" type="text" class="form-control{{ $errors->has('agent_name') ? ' is-invalid' : '' }}" placeholder="ชื่อบุคคลที่ติดต่อ" name="agent_name" value="{{ $soko->agent_name??old('agent_name')}}" required>

                        @if ($errors->has('agent_name'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('agent_name') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-6">
                        <input id="agent_mobile" type="text" class="form-control{{ $errors->has('agent_mobile') ? ' is-invalid' : '' }}" placeholder="โทรศัพท์ผู้ติดต่อ" name="agent_mobile" value="{{ $soko->agent_mobile??old('agent_mobile')}}" required>

                        @if ($errors->has('agent_mobile'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('agent_mobile') }}</strong>
                                    </span>
                        @endif
                    </div>

                </div>


  </div>

</div>

<div class="col-md-4">

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title m-b-0">กลุ่มผู้ใช้งาน</h3>
      <p class="text-muted m-b-30 font-13"> จัดการกลุ่มผู้ใช้งาน </p>

        <div class="form-group">
          {!! Form::label('roles', ' ', ['class' => 'col-sm-1 control-label']) !!}
          <div class="col-sm-11">

            @foreach ($roles as $role)
              @if($role->label!='trader')
                @continue
              @endif
              <div class="checkbox checkbox-success">
                  {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $trader_roles), ['class' => 'form-control']) !!}
                  <label for="roles">&nbsp;{{ $role->name }}</label>
              </div>
            @endforeach

          </div>
        </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title m-b-0">เปลี่ยนรหัสผ่าน</h3>
      <p class="text-muted m-b-30 font-13"> ถ้าไม่เปลียนปล่อยว่างไว้ </p>

      <div class="form-group">
        {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-5 control-label']) !!}
        <div class="col-sm-7">
          {!! Form::password('password', ['class' => 'form-control']) !!}
          {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
  </div>

</div>

<div class="form-group">
  <div class="col-md-offset-4 col-md-4">

    <button class="btn btn-primary" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
    @can('view-'.str_slug('soko'))
    <a class="btn btn-default" href="{{url('/basic/soko')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
  <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script>
        $(document).ready(function () {

            // $('#trader_provinceID').trigger('change');

            $('form').submit(function() {
                $(this).find("button[type='submit']").prop('disabled',true);
            });

            $('#checkbox-signup').attr('checked', false); // Unchecks it

            $('#checkbox-signup').click(function(){
                if($(this).is(':checked')){
                    $('#sign_applicant').attr('disabled', false);
                } else {
                    $('#sign_applicant').attr('disabled', true);
                }
            });

            // Date Picker Thai
            $('.datepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            if ($('#trader_type2').is(':checked')) {
                $('#trader_inti').html('');
                    var html = '<option value="">=== เลือกคำนำหน้า ===</option>';
                        html += '<option value="นาย" @if($soko->trader_inti=="นาย")selected="selected" @endif > นาย </option>';
                        html += '<option value="นาง" @if($soko->trader_inti=="นาง")selected="selected" @endif > นาง </option>';
                        html += '<option value="นางสาว" @if($soko->trader_inti=="นาง")selected="selected" @endif > นางสาว </option>';
                $('#trader_inti').append(html);
                $('#trader_inti').trigger('change');
                $('#operater_name').attr("placeholder", "ขื่อ - สกุล");
                $('#trader_id').attr("placeholder", "เลข13หลัก/Passpost");
                $('#date').val('');
                $('#for_date_niti').hide(300);
            }

            $('input[name="trader_type"]').click(function() {
                if ($('#trader_type1').is(':checked')) {
                    $('#trader_inti').html('');
                        var html = '<option value="">=== เลือกประเภทการจดทะเบียน ===</option>';
                            html += '<option value="บริษัท จำกัด"> บริษัท จำกัด </option>';
                            html += '<option value="บริษัท จำกัด มหาชน"> บริษัท จำกัด มหาชน </option>';
                            html += '<option value="ห้างหุ้นส่วนจำกัด"> ห้างหุ้นส่วนจำกัด </option>';
                            html += '<option value="รัฐวิสาหกิจ"> รัฐวิสาหกิจ </option>';
                            html += '<option value="อื่น ๆ"> อื่น ๆ </option>';
                    $('#trader_inti').append(html);
                    $('#trader_inti').trigger('change');
                    $('#operater_name').attr("placeholder", "ชื่อสถานประกอบการ เช่น บริษัท ตัวอย่าง จำกัด");
                    $('#trader_id').attr("placeholder", "เลขนิติบุคคล");
                    $('#for_date_niti').show(300);
                } else {
                    $('#trader_inti').html('');
                        var html = '<option value="">=== เลือกคำนำหน้า ===</option>';
                            html += '<option value="นาย"> นาย </option>';
                            html += '<option value="นาง"> นาง </option>';
                            html += '<option value="นางสาว"> นางสาว </option>';
                    $('#trader_inti').append(html);
                    $('#trader_inti').trigger('change');
                    $('#operater_name').attr("placeholder", "ขื่อ - สกุล");
                    $('#trader_id').attr("placeholder", "เลข13หลัก/Passpost");
                    $('#date').val('');
                    $('#for_date_niti').hide(300);
                }
            });


        });

        function checkmailexits(email)
        {
            if(email){
                $('#agent_email').val(email);
                $.ajax({
                    url: "{!! url('basic/soko/checkemailexits') !!}",
                    type: 'POST',
                    data: { email: email, _token: '{{csrf_token()}}' },
                }).done(function(response) {
                    // console.log(response);
                    if(response == "already")
                    {
                    alert("Email Already In Use.");
                    $('#trader_username').val('');
                    $('#agent_email').val('');
                    }
                });
            }

        }
    </script>
@endpush
