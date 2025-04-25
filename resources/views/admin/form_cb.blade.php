<div class="row colorbox-group-widget">

    @can('view-'.str_slug('checkcertificatecb'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/check_certificate-cb') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">คำขอรับบริการ(CB)<br/>
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
  @can('view-'.str_slug('estimatedcostcb'))
  <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
      <a href="{{ url('certify/estimated_cost-cb') }}">
          <div class="white-box">
              <div class="media bg-dashboard5">
                  <div class="media-body">
                      <h3 class="info-count">ประมาณค่าใช้จ่าย(CB)<br/>
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
@can('view-'.str_slug('auditorcb'))
<div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
  <a href="{{ url('/certify/auditor-cb') }}">
    <div class="white-box">
        <div class="media bg-dashboard5">
            <div class="media-body">
                 <h3   class="info-count" style="font-size:25px;">แต่งตั้งคณะผู้ตรวจฯ(CB)<br/>
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

 

    @can('view-'.str_slug('saveassessmentcb'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/save_assessment-cb') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ผลการประเมิน(CB)<br/>
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
    @can('view-'.str_slug('certificateexportcb'))
    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
        <a href="{{ url('certify/certificate-export-cb') }}">
            <div class="white-box">
                <div class="media bg-dashboard5">
                    <div class="media-body">
                        <h3 class="info-count">ออกใบรับรอง(CB)<br/>
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