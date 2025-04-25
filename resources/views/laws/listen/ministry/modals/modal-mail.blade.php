<div class="modal fade" id="exampleModal">
    <div  class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel1">ข้อมูลผู้มีส่วนได้เสีย</h4>
            </div>
            <div class="modal-body">
                <div class="white-box">
                <div class="row">
                    <div class="col-sm-12">
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12" id="BoxSearching">
                                        <div class="form-group ">
                                            {!! Form::label('filter_type', 'เลือกจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('filter_type', [ 1=> 'หน่วยงานผู้มีส่วนได้เสีย', 2=> 'ผู้ได้รับใบอนุญาต'],null, ['class' => 'form-control  text-center', 'id' => 'filter_type']); !!}

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('filter_standard', 'มอก. ที่เกี่ยวข้อง', ['class' => 'col-md-2 control-label text-right']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('filter_standard', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา มอก.-', 'id' => 'filter_standard']); !!}
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group  pull-left">
                                                <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search"></i> ค้นหา</button>
                                            </div>
                                            <div class="form-group  pull-left m-l-15">
                                                <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                                    ล้างค่า
                                                </button>
                                            </div>

                                        </div>
                                        </div>
                               
                                    </div>
                                </div>
                            <div class="clearfix"></div>
        
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered color-bordered-table info-bordered-table" id="myTableMail">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="2%"><input type="checkbox" id="checkall"></th>
                                                <th class="text-center" width="2%">#</th>
                                                <th class="text-center" width="20%">มอก.</th>
                                                <th class="text-center" width="25%">ชื่อผู้ประกอบการ</th>
                                                <th class="text-center" width="20%">อีเมล</th>
                                                <th class="text-center" width="35%">ที่ตั้งสำนักงานใหญ่</th>
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
                    <div class="text-center">
                        <button type="button"class="btn btn-primary" id="btn-modal"><i class="icon-check"></i> เลือก</button>
                    </div>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>
        $(document).ready(function() {

            $("#filter_standard").select2({
                dropdownAutoWidth: false,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,

            });

            $('#btn_search').click(function () {
                $('#myTableMail').DataTable().draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_standard').select2('val','');
                $('#myTableMail').DataTable().draw();
            });
            
            $('#filter_type').change(function(){ //เลือกรายชื่อส่งเมล

                $('#myTableMail').DataTable().destroy();
                if($(this).val() == 2){//ผู้ได้รับใบอนุญาต
                    LoadDataTb4Tisilicense();
                }else{//หน่วยงานผู้มีส่วนได้เสีย
                    LoadDepartmentTakeholder();
                }
            });
            LoadDepartmentTakeholder();

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

        });
        function LoadDataTb4Tisilicense(){
              
            $('#myTableMail').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry/data_tb4_tisilicense') !!}',
                    data: function (d) {
                        d.filter_standard         = $('#filter_standard').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false  },
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tisi_no', name: 'tisi_no' },
                    { data: 'title', name: 'title' },
                    { data: 'email', name: 'email' },
                    { data: 'contact', name: 'contact' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,1] }
                ]
            });
          
        }
      
        function LoadDepartmentTakeholder(){
                    
            $('#myTableMail').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry/data_department_takeholder') !!}',
                    data: function (d) {
                        d.filter_standard         = $('#filter_standard').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false  },
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tisi_no', name: 'tisi_no' },
                    { data: 'title', name: 'title' },
                    { data: 'email', name: 'email' },
                    { data: 'contact', name: 'contact' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,1] }
                ]
            });
                
        }
      
    </script>
@endpush