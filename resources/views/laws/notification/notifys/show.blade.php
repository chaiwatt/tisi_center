@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">แจ้งเตือน</h3>
                    @can('view-'.str_slug('law-notifys'))
               
                        <div class="pull-right">

                            @php
                                $next  = $query->where('id', '>', $notifys->id )->first();
                                $perv  = $query->where('id', '<', $notifys->id )->last();
                            @endphp

                            <div class="btn-group">
                                @if( !empty($perv) )
                                    <a class="btn btn-default waves-effect" href="{!! !empty($perv)?url('/law/notifys/'.$perv->id):"javascript:void(0)" !!}">
                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if( !empty($next) )
                                    <a class="btn btn-default waves-effect" href="{!! !empty($next)?url('/law/notifys/'.$next->id):"javascript:void(0)" !!}">
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>

                            <a class="btn btn-default" href="{{ url('/law/notifys') }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                            </a>

                        </div>

           
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="media p-t-20">
                        <h3 class="m-t-0">เรื่อง: {!! $notifys->title !!}</h3>
                        <div class="media-body"> 
                            <span class="media-meta pull-right">
                                {!! !empty($notifys->created_at)?HP::DateThai($notifys->created_at):null !!}
                                {!! !empty($notifys->created_at)?' | '.Carbon\Carbon::parse($notifys->created_at)->format('H.i').' น.':null !!}
                            </span>
                            <h4 class="m-0">หมวดหมู่ระบบ: {!! !empty($notifys->category->name)?$notifys->category->name:null !!}</h4> 
                            <small class="text-muted">ผู้บันทึก: {!! !empty($notifys->CreatedName)?$notifys->CreatedName:'-' !!}</small>
                            @if (!empty($notifys->law_system_category_id) && $notifys->law_system_category_id =='3')
                               <br> <small class="text-muted">ส่งถึง: {!! !empty($notifys->email)?str_replace('"', '',$notifys->email):'-' !!}</small>
                            @endif
                        </div>
                    </div>

                    <div class="media p-t-0 box_content">
                        {!! !empty( $notifys->content )? $notifys->content :null !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable
            // $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            // $('#box-readonly').find('button').remove();
            // $('#box-readonly').find('.show_tag_a').hide();
            // $('#box-readonly').find('.box_remove').remove();:

            $('.box_content').find('#style').removeAttr('id');

        });

    </script>
@endpush