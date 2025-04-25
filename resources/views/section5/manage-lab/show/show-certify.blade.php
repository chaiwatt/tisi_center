@php
    $lab_certify =  $labs->lab_certify;
@endphp

<div class="col-md-12 col-sm-12">
  
    <table width="100%" class="table table-bordered table-striped">
    
        <thead>
            <tr>
                <th class="text-center" width="5%">ลำดับ</th>
                <th class="text-center" width="25%">ใบรับรองเลขที่</th>
                <th class="text-center" width="20%">วันที่ออก</th>
                <th class="text-center" width="20%">วันที่สิ้นสุด</th>
                <th class="text-center" width="15%">หมายเลขการรับรอง</th>
                <th class="text-center" width="15%">จัดการ</th>

            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="5"></td>
            </tr>
        </tfoot>

        <tbody>
            @foreach ( $lab_certify as $key=> $certify )
                <tr>
                    <td class="text-top text-center">{!!$key+1 !!}</td>
                    <td class="text-top text-left">

                        @if( !empty($certify->certify_export) )
                            <a href="{!! url('/api/v1/certificate?cer='.(!empty($certify->certificate_no)?$certify->certificate_no:null)) !!}"  target="_blank"><span class="text-info">   {!! !empty($certify->certificate_no)?$certify->certificate_no:null !!}</span></a>
                        @else
                            {!! $certify->certificate_no !!}
                        @endif
                    </td>
                    <td class="text-top text-center">
                        {!! !empty($certify->certificate_start_date)?HP::revertDate($certify->certificate_start_date,true):'-' !!}
                    </td>
                    <td class="text-top text-center">
                        {!! !empty($certify->certificate_end_date)?HP::revertDate($certify->certificate_end_date,true):'-' !!}
                    </td>
                    <td class="text-top text-center">
                        {!! !empty( $certify->accereditatio_no )?$certify->accereditatio_no:'-' !!}
                    </td>
                    <td class="text-top text-center">
                        @can('edit-'.str_slug('manage-lab'))

                        {{-- @php
                            $certify_scope_max = $certify->certify_scope->max('end_date');
                        @endphp

                        @if(  !empty($certify->CheckCertifyRenew) && !empty($certify->CheckCertifyRenew->end_date) && ($certify->CheckCertifyRenew->end_date >  $certify->certificate_end_date) )
                            <button class="btn btn-primary btn-sm waves-effect waves-light m-l-5 btn_update_scope" 
                                    title="อัพเดทขอบข่ายวันที่สิ้นสุด ( {!! HP::revertDate($certify->CheckCertifyRenew->end_date,true) !!} )" 
                                    data-lab_id="{!! $certify->lab_id !!}"
                                    data-id="{!! $certify->id !!}" 
                                    data-app_cert_lab_file_all ="{!! $certify->CheckCertifyRenew->id !!}" 
                                    data-end_date="{!! $certify->CheckCertifyRenew->end_date !!}" 
                                    data-ref_app="{!! $certify->ref_lab_application_no   !!}">
                                <i class="fa  fa-refresh"></i>
                            </button>
                        @endif --}}

                        @can('view-'.str_slug('manage-lab'))
                            <button type="button" class="btn btn-sm btn-info glow m-l-5 btn_get_cer_update" data-id="{!! $certify->id !!}" >
                                <i class="mdi mdi-timetable"></i>
                            </button>
                        @endcan
                    @endcan
                    </td>
                </tr> 

            @endforeach
        </tbody>

    </table>

    @include ('section5.manage-lab.modals.modal-certify-history')

</div>

@push('js')

    <script>
        jQuery(document).ready(function() {

            // $(document).on('click', '.btn_update_scope', function(e) {

            //     var id                    = $(this).data('id');
            //     var end_date              = $(this).data('end_date');
            //     var lab_id                = $(this).data('lab_id');
            //     var ref_app               = $(this).data('ref_app');
            //     var app_cert_lab_file_all = $(this).data('app_cert_lab_file_all');

            //     $.ajax({
            //         method: "POST",
            //         url: "{{ url('section5/labs/update_expiration_date_scope') }}",
            //         data: {
            //             "_token": "{{ csrf_token() }}",
            //             "id": id,
            //             "end_date": end_date,
            //             "lab_id": lab_id,
            //             "ref_app": ref_app,
            //             "app_cert_lab_file_all_id": app_cert_lab_file_all
            //         }
            //     }).success(function (msg) {
            //         if (msg == "success") {

            //             $.toast({
            //                 heading: 'Compleate!',
            //                 text: 'บันทึกสำเร็จ',
            //                 position: 'top-right',
            //                 loaderBg: '#ff6849',
            //                 icon: 'success',
            //                 hideAfter: 1000,
            //                 stack: 6,
            //             });

            //             location.reload(); 
            //         }
            //     });

            // });
            
            $(document).on('click', '.btn_get_cer_update', function(e) {

                var id = $(this).data('id');

                $.LoadingOverlay("show", {
                    image       : "",
                    text        : "Loading..."
                });

                $('#box_certify-history').html('');
                $.ajax({
                    url: "{!! url('/section5/labs/get-log-certify') !!}" + "/" + id
                }).done(function( object ) {

                    $.LoadingOverlay("hide", true);
                    $('#box_certify-history').html(object);
                    $('#MCertify-History').modal('show');
                });


            });
        });
    </script>

@endpush