@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush
 
@php
                $text = '';         
if(!empty($certi_cb->CertiCBExportTo->certificate_newfile)){
                $text =   '<a href="'. ( url('funtions/get-view').'/'.$certi_cb->CertiCBExportTo->certificate_path.'/'.$certi_cb->CertiCBExportTo->certificate_newfile.'/'.$certi_cb->CertiCBExportTo->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                </a> ';
}else if(!empty($certi_cb->CertiCBExportTo->attachs)){
                $text =   '<a href="'. ( url('certify/check/file_client').'/'.$certi_cb->CertiCBExportTo->attachs.'/'. ( !empty($certi_cb->CertiCBExportTo->attachs_client_name) ? $certi_cb->CertiCBExportTo->attachs_client_name :  basename($certi_cb->CertiCBExportTo->attachs)  )).'" target="_blank">
                                '. HP::FileExtension($certi_cb->CertiCBExportTo->attachs).' 
                </a> ';
}else{
                $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_cb->id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
}
 
@endphp

 
<div class="row">
    <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($certi_cb->CertiCBExportTo->certificate) ? $certi_cb->CertiCBExportTo->certificate  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หน่วยรับรอง : </p>
        <p class="col-md-8"> {!! !empty($certi_cb->name_standard)?  $certi_cb->name_standard:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ชื่อย่อหน่วยรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->name_short_standard)? $certi_cb->name_short_standard:'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานที่ตั้งหน่วยรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->FormatAddress)? $certi_cb->FormatAddress:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->CertiCBExportTo->accereditatio_no)? $certi_cb->CertiCBExportTo->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->CertiCBExportTo->formula)? $certi_cb->CertiCBExportTo->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->CertiCBExportTo->date_start)? HP::formatDateThaiFull($certi_cb->CertiCBExportTo->date_start):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($certi_cb->CertiCBExportTo->date_end)? HP::formatDateThaiFull($certi_cb->CertiCBExportTo->date_end):'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>
     
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
        <p class="col-md-8"> 
            @if (!is_null($certi_cb->CertiCBFileTo))
                @php
                    $attach_path = 'files/applicants/check_files_cb/';
                @endphp
                   @if(!empty($certi_cb->certi_cBFile_state1_to) && HP::checkFileStorage($certi_cb->certi_cBFile_state1_to->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_cb->certi_cBFile_state1_to->attach_pdf.'/'.  basename($certi_cb->certi_cBFile_state1_to->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_cb->certi_cBFile_state1_to->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                     @elseif(!empty($certi_cb->certi_cBFile_state1_to->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_cb->certi_cBFile_state1_to->attach_pdf) !!}" class="attach_pdf" target="_blank">
                              {!! HP::FileExtension($certi_cb->certi_cBFile_state1_to->attach_pdf) ?? '' !!}
                        </a>
                    @endif
            @endif
       </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
        <p class="col-md-8"> {!!  !empty($certi_cb->CertiCBFileTo->start_date)? HP::formatDateThaiFull($certi_cb->CertiCBFileTo->start_date):'' !!} </p>
    </div>
    <div class="col-sm-12">
         <p class="col-md-4 text-right">วันที่ใช้งานขอบข่ายตั้งแต่วันที่ :</p>
         <p class="col-md-3"> 
                {!!  !empty($certi_cb->certi_cBFile_state1_to->start_date)? HP::formatDateThaiFull($certi_cb->certi_cBFile_state1_to->start_date):'' !!}      
        </p>
        <p class="col-md-1 text-center"> 
              ถึง 
        </p>
        <p class="col-md-3"> 
                {!!  !empty($certi_cb->certi_cBFile_state1_to->end_date)? HP::formatDateThaiFull($certi_cb->certi_cBFile_state1_to->end_date):'' !!}      
        </p>
    </div>
</div>



 
 
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush
