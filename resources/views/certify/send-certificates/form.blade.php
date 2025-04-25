@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
@endpush



<div class="form-group required{{ $errors->has('sign_id') ? 'has-error' : ''}}">
    {!! Form::label('sign_id', 'ผู้ลงนาม'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @if (!empty($sendcertificate))
                {!! Form::text('sign_name', null,   ['class' => 'form-control','id'=>'sign_name', 'disabled' => true]) !!}
                {!! $errors->first('sign_name', '<p class="help-block">:message</p>') !!}
        @else
          {!! Form::select('sign_id',
               $signs ?? [], 
            null,
            ['class' => 'form-control',
            'id'=>'sign_id',
                'placeholder'=>'-- เลือกผู้ลงนาม --', 'required' => true]) 
            !!} 
        @endif

    </div>
</div>
<div class="form-group  {{ $errors->has('sign_position') ? 'has-error' : ''}}">
    {!! Form::label('sign_position', 'ตำแหน่ง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('sign_position', null,   ['class' => 'form-control','id'=>'sign_position', 'disabled' => true]) !!}
        {!! $errors->first('sign_position', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('certificate_type') ? 'has-error' : ''}}">
    {!! Form::label('certificate_type', 'การรับรอง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
          {!! Form::select('certificate_type', ['3' => 'LAB', '2' => 'IB', '1' => 'CB'], null, ['class' => 'form-control', 'id'=>'certificate_type', 'placeholder'=>'-- เลือกการรับรอง --', 'required' => true]) !!} 
    </div>
</div>

@if (!empty($sendcertificate))
<div class="form-group  {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'สถานะการลงนาม'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('status', null,   ['class' => 'form-control','id'=>'status' ,'disabled' => true]) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ผู้บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 p-t-5">
 
         {!!   !empty($sendcertificate->created_by)  && !empty($sendcertificate->user_created->FullName) ? $sendcertificate->user_created->FullName :    auth()->user()->FullName  !!}   
    </div>
</div>
<div class="form-group  {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'วันที่บันทึก'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 p-t-5 ">
          {!!   !empty($sendcertificate->updated_at)   ? HP::DateTimeThai($sendcertificate->updated_at) :  HP::DateTimeThai(date('Y-m-d H:i:s'))   !!}   
    </div>
</div>
<div class="row ">
    <div class="col-md-12">
        <table class="table color-bordered-table primary-bordered-table"  id="myTable">
            <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                 @if (isset($signs)  || (!empty($sendcertificate) && in_array($sendcertificate->state,[99])))
                    <th  width="1%" class="div_hide"><input type="checkbox" id="checkall"></th>
                @endif
                    <th class="text-center" width="15%">ผู้ขอรับใบรับรอง</th>
                    <th class="text-center" width="25%"> ห้องปฏิบัติการ/หน่วยตรวจสอบ/ห้องหน่วยรับรอง</th>
                    <th class="text-center" width="15%">เลขที่คำขอ</th>
                    <th class="text-center" width="10%">วัตถุประสงค์</th>
                    <th class="text-center" width="10%">เลขที่ใบรับรอง</th>

                 @if ( (!empty($sendcertificate) && in_array($sendcertificate->state,[1,2,3])))
                    <th  width="10%" >สถานะ</th>
                @endif
                    <th class="text-center" width="5%">preview</th>
                    <th class="text-center" width="5%">ขอบข่าย</th>
                </tr>
            </thead>
            <tbody>       
                    @php
                        $purpose_type =  ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
                    @endphp
                @if (!empty($sendcertificate) && count($sendcertificate->send_certificate_lists_many) > 0)
                    @foreach ($sendcertificate->send_certificate_lists_many as  $key => $item)
                            @php
                                if($item->certificate_tb == 'certificate_exports'){  //ห้องปฏิบัติการ
                                    $export    =   App\CertificateExport::findOrFail($item->certificate_id); 
                                }else   if($item->certificate_tb == 'app_certi_ib_export'){  //หน่วยตรวจสอบ
                                    $export    =   App\Models\Certify\ApplicantIB\CertiIBExport::findOrFail($item->certificate_id); 
                                }else   if($item->certificate_tb == 'app_certi_cb_export'){  //ห้องหน่วยรับรอง
                                    $export    =   App\Models\Certify\ApplicantCB\CertiCBExport::findOrFail($item->certificate_id); 
                                }
                            @endphp
                        @if (!is_null($export))
                            @php
                                if($item->certificate_tb == 'certificate_exports'){  //ห้องปฏิบัติการ
                                    $cer    =   $export->CertiLabTo; 
                                }else   if($item->certificate_tb == 'app_certi_ib_export'){  //หน่วยตรวจสอบ
                                    $cer    =   $export->CertiIBCostTo; 
                                }else   if($item->certificate_tb == 'app_certi_cb_export'){  //ห้องหน่วยรับรอง
                                    $cer    =   $export->CertiCbTo; 
                                }
                            @endphp
                        @if (!is_null($cer))
                                @php
                                    if($item->certificate_tb == 'certificate_exports'){  //ห้องปฏิบัติการ
                                        $cer->room             =  $cer->lab_name ?? '';
                                        $cer->cer_link         = '<a class="btn btn-link" href="'.(route('check_certificate.show', ['cc' => $cer->id])).'" target="_blank">  '.($cer->app_no ?? '').' </a>'; 
                                        $cer->purpose_type     =  array_key_exists($cer->purpose_type,$purpose_type) ? $purpose_type[$cer->purpose_type] : null; 
                                        $cer->accereditatio_no =   $export->accereditatio_no ??  '';
                                        if(!empty($export->certificate_newfile)){
                                            $cer->cer_pdf          =  '<a href="'. ( url('funtions/get-view').'/'.$export->certificate_path.'/'.$export->certificate_newfile.'/'.$export->certificate_no.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                                             <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                                      </a>';  
                                        }else{
                                            $cer->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$export->id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  
                                        }
                                            $certilab_file =  $cer->CertLabsFileScope;
                                        if(is_null($certilab_file)){
                                            $certilab_file          = App\Models\Certify\Applicant\CertLabsFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_lab_id',$cer->id)->where('state',1)->first();  
                                        }
                                       
                                        if(!is_null($certilab_file)){
                                            $cer->cer_file     =   ' <a href="'.(url('certify/check/file_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                        }else{
                                            $cer->cer_file     =  null;
                                        }
                                    }else     if($item->certificate_tb == 'app_certi_ib_export'){  //หน่วยตรวจสอบ
                                        $cer->room             =  $export->name_unit ??  '';
                                        $cer->cer_link         = '<a class="btn btn-link" href="'.(url('/certify/check_certificate-ib/' . $cer->token)).'" target="_blank">  '.($cer->app_no ?? '').' </a>'; 
                                        $cer->purpose_type     =  array_key_exists($cer->standard_change,$purpose_type) ? $purpose_type[$cer->standard_change] : null; 
                                        $cer->accereditatio_no =   $export->accereditatio_no ??  '';
                                        if(!empty($export->certificate_newfile)){
                                            $cer->cer_pdf          =  '<a href="'. ( url('funtions/get-view').'/'.$export->certificate_path.'/'.$export->certificate_newfile.'/'.$export->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                                             <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                                      </a>';  
                                        }else{
                                            $cer->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$export->id.'/2')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  
                                        }
                                        $certilab_file          = App\Models\Certify\ApplicantIB\CertiIBFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_ib_id',$cer->id)->where('state',1)->first();   
                                        if(!is_null($certilab_file)){
                                            $cer->cer_file     =   ' <a href="'.(url('certify/check/file_ib_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                        }else{
                                            $cer->cer_file     =  null;
                                        }
                                    }else     if($item->certificate_tb == 'app_certi_cb_export'){  //ห้องหน่วยรับรอง
                                        $cer->room             =  $export->name_standard ??   '';
                                        $cer->cer_link         = '<a class="btn btn-link" href="'.(url('/certify/check_certificate-ib/' . $cer->token)).'" target="_blank">  '.($cer->app_no ?? '').' </a>'; 
                                        $cer->purpose_type     =  array_key_exists($cer->standard_change,$purpose_type) ? $purpose_type[$cer->standard_change] : null; 
                                        $cer->accereditatio_no =   $export->accereditatio_no ??  '';
                                        if(!empty($export->certificate_newfile)){
                                            $cer->cer_pdf          =  '<a href="'. ( url('funtions/get-view').'/'.$export->certificate_path.'/'.$export->certificate_newfile.'/'.$export->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                                             <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                                      </a>';  
                                        }else{
                                            $cer->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$export->id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  
                                        }
                                       
                                        $certilab_file          = App\Models\Certify\ApplicantCB\CertiCBFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_cb_id',$cer->id)->where('state',1)->first();   
                                        if(!is_null($certilab_file)){
                                            $cer->cer_file     =   ' <a href="'.(url('certify/check/file_cb_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                        }else{
                                            $cer->cer_file     =  null;
                                        }

                                    }
                                @endphp
                            <tr>
                                <td class="text-center">{{ $key +1}}</td>
                     @if (isset($signs)  || (!empty($sendcertificate) && in_array($sendcertificate->state,[99])))
                                <td class="text-center div_hide">
                                    <input type="checkbox" name="lists[id][]" class="item_checkbox"  value="{!! $item->certificate_id !!}" checked>
                                </td>
                    @endif  
                                <td> 
                                        {!! $cer->name !!} <br>  {!! $cer->tax_id !!}
                                </td>
                                <td> 
                                        {!! $cer->room !!}  
                                </td>
                                <td> 
                                        {!! $cer->cer_link !!}  
                                </td>
                                <td> 
                                        {!! $cer->purpose_type !!}  
                                </td>
                                <td> 
                                        {!! $cer->accereditatio_no !!}  
                                </td>
                        @if ( (!empty($sendcertificate) && in_array($sendcertificate->state,[1,2,3])))
                                    <td> 
                                            {!! $item->SignStatusTitle ?? null !!}  
                                    </td>
                        @endif
                                <td class="text-center"> 
                                        {!! $cer->cer_pdf !!}  
                                </td>
                                <td class="text-center">
                                        {!! $cer->cer_file !!}  
                                </td>
                            </tr>
                            @endif
                        @endif
                    @endforeach
                @else
                    
                @endif


            </tbody>
        </table>
        
    </div>
</div>  

@if (!empty($sendcertificate->state) && in_array($sendcertificate->state,[1,2,3]))
        <div class="form-group">
       
                @can('view-'.str_slug('sendcertificates'))
                    <a   href="{{url('/certify/send-certificates')}}" class="btn btn-default btn-lg btn-block">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
             
        </div>
@else
    {{-- {!! Form::hidden('certificate_type', null, [ 'class' => '', 'id' => 'certificate_type' ] ) !!} --}}
    {!! Form::hidden('state', null, [ 'class' => '', 'id' => 'state' ] ) !!}
    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
            <button class="btn btn-warning div_hide" type="button" id="draft-submit">
                <i class="fa   fa-file"></i> ร่าง
            </button>
            <button class="btn btn-primary div_hide" type="button" id="button-submit">
            <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('sendcertificates'))
                <a class="btn btn-default" href="{{url('/certify/send-certificates')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
@endif

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
  <script type="text/javascript">
        $(document).ready(function () {
 

            $('#draft-submit').on('click',function(event) {
                    if($('.item_checkbox:checked').length > 0) {
                        $('#state').val('99');
                        $('#form-send-certificates').submit();
                    }else{
                        Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกใบรับรอง',
                        showConfirmButton: false,
                        timer: 1500
                        })
                    }
            });

            // $('#button-submit').on('click',function(event) {
            //         if($('.item_checkbox:checked').length > 0) {
            //             $('#state').val('1');
            //             $('#form-send-certificates').submit();
            //         }else{
            //             Swal.fire({
            //             position: 'center',
            //             icon: 'warning',
            //             title: 'กรุณาเลือกใบรับรอง',
            //             showConfirmButton: false,
            //             timer: 1500
            //             })
            //         }
            // });

            $('#button-submit').on('click', function(event) {
                if ($('.item_checkbox:checked').length > 0) {
                    // แสดง Loading Overlay
                    $.LoadingOverlay("show", {
                        image: "",
                        text: "กำลังบันทึก กรุณารอสักครู่..."
                    });

                    $('#state').val('1');

                    // ส่งฟอร์มและซ่อน Loading Overlay หลังจากสำเร็จ
                    $('#form-send-certificates').submit(function(e) {
                        e.preventDefault();

                        $.ajax({
                            url: $(this).attr('action'),
                            method: $(this).attr('method'),
                            data: $(this).serialize(),
                            success: function(response) {
                                // ซ่อน Loading Overlay หลังสำเร็จ
                                $.LoadingOverlay("hide");
                                window.location.reload();

           
                            },
                            error: function() {
                                // ซ่อน Loading Overlay หากเกิดข้อผิดพลาด
                                $.LoadingOverlay("hide");

                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }).submit();
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกใบรับรอง',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });



            $('#checkall').change(function (event) {
                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
                }
            });

            

            $('#sign_id').change(function(){
                data_value_null();
                if(!!$(this).val()){
                    $.ajax({
                        type: 'get',
                        url: "{!! url('certify/send-certificates/getsign_position') !!}" ,
                        data:{id:  $('#sign_id').val()}
                    }).done(function( object ) { 
                        if(object.message == true){
                            var  signer = object.signer;
                            $('#sign_position').val(signer.position);
                        }else{
                            data_value_null();
                        }
                    }); 

                }
            });

            $('#certificate_type').change(function(){
                    $('#myTable tbody').html('');
                    if(!!$('#sign_id').val() && !!$('#certificate_type').val()){
                        $('#myTable tbody').html('');
                        $.ajax({
                          type: 'get',
                           url: "{!! url('certify/send-certificates/getsign') !!}" ,
                           data:{
                                id:  $('#sign_id').val(), 
                                certificate_type:  $('#certificate_type').val()
                            }
                       }).done(function( object ) { 
                           if(object.message == true){
                                var  signer = object.signer;
                                var  datas = object.datas;
                          
                                if(datas.length > 0){
                                            let html = [];
                                        $.each(datas, function( index, item ) {
                                            html += '<tr>';
                                            html += '<td  class="text-center"> ';
                                                html +=  (index +1);
                                            html += '</td>';
                                            html += '<td  class="text-center">';
                                                html +=  (item.checkbox)  ;
                                            html += '</td>';
                                            html += '<td>';
                                                html +=  (item.name) +'<br/>'+  (item.tax_id) ;
                                            html += '</td>';
                                            html += '<td>';
                                                html +=  (item.room);
                                            html += '</td>';
                                            html += '<td>';
                                                html +=  (item.cer_link);
                                            html += '</td>';
                                            html += '<td>';
                                                html +=  (item.purpose_type);
                                            html += '</td>';
                                            html += '<td>';
                                                html +=  (item.accereditatio_no);
                                            html += '</td>';
                                            html += '<td class="text-center">';
                                                html +=  (item.cer_pdf);
                                            html += '</td>';
                                            html += '<td class="text-center">';
                                                html +=  (item.cer_file);
                                            html += '</td>';
                                            html += '</tr>';
                                        });  
                    
                                        $('#myTable tbody').append(html);
                                }else{ 	 
                                    let html = [];
                                    html += '<tr>';
                                            html += '<td colspan="9" class="text-center">';
                                                html += '<b style="color:#ffc107!important;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>  ไม่มีรายการ'+signer.certify+'</b>';
                                            html += '</td>';
                                            html += '</tr>';
                                    $('#myTable tbody').append(html);
                                }

                            }else{
                                $('#myTable tbody').html('');
                            }
                       }); 

                    }
                });

        });

        function data_value_null() { 
            $('#sign_position').val('');
            $('#certificate_type').val('').trigger('change');
            
            $('#myTable tbody').html('');
        }
    </script>
@endpush
