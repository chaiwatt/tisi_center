@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานติดตามใบรับรอง</h3>
                    @can('view-'.str_slug('cerreport-epayments'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">ชื่อผู้ประกอบการ : </p>
                          <p class="col-md-9"> {!! !empty($tracking->sso_user_to->name)?  $tracking->sso_user_to->name:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">เลขประจำตัวผู้เสียภาษี : </p>
                          <p class="col-md-9"> {!! !empty($tracking->sso_user_to->tax_number)?  $tracking->sso_user_to->tax_number:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">ที่ตั้งสำนักงานใหญ่ : </p>
                          <p class="col-md-9"> {!! !empty($tracking->sso_user_to->FormatAddress)?  $tracking->sso_user_to->FormatAddress:''  !!} </p>
                        </div>
                    </div>
                    
                        <div class="clearfix"></div>
                      <div class="table-responsive">
                          <table class="table  table-bordered color-bordered-table info-bordered-table"   width="100%">
                              <thead>
                                      <tr>
                                          <th class="text-center " width="2%">ลำดับ</th>
                                          <th class="text-center " width="10%">เลขอ้างอิง</th>
                                          <th class="text-center " width="20%">วันที่เลขอ้างอิง</th>
                                          <th class="text-center " width="20%">เจ้าหน้าที่รับผิดชอบ</th>
                                          <th class="text-center " width="20%">วันที่ครบกำหนด</th>
                                          <th class="text-center " width="10%">ขอบข่าย</th>
                                      </tr>
                              </thead> 
                              <tbody>
                                  @if (count($trackings) > 0)
                                    @foreach ($trackings as  $key => $item) 
                                    <tr>
                                        <td class="text-center">{!! ($key+1) !!}</td>
                                        <td> {{ $item->reference_refno ?? '-'}} </td>
                                        <td> {{ HP::DateTimeThai($item->reference_date) ?? '-'}} </td>
                                        <td> {{  (!empty($item->AssignName) ?  implode(',',$item->AssignName) : '')   }} </td>
                                        <td> {{  (!empty($item->tracking_report_to->end_date) ?  HP::DateThai($item->tracking_report_to->end_date) : '')   }} </td>
                                        <td class="text-center">  
                                            @if(!empty($item->tracking_report_to->FileAttachFileLoaTo))
                                            @php
                                                $report =  $item->tracking_report_to->FileAttachFileLoaTo;
                                            @endphp
          
                                                  <a href="{{url('funtions/get-view/'.$report->url.'/'.( !empty($report->filename) ? $report->filename :  basename($report->url)  ))}}" 
                                                      title="{{  !empty($report->filename) ? $report->filename : basename($report->url) }}" target="_blank">
                                                      {!! HP::FileExtension($report->url)  ?? '' !!}
                                                  </a> 
                                        
                                            @else 
                                              -
                                            @endif
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

@endsection
