@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush



<div class="row">
  <div class="col-xs-12">
       <div class="tab" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                  <li class="tab active">
                      <a data-toggle="tab" href="#tab_lab" aria-expanded="true"> 
                        <span><i class='fa fa-graduation-cap'></i></span>
                        ห้องปฏิบัติการ (LAB)
                     </a>
                  </li>
                  <li class="tab  ">
                    <a data-toggle="tab" href="#tab_ib" aria-expanded="false"> 
                        <span><i class='fa fa-book'></i></span>
                        หน่วยตรวจ (IB)
                    </a>
                  </li>
                  <li class="tab  ">
                    <a data-toggle="tab" href="#tab_cb" aria-expanded="false"> 
                        <span><i class='fa fa-child'></i></span>
                        หน่วยรับรอง (CB)
                    </a>
                  </li>
              </ul>
    <div class="tab-content">
  <!-- start ห้องปฏิบัติการ (LAB) -->
<div role="tab_lab" class="tab-pane fade in active" id="tab_lab">
<div class="white-box"> 
      <div class="row">
         <div class="col-sm-12">
  <legend><h3 class="box-title"> ห้องปฏิบัติการ (LAB)</h3></legend>
<div class="form-group {{ $errors->has('payin1_status') ? 'has-error' : ''}}">
  {!! Form::label('payin1_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 1)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin1_status', '1', !empty($feewaiver->payin1_status) && $feewaiver->payin1_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin1_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('AMPHUR_CODE') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_CODE', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin1_start_date',  
                   !empty($feewaiver->payin1_start_date) ?  HP::revertDate($feewaiver->payin1_start_date,true)  : null ,
                   ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin1_end_date', 
                   !empty($feewaiver->payin1_end_date) ?  HP::revertDate($feewaiver->payin1_end_date,true)  : null ,
                    ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_end_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin1_file') ? 'has-error' : ''}}">
  {!! Form::label('payin1_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      @if(!empty($feewaiver->payin1_file) && HP::checkFileStorage($feewaiver->payin1_file)) 
        <div id="delete_payin1">
              <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver->payin1_file).'/'.( !empty($feewaiver->payin1_file_client_name) ? $feewaiver->payin1_file_client_name :  basename($feewaiver->payin1_file)  ))}}" target="_blank">
              {!! HP::FileExtension($feewaiver->payin1_file)  ?? '' !!}
              </a>
              @can('delete-'.str_slug('feewaiver'))
              <button class="btn btn-danger btn-xs   " type="button"  onclick="delete_payin_file('1','1')" >
                    <i class="icon-close"></i>
              </button>   
              @endcan
        </div>
        <div id="add_payin1"> </div>
      @else 
        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
              <i class="glyphicon glyphicon-file fileinput-exists"></i>
              <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
              <span class="fileinput-new">เลือกไฟล์</span>
              <span class="fileinput-exists">เปลี่ยน</span>
              <input type="file" name="payin1_file" id="payin1_file" class="check_max_size_file">
              </span>
              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
      @endif
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_status') ? 'has-error' : ''}}">
  {!! Form::label('payin2_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 2)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin2_status', '1',  !empty($feewaiver->payin2_status) && $feewaiver->payin2_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin2_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('payin2_start_date') ? 'has-error' : ''}}">
  {!! Form::label('payin2_start_date', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin2_start_date',
                   !empty($feewaiver->payin2_start_date) ?  HP::revertDate($feewaiver->payin2_start_date,true)  : null,
                    ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin2_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin2_end_date',
                   !empty($feewaiver->payin2_end_date) ?  HP::revertDate($feewaiver->payin2_end_date,true)  : null,
                    ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin2_end_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_file') ? 'has-error' : ''}}">
      {!! Form::label('payin2_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-6">
          
            @if(!empty($feewaiver->payin2_file) && HP::checkFileStorage($feewaiver->payin2_file)) 
            <div id="delete_payin2">
            <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver->payin2_file).'/'.( !empty($feewaiver->payin2_file_client_name) ? $feewaiver->payin2_file_client_name :  basename($feewaiver->payin2_file)  ))}}" target="_blank">
                 {!! HP::FileExtension($feewaiver->payin2_file)  ?? '' !!}
             </a>
             @can('delete-'.str_slug('feewaiver'))
                  <button class="btn btn-danger btn-xs" type="button" onclick="delete_payin_file('2','1')"  >
                  <i class="icon-close"></i>
                  </button>   
            @endcan
            </div>
            <div id="add_payin2"> </div>
          @else 
          <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                <input type="file" name="payin2_file" id="payin2_file" class="check_max_size_file">
               </span>
               <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
           </div>
          @endif
      </div>
</div>
 


        </div>
      </div>
  </div>
 </div>
<!-- END ห้องปฏิบัติการ (LAB) -->
<!-- start  หน่วยตรวจ (IB) -->
<div id="tab_ib" class="tab-pane">
 <div class="white-box"> 
    <div class="row">
       <div class="col-sm-12">
  <legend><h3 class="box-title">หน่วยตรวจ (IB) </h3></legend>
<div class="form-group {{ $errors->has('payin1_ib_status') ? 'has-error' : ''}}">
  {!! Form::label('payin1_ib_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 1)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin1_ib_status', '1', !empty($feewaiver_ib->payin1_status) && $feewaiver_ib->payin1_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin1_ib_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('AMPHUR_CODE') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_CODE', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin1_ib_start_date', 
                   !empty($feewaiver_ib->payin1_start_date) ?  HP::revertDate($feewaiver_ib->payin1_start_date,true)  : null , 
                   ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_ib_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin1_ib_end_date', 
                  !empty($feewaiver_ib->payin1_end_date) ?  HP::revertDate($feewaiver_ib->payin1_end_date,true)  : null , 
                  ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_ib_end_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin1_ib_file') ? 'has-error' : ''}}">
  {!! Form::label('payin1_ib_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      @if(!empty($feewaiver_ib->payin1_file) && HP::checkFileStorage($feewaiver_ib->payin1_file)) 
        <div id="delete_payin1_ib">
              <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver_ib->payin1_file).'/'.( !empty($feewaiver_ib->payin1_file_client_name) ? $feewaiver_ib->payin1_file_client_name :  basename($feewaiver_ib->payin1_file)  ))}}" target="_blank">
              {!! HP::FileExtension($feewaiver_ib->payin1_file)  ?? '' !!}
              </a>
              @can('delete-'.str_slug('feewaiver'))
              <button class="btn btn-danger btn-xs   " type="button"  onclick="delete_payin_file('1_ib','2')" >
                    <i class="icon-close"></i>
              </button>   
              @endcan
        </div>
        <div id="add_payin1_ib"> </div>
      @else 
        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
              <i class="glyphicon glyphicon-file fileinput-exists"></i>
              <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
              <span class="fileinput-new">เลือกไฟล์</span>
              <span class="fileinput-exists">เปลี่ยน</span>
              <input type="file" name="payin1_ib_file" id="payin1_ib_file" class="check_max_size_file">
              </span>
              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
      @endif
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_ib_status') ? 'has-error' : ''}}">
  {!! Form::label('payin2_ib_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 2)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin2_ib_status', '1',  !empty($feewaiver_ib->payin2_status) && $feewaiver_ib->payin2_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin2_ib_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('payin2_ib_start_date') ? 'has-error' : ''}}">
  {!! Form::label('payin2_ib_start_date', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin2_ib_start_date', 
                  !empty($feewaiver_ib->payin2_start_date) ?  HP::revertDate($feewaiver_ib->payin2_start_date,true)  : null, 
                  ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin2_ib_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin2_ib_end_date', 
                  !empty($feewaiver_ib->payin2_end_date) ?  HP::revertDate($feewaiver_ib->payin2_end_date,true)  : null,
                   ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin2_ib_end_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_ib_file') ? 'has-error' : ''}}">
      {!! Form::label('payin2_ib_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-6">
          
            @if(!empty($feewaiver_ib->payin2_file) && HP::checkFileStorage($feewaiver_ib->payin2_file)) 
            <div id="delete_payin2_ib">
            <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver_ib->payin2_file).'/'.( !empty($feewaiver_ib->payin2_file_client_name) ? $feewaiver_ib->payin2_file_client_name :  basename($feewaiver_ib->payin2_file)  ))}}" target="_blank">
                 {!! HP::FileExtension($feewaiver_ib->payin2_file)  ?? '' !!}
             </a>
             @can('delete-'.str_slug('feewaiver'))
                  <button class="btn btn-danger btn-xs" type="button" onclick="delete_payin_file('2_ib','2')"  >
                  <i class="icon-close"></i>
                  </button>   
            @endcan
            </div>
            <div id="add_payin2_ib"> </div>
          @else 
          <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                <input type="file" name="payin2_ib_file" id="payin2_ib_file" class="check_max_size_file">
               </span>
               <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
           </div>
          @endif
      </div>
</div>

      </div>
    </div>
</div>
</div>
<!-- end  หน่วยตรวจ (IB) -->
<!-- start  หน่วยรับรอง (CB) -->
<div id="tab_cb" class="tab-pane">
   <div class="white-box"> 
      <div class="row">
         <div class="col-sm-12">
  <legend><h3 class="box-title"> หน่วยรับรอง (CB)</h3></legend>
<div class="form-group {{ $errors->has('payin1_cb_status') ? 'has-error' : ''}}">
  {!! Form::label('payin1_cb_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 1)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin1_cb_status', '1', !empty($feewaiver_cb->payin1_status) && $feewaiver_cb->payin1_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin1_cb_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('payin1_cb_start_date') ? 'has-error' : ''}}">
  {!! Form::label('payin1_cb_start_date', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin1_cb_start_date',  
                  !empty($feewaiver_cb->payin1_start_date) ?  HP::revertDate($feewaiver_cb->payin1_start_date,true)  : null , 
                  ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_cb_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin1_cb_end_date', 
                   !empty($feewaiver_cb->payin1_end_date) ?  HP::revertDate($feewaiver_cb->payin1_end_date,true)  : null , 
                   ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_cb_end_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin1_cb_file') ? 'has-error' : ''}}">
  {!! Form::label('payin1_cb_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      @if(!empty($feewaiver_cb->payin1_file) && HP::checkFileStorage($feewaiver_cb->payin1_file)) 
        <div id="delete_payin1_cb">
              <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver_cb->payin1_file).'/'.( !empty($feewaiver_cb->payin1_file_client_name) ? $feewaiver_cb->payin1_file_client_name :  basename($feewaiver_cb->payin1_file)  ))}}" target="_blank">
              {!! HP::FileExtension($feewaiver_cb->payin1_file)  ?? '' !!}
              </a>
              @can('delete-'.str_slug('feewaiver'))
              <button class="btn btn-danger btn-xs   " type="button"  onclick="delete_payin_file('1_cb','3')" >
                    <i class="icon-close"></i>
              </button>   
              @endcan
        </div>
        <div id="add_payin1_cb"> </div>
      @else 
        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
              <i class="glyphicon glyphicon-file fileinput-exists"></i>
              <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
              <span class="fileinput-new">เลือกไฟล์</span>
              <span class="fileinput-exists">เปลี่ยน</span>
              <input type="file" name="payin1_cb_file" id="payin1_cb_file" class="check_max_size_file">
              </span>
              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
      @endif
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_cb_status') ? 'has-error' : ''}}">
  {!! Form::label('payin2_cb_status', 'ยกเว้นค่าธรรรมเนียม (Pay-in 2)'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="checkbox">
            {!! Form::checkbox('payin2_cb_status', '1',  !empty($feewaiver_cb->payin2_status) && $feewaiver_cb->payin2_status == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
           </div>
         {!! $errors->first('payin2_cb_status', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('payin2_cb_start_date') ? 'has-error' : ''}}">
  {!! Form::label('payin2_cb_start_date', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group date-range">
            <div class="input-group">
                  {!! Form::text('payin2_cb_start_date', !empty($feewaiver_cb->payin2_start_date) ?  HP::revertDate($feewaiver_cb->payin2_start_date,true)  : null, ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'required' => true]) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
            <label class="input-group-addon bg-white b-0 control-label "> ถึงวันที่ </label>
            <div class="input-group">
                  {!! Form::text('payin2_cb_end_date', !empty($feewaiver_cb->payin2_end_date) ?  HP::revertDate($feewaiver_cb->payin2_end_date,true)  : null, ['class' => 'form-control','placeholder'=>"mm/dd/yyyy", 'required' => true]) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div>
<div class="form-group {{ $errors->has('payin2_cb_file') ? 'has-error' : ''}}">
      {!! Form::label('payin2_cb_file', 'ไฟล์แนบเอกสารยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
      <div class="col-md-6">
          
            @if(!empty($feewaiver_cb->payin2_file) && HP::checkFileStorage($feewaiver_cb->payin2_file)) 
            <div id="delete_payin2_cb">
            <a href="{{url('funtions/get-view-file/'.base64_encode($feewaiver_cb->payin2_file).'/'.( !empty($feewaiver_cb->payin2_file_client_name) ? $feewaiver_cb->payin2_file_client_name :  basename($feewaiver_cb->payin2_file)  ))}}" target="_blank">
                 {!! HP::FileExtension($feewaiver_cb->payin2_file)  ?? '' !!}
             </a>
             @can('delete-'.str_slug('feewaiver'))
                  <button class="btn btn-danger btn-xs" type="button" onclick="delete_payin_file('2_cb','3')"  >
                  <i class="icon-close"></i>
                  </button>   
            @endcan
            </div>
            <div id="add_payin2_cb"> </div>
          @else 
          <div class="fileinput fileinput-new input-group" data-provides="fileinput">
              <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename"></span>
              </div>
              <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                <input type="file" name="payin2_cb_file" id="payin2_cb_file" class="check_max_size_file">
               </span>
               <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
           </div>
          @endif
      </div>
</div>
 



        </div>
      </div>
  </div>
 </div>
<!-- end  หน่วยรับรอง (CB) -->

 </div>

          </div>
    </div>
</div>





<div class="form-group">
  <div class="col-md-offset-4 col-md-4">

    <button class="btn btn-primary" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
    @can('view-'.str_slug('feewaiver'))
    <a class="btn btn-default" href="{{url('/certify')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
                <!-- เริ่ม สรุปรายงาน -->
     <script type="text/javascript">
        jQuery(document).ready(function() {
             $("input[name=payin1_status]").on("change", function(event) {;
                status_show_payin1_status();
              });
              status_show_payin1_status();
            function status_show_payin1_status(){
                  var row = $("input[name=payin1_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin1_start_date').prop('required', true);
                      $('#payin1_end_date').prop('required', true);
                  } else{
                      $('#payin1_start_date').prop('required', false);
                      $('#payin1_end_date').prop('required', false);
                  }
              }
              $("input[name=payin2_status]").on("change", function(event) {;
                status_show_payin2_status();
              });
              status_show_payin2_status();
            function status_show_payin2_status(){
                  var row = $("input[name=payin2_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin2_start_date').prop('required', true);
                      $('#payin2_end_date').prop('required', true);
                  } else{
                      $('#payin2_start_date').prop('required', false);
                      $('#payin2_end_date').prop('required', false);
                  }
              }
              $("input[name=payin1_ib_status]").on("change", function(event) {;
                status_show_payin1_ib_status();
              });
              status_show_payin1_ib_status();
            function status_show_payin1_ib_status(){
                  var row = $("input[name=payin1_ib_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin1_ib_start_date').prop('required', true);
                      $('#payin1_ib_end_date').prop('required', true);
                  } else{
                      $('#payin1_ib_start_date').prop('required', false);
                      $('#payin1_ib_end_date').prop('required', false);
                  }
              }
              $("input[name=payin2_ib_status]").on("change", function(event) {;
                status_show_payin2_ib_status();
              });
              status_show_payin2_ib_status();
            function status_show_payin2_ib_status(){
                  var row = $("input[name=payin2_ib_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin2_ib_start_date').prop('required', true);
                      $('#payin2_ib_end_date').prop('required', true);
                  } else{
                      $('#payin2_ib_start_date').prop('required', false);
                      $('#payin2_ib_end_date').prop('required', false);
                  }
              }

              $("input[name=payin1_c_status]").on("change", function(event) {;
                status_show_payin1_cb_status();
              });
              status_show_payin1_cb_status();
            function status_show_payin1_cb_status(){
                  var row = $("input[name=payin1_cb_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin1_cb_start_date').prop('required', true);
                      $('#payin1_cb_end_date').prop('required', true);
                  } else{
                      $('#payin1_cb_start_date').prop('required', false);
                      $('#payin1_cb_end_date').prop('required', false);
                  }
              }
              $("input[name=payin2_cb_status]").on("change", function(event) {;
                status_show_payin2_cb_status();
              });
              status_show_payin2_cb_status();
            function status_show_payin2_cb_status(){
                  var row = $("input[name=payin2_cb_status]:checked").val();
                  if(row == "1"){ 
                      $('#payin2_cb_start_date').prop('required', true);
                      $('#payin2_cb_end_date').prop('required', true);
                  } else{
                      $('#payin2_cb_start_date').prop('required', false);
                      $('#payin2_cb_end_date').prop('required', false);
                  }
              }
         });
     </script>
     <!-- จบ สรุปรายงาน -->
  <script type="text/javascript">
      $(document).ready(function () {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
          $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
          });
            //ช่วงวันที่
            $('.date-range').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
            });

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
        
      });

      function  delete_payin_file(payin_id,certify){
            var html =[];
                    html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="payin'+payin_id+'_file" id="payin'+payin_id+'_file" class="check_max_size_file">';
                    html += '</span>';
                    html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                    html += '</div>';
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
                            url: "{!! url('/basic/feewaiver/remove_file') !!}"  + "/" + payin_id   + "/" + certify
                        }).done(function( object ) {
                            if(object == 'true'){
                              $('#delete_payin'+payin_id).remove();
                               $("#add_payin"+payin_id).append(html);
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
                check_max_size_file();
         }

</script>
@endpush
