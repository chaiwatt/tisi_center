@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('branch_group_id') ? 'has-error' : ''}}">
    {!! Form::label('branch_group_id', 'หมวดสาขา/สาขา:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('branch_group_id', App\Models\Basic\BranchGroup::where('state', 1)->pluck('title', 'id')->all(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหมวดสาขา/สาขา-']); !!}
        {!! $errors->first('branch_group_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อรายสาขา:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('select_tis_id') ? 'has-error' : ''}}">
    {!! Form::label('select_tis_id', 'มอก.', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('select_tis_id',
          App\Models\Basic\Tis::select(DB::raw("CONCAT(tb3_Tisno, ' : ', tb3_TisThainame) AS standard_title"), 'tb3_TisAutono')->pluck('standard_title', 'tb3_TisAutono'),
          null,
          ['class' => 'form-control',
           'placeholder'=>'- เลือกมอก. -',
           'id' => 'select_tis_id']) !!}
        {!! $errors->first('select_tis_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-success glow mr-1 mb-1 pull-right" data-toggle="modal" data-target="#AddStdForm" id="btn-add"> <i class="icon-plus" aria-hidden="true"></i>
            <span class="align-middle ml-25"> เพิ่ม</span>
        </button>
    </div>
</div>

<div class="col-12">
    <div class="clearfix"></div>
    <div class="table-responsive">
        <table class="table color-bordered-table primary-bordered-table" width="60%"id="div_table_scope">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th class="text-center" width="90%">มอก.</th>
                    <th class="text-center" width="10%">ลบ</th>
            </thead>
            <tbody class="font-medium-1" id="tbody-add-std">
                @isset($branch)
                    @php
                        $arr_tis_id = [];
                    @endphp
                    @foreach ($branch->branch_tis as $key => $item_tis)

                        @if( !array_key_exists( $item_tis->tis_id, $arr_tis_id ) )
                            @php
                                $tis_standards = App\Models\Basic\Tis::find($item_tis->tis_id);
                                $arr_tis_id[ $item_tis->tis_id ] = $item_tis->tis_id;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>
                                    @if (!is_null($tis_standards))
                                        <p>{!! 'มอก. '.$tis_standards->tb3_Tisno.' '.$tis_standards->tb3_TisThainame !!}</p>
                                        <input type="hidden" name="tis_id[]" class="input_tis_id" value="{!! $item_tis->tis_id !!}">
                                    @endif
                                </td>
                                <td class="text-center font-medium-1">
                                    <button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn-remove "><i class="fa fa-trash"></i></i></button>
                                </td>
                            </tr>
                        @endif

                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('branch'))
            <a class="btn btn-default" href="{{url('/basic/branches')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>

        jQuery(document).ready(function() {

            //เพิ่มลงตาราง
            $('#btn-add').click(function(event) {

                if($('#select_tis_id').val()==''){
                    alert('กรุณาเลือก "รายการ มอก." ก่อน');
                    return false;
                }

                var input_tis = $('#select_tis_id');
                var tis_id   = $(input_tis).val();
                var tis_text = $(input_tis).find('option:selected').text();

                var tis_all =  $('#div_table_scope').find(".input_tis_id").map(function(){return $(this).val(); }).get();

                if(tis_all.indexOf( String(tis_id) ) == -1){

                    var inputs = '';
                        inputs += '<input type="hidden" class="input_tis_id" name="tis_id[]" value="' + tis_id + '" />';

                    var tr  = '<tr>';
                        tr += '    <td class="text-center"></td>';
                        tr += '    <td>' + tis_text + '</td>';
                        tr += '    <td class="text-center">' + inputs + '<button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn-remove "><i class="fa fa-trash"></i></i></button></td>';
                        tr += '</tr>';

                    $('#tbody-add-std').append(tr);

                    resetOrder();

                    $(input_tis).val('').select2();

                }else{
                    alert('กรุณาเลือก "รายการ มอก." ซ้ำ');
                }

            });

            //ลบออกจากตาราง
            $(document).on('click', '.btn-remove', function(event) {

                if( confirm("ยืนยันการลบข้อมูล") ){
                    $(this).closest('tr').remove();
                    resetOrder();
                }

            });

        });

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#tbody-add-std').children().each(function(index, el) {
                $(el).find('td:first').text((index+1));
            });

        }
    </script>
@endpush
