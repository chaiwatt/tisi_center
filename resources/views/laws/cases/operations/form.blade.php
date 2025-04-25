@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .div_dotted {
        border-bottom: 1px dotted #000;
        padding: 0 0 5px 0;
        cursor: not-allowed;
    }

    .input_dotted {
        border: none;
        border-bottom: 1px dotted #000;
        cursor: not-allowed;
    }

    legend {
        margin-bottom: 0px;
    }
 
 

    .div-show{
        display: block;
    }
    .div-hide{
        display: none;
    }
    .input_dotted[disabled] {
        background-color: #ffffff;
        opacity: 1;
    }
    
 
    .btn-sm {
    padding: 2px 5px;
    font-size: 12px;
    font-family: 'Kanit', Open Sans, sans-serif;
    line-height: 1.5;
    border-radius: 3px;
}



 
</style>
@endpush


<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ข้อมูลผู้กระทำความผิด</h5>
            </legend>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">ชื่อผู้ประกอบการ/TAXID :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->offend_name) &&  !empty($cases->offend_taxid)   ? $cases->offend_name .' | '.$cases->offend_taxid: null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มอก./ผลิตภัณฑ์ :</label>
                        <div class="col-md-9">
                            {!! Form::text('', !empty($cases->StandardNo) ? $cases->StandardNo : ''  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">มาตราความผิด :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$cases->law_cases_result_to->OffenseSectionNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                        <label class="control-label col-md-3">อัตราโทษ :</label>
                        <div class="col-md-3">
                            {!! Form::text('',  !empty($cases->law_cases_result_to->PunishNumber)   ?  implode(", ",$cases->law_cases_result_to->PunishNumber)  : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">การจับกุม :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->law_basic_arrest)  ? $cases->law_basic_arrest : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row for-show">
                <div class="col-md-12">
                    <div class="form-group m-0">
                        <label class="control-label col-md-3">เจ้าของเรื่อง :</label>
                        <div class="col-md-9">
                            {!! Form::text('',  !empty($cases->owner_name)  ? $cases->owner_name : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
        </fieldset>
    </div>

    <div class="col-md-4">

        <div class="alert bg-dashboard5 m-t-15 text-center p-17"> {!!  !empty($cases->StatusText)   ? $cases->StatusText : null  !!} </div>

        <fieldset class="white-box">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">เลขคดี :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->case_number)   ? $cases->case_number : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div> 
            </div>

            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">นิติกร :</label>
                        <div class="col-md-7">
                             {!! Form::text('',  !empty($cases->user_lawyer_to->FullName)   ? $cases->user_lawyer_to->FullName : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>  

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">เลขที่อ้างอิงแจ้ง :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->ref_no)   ? $cases->ref_no : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-2">
                        <label class="control-label col-md-5">วันที่แจ้ง :</label>
                        <div class="col-md-7">
                            {!! Form::text('',  !empty($cases->created_at) ?  HP::DateThaiFull($cases->created_at) : null  ,  ['class' => 'form-control input_dotted', 'disabled'=> true]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="col-md-2">
    <ul class="nav tabs-vertical">

        <li class="tab active">
            <a data-toggle="tab" data-tap="#person" href="#person" aria-expanded="true"> 
                <span>
                    <i class="mdi mdi-account-outline"></i>
                     บันทึกดำเนินงานอาญา
                </span>
            </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" data-tap="#license" href="#license" aria-expanded="false"> 
                <span>
                    <i class="mdi mdi-certificate"></i>
                    บันทึกดำเนินงานปกครอง
                </span>
            </a>
        </li>
        <li class="tab">
            <a aria-expanded="false" data-toggle="tab" data-tap="#product" href="#product"> 
                <span>
                    <i class="mdi mdi-buffer"></i>
                    บันทึกดำเนินงานของกลาง
                </span>
            </a>
        </li>
        <li class="tab ">
            <a aria-expanded="false" data-toggle="tab" data-tap="#indict" href="#indict"> 
                <span>
                    <i class="mdi mdi-gavel"></i>
                    บันทึกดำเนินงานฟ้องคดี
                </span>
            </a>
        </li>
    </ul>
</div>

<div class="col-md-10  white-box">
    <div class="tab-content">
   
        <div id="person" class="tab-pane active">
            @include('laws.cases.operations.person')  
        </div>
        <div id="license" class="tab-pane">
            @include('laws.cases.operations.license')
        </div>
        <div id="product" class="tab-pane">
            @include('laws.cases.operations.product')
        </div>
        <div id="indict" class="tab-pane ">
            @include('laws.cases.operations.indict')
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group required{{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'สถานะ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('status',App\Models\Law\Cases\LawCasesOperation::list_status(),null,['class' => 'form-control ','id' => 'status','required' => true,'placeholder'=>'-เลือกสถานะ-']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}" id="div_case_number">
    {!! Form::label('remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('remark',null, ['class' => 'form-control', 'rows'=>'3']); !!}
        {!! $errors->first('remark', '<p class="help-block">:message</p> ') !!}
    </div>
</div>

{!! Form::hidden('prosecute' ,null, ['id' => 'prosecute' , 'required' => false])   !!}

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="button" id="btn_save">
            <i class="fa fa-save"></i> บันทึก
        </button>
        @can('view-'.str_slug('law-cases-result'))
            <a class="btn btn-default show_tag_a"  href="{{ url('/law/cases/operations') }}">
                <i class="fa fa-rotate-right"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
 
@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
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
                //ปฎิทิน
                $('.mydatepicker').datepicker({
                    autoclose: true,
                    toggleActive: true,
                    language:'th-th',
                    format: 'dd/mm/yyyy',
                });

            //บังคับกรอกเฉพาะแทป
            $('a[data-toggle="tab"]').click(function () {
                var id_tap = $(this).data('tap');
                required_tab(id_tap);
            });
            required_tab('');



            //แก้ไขแถวในตาราง
            $('body').on('click', '.staf_edit', function(){
            var row = $(this).parent().parent().parent();
                row.find('input, select, textarea').prop('readonly', false);
                row.find('input, select, textarea').prop('disabled', false);
                row.find('.show_tag_a').show();
                row.find('.status_job_track_id').remove();//ลบ hidden select
                
                row.find('.mydatepicker_edit').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        language:'th-th',
                        format: 'dd/mm/yyyy'
                });
            });

            $('#btn_save').click(function () { 
                var tap_indict = $('li.active').find('a[data-toggle="tab"]').data('tap');
                if(tap_indict == '#indict'){ //แทปฟ้องคดี
                    Swal.fire({
                        title: 'ฟ้องคดีแล้วใช่หรือไม่ ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#808080',
                        cancelButtonText: 'ไม่ฟ้อง',
                        confirmButtonText: 'ฟ้อง',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#prosecute').val('1'); 
                            $('#form-operations').submit();  
                        }else if(result.dismiss == 'cancel'){
                            $('#prosecute').val('0'); 
                            $('#form-operations').submit();
                        }
                    })
                }else{
                    $('#form-operations').submit();
                }
                
            });

        });
        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function required_tab(id_tap) {
            if(checkNone(id_tap)){
                $(id_tap).find('input.required, select').prop('required', true);
                $('.tab-pane:not('+id_tap+')').find('input.required, select').prop('required', false);
            }else{
                $('#person').find('input.required, select').prop('required', true);
                $('.tab-pane:not(#person)').find('input.required, select').prop('required', false);
            }
        }

    </script>
    @endpush
