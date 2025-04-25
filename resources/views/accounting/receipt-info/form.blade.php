@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required">
    {!! Form::label('depart_type', 'ประเภทหน่วยงาน'.':', ['class' => 'col-md-4 control-label text-right']) !!}
    <div class="col-md-6">
        <label class="m-r-20">{!! Form::radio('depart_type', '1', true, ['class'=>'check input_condition', 'data-radio'=>'iradio_flat-green', 'id' => 'depart_type_1', 'required' => 'required']) !!} ภายใน</label>
        <label>{!! Form::radio('depart_type', '2', false, ['class'=>'check input_condition', 'data-radio'=>'iradio_flat-green', 'id' => 'depart_type_2', 'required' => 'required']) !!} ภายนอก</label>
    </div>
</div>

<div class="form-group required{{ $errors->has('department_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('department_name', 'หน่วยงาน'.':', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-6">
        {!! Form::text('department_name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('department_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="clearfix"></div>

<div class="form-group">
    <div class="col-md-offset-4">
        <p class="h4">รายการใบเสร็จ</p>
    </div>
</div>

<div class="form-group">
    <div class="repeater-default">

        <div class="col-md-12"  data-repeater-list="repeater-details">

            <div class="row" data-repeater-item>
                <div class="col-md-3">
                    <div class="form-group required">
                        {!! Form::label('name', 'ชื่อในใบเสร็จรับเงิน.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('name', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        {!! Form::label('tel', 'เบอร์โทร.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('tel', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        {!! Form::label('address', 'หมายเลขสมุดบัญชีธนาคาร.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('address', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group required">
                        {!! Form::label('taxid', 'เลขประจำตัวผู้เสียภาษี.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('taxid', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        {!! Form::label('address', 'ธนาคาร.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::select('bs_bank_id', App\Models\Accounting\Bank::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกธนาคาร -']) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        {!! Form::label('bank_book_file', 'ไฟล์สมุดบัญชีธนาคาร.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control " data-trigger="fileinput" >
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                    <span class="input-group-text btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="bank_book_file" class="check_max_size_file">
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group required">
                        {!! Form::label('email', 'อีเมล.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('email', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        {!! Form::label('address', 'ชื่อสมุดบัญชีธนาคาร.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::text('address', null,  ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group required">
                        {!! Form::label('address', 'ที่อยู่ออกใบเสร็จรับเงิน.'.' :', ['class' => 'col-md-12 text-left']) !!}
                        <div class="col-md-12">
                            {!! Form::textarea('address', null,  ['class' => 'form-control', 'rows' => 5]) !!}
                        </div>
                    </div>
                    <div class="form-group required">
                        <div class="col-md-12">
                            <button class="btn btn-danger btn-outline btn-sm pull-right " type="button" data-repeater-delete>
                                ลบ
                            </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
            </div>


        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="pull-right">
                <button type="button" class="btn btn-success" data-repeater-create><i class="bx bx-plus"></i><span class="align-middle ml-25">เพิ่ม</span></button>
            </div>
        </div>

    </div>
</div>



<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('add-'.str_slug('accounting-receipt-info'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/accounting/receipt-info') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script>

        $(document).ready(function() {

            $('input[name="depart_type"]').on('ifChecked', function(event){
                CkeckCondition($(this).val());
            });
            CkeckCondition($('input[name="depart_type"]:checked').val());

            $('.repeater-default').repeater({
                show: function () {

                    $(this).slideDown();

                    reBuiltSelect2($(this).find('select'));
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

        });

        function CkeckCondition(vals){
            if(vals == 1 ){
                $('#department_name').val('สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สมอ.)');
                $('#department_name').prop('readonly', true);

            }else{
                $('#department_name').val('');
                $('#department_name').prop('readonly', false);

            }
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function reBuiltSelect2(select){
            //Clear value select
            $(select).val('');
            //Select2 Destroy
            $(select).val('');  
            $(select).prev().remove();
            $(select).removeAttr('style');
            $(select).select2();
        }

    </script>
@endpush
