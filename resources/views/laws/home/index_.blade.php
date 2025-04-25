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
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    @foreach (  HP_Law::MenuLaw() as $section )
                        <h3 class="box-title">{!! $section->_comment  !!}</h3>
                        <hr>
                        @foreach ( $section->items as $menu )
                    
                            @if( isset($menu->sub_menus) )
                                <h3>{!! $menu->display  !!}</h3>
                                <div class="row colorbox-group-widget">
                                    @include('laws.home.menu',[ 'submenu' => $menu->sub_menus  ])
                                </div>
                                <hr>
                            @else
                                    {{-- @can('view-'.str_slug($menu->title))
                                        <li>
                                            <a class="waves-effect" href="{{ url($menu->url) }}">
                                                <i class="{{$menu->icon}} pre-icon"></i>
                                                {{ $menu->display }}
                                            </a>
                                        </li>
                                    @endcan --}}
                            @endif
                        @endforeach
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
