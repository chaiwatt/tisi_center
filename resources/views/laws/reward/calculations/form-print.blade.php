{{-- @if ($cases->law_basic_arrest_id == '1') <!-- ไม่มีการจับกุม --> --}}
<div class="row">
             <div class="col-md-12">
                 <div class="white-box">

<div class="form-group">
  <div class="col-md-12 text-center">
   <b><u>บัญชีการจ่ายเงินรางวัล</u></b>
  </div>
</div> 

<div class="form-group">
   <div class="col-md-12">
      <div class="table">
             <table class="table table-bordered table-sm"  >
                          <tbody  >
                                       <tr>
                                                    <td class="text-center text-top font-medium-6" width="10%"  rowspan="2"></td>
                                                    <td class="text-center text-top font-medium-6" width="50%"  rowspan="2"><b>ผู้มีสิทธิ์ได้เงินรางวัล</b></td>
                                                    <td class="text-center text-top font-medium-6" width="10%"  rowspan="2"><b>สัดส่วน</b></td>
                                                    <td class="text-center text-top font-medium-6" width="15%"><b>เงินค่าปรับรวม</b></td>
                                                    <td class="text-center text-top font-medium-6" width="15%"  rowspan="2"></td>
                                       </tr>
                                       <tr>
                                                    <td class="text-right text-top font-medium-6" >
                                                         {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : null !!}
                                                    </td>
                                       </tr>
                                       @if (!empty($cases->law_reward_to->law_calculation1_many) && count($cases->law_reward_to->law_calculation1_many) > 0)
                                       @foreach ($cases->law_reward_to->law_calculation1_many as  $key => $item)
                                                    <tr>
                                                    @if ($key == 0)
                                                    <td  class=" text-top text-right  font-medium-6"  rowspan="{{count($cases->law_reward_to->law_calculation1_many) }}">
                                                                 {{  !empty($cases->law_reward_to->law_calculation1_many->sum('amount')) ? number_format($cases->law_reward_to->law_calculation1_many->sum('amount'),2): ''}}
                                                    </td>      
                                                    @endif
                                                    <td  class=" text-top font-medium-6">
                                                                 {{ $item->division_name  ?? null}}
                                                    </td>
                                                    <td  class=" text-top  text-center font-medium-6">
                                                                 {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
                                                    </td>
                                                    <td  class=" text-top text-right font-medium-6">
                                                                 {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
                                                    </td>
                                                    <td  class=" text-top font-medium-6">
                                                    
                                                    </td>
                                       </tr>
                                      @endforeach   
                                      @endif 
                                      @if (!empty($cases->law_reward_to->law_calculation2_many) && count($cases->law_reward_to->law_calculation2_many) > 0)
                                      @foreach ($cases->law_reward_to->law_calculation2_many as  $key => $item)
                                                   <tr>
                                                   @if ($key == 0)
                                                   <td  class=" text-top text-right  font-medium-6"  rowspan="{{count($cases->law_reward_to->law_calculation2_many) }}">
                                                                {{  !empty($cases->law_reward_to->law_calculation2_many->sum('amount')) ? number_format($cases->law_reward_to->law_calculation2_many->sum('amount'),2): ''}}
                                                   </td>      
                                                   @endif
                                                   <td  class=" text-top font-medium-6">
                                                                {{ $item->division_type_name  ?? null}}
                                                   </td>
                                                   <td  class=" text-top  text-center font-medium-6">
                                                                {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
                                                   </td>
                                                   <td  class=" text-top text-right font-medium-6">
                                                                {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
                                                   </td>
                                                   <td  class=" text-top font-medium-6">
                                                   
                                                   </td>
                                      </tr>
                                     @endforeach   
                                     @endif 
                          </tbody>
             </table>
      </div>
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
     <div class="table">
            <table class="table table-bordered table-sm"  >
                         <tbody  >
                                    <tr>
                                      <td class="text-center text-top font-medium-6">
                                            <b>ผู้มีสิทธิ์ได้เงินรางวัล</b> 
                                      </td>
                                       <td class="text-center text-top font-medium-6">
                                           <b>รายชื่อ</b> 
                                       </td>
                                      <td class="text-center text-top font-medium-6">
                                           <b>สัดส่วน</b> 
                                       </td>
                                      <td class="text-center text-top font-medium-6">
                                           <b>จำนวนเงิน</b> 
                                       </td>
                                      <td class="text-center text-top font-medium-6">
                                           <b>หมายเหตุ</b> 
                                       </td>
                                    </tr>
                                    @if (!empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) > 0)
                                    @foreach ($cases->law_reward_to->law_calculation3_many as  $key => $item)
                                      @php
                                            $staffs =     App\Models\Law\Reward\LawlRewardStaffLists::select('name')->where('law_reward_id',$item->law_reward_id)->where('basic_reward_group_id',$item->law_basic_reward_group_id)->get();
                                      @endphp
                                    <tr>
                                      <td class=" text-top font-medium-6">
                                             {{ $item->name  ?? null}}
                                      </td>
                                       <td class=" text-top font-medium-6">
                                          @if (count($staffs) > 0)
                                                      {{ $staffs->pluck('name')->implode(', ') }}
                                                   {{-- <p>
                                                                @foreach ($staffs as $item1)    
                                                                {!!   (!empty($item1->name) ?  $item1->name.'<br/>' :  '' ) !!}
                                                                @endforeach        
                                                    </p> --}}
                                          @endif         
                                          
                                       </td>
                                      <td class="text-center text-top font-medium-6">
                                                   {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
                                       </td>
                                      <td class="text-right text-top font-medium-6">
                                                   {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
                                       </td>
                                      <td class="text-top font-medium-6">
                                                   {{  !empty($item->remark) ? $item->remark : ''}}
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
</div> 

{{-- @else --}}

<div class="row">
             <div class="col-md-12">
                 <div class="white-box">


  <div class="col-md-12 text-center">
             <b><u>บัญชีการจ่ายเงินรางวัล</u></b>
  </div>
<div class="form-group">
  <div class="col-md-12 text-left">
             <b><u> {!! !empty($cases->law_basic_arrest) ? "ประเภทคดี".$cases->law_basic_arrest : '' !!}   </u></b>
  </div>
</div> 
<div class="form-group">
  <div class="col-md-12 text-center">
         เลขทะเบียนคดีเปรียบปรับ
         <span class="free-dot">&nbsp;&nbsp;&nbsp;{!! !empty($cases->law_cases_compare_to->book_number) ? $cases->law_cases_compare_to->book_number : null !!} &nbsp;&nbsp;&nbsp;</span>
  </div>
</div>
<div class="form-group">
  <div class="col-md-12 text-center">
         ชื่อ คดี
         <span class="free-dot">&nbsp;&nbsp;&nbsp;{!! !empty($cases->offend_name) ? $cases->offend_name : null !!}&nbsp;&nbsp;&nbsp;</span>
  </div>
</div>
<div class="form-group"> 
  <div class="col-md-12 text-center">
         วันที่ออกใบเสร็จรับเงิน/เลขที่ใบเสร็จรับเงิน
         <span class="free-dot">&nbsp;&nbsp;&nbsp;{!! !empty($cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode) ? $cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode: ''  !!}&nbsp;&nbsp;&nbsp;</span>
         เงินปรับ
         <span class="free-dot">&nbsp;&nbsp;&nbsp; {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : null !!}&nbsp;&nbsp;&nbsp;</span>
  </div>
</div>
 


<div class="form-group">
  <div class="col-md-12">
     <div class="table">
            <table class="table table-bordered table-sm"  >
                         <tbody  >
                                    <tr>
                                      <td class="text-center text-top font-medium-6"  width="5%">
                                            <b>ลำดับ</b> 
                                      </td>
                                       <td class="text-center text-top font-medium-6"  width="95%">
                                           <b>รายชื่อผู้รับเงินรางวัล</b> 
                                       </td>
                                    </tr>
                                    @if (!empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) > 0)
                                       @php
                                           $k = 1;
                                       @endphp
                                    @foreach ($cases->law_reward_to->law_calculation3_many as    $item)
                                      @php
                                            $staffs =     App\Models\Law\Reward\LawlRewardStaffLists::select('name')->where('law_reward_id',$item->law_reward_id)->where('basic_reward_group_id',$item->law_basic_reward_group_id)->get();
                                      @endphp
                                      @if (count($staffs) > 0)
                                              <tr>
                                                  <td class=" text-top text-center font-medium-6"   valign="top">
                                                        {{ ($k++) }}
                                                  </td>
                                                  <td class=" text-top font-medium-6"   valign="top">
                                                        <b>{{ $item->name ?? '' }}</b>  
                                                  </td>
                                             </tr>
                                        @foreach ($staffs as  $key => $staff)
                                            <tr>
                                               <td class=" text-top font-medium-6"   valign="top">
                                                   
                                               </td>
                                               <td class=" text-top font-medium-6"   valign="top">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {{ ($key+1).' '.($staff->name ?? '') }}
                                               </td>
                                           </tr>
                                       @endforeach    
                                    @endif    
                                   @endforeach   
                                   @endif 

                         </tbody>
            </table>
     </div>
 </div>
</div>




      </div>
  </div>
</div>  

{{-- @endif --}}


 