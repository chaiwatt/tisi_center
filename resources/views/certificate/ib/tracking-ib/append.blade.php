


@extends('layouts.master')


  
@push('css')
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แนบท้าย</h3>
                    @can('view-'.str_slug('trackingib'))
                        <a class="btn btn-success pull-right" href="{{url('/certificate/tracking-ib/'.$tracking->id.'/edit')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
 
<div class="row">
    <div class="col-sm-12">
        <div class="form-group  pull-right">
            <button type="button" class="btn btn-primary add_certiib_file_all" data-toggle="modal" data-target="#exampleModalExport">เพิ่มไฟล์แนบท้าย</button>
        </div>
    </div>
 
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">
            <div class="table-responsive">
                    <table class="table color-bordered-table info-bordered-table"  id="myTable">
                        <thead>
                        <tr>
                        <th width="1%" class="text-center">#</th>
                        <th width="20%" class="text-center">ไฟล์แนบท้าย</th>
                        <th width="10%" class="text-center">วันที่ออกให้</th>
                        <th width="10%" class="text-center">วันที่หมดอายุ</th>
                        <th width="10%" class="text-center">สถานะ</th>
                        <th width="10%" class="text-center">วันที่บันทึก</th>
                        </tr>
                        </thead>
                        <tbody>
                           @php
                                $attach_path = 'files/applicants/check_files_ib/';
                          @endphp
                        @if ($certiib_file_all and $certiib_file_all->count() > 0)
                        @foreach ($certiib_file_all as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td class="text-center">
                                    <p class="text-center">
                                        @if(HP::checkFileStorage($item->attach))
                                            <a href="{{ url('funtions/get-view/'.$item->attach.'/'.  basename($item->attach_client_name))}}"   target="_blank">
                                                {!! HP::FileExtension($item->attach_client_name)  ?? '' !!}
                                            </a> 
                                        @elseif(!is_null($item->attach))
                                                <a href="{!! HP::getFileStorage($attach_path.$item->attach) !!}" class="attach" target="_blank">
                                                    {!! HP::FileExtension($item->attach) ?? '' !!}
                                                </a>
                                        @endif

                                        @if(HP::checkFileStorage($item->attach_pdf))
                                            <a href="{{ url('funtions/get-view/'.$item->attach_pdf.'/'.  basename($item->attach_pdf_client_name))}}"   target="_blank">
                                                {!! HP::FileExtension($item->attach_pdf_client_name)  ?? '' !!}
                                            </a> 
                                        @elseif(!is_null($item->attach_pdf))
                                            <a href="{!! HP::getFileStorage($attach_path.$item->attach_pdf) !!}" class="attach_pdf" target="_blank">
                                                {!! HP::FileExtension($item->attach_pdf) ?? '' !!}
                                            </a>
                                        @endif
                                    </p>
                                </td>
                                <td class="text-center">{{ HP::DateThai($item->start_date) ?? '-' }}</td>
                                <td class="text-center">{{ HP::DateThai($item->end_date) ?? '-' }}</td>
                                <td class="text-center">
                                    {{-- <div class="checkbox">
                                        {!! Form::checkbox('state', '1', !empty($item->state) && $item->state == '1' ? true : false , ['class' => 'js-switch', 'data-color'=>'#13dafe', 'data-item_id'=>$item->id]) !!}
                                    </div> --}}
                                    @if ($item->state == 1) 
                                         <span class="js-state pointer"   data-state="0"   title="ปิดใช้งาน">   <i class="fa fa-check-circle fa-lg text-success"></i>  </span>
                                    @else 
                                          <span class="js-state pointer" data-state="1" title="เปิดใช้งาน" >      <i class="fa fa-times-circle fa-lg text-danger"></i> </span>   
                                    @endif 

                                </td>
                                <td class="text-center">{{ HP::DateThai($item->created_at) }}</td>
                            </tr>
                        @endforeach
                    @endif

                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>


                      
 {!! Form::open(['url' => 'certificate/tracking-ib/update_append/'.$tracking->id,
                'class' => 'form-horizontal', 
                'files' => true,
                'method' => 'POST',
                'id'=>"form_append"]) 
!!}

<!-- Modal -->
<div class="modal fade " id="exampleModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalExportLabel">ไฟล์แนบท้าย 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h4>
            </div>
            <div class="modal-body">

 
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
                                            <input type="file" name="attach" id="attach" class="check_max_size_file" required accept=".doc,.docx">
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
                                            <input type="file" name="attach_pdf" id="attach_pdf" class="check_max_size_file" required accept=".pdf">
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
                                    {!! Form::text('start_date', null, ['class' => 'form-control date', 'required' => true , 'id' => 'modal_start_date']) !!}
                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                    {!! Form::text('end_date', null, ['class' => 'form-control date', 'required' => true, 'id' => 'modal_end_date']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

 
            </div>
            <div class="modal-footer">
                <input type="hidden" name="app_certi_cb_id" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" id="save_evidence" class="btn btn-primary">บันทึก</button>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}  
                   

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<script>
 
    $(document).ready(function () {
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
                          
            $('#form_append').parsley().on('field:validated', function() {
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

            check_max_size_file();
 
        $(".js-switch").each(function() {
                new Switchery($(this)[0], { size: 'small' });
         });

         $(".js-switch").change( function () {

                if($(this).prop('checked')){
                      $('.js-switch').prop('checked',false)
                        $(this).prop('checked',true)
                        $('.switchery-small').remove();
                       $(".js-switch").each(function( index, data) {
                          new Switchery($(this)[0], { secondaryColor :'red', size: 'small' });
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

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


});

</script>
 
@endpush


