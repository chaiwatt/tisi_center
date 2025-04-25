@php
    $amount_meeting = 0;
    $cost_sum = 0;
    foreach ($meetingstandards as $meetingstandard){
        if(!is_null($meetingstandard->record)){//มีการบันทึกการประชุม
            $amount_meeting++;
            $cost_sum += $meetingstandard->record->costs->pluck('cost')->sum();
        }
    }
@endphp

@if ($amount_meeting > 0)
    <div class="panel-group accordion-id" id="accordion">
        <div class="panel panel-info">
            <div class="panel card-collaps">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4" > สรุปรายงานการประชุม </a>
                    </h4>
                </div>

                <div id="collapse4" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <div class="form-group {{ $errors->has('status_id') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('status_id', 'สถานะการกำหนดมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-6">
                                {!! Form::select('status_id', ['2'=>'อยู่ระหว่างการประชุม', '3'=>'อยู่ระหว่างสรุปรายงานการประชุม', '4'=>'สรุปวาระการประชุมเรียบร้อย'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะการกำหนดมาตรฐาน-', 'required' => true]); !!}
                                {!! $errors->first('status_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="  {{ $errors->has('amount_sum') ? 'has-error' : ''}}">
                                {!! HTML::decode(Form::label('amount_sum', 'จำนวนครั้งในการประชุมทั้งหมด :', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-4">
                                    <div class="input-group" >
                                        {!! Form::text('amount_sum', (!empty($setstandard_summeeting) ? $setstandard_summeeting->amount_sum : $amount_meeting), ['class' => 'form-control text-right amount', 'readonly'=>true, 'required' => true]) !!}
                                        <span class="input-group-addon bg-secondary  b-0 text-dark"> ครั้ง </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group">
                            <div class="  {{ $errors->has('cost_sum') ? 'has-error' : ''}}">
                                {!! HTML::decode(Form::label('cost_sum', 'ค่าใช้จ่ายในการประชุมทั้งหมด :', ['class' => 'col-md-3 control-label '])) !!}
                                <div class="col-md-4">
                                     <div class="input-group" >
                                        {!! Form::text('cost_sum', (!empty($setstandard_summeeting) ? $setstandard_summeeting->cost_sum : $cost_sum), ['class' => 'form-control text-right amount','id'=>'amount_bill_all','readonly'=>true, 'required' => true]) !!}
                                        <span class="input-group-addon bg-secondary  b-0 text-dark"> บาท </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('detail', 'รายละเอียด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
                            <div class="col-md-6">
                                  {!! Form::textarea('detail', (!empty($setstandard_summeeting) ? $setstandard_summeeting->detail : ''), ['class' => 'form-control assessment_desc', 'rows'=>'2', 'required' => true]); !!}
                            </div>
                        </div>

                        @php
                            $file_setstandard_summeeting = [];
                            if( !empty($setstandard_summeeting) ){
                                $file_setstandard_summeeting = App\AttachFile::where('ref_table', (new App\Models\Certify\SetStandardSummeetings)->getTable())
                                                                ->where('ref_id', $setstandard_summeeting->id)
                                                                ->where('section', 'file_set_standards_summeeting')
                                                                ->get();
                            }
                        @endphp

                        <div id="attach-box">
                            @if( count($file_setstandard_summeeting) > 0 )

                            @foreach ( $file_setstandard_summeeting as $other )
                                <div class="form-group">
                                    {!! Form::label('', '', ['class' => 'col-md-3 control-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('file_meet', ( !empty($other->caption)?$other->caption:null ),['class' => 'form-control' , 'disabled' => true]) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{!! HP::getFileStorage($other->url) !!}" target="_blank">
                                            {!! HP::FileExtension($other->filename)  ?? '' !!}
                                        </a>
                                        <a class="btn btn-danger btn-xs show_tag_a" href="{!! url('certify/delete-files/'.($other->id).'/'.base64_encode('certify/set-standards/'.$setstandard->id.'/edit') ) !!}" title="ลบไฟล์"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </div>

                                </div>
                                @endforeach

                            @endif
                            <div class="form-group other_attach_item">
                                {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
                               <div class="col-md-4">
                                    {!! Form::text('file_desc[]', null, ['class' => 'form-control', 'placeholder' => 'ชื่อไฟล์']) !!}
                               </div>
                               <div class="col-md-4">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                           <div class="form-control" data-trigger="fileinput">
                                               <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                               <span class="fileinput-filename"></span>
                                           </div>
                                           <span class="input-group-addon btn btn-default btn-file">
                                               <span class="fileinput-new">เลือกไฟล์</span>
                                               <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="file[]" class="check_max_size_file">
                                            </span>
                                           <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                   </div>
                               </div>
                               <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-success attach-add"  id="attach-add">
                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                    </button>
                                    <div class="button_remove"></div>
                               </div>
                           </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('js')

<script>

    $(document).ready(function () {

             //เพิ่มไฟล์แนบ
             $('#attach-add').click(function(event) {
                 $('.other_attach_item:first').clone().appendTo('#attach-box');

            var last_new = $('.other_attach_item:last');
                $(last_new).find('input').val('');
                $(last_new).find('a.fileinput-exists').click();
                $(last_new).find('a.view-attach').remove();
                $(last_new).find('button.attach-add').remove();
                $(last_new).find('.button_remove').html('<button class="btn btn-danger btn-sm select-remove" type="button"> <i class="icon-close"></i> ลบ </button>');
                 check_max_size_file();
             });

             //ลบไฟล์แนบ
             $('body').on('click', '.attach-remove', function(event) {
                 $(this).parent().parent().remove();
             });

             check_max_size_file();
         });

    function check_max_size_file() {
        var max_size = "{{ ini_get('upload_max_filesize') }}";
        var res = max_size.replace("M", "");
        $('.check_max_size_file').bind('change', function() {
            if( $(this).val() != ''){
            var size =   (this.files[0].size)/1024/1024 ; // หน่วย MB
              console.log(this.files[0]);
              if(size > res ){
                Swal.fire(
                        'ขนาดไฟล์เกินกว่า ' + res +' MB',
                        '',
                        'info'
                        )
                $(this).parent().parent().find('.fileinput-exists').click();
                $(this).val('');
                $(this).parent().parent().find('.custom-file-label').html('');
                  return false;
              }
            }
        });
    }

</script>
@endpush
