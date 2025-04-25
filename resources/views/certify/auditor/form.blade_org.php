@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-12">
        <div class="col-md-10">
   
            <div class="form-group {{ $errors->has('app_certi_lab_id') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('app_certi_lab_id', '<span class="text-danger">*</span>  เลขคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::select('app_certi_lab_id', 
                        $app_certi_lab,
                         $app_certi_lab_id  ?? null,
                     ['class' => 'form-control',
                      'id' => 'app_certi_lab_id',
                      'placeholder'=>'- เลขคำขอ -',
                      'required' => true]); !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('no', '<span class="text-danger">*</span>  ชื่อผู้ยื่นคำขอ', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('no', null, ['class' => 'form-control', 'maxlength' => '255', 'required' => true]); !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('auditor') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('auditor', '<span class="text-danger">*</span>   ชื่อคณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! Form::text('auditor', null, ['class' => 'form-control',  'maxlength' => '255', 'required' => true]); !!}
                </div>
            </div>
            <div class="form-group dev_form_date {{ $errors->has('judgement_date') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('judgement_date', '<span class="text-danger">*</span>  วันที่ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-6">
                       <div class="input-daterange input-group date-range">
                        {!! Form::text('start_date[]', null, ['class' => 'form-control date', 'required' => true]) !!}
                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                        {!! Form::text('end_date[]', null, ['class' => 'form-control date', 'required' => true]) !!}
                      </div>
                </div>
                <div class="col-md-1">
                      <button type="button" class="btn btn-success btn-sm pull-right add_date" id="add_date">
                        <i class="icon-plus" aria-hidden="true"></i>
                        เพิ่ม
                    </button>
                    <div class="add_button_delete"></div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('other_attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('other_attach', '<span class="text-danger">*</span> บันทึก ลมอ. แต่งตั้งคณะผู้ตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! $errors->first('other_attach', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="other_attach" required class="check_max_size_file">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
            <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                {!! HTML::decode(Form::label('attach', '<span class="text-danger">*</span> กำหนดการตรวจประเมิน', ['class' => 'col-md-5 control-label'])) !!}
                <div class="col-md-7">
                    {!! $errors->first('attach', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="attach" required class="check_max_size_file">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 repeater">
    <button type="button" class="btn btn-success btn-sm pull-right clearfix" id="plus-row">
        <i class="icon-plus" aria-hidden="true"></i>
        เพิ่ม
    </button>
    <div class="clearfix"></div>
    <br/>

    <table class="table color-bordered-table primary-bordered-table">
        <thead>
        <tr>
            {{-- <th class="text-center">ลำดับ</th>  --}}
            <th class="text-center">สถานะผู้ตรวจประเมิน</th>
            <th class="text-center">ชื่อผู้ตรวจประเมิน</th>
            <th class="text-center"></th>
            <th class="text-center">หน่วยงาน</th>
            <th class="text-center"> ลบรายการ</th>
        </tr>
        </thead>
        <tbody id="table-body">
        <tr class="repeater-item">
            <td class="text-center text-top">
                <div class="form-group {{ $errors->has('taxid') ? 'has-error' : ''}}">
                    <div class="col-md-9">
                        {!! Form::select('status', $status_auditor,
                          null, ['class' => 'form-control item status', 'placeholder'=>'-เลือกสถานะผู้ตรวจประเมิน-', 'data-name'=>'status', 'required'=>true]); !!}
                    </div>
                </div>
            </td>
            {{-- จะแสดงข้อมูลชื่อผู้ทบทวนฯ จากการติ๊กเลือกใน popup  --}}
            <td class="align-right text-top td-users">
                {!! Form::text('filter_search', null, ['class' => 'form-control item', 'placeholder'=>'','data-name'=>'filter_search','required' => true]); !!}
            </td>
            {{-- จะแสดงข้อมูลใน popup ก็ต้องเมื่อเลือก "สถานะผู้ทบทวนผลการประเมิน" --}}
            <td class="text-top">
                <button type="button" class="btn btn-primary repeater-modal-open exampleModal" data-toggle="modal" data-target="#exampleModal"  disabled
                        data-whatever="@mdo"> select
                </button>
                <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
                <div class="modal fade repeater-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="exampleModalLabel1">ผู้ตรวจประเมิน</h4>
                            </div>
                            <div class="modal-body">
                                {{-- ------------------------------------------------------------------------------------------------- --}}
                                <div class="white-box">
                                    <div class="row">
                                        <div class="form-group {{ $errors->has('myInput') ? 'has-error' : ''}}">
                                            {!! HTML::decode(Form::label('myInput', 'ค้นหา', ['class' => 'col-md-2 control-label'])) !!}
                                            <div class="col-md-7">
                                                <input class="form-control myInput"  type="text" placeholder="ชื่อผู้ตรวจประเมิน,หน่วยงาน,ตำแหน่ง,สาขา">
                                            </div>
                                        </div>
      
                                        <div class="col-md-12 form-group ">
                                        <div class="table-responsive">
                                            <table class="table table-bordered color-table primary-table" id="myTable" width="100%">
                                                <thead>
                                                <tr>
                                                    <th  class="text-center" width="2%">#</th>
                                                    <th  class="text-center" width="2%">
                                                        <input type="checkbox" class="select-all">
                                                    </th>
                                                    <th class="text-center" width="10%">ชื่อผู้ตรวจประเมิน</th>
                                                    <th class="text-center" width="10%">หน่วยงาน</th>
                                                    <th class="text-center" width="10%">ตำแหน่ง</th>
                                                    <th class="text-center" width="10%">สาขา</th>
                                                </tr>
                                                </thead>
                                                <tbody class="tbody-auditor">

                                                </tbody>
                                            </table>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <div class="pull-right">
                                        {!! Form::button('<i class="icon-check"></i> เลือก', ['type' => 'button', 'class' => 'btn btn-primary btn-user-select']) !!}

                                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                            {!! __('ยกเลิก') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="align-top text-top td-departments">
                {!! Form::text('department', null, ('' == 'required') ? ['class' => 'form-control item', 'required' => 'required'] : ['class' => 'form-control item','readonly'=>'readonly','data-name'=>'department']) !!}
            </td>
            <td align="center" class="text-top">
                <button type="button" class="btn btn-danger btn-xs repeater-remove">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="row form-group" id="table_cost">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4>ประมาณค่าใช้จ่าย</h4></legend>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-8"> </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-success btn-sm" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
                        </div>
                        <div class="col-sm-12 m-t-15">
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="38%">รายละเอียด</th>
                                        <th class="text-center" width="20%">จำนวนเงิน</th>
                                        <th class="text-center" width="10%">จำนวนวัน</th>
                                        <th class="text-center" width="20%">รวม (บาท)</th>
                                        <th class="text-center" width="5%">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">

                                </tbody>
                                <footer>
                                    <tr>
                                        <td colspan="4" class="text-right">รวม</td>
                                        <td>
                                            {!! Form::text('costs_total',
                                                null,
                                                ['class' => 'form-control text-right costs_total',
                                                    'id'=>'costs_total',
                                                    'disabled'=>true
                                                ])
                                            !!}
                                        </td>
                                        <td>
                                             บาท
                                        </td>
                                    </tr>
                                </footer>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
        <label for="vehicle1">ขอความเห็นการแต่งตั้ง</label>
        <br>
        <input type="hidden" name="previousUrl" id="previousUrl" value="{{ app('url')->previous() }}">
        <button class="btn btn-primary" type="submit" id="form-save"  onclick="submit_form();return false;">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>

        <a class="btn btn-default" href="{{ app('url')->previous()  }}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>

    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script type="text/javascript">
        function  submit_form(){
            Swal.fire({
                title: 'ยืนยันการทำรายการ !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        $('#form_auditor').submit();
                    }
                })
        }
        var $uploadCrop;
        $(document).ready(function () {
                check_max_size_file();
                //Validate
                $('#form_auditor').parsley().on('field:validated', function() {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                        })
                        .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                                image       : "",
                                text        : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                        return true; // Don't submit form for this demo
                });

            //เพิ่มวันที่ตรวจประเมิน
            $("#add_date").click(function() {
                $('.dev_form_date:first').clone().insertAfter(".dev_form_date:last");
                var row = $(".dev_form_date:last");
                $('.dev_form_date:last > label').text(''); 
                row.find('input.date').val('');
                row.find('button.add_date').remove();
                row.find('div.add_button_delete').html('<button type="button" class="btn btn-danger btn-sm pull-right date_remove"><i class="fa fa-close" aria-hidden="true"></i> ลบ </button>');
               //ช่วงวันที่
                $('.date-range').datepicker({
                toggleActive: true, 
                language:'th-th',
                format: 'dd/mm/yyyy',
                });
            });
            //ช่วงวันที่
           $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
            //ลบตำแหน่ง
            $('body').on('click', '.date_remove', function() {
                    $(this).parent().parent().parent().remove();
            });

         
            $('#table_cost').hide();
            $('#app_certi_lab_id').change(function () {
                  let html = [];
                  $('#table_body').children().remove();
                    if($(this).val() != ''){
                        $('#table_cost').show();
                        $.ajax({
                           url: "{!! url('certify/auditor/certi_no') !!}" + "/" +  $(this).val()
                       }).done(function( object ) { 
                           $('#no').val(object.name);
                           $('#app_id').val(object.id);
                          


                           if(object.cost_item  != '-'){
                              $.each(object.cost_item, function( index, item ) {
                                html += '<tr>';
                                html += '<td>';
                                    html +=  (index +1);
                                html += '</td>';
                                html += '<td>';
                                    // html +=  item.desc ; 
                                    html +=  ' <select name="detail[desc][]" class="form-control select2 desc">' ; 
                                        html+=  '<option value="">- เลือกรายละเอียดประมาณค่าใช้จ่าย -</option>';
                                        $.each(object.cost_details, function( index1, item1 ) {
                                        var selected = (index1 == item.desc )?'selected="selected"':'';
                                         html+=  '<option value="'+index1+'"  '+selected+'>'+ item1 +'</option>';
                                        });  
                                    html +=  '</select>' ; 
                                    // html +=  '{!! Form::select('detail[desc][]', App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), '+ item.desc +',   ['class' => 'form-control select2 desc', 'required'=>true,'placeholder'=>'- เลือกรายละเอียดประมาณค่าใช้จ่าย -']); !!}'; 
                                html += '</td>';
                                html += '<td>';
                                    html +=   '<input type="text" name="detail[cost][]" class="form-control input_number cost_rate  text-right" required value="'+ addCommas(item.amount, 2)   +'"> ';
                                html += '</td>';
                                html += '<td>';
                                    html +=   '<input type="text" name="detail[nod][]" class="form-control amount_date  text-right" required value="'+ item.amount_date +'"> '; 
                                html += '</td>';
                                html += '<td>';
                                    html +=  '<input type="text" name="number[]" class="form-control number  text-right" readonly  value="'+ addCommas((item.amount * item.amount_date), 2)  +'"> '; 
                                html += '</td>';
                                html += '<td>';
                                     html +=  ' <button type="button" class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button>';
                                html += '</td>';
                                html += '</tr>';
                               
                               });  
        
                               $('#table_body').append(html);
                               TotalValue();
                               cost_rate();
                               IsNumber();
                               IsInputNumber();
                               var row = $('#table_body').children('tr');
                                   row.find('select.select2').prev().remove();
                                   row.find('select.select2').removeAttr('style');
                                   row.find('select.select2').select2();
                               data_list_disabled();
                           }
                       }); 
                    }else{
                          $('#table_cost').hide();
                           $('#no').val('');
                           $('#app_id').val('');
                    }
            });

            var lab_id    = '{{!empty($request->app_certi_lab_id) ?  $request->app_certi_lab_id : null}}'  ;
            if(lab_id != null){
                $('#app_certi_lab_id').change();
            }



            //เพิ่มแถว
            $('#addCostInput').click(function(event) {
                var data_list = $('.desc').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data_list == 0){
                        Swal.fire('หมอรายการรายละเอียดประมาณค่าใช้จ่าย !!')
                        return false;
                }
              //Clone
                $('#table_body').children('tr:first()').clone().appendTo('#table_body');
                //Clear value
                    var row = $('#table_body').children('tr:last()');
                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();
                    row.find('input[type="text"]').val('');
                ResetTableNumber();
                IsInputNumber();
                IsNumber();
                cost_rate();
                data_list_disabled();
            });


           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
            });



        function ResetTableNumber(){
                var rows = $('#table_body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                });
         }


        function  TotalValue() {
            var rows = $('#table_body').children(); //แถวทั้งหมด
            var total_all = 0.00;
            rows.each(function(index, el) {
                if($(el).children().find("input.number").val() != ''){
                    var number = parseFloat(RemoveCommas($(el).children().find("input.number").val()));
                    total_all  += number;
                }
            });
            $('#costs_total').val(addCommas(total_all.toFixed(2), 2));
           }

           function RemoveCommas(str) {
                   var res = str.replace(/[^\d\.\-\ ]/g, '');
                   return   res;
             }

           function  addCommas(nStr, decimal){
                    var tmp='';
                    var zero = '0';

                    nStr += '';
                    x = nStr.split('.');

                    if((x.length-1) >= 1){//ถ้ามีทศนิยม
                        if(x[1].length > decimal){//ถ้าหากหลักของทศนิยมเกินที่กำหนดไว้ ตัดให้เหลือเท่าที่กำหนดไว้
                            x[1] = x[1].substring(0, decimal);
                        }else if(x[1].length < decimal){//ถ้าหากหลักของทศนิยมน้อยกว่าที่กำหนดไว้ เพิ่ม 0
                            x[1] = x[1] + zero.repeat(decimal-x[1].length);
                        }
                        tmp = '.'+x[1];
                    }else{//ถ้าไม่มีทศนิยม
                        if(parseInt(decimal)>0){//ถ้ามีการกำหนดให้มี ทศนิยม
                                tmp = '.'+zero.repeat(decimal);
                            }
                    }
                    x1 = x[0];
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1+tmp;
           }

           function IsNumber() {
            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
                    $(".amount_date").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                    }); 
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
                    $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                    }); 
                    
                    // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
                    $(".input_number").on("change",function(){
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

           function cost_rate() {
             $('.cost_rate,.amount_date').keyup(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();
           
                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else if(cost_rate == '' || amount_date == ''){
                      row.find('.number').val('');
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });

             $('.cost_rate,.amount_date').change(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();
           
                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else if(cost_rate == '' || amount_date == ''){
                      row.find('.number').val('');
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });
         }

         function data_list_disabled(){
                $('.desc').children('option').prop('disabled',false);
                $('.desc').each(function(index , item){
                    var data_list = $(item).val();
                    $('.desc').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }




            let mock = $('.repeater-item').clone();
            setRepeaterIndex();

            //เพิ่มตำแหน่งงาน
            $('#plus-row').click(function () {

                let item = mock.clone();

                //Clear value select
                item.find('.myInput').val('');
                item.find('select').val('');
                item.find('select').prev().remove();
                item.find('select').removeAttr('style');
                item.find('select').select2();

                item.find('.repeater-remove').on('click', function () {
                    removeIndex(this)
                });

                item.find('.btn-user-select').on('click', function () {
                    modalHiding($(this).closest('.modal'));
                });
                item.find('.modal').on('show.bs.modal', function () {
                    modalOpening($(this));
                });
                item.find('.modal').on('hidden.bs.modal', function () {
                    modalClosing($(this));
                });

                item.find('.status').on('change', function () {
                    statusChange($(this));
                });

                item.find('.select-all').on('change', function () {
                    checkedAll($(this));
                });

                item.appendTo('#table-body');

                setRepeaterIndex();

            });

            $('.status').change(function () {
                statusChange($(this));
            });

            $('.repeater-remove').click(function () {
                removeIndex(this)
            });

            $('.btn-user-select').on('click', function () {
                modalHiding($(this).closest('.modal'));
            });

            $('.modal').on('show.bs.modal', function () {
                modalOpening($(this));
            });

            $('.modal').on('hidden.bs.modal', function () {
                modalClosing($(this));
            });

            $('.select-all').change(function () {
                checkedAll($(this));
            });

            //เพิ่มตำแหน่งงาน
            $('#work-add').click(function() {

                $('#work-box').children(':first').clone().appendTo('#work-box'); //Clone Element

                var last_new = $('#work-box').children(':last');

                //Clear value text
                $(last_new).find('input[type="text"]').val('');

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                //Clear Radio
                $(last_new).find('.check').each(function(index, el) {
                    $(el).prependTo($(el).parent().parent());
                    $(el).removeAttr('style');
                    $(el).parent().find('div').remove();
                    $(el).iCheck();
                    $(el).parent().addClass($(el).attr('data-radio'));
                });

                //Change Button
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger work-remove');
                $(last_new).find('button').html('<i class="icon-close"></i> ลบ');

                resetOrder();

            });

            //ลบตำแหน่ง
            $('body').on('click', '.work-remove', function() {

                $(this).parent().parent().parent().parent().remove();

                resetOrder();

            });

            //Crop image
            $uploadCrop = $('#upload-demo').croppie({

                enableExif: true,

                viewport: {

                    width: 140,

                    height: 140,

                },

                boundary: {

                    width: 200,

                    height: 200

                }

            });

            $('#upload').on('change', function () {

                $('#upload-demo').removeClass('hide');
                $('#image-show').addClass('hide');

                var reader = new FileReader();

                reader.onload = function (e) {

                    $uploadCrop.croppie('bind', {

                        url: e.target.result

                    }).then(function(){

                        console.log('jQuery bind complete');

                    });

                }

                reader.readAsDataURL(this.files[0]);

            });

            $('#form-save').click(function(event) {

                //เลื่อนมาแถบแรก
                $('.tab-pane').removeClass('active in');
                $('#home1').addClass('active in');

                //คัดลอกข้อมูลภาพที่ Crop
                CropFile();

            });
        });

        function checkedAll(that) {
            let checkboxes = $(that).closest('.modal').find('.tbody-auditor').find('input[type=checkbox]');
            checkboxes.each(function() {
                $(this).prop('checked', $(that).is(':checked'));
            });
        }
        function   filter_tbody_auditor() {
               $(".myInput").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            var row =   $(this).parent().parent().parent().parent();
                            $(row).find(".tbody-auditor tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                });   
        }

        function statusChange(that) {

            var app_lab_id  = $('#app_certi_lab_id').val();

            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            tdUsers.children().remove();
            tdDepartments.children().remove();

            let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
            input.appendTo(tdUsers);
            let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
            inputDepart.appendTo(tdDepartments);

            let tbody = $(that).closest('tr').find('.modal').find('tbody');
            let id = $(that).val();
            if(app_lab_id == ''){
                $(that).val('').select2();
                Swal.fire(
                        'กรุณาเลือกเลขคำขอขอก่อน',
                        '',
                        'info'
                        )
            } else if (id !== "" && id !== undefined) {
                that.parent().parent().parent().parent().find('.exampleModal').prop('disabled',false);
                let url = '{{url('/certify/auditor/status')}}'+'/'+id +'/'+ app_lab_id;
                $.ajax({
                    type: 'get',
                    url: url,
                    success: function (resp) {
                        tbody.children().remove();
                        let auditors = resp.auditors;
                        let n = 1;
                        auditors.forEach(auditor => {
                            let tr = $('<tr rolw="row" class="odd">');
                            let td = $('<td class="sorting_1">');
                            td.text(n + '.');
                            td.appendTo(tr);

                            let td2 = $('<td>');
                            let input = $('<input type="checkbox" id="master" value="'+auditor.id+'">');
                            input.attr('data-value', auditor.name_th).attr('data-department', auditor.department);
                            input.on('change', function () {
                                changeSelectAll($(this));
                            });
                            input.appendTo(td2);
                            td2.appendTo(tr);

                            let td3 = $('<td>');
                            td3.text(auditor.name_th);
                            td3.appendTo(tr);

                            let td4 = $('<td>');
                            td4.text(auditor.department);
                            td4.appendTo(tr);

                            let td5 = $('<td>');
                            td5.text(auditor.position);
                            td5.appendTo(tr);

                            let td6 = $('<td>');
                            td6.text(auditor.branch);
                            td6.appendTo(tr);

                            // let td6 = $('<td>');
                            // let button = $('<button type="button" class="btn btn-primary">');
                            // let icon = $('<i class="glyphicon glyphicon-info-sign" aria-hidden="true">');
                            // icon.appendTo(button);
                            // button.appendTo(td6);
                            // td6.appendTo(tr);

                            tr.appendTo(tbody);
                            n++;
                        });
                    },
                    error: function (resp) {
                        console.log(resp);
                    },
                })
                filter_tbody_auditor();
            } else if (id === "") {
                that.parent().parent().parent().parent().find('.exampleModal').prop('disabled',true);
                tbody.children().remove();
            }
        }

        var tempCheckboxes = [];
        function modalHiding(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let tdUsers = $(that).closest('.repeater-item').find('.td-users');
            let tdDepartments = $(that).closest('.repeater-item').find('.td-departments');
            let empty = true;
            let groupVal = "";
            tdUsers.children().remove();
            tdDepartments.children().remove();
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    let val = $(this).data('value');
                    let depart = $(this).data('department');
                    let input = $('<input type="text" class="form-control item" data-name="temp_users[]" value="'+val+'" readonly>');
                    input.appendTo(tdUsers);
                    let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" value="'+depart+'" readonly>');
                    inputDepart.appendTo(tdDepartments);
                    empty = false;

                    groupVal += groupVal !== "" ? ";" + $(this).val() : $(this).val();

                    tempCheckboxes.push($(this));
                }
            });

            let input = $('<input type="hidden" class="form-control item" data-name="users" value="'+groupVal+'">');
            input.appendTo(tdUsers);

            if (empty) {
                let input = $('<input type="text" class="form-control item" data-name="temp_users[]" required>');
                input.appendTo(tdUsers);
                let inputDepart = $('<input type="text" class="form-control item" data-name="temp_departments[]" readonly>');
                inputDepart.appendTo(tdDepartments);
            }

            $(that).modal('hide');

            setRepeaterIndex();
         
        }

        function modalOpening(that) {
            tempCheckboxes = [];
            let checkboxes = $(that).find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    tempCheckboxes.push($(this));
                    checkedCount++;
                }
            });

            changeSelectAll(that);

        }

        function modalClosing(that) {
            let checkboxes = $(that).find('input[type=checkbox]');
            checkboxes.prop('checked', false);
            tempCheckboxes.forEach(function (checkbox) {
                checkboxes.each(function () {
                    if (checkbox.val() === $(this).val()) {
                        $(this).prop('checked', true);
                    }
                });
            });
            tempCheckboxes = [];
        }

        function changeSelectAll(that) {
            let modal = $(that).closest('.modal');
            let checkboxes = modal.find('.tbody-auditor').find('input[type=checkbox]');
            let checkedCount = 0;
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    checkedCount++;
                }
            });

            if (checkedCount === checkboxes.length && checkboxes.length > 0) {
                modal.find('.select-all').prop('checked', true);
            } else {
                modal.find('.select-all').prop('checked', false);
            }
        }

        function setRepeaterIndex() {
            let group_name = "group";
            let n = 0;
            $('#table-body').find('tr.repeater-item').each(function () {
                $(this).find('.item').each(function () {
                    let dataName = $(this).data('name');
                    if (dataName !== undefined) {
                        let strArray = '';
                        if (dataName.includes('[]')) {
                            strArray = "[]";
                            dataName = dataName.substring(0, dataName.length - 2);
                        }

                        $(this).attr('name', group_name + "[" + n + "]" + "[" + dataName + "]" + strArray);
                    }
                });

                let newId = 'modal-' + n;
                $(this).find('.repeater-modal').attr('id', newId);
                $(this).find('.repeater-modal-open').attr('data-target', '#'+newId);
                n++;
            });
        }

        function removeIndex(that) {
            that.closest('tr').remove();

            setRepeaterIndex();
        }

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#work-box').children().each(function(index, el) {
                $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
                $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
            });

        }

        function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

            var croppied = $uploadCrop.croppie('get');

            $('#top').val(croppied.points[1]);
            $('#left').val(croppied.points[0]);
            $('#bottom').val(croppied.points[3]);
            $('#right').val(croppied.points[2]);
            $('#zoom').val(croppied.zoom);

            $uploadCrop.croppie('result', {

                type: 'canvas',

                size: 'viewport'

            }).then(function (resp) {

                $('#croppied').val(resp);

            });
        }


    </script>

@endpush
