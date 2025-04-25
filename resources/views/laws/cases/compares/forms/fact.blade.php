
@php
    $offender       = $lawcases->offender;
    $cases_impound  = $lawcases->law_cases_impound_to;
    $total_value    = !empty($lawcases->law_cases_impound_to->total_value) ? number_format($lawcases->law_cases_impound_to->total_value,2) : number_format(0, 2);

    // $offender_cases = $lawcases->offender_cases()->whereNotIn('law_cases_id', [ $lawcases->id ] )->get();
    $offender_cases  = $lawcases->offender_cases_many ;
    $fact_books      = $lawcases->fact_books;
    $date            = date("Y-m-d");
    $date            = explode("-",$date);
 
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group requied{{ $errors->has('fact_book_numbers') ? 'has-error' : ''}}">
            {!! Form::label('fact_book_numbers', 'วาระที่'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_book_numbers',  !empty($fact_books->fact_book_numbers)?$fact_books->fact_book_numbers:null ,['class' => 'form-control'  ]) !!}
                {!! $errors->first('fact_book_numbers', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group ">
            {!! Form::label('book_date', 'วันที่จัดทำ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <div class="input-group">
                    <div class="input-group-btn bg-white">
                        {!! Form::select('fact_book_date[book_day]', HP::RangeData(1,31) , !empty($fact_books->fact_book_date['book_day'])?$fact_books->fact_book_date['book_day']:number_format($date[2]),  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- วัน -']) !!}
                    </div>
                    <div class="input-group-btn bg-white p-l-15">
                        {!! Form::select('fact_book_date[book_month]', HP_Law::getMonthThais(), !empty($fact_books->fact_book_date['book_month'])?$fact_books->fact_book_date['book_month']:$date[1],  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- เดือน -']) !!}
                    </div>
                    <div class="input-group-btn bg-white p-l-15">
                        {!! Form::select('fact_book_date[book_year]', HP::YearListReport(), !empty($fact_books->fact_book_date['book_year'])?$fact_books->fact_book_date['book_year']:$date[0],  ['class' => 'form-control', 'required' => false, 'placeholder'=>'- ปี -']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_value_products') ? 'has-error' : ''}}">
            {!! Form::label('fact_lawyer_by', 'นิติกรเจ้าของสำนวน'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_lawyer_by', !empty($fact_books->fact_lawyer_by) ? $fact_books->fact_lawyer_by : (!empty($lawcases->user_lawyer_to->FullName)?$lawcases->user_lawyer_to->FullName:'n/a') ,['class' => 'form-control', 'required' => false  , 'readonly' => true]) !!}
                {!! $errors->first('fact_lawyer_by', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_maker_by') ? 'has-error' : ''}}">
            {!! Form::label('fact_maker_by', 'ผู้จัดทำหนังสือ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_maker_by', !empty($fact_books->fact_maker_by)?$fact_books->fact_maker_by:auth()->user()->Fullname ,['class' => 'form-control', 'required' => false  ]) !!}
                {!! $errors->first('fact_maker_by', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-1 col-md-11">
        <div class="divider divider-left divider-secondary">
            <div class="divider-text">ข้อมูลเกี่ยวกับผู้กระทำผิด</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_offend_name') ? 'has-error' : ''}}">
            {!! Form::label('fact_offend_name', 'ผู้กระทำความผิด'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_offend_name', !empty($fact_books->fact_offend_name)?$fact_books->fact_offend_name:$lawcases->offend_name,['class' => 'form-control', 'required' => false, 'readonly' => true ]) !!}
                {!! $errors->first('fact_offend_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>   
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_offend_name') ? 'has-error' : ''}}">
            {!! Form::label('fact_offend_name', 'เลขประจำตัวผู้เสียภาษี'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_offend_name', !empty($lawcases->offend_taxid)?$lawcases->offend_taxid:null,['class' => 'form-control', 'disabled' => true ]) !!}
                {!! $errors->first('fact_offend_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>   
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_detection_date') ? 'has-error' : ''}}">
            {!! Form::label('fact_detection_date', 'วันที่ตรวจพบ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {{-- mydatepicker --}}
                <div class="inputWithIcon">
                    {!! Form::text('fact_detection_date',  !empty($fact_books->fact_detection_date)?HP::revertDate($fact_books->fact_detection_date,true):(!empty($lawcases->offend_date)?HP::revertDate($lawcases->offend_date,true):null) , ['class' => 'form-control  ', 'required' => false , 'readonly' => true, 'placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
                    {!! $errors->first('fact_detection_date', '<p class="help-block">:message</p>') !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_locale') ? 'has-error' : ''}}">
            {!! Form::label('fact_locale', 'สถานที่เกิดเหตุ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::textarea('fact_locale', !empty($fact_books->fact_locale)?$fact_books->fact_locale:(!empty($cases_impound->ImpoundDataAdress)?$cases_impound->ImpoundDataAdress:null) ,['class' => 'form-control', 'required' => false, 'rows' => 3 , 'readonly' => true]) !!}
                {!! $errors->first('fact_locale', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-1 col-md-11">
        <div class="divider divider-left divider-secondary">
            <div class="divider-text">ข้อมูลเกี่ยวกับใบอนุญาต</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('fact_license_currently') ? 'has-error' : ''}}">
            {!! Form::label('fact_license_currently', 'ใบอนุญาต'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-8">
                <div>
                    <label>
                        <div class="state iradio_square-green {!! $lawcases->offend_license_type == 2 ? 'checked':'' !!} "></div>
                        &nbsp;  ไม่ได้เป็นผู้ได้รับอนุญาตจาก สมอ.   &nbsp;
                    </label>
                </div>
                @if( $lawcases->offend_license_type == 2 )
                    <label>{!! Form::radio('fact_license_currently', '1', !empty($fact_books->fact_license_currently) && $fact_books->fact_license_currently == 1?true:false,  ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'license_currently_1']) !!} &nbsp; ปัจจุบันได้รับใบอนุญาตแล้ว &nbsp;</label>
                    <label>{!! Form::radio('fact_license_currently', '2', !empty($fact_books->fact_license_currently) && $fact_books->fact_license_currently == 2?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-blue', 'id' => 'license_currently_2']) !!} &nbsp; ปัจจุบันยังไม่ได้รับใบอนุญาต &nbsp;</label>
                @endif
                <div>
                    <label>
                        <div class="state iradio_square-green {!! $lawcases->offend_license_type == 1 ? 'checked':'' !!}"></div>
                        &nbsp;  เป็นผู้ได้รับอนุญาตจาก สมอ. {!! $lawcases->offend_license_number ?'( '.$lawcases->offend_license_number.' )':null !!} &nbsp;
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-1 col-md-11">
        <div class="divider divider-left divider-secondary">
            <div class="divider-text">ข้อมูลเกี่ยวกับผลิตภัณฑ์อุตสาหกรรม</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('fact_offend_name', 'เลขที่มอก.'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('', !empty($lawcases->tis)?$lawcases->tis->tb3_Tisno:null,['class' => 'form-control', 'disabled' => true ]) !!}
                {!! $errors->first('standard_number', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>   
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('standard_name', 'ชื่อมอก.'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('standard_name', !empty($lawcases->tis)?$lawcases->tis->tb3_TisThainame:null,['class' => 'form-control', 'disabled' => true ]) !!}
                {!! $errors->first('standard_name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>   
</div>

@php
    $sum_product = 0;
    $sum_price   = 0;
@endphp

@if( !empty($lawcases->impound_products) && count($lawcases->impound_products) > 0 )

    <div class="row">
        <div class="col-md-offset-2 col-md-10">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="35%">ผลิตภัณฑ์</th>
                        <th class="text-center" width="21%">จำนวน</th>
                        <th class="text-center" width="22%">ราคา/หน่วย</th>
                        <th class="text-center" width="22%">รวมราคา</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ( $lawcases->impound_products as $products )

                        @php
                            $product = 0;
                            //จำนวนที่ยึด
                            $product += (int)$products->amount_impounds;
                            //จำนวนที่อายัด
                            $product += (int)$products->amount_keep;

                            $sum_product +=  $product;
                            $sum_price   +=  $products->total_price;
                        @endphp

                        <tr>
                            <td class="text-top">{!! !empty($products->detail)?$products->detail:null !!}</td>
                            <td class="text-top text-center">{!! number_format($product) !!}</td>
                            <td class="text-top text-center">{!! number_format($products->price) !!}</td>
                            <td class="text-top text-center">{!! number_format($products->total_price) !!}</td>
                        </tr>
                        
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
    
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('fact_value_products') ? 'has-error' : ''}}">
            {!! Form::label('fact_value_products', 'จำนวนผลิตภัณฑ์'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_value_products', number_format($sum_product) ,['class' => 'form-control', 'readonly' => true ]) !!}
                {!! $errors->first('fact_value_products', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('fact_value_products') ? 'has-error' : ''}}">
            {!! Form::label('fact_value_products', 'มูลค่าผลิตภัณฑ์'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('fact_value_products', number_format($sum_price,2) ,['class' => 'form-control', 'readonly' => true ]) !!}
                {!! $errors->first('fact_value_products', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

@php
    $law_resource = App\Models\Law\Basic\LawResource::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->get();
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('fact_resource') ? 'has-error' : ''}}">
            {!! Form::label('fact_resource', 'แหล่งอ้างอิง'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-8">

                @foreach ( $law_resource as $Iresource )
                     <div>
                    <label>
                        <div class="state iradio_square-green {!! !empty($cases_impound->law_basic_resource_id) && $cases_impound->law_basic_resource_id == $Iresource->id  ? 'checked':'' !!} "></div>
                        &nbsp;  {!! $Iresource->title !!}   &nbsp;
                    </label>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fact_impound_status') ? 'has-error' : ''}}">
            {!! Form::label('fact_impound_status', 'การยึด/อายัดผลิตภัณฑ์ฯ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>
                    <div class="state iradio_square-green {!! $lawcases->impound_status == 1 || !empty($cases_impound) ? 'checked':'' !!} "></div>
                    &nbsp;  มี   &nbsp;
                </label>
                <label>
                    <div class="state iradio_square-green {!! $lawcases->impound_status == "0" || empty($cases_impound) ? 'checked':'' !!} "></div>
                    &nbsp;  ไม่มี   &nbsp;
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('fact_product_marking') ? 'has-error' : ''}}">
            {!! Form::label('fact_product_marking', 'แสดงเครื่องหมายผลิตภัณฑ์ฯ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>{!! Form::radio('fact_product_marking', '1', ( empty($fact_books) || (!empty($fact_books->fact_product_marking) && $fact_books->fact_product_marking == 1)) ? true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_marking_1']) !!} &nbsp; มี &nbsp;</label>
                <label>{!! Form::radio('fact_product_marking', '2', !empty($fact_books->fact_product_marking) && $fact_books->fact_product_marking == 2?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_marking_2']) !!} &nbsp; ไม่มี &nbsp;</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('fact_product_sell') ? 'has-error' : ''}}">
            {!! Form::label('fact_product_sell', 'การจำหน่ายผลิตภัณฑ์ฯ'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>{!! Form::radio('fact_product_sell', '1', (empty($fact_books) || (!empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == 1))  ?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_sell_1']) !!} &nbsp; ทั้งหมด &nbsp;</label>
                <label>{!! Form::radio('fact_product_sell', '2',  !empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == 2?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_sell_2']) !!} &nbsp; บางส่วน &nbsp;</label>
                <label>{!! Form::radio('fact_product_sell', '3',  !empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == 3?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_sell_3']) !!} &nbsp; ไม่มี &nbsp;</label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('fact_product_reclaim') ? 'has-error' : ''}}">
            {!! Form::label('fact_product_reclaim', 'ผลิตภัณฑ์ฯที่เรียกคืนได้'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                <label>{!! Form::radio('fact_product_reclaim', '1', !empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == 1?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_reclaim_1']) !!} &nbsp; ทั้งหมด &nbsp;</label>
                <label>{!! Form::radio('fact_product_reclaim', '2', !empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == 2?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_reclaim_2']) !!} &nbsp; บางส่วน &nbsp;</label>
                <label>{!! Form::radio('fact_product_reclaim', '3',  (empty($fact_books) || (!empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == 3))?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id' => 'fact_product_reclaim_3']) !!} &nbsp; ไม่มี &nbsp;</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-1 col-md-11">
        <div class="divider divider-left divider-secondary">
            <div class="divider-text">ข้อมูลเกี่ยวกับประวัติการกระทำผิด/ผลการตรวจติดตาม</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('fact_offend_type', 'ประวัติการกระทำความผิด'.' :', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-8">
                <div>
                    <label>
                        <div class="state iradio_square-green {!! count($offender_cases) == 0 ? 'checked':'' !!}"></div>
                        &nbsp;  ไม่มีประวัติการถูกดำเนินการทางกฎหมาย  &nbsp;
                    </label>
                </div>
                <div>
                    <label>
                        <div class="state iradio_square-green {!! count($offender_cases) >= 1 ? 'checked':'' !!}"></div>
                        &nbsp;  เคยถูกดำเนินการเปรียบเทียบปรับ / ดำเนิดคดีแล้ว {!! count($offender_cases) >= 1 ?'( '.$lawcases->SectionListText.' )':null !!} &nbsp;
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('fact_book_file', 'ไฟล์หนังสือสรุปข้อเท็จจริง'.' :', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                @if( !empty($fact_books->id) && !empty($lawcases->id) )
                    <a class="btn btn-icon btn-primary"  target="_blank"   href="{!! url('/law/export/compares/fact?id='.$lawcases->id) !!}" >
                        <i  class="fa fa-file-word-o"  style="font-size: 1.5em;" aria-hidden="true"></i>
                    </a>   
                @else
                    {!! Form::text('fact_book_file', null,['class' => 'form-control' ,'disabled' => true , 'placeholder'=>'แสดงเมื่อบันทึกข้อมูล'  ]) !!}
                @endif
            </div>
        </div>
    </div>
</div>

<center>
    <div class="form-group m-t-15">
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

