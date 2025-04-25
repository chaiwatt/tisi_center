<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> มอบหมาย
</button>
<!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel1">{{ $title }}</h4>
            </div>
            <div class="modal-body">
                <form id="form_assign" action="{{ $route }}" method="post">
                    {{ csrf_field() }}
                    <div class="white-box">
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                        {!! Form::label('checker', 'เลือกเจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('checker', $users, null, ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-', 'required' => true]); !!}
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        {!! Form::button('<i class="icon-check"></i> บันทึก', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}

                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>