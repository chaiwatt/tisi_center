@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
<style type="text/css">
  .img{
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
  }
</style>
@endpush

<div class="form-group required {{ $errors->has('save_date') ? 'has-error' : ''}}">
    {!! Form::label('save_date', 'วันที่บันทึก :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4"> 
        {!! Form::text('save_date', !empty($import_comment->save_date) ?  $import_comment->save_date : HP::revertDate(date('Y-m-d'),true) , ['class' => 'form-control  ','disabled' => true, 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'autocomplete'=>'off']) !!}
        {{-- <input type="text" id="save_date" name="save_date" class="form-control datepicker" data-provide="datepicker" data-date-language="th-th" required> --}}
        {!! $errors->first('save_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!-- แนบไฟล์ -->
<div class="form-group required{{ $errors->has('attach_excel') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('attach_excel', 'ไฟล์ :', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-8">
    @if(@$attach_excel->file_name!='' && HP::checkFileStorage($attach_path.@$attach_excel->file_name))
          <a href="{{ HP::getFileStorage($attach_path.$attach_excel->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm" style="width: auto"><i class="fa fa-search"></i> {{ $attach_excel->file_client_name }}</a>
    @endif
    <div class="col-md-6 file-attach">
        <div class="fileinput fileinput-new input-group " data-provides="fileinput" >
            <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                <span class="fileinput-filename">{{ !empty(@$attach_excel->file_client_name)?$attach_excel->file_client_name:"" }}</span>
            </div>
            <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">เลือกไฟล์</span>
                <span class="fileinput-exists">เปลี่ยน</span>
                {!! Form::file('attach_excel', ['accept' => '.xlsx','required' =>  (@$attach_excel->file_name =='' ? true : false),'id'=>'attach_excel']) !!}
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
    </div>
    <button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i>  </button>

    <small class="text-muted" style="color: red">
      (เฉพาะไฟล์ .xlsx เท่านั้น)
    </small>
    </div>
 
</div>

<!-- รายละเอียด -->
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'รายละเอียด :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('description', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'rows'=>4]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
        {!! Form::label('description', 'ผู้บันทึก :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-sm-4">
            <input class="form-control" name="user_create" value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}" disabled="true">
    </div>
</div>

@if($attach_excel && isset($import_comment) && $import_comment->status!=2)
  <div id="show-upload">
      <div class="form-group">
          <div class="col-md-offset-4 col-md-4">
              <button id="btn_update" type="button" class="btn btn-info btn-outline"
                      data-loading-text="<img src='{{ asset('/images/1488.gif') }}' width='32px' /> กำลังนำเข้าข้อมูล...">
                <span><i class="fa fa-upload"></i> ดำเนินการนำเข้า</span>
              </button>
          </div>
      </div>
  </div>
@endif

<div class="row" id="show_data" @if(!isset($import_comment->id)) style="display:none" @endif>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table" id="upload-table">
                <thead>
                    <tr>
                        <th width="1%" class="text-center">ลำดับ</th>
                        <th width="9%" class="text-center">วันที่</th>
                        <th width="20%" class="text-center">ผู้ให้ข้อคิดเห็น</th>
                        <th width="20%" class="text-center">หน่วยงาน</th>
                        <th width="10%" class="text-center">ชื่อมาตรฐาน</th>
                        <th width="10%" class="text-center">เลข มอก.</th>
                        <th width="10%" class="text-center">สาขา</th>
                        <th width="10%" class="text-center">เบอร์โทร</th>
                        <th width="10%" class="text-center">E-mail</th>
                        <th width="10%" class="text-center">สถานะ</th>
                        <th width="10%" class="text-center">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div> 								 
</div>

<div class="row" id="show_error0">
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <div class="alert alert-danger error_header"></div>
  </div>
  <div class="col-md-2"></div>
</div>

<div class="row" id="show_error1">

  <div class="col-md-12">

      <div class="row">
        <div class="col-md-4">    <h3 class="error_header text-danger"> </h3></div>
        <div class="col-md-8">        
            <button id="btn_insert_data" type="button" class="btn btn-success btn-outline"
                    data-loading-text="<img src='{{ asset('/images/1488.gif') }}' width='32px' /> กำลังนำเข้าข้อมูล...">
                <span><i class="fa fa-upload"></i>ต้องการนำเข้า</span>
           </button>
        </div>
      </div>


      <table class="table color-bordered-table info-bordered-table" id="error1-table">
        <thead>
            <tr>
                <th width="1%" class="text-center">ลำดับ</th>
                <th width="10%" class="text-center">เลข มอก.</th> 
                <th width="10%" class="text-center">ปี มอก.</th>
                <th width="10%" class="text-center">เล่มที่ มอก.</th>
                <th width="10%" class="text-center">ผู้ให้ข้อคิดเห็น</th>
                <th width="10%" class="text-center">หน่วยงาน</th>
                <th width="10%" class="text-center">เบอร์โทร</th>
                <th width="10%" class="text-center">E-mail</th>
                <th width="9%" class="text-center">วันที่</th>
                <th width="10%" class="text-center">สถานะ</th>
                <th width="25%" class="text-center">หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

  </div>

</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <button class="btn btn-primary" type="submit" id="form-save" >
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('import_comment'))
            <a class="btn btn-default" href="{{url('/tis/import_comment')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            //ลบไฟล์แนบ
             $('body').on('click', '.attach-remove', function(event) {
                $('.view-attach').hide() ; 
                $('.file-attach').show() ;
                $('.fileinput-filename').html('') ;
                $('#attach_excel').val('') ;
                $('#attach_excel').prop('required',true) ;
                $('.attach-remove').hide() ;
            });

            $('#show_data, #show_error0, #show_error1,.file-attach,.attach-remove').hide();

            @if ($message = Session::get('upload_message'))
            Swal.fire({
                // position: 'top-end',
                icon: 'success',
                title: 'นำเข้าข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 3000
            })
            @endif

            @if ($message = Session::get('error_message'))
            Swal.fire({
                // position: 'top-end',
                icon: 'error',
                title: 'นำเข้าข้อมูลไม่สำเร็จ',
                showConfirmButton: false,
                timer: 3000
            })
            @endif

            @if ($message = Session::get('column_message'))
            Swal.fire({
                icon: 'error',
                title: 'หัวตารางไม่ถูกต้อง',
                text: 'กรุณาเช็คหัวตารางก่อน !',
            })
            @endif
            // Switchery
            $("#state").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            //ปฎิทินไทย
            $('.datepicker').datepicker();

            $('#btn_update').click(function(){

                $(this).button('loading');//ปิดปุ่ม

                $.ajax({
                    url: "{{ url('tis/import_comment/upload/'.$import_comment->id) }}"
                }).done(function( object ) {

                    $('#btn_update').button('reset');
                    $('#btn_update').prop('disabled', true);
                    $('#btn_update').hide();

                    ShowResultImport(object);
                });
            });

            $('#btn_insert_data').click(function(){
                $(this).button('loading');//ปิดปุ่ม
                $.ajax({
                    url: "{{ url('tis/import_comment/insert_data/'.$import_comment->id) }}"
                }).done(function( object ) {
                    $('#btn_insert_data').button('reset');
                    $('#btn_insert_data').prop('disabled', true);
                    $('#btn_insert_data').hide();
                    ShowResultImport(object);
                });
            });
            
            //ถ้านำเข้าแล้ว
            @if(isset($import_comment) && $import_comment->status==2)
                  $('#form-save').prop('disabled',true) ;
                $.ajax({
                    url: "{{ url('tis/import_comment/result-import/'.$import_comment->id) }}"
                }).done(function( object ) {
                  
                    $('#btn_update').button('reset');
                    $('#btn_update').prop('disabled', true);
                    $('#btn_update').hide();
                     ShowResultImport(object);
                });
            @endif
            @if(isset($attach_excel->file_name) && $attach_excel->file_name!= '')
               $('.file-attach').hide() ;
               $('#attach_excel').prop('required',false) ; 
            @else 
            $('.file-attach').show() ;
               $('#attach_excel').prop('required',true) ;
            @endif

        });

        //แสดงข้อมูลจาก ajax
        function ShowResultImport(object){
               
            if(object.message == "check"){// Error ไฟล์หรือ sheet
              $('#show_error0').find('.error_header').text("ข้อมูลที่ไม่มีตามมาตรฐา");
              $('#show_error0').show(500);

            }else if(object.message == "false"){ // Error ที่หัวตาราง
                $('#show_error1').find('.error_header').text("ข้อมูลที่ไม่มีตามมาตรฐาน (มอก.)");
                var i = 0;
                $.each(object.data, function(index, detail) {
                    i++;
                    var _tr = '<tr>';
                        _tr+= '  <td class="text-center">'+(i)+'</td>'  
                        _tr+= '  <td>'+detail.tis_no +'</td>' //เลข มอก.
                        _tr+= '  <td>'+detail.tis_year +'</td>' //ปี มอก. 
                        _tr+= '  <td>'+detail.tis_book+'</td>' //เล่มที่ มอก.
                        _tr+= '  <td>'+detail.name+'</td>' //ผู้ให้ข้อคิดเห็น
                        _tr+= '  <td>'+detail.department+'</td>' //หน่วยงาน
                        _tr+= '  <td>'+detail.tel+'</td>' //เบอร์โทร 
                        _tr+= '  <td>'+detail.email+'</td>' //	E-mail 
                        _tr+= '  <td>'+detail.created_at+'</td>' //วันที่
                        _tr+= '  <td>'+detail.status+'</td>' //	สถานะ
                        _tr+= '  <td>'+detail.error+'</td>' // หมายเหตุ
                        _tr+= '</tr>'
                    $('#error1-table tbody').append(_tr);
                });
                $('#show_error1').show(500);
                $('.attach-remove').show();
                
            }else{
                    $('#upload-table tbody').html(" ");
                    $('#show_error1').hide(); 
                    $('.attach-remove').hide();
                    $('#show_data').show(500);
                    $('#form-save').prop('disabled',true) ;
                    $.each(object.data, function( index, data ) {
                        console.log(data);
                        var _tr = '<tr>';
                            _tr+= '  <td class="text-center">'+(index+1)+'</td>' //ลำดับ
                            _tr+= '  <td>'+data.created_at+'</td>' //วันที่
                            _tr+= '  <td>'+data.name+'</td>' //ผู้ให้ข้อคิดเห็น
                            _tr+= '  <td>'+data.department +'</td>' //หน่วยงาน
                            _tr+= '  <td>'+data.title+'</td>' //ชื่อมาตรฐาน
                            _tr+= '  <td>'+data.tis_no+'</td>' //เลข มอก.
                            _tr+= '  <td>'+data.get_stand+'</td>' //สาขา 
                            _tr+= '  <td>'+data.tel+'</td>' //เบอร์โทร
                            _tr+= '  <td>'+data.email+'</td>' //	E-mail
                            _tr+= '  <td>'+data.status+'</td>' //	สถานะ
                             _tr+= '  <td>'+data.error+'</td>' // หมายเหตุ
                            _tr+= '</tr>'
                        $('#upload-table tbody').append(_tr);

                    });
            }
            						
        }
    </script>
@endpush
