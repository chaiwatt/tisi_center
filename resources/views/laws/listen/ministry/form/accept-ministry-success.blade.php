@extends('layouts.master')
@php
    $config = HP::getConfig(false);
@endphp
@push('css')
    <style>
        .white-box {
            padding: 75px;
        }
        .panel {
            border-radius: 11px;
            border: 2px solid #DEDEDE;
        }
        .panel .panel-heading {
            border-radius: 11px 11px 0 0;
        }
        .text-white {
            color: white;
        }
        .d-flex {
            display: flex;
        }
        .justify-content-between {
            justify-content: space-between
        }
        .align-items-end {
            align-items: flex-end;
        }
        .content-primary {
            margin-bottom: 4.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title text-white"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;บันทึกแบบตอบรับฟังความคิดเห็นเรียบร้อย</h3>
                        </div>
                        <div class="panel-body">
                            <div class="content-primary">
                                ขอขอบคุณ {{ !empty($lawlistministryrsponse->name)?$lawlistministryrsponse->name:'N/A' }} ที่ตอบรับฟังความคิดเห็น หากวินิจฉัยเป็นอย่างไร เราจะแจ้งให้ท่านทราบในภายหลัง
                                ผ่านอีเมล {{ !empty($lawlistministryrsponse->email)?$lawlistministryrsponse->email:'N/A' }} ตามที่ระบุไว้
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <span>
                                    @if(!empty($config->contact_mail_footer))
                                        {!! $config->contact_mail_footer !!}
                                    @else
                                        สอบถามข้อมูลเพิ่มเติมได้ที่ : กองกฎหมาย
                                        <br>
                                        Tel. : 0-2430-6830 ต่อ 2001 E-mail. : Legal.tisi@gmail.com
                                    @endif
                                </span>
                                <a href="{{ url('/') }}" class="btn btn-success text-white">
                                    กลับไปหน้าแรก
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
