<?php
    $theme_name = 'default';
    $fix_header = false;
    $fix_sidebar = false;
    $theme_layout = '';

    if(auth()->user()){

        $user = auth()->user();

        $params = (object)json_decode($user->params);

        if(!empty($params->theme_name)){
            if(is_file('css/colors/'.$params->theme_name.'.css')){
                $theme_name = $params->theme_name;
            }
        }

        if(!empty($params->fix_header) && $params->fix_header=="true"){
            $fix_header = true;
        }

        if(!empty($params->fix_sidebar) && $params->fix_sidebar=="true"){
            $fix_sidebar = true;
        }

        if(!empty($params->theme_layout)){
            $theme_layout = $params->theme_layout;;
        }

    }

?>

<aside class="sidebar">
    <div class="scroll-sidebar">

        @if(auth()->check())
            @if($theme_layout != 'fix-header')
                <div class="user-profile">
                    <div class="dropdown user-pro-body ">
                        <div class="profile-image" id="profile-image">
                            @if($user->profile == null || $user->profile->pic == null)
                                <img src="{{asset('storage/uploads/users/no_avatar.png')}}" alt="user-img"
                                     class="img-circle">
                            @else
                                <img src="{{ HP::getFileStorage('users/'.$user->profile->pic)}}"
                                     alt="user-img" class="img-circle">
                            @endif

                            <a href="javascript:void(0);" class="dropdown-toggle u-dropdown text-blue"
                               data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="badge badge-danger">
                                    <i class="fa fa-angle-down"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated flipInY">
                                <li class="font-15"><a href="{{ url('profile')}}"><i class="fa fa-user"></i> โปรไฟล์ </a></li>
                                <li class="font-15"><a href="{{ url('image-crop') }}"><i class="fa fa-camera"></i> เปลี่ยนภาพโปรไฟล์</a></li>
                                <li class="font-15"><a href="{{ url('account-settings') }}"><i class="fa fa-cog"></i> ตั้งค่าบัญชีผู้ใช้</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="font-15"><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i> ออกจากระบบ</a></li>
                            </ul>
                        </div>
                        <p class="profile-text m-t-15 font-16">
                           <a href="javascript:void(0);">
                               {{ $user->reg_fname }} {{ $user->reg_lname }}
                           </a><br>
                           <span style="margin-top:-10px;">(Back office)</span>
                       </p>
                        <a href="{{ url('profile')}}"> โปรไฟล์ </a>

                    </div>
                </div>
            @endif
            <nav class="sidebar-nav">
                <ul id="side-menu">

                    <li>
                        <a class="waves-effect" href="{{ url('/page/manuals') }}">
                            <i class="mdi mdi-library-books pre-icon"></i>
                            <span class="hide-menu">คู่มือการใช้งาน</span>
                        </a>
                    </li>

                    @foreach (  HP::MenuSidebar() as $section )

                        @if( isset($section->hr) )
                            <li>
                                <hr class="m-t-0 m-b-0"/>
                            </li>
                        @else
                            <li>
                                <a class="waves-effect sidebar-item" href="#" aria-expanded="false" data-mianulr="{!! !empty($section->url)? url($section->url):'-' !!}">
                                    <i class="{!! isset($section->icon)?$section->icon:''  !!} pre-icon"></i>
                                    <span class="hide-menu">{!! $section->_comment  !!}</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @foreach ( $section->items as $menu )

                                        @if(isset($menu->sub_menus) && HP::CheckMenuItem([$menu]))
                                            <li>
                                                <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                                                    <span><i class="fa fa-caret-down pre-icon"></i> {!!  $menu->display !!} </span>
                                                </a>
                                                <ul aria-expanded="false" class="collapse">
                                                    @include('layouts.partials.sub-menu',[ 'submenu' => $menu->sub_menus])
                                                </ul>
                                            </li>
                                        @else
                                            @can('view-'.str_slug($menu->title))
                                                <li>
                                                    <a class="waves-effect" href="{{ url($menu->url) }}">
                                                        <i class="{{$menu->icon}} pre-icon"></i>
                                                        {{ $menu->display }}

                                                        @if( array_key_exists($menu->title, ['law-notifys'=>'law-notifys'] ) && ( HP_Law::CategoryNotify()->sum('law_notify_count') >= 1 ) )
                                                            <span class="badge badge-danger pull-right m-t-10"> 
                                                                {!! HP_Law::CategoryNotify()->sum('law_notify_count') !!}
                                                            </span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endcan
                                        @endif

                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </nav>
        @else
            @php
                $categories = App\BlogCategory::all();
                $tags = App\Tag::all();
            @endphp

            <div class="list-group m-b-0">
            <h4 align="center">ยินดีต้อนรับ</h4>
                <span class="list-group-item bg-primary no-border text-center">หมวดหมู่</span>
                @if(count($categories) > 0)
                    @foreach($categories as $category)
                        <a class="list-group-item"
                        href="{{url('blogs/category/'.$category->slug)}}">{{$category->title}}</a>
                    @endforeach
                @else
                    No Categories Yet
                @endif
            </div>
            <div class="list-group">
                <span class="list-group-item bg-primary no-border text-center">แท็ก</span>
                @if(count($tags) > 0)
                    @foreach($tags as $tag)
                        <a class="list-group-item" href="{{url('blogs/tag/'.$tag->slug)}}">{{$tag->name}}</a>
                    @endforeach
                @else
                    No Categories Yet
                @endif
            </div>
            {{-- <div class="list-group">
                <span class="list-group-item bg-primary no-border text-center">ข้อมูลอ้างอิง</span>
                <a class="list-group-item" href="{{ url('reference/role') }}">กลุ่มผู้ใช้งาน</a>
                <a class="list-group-item" href="{{ url('reference/sub_department') }}">หน่วยงาน</a>
                <a class="list-group-item" href="{{ url('reference/country') }}">ประเทศ</a>
            </div> --}}
        @endif
    </div>
</aside>
