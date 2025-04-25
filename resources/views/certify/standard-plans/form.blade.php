@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
@endpush

<div class="form-group {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('std_type', 'ประเภทมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('std_type',
        App\Models\Bcertify\Standardtype::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
          null, 
        ['class' => 'form-control',
        'disabled'=> true,
        'placeholder'=>'- เลือกประเภทมาตรฐาน -']) !!}
        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>
    
<div class="form-group  {{ $errors->has('list[start_std]') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('list[start_std]', 'การกำหนดมาตรฐาน : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-9">
        <label>{!! Form::radio('start_std', '1', is_null($standardplan->start_std) ||  $standardplan->start_std == 1, ['class'=> "check start_std_check", 'data-id' => "#start_std", 'data-radio'=>'iradio_square-green']) !!} กำหนดใหม่ &nbsp;&nbsp;</label>
        <label>{!! Form::radio('start_std', '2', $standardplan->start_std == 2  , ['class'=> "check start_std_check", 'data-id' => "#start_std", 'data-radio'=>'iradio_square-green']) !!} ทบทวน &nbsp;&nbsp;</label>
    </div>
</div>
@if (!empty($standardplan->start_std) && $standardplan->start_std == 2)
<div class="form-group {{ $errors->has('list[ref_std]') ? 'has-error' : ''}}"  >
    {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('ref_std',
            App\Models\Certify\Standard::selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id'),
            null,
            ['class' => 'form-control',
            'disabled' => true,
            'placeholder'=>'- เลือกมาตรฐาน -']) !!}
        {!! $errors->first('list[ref_std]', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group {{ $errors->has('tis_number') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_number', 'เลขที่มาตรฐาน'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-3">
        {!! Form::text('tis_number', null, ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_number', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('tis_book', null, ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_book', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('tis_year',
                        HP::Years(),
                        null,
                        ['class' => 'form-control',
                         'disabled' => true,
                         'placeholder' => '- เลือกปีมาตรฐาน -'
                        ])
        !!}
        {!! $errors->first('tis_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('tis_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name', 'ชื่อมาตรฐาน'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name',    null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tis_name_eng') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('tis_name_eng', 'ชื่อมาตรฐาน (eng)'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('tis_name_eng',    null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('tis_name_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>

 
<div class="form-group {{ $errors->has('ref_document') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('ref_document', 'เอกสารอ้างอิง'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('ref_document', null , ['class' => 'form-control ' ,'disabled'=>true]) !!}
        {!! $errors->first('ref_document', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reason') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('reason', 'เหตุผลและความจำเป็น'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('reason', null  ,  ['class' => 'form-control ','disabled'=>true]) !!}  
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time', null ,  ['class' => 'form-control ','disabled'=>true]) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-4 control-label '])) !!}
    <div class="col-md-7">
        {!! Form::select('industry_target',
                          App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),//อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต
                           null,
                        ['class' => 'form-control',
                         'disabled'=>true,
                         'placeholder' => '- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -'
                        ])
        !!}
        {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
    </div>
</div>

@php
    $attach = $standardplan->AttachFileAttachTo;
@endphp
@if (!empty($attach))
<div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('attach', 'เอกสารที่เกี่ยวข้อง'.' : ', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
            {!! !empty($attach->caption) ? $attach->caption : '' !!}
            <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                {!! !empty($attach->filename) ? $attach->filename : '' !!}
            </a>
    </div>
</div>
@endif
@if (count($standardplan->boards) > 0)
<div class="form-group">

    {!! Html::decode(Form::label('name', 'ความเห็นการกำหนดมาตรฐาน : ', ['class' => 'col-md-3 control-label'])) !!}

    @php
        $boards = $standardplan->boards;
        $boards = count($boards)==0 ? collect([new App\Models\Tis\TisiEstandardDraftBoard]) : $boards ;
    @endphp
 
@if (!empty($boards))
    <div class="table-responsive col-md-9">
        <table class="table color-bordered-table primary-bordered-table">
            <thead>
                <tr>
                    <th class="col-md-1">#</th>
                    <th class="col-md-6">ความเห็นการกำหนดมาตรฐาน</th>
                    <th class="col-md-4">หน่วยงาน</th>
                </tr>
            </thead>
            <tbody class="box-board">
                 @foreach ($boards as $key => $board)
                 @php
                    $offers = $board->estandard_offers_to;
                @endphp   
                    <tr class="item-board">
                        <td class="text-top order-board">{{($key+1)}}.</td>
                        <td class="text-top">
                                {{ !empty($offers->title) ? $offers->title : null  }}
                        </td>
                        <td class="text-top data-board">
                            <p>ผู้ประสานงาน : {{ !empty($offers->name) ? $offers->name : null  }}</p>
                            <p>เบอร์โทร : {{ !empty($offers->telephone) ? $offers->telephone : null  }}</p>
                            <p>อีเมล : {{ !empty($offers->email) ? $offers->email : null  }}</p>
                            <p>หน่วยงาน : {{ !empty($offers->department) ? $offers->department : null  }}</p>
                        </td>
                   
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif      
</div>
@endif
{{-- <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('name', 'ความต้องการจาก'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::text('name', null,  ['class' => 'form-control ','disabled'=>true]) !!}  
    </div>
</div> --}}

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                  รายละเอียดการจัดทำแผน
                <div class="pull-right">
                    <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                </div>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
<div id="input_disabled">
    @php
        if(!empty($standardplan) && ($standardplan->status_id >= 3 && $standardplan->status_id != 6)){
            $standardplan->status_id = 3;
        }
    @endphp
 <div class="form-group {{ $errors->has('status_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status_id', 'สถานะ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('status_id',
         ['2'=>'อยู่ระหว่างจัดทำแผน','3'=>'นำส่งแผน'],
            null,
        ['class' => 'form-control',
        'required'=> true,
        'placeholder'=>'- เลือกสถานะ -']) !!}
        {!! $errors->first('status_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('method_id', 'วิธีการ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::select('method_id',
          App\Models\Basic\Method::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
            null,
        ['class' => 'form-control',
        'required'=> true,
        'placeholder'=>'- เลือกวิธีการ -']) !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('period') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('period', 'ระยะเวลา'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
          <div class=" input-group ">
            {!! Form::text('period', ( !empty($standardplan->period) ? $standardplan->period :(!empty($standardplan->method_to->period) ? $standardplan->method_to->period : null) ), ['class' => 'form-control input_number', 'required' => true,'id'=>'period']) !!}
            <span class="input-group-addon bg-secondary b-0  "> เดือน </span>
          </div>
          {!! $errors->first('period', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('plan_startdate') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('plan_startdate', 'กำหนด'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class="input-daterange input-group date-range">
            {!! Form::text('plan_startdate', null, ['class' => 'form-control date', 'required' => true,'id'=>"plan_startdate"]) !!}
            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
            {!! Form::text('plan_enddate', null, ['class' => 'form-control date', 'required' => true,'id'=>"plan_enddate"]) !!}
          </div>
        {!! $errors->first('plan_enddate', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('budget') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('budget', 'งบประมาณ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <div class=" input-group ">
            {!! Form::text('budget', null, ['class' => 'form-control amount', 'required' => true]) !!}
            <span class="input-group-addon bg-secondary b-0  "> บาท </span>
        </div>
        {!! $errors->first('budget', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('budget') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('budget', 'แหล่งที่มางบประมาณ'.' : '.'<span class="text-danger">*</span>', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-4">
        <label>{!! Form::radio('ref_budget', '1', is_null($standardplan->ref_budget) ||  $standardplan->ref_budget == 1, ['class'=> "check  ref_budget_check",  'data-radio'=>'iradio_square-green']) !!} งบประมาณ &nbsp;&nbsp;</label>
        <label>{!! Form::radio('ref_budget', '2', $standardplan->ref_budget == 2  , ['class'=> "check ref_budget_check", 'data-radio'=>'iradio_square-green']) !!} ผู้สนับสนุน &nbsp;&nbsp;</label>
    </div>
</div>

<div class="form-group {{ $errors->has('budget_by') ? 'has-error' : ''}}" id="div_budget_by">
    {!! Html::decode(Form::label('', ' ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
            {!! Form::text('budget_by', null, ['class' => 'form-control ','id'=>"budget_by",'placeholder'=>'ระบุผู้สนับสนุน']) !!}
           {!! $errors->first('budget_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}" >
    {!! Html::decode(Form::label('', 'หมายเหตุ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-8">
        {!! Form::textarea('remark', null, ['class' => 'form-control ', 'rows'=>'3']); !!}
           {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'ผู้จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-10">
        {{ !empty($standardplan->user_updated->FullName) ?  $standardplan->user_updated->FullName : auth()->user()->FullName }}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', 'วันที่จัดทำ'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-6 m-t-10">
        {{ !empty($standardplan->user_updated->FullName) ?  HP::DateTimeFullThai($standardplan->updated_at)  : HP::DateTimeFullThai(date('Y-m-d H:i:s')) }}
    </div>
</div>
</div>

@includeWhen((!empty($standardplan->id) && @$standardplan->TisiEstandardDraftPlanLogCheck), 'certify.standard-plans.log-form')
@if( !empty($standardplan) && ($standardplan->status_id >= 3 && $standardplan->status_id != 6)) 
<div class="clearfix"></div>
   <a  href="{{ url(app('url')->previous()) }}"  class="btn btn-default btn-lg btn-block" style="color:black">
      <i class="fa fa-rotate-left"></i>
     <b>กลับ</b>
 </a>
 @php
     $checkstatus = 'true';
 @endphp
@else 
@php
    $checkstatus = 'false';
@endphp
<div class="form-group div_hide">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit"    style="color:#ffffff">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standardplans'))
            <a class="btn btn-default" href="{{url('/certify/standard-plans')}}"  style="color:black">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>
@endif 
 
                </div>
            </div>
        </div>
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
      <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
      <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
 
      <script>
        $(document).ready(function () {
            /*$('#status_id').change(function(){
                if($(this).val()=='6'){
                    $(this).attr("required", false);
                }else{
                    $(this).attr("required", true);
                }
            });
            $('#status_id').change();
            $('#status_id option[value="6"]').attr("disabled", true);
            */
           
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/certify/standard-plans/data_log_list') !!}',
                    data: function (d) {
                        d.plan_id = '{{ $standardplan->id }}';
                    }
                },
                columns: [
                    { data: 'reverse_user', name: 'reverse_user' },
                    { data: 'reverse_detail', name: 'reverse_detail' },
                    { data: 'reverse_date', name: 'reverse_date' }, 
                    { data: 'update_user', name: 'update_user' },
                    { data: 'update_detail', name: 'update_detail' },
                    { data: 'update_date', name: 'update_date' },
                    { data: 'update_status', name: 'update_status' }
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,-1] }
                ],
                fnDrawCallback: function() {
                    
                }
            });

            $('#plan_startdate').change(function () {
                set_date();
            });

            $('#period').keyup(function () {
                set_date();
            });

            $('.start_std_check').prop('disabled', true);
            $('.start_std_check').parent().removeClass('disabled');
            $('.start_std_check').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});

         var checkstatus = "{{ $checkstatus }}";
        if(checkstatus == 'true'){
            $('#input_disabled').find('input, textarea, select, hidden, fileinput').prop('disabled',true);
            $('.ref_budget_check').prop('disabled', true);
            $('.ref_budget_check').parent().removeClass('disabled');
            $('.ref_budget_check').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%", "cursor": "not-allowed"});
        }
        
        $("input[name=ref_budget]").on("ifChanged",function(){
            ref_budget();
         });
         ref_budget();

           //ช่วงวันที่
           $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
                // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
            $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                        return false;
                    }
                });
            IsInputNumber();
        });
        function ref_budget(){
           var status = $("input[name=ref_budget]:checked").val();
        
           if(status == '1'){ // งบประมาณ   
                $('#div_budget_by').hide();
                $('#budget_by').prop('required' ,false);    
              }else { // ผู้สนับสนุน 
                $('#div_budget_by').show();   
                $('#budget_by').prop('required' ,true);  
             }
         }
 
        function IsInputNumber() {
                   // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                   String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                   };

                   var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน
                    var s_inum=new String(inum);
                    var num2=s_inum.split(".");
                    var n_inum="";
                    if(num2[0]!=undefined){
                   var l_inum=num2[0].length;
                   for(i=0;i<l_inum;i++){
                    if(parseInt(l_inum-i)%3==0){
                   if(i==0){
                    n_inum+=s_inum.charAt(i);
                   }else{
                    n_inum+=","+s_inum.charAt(i);
                   }
                    }else{
                   n_inum+=s_inum.charAt(i);
                    }
                   }
                    }else{
                   n_inum=inum;
                    }
                    if(num2[1]!=undefined){
                   n_inum+="."+num2[1];
                    }
                    return n_inum;
                   }
                   // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                   $(".amount").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                   return false;
                    }
                   });

                   // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ
                   $(".amount").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                               if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                           thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }else{ // ถ้าไม่มีคอมม่า
                           thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                   });
         }



         function set_date() {
            var period = $('#period').val();
            var plan_startdate = $('#plan_startdate').val();
            if(checkNone(period) && checkNone(plan_startdate)){
                $.ajax({
                            method: "GET",
                            url: "{{ url('certify/standard-plans/set_date_end') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "period": period,
                                "plan_startdate": plan_startdate
                        },
                        success : function (msg){
      
                            if(checkNone(msg.date)){
                                $('#plan_enddate').val(msg.date);
                            }      
                        }
                    });
            }
        }
            function checkNone(value) {
                return value !== '' && value !== null && value !== undefined;
            }

        </script>
@endpush
