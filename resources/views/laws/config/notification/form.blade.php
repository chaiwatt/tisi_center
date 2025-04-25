@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อเรื่อง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'ชื่อเรื่อง', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('condition') ? 'has-error' : ''}}">
    {!! Form::label('condition', 'เงื่อนไข', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="row">

                <div class="col-md-12 repeater-form">
                    <table class="table table-bordered" id="myTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="35%">สีที่แสดง</th>
                                <th class="text-center" width="25">เงื่อนไข</th>
                                <th class="text-center" width="30%">จำนวน/วัน</th>
                                <th class="text-center" width="10%">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody data-repeater-list="repeater-condition">
                            @if(!empty($config_notification) && $config_notification->law_config_notification_details->count() > 0)
                                @php
                                    $details = $config_notification->law_config_notification_details;
                                @endphp
                                @foreach ($details as $detail)
                                    <tr data-repeater-item>
                                        <td class="text-center">
                                            {!! Form::select('color', (new App\Models\Law\Config\LawConfigNotification)->color_list(), $detail->color, ['class' => 'form-control color', 'placeholder'=>'- เลือกสี -', 'required' => true]) !!}
                                            {!! Form::hidden('detail_old_id', $detail->id) !!}
                                        </td>
                                        <td class="text-center" >
                                            {!! Form::select('condition', (new App\Models\Law\Config\LawConfigNotification)->condition_list(), $detail->condition, ['class' => 'form-control', 'placeholder'=>'- เลือกเงื่อนไข -', 'required' => true]) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! Form::number('amount', $detail->amount, ['class' => 'form-control', 'placeholder' => 'กรอกเป็นตัวเลข', 'required' => true, 'min' => 0]) !!}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn_remove" data-repeater-delete>
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr data-repeater-item>
                                    <td class="text-center">
                                        {!! Form::select('color', (new App\Models\Law\Config\LawConfigNotification)->color_list(), null, ['class' => 'form-control color', 'placeholder'=>'- เลือกสี -', 'required' => true]) !!}
                                    </td>
                                    <td class="text-center" >
                                        {!! Form::select('condition', (new App\Models\Law\Config\LawConfigNotification)->condition_list(), null, ['class' => 'form-control', 'placeholder'=>'- เลือกเงื่อนไข -', 'required' => true]) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'กรอกเป็นตัวเลข', 'required' => true, 'min' => 0]) !!}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm btn_remove" data-repeater-delete>
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-top text-center">
                                    <button type="button" class="btn btn-success btn-sm" data-repeater-create>
                                        <i class="fa fa-plus"></i>
                                    </button>  
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="clearfix"></div>

        </div>
    </div>
</div>

<div class="form-group required {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 ">
        {!! Form::text('created_by_show', !empty($config_section->created_by)? $config_section->CreatedName:auth()->user()->Fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('created_by_show',  !empty($config_section->created_at)? HP::revertDate($config_section->created_at, true):HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control ', 'disabled' => true]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-success" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-config-notification'))
            <a class="btn btn-default show_tag_a" href="{{url('/law/config/notification')}}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.repeater-form').repeater({
                show: function () {
                    let $row = $('tbody[data-repeater-list="repeater-condition"] tr').length;
                    if($row <= 3){
                        $(this).slideDown();
                        reBuiltSelect2($(this).find('select'));
                        CheckBtnDelete();
                    }else{
                        $(this).remove();
                        alert('เพิ่มได้ไม่เกิน 3 แถว !');
                    }
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                    setTimeout(() => { 
                        CheckBtnDelete();
                    }, 500);
                }
            });
            
        });
        
       function reBuiltSelect2(select){
            //Clear value select
            $(select).val('');
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

        function CheckBtnDelete(){
            if($('.btn_remove').length <= 1){
                $('.btn_remove:first').hide();   
            }else{
                $('.btn_remove').show();
            }
        }

    </script>

@endpush
