<style>
 
    body {
        /* font-family: 'THSarabunNew', sans-serif; */
        font-family: 'thiasarabun', sans-serif;
    }
    .content{
            padding-top: 5%;
            padding-left: 2%;
            padding-right: 2%;
            margin: 0px;
            height: 100%;
            top: 10%;
            position: relative;
    
        }

  
    .font-16{
        font-size: 16pt;
    } 
    .font-20{
        font-size: 20pt;
    } 
 
    .padding-bottom-6{
        padding-bottom: -6px;
    } 
  
   .custom-label{
        background: #ffffff;
        border-bottom: thin dotted #ffffff; 
        padding-bottom: 5px;
      }

    .line-height25  {
        line-height:25px;
        padding-top: -5px;
    }
    .box-border {
        border: 1px solid #000000;
    }
    .box-border-right {
        border-right: 1px solid #000000;
    }
    .box-border-left  {
        border-left : 1px solid #000000;
    }
    .box-border-top  {
        border-top : 1px solid #000000;
    }
    .box-border-bottom  {
        border-bottom : 1px solid #000000;
    }

    .box-white  {
        border : 1px solid #ffffff;
    }
    .box-bottom-white  {
        border-bottom : 1px solid #ffffff;
    }

    .free-dot {
			border-bottom: thin dotted #000000;
            padding-bottom: -5px !important;
		}

 

</style>



<div class="content">
 
 <table width="100%"      > 
    <tr>
    <td width="33%">  </td>
    <td class="font-20" width="33%"  align="center"> 
             <b><u>บัญชีการจ่ายเงินรางวัล</u></b>
    </td>
     <td width="33%"> </td>
    </tr>
</table>
 


 
<table width="100%" class="table" style="border-collapse: collapse;" > 
    <tr  >
    <td width="10%">  </td>
    <td class="font-16 box-border" rowspan="2" valign="top"  width="50%"  align="center"> 
        <b>ผู้มีสิทธิ์ได้เงินรางวัล</b>
    </td>
    <td class="font-16 box-border"  rowspan="2" valign="top" width="10%" align="center"> 
        <b>สัดส่วน</b>
     </td>
    <td class="font-16 box-border" width="15%" valign="top" align="center">
        <b>เงินค่าปรับรวม</b>
    </td>
    <td width="15%"  rowspan="2">  </td>
    </tr>
    <tr >
        <td></td>
        <td class="font-16 box-border" valign="top"  width="15%" align="right">
             {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : '' !!}
        </td>
        <td></td>
    </tr>
    @if (!empty($cases->law_reward_to->law_calculation1_many) && count($cases->law_reward_to->law_calculation1_many) > 0)
    @foreach ($cases->law_reward_to->law_calculation1_many as  $key => $item)
                 <tr>
                 @if ($key == 0)
                 <td  class="font-16  box-border" valign="top"  rowspan="{{count($cases->law_reward_to->law_calculation1_many) }}"  align="right" >
                              {{  !empty($cases->law_reward_to->law_calculation1_many->sum('amount')) ? number_format($cases->law_reward_to->law_calculation1_many->sum('amount'),2): ''}}
                 </td>      
                 @endif
                 <td  class="font-16 box-border" valign="top" >
                    &nbsp;{{ $item->division_name  ?? null}}
                 </td>
                 <td  class="font-16 box-border" valign="top"  align="center" >
                              {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
                 </td>
                 <td  class="font-16 box-border" valign="top" align="right" >
                              {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
                 </td>
                 <td > </td>
    </tr>
   @endforeach   
   @endif 
   @if (!empty($cases->law_reward_to->law_calculation2_many) && count($cases->law_reward_to->law_calculation2_many) > 0)
   @foreach ($cases->law_reward_to->law_calculation2_many as  $key => $item)
                <tr>
                @if ($key == 0)
                <td   class="font-16  box-border"   valign="top" rowspan="{{count($cases->law_reward_to->law_calculation2_many) }}"  align="right">
                             {{  !empty($cases->law_reward_to->law_calculation2_many->sum('amount')) ? number_format($cases->law_reward_to->law_calculation2_many->sum('amount'),2): ''}}
                </td>      
                @endif
                <td   class="font-16  box-border"  valign="top">
                    &nbsp;{{ $item->division_type_name  ?? null}}
                </td>
                <td   class="font-16  box-border"  valign="top"  align="center">
                             {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
                </td>
                <td   class="font-16  box-border"  valign="top"  align="right" >
                             {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
                </td>
                <td > </td>
   </tr>
  @endforeach   
  @endif 
  <tr>
    <td   class="font-16  box-border"  valign="top"  align="center">
          <b>ผู้มีสิทธิ์ได้เงินรางวัล</b> 
    </td>
    <td   class="font-16  box-border"  valign="top"  align="center">
         <b>รายชื่อ</b> 
     </td>
    <td   class="font-16  box-border"  valign="top"  align="center">
         <b>สัดส่วน</b> 
     </td>
    <td   class="font-16  box-border"  valign="top"  align="center">
         <b>จำนวนเงิน</b> 
     </td>
     <td   class="font-16  box-border"  valign="top"  align="center">
         <b>หมายเหตุ</b> 
     </td>
  </tr>
 @if (!empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) > 0)
@foreach ($cases->law_reward_to->law_calculation3_many as  $key => $item)
  @php
        $staffs =     App\Models\Law\Reward\LawlRewardStaffLists::select('name')->where('law_reward_id',$item->law_reward_id)->where('basic_reward_group_id',$item->law_basic_reward_group_id)->get();
  @endphp
<tr>
    <td   class="font-16  box-border"  valign="top" >
         &nbsp;{{ $item->name  ?? null}}
  </td>
  <td   class="font-16  box-border"  valign="top" >
      @if (count($staffs) > 0)
                  {{-- {{ $staffs->pluck('name')->implode(', ') }} --}}
                 <p>
                            @foreach ($staffs as $item1)    
                            {!!   (!empty($item1->name) ?  $item1->name.'<br/>' :  '' ) !!}
                            @endforeach        
                </p>  
      @endif         
      
   </td>
   <td   class="font-16  box-border"  valign="top"  align="center">
               {{  (!empty($item->division) ?  $item->division.'%' :  '' )}}
   </td>
   <td   class="font-16  box-border"  valign="top"  align="right">
               {{   (!empty($item->amount) ? number_format($item->amount,2) : '' ) }}
   </td>
   <td   class="font-16  box-border"  valign="top" >
        &nbsp;{{  !empty($item->remark) ? $item->remark : ''}}
   </td>
</tr>
@endforeach   
@endif 

</table>



</div>


