<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-pills m-b-30 ">
                    <li class="{{ is_null(Request::get('tab_active')) ? 'active' : '' }}">
                        <a href="#navpills-infomation" data-toggle="tab" aria-expanded="false">ข้อมูลที่อยู่</a>
                    </li>
                    <li class="{{ Request::get('tab_active')=='2' ? 'active' : '' }}">
                        <a href="#navpills-scope" data-toggle="tab" aria-expanded="false">ขอบข่ายที่ตรวจสอบได้</a>
                    </li>
                    <li class="">
                        <a href="#navpills-std" data-toggle="tab" aria-expanded="false">มอก. ที่ตรวจสอบได้</a>
                    </li>
                    <li class="">
                        <a href="#navpills-inspestor" data-toggle="tab" aria-expanded="false">ข้อมูลผู้ตรวจ/ผู้ประเมิน</a>
                    </li>
                    <li class="">
                        <a href="#navpills-certify" data-toggle="tab" aria-expanded="false">ข้อมูลใบรับรอง</a>
                    </li>
                    <li class="">
                        <a href="#navpills-government_gazette" data-toggle="tab" aria-expanded="false">ข้อมูลเอกสารประกาศราชกิจจาฯ</a>
                    </li>
                    <li class="">
                        <a href="#navpills-history" data-toggle="tab" aria-expanded="false">ประวัติการแก้ไขข้อมูล</a>
                    </li>
                </ul>

                <div class="tab-content br-n pn">
                    <div id="navpills-infomation" class="tab-pane {{ is_null(Request::get('tab_active')) ? 'active' : '' }}">
                            <div class="row">

                            <div class="col-md-12">

                                <fieldset class="white-box">
                                    <div class="row"  style="margin-top:5px;">
                                        <div class="col-md-10 col-sm-12">
                                            <h2 style="padding-left:25px">ที่อยู่</h2>
                                        </div>
                                    </div>
                                    <hr>
    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ที่อยู่ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_address)?$ibcb->ibcb_address:' - ') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">หมู่ที่ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_moo)?$ibcb->ibcb_moo:' - ') !!}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">หมู่บ้าน/อาคาร :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_building)?$ibcb->ibcb_building:' - ') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ตรอก/ซอย :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_soi)?$ibcb->ibcb_soi:' - ') !!}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ถนน :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_road)?$ibcb->ibcb_road:' - ') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ตำบล/แขวง :</span></p>
                                        </div>
                                         <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->IbcbSubdistrictName)?$ibcb->IbcbSubdistrictName:' - ') !!}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">อำเภอ/เขต :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->IbcbDistrictName)?$ibcb->IbcbDistrictName:' - ') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">จังหวัด :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->IbcbProvinceName)?$ibcb->IbcbProvinceName:' - ') !!}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">รหัสไปรษณีย์ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_zipcode)?$ibcb->ibcb_zipcode:' - ') !!}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">โทรศัพท์ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_phone)?$ibcb->ibcb_phone: '-') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">โทรสาร :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->ibcb_fax)?$ibcb->ibcb_fax: '-') !!}</span></p>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ปรับปรุงล่าสุดโดย :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->updated_by)?$ibcb->UpdatedName:(!empty($ibcb->created_by)?$ibcb->CreatedName:'-')) !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ปรับปรุงล่าสุดเมื่อ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->updated_at)?HP::DateThaiFull($ibcb->updated_at):(!empty($ibcb->created_at)?HP::DateThaiFull($ibcb->created_at):'-') ) !!}</span></p>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset class="white-box">
                                    <div class="row" style="margin-top:5px;">
                                        <div class="col-md-10 col-sm-12">
                                            <h2 style="padding-left:25px">ผู้ประสานงาน</h2>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ชื่อผู้ประสานงาน :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_name)?$ibcb->co_name:' - ') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">ตำแหน่งผู้ประสานงาน :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_position)?$ibcb->co_position:' - ') !!}</span></p>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">โทรศัพท์มือถือ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_mobile)?$ibcb->co_mobile: '-') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">โทรศัพท์ :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_phone)?$ibcb->co_phone: '-') !!}</span></p>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">โทรสาร :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_fax)?$ibcb->co_fax: '-') !!}</span></p>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <p class="text-right"><span class="text-bold-400">อีเมล :</span></p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <p class=""><span class="text-bold-400">{!! (!empty($ibcb->co_email)?$ibcb->co_email: '-') !!}</span></p>
                                        </div>
                                    </div>

                                </fieldset>

                            </div>
                        </div>
                    </div>
                    <div id="navpills-scope" class="tab-pane {{ Request::get('tab_active')=='2' ? 'active' : '' }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box_scope">
                                    @include ('section5.manage-ibcb.show.show-scope')
                                </div>
                                @include('section5.manage-ibcb.scopes.plus-scope')
                                @include('section5.manage-ibcb.scopes.minus-scope')

                            </div>
                        </div>
                    </div>
                    <div id="navpills-std" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box_std">
                                    @include ('section5.manage-ibcb.show.show-std')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-inspestor" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="box_inspestor">
                                    @include ('section5.manage-ibcb.show.show-inspestor')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="navpills-certify" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="box_certify">
                                    @include ('section5.manage-ibcb.show.show-certify')
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="navpills-government_gazette" class="tab-pane">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-12  col-sm-12">
                                @can('edit-'.str_slug('manage-ibcb'))
                                    <button type="button" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#MdGazette" @if( !isset($ibcb->id) ) disabled @endif><i class="fa fa-plus"></i> เพิ่ม</button>
                                @endcan
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @include ('section5.manage-ibcb.modals.modal-gazette')

                                @include ('section5.manage-ibcb.show.show-government-gazette')
                            </div>
                        </div>
                    </div>
                    <div id="navpills-history" class="tab-pane">
                        <div class="row">
                            <div class="col-md-12">
                                @include ('section5.manage-ibcb.show.show-history')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>