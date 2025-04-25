@push('css')
    <link href="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<div class="form-group required {{ $errors->has('survey_year') ? 'has-error' : ''}}">
    {!! Form::label('survey_year', 'ปีที่หยุด พ.ศ.', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('survey_year',  HP::fiveYearFWList(), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'disabled' => (isset($import_holiday->id)?true:false  ) , 'placeholder'=>'- เลือกปี -'] : ['class' => 'form-control']) !!}
        {!! $errors->first('survey_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{{-- <div class="form-group required{{ $errors->has('AMPHUR_CODE') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_CODE', 'วันที่เริ่มยกเว้นค่าธรรมเนียม'.' :', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
      <div class="input-daterange input-group">
            <div class="input-group">
                  {!! Form::text('payin1_start_date',  
                   !empty($feewaiver->payin1_start_date) ?  HP::revertDate($feewaiver->payin1_start_date,true)  : null ,
                   ['class' => 'form-control mydatepicker','placeholder'=>"mm/dd/yyyy", 'id' => 'payin1_start_date']) !!}
                   <span class="input-group-addon"><i class="icon-calender"></i></span> 
             </div>
     </div>
  </div>
</div> --}}
<div class="form-group {{ $errors->has('payin1_file') ? 'has-error' : ''}}">
  {!! Form::label('payin1_file', 'ไฟล์นำเข้า Excel'.' :', ['class' => 'col-md-4 control-label']) !!}
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
              <input type="file" name="attach_file" id="attach_file" class="check_max_size_file">
              </span>
              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
        </div>
      @endif
  </div>
</div>

<div class="form-group {{ $errors->has('remarks') ? 'has-error' : ''}}">
    {!! Form::label('remarks', 'หมายเหตุ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('remarks', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'rows' => 3]) !!}
        {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

	<div class="span5 form-horizontal">

				<div class="clearfix">

					<div class="c100 p0 big hide" id="progress">
						<span>0%</span>
						<div class="slice">
							<div class="bar"></div>
							<div class="fill"></div>
						</div>
					</div>

					<h3 class="span12 hide" id="text-progress" style="margin:0px;">
						<span>
							<span class="span3">นำเข้าแล้ว</span>
							<span class="span2" id="imported"></span>
							<span class="span3">รายการ</span>
						</span>
						<br clear="all"/>
						<span>
							<span class="span3">ผิดพลาด</span>
							<span class="span2" id="fault"></span>
							<span class="span3">รายการ</span>
						</span>
						<br clear="all"/>
						<span>
							<span class="span3">ทั้งหมด</span>
							<span class="span2" id="all"></span>
							<span class="span3">รายการ</span>
						</span>
						<br clear="all"/>
						<span id="remark-progress" class="alert-danger"></span>
					</h3>

					<h2 class="span12 hide" id="text-prepare" style="margin:0px;">
						กำลังเตรียมนำเข้าข้อมูล...
					</h2>


				</div>

			</div>

			<div class="span12 form-horizontal" id="display-error">

			</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        <button class="btn btn-info" type="button" id="import_data">
            <i class="fa fa-upload"></i> นำเข้าข้อมูล
        </button>
        @can('view-'.str_slug('configs-format-code'))
            <a class="btn btn-default show_tag_a" href="{{url('/config/import-holiday')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>

    <script>
        jQuery(document).ready(function() { 

            @if(\Session::has('error_message'))
                Swal.fire({
                    type: 'error',
                    title: 'บันทึกไม่สำเร็จ',
                    text: 'ระบบงานที่บันทึกยังเปิดใช้งานไม่สามารถบันทึกซ้ำได้',
                    confirmButtonText:'รับทราบ'
                });
            @endif

            //ปฏิทิน
            $('.mydatepicker').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
            });

            $('#import_data').click(function(){

                alert('tesst');

            });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        
	var Interval = '';
	var Prepare = '';
	function ProcessImport(task){

		//Clear alert box
		$('#system-message-container, #display-error').html('');

		$.ajax({
  		url: "index.php?option=com_rbasicdata&task="+task,
  		data: {
    		id: <?php echo (int)$id; ?>
  		},
  		success: function( result ) {

				var css_class = '';
				var message = '';
    		if(result=='Success'){//สำเร็จ
					css_class = 'alert-success';
					message = 'นำเข้าข้อมูลปฏิทินวันหยุดเรียบร้อยแล้ว';
				}else{//ไม่สำเร็จ
					css_class = 'alert-danger';
					message = result;
					input.attr('disabled', false);
				
				}

				sendRequest('id=<?php echo (int)$id; ?>',
										'index.php?option=com_rbasicdata&view=holidayimport&layout=edit_error',
										'POST', '#display-error'
									 );//function.js

				var box_msg = '';
						box_msg =  '<div class="alert '+css_class+'">';
						box_msg += 		'<button type="button" class="close" data-dismiss="alert">×</button>';
						box_msg +=  	'<h4 class="alert-heading">ข้อความ</h4>';
						box_msg +=  	'<div class="alert-message">'+message+'</div>';
						box_msg += '</div>';

				$('#system-message-container').html(box_msg);

  		}
		});

		//แสดง progress circle
		$('#progress, #text-prepare').show();
		$('#text-progress').hide();
		$('#remark-progress').text('');

		$("#progress").removeClass (function (index, className) {
    	return (className.match (/(^|\s)p\S+/g) || []).join(' ');
		});

		Prepare = setInterval(effect_text_prepare, 1000);//Run....
		Interval = setInterval(getPercent, 2000);//get Percent

	}

    function sendRequest(data, url, method, recieve_element, attr, func){ //require JQuery Library
        $.ajax({
                type: method,
                url: url,
                data: data,
                success: function (msg) {
                    if(attr){
                        $(recieve_element).attr(attr, msg);
                    }else{
                        $(recieve_element).html(msg);
                    }
                    if(func){setTimeout(func, 500); }
                }, error: function(){
                    alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
                }
        });
    }

	function getPercent(){

		$.ajax({
  		url: "index.php?option=com_rbasicdata&task=holidayimport.DataImport",
  		data: {
    		id: <?php echo (int)$id; ?>
  		},
  		success: function( result ) {

				$('#text-prepare').hide();
				clearInterval(Prepare);
				$('#text-progress').show();

				var percent = Math.ceil(((result.imported+result.error)/result.all)*100);

				$("#progress").removeClass (function (index, className) {
		    	return (className.match (/(^|\s)p\S+/g) || []).join(' ');
				});
				$('#progress').addClass('p'+percent);
				$('#progress').find('span').text(percent+'%');

				$('#all').text(result.all);
				$('#imported').text(result.imported);
				$('#fault').text(result.error);

				if(result.status!=1){
					clearInterval(Interval);
					if(result.status==0){
						$('#imported').text(result.imported);
						$('#remark-progress').text('(ระบบได้ยกเลิกข้อมูลที่นำเข้าแล้ว)');
					}else{
						$('#remark-progress').text('');
					}
				}

			}
		});
	}

	var dot = 0;
	function effect_text_prepare(){

		dot++;
		$('#text-prepare').text('กำลังเตรียมนำเข้าข้อมูล'+'.'.repeat(dot));
		if(dot===8){
			dot=0;
		}

	}

    </script>
@endpush