@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 <style>

.send_sign {cursor: pointer;}
 </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานข้อมูลใบรับรองระบบงาน</h3>
 
                    <hr class="hr-line">
                    <div class="clearfix"></div>
             
                    <div class="row ">
                        <div class="col-md-7 form-group">
                          <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                              {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-2 control-label text-right ']) !!}
                              <div class="col-md-10">
                                  {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control', 'placeholder' => 'ค้นหาจาก ชื่อหน่วยงาน, เลขที่ใบรับรอง, หมายเลขการรับรอง, เลขที่คำขอ']) !!}
                              </div>
                           </div>
                        </div> 
                         <div class="col-md-3">
                                {!! Form::select('filter_certify',
                                 ['1'=>'หน่วยรับรอง','2'=>'หน่วยตรวจสอบ','3'=>'ห้องปฏิบัติการ'], 
                                  null, 
                                  ['class' => 'form-control', 
                                  'id'=>'filter_certify',
                                  'placeholder' => '- เลือกกลุ่มระบบงาน -']); !!}
                        </div> 
                        <div class="col-md-2">
                            <div class="  pull-left">
                              <button type="button" class="btn btn-info waves-effect waves-light" id="button_search"  style="margin-bottom: -1px;">ค้นหา</button>
                          </div>
                          <div class="  pull-left m-l-15">
                              <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                  ล้าง
                              </button>
                          </div>
                        </div> 
                    </div> 
            
 
                    <div class="clearfix"></div>
      
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%"  class="text-center">No.</th>
                                        <th width="10%" class="text-center">เลขที่คำขอ</th>
                                        <th width="12%" class="text-center">หมายเลขการรับรอง</th>
                                        <th width="12%" class="text-center">เลขที่ใบรับรอง</th>
                                        <th width="" class="text-center">ชื่อผู้ประกอบการ</th>
                                        <th width="13%" class="text-center">กลุ่มระบบงาน</th>
                                        <th width="8%"  class="text-center">ใบรับรอง</th>
                                        <th width="8%"  class="text-center">ออกให้ ณ วันที่</th>
                                        <th width="8%"  class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
      
                                </tbody>
                            </table>
      
                        </div>
                    </div>
      
                    <div class="clearfix"></div>
                    <!-- ปิดงาน -->
                    @include('cerreport.system-certification.modals')
                    @include('cerreport.system-certification.modals-cancel')
                </div>
            </div>
        </div>
      
      </div>
@endsection
@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script>
        var _modal = '#cancelModals'; 
        var form_modal = '#form-cancel';
        $(document).ready(function () {

            $(document).on('click', '#ButtonPrintExcel', function(){
                var url = 'cerreport/certificates/export_excel';
                    url += '?filter_search=' + $('#filter_search').val();
                    url += '&filter_certificate_type=' + $('#filter_certificate_type').val();
                    window.location = '{!! url("'+url +'") !!}';
                });


                  //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif

            @if ( \Session::has('success_message'))
                Swal.fire({
                    position: 'center',
                    width: 600,
                    icon: 'success',
                    title: 'ลงนามใบนุญาตสำเร็จ',
                    html: '{!! session()->get('success_message') !!}',
                    showConfirmButton: false,
                });
            @endif


        });

        $(function () {



            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('cerreport/system-certification/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_certify = $('#filter_certify').val();           
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'app_no', name: 'app_no' },
                    { data: 'accereditatio_no', name: 'accereditatio_no' },
                    { data: 'certificate_no', name: 'certificate_no' },
                    { data: 'name', name: 'name' }, 
                    { data: 'certify', name: 'certify' },
                    { data: 'certification', name: 'certification' },
                    { data: 'date_start', name: 'date_start' },
                    { data: 'action', name: 'action' }
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2,-3] },
                    { className: "text-top", targets: "_all" }
                ],
                fnDrawCallback: function() {
                    
                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);
                    $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
                }
            });
 
            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
              $('#filter_search').val('');
              $('#filter_certify').val('').select2();
                table.draw();
 
           });

       
           $("body").on("click", ".btn-cancel", function() {
                $(form_modal).find('input, textarea').val('');
                $(form_modal).parsley().reset();
                 $('#certificate_no').html( $(this).data('certificate_no'));
                 $('#certificate_type').val( $(this).data('certificate_type'));
                 $('#certificate_id').val( $(this).data('id'));
                $(_modal).modal('show');
           });

           $(form_modal).parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
                })
                .on('form:submit', function() {

                 Swal.fire({
                    title: 'ยืนยันการยกเลิกใบรับรอง',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#e74a25',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {

                                // Custom
                                $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กําลังอยู่ระหว่างยกเลิกลงนามใบรับรอง กรุณารอสักครู่..." 
                                });

                        $.ajax({
                            data: {
                            "_token": "{{ csrf_token() }}",
                            "certificate_type": $('#certificate_type').val(),
                            "certificate_id": $('#certificate_id').val(),
                            "remark": $('#remark').val()
                            },
                            url: $(form_modal).prop('action'),
                            type: 'POST'
                            }).done(function( object ) {
                                $.LoadingOverlay("hide");
                                  if(object.message == true){
                                    var obj = object.datas;
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'ยกเลิกลงนามใบรับรองสำเร็จ',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });	
                                    table.draw();
                                    $(_modal).modal('hide');
                                  }else{

                                    Swal.fire({
                                        position: 'center',
                                        width: 600,
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });	
                                    table.draw();
                                    $(_modal).modal('hide');
                                  }
                            });
                   

                    }
                });

                return false; // Don't submit form for this demo
            });


 
 
            $('body').on('click', '.send_sign', function(){
          
                var certificate_type        = $(this).data('certificate_type');
                var certificate_id          = $(this).data('certificate_id');
                    Swal.fire({
                        title: 'ยืนยันการส่งลงนามใหม่',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00ff00',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                        if (result.isConfirmed) {
                                // Custom
                                $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กําลังอยู่ระหว่างลงนามใบรับรอง กรุณารอสักครู่..." 
                                });


                            $.ajax({
                                type: 'get',
                                url: "{!! url('certify/sign-certificates/save_new_cer') !!}" ,
                                data: {
                                    certificate_type:certificate_type,
                                    certificate_id:certificate_id,
                                    type:"F"
                                },
                            }).done(function( object ) {
                                $.LoadingOverlay("hide");
                                  if(object.message == true){
                                    var obj = object.datas;
                                    Swal.fire({
                                        position: 'center',
                                        width: 600,
                                        icon: 'success',
                                        title: 'ลงนามใบนุญาตสำเร็จ',
                                        html:
                                        'DocumentID :' +  obj.documentId + '<br/>' +
                                        'SignatureID :' + obj.signtureid  ,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });	
                                    table.draw();
                                    $('#cancelModals').modal('hide');
                                  }else{

                                    Swal.fire({
                                        position: 'center',
                                        width: 600,
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });	
                                    table.draw();
                                    $('#cancelModals').modal('hide');
                                  }
                            });

                 }});

            });


            $("body").on("click", ".log_cer_moao", function() {
                var certificate_type        = $(this).data('certificate_type');
                var certificate_id          = $(this).data('certificate_id');
                $('#table_tbody_close').html('');
                           var url 		            =  "{{ url('/') }}";
                         // Custom
                      $.LoadingOverlay("show", {
                                    image       : "",
                                    text  : "กําลังดาวน์โหลด กรุณารอสักครู่..." 
                                });

                      $.ajax({
                                type: 'get',
                                url: "{!! url('cerreport/system-certification/datas_cer') !!}" ,
                                data: {
                                    certificate_type:certificate_type,
                                    certificate_id:certificate_id,
                                    type:"F"
                                },
                            }).done(function( object ) {
                                $.LoadingOverlay("hide");
                                  if(object.message == true){
                                 
                                       $.each(object.datas, function (key, value) {
                                                        var html_add_item = '<tr class="sub-detail">';

                                                    html_add_item += '<td  class="text-center">';
                                                    html_add_item += ''+ (key +1) +'';
                                                    html_add_item += '</td>';

                                                    html_add_item += '<td class="text-center">';
                                                        if(checkNone(value.certificate_oldfile)){
                                                            html_add_item += '<a href="'+url+'/funtions/get-view/'+value.certificate_path+'/'+value.certificate_oldfile+'/'+value.certificate_no+'"   target="_blank"> <img src="'+url+'/images/icon-certification-black.jpg"   width="22"/></a>';
                                                        }
                                                        html_add_item += '</td>';

                                                    html_add_item += '<td  class="text-center">';
                                                        if(checkNone(value.certificate_newfile)){
                                                            html_add_item += '<a href="'+url+'/funtions/get-view/'+value.certificate_path+'/'+value.certificate_newfile+'/'+value.certificate_no+'"   target="_blank"> <img src="'+url+'/images/icon-certification.jpg"   width="22"/></a>';
                                                        }
                                                    html_add_item += '</td>';

                                                    html_add_item += '<td>';
                                                    html_add_item += ''+  value.user_created + '';
                                                    html_add_item += '</td>'; 

                                                    html_add_item += '<td>';
                                                    html_add_item += ''+  value.date_revoke  +'';
                                                    html_add_item += '</td>';
                                                    html_add_item += '</tr>';
                                                 $('#table_tbody_close').append(html_add_item);
                                       }); 
                                          $('#LogModals').modal('show');
                                  }else{

                                            Swal.fire({
                                                position: 'center',
                                                width: 600,
                                                icon: 'error',
                                                title: 'เกิดข้อผิดพลาด',
                                                showConfirmButton: false,
                                                timer: 2000
                                            });	
                                        
                                  }
                            });



                });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }

    </script> 
@endpush
