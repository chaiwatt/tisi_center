<?php

    $theme_name = 'default';
    $fix_header = false;
    $fix_sidebar = false;
    $theme_layout = 'normal';

    //Search Menus Json
    $json_search = [];

    if(auth()->user()){

        $params = (object)json_decode(auth()->user()->params);

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
            $theme_layout = $params->theme_layout;
        }

        /**************** Add permission to array ***************/
    
        //เมนูทั้งหมด
        $all_menus = array($laravelAdminMenus,
                           $laravelMenuTis,
                           $laravelMenuCertify,                         
                           $laravelMenuCertificate,
                           $laravelMenuSignCertify,
                           $laravelMenuBcertify,
                           $laravelMenuBesurv,
                           $laravelMenuEsurv,
                           $laravelMenuRsurv,
                           $laravelMenuCsurv,
                           $laravelMenuAsurv,
                           $laravelMenuUser,
                           $laravelMenuBsection5,
                           $laravelMenuCerreport,
                           $laravelMenuReport,
                           $laravelMenuSection5,
                           $laravelMenuConfig,
                           $laravelMenuiIndustry,
                           $laravelMenuStandards,
                            $laravelMenuLaw
                        );

        // foreach ($all_menus as $all_menu) {

        //     foreach ($all_menu as $menu_key => $menu_groups) {
        //      $menu_groups = $menu_groups[0];

        //       foreach ($menu_groups->items as $item_menu) {
        //         if(auth()->user()->can('view-'.str_slug($item_menu->title,'-'))){
        //           $json_search[] = ['value'=> url($item_menu->url),
        //                             'label'=> $item_menu->display,
        //                             'desc'=> $menu_groups->_comment.' <big><i class="mdi mdi-arrow-right-bold"/></i></big> '.$item_menu->display
        //                            ];
        //         }
        //       }

        //     }

        // }

    }

?>

<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <a class="navbar-toggle font-20 hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse"
           data-target=".navbar-collapse">
            <i class="fa fa-bars"></i>
        </a>
        <div class="top-left-part">
            @if(auth()->check())
                <a class="logo" href="{{ url('/home') }}">
                    <b>
                        <img src="{{asset('images/logo01.png')}}"  width="35px" alt="home"/>
                      {{-- <img src="{{asset('plugins/images/logo.png')}}" alt="home"/> --}}
                    </b>
                    <span>
                        บริการอิเล็กทรอนิกส์ สมอ.
                      {{-- <img src="{{asset('plugins/images/logo-text.png')}}" alt="homepage" class="dark-logo"/> --}}
                    </span>
                </a>
            @else
                <a class="logo" href="{{ url('/') }}">
                    <b>
                        <img src="{{asset('images/logo01.png')}}"  width="35px" alt="home"/>
                    </b>
                    <span>
                        บริการอิเล็กทรอนิกส์ สมอ.
                    </span>
                </a>
            @endif

        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            @if($theme_layout != 'fix-header' && auth()->check())
                <li class="sidebar-toggle">
                    <a href="javascript:void(0)" class="sidebartoggler font-20 waves-effect waves-light"><i class="icon-arrow-left-circle"></i></a>
                </li>
            @endif

            <li>
                <div role="search" class="app-search hidden-xs">
                    <i class="icon-magnifier"></i>
                    <input type="text" placeholder="ค้นหาเมนู..." class="form-control" id="search-menu">
                </div>
            </li>
        </ul>

        <ul class="nav navbar-top-links navbar-right pull-right">
            @if(auth()->check())
                <li class="dropdown">
                    {{-- <a class="dropdown-toggle waves-effect waves-light font-20" data-toggle="dropdown"
                       href="javascript:void(0);">
                        <i class="icon-speech"></i>
                        <span class="badge badge-xs badge-danger">6</span>
                    </a> --}}
                    <ul class="dropdown-menu mailbox animated bounceInDown">
                        <li>
                            <div class="drop-title">You have 4 new messages</div>
                        </li>
                        <li>
                            <div class="message-center">
                                <a href="javascript:void(0);">
                                    <div class="user-img">
                                        <img src="{{asset('plugins/images/users/1.jpg')}}" alt="user" class="img-circle">
                                        <span class="profile-status online pull-right"></span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Pavan kumar</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:30 AM</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="user-img">
                                        <img src="{{asset('plugins/images/users/2.jpg')}}" alt="user" class="img-circle">
                                        <span class="profile-status busy pull-right"></span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Sonu Nigam</h5>
                                        <span class="mail-desc">I've sung a song! See you at</span>
                                        <span class="time">9:10 AM</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="user-img">
                                        <img src="{{asset('plugins/images/users/3.jpg')}}" alt="user"
                                             class="img-circle"><span class="profile-status away pull-right"></span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Arijit Sinh</h5>
                                        <span class="mail-desc">I am a singer!</span>
                                        <span class="time">9:08 AM</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="user-img">
                                        <img src="{{asset('plugins/images/users/4.jpg')}}" alt="user" class="img-circle">
                                        <span class="profile-status offline pull-right"></span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Pavan kumar</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a class="text-center" href="javascript:void(0);">
                                <strong>See all notifications</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    {{-- <a class="dropdown-toggle waves-effect waves-light font-20" data-toggle="dropdown"
                       href="javascript:void(0);">
                        <i class="icon-calender"></i>
                        <span class="badge badge-xs badge-danger">3</span>
                    </a> --}}
                    <ul class="dropdown-menu dropdown-tasks animated slideInUp">
                        <li>
                            <a href="javascript:void(0);">
                                <div>
                                    <p>
                                        <strong>Task 1</strong>
                                        <span class="pull-right text-muted">40% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                             aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);">
                                <div>
                                    <p>
                                        <strong>Task 2</strong>
                                        <span class="pull-right text-muted">20% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20"
                                             aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);">
                                <div>
                                    <p>
                                        <strong>Task 3</strong>
                                        <span class="pull-right text-muted">60% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
                                             aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);">
                                <div>
                                    <p>
                                        <strong>Task 4</strong>
                                        <span class="pull-right text-muted">80% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80"
                                             aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="javascript:void(0);">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="right-side-toggle">
                    <a class="right-side-toggler waves-effect waves-light b-r-0 font-20" href="javascript:void(0)">
                        <i class="icon-settings"></i>
                    </a>
                </li>
            @else
                <li>
                    <a class="waves-effect waves-light b-r-0 font-20" href="{{ route('login') }}">
                        <i class="icon-user"></i>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</nav>

@push('js')

  <script type="text/javascript">

    $(document).ready(function () {

      var projects = {!! json_encode($json_search) !!};

      $( "#search-menu" ).autocomplete({
        minLength: 1,
        source: projects,
        focus: function( event, ui ) {
          $( "#search-menu" ).val( ui.item.label );
          return false;
        },
        select: function( event, ui ) {
          $( "#search-menu" ).val( ui.item.label );
          window.location = ui.item.value;
          return false;
        }
      })
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div>" + item.label + "<br><small class=\"text-muted\">" + item.desc + "</small></div>" )
          .appendTo( ul );
      };

    });

  </script>

@endpush
