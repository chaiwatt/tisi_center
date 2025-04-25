@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/Magnific-Popup-master/dist/magnific-popup.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css')}}" rel="stylesheet" />

    <style>
        .el-element-overlay .el-card-item .el-overlay-1 img {
            display: block;
            position: relative;
            -webkit-transition: all .4s linear;
            transition: all .4s linear;
            width: 50%;
            height: auto;
            margin-left: 25%;
            margin-right: 25%;
            margin-top:5%;
        }

        .cut-text { 
            text-overflow: ellipsis;
            overflow: hidden; 
            height: 1.2em; 
            white-space: nowrap;
            text-align: center !important;
            margin-left: 5%;
            margin-right: 5%;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Left sidebar -->
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-2 col-md-3  col-sm-4 col-xs-12 inbox-panel">
                            <div><a href="javascript:void(0)" class="btn btn-custom btn-block waves-effect waves-light">Folder</a>
                                <div class="list-group mail-list m-t-20" id="treview_folder" >

                                    {{-- @foreach ( Storage::Directories('/') as $directory )
                                        <a type="button" class="list-group-item btn_selected_file"data-path="{!! base64_encode($directory)  !!}"  value="{!! $directory !!}" href="javascript:void(0)">{!! $directory !!}</a>
                                    @endforeach --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12 mail_listing">
                            <h3 class="box-title">File Manager</h3>
                            <hr>

                            <div class="row">
                                <div class="col-md-8  col-xs-12">
                                    <div class="input-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search',  'placeholder' => 'ค้นหาไฟล์']) !!}
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="button" id="btn_search">ค้นหา</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4  col-xs-12">
                                    <div class="input-group">
                                        {{-- {!! Form::text('filter_folder', null, ['class' => 'form-control', 'id' => 'filter_folder',  'placeholder' => 'ค้นหาใน Folder']) !!} --}}
                                    </div>
                                </div>
                            </div>

                            <br class="clearfix">

                            <div id="box_show"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('function.manager-file.modal')

@endsection

@push('js')
    <script src="{{asset('plugins/components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('plugins/components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-treeview/js/bootstrap-treeview.min.js')}}"></script>
    <script>
        jQuery(document).ready(function() {

            $(document).on('click', '.btn_selected_file', function () {
                $('#filter_search').val('');
                var path = $(this).data('path');
                LoadFile(path);

                loadFolder();
            });
            LoadFile('/');

            $('#btn_search').click(function (e) { 
                LoadFile('/');
            });

            loadFolder();
        });

        function loadFolder(){
            $.ajax({
                url: "{!! url('/funtions/file-manager/load-folder') !!}"
            }).done(function( object ) {
                if(object != ''){

                    $('#treview_folder').treeview({
                        data: object,
                        collapseIcon:'icon-arrow-down',
                        expandIcon:'icon-arrow-right',
                        showBorder: false,
                        showTags: false,
                        highlightSelected: false,
                        enableLinks: true,
                    });

                    $('#treview_folder').treeview('expandAll', { levels: 10, silent: true });

                    $('#treview_folder').find('li').each(function(index, element){
                        var herf = $(element).find('a').attr('href');
                        $(element).find('a').attr( "data-path", btoa(herf) );
                        $(element).find('a').addClass( "btn_selected_file" );
                        $(element).find('a').attr("href", "javascript:void(0)");
                    });

                }
            }); 
        }

        function LoadFile(path){

            var search =  $('#filter_search').val();

            $('#box_show').html('');

            $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });
   
            $.ajax({
                url: "{!! url('/funtions/file-manager/show_all?') !!}" + "folder=" + path + '&search=' + search
            }).done(function( object ) {
                $.LoadingOverlay("hide");
                $('#box_show').html(object);

                $('.image-popup-vertical-fit').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }
                });

                $('#treview_folder').find('li').each(function(index, element){
                    var herf = $(element).find('a').attr('href');
                    $(element).find('a').attr( "data-path", btoa(herf) );
                    $(element).find('a').addClass( "btn_selected_file" );
                    $(element).find('a').attr("href", "javascript:void(0)");
                });
            });
        }

    </script>

@endpush