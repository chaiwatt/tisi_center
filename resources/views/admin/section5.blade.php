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

@php 
    $controller = new App\Http\Controllers\FuntionCenter\Section5Controller;
@endphp

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">

                <h3 class="box-title">ระบบงานหลัก

                    @if ($ibcbs->count() > 0)
                        <button class="fcbtn btn btn-primary btn-outline btn-1e pull-right m-l-5" data-toggle="modal" data-target="#IBCB-Modal">
                            IBCB มอก.ยกเลิก 
                        </button>
                    @endif

                    @if ($labs->count() > 0)
                        <button class="fcbtn btn btn-info btn-outline btn-1e pull-right" data-toggle="modal" data-target="#Lab-Modal">
                            Lab มอก.ยกเลิก 
                        </button>
                    @endif
                </h3>

                @php
                    $check_menu = false;

                    // dd( $controller->ClassNameInfo('ตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมิน') );
                @endphp

                @foreach ( $controller->AllMenuSection5() as $menu )
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
                                                        <p class="info-text font-12">{!! !empty($submenu->title)?$submenu->title:null !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endcan

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

@if ($ibcbs->count() > 0)
{{-- Modal IBCB --}}
<div class="modal fade" id="IBCB-Modal" tabindex="-1" role="dialog" aria-labelledby="IBCB-ModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="IBCB-ModalLabel">แจ้งเตือน มอก.ที่ถูกยกเลิก</h4> 
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 225px); overflow-y: auto;">
                <h4>มีขอบข่ายของหน่วยตรวจสอบ IB/CB ที่มี มอก. ยกเลิก จำนวน {{ $ibcbs->count() }} ราย</h4>

                <table class="font-14" width="100%">
                    @foreach ($ibcbs as $ibcb)
                        <tr>
                            <td width="10%">{{ $ibcb->ibcb_code }}</td>
                            <td width="60%">{{ $ibcb->name }}</td>
                            <td width="15%">{{ $ibcb->tis_amount }} มอก. ยกเลิก</td>
                            <td width="10%">{{ $ibcb->scope_amount }} ขอบข่าย</td>
                            <td width="5%"><a href="{{ url('section5/ibcb/'.$ibcb->id) }}?tab_active=2" class="fcbtn btn btn-link">ดู</a></td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endif

@if ($labs->count() > 0)
{{-- Modal Lab --}}
<div class="modal fade" id="Lab-Modal" tabindex="-1" role="dialog" aria-labelledby="Lab-ModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="Lab-ModalLabel">แจ้งเตือน มอก.ที่ถูกยกเลิก</h4> 
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 225px); overflow-y: auto;">
                <h4>มีขอบข่ายของหน่วยตรวจสอบ Labs ที่มี มอก. ยกเลิก จำนวน {{ $labs->count() }} ราย</h4>

                <table class="font-14" width="100%">
                    @foreach ($labs as $lab)
                        <tr>
                            <td width="10%">{{ $lab->lab_code }}</td>
                            <td width="70%">{{ $lab->lab_name }}</td>
                            <td width="15%">{{ $lab->tis_amount }} มอก. ยกเลิก</td>
                            <td width="5%"><a href="{{ url('section5/labs/'.$lab->id) }}?tab_active=2" class="fcbtn btn btn-link">ดู</a></td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('js')
    <script>
        $(document).ready(function () {

            @if ($ibcbs->count() > 0)
                $('#IBCB-Modal').modal('show');
            @elseif($labs->count() > 0)
                $('#Lab-Modal').modal('show');
            @endif

            var open_modal_counter = 0;
            $('#IBCB-Modal').on('hidden.bs.modal', function (e) {
                if($('#Lab-Modal').length==1 && open_modal_counter==0){ //มีแจ้งของ Lab ด้วย
                    $('#Lab-Modal').modal('show');
                    open_modal_counter++;
                }
            })

        });
    </script>
@endpush
