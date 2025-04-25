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
  @foreach($cost->CertiIbHistorys as $key => $item)
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
                        @if($details_one->check_status == 1 && $details_one->status_scope == 1) 
                            @php 
                                $back = true; // กลับหน้า index 
                            @endphp
                            <i class="fa fa-check-square" style="color:rgb(8, 180, 2);font-size:30px;" aria-hidden="true"></i>
                            @elseif($details_one->check_status == null && $details_one->status_scope == null) 
                            <i class="fa fa-paper-plane" style="color:rgb(4, 0, 255);font-size:30px;" aria-hidden="true"></i>
                            @else 
                            <i class="fa fa-exclamation-triangle" style="color:rgb(229, 255, 0); background-color: red;font-size:30px;" aria-hidden="true"></i>
                        @endif
                    ครั้งที่ {{ $key +1}} 
                </h3>
                </legend>

                @if(!is_null($item->details_two))
                @php 
                    $details_two =json_decode($item->details_two);
                @endphp              
                        <h4>1. จำนวนวันที่ใช้ตรวจประเมินทั้งหมด <span>{{ $item->MaxAmountDate  ?? '-' }}</span> วัน</h4>
                        <h4>2. ค่าใช้จ่ายในการตรวจประเมินทั้งหมด <span>{{ $item->SumAmount ?? '-' }}</span> บาท </h4>
             
                    <table class="table table-bordered" id="myTable_labTest">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center text-white" width="2%">ลำดับ</th>
                            <th class="text-center text-white" width="38%">รายละเอียด</th>
                            <th class="text-center text-white" width="20%">จำนวนเงิน (บาท)</th>
                            <th class="text-center text-white" width="20%">จำนวนวัน (วัน)</th>
                            <th class="text-center text-white" width="20%">รวม (บาท)</th>
                        </tr>
                        </thead>
                        <tbody id="costItem">
                            @foreach($details_two as $key1 => $item1)
                                @php     
                                $amount_date = !empty($item1->amount_date) ? $item1->amount_date : 0 ;
                                $amount = !empty($item1->amount) ? $item1->amount : 0 ;
                                $sum =   $amount*$amount_date;
                                $details =  App\Models\Bcertify\StatusAuditor::where('id',$item1->detail)->first();
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key1+1 }}</td>
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
                                     {{ $item->SumAmount ?? '-' }} 
                                </td>
                            </tr>
                        </footer>
                    </table>
                 @endif

                 @if(!is_null($item->attachs)) 
                 @php 
                 $attachs = json_decode($item->attachs);
                 @endphp
                 <div class="row">
                 <div class="col-md-3 text-right">
                 <p class="text-nowrap">ขอบข่าย:</p>
                 </div>
                 <div class="col-md-9">
                 @foreach($attachs as $scope)
                         <p> 
                            <a href="{{url('certify/check/file_ib_client/'.$scope->file.'/'.( !empty($scope->file_client_name) ? $scope->file_client_name :  basename($scope->file)  ))}}" target="_blank">
                                {!! HP::FileExtension($scope->file)  ?? '' !!}
                                {{  !empty($scope->file_client_name) ? $scope->file_client_name :  basename($scope->file)}}
                            </a> 
                         </p>
                     @endforeach
                 </div>
                 </div>
                 @endif
                 
                 @if(!is_null($item->created_at)) 
                 <div class="row">
                 <div class="col-md-3 text-right">
                     <p class="text-nowrap">วันที่บันทึก</p>
                 </div>
                 <div class="col-md-9">
                     {{ @HP::DateThai($item->created_at) ?? '-' }}
                 </div>
                 </div>
                 @endif


                 @if($details_one->check_status != null && $details_one->status_scope != null) 
                     <legend><h3>เหตุผล / หมายเหตุ ขอแก้ไข</h3></legend>

                     <div class="row">
                        <div class="col-md-3 text-right">
                                 <p class="text-nowrap">เห็นชอบกับค่าใช่จ่ายที่เสนอมา</p>
                         </div>
                         <div class="col-md-9">
                             <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($details_one->check_status == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน &nbsp;</label>
                             <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($details_one->check_status == 2 ) ? 'checked' : ' '  }}>  &nbsp;แก้ไข &nbsp;</label>
                         </div>
                     </div>
                 
                     @if(isset($details_one->remark) && $details_one->check_status == 2) 
                         <div class="row">
                         <div class="col-md-3 text-right">
                         <p class="text-nowrap">หมายเหตุ</p>
                         </div>
                         <div class="col-md-9">
                            {{ @$details_one->remark ?? ''}}
                         </div>
                         </div>
                     @endif

           
                    @if(!is_null($item->attachs_file))
                        @php 
                        $attachs_file = json_decode($item->attachs_file);
                        @endphp 
                        <div class="row">
                        <div class="col-md-3 text-right">
                        <p class="text-nowrap">หลักฐาน:</p>
                        </div>
                        <div class="col-md-9">
                        @foreach($attachs_file as $files)
                            <p> 
                                @if(isset($files->file))
                                {{  @$files->file_desc  }}
                                <a href="{{url('certify/check/file_ib_client/'.$files->file.'/'.( !empty($files->file_client_name) ? $files->file_client_name :  basename($files->file)  ))}}" target="_blank">
                                    {!! HP::FileExtension($files->file)  ?? '' !!}
                                    {{  !empty($files->file_client_name) ? $files->file_client_name :  @basename($files->file)}}
                                </a>
                                @endif
                            </p>
                        @endforeach
                        </div>
                        </div>
                    @endif


                     <div class="row">
                        <div class="col-md-3 text-right">
                            <p class="text-nowrap">เห็นชอบกับ Scope</p>
                         </div>
                         <div class="col-md-9">
                             <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($details_one->status_scope == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน Scope &nbsp;</label>
                             <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($details_one->status_scope == 2 ) ? 'checked' : ' '  }}>  &nbsp; แก้ไข Scope &nbsp;</label>
                         </div>
                     </div>
                 
                     @if(isset($details_one->remark_scope) && $details_one->status_scope == 2) 
                         <div class="row">
                         <div class="col-md-3 text-right">
                         <p class="text-nowrap">หมายเหตุ</p>
                         </div>
                         <div class="col-md-9">
                            {{ @$details_one->remark_scope ?? ''}}
                         </div>
                         </div>
                     @endif

                     @if(!is_null($item->evidence))
                     @php 
                     $evidence = json_decode($item->evidence);
                     @endphp 
                     <div class="row">
                     <div class="col-md-3 text-right">
                     <p class="text-nowrap">หลักฐาน:</p>
                     </div>
                     <div class="col-md-9">
                     @foreach($evidence as $files)
                         <p> 
                             @if(isset($files->attach_files))
                               {{  @$files->file_desc_text  }}
                                <a href="{{url('certify/check/file_ib_client/'.$files->attach_files.'/'.( !empty($files->file_client_name) ? $files->file_client_name :  basename($files->attach_files)  ))}}" target="_blank">
                                   {!! HP::FileExtension($files->attach_files)  ?? '' !!}
                                   {{   !empty($files->file_client_name) ? $files->file_client_name :  basename($files->attach_files)}}
                               </a>
                             @endif
                         </p>
                     @endforeach
                     </div>
                     </div>
                     @endif

                 
                     @if(!is_null($item->date)) 
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <p class="text-nowrap">วันที่บันทึก</p>
                            </div>
                            <div class="col-md-9">
                                {{ @HP::DateThai($item->date) ?? '-' }}
                            </div>
                        </div>
                     @endif
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


 