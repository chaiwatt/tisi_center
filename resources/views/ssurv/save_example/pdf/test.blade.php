<style>
    @page {
        margin: 1% 2%;
        padding: 0;
    }

    body {
        font-family: 'THSarabunNew', sans-serif;
    }

    .content {
        /*border: 5px solid black;*/
        padding: 5%;
        margin: 0px;
        height: 100%;
        top: 10%;
        position: relative;

    }

    .tc {
        text-align: center;
    }

    div {
        width: 100%;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    }

    .space {
        height: 20px;
    }

    .space-mini {
        height: 10px;
    }

    b {
        font-weight: bold;
    }

    h1 {
        margin-bottom: 10px;
    }

    .tab {
        display: inline-block;
        margin-left: 40px;
    }

    .tr {
        text-align: right;
    }

    .w-50 {
        width: 50%;
    }

    .w-100 {
        width: 100%;
    }

    table {
        line-height: 1.3em;
        font-size: 16px;
    }

    td {
        font-size: 15px;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-justify {
        text-align: justify;
    }

    tr.sample_detail td,
    tr.lab_detail td {
        font-size: 13px !important;
    }

    .page-break {
        page-break-after: always;
    }

    .center {
        margin: auto; //ระยะขอบ ผลักอัตโนมัติ
        width: 50%; //ความกว้าง 50%
        border: 3px solid blue; //ความหนา รูปแบบ สีของขอบ
        padding: 3%; //ขยายขอบด้านใน
    }

    #show_room {
        padding: 2px 5px !important;
        display: inline;
        /* or block */
        border: 1px solid black;
        float: left;
    }

    p.main {
        text-align: justify;
    }

    .space {
        height: 20px;
    }

    .space-mini {
        height: 10px;
    }
</style>

<!-- HTML-->
@php
    $checked_checkbox   = '<img src="'. public_path('storage/uploads/ssurv/icons8-checked-checkbox-32.png') .'" width="25px" style="padding-bottom:-6px" />';
    $unchecked_checkbox = '<img src="'. public_path('storage/uploads/ssurv/icons8-unchecked-checkbox-32.png') .'" width="25px" style="padding-bottom:-6px" />';

    if( !empty( $data_user_register )  ){
        $data_depart[0]   = !empty( $data_user_register->subdepart->department )?$data_user_register->subdepart->department->depart_name:null;
        $data_depart[1]   = !empty( $data_user_register->subdepart )?$data_user_register->subdepart->sub_departname:null;

       
    }

@endphp

<body>
    <div class="content">
        <!-- Page 1-->
        <div class="row-fluid" style="width: 768px; padding: 5px 15px; margin-top: 45px">

            <div class="space-mini"></div>
            <div class="space-mini"></div>

            <p style="text-indent: 50%" class="main">  วันที่<span> {{HP::DateThai($sample_submission_date)}}</span> </p>

            <div class="space-mini"></div>

            <div class="text-left">
                <p>เรื่อง ขอส่งตัวอย่างตรวจสอบ </p>
                <p>เรียน {{ $data_lap[0]->name_lap??'-' }} </p>
            </div>

            <div class="space-mini"></div>
            <p style="text-indent: 100px;" class="main">
                ข้าพเจ้าขอนำส่งตัวอย่าง ซึ่งพนักงานเจ้าหน้าที่สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม
                ได้สุ่มเก็บและลงลายมือชื่อไว้บนตัวอย่างผลิตภัณฑ์ดังกล่าวข้างต้น และแจ้งให้ข้าพเจ้าส่งมายังหน่วยตรวจสอบ
                ของท่าน พร้อมทั้งชำระค่าใช้จ่ายในการตรวจสอบให้แก่หน่วยตรวจสอบ โดยขอให้สรุปผลการตรวจสอบไปที่:
                กองตรวจการมาตรฐาน{!! str_repeat("&nbsp;",3).(!empty($data_depart[0])? preg_replace("/[^a-z\d]/i", '', $data_depart[0]):null).str_repeat("&nbsp;",3) !!}กลุ่มที่{!! str_repeat("&nbsp;",3).(!empty($data_depart[1])?preg_replace("/[^a-z\d]/i", '', $data_depart[1]):null).str_repeat("&nbsp;",3) !!} <b>สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เลขที่ 75/42
                    ถนนพระรามที่ 6 แขวงทุ่งพญาไท เขตราชเทวี กรุงเทพมหานคร 10400</b> โดยมีรายละเอียดของตัวอย่าง
                ลายมือชื่อบนตัวอย่าง และรายการตรวจสอบ ดังใบรับ-นำส่งตัวอย่างที่แนบ หากมีกรณีที่ต้องดำเนินการ
                ในส่วนที่เกี่ยวข้องกับตัวอย่างและผลการทดสอบ ขอให้ติดต่อประสานงานกับสำนักงานมาตรฐานผลิตภัณฑ์
                อุตสาหกรรมต่อไปด้วย
            </p>

            <div class="space-mini"></div>
            <p style="text-indent: 100px;" class="main">
                ข้าพเจ้ามีความประสงค์ {!! ($sample_return == 'รับคืน') ? $checked_checkbox:$unchecked_checkbox !!}
                <label class="col-md-2"> รับคืน </label> {!! ($sample_return == 'ไม่รับคืน') ? $checked_checkbox:$unchecked_checkbox !!}
                <label class="col-md-2"> ไม่รับคืน </label> ตัวอย่างดังกล่าว ภายใน 60 วัน ทั้งนี้
                หากข้าพเจ้าไม่มารับคืนในระยะเวลาดังกล่าว ข้าพเจ้ายินยอมให้สำนักงานดำเนินการกับตัวอย่างดังกล่าว
                ตามความเหมาะสม
            </p>

            <div class="space-mini"></div>
            <p style="text-indent: 51%;" class="main">
                {{ str_repeat(".",70) }}
            </p>
            <p style="text-indent: 50%;" class="main">
                ( {{ str_repeat(".",70) }} )
            </p>
            <p style="text-indent: 45%" class="main">
                ตำแหน่ง
                {{ str_repeat(".",40) }}
                โทร.
                {{ str_repeat(".",20) }}
            </p>

            <br clear="all">
            <div class="split-page"></div>

        </div>

        <div class="page-break"></div>

        <!-- Page 2-->
        <table width="100%" style="margin-top:0px;">
            <tr>
                <td width="30%" rowspan="3" class="text-right">
                    <img src="{{ public_path('storage/uploads/ssurv/tisi.png') }}" width="64px" />
                </td>
                <td width="40%">&nbsp;</td>
                <td width="30%">&nbsp;</td>
            </tr>
            <tr>
                <td width="40%" class="text-center">
                    <span style="font-size: 18pt;"><strong>ใบรับ-นำส่งตัวอย่าง</strong></span>
                </td>
                <td width="30%">
                    <div class="pull-right">
                        <span style="line-height: 20px; border:1px black solid; padding: 4px; background-color: #CCCCCC; font-size: 14pt;" class="pull-left">
                            <strong><?php echo ''; ?></strong>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center" width="40%">
                    <span><strong>สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</strong></span>
                </td>
                <td class="text-center" width="30%"><strong>เลขที่
                        <span>{{$no}}</span></strong>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="30%">&nbsp;</td>
                <td width="40%"></td>
                <td class="" width="30%"><strong>วันที่</strong>
                    <span class="free-dot">{{HP::DateThai($sample_submission_date)}}</span>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="20%">มาตรฐาน :</td>
                <td width="80%">{{ $tis_standard }}</td>
            </tr>
            <tr>
                <td>ผู้รับใบอนุญาต :</td>
                <td>{{ $licensee }}</td>
            </tr>
            <tr>
                <td>ใบอนุญาต :</td>
                <td>{{ $licensee_no }}</td>
            </tr>
        </table>


        <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <caption>รายละเอียดตัวอย่างผลิตภัณฑ์อุตสาหกรรม</caption>
            <thead>
                <tr style="background-color: #CCCCCC;">
                    <th style="width: 7%"><small>รายการ<br>ที่</small></th>
                    <th style="width: 65%">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                    <th style="width: 10%">จำนวน</th>
                    <th style="width: 18%">หมายเลขตัวอย่าง</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_detail as $key => $list_detail)
                <tr class="sample_detail">
                    <td class="text-center" style="padding: 0px 5px;">
                        <span class="running-no">{{$key+1}}</span>
                    </td>
                    <td style="padding: 5px;word-wrap: break-word;">
                        <p align="left">{{HP::map_lap_sizedetail($list_detail->detail_volume)}}</p>
                    </td>
                    <td class="text-right" style="padding: 0px 5px;">{{$list_detail->number}}</td>
                    <td class="text-center" style="padding: 0px 5px;">{{$list_detail->num_ex}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- @php
        $checked_checkbox = '<img src="'. public_path('storage/uploads/ssurv/icons8-checked-checkbox-32.png') .'" width="25px" style="padding-bottom:-6px" />';
        $unchecked_checkbox = '<img src="'. public_path('storage/uploads/ssurv/icons8-unchecked-checkbox-32.png') .'" width="25px" style="padding-bottom:-6px" />';
        @endphp -->

        <div class="form-group m-b-5">
            <div class="col-sm-6">
                <h5><b>รูปแบบการตรวจ</b>&emsp;
                    <span>
                        <!--  <input type="radio" name="type_save" value="all" disabled <?php //echo ($type_send == 'all') ? 'checked' : '' 
                                                                                        ?>> -->
                        {!! ($type_send == 'all') ? $checked_checkbox:$unchecked_checkbox !!}
                        ทุกรายการทดสอบ
                        <!--   <input type="radio" name="type_save" value="some" disabled <?php echo ($type_send == 'some') ? 'checked' : '' ?>> -->
                        {!! ($type_send == 'some') ? $checked_checkbox:$unchecked_checkbox !!}
                        บางรายการทดสอบ
                    </span>
                </h5>
            </div>
        </div>


        <table width="100%" border="1" cellpadding="0" cellspacing="0">
            <caption></caption>
            <thead>
                <tr style="background-color: #CCCCCC;">
                    <th style="width: 7%; line-height: 40px;">ลำดับ<br>ที่</th>
                    <th style="width: 20%">ชื่อหน่วยตรวจสอบ</th>
                    <th style="width: 33%">รายการตรวจ</th>
                    <th style="width: 25%">รายการทดสอบ</th>
                    <th style="width: 15%">เลขที่ใบนำส่ง<br>ตัวอย่าง</th>
                    {{-- <th style="width: 10%">สถานะ</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($data_lap as $key => $data_laps)
                @php
                $data_lap_detail = DB::table( 'save_example_map_lap' )->where('no_example_id', $data_laps->no_example_id)->get();
                @endphp
                <tr class="lab_detail" style="padding: 5px;">
                    <td style="vertical-align: text-top; padding: 5px;" class="text-center">{{$key+1}}</td>
                    <td style="vertical-align: text-top; padding: 5px;">{{$data_laps->name_lap}}</td>
                    <td style="vertical-align: text-top; padding: 5px; word-wrap: break-word;">
                        @foreach($data_lap_detail as $key => $details)
                        <p>
                            {!! HP::map_lap_sizedetail($details->detail_product_maplap) !!}
                        </p>
                        @endforeach
                    </td>
                    <td style="vertical-align: text-top; padding: 5px; word-wrap: break-word;">
                        @foreach($data_lap_detail as $key => $details)
                        {!!HP::map_lap_detail($details->id, $data_laps->example_id)!!}
                        @endforeach
                    </td>
                    <td class="text-center" style="vertical-align: text-top; padding: 5px;">{{$data_laps->no_example_id}}</td>
                    {{-- <td style="vertical-align: text-top; padding: 5px;">{{HP::map_lap_status($data_laps->status)}}</td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>

        <fieldset class="row">
            <div class="form-group">
                <div class="col-sm-7">
                    <label class="col-sm-4"> รายละเอียดเพิ่มเติม : </label>
                    <span class="col-md-8">{{ $more_details }}</span>
                </div>
            </div>
        </fieldset>

        <fieldset class="row">

            <div class="form-group">
                <div class="col-sm-7">
                    <label class="col-sm-4"> การตรวจสอบ : </label>
                    {{-- <input type="radio" name="test1" value="ตรวจสอบที่หน่วยตรวจสอบ"
                                               class="col-md-1" {{ ($verification == 'ตรวจสอบที่หน่วยตรวจสอบ') ? $checked_factory:'' }} > --}}
                    <span class="col-md-1">
                        {!! ($verification == 'ตรวจสอบที่หน่วยตรวจสอบ') ? $checked_checkbox:$unchecked_checkbox !!}
                    </span>
                    <label class="col-md-2"> ตรวจสอบที่หน่วยตรวจสอบ </label>
                    {{-- <input type="radio" name="test1" value="ตรวจสอบที่โรงงาน" checked
                                               class="col-md-1" {{ ($verification == 'ตรวจสอบที่โรงงาน') ? 'checked' : '' }} > --}}
                    <span class="col-md-1" style="padding-bottom:-15px">
                        {!! ($verification == 'ตรวจสอบที่โรงงาน') ? $checked_checkbox:$unchecked_checkbox !!}
                    </span>
                    <label class="col-md-2"> ตรวจสอบที่โรงงาน </label>
                </div>
            </div>
            <!-- <div class="white-box" style="display: flex; flex-direction: column;"> -->

            @if($verification == 'ตรวจสอบที่หน่วยตรวจสอบ')
            <div id="sample_delivery" class="form-group">
                <div class="form-group">
                    <div class="col-sm-8 m-b-10">
                        <label class="col-sm-3 text-right"> การนำส่งตัวอย่าง : </label>
                        <div class="col-md-7" style="margin-left: 25px;">
                            <!-- <input type="radio" class="col-sm-1" name="sample_submission"
                                                       <?php //echo ($sample_submission == 'ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง') ? 'checked' : '' 
                                                        ?>
                                                       value="ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง" disabled> -->
                            {!! ($sample_submission == 'ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง') ? $checked_checkbox:$unchecked_checkbox !!}

                            <label> ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8 m-b-10">
                        <label class="col-sm-3 text-right"></label>
                        <div class="col-md-7" style="margin-left: 25px;">
                            <!-- <input type="radio" class="col-sm-1" name="sample_submission"
                                                       <?php //echo ($sample_submission == 'กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง') ? 'checked' : '' 
                                                        ?>
                                                       value="กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง" disabled> -->
                            {!! ($sample_submission == 'กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง') ? $checked_checkbox:$unchecked_checkbox !!}
                            <label> กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-7" style="margin-left: 25px;">
                        <!-- <p class="col-sm-2"></p> -->
                        <label class="col-sm-2 text-right"> โดยเก็บตัวอย่างไว้ที่ </label>
                        <!-- <input type="radio" class="col-sm-1" name="stored_add" disabled
                                                   value="โรงงาน" <?php //echo ($stored_add == 'โรงงาน') ? 'checked' : '' 
                                                                    ?>> -->
                        {!! ($stored_add == 'โรงงาน') ? $checked_checkbox:$unchecked_checkbox !!}
                        <label class="col-sm-1"> โรงงาน </label>
                        <!-- <input type="radio" class="col-sm-1" name="stored_add" disabled
                                                   value="สมอ. ห้อง" <?php //echo ($stored_add == 'สมอ. ห้อง') ? 'checked' : '' 
                                                                        ?>> -->
                        {!! ($stored_add == 'สมอ. ห้อง') ? $checked_checkbox:$unchecked_checkbox !!}
                        <label class="col-sm-2"> สมอ. ห้อง </label>
                        <span class="col-sm-6" style="border-bottom: 1.5px dotted black">
                            {{-- <input type="text" class="pull-right" name="room_anchor" disabled --}}
                            {{-- value="{!! $room_anchor !!}" size="50"> --}}
                            {{-- <div class="center" style="border: 1px solid black; padding: 2px 5px; float: left; display: inline-block; width: 150px">{{ $room_anchor }}
                    </div> --}}
                    {{ $room_anchor }}
                    </span>
                </div>
            </div>
    </div>
    @endif
    <div class="form-group">
        <p class="center" style="margin-top:5px; padding: 5px 10px; border: 2px solid black; width: 575px">
            ตามเงื่อนไขที่ผู้รับใบอนุญาตต้องปฏิบัติ ตามมาตรา 25 ทวิ สำนักงานขอแจ้งให้ท่านนำส่งตัวอย่าง<br>
            พร้อมชำระค่าใช้จ่ายในการตรวจสอบที่หน่วยตรวจสอบตามที่ระบุไว้ ในใบรับ-นำส่งตัวอย่างนี้<br>
            ภายใน 15 วัน นับจากวันที่เก็บตัวอย่าง
        </p>
    </div>
    <div class="space"></div>

    <table width="100%" border="0">
        {{-- <tr>
                                        <td width="20%">วันที่เก็บตัวอย่าง :</td>
                                        <td colspan="3">{{$sample_submission_date}}</td>
        </tr> --}}
        <tr>
            <td width="15%">ผู้จ่ายตัวอย่าง :</td>
            <td width="35%">{{ str_repeat(".",62) }}</td>
            <td width="15%">ผู้รับตัวอย่าง :</td>
            <td width="35%">{{ str_repeat(".",62) }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="text-center">{{$sample_pay}}</td>
            <td>&nbsp;</td>
            <td class="text-center">{{$sample_recipient}}</td>
        </tr>

        <tr>
            <td>ตำแหน่ง :</td>
            <td>{{$permission_submiss}}</td>
            <td>ตำแหน่ง :</td>
            <td>{{$permission_receive}}</td>
        </tr>

        <tr>
            <td>เบอร์โทรศัพท์ :</td>
            <td>{{$tel_submiss}}</td>
            <td>เบอร์โทรศัพท์ :</td>
            <td>{{$tel_receive}}</td>
        </tr>

        <tr>
            <td>Email :</td>
            <td>{{$email_submiss}}</td>
            <td>Email :</td>
            <td>{{$email_receive}}</td>
        </tr>
        {{--
                                    <tr>
                                        <td>เบอร์โทรศัพท์ :</td>
                                        <td>{{$tel_submiss}}</td>
        <td>Email :</td>
        <td>{{$email_submiss}}</td>
        </tr>

        <tr>
            <td width="20%">ผู้รับตัวอย่าง :</td>
            <td width="30%">{{$sample_recipient}}</td>
            <td width="20%">ตำแหน่ง :</td>
            <td width="30%">{{$permission_receive}}</td>
        </tr>
        <tr>
            <td>เบอร์โทรศัพท์ :</td>
            <td>{{$tel_receive}}</td>
            <td>Email :</td>
            <td>{{$email_receive}}</td>
        </tr> --}}

    </table>

    <div class="space"></div>

    <div class="form-group">
        <div class="col-sm-7">
            <label class="col-sm-4"> การรับคืนตัวอย่าง : </label>
            <!--  <input type="radio" name="sample_return" value="ไม่รับคืน" disabled
                                               class="col-md-1" <?php //echo ($sample_return == 'ไม่รับคืน') ? 'checked' : '' 
                                                                ?> -->
            {!! ($sample_return == 'ไม่รับคืน') ? $checked_checkbox:$unchecked_checkbox !!}
            <label class="col-md-2"> ไม่รับคืน </label>
            <!-- <input type="radio" name="sample_return" value="รับคืน" disabled
                                               class="col-md-1" <?php //echo ($sample_return == 'รับคืน') ? 'checked' : '' 
                                                                ?> -->
            {!! ($sample_return == 'รับคืน') ? $checked_checkbox:$unchecked_checkbox !!}
            <label class="col-md-2"> รับคืน </label>

        </div>
    </div>

    <!-- </div> -->
    </fieldset>


    <div class="space"></div>

    <div class="footer">
        <table class="w-100">
            <tr>
                <td class="w-50"></td>
                <td class="w-50 tc">
                    <p>..........................................</p>
                    <p>(........................................)</p>
                    <p>วันที่ .........................................</p>
                    <p></p>
                </td>
            </tr>
        </table>
    </div>
    {{-- <div class="page-break"></div> --}}
    </div>

</body>