<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            /* width: 60%; */
            padding: 5px;
            border: 5px solid gray;
            margin: 0;
            
       }    
       .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

        .customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #66ccff;
        color: #000000;
        }   
        
        .center {
            text-align: center;
         }
         .right {
            text-align: right;
        }    
        .indent50 {
        text-indent: 50px;
        } 
        .indent100 {
        text-indent: 100px;
        }
   </style>
</head>
<body>
   <div id="style">
    
    <p> 
        <b>เรียน    {{  !empty($certi_lab->BelongsInformation->name) ?   $certi_lab->BelongsInformation->name   :  ''  }} </b>
    </p>
    <p> 
        <b>เรื่อง  การแต่งตั้งคณะผู้ตรวจประเมิน</b>    
    </p>

    <p class="indent50"> 
        ตามที่    {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }}
        เห็นชอบการประมาณการค่าใช้จ่าย ของ   {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name  :  ''  }}
        คำขอเลขที่  {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมขอแจ้งกำหนดการตรวจประเมินพร้อมรายชื่อคณะผู้ตรวจประเมิน 
        โดยมีรายละเอียดดังนี้
    </p>
    <p class="indent50"> 
        กำหนดการ  {{  !empty($auditors->DataBoardAuditorDateMail ) ?  $auditors->DataBoardAuditorDateMail   :  ''  }}    
    </p>
    <p class="indent50"> 
        ผู้ตรวจประเมิน
    </p>


        @if(count($auditors->groups) > 0)
        <p> 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            โดยคณะผู้ตรวจประเมิน มีรายนามดังต่อไปนี้
        </p>
        <table   class="customers" width="80%">
            <thead>
                    <tr>
                        <th width="2%">#</th>
                        <th width="10%">สถานะผู้ตรวจประเมิน</th>
                        <th width="10%" >ชื่อผู้ตรวจประเมิน</th>
                        <th width="10%" >หน่วยงาน</th>
                    </tr>
            </thead>
            <tbody>
                @foreach ($auditors->groups as $key => $data)
                <tr> 
                    <td  class="center">{{ $key + 1 }}</td>
                    <td>{{  $data->sa->title ?? '-' }}</td>
                    <td>
                        @if(count($data->auditors) > 0)
                                @foreach ($data->auditors as $ai)
                                @php
                                    $auditor = $ai->auditor;
                                @endphp
                                    {{ $auditor->name_th ?? '-' }} <br>
                                @endforeach
                        @else
                         -
                        @endif
                    </td>
                    {{-- <td>
                        @if(count($data->auditors) > 0)
                                @foreach ($data->auditors as $ai)
                                    @php
                                        $auditor = $ai->auditor;
                                    @endphp
                                {{  $auditor->department->title ?? '-'}}
                                @endforeach
                         @else
                         -
                         @endif
                    </td> --}}
                    <td>
                        @if(count($data->auditors) > 0)
                            @foreach ($data->auditors as $ai)
                                @php
                                    $auditor = $ai->auditor;
                                    $departmentTitle = $auditor->department->title ?? ''; // ตรวจสอบว่ามี title หรือไม่
                                @endphp
                                {{ str_contains($departmentTitle, 'ไม่มีรายละเอียด') ? '-' : $departmentTitle }} <!-- ตรวจสอบคำว่า "ไม่มีรายละเอียด" -->
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    
                </tr> 
                @endforeach
            </tbody>
        </table>
        @endif

        @if(count($auditors->cost_item_confirm) > 0)
        <p class="indent50"> 
            ค่าใช้จ่าย 
        </p>
        <table   class="customers" width="80%">
            <thead>
                     <tr>
                        <th  class="center">#</th>
                        <th>รายละเอียด</th>
                        <th>จำนวนเงิน</th>
                        <th>จำนวนวัน</th>
                        <th>รวม (บาท)</th>
                    </tr>
            </thead>
            <tbody>
                @php    
                $SumAmount = 0;
                @endphp
                @foreach ($auditors->cost_item_confirm as $key => $items)
                    @php     
                    $amount_date = !empty($items->amount_date) ? $items->amount_date : 0 ;
                    $amount = !empty($items->amount) ? $items->amount : 0 ;
                    $sum =   $amount*$amount_date;
                    $SumAmount  +=  $sum;
                    @endphp
                <tr> 
                    <td  class="center">{{ $key + 1 }}</td>
                    <td>{{ !empty($items->StatusAuditorTo->title) ? $items->StatusAuditorTo->title : null}}</td>
                    <td  class="right">{{ number_format( $items->amount,2) }}</td>
                    <td  class="right">{{  $amount_date ?? null }}</td>
                    <td  class="right">{{ number_format($sum,2) }}</td>
                </tr> 
               @endforeach
            </tbody>
            <footer>
                <tr>
                    <td colspan="4" class="right">รวม</td>
                    <td  class="right">
                         {{ !empty($SumAmount) ?  number_format($SumAmount, 2) : '-' }} 
                    </td>
                </tr>
            </footer>
        </table>
        @endif
        
        <p class="indent50"> 
            จึงเรียนมาเพื่อทราบ และหากท่านมีข้อขัดข้องในองค์ประกอบของคณะผู้ตรวจประเมินและกำหนดการตรวจประเมิน ดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
            และขอให้ท่านเข้าระบบ <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank">E-Accreditation</a> เพื่อยื่นยันคณะผู้ตรวจประเมินและวันที่ตรวจประเมิน ภายหลังจากรับการตรวจประเมินเรียบร้อยแล้ว  จักขอบคุณยิ่ง
          <br>
          {{-- โปรดคลิก <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a> เพื่อตรวจสอบข้อมูลในระบบ 
           <br> --}}
          --------------------------
      </p>
          <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
     <p>
        {{-- @if ($examinerUser !== null)

        {!!$examinerUser->UserContact!!}
            @else --}}
         
        {{-- @endif --}}

        @php
        $user = null;
            $examiner =  App\Models\Certify\Applicant\CheckExaminer::where('app_certi_lab_id', $certi_lab->id)->first();
            if($examiner !== null){
                $user = \App\User::find($examiner->user_id);
            }
            // $user = \App\User::find(1);
        @endphp
        {{-- {{$user->reg_fname}} --}}

        @if ($user == null)
        {!!auth()->user()->UserContact!!}
            @else
            {!!$user->UserContact!!}
        @endif
          
     </p>
    </div> 
</body> 
</html>

