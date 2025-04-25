<div class="content">
 
 <table width="100%"      > 
    <tr>
    <td class="font-16 padding-bottom-6" width="33%" > 
        <b><u> {!! !empty($cases->law_basic_arrest) ? "ประเภทคดี".$cases->law_basic_arrest : '' !!}   </u></b>
    </td>
    <td class="font-20" width="33%"  align="center"> 
             <b><u>บัญชีการจ่ายเงินรางวัล</u></b>
    </td>
     <td width="33%"> </td>
    </tr>
</table>
 
 
<table width="100%"      > 
    <tr>
    <td width="15%">  </td>
    <td class="font-16 padding-bottom-6" width="20%"  align="right"> 
          เลขทะเบียนคดีเปรียบปรับ
    </td>
     <td width="20%" class="font-16 free-dot">
        &nbsp;&nbsp;&nbsp;{!! !empty($cases->law_cases_compare_to->book_number) ? $cases->law_cases_compare_to->book_number : '' !!}&nbsp;&nbsp;&nbsp;
    </td>
    <td width="25%">  </td>
    </tr>
</table>
<table width="100%"      > 
    <tr>
    <td width="15%">  </td>
    <td class="font-16 padding-bottom-6" width="10%"  align="right"> 
          ชื่อ คดี
    </td>
     <td width="30%" class="font-16 free-dot">
        &nbsp;&nbsp;&nbsp;{!! !empty($cases->offend_name) ? $cases->offend_name : '' !!}&nbsp;&nbsp;&nbsp;
    </td>
    <td width="15%">  </td>
    </tr>
</table>
<table width="100%"      > 
    <tr>
        <td class="font-16 padding-bottom-6" width="5%"  align="right"> 
            วันที่ชำระ
        </td>
        <td width="20%" class="font-16 free-dot" align="center">
              {!! !empty($cases->law_reward_to->paid_date) ? HP::formatDateThaiFull($cases->law_reward_to->paid_date) : '' !!}
        </td>
        <td class="font-16 padding-bottom-6" width="10%"  align="right"> 
            เลขที่ใบเสร็จ
        </td> 
        <td width="35%" class="font-16 free-dot"  align="center">
            {!! !empty($cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode) ? $cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode: ''  !!}
        </td>
        <td width="5%">  </td>
    </tr>
</table>
<table width="100%"      > 
    <tr>
        <td width="35%">  </td>
        <td class="font-16 padding-bottom-6" width="5%"  align="right"> 
            เงินปรับ
        </td>
        <td width="25%" class="font-16 free-dot"  align="center">
            {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : '' !!}
        </td>
        <td width="35%">  </td>
    </tr>
</table>
<br>

<table width="100%" class="table" style="border-collapse: collapse;border: 1px solid #000000;" > 
    <tr>
        <td width="10%" class="font-16   box-border-right box-border-bottom"  valign="top" align="center">  
            <b>ลำดับ</b>  
        </td>
       <td   width="90%" class="font-16 box-border-bottom" valign="top" align="center"> 
           <b>ผู้มีสิทธิ์ได้เงินรางวัล</b>
      </td>
    </tr>
    @if (!empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) > 0)
            @php
                $k = 1;
                $count = count($cases->law_reward_to->law_calculation3_many);
            @endphp
        @foreach ($cases->law_reward_to->law_calculation3_many as   $key =>   $item)
            @php
                $staffs =     App\Models\Law\Reward\LawlRewardStaffLists::select('name')->where('law_reward_id',$item->law_reward_id)->where('basic_reward_group_id',$item->law_basic_reward_group_id)->get();
            @endphp
            @if (count($staffs) > 0)
                    <tr>
                        <td class="font-16 box-border-left  box-border-right"   valign="top" align="center">  
                            {{ ($k++) }}
                        </td>
                        <td class="font-16 box-border-right"   valign="top">
                            &nbsp;&nbsp;<b>{{ $item->name ?? '' }}</b>  
                        </td>
                    </tr>
            @foreach ($staffs as  $key1 => $staff)
                <tr>
                    <td class="font-16 box-border-left  box-border-right"   valign="top">
                        
                    </td>
                    <td class="font-16  box-border-right"   valign="top">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ ($key1+1).' '.($staff->name ?? '') }}
                    </td>
                </tr>
            @endforeach    
        @endif    
        @endforeach   
        @endif 
</table>

</div>


