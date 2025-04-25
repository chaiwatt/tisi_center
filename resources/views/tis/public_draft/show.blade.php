@extends('layouts.master')
@push('css')
    <link href="{{asset('js/magnific-popup/dist/magnific-popup.css')}}" rel="stylesheet">
    <style>
        .image-popup-vertical-fit:hover{
            cursor: pointer;
        }

        .centerOfRow{
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .imageGrow{
            transition: all .4s;
            cursor: pointer;
        }
        .imageGrow:hover{
            -ms-transform: scale(1.08) translateZ(0);
            -webkit-transform: scale(1.08) translateZ(0);
        }

        .customerPage {
            background-color: white;
            padding: 10px 0px;
            border-radius: 10px;
            border: 1px solid #dcdbd8;
            transition: 0.3s;
            /*height: 140px;*/
            vertical-align: middle;
            /*max-height: 140px;*/
        }

        .customerPage:hover {
            background-color: #f5f5f5;
        }

        .gridBlue{
            border-top: 2px solid rgba(9, 132, 227, 0.5);
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="white-box">
            <h3 class="box-title pull-left">ข้อมูลเผยแพร่/เวียนร่างมาตรฐาน</h3>
            @can('view-'.str_slug('public_draft'))
                <a class="btn btn-danger pull-right" href="{{ url('tis/public_draft') }}">
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                </a>
            @endcan
            <div class="clearfix"></div>

                    {!! Form::open(['url' => '/tis/public_draft/'.$public_draft->token, 'class' => 'form-horizontal','method'=>'put', 'files' => true]) !!}

<div class="m-t-40">
    <div class="form-group {{ $errors->has('public_draft_type') ? 'has-error' : ''}}">
        {!! Form::label('public_draft_type', ' ', ['class' => 'col-md-4 control-label']) !!}
        <div class="radio-list">
            <label class="radio-inline">
                <div class="radio radio-info">
                    <input type="radio" name="public_draft_type" id="radio1" value="0" {{$public_draft->public_draft_type == 0 ? 'checked':null}} class="draft_type">
                    <label for="radio1">เวียนร่าง</label>
                </div>
            </label>
            <label class="radio-inline">
                <div class="radio radio-info">
                    <input type="radio" name="public_draft_type" id="radio2" value="1" {{$public_draft->public_draft_type == 1 ? 'checked':null}} class="draft_type">
                    <label for="radio2">เวียนทบทวน</label>
                </div>
            </label>
        </div>
        {!! $errors->first('public_draft_type', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group required {{ $errors->has('set_format_id') ? 'has-error' : ''}}">
        {!! Form::label('set_format_id', 'รูปแบบการกำหนดมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            @php
                use App\Models\Basic\SetFormat;
                $formatArr = null;
                if ($public_draft->public_draft_type == 0){
                    $formatArr = SetFormat::whereIn('id',[1,2])->get();
                }elseif ($public_draft->public_draft_type == 1){
                    $formatArr = SetFormat::where('id',2)->get();}
            @endphp
            <select name="set_format_id" id="set_format_id" class="form-control" required>
                <option value="" selected>- เลือกรูปแบบการกำหนดมาตรฐาน -</option>
                @foreach ($formatArr as $format)
                    @if ($format->id == $public_draft->set_format_id)
                        <option value="{{$format->id}}" selected>{{$format->title ?? '-'}}</option>
                        @else
                        <option value="{{$format->id}}">{{$format->title ?? '-'}}</option>
                    @endif
                @endforeach
            </select>
            {!! $errors->first('set_format_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('tis_no') ? 'has-error' : ''}}">
        {!! Form::label('tis_no', 'เลขมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            @php

                use App\Models\Tis\SetStandard;
                use App\Models\Tis\Standard;
                $number_formula = null;
                if ($public_draft->public_draft_type == 1 && $public_draft->set_format_id == 2){ //ดึงจาก D112 // ทบทวน // เวียนทบทวน
                    $number_formula = Standard::where('state', 1)->get();
                }else{ // ดึงจาก D115 เวียนร่าง
                    $number_formula = SetStandard::where('state', 1)->get();}
            @endphp
            <select name="tis_no_select" id="tis_no_select" class="form-control" required>
                <option value="" selected>- เลือกเลขมาตรฐาน -</option>
                @foreach ($number_formula as $formula)
                    <?php
                        $year = $formula->tis_year ?? $formula->start_year ?? '-';
                        $tis_book = (!empty($formula->tis_book) && $formula->tis_book != "-") ? ' เล่ม '.$formula->tis_book:'';
                    ?>
                    @if ($formula->id == $public_draft->set_standard_id)
                        <option value="{{$formula->id}}" selected>{{$formula->tis_no.$tis_book.'-'.$year.' : '.$formula->title}}</option>
                        @else
                        <option value="{{$formula->id}}">{{$formula->tis_no.$tis_book.'-'.$year.' : '.$formula->title}}</option>
                    @endif
                @endforeach
            </select>
            <input type="hidden" name="tis_no" id="tis_no" value="{{$public_draft->tis_no}}">
            {!! $errors->first('tis_no_select', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('set_standard_id') ? 'has-error' : ''}}">
        {!! Form::label('set_standard_id', 'ชื่อมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="set_standard_id" id="set_standard_id" class="form-control" required>
                <option value="">- ชื่อมาตรฐาน -</option>
                <option value="{{$public_draft->set_standard_id}}" selected>{{$public_draft->StandardName}}</option>
            </select>
            {!! $errors->first('set_standard_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
        {!! Form::label('product_group_id', 'สาขา :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="product_group_id" id="product_group_id" class="form-control" required>
                <option value="">- สาขา -</option>
                <option value="{{$public_draft->product_group_id}}" selected>{{$public_draft->getStand_Branch()->title ?? '-'}}</option>
            </select>
            {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', 'เรื่อง :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="title" name="title" class="form-control" placeholder="เรื่อง" required value="{{$public_draft->title}}">
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('number_book') ? 'has-error' : ''}}">
        {!! Form::label('number_book', 'เลขหนังสือ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="number_book" name="number_book" class="form-control" placeholder="เลขหนังสือ" required value="{{$public_draft->number_book}}">
            {!! $errors->first('number_book', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('mask_date') ? 'has-error' : ''}}">
        {!! Form::label('mask_date', 'ลงวันที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="mask_date" name="mask_date" class="form-control mydatepicker" required value="{{$public_draft->mask_date}}">
            {!! $errors->first('mask_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required {{ $errors->has('anniversary_date') ? 'has-error' : ''}}">
        {!! Form::label('anniversary_date', 'วันครบกำหนด :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <input type="text" id="anniversary_date" name="anniversary_date" class="form-control mydatepicker" required value="{{$public_draft->anniversary_date}}">
            {!! $errors->first('anniversary_date', '<p class="help-block">:message</p>') !!}
            <label class="m-t-10"><input type="checkbox" class="check" name="lock_qr" value="locked" {{$public_draft->lock_qr == 'locked' ? 'checked':null}}> &nbsp;ล็อกวัน </label>
        </div>
    </div>

    <div class="form-group {{ $errors->has('staff_group') ? 'has-error' : ''}}">
        {!! Form::label('staff_group', 'กลุ่มงานเจ้าหน้าที่ :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="staff_group" id="staff_group" class="form-control" required>

                @if ($public_draft->basic_staff_groups_id && $public_draft->getStaff() && $public_draft->getStaff()->title)
                    <option value="{{$public_draft->basic_staff_groups_id}}" selected>{{$public_draft->getStaff()->order.' - '.$public_draft->getStaff()->title ?? '-'}}</option>
                    @else
                    <option value="" selected>- กลุ่มงานเจ้าหน้าที่ -</option>
                @endif

            </select>
            {!! $errors->first('staff_group', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('result_draft') ? 'has-error' : ''}} {{$public_draft->public_draft_type == 1 ? 'show':'hide'}}" id="result_draft_div">
        {!! Form::label('result_draft', 'ผลการเวียนทบทวน:', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <select name="result_draft" id="result_draft" class="form-control">
                <option value="" selected>- เลือก -</option>
                @if (!is_null($public_draft->result_draft))
                    <option value="1" {{$public_draft->result_draft == 1 ? 'selected':null}}>ใช้มาตรฐานเดิม</option>
                    <option value="2" {{$public_draft->result_draft == 2 ? 'selected':null}}>ทบทวนมาตรฐาน</option>
                    @else
                    <option value="1">ใช้มาตรฐานเดิม</option>
                    <option value="2">ทบทวนมาตรฐาน</option>
                @endif
            </select>
            {!! $errors->first('result_draft', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('or_code', 'OR Code :', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
    <script>
                            let type = '{{$public_draft->public_draft_type ?? ''}}';
                            if (type == 1){ // เวียนทบทวน
                                this_url = '{{env('APP_URL').'/tis/comment_standard_reviews/form/'.$public_draft->token}}';
                            }else if (type == 0){
                                this_url = '{{env('APP_URL').'/tis/comment_standard_drafts/form/'.$public_draft->token}}';
                            }
                        </script>
   <?php
                            $anniversary_date = \Carbon\Carbon::parse($public_draft->anniversary_date);
                            $today = \Carbon\Carbon::today();
                            ?>
                            @if ($anniversary_date < $today && $public_draft->lock_qr == 'locked')
                                <span class="text-danger">ครบกำหนดแล้ว</span>
                            @else
                                    <img class="image-popup-vertical-fit imageGrow" style="width: 50px;height:50px;object-fit: cover;"
                                         src="" alt="QRcode" href="" id="showQR">
                                    @if ($public_draft->public_draft_type == 1)
                                        <a href="{{env('APP_URL').'/tis/comment_standard_reviews/form/'.$public_draft->token}}" target="_blank">&emsp;link</a>
                                        @else
                                        <a href="{{env('APP_URL').'/tis/comment_standard_drafts/form/'.$public_draft->token}}" target="_blank">&emsp;link</a>
                                    @endif
                            @endif
        </div>
    </div>

</div>
  {!! Form::close() !!}
            <div class="clearfix"></div>

            @if ($public_draft->getFiles()->count() > 0)
                <hr>
                <div id="appoint_files_table">
                    <h3 class="m-b-10">ไฟล์แนบ</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center">#</th>
                                <th class="text-white text-center">ชื่อไฟล์</th>
                                <th class="text-white text-center">บันทึกวันที่</th>
                                <th class="text-white text-center">ดาวน์โหลด</th>
                            </tr>
                            </thead>
                            <tbody id="appoint_files_body">
                                @foreach ($public_draft->getFiles() as $file)
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{$file->file_name ?? '-'}}</td>
                                        <td class="text-center">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y') ?? '-'}}</td>
                                        <td class="text-center">
                                            <a href="{{url('tis/public_draft/files/'.basename($file->file_path))}}" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:25px; color:red" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('js')
    {{--    pop up--}}
    <script src="{{asset('js/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('js/magnific-popup/meg.init.js')}}"></script>
    {{--    QR CODE--}}
    <script type="text/javascript" src="{{asset('js/qrCode/jquery.qrcode.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/qrCode/qrcode.js')}}"></script>

    <script>
        $(document).ready(function () {

            $('input,select').attr('disabled','disabled');

            $('body').append("<div id='divQR' style='display: none'></div>");
            $('#divQR').qrcode({
                render: 'canvas',
                text: this_url,
                width: 300,
                height: 300
            });
            var canvas =  $('#divQR canvas');
            var img = canvas[0].toDataURL("image/png");
            $('#showQR').attr('src',img).attr('href',img);
        });
    </script>
@endpush

