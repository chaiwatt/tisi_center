@php
    $config = HP::getConfig(false);
@endphp

@foreach($submenu as $menu )

    @if( isset($menu->title) )
        @can('view-'.str_slug($menu->title))
            @if($menu->title=='report-std-certifies')
            <li>
                <a class="waves-effect" href="{{ url($config->url_acc.$menu->url) }}">
                    <i class="fa fa-play-circle pre-icon"></i>
                    {{ $menu->display }}
                </a>
            </li>
            @else
            <li>
                <a class="waves-effect" href="{{ url($menu->url) }}">
                    <i class="fa fa-play-circle pre-icon"></i>
                    {{ $menu->display }}
                </a>
            </li>
            @endif
        @endcan
    @else
        <li>
            <a class="waves-effect" href="{{ url($menu->url) }}">
                <i class="fa fa-play-circle pre-icon"></i>
                {{ $menu->display }}
            </a>
        </li>
    @endif

@endforeach
