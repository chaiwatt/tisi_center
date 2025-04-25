@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">รับคำขอเป็นหน่วยตรวจสอบ (IB)</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('standard_type'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" type="button" id="btn_assign">
                                <b>มอบหมาย</b>
                            </a>
                        @endcan

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบรับคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (IB)</em></p>
                   

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขที่คำขอ/ผู้ยื่นคำขอ/เลขผู้เสียภาษี']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                            <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                            ล้าง
                                        </button>
                                    </div>
                                </div><!-- /.col-lg-1 -->

                                <div class="col-lg-5">
                                    <div class="form-group col-md-7">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_status', [ 1=> 'อยู่ระหว่างการตรวจสอบ', 2=> 'เอกสารไม่ครบถ้วน', 3 => 'เอกสารไม่ครบถ้วน', 4 => 'เอกสารไม่ครบถ้วน', 5 => 'อนุมัติ', 6 => 'ไม่อนุมัติ ตรวจสอบอีกครั้ง', 7 => 'ไม่รับคำขอ/Reject' ], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th width="2%" class="text-center">No.</th>
                                        <th width="10%" class="text-center">เลขที่คำขอ</th>
                                        <th width="17%" class="text-center">ผู้ยื่นคำขอ</th>
                                        <th width="15%" class="text-center">เลขผู้เสียภาษี</th>
                                        <th width="15%" class="text-center">เลขที่ มอก.</th>
                                        <th width="10%" class="text-center">วันที่ยื่นคำขอ</th>
                                        <th width="10%" class="text-center">สถานะ</th>
                                        <th width="13%" class="text-center">ผู้รับมอบหมาย</th>
                                        <th width="5%" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="clearfix"></div>

                    @include ('section5.accept-inspection-unit.modals')

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

    <script>
        $(document).ready(function () {
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
                ajax: {
                    url: '{!! url('/section5/accept-inspection-unit/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'refno_application', name: 'refno_application' },
                    { data: 'authorized_name', name: 'authorized_name' },
                    { data: 'authorized_taxid', name: 'authorized_taxid' },
                    { data: 'standards', name: 'standards' },
                    { data: 'date_application', name: 'date_application' },
                    { data: 'status_application', name: 'status_application' },
                    { data: 'assign_by', name: 'assign_by' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });


            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                } else {
                $(".item_checkbox").prop('checked',false);
                }
            });


            $('#btn_assign').click(function (e) {
                
                $('#m_assign_by').val('').trigger('change.select2');
                $('#m_assign_comment').val('');
                $('#MyTable-Modal tbody').html('');
                var arrRowId = [];
                var tb = '';
                //Iterate over all checkboxes in the table
                $('.item_checkbox:checked').each(function (index, rowId) {
                    arrRowId.push(rowId.value);


                    tb += '<tr data-repeater-item>';
                    tb += '<td class="text-center">'+(index + 1)+'</td>';
                    tb += '<td>'+( $(rowId).data('app_no') )+'</td>';
                    tb += '</tr>';
                });

                if (arrRowId.length > 0) {

                    $('#MyTable-Modal tbody').append(tb);
                    $('#modal-assign').modal('show');
                }else {
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }
                
            });

            $('#btn_save_modal').click(function (e) {

                var assign_by = $('#m_assign_by').val();
                var assign_commen = $('#m_assign_comment').val();

                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });


                if( checkNone(assign_by)  ){

                    $.ajax({
                        type:"POST",
                        url:  "{{ url('/section5/accept-inspection-unit/assing_data_update') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: id,
                            assign_by: assign_by,
                            assign_commen: assign_commen
                        },
                        success:function(data){

                            if( data == "success"){

                                $.toast({
                                    heading: 'Success!',
                                    position: 'top-center',
                                    text: 'มอบหมายสำเร็จ !',
                                    loaderBg: '#70b7d6',
                                    icon: 'success',
                                    hideAfter: 3000,
                                    stack: 6
                                });
                   
                                $('#checkall').prop('checked', false);
                                $(".item_checkbox").prop('checked',false);
                                $('#modal-assign').modal('hide');

                                table.draw();
                                // $('#myFilter').submit();
                            }else{

                                $.toast({
                                    heading: 'Error!',
                                    position: 'top-center',
                                    text: 'มอบหมายไม่สำเร็จ !',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 3000,
                                    stack: 6
                                });

                            }


                        }
                    });

                }else{
                    alert("โปรดเลือกผู้รับมอบหมาย ?"); 
                }


            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search').val('');
                $('#filter_status').val('').select2();
                table.draw();
            });

            

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script> 
@endpush