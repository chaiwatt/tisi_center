@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานประวัติการส่งอีเมล</h3>
 
                    <hr class="hr-line">
                    <div class="clearfix"></div>
             
                    <div class="row ">
                        <div class="col-md-7 form-group">
                          <div class=" {{ $errors->has('filter_search') ? 'has-error' : ''}}">
                              {!! Form::label('filter_search', 'คำค้น'.' :', ['class' => 'col-md-2 control-label text-right ']) !!}
                              <div class="col-md-10">
                                  {!! Form::text('filter_search', null,  ['id' => 'filter_search','class' => 'form-control']) !!}
                              </div>
                           </div>
                        </div> 
                         <div class="col-md-3">
                                {!! Form::select('filter_certify',
                                 ['1'=>'ห้องปฏิบัติการ','2'=>'หน่วยตรวจสอบ','3'=>'หน่วยรับรอง','4'=>'ห้องปฏิบัติการ(ต่อตาม)','5'=>'หน่วยตรวจสอบ(ต่อตาม)','6'=>'หน่วยรับรอง(ต่อตาม)'], 
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
                                        <th width="19%" class="text-center">เลขที่คำขอ</th>
                                        <th width="40%" class="text-center">ชื่อผู้ประกอบการ</th>
                                        <th width="25%" class="text-center">กลุ่มระบบงาน</th>
                                        <th width="10%"  class="text-center">จำนวน</th>
                                        <th width="5%"  class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
      
                                </tbody>
                            </table>
      
                        </div>
                    </div>
      
                    <div class="clearfix"></div>
      
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
        });

        $(function () {

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/cerreport/certify-applicant/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_certify = $('#filter_certify').val();           
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'app_no', name: 'app_no' },
                    { data: 'name', name: 'name' },
                    { data: 'certify', name: 'certify' },
                    { data: 'number', name: 'number' },
                    { data: 'action', name: 'action' }
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1,-2] }
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
