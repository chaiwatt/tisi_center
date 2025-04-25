@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush
 
 
 
@php
    $text = '';        
if(!empty($certi_ib->CertiIBExportTo->certificate_newfile)){
                $text =   '<a href="'. ( url('funtions/get-view').'/'.$certi_ib->CertiIBExportTo->certificate_path.'/'.$certi_ib->CertiIBExportTo->certificate_newfile.'/'.$certi_ib->CertiIBExportTo->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                </a> ';
}else if(!empty($certi_ib->CertiIBExportTo->attachs)){
                $text =   '<a href="'. ( url('certify/check/file_ib_client').'/'.$certi_ib->CertiIBExportTo->attachs.'/'. ( !empty($certi_ib->CertiIBExportTo->attachs_client_name) ? $certi_ib->CertiIBExportTo->attachs_client_name :  basename($certi_ib->CertiIBExportTo->attachs)  )).'" target="_blank">
                                '. HP::FileExtension($certi_ib->CertiIBExportTo->attachs).' 
                </a> ';
 }else if(!is_null($certi_ib->id)){
                $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_ib->id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
}
@endphp


 
<div class="row">
    <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($certi_ib->CertiIBExportTo->certificate) ? $certi_ib->CertiIBExportTo->certificate  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หน่วยตรวจประเภท : </p>
        <p class="col-md-8"> {!! !empty($certi_ib->TypeUnitTitle)?  $certi_ib->TypeUnitTitle:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ชื่อหน่วยตรวจสอบ : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->name_unit)? $certi_ib->name_unit:'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานที่ตั้งหน่วยตรวจ : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->CertiIBExportTo->FormatAddress)? $certi_ib->CertiIBExportTo->FormatAddress:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->CertiIBExportTo->accereditatio_no)? $certi_ib->CertiIBExportTo->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->CertiIBExportTo->formula)? $certi_ib->CertiIBExportTo->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->CertiIBExportTo->date_start)? HP::formatDateThaiFull($certi_ib->CertiIBExportTo->date_start):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($certi_ib->CertiIBExportTo->date_end)? HP::formatDateThaiFull($certi_ib->CertiIBExportTo->date_end):'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
        <p class="col-md-8"> 
          
               @if (!is_null($certi_ib->certi_iBFile_state1_to))
                    @php
                        $attach_path = 'files/applicants/check_files_ib/';
                    @endphp
                  @if(!empty($certi_ib->certi_iBFile_state1_to) && HP::checkFileStorage($certi_ib->certi_iBFile_state1_to->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_ib->certi_iBFile_state1_to->attach_pdf.'/'.  basename($certi_ib->certi_iBFile_state1_to->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_ib->certi_iBFile_state1_to->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                    @elseif(!empty($certi_ib->certi_iBFile_state1_to->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_ib->certi_iBFile_state1_to->attach_pdf) !!}" class="attach_pdf" target="_blank">
                              {!! HP::FileExtension($certi_ib->certi_iBFile_state1_to->attach_pdf) ?? '' !!}
                        </a>
                    @endif
                @endif
       </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
        <p class="col-md-8"> {!!  !empty($certi_ib->CertiIBFileTo->start_date)? HP::formatDateThaiFull($certi_ib->CertiIBFileTo->start_date):'' !!} </p>
    </div>
    <div class="col-sm-12">
         <p class="col-md-4 text-right">วันที่ใช้งานขอบข่ายตั้งแต่วันที่ :</p>
         <p class="col-md-2"> 
                {!!  !empty($certi_ib->certi_iBFile_state1_to->start_date)? HP::formatDateThaiFull($certi_ib->certi_iBFile_state1_to->start_date):'' !!}      
        </p>
        <p class="col-md-2 text-center"> 
              ถึง 
        </p>
        <p class="col-md-2"> 
                {!!  !empty($certi_ib->certi_iBFile_state1_to->end_date)? HP::formatDateThaiFull($certi_ib->certi_iBFile_state1_to->end_date):'' !!}      
        </p>
    </div>
</div>



 
 
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush
