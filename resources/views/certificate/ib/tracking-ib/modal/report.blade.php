
@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
     <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css?20200630')}}" rel="stylesheet" />
@endpush

{!! Form::open(['url' => 'certificate/tracking-ib/update_report/'.$report->id,    'method' => 'POST', 'class' => 'form-horizontal  ','id'=>'form_report', 'files' => true]) !!}

<div class="modal fade text-left" id="report" tabindex="-1" role="dialog" aria-labelledby="addBrand">
          <div class="modal-dialog  modal-xl" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel1">สรุปรายงานและเสนออนุกรรมการฯ
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </h4>
                  </div>
 <div class="modal-body"> 

<div class="row">
    <div class="col-md-12">

<div class="form-group {{ $errors->has('meet_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('meet_date', '<span class="text-danger">*</span> วันที่ประชุม'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
    <div class="col-md-4 text-left">
        <div class="input-group">
            {!! Form::text('report_date',  !empty($report->report_date)  ? HP::revertDate($report->report_date,true) :  null,  ['class' => 'form-control mydatepicker input_readonly','required'=>true,'placeholder'=>'dd/mm/yyyy'])!!}
            <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
        {!! $errors->first('meet_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('meet_date') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('meet_date', '<span class="text-danger">*</span> มติคณะกรรมการ'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
        <label>{!! Form::radio('report_status', '1',  !empty($report->report_status==2) ? false : true, ['class'=>'check check_readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp; เห็นชอบ &nbsp;</label>
        <label>{!! Form::radio('report_status', '2',  !empty($report->report_status==2) ? true : false , ['class'=>'check check_readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp; ไม่เห็นชอบ &nbsp;</label>
    </div>
</div>
<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('details', 'รายละเอียด'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
        {!! Form::textarea('details',  $report->details  ??  null ,  ['class' => 'form-control input_readonly','cols'=>'30','rows'=>'5'])!!}
         {!! $errors->first('details', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('details', '<span class="text-danger">*</span> ขอบข่ายที่ได้รับการเห็นชอบ'.': <span class="text-danger" style="font-size:10px;">(.pdf)</span>', ['class' => 'col-md-4 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
           @if(isset($report) && !is_null($report->FileAttachFileLoaTo))
                        <p class="text-left">
                            <a href="{{url('funtions/get-view/'.$report->FileAttachFileLoaTo->url.'/'.( !empty($report->FileAttachFileLoaTo->filename) ? $report->FileAttachFileLoaTo->filename :  basename($report->FileAttachFileLoaTo->url)  ))}}" 
                                title="{{  !empty($report->FileAttachFileLoaTo->filename) ? $report->FileAttachFileLoaTo->filename : basename($report->FileAttachFileLoaTo->url) }}" target="_blank">
                                {!! HP::FileExtension($report->FileAttachFileLoaTo->url)  ?? '' !!}
                            </a> 
                        </p> 
            @else 
                    <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">เลือกไฟล์</span>
                            <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="file_loa"  accept=".pdf"   id="file_loa" class="file_loa check_max_size_file" required>
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                    </div>
           @endif
    </div>
</div>
<div class="form-group {{ $errors->has('details') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('details', '<span class="text-danger">*</span> วันที่เริ่ม-สิ้นสุดขอบข่าย'.':', ['class' => 'col-md-4 control-label text-right'])) !!}
    <div class="col-md-7 text-left">
        <div class="input-daterange input-group date-range">
            {!! Form::text('start_date', !empty($report->start_date)  ? HP::revertDate($report->start_date,true) :  null, ['class' => 'form-control input_readonly','id'=>'start_date','required'=>true]) !!}
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('end_date', !empty($report->end_date)  ? HP::revertDate($report->end_date,true) :  null, ['class' => 'form-control input_readonly','id'=>'end_date','required'=>true]) !!}
          </div>
    </div>
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}" >
    {!! HTML::decode(Form::label('status', 'หลักฐานอื่นๆ'.' :', ['class' => 'col-md-4 control-label text-right'])) !!}
      <div class="col-md-7">
       <button type="button" class="btn btn-sm btn-success m-l-10 data_hide" id="attach-add">
           <i class="icon-plus"></i>&nbsp;เพิ่ม
       </button>
       @if (!empty($report->FileAttachFilesMany) && count($report->FileAttachFilesMany) > 0)
            @foreach ($report->FileAttachFilesMany as $item)
                 <p id="remove_attach_all{{$item->id}}">
                        <a href="{{url('funtions/get-view/'.$item->url.'/'.( !empty($item->filename) ? $item->filename :  basename($item->url)  ))}}" 
                            title="{{  !empty($item->filename) ? $item->filename : basename($item->url) }}" target="_blank">
                            {!! HP::FileExtension($item->url)  ?? '' !!}
                        </a> 
                        @if($report->report_status == 2)
                            <button class="btn btn-danger btn-xs deleteFlie div_hide"
                                type="button" onclick="deleteFlieAttachAll({{$item->id}})">
                                <i class="icon-close"></i>
                            </button>   
                        @endif
                </p>
            @endforeach
       @endif
       <div id="attach-box" class="data_hide">
          <div class="form-group other_attach_item">
             <div class="col-md-5">
                  {!! Form::text('file_desc[]', null, ['class' => 'form-control m-t-10', 'placeholder' => 'ชื่อไฟล์']) !!}
             </div>
             <div class="col-md-6">
                  <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                         <div class="form-control" data-trigger="fileinput">
                             <i class="glyphicon glyphicon-file fileinput-exists"></i>
                             <span class="fileinput-filename"></span>
                         </div>
                         <span class="input-group-addon btn btn-default btn-file">
                             <span class="fileinput-new">เลือกไฟล์</span>
                             <span class="fileinput-exists">เปลี่ยน</span>
                              <input type="file" name="file[]" class="  check_max_size_file">
                          </span>
                         <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                 </div>
             </div>
             <div class="col-md-1 text-left m-t-15" style="margin-top: 3px">
                 <button class="btn btn-danger btn-sm attach-remove" type="button" >
                     <i class="icon-close"></i>
                 </button>
             </div>
         </div>
      </div>
    </div>
</div>

    </div> 
</div>

</div>

@if ($certi->status_id == 6)
<input type="hidden" name="previousUrl" id="previousUrl" value="{{  url('certificate/tracking-ib/'.$certi->id.'/edit')  }}">
<div class="modal-footer">
    <button type="submit" class="btn btn-success" onclick="submit_form_report();return false">ยืนยัน</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
</div> 
@endif


        </div>
    </div>
</div>
{!! Form::close() !!}
      
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
     <!-- ===== PARSLEY JS Validation ===== -->
     <script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
     <script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>
     <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
     <script>
         function submit_form_report() {
                 Swal.fire({
                         title: 'ยืนยันทำรายการ !',
                         icon: 'warning',
                         showCancelButton: true,
                         confirmButtonColor: '#3085d6',
                         cancelButtonColor: '#d33',
                         confirmButtonText: 'บันทึก',
                         cancelButtonText: 'ยกเลิก'
                         }).then((result) => {
                             if (result.value) {
                                 $('#form_report').submit();
                             }
                         })
             }
         $(document).ready(function () {




             check_max_size_file();
                    // สรุปรายงานและเสนออนุกรรมการฯ
              $('#form_report').parsley().on('field:validated', function() {
                 var ok = $('.parsley-error').length === 0;
                  $('.bs-callout-info').toggleClass('hidden', !ok);
                  $('.bs-callout-warning').toggleClass('hidden', ok);
              })  .on('form:submit', function() {
                     // Text
                     $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                       });
                 return true; // Don't submit form for this demo
              });

             //เพิ่มไฟล์แนบ
             $('#attach-add').click(function(event) {
                 $('.other_attach_item:first').clone().appendTo('#attach-box');
     
                 $('.other_attach_item:last').find('input').val('');
                 $('.other_attach_item:last').find('a.fileinput-exists').click();
                 $('.other_attach_item:last').find('a.view-attach').remove();
     
                 ShowHideRemoveBtn94();
                 check_max_size_file();
             });
     
             //ลบไฟล์แนบ
             $('body').on('click', '.attach-remove', function(event) {
                 $(this).parent().parent().remove();
                 ShowHideRemoveBtn94();
             });
     
             ShowHideRemoveBtn94();
         });
     
         function ShowHideRemoveBtn94() { //ซ่อน-แสดงปุ่มลบ
     
             if ($('.other_attach_item').length > 1) {
                 $('.attach-remove').show();
             } else {
                 $('.attach-remove').hide();
             }
         }
     </script>
     <script type="text/javascript">
      
      function  deleteFlieAttachAll(id){
               Swal.fire({
                     icon: 'error',
                     title: 'ยื่นยันการลบไฟล์แนบ !',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'บันทึก',
                     cancelButtonText: 'ยกเลิก'
                     }).then((result) => {
                         if (result.value) {
                            $.ajax({
                                 url: "{!! url('/certificate/tracking-labs/delete_file') !!}"  + "/" + id
                             }).done(function( object ) {
                                 if(object == 'true'){
                                     $('#remove_attach_all'+id).remove();
                                 }else{
                                     Swal.fire('ข้อมูลผิดพลาด');
                                 }
                             });
     
                         }
                     })
              }
         </script>
    @endpush
    