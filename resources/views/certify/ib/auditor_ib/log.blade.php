<div class="clearfix"></div>
<div class="row form-group">
   <div class="col-md-12">
    <div class="white-box" style="border: 2px solid #e5ebec;">
        <legend><h3>เหตุผล / หมายเหตุ ขอแก้ไข</h3></legend>    

<div class="row">
    <div class="col-md-12">
     <div class="panel block4">
        <div class="panel-group" id="accordion">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapse"> <dd> เหตุผล / หมายเหตุ ขอแก้ไข</dd>  </a>
                    </h4>
                </div>
<div id="collapse" class="panel-collapse collapse in">
    <br>
  @foreach($auditorib->CertiIbHistorys as $key => $item)
  @if(!is_null($item->details_one))
    @php 
        $details_one = json_decode($item->details_one);
    @endphp
    <div class="row form-group">
        <div class="col-md-1"></div>
            <div class="col-md-10">
            <div class="white-box" style="border: 2px solid #e5ebec;">
            <div class="container-fluid">
                <legend>
                    <h3>
                    @if($item->status == 1)
                        <i class="fa fa-check-square" style="color:rgb(8, 180, 2);font-size:30px;" aria-hidden="true"></i>
                    @elseif($item->status == null)
                        <i class="fa fa-paper-plane" style="color:rgb(4, 0, 255);font-size:30px;" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-exclamation-triangle" style="color:rgb(229, 255, 0); background-color: red;font-size:30px;" aria-hidden="true"></i>
                    @endif
                    ครั้งที่ {{ $key +1}}
                </h3>
                </legend>
                
                @if(isset($details_one->no))
                <div class="row">
                  <div class="col-md-5 text-right">
                     <p class="text-nowrap">ชื่อคณะผู้ตรวจประเมิน</p>
                  </div>
                  <div class="col-md-7">
                      <span>{{$details_one->no ?? '-'}}</span>
                  </div>
                 </div>
                @endif


                <div class="row">
                  <div class="col-md-5 text-right">
                     <p class="text-nowrap">วันที่ตรวจประเมิน</p>
                  </div>
                  <div class="col-md-7">
                      <span>{!! $item->DataBoardAuditorDateTitle ?? '-'!!}</span>
                  </div>
               </div>

               @if(!is_null($item->file))
                  <div class="row">
                    <div class="col-md-5 text-right">
                        <p class="text-nowrap">บันทึก ลมอ.  แต่งตั้งคณะผู้ตรวจประเมิน</p>
                    </div>
                    <div class="col-md-7">
                        <a href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file)  ))}}" 
                            title="{{  !empty($item->file_client_name) ? $item->file_client_name : basename($item->file) }}" target="_blank">
                            {!! HP::FileExtension($item->file)  ?? '' !!}
                        </a>    
                    </div>
                  </div>
               @endif

               @if(!is_null($item->attachs))
               <div class="row">
                 <div class="col-md-5 text-right">
                     <p class="text-nowrap">กำหนดการตรวจประเมิน</p>
                 </div>
                 <div class="col-md-7">
                    <a href="{{url('certify/check/file_ib_client/'.$item->attachs.'/'.( !empty($item->attach_client_name) ? $item->attach_client_name :  basename($item->attachs)  ))}}" 
                        title="{{  !empty($item->attach_client_name) ? $item->attach_client_name : basename($item->attachs) }}" target="_blank">
                         {!! HP::FileExtension($item->attachs)  ?? '' !!}
                     </a>    
                 </div>
               </div>
               @endif

               @if(!is_null($item->details_two))
               <div class="col-md-12">
                <label>โดยคณะผู้ตรวจประเมิน มีรายนามดังต่อไปนี้</label>
                </div>
               <div class="col-md-12">
               <table class="table table-bordered">
                   <thead class="bg-primary">
                   <tr>
                       <th class="text-center text-white" width="2%">ลำดับ</th>
                       <th class="text-center text-white" width="30%">สถานะผู้ตรวจประเมิน</th>
                       <th class="text-center text-white" width="40%">ชื่อผู้ตรวจประเมิน</th>
                       <th class="text-center  text-white" width="26%">หน่วยงาน</th>
                   </tr>
                   </thead>
                   <tbody>
                    @php
                    $details_three = json_decode($item->details_three);
                    @endphp
                    @foreach($details_three as $key3 => $three)
                        @php
                            $status = App\Models\Bcertify\StatusAuditor::where('id',$three->status)->first();
                        @endphp
                   <tr>
                       <td  class="text-center">{{ $key3 +1 }}</td>
                       <td> {{ $status->title ?? '-'  }}</td>
                       <td>
                            {{ $three->temp_users ?? '-'  }}
                       </td>
                       <td>
                             {{ $three->temp_departments ?? '-'  }}
                       </td>
                   </tr>
                   @endforeach
                   </tbody>
               </table>
               </div>
               @endif
               
               @if(!is_null($item->details_four))
               @php
                 $details_four = json_decode($item->details_four);
              @endphp
             <div class="col-md-12">
                 <label>ค่าใช้จ่าย</label>
              </div>
             <div class="col-md-12">
             <table class="table table-bordered">
                 <thead class="bg-primary">
                 <tr>
                     <th class="text-center text-white" width="2%">ลำดับ</th>
                     <th class="text-center text-white" width="38%">รายละเอียด</th>
                     <th class="text-center text-white" width="20%">จำนวนเงิน (บาท)</th>
                     <th class="text-center text-white" width="20%">จำนวนวัน (วัน)</th>
                     <th class="text-center text-white" width="20%">รวม (บาท)</th>
                 </tr>
                 </thead>
                 <tbody>
                        @php    
                       $SumAmount = 0;
                       @endphp
                     @foreach($details_four as $key4 => $four)
                         @php     
                         $amount_date = !empty($four->amount_date) ? $four->amount_date : 0 ;
                         $amount = !empty($four->amount) ? $four->amount : 0 ;
                         $sum =   $amount*$amount_date;
                         $SumAmount  +=  $sum;
                         $details =  App\Models\Bcertify\StatusAuditor::where('id',$four->detail)->first();
                         @endphp
                         <tr>
                             <td class="text-center">{{ $key4+1 }}</td>
                             <td>{{ !is_null($details) ? $details->title : null  }}</td>
                             <td class="text-right">{{ number_format($amount, 2) }}</td>
                             <td class="text-right">{{ $amount_date }}</td>
                             <td class="text-right">{{ number_format($sum, 2) ?? '-'}}</td>
                         </tr>
                      @endforeach 
                 </tbody>
                 <footer>
                     <tr>
                         <td colspan="4" class="text-right">รวม</td>
                         <td class="text-right">
                              {{ !empty($SumAmount) ?  number_format($SumAmount, 2) : '-' }} 
                         </td>
                     </tr>
                 </footer>
             </table>
             </div>
             @endif

             <hr>

            @if(!is_null($item->status))
            <div class="row">
                <div class="col-md-4 text-right">
                <p class="text-nowrap">กำหนดการตรวจประเมิน</p>
                </div>
                <div class="col-md-7">
                <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item->status == 1 ) ? 'checked' : ' '  }}>  &nbsp;เห็นชอบดำเนินการแต่งตั้งคณะผู้ตรวจประเมินต่อไป &nbsp;</label>
                <br>
                <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item->status == 2 ) ? 'checked' : ' '  }}>  &nbsp;ไม่เห็นชอบ เพราะ  &nbsp;</label>
                </div>
            </div>
            @endif

            @if(isset($details_one->remark) &&  !is_null($details_one->remark))
            <div class="row">
              <div class="col-md-4 text-right">
                 <p class="text-nowrap">หมายเหตุ</p>
              </div>
              <div class="col-md-7">
                  {{ @$details_one->remark  ?? '-'}}
              </div>
             </div>
            @endif
            
            @if(!is_null($item->attachs_file))
            @php 
                $attachs_file = json_decode($item->attachs_file);
            @endphp 
             <div class="col-md-12">
                {!! Form::label('no', 'หลักฐาน :', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                @foreach($attachs_file as $files)
                        <p> 
                            {{  @$files->file_desc  }}
                            <a href="{{url('certify/check/file_ib_client/'.$files->file.'/'.( !empty($files->file_client_name) ? $files->file_client_name :  basename($files->file)  ))}}" 
                                title="{{  !empty($files->file_client_name) ? $files->file_client_name : basename($files->file) }}" target="_blank">
                                {!! HP::FileExtension($files->file)  ?? '' !!}
                            </a>
                        </p>
                    @endforeach
             </div>
            </div>
            @endif

           @if(!is_null($item->date))
                <div class="row">
                <div class="col-md-4 text-right">
                    <p class="text-nowrap">วันที่บันทึก</p>
                </div>
                <div class="col-md-7">
                    {{ HP::DateThai($item->date)  ?? '-'}}
                </div>
                </div>
           @endif

            </div>
        </div>
    </div>
</div>           
   @endif
  @endforeach

 </div>
            </div>
        </div>
      </div>
    </div>
</div>   

    </div>
  </div>
</div>
