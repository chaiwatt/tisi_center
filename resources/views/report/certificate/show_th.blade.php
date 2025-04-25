@extends('layouts.app')
@push('css')
<style>
    .btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
     <div class="col-md-12 ">
          <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="60px" width="60px"/>
          <b style="font-size: 16pt;"> ข้อมูลใบรับรองระบบงาน</b>   
          <div class="pull-right">
 

            <div class="btn-group btn-group-toggle" data-toggle="buttons"  >
                <label class="btn btn-success  active">
                    <input type="radio" name="options" id="option1"  value="1" checked>
                    <span  style="color:#fff;">TH</span> 
                </label>
                <label class="btn btn-secondary  ">
                    <input type="radio" name="options" id="option2"  value="2"  >
                    <span  style="color:#fff;">EN</span> 
                </label>    
            </div> 

            <a class="btn btn-default  " href="{{  url('report/certificate') }}">
                   <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
            </a>
 

         </div>

 
          <div class="clearfix"></div>
          <hr>
       </div><!-- /.col-md-12 -->
     
@php
$id             =  '';
$certify_base64 =  '';
@endphp

@if (!is_null($item))

@php
        $id             =   rtrim(strtr(base64_encode($item->id), '+/', '-_'), '=');
        $certify_base64  =   rtrim(strtr(base64_encode($certify), '+/', '-_'), '=');
@endphp



@if ($certify == "CB"  || $certify == "1")
        @php
            $text = '';        
        if(!empty($item->certificate_newfile)){
                        $text =   '<a href="'. ( url('funtions/get-view').'/'.$item->certificate_path.'/'.$item->certificate_newfile.'/'.$item->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                        <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                        </a> ';
        }else if(!empty($item->attachs)){
                        $text =   '<a href="'. ( url('certify/check/file_cb_client').'/'.$item->attachs.'/'. ( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)  )).'" target="_blank">
                                        '. HP::FileExtension($item->attachs).' 
                        </a> ';
       }else if(!is_null($item->CertiCbTo)){
                       $certi_cb =  $item->CertiCbTo;
                        $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_cb->id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
        }
        @endphp

     <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($item->certificate) ? $item->certificate  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หน่วยรับรอง : </p>
        <p class="col-md-8"> {!! !empty($item->cb_name)?  $item->cb_name:''  !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานที่ตั้งหน่วยรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($item->FormatAddress)? $item->FormatAddress:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($item->accereditatio_no)? $item->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($item->formula)? $item->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_first)? HP::formatDateThaiFull($item->certificate_date_first):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_start)? HP::formatDateThaiFull($item->certificate_date_start):'' !!} </p>
  </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>

    

    @if (!is_null($item->CertiCbTo))
    @php
        $certi_cb =  $item->CertiCbTo;
        $attach_path = 'files/applicants/check_files_cb/';
    @endphp
        <div class="col-sm-12">
            <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
            <p class="col-md-8"> 
            
                    @if(!empty($certi_cb->certi_cBFile_state1_to) && HP::checkFileStorage($certi_cb->certi_cBFile_state1_to->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_cb->certi_cBFile_state1_to->attach_pdf.'/'.  basename($certi_cb->certi_cBFile_state1_to->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_cb->certi_cBFile_state1_to->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                    @elseif(!empty($certi_cb->certi_cBFile_state1_to->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_cb->certi_cBFile_state1_to->attach_pdf) !!}" class="attach_pdf" target="_blank">
                              {!! HP::FileExtension($certi_cb->certi_cBFile_state1_to->attach_pdf) ?? '' !!}
                        </a>
                    @endif
        </p>
        </div>
        <div class="col-sm-12">
            <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
            <p class="col-md-8"> {!!  !empty($certi_cb->certi_cBFile_state1_to->issue_date)? HP::formatDateThaiFull($certi_cb->certi_cBFile_state1_to->issue_date):'' !!} </p>
        </div>
        <div class="col-sm-12">
            <p class="col-md-4 text-right">วันที่ใช้งานขอบข่ายตั้งแต่วันที่ :</p>
            <p class="col-md-2"> 
                    {!!  !empty($certi_cb->certi_cBFile_state1_to->start_date)? HP::formatDateThaiFull($certi_cb->certi_cBFile_state1_to->start_date):'' !!}      
            </p>
            <p class="col-md-2 text-center"> 
                ถึง 
            </p>
            <p class="col-md-2"> 
                    {!!  !empty($certi_cb->certi_cBFile_state1_to->end_date)? HP::formatDateThaiFull($certi_cb->certi_cBFile_state1_to->end_date):'' !!}      
            </p>
        </div>
        <div class="col-sm-12">
            <p class="col-md-4 text-right">บุคคลที่ติดต่อ : </p>
            <p class="col-md-8">
               {!!  !empty($item->contact_name) ?  $item->contact_name.'<br>' : '' !!} 
               {!!  !empty($item->contact_tel) ?   'โทรศัพท์ : '.$item->contact_tel.'<br>' : '' !!} 
               {!!  !empty($item->contact_mobile) ?   'โทรศัพท์มือถือ : '.$item->contact_mobile.'<br>' : '' !!} 
               {!!  !empty($item->contact_email) ?   'E-Mail : '.$item->contact_email : '' !!} 
            </p>
        </div> 
   @endif
   @if (!empty($certi_cb->cb_latitude) && !empty($certi_cb->cb_longitude))
   @php
      $latitude  =  $certi_cb->cb_latitude;
      $longitude =  $certi_cb->cb_longitude;
   @endphp
   <div class="col-sm-12">
        <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
        <div class="col-md-7"> 
            <div id="map" style="height: 250px;"></div>
        </div>
   </div>
    <div class="col-sm-12">
        <br> <br>
    </div>
    @else
    <div class="col-sm-12">
      <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
      <p class="col-md-8 text-muted"><i>- ไม่ระบุ - </i> </p>
    </div>
  @endif

  @elseif ($certify == "IB" || $certify == "2")
 
 
             @php
                 $text = '';        
                if(!empty($item->certificate_newfile)){
                                $text =   '<a href="'. ( url('funtions/get-view').'/'.$item->certificate_path.'/'.$item->certificate_newfile.'/'.$item->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                </a> ';
                }else if(!empty($item->attachs)){
                                $text =   '<a href="'. ( url('certify/check/file_ib_client').'/'.$item->attachs.'/'. ( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)  )).'" target="_blank">
                                                '. HP::FileExtension($item->attachs).' 
                                </a> ';
                }else  if(!is_null($item->CertiIBCostTo)){
                         $certi_ib =  $item->CertiIBCostTo;
                         $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_ib->id.'/2')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                }
            @endphp
 
     <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($item->certificate) ? $item->certificate  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หน่วยตรวจประเภท : </p>
        <p class="col-md-8"> {!! !empty($item->CertiIBCostTo->TypeUnitTitle)?  $item->CertiIBCostTo->TypeUnitTitle:''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ชื่อหน่วยตรวจสอบ : </p>
        <p class="col-md-8"> {!!   !empty($item->name_unit)? $item->name_unit:'' !!} </p>
    </div>
     <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานที่ตั้งหน่วยตรวจ : </p>
        <p class="col-md-8"> {!!   !empty($item->FormatAddress)? $item->FormatAddress:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($item->accereditatio_no)? $item->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($item->formula)? $item->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_first)? HP::formatDateThaiFull($item->certificate_date_first):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_start)? HP::formatDateThaiFull($item->certificate_date_start):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>

    @if (!is_null($item->CertiIBCostTo))
    @php
        $certi_ib =  $item->CertiIBCostTo;
        $attach_path = 'files/applicants/check_files_ib/';
    @endphp
        <div class="col-sm-12">
            <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
            <p class="col-md-8"> 
            
                    @if(!empty($certi_ib->certi_iBFile_state1_to) && HP::checkFileStorage($certi_ib->certi_iBFile_state1_to->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_ib->certi_iBFile_state1_to->attach_pdf.'/'.  basename($certi_ib->certi_iBFile_state1_to->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_ib->certi_iBFile_state1_to->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                    @elseif(!empty($certi_ib->certi_iBFile_state1_to->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_ib->certi_iBFile_state1_to->attach_pdf) !!}" class="attach_pdf" target="_blank">
                              {!! HP::FileExtension($certi_ib->certi_iBFile_state1_to->attach_pdf) ?? '' !!}
                        </a>
                    @endif
        </p>
        </div>
        {{-- <div class="col-sm-12">
            <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
            <p class="col-md-8"> {!!  !empty($certi_ib->CertiIBFileTo->start_date)? HP::formatDateThaiFull($certi_ib->CertiIBFileTo->start_date):'' !!} </p>
        </div> --}}
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
        <div class="col-sm-12">
            <p class="col-md-4 text-right">บุคคลที่ติดต่อ : </p>
            <p class="col-md-8">
                {!!  !empty($item->contact_name) ?  $item->contact_name.'<br>' : '' !!} 
                {!!  !empty($item->contact_tel) ?   'โทรศัพท์ : '.$item->contact_tel.'<br>' : '' !!} 
                {!!  !empty($item->contact_mobile) ?   'โทรศัพท์มือถือ : '.$item->contact_mobile.'<br>' : '' !!} 
                {!!  !empty($item->contact_email) ?   'E-Mail : '.$item->contact_email : '' !!} 
            </p>
        </div>
    @endif

    @if (!empty($certi_ib->ib_latitude) && !empty($certi_ib->ib_longitude))
    @php
       $latitude  =  $certi_ib->ib_latitude;
       $longitude =  $certi_ib->ib_longitude;
    @endphp
    <div class="col-sm-12">
         <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
         <div class="col-md-7"> 
             <div id="map" style="height: 250px;"></div>
         </div>
    </div>
     <div class="col-sm-12">
         <br> <br>
     </div>
   @else
     <div class="col-sm-12">
       <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
       <p class="col-md-8 text-muted"><i>- ไม่ระบุ - </i> </p>
     </div>
   @endif
 


@elseif ($certify == "LAB")
        @php
            $text = '';        
        if(!empty($item->certificate_newfile)){
                        $text =   '<a href="'. ( url('funtions/get-view').'/'.$item->certificate_path.'/'.$item->certificate_newfile.'/'.$item->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                        <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                        </a> ';
        }else if(!empty($item->attachs)){
                        $text =   '<a href="'. ( url('certify/check/file_client').'/'.$item->attachs.'/'. ( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)  )).'" target="_blank">
                                        '. HP::FileExtension($item->attachs).' 
                        </a> ';
        }else if(!is_null($item->CertiLabTo)){
                        $certi_lab =  $item->CertiLabTo;
                        $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$certi_lab->id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
        }
        @endphp

    <div class="col-sm-12">
        <p class="col-md-4 text-right">เลขที่ใบรับรอง : </p>
        <p class="col-md-8"> {!!!empty($item->certificate_no) ? $item->certificate_no  : ''!!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ความสามารถห้องปฏิบัติการ : </p>
        <p class="col-md-8"> 
            @if ( !empty($item->CertiLabTo->lab_type) && $item->CertiLabTo->lab_type == 3)
                ทดสอบ
            @elseif(!empty($item->CertiLabTo->lab_type) && $item->CertiLabTo->lab_type == 4)
                สอบเทียบ 
            @endif
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ชื่อห้องปฏิบัติการ : </p>
        <p class="col-md-8"> {!! !empty($item->lab_name)?  $item->lab_name :''  !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">สถานะที่ตั้งห้องปฏิบัติการ : </p>
        <p class="col-md-8"> {!!   !empty($item->FormatAddress)? $item->FormatAddress:'' !!} </p>
    </div>

    <div class="col-sm-12">
        <p class="col-md-4 text-right">หมายเลขการรับรองที่ : </p>
        <p class="col-md-8"> {!!   !empty($item->accereditatio_no)? $item->accereditatio_no:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">มาตรฐานการรับรอง : </p>
        <p class="col-md-8"> {!!   !empty($item->formula)? $item->formula:'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองครั้งแรก : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_first)? HP::formatDateThaiFull($item->certificate_date_first):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">วันที่ได้รับการรับรองล่าสุด : </p>
        <p class="col-md-8"> {!!   !empty($item->certificate_date_start)? HP::formatDateThaiFull($item->certificate_date_start):'' !!} </p>
    </div>
    <div class="col-sm-12">
        <p class="col-md-4 text-right">ไฟล์ใบรับรองระบบงาน : </p>
        <p class="col-md-8"> {!!  $text !!} </p>
    </div>

    @if (!is_null($item->CertiLabTo))
    @php
        $mapreq_lab  =   !empty($item->CertiLabFileAll->last()) ? $item->CertiLabFileAll->last() : null;
        $certi_lab   =  $item->CertiLabTo;
        $attach_path = 'files/applicants/check_files/';
    @endphp
        <div class="col-sm-12">
            <p class="col-md-4 text-right">ขอบข่ายการรับรองระบบงาน : </p>
            <p class="col-md-8"> 
               
                    @if(!empty($mapreq_lab) && HP::checkFileStorage($attach_path.$mapreq_lab->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$mapreq_lab->attach_pdf) !!}" class="attach_pdf" target="_blank">
                                {!! HP::FileExtension($mapreq_lab->attach_pdf) ?? '' !!}
                        </a>
                    @elseif(!empty($certi_lab->Certi_Lab_State1_FileTo) && HP::checkFileStorage($certi_lab->Certi_Lab_State1_FileTo->attach_pdf))
                        <a href="{{ url('funtions/get-view/'.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf.'/'.  basename($certi_lab->Certi_Lab_State1_FileTo->attach_pdf_client_name))}}"   target="_blank">
                            {!! HP::FileExtension($certi_lab->Certi_Lab_State1_FileTo->attach_pdf_client_name)  ?? '' !!}
                        </a> 
                    @elseif(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf))
                        <a href="{!! HP::getFileStorage($attach_path.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf) !!}" class="attach_pdf" target="_blank">
                              {!! HP::FileExtension($certi_lab->Certi_Lab_State1_FileTo->attach_pdf) ?? '' !!}
                        </a>
                    @endif
        </p>
        </div>
        {{-- <div class="col-sm-12">
            <p class="col-md-4 text-right">ออกขอบข่ายให้ ณ วันที่ :</p>
            <p class="col-md-8"> {!!  !empty($certi_lab->CertiLABFileTo->start_date)? HP::formatDateThaiFull($certi_lab->CertiLABFileTo->start_date):'' !!} </p>
        </div> --}}
        <div class="col-sm-12">
            <p class="col-md-4 text-right">วันที่ใช้งานขอบข่ายตั้งแต่วันที่ :</p>
            <p class="col-md-2"> 
                    @if (!empty($mapreq_lab) )
                    {!!  !empty($mapreq_lab->start_date)? HP::formatDateThaiFull($mapreq_lab->start_date):'' !!}      
                    @else
                    {!!  !empty($certi_lab->Certi_Lab_State1_FileTo->start_date)? HP::formatDateThaiFull($certi_lab->Certi_Lab_State1_FileTo->start_date):'' !!}      
                    @endif
                 
            </p>
            <p class="col-md-2 text-center"> 
                ถึง 
            </p>
            <p class="col-md-2"> 
                @if (!empty($mapreq_lab) )
                     {!!  !empty($mapreq_lab->end_date)? HP::formatDateThaiFull($mapreq_lab->end_date):'' !!}      
                @else
                    {!!  !empty($certi_lab->Certi_Lab_State1_FileTo->end_date)? HP::formatDateThaiFull($certi_lab->Certi_Lab_State1_FileTo->end_date):'' !!}      
                @endif
                
            </p>
        </div>
        <div class="col-sm-12">
            <p class="col-md-4 text-right">บุคคลที่ติดต่อ : </p>
            <p class="col-md-8">
                {!!  !empty($item->contact_name) ?  $item->contact_name.'<br>' : '' !!} 
                {!!  !empty($item->contact_tel) ?   'โทรศัพท์ : '.$item->contact_tel.'<br>' : '' !!} 
                {!!  !empty($item->contact_mobile) ?   'โทรศัพท์มือถือ : '.$item->contact_mobile.'<br>' : '' !!} 
                {!!  !empty($item->contact_email) ?   'E-Mail : '.$item->contact_email : '' !!} 
            </p>
        </div>
    @endif


  @if (!empty($certi_lab->lab_latitude) && !empty($certi_lab->lab_longitude))
    @php
       $latitude  =  $certi_lab->lab_latitude;
       $longitude =  $certi_lab->lab_longitude;
    @endphp
    <div class="col-sm-12">
         <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
         <div class="col-md-7"> 
             <div id="map" style="height: 250px;"></div>
         </div>
    </div>
     <div class="col-sm-12">
         <br> <br>
     </div>
  @else
  <div class="col-sm-12">
    <p class="col-md-4 text-right">แผนที่ตั้งสถานประกอบการ : </p>
    <p class="col-md-8 text-muted"><i>- ไม่ระบุ - </i> </p>
  </div>
   @endif

@else 

@endif

@else 

@endif

    
   </div>
</div>
@endsection


@push('js')
 
@if (!empty($latitude) && !empty($longitude))
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkwr5rmzY9btU08sQlU9N0qfmo8YmE91Y&libraries=places&callback=initAutocomplete"   async defer></script>
<script>
    var markers = [];

 
    function initAutocomplete() {
        var latitude = '{!! $latitude !!}';
        var longitude = '{!! $longitude !!}';
            var array = {};
                array['lat'] = parseFloat(latitude);
                array['lng'] =  parseFloat(longitude);
        var map = new google.maps.Map(document.getElementById('map'), {
            center: array,
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });


        markers = new google.maps.Marker({
            position:  array,
            map: map,
        });

        google.maps.event.addListener(map, 'click', function (event) {
            markers.setMap(null);

            markers = new google.maps.Marker({
                position: { lat: event.latLng.lat(), lng: event.latLng.lng() },
                map: map,
            });

        });
    }
</script>
@endif


<script>
    $(document).ready(function () {
        $('input[name="options"]').change(function(){         
            if($(this).is(':checked')  && $(this).val() == 2){
                  var url        =   "{{url('/report/certificate-en')}}";
                  var id         =   "{{$id}}";
                  var certify    =   "{{$certify_base64}}";
                  window.location.href = url  + '?id='+id+'&certify='+certify;
            }
        });
    });
 </script>  

@endpush
