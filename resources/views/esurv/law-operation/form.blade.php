@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
        fieldset {
            padding: 20px;
        }
        .dottedUnderline {
            text-decoration: underline dotted;
        }
        .input-show {
            height: 27px;
            padding: 3px 7px;
            font-size: 15px;
            line-height: 1.5;
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
        }
        .input-show[disabled]{
            background-color: #FFFFFF;
        }
    </style>
@endpush

<div class="white-box">
    <div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <ul class="icheck-list">
                    <label for="chkYes">
                        <input type="radio" class="check" id="1" name="typetable"  value="1"  /> การตรวจควบคุมฯ
                    </label>
                    <label for="chkNo">
                        <input type="radio" class="check"  id="2" name="typetable"  value="2"  /> การตรวจประเมินระบบควบคุมคุณภาพ
                    </label>
                </ul>
            </div>
        </div>
    </div> 
    <div id="dvPinNo" style="display: none">
        <div class="col-md-5 col-lg-5">
            <div class="form-group {{ $errors->has('control_check_id') ? 'has-error' : ''}}">
                {!! Form::label('control_check_id', 'เลขที่หนังสือ:', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::select('control_check_id',
                    $control_check_list,
                    !empty($control_check->id)?$control_check->id:null,
                    ['class' => 'form-control',
                    'placeholder'=> '-เลือก-'
                    ]
                    )
                    !!}
                {!! $errors->first('control_check_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12">
                <div style="border: solid 0.1em" class="p-40">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center">บันทึกการตรวจควบคุมฯ</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 m-b-40">
                                <label class="pull-right ">เลขที่เอกสาร</label>
                            </div>
                            <div class="dottedUnderline">
                                <input type="text" name="auto_id_doc" id="auto_id_doc" class="input-show">    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                        <div class="col-md-6">
                                            <input type="text" name="trade_name" id="trade_name" class="form-control input-show">
                                        </div>
                                    </div>
                                </div>
                                <div class="row  m-b-10">
                                    <div class="form-group">
                                        <label class="col-md-1"></label>
                                        <label class="col-md-1 text-right">มาตราฐาน</label>
                                        <div class="col-md-6">
                                            <input type="text" name="tbl_tisiNo" id="tbl_tisiNo" class="form-control input-show">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="col-md-3">
                                                <label>มอก.</label>
                                            </div>
                                            <div class="dottedUnderline">
                                                <div id="mog"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  m-b-10">
                                    <div class="form-group">
                                        <label class="col-md-1"></label>
                                        <label class="col-md-1 text-right">ใบอนุญาต</label>
                                        <div class="col-sm-8">
                                            <input type="checkbox" name="check_all" id="check_all">
                                            <label>เลือกทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div>
                                        <label class="col-md-2 "></label>
                                        <div class="col-sm-10 p-0">
                                            <div class="row col-sm-12 p-0" id="license"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  m-b-10">
                                    <div class="form-group">
                                        <label class="col-md-1"></label>
                                        <label class="col-md-1 text-right">สถานที่ตรวจ</label>
                                        <div class="col-sm-2">
                                            <input type="checkbox"
                                                   id="located_checking"
                                                   name="located_check"
                                                   value="สถานที่ผลิต"
                                                   onclick="location_gen();">
                                            <label>สถานที่ผลิต</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="checkbox"
                                                   id="located_keeping"
                                                   name="located_keep"
                                                   value="สถานที่เก็บ"
                                                   onclick="location_keep1();">
                                            <label>สถานที่เก็บ</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="checkbox"
                                                   id="located_selling"
                                                   name="located_sell"
                                                   value="สถานที่จำหน่าย"
                                                   onclick="located_sell1();">
                                            <label>สถานที่จำหน่าย</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <label class="col-sm-2"></label>
                                            <label class="col-sm-1 text-right">ตั้งอยู่เลขที่</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_no" id="address_no" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-1 text-right">หมู่ที่</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_village_no" id="address_village_no" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-2 text-right">นิคมอุตสาหกรรม
                                                (ถ้ามี)</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_industrial_estate" id="address_industrial_estate" class="form-control input-show">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <label class="col-sm-2"></label>
                                            <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_alley" id="address_alley" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-1 text-right">ถนน</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_road" id="address_road" class="form-control input-show">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <label class="col-sm-2"></label>
                                            <label class="col-sm-1 text-right">จังหวัด</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_province" id="address_province" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-1 text-right">อำเภอ/เขต </label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_amphoe" id="address_amphoe" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-2 text-right">ตำบล/แขวง </label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_district" id="address_district" class="form-control input-show">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-2 text-right">รหัสไปรษณีย์</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="address_zip_code" id="address_zip_code" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-1 text-right">โทรศัพท์ </label>
                                            <div class="col-sm-2">
                                                <input type="text" name="tel" id="tel" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-2 text-right">โทรสาร </label>
                                            <div class="col-sm-2">
                                                <input type="text" name="fax" id="fax" class="form-control input-show">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-2 text-right">พิกัดที่ตั้ง
                                                (ละติจูด)</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="latitude" id="latitude" class="form-control input-show">
                                            </div>

                                            <label class="col-sm-2 text-right">พิกัดที่ตั้ง
                                                (ลองจิจูด)</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="longitude" id="longitude" class="form-control input-show">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <label class="col-md-2 text-right">ชื่อพนักงาน/ เจ้าหน้าที่</label>
                                        <div class="col-md-6">
                                            <input type="text" name="officer_name" id="officer_name" class="form-control input-show">
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-b-10">
                                    <div class="form-group">
                                        <label class="col-sm-1"></label>
                                        <label class="col-sm-1 text-right">วันที่ตรวจ</label>
                                        <div class="col-sm-3 input-group p-l-15">
                                            <input type="text"
                                                   class="form-control pull-right"
                                                   name="checking_date"
                                                   id="checking_date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                            <input type="text"
                                                   class="form-control timepicker"
                                                   name="checking_time"
                                                   id="checking_time">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div> 
    <div id="dvPinShow" style="display: none">
        <div class="col-md-5 col-lg-5">
            <div class="form-group {{ $errors->has('control_performance_id') ? 'has-error' : ''}}">
                {!! Form::label('control_performance_id', 'เลขที่หนังสือ:', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::select('control_performance_id',
                        $control_performance_list,
                        !empty($control_performance->id)?$control_performance->id:null,
                        ['class' => 'form-control',
                        'placeholder'=> '-เลือก-'
                        ]
                        )
                    !!}
                    {!! $errors->first('control_performance_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12">
            <div style="border: solid 0.1em" class="p-40">
                <div class="row form-group">
                        <div class="col-md-12">
                            {{-- <div style="border: solid 0.1em" class="p-40"> --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center">บันทึกการตรวจประเมินระบบควบคุมคุณภาพ</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 m-b-40">
                                        <label class="pull-right ">เลขที่เอกสาร</label>
                                    </div>
                                    <div class="dottedUnderline">
                                        <input type="text" name="auto_id_doc_pre" id="auto_id_doc_pre" class="input-show">
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-12">

                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="trade_name_pre" id="trade_name_pre" class="form-control input-show">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  m-b-10">
                                            <div class="form-group">
                                                <label class="col-md-1"></label>
                                                <label class="col-md-1 text-right">มาตราฐาน</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="tbl_tisiNo_pre" id="tbl_tisiNo_pre" class="form-control input-show">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="col-md-3">
                                                        <label>มอก.</label>
                                                    </div>
                                                    <div class="dottedUnderline">
                                                        <div id="mog"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row  m-b-10">
                                            <div class="form-group">
                                                <label class="col-md-1"></label>
                                                <label class="col-md-1 text-right">ใบอนุญาต</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" name="check_all" id="check_all">
                                                    <label>เลือกทั้งหมด</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div>
                                                <label class="col-md-2 "></label>
                                                <div class="col-sm-10 p-0">
                                                    <div class="row col-sm-12 p-0" id="license"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-md-2 text-right">ชื่อโรงงาน</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="factory_name_pre" id="factory_name_pre" class="form-control input-show">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <label class="col-sm-2"></label>
                                                    <label class="col-sm-1 text-right">ตั้งอยู่เลขที่</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_no_pre" id="address_no_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-2 text-right">นิคมอุตสาหกรรม
                                                        (ถ้ามี)</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_industrial_estate_pre" id="address_industrial_estate_pre" class="form-control input-show">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <label class="col-sm-2"></label>
                                                    <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_alley_pre" id="address_alley_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-2 text-right">ถนน</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_road_pre" id="address_road_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-1 text-right">หมู่ที่</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_village_no_pre" id="address_village_no_pre" class="form-control input-show">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <label class="col-sm-2"></label>
                                                    <label class="col-sm-1 text-right">จังหวัด</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_province_pre" id="address_province_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-2 text-right">อำเภอ/เขต </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_amphoe_pre" id="address_amphoe_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-1 text-right">ตำบล/แขวง </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_district_pre" id="address_district_pre" class="form-control input-show">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <label class="col-sm-1"></label>
                                                    <label class="col-sm-2 text-right">รหัสไปรษณีย์</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_zip_code_pre" id="address_zip_code_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-2 text-right">โทรศัพท์ </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="tel_pre" id="tel_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-1 text-right">โทรสาร </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="fax_pre" id="fax_pre" class="form-control input-show">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <div class="col-md-10">
                                                    <label class="col-sm-1"></label>
                                                    <label class="col-sm-2 text-right">พิกัดที่ตั้ง
                                                        (ละติจูด)</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="latitude_pre" id="latitude_pre" class="form-control input-show">
                                                    </div>

                                                    <label class="col-sm-2 text-right">พิกัดที่ตั้ง
                                                        (ลองจิจูด)</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="longitude_pre" id="longitude_pre" class="form-control input-show">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-sm-1"></label>
                                                <label class="col-sm-1 text-right">วันที่ตรวจ</label>
                                                <div class="col-sm-2 input-group p-l-15">
                                                    <input type="text"
                                                           class="form-control pull-right"
                                                           name="checking_date_pre"
                                                           id="checking_date_pre">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </div>
            </div>
        </div>

    </div>  
    <div class="clearfix"></div>
</div>


<div class="row form-group">
    <div class="col-md-12" id="">
        <fieldset style="border: solid 0.1em" class="p-40">
            <legend><h3> การดำเนินการผู้ประกอบการ</h3></legend>
            <div class="row">
                <div class="form-group {{ $errors->has('trader_status') ? 'has-error' : ''}}">
                    {!! Form::label('trader_status', 'สถานะการดำเนินการ', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-3">
                        {!! Form::select('trader_status',
                            App\Models\Besurv\SettingLawOperation::pluck('title', 'id')->all(),
                            null,
                            ['class' => 'form-control',
                            'placeholder'=> '- ดำเนินการเปรียบเทียบปรับ -'
                            ]
                            )
                        !!}
                        {!! $errors->first('trader_status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>      
            </div>
            <div class="form-group">
                {!! Form::label('attach2', 'ไฟล์แนบ (ถ้ามี)'.':', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-6">
                    <button type="button" class="btn btn-sm btn-success" id="attach-add2">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                    </button>
                </div>
            </div>
            <div id="other_attach-box2">
                @foreach ($attachs as $key => $attach)
                    <div class="form-group other_attach_item2">
                        <div class="col-md-4">
                            {!! Form::hidden('attach_filenames2[]', $attach->file_name); !!}
                        </div>
                        <div class="col-md-5">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                {!! Form::file('attachs2[]', null) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-2">
                            @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm attach-remove2" type="button">
                                <i class="icon-close"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </fieldset>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-12" id="">
        <fieldset style="border: solid 0.1em" class="p-40">
            <legend><h3> การดำเนินการกับใบอนุญาต</h3></legend>
            <div class="row">
                <div class="form-group {{ $errors->has('license_status') ? 'has-error' : ''}}">
                    {!! Form::label('license_status', 'สถานะการดำเนินการ', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-3">
                        {!! Form::select('license_status',
                            App\Models\Besurv\SettingLawOperation::pluck('title', 'id')->all(),
                            null,
                            ['class' => 'form-control',
                            'placeholder'=> '- ดำเนินการทำหนังสือแจ้งเตือน -'
                            ]
                            )
                        !!}
                        {!! $errors->first('license_status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attach3', 'ไฟล์แนบ (ถ้ามี)'.':', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-success" id="attach-add3">
                            <i class="icon-plus"></i>&nbsp;เพิ่ม
                        </button>
                    </div>
                </div>
                <div id="other_attach-box3">
                    @foreach ($attachs as $key => $attach)
                    <div class="form-group other_attach_item3">
                        <div class="col-md-4">
                            {!! Form::hidden('attach_filenames3[]', $attach->file_name); !!}
                        </div>
                        <div class="col-md-5">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                {!! Form::file('attachs3[]', null) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-2">
                            @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm attach-remove3" type="button">
                                <i class="icon-close"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>     
            </div>
        </fieldset>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-12" id="">
        <fieldset style="border: solid 0.1em" class="p-40">
            <legend><h3> สถานะการดำเนินการกับผลิตภัณฑ์</h3></legend>
            <div class="row">
                <div class="form-group {{ $errors->has('product_status') ? 'has-error' : ''}}">
                    {!! Form::label('product_status', 'สถานะการดำเนินการ ', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-3">
                        {!! Form::select('product_status',
                            App\Models\Besurv\SettingLawOperation::pluck('title', 'id')->all(),
                            null,
                            ['class' => 'form-control',
                            'placeholder'=> '- จัดทำคำสั่งให้ทำลายของกลาง -'
                            ]
                            )
                        !!}
                        {!! $errors->first('product_status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('attach', 'ไฟล์แนบ (ถ้ามี)'.':', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-success" id="attach-add">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                        </button>
                    </div>
                </div>
                <div id="other_attach-box">
                    @foreach ($attachs as $key => $attach)
                    <div class="form-group other_attach_item">
                        <div class="col-md-4">
                            {!! Form::hidden('attach_filenames[]', $attach->file_name); !!}
                        </div>
                        <div class="col-md-5">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                <span class="fileinput-new">เลือกไฟล์</span>
                                <span class="fileinput-exists">เปลี่ยน</span>
                                {!! Form::file('attachs[]', null) !!}
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-2">
                            @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm attach-remove" type="button">
                                <i class="icon-close"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>     
            </div>
        </fieldset>
    </div>
</div>

<div class="white-box">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
            {!! Form::label('status', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-3">
                {!! Form::select('status',
               ['1'=>'รอดำเนินการ','2'=>'อยู่ระหว่างพิจารณา','3'=>'อยู่ระหว่างดำเนินการ','4'=>'เสร็จสิ้น'],
                null,
                ['class' => 'form-control',
                'placeholder'=> '- เลือกสถานะ -'
                ]
                )
                !!}
                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
            {!! Form::label('details', 'หมายเหตุ', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-4">
                {!! Form::textarea('details', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control','rows'=>3]) !!}
                {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('attach4', 'ไฟล์แนบ (ถ้ามี)'.':', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-success" id="attach-add4">
                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                </button>
            </div>
        </div>
        <div id="other_attach-box4">
            @foreach ($attachs as $key => $attach)
            <div class="form-group other_attach_item4">
                <div class="col-md-4">
                    {!! Form::hidden('attach_filenames4[]', $attach->file_name); !!}
                </div>
                <div class="col-md-5">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        {!! Form::file('attachs4[]', null) !!}
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="col-md-2">
                    @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                        <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                    @endif
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger btn-sm attach-remove4" type="button">
                        <i class="icon-close"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('LawOperation'))
            <a class="btn btn-default" href="{{url('esurv/law-operation')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <!-- input file -->
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script>
    jQuery(document).ready(function() {


     //เลือกการตรวจควบคุมฯ
    $('#control_check_id').change(function () {

    if ($(this).val() != "") {
        $('#auto_id_doc').val('');
        $('#trade_name').val('');
        $('#tbl_tisiNo').val('');
        $('#checking_date').val('');
        $('#checking_time').val('');
        $('#officer_name').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        $('#officer_name').val('');
        $('#address_no').val('');
        $('#address_industrial_estate').val('');
        $('#address_village_no').val('');
        $('#address_alley').val('');
        $('#address_road').val('');
        $('#address_district').val('');
        $('#address_amphoe').val('');
        $('#address_province').val('');
        $('#address_zip_code').val('');
        $('#tel').val('');
        $('#fax').val('');


        $.ajax({
        url: "{!! url('law-operation/control-check') !!}" + "/" + $(this).val()
        }).done(function (obj) {
            $('#auto_id_doc').val(obj.id_doc);
            $('#trade_name').val(obj.trade_name);
            $('#tbl_tisiNo').val(obj.tbl_tisiNo);
            $('#checking_date').val(obj.checking_date);
            $('#checking_time').val(obj.checking_time);
            $('#officer_name').val(obj.officer_name);
            $('#latitude').val(obj.latitude);
            $('#longitude').val(obj.Longitude);
            $('#address_no').val(obj.address_no);
            $('#address_industrial_estate').val(obj.address_industrial_estate);
            $('#address_village_no').val(obj.address_village_no);
            $('#address_alley').val(obj.address_alley);
            $('#address_road').val(obj.address_road);
            $('#address_district').val(obj.address_district); 
            $('#address_amphoe').val(obj.address_amphoe);
            $('#address_province').val(obj.address_province);
            $('#address_zip_code').val(obj.address_zip_code);
            $('#tel').val(obj.tel);
            $('#fax').val(obj.fax);

        });
    }
    });

    //เลือกการตรวจควบคุมคุณภาพ
    $('#control_performance_id').change(function () {

    if ($(this).val() != "") {
        $('#auto_id_doc_pre').val('');
        $('#trade_name_pre').val('');
        $('#tbl_tisiNo_pre').val('');
        $('#factory_name_pre').val('');
        $('#address_no_pre').val('');
        $('#address_industrial_estate_pre').val('');
        $('#address_village_no_pre').val('');
        $('#address_alley_pre').val('');
        $('#address_road_pre').val('');
        $('#address_district_pre').val('');
        $('#address_amphoe_pre').val('');
        $('#address_province_pre').val('');
        $('#address_zip_code_pre').val('');
        $('#tel_pre').val('');
        $('#fax_pre').val('');
        $('#latitude_pre').val('');
        $('#longitude_pre').val('');
        $('#checking_date_pre').val('');

        $.ajax({
        url: "{!! url('law-operation/control-performance') !!}" + "/" + $(this).val()
        }).done(function (obj) {
            $('#auto_id_doc_pre').val(obj.id_doc);
            $('#trade_name_pre').val(obj.trade_name);
            $('#tbl_tisiNo_pre').val(obj.tbl_tisiNo);
            $('#factory_name_pre').val(obj.factory_name);
            $('#address_no_pre').val(obj.address_no);
            $('#address_industrial_estate_pre').val(obj.address_industrial_estate);
            $('#address_village_no_pre').val(obj.address_village_no);
            $('#address_alley_pre').val(obj.address_alley);
            $('#address_road_pre').val(obj.address_road);
            $('#address_district_pre').val(obj.address_district); 
            $('#address_amphoe_pre').val(obj.address_amphoe);
            $('#address_province_pre').val(obj.address_province);
            $('#address_zip_code_pre').val(obj.address_zip_code);
            $('#tel_pre').val(obj.tel);
            $('#fax_pre').val(obj.fax);
            $('#latitude_pre').val(obj.latitude);
            $('#longitude_pre').val(obj.Longitude);
            $('#checking_date_pre').val(obj.checking_date);

        });
    }
    });

    
    //เพิ่มไฟล์แนบ
    $('#attach-add').click(function(event) {
      $('.other_attach_item:first').clone().appendTo('#other_attach-box');
      $('.other_attach_item:last').find('input').val('');
      $('.other_attach_item:last').find('a.fileinput-exists').click();
      $('.other_attach_item:last').find('a.view-attach').remove();
      ShowHideRemoveBtn();
    });
    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn();
    });

    //เพิ่มไฟล์แนบ
    $('#attach-add2').click(function(event) {
      $('.other_attach_item2:first').clone().appendTo('#other_attach-box2');
      $('.other_attach_item2:last').find('input').val('');
      $('.other_attach_item2:last').find('a.fileinput-exists').click();
      $('.other_attach_item2:last').find('a.view-attach').remove();
      ShowHideRemoveBtn2();
    });
    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove2', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn2();
    });

    //เพิ่มไฟล์แนบ
    $('#attach-add3').click(function(event) {
      $('.other_attach_item3:first').clone().appendTo('#other_attach-box3');
      $('.other_attach_item3:last').find('input').val('');
      $('.other_attach_item3:last').find('a.fileinput-exists').click();
      $('.other_attach_item3:last').find('a.view-attach').remove();
      ShowHideRemoveBtn3();
    });
    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove3', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn3();
    });

    //เพิ่มไฟล์แนบ
    $('#attach-add4').click(function(event) {
      $('.other_attach_item4:first').clone().appendTo('#other_attach-box4');
      $('.other_attach_item4:last').find('input').val('');
      $('.other_attach_item4:last').find('a.fileinput-exists').click();
      $('.other_attach_item4:last').find('a.view-attach').remove();
      ShowHideRemoveBtn4();
    });
    //ลบไฟล์แนบ
    $('body').on('click', '.attach-remove4', function(event) {
      $(this).parent().parent().remove();
      ShowHideRemoveBtn4();
    });

      $(".check").on('ifChecked', function(event){
        // alert($();
        onbotton();
      });

    onbotton();
    ShowHideRemoveBtn();
    ShowHideRemoveBtn2();
    ShowHideRemoveBtn3();
    ShowHideRemoveBtn4();
        
    });
       
    function onbotton(){

        var checkon = $(".check:checked").val()
        $("#dvPinNo").hide();
        $("#dvPinShow").hide();
        if (checkon=='1'){ 
            $("#dvPinNo").show();
            //Disabled input
            $("#dvPinNo").find('select').prop('disabled', false);
            $("#dvPinNo").find('input, textarea,hidden, fileinput').prop('disabled',true);
            $("#dvPinShow").find('input, textarea, select, hidden, fileinput').prop('disabled',true);
        }else if (checkon=='2'){ 
            $("#dvPinShow").show();
            //Disabled input
            $("#dvPinNo").find('input, textarea, select, fileinput').prop('disabled', true);
            $("#dvPinShow").find('input, textarea, hidden, fileinput').prop('disabled',true);
            $("#dvPinShow").find('select').prop('disabled',false);
        } 

    }
    function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ
        if ($('.other_attach_item').length > 1) {
            $('.attach-remove').show();
        } else {
            $('.attach-remove').hide();
        }
    }
    function ShowHideRemoveBtn2() { //ซ่อน-แสดงปุ่มลบ
        if ($('.other_attach_item2').length > 1) {
            $('.attach-remove2').show();
        } else {
            $('.attach-remove2').hide();
        }
    }
    function ShowHideRemoveBtn3() { //ซ่อน-แสดงปุ่มลบ
        if ($('.other_attach_item3').length > 1) {
            $('.attach-remove3').show();
        } else {
            $('.attach-remove3').hide();
        }
    }
    function ShowHideRemoveBtn4() { //ซ่อน-แสดงปุ่มลบ
        if ($('.other_attach_item4').length > 1) {
            $('.attach-remove4').show();
        } else {
            $('.attach-remove4').hide();
        }
    }


    </script>
  
@endpush

