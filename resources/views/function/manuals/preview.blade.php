@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css')}}" rel="stylesheet" />
    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">คู่มือการใช้งาน</h3>
              
                        <a class="btn btn-success pull-right" href="{{ url('/') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
    
                    <div class="clearfix"></div>
                    <hr>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="75%" class="text-left">ชื่อคู่มือ</th>
                                        <th width="20%" class="text-center">ดาวน์โหลด</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach (App\Models\Config\ConfigsManual::where('site', 'tisi-center' )->get() as $key => $item )

                                        <tr>
                                            <td>{!! $key+1 !!}</td>
                                            <td>{!! $item->title !!}</td>
                                            <td>
                                                
                                                @if( !empty($item->file) )
                                                    @php
                                                        $attach = json_decode($item->file);
                                                    @endphp
                                                    <a href="{!! HP::getFileStorage($item->file_url) !!}" target="_blank" class="show_tag_a">
                                                        {!! HP::FileExtension($attach->file_client_name)  ?? '' !!}
                                                    </a>  
                                                @endif
                                            </td>
                                        </tr>
                                        
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
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
    <script>
        $(document).ready(function () {
            var table = $('#myTable').DataTable({

                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
                    { className: "text-top", targets: "_all" }
                ]
            });

            $('#myTable_filter > .myTable_filter ').on( 'keyup', function () {
                table.search( this.value ).draw();
            });
        });
    </script>
@endpush