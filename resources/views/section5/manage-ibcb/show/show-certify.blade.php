
@php
    $ibcbs_certify =  $ibcb->ibcbs_certify;
@endphp

<div class="col-md-12 col-sm-12">
  
    <table width="100%" class="table table-bordered table-striped">
    
        <thead>
            <tr>
                <th class="text-center" width="5%">ลำดับ</th>
                <th class="text-center" width="40%">ใบรับรองเลขที่</th>
                <th class="text-center" width="20%">วันที่ออก</th>
                <th class="text-center" width="20%">วันที่สิ้นสุด</th>
                <th class="text-center" width="15%">มอก.</th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="5"></td>
            </tr>
        </tfoot>

        <tbody>
            @foreach ( $ibcbs_certify as $key=> $certify )

                @php
                    $cer_std =  $certify->tis_standard;

                    $certificate_ref = '';        
                    if( !empty($certify->certificate_table) && $certify->certificate_table == ((new App\Models\Certify\ApplicantCB\CertiCBExport)->getTable()) ){
                        $cer =  $certify->certify_cb_export;
                        $certificate_ref = !empty($cer->app_no)?$cer->app_no:null;

                    }else if( !empty($certify->certificate_table) && $certify->certificate_table == ((new App\Models\Certify\ApplicantIB\CertiIBExport)->getTable()) ){
                        $cer =  $certify->certify_ib_export;
                        $certificate_ref = !empty($cer->app_no)?$cer->app_no:null;
                    }

                @endphp
                <tr>
                    <td class="text-center">{!!$key+1 !!}</td>
                    <td class="text-left">
                        {!! $certify->certificate_no !!}
                        <div>{!! !empty($certificate_ref)?('<em>( Ref: '.($certificate_ref).')</em>'):null; !!}</div>
                    </td>
                    <td class="text-center">
                        {!! !empty($certify->certificate_start_date)?HP::revertDate($certify->certificate_start_date,true):'-' !!}
                    </td>
                    <td class="text-center">
                        {!! !empty($certify->certificate_end_date)?HP::revertDate($certify->certificate_end_date,true):'-' !!}
                    </td>
                    <td class="text-center">
                        {!! !empty( $cer_std->title )?$cer_std->title:'-' !!}
                    </td>
                </tr>

            @endforeach
        </tbody>

    </table>

</div>