<div class="row colorbox-group-widget">

    @can('view-'.str_slug('check_certificate'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/check_certificate') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">คำขอรับบริการ<br/>
                            <span class="pull-right" style="font-size:45px;">
                        <i class="mdi mdi-human-handsup"></i>
                      </span>
                        </h3>
                        <p class="info-text font-12">ตรวจสอบคำขอรับบริการ</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
  @endcan

    @can('view-'.str_slug('estimated_cost'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/estimated_cost') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ประมาณค่าใช้จ่าย<br/>
                            <span class="pull-right" style="font-size:45px;">
                        <i class="mdi mdi-cash"></i>
                      </span>
                        </h3>
                        <p class="info-text font-12">ระบบประมาณค่าใช้จ่าย</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
  @endcan

  @can('view-'.str_slug('board_auditor'))
  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
    <a href="{{ url('certify/auditor') }}">
      <div class="white-box">
          <div class="media bg-dashboard5">
              <div class="media-body">
                  <h3 class="info-count">แต่งตั้งคณะผู้ตรวจฯ<br/>
                    <span class="pull-right" style="font-size:45px;">
                      <i class="mdi mdi-incognito"></i>
                    </span>
                  </h3>
                  <p class="info-text font-12">แต่งตั้งคณะผู้ตรวจประเมิน</p>
              </div>
          </div>
      </div>
    </a>
  </div>
@endcan
{{-- </div> --}}

{{-- <div class="row colorbox-group-widget"> --}}

{{-- @can('view-'.str_slug('check_assessment'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
    <a href="{{ url('certify/check_assessment') }}">
        <div class="white-box">
            <div class="media bg-dashboard5">
                <div class="media-body">
                    <h3 class="info-count">ตรวจประเมิน<br/>
                        <span class="pull-right" style="font-size:45px;">
                    <i class="mdi mdi-check-circle"></i>
                  </span>
                    </h3>
                    <p class="info-text font-12">ระบบตรวจประเมิน</p>
                </div>
            </div>
        </div>
    </a>
</div>
@endcan --}}

@can('view-'.str_slug('save_assessment'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
  <a href="{{ url('certify/save_assessment') }}">
      <div class="white-box">
          <div class="media bg-dashboard5">
              <div class="media-body">
                  <h3 class="info-count">ผลการประเมิน<br/>
                      <span class="pull-right" style="font-size:45px;">
                  <i class="mdi mdi-alert"></i>
                </span>
                  </h3>
                  <p class="info-text font-12">ระบบผลการประเมิน</p>
              </div>
          </div>
      </div>
  </a>
</div>
@endcan

@can('view-'.str_slug('certificate-export'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
<a href="{{ url('certify/certificate-export-lab') }}">
    <div class="white-box">
        <div class="media bg-dashboard5">
            <div class="media-body">
                <h3 class="info-count">ออกใบรับรองระบบงาน<br/>
                    <span class="pull-right" style="font-size:45px;">
                <i class="mdi mdi-export"></i>
              </span>
                </h3>
                <p class="info-text font-12">การออกใบรับรองระบบงาน</p>
            </div>
        </div>
    </div>
</a>
</div>
@endcan

@can('view-'.str_slug('committee'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
  <a href="{{ url('committee') }}">
    <div class="white-box">
        <div class="media bg-dashboard5">
            <div class="media-body">
                <h3 class="info-count">คณะกรรมการ<br/>
                  <span class="pull-right" style="font-size:45px;">
                    <i class="mdi mdi-account-multiple-outline"></i>
                  </span>
                </h3>
                <p class="info-text font-12">คณะกรรมการเฉพาะด้าน</p>
            </div>
        </div>
    </div>
  </a>
</div>
@endcan


{{-- @can('view-'.str_slug('certificate'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
  <a href="{{ url('certificate') }}">
    <div class="white-box">
        <div class="media bg-dashboard5">
            <div class="media-body">
                <h3 class="info-count">ใบรับรองระบบงาน<br/>
                  <span class="pull-right" style="font-size:45px;">
                    <i class="mdi mdi-certificate"></i>
                  </span>
                </h3>
                <p class="info-text font-12">ข้อมูลใบรับรองระบบงาน</p>
            </div>
        </div>
    </div>
  </a>
</div>
@endcan --}}


  @can('view-'.str_slug('board_review'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
      <a href="{{ url('certify/board_review') }}">
        <div class="white-box">
            <div class="media bg-dashboard5">
                <div class="media-body">
                    <h3 class="info-count">แต่งตั้งคณะทบทวนฯ<br/>
                      <span class="pull-right" style="font-size:45px;">
                        <i class="mdi mdi-worker"></i>
                      </span>
                    </h3>
                    <p class="info-text font-12">แต่งตั้งคณะทบทวนผลการตรวจประเมิน</p>
                </div>
            </div>
        </div>
      </a>
    </div>
  @endcan

  @can('view-'.str_slug('board_auditor'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/alert/check/expire/date') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">แจ้งเตือนข้อมูลใบรับรอง<br/>
                            <span class="pull-right" style="font-size:45px;">
                        <i class="mdi mdi-incognito"></i>
                      </span>
                        </h3>
                        <p class="info-text font-12">ระบบแจ้งเตือนข้อมูลใบรับรอง</p>
                    </div>
                </div>
            </div>
        </a>
    </div>
  @endcan

  @can('view-'.str_slug('summary_request_service'))
  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
      <a href="{{ url('certify/request-service/list') }}">
          <div class="white-box">
              <div class="media bg-dashboard5">
                  <div class="media-body">
                      <h3 class="info-count">สรุปคำขอรับบริการ<br/>
                          <span class="pull-right" style="font-size:45px;">
                      <i class="mdi mdi-bookmark"></i>
                    </span>
                      </h3>
                      <p class="info-text font-12">รายงานข้อมูลสรุปคำขอรับบริการ</p>
                  </div>
              </div>
          </div>
      </a>
  </div>
@endcan
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">  </div>
</div>