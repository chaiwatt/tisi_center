@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
    .swal-wide{
        width:450px !important;
    }
    .tip {
    position: relative;
    display: inline-block;
    color: red;
     cursor: pointer
   }

.tip .tooltiptext {
  visibility: hidden;
  width: 350px;
  background-color: #fff;
  color: black;
  border: 1px solid  #e5ebec;
  border-radius: 6px;
  padding: 10px 10px;
  font-size:13px;
  position: absolute;
  z-index: 1;
}

.tip:hover .tooltiptext {
  visibility: visible;
}

.text-sugar {
    color: #996633!important;
    cursor: pointer;
}
 
.text-sugar:hover {
    color: blue;
    text-decoration: underline;
    cursor: pointer;
  }
  .mb-3{margin-bottom:1rem!important}
  .visually-hidden {
  clip: rect(0 0 0 0);
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}
.pt-7px {
    padding-top: 7px;
}

.span-grey{
            background-color: #eee;
            color: #cc9900;
            padding-top: -10px;
            padding-right: 10px;
            padding-bottom: 5px;
            padding-left: 10px;
        }
  </style>

@endpush

 
 
<div class="panel panel-info">
    <div class="panel-heading">
          1. ข้อมูลแจ้งงานคดี
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
            
                    
                    @include('laws.cases.request-form.cases')

                </div>
            </div>

        </div>
    </div>
</div>

@if (count($approves->whereIn('status',['2','3','4'])) > 0)
<div class="panel panel-info">
    <div class="panel-heading">
        2. ผลพิจารณาคดี
        <div class="pull-right">
            <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in" aria-expanded="true">
        <div class="panel-body">
 @php
 
    $roles  = [
                '1'=>'ลมอ',
                '2'=>'รมอ', 
                '4'=>'ทป',
                '5'=>'ผอ',
                '6'=>'ผก',
                '7'=>'จนท'
             ];
     $status_cases   =     ['ขอข้อมูลเพิ่มเติม (ตีกลับ)'  => 'ขอข้อมูลเพิ่มเติม (ตีกลับ)','ยกเลิก' => 'ยกเลิก' ] ;
       //ผู้ใช้งาน
       $role_ids   = !empty(auth()->user()->RoleIds) ? auth()->user()->RoleIds : [];
 @endphp

 @foreach ($approves as $key => $approve)
 @php

     $shortname =  array_key_exists($approve->role,$roles) ?  $roles[$approve->role] : ''   ;
    //  $shortname =  !empty($approve->subdepartment->sub_depart_shortname) ?  $approve->subdepartment->sub_depart_shortname : null;
     $fullname =  !empty($approve->authorize_name) ?  $approve->authorize_name : null ;
     $position =  !empty($approve->position) ?  $approve->position : '' ;
     $level = ((int)$approve->level +1);
     $level_approve =   App\Models\Law\Cases\LawCasesLevelApprove::where('law_cases_id',$lawcase->id)->where('level', $level)->first();
     $send_position =  !empty($level_approve->authorize_name) ?  'ลำดับ '.$level.' : '.$level_approve->authorize_name :  'ส่งกองกฎหมาย';
 @endphp

@if ($approve->status == '3')
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ลำดับที่ {!! $approve->level ?? '' !!} : ผลพิจารณา สำหรับ  {!! $shortname !!}  </h3>
            </legend>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('fullname', 'ผู้มีอำนาจพิจารณา', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-3">
                           {!! Form::text('fullname',$fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('position', 'ตำแหน่ง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3">
                           {!! Form::text('position',$position, ['id'=>'position', 'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                    </div> 
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('status', 'สถานะ', ['class' => 'col-md-2 control-label text-right required'])) !!}
                        <div class="col-md-3">
                            <label>{!! Form::radio('status'.$key, '1', $approve->status  == '3' ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green', 'id'=>'status_1']) !!}&nbsp; เห็นชอบ &nbsp;</label>
                           <label>{!! Form::radio('status'.$key, '2',  $approve->status  == '4' ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','id'=>'status_2']) !!}&nbsp; ไม่เห็นชอบ &nbsp;</label>
                        </div>
                        {!! HTML::decode(Form::label('send_position', 'ส่งเรื่องต่อไปยัง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3">
                           {!! Form::text('send_position',$send_position, ['id'=>'send_position', 'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                    </div> 
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('name', 'ความคิดเห็น', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-10">
                            {!! Form::textarea('detail', @$approve->remark, ['id'=>'detail', 'class' => 'form-control ', 'rows' => 3, 'style' => 'width: 50%']) !!}
                        </div>
                    </div> 
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('', 'เมื่อวันที่', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3 pt-7px">
                            {{ @$approve->FormatCreateAtTime }}
                        </div>
                    </div> 
                </div>
            </div>

        </fieldset>
    </div>
</div> 
@elseif( $approve->status == '4')
@php

$return_to      =  App\Models\Law\Cases\LawCasesLevelApprove::where('law_cases_id',$lawcase->id)->where('authorize_userid', $approve->return_to)->first();
$send_return_to =  !empty($return_to->authorize_name) ? 'ลำดับ '.$return_to->level.' : '. $return_to->authorize_name :  '';
if(empty($return_to) && @$lawcase->created_by == @$approve->return_to){
    $send_return_to =  !empty($lawcase->CreatedName)?'ผู้แจ้งคดี : '.$lawcase->CreatedName:'';  
}
@endphp
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ลำดับที่ {!! $approve->level ?? '' !!} : ผลพิจารณา สำหรับ  {!! $shortname !!}  </h3>
            </legend>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('fullname', 'ผู้มีอำนาจพิจารณา', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-3">
                           {!! Form::text('fullname',$fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('position', 'ตำแหน่ง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3">
                           {!! Form::text('position',$position, ['id'=>'position', 'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                    </div> 
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('status', 'สถานะ', ['class' => 'col-md-2 control-label text-right required'])) !!}
                        <div class="col-md-3">
                            <label>{!! Form::radio('status'.$key, '1', $approve->status  == '3' ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green', 'id'=>'status_1']) !!}&nbsp; เห็นชอบ &nbsp;</label>
                           <label>{!! Form::radio('status'.$key, '2',  $approve->status  == '4' ? true : false , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red','id'=>'status_2']) !!}&nbsp; ไม่เห็นชอบ &nbsp;</label>
                        </div>
                     
                    </div> 
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('fullname', 'เนื่องจาก', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-3">
                           {!! Form::text('status_cases',( array_key_exists($approve->causes,$status_cases) ?  $status_cases[$approve->causes] : ''), ['class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('user_id', 'ส่งเรื่องกลับไปยัง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3">
                           {!! Form::text('user_id',  $send_return_to  , ['id'=>'user_id', 'class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                    </div>  
                </div>
            </div>
 
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('name', 'ความคิดเห็น', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-10">
                            {!! Form::textarea('detail', @$approve->remark, ['id'=>'detail', 'class' => 'form-control ', 'rows' => 3, 'style' => 'width: 50%']) !!}
                        </div>
                    </div> 
                </div>
            </div>
 
            <div class="row mb-3">
                <div class="col-md-12">
                    @if (!empty($approve->file_law_cases_approves_to) && $approve->status  == '4')
                    @php
                        $attach = $approve->file_law_cases_approves_to;
                    @endphp
                     {!! HTML::decode(Form::label('name', 'แนบไฟล์', ['class' => 'col-md-2 control-label text-right '])) !!}
                     <div class="col-md-3 pt-4">
                        <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank" title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}"> 
                             {!! !empty($attach->filename) ? $attach->filename : '' !!} {!! HP::FileExtension($attach->url) ?? '' !!}
                        </a>
                     </div>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('', 'เมื่อวันที่', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3 pt-7px">
                            {{ @$approve->FormatCreateAtTime }}
                        </div>
                    </div> 
                </div>
            </div>
            
        </fieldset>
    </div>
</div> 
@php
    break;
@endphp
@elseif($approve->status == '1')
@php
    break;
@endphp
@elseif($approve->status == '2' )
 
<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h3>ลำดับที่ {!! $approve->level ?? '' !!} : ผลพิจารณา สำหรับ  {!! $shortname !!}  </h3>
            </legend>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! HTML::decode(Form::label('fullname', 'ผู้มีอำนาจพิจารณา', ['class' => 'col-md-2 control-label text-right '])) !!}
                        <div class="col-md-3">
                           {!! Form::text('fullname',$fullname, ['class' => 'form-control ', 'disabled' => true]) !!}
                        </div>
                        {!! HTML::decode(Form::label('position', 'ตำแหน่ง', ['class' => 'col-md-2 control-label text-right'])) !!}
                        <div class="col-md-3">
                           {!! Form::text('position',$position, ['id'=>'position', 'class' => 'form-control ', 'disabled' => true]) !!}
                        
                        </div>
                        <div class="col-md-2">
                              @if(auth()->user()->getKey() == $approve->authorize_userid   || in_array('1',$role_ids) ||   in_array('56',$role_ids) )
                                    {{-- <span class="span-grey" >รอพิจารณา</span>  --}}
                                  <button type="button" class="btn  text-sugar"  
                                        data-id="{{$approve->law_cases_id}}" 
                                        data-approve_id="{{$approve->id}}"    
                                        data-level="{{$key+1}}"    
                                        data-shortname="{{$shortname}}"    
                                        data-fullname="{{$fullname}}" 
                                        data-position="{{$position}}"  
                                        data-send_position="{{$send_position}}"  >
                                        รอพิจารณา
                                </button>
                              @else
                                 <span class="span-grey">รอพิจารณา</span> 
                              @endif
                            
                         </div>
                    </div> 
                </div>
            </div>
        </fieldset>
    </div>
</div> 

@php
    break;
@endphp

@endif

 @endforeach

        </div>
    </div>
</div>
@endif




<div class="form-group ">
        <div class="clearfix"></div>
         <a  href="{{ url('/law/cases/forms_approved') }}"  class="btn btn-default btn-lg btn-block">
             <i class="fa fa-rotate-left"></i>
          <b>กลับ</b>
        </a>
</div>




@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>
 
        $(document).ready(function() {

            @if(\Session::has('flash_message'))
             Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif



            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});


            $("body").on("click", ".text-sugar", function() {     

                $('#actionStatus').find('input, select, textarea').not('.not_enable').prop('disabled', false);

                $('#m_id').val($(this).data('id'));  
                $('#approve_id').val($(this).data('approve_id'));
                $('#level').val($(this).data('level'));

                $('#span_no').html($(this).data('level'));
                $('#span_shortname').html($(this).data('shortname'));

                $('.fullname_modal').val($(this).data('fullname'));
                $('.position_modal').val($(this).data('position'));
                $('#send_position').val($(this).data('send_position'));

                $('#remark').val('');
                $('#attachs').val('');
                $('#attachs').prop('required', false);

                $('input[name=status][value=1]').prop('checked', true);
                $('input[name=status]').iCheck('update');
                function_status();
                
                $('#status_cases').val('').select2();
                $('#user_id').html("<option value='' >- เลือกส่งเรื่องกลับไปยัง -</option>").select2();
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ url('law/cases/forms_approved/get_user_approve') }}",
                    data: {
                            "_token": "{{ csrf_token() }}",
                            "id": $(this).data('id'),
                            "approve_id":$(this).data('approve_id'),
                            "level": $(this).data('level')
                        },
                }).success(function (obj) {
                    if(obj.message == true){
                        $.each(obj.datas, function( index, data ) {
                            $('#user_id').append('<option value="'+data.id+'"  >'+data.text+'</option>');
                        }); 
                    } 
                });
                
                $('#actionStatus').modal('show');

            });

            $('#form_status').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {
                Swal.fire({
                    title: 'ยืนยันการพิจารณา !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        // Text
                        $.LoadingOverlay("show", {
                            image       : "",
                            text        :   "กำลังบันทึกการพิจารณา กรุณารอสักครู่..." 
                        });
                        var formData = new FormData($("#form_status")[0]);
                            formData.append('_token', "{{ csrf_token() }}");

                            if($('#status_1').is(':checked',true)){
                                formData.append('status', "1");    
                            }else{
                                formData.append('status', "2");    
                            }
                    
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/law/cases/forms_approved/save') }}",
                            datatype: "script",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (msg) {
                                $.LoadingOverlay("hide");
                                if(msg.message == true){
                                    Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'บักทึกเรียบร้อยแล้ว!',
                                            showConfirmButton: false,
                                            timer: 1500
                                    });
                                    $('#actionStatus').modal('hide');
                                    $('#form_status').find('ul.parsley-errors-list').remove();
                                    $('#form_status').find('input,textarea').removeClass('parsley-success');
                                    $('#form_status').find('input,textarea').removeClass('parsley-error'); 
                                }else{
                                    Swal.fire({
                                            position: 'center',
                                            icon: 'error',
                                            title: 'เกิดข้อผิดพลาด!',
                                            showConfirmButton: false,
                                            timer: 1500
                                    });
                                }
                            }
                        });
                    }
                })
                return false;
            });

            
                    $('input[name=status]').on('ifChecked', function(event){
                        function_status();
                    });
                    function_status();
        });
        function function_status() {
            var status =  ($("input[name=status]:checked").val() == 1 )?'1':'2';
            if( status == 2){
                $('#box_status, #label_attachs , #box_attachs').removeClass('visually-hidden');
                $('#box_status').find('#status_cases, #user_id').prop('required', true);
                $('.send_position').addClass('visually-hidden');
            }else{
                $('#box_status, #label_attachs , #box_attachs').addClass('visually-hidden');
                $('#box_status').find('#status_cases, #user_id').prop('required', false);  
                $('.send_position').removeClass('visually-hidden');
            }
      
          }
 
        
        
    </script>
@endpush
