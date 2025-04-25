@extends('layouts.master')
@push('css')
    <style>
        .info-box .info-count2 {
            font-size: 25px;
            margin-top: -5px;
            margin-bottom: 5px  
        }

        .colorbox-group-widget .info-color-box .media .info-count2 {
            font-size: 25px;
            margin-bottom: 5px;
            color: #fff
        }

        .info-box .info-count3 {
            font-size: 20px;
            margin-top: -5px;
            margin-bottom: 5px  
        }

        .colorbox-group-widget .info-color-box .media .info-count3 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #fff
        }
    </style>
@endpush

@php 
   $controller = new App\Http\Controllers\FuntionCenter\MenusController;
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    @foreach ( $controller->AllMenuTis() as $menu )
                        <div class="clearfix"></div>
                        <hr>
                        <h3 class="box-title">{!! isset($menu->title)?$menu->title:null !!}</h3>
                        <div class="row colorbox-group-widget">
                            @isset( $menu->submenu )
                            
                                @php
                                    $i_check = 0;
                                @endphp

                                @foreach (  $menu->submenu as $keys => $submenu )

                                    @php
                                        $submenu = (object)$submenu;
                                    @endphp

                                    @if( isset($submenu->slug) )
                                        @can('view-'.str_slug($submenu->slug))
                                            @php
                                                $i_check += 4;
                                            @endphp
                                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                                <a href="{!! url($submenu->url) !!}">
                                                    <div class="white-box">
                                                        <div class="media {!! !empty($submenu->color)?$submenu->color:null !!}">
                                                            <div class="media-body">
                                                                <h3 class="{!! !empty($submenu->class)?$submenu->class:'info-count' !!}">
                                                                    {!! !empty($submenu->short)?$submenu->short:null !!}<br/>
                                                                    <span class="pull-right" style="font-size:45px;"><i class="{!! !empty($submenu->icon)?$submenu->icon:null !!}"></i></span>
                                                                </h3>
                                                                <p class="info-text font-12">{!! !empty($submenu->name)?$submenu->name:(!empty($submenu->title)?$submenu->title:null) !!}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcan
                                    @else
                                        @php
                                            $i_check += 4;
                                        @endphp
                                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                            <a href="{!! url($submenu->url) !!}">
                                                <div class="white-box">
                                                    <div class="media {!! !empty($submenu->color)?$submenu->color:null !!}">
                                                        <div class="media-body">
                                                            <h3 class="{!! !empty($submenu->class)?$submenu->class:'info-count' !!}">
                                                                {!! !empty($submenu->short)?$submenu->short:null !!}<br/>
                                                                <span class="pull-right" style="font-size:45px;"><i class="{!! !empty($submenu->icon)?$submenu->icon:null !!}"></i></span>
                                                            </h3>
                                                            <p class="info-text font-12">{!! !empty($submenu->name)?$submenu->name:(!empty($submenu->title)?$submenu->title:null) !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif


                                    @if($i_check == 12 || $keys == count($menu->submenu) )
                                        <div class="clearfix"></div>
                                    @endif

                                    @php
                                        if( $i_check == 12  ){
                                            $i_check = 0;
                                        }else if($keys == count($menu->submenu)){
                                            $i_check = 0;
                                        }
                                    @endphp
                                        
                                @endforeach       
                            @endisset
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
@endpush
