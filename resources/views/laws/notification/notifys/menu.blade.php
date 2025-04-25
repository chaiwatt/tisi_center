@extends('layouts.master')
@push('css')
    <link href="{{ asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css') }}" rel="stylesheet" />

    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">แจ้งเตือน</h3>
                    @can('view-'.str_slug('law-notifys'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/notifys') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">

                            <table class="table " width="100%">
                                <tbody id="sortable">
                                    @if( isset($menu) )

                                        @foreach ( $menu as $item  )

                                            <tr class="info">
                                                <td>
                                                    <b>{!! $item->text !!} ( {!!  $item->title  !!} )</b>
                                                </td>
                                            </tr>
                                            
                                            @isset( $item->nodes )
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ( $item->nodes as $nodes  )
                                                    <tr class="sortable">
                                                        <td>
                                                            {!! $nodes->text !!} ( {!!  $nodes->title  !!} )

                                                            <input type="hidden" name="ordering[]" value="{!!  $i++ !!}" data-title="{!! $nodes->title !!}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        @endforeach
                                        
                                    @endif
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
    <script src="{{ asset('plugins/components/bootstrap-treeview/js/bootstrap-treeview.min.js') }}"></script>

    <script>

        $(document).ready(function() {

            $( ".sortable" ).sortable();

        });


    </script>
@endpush