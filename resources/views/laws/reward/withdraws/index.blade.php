@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <style>
        .has-dropdown {
            position: relative;
        }
        .show_status {
          border: 2px solid #00BFFF;
          padding: 0px 7px;
          -webkit-padding: 0px 7px;
          -moz-padding: 0px 7px;
          border-radius: 25px;
          -webkit-border-radius: 25px;
          -moz-border-radius: 25px;
          width: auto;
    }
    .not-allowed {
           cursor: not-allowed
       }
 
       .rounded-circle {
        border-radius: 50% !important;
    }
        .download {
            color: blue;
        }
        /* mouse over link */
        .download:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }
    

    </style>
@endpush

@section('content')

 
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">เบิกเงินรางวัล</h3>

                    <div class="pull-right">
                           @can('add-'.str_slug('law-reward-withdraws'))
                                <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/reward/withdraws/create') }}">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                </a>
                            @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <div class="form-group col-md-6">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขอ้างอิงการเบิกจ่าย'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขอ้างอิงการเบิกจ่าย']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                            ล้างค่า
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                                {!! Form::select('filter_status',  [ 1 => 'อยู่ระหว่างเบิกจ่าย', 2 => 'เบิกจ่ายเรียบร้อย'], null, ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="row">
                        <div class="col-md-12"> 
     
                            <table class="table table-striped"  id="myTable" >
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="15%">เลขอ้างอิงการเบิกจ่าย</th>
                                        <th class="text-center" width="10%">จำนวนคดี</th>
                                        <th class="text-center" width="10%">รูปแบบ</th>
                                        <th class="text-center" width="10%">จำนวนเงิน</th>
                                        <th class="text-center" width="12%">สถานะ</th>
                                        <th class="text-center" width="13%">ผู้บันทึก/วันที่บันทึก</th>
                                        <th class="text-center" width="15%">จัดการ</th> 
                                        <th class="text-center" width="15%">พิมพ์</th> 

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

 
    @include('laws.reward.withdraws.modals.form')


@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        var table = '';
        $(document).ready(function () {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif
            
            //สิทธิ์การพิมพ์
            var permisionprinting = "{!! auth()->user()->can('printing-'.str_slug('law-reward-withdraws'))?true:false !!}";

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/reward/withdraws/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'reference_no', name: 'reference_no' },
                    { data: 'number', name: 'number' },
                    { data: 'type', name: 'type' },
                    { data: 'amounts', name: 'amounts' },
                    { data: 'status', name: 'status' }, 
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                    { data: 'evidence', name: 'evidence' }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,2,-1,-2,-4] },
                    { className: "text-right  text-top", targets:[4] },
                    { className: "text-top", targets: "_all" },
                    { className: "text-center", visible: permisionprinting, targets: -1 },

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });

                $("body").on("click", ".withdraws", function() {

                 $('#withdraws_id').val($(this).data('id')); 
                 
                 if(checkNone($(this).data('status')) && $(this).data('status') == '2'){
                    $("#button_save").prop('disabled', true);  
                    $('#WithdrawsModals').find('input, select, textarea').prop('disabled', true);
                }else{
                    $("#button_save").prop('disabled', false);
                    $('#WithdrawsModals').find('input, select, textarea').prop('disabled', false);

                }
                if(checkNone($(this).data('approve_date'))){
                    $('#approve_date').val($(this).data('approve_date'));  
                }else{
                    $('#approve_date').val('');   
                }
                if(checkNone($(this).data('approve_remark'))){
                    $('#approve_remark').val($(this).data('approve_remark'));  
                }else{
                    $('#approve_remark').val('');   
                }
                  $("#approve_status").attr('data-emails', $(this).data('approve_emails'));
                if(checkNone($(this).data('approve_status')) && $(this).data('approve_status') == '1'){
                      $("#approve_status").prop('checked', true);
                }else{
                     $("#approve_status").prop('checked', false);
                }
                if(checkNone($(this).data('approve_emails')) && checkNone($(this).data('approve_status')) && $(this).data('approve_status') == '1'){
                    $('#approve_emails').tagsinput('add', $(this).data('approve_emails')); 
                }else{
                    $('#approve_emails').tagsinput('removeAll'); 
                }

                if(checkNone($(this).data('url')) && checkNone($(this).data('filename'))){
                    $('#div_attach').hide();  
                    var url = '{{ url('funtions/get-law-view/files') }}';
                    $('#span_attach').html('<a   href="'+url +'/'+   $(this).data('url') +'/'+ $(this).data('filename') +'"   class="link_file"  target="_blank"> '+ $(this).data('filename') +'</a>');   
                }else{
                    $('#div_attach').show();  
                    $('#span_attach').html('');  
                }
                
                $('#WithdrawsModals').modal('show');
            });
   
           

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

          


            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });


        });
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        function basename(path) {
                return path.split('/').reverse()[0];
       }

    </script>

@endpush

 