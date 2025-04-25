@extends('layouts.master')

@push('css')
    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @if( Auth::check() )
            <!-- .row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title pull-left">ENV</h3>

                        <div class="pull-right"></div>
                        <div class="clearfix"></div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                            
                                <table class="table table-borderless" id="myTable">
                                    <thead>
                                        <tr>
                                            <th width="25%">ENV NAME</th>
                                            <th>ENV Value</th>
                                        </tr>
                                    </thead>    
                                    <tbody>
                                        @isset( $_ENV )
                                            @foreach (  $_ENV as $key => $env )
                                                <tr>
                                                    <td>{!! $key !!}</td>
                                                    <td>{!! $env !!}</td>
                                                </tr>
                                            @endforeach
                                        @endisset

                                    </tbody>
                                </table>

                            </div>
                        </div>
        
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection



@push('js')
    <script>
        $(document).ready(function () {



        });
    </script>
@endpush
