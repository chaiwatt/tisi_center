@foreach($submenu as $menu )
    @can('view-'.str_slug($menu->title))
        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
            <a href="{{ url($menu->url) }}">
                <div class="white-box">
                    <div class="media bg-success">
                        <div class="media-body">
                            <h3 class="info-count2">
                                {!! $menu->display !!}<br>
                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-format-list-numbers"></i></span>
                            </h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endcan
@endforeach