@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <style>
        .div_dotted {
            border-bottom: 1px dotted #000;
            padding: 0 0 5px 0;
            cursor: not-allowed;
        }

        .input_dotted {
            border: none;
            border-bottom: 1px dotted #000;
            cursor: not-allowed;
        }

        legend {
            margin-bottom: 0px;
        }

        .div-show{
            display: block;
        }

        .div-hide{
            display: none;
        }

        .input_dotted[disabled] {
            background-color: #ffffff;
            opacity: 1;
        }

        .btn-sm {
            padding: 2px 5px;
            font-size: 12px;
            font-family: 'Kanit', Open Sans, sans-serif;
            line-height: 1.5;
            border-radius: 3px;
        }

        .font-large {
            font-family: 'Kanit', Open Sans, sans-serif;
        }

    </style>
@endpush

<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลผู้กระทำความผิด</h5></legend>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">ชื่อผู้ประกอบการ/TAXID :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->offend_name) &&  !empty($cases->offend_taxid)   ? $cases->offend_name .' | '.$cases->offend_taxid: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มอก./ผลิตภัณฑ์ :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->tis->tb3_Tisno) &&  !empty($cases->tis->tb3_TisThainame)   ? $cases->tis->tb3_Tisno .' | '.$cases->tis->tb3_TisThainame: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มาตราความผิด :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$cases->law_cases_result_to->OffenseSectionNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                        <label class="control-label col-md-3">อัตราโทษ :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->PunishNumber)   ?  implode(", ",$cases->law_cases_result_to->PunishNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">การจับกุม :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->law_basic_arrest)  ? $cases->law_basic_arrest : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">เจ้าของเรื่อง :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->owner_name)  ? $cases->owner_name : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
        </fieldset>
    </div>

    <div class="col-md-4">

        <div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!!  !empty($cases->StatusText)   ? $cases->StatusText : null  !!} </div>

        <fieldset class="white-box">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">เลขคดี :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->case_number)   ? $cases->case_number : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">นิติกร :</label>
                        <div class="col-md-7">
                             {!! Form::text('',  !empty($cases->user_lawyer_to->FullName)   ? $cases->user_lawyer_to->FullName : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>  

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">เลขที่อ้างอิงแจ้ง :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->ref_no)   ? $cases->ref_no : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">วันที่แจ้ง :<div><span class="text-muted  font-15"><i>(ผ่านระบบ)</i></span></div></label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->created_at) ?  HP::DateThaiFull($cases->created_at) : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

{{-- start ผลพิจารณาเปรียบเทียบปรับ --}}
<div class="row" id="compare_form">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>ผลพิจารณาเปรียบเทียบปรับ</h5></legend>

            <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-3">
                    {!! Form::select('status', 
                     [  '11'=> 'บันทึกผลแจ้งเปรียบเทียบปรับ',  '7'=> 'ส่งเรื่องดำเนินคดี'], 
                      (!empty($cases->status) && $cases->status != 7) ?  11 : null,
                      ['class' => 'form-control ', 'id' => 'status', 'required' => true, 'placeholder'=>'-เลือกสถานะ-']) !!}
                    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            @php
                $law_compare  = !empty($cases->law_cases_compare_to)?$cases->law_cases_compare_to:null;
                $compare_book = !empty($cases->compare_book)?$cases->compare_book:null;

                if( !empty($compare_book->book_date) ){
                    if( !empty($compare_book->book_date['book_day']) &&  !empty($compare_book->book_date['book_month']) && !empty($compare_book->book_date['book_year']) ){

                        $day                      = $compare_book->book_date['book_day'];
                        $month                    = $compare_book->book_date['book_month'];
                        $year                     = $compare_book->book_date['book_year'];

                        $compare_book->book_dates = HP::revertDate( ( $year.'-'.$month.'-'.$day ), true); 
                    }
                }

            @endphp
            <div class="box_compare">

                <div class="form-group required{{ $errors->has('[book_number]') ? 'has-error' : ''}}">
                    {!! Form::label('[book_number]', 'เลขที่หนังสือ', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-3">
                        {!! Form::text('compare[book_number]', !empty($law_compare->book_number)? $law_compare->book_number : ( !empty($compare_book->book_number)?$compare_book->book_number:null ), ['class' => 'form-control ', 'id'=>'book_number',  'required' => true  ]) !!}
                        {!! $errors->first('[book_number]', '<p class="help-block">:message</p>') !!}
                    </div>
                    {!! Form::label('[book_date]', 'ลงวันที่', ['class' => 'col-md-2 control-label']) !!}
                    <div class="col-md-3">
                        <div class="inputWithIcon">
                            {!! Form::text('compare[book_date]', !empty($law_compare->book_date)? HP::revertDate($law_compare->book_date, true) : ( !empty($compare_book->book_dates)?$compare_book->book_dates:null ), ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off',  'required' => true   ] ) !!}
                            <i class="icon-calender"></i>
                        </div>
                        {!! $errors->first('compare[book_date]', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status', 'หนังสือแจ้งปรับเปรียบเทียบ', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        @if (!empty($law_compare->file_law_cases_compare_to))
                            @php
                                $attach = $law_compare->file_law_cases_compare_to;
                                $url = url('funtions/get-law-view/files/'.(str_replace("//","/",$attach->url)).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)));
                            @endphp
                            <a href="{!! $url !!}" target="_blank">  {!! !empty($attach->filename) ? $attach->filename : '' !!} {!! HP::FileExtension($attach->url) ?? '' !!}</a>
                        @else
                        <div class="fileinput fileinput-new input-group " data-provides="fileinput" >
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attachs" id="attachs" required  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        @endif
                        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status', 'ผลเปรียบเทียบปรับ', ['class' => 'col-md-3 control-label']) !!}
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">ลำดับ</th>
                                        <th class="text-center" width="65%">รายละเอียดผลเปรียบเทียบปรับ</th>
                                        <th class="text-center" width="30%">จำนวนเงิน</th>
                                        <th class="text-center" width="3%"></th>
                                    </tr>
                                </thead>
                                <tbody id="table_tbody_adjusting">
                                    @if (count($compare_amounts) > 0)
                                        @foreach ($compare_amounts as $item)
                                            <tr>
                                                <td class="text-center text-top">1</td> 
                                                <td class="text-top">
                                                    {!! Form::text('compare_amount[detail_amounts][]', !empty($item->detail_amounts) ? $item->detail_amounts :  @$cases->detail , ['class' => 'form-control detail_amounts  ', 'required' => true  ] ) !!}
                                                </td>
                                                <td class="text-top">
                                                    {!! Form::text('compare_amount[amount][]',  !empty($item->amount) ?  number_format($item->amount,2) : @$cases->amount  , ['class' => 'form-control   amount text-right ','placeholder' => '0.00', 'required' => true  ] ) !!}
                                                </td>
                                                <td class="text-right   text-top">
                                                    <button type="button" class=" btn btn-danger btn-outline  manage  btn-sm  ">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-top text-right"><span class="font-medium-7">รวมเงิน</span></td>
                                        <td class="text-top text-right"><span id="amount_sum" class="font-medium-7"></span></td>
                                        <td class="text-top"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
{{-- end ผลพิจารณาเปรียบเทียบปรับ --}}
 
{{-- start ข้อมูลใบแจ้งชำระ (Pay-in) --}}
<div class="row box_payment">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลใบแจ้งชำระ (Pay-in)</h5></legend>

    @php
        $cases_payments = !empty($cases->law_cases_payments_to)?$cases->law_cases_payments_to:null;

        $email_results = [];
        if(!is_null($law_notify)){
            $checked = 'checked'; 
            // อีเมล
            $emails =  $law_notify->email;
            if(!is_null($emails)){
                $emails = json_decode($emails,true);
                if(!empty($emails) && count($emails) > 0){ 
                    $email_results = $emails; 
                }
            }
        }else{
            $checked = 'checked'; 
            $email_results[] =  $cases->offend_email ?? '';
        }
    @endphp

            <div class="form-group required{{ $errors->has('payment_status') ? 'has-error' : ''}}">
                {!! Form::label('payment_status', 'สถานะใบแจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <label>{!! Form::radio('payment_status', '1', (!empty($cases_payments->status) && $cases_payments->status == '1') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green', 'required' => true]) !!}
                        รอสร้างใบแจ้งชำระ
                    </label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('payment_status', '2',  empty($cases_payments) || (!empty($cases_payments->status) && $cases_payments->status == '2') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!}
                        สร้างใบแจ้งชำระ
                    </label>
                </div>
            </div>
    
<span id="span_payment_statust">      


            <div class="form-group required{{ $errors->has('condition_type') ? 'has-error' : ''}}">
                {!! Form::label('condition_type', 'เงื่อนไขการชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    <label>{!! Form::radio('condition_type', '1',  empty($cases_payments->condition_type) || (!empty($cases_payments->condition_type) && $cases_payments->condition_type == '1') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} เรียกเก็บเงินค่าปรับ</label>
                    &nbsp;&nbsp;
                    {{-- <label>{!! Form::radio('condition_type', '2', (!empty($cases_payments->condition_type) && $cases_payments->condition_type == '2') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} ไม่เรียกเก็บค่าปรับ</label>
                    &nbsp;&nbsp; --}}
                    <label>{!! Form::radio('condition_type', '3', (!empty($cases_payments->condition_type) && $cases_payments->condition_type == '3') ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} เรียกเก็บเงินค่าปรับนอกระบบ</label>
                </div>
            </div>
 
            <div class="form-group required div_case_payment{{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'ชื่อผู้ชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::text('name', 
                    !empty($cases_payments->name) ?  $cases_payments->name : ( !empty($cases->offend_name)  && !empty($cases->offend_taxid) ? HP::LawPayrName($cases->offend_name,$cases->offend_taxid) : @$cases->offend_name ), 
                    ['class' => 'form-control ',   'required' => true   ] ) !!}
                </div>
            </div>
            <div class="form-group required div_case_payment {{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status', 'วันที่แจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    <div class="table-responsive">
                         <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">ลำดับ</th>
                                    <th class="text-center" width="40%">รายการ</th>
                                    <th class="text-center" width="20%">จำนวนเงิน</th>
                                    <th class="text-center" width="38%">หมายเหตุ<i class="text-muted">(แสดงภายใต้ชื่อรายการ)</i></th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr> 
                                    <td class="text-center text-top">
                                        <span class="font-medium-7">1</span>
                                    </td>
                                    <td class="text-top">
                                        <span  class="font-medium-7">ค่าปรับเปรียบเทียบคดี{!!  !empty($cases->law_basic_arrest_to->title)  ? ' (กรณี'.$cases->law_basic_arrest_to->title.')' : ''  !!}</span>      
                                    </td>
                                    <td class="text-top text-right">
                                        <span id="inform_amount" class="font-medium-7"></span>   
                                    </td>
                                    <td class="text-top">
                                        {!! Form::text('payments_detail[remark_fee_name]', !empty($cases_payments->law_cases_payments_detail_to->remark_fee_name)?$cases_payments->law_cases_payments_detail_to->remark_fee_name:null , ['class' => 'form-control', 'id' =>'remark_fee_name' , 'required' => true  ] ) !!}
                                    </td>
                                </tr>  
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-top text-right"><span class="font-medium-7">รวมเงิน</span></td>
                                    <td class="text-top text-right "><span id="inform_sum" class="font-medium-7"></span></td>
                                    <td class="text-top"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group required div_case_payment {{ $errors->has('start_date') ? 'has-error' : ''}}">
                {!! Form::label('start_date', 'วันที่แจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-3">
                    <div class="inputWithIcon">
                        {!! Form::text('payment[start_date]',  !empty($cases_payments->start_date) ? HP::revertDate($cases_payments->start_date, true) :  HP::revertDate(date("Y-m-d"), true), ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'id'=>'start_date'  , 'autocomplete' => 'off',  'required' => true   ] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                </div>
                {!! Form::label('payment[amount_date]', 'ชำระภายใน/วัน', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-2">
                    {!! Form::text('payment[amount_date]',   !empty($cases_payments->amount_date) ? $cases_payments->amount_date :  '30', ['class' => 'form-control amount_date', 'id'=>'amount_date'  ,  'required' => true  ]) !!}
                    {!! $errors->first('payment[amount_date]', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group div_case_payment  {{ $errors->has('end_date') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('end_date', 'วันที่ครบกำหนดชำระ'.' <span class="text-danger">*</span>', ['class' => 'col-md-3 control-label font-medium-7 text-right'])) !!}
                <div class="col-md-3">
                    <div class="inputWithIcon">
                        {!! Form::text('end_date',     !empty($cases_payments->end_date) ? HP::revertDate($cases_payments->end_date, true) :  null, ['class' => 'form-control','placeholder' => 'dd/mm/yyyy', 'id'=>'end_date' , 'autocomplete' => 'off',  'disabled' => true   ] ) !!}
                        <i class="icon-calender"></i>
                    </div>
                    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                </div>
                {!! Form::label('status', 'ใบแจ้งชำระ (Pay-in)', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-3">
                    @if (!empty($cases_payments->file_law_cases_pay_in_to))
                        @php
                            $pay_in = $cases_payments->file_law_cases_pay_in_to;
                        @endphp
                        <a href="{!! url('funtions/get-law-view/files/'.$pay_in->url.'/'.(!empty($pay_in->filename) ? $pay_in->filename :  basename($pay_in->url))) !!}" target="_blank">
                            {!! HP::FileExtension($pay_in->url) ?? '' !!}
                        </a>
                    @else
                        {!! Form::text('','แสดงไฟล์อัตโนมัติเมื่อบันทึก',  ['class' => 'form-control ', 'disabled'=>true ]) !!}
                    @endif
                </div>
            </div>

            <div class="form-group required  div_case_payment_remark {{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status', 'วันที่แจ้งชำระ', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-8">
                    <div class="table-responsive">
                         <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">ลำดับ</th>
                                    <th class="text-center" width="40%">รายการ</th>
                                    <th class="text-center" width="20%">จำนวนเงิน</th>
                                    <th class="text-center" width="38%">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr> 
                                    <td class="text-center text-top">
                                        <span class="font-medium-7">1</span>
                                    </td>
                                    <td class="text-top">
                                        <span  class="font-medium-7">ค่าปรับเปรียบเทียบคดี{!!  !empty($cases->law_basic_arrest_to->title)  ? ' (กรณี'.$cases->law_basic_arrest_to->title.')' : ''  !!}</span>      
                                    </td>
                                    <td class="text-top text-right">
                                        <span   class="font-medium-7 inform_amount"></span>   
                                    </td>
                                    <td class="text-top">
                                        {!! Form::text('payment[remark]', !empty($cases_payments->law_cases_payments_detail_to->remark_fee_name)?$cases_payments->law_cases_payments_detail_to->remark_fee_name:null , ['class' => 'form-control', 'id' =>'remark' , 'required' => false  ] ) !!}
                                    </td>
                                </tr>  
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-top text-right"><span class="font-medium-7">รวมเงิน</span></td>
                                    <td class="text-top text-right "><span class="font-medium-7 inform_sum"></span></td>
                                    <td class="text-top"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox1" type="checkbox" value="1" name="funnel_system"  {{ $checked }}>
                        <label for="checkbox1"> ส่งอีเมลแจ้งเตือนไปยังผู้กระทำความผิด </label>
                    </div>
                </div>
            </div>

            <div class="form-group {{ $errors->has('') ? 'has-error' : ''}}" >
                {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                <div class="col-md-7">
                    <input type="text" value="{{ count($email_results) > 0 ?  implode(",",$email_results) : '' }}" data-role="tagsinput"  name="email_results"  id="email_results"  /> 
                </div>
            </div>

            <div class="form-group div_case_payment">
                <div class="col-md-offset-2 col-md-8">
                   <p class="text-warning">คำอธิบาย : ระบบจะสร้างใบแจ้งชำระเงิน (Pay-in) ในกรณีที่เลือกเงื่อนไข "เรียกเก็บเงินค่าปรับ" เท่านั้น</p>  
                </div>
            </div>
  </span>      

        </fieldset>
    </div>
</div>
{{-- end ข้อมูลใบแจ้งชำระ (Pay-in) --}}

<div class="clearfix"></div>

@if (!empty($compare) &&  (!empty($cases_payments->status) && $cases_payments->status == '2'))
    <a  href="{{ url('/law/cases/compares') }}"  class="btn btn-default btn-lg btn-block">
        <i class="fa fa-rotate-left"></i>
        <b>กลับ</b>
    </a>
@else
    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
            <button class="btn btn-primary" type="button" id="save_pay_in"  >
                <i class="fa fa-save"></i> บันทึก
            </button>
            @can('view-'.str_slug('law-cases-compares'))
                <a class="btn btn-default show_tag_a"   href="{{ url('/law/cases/compares') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>

    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('js/function.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        $(document).ready(function() {

            @if(\Session::has('flash_message'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session()->get('flash_message')}}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            var offend_email =  '{{  (!empty($cases->offend_email)  && filter_var($cases->offend_email, FILTER_VALIDATE_EMAIL) ? $cases->offend_email : '') }}';
            $('#checkbox1').on('click', function(e) {
                var checked = $(this).is(':checked',true);
                if(checked && offend_email != ''){
                    $('#email_results').tagsinput('add', offend_email); 
                }else{
                    $('#email_results').tagsinput('remove', offend_email);
                }
            });

            $('#pay_in_form').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
                    // Text
                    $.LoadingOverlay("show", {
                        image       : "",
                        text        :   "กำลังบันทึก กรุณารอสักครู่..." 
                    });
                   return true; // Don't submit form for this demo
            });

            @if (!empty($compare) &&  (!empty($cases_payments->status) && $cases_payments->status == '2'))
                 //Disable
                $('#pay_in_form').find('input, select, textarea').prop('disabled', true);
                $('#pay_in_form').find('input, select, textarea').prop('required', false);
                $('#pay_in_form').find('button').remove();
                $('#pay_in_form').find('.show_tag_a').hide();
                $('#pay_in_form').find('.box_remove').remove();
                $('.check-readonly').prop('disabled', true);
                $('.check-readonly').parent().removeClass('disabled');
                $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});
            @endif 

            @if (!empty($compare))
                //Disable
                $('#compare_form').find('input, select, textarea').prop('disabled', true);
                $('#compare_form').find('input, select, textarea').prop('required', false);
                $('#compare_form').find('button').remove();
                $('#compare_form').find('.show_tag_a').hide();
                $('#compare_form').find('.box_remove').remove();
             
            @else 
                   remark_fee_name();
            @endif

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            //เพิ่มแถว
            $('body').on('click', '.add-row', function(){

                $(this).removeClass('add-row').addClass('remove-row');
                $(this).removeClass('btn-success').addClass('btn-danger');
                $(this).parent().find('i').removeClass('fa-plus').addClass('fa-close');

                //Clone b
                $('#table_tbody_adjusting').children('tr:last()').clone().appendTo('#table_tbody_adjusting');

                //Clear value
                var row = $('#table_tbody_adjusting').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"], input[type="hidden"]').val('');
                    row.find('textarea').val('');
                    row.find('ul.parsley-errors-list').remove();

                ResetTableNumber();
                IsInputNumber();

                $('.amount').keyup(function(event) {
                    amount_sum();
                });
                $('.amount').change(function(event) {
                    amount_sum();
                });
                $('.amount').blur(function(event) {
                    amount_sum();
                });

            });

            //ลบแถว
            $('body').on('click', '.remove-row', function(){
                $(this).parent().parent().remove();
                ResetTableNumber();
                amount_sum();
            });

            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".amount_date").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            });

            $('.amount').keyup(function(event) {
                amount_sum();
            });

            $('.amount').change(function(event) {
                amount_sum();
            });

            $('.amount').blur(function(event) {
                amount_sum();
            });

            $('#book_number').keyup(function(event) {
                remark_fee_name();
            });

            $('#book_number').change(function(event) {
                remark_fee_name();
            });

            $('#book_number').blur(function(event) {
                remark_fee_name();
            });

            $('#start_date').change(function(event) {
                payments_date();
            });

            $('#amount_date').keyup(function(event) {
                payments_date();
            });
          
            ResetTableNumber();
            IsInputNumber();
            amount_sum();
            payments_date();

            $('#save_pay_in').click(function () { 
                var row =  $("input[name=condition_type]:checked").val();
                var payment_status = $("input[name=payment_status]:checked").val();
                var status =  $("#status").val();
     
                if(row == '1'  && status == '11' && payment_status == '2'){ // เรียกเก็บเงินค่าปรับ
                    var start_date = $('#start_date').val();  
                    var amount_date = $('#amount_date').val();  
                    $.ajax({
                        type:"get",
                        url:  "{{ url('/law/cases/compares/check_pay_in') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id:  "{{ $cases->id ?? null }}",  
                            amount:   $('#inform_sum').html(),
                            name:  $('#name').val(),
                            remark_fee_name:  $('#remark_fee_name').val(),
                            start_date: start_date,
                            amount_date: amount_date
                        },
                        success:function(data){
                            if(data.message === true){
                                    $('#pay_in_form').submit();
                            }else{
                                Swal.fire(data.status_error,'','warning');
                            }
                        }
                    });

                }else if(  status == '7'  || payment_status == '1' || row == '2' || row == '3'  ){ // ไม่เรียกเก็บค่าปรับ
                       $('#pay_in_form').submit();
                }
            });

            $("input[name=condition_type]").on("ifChanged", function(event) {
                condition_type();
            });
            $("input[name=payment_status]").on("ifChanged", function(event) {
                    payment_status();
            });

            //    condition_type();

            $('#status').change(function (e) { 
                BoxStatus();
            });
                 BoxStatus();
        });


        function BoxStatus(){
            var status      = $('#status').val();

            var box_payment = $('.box_payment');
            var box_compare = $('.box_compare');

            if( status == 11 ){
                box_payment.show();
                box_payment.find('input.form-control, select, textarea,input[type=file]').prop('required', true);

                box_compare.show();
                box_compare.find('input.form-control, select, textarea,input[type=file]').prop('required', true);

                // condition_type();
                 payment_status();
            }else{
                box_payment.hide();
                box_payment.find('input.form-control, select, textarea,input[type=file]').prop('required', false);

                box_compare.hide();  
                box_compare.find('input.form-control, select, textarea,input[type=file]').prop('required', false);
            }
        }
        function payment_status(){ 
            var payment_status = $("input[name=payment_status]:checked").val();
            if( payment_status == '2' ){
                $('#span_payment_statust').show(200);
            } else{
                $('#span_payment_statust').hide(400);
             }
              condition_type();
        }

        function condition_type(){ 
            var row = $("input[name=condition_type]:checked").val();
            var payment_status = $("input[name=payment_status]:checked").val();
            if(row == "1" && payment_status == '2' ){
                $('.div_case_payment').show(200);
                $('.div_case_payment_remark').hide(400);
                $('#remark_fee_name, #start_date, #amount_date, #name').prop('required' ,true);
                $('#remark, #amount, #fee_name').prop('required' ,false);
            } else{
                $('.div_case_payment').hide(400);
                $('.div_case_payment_remark').show(200);
                $('#remark_fee_name, #start_date, #amount_date, #name').prop('required' ,false);
                $('#remark, #amount, #fee_name').prop('required' ,false);
            }
            amount_sum();
        }

        function payments_date(){
            var start_date = $('#start_date').val();  
            var amount_date = $('#amount_date').val();  
            if(checkNone(start_date) && checkNone(amount_date)){
                $.ajax({
                        type:"get",
                        url:  "{{ url('/law/cases/compares/check_payments_date') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            start_date: start_date,
                            amount_date: amount_date
                        },
                        success:function(data){
                            if(data.message === true){
                                $('#end_date').val(data.end_date );
                            }
                        }
                });
            }
        }

        function remark_fee_name(){
            var section = '{{  !empty($cases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$cases->law_cases_result_to->OffenseSectionNumber)  : '' }}';  
            if(checkNone($('#book_number').val())){
                $('#remark_fee_name').val('ม.'+section +' เลขที่หนังสือ '+ $('#book_number').val());
            }else{
                $('#remark_fee_name').val('ม. '+section);
            }
        }

        function ResetTableNumber(){
            var rows = $('#table_tbody_adjusting').children(); //แถวทั้งหมด
                rows.each(function(index, el) {
                    var key = (index+1);
                    //เลขรัน
                    $(el).children().first().html(key);
                }); 
            var row = $('#table_tbody_adjusting').children('tr:last()');
                row.find('.manage').removeClass('remove-row').addClass('add-row');
                row.find('.manage').removeClass('btn-danger').addClass('btn-success');
                row.find('.manage > i').removeClass('fa-close').addClass('fa-plus');
        }

        function amount_sum() {
            var rows = $('#table_tbody_adjusting').children(); //แถวทั้งหมด
            var sum = 0.00;
            rows.each(function(index, el) {
                var amount = $(el).find('.amount').val();  
                if(checkNone(amount)){
                    sum  += parseFloat(RemoveCommas(amount));
                } 
            });  
            $('#amount_sum, #inform_amount, #inform_sum , .inform_amount , .inform_sum').html(addCommas(sum.toFixed(2), 2)); 

            var condition_type = $("input[name=condition_type]:checked").val();
            var start_date = $('#start_date').val();  
            var amount_date = $('#amount_date').val();  
            if(condition_type == "1" ){ 
                if(sum != 0.00 && checkNone(start_date) && checkNone(amount_date)){
                    $('#save_pay_in').prop('disabled' ,false);      
                }else{
                    $('#save_pay_in').prop('disabled' ,true);      
                }
            } else{ 
                $('#save_pay_in').prop('disabled' ,false);   
            }
        } 

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function IsInputNumber() {
            // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
            String.prototype.replaceAll = function(search, replacement) {
                var target = this;
                return target.replace(new RegExp(search, 'g'), replacement);
            }; 
                    
            var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน 
                var s_inum=new String(inum); 
                var num2=s_inum.split("."); 
                var n_inum=""; 
                if(num2[0]!=undefined){
                    var l_inum=num2[0].length; 
                    for(i=0;i<l_inum;i++){ 
                        if(parseInt(l_inum-i)%3==0){ 
                            if(i==0){ 
                                n_inum+=s_inum.charAt(i); 
                            }else{ 
                                n_inum+=","+s_inum.charAt(i); 
                            } 
                        }else{ 
                            n_inum+=s_inum.charAt(i); 
                        } 
                    } 
                }else{
                    n_inum=inum;
                }
                if(num2[1]!=undefined){ 
                    n_inum+="."+num2[1]; 
                }
                return n_inum; 
            } 

            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".amount").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 
                   
            // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
            $(".amount").on("change",function(){
                var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                if(thisVal != ''){
                    if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                        thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                        thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                    }else{ // ถ้าไม่มีคอมม่า
                        thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                    } 
                    thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                    $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                    $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                }else{
                    $(this).val('');
                }
            });
        }
    </script>
@endpush