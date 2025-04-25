

<div class="row">
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">เลขที่ใบอนุญาต :</label>
            <div class="col-md-9">
                <p class="form-control-static text-left"> {!! !empty($query->tbl_licenseNo)?$query->tbl_licenseNo:null !!} </p>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group m-0">
            <label class="control-label col-md-3">ชื่อผู้ประกอบการ :</label>
            <div class="col-md-9">
                <p class="form-control-static text-left"> {!! (!empty($query->tbl_tradeName)?$query->tbl_tradeName:null).(!empty($query->tbl_taxpayer)?' ('.$query->tbl_taxpayer.') ':null) !!} </p>
            </div>
        </div>
    </div>
</div>

<!-- ยกเลิก -->
@if( !empty($query->license_cancel) )
    @php
        $license_cancel = $query->license_cancel;
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-0">
                <label class="control-label col-md-3">วันที่แจ้งยกเลิก :</label>
                <div class="col-md-9">
                    <p class="form-control-static text-left"> {!! !empty($license_cancel->tbl_cancelDate)?HP::DateThai($license_cancel->tbl_cancelDate):null !!} </p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group m-0">
                <label class="control-label col-md-3">เหตุผลที่ยกเลิก :</label>
                <div class="col-md-9">
                    <p class="form-control-static text-left"> {!! !empty($license_cancel->cancel_reason)?$license_cancel->cancel_reason->reason:null !!} </p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group m-0">
                <label class="control-label col-md-3">หมายเหตุ :</label>
                <div class="col-md-9">
                    <p class="form-control-static text-left"> {!! !empty($license_cancel->tbl_reason)?$license_cancel->tbl_reason:null !!}  </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- พักใช้ -->
@if($query->Is_pause == 1 || count($query->license_pause_list) > 0)
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped" id="myTableHistory">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th class="text-center" width="15%">เลขคดี</th>
                        <th class="text-center" width="25%">วันที่พักใบอนุญาต</th>
                        <th class="text-center" width="25%">วันที่ยกเลิกพักใบอนุญาต</th>
                        <th class="text-center" width="25%">หมายเหตุ (ถ้ามี)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @if($query->Is_pause == 1)
                        <tr>
                            <tr>
                                <td class="text-center text-top">{!! ++$i !!}</td>
                                <td class="text-center text-top">-</td>
                                <td class="text-center text-top">{!! (!empty($query->date_pause_start)?HP::DateThai($query->date_pause_start):null).(!empty($query->date_pause_end)?' ถึง '.HP::DateThai($query->date_pause_end):null) !!} <div>(NSW)</div></td>
                                <td class="text-center text-top">-</td>
                                <td class="text-left text-top">-</td>
                            </tr>
                        </tr>
                    @endif
                    @isset( $query->license_pause_list )

                        @foreach ( $query->license_pause_list as $key => $item )

                            <tr>
                                <td class="text-center text-top">{!! ++$i !!}</td>
                                <td class="text-center text-top">{!! !empty($item->case_number)?$item->case_number:null !!}</td>
                                <td class="text-center text-top">{!! (!empty($item->date_pause_start)?HP::DateThai($item->date_pause_start):null).(!empty($item->date_pause_end)?' ถึง '.HP::DateThai($item->date_pause_end):null) !!} <div>(Law)</div></td>
                                <td class="text-center text-top">{!! !empty($item->date_pause_cancel)?HP::DateThai($item->date_pause_cancel):null !!}</td>
                                <td class="text-left text-top">{!! !empty($item->remark)?$item->remark:'-' !!}</td>
                            </tr>
                            
                        @endforeach
                        
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
@endif