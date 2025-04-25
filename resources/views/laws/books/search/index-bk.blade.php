@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .faq-header {
            background-color: rgba(90,141,238,.08);
            border-radius: .25rem;
            min-height: 270px !important;
            overflow: hidden;
            position: relative;
            /* background: url("{{asset('images/laws/vuesax-login-bg.jpg')}}") repeat; */
        }

        .align-items-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }
        .justify-content-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }
        .flex-column {
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .d-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }

        .faq-bg{
            background: url("{{asset('images/laws/vuesax-login-bg.jpg')}}") repeat;
        }

        td.details-control {
            background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
        }

    </style>
@endpush

@section('content')

    <div class="container-fluid">

        <!-- .row -->
        <div class="row p-10">
            <div class="col-md-12">
                <div class="faq-header faq-bg d-flex flex-column justify-content-center align-items-center">
                    <h1 class="text-dark"><b>สืบค้นข้อมูลห้องสมุด</b></h1>
                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::select('filter_group', App\Models\Law\Basic\LawBookGroup::pluck('title', 'id'), null, ['class' => 'form-control ', 'id' => 'filter_group', 'placeholder'=>'- หมวดหมู่ -']); !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::select('filter_type', App\Models\Law\Basic\LawBookType::pluck('title', 'id') , null, ['class' => 'form-control ', 'id' => 'filter_type', 'placeholder'=>'- ประเภท -']); !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder' => 'ค้นหา', 'id' => 'filter_search']); !!}
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-success waves-effect waves-light" id="btn_search">ค้นหา</button>
                                        <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row p-10">
            <div class="col-md-3">
                <div class="">
                    <div class="mb-2 mb-md-0">
                        <ul class="list-unstyled">

                            @php
                                $book_type = App\Models\Law\Basic\LawBookType::withCount('law_book_manage')->get();
                            @endphp

                            @foreach ( $book_type  as $type )
                                <li>
                                    <div class="checkbox checkbox-circle checkbox-primary">
                                        <input class="filter_tap_type" id="tap_type_{!! $type->id !!}" type="checkbox" value="{!! $type->id !!}">
                                        <label class="h4 text-dark" for="tap_type_{!! $type->id !!}">&nbsp; <i class="mdi {!! $type->icons !!}"></i> {!! $type->title !!} ({!! $type->law_book_manage_count !!})</label>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="white-box">

                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="2%"></th>
                                    <th class="text-center">
                                        รายการ
                                    </th>
                                    <th width="15%" class="text-center">วันที่เผยแพร่</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

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

            table = $('#myTable').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/book/search/data_list') !!}',
                    data: function (d) {
                        d.filter_search   = $('#filter_search').val();
                        d.filter_group    = $('#filter_group').val();
                        d.filter_type     = $('#filter_type').val();
                        d.filter_tap_type =  Getvalue();
                    }
                },
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        searchable: false,
                        data:  null,
                        defaultContent: ''
                    },
                    { data: 'title', searchable: false, orderable: false},
                    { data: 'date_publish', searchable: false, orderable: false},

                ],
                columnDefs: [
                    { className: "text-top", targets: "_all" },
                    { className: "text-center text-top", targets:[0,-1] },


                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });

            $('#myTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                var id = tr.find('.item_checkbox').val();

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {

                    var view = tr.find('.box_details').html();
                    row.child( view ).show();
                    tr.addClass('shown');

                }
            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });


            $('#btn_clean').click(function () {
                $('#filter_search,#filter_created_at').val('');
                $('#filter_status').val('').trigger('change.select2');
                table.draw();
            });

            $('.filter_tap_type').change(function (e) {
                table.draw();
            });

            $('#filter_group').change(function (e) {
                $('#filter_type').html('<option value=""> -ประเภท- </option>');
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('/law/funtion/get-book-type') !!}" + "?id=" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_type').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }

            });
        });

        function Getvalue(){
            var arrRowId = [];
            $('input.filter_tap_type:checked').each(function (index, rowId) {
                arrRowId.push(rowId.value);
            });
            return arrRowId;
        }

        function template(id) {

            if(id!=""){
                var html = "";
                $.ajax({
                    url: "{!! url('/law/book/search/get-book-data') !!}" + "?id=" + id
                }).done(function( object ) {
                    // html = object.html();
                    console.log(object.title);
                    html += object.title;
                });

                console.log(html);

                return (html);
            }

        }

    </script>

@endpush
