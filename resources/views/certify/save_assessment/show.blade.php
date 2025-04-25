@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกผลการตรวจประเมิน #{{ $notice->id }}</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/save_assessment') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
 @if(count($notice->CertificateHistorys) > 0)
  <div class="row">
      <div class="col-md-12">
           <div class="white-box" style="border: 2px solid #e5ebec;">
          <legend><h4>ประวัติบันทึกผลการตรวจประเมิน</h4></legend>


                      @foreach($notice->CertificateHistorys as $key => $item)    
                      <div class="row">
                          <div class="col-md-12">
                              <div class="panel block4">
                                  <div class="panel-group" id="accordion{{ $key +1 }}">
                                    <div class="panel panel-info">
                                      <div class="panel-heading">
                                          <h4 class="panel-title">
                                              <a data-toggle="collapse" data-parent="#accordion{{ $key +1 }}" href="#collapse{{ $key +1 }}"> <dd> ประวัติบันทึกผลการตรวจประเมิน ครั้งที่ {{ $key +1}}</dd>  </a>
                                          </h4>
                                          </div>

                      <div id="collapse{{ $key +1 }}" class="panel-collapse collapse {{ (count($notice->CertificateHistorys) == $key +1 ) ? 'in' : ' '  }}">
                      <br>

                          <div class="row form-group">
                                <div class="col-md-12">
                                  {{-- <div class="white-box" style="border: 2px solid #e5ebec;"> --}}

                                    
                                      <div class="container-fluid">
                                        
                                        <div class="row form-group">
                                          <div class="col-md-6">
                                              <label class="col-md-4 text-right"> เลขคำขอ : </label>
                                              <div class="col-md-8">
                                                   {!! Form::text('app_no',$app->app_no ??  null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <label class="col-md-4 text-right">หน่วยงาน : </label>
                                              <div class="col-md-8">
                                                  {!! Form::text('name',$app->name ??   null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                              </div>
                                          </div>
                                      </div>
                                      
                                      <div class="row form-group">
                                          <div class="col-md-6">
                                              <label class="col-md-4 text-right"> ชื่อห้องปฏิบัติการ : </label>
                                              <div class="col-md-8">
                                                   {!! Form::text('lab_name', $app->lab_name ??  null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <label class="col-md-4 text-right">วันที่ทำรายงาน : </label>
                                              <div class="col-md-8">
                                                  {!! Form::text('assessment_date', $app->assessment_date ??   null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                              </div>
                                          </div>
                                      </div>

                                      <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="col-md-4 text-right"> คณะผู้ตรวจประเมิน : </label>
                                            <div class="col-md-8">
                                                 {!! Form::text('DataGroupeTitle', $app->DataGroupeTitle ??   null,  ['class' => 'form-control', 'id'=>'appDepart','disabled'=>true])!!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-md-4 text-right"> รายงานการตรวจประเมิน : </label>
                                            <div class="col-md-8">
                                                @if(!is_null($item) && !is_null($item->file) ) 
                                                   <p> 
                                                    <a href="{{ url('certify/check/files/'.$item->file) }}" title=" {{basename($item->file)}}" target="_blank"> 
                                                         @php
                                                             $type = strrchr(basename($item->file),".");
                                                         @endphp
                                                         @if($type == ".pdf") 
                                                         <i class="fa fa-file-pdf-o" style="font-size:20px; color:red" aria-hidden="true"></i>
                                                         @else 
                                                         <i  class="fa fa-file-word-o"  style="font-size:20px; color:#0000ff" aria-hidden="true"></i>
                                                         @endif
                                                      </a>
                                                 </p>
                                                @endif
                                            </div>
                                           </div>
                                        </div>

                                      </div>    

                                    @if(!is_null($item->details_table) && !is_null($item->details)) 
                                       @php 
                                       $details = json_decode($item->details);
                                       $details_table = json_decode($item->details_table);
                          
                                      @endphp
                                      @if($details->step == 2)
                                      <div class="row">
                                      <div class="col-sm-12 m-t-15" v-if="isTable">
                                          <table class="table color-bordered-table primary-bordered-table">
                                              <thead>
                                              <tr>
                                                  <th class="text-center" width="2%">ลำดับ</th>
                                                  <th class="text-center" width="20%">ผลการประเมินที่พบ</th>
                                                  <th class="text-center" width="10%">ประเภท</th>
                                                  <th class="text-center" width="20%" >แนวทางการแก้ไข</th>
                                                  @if($key > 0) <!-- key หลัก -->
                                                  <th class="text-center" width="28%">ผลการประเมิน</th>
                                                   @endif 
                                              </tr>
                                              </thead>
                                              <tbody >
                                                @foreach($details_table as $key1 => $item1)   
                                                    @php 
                                                      $type =   ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต'];
                                                  @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            {{$key1+1}}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('notice[]', $item1->remark ?? null,  ['class' => 'form-control notice','disabled'=>true])!!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('type[]',  $type[$item1->type] ?? null,  ['class' => 'form-control','disabled'=>true])!!}
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                     {!! Form::text('details[]', $item1->details ?? null,  ['class' => 'form-control','disabled'=>true])!!}
                                                                </div>
                                                                <div class="col-md-2">
                                                                   @if(!is_null($item1->attachs))
                                                                   <a href="{{ url('certify/check/files/'.$item1->attachs) }}" title=" {{basename($item1->attachs)}}" target="_blank"> 
                                                                    @php
                                                                        $type = strrchr(basename($item1->attachs),".");
                                                                    @endphp
                                                                    @if($type == ".pdf") 
                                                                    <i class="fa fa-file-pdf-o" style="font-size:20px; color:red" aria-hidden="true"></i>
                                                                    @else 
                                                                    <i  class="fa fa-file-word-o"  style="font-size:20px; color:#0000ff" aria-hidden="true"></i>
                                                                    @endif
                                                                   </a>
                                                                @endif
                                                                </div>
                                                            </div>
                                                         
                                                        </td>
                                                        @if($key > 0)   <!-- key หลัก -->
                                                        <td >
                                                           @if(isset($item1->status))
                                                              <div class="row">
                                                                <label class="col-md-12">   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item1->status == 1 ) ? 'checked' : ' '  }}>  &nbsp;ผ่าน &nbsp;</label>
                                                                <label class="col-md-12">   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item1->status == 2 ) ? 'checked' : ' '  }}> 
                                                                   &nbsp;ไม่ผ่าน &nbsp;
                                                                   {{  @': '.$item1->comment ?? '' }}
                                                                </label>
                                                              </div>
                                                            @endif 
                                                          </td>
                                                          @endif 
                                                      </tr> 
                                                @endforeach
                                              </tbody>
                                          </table>
                                      </div>
                                      </div>
                                      @endif   

                                        @if($details->step == 4)
                                        <div class="form-group">
                                          <div class="col-md-6">
                                              <label class="col-md-5 text-right"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                                              <div class="col-md-7">
                                                  <div class="row">
                                                      <label class="col-md-6">
                                                          {!! Form::radio('report_status', '1',  true , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green','required'=>'required']) !!}  มี
                                                      </label>
                                                      <label class="col-md-6">
                                                          {!! Form::radio('report_status', '2',false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','required'=>'required']) !!} ไม่มี
                                                      </label>
                                                  </div>
                                              </div>
                                          </div> 
                                      </div>
                                        @endif   

                                     @endif    
                                  {{-- </div>     --}}
                              </div>  
                          </div>

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      @endforeach

           </div>
     </div>
 </div>
  @endif
  <div class="clearfix"></div>
  <a  href="{{ url("$previousUrl") }}"  class="btn btn-default btn-lg btn-block">
    <i class="icon-arrow-left-circle" aria-hidden="true"></i> <b>กลับ</b> 
  </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script>

    jQuery(document).ready(function() {
 
        $('.check-readonly').prop('disabled', true);//checkbox ความคิดเห็น
        $('.check-readonly').parent().removeClass('disabled');
        $('.check-readonly').parent().css('margin-top', '8px');//checkbox ความคิดเห็น
 
    });
   </script>
@endpush
