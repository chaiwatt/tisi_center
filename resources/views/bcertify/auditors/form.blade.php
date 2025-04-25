@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

@endpush
{{-- <div class="container"> --}}
    
    <div class="row">
        <div class="col-xs-12">
 

<div class="white-box"> 
    <div class="row">
        
        <div class="col-sm-6">
<!-- start ข้อมูลส่วนตัว -->
<legend><h3 class="box-title">ข้อมูลส่วนตัว</h3></legend>
<div class="col-sm-12">
   <div class="form-group {{ $errors->has('information[title]') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('information[title]', 'คำนำหน้า'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
      <div class="col-md-8">
          {!! Form::select('information[title]', 
            ['1'=>'นาย','2'=>'นาง','3'=>'นางสาว'], 
            (!empty($information->title) ? $information->title : null),
            ['class' => 'form-control',
            'id' => 'title',
            'required' => true , 
            'placeholder'=>'- เลือกคำนำหน้า (TH) -' ]); !!}
           {!! $errors->first('status', '<p class="help-block">:message</p>') !!} 
     </div>
   </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[th_fname]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[th_fname]', 'ชื่อ (TH)'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[th_fname]',   (!empty($information->fname_th) ? $information->fname_th : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => true]) !!}
                {!! $errors->first('information[th_fname]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[th_lname]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[th_lname]', 'นามสกุล (TH)'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[th_lname]',   (!empty($information->lname_th) ? $information->lname_th : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => true]) !!}
                {!! $errors->first('information[th_lname]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
 <div class="col-sm-12">
   <div class="form-group {{ $errors->has('information[title_en_js]') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('information[title_en_js]', 'คำนำหน้า'.' : ', ['class' => 'col-md-4 control-label'])) !!}
      <div class="col-md-8">
          {!! Form::select('information[title_en_js]', 
            ['1'=>'MR.','2'=>'MRS.','3'=>'Miss.'], 
            (!empty($information->title) ? $information->title : null),
            ['class' => 'form-control',
            'id' => 'title_en_js',
            'disabled' => true , 
            'placeholder'=>'- เลือกคำนำหน้า (EN) -' ]); !!}
           {!! $errors->first('status', '<p class="help-block">:message</p>') !!} 
     </div>
   </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[en_fname]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[en_fname]', 'ชื่อ (EN)'.' :', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[en_fname]',   (!empty($information->fname_en) ? $information->fname_en : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => false]) !!}
                {!! $errors->first('information[en_fname]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[en_lname]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[en_lname]', 'นามสกุล (EN)'.' :', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[en_lname]',   (!empty($information->lname_en) ? $information->lname_en : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => false]) !!}
                {!! $errors->first('information[en_lname]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<!-- end ข้อมูลส่วนตัว -->
 </div>

 <div class="col-sm-6">
     <legend><h3 class="box-title">เลขทะเบียนผู้ประเมิน</h3></legend>
  <!-- start เลขทะเบียนผู้ประเมิน -->   
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[regis_number]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[regis_number]', 'เลขทะเบียนผู้ประเมิน'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[regis_number]',  
                 (!empty($information->number_auditor) ? $information->number_auditor : null),
                  ['class' => 'form-control text-center', 
                  'placeholder'=>'กรอกเลขทะเบียนผู้ประเมิน', 
                   'required' =>   (!empty($information->number_auditor) ?  false : true),
                   'readonly' =>   (!empty($information->number_auditor) ?  true   : false)
                  ]) !!}
                {!! $errors->first('information[regis_number]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<div class="col-sm-12">
   <div class="form-group {{ $errors->has('information[department]') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('information[department]', 'หน่วยงาน'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
      <div class="col-md-8">
          {!! Form::select('information[department]', 
          App\Models\Basic\Department::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
          (!empty($information->department_id) ? $information->department_id : null),
          ['class' => 'form-control',
          'id' => 'department',
          'required' => true , 
          'placeholder'=>'- เลือกหน่วยงาน -' ]); !!}
           {!! $errors->first('information[department]', '<p class="help-block">:message</p>') !!} 
     </div>
   </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[position]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[position]', 'ตำแหน่ง'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[position]',   (!empty($information->position) ? $information->position : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => true]) !!}
                {!! $errors->first('information[position]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<div class="col-sm-12">
       <div class="form-group {{ $errors->has('information[choice]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[choice]', 'เจ้าหน้าที่ AB'.' : ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::checkbox('choice', '2',(!empty($information->choice)  ?  true :  false)  , ['class'=>'check','data-checkbox'=>"icheckbox_flat-red",'id'=>"choice"]) !!} Yes
          </div>
      </div>
</div>
<div class="col-sm-12 " id="group_space">
       <div class="form-group {{ $errors->has('information[group]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[group]', 'กลุ่ม'.' : <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
           {!! Form::select('information[group]', 
            ['1'=>'CB','2'=>'IB','3'=>'LAB 1 //ทดสอบ','4'=>'LAB 2 //ทดสอบ','5'=>'LAB 3 //สอบเทียบ'], 
            (!empty($information->group_id) ? $information->group_id : null),
            ['class' => 'form-control',
            'id' => 'group',
            'required' => false , 
            'placeholder'=>'- เลือกกลุ่ม -' ]); !!}
             <p class="text-center">(เฉพาะเลือก AB เท่านั้น)</p>
           {!! $errors->first('group', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>

<div class="col-sm-12">
    <div class="form-group {{ $errors->has('information[onOrOff]') ? 'has-error' : ''}}">
          {!! HTML::decode(Form::label('information[onOrOff]', 'สถานะ'.' : ', ['class' => 'col-md-4 control-label'])) !!}
          <div class="col-md-8">
              <label>{!! Form::radio('information[onOrOff]', '1', (!empty($information->onOrOff)  ?  false :  true), ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
              <label>{!! Form::radio('information[onOrOff]', '0',   (!empty($information->onOrOff) ? true : false), ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
          </div>
      </div>
 </div>
<!-- end เลขทะเบียนผู้ประเมิน -->

     </div>

  <div class="col-sm-12">
<!-- start ที่อยู่ -->
<legend><h3 class="box-title">ที่อยู่</h3></legend>

<div class="col-sm-12">
   <div class="form-group {{ $errors->has('information[address]') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('information[address]', 'ที่อยู่'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-2 control-label'])) !!}
      <div class="col-md-6">
           {!! Form::textarea('information[address]',  (!empty($information->address) ? $information->address : null), ['class' => 'form-control', 'rows'=>'2', 'required' => true]); !!}
           {!! $errors->first('information[address]', '<p class="help-block">:message</p>') !!} 
     </div>
   </div>
</div>

<div class="col-sm-6">
   <div class="form-group {{ $errors->has('information[province]') ? 'has-error' : ''}}">
      {!! HTML::decode(Form::label('information[province]', 'จังหวัด'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
      <div class="col-md-8">
          {!! Form::select('information[province]', 
          App\Models\Basic\Province::whereNull('state')->orderbyRaw('CONVERT(PROVINCE_NAME USING tis620)')->pluck('PROVINCE_NAME','PROVINCE_ID'),
          (!empty($information->province_id) ? $information->province_id : null),
          ['class' => 'form-control',
          'id' => 'province',
          'required' => true , 
          'placeholder'=>'- เลือกจังหวัด -' ]); !!}
           {!! $errors->first('information[province]', '<p class="help-block">:message</p>') !!} 
     </div>
   </div>
</div>
 
<div class="col-sm-6">
    <div class="form-group {{ $errors->has('information[amphur]') ? 'has-error' : ''}}">
       {!! HTML::decode(Form::label('information[amphur]', 'อำเภอ/เขต'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
       <div class="col-md-8">
          {!! Form::select('information[amphur]', 
              App\Models\Basic\Amphur::whereNull('state')->where('PROVINCE_ID',(!empty($information->province_id) ? $information->province_id : ''))->orderbyRaw('CONVERT(AMPHUR_NAME USING tis620)')->pluck('AMPHUR_NAME','AMPHUR_ID'),
             (!empty($information->amphur_id)  ?   $information->amphur_id :  null),
           ['class' => 'form-control',
           'id' => 'amphur',
           'required' => true , 
            'placeholder'=>'- เลือกอำเภอ/เขต -' ]); !!}
           {!! $errors->first('information[amphur]', '<p class="help-block">:message</p>') !!} 
       </div>
   </div>
</div>
<div class="col-sm-6">
    <div class="form-group {{ $errors->has('information[district]') ? 'has-error' : ''}}">
       {!! HTML::decode(Form::label('information[district]', 'ตำบล/แขวง'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
       <div class="col-md-8">
          {!! Form::select('information[district]', 
             App\Models\Basic\District::whereNull('state')->where('AMPHUR_ID',(!empty($information->amphur_id) ? $information->amphur_id : ''))->orderbyRaw('CONVERT(DISTRICT_NAME USING tis620)')->pluck('DISTRICT_NAME','DISTRICT_ID'),
             (!empty($information->district_id)  ?   $information->district_id :  null),
           ['class' => 'form-control',
           'id' => 'district',
           'required' => true , 
            'placeholder'=>'- เลือกตำบล/แขวง -' ]); !!}
           {!! $errors->first('information[district]', '<p class="help-block">:message</p>') !!} 
       </div>
   </div>
</div>
<div class="col-sm-6">
       <div class="form-group {{ $errors->has('information[tel]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[tel]', 'เบอร์โทรศัพท์'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::text('information[tel]',   (!empty($information->tel) ? $information->tel : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => true]) !!}
                {!! $errors->first('information[tel]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<div class="col-sm-6">
       <div class="form-group {{ $errors->has('information[email]') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('information[email]', 'E-mail'.' :<span class="text-danger">*</span> ', ['class' => 'col-md-4 control-label'])) !!}
           <div class="col-md-8">
                {!! Form::email('information[email]',   (!empty($information->email) ? $information->email : null), ['class' => 'form-control', 'placeholder'=>'', 'required' => true]) !!}
                {!! $errors->first('information[email]', '<p class="help-block">:message</p>') !!} 
          </div>
      </div>
</div>
<!-- end ที่อยู่ -->
 </div>



   </div>
</div>
  

        </div>
    </div>
   <div class="row">
      <div class="col-xs-12">
           <div class="tab" role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills" role="tablist">
                      <li class="tab active">
                          <a data-toggle="tab" href="#education" aria-expanded="true"> 
                            <span><i class='fa fa-graduation-cap'></i></span>
                              การศึกษา
                         </a>
                      </li>
                      <li class="tab  ">
                        <a data-toggle="tab" href="#training" aria-expanded="false"> 
                            <span><i class='fa fa-book'></i></span>
                            การฝึกอบรม
                        </a>
                      </li>
                      <li class="tab  ">
                        <a data-toggle="tab" href="#expertise" aria-expanded="false"> 
                            <span><i class='fa fa-child'></i></span>
                            ความเชี่ยวชาญ
                        </a>
                      </li>
                      <li class="tab ">
                        <a data-toggle="tab" href="#experience" aria-expanded="false"> 
                            <span><i class='fa fa-building'></i></span>
                            ประสบการณ์การทำงาน
                        </a>
                      </li>
                      <li class="tab ">
                        <a data-toggle="tab" href="#assessment_experience" aria-expanded="false"> 
                            <span><i class='fa fa-medkit'></i></span>
                            ประสบการณ์การตรวจประเมิน
                        </a>
                      </li>
                  </ul>
                  <div class="tab-content">
                     <div role="education" class="tab-pane fade in active" id="education">
                        @include ('bcertify/auditors.education') 
                      </div>
                      <div id="training" class="tab-pane">
                        @include ('bcertify/auditors.training') 
                      </div>
                      <div id="expertise" class="tab-pane ">
                        @include ('bcertify/auditors.expertise') 
                      </div>
                      <div id="experience" class="tab-pane ">
                         @include ('bcertify/auditors.experience') 
                      </div>
                      <div id="assessment_experience" class="tab-pane  ">
                          @include ('bcertify/auditors.assessment_experience') 
                      </div>
                  </div>

              </div>
        </div>
   </div>
 
{{-- </div> --}}
    <div class="form-group">
        <div class="col-md-offset-3 col-md-6 text-center">
            <button class="btn btn-primary" name="submit" type="submit" id="submit_form"    >
            <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('auditor'))
                <a class="btn btn-default" href="{{url('/bcertify/auditors')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
 
@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    
<script type="text/javascript">
    $(document).ready(function() {
        $('#title').change(function () {
             const select = $(this).val();
             $('#title_en_js').val(select).select2();
         })
         $('#title').change();
 
        // จังหวัด -> อำเภอ/เขต
         $('#province').change(function () {
             const select = $(this).val();
             const _token = $('input[name="_token"]').val();
             $('#amphur').html("<option value='' > - เลือกอำเภอ/เขต - </option>").select2();
             $('#district').html("<option value='' > - เลือกตำบล/แขวง - </option>").select2();
          
             if(checkNone(select)){
                $.ajax({
                     url:"{{route('bcertify.api.province')}}",
                     method:"POST",
                     data:{select:select,_token:_token},
                     success:function (result){
                         let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                         $('#amphur').empty();
                          $.each(data,function (index,value) {
                            $('#amphur').append('<option value='+value.AMPHUR_ID+'  >'+value.AMPHUR_NAME+'</option>');
                         })
                        
 
                     }
                })
             }
         })
    
         // อำเภอ/เขต -> ตำบล/แขวง
         $('#amphur').change(function () {
             const select = $(this).val();
             const _token = $('input[name="_token"]').val();
             $('#district').html("<option value='' > - เลือกตำบล/แขวง - </option>").select2();
  
             if(checkNone(select)){ 
                     $.ajax({
                     url:"{{route('bcertify.api.amphur')}}",
                     method:"POST",
                     data:{select:select,_token:_token},
                     success:function (result){
                       let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $('#district').empty();
                        $.each(data,function (index,value) {
                            $('#district').append('<option value='+value.DISTRICT_ID+' >'+value.DISTRICT_NAME+'</option>');
                       })
                       
                     }
                 })
             }
         })
       
         
         $("#choice").on("ifChanged",function(){
             if($(this).prop('checked')){
                     $('#group_space').fadeIn ();
                     $('#group').prop('required',true);
              }else{
                     $('#group_space').fadeOut();    
                     $('#group').prop('required',false);
              }
          })
          $('#group_space').fadeOut();
          @if (!empty($information->choice))
                $('#group_space').fadeIn();
                 $('#group').prop('required',true );    
          @endif
 
    });
 </script>     
    <script type="text/javascript">

        $('#submit_form').on('click',function () {
             $('#commentForm').submit();
        })
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        function DateFormateTh(str){
            var arr_mount = {} ;
                arr_mount['01']  = 'ม.ค.';
                arr_mount['02']  = 'ก.พ.';
                arr_mount['03']  = 'มี.ค.';
                arr_mount['04']  = 'เม.ษ.';
                arr_mount['05']  = 'พ.ค.';
                arr_mount['06']  = 'มิ.ย.';
                arr_mount['07']  = 'ก.ค.';
                arr_mount['08']  = 'ส.ค.';
                arr_mount['09']  = 'ก.ย.';
                arr_mount['10']  = 'ต.ค.';
                arr_mount['11']  = 'พ.ย.';
                arr_mount['12']  = 'ธ.ค.';
              var appoint_date=str;
              var getdayBirth=appoint_date.split("/");
              var YB=getdayBirth[2];
              var MB=getdayBirth[1];
              var DB=getdayBirth[0];
              var date = DB+' '+arr_mount[MB]+' '+YB ;
              return date;
          }

    </script>
@endpush
