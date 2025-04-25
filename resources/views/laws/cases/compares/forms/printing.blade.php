@push('css')
    <style>


    </style>
@endpush

@php
    $compare_book = $lawcases->compare_book;
    $amount       = !empty($lawcompare->law_cases_compare_amounts_many) ? $lawcompare->law_cases_compare_amounts_many->sum('amount') : '0.00';
    $date = date("Y-m-d");
    $date = explode("-",$date);
 
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('book_numbers') ? 'has-error' : ''}}">
            {!! Form::label('book_numbers', 'หนังสือเลขที่'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('book_numbers', !empty($compare_book->book_number)?$compare_book->book_number:null ,['class' => 'form-control','disabled' => true, 'placeholder'=>'แสดงอัตโนมัติเมื่อบันทึกข้อมูล']) !!}
                {!! $errors->first('book_numbers', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group ">
            {!! Form::label('taxid', 'วันที่'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <div class="input-group">
                    <div class="input-group-btn bg-white">
                        {!! Form::select('book_date[book_day]', HP::RangeData(1,31) , !empty($compare_book->book_date['book_day'])?$compare_book->book_date['book_day']:number_format($date[2]),  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- วัน -']) !!}
                    </div>
                    <div class="input-group-btn bg-white p-l-15">
                        {!! Form::select('book_date[book_month]', HP_Law::getMonthThais(), !empty($compare_book->book_date['book_month'])?$compare_book->book_date['book_month']:$date[1],  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- เดือน -']) !!}
                    </div>
                    <div class="input-group-btn bg-white p-l-15">
                        {!! Form::select('book_date[book_year]', HP::YearListReport(), !empty($compare_book->book_date['book_year'])?$compare_book->book_date['book_year']:$date[0],  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- ปี -']) !!}
                    </div>
                </div>
        
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
            {!! Form::label('title', 'เรื่อง'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('title', !empty($compare_book->title)?$compare_book->title:'แจ้งผลการเปรียบเทียบปรับ',['class' => 'form-control', 'required' => true  ]) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('send_to') ? 'has-error' : ''}}">
            {!! Form::label('send_to', 'เรียน'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('send_to', !empty($compare_book->send_to)?$compare_book->send_to:@$lawcases->offend_name,['class' => 'form-control', 'required' => true ]) !!}
                {!! $errors->first('send_to', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group repeater-refer">
            {!! Form::label('refer', 'สิ่งที่ส่งมาด้วย'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9" data-repeater-list="repeater-refer">

                @if( !empty($compare_book->refer) && is_array($compare_book->refer) )

                    @foreach ( $compare_book->refer as $refer )

                        <div class="form-group" data-repeater-item>
                            <div class="input-group">
                                {!! Form::text('refer', !empty($refer)?$refer:null ,['class' => 'form-control' ]) !!}
                                <span class="input-group-addon bg-white b-0 text-white"></span>
                                <div class="input-group-btn bg-white">
                                    <button type="button" class="btn btn-danger btn-sm btn_refer_remove" data-repeater-delete>
                                        <i class="fa fa-times"></i>
                                    </button> 
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="form-group" data-repeater-item>
                        <div class="input-group">
                            {!! Form::text('refer', 'ใบแจ้งการชำระเงิน จำนวน ๑ ฉบับ',['class' => 'form-control' ]) !!}
                            <span class="input-group-addon bg-white b-0 text-white"></span>
                            <div class="input-group-btn bg-white">
                                <button type="button" class="btn btn-danger btn-sm btn_refer_remove" data-repeater-delete>
                                    <i class="fa fa-times"></i>
                                </button> 
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-md-1">
                <div class="form-group m-t-5">
                    <button type="button" class="btn btn-success btn-sm btn_refer_add" data-repeater-create>
                        <i class="fa fa-plus"></i>
                    </button>  
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_name') ? 'has-error' : ''}}">
            {!! Form::label('offend_name', 'ผู้กระทำความผิด'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('offend_name', !empty($compare_book->offend_name)?$compare_book->offend_name:$lawcases->offend_name,['class' => 'form-control', 'required' => false , 'readonly' => true]) !!}
                {!! $errors->first('offend_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>   
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('offend_address') ? 'has-error' : ''}}">
            {!! Form::label('offend_address', 'ที่ตั้งสำนักงานใหญ่'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::textarea('offend_address', !empty($compare_book->offend_address)?$compare_book->offend_address:$lawcases->OffendDataAdress,['class' => 'form-control', 'required' => false, 'rows' => 3 , 'readonly' => true]) !!}
                {!! $errors->first('offend_address', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('detail') ? 'has-error' : ''}}">
            {!! Form::label('detail', 'รายละเอียดค่าปรับ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::textarea('detail',  !empty($compare_book->detail)?$compare_book->detail:null,['class' => 'form-control', 'rows' => 3, 'required' => true ]) !!}
                {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group required{{ $errors->has('amount') ? 'has-error' : ''}}">
            {!! Form::label('amount', 'จำนวนเงิน'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-4">
                {!! Form::text('amount',  !empty($compare_book->amount)?$compare_book->amount:$amount,['class' => 'form-control input_number', 'required' => true ]) !!}
                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-6">
                <span class="b-0" id="text-amount">( {!! HP_Law::TextBathFormat((!empty($compare_book->amount)?$compare_book->amount:$amount)) !!} )</span>
            </div>
      
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('file') ? 'has-error' : ''}}">
            {!! Form::label('file', 'ไฟล์หนังสือแจ้งเปรียบเทียบปรับ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                @if( !empty($compare_book->id) && !empty($lawcases->id) )
                    <a class="btn btn-icon btn-primary"  target="_blank"   href="{!! url('/law/export/compares/book?id='.$lawcases->id) !!}" >
                        <i  class="fa fa-file-word-o"  style="font-size: 1.5em;" aria-hidden="true"></i>
                    </a>   
                @else
                    {!! Form::text('file', null,['class' => 'form-control' ,'disabled' => true , 'placeholder'=>'แสดงเมื่อบันทึกข้อมูล'  ]) !!}
                    {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
                @endif
            </div>
        </div>
    </div>
</div>

<center>
    <div class="form-group">
        <div class="col-md-12">

            <button class="btn btn-primary btn-rounded" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
    
            @can('view-'.str_slug('law-cases-compares'))
                <a class="btn btn-default show_tag_a btn-rounded"  href="{{ url('/law/cases/compares') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
</center>

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.repeater-refer').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeleteRefer();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
                            BtnDeleteRefer();
                        }, 500);

                    }
                }
            });
            BtnDeleteRefer();

            $(".number_only").on("keypress keyup blur",function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $("#amount").on("keypress keyup blur",function (event) {
             
                var msg = '';
                if( checkNone($(this).val()) ){
                    msg =  "( "+( ThaiBaht($(this).val()) )+" )";
                }
                $('#text-amount').text(msg);
            });

        });
        
        function ThaiBaht(Number)
        {
            //ตัดสิ่งที่ไม่ต้องการทิ้งลงโถส้วม
            for (var i = 0; i < Number.length; i++)
            {
                Number = Number.replace (",", ""); //ไม่ต้องการเครื่องหมายคอมมาร์
                Number = Number.replace (" ", ""); //ไม่ต้องการช่องว่าง
                Number = Number.replace ("บาท", ""); //ไม่ต้องการตัวหนังสือ บาท
                Number = Number.replace ("฿", ""); //ไม่ต้องการสัญลักษณ์สกุลเงินบาท
            }

            //สร้างอะเรย์เก็บค่าที่ต้องการใช้เอาไว้
            var TxtNumArr   = new Array ("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า", "สิบ");
            var TxtDigitArr = new Array ("", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน", "ล้าน" ,"ร้อยล้าน","พันล้าน","หมื่นล้าน","แสนล้าน","ล้านล้าน");
            var TxtDigitMillion = new Array ("สิบ", "ยี่สิบ", "สามสิบ",  "สี่สิบ", "ห้าสิบ", "หกสิบ", "เจ็ดสิบ", "แปดสิบ", "เก้าสิบ");
            var BahtText = "";
            //ตรวจสอบดูซะหน่อยว่าใช่ตัวเลขที่ถูกต้องหรือเปล่า ด้วย isNaN == true ถ้าเป็นข้อความ == false ถ้าเป็นตัวเลข
            if (isNaN(Number)){
                return "ข้อมูลนำเข้าไม่ถูกต้อง";
            } else{
                //ตรวสอบอีกสักครั้งว่าตัวเลขมากเกินความต้องการหรือเปล่า
                if ((Number - 0) > 9999999999999.9999)
                {
                    return "ข้อมูลนำเข้าเกินขอบเขตที่ตั้งไว้";
                } else
                {
        
                    //ทศนิยมเติม
                    if(  Number.indexOf(".") < 0 ){
                        Number = Number+'.00';
                    }

                    //ทศนิยม กับจำนวนเต็มออกจากกัน
                    Number = Number.split (".");

                    //ขั้นตอนต่อไปนี้เป็นการประมวลผล
                    if ( checkNone(Number[1]) &&  Number[1].length > 0)
                    {
                        Number[1] = Number[1].substring(0, 2);
                    }
                    var NumberLen = Number[0].length - 0;
           
                    for(var i = 0; i < NumberLen; i++)
                    {
                        var tmp = Number[0].substring(i, i + 1) - 0;
                        if (tmp != 0)
                        {

                            if( (NumberLen - i - 1) == 7 ){
                                BahtText += TxtDigitMillion[tmp - 1 ];
                                BahtText += TxtDigitArr[NumberLen - i - 1];
                            }else{

                                if ( (NumberLen >= 2) && (i == (NumberLen - 1)) && (tmp == 1))
                                {
                                    BahtText += "เอ็ด";
                                } else
                                if ((NumberLen >= 2) && (i == (NumberLen - 2)) && (tmp == 2))
                                {
                                    BahtText += "ยี่";
                                } else
                                if ((i == (NumberLen - 2)) && (tmp == 1))
                                {
                                    BahtText += "";
                                } else
                                {
                                    BahtText += TxtNumArr[tmp];
                                }

                                BahtText += TxtDigitArr[NumberLen - i - 1];
                            }

                        }
                    }
                    BahtText += "บาท";

                    if ((Number[1] == "0") || (Number[1] == "00"))
                    {
                        BahtText += "ถ้วน";
                    } else {
                        DecimalLen = Number[1].length - 0;
                        for (var i = 0; i < DecimalLen; i++){
                            var tmp = Number[1].substring(i, i + 1) - 0;
                            if (tmp != 0) {
                                if ( (DecimalLen >= 2) && (i == (DecimalLen - 1)) && (tmp == 1))
                                {
                                    BahtText += "เอ็ด";
                                } else
                                if ( (DecimalLen >= 2) && (i == (DecimalLen - 2)) && (tmp == 2))
                                {
                                    BahtText += "ยี่";
                                } else
                                if ((i == (DecimalLen - 2)) && (tmp == 1))
                                {
                                    BahtText += "";
                                } else
                                {
                                    BahtText += TxtNumArr[tmp];
                                }
                                BahtText += TxtDigitArr[DecimalLen - i - 1];
                            }
                        }
                        BahtText += "สตางค์";
                    }
                    return BahtText;    
                }
            }
        }
        
        function BtnDeleteRefer(){

            if( $('.btn_refer_remove').length >= 2 ){
                $('.btn_refer_remove').show();
            }else{
                $('.btn_refer_remove:first').hide(); 
            }

        }
        
    </script>
@endpush