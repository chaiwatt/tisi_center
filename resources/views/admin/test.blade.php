
                @if(
                    auth()->user()->can('view-'.str_slug('application-inspectors-accept')) ||
                    auth()->user()->can('view-'.str_slug('application-inspectors-audit')) ||
                    auth()->user()->can('view-'.str_slug('application-inspectors-agreement')) ||
                    auth()->user()->can('view-'.str_slug('manage-inspector'))
                )

                    @php
                        $check_menu = true;

                    //    dd( mb_strlen( 'บันทึกผลการเสนอพิจารณา IB/CB', 'UTF-8' ) )
                    @endphp
                    <hr>
                    <h3 class="box-title">ผู้ตรวจ/ผู้ประเมิน</h3>
                    <div class="row colorbox-group-widget">

                        @can('view-'.str_slug('application-inspectors-accept'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('section5/application_inspectors_accept') }}">
                                <div class="white-box">
                                    <div class="media bg-success">
                                        <div class="media-body">
                                            <h3 class="info-count">ตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมิน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-account-multiple"></i></span>
                                            </h3>
                                            <p class="info-text font-12"> ระบบตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('application-inspectors-audit'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('section5/application-inspectors-audit') }}">
                                <div class="white-box">
                                    <div class="media bg-success">
                                        <div class="media-body">
                                            <h3 class="info-count">อนุมัติผลตรวจประเมิน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                            </h3>
                                            <p class="info-text font-12"> ระบบอนุมัติผลตรวจประเมิน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('application-inspectors-agreement'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application-inspectors-agreement') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h4 class="info-count2">ขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน<br/>
                                                    <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                                </h4>
                                                <p class="info-text font-12"> ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="row colorbox-group-widget">
                        @can('view-'.str_slug('manage-inspector'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/inspectors') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count">รายชื่อผู้ตรวจ/ผู้ประเมิน <br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> รายชื่อผู้ตรวจ/ผู้ประเมิน </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan
                    </div>
                @else
                    <hr>
                    <div class="alert alert-info"> <i class="fa fa-info-circle"></i> คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ </div>
                @endif

                @if(
                    auth()->user()->can('view-'.str_slug('accept-inspection-unit')) ||
                    auth()->user()->can('view-'.str_slug('application-ibcb-accept')) ||
                    auth()->user()->can('view-'.str_slug('application-ibcb-audit')) ||
                    auth()->user()->can('view-'.str_slug('application-ibcb-approve')) ||
                    auth()->user()->can('view-'.str_slug('manage-ibcb')) 
                )
                    @php
                        $check_menu = true;
                    @endphp
                    <hr>
                    <h3 class="box-title">IB/CB</h3>
                    <div class="row colorbox-group-widget">
                        
                        {{-- @can('view-'.str_slug('accept-inspection-unit'))
                          <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('section5/accept-inspection-unit') }}">
                              <div class="white-box">
                                  <div class="media bg-success">
                                      <div class="media-body">
                                          <h3 class="info-count">รับคำขอเป็นหน่วยตรวจสอบ (IB)<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-human-handsup"></i></span>
                                          </h3>
                                          <p class="info-text font-12"> ระบบรับคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (IB) </p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                          </div>
                        @endcan --}}

                        @can('view-'.str_slug('application-ibcb-accept'))
                          <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('section5/application_ibcb_accept') }}">
                              <div class="white-box">
                                  <div class="media bg-success">
                                      <div class="media-body">
                                          <h3 class="info-count">ระบบรับคำขอเป็น IB/CB<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-human-handsup"></i></span>
                                          </h3>
                                          <p class="info-text font-12"> ระบบรับคำขอเป็น IB/CB </p>
                                      </div>
                                  </div>
                              </div>
                            </a>
                          </div>
                        @endcan

                        @can('view-'.str_slug('application-ibcb-audit'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application-ibcb-audit') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count">บันทึกผลตรวจประเมิน IB/CB<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-human-handsup"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ระบบรับันทึกผลตรวจประเมิน IB/CB</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('application-ibcb-approve'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application-ibcb-board-approve') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count2">บันทึกผลการเสนอพิจารณา IB/CB<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-file-document"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ระบบผลการเสนอพิจารณาอนุมัติ IB/CB </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('manage-ibcb'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/ibcb') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count">รายชื่อหน่วยตรวจสอบ IB/CB<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> รายชื่อหน่วยตรวจสอบ IB/CB </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                    </div>
                @else
                    <hr>
                    <div class="alert alert-info"> <i class="fa fa-info-circle"></i> คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ </div>
                @endif

                @if (

                    auth()->user()->can('view-'.str_slug('application-lab-accept')) || 
                    auth()->user()->can('view-'.str_slug('application-lab-audit')) || 
                    auth()->user()->can('view-'.str_slug('application-lab-approve')) ||
                    auth()->user()->can('view-'.str_slug('manage-lab'))

                )
                    @php
                        $check_menu = true;
                    @endphp
                    <hr>
                    <h3 class="box-title">LAB</h3>
                    <div class="row colorbox-group-widget">

                        @can('view-'.str_slug('application-lab-accept'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application_lab_accept') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count">รับคำขอเป็นผู้ตรวจสอบ (LAB)<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-human-handsup"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ระบบรับคำขอรับการแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (LAB) </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('application-lab-audit'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application_lab_audit') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count2">ตรวจประเมินหน่วยตรวจสอบ (LAB)<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-magnify"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('application-lab-approve'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/application-lab-board-approve') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count2">บันทึกผลการเสนอพิจารณา (LAB)<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-file-document"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ระบบผลการเสนอพิจารณาอนุมัติ (LAB) </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('view-'.str_slug('manage-lab'))
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{{ url('section5/labs') }}">
                                    <div class="white-box">
                                        <div class="media bg-success">
                                            <div class="media-body">
                                                <h3 class="info-count">รายชื่อหน่วยตรวจสอบ (LAB)<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                                </h3>
                                                <p class="info-text font-12"> ระบบรายชื่อหน่วยตรวจสอบ (LAB) </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                    </div>

                @else
                    <hr>
                    <div class="alert alert-info"> <i class="fa fa-info-circle"></i> คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ </div>
                @endif

                @if(auth()->user()->can('view-'.str_slug('branchgroup'))  ||
                    auth()->user()->can('view-'.str_slug('branch'))  ||
                    auth()->user()->can('view-'.str_slug('testmethod'))  ||
                    auth()->user()->can('view-'.str_slug('testtools'))  ||
                    auth()->user()->can('view-'.str_slug('bsection5-unit')) ||
                    auth()->user()->can('view-'.str_slug('bsection5-testitem')) ||
                    auth()->user()->can('view-'.str_slug('bsection5-standard'))
                )
                    <hr>
                    <h3 class="box-title">ข้อมูลพื้นฐาน</h3>
                    <div class="row colorbox-group-widget">

                        @can('view-'.str_slug('branchgroup'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('basic/branch-groups') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count">หมวดสาขา/สาขา<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-checkbox-multiple-blank"></i></i></span>
                                        </h3>
                                        <p class="info-text font-12"> ข้อมูลหมวดสาขา/สาขา </p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('branch'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('basic/branches') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count">รายสาขา<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-layers"></i></span>
                                        </h3>
                                        <p class="info-text font-12"> ข้อมูลรายสาขา </p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('testmethod'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/test_method') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count">วิธีทดสอบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-file-document"></i></span>
                                        </h3>
                                        <p class="info-text font-12"> ข้อมูลวิธีทดสอบ </p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('testtools'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/test_tools') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count">เครื่องมือทดสอบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-wrench"></i></i></span>
                                        </h3>
                                        <p class="info-text font-12"> ข้อมูลเครื่องมือทดสอบ </p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('bsection5-unit'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/basic/unit') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count"> หน่วย <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-ruler"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลหน่วย</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('bsection5-testitem'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/test_item') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count"> รายการทดสอบ <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลรายการทดสอบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('bsection5-workgroup'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/workgroup') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count"> กลุ่มงาน LAB <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลกลุ่มงาน</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('bsection5-standard'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bsection5/standards') }}">
                            <div class="white-box">
                                <div class="media bg-green">
                                    <div class="media-body">
                                        <h3 class="info-count"> มาตรฐานรับรองระบบงาน <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลมาตรฐานรับรองระบบงาน</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    </div>
                @else
                    <hr>
                    <div class="alert alert-info"> <i class="fa fa-info-circle"></i> คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ </div>
                @endif