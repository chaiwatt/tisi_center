
  
@push('css')
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush
{!! Form::open(['url' => 'certify/certificate-export-cb/add_attach/'.$export_cb->id, 'class' => 'form-horizontal', 'files' => true,'id'=>'form_add_attach']) !!}
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalExportLabel">ไฟล์แนบท้าย 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h4>
        </div>

        <div class="modal-body">

            <div id="div_evidence">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group  pull-right">
                            <button type="button" id="evidence" class="btn btn-default">ประวัติการบันทึก</button>
                        </div>
                    </div><!-- /.col-lg-1 -->
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('app_no') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('app_no', '<span class="text-danger">*</span> เลขที่คำขอ:', ['class' => 'col-md-4 control-label'])) !!}
                            <div class="col-md-6">
                                {!! Form::text('app_no', $export_cb->app_no, ['class' => 'form-control','id'=>'app_no','required' => true,'readonly' => true]) !!}
                                {!! $errors->first('app_no', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class=" {{ $errors->has('attach') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> หลักฐาน:', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6 control-label text-left">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="attach" id="attach" class="check_max_size_file"accept=".doc,.docx">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2 control-label text-left ">
                                <p class="text-left"><span class="text-danger">(.docx,doc)</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class=" {{ $errors->has('attach_pdf') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label(' ', ' ', ['class' => 'col-md-4 control-label text-right'])) !!}
                            <div class="col-md-6 control-label text-left">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="attach_pdf" id="attach_pdf" class="check_max_size_file"  accept=".pdf" >
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                            </div>
                            <div class="col-md-2 control-label text-left ">
                                <p class="text-left"><span class="text-danger">(.pdf)</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        {!! HTML::decode(Form::label('start_date', '<span class="text-danger">*</span> ออกให้ตั้งแต่วันที่:', ['class' => 'col-md-4 control-label text-right'])) !!}
                        <div class="col-md-6">
                            <div class="input-daterange input-group date-range">
                                {!! Form::text('start_date', null, ['class' => 'form-control date', 'required' => true]) !!}
                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                {!! Form::text('end_date', null, ['class' => 'form-control date', 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="table_evidence">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group  pull-right">
                            <button type="button" id="add_evidence" class="btn btn-primary">เพิ่มไฟล์แนบท้าย</button>
                        </div>
                    </div>
                </div>

                @if(count($file_all) > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                {!! Form::label('file', 'ประวัติการบันทึก',['class' => 'col-md-4 control-label  text-right']) !!}
                                <div class="col-md-8 text-left ">
                                    <div class="table-responsive">
                                        <table class="table color-bordered-table info-bordered-table">
                                            <thead>
                                            <tr>
                                                <th class="text-center" width="2%">#</th>
                                                <th class="text-center" width="40%">หลักฐาน</th>
                                                <th class="text-center" width="20%"></th>
                                                <th class="text-center" width="30%">วันที่บันทึก</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($certicb_file_all as $key => $certicb_file)
                                                <tr>
                                                    <td>{{ $key+1 }}.</td>
                                                    <td>
                                                        <p class="text-left">
                                                            @if(!is_null($certicb_file->attach))
                                                                <a href="{!! HP::getFileStorage($certicb_file->attach) !!}" target="_blank">
                                                                    {!! HP::FileExtension($certicb_file->attach) ?? '' !!}
                                                                </a>
                                                            @endif
                                                            @if(!is_null($certicb_file->attach_pdf))
                                                                <a href="{!! HP::getFileStorage($certicb_file->attach_pdf) !!}" target="_blank">
                                                                    {!! HP::FileExtension($certicb_file->attach_pdf) ?? '' !!}
                                                                </a>
                                                            @endif
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <div class="checkbox"><input class="js-switch " name="state" type="checkbox" value="{{$certicb_file->id}}" {{ ($certicb_file->state == 1) ? 'checked ' : '' }}></div>
                                                    </td>
                                                    <td>
                                                        {{ HP::DateThai($certicb_file->created_at) ?? '-' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div> 

        </div>

        <div class="modal-footer">
            <input type="hidden" name="app_certi_cb_id" value="{{ $export_cb->id }}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </div>
 
        </div>
    </div>
</div>

{!! Form::close() !!}
@push('js')
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script>
 
        $(document).ready(function () {

            check_max_size_file();
            $("#evidence").click(function(){
                $('#div_evidence').hide(300);
                $('#table_evidence').show(300);
            });
    
            $("#add_evidence").click(function(){
                $('#div_evidence').show(300);
                $('#table_evidence').hide(300);
            });

            $("#evidence").click();

            $(".js-switch").each(function() {
                new Switchery($(this)[0], { size: 'small' });
            });

            $(".js-switch").change( function () {

                if($(this).prop('checked')){
                    $('.js-switch').prop('checked',false)
                    $(this).prop('checked',true)
                    $('.switchery-small').remove();
                    $(".js-switch").each(function( index, data) {
                        new Switchery($(this)[0], {  size: 'small' });
                    });
                }

            });
                
            $('#attach').change( function () {
                var fileExtension = ['docx','doc'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .docx,.doc");
                this.value = '';
                return false;
                }
            });
            $('#attach_pdf').change( function () {
                var fileExtension = ['pdf'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf");
                this.value = '';
                return false;
                }
            });

            //ช่วงวันที่
            $('.date-range').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

        });

    </script>
 
@endpush

