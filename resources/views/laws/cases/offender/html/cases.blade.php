@php
    $option_section = App\Models\Law\Basic\LawSection::where('section_type','1')->select(DB::Raw('CONCAT(number," : ",title) AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    $option_punish  = App\Models\Law\Basic\LawSection::where('section_type','2')->select(DB::Raw('CONCAT(number," : ",title) AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');

    $subdepart_ids  = ['0600','0601','0602','0603','0604'];
    //นิติกร
    $users_list     = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')->whereIn('reg_subdepart',$subdepart_ids)->pluck('title', 'id');    

    $Tb4_licenses   = App\Models\Basic\TisiLicense::where('tbl_taxpayer', $offender->taxid )->select("Autono",'tbl_licenseNo', 'tbl_licenseStatus', 'license_pdf', 'tbl_tisiNo', 'Is_pause', 'date_pause_start', 'date_pause_end', 'tbl_licenseType')->get();
    $type_name      =  ['ท'=>'ทำ', 'ส'=>'แสดง','น'=>'นำเข้า', 'นค'=>'นำเข้าเฉพาะครั้ง'];

    $license_list   = !empty($cases)?$cases->license_list()->pluck('tb4_tisilicense_id','tb4_tisilicense_id')->toArray():[];

    $readonly       = !empty($cases->law_cases_id)?true:false;
    $mydatepicker   = !empty($cases->law_cases_id)?'form-control':'form-control mydatepicker';
@endphp

{!! Form::hidden('id', !empty($cases->id)?$cases->id:null  , ['class' => 'form-control']) !!}
{!! Form::hidden('law_offender_id', !empty($offender->id)?$offender->id:null  , ['class' => 'form-control']) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Html::decode(Form::label('case_number', 'เลขคดี'.':', ['class' => 'col-md-2 control-label'])) !!}
            <div class="col-md-4">
                {!! Form::text('case_number', (!empty($cases->case_number)?$cases->case_number:null) , ['class' => 'form-control', 'required' => 'required', 'readonly' => $readonly]) !!}
            </div>
            {!! Html::decode(Form::label('date_offender_case', 'วันที่พบการกระทำผิด'.':', ['class' => 'col-md-2 control-label'])) !!}
            <div class="col-md-4">
                <div class="input-group">
                    {!! Form::text('date_offender_case',  (!empty($cases->date_offender_case)?HP::revertDate($cases->date_offender_case, true):null), ['class' => $mydatepicker,'readonly' => $readonly]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="panel panel-info">
    <div class="panel-heading"> ข้อมูลคดี
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-plus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true" style="">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('section', 'ความผิดมาตรา'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::select('section[]',  $option_section, (!empty($cases->section)?$cases->section:null) , ['class' => '','multiple' => 'multiple', 'data-placeholder'=>'- เลือกฝ่าฝืนตามมาตรา -']) !!}
                        </div>
                        {!! Html::decode(Form::label('punish', 'บทลงโทษ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::select('punish[]',  $option_punish,  (!empty($cases->punish)?$cases->punish:null), ['class' => '' ,'multiple' => 'multiple', 'data-placeholder'=>'- เลือกบทลงโทษ -']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('checkbox', 'ดำเนินการ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-2">
                            <div class="checkbox checkbox-warning">
                                <input id="case_person"  name="case_person"  type="checkbox" value="1" @if( !empty($cases->case_person) && $cases->case_person == 1 ) checked @endif>
                                <label for="case_person">&nbsp;อาญา&nbsp;</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox checkbox-warning">
                                <input id="case_license" name="case_license" type="checkbox" value="1" @if( !empty($cases->case_license) && $cases->case_license == 1 ) checked @endif>
                                <label for="case_license">&nbsp;ปกครอง (ใบอนุญาต)&nbsp;</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox checkbox-warning">
                                <input id="case_product" name="case_product" type="checkbox" value="1" @if( !empty($cases->case_product) && $cases->case_product == 1 ) checked @endif>
                                <label for="case_product">&nbsp;ของกลาง (ผลิตภัณฑ์)&nbsp;</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('checkbox', 'ดำเนินคดี'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-3">
                            <div class="radio radio-warning">
                                <input id="prosecute1"  name="prosecute"  type="radio" value="1" @if( !empty($cases->prosecute) && $cases->prosecute == 1 ) checked @endif>
                                <label for="prosecute1">&nbsp;ดำเนินคดี&nbsp;</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="radio radio-warning">
                                <input id="prosecute2"  name="prosecute"  type="radio" value="0" @if( !empty($cases->prosecute) && $cases->prosecute == 0 ) checked @endif>
                                <label for="prosecute2">&nbsp;ไม่ดำเนินคดี&nbsp;</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('lawyer_by', 'นิติกร'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::select('lawyer_by', $users_list , (!empty($cases->lawyer_by)?$cases->lawyer_by:null), ['class' => 'form-control', 'placeholder'=>'- เลือกนิติกร -']); !!}
                        </div>

                        {!! Html::decode(Form::label('date_close', 'วันที่ปิดคดี'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('date_close',  (!empty($cases->date_close)?HP::revertDate($cases->date_close, true):null), ['class' => 'form-control mydatepicker']) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('status', 'สถานะ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::select('status', [1 => 'รอดำเนินการ', 2 => 'อยู่ระหว่างดำเนินการ', 3 => 'ปิดงานคดี'] , (!empty($cases->status)?$cases->status:null), ['class' => 'form-control', 'placeholder'=>'- เลือกสถานะ -']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('assign_date', 'วันที่ได้รับมอบหมาย'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('assign_date',  (!empty($cases->assign_date)?HP::revertDate($cases->assign_date, true):null), ['class' => $mydatepicker, 'readonly' => $readonly]) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                        {!! Html::decode(Form::label('approve_date', 'วันที่อนุมัติ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('approve_date',  (!empty($cases->approve_date)?HP::revertDate($cases->approve_date, true):null), ['class' => 'form-control mydatepicker']) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('power', 'อำนาจ (เสนอ ลมอ./คกก.)'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('power',  (!empty($cases->power)?$cases->power:null), ['class' => 'form-control']) !!}
                        </div>
                        {!! Html::decode(Form::label('power_present_date', 'วันที่เสนอ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('power_present_date',  (!empty($cases->power_present_date)?HP::revertDate($cases->power_present_date, true):null), ['class' => 'form-control mydatepicker']) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tisi_present', 'เสนอลงนามคำสั่ง กมอ.'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('tisi_present',  (!empty($cases->tisi_present)?$cases->tisi_present:null), ['class' => 'form-control']) !!}
                        </div>
                        {!! Html::decode(Form::label('tisi_dictation_no', 'คำสั่งกมอ. ที่'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('tisi_dictation_no',  (!empty($cases->tisi_dictation_no)?$cases->tisi_dictation_no:null), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tisi_dictation_date', 'วันที่คำสั่ง กมอ.ทำให้สิ้นสภาพ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('tisi_dictation_date',  (!empty($cases->tisi_dictation_date)?HP::revertDate($cases->tisi_dictation_date, true):null), ['class' => 'form-control mydatepicker']) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                        {!! Html::decode(Form::label('tisi_dictation_cppd', 'แจ้งคำสั่ง กมอ.(ปคบ.)'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('tisi_dictation_cppd',  (!empty($cases->tisi_dictation_cppd)?$cases->tisi_dictation_cppd:null), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tisi_dictation_company', 'แจ้งคำสั่ง กมอ.(บริษัท)'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('tisi_dictation_company',  (!empty($cases->tisi_dictation_company)?$cases->tisi_dictation_company:null), ['class' => 'form-control']) !!}
                        </div>
                        {!! Html::decode(Form::label('tisi_dictation_committee', 'แจ้งคำสั่ง กมอ. คืนเรื่องเดิม (กต.)'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('tisi_dictation_committee',  (!empty($cases->tisi_dictation_committee)?$cases->tisi_dictation_committee:null), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('cppd_result', 'แจ้งผล การเปรียบเทียบปรับ(ปคบ.)'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('cppd_result',  (!empty($cases->cppd_result)?$cases->cppd_result:null), ['class' => 'form-control']) !!}
                        </div>
                        {!! Html::decode(Form::label('result_summary', 'สรุปเรื่องให้ลมอ. ทราบ'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('result_summary',  (!empty($cases->result_summary)?$cases->result_summary:null), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('destroy_date', 'วันที่ทำลาย/ส่งคืน'.':', ['class' => 'col-md-2 control-label'])) !!}
                        <div class="col-md-4">
                            <div class="input-group">
                                {!! Form::text('destroy_date',  (!empty($cases->destroy_date)?HP::revertDate($cases->destroy_date, true):null), ['class' => 'form-control mydatepicker']) !!}
                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading"> ข้อมูลผลิตภัณฑ์
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-plus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true" style="">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped repeater-product" id="myTableProduct">
                        <thead>
                            <tr>
                                <th class="text-center" width="2%">#</th>
                                <th class="text-center" width="48%">รายละเอียดผลิตภัณฑ์</th>
                                <th class="text-center" width="13%">จำนวนของกลาง</th>
                                <th class="text-center" width="13%">หน่วยของกลาง</th>
                                <th class="text-center" width="13%">มูลค่าของกลาง</th>
                                <th class="text-center" width="9%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody  data-repeater-list="product-list">

                            @if( !empty($cases->product_list) && count( $cases->product_list ) )

                                @foreach ( $cases->product_list as  $i => $product )

                                    <tr data-repeater-item>
                                        <td class="text-top product_no">{!! ++$i !!}</td>
                                        <td class="text-top">
                                            {!! Form::textarea('detail', !empty($product->detail)?$product->detail:null  , ['class' => 'form-control', 'rows' => 4]) !!}
                                        </td>
                                        <td class="text-top">
                                            {!! Form::text('amount', !empty($product->amount)?$product->amount:null  , ['class' => 'form-control text-right check_format_en_and_number']) !!}
                                        </td>
                                        <td class="text-top">
                                            {!! Form::text('unit', !empty($product->unit)?$product->unit:null  , ['class' => 'form-control']) !!}
                                        </td>
                                        <td class="text-top">
                                            {!! Form::text('total_price', !empty($product->total_price)?$product->total_price:null  , ['class' => 'form-control text-right check_format_en_and_number']) !!}
                                        </td>
                                        <td class="text-top text-center">
                                            <button class="btn btn-icon btn-danger btn-sm product_delete" type="button" data-repeater-delete>
                                                <i class="bx bx-x"></i>
                                            </button>
                                            {!! Form::hidden('id', !empty($product->id)?$product->id:null  , ['class' => 'form-control']) !!}
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            
                            @else
                                <tr data-repeater-item>
                                    <td class="text-top product_no">1</td>
                                    <td class="text-top">
                                        {!! Form::textarea('detail', null  , ['class' => 'form-control', 'rows' => 4]) !!}
                                    </td>
                                    <td class="text-top">
                                        {!! Form::text('amount', null  , ['class' => 'form-control text-right check_format_en_and_number']) !!}
                                    </td>
                                    <td class="text-top">
                                        {!! Form::text('unit', null  , ['class' => 'form-control']) !!}
                                    </td>
                                    <td class="text-top">
                                        {!! Form::text('total_price', null  , ['class' => 'form-control text-right check_format_en_and_number']) !!}
                                    </td>
                                    <td class="text-top text-center">
                                        <button class="btn btn-icon btn-danger btn-sm product_delete" type="button" data-repeater-delete>
                                            <i class="bx bx-x"></i>
                                        </button>
                                        {!! Form::hidden('id', null  , ['class' => 'form-control']) !!}
                                    </td>
                                </tr>
                                
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm" data-repeater-create type="button">
                                        <i class="bx bx-plus"></i>เพิ่ม
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading"> ข้อมูลมอก.
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-plus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true" style="">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('seleted_tis_id', 'มอก.', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('seleted_tis_id',  null, ['class' => 'form-control', 'placeholder'=>'- เลือกมอก. -']) !!}
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm" type="button" id="btn_seleted_tis_id">
                                เลือก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped repeater-standard" id="myTableStandard">
                        <thead>
                            <tr>
                                <th class="text-center" width="2%">#</th>
                                <th class="text-center" width="30%">มอก.</th>
                                <th class="text-center" width="60%">ผลิตภัณฑ์อุตสาหกรรม</th>
                                <th class="text-center" width="8%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody  data-repeater-list="standard-list">
                            @if( !empty($cases->standard_list) && count( $cases->standard_list ) )

                                @foreach ( $cases->standard_list as  $i => $standard )
                                    <tr data-repeater-item>
                                        <td class="text-top text-center standard_no">1</td>
                                        <td class="text-top">{!! !empty($standard->tb3_tisno)?$standard->tb3_tisno:null !!}</td>
                                        <td class="text-top">{!! !empty($standard->tis_name)?$standard->tis_name:null !!}</td>
                                        <td class="text-top text-center">
                                            <button class="btn btn-icon btn-danger btn-sm standard_delete" type="button" data-repeater-delete><i class="bx bx-x"></i></button>

                                            {!! Form::hidden('tb3_tisno', !empty($standard->tb3_tisno)?$standard->tb3_tisno:null  , ['class' => 'form-control']) !!}
                                            {!! Form::hidden('tis_name', !empty($standard->tis_name)?$standard->tis_name:null  , ['class' => 'form-control']) !!}
                                            {!! Form::hidden('tis_id', !empty($standard->tis_id)?$standard->tis_id:null  , ['class' => 'form-control tis_id']) !!}
                                            {!! Form::hidden('id', $standard->id  , ['class' => 'form-control']) !!}

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
</div>

<div class="panel panel-info">
    <div class="panel-heading"> ข้อมูลใบอนุญาต
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-plus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true" style="">
        <div class="panel-body">

            <table class="table table-striped repeater-licenses" id="myTableLicenses">
                <thead>
                    <tr>
                        <th class="text-center" width="2%">เลือก</th>
                        <th class="text-center" width="15%">เลขที่ใบอนุญาต</th>
                        <th class="text-center" width="53%">มอก.</th>
                        <th class="text-center" width="15%">ไฟล์ใบอนุญาต</th>
                        <th class="text-center" width="15%">สถานะใบอนุญาต</th>
                    </tr>
                </thead>
                <tbody  data-repeater-list="licenses-list">
                    @foreach ( $Tb4_licenses as $licenses )
                        @php
                            $file            = '';
                            if(!empty($licenses->license_pdf)){
                                $file       .= ' <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/'.$licenses->license_pdf.'" target="_blank">'.(HP::FileExtension($licenses->license_pdf)?? '').'</a>' ;
                            }else{
                                $file       .='<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
                            }

                            $status         = '';

                            $Is_pause       = $licenses->Is_pause;
                            $license_pause  = $licenses->license_pause;
                            $license_cancel = $licenses->license_cancel;

                            if($licenses->tbl_licenseStatus == 1){
                                $status     .= '<div><span class="text-success">ใช้งาน</span></div>';
                            }else{
                                if( empty($Is_pause) && empty( $license_pause) && empty( $license_cancel) ){
                                    $status .= '<div><span class="text-danger">ไม่ใช้งาน</span></div>';
                                }
                            }
                            if($licenses->Is_pause == 1){
                                $status     .= '<div><span class="text-danger">พักใช้</span></div>';
                                $status     .= '('. (!empty($licenses->date_pause_start)?HP::DateThai($licenses->date_pause_start):'-').(!empty($licenses->date_pause_end)?'ถึง'. HP::DateThai($licenses->date_pause_end):'-').')';
                                $status     .= '<div><span class="text-muted">NSW</span></div>';
                            }

                            if( !is_null($license_pause) && empty($license_pause->date_pause_cancel) ){
                                $status     .= '<div><span class="text-danger">พักใช้</span></div>';
                                $status     .= '('. (!empty($license_pause->date_pause_start)?HP::DateThai($license_pause->date_pause_start):'-').(!empty($license_pause->date_pause_end)?'ถึง'. HP::DateThai($license_pause->date_pause_end):'-').')';
                                $status     .= '<div><span class="text-muted">Law</span></div>';
                            }

                            if( !is_null($license_cancel) ){
                                $status .= '<div><span class="text-danger">เพิกถอน</span></div>';
                                $status .= '('. (!empty($license_cancel->tbl_cancelDate)?HP::DateThai($license_cancel->tbl_cancelDate):'-').')';
                            }

                            $tbl_tisiName  = '';
                            if(!empty($licenses->tbl_tisiNo)){
                                $tis           = $licenses->tis;
                                $tbl_tisiName .= !empty($tis->tb3_Tisno)?$tis->tb3_Tisno:null;
                                $tbl_tisiName .= !empty($tis->tb3_TisThainame)? ' : '. $tis->tb3_TisThainame:null;
                            }

                        @endphp
                        <tr data-repeater-item>
                            <td>
                                <div class="checkbox checkbox-info">
                                    <input id="tb4_tisilicense_{!! $licenses->Autono !!}" name="tb4_tisilicense_id" type="checkbox" value="{!! $licenses->Autono !!}" @if( in_array( $licenses->Autono , $license_list ) ) checked @endif>
                                    <label for="tb4_tisilicense_{!! $licenses->Autono !!}"></label>
                                </div> 

                                {!! Form::hidden('license_number', !empty($licenses->tbl_licenseNo)?trim($licenses->tbl_licenseNo):null  , ['class' => 'form-control']) !!}
                            </td>
                            <td class="text-top">
                                {!! !empty($licenses->tbl_licenseNo)?$licenses->tbl_licenseNo:null !!} 
                                <div>( {!! array_key_exists($licenses->tbl_licenseType,$type_name)?$type_name[$licenses->tbl_licenseType]:null !!} )</div>
                            </td>
                            <td class="text-top">{!! !empty($tbl_tisiName)?$tbl_tisiName:null !!}</td>
                            <td class="text-top text-center">{!! !empty($file)?$file:null !!}</td>
                            <td class="text-top text-center">{!! !empty($status)?$status:null !!}</td>

                        </tr>
                    @endforeach        
                </tbody>
            </table>
        </div>
    </div>
</div>







