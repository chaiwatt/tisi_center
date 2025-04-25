<div class="row colorbox-group-widget">

    @can('view-'.str_slug('checkcertificateib'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/check_certificate-ib') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">คำขอรับบริการ(IB)<br/>
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

    @can('view-'.str_slug('estimatedcostib'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/estimated_cost-ib') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ประมาณค่าใช้จ่าย(IB)<br/>
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

  @can('view-'.str_slug('auditorib'))
  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
    <a href="{{ url('/certify/auditor-ib') }}">
      <div class="white-box">
          <div class="media bg-dashboard5">
              <div class="media-body">
                  <h3 class="info-count">แต่งตั้งคณะผู้ตรวจฯ(IB)<br/>
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


    @can('view-'.str_slug('saveassessmentib'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/save_assessment-ib') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ผลการประเมิน(IB)<br/>
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
    @can('view-'.str_slug('certificateexportib'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/certificate-export-ib') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ออกใบรับรอง(IB)<br/>
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
  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">  </div>
</div>