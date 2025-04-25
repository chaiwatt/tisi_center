@extends('layouts.master')

@push('css')
<style>
.bg-green {
  background-color: #009999!important;
  color: #fff
}
</style>

@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

 @php
  $config = HP::getConfig(false);
 @endphp

                  <h3 class="box-title">กำหนดมาตรฐาน (สก.)</h3>

                  <div class="row colorbox-group-widget">

                    @can('view-'.str_slug('standardsoffers'))
                      <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('bcertify/standards-offers') }}">
                          <div class="white-box">
                              <div class="media bg-green">
                                  <div class="media-body">
                                      <h3 class="info-count" style="font-size:130%;">ความเห็นการกำหนดมาตรฐาน<br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-comment-processing-outline"></i></span>
                                      </h3>
                                      <p class="info-text font-10">ระบบความเห็นการกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                      </div>
                    @endcan

                    {{-- @can('view-'.str_slug('')) --}}
                      {{-- <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="#">
                          <div class="white-box">
                              <div class="media bg-green">
                                  <div class="media-body">
                                      <h3 class="info-count" style="font-size:130%;">นำเข้ารายชื่อจัดทำมาตรฐาน<br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-view-headline"></i></span>
                                      </h3>
                                      <p class="info-text font-10">ระบบนำเข้ารายชื่อจัดทำมาตรฐาน</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                      </div> --}}
                    {{-- @endcan --}}
                    
                    @can('view-'.str_slug('standarddrafts'))
                      <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('certify/standard-drafts') }}">
                          <div class="white-box">
                              <div class="media bg-green">
                                  <div class="media-body">
                                      <h3 class="info-count" style="font-size:130%;">ร่างแผนการกำหนดมาตรฐาน<br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-receipt"></i></span>
                                      </h3>
                                      <p class="info-text font-10">ระบบร่างแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                      </div>
                    @endcan

                    @can('view-'.str_slug('standardplans'))
                      <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('certify/standard-plans') }}">
                          <div class="white-box">
                              <div class="media bg-green">
                                  <div class="media-body">
                                      <h3 class="info-count" style="font-size:130%;">จัดทำแผนการกำหนดมาตรฐาน<br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-library-books"></i></span>
                                      </h3>
                                      <p class="info-text font-10">ระบบจัดทำแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                      </div>
                    @endcan

                    
                    @can('view-'.str_slug('standardconfirmplans'))
                      <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('certify/standard-confirmplans') }}">
                          <div class="white-box">
                              <div class="media bg-green">
                                  <div class="media-body">
                                      <h3 class="info-count" style="font-size:130%;">พิจารณาแผนการกำหนดมาตรฐาน<br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-mdi-format-list-numbers"></i></span>
                                      </h3>
                                      <p class="info-text font-10">ระบบพิจารณาแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                      </div>
                    @endcan

                    @can('view-'.str_slug('setstandard'))
                    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                      <a href="{{ url('certify/set-standards') }}">
                        <div class="white-box">
                            <div class="media bg-green">
                                <div class="media-body">
                                    <h3 class="info-count" style="font-size:130%;">กำหนดมาตรฐาน<br/>
                                      <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-layers"></i></span>
                                    </h3>
                                    <p class="info-text font-10">ระบบกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                </div>
                            </div>
                        </div>
                      </a>
                    </div>
                  @endcan

                  @can('view-'.str_slug('meetingstandards'))
                  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                    <a href="{{ url('certify/meeting-standards') }}">
                      <div class="white-box">
                          <div class="media bg-green">
                              <div class="media-body">
                                  <h3 class="info-count" style="font-size:130%;">นัดหมายการประชุม<br/>
                                    <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-update"></i></span>
                                  </h3>
                                  <p class="info-text font-10">ระบบนัดหมายการประชุม</p>
                              </div>
                          </div>
                      </div>
                    </a>
                  </div>
                @endcan

                @can('view-'.str_slug('certifystandard'))
                <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                  <a href="{{ url('certify/standards') }}">
                    <div class="white-box">
                        <div class="media bg-green">
                            <div class="media-body">
                                <h3 class="info-count" style="font-size:130%;">จัดทำมาตรฐาน<br/>
                                  <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-file-document"></i></span>
                                </h3>
                                <p class="info-text font-10">ระบบจัดทำมาตรฐานการตรวจสอบและรับรอง</p>
                            </div>
                        </div>
                    </div>
                  </a>
                </div>
              @endcan
              
              @can('view-'.str_slug('gazette'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                <a href="{{ url('certify/gazette') }}">
                  <div class="white-box">
                      <div class="media bg-green">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">ประกาศราชกิจจานุเบกษา<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-chemical-weapon"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบประกาศราชกิจจานุเบกษา</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan


         </div> 

         <h3 class="box-title">รายงานกำหนดมาตรฐาน (สก.)</h3>

         <div class="row colorbox-group-widget">
             @can('view-'.str_slug('report-standard-status'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify/report/standard-status') }}">
                  <div class="white-box">
                      <div class="media bg-dashboard3">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">รายงานการจัดทำมาตรฐาน<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-comment-processing-outline"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบรายงานการจัดทำมาตรฐาน</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

            @can('view-'.str_slug('report-std-certifies'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url($config->url_acc.'std-certifies') }}">
                  <div class="white-box">
                      <div class="media bg-dashboard3">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">รายชื่อมาตรฐานการตรวจสอบและรับรอง<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-library-books"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบรายชื่อมาตรฐานการตรวจสอบและรับรอง</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

        </div>

         <h3 class="box-title">ข้อมูลพื้นฐานการกำหนดมาตรฐาน</h3>

         <div class="row colorbox-group-widget">

             @can('view-'.str_slug('committee'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('committee') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">คณะกรรมการเฉพาะด้าน<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบรายชื่อคณะกรรมการเฉพาะด้าน</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

            @can('view-'.str_slug('registerexperts'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify/register-experts') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">ระบบพิจารณาคำขอผู้เชี่ยวชาญ<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบพิจารณาคำขอผู้เชี่ยวชาญ</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

            @can('view-'.str_slug('expertgroups'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('basic/expert-groups') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">ข้อมูลพื้นฐานความเชี่ยวชาญ<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบข้อมูลพื้นฐานความเชี่ยวชาญ</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan
            
            @can('view-'.str_slug('method'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('basic/method') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">วิธีจัดทำ<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบวิธีจัดทำ</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan
          
            @can('view-'.str_slug('meetingtypes'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/meetingtypes') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">หัวข้อวาระการประชุม<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบหัวข้อวาระการประชุม</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan
        
            @can('view-'.str_slug('standardtypes'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/standardtypes') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">ประเภทมาตรฐานการรับรอง<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบประเภทมาตรฐานการรับรอง</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan
        
            @can('view-'.str_slug('board_type'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('basic/board_type') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">ประเภทของคณะกรรมการ<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบประเภทของคณะกรรมการ</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

            @can('view-'.str_slug('bcertify-reason'))
              <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/reason') }}">
                  <div class="white-box">
                      <div class="media bg-primary">
                          <div class="media-body">
                              <h3 class="info-count" style="font-size:130%;">เหตุผลและความจำเป็น<br/>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-tune"></i></span>
                              </h3>
                              <p class="info-text font-10">ระบบเหตุผลและความจำเป็น</p>
                          </div>
                      </div>
                  </div>
                </a>
              </div>
            @endcan

        </div>



     

            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
@endpush
