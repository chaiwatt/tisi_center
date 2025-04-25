@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
 
        .not-allowed {
           cursor: not-allowed
       }
 
       .preview {
            color: blue;
        }
        /* mouse over link */
        .preview:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }
       </style>
@endpush
 
@php
      $config = HP::getConfig();
      $i = 1;
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2 required">
            <label class="control-label col-md-3">รูปแบบ</label>
            <div class="col-md-4">
                {!! Form::select('recepts_type',
                [ '1'=> 'รายคดี',
                    '2'=> 'รายเดือน',
                    '3'=> 'ช่วงวันที่' 
                ],
                    null, 
                ['class' => 'form-control ', 
                'placeholder'=>'-เลือกรูปแบบ-',
                 'required' => true,
                'id' => 'recepts_type']); !!}
            </div>
            <div class="col-md-3" id="div_case_number">
                {!! Form::select('filter_case_number', 
                            App\Models\Law\Reward\LawlRewardStaffLists::with(['law_reward_recepts_detail_case_number_to'])  
                                                                    ->doesntHave('law_reward_recepts_detail_case_number_to')
                                                                    ->Where('created_by',auth()->user()->getKey())
                                                                     ->whereHas('law_reward_to', function ($query2) {
                                                                              return  $query2->WhereIn('status',['2','3','4','5']);
                                                                      })
                                                                    ->orderbyRaw('CONVERT(case_number USING tis620)')
                                                                    ->pluck('case_number', 'case_number'),
                            null,
                            ['class' => 'form-control ', 
                            'id' => 'filter_case_number',
                            'placeholder'=>'-เลือกรายคดี-']);
                        !!}
            </div>
            <div class="col-md-3" id="div_paid_date">
                <div class="input-daterange  input-group  ">
                        {!! Form::select('filter_paid_date_month',
                            HP::MonthList(),
                            null, 
                            ['class' => 'form-control ', 
                            'placeholder'=>'-เลือกเดือน-',
                            'id' => 'filter_paid_date_month']);
                         !!}
                         <span class="input-group-addon"></span>
                        {!! Form::select('filter_paid_date_year',
                            HP::year_list(),
                            null, 
                          ['class' => 'form-control ', 
                          'placeholder'=>'-เลือกปี-',
                         'id' => 'filter_paid_date_year']);
                     !!}
                </div>
            </div>
            <div class="col-md-3" id="div_paid_date_start">
                <div class="input-daterange input-group  date-range">
                    {!! Form::text('filter_paid_date_start',   null, ['class' => 'form-control','id'=>'filter_paid_date_start']) !!}
                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                    {!! Form::text('filter_paid_date_end',  null, ['class' => 'form-control','id'=>'filter_paid_date_end']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
 
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3">เงื่อนไขการสร้าง</label>
            <div class="col-md-6">
                <label>{!! Form::radio('condition_group', '1', false , ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} รวมตามรายชื่อผู้มีสิทธิ์รับเงิน</label>
                <label>{!! Form::radio('condition_group', '2',  true , ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} แยกตามรายชื่อผู้มีสิทธิ์รับเงิน</label>
            </div>
        </div>
    </div>
</div>
 
 
<div class="row">
    <div class="col-md-12"> 

        <table class="table table-striped" >
            <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    {{-- <th class="text-center" width="15%">เลขอ้างอิงใบสำคัญรับเงิน</th> --}}
                    <th class="text-center" width="15%">ชื่อผู้สิทธิ์</th>
                    <th class="text-center" width="15%">อีเมล/เบอร์โทร</th>
                    <th class="text-center" width="10%">ชื่อคดี</th>
                    <th class="text-center" width="10%">การจับกุม</th>
                    <th class="text-center" width="10%">กลุ่มผู้มีสิทธิ์</th> 
                    <th class="text-center" width="10%">จำนวนเงิน</th>
                    @if ($config->check_deduct_money == '1')
                    @php
                        $i++;
                    @endphp
                     <th class="text-center" width="10%">หักเงินไว้</th>
                    @endif
                    @if ($config->check_deduct_vat == '1')
                    @php
                        $i++;
                    @endphp
                    <th class="text-center" width="10%">หักเงิน VAT</th>
                   @endif
                    <th class="text-center" width="10%">Preview</th>
                </tr>
            </thead>
            <tbody id="table_tbody">
                {{-- @php
                   $check = [];
                @endphp
                @if (count($query) > 0)
                    @foreach ($query as $key => $item)   
                             @php
                                 if(array_key_exists($item->taxid,$taxids)){
                                    $ids =  $taxids[$item->taxid];
                                 }else{
                                    $ids = $item->id;
                                 }
                                 if(!in_array($item->taxid,$check)){
                                     $i = 1;
                                    $check[$item->taxid] =  $item->taxid;
                                 }else{
                                      $i = 0;
                                 }
                             @endphp
                            <tr> 
                                <td class="text-top">
                                      <span class="span{{$i}}">
                                        {!! 'แสดงเมื่อบันทึก' !!}
                                     </span>
                                </td>
                                <td class="text-top">
                                    {!!  !empty($item->name) ? $item->name : '' !!}  
                                    <input type="hidden"  name="details[id][]"  value="{{  $item->id  }}">   
                                    <input type="hidden"  name="details[ids][{{$ids}}]"  value="{{$ids}}">   
                                </td>
                                <td class="text-top">
                                    @php
                                          $text  = !empty($item->mobile) ? $item->mobile : '-';
                                          $text  .= !empty($item->email) ? '<br/>'.$item->email : '';
                                    @endphp
                                        {!! $text !!}
                                </td>
                                <td class="text-top">
                                    @php
                                          $text  = !empty($item->case_number) ? $item->case_number : '';
                                          $text  .= !empty($item->law_case_to->offend_name) ? ' : '.$item->law_case_to->offend_name : '';
                                    @endphp
                                        {!! $text !!}    
                                </td>
                                <td class="text-top">
                                         {!!  !empty($item->law_case_to->law_basic_arrest_to->title) ? $item->law_case_to->law_basic_arrest_to->title : '' !!}    
                                </td>
                                <td class="text-top">
                                    @if($item->basic_reward_group_id == '9') <!-- ผู้แจ้งเบาะแส --> 
                                        {!! !empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : '' !!}
                                     @else
                                     {!! !empty($item->law_calculation3_to->name) ? $item->law_calculation3_to->name : '' !!}
                                    @endif
                                </td>
                                <td class="text-top">
                                    @if($item->basic_reward_group_id == '9') <!-- ผู้แจ้งเบาะแส --> 
                                        {!! !empty($item->law_calculation2_to->total) ?  number_format($item->law_calculation2_to->total,2): '' !!}
                                     @else
                                      {!! !empty($item->law_calculation3_to->total) ? number_format($item->law_calculation3_to->total,2) : '' !!}
                                    @endif
                                      
                                </td>
                                <td class="text-top text-center">
                                     <span class="span{{$i}}">
                                        <span class="preview" data-id="{{$item->id}}"  data-ids="{{$ids}}"  data-case_number="{{$item->case_number}}"  data-taxid="{{$item->taxid}}" >Preview</span>
                                     </span>
                                     
                                </td>
                            </tr>

                    @endforeach
                @endif --}}
            </tbody>
          
            <tfoot style="background-color: rgb(245, 245, 245)" id="table_tfoot" >
                <tr>
                    <td class="text-right" colspan="6"><b>รวม</b></td>
                    <td class="text-top text-right"> <b id="amount"> </b></td>
                    <td class="text-top text-right"  colspan="{{$i}}"></td>
                </tr>
             </tfoot>
        </table>
    </div>
</div>
 
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3">กำหนดแสดงรายการ</label>
            <div class="col-md-1 text-center">
                {!! Form::text('set_item[]','5', ['class' => 'form-control set_item', 'required' => false]) !!}
            </div>
            <label class="control-label text-center col-md-1">ต่อ</label>
            <div class="col-md-1 text-center">
                {!! Form::text('set_item[]','1', ['class' => 'form-control set_item',   'required' => false]) !!}
            </div>
            <label class="control-label col-md-1">หน้า</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3">เงื่อนไขตอบกลับ</label>
            <div class="col-md-6">
                <label>{!! Form::radio('conditon_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => true]) !!} ส่งหลักฐานกลับ</label>
                <label>{!! Form::radio('conditon_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ไม่ต้องส่งหลักฐานกลับ</label>
            </div>
        </div>
    </div>
</div>
<div class="row" id="div_due_date">
    <div class="col-md-12">
        <div class="form-group m-2 required">
            <label class="control-label col-md-3">วันครบกำหนดส่งกลับ</label>
            <div class="col-md-3">
                <div class="inputWithIcon">
                    {!! Form::text('due_date',null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','id'=>'due_date'] ) !!}
                    <i class="icon-calender"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group m-2">
            <label class="control-label col-md-3">แจ้งเตือน</label>
            <div class="col-md-6">
                  {!! Form::checkbox('notices', '1',
                     false,
                    ['class'=>'check input_get_mail', 'id' => 'notices-1', 'data-checkbox'=>'icheckbox_minimal-blue' ]) !!}
                  <label for="notices-1">ผู้สิทธิ์ได้รับเงินรางวัล</label>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
          <button class="btn btn-primary" type="submit" id="btn_save">
              <i class="fa fa-paper-plane"></i> บันทึก
          </button>
       @can('view-'.str_slug('law-reward-receipts'))
          <a class="btn btn-default"  href="{{url('/law/reward/receipts')}}">
              <i class="fa fa-rotate-left"></i> ยกเลิก
          </a>
      @endcan
    </div>
  </div>
 
@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('js/function.js') }}"></script>
    <script>

        $(document).ready(function () {

                    //ปฎิทิน
                    $('.mydatepicker').datepicker({
                        autoclose: true,
                        toggleActive: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy',
                    });


                         $('#btn_save').prop('disabled', true);
                         ResetTableNumber();
                    $('body').on('change', '#recepts_type', function(){
                        $('#filter_case_number,#filter_paid_date_month,#filter_paid_date_year,#filter_paid_date_start,#filter_paid_date_end').val('').change();         
                        if($(this).val() == '1'){
                            $('#div_case_number').show(); 
                            $('#div_paid_date,#div_paid_date_start').hide(); 
                        }else  if($(this).val() == '2'){
                            $('#div_paid_date').show(); 
                            $('#div_case_number,#div_paid_date_start').hide(); 
                        }else  if($(this).val() == '3'){
                            $('#div_paid_date_start').show(); 
                            $('#div_case_number,#div_paid_date').hide(); 
                        }else{
                            $('#div_case_number,#div_paid_date,#div_paid_date_start').hide(); 
                        }
        
                    });
                    $('#recepts_type').change();

                    $('body').on('change', '#filter_case_number,#filter_paid_date_month,#filter_paid_date_year,#filter_paid_date_start,#filter_paid_date_end', function(){
                         $('#table_tbody').html('');
                         $('#btn_save').prop('disabled', true);
                        if(
                             checkNone($('#filter_case_number').val()) ||
                             checkNone($('#filter_paid_date_month').val()) ||
                             checkNone($('#filter_paid_date_year').val()) ||
                             checkNone($('#filter_paid_date_start').val()) ||
                             checkNone($('#filter_paid_date_end').val()) 
                          ){
                                         // Text
                                        $.LoadingOverlay("show", {
                                                image       : "",
                                                text  : "กำลังค้นหารายชื่อผู้สิทธิ์ กรุณารอสักครู่..."
                                        });
                            $.ajax({
                                        method: "GET",
                                        url: "{{ url('law/reward/receipts/get_datas_html') }}",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "recepts_type": $('#recepts_type').val(),
                                            "filter_case_number": $('#filter_case_number').val(),
                                            "filter_paid_date_month": $('#filter_paid_date_month').val(),
                                            "filter_paid_date_year": $('#filter_paid_date_year').val(),
                                            "filter_paid_date_start": $('#filter_paid_date_start').val(),
                                            "filter_paid_date_end": $('#filter_paid_date_end').val() 
                                        }
                                    }).success(function (msg) { 
                                          $.LoadingOverlay("hide");
                                        if(msg.message == true){
                                            $('#table_tbody').html(msg.htmls);
                                            $('#btn_save').prop('disabled', false);
                                            // condition_group();
                                            ResetTableNumber();
                                        }
                                    });
                         }
                            ResetTableNumber();
                    });

                    //ปฎิทิน
                    $('.date-range').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                    });
                 // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
                $(".set_item").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                 }); 

                $('body').on('click', '.preview', function(){

                    var url = 'law/reward/receipts/preview';
                    var group = $("input[name=condition_group]:checked").val();
                    
                        url += '?group=' +group;
                        if(group == '2'){
                            url += '&id=' + $(this).data('id');
                        }else{
                            url += '&id=' + $(this).data('ids');
                        }
                     
                        if(checkNone($(this).data('case_number'))){
                            url += '&case_number=' +$(this).data('case_number');
                        }

                     
                        var row =  $(this).parent().parent().parent();
                        if(checkNone(row.find('.deducts:checked').val())){
                            url += '&deducts=1';
                        }else{
                            url += '&deducts=0';
                        }
                        if(checkNone(row.find('.deducts_vat:checked').val())){
                            url += '&deducts_vat=1';
                        }else{
                            url += '&deducts_vat=0';
                        }
                        if(checkNone($(this).data('taxid'))){
                            url += '&taxid=' +$(this).data('taxid');
                        }
 
                        if(checkNone($('#recepts_type').val())){
                            url += '&recepts_type=' + $('#recepts_type').val();
                        }
                        if(checkNone($('#filter_case_number').val())){
                            url += '&filter_case_number=' + $('#filter_case_number').val();
                        }
                        if(checkNone($('#filter_paid_date_month').val())){
                            url += '&filter_paid_date_month=' + $('#filter_paid_date_month').val();
                        }
                        if(checkNone($('#filter_paid_date_year').val())){
                            url += '&filter_paid_date_year=' + $('#filter_paid_date_year').val();
                        }
                        if(checkNone($('#filter_paid_date_start').val())){
                            url += '&filter_paid_date_start=' + $('#filter_paid_date_start').val();
                        }
                        if(checkNone($('#filter_paid_date_end').val())){
                            url += '&filter_paid_date_end=' + $('#filter_paid_date_end').val();
                        }
            
 
                        if(checkNone($(this).data('taxid'))){
                            url += '&taxid=' +$(this).data('taxid');
                        }
                        if(checkNone($('.set_item').val())){
                                var set_item   = [];
                            $('.set_item').each(function(index, element){
                                if(checkNone($(element).val())){
                                    set_item.push($(element).val());
                                }
                            });
                            if(set_item.length > 0){
                                url += '&set_item=' +  set_item.toString();
                            }
                        }
                        
                        window.open('{!! url("'+url +'") !!}', '_blank');
                });
                    //    condition_group();
                    // $("input[name=condition_group]").on("ifChanged",function(){
                    //     condition_group();
                    // });
                       conditon_type();
                    $("input[name=conditon_type]").on("ifChanged",function(){
                        conditon_type();
                    });

                    $('#myForm').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                    })  .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กำลังบันทึก กรุณารอสักครู่..."
                                    });
                            return true;
                    });

        });
        function conditon_type(){
           var status = $("input[name=conditon_type]:checked").val();
           if(status == '1'){ 
                   $('#due_date').prop('required', true);  
                   $('#div_due_date').show();  
           }else{
                  $('#due_date').prop('required', false);  
                  $('#div_due_date').hide();  
           }
      }
    //   function condition_group(){
    //        var status = $("input[name=condition_group]:checked").val();
    //        var rows = $('#table_tbody').children(); //แถวทั้งหมด
    //        if(status == '2'){ 
    //            $(rows).find('.span0').removeClass('hide');
    //        }else{
    //            $(rows).find('.span0').addClass('hide');
    //        }
    //   }
      function ResetTableNumber(){
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                (rows.length==0)?$('#table_tfoot').hide():$('#table_tfoot').show();
                var amount = 0;
                rows.each(function(index, el) {
                      var total =  $(el).find('.total').val();
                        if( checkNone(total)){
                            amount  +=  parseFloat(RemoveCommas($(el).find('.total').val()));
                        }
                });
                $("#amount").html(addCommas(amount.toFixed(2), 2));
     }


      
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


    </script>
@endpush
