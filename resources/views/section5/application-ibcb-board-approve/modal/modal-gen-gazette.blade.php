  <div id="modal_announcement" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('m_issue', 'ฉบับที่:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('m_issue', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('m_year', 'ปีที่ประกาศ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('m_year', HP::YearRange(),( date('Y') ), ['class' => 'form-control', 'placeholder'=>'-เลือกปี-']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required">
                                {!! Form::label('m_announcement_date', 'ประกาศ ณ วันที่', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('m_announcement_date', null,  ['class' => 'form-control mydatepicker']) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group required">
                                {!! Form::label('m_sign_id', 'ผู้ลงนาม:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('m_sign_id',  App\Models\Besurv\Signer::orderbyRaw('CONVERT(name USING tis620)')->pluck('name','id') , null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ลงนาม-']);!!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('m_sign_position', 'ตำแหน่ง:', ['class' => 'control-label text-right col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('m_sign_position', null, ['class' => 'form-control','readonly' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mannouncement">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="20%" class="text-center">เลขที่คำขอ</th>
                                        <th width="20%" class="text-center">ผู้ยื่นคำขอ</th>
                                        <th width="25%" class="text-center">สาขา</th>
                                        <th width="15%" class="text-center">ประเภท</th>
                                        <th width="15%" class="text-center">วันประชุมคณะอนุฯ</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <a id="link-doc-gazette" target="_blank" class="btn btn-info btn-sm waves-effect waves-light pull-left"><i class="fa fa-download"></i> โหลดไฟล์ประกาศ</a>
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_announcement"><i class="fa fa-plus-square"></i> สร้างไฟล์ประกาศ</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#m_sign_id').change(function(){

                if($(this).val() != ''){

                    $.ajax({
                        url: "{!! url('section5/sign_position') !!}" + "/" +  $(this).val()
                    }).done(function( object ) {
                        $('#m_sign_position').val(object.sign_position);
                    });

                }else{
                    $('#m_sign_position').val('');
                }
            });

            $('#btn_save_announcement').click(function (e) {
                SaveAnnouncement();

            });

        });

    </script>
@endpush
