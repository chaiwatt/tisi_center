@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush
 
@php
    $text = '';        
   if(!empty($export_lab->certificate_newfile)){
                   $text =   '<a href="'. ( url('funtions/get-view').'/'.$export_lab->certificate_path.'/'.$export_lab->certificate_newfile.'/'.$export_lab->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                   <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                   </a> ';
   }else if(!empty($export_lab->attachs)){
                   $text =   '<a href="'. ( url('certify/check/file_client').'/'.$export_lab->attachs.'/'. ( !empty($export_lab->attachs_client_name) ? $export_lab->attachs_client_name :  basename($export_lab->attachs)  )).'" target="_blank">
                                   '. HP::FileExtension($export_lab->attachs).' 
                   </a> ';
     }else  if(!is_null($export_lab->CertiIBCostTo)){
                   $certi_ib =  $export_lab->CertiIBCostTo;
                   $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_ib->id.'/2')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
   }
@endphp
 
<div class="row">
    <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($export_lab->certificate_no) ? $export_lab->certificate_no  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ความสามารถห้องปฏิบัติการ : </p>
        <p class="col-md-8"> 
            @if ( !empty($export_lab->CertiLabTo->lab_type) && $export_lab->CertiLabTo->lab_type == 3)
                ทดสอบ
            @elseif(!empty($export_lab->CertiLabTo->lab_type) && $export_lab->CertiLabTo->lab_type == 4)
                สอบเทียบ 
            @endif
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ห้องปฏิบัติการ : </p>
        <p class="col-md-8"> {!! !empty($export_lab->CertiLabTo->lab_name)?  $export_lab->CertiLabTo->lab_name:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานะที่ตั้งห้องปฏิบัติการ : </p>
        <p class="col-md-8"> {!!   !empty($export_lab->FormatAddress)? $export_lab->FormatAddress:'' !!} </p>
    </div>

    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($export_lab->accereditatio_no)? $export_lab->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($export_lab->formula)? $export_lab->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($export_lab->certificate_date_start)? HP::formatDateThaiFull($export_lab->certificate_date_start):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($export_lab->certificate_date_end)? HP::formatDateThaiFull($export_lab->certificate_date_end):'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>
 
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
        <p class="col-md-8"> 
            @if (!is_null($export_lab->CertiLabTo))
                @php
                    $certi_lab =  $export_lab->CertiLabTo;
                    $attach_path = 'files/applicants/check_files/';
                @endphp
                @if(!empty($certi_lab->Certi_Lab_State1_FileTo) && HP::checkFileStorage($certi_lab->Certi_Lab_State1_FileTo->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf.'/'.  basename($certi_lab->Certi_Lab_State1_FileTo->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_lab->Certi_Lab_State1_FileTo->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                @elseif(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf) !!}" class="attach_pdf" target="_blank">
                                {!! HP::FileExtension($certi_lab->Certi_Lab_State1_FileTo->attach_pdf) ?? '' !!}
                        </a>
                @endif
            @endif
 
       </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
        <p class="col-md-8"> {!!  !empty($export_lab->CertiLabTo->CertiLABFileTo->start_date)? HP::formatDateThaiFull($export_lab->CertiLabTo->CertiLABFileTo->start_date):'' !!} </p>
    </div>
    <div class="col-sm-12">
         <p class="col-md-4 text-right">วันที่ใช้งานขอบข่ายตั้งแต่วันที่ :</p>
         <p class="col-md-2"> 
                {!!  !empty($export_lab->CertiLabTo->Certi_Lab_State1_FileTo->start_date)? HP::formatDateThaiFull($export_lab->CertiLabTo->Certi_Lab_State1_FileTo->start_date):'' !!}      
        </p>
        <p class="col-md-2 text-center"> 
              ถึง 
        </p>
        <p class="col-md-2"> 
                {!!  !empty($export_lab->CertiLabTo->Certi_Lab_State1_FileTo->end_date)? HP::formatDateThaiFull($export_lab->CertiLabTo->Certi_Lab_State1_FileTo->end_date):'' !!}      
        </p>
    </div>
</div>



 
 
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush
