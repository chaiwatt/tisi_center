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

                    @if(HP::check_group_menu($laravelAdminMenus))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-google-circles-extended pre-icon"></i>
                              <span class="hide-menu"> ข้อมูลพื้นฐาน (กก.)</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelAdminMenus->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>

                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuTis))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-web pre-icon"></i>
                              <span class="hide-menu"> กำหนดมาตรฐาน </span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuTis->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li style="line-height: 18px;">
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}}" style="font-size:20px;"></i>
                                                     <small>{{ $menu->display }}</small>
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>

                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuBcertify))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-google-circles-extended pre-icon"></i>
                              <span class="hide-menu"> ข้อมูลพื้นฐาน (สก.) </span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuBcertify->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuCertify))
                      <li>

                          <a class="waves-effect" href="#" aria-expanded="false">
                              <i class="mdi mdi-certificate pre-icon"></i>
                              <span class="hide-menu"> รับรองระบบงาน </span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuCertify->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                        @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                        @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>

                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuSignCertify))
                    <li>
                        <a class="waves-effect" href="#" aria-expanded="false">
                            <i class="mdi mdi-file "></i>
                            <span class="hide-menu">ลงนามอิเล็กทรอนิกส์(สก.)</span>
                        </a>

                        <ul aria-expanded="false" class="collapse">
                            @foreach($laravelMenuSignCertify->menus as $section)
                                @if(count(collect($section->items)) > 0)
                                    @foreach($section->items as $menu)
                                      @can('view-'.str_slug($menu->title))
                                            <li>
                                                <a class="waves-effect" href="{{ url($menu->url) }}">
                                                    <i class="{{$menu->icon}} pre-icon"></i>
                                                    {{ $menu->display }}
                                                </a>
                                            </li>
                                      @endcan
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </li>
                  @endif

                    @if(HP::check_group_menu($laravelMenuCertificate))
                    <li>
                        <a class="waves-effect" href="#" aria-expanded="false">
                            <i class="mdi mdi-bookmark pre-icon"></i>
                            <span class="hide-menu"> ติดตามใบรับรอง (สก.) </span>
                        </a>

                        <ul aria-expanded="false" class="collapse">
                            @foreach($laravelMenuCertificate->menus as $section)
                                @if(count(collect($section->items)) > 0)
                                    @foreach($section->items as $menu)
                                      @can('view-'.str_slug($menu->title))
                                            <li>
                                                <a class="waves-effect" href="{{ url($menu->url) }}">
                                                    <i class="{{$menu->icon}} pre-icon"></i>
                                                    {{ $menu->display }}
                                                </a>
                                            </li>
                                      @endcan
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </li>
                  @endif



                    @if(HP::check_group_menu($laravelMenuBesurv))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-google-circles-extended pre-icon"></i>
                              <span class="hide-menu"> ข้อมูลพื้นฐาน (กต.) </span>
                          </a>

                          <ul aria-expanded="false" class="collapse">

                              @foreach($laravelMenuBesurv->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuEsurv))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-clipboard-text pre-icon"></i>
                              <span class="hide-menu"> ตรวจติดตามออนไลน์</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuEsurv->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuRsurv))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-book-open-variant pre-icon"></i>
                              <span class="hide-menu"> รายงาน (กต.)</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuRsurv->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuiIndustry))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-magnify pre-icon"></i>
                              <span class="hide-menu"> ค้นหาข้อมูลจากหน่วยงาน</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuiIndustry->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuBsection5))
                        <li>
                            <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                                <i class="mdi mdi-google-circles-extended pre-icon"></i>
                                <span class="hide-menu"> ข้อมูลพื้นฐาน (ขึ้นทะเบียน ม.5)</span>
                            </a>

                            <ul aria-expanded="false" class="collapse">
                                @foreach($laravelMenuBsection5->menus as $section)
                                    @if(count(collect($section->items)) > 0)
                                        @foreach($section->items as $menu)
                                            @can('view-'.str_slug($menu->title))
                                                <li>
                                                    <a class="waves-effect" href="{{ url($menu->url) }}">
                                                        <i class="{{$menu->icon}} pre-icon"></i>
                                                        {{ $menu->display }}
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuSection5))
                        <li>
                            <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                                <i class="mdi mdi-inbox-arrow-down pre-icon"></i>
                                <span class="hide-menu"> รับคำขอมาตรา 5</span>
                            </a>

                            <ul aria-expanded="false" class="collapse">
                                @foreach($laravelMenuSection5->menus as $section)
                                    @if(count(collect($section->items)) > 0)
                                        @foreach($section->items as $menu)
                                            @can('view-'.str_slug($menu->title))
                                                <li>
                                                    <a class="waves-effect" href="{{ url($menu->url) }}">
                                                        <i class="{{$menu->icon}} pre-icon"></i>
                                                        {{ $menu->display }}
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuCerreport))
                        <li>
                            <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                                <i class="mdi mdi-clipboard-text pre-icon"></i>
                                <span class="hide-menu">รายงาน (สก.)</span>
                            </a>

                            <ul aria-expanded="false" class="collapse">
                                @foreach($laravelMenuCerreport->menus as $section)
                                    @if(count(collect($section->items)) > 0)
                                        @foreach($section->items as $menu)
                                            @can('view-'.str_slug($menu->title))
                                                <li>
                                                    <a class="waves-effect" href="{{ url($menu->url) }}">
                                                        <i class="{{$menu->icon}} pre-icon"></i>
                                                        {{ $menu->display }}
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuReport))
                    <li>
                        <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                            <i class="mdi mdi-clipboard-text pre-icon"></i>
                            <span class="hide-menu">รายงาน</span>
                        </a>

                        <ul aria-expanded="false" class="collapse">
                            @foreach($laravelMenuReport->menus as $section)
                                @if(count(collect($section->items)) > 0)
                                    @foreach($section->items as $menu)
                                        @can('view-'.str_slug($menu->title))
                                            <li>
                                                <a class="waves-effect" href="{{ url($menu->url) }}">
                                                    <i class="{{$menu->icon}} pre-icon"></i>
                                                    {{ $menu->display }}
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                    @can('view-'.str_slug('report-power-bi'))
                        <li>
                            <a class="waves-effect" href="{{ url('report-power-bi') }}">
                                <i class="mdi mdi-chart-pie pre-icon"></i>
                                <span class="hide-menu"> รายงาน Power BI </span>
                            </a>
                        </li>
                    @endcan

                    @if(HP::check_group_menu($laravelMenuConfig))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-settings pre-icon"></i>
                              <span class="hide-menu"> ตั้งค่า</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuConfig->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuWS))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-access-point-network pre-icon"></i>
                              <span class="hide-menu"> เว็บเซอร์วิส</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuWS->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                    @if(HP::check_group_menu($laravelMenuStandards))
                      <li>
                          <a class="waves-effect sidebar-item" href="#" aria-expanded="false">
                              <i class="mdi mdi-sync  pre-icon"></i>
                              <span class="hide-menu"> กำหนดมาตรฐานรับรอง</span>
                          </a>

                          <ul aria-expanded="false" class="collapse">
                              @foreach($laravelMenuStandards->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach
                          </ul>
                      </li>
                    @endif

                        <li>
                            <hr class="m-t-0 m-b-0"/>
                        </li>

                      @if(HP::check_group_menu($laravelMenuUser))
                        <li class="two-column">
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false">
                              <i class="icon-user fa-fw"></i>
                              <span class="hide-menu"> ผู้ใช้งาน</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">

                                @foreach($laravelMenuUser->menus as $section)
                                    @if(count(collect($section->items)) > 0)
                                        @foreach($section->items as $menu)
                                            @can('view-'.str_slug($menu->title))
                                                <li>
                                                    <a class="waves-effect" href="{{ url($menu->url) }}">
                                                        <i class="{{$menu->icon}} pre-icon"></i>
                                                        {{ $menu->display }}
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    @endif
                                @endforeach

                            </ul>
                        </li>
                      @endif

                        @can('view-'.str_slug('permission'))
                            <li>
                                <a class="waves-effect" href="{{ asset('role-management') }}">
                                    <i class=" icon-layers fa-fw"></i><span class="hide-menu"> สิทธิ์การใช้งาน </span>
                                </a>
                            </li>
                        @endif

                      <li>
                          <hr class="m-t-0 m-b-0 bg-primary"/>
                      </li>

                    @if(HP::check_group_menu($laravelMenuBlog))
                      <li class="two-column">
                          <a class="waves-effect" href="javascript:void(0);" aria-expanded="false">
                            <i class="mdi mdi-new-box pre-icon"></i>
                            <span class="hide-menu"> ข่าวประชาสัมพันธ์</span>
                          </a>
                          <ul aria-expanded="false" class="collapse">

                              @foreach($laravelMenuBlog->menus as $section)
                                  @if(count(collect($section->items)) > 0)
                                      @foreach($section->items as $menu)
                                          @can('view-'.str_slug($menu->title))
                                              <li>
                                                  <a class="waves-effect" href="{{ url($menu->url) }}">
                                                      <i class="{{$menu->icon}} pre-icon"></i>
                                                      {{ $menu->display }}
                                                  </a>
                                              </li>
                                          @endcan
                                      @endforeach
                                  @endif
                              @endforeach

                          </ul>
                      </li>
                    @endif

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
                <div class="list-group">
                    <span class="list-group-item bg-primary no-border text-center">ข้อมูลอ้างอิง</span>
                    <a class="list-group-item" href="{{ url('reference/role') }}">กลุ่มผู้ใช้งาน</a>
                    <a class="list-group-item" href="{{ url('reference/sub_department') }}">หน่วยงาน</a>
                    <a class="list-group-item" href="{{ url('reference/country') }}">ประเทศ</a>
                </div>
        @endif


    </div>
</aside>
