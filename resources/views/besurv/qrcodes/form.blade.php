@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush


<div class="row">
  <div class="col-xs-12">
       <div class="tab" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                  <li class="tab active">
                      <a data-toggle="tab" href="#tab_20ter" aria-expanded="true">
                        <span><i class='fa fa-book'></i></span>
                        ระบบมาตรา 20 ตรี
                     </a>
                  </li>
                  <li class="tab  ">
                    <a data-toggle="tab" href="#tab_21ter" aria-expanded="false">
                        <span><i class='fa fa-book'></i></span>
                        ระบบมาตรา 21 ตรี
                    </a>
                  </li>
              </ul>
    <div class="tab-content">
  <!-- start 20ter -->
<div role="tab_lab" class="tab-pane fade in active" id="tab_20ter">
<div class="white-box">
  <div class="row">
         <div class="col-sm-12">
  <legend><h3 class="box-title"> ระบบมาตรา 20 ตรี</h3></legend>
<div class="form-group {{ $errors->has('qrcode20_state') ? 'has-error' : ''}}">
    {!! Form::label('qrcode20_state', 'เปิดใช้งาน QR CODE', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div clas="checkbox">
        {!! Form::checkbox('qrcode20_state', '1', !empty($qrcode20->qrcode_state) && $qrcode20->qrcode_state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('qrcode20_state', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>

<div class="form-group required qrcode20_state{{ $errors->has('qrcode20_link') ? 'has-error' : ''}}">
    {!! Form::label('qrcode20_link', 'ใส่ link ที่ต้องการให้แสดงบน QR Code', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('qrcode20_link', !empty($qrcode20->qrcode_link)   ?  $qrcode20->qrcode_link : null  , ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('qrcode20_link', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group qrcode20_state{{ $errors->has('qrcode20_announce') ? 'has-error' : ''}}">
    {!! Form::label('qrcode20_announce', 'ข้อความที่ต้องการให้แสดงใต้ QR Code', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('qrcode20_announce', !empty($qrcode20->qrcode_announce)   ?  $qrcode20->qrcode_announce : null , ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('qrcode20_announce', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('index20_state') ? 'has-error' : ''}}">
    {!! Form::label('index20_state', 'เปิดใช้งานประกาศฝั่งผู้ประกอบการ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('index20_state', '1', !empty($qrcode20->index_state) && $qrcode20->index_state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('index20_state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group index20_state{{ $errors->has('index20_link') ? 'has-error' : ''}}">
    {!! Form::label('index20_link', 'ใส่ link ที่ต้องการให้คลิก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('index20_link', !empty($qrcode20->index_link)   ?  $qrcode20->index_link : null , ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('index20_link', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group required index20_state{{ $errors->has('index20_announce') ? 'has-error' : ''}}">
    {!! Form::label('index20_announce', 'ข้อความที่ต้องการแสดง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('index20_announce', !empty($qrcode20->index_announce)   ?  $qrcode20->index_announce : null , ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('index20_announce', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('attach20_state') ? 'has-error' : ''}}">
    {!! Form::label('attach20_state', 'ไฟส์แนบท้าย', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('attach20_state', '1', !empty($qrcode20->attach_state) && $qrcode20->attach_state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('attach20_state', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('attach20') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @if(!empty($qrcode20->attach) && HP::checkFileStorage($qrcode20->attach)) 
        <div id="delete_attach20" class="attach20_state">
              <a href="{{url('funtions/get-view-file/'.base64_encode($qrcode20->attach).'/'.( !empty($qrcode20->file_client_name) ? $qrcode20->file_client_name :  basename($qrcode20->attach)  ))}}" target="_blank">
              {!! HP::FileExtension($qrcode20->attach)  ?? '' !!}
              </a>
              @can('delete-'.str_slug('qrcodes'))
              <button class="btn btn-danger btn-xs   " type="button"  onclick="delete_file('20')" >
                    <i class="icon-close"></i>
              </button>   
              @endcan
        </div>
        <div id="add_attach20"> </div>
      @else 
            <div class="attach20_state fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">เลือกไฟล์</span>
                    <span class="fileinput-exists">เปลี่ยน</span>
                    <input type="file" name="attach20"   id="attach20" accept=".pdf" class="check_max_size_file">
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
<!-- END  20ter -->

<!-- start  21ter -->
<div id="tab_21ter" class="tab-pane">
 <div class="white-box">

    <div class="form-group {{ $errors->has('qrcode21_state') ? 'has-error' : ''}}">
    {!! Form::label('qrcode21_state', 'เปิดใช้งาน QR CODE', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('qrcode21_state', '1',  !empty($qrcode21->qrcode_state) && $qrcode21->qrcode_state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('qrcode21_state', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group qrcode21_state{{ $errors->has('qrcode21_link') ? 'has-error' : ''}}">
    {!! Form::label('qrcode21_link', 'ใส่ link ที่ต้องการให้แสดงบน QR Code', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('qrcode21_link', !empty($qrcode21->qrcode_link)   ?  $qrcode21->qrcode_link : null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('qrcode21_link', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group qrcode21_state{{ $errors->has('qrcode21_announce') ? 'has-error' : ''}}">
    {!! Form::label('qrcode21_announce', 'ข้อความที่ต้องการให้แสดงใต้ QR Code', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('qrcode21_announce', !empty($qrcode21->qrcode_announce)   ?  $qrcode21->qrcode_announce : null , ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('qrcode21_announce', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('index21_state') ? 'has-error' : ''}}">
    {!! Form::label('index21_state', 'เปิดใช้งานประกาศฝั่งผู้ประกอบการ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('index21_state', '1', !empty($qrcode21->index_state) && $qrcode21->index_state == '1' ?  true : false   , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('index21_state', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group index21_state{{ $errors->has('index21_link') ? 'has-error' : ''}}">
    {!! Form::label('index21_link', 'ใส่ link ที่ต้องการให้คลิก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('index21_link',  !empty($qrcode21->index_link)   ?  $qrcode21->index_link : null , ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('index21_link', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group index21_state{{ $errors->has('index21_announce') ? 'has-error' : ''}}">
    {!! Form::label('index21_announce', 'ข้อความที่ต้องการแสดง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('index21_announce', !empty($qrcode21->index_announce)   ?  $qrcode21->index_announce : null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('index21_announce', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group {{ $errors->has('attach21_state') ? 'has-error' : ''}}">
    {!! Form::label('attach21_state', 'ไฟส์แนบท้าย', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('attach21_state', '1', !empty($qrcode21->attach_state) && $qrcode21->attach_state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#13dafe']) !!}
        {!! $errors->first('attach21_state', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('attach21') ? 'has-error' : ''}}">
    {!! Form::label('', '', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        @if(!empty($qrcode21->attach) && HP::checkFileStorage($qrcode21->attach)) 
        <div id="delete_attach21"  class="attach21_state">
              <a href="{{url('funtions/get-view-file/'.base64_encode($qrcode21->attach).'/'.( !empty($qrcode21->file_client_name) ? $qrcode21->file_client_name :  basename($qrcode21->attach)  ))}}" target="_blank">
              {!! HP::FileExtension($qrcode21->attach)  ?? '' !!}
              </a>
              @can('delete-'.str_slug('qrcodes'))
              <button class="btn btn-danger btn-xs   " type="button"  onclick="delete_file('21')" >
                    <i class="icon-close"></i>
              </button>   
              @endcan
        </div>
        <div id="add_attach21"> </div>
      @else 
            <div class="attach21_state fileinput fileinput-new input-group" data-provides="fileinput">
                <div class="form-control" data-trigger="fileinput">
                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                    <span class="fileinput-filename"></span>
                </div>
                <span class="input-group-addon btn btn-default btn-file">
                    <span class="fileinput-new">เลือกไฟล์</span>
                    <span class="fileinput-exists">เปลี่ยน</span>
                    <input type="file" name="attach21"   id="attach21" accept=".pdf"  class="check_max_size_file">
                </span>
                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
            </div>
        @endif
    </div>
</div>



  </div>
 </div>
<!-- END  21ter -->

{{-- <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'State', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
<label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

    <div class="form-group">
        <div class="col-md-offset-4 col-md-4">

            <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
            </button>
            @can('view-'.str_slug('qrcodes'))
                <a class="btn btn-default" href="{{url('/besurv/qrcodes')}}">
                    <i class="fa fa-rotate-left"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>


        </div>
    </div>
</div>
@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

  <script type="text/javascript">
        $(document).ready(function () {

            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                     $('.js-switch').each(function() {
                            new Switchery($(this)[0], $(this).data());
                    });

            $("input[name=qrcode20_state]").on("change", function(event) {;
                     qrcode20_state();
              });
              qrcode20_state();
               function qrcode20_state(){
                  var row = $("input[name=qrcode20_state]:checked").val();
                  if(row == "1"){ 
                      $('.qrcode20_state').show();
                      $('#qrcode20_link').prop('required', true);
                      $('#qrcode20_announce').prop('required', true);
                  } else{
                      $('.qrcode20_state').hide();
                      $('#qrcode20_link').prop('required', false);
                      $('#qrcode20_announce').prop('required', false);
                  }
              }

              $("input[name=index20_state]").on("change", function(event) {;
                index20_state();
              });
              index20_state();
               function index20_state(){
                  var row = $("input[name=index20_state]:checked").val();
                  if(row == "1"){ 
                      $('.index20_state').show();
                      $('#index20_announce').prop('required', true);
                      $('#index20_link').prop('required', true);
                  } else{
                      $('.index20_state').hide();
                      $('#index20_announce').prop('required', false);
                      $('#index20_link').prop('required', false);
                  }
              }

              $("input[name=attach20_state]").on("change", function(event) {;
                attach20_state();
              });
                 attach20_state();
               function attach20_state(){
                  var row = $("input[name=attach20_state]:checked").val();
                  if(row == "1"){ 
                      $('.attach20_state').show();
                      $('#attach20').prop('required', true);
                  } else{
                      $('.attach20_state').hide();
                      $('#attach20').prop('required', false);
                  }
              }



              
              $("input[name=qrcode21_state]").on("change", function(event) {;
                qrcode21_state();
              });
              qrcode21_state();
               function qrcode21_state(){
                  var row = $("input[name=qrcode21_state]:checked").val();
                  if(row == "1"){ 
                      $('.qrcode21_state').show();
                      $('#qrcode21_link').prop('required', true);
                      $('#qrcode21_announce').prop('required', true);
                  } else{
                      $('.qrcode21_state').hide();
                      $('#qrcode21_link').prop('required', false);
                      $('#qrcode21_announce').prop('required', false);
                  }
              }


              $("input[name=index21_state]").on("change", function(event) {;
                index21_state();
              });
              index21_state();
               function index21_state(){
                  var row = $("input[name=index21_state]:checked").val();
                  if(row == "1"){ 
                      $('.index21_state').show();
                      $('#index21_announce').prop('required', true);
                      $('#index21_link').prop('required', true);
                  } else{
                     $('.index21_state').hide();
                      $('#index21_announce').prop('required', false);
                      $('#index21_link').prop('required', false);
                  }
              }

              $("input[name=attach21_state]").on("change", function(event) {;
                attach21_state();
              });
              attach21_state();
               function attach21_state(){
                  var row = $("input[name=attach21_state]:checked").val();
                  if(row == "1"){ 
                      $('.attach21_state').show();
                      $('#attach21').prop('required', true);
                  } else{
                      $('.attach21_state').hide();
                      $('#attach21').prop('required', false);
                  }
              }

              check_max_size();
           });

           function  delete_file(type){
            var html =[];
                    html += '<div class="attach'+type+'_state fileinput fileinput-new input-group" data-provides="fileinput" >';
                    html += '<div class="form-control" data-trigger="fileinput">';
                    html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                    html += '<span class="fileinput-filename"></span>';
                    html += '</div>';
                    html += '<span class="input-group-addon btn btn-default btn-file">';
                    html += '<span class="fileinput-new">เลือกไฟล์</span>';
                    html += '<span class="fileinput-exists">เปลี่ยน</span>';
                    html += '<input type="file" name="attach'+type+'" id="attach'+type+'" accept=".pdf"   required class="check_max_size_file">';
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
                            url: "{!! url('/besurv/qrcodes/remove_file') !!}"    + "/" + type
                        }).done(function( object ) {
                            if(object == 'true'){
                              $('#delete_attach'+type).remove();
                               $("#add_attach"+type).append(html);
                            }else{
                                Swal.fire('ข้อมูลผิดพลาด');
                            }
                        });

                    }
                })
                check_max_size();
         }
  function check_max_size() {
     
        $('.check_max_size_file').bind('change', function() {
            if( $(this).val() != ''){
            var size =   this.files[0].size  
 
            let fileSizeKB = roundOff(size*0.001);
            
              if(fileSizeKB > 1024  ){
                Swal.fire(
                        'ขนาดไฟล์เกินกว่า 1024  KB',
                        '',
                        'info'
                        )
                //  this.value = '';
                $(this).parent().parent().find('.fileinput-exists').click(); 
                $(this).val('');
                $(this).parent().parent().find('.custom-file-label').html('');
                  return false;
              } 
            }
        });
   } 

   function roundOff(value) {
        return Math.round(value*100)/100;
    }


           </script>
@endpush
