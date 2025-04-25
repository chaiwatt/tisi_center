@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

@endpush

@section('content')
 
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
      <div class="col-sm-12">
          <div class="white-box">

    
              <h3 class="box-title pull-left">กลุ่มงาน</h3>

              <div class="pull-right">

                  @can('edit-'.str_slug('bsection5-workgroup'))

                      <button class="btn btn-success btn-outline waves-effect waves-light" type="button" name="publish" id="publish"><span class="btn-label"><i class="fa fa-check"></i></span>เปิด</button>
                      <button class="btn btn-danger btn-outline waves-effect waves-light" type="button" name="no_publish" id="no_publish"><span class="btn-label"><i class="fa fa-close"></i></span>ปิด</button>

                  @endcan

                  @can('add-'.str_slug('bsection5-workgroup'))
                      <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/bsection5/workgroup/create') }}">
                          <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                      </a>
                  @endcan

                  @can('delete-'.str_slug('bsection5-workgroup'))
                      <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" id="bulk_delete">
                          <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                      </a>
                  @endcan

              </div>
              <hr class="hr-line bg-primary">
              <div class="clearfix"></div>

              <div class="row">
                  <div class="col-md-12">

                      <div class="row">
                          <div class="col-lg-3">
                              <div class="form-group">
                                  {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อกลุ่ม/ผู้สร้าง']); !!}
                              </div><!-- /form-group -->
                          </div><!-- /.col-lg-4 -->

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
                                      {!! Form::select('filter_status', [ 1=> 'เปิดใช้งาน', 2=> 'ปิดใช้งาน' ], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-','id'=>'filter_status']); !!}
                                  </div>
                              </div>
                          </div><!-- /.col-lg-5 -->
                      </div><!-- /.row -->

                  </div>
              </div>
              <div class="clearfix"></div>

              <div class="row">
                  <div class="col-md-12">
                      <table class="table table-striped" id="myTable">
                          <thead>
                              <tr>
                                  <th width="2%"><input type="checkbox" id="checkall"></th>
                                  <th width="2%"  class="text-center">No.</th>
                                  <th width="20%" class="text-center">ชื่อกลุ่ม</th>
                                  <th width="20%" class="text-center">ผู้สร้าง</th>
                                  <th width="20%" class="text-center">วันที่สร้าง</th>
                                  <th width="5%"  class="text-center">สถานะ</th>
                                  <th width="10%" class="text-center">จัดการ</th>             
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
                    url: '{!! url('/bsection5/workgroup/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();                     
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'title', name: 'title' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1,-2] }
                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });


            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                } else {
                $(".item_checkbox").prop('checked',false);
                }
            });


            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search').val('');
                table.draw();
            });

            
            //เลือกสถานะ
            $('#myTable tbody').on('change', '.js-switch', function(){
                var id_status = $(this).val();
                if ($(this).is(":checked")) {
                    $.ajax({
                        method: "POST",
                        url: "{{ url('bsection5/test_item/update_status') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_status": id_status,
                            "state":1
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            table.draw();
                        }
                    });
                } else {
                    $.ajax({
                         method: "POST",
                         url: "{{ url('bsection5/test_item/update_status') }}",
                         data: {
                            "_token": "{{ csrf_token() }}",
                            "id_status": id_status,
                            "state":0
                         }
                    }).success(function (msg) {
                        if (msg == "success") {
                            table.draw();
                        }
                    });
                }
            });

            //เลือกลบ
            $(document).on('click', '#bulk_delete', function(){

                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                if(id.length > 0){

                    if(confirm("ยืนยันการลบข้อมูล " + id.length + " แถว นี้ ?")){
                        $.ajax({
                            type:"POST",
                            url:  "{{ url('/bsection5/test_item/delete') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success:function(data){
                                table.draw();
                                toastr.success('ลบสำเร็จ !');
                                $('#checkall').prop('checked', false);
                            }
                        });
                    }

                }else{
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }
            });

            //เลือกเปิด-ปิด
            $('#publish, #no_publish').on('click', function () {

                    if($(this).attr('id')=='publish'){
                        var text_alert = 'เปิด';
                        var state = 1;
                    }else if($(this).attr('id')=='no_publish'){
                        var text_alert = 'ปิด';
                        var state = 0;
                    }

                    var arrRowId = [];

                    //Iterate over all checkboxes in the table
                    table.$('.item_checkbox:checked').each(function (index, rowId) {
                        arrRowId.push(rowId.value);
                    });

                    if (arrRowId.length > 0) {

                        if (confirm("ยืนยันการ"+text_alert+" " + arrRowId.length + " แถว นี้ ?")) {

                            $.ajax({
                                method: "POST",
                                url: "{{ url('/bsection5/test_item/update_publish') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id_publish": arrRowId,
                                    "state": state
                                },
                                success : function (msg){
                                    if (msg == "success") {
                                        table.draw();
                                        if(text_alert == 'เปิด'){
                                            toastr.success('เปิดการใช้งาน !');
                                        }else{
                                            toastr.error('ปิดการใช้งาน !');
                                        }
                                        $('#checkall').prop('checked',false );
                                    }
                                }
                            });
                        }

                    }else {
                        alert("โปรดเลือกอย่างน้อย 1 รายการ");
                    }
            });
            

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }


    </script> 
@endpush