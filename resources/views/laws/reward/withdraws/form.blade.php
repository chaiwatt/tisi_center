@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
 
        .not-allowed {
           cursor: not-allowed
       }
       .evidence {
            color: blue;
        }
        /* mouse over link */
        .evidence:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }
        .edit_file {
            color: blue;
        }
        /* mouse over link */
        .edit_file:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }
        
       </style>
@endpush

<div class="form-group  {{ $errors->has('reference_no') ? 'has-error' : ''}}">
    {!! Form::label('reference_no', 'เลขอ้างอิงการเบิกจ่าย', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('reference_no', !empty($withdraws->reference_no) ?  $withdraws->reference_no : 'แสดงอัตโนมัติเมื่อบันทึก', ['class' => 'form-control text-center',  'disabled' => true]) !!}
        {!! $errors->first('reference_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>
 
<div class="form-group  {{ $errors->has('plan_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('plan_name', 'ชื่อแผนงาน'.' <span class="text-danger">*</span>', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('plan_name', !empty($withdraws->plan_name) ?  $withdraws->plan_name : 'เงินนอกงบประมาณ', ['class' => 'form-control', 'required' => true]) !!}
        {!! $errors->first('plan_name', '<p class="help-block">:message</p>') !!}
    </div>
    {{-- ศูนย์ต้นทุน --}}
    {!! HTML::decode(Form::label('cost_center', 'รหัสศูนย์ต้นทุน', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('cost_center', null, ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('cost_center', '<p class="help-block">:message</p>') !!}
    </div>
</div>
 
<div class="form-group  {{ $errors->has('category') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('category', 'หมวดหมู่รายจ่าย', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('category', !empty($withdraws->category) ?  $withdraws->category : ' เงินฝากคลัง เงินสินบนรางวัล', ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
    </div>
    {!! HTML::decode(Form::label('year_code', 'รหัสปีงบประมาณ'.' <span class="text-danger">*</span>', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('year_code', null, ['class' => 'form-control', 'required' => true]) !!}
        {!! $errors->first('year_code', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('activity_main_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('activity_main_name', 'ชื่อกิจกรรมหลัก', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('activity_main_name', null, ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('activity_main_name', '<p class="help-block">:message</p>') !!}
    </div>
    {!! HTML::decode(Form::label('activity_main_code', 'รหัสกิจกรรมหลัก', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('activity_main_code', null, ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('activity_main_code', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('activity_small_name') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('activity_small_name', 'ชื่อกิจกรรมย่อย', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('activity_small_name', !empty($withdraws->activity_small_name) ?  $withdraws->activity_small_name : 'บ/ช. 00658', ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('activity_small_name', '<p class="help-block">:message</p>') !!}
    </div>
    {!! HTML::decode(Form::label('activity_small_code', 'รหัสกิจกรรมย่อย', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::text('activity_small_code', null, ['class' => 'form-control', 'required' => false]) !!}
        {!! $errors->first('activity_small_code', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@php      
    $subdepart_ids    = ['0600','0601','0602','0603','0604'];
    $users     = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                ->whereIn('reg_subdepart',$subdepart_ids)
                                ->pluck('title', 'id');  
 
@endphp

<div class="form-group  {{ $errors->has('forerunner_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('forerunner_id', 'ผู้เบิก'.' <span class="text-danger">*</span>', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-4">
        {!! Form::select('forerunner_id',
        $users ,
        (!empty($withdraws->forerunner_id) ?  $withdraws->forerunner_id :  auth()->user()->getKey())  ,
         ['class' => 'form-control', 
         'id'=>"forerunner_id",
          'required' => true,
          'placeholder'=>'- เลือกผู้เบิก -']); !!}
        {!! $errors->first('forerunner_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<hr>

<div class="form-group  {{ $errors->has('filter_type') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('end_date', 'เบิกค่าใช้จ่ายในคดี'.' <span class="text-danger">*</span>', ['class' => 'col-md-2 control-label  text-right'])) !!}
    <div class="col-md-2">
        {!! Form::select('filter_type',
        [ '1'=> 'รายคดี',
            '2'=> 'รายเดือน',
            '3'=> 'ช่วงวันที่' 
        ],
            null, 
        ['class' => 'form-control ', 
        'id' => 'filter_type']); !!}
    </div>

    <div class="col-md-3" id="div_case_number">
         @if ( !empty($withdraws->filter_case_number))
              {!! Form::text('filter_case_number', !empty($withdraws->filter_case_number) ?  $withdraws->filter_case_number :null, ['class' => 'form-control ',  'disabled' => true]) !!}
        @else
         {!! Form::select('filter_case_number', 
                    App\Models\Law\Reward\LawlRewardStaffLists::with(['law_reward_withdraws_detail_case_number_to','law_reward_recepts_detail_to'])  
                                                                        ->whereHas('law_reward_recepts_detail_to', function ($query2) {
                                                                            return  $query2->WhereNotNull('created_by');
                                                                        }) 
                                                                        ->doesntHave('law_reward_withdraws_detail_case_number_to')
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
         @endif
    </div>
    <div class="col-md-3" id="div_paid_date">
        @if (!empty($withdraws->filter_paid_date_month))
           <div class="input-daterange  input-group  ">
              {!! Form::text('',!empty($withdraws->filter_paid_date_month) && array_key_exists($withdraws->filter_paid_date_month,HP::MonthList())  ?   HP::MonthList()[$withdraws->filter_paid_date_month] : null, ['class' => 'form-control ',  'disabled' => true]) !!}
                <span class="input-group-addon"></span>
                {!! Form::text('',!empty($withdraws->filter_paid_date_year)&& array_key_exists($withdraws->filter_paid_date_year,HP::TenYearListReport())   ?   HP::TenYearListReport()[$withdraws->filter_paid_date_year] : null, ['class' => 'form-control ',  'disabled' => true]) !!}
           </div>
         @else
            <div class="input-daterange  input-group  ">
                    {!! Form::select('filter_paid_date_month',
                        HP::MonthList(),
                        null, 
                        ['class' => 'form-control ', 
                        'placeholder'=>'-เลือกเดือน-',
                        'id' => 'filter_paid_date_month']);
                    !!}
                    <span class="input-group-addon"></span>
                      <select name="filter_paid_date_year" id="filter_paid_date_year" class="form-control">
                          <option value="">-เลือกปี-</option>
                          @for ($start_year = date('Y'); $start_year >= 1880; $start_year--)
                            @php
                              $year = $start_year + 543;
                           @endphp   
                               <option value="{{$start_year}}">{!!$year!!}</option>
                          @endfor
                      </select>
 
            </div>
        @endif
    </div>
    <div class="col-md-3" id="div_paid_date_start">
         @if (!empty($withdraws->filter_paid_date_start))
            <div class="input-daterange input-group  date-range">
                {!! Form::text('',!empty($withdraws->filter_paid_date_start) ?   HP::revertDate($withdraws->filter_paid_date_start,true) : null , ['class' => 'form-control ',  'disabled' => true]) !!}
                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                {!! Form::text('',!empty($withdraws->filter_paid_date_end) ?   HP::revertDate($withdraws->filter_paid_date_end,true) : null, ['class' => 'form-control ',  'disabled' => true]) !!}
            </div>
         @else
             <div class="input-daterange input-group  date-range">
                {!! Form::text('filter_paid_date_start', null, ['class' => 'form-control','id'=>'filter_paid_date_start']) !!}
                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                {!! Form::text('filter_paid_date_end', null, ['class' => 'form-control','id'=>'filter_paid_date_end']) !!}
            </div>
        @endif
     
    </div>
    <div class="col-md-2">
        @if (empty($withdraws))
             <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-1"> </div> 
    <div class="col-md-11"> 
 
        <table class="table table-striped"  id="myTable" >
            <thead>
                <tr>
                    <th class="text-center" width="1%">#</th>
                    @if (empty($withdraws))
                       <th  width="1%"><input type="checkbox" id="checkall" checked></th>
                    @endif
                    <th class="text-center" width="10%">เลขคดี</th>
                    <th class="text-center" width="15%">จำนวนผู้มีสิทธิ์/ราย</th>
                    <th class="text-center" width="10%">จำนวนเงิน</th>
                    <th class="text-center" width="10%">บัญชีจ่าย</th>
                    <th class="text-center" width="15%">หลักฐานใบสำคัญรับเงิน</th>
                    <th class="text-center" width="20%">หมายเหตุ</th> 
                </tr>
            </thead>
            <tbody  id="table_tbody" >
                @if (!empty($withdraws) && count($withdraws->law_reward_withdraws_detail_many) > 0)
                @php
                    $amount1 = 0;
                @endphp
                  @foreach ($withdraws->law_reward_withdraws_detail_many as  $key => $item)
                       @php
                           $case_number =   !empty($item->case_number) ? $item->case_number : '0' ;
                           $income_number =   !empty($item->income_number) ? $item->income_number : '0' ;
                           $amount = 0;
                           $different = 0;
                           $send = 0;
                           $amount1 +=  !empty($item->amount) ? $item->amount : '0';
                       @endphp
                        <tr>
                            <td class="text-top text-center">
                                    {!! $key+1 !!}
                            </td>
                            <td class="text-top">
                                    {!!  $case_number !!}
                            </td>
                            <td class="text-top text-center">
                                    {!! $income_number !!}
                            </td>
                            <td class="text-top text-right">
                                    {!!  !empty($item->amount) ? number_format($item->amount,2) : '' !!}
                            </td>
                            <td class="text-top text-center">
                                @if(!empty($item->law_cases->id))
                                              <a   href="{!! url('/law/reward/calculations/print_pdf/'. base64_encode($item->law_cases->id)) !!}"  target="_blank">
                                                    <img src="{!! asset('icon/i-pdf.png') !!}"   height="30" width="30" >
                                             </a>
                                @endif
                            </td>
                            <td class="text-top text-center">
                                <div class="modal fade" id="evidence{!!$case_number!!}"  data-backdrop="static" tabindex="-1" role="dialog"   aria-hidden="true">
                                    <div class="modal-dialog   modal-xl"  >
                                       <div class="modal-content">   
                                         <div class="modal-header">
                                             <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                                             <h4 class="modal-title text-left"  >หลักฐานใบสำคัญรับเงิน</h4>
                                          </div>
                                         <div class="modal-body">
                                     <table class="table table-striped">
                                     <thead>
                                        <tr>
                                           <th class="text-center text-top" width="5%" rowspan="2">ลำดับ</th>
                                           <th class="text-center text-top" width="10%" rowspan="2">ชื่อสิทธิ์</th>
                                           <th class="text-center text-top" width="10%" rowspan="2">กลุ่มผู้มีสิทธิ์</th>
                                           <th class="text-center text-top" width="20%" rowspan="2">ใบสำคัญรับเงิน(ลงนาม)</th>
                                           <th class="text-center text-top" width="10%" rowspan="2">จำนวนเงิน</th>
                                           <th class="text-center text-top" width="20%" colspan="2">สถานะ</th>
                                           <th class="text-center text-top" width="10%"rowspan="2">หมายเหตุ</th>
                                        </tr>
                                        <tr>
                                           <th class="text-center  text-top" width="10%">ขอรับเงิน</th>
                                           <th class="text-center  text-top" width="10%">ไม่ขอรับเงิน</th>
                                        </tr>
                                     </thead>
                                     <tbody   id="tbody_{!!$case_number!!}" >
                                        @if (!empty($item) && count($item->law_reward_withdraws_detail_sub_many) > 0)
                                        @foreach ($item->law_reward_withdraws_detail_sub_many as  $key1 => $item1)
                                        
                                        @if(!empty($item1->law_reward_recepts_to))
                                          @php
                                                    $recepts =  $item1->law_reward_recepts_to;
                                     
                                                    if(!is_null($item1->attach_evidence_file)){
                                                        $attach = $item1->attach_evidence_file;
                                                      
                                                       $url    =  '<a   href="'.(url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)))).'"   class="link_file"  target="_blank">    
                                                                    <img src="'.asset('icon/i-pdf.png').'"  height="30" width="30" >
                                                                 </a> <span  class="edit_file">แก้ไข</span>';
                                                        $hide = 'hide';
                                                    }else if(!is_null($recepts->attach_evidence_file)){
                                                        $attach = $recepts->attach_evidence_file;
                                                      
                                                       $url    =  '<a   href="'.(url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)))).'"   class="link_file"  target="_blank">    
                                                                    <img src="'.asset('icon/i-pdf.png').'"  height="30" width="30" >
                                                                 </a> <span  class="edit_file">แก้ไข</span>';
                                                        $hide = 'hide';
                                                    }else{
                                                        $url  = '';
                                                        $hide = '';
                                                    }
                                                    if($item1->status == '1'){
                                                        $checked1 = 'checked';
                                                        $checked2 = '';
                                                        $send += 1;
                                                    }else{
                                                        $checked1 = '';
                                                        $checked2 = 'checked';
                                                    }

                                                       $amount += !empty($item1->amount) ? $item1->amount : 0;
                                                     if($item1->status == '2'){
                                                          $different += !empty($item1->amount) ? $item1->amount : 0;
                                                     }
                                        @endphp
                                            <tr>
                                                <td  class="text-top text-center" >
                                                    {!!($key1+1)!!}
                                                </td>
                                                <td class="text-top text-left" >
                                                    {!! $item1->name !!}
                                                </td>
                                                <td class="text-top text-left" >
                                                
                                                    {!!  !empty($item1->law_reward_group_to) ? $item1->law_reward_group_to->title : '' !!}   
                                                </td>
                                                <td class="text-top text-center " >
                                                       <div class="fileinput fileinput-new input-group {{$hide}}" data-provides="fileinput" >
                                                        <div class="form-control" data-trigger="fileinput">
                                                              <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                 <span class="fileinput-filename"></span>
                                                         </div>
                                                           <span class="input-group-addon btn btn-default btn-file">
                                                          <span class="fileinput-new">เลือกไฟล์</span>
                                                          <span class="fileinput-exists">เปลี่ยน</span>
                                                          <input type="file" name="subs[{!!$case_number!!}][attach][{!!$recepts->id!!}]"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                                          </span>
                                                         <a href="#" class="input-group-addon btn btn-default fileinput-exists delete-exists" data-dismiss="fileinput">ลบ</a>
                                                       </div>
                                                       {!! $url !!}
                                                </td>
                                                <td class="text-top text-right" >
                                                    {!!(!empty($item1->amount) ? number_format($item1->amount,2) : '0.00') !!}
                                                </td>
                                                <td  class="text-top text-center" > 
                                                    <input type="radio"   class="check" data-radio="iradio_square-green" {!!$checked1!!}  name="subs[{!!$case_number!!}][status][{!!$recepts->id!!}]"  value="1"> 
                                                </td>
                                                <td  class="text-top text-center" > 
                                                    <input type="radio"  class="check"  data-radio="iradio_square-green"  {!!$checked2!!}  name="subs[{!!$case_number!!}][status][{!!$recepts->id!!}]"   value="2">
                                                </td>
                                                <td class="text-top text-left" > 
                                                <textarea  name="subs[{!!$case_number!!}][remark][{!!$recepts->id!!}]" rows="1">{!!  !empty($item1->remark) ? $item1->remark : '' !!}</textarea> 
                                                  <input type="hidden"   name="subs[{!!$case_number!!}][recepts_id][{!!$recepts->id!!}]"  value="{!!$recepts->id!!}">
                                                  <input type="hidden"   name="subs[{!!$case_number!!}][law_basic_reward_group_id][{!!$recepts->id!!}]"  value="{!!$item1->law_basic_reward_group_id!!}">
                                                  <input type="hidden"   name="subs[{!!$case_number!!}][law_reward_staff_lists_id][{!!$recepts->id!!}]"  value="{!!$item1->law_reward_staff_lists_id!!}">
                                                  <input type="hidden"   name="subs[{!!$case_number!!}][amount][{!!$recepts->id!!}]"  value="{!!$item1->amount!!}" class="amount">
                                                </td> 
                                            </tr>
                                            @endif
                                         @endforeach  
                                         @endif 
                                       
                                    </tbody>
                                    <tfoot id="tfoot_{!!$case_number!!}">
                                        <tr>
                                            <td  class="text-top text-right" colspan="4"><b>รวม</b></td>
                                            <td  class="text-top text-right" >{!!  number_format($amount,2)  !!}</td>
                                            <td  class="" colspan="3"></td>
                                            </tr>
                                            <tr>
                                            <td  class="text-top text-right" colspan="4"><b>ส่วนต่าง</b></td>
                                            <td  class="text-top text-right different">{!! number_format($different,2) !!}</td>
                                            <td  class="" colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                
                                <p class="text-left text-muted" > หมายเหตุ : ส่วนต่าง หมายถึง ยอดเงินทั้งหมดที่สิทธิ์ได้รับเงิน ไม่ประสงค์ขอรับเงิน ซึ่งเงินดังกล่าวตกเป็นรายได้แผ่นดิน</p>
                                
                                           <div class="text-center ">
                                                 <button type="button" class="btn btn-primary save_evidence"  data-dismiss="modal" aria-label="Close"  data-case_number="{!!$case_number!!}">
                                                     บันทีก
                                                 </button>&nbsp;&nbsp;
                                                 <button type="button" class="btn btn-default cancel" data-dismiss="modal" aria-label="Close">
                                                     ยกเลิก
                                                </button>
                                             </div>
                                
                                           </div>
                                       </div>
                                  </div>
                                </div>
                                      <span  class="evidence" data-case_number="{!!$case_number!!}" >{!! '<span class="send-'. $case_number .'">'.($send).'</span>/'.$income_number !!}</span>
                            </td>
                             <td class="text-top">
                                    <textarea name="details[remark][{!!$case_number!!}]" class="form-control" rows="1">{!!  !empty($item->remark) ? $item->remark : '' !!}</textarea>
                                    <input type="hidden"   name="details[item_checkbox][{!!$case_number!!}]]"  class="item_checkbox" value="{!!$case_number!!}">
                                    <input type="hidden"   name="details[income_number][{!!$case_number!!}]"  value="{!! $income_number !!}">
                                    <input type="hidden"   name="details[amount][{!!$case_number!!}]"  value="{!!  !empty($item->amount) ? number_format($item->amount,2) : '' !!}">
                            </td>
                        </tr>
                  @endforeach
                @endif
          </tbody>
          <tfoot style="background-color: rgb(245, 245, 245)" id="table_tfoot" >
            @if (!empty($item) && count($item->law_reward_withdraws_detail_sub_many) > 0)
            <tr>
                <td class="text-right" colspan="3"><b>รวม</b></td>
                <td class="text-top text-right"> <b>{{  number_format($amount1,2)  }}</b></td>
                <td class="text-top text-right"  colspan="3"></td>
            </tr>
            @endif 
         </tfoot>
        </table>
    </div>
</div>

 
{{-- <div class="row">
    <div class="col-md-1"> </div> 
    <div class="col-md-11"> 
        {!! Form::checkbox('check_file', '1',  !empty($withdraws->check_file) && $withdraws->check_file == '1' ? true :false,  ['class'=>'check input_get_mail', 'id' => 'check_file-1', 'data-checkbox'=>'icheckbox_minimal-blue' ]) !!}
        <label for="check_file-1"> ต้องการแนบไฟล์เอง</label>
    </div>
</div> --}}

 
<div class="form-group">
  <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit" id="button_save">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
    @can('view-'.str_slug('law-reward-withdraws'))
        <a class="btn btn-default show_tag_a" href="{{url('/law/reward/withdraws')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    @endcan
  </div>
</div>
 
@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
    <script>
        var table = '';
        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });
            //ช่วงวันที่
            $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

  @if (empty($withdraws))
            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/reward/withdraws/data_detail_list') !!}',
                    data: function (d) {
                        d.filter_type               = $('#filter_type').val();
                        d.filter_case_number        = $('#filter_case_number').val();
                        d.filter_paid_date_month    = $('#filter_paid_date_month').val();
                        d.filter_paid_date_year     = $('#filter_paid_date_year').val();
                        d.filter_paid_date_start    = $('#filter_paid_date_start').val();
                        d.filter_paid_date_end      = $('#filter_paid_date_end').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'amounts', name: 'amounts' },
                    { data: 'pay_account', name: 'pay_account' },
                    { data: 'evidence', name: 'evidence' },
                    { data: 'action', name: 'action' }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,3,-2,-3] },
                    { className: "text-right text-top", targets:[4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

    
                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 4, {page:'current'} ).data().sum().toFixed(2);
                      $('#table_tfoot').html('');
                        html +=  '<tr>';
                        html +=  '<td class="text-right" colspan="4"><b>รวม</b></td>';
                        html +=  '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>';
                        html +=  '<td class="text-top text-right"  colspan="3"></td>';
                        html +=  '</tr>';
                        $('#table_tfoot').html(html);
                        check_max_size_file();
                        $('.check').each(function() {
                            var ck = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-red';
                            var rd = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-red';

                            if (ck.indexOf('_line') > -1 || rd.indexOf('_line') > -1) {
                                $(this).iCheck({
                                    checkboxClass: ck,
                                    radioClass: rd,
                                    insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                                });
                            } else {
                                $(this).iCheck({
                                    checkboxClass: ck,
                                    radioClass: rd
                                });
                            }
                        });

                        $('.check').on('ifChanged', function(event){
                                var amount          = 0;
                                var case_number     = $(this).parent().parent().parent().parent().parent().parent().find("button.save_evidence").data('case_number');
                                if(checkNone(case_number)){
                                    var table = $('#tbody_'+case_number).children(); 
                                        if(table.find(".check[value='2']:checked").length > 0){
                                            table.find(".check[value='2']:checked").each(function(index, el) {
                                                var a =  $(el).parent().parent().parent().find(".amount").val();
                                                    if(checkNone(a)){
                                                        amount  +=  parseFloat(RemoveCommas(a));
                                                    }
                                                });
                                        }
                                    $('#tfoot_'+case_number).find(".different").html(addCommas(amount.toFixed(2), 2));
                                }
                        });
       
                        if($('#checkall').is(':checked',true)){
                             $(".item_checkbox").prop('checked', true);
                             button_save();
                        }
                }
            });

            $('#btn_search').click(function () {
                table.draw();
                button_save();
            });
            button_save();
@else

            $('#myTable').DataTable( {
                dom: 'Brtip',
                pageLength:25,
                processing: true,
                lengthChange: false,
                ordering: false,
                order: [[ 0, "asc" ]]
            });

@endif
 
          $('body').on('click', '.evidence', function(){
                var case_number = $(this).data('case_number'); 
                $('#evidence'+case_number).modal('show');  
            });
            
            $('body').on('click', '.save_evidence', function(){
 
                    var case_number = $(this).data('case_number'); 
                    var row = $('#tbody_'+case_number).children(); 
                    var check  = row.find(".check[value='1']:checked").length;

                    var table = $('#table_tbody').children(); 
                    if(check > 0){
                        table.find('.send-'+case_number).html(check);
                    }else{
                        table.find('.send-'+case_number).html('0');
                    }
            });
            
            $('.check').on('ifChanged', function(event){
                    var amount          = 0;
                    var case_number     = $(this).parent().parent().parent().parent().parent().parent().find("button.save_evidence").data('case_number');
                    if(checkNone(case_number)){
                        var table = $('#tbody_'+case_number).children(); 
                            if(table.find(".check[value='2']:checked").length > 0){
                                table.find(".check[value='2']:checked").each(function(index, el) {
                                     var a =  $(el).parent().parent().parent().find(".amount").val();
                                           if(checkNone(a)){
                                            amount  +=  parseFloat(RemoveCommas(a));
                                           }
                                     });
                            }
                           $('#tfoot_'+case_number).find(".different").html(addCommas(amount.toFixed(2), 2));
                    }
             });
       

            $('body').on('change', '#filter_type', function(){

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
                }
 
            });
            $('#filter_type').change();
 
            $('body').on('click', '.edit_file', function(){
                var $this =   $(this).parent().parent();     
                    $this.find('.input-group').removeClass('hide');
                    $this.find('.link_file').addClass('hide');
                    $(this).addClass('hide');
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

        

            //เลือกทั้งหมด
             $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
                button_save();
            });
            $('body').on('click', '.item_checkbox', function(){
                button_save();
            });
     });

     
     function button_save() {
                var rows = $('#table_tbody').children(); //แถวทั้งหมด
                if( $(rows).find(".item_checkbox:checked").length > 0){
                    $('#button_save').prop('disabled', false);
                }else{
                    $('#button_save').prop('disabled', true);
                }
         
        }
           
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush
