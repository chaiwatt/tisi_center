{{-- AppointedAcademicSubCommitteeController --}}
@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หนังสือเชิญประชุม มาตรฐาน</h3>
                    @can('view-'.str_slug('standarddrafts'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/standard-drafts')}}">
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
                        <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
                        <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
                    @endpush

                    <form action="{{ url('/certify/appointed-academic-sub-committee/store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group {{ $errors->has('doc_type') ? 'has-error' : '' }}">
                            <label for="doc_type" class="col-md-3 control-label">ประเภท:</label>
                            <div class="col-md-8">
                                <select name="doc_type" id="doc_type" class="select2 form-control" data-placeholder="- เลือกประเภท -">
                                    <option></option> <!-- placeholder -->
                                    
                                    <option value="1" {{ old('doc_type') == '1' ? 'selected' : '' }}>เชิญประชุมคณะกำหนด</option>
                                    <option value="2" {{ old('doc_type') == '2' ? 'selected' : '' }}>เชิญประชุมอนุกรรมการวิชาการ</option>
                                    <option value="3" {{ old('doc_type') == '3' ? 'selected' : '' }}>เชิญประชุม เจ้าหน้าที่ ลท.</option>
                                </select>
                                @if ($errors->has('doc_type'))
                                    <p class="help-block">{{ $errors->first('doc_type') }}</p>
                                @endif
                            </div>
                        </div>

                 

                        <div class="form-group {{ $errors->has('header') ? 'has-error' : '' }}">
                            <label for="header" class="col-md-3 control-label">ที่ :</label>
                            <div class="col-md-8">
                                <input type="text" name="header" class="form-control" value="อก ๐๐๐/ว ๑๒๓๔">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('order_date') ? 'has-error' : '' }}">
                            <label for="order_date" class="col-md-3 control-label">วันที่:</label>
                            <div class="col-md-8">
                                <input type="text" name="order_date" class="form-control" value="๒๒ สิงหาคม ๒๕๖๗">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title" class="col-md-3 control-label">เรื่อง :</label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="ขอเชิญประชุมคณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบและรับรอง ครั้งที่ ๒๓ – ๓/๒๕๖๗">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('attachment_text') ? 'has-error' : '' }}">
                            <label for="attachment_text" class="col-md-3 control-label">สิ่งที่ส่งมาด้วย:</label>
                            <div class="col-md-8">
                                <textarea name="attachment_text" class="form-control" rows="3" >๑. ระเบียบวาระการประชุมคณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบและรับรองครั้งที่ ๒๓ – ๓/๒๕๖๗
๒. แบบตอบรับการเข้าร่วมประชุม</textarea>
                                @if ($errors->has('attachment_text'))
                                    <p class="help-block">{{ $errors->first('attachment_text') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('detail') ? 'has-error' : '' }}">
                            <label for="detail" class="col-md-3 control-label">รายละเอียด:</label>
                            <div class="col-md-8">
                                <textarea name="detail" class="form-control" rows="10" >ด้วยคณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบและรับรอง กำหนดให้มีการประชุม คณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบและรับรอง ครั้งที่ ๒๓ – ๓/๒๕๖๗ในวันจันทร์ที่ ๑๖ กันยายน ๒๕๖๗ เวลา ๑๓.๓๐ – ๑๖.๐๐ น. ณ ห้องประชุม ๒๓๐ สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม และระบบ Zoom Cloud Meeting (Meeting ID: 945 1428 2434 Passcode: 603552) ทั้งนี้ ระเบียบวาระการประชุม เอกสารประกอบการประชุม และแบบตอบรับการเข้าร่วมประชุม สามารถสแกนผ่าน QR Code ท้ายหนังสือนี้</textarea>
                                @if ($errors->has('detail'))
                                    <p class="help-block">{{ $errors->first('detail') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ps_text') ? 'has-error' : '' }}">
                            <label for="ps_text" class="col-md-3 control-label">ท้าย:</label>
                            <div class="col-md-8">
                                <textarea name="ps_text" class="form-control" rows="3" >จึงเรียนมาเพื่อขอเรียนเชิญท่านเข้าร่วมประชุมตามวัน เวลา และสถานที่ดังกล่าวข้างต้นโดยขอความกรุณาส่งแบบตอบรับการเข้าร่วมประชุมให้ฝ่ายเลขานุการทราบ ภายในวันที่ ๑๐ กันยายน ๒๕๖๗</textarea>
                                @if ($errors->has('ps_text'))
                                    <p class="help-block">{{ $errors->first('ps_text') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('set_standard') ? 'has-error' : '' }}">
                            <label for="set_standard" class="col-md-3 control-label">มาตรฐาน / คำขอ :</label>
                            <div class="col-md-8">
                                <select name="set_standard[]" id="set_standard" class="select2-multiple" multiple data-placeholder="- เลือกมาตรฐาน -">
                                     @foreach ($setStandards as $setStandard)
                                        <option value="{{ $setStandard->id }}" {{ old('set_standard') == $setStandard->id ? 'selected' : '' }}>
                                            {{ $setStandard->TisName }} <!-- ใช้ฟิลด์ name หรือฟิลด์ที่เหมาะสมจากโมเดล set_standard -->
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('set_standard'))
                                    <p class="help-block">{{ $errors->first('set_standard') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('board') ? 'has-error' : '' }}">
                            <label for="board" class="col-md-3 control-label">คณะเข้าร่วมประชุม :</label>
                            <div class="col-md-8">
                                <select name="board[]" id="board" class="select2-multiple" multiple data-placeholder="- เลือกคณะกรรมการเฉพาะด้าน -">
                                    @foreach(\App\CommitteeSpecial::orderbyRaw('CONVERT(committee_group USING tis620)')->get() as $committee)
                                        <option value="{{ $committee->id }}">{{ $committee->committee_group }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('board'))
                                    <p class="help-block">{{ $errors->first('board') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('image_file') ? 'has-error' : '' }}">
                            <label for="image_file" class="col-md-3 control-label">QR ระเบียบวาระการประชุม:</label>
                            <div class="col-md-8">
                                <input type="file" name="image_file" accept=".jpg,.jpeg,.png" class="form-control">
                                @if ($errors->has('image_file'))
                                    <p class="help-block">{{ $errors->first('image_file') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('google_form_qr') ? 'has-error' : '' }}">
                            <label for="google_form_qr" class="col-md-3 control-label">QR แบบตอบรับ:</label>
                            <div class="col-md-8">
                                <input type="file" name="google_form_qr" accept=".jpg,.jpeg,.png" class="form-control">
                                @if ($errors->has('google_form_qr'))
                                    <p class="help-block">{{ $errors->first('google_form_qr') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('signer') ? 'has-error' : '' }}">
                            <label for="signer" class="col-md-3 control-label">ผู้ลงนาม:</label>
                            <div class="col-md-8">
                                <select name="signer" id="signer" class="select2 form-control" data-placeholder="- เลือกผู้ลงนาม -">
                                    <option></option> <!-- placeholder -->
                                    @foreach ($signers as $signer)
                                        <option value="{{ $signer->id }}" {{ old('signer') == $signer->id ? 'selected' : '' }}>
                                            {{ $signer->name }} <!-- ใช้ฟิลด์ name หรือฟิลด์ที่เหมาะสมจากโมเดล Signer -->
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('signer'))
                                    <p class="help-block">{{ $errors->first('signer') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('signer_position') ? 'has-error' : '' }}">
                            <label for="signer_position" class="col-md-3 control-label">ตำแหน่ง :</label>
                            <div class="col-md-8">
                                <input type="text" name="signer_position" value="คณะกรรมการกำหนดมาตรฐานด้านการตรวจสอบและรับรอง" value="" class="form-control">
                            </div>
                        </div>

                        <div class="form-group div_hide">
                            <div class="col-md-offset-4 col-md-4">

                                <!-- ปุ่มส่งลงนาม -->
                                <button class="btn btn-success" type="submit" name="action" value="submit">
                                    <i class="fa fa-check"></i> ส่งลงนาม
                                </button>

                                <!-- ปุ่มบันทึก -->
                                <button class="btn btn-primary" type="submit" name="action" value="save">
                                    <i class="fa fa-paper-plane"></i> บันทึก
                                </button>

                                @can('view-'.str_slug('standarddrafts'))
                                    <a class="btn btn-default" href="{{ url('/certify/standard-drafts') }}">
                                        <i class="fa fa-rotate-left"></i> ยกเลิก
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </form>

                    @push('js')
                        <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
                        <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
                        <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
                        <script type="text/javascript">
                            $('.select2').select2({
                                placeholder: "- เลือกผู้ลงนาม -",
                                allowClear: true
                            });
                        </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
@endsection