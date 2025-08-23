@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หนังสือเชิญประชุม รม</h3>
                    @can('view-' . str_slug('appointed-academic-sub-committee'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/appointed-academic-sub-committee') }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @push('css')
                        <link href="{{ asset('plugins/components/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
                        <link href="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
                    @endpush

                    <!-- แสดงข้อมูลในโหมด read-only -->
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label">ประเภท:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                    {{ $meetingInvitation->type == 1 ? 'เชิญประชุมอนุกรรมการวิชาการ' : 'เชิญประชุมคณะกำหนด' }}
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">ที่:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">{{ $meetingInvitation->reference_no }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">วันที่:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">{{ $meetingInvitation->date }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">เรื่อง:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">{{ $meetingInvitation->subject }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">สิ่งที่ส่งมาด้วย:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">{{ $meetingInvitation->attachments }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">รายละเอียด:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">{!! nl2br(e($meetingInvitation->details)) !!}</p>
                                <p class="form-control-static" style="text-indent:50px">{!! nl2br(e($meetingInvitation->ps_text)) !!}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">มาตรฐาน:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                    {{ $meetingInvitation->setStandards->pluck('TisName')->implode(', ') ?: 'ไม่มี' }}
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">คณะกรรมการเฉพาะด้าน:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                    {{ $meetingInvitation->committeeSpecials->pluck('committee_group')->implode(', ') ?: 'ไม่มี' }}
                                </p>
                            </div>
                        </div>
                        @if ($order_book_url != "")
                            <div class="form-group">
                                <label class="col-md-3 control-label">หนังสือเชิญประชุม:</label>
                                <div class="col-md-8">
                                    <p class="form-control-static">
                                        <a href="{{$order_book_url}}" target="_blank">หนังสือเชิญประชุม</a>
                                    </p>
                                </div>
                            </div>
                        @endif


                        

                        {{-- <div class="form-group">
                            <label class="col-md-3 control-label">QR ระเบียบวาระการประชุม:</label>
                            <div class="col-md-8">
                                @if ($meetingInvitation->qr_file_path)
                                    <p>ไฟล์: <a href="{{ url($meetingInvitation->qr_file_path) }}" target="_blank">ดูไฟล์</a></p>
                                @else
                                    <p>ไม่มีไฟล์ QR</p>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label class="col-md-3 control-label">QR ระเบียบวาระการประชุม:</label>
                            <div class="col-md-8">
                                {{-- {{$meetingInvitation}} --}}
                                @if ($meetingInvitation->qr_file_path)
                                    <img src="{{ url($meetingInvitation->qr_file_path) }}" alt="" style="width: 100px">
                                    {{-- <p>ไฟล์: <a href="{{ url($meetingInvitation->qr_file_path) }}" target="_blank">ดูไฟล์</a></p> --}}
                                @else
                                    <p>ไม่มีไฟล์ QR</p>
                                @endif
                            </div>
                        </div>
                          <div class="form-group">
                            <label class="col-md-3 control-label">QR กูเกิลฟอร์ม:</label>
                            <div class="col-md-8">
                                {{-- {{$meetingInvitation}} --}}
                                @if ($meetingInvitation->google_form_qr)
                                    <img src="{{ url($meetingInvitation->google_form_qr) }}" alt="" style="width: 100px">
                                    {{-- <p>ไฟล์: <a href="{{ url($meetingInvitation->qr_file_path) }}" target="_blank">ดูไฟล์</a></p> --}}
                                @else
                                    <p>ไม่มีไฟล์ QR</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">กรรมการและเลขานุการ:</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                    {{ $meetingInvitation->signer->name ?? 'ไม่พบข้อมูลการลงนาม' }}
                                </p>
                            </div>
                        </div>

                     <div class="form-group">
                        <label class="col-md-3 control-label">สถานะ:</label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                {{ [
                                    1 => 'ร่าง',
                                    2 => 'ส่งลงนาม',
                                    3 => 'ลงนามแล้ว'
                                ][$meetingInvitation->status] ?? 'ไม่ทราบสถานะ' }}
                            </p>
                        </div>
                    </div>
                 </div>

                    @push('js')
                        <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
                        <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
@endsection