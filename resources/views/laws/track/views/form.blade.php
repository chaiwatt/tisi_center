@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/clockpicker/dist/jquery-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .border-left {
            border-left: 1px solid #dee2e6 !important;
        }
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }

    </style>
@endpush
@php
    $lawtrackreceive->assign_data = $lawtrackreceive->law_trackreceives_assign->max('created_at');
@endphp

<!-- รายละเอียด -->
@include('laws.track.form.detail')

<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">
            <legend class="legend"><h4>ข้อมูลการดำเนินการ</h4></legend>

            <table class="table table-bordered repeater-form">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" width="15%">วันที่ดำเนินการ</th>
                        <th class="text-center" width="20%">การดำเนินการ</th>
                        <th class="text-center" width="15%">วันที่ครบกำหนด</th>
                        <th class="text-center" width="25%">รายละเอียด</th>
                        <th class="text-center" width="20%">ไฟล์แนบ</th>
                    </tr>
                </thead>
                <tbody>
                    @if( count($lawtrackreceive->law_track_operation) >= 1 )
                        @foreach(  $lawtrackreceive->law_track_operation as $key => $operation )
                            <tr>
                                <td class="text-top text-center">
                                    <span class="td_no">{!! $key+1 !!}</span>
                                </td>
                                <td class="text-top text-center">
                                    {!! !empty($operation->operation_date)?HP::revertDate($operation->operation_date,true):null !!}
                                </td>
                                <td class="text-top">
                                    {!! !is_null($operation->law_status_job_tracks) && !empty($operation->law_status_job_tracks->title)?$operation->law_status_job_tracks->title:null !!}
                                </td>
                                <td class="text-top text-center">
                                    {!! !empty($operation->due_date)?HP::revertDate($operation->due_date,true):null !!}
                                </td>
                                <td class="text-top">
                                    {!! !empty($operation->detail)?$operation->detail:null !!}
                                </td>
                                <td class="text-top">
                                    @if( !empty($operation->attach_file) )
                                        @php
                                            $attach = $operation->attach_file;
                                        @endphp
                                        <div class="form-group col-md-12 operation_attach">
                                            <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                                {!! !empty($attach->filename) ? $attach->filename : '' !!}
                                                {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr >
                            <td class="text-top text-center" colspan="6">
                                ไม่พบข้อมูล
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>

        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-md-12">

        <fieldset class="white-box">

            <div class="col-md-6 col-12 mb-md-0 mb-4 m-t-5">
                <p><h4>สถานะงาน</h4></p>
        
                <div class="form-group">
                    <label class="control-label col-md-2">สถานะ :</label>
                    <div class="col-md-10">
                        <p class="form-control-static"> {!!  !is_null($lawtrackreceive->law_status_job_tracks) && !empty($lawtrackreceive->law_status_job_tracks->title)?$lawtrackreceive->law_status_job_tracks->title:'-' !!} </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">หมายเหตุ :</label>
                    <div class="col-md-10">
                        <p class="form-control-static"> {!!  !is_null($lawtrackreceive->remarks)?$lawtrackreceive->remarks:'-' !!} </p>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-12 mb-md-0 mb-4 border-left m-t-5">
                <p><h4>สรุประยะเวลาดำเนินงาน</h4></p>
        
                <div class="form-group">
                    <label class="control-label col-md-4">วันที่รับเรื่องเข้า :</label>
                    <div class="col-md-8">
                        <p class="form-control-static"> {!!  !is_null($lawtrackreceive->receive_date)?HP::DateThai($lawtrackreceive->receive_date):'-' !!} </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4">วันที่มอบหมาย :</label>
                    <div class="col-md-8">
                        <p class="form-control-static"> {!!  !is_null($lawtrackreceive->assign_data)?HP::DateThai($lawtrackreceive->assign_data):'-' !!} </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4">วันที่ดาเนินการเสร็จสิ้น :</label>
                    <div class="col-md-8">
                        <p class="form-control-static"> {!!  !is_null($lawtrackreceive->close_date)?HP::DateThai($lawtrackreceive->close_date):'-' !!} </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4">รวมระยะเวลาดำเนินการ/วัน :</label>
                    <div class="col-md-8">
                        @php
                            $startDate = \Carbon\Carbon::parse( $lawtrackreceive->receive_date )->format('Y-m-d');
                            $endDate   = \Carbon\Carbon::parse( !empty($lawtrackreceive->close_date)?$lawtrackreceive->close_date:date('Y-m-d') )->format('Y-m-d');
                            $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                        @endphp
                        <p class="form-control-static"> {!!  count($lits) !!} </p>
                    </div>
                </div>

                <div class="alert alert-bg-primary p-10"><small> หมายเหตุ : การคำนวณรวมจำนวนวันที่ดำเนินงานจะหักลบ วันหยุดของ สมอ. / วันหยุดเสาร์-อาทิตย์ </small> </div>

            </div>

        </fieldset>
    </div>
</div>
