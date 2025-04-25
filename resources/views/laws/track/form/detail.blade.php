@push('css')
    <style>
        
        .alert-primary {
            background-color: #7ab2fa;
            border-color: #ceddfa;
            color: #ffffff;
        }
    </style>
@endpush
<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลรายละเอียด</h5></legend>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">หน่วยงานเจ้าของเรื่อง :</label>
                        <div class="col-md-9">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->DeparmentName)?$lawtrackreceive->DeparmentName:'-' !!} </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-6">ประเภทหน่วยงาน :</label>
                        <div class="col-md-6">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->DeparmentTypeName)?$lawtrackreceive->DeparmentTypeName:'-' !!} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-5">ประเภทงาน :</label>
                        <div class="col-md-7">
                            <p class="form-control-static div_dotted"> {!! !is_null($lawtrackreceive->law_job_types) && !empty($lawtrackreceive->law_job_types->title)?$lawtrackreceive->law_job_types->title:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">เรื่อง :</label>
                        <div class="col-md-9">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->title)?$lawtrackreceive->title:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">คำอธิบาย :</label>
                        <div class="col-md-9">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->description)?$lawtrackreceive->description:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-0">
                        <label class="control-label col-md-6">ไฟล์เเนบ :</label>
                        <div class="col-md-6">
                        
                            @if( isset($lawtrackreceive->file_law_track_receives) && ($lawtrackreceive->file_law_track_receives->count() >= 1) )

                                @foreach ( $lawtrackreceive->file_law_track_receives as $Ifile )
                                    <p class="form-control-static">
                                        <a href="{!! HP::getFileStorage($Ifile->url) !!}" target="_blank" class="m-t-0">
                                            {!! !empty($Ifile->filename) ? $Ifile->filename : '' !!}
                                            {!! HP::FileExtension($Ifile->filename)  ?? '' !!}
                                        </a>
                                    </p>
                                @endforeach

                            @else
                                <p class="form-control-static">ไม่พบไฟล์แนบ</p>
                            @endif
                        
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>

    <div class="col-md-4">

        <div class="alert alert-primary m-t-15 text-center p-10"> {!! !is_null($lawtrackreceive->law_status_job_tracks) && !empty($lawtrackreceive->law_status_job_tracks->title)?$lawtrackreceive->law_status_job_tracks->title:null !!} </div>

        <fieldset class="white-box">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">เลขที่อ้างอิง :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->reference_no)?$lawtrackreceive->reference_no:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">เลขที่หนังสือ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->book_no)?$lawtrackreceive->book_no:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">เลขรับ :</label>
                        <div class="col-md-8">
                            <p class="form-control-static div_dotted"> {!!  !is_null($lawtrackreceive->receive_no)?$lawtrackreceive->receive_no:'-' !!} </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-4">ผู้รับมอบหมาย :</label>
                        <div class="col-md-8">
                            @if(!empty($lawtrackreceive->user_lawyer_to->FullName))
                                <p class="form-control-static div_dotted"> {!!  $lawtrackreceive->user_lawyer_to->FullName !!} </p>
                            @else
                                <p class="form-control-static div_dotted"> รอมอบหมาย </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


        </fieldset>
    </div>
</div>