@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

    <style>
        .div_dotted {
            border-bottom: 1px dotted #000;
            /* padding: 0 0 5px 0;
            cursor: not-allowed; */
        }

        .form-body input[type="text"]:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
        }

        .form-body textarea:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
        }
        tbody tr:hover {
            background-color: unset !important;
        }
    </style>
@endpush


<div class="form-body">
    <div class="row">
        <div class="col-md-8">
            <fieldset class="white-box">
                <legend class="legend"> <h4>ส่วนที่ 1: ข้อมูลเจ้าของคดี(ผู้แจ้ง)</h4></legend>

                @php
                    $owner_depart_type_arr =  ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก']
                @endphp

                <div class="row">
                    <div class="col-md-offset-1 col-md-11">
                        <div class="divider divider-left divider-secondary">
                            <div class="divider-text">ผู้แจ้ง</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">หน่วยงาน :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_depart_type) && array_key_exists( $lawcase->owner_depart_type, $owner_depart_type_arr ) ? $owner_depart_type_arr[$lawcase->owner_depart_type] :'-' !!} </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-5">ชื่อหน่วยงาน/กอง/กลุ่ม :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->OwnerDeparmentName)?$lawcase->OwnerDeparmentName:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ((!empty($lawcase->law_deparment->other) && $lawcase->law_deparment->other == 1 ))
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-5">อื่นๆระบุ</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->OwnerDeparmentOther)?$lawcase->OwnerDeparmentOther:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">ข้าพเจ้า :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_name)?$lawcase->owner_name:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-5">เลขประจำตัวผู้เสียภาษี :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_taxid)?$lawcase->owner_taxid:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">อีเมล :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_email)?$lawcase->owner_email:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-5">เบอร์มือถือ :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_phone)?$lawcase->owner_phone:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">เบอร์โทร :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_tel)?$lawcase->owner_tel:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-1 col-md-11">
                        <div class="divider divider-left divider-secondary">
                            <div class="divider-text">ติดต่อประสานงาน</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">ชื่อ-สกุล :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_contact_name)?$lawcase->owner_contact_name:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-5">อีเมล :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_contact_email)?$lawcase->owner_contact_email:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-0">
                            <label class="control-label col-md-6">เบอร์มือถือ :</label>
                            <div class="col-md-6">
                                <p class="form-control-static div_dotted"> {!!  !empty($lawcase->owner_contact_phone)?$lawcase->owner_contact_phone:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="col-md-4">

            <div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!! $lawcase->StatusText !!} </div>

            <fieldset class="white-box">

                <div class="row p-t-20">
                    <div class="col-md-12">
                        <div class="form-group m-2">
                            <label class="control-label col-md-5">เลขที่อ้างอิง :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!! !empty($lawcase->ref_no)?$lawcase->ref_no:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-2">
                            <label class="control-label col-md-5">วันที่แจ้ง :<div><span class="text-muted  font-15"><i>(ผ่านระบบ)</i></span></div></label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!! !empty($lawcase->created_at)?HP::DateThaiFull($lawcase->created_at):'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-2">
                            <label class="control-label col-md-5">เลขคดี :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!! !empty($lawcase->case_number)?$lawcase->case_number:'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12">
                        <div class="form-group m-2 m-b-30">
                            <label class="control-label col-md-5">นิติกรเจ้าของคดี :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted">{!! !empty($lawcase->user_lawyer_to)?$lawcase->user_lawyer_to->FullName:'รอมอบหมาย' !!}  </p>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="white-box">
                <legend class="legend"><h4>ส่วนที่ 2 : ข้อมูลผู้ต้องหา/ผู้กระทำความผิด</h4></legend>

                @if( $lawcase->owner_depart_type == 2 )
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="control-label col-md-5">วันที่แจ้งเรื่อง :</label>
                                <div class="col-md-7">
                                    <p class="form-control-static div_dotted"> {!! !empty($lawcase->offend_report_date)?HP::revertDate($lawcase->offend_report_date, true) :'-' !!} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label class="control-label col-md-5">วันที่ลงรับเรื่อง :</label>
                                <div class="col-md-7">
                                    <p class="form-control-static div_dotted"> {!! !empty($lawcase->offend_accept_date)?HP::revertDate($lawcase->offend_report_date, true) :'-' !!} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label class="control-label col-md-5">วันที่พบการกระทำความผิด :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!! !empty($lawcase->offend_date)?HP::revertDate($lawcase->offend_date, true) :'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label class="control-label col-md-5">สาเหตุที่พบ :</label>
                            <div class="col-md-7">
                                <p class="form-control-static div_dotted"> {!! !empty($lawcase->law_basic_offend_type_to)?$lawcase->law_basic_offend_type_to->title :'-' !!} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_license_type', 'ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <label>
                                    <div class="state iradio_square-green {!! $lawcase->offend_license_type == "1"?'checked':'' !!}"></div>&nbsp;&nbsp; มี&nbsp;&nbsp;&nbsp;
                                </label>
                                <label>
                                    <div class="state iradio_square-red {!! $lawcase->offend_license_type == "2"?'checked':'' !!}"></div>&nbsp;&nbsp; ไม่มี
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                   $license_pdf = $lawcase->tb4_tisilicense;
                @endphp

                @if (!empty( $lawcase->offend_license_type) &&  $lawcase->offend_license_type == 1)
                <div class="row" id="box_license">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_license_number', 'เลขที่ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_license_number', null , ['class' => 'form-control', 'id'=>'offend_license_number']) !!}
                                {!! $errors->first('offend_license_number', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6">
                            {!! Form::label('offend_license_number', 'ใบอนุญาต', ['class' => 'col-md-5 control-label']) !!}
                            <p id="show_license">
                                @if(!empty($license_pdf))
                                <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/{{ $license_pdf->license_pdf }}" target="_blank">
                                        <i class="fa fa-file-pdf-o" style="font-size:35px; color:orange" aria-hidden="true"></i>
                                        </a>
                                    &nbsp [ <b style="color:green">ใช้งาน</b> ]
                                @else
                                    <i class="fa fa-file-text" style="font-size:35px; color:#92b9b9" aria-hidden="true"></i>
                                    &nbsp [ <b style="color:red">ไม่พบไฟล์</b> ]
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            {!! Form::checkbox('offend_license_notify', '1', (!empty($lawcase->offend_license_notify)?true:(empty($lawcase->offend_license_notify)?false:null)), ['class'=>'check', 'id' => 'offend_license_notify', 'data-checkbox'=>'icheckbox_minimal-blue']) !!}
                            <label for="offend_license_notify">มีหนังสือแจ้งเตือนพักใช้หรือไม่?</label>
                        </div>
                    </div>
                </div>
                @endif


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_taxid', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_taxid', null , ['class' => 'form-control', 'id'=>'offend_taxid']) !!}
                                {!! $errors->first('offend_taxid', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_name', 'ผู้ประกอบการ/ผู้กระทำความผิด', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_name', null , ['class' => 'form-control', 'disabled' => true, 'id'=>'offend_name']) !!}
                                {!! Form::hidden('offend_sso_users_id', null , ['id'=>'offend_sso_users_id']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_address', 'ที่ตั้งสำนักงานใหญ่/ที่อยู่', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('offend_address', null , ['class' => 'form-control', 'disabled' => true, 'rows'=>1, 'id'=>'offend_address']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                            {!! Form::label('offend_soi', 'ตรอก/ซอย', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_soi', !empty($lawcase)?$lawcase->offend_soi:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_moo', 'หมู่', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_moo', !empty($lawcase)?$lawcase->offend_moo:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_street', 'ถนน', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_street', !empty($lawcase)?$lawcase->offend_street:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_subdistrict_txt', 'ตำบล/แขวง', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_subdistrict_txt', !empty($lawcase->offend_subdistricts)?trim($lawcase->offend_subdistricts->DISTRICT_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! HTML::decode(Form::label('offend_district_txt', 'อำเภอ/เขต', ['class' => 'col-md-5 control-label'])) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_district_txt', !empty($lawcase->offend_districts)?trim($lawcase->offend_districts->AMPHUR_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_province_txt', 'จังหวัด', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_province_txt', !empty($lawcase->offend_provinces)?trim($lawcase->offend_provinces->PROVINCE_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_zipcode', 'รหัสไปรษณีย์', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_zipcode', !empty($lawcase)?$lawcase->offend_zipcode:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_tel', 'เบอร์โทร', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_tel', !empty($lawcase)?$lawcase->offend_tel:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ">
                            {!! Form::label('offend_email', 'อีเมล', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_email', !empty($lawcase)?$lawcase->offend_email:null , ['class' => 'form-control', 'disabled' => true, 'id'=>'offend_email']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group ">
                                {!! Form::label('offend_power', 'กรรมการบริษัท', ['class' => 'col-md-5 control-label']) !!}
                                <div class="col-md-7">

                                    @if( is_array($lawcase->offend_power) )
                                        @foreach ( $lawcase->offend_power  as $offend_power )
                                            {!! Form::text('offend_power', !empty($offend_power)?$offend_power:'-' , ['class' => 'form-control','placeholder' => 'กรอกชื่อกรรมการ', 'disabled' => true]) !!}
                                        @endforeach
                                    @else
                                        {!! Form::text('offend_power', '-' , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                    @endif
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $tis = $lawcase->tis;
                    // dd($lawcase);
                @endphp

                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('tb3_tisno', 'มาตรฐานผลิตภัณฑ์อุตสาหกรรม', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-8">
                                <table class="table color-bordered-table info-bordered-table table-bordered table-sm" id="table_tisno">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="2%">#</th>
                                            <th class="text-center" width="30%">เลข มอก.</th>
                                            <th class="text-center" width="68%">ผลิตภัณฑ์อุตสาหกรรม</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_tbody_tisno">
                                        @if (!empty($lawcase) && count($lawcase->cases_standards) > 0)
                                            @foreach ($lawcase->cases_standards as $key => $item)
                                                    <tr>
                                                        <td class="text-top text-center">
                                                            {!! ($key+1) !!}
                                                        </td>
                                                        <td class="text-top text-center">
                                                            <input type="hidden"  name="standard[tb3_tisno][]" value="{!!  $item->tb3_tisno !!}">
                                                            {!!  $item->tb3_tisno !!}
                                                        </td>
                                                        <td class="text-top text-left">
                                                            {!!  !empty($item->tis->tb3_TisThainame) ?  $item->tis->tb3_TisThainame: ''  !!}
                                                        </td>
                                                    </tr>
                                            @endforeach 
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('tb3_tis_thainame', 'ผลิตภัณฑ์อุตสาหกรรม', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('tb3_tis_thainame', !empty($tis)?$tis->tb3_TisThainame:null , ['class' => 'form-control', 'disabled' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_contact_name', 'ชื่อ-สกุลผู้ประสานงาน', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_contact_name', null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_contact_tel', 'เบอร์โทร', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_contact_tel', null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_contact_email', 'อีเมล', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_contact_email', null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('law_basic_arrest_id', 'มีการจับกุม', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('law_basic_arrest_id', !empty($lawcase->law_basic_arrest_to->title)?$lawcase->law_basic_arrest_to->title:'-'  , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('law_basic_offend_type_id', 'สาเหตุที่พบ', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('law_basic_offend_type_id', !empty($lawcase->law_basic_offend_type_to->title)?$lawcase->law_basic_offend_type_to->title:'-' , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('offend_ref_id', 'เลขที่อ้างอิง (ถ้ามี)', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('offend_ref_id', !empty($lawcase->law_offend_type_to->title)?$lawcase->law_offend_type_to->title:'-' , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
 
                            {!! Form::label('law_basic_section_id', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                {!! Form::text('basic_section', !empty($lawcase->SectionListName)?$lawcase->SectionListName:'-', ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                @if( !empty($lawcase->sectionlist) && count($lawcase->sectionlist) > 0)
                                    <a type="button" class="btn btn-link" data-toggle="collapse" href="#collapse_section">รายละเอียดฝ่าฝืนตามมาตรา</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if( !empty($lawcase->sectionlist) && count($lawcase->sectionlist) > 0)
                    <div id="collapse_section" class="row panel-collapse collapse">
                        <div class="col-md-6">
                            <div class="col-md-offset-5">
                                <table class="table table-striped">

                                    <thead>
                                        <td class="text-top">#</td>
                                        <td colspan="2">ฝ่าฝืนตามมาตรา</td>
                                    </thead>

                                    <tbody>
                                        @foreach ( $lawcase->sectionlist  as $ls => $section )
                                            <tr>
                                                <td class="text-top">{!!  ++$ls  !!}</td>
                                                <td class="text-top" width="25%">{!!  !empty($section->number)?$section->number:null  !!}</td>
                                                <td class="text-top" width="70%">{!!  !empty($section->title)?$section->title:null  !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-5 text-right">
                            <h4>รายละเอียดเพิ่มเติม</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::label('offend_location_detail', 'รายละเอียดเกี่ยวกับสถานที่ที่ตรวจพบการกระทำความผิด (เช่น ตรวจพบผลิตภัณฑ์อย่างไร สถานที่ดังกล่าว ประกอบกิจการอะไร ระยะเวลาที่ประกอบกิจการ)', ['class' => 'col-md-12']) !!}
                                {!! Form::textarea('offend_location_detail', !empty($lawcase->offend_location_detail)?$lawcase->offend_location_detail :'-', ['class' => 'form-control ', 'rows'=>3]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::label('offend_product_detail', 'รายละเอียดเกี่ยวกับผลิตภัณฑ์ที่ไม่เป็นไปตามมาตรฐาน/มีเหตุอันควรเชื่อว่าไม่เป็นไปตามมาตรฐานที่พนักงานตรวจสอบพบ', ['class' => 'col-md-12']) !!}
                                {!! Form::textarea('offend_product_detail', !empty($lawcase->offend_product_detail)?$lawcase->offend_product_detail :'-', ['class' => 'form-control ', 'rows'=>3]) !!}
                            </div>
                        </div>
                    </div>
                </div>


            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="white-box">
                <legend class="legend"><h4>ส่วนที่ 3 : รายการผลิตภัณฑ์ตรวจยึด-อายัด (ของกลาง)</h4></legend>
                @php
                    if(!empty($lawcase->offend_impound_type)){
                        $check1 = $lawcase->offend_impound_type == '1'?'checked':'';
                        $check2 = $lawcase->offend_impound_type == '2'?'checked':'';
                        $check1_condition = $lawcase->offend_impound_type == '1';
                        $check2_condition = $lawcase->offend_impound_type == '2';
                    }else if(!empty($lawcase->law_cases_impound_to->impound_status)){
                        $check1 = $lawcase->law_cases_impound_to->impound_status == '1'?'checked':'';
                        $check2 = $lawcase->law_cases_impound_to->impound_status == '2'?'checked':'';
                        $check1_condition = $lawcase->law_cases_impound_to->impound_status == '1';
                        $check2_condition = $lawcase->law_cases_impound_to->impound_status == '2';
                    }else{
                        $check1_condition = false;
                        $check2_condition = false;
                        $check1 = '';
                        $check2 ='';
                    }
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('impound_status', 'มีผลิตภัณฑ์ยึด-อายัดหรือไม่', ['class' => 'col-md-5 control-label']) !!}
                            <div class="col-md-7">
                                <label>
                                    <div class="state iradio_square-green {!! !empty($check1)?$check1:'' !!}"></div>&nbsp;&nbsp; มี&nbsp;&nbsp;&nbsp;
                                </label>
                                <label>
                                    <div class="state iradio_square-red {!! !empty($check2)?$check2:'' !!}"></div>&nbsp;&nbsp; ไม่มี
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $lawcasesimpound = $lawcase->law_cases_impound_to;
                @endphp

                @if($check1_condition)

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('date_impound', 'วันที่ยึด-อายัดของกลางไว้', ['class' => 'col-md-5 control-label']) !!}
                                <div class="col-md-7">
                                    <div class="inputWithIcon">
                                        {!! Form::text('date_impound', !empty($lawcasesimpound)?HP::revertDate($lawcasesimpound->date_impound,true):null, ['class' => 'form-control', 'disabled'=>'disabled', 'placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                        <i class="icon-calender"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5">สถานที่ตรวจยึด</label>
                                <div class="col-md-7">
                                    {!! Form::text('location', !empty($lawcasesimpound)?$lawcasesimpound->location:null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-11">
                            <div class="divider divider-left divider-secondary">
                                <div class="divider-text">รายการผลิตภัณฑ์ยึด-อายัด</div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-10">
                        <div class="col-md-12">

                            @php
                            $sum_impound     = 0;
                            $sum_price       = 0;
                            $sum_total_price = 0;
                            $sum_total = 0;
                        @endphp
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2" width="4%">#</th>
                                    <th class="text-center" rowspan="2" width="35%">รายการ</th>
                                    <th class="text-center" colspan="2">จำนวน</th>
                                    <th class="text-center" rowspan="2">จำนวน<br>ของทั้งหมดที่พบ</th>
                                    <th class="text-center" rowspan="2">หน่วย</th>
                                    <th class="text-center" rowspan="2">ราคา/หน่วย</th>
                                    <th class="text-center" rowspan="2" width="15%">รวมราคา</th>
                                </tr>
                                <tr>
                                    <th class="text-center" width="8%">ยึด</th>
                                    <th class="text-center" width="8%">อายัด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if( !empty($lawcasesimpound) && count( $lawcasesimpound->impound_product) >= 1 )

                                    @foreach ( $lawcasesimpound->impound_product as $key => $impound_data )
                                        @php
                                            $sum_impound     += (int)str_replace(',', '', $impound_data->amount_impounds);
                                            $sum_impound     += (int)str_replace(',', '', $impound_data->amount_keep);
                          
                                            $sum_total       += (int)str_replace(',', '', $impound_data->total);
                                            $sum_price       += (int)str_replace(',', '', $impound_data->price);
                                            $sum_total_price += (int)str_replace(',', '', $impound_data->total_price);
                                        @endphp
                                        <tr>
                                            <td class="text-center text-top">{!! ++$key !!}</td>
                                            <td class="text-center text-top">{!! !empty($impound_data->detail)?$impound_data->detail:null !!}</td>
                                            <td class="text-center text-top">{!! !empty($impound_data->amount_impounds)?number_format((int)str_replace(',', '', $impound_data->amount_impounds)):0 !!}</td>
                                            <td class="text-center text-top">{!! !empty($impound_data->amount_keep)?number_format((int)str_replace(',', '', $impound_data->amount_keep)):0 !!}</td>
                                            <td class="text-center text-top">{!! !empty($impound_data->total)?number_format((int)str_replace(',', '', $impound_data->total)):0 !!}</td>
                                            <td class="text-center text-top">{!! !empty($impound_data->unit)?$impound_data->unit:null !!}</td>
                                            <td class="text-right text-top">{!! !empty($impound_data->price)?number_format((int)str_replace(',', '', $impound_data->price)):0 !!}</td>
                                            <td class="text-right text-top">{!! !empty($impound_data->total_price)?number_format((int)str_replace(',', '', $impound_data->total_price)):0 !!}</td>
                                        </tr>
                                        
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center text-top" colspan="7">ไม่พบข้อมูล</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right">รวมของกลาง</td>
                                    <td colspan="2" class="text-right">{!! number_format($sum_impound) !!}</td>       
                                    <td class="text-right">{!! number_format($sum_total) !!}</td>
                                    <td></td>
                                    <td class="text-right">{!! number_format($sum_price) !!}</td>
                                    <td class="text-right">{!! number_format($sum_total_price) !!}</td>
                                </tr>
                            </tfoot>
                        </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5">แหล่งที่มาราคาผลิตภัณฑ์</label>
                                <div class="col-md-7">
                                    {!! Form::text('location', !empty($lawcase->law_cases_impound_to->law_basic_resource_to)?$lawcase->law_cases_impound_to->law_basic_resource_to->title:null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5">รวมมูลค่าของกลาง/บาท</label>
                                <div class="col-md-7">
                                    {!! Form::text('location', !empty($sum_total_price)?number_format($sum_total_price):null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- @endif --}}

                {{-- @if($check2_condition) --}}
                    <div class="row">
                        <div class="col-md-offset-1 col-md-11">
                            <div class="divider divider-left divider-secondary">
                                <div class="divider-text">สถานที่เก็บผลิตภัณฑ์</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('storage_name', 'ชื่อสถานที่', ['class' => 'col-md-2 control-label']) !!}
                                <div class="col-md-10">
                                    {!! Form::text('storage_name', !empty($lawcasesimpound)?$lawcasesimpound->storage_name:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! HTML::decode(Form::label('storage_address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-4 control-label'])) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_address_no', !empty($lawcasesimpound)?$lawcasesimpound->storage_address_no:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_soi', 'ตรอก/ซอย', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_soi', !empty($lawcasesimpound)?$lawcasesimpound->storage_soi:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_moo', 'หมู่', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_moo', !empty($lawcasesimpound)?$lawcasesimpound->storage_moo:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_street', 'ถนน', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_street', !empty($lawcasesimpound)?$lawcasesimpound->storage_street:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_subdistrict_txt', 'ตำบล/แขวง', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_subdistrict_txt', !empty($lawcasesimpound->storage_subdistricts)?trim($lawcasesimpound->storage_subdistricts->DISTRICT_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! HTML::decode(Form::label('storage_district_txt', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label'])) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_district_txt', !empty($lawcasesimpound->storage_districts)?trim($lawcasesimpound->storage_districts->AMPHUR_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_province_txt', 'จังหวัด', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_province_txt', !empty($lawcasesimpound->storage_provinces)?trim($lawcasesimpound->storage_provinces->PROVINCE_NAME):null, ['class' => 'form-control',  'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_zipcode', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_zipcode', !empty($lawcasesimpound)?$lawcasesimpound->storage_zipcode:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('storage_tel', 'เบอร์โทร', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('storage_tel', !empty($lawcasesimpound)?$lawcasesimpound->storage_tel:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                </fieldset>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <fieldset class="white-box">
                <legend class="legend"><h4>ส่วนที่ 4 : ผู้มีส่วนร่วมในคดี</h4></legend>

                <div class="row m-t-10">
                    <div class="col-md-12">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">#</th>
                                    <th class="text-center" width="23%">ชื่อ-สกุล</th>
                                    <th class="text-center" width="25%">ที่อยู่ (สำหรับออกใบสำคัญรับเงินรางวัล)</th>
                                    <th class="text-center" width="15%">กลุ่ม/กอง</th>
                                    <th class="text-center" width="15%">หน้าที่ในคดี</th>
                                    <th class="text-center" width="20%">ชื่อบัญชี/เลขที่บัญชี</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if( !empty($lawcase->cases_staff) && count( $lawcase->cases_staff) >= 1 )

                                    @foreach ( $lawcase->cases_staff as $ks => $staff )
                                        <tr data-repeater-item class="staff_tr">
                                            <td class="text-top text-center staff_list_no">{!! ++$ks !!}</td>
                                            <td class="text-top text-center">
                                                {!! !empty($staff->name)?$staff->name:null !!} 
                                                {{-- {!! !empty($staff->taxid)?'<div>('.$staff->taxid.')</div>':null !!}  --}}
                                            </td>
                                            <td class="text-top">
                                                {!! !empty($staff->address)?$staff->address:null !!} 
                                                <div><i class="icon-phone"></i>{!! !empty($staff->mobile)?$staff->mobile:null !!} </div>
                                                <div><i class="icon-envelope-open"></i>{!! !empty($staff->email)?$staff->email:null !!} </div>
                                            </td>
                                            <td class="text-top text-center">
                                                {!! !empty($staff->DeparmentName)?$staff->DeparmentName:null !!} 
                                                {!! !empty($staff->DeparmentTypeName)?'<div>('.$staff->DeparmentTypeName.')</div>':null !!} 
                                            </td>
                                            <td class="text-top text-center">
                                                {!! !empty($staff->reward_group)?$staff->reward_group->title:null !!} 
                                            </td>
                                            <td class="text-top text-center">
                                                {!! !empty($staff->ac_bank)?$staff->ac_bank->title:null !!} 
                                                {!! !empty($staff->bank_account_name)?'<div>'.$staff->bank_account_name.'</div>':null !!} 
                                                {!! !empty($staff->bank_account_number)?'<div>('.$staff->bank_account_number.')</div>':null !!} 
                        
                                                @if(!empty($staff->file_book_bank))
                                                    <a href="{!!  HP::getFileStorage($staff->file_book_bank->url) !!}" data-id="{!! $staff->file_book_bank->id !!}" class="attach" target="_blank">ไฟล์สมุดบัญชี</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                @else
                                    <tr>
                                        <td class="text-center text-top" colspan="5">ไม่พบข้อมูล</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>

            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="white-box">
                <legend class="legend"><h4>ส่วนที่ 5 : ไฟล์แนบ/หลักฐาน</h4></legend>

                @php
                    $config_evidence = [];
                    if( !empty($lawcase->config_evidence) ){
                        $configs_evidences = json_decode($lawcase->config_evidencce);
                    }else{
                        $configs_evidences = App\Models\Config\ConfigsEvidence::whereHas('configs_evidence_groups', function($query){
                                                            return $query->where('state', 1);
                                                        })
                                                        ->Where(function($query){
                                                            $query->where('state', 1)->where('evidence_group_id', 6);
                                                        })
                                                        ->orderBy('ordering')
                                                        ->get();
                    }
                @endphp

                @foreach ( $configs_evidences as $evidences )
                    @php
                        $attachment      = null;
                        $setting_file_id = $evidences->id;
                        if( isset($lawcase->id) ){
                            $attachment = App\Models\Law\File\AttachFileLaw::where('ref_table', (new App\Models\Law\Cases\LawCasesForm )->getTable() )
                                            ->where('ref_id', $lawcase->id )
                                            ->when($setting_file_id, function ($query, $setting_file_id){
                                                return $query->where('setting_file_id', $setting_file_id);
                                            })
                                            ->first();
                        }

                    @endphp

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($evidences->required == 1) required @endif">
                                {!! HTML::decode(Form::label('file_attachment', (!empty($evidences->title)?$evidences->title:null).' : ', ['class' => 'col-md-4 control-label'])) !!}

                                @if( is_null($attachment) )
                                    <div class="col-md-4">
                                        <a class="btn" disabeld><i class="fa fa-folder-open fa-lg" style="color:#999;" aria-hidden="true" ></i></a>
                                    </div>
                                @else
                                    <div class="col-md-4" >
                                        <a href="{!! HP::getFileStorage($attachment->url) !!}" target="_blank">
                                            {!! HP::FileExtension($attachment->filename)  ?? '' !!}
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-12">
        
                        @php
                            $file_other = [];
                            if( isset($lawcase->id) ){
                                $file_other = App\Models\Law\File\AttachFileLaw::where('section', 'evidence_file_other')->where('ref_table', (new App\Models\Law\Cases\LawCasesForm )->getTable() )->where('ref_id', $lawcase->id )->get();
                            }
                        @endphp
        
                        @foreach ( $file_other as $attach )
        
                            <div class="form-group">
                                {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.' : ', ['class' => 'col-md-4 control-label'])) !!}
                                <div class="col-md-3">
                                    {!! Form::text('file_documents', ( !empty($attach->caption) ? $attach->caption:null) , ['class' => 'form-control' , 'placeholder' => 'คำอธิบาย', 'disabled' => true]) !!}
                                </div>
                                <div class="col-md-2">
                                    <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                        {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                    </a>
                                </div>
                                <div class="col-md-1" >
        
                                </div>
                            </div>
        
                        @endforeach
        
                    </div>
                </div>

            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="white-box">
                <legend class="legend"><h4>ส่วนที่ 6 : การพิจารณา</h4></legend>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group required{{ $errors->has('offend_impound_type') ? 'has-error' : ''}}">
                            {!! Form::label('approve_type', 'ส่งเรื่องถึง', ['class' => 'col-md-2 control-label']) !!}
                            <div class="col-md-10">
                                <label>{!! Form::radio('approve_type', '1', !empty($lawcase->approve_type)?$lawcase->approve_type:true , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'approve_type_1']) !!} ส่งเรื่องถึงผู้มีอำนาจพิจารณา (ขออนุมัติผ่านระบบ)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('offend_impound_type') ? 'has-error' : ''}}">
                            {!! Form::label(' ', 'ส่งเรื่องถึง', ['class' => 'col-md-2 control-label']) !!}
                            <div class="col-md-10">
                                <label>{!! Form::radio('approve_type', '2', !empty($lawcase->approve_type)?$lawcase->approve_type:true , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'approve_type_2']) !!} ส่งเรื่องถึง กม.(ผ่านอนุมัตินอกระบบแล้ว)</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                @php
                $role  = [ 
                          '7'=>'จนท',
                          '6'=>'ผก',
                          '5'=>'ผอ',
                          '4'=>'ทป',
                          '2'=>'รมอ',
                          '1'=>'ลมอ'
                        ];
                
                $department =  App\Models\Besurv\Department::pluck('depart_name', 'did');
                
                @endphp
                <div class="row" id="div_repeater_approve">
                    <div class="col-md-12">
                            <p class="font-medium-6 text-orange m-t10"> * ระบบจะส่งอีเมลแจ้งเตือนพิจารณาตามลำดับ </p>
                            <table class="table color-bordered-table primary-bordered-table table-bordered table-sm repeater-form-approve" id="table_approve">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">ลำดับ</th>
                                        <th class="text-center" width="15%">ส่งถึง</th>
                                        <th class="text-center" width="20%">ส่งเรื่องถึงกอง</th>
                                        <th class="text-center" width="20%">ผู้มีอำนาจพิจารณา</th>
                                        <th class="text-center" width="20%">ตำแหน่ง</th>
                                        <th class="text-center" width="10%">รักษาการแทน</th>
                                        <th class="text-center" width="5%">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody data-repeater-list="repeater-approve" id="table_tbody_approve">
                                    @if( !empty($lawcase->cases_level_approve) && count($lawcase->cases_level_approve) >= 1 )
                                        @foreach(  $lawcase->cases_level_approve as $key=>$approve )
                                            @php
                                                    $sub_id =  App\Models\Basic\SubDepartment::where('did',  $approve->send_department )->select('sub_id');
                                                    $user   =  App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->pluck('runrecno');
                                                    if(!empty($user) && count($user) == 1){
                                                        $user_list =   App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->pluck('name','runrecno');
                                                    }else{
                                                        $user_list =   App\User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->pluck('name','runrecno');
                                                    }
                                            @endphp
                                        <tr  data-repeater-item>
                                            <td class="text-top text-center">
                                                <span class="td_approve_no">{{ $key+1 }}</span>
                                            </td>
                                            <td class="text-top">
                                                <div class="form-group col-md-12">
                                                    {!! Form::select('role',$role,!empty($approve->role)?$approve->role:null, ['class' => 'form-control role_approve', 'placeholder'=>'- เลือกกอง -', 'required' => true ]) !!}
                                                </div>
                                            </td>
                                            <td class="text-top">
                                                <div class="form-group col-md-12">
                                                    {!! Form::select('send_department',$department,!empty($approve->send_department)?$approve->send_department:null, ['class' => 'form-control send_department', 'placeholder'=>'- เลือกกอง -', 'required' => true]) !!}
                                                </div>
                                            </td>
                                            <td class="text-top">
                                                <div class="form-group col-md-12">
                                                    {!!  Form::select('authorize_userid',$user_list,!empty($approve->authorize_userid)?$approve->authorize_userid:null, ['class' => 'form-control authorize_userid_approve', 'placeholder'=>'- เลือกผู้มีอำนาจพิจารณา -', 'required' => true ]) !!}
                                                </div>
                                            </td>
                                            <td class="text-top">
                                                <div class="form-group col-md-12">
                                                    {!! Form::text('position',!empty($approve->position)?$approve->position:null , ['class' => 'form-control position_approve', 'required' => 'required']) !!}
                                                </div>
                                            </td>
                                            <td class="text-top text-center">
                                                <div class="form-group col-md-12">
                                                    {!! Form::checkbox('acting', '1', !empty($approve->acting)?$approve->acting:false, ['data-color'=>'#13dafe' ,'class'=>'acting' ,'id'=>'acting']) !!}
                                                </div>
                                            </td>
                                            <td class="text-top text-center ">
                                                <div class="td_approve_remove">
                                                    <button type="button" class="btn btn-danger btn-sm btn_approve_remove" >
                                                        <i class="fa fa-times"></i>
                                                    </button> 
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach       
                                        @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td class="text-top text-center">
                                            <button type="button" class="btn btn-success btn-sm btn_approve_add" >
                                                <i class="fa fa-plus"></i>
                                            </button>  
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                    </div>
                </div>
                
            </fieldset>
        </div>
    </div>

</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script>
        jQuery(document).ready(function() {
            $('.form-body').find('button[type="submit"]').remove();
            $('.form-body').find('.icon-close').parent().remove();
            $('.form-body').find('.fa-copy').parent().remove();
            $('.form-body').find('input').prop('disabled', true);
            $('.form-body').find('textarea').prop('disabled', true);
            $('.form-body').find('select').prop('disabled', true);
            $('.form-body').find('.bootstrap-tagsinput').prop('disabled', true);
            $('.form-body').find('span.tag').children('span[data-role="remove"]').remove();
            $('.form-body').find('button').prop('disabled', true);
            $('.form-body').find('button').remove();
            $('.form-body').find('.btn-remove-file').parent().remove();
            $('.form-body').find('.show_tag_a').hide();
            $('.form-body').find('.input_show_file').hide();
            
            $(".acting").each(function() {
                new Switchery($(this)[0], $(this).data());
            });
        });
    </script>
@endpush
