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
            background: url("{{asset('images/laws/sky.png')}}") repeat;
        }

        .table-responsive{
            overflow-x: clip;
        }

        .none-bg{
            background-color: transparent;
        }

    </style>
@endpush

@section('content')

    <div class="container-fluid">

        <!-- .row -->
        <div class="row p-10">
            <div class="col-md-12">
                <div class="faq-header faq-bg d-flex flex-column justify-content-center align-items-center">
                    <h1 class="text-dark"><b>สืบค้นข้อมูลห้องสมุดกฏหมาย</b></h1>
                    <div class="clearfix"></div>

                        <div class="col-md-12">
                            <div class="col-md-12" id="BoxSearching">
                                <div class="row">
                                    <div class="col-md-11 form-group">
                                        {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                        <div class="form-group col-md-3">
                                            {!! Form::select('filter_condition_search', array('1' => 'ชื่อเรื่อง', '2' => 'ใจความสำคัญ', '3' => 'คำอธิบาย', '4' => 'tag'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                        </div>
                                        <div class="col-md-7">
                                            <div class="input-group none-bg">
                                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder' => 'ค้นหา','title'=>'ค้นหาจาก : ชื่อเรื่อง,tag' ,'id' => 'filter_search']); !!}
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="btn_search">ค้นหา</button>
                                                    <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">ล้าง</button>
                                                    <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                        <i class="fa fa-ellipsis-h"></i>
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="none-bg" style="display: flex; flex-direction: column;">
                                        <div class="row">
                                            <div class="col-md-3"></div>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        <div class="row p-10">
            <div class="col-md-12">
                <div class="white-box">

                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        รายการ
                                    </th>
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
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            table = $('#myTable').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/book/search/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search  = $('#filter_condition_search').val();
                        d.filter_search   = $('#filter_search').val();
                        d.filter_group    = $('#filter_group').val();
                        d.filter_type     = $('#filter_type').val();
                    }
                },
                columns: [
                    { data: 'title', searchable: false, orderable: false},
                ],
                columnDefs: [
                    { className: "text-top", targets:[0] }
                ],
                fnDrawCallback: function() {
                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);
                }
            });

            //filter select list change
            $('#BoxSearching').find('select').change(function(event) {
                table.draw();
            });

            //ค้นหา
            $('#filter_search').keyup(function () {
                table.draw();
            });

            @if(\Session::has('error_message'))
                Swal.fire({
                    type: 'error',
                    title: 'ไม่พบไฟล์แนบห้องสมุดนี้',
                });
            @endif

            //ล้าง
            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input').val('');
                $('#BoxSearching').find('select').val('').trigger('change.select2');
                table.draw();
            });

            //เมื่อเลือกหมวดหมู่
            $('#filter_group').change(function (e) {
                Loadtype()
            });
            Loadtype();
        });

        function Loadtype(){
            $('#filter_type').html('<option value=""> - ประเภท - </option>');
            var seleted = $('#filter_group').val() != '' ? $('#filter_group').val():'all';

            $.ajax({
                url: "{!! url('/law/funtion/get-book-type') !!}" + "?id=" + seleted
            }).done(function( object ) {
                $.each(object, function( index, data ) {
                    $('#filter_type').append('<option value="'+data.id+'">'+data.title+'</option>');
                });
                $('#filter_type').val('').trigger('change.select2');
            });
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
