@extends('layouts.master')
@push('css')
{{--    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">--}}
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush
@section('content')
    <div class="container-fluid">
        <div class="white-box">
            <h3 class="box-title pull-left">ระบบตั้งค่าการแจ้งเตือนข้อมูลใบรับรอง</h3>
{{--            @can('view-'.str_slug('department'))--}}
{{--                <a class="btn btn-success pull-right" href="{{ url('certificate') }}">--}}
{{--                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ--}}
{{--                </a>--}}
{{--            @endcan--}}
            <div class="clearfix"></div>
            <form action="{{route('setting.store')}}" method="post" id="alertForm">
                @CSRF
                <div class="table-responsive m-t-10">
                    <table class="table table-striped table-bordered color-bordered-table info-bordered-table" id="alertTable">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center col-xs-2">เปิดใช้งาน</th>
                            <th class="text-white text-center col-xs-2">สีที่แสดง</th>
                            <th class="text-white text-center col-xs-4">เงื่อนไข</th>
                            <th class="text-white text-center col-xs-4">ค่าที่ต้องการ</th>
                        </tr>
                        </thead>
                        <tbody id="appoint_files_body">
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="red_status" class="switch" {{$red_color->status == 'on' ? 'checked':null}} onchange="$(this).val(this.checked ? 'on' : null)">
                            </td>
                            <td class="text-center">
                                <span class="badge badge-danger text-white" style="padding: 10px 25px;font-size: 14px;">สีแดง</span>
                            </td>
                            <td class="text-center" style="padding-left: 30px;padding-right: 30px">
                                <select class="form-control input-xs condition" name="condition_red">
                                    @if ($red_color->condition)
                                        <option value="" readonly>- เงื่อนไข -</option>
                                        <option value="under" {{$red_color->condition == 'under' ? 'selected':null}}>น้อยกว่า</option>
                                        <option value="between" {{$red_color->condition == 'between' ? 'selected':null}}>ระหว่าง</option>
                                        <option value="over" {{$red_color->condition == 'over' ? 'selected':null}}>มากกว่า</option>
                                        @else
                                        <option value="" selected>- เงื่อนไข -</option>
                                        <option value="under">น้อยกว่า</option>
                                        <option value="between">ระหว่าง</option>
                                        <option value="over">มากกว่า</option>
                                    @endif
                                </select>
                            </td>
                            <td class="text-center">
{{--                                <input type="number" class="text-center" name="red_day" id="red_day" value="{{$red_color->date_start ?? ''}}"><span>&emsp;วัน</span>--}}
                                <div class="col-md-3">
                                    {!! Form::number('red_day_start',$red_color->date_start ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter equal {{$red_color->condition == 'between' ? "show":"hide"}}">
                                    ถึง
                                </div>
                                <div class="col-md-3 equal {{$red_color->condition == 'between' ? "show":"hide"}}">
                                    {!! Form::number('red_day_end',$red_color->date_end ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter">
                                    วัน
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="yellow_status" class="switch" {{$yellow_color->status == 'on' ? 'checked':null}} onchange="$(this).val(this.checked ? 'on' : null)">
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning text-white" style="padding: 10px 25px;font-size: 14px;">สีเหลือง</span>
                            </td>
                            <td class="text-center" style="padding-left: 30px;padding-right: 30px">
                                <select class="form-control input-xs condition" name="condition_yellow">
                                    @if ($yellow_color->condition)
                                        <option value="" readonly>- เงื่อนไข -</option>
                                        <option value="under" {{$yellow_color->condition == 'under' ? 'selected':null}}>น้อยกว่า</option>
                                        <option value="between" {{$yellow_color->condition == 'between' ? 'selected':null}}>ระหว่าง</option>
                                        <option value="over" {{$yellow_color->condition == 'over' ? 'selected':null}}>มากกว่า</option>
                                    @else
                                        <option value="" selected>- เงื่อนไข -</option>
                                        <option value="under">น้อยกว่า</option>
                                        <option value="between">ระหว่าง</option>
                                        <option value="over">มากกว่า</option>
                                    @endif
                                </select>
                            </td>
                            <td class="text-center">
{{--                                <input type="number" class="text-center" name="yellow_day_start" id="yellow_day_start" style="width: 20%" value="{{$yellow_color->date_start ?? ''}}"><span>&emsp;ถึง&emsp;</span>--}}
{{--                                <input type="number" class="text-center" name="yellow_day_end" id="yellow_day_end" style="width: 20%" value="{{$yellow_color->date_end ?? ''}}"><span>&emsp;วัน</span>--}}
                                <div class="col-md-3">
                                    {!! Form::number('yellow_day_start', $yellow_color->date_start ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter equal {{$yellow_color->condition == 'between' ? "show":"hide"}}">
                                    ถึง
                                </div>
                                <div class="col-md-3 equal {{$yellow_color->condition == 'between' ? "show":"hide"}}">
                                    {!! Form::number('yellow_day_end', $yellow_color->date_end ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter">
                                    วัน
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="green_status" class="switch" {{$green_color->status == 'on' ? 'checked':null}} onchange="$(this).val(this.checked ? 'on' : null)">
{{--                                {{ Form::checkbox('state3', '1', null, ['class'=>'switch']) }}--}}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success text-white" style="padding: 10px 25px;font-size: 14px;">สีเขียว</span>
                            </td>
                            <td class="text-center" style="padding-left: 30px;padding-right: 30px">
                                <select class="form-control input-xs condition" name="condition_green">
                                    @if ($green_color->condition)
                                        <option value="" readonly>- เงื่อนไข -</option>
                                        <option value="under" {{$green_color->condition == 'under' ? 'selected':null}}>น้อยกว่า</option>
                                        <option value="between" {{$green_color->condition == 'between' ? 'selected':null}}>ระหว่าง</option>
                                        <option value="over" {{$green_color->condition == 'over' ? 'selected':null}}>มากกว่า</option>
                                    @else
                                        <option value="" selected readonly="">- เงื่อนไข -</option>
                                        <option value="under">น้อยกว่า</option>
                                        <option value="between">ระหว่าง</option>
                                        <option value="over">มากกว่า</option>
                                    @endif
                                </select>
                            </td>
                            <td class="text-center">
{{--                                <input type="number" class="text-center" name="green_day" id="green_day" value="{{$green_color->date_start ?? ''}}"><span>&emsp;วัน</span>--}}
                                <div class="col-md-3">
                                    {!! Form::number('green_day_start',$green_color->date_start ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter equal {{$green_color->condition == 'between' ? "show":"hide"}}">
                                    ถึง
                                </div>
                                <div class="col-md-3 equal {{$green_color->condition == 'between' ? "show":"hide"}}">
                                    {!! Form::number('green_day_end', $green_color->date_end ?? '', ['class' => 'form-control','min'=>0]) !!}
                                </div>
                                <div class="col-md-1 label-filter">
                                    วัน
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <div class="text-right">

                        <button class="btn btn-primary" type="button" id="submitAlert">บันทึก</button>
                        {{--                            @can('view-'.str_slug('committee'))--}}
                        <a class="btn btn-default" href="#">ยกเลิก</a>
                        {{--@endcan--}}
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
{{--    <script src="{{asset('js/bootstrap-toggle.min.js')}}"></script>--}}
    <!-- Switchery -->
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            let allReady = [];

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif

            // Switchery
            $(".switch").each(function() {
                new Switchery($(this)[0], {
                    color: '#13dafe'
                })
            });

            //condition change
            $('.condition').change(function(){

                if($(this).val()=='between'){//ระหว่าง
                    $(this).parent().parent().find('.equal').removeClass('hide').addClass('show').show();
                }else{
                    $(this).parent().parent().find('.equal').removeClass('show').addClass('hide').hide();
                }

            });

            $('.condition').change();

            $('.switch').on('change',function () {
                if ($(this).val() != 'on'){
                    $(this).parent().next('td').next('td').find('select').val('').change()
                }
            });

            $('#submitAlert').on('click',function () {
                let dup = false;
                let val_none = false;
                allReady = [];
                $('#alertTable').find('select').each(function (k,el) {
                    let value = $(el).find('option:selected').val();
                    if (value !== '' && value !== undefined && value !== null){
                        let find = allReady.indexOf(value);
                        if (find < 0){
                            allReady.push(value);
                        }else{
                            dup = true;
                        }
                    }
                });
                $('#alertTable input:checkbox:checked').each(function (k,el) {
                    let check_val = $(this).parent().next('td').next('td').find('select').val();
                    if (check_val == ''){
                        val_none = true;
                    }
                });
                if (dup === true){
                    alert('เลือกเงื่อนไขซ้ำ กรุณาเปลี่ยนเงื่อนไขในรายการ')
                }else if (val_none === true){
                    alert('กรุณาเลือกเงื่อนไขให้ครบ')
                }else{
                    $('#alertForm').submit();
                }
            });
        });
    </script>
@endpush
