@php
    $cut_path = explode('/', $path_main );
@endphp
<div class="row m-b-20">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">

                @if( $path_main == '/' )
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{!! url('/funtions/file-manager?folder='.base64_encode('/')) !!}">Uploads</a>
                    </li>
                @else
                    @php
                        $path_contion = '';
                    @endphp
                    @foreach ( $cut_path  as $key => $item )
                    
                        @if( $item == 'uploads' )
                            <li class="breadcrumb-item">
                                <a  type="button" class="btn_selected_file" data-path="{!! base64_encode('/')  !!}" href="javascript:void(0)">Uploads</a>
                            </li>  
                        @else
                            @php $path_contion .= ( $key >= 2 )?'/'.$item:$item @endphp
                            <li class="breadcrumb-item">
                                <a  type="button" class="btn_selected_file" data-path="{!! base64_encode($path_contion)  !!}" href="javascript:void(0)">{!! $item !!}</a>
                            </li>
                        @endif
                
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
</div>
<div class="clearfix"></div>
<div class="row el-element-overlay m-b-20">
    @php
        $i = 0;
    @endphp

    @foreach ( $list as $dir )
        @php
            $i += 2;
        @endphp

        @if($dir->pathinfo == 'folder')

            @php
                $fileTime = HP::DateTimeFullThai(date("Y-m-d H:i:s.",  $dir->time));   
            @endphp
        
            <div class="col-md-2">
                <div class="white-box">
                    <div class="el-card-item">
                        <div class="el-card-avatar el-overlay-1"> <img src="{!! asset('icon/i-folder.png') !!}"/>
                            <div class="el-overlay">
                                <ul class="el-info">
                                    <li><button class="btn default btn-outline btn_selected_file" href="{!! url('/funtions/file-manager?folder='.base64_encode($dir->path)) !!}" data-path="{!! base64_encode($dir->path)  !!}"><i class="icon-magnifier"></i></button></li>
                                    <li><a class="btn default btn-outline btn_file_info" href="javascript:void(0);" data-type="{!!  $dir->pathinfo !!}" data-path="{!! $dir->path !!}" data-name="{!! $dir->name !!}" data-time="{!! $fileTime !!}" data-size=""><i class="icon-info"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="el-card-content">
                            <h6 class="cut-text">{!! $dir->name !!}</h6>
                        </div>
                    </div>
                </div>
            </div>
        
        @else
        
            @php
                $type = $dir->pathinfo;
        
                if($type == 'pdf'    || $type ==  'PDF'){
                    $url = asset('icon/i-pdf.png');
                }elseif($type == 'xlsx'){
                    $url = asset('icon/i-excel.png');
                }elseif($type == 'doc' || $type == 'docx'){
                    $url = asset('icon/i-word.png');
                }elseif($type == 'jpg'  || $type == 'jpeg'){
                    $url = HP::getFileStorage($dir->path);
                }elseif($type == 'png'){
                    $url = HP::getFileStorage($dir->path);
                }elseif($type == 'zip' || $type == '7z' ){
                    $url = asset('icon/i-zip.png');
                }elseif($type == 'txt' ){
                    $url = asset('icon/i-txt.png');
                }else{
                    $url = asset('icon/i-file.png');
                }    
                $fileTime = HP::DateTimeFullThai(date("Y-m-d H:i:s.",  $dir->time));       
                $fileSize = number_format(round($dir->size / 1024)).' KB'; 
            @endphp
        
            <div class="col-md-2">
                <div class="white-box">
                    <div class="el-card-item">
                        <div class="el-card-avatar el-overlay-1"> <img src="{!! $url !!}"/>
                            <div class="el-overlay">
                                <ul class="el-info">
                                    <li>
                                        @if ( $type == 'jpg'  || $type == 'jpeg' || $type == 'png' )
                                            <a class="btn default btn-outline image-popup-vertical-fit" href="{!! HP::getFileStorage($dir->path) !!}"><i class="icon-magnifier"></i></a>
                                        @else
                                            <a class="btn default btn-outline" href="{!! HP::getFileStorage($dir->path) !!}" target="_blank"><i class="icon-cloud-download"></i></a>
                                        @endif
                                    </li>
                                    <li><a class="btn default btn-outline btn_file_info" href="javascript:void(0);" data-type="{!!  $dir->pathinfo !!}" data-path="{!! $dir->path !!}" data-name="{!! $dir->name !!}" data-time="{!! $fileTime !!}" data-size="{!! $fileSize !!}"><i class="icon-info"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="el-card-content">
                            <h6 class="cut-text">{!! $dir->name !!}</h6>
                        </div>
                    </div>
                </div>
            </div>
         
        @endif
        
        @php
            if( $i == 12 ){
                echo '<div class="clearfix"></div>';
                $i = 0;
            }
        @endphp
        
        
    @endforeach
        
</div>