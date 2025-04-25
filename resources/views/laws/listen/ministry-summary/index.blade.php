@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
    .bg-rgba-warning {
        background: rgba(253, 172, 65, 0.2) !important;
    }

    .bg-rgba-warning.alert {
        color: #FDAC41;
    }

    .bg-rgba-warning.alert.alert-dismissible .close {
        color: #e7b16b;
    }
    .alert.alert-dismissible .close {
        color: #FFFFFF;
        opacity: 1;
        top: -4px;
        text-shadow: none;
        font-weight: normal;
        font-size: 1.5rem;
    }

    .alert.alert-dismissible .close:focus {
        outline: none;
    }
    .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
    .bootstrap-tagsinput {
        min-height: 70px;
        border-radius: 0;
        width: 100% !important;
        -webkit-border-radius: 7px;
        -moz-border-radius: 7px;
    }
    .bootstrap-tagsinput input {
        padding: 6px 6px;
    }
    .note-editor.note-frame {
        border-radius: 4px !important;
    }

    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">สรุปความเห็นร่างกฏกระทรวง</h3>
                
                    <div class="pull-right">
                        @can('add-'.str_slug('law-listen-ministry-response'))
                            <button type="button"  class="btn btn-primary waves-effect waves-light"  id="ButtonModalClose">
                                <span class="btn-label"><i class="mdi mdi-close-outline"></i></span>ปิดประกาศฯ
                            </button>
                        @endcan
                        @can('printing-'.str_slug('law-listen-ministry-response'))
                            <button type="button"  class="btn btn-success waves-effect waves-light"  id="ButtonPrintExcel">
                                <span class="btn-label"><i class="mdi mdi-file-excel"></i></span>Excel
                            </button>
                        @endcan
                        @can('edit-'.str_slug('law-listen-ministry-response'))
                            <button type="button"  class="btn btn-warning waves-effect waves-light"  id="ButtonModalResult">
                                <span class="btn-label"><i class="mdi mdi-library-books"></i></span>แจ้งผลวินิจฉัย
                            </button>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                             <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ชื่อเรื่องประกาศ', '3' => 'มาตรฐาน (มอก.)'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search']); !!}
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
                                    <div class="form-group pull-left m-l-15">
                                        <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                        {!! Form::select('filter_status', App\Models\Law\Listen\LawListenMinistry::list_status(), null, ['class' => 'form-control  text-center', 'id' => 'filter_status', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_standard', 'เลขที่ มอก. / ชื่อ มอก. ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                            {!! Form::select('filter_standard',App\Models\Law\Listen\LawListenMinistry::selectRaw('CONCAT(tis_no," : ",tis_name) As tis_title, id')->pluck('tis_title', 'id'), null, ['class' => 'form-control  text-center', 'id' => 'filter_standard', 'placeholder'=>'-เลือก มอก.-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_access', 'วันที่ประกาศ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                                                    <div class="input-daterange input-group date-range">
                                                        {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center"  rowspan="2" width="2%"><input type="checkbox" id="checkall"></th>
                                        <th class="text-center"  rowspan="2" width="2%"> #</th>
                                        <th class="text-center"  rowspan="2" width="8%">เลขที่อ้างอิง/วันที่ประกาศ</th>
                                        <th class="text-center"  rowspan="2" width="20%">ชื่อเรื่องประกาศ</th>
                                        <th class="text-center"  rowspan="2" width="12%">สถานะ</th>     
                                        <th class="text-center"  colspan="4" width="30%">ความเห็น</th>
                                        <th class="text-center"  rowspan="2" width="7%">รวม</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" width="10%">เห็นชอบให้บังคับ<br>ตามร่างกฎกระกระทรวงฯ ทุกประการ</th>
                                        <th class="text-center" width="10%">ไม่เห็นชอบให้บังคับ<br>ตามร่างกฎกระกระทรวงฯ</th>
                                        <th class="text-center" width="10%">เห็นชอบ<br>กับการขยายระยะเวลา</th>
                                        <th class="text-center" width="10%">ไม่เห็นชอบ<br>กับการขยายระยะเวลา</th>
                                    </tr>                                        
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    @include ('laws.listen.ministry-summary.modal-close')
    @include ('laws.listen.ministry-summary.modal-result')

@endsection

@push('js')
    <script src="{{ asset('plugins/components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/components/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/components/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/components/datatables-api-sum/api/sum().js') }}"></script>
    <script src="{{ asset('js/function.js') }}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            //modalแจ้งผลวินิจฉัย
            $("body").on("click", "#ButtonModalResult", function() {
                var ids = [];
                  $('.item_checkbox:checked').each(function(index, element){
                    ids.push($(element).val());
                  });

                if(ids.length > 0){
                    $("#result_ids").val(ids);
                    $('#ResultModals').modal('show');
                    LoadSelectMail(ids);
                }else{
                    $('#ResultModals').modal('hide');
                    Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'กรุณาเลือกเลขที่อ้างอิง',
                                showConfirmButton: false,
                                timer: 1500
                    });
                }
            });

            $('#btn_send_mail').click(function(){  
                $("#box_mail_list").toggle(400);
            });

            //modalปิดประกาศ
            $("body").on("click", "#ButtonModalClose", function() {
                var ids = [];
                  $('.item_checkbox:checked').each(function(index, element){
                    ids.push($(element).val());
                  });

                if(ids.length > 0){
                    $("#close_ids").val(ids);
                    $('#CloseModals').modal('show');
                }else{
                    $('#CloseModals').modal('hide');
                    Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'กรุณาเลือกเลขที่อ้างอิง',
                                showConfirmButton: false,
                                timer: 1500
                    });
                }
            });
    
            //ปฎิทิน
            $('.date-range').datepicker({
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

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry-summary/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_standard = $('#filter_standard').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false },
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'comment1', name: 'comment1' },
                    { data: 'comment2', name: 'comment2' },
                    { data: 'comment3', name: 'comment3' },
                    { data: 'comment4', name: 'comment4' },
                    { data: 'comment_amonut', name: 'comment_amonut' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,1,-1,-2,-3,-4,-5,-6] },
                    { className: "text-top", targets: [0,1,2,3,4,5,6,7] }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    var api    = this.api();

                    var col5   =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var col6   =  api.column( 6, {page:'current'} ).data().sum().toFixed(2);
                    var col7   =  api.column( 7, {page:'current'} ).data().sum().toFixed(2);
                    var col8   =  api.column( 8, {page:'current'} ).data().sum().toFixed(2);
                    var col9   =  api.column( 9, {page:'current'} ).data().sum().toFixed(2);

                    var html   = '';
                        html   += '<tr>';
                        html   += '<td class="text-center" colspan="5"><b>รวม</b></td>';
                        html   += '<td class="text-top text-center"> <b>'+  addCommas(col5)  +'</b></td>';
                        html   += '<td class="text-top text-center"> <b>'+  addCommas(col6)  +'</b></td>';
                        html   += '<td class="text-top text-center"> <b>'+  addCommas(col7)  +'</b></td>';
                        html   += '<td class="text-top text-center"> <b>'+  addCommas(col8)  +'</b></td>';
                        html   += '<td class="text-top text-center"> <b>'+  addCommas(col9)  +'</b></td>';
                        html   += '<tr>';

                    $(api.table().footer()).html( html );

                }
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
                $('#filter_standard').select2('val','');
                table.draw();
            });

            $('#ButtonPrintExcel').on('click', function() {
                var url = 'law/listen/ministry-summary/export_excel';
                    url += '?filter_search=' + $('#filter_search').val();
                    url += '&filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_status=' + $('#filter_status').val();
                    url += '&filter_standard=' + $('#filter_standard').val();
                    url += '&filter_start_date=' + $('#filter_start_date').val();
                    url += '&filter_end_date=' + $('#filter_end_date').val();

                    window.location = '{!! url("'+url +'") !!}';
            });


        });

        function LoadSelectMail(ids){
            if (ids.length > 0) {
                $("#mail_list").tagsinput('removeAll');
                $.ajax({
                    method: "get",
                    url: "{{ url('law/listen/ministry-summary/select_mail') }}",
                    data: {
                        "listen_id": ids
                    }
                }).done(function( object ) {
                    $.each(object, function( index, data ) {
                        $("#mail_list").tagsinput('add',data.email);

                    });

                });

            }
        }
 

    </script>

@endpush
