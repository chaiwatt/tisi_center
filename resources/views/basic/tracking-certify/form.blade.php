@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@php
  $array =  ['#_','__'];
@endphp

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
  <hr>

<div class="form-group {{ $errors->has('reference_number_lab') ? 'has-error' : ''}}">
  {!! Form::label('reference_number_lab', 'จำนวนวันที่แจ้งเตือนต่ออายุ'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-7">
      {!! Form::text('reference_number_lab', null, ['class' => 'form-control', 'maxlength' => '512']) !!}
      {!! $errors->first('reference_number_lab', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('refno_lab') ? 'has-error' : ''}}">
  {!! Form::label('refno_lab', 'เลขอ้างอิง'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-8">
        <div class="table-responsive">
          <table class="table color-bordered-table info-bordered-table">
              <thead>
              <tr>
                  <th class="text-center" width="40%">รูปแบบ</th>
                  <th class="text-center" width="50%">ข้อมูล</th>
                  <th class="text-center" width="10%">ลบ</th>
              </tr>
              </thead>
              <tbody id="table_refno_lab">
                
                @if (count($config->reference_refno_lab) > 0)
                @foreach ($config->reference_refno_lab as $key => $item)
                    @php
                        $type_lab =   HP::SplitDataType($item,1);

                        $val_lab =   HP::SplitDataType($item,2);
                     
                    @endphp
 
                      <tr>
                          <td>
                            {!! Form::select('select_refno_lab[]', 
                                ['#_'=>'อักษรคั่น','__'=>'อักษรนำ','BE'=>'ปี พ.ศ.','AC'=>'ปี ค.ศ.','NO'=>'เลขรัน'], 
                                $type_lab ?? null, 
                              ['class' => 'form-control  select_refno_lab']); !!}
                          </td>
                          <td>
                            <span class="input">
                                @if ($type_lab == 'BE' || $type_lab == 'AC')
                                      {!! Form::select('text_refno_lab[]', 
                                        ['2'=>'2 หลัก','4'=>'4 หลัก'], 
                                          $val_lab ??  null, 
                                        ['class' => 'form-control select2  select_lab '  ]) 
                                    !!}
                                @elseif ($type_lab == 'NO')     
                                  {!! Form::number('text_refno_lab[]', $val_lab ??  null, ['class' => "form-control number_lab"]) !!}
                                @else 
                                    {!! Form::text('text_refno_lab[]',$val_lab ??  null, ['class' => "form-control  text_lab  " ,'maxlength' => '512']) !!}
                                @endif
                            </span>
                          </td>
                          <td align="center" >
                                <button type="button" class="btn btn-danger btn-xs remove_refno_lab">
                                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                              </button>
                          </td>
                      </tr>
                @endforeach
                @endif
              </tbody>
              <footer>
                  <tr>
                    <td align="center"> ตัวอย่าง </td>
                    <td  colspan="2"> <span id="footer_refno_lab"></span>  </td>
                  </tr>
              </footer>
          </table>
       </div>
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-success"  id="add_refno_lab"> เพิ่ม  </button>
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
  <hr>
<div class="form-group {{ $errors->has('reference_number_ib') ? 'has-error' : ''}}">
  {!! Form::label('reference_number_ib', 'จำนวนวันที่แจ้งเตือนต่ออายุ'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-7">
      {!! Form::text('reference_number_ib', null, ['class' => 'form-control', 'maxlength' => '512']) !!}
      {!! $errors->first('reference_number_ib', '<p class="help-block">:message</p>') !!}
  </div>
</div> 
<div class="form-group {{ $errors->has('refno_ib') ? 'has-error' : ''}}">
  {!! Form::label('refno_ib', 'เลขอ้างอิง'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-8">
        <div class="table-responsive">
          <table class="table color-bordered-table info-bordered-table">
              <thead>
              <tr>
                  <th class="text-center" width="40%">รูปแบบ</th>
                  <th class="text-center" width="50%">ข้อมูล</th>
                  <th class="text-center" width="10%">ลบ</th>
              </tr>
              </thead>
              <tbody id="table_refno_ib">
                
                @if (count($config->reference_refno_ib) > 0)
                @foreach ($config->reference_refno_ib as $key => $item)
                    @php
                        $type_ib =   HP::SplitDataType($item,1);

                        $val_ib =   HP::SplitDataType($item,2);
                     
                    @endphp
 
                      <tr>
                          <td>
                            {!! Form::select('select_refno_ib[]', 
                                ['#_'=>'อักษรคั่น','__'=>'อักษรนำ','BE'=>'ปี พ.ศ.','AC'=>'ปี ค.ศ.','NO'=>'เลขรัน'], 
                                $type_ib ?? null, 
                              ['class' => 'form-control  select_refno_ib']); !!}
                          </td>
                          <td>
                            <span class="input">
                                @if ($type_ib == 'BE' || $type_ib == 'AC')
                                      {!! Form::select('text_refno_ib[]', 
                                        ['2'=>'2 หลัก','4'=>'4 หลัก'], 
                                          $val_ib ??  null, 
                                        ['class' => 'form-control select2  select_ib '  ]) 
                                    !!}
                                @elseif ($type_ib == 'NO')     
                                  {!! Form::number('text_refno_ib[]', $val_ib ??  null, ['class' => "form-control number_ib"]) !!}
                                @else 
                                    {!! Form::text('text_refno_ib[]',$val_ib ??  null, ['class' => "form-control  text_ib  " ,'maxlength' => '512']) !!}
                                @endif
                            </span>
                          </td>
                          <td align="center" >
                                <button type="button" class="btn btn-danger btn-xs remove_refno_ib">
                                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                              </button>
                          </td>
                      </tr>
                @endforeach
                @endif
              </tbody>
              <footer>
                  <tr>
                    <td align="center"> ตัวอย่าง </td>
                    <td  colspan="2"> <span id="footer_refno_ib"></span>  </td>
                  </tr>
              </footer>
          </table>
       </div>
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-success"  id="add_refno_ib"> เพิ่ม  </button>
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
  <hr>
<div class="form-group {{ $errors->has('reference_number_cb') ? 'has-error' : ''}}">
  {!! Form::label('reference_number_cb', 'จำนวนวันที่แจ้งเตือนต่ออายุ'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-7">
         {!! Form::text('reference_number_cb', null, ['class' => 'form-control', 'maxlength' => '512']) !!}
         {!! $errors->first('reference_number_cb', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group {{ $errors->has('refno_cb') ? 'has-error' : ''}}">
  {!! Form::label('refno_cb', 'เลขอ้างอิง'.' :', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-8">
        <div class="table-responsive">
          <table class="table color-bordered-table info-bordered-table">
              <thead>
              <tr>
                  <th class="text-center" width="40%">รูปแบบ</th>
                  <th class="text-center" width="50%">ข้อมูล</th>
                  <th class="text-center" width="10%">ลบ</th>
              </tr>
              </thead>
              <tbody id="table_refno_cb">
                
                @if (count($config->reference_refno_cb) > 0)
                @foreach ($config->reference_refno_cb as $key => $item)
                    @php
                        $type_cb =   HP::SplitDataType($item,1);

                        $val_cb =   HP::SplitDataType($item,2);
                     
                    @endphp
 
                      <tr>
                          <td>
                            {!! Form::select('select_refno_cb[]', 
                                ['#_'=>'อักษรคั่น','__'=>'อักษรนำ','BE'=>'ปี พ.ศ.','AC'=>'ปี ค.ศ.','NO'=>'เลขรัน'], 
                                $type_cb ?? null, 
                              ['class' => 'form-control  select_refno_cb']); !!}
                          </td>
                          <td>
                            <span class="input">
                                @if ($type_cb == 'BE' || $type_cb == 'AC')
                                      {!! Form::select('text_refno_cb[]', 
                                        ['2'=>'2 หลัก','4'=>'4 หลัก'], 
                                          $val_cb ??  null, 
                                        ['class' => 'form-control select2  select_cb '  ]) 
                                    !!}
                                @elseif ($type_cb == 'NO')     
                                  {!! Form::number('text_refno_cb[]', $val_cb ??  null, ['class' => "form-control number_cb"]) !!}
                                @else 
                                    {!! Form::text('text_refno_cb[]',$val_cb ??  null, ['class' => "form-control  text_cb  " ,'maxlength' => '512']) !!}
                                @endif
                            </span>
                          </td>
                          <td align="center" >
                                <button type="button" class="btn btn-danger btn-xs remove_refno_cb">
                                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                              </button>
                          </td>
                      </tr>
                @endforeach
                @endif
              </tbody>
              <footer>
                  <tr>
                    <td align="center"> ตัวอย่าง </td>
                    <td  colspan="2"> <span id="footer_refno_cb"></span>  </td>
                  </tr>
              </footer>
          </table>
       </div>
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-success"  id="add_refno_cb"> เพิ่ม  </button>
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
  <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
  <script type="text/javascript">
    jQuery(document).ready(function() { 

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

             reset_refno_lab();
             reset_refno_ib();
             reset_refno_cb();
          //เพิ่มแถว
          $('#add_refno_lab').click(function(event) {
                    $('#table_refno_lab').children('tr:first()').clone().appendTo('#table_refno_lab');
                var row = $('#table_refno_lab').children('tr:last()');
                    // row.find('input[type=text]').val(''); 
                    row.find('select.select_refno_lab').val('#_');
                    row.find('select.select_refno_lab').prev().remove();
                    row.find('select.select_refno_lab').removeAttr('style');
                    row.find('select.select_refno_lab').select2();

                    row.find('select.select2').val('');
                    row.find('select.select2').prev().remove();
                    row.find('select.select2').removeAttr('style');
                    row.find('select.select2').select2();

                    var input =  row.find('.input');
                        input.html('');
                        input.html('<input type="text"  name="text_refno_lab[]"  class="form-control  text_lab "  maxlength="512" />');
                    reset_refno_lab();
            });

             //ลบแถว
             $('body').on('click', '.remove_refno_lab', function(){
                   $(this).parent().parent().remove();
                   reset_refno_lab();
              });
 
              $('body').on('change', '.select_lab,.text_lab,.number_lab', function(){
                   reset_refno_lab();
              });

              $(".text_lab,.number_lab").keyup(function(){
                    reset_refno_lab();
               });

      
             $('body').on('change', '.select_refno_lab', function(){
             
                   var  row =  $(this).val();
                  var $this = $(this).parent().parent();
                   var input =  $this.find('.input');
                   input.html('');
                    if(row  == 'BE' || row  == 'AC' ){
                      input.html('<select  name="text_refno_lab[]"  class="form-control select2  select_lab  "   ><option value="2">2 หลัก</option><option value="4">4 หลัก</option></select>');
                      input.find('select.select2').select2();
                    }else if(row == 'NO'){
                      input.html('<input type="number" name="text_refno_lab[]" class="form-control  number_lab " />');
                    }else{
                      input.html('<input type="text"  name="text_refno_lab[]"  class="form-control  text_lab "  maxlength="512" />');
                    }
                    reset_refno_lab();
              });

            //เพิ่มแถว
            $('#add_refno_ib').click(function(event) {
                      $('#table_refno_ib').children('tr:first()').clone().appendTo('#table_refno_ib');
                  var row = $('#table_refno_ib').children('tr:last()');
                      // row.find('input[type=text]').val(''); 
                      row.find('select.select_refno_ib').val('#_');
                      row.find('select.select_refno_ib').prev().remove();
                      row.find('select.select_refno_ib').removeAttr('style');
                      row.find('select.select_refno_ib').select2();

                      row.find('select.select2').val('');
                      row.find('select.select2').prev().remove();
                      row.find('select.select2').removeAttr('style');
                      row.find('select.select2').select2();

                      var input =  row.find('.input');
                          input.html('');
                          input.html('<input type="text"  name="text_refno_ib[]"  class="form-control  text_ib "  maxlength="512" />');
                      reset_refno_ib();
              });

              //ลบแถว
              $('body').on('click', '.remove_refno_ib', function(){
                    $(this).parent().parent().remove();
                    reset_refno_ib();
                });

                $('body').on('change', '.select_ib,.text_ib,.number_ib', function(){
                    reset_refno_ib();
                });

                $(".text_ib,.number_ib").keyup(function(){
                      reset_refno_ib();
                });


              $('body').on('change', '.select_refno_ib', function(){
              
                    var  row =  $(this).val();
                    var $this = $(this).parent().parent();
                    var input =  $this.find('.input');
                    input.html('');
                      if(row  == 'BE' || row  == 'AC' ){
                        input.html('<select  name="text_refno_ib[]"  class="form-control select2  select_ib  "   ><option value="2">2 หลัก</option><option value="4">4 หลัก</option></select>');
                        input.find('select.select2').select2();
                      }else if(row == 'NO'){
                        input.html('<input type="number" name="text_refno_ib[]" class="form-control  number_ib " />');
                      }else{
                        input.html('<input type="text"  name="text_refno_ib[]"  class="form-control  text_ib "  maxlength="512" />');
                      }
                      reset_refno_ib();
                });

              //เพิ่มแถว
              $('#add_refno_cb').click(function(event) {
                        $('#table_refno_cb').children('tr:first()').clone().appendTo('#table_refno_cb');
                    var row = $('#table_refno_cb').children('tr:last()');
                        // row.find('input[type=text]').val(''); 
                        row.find('select.select_refno_cb').val('#_');
                        row.find('select.select_refno_cb').prev().remove();
                        row.find('select.select_refno_cb').removeAttr('style');
                        row.find('select.select_refno_cb').select2();

                        row.find('select.select2').val('');
                        row.find('select.select2').prev().remove();
                        row.find('select.select2').removeAttr('style');
                        row.find('select.select2').select2();

                        var input =  row.find('.input');
                            input.html('');
                            input.html('<input type="text"  name="text_refno_cb[]"  class="form-control  text_cb "  maxlength="512" />');
                        reset_refno_cb();
                });

                //ลบแถว
                $('body').on('click', '.remove_refno_cb', function(){
                      $(this).parent().parent().remove();
                      reset_refno_cb();
                  });

                  $('body').on('change', '.select_cb,.text_cb,.number_cb', function(){
                      reset_refno_cb();
                  });

                  $(".text_cb,.number_cb").keyup(function(){
                        reset_refno_cb();
                  });


                $('body').on('change', '.select_refno_cb', function(){
                
                      var  row =  $(this).val();
                      var $this = $(this).parent().parent();
                      var input =  $this.find('.input');
                      input.html('');
                        if(row  == 'BE' || row  == 'AC' ){
                          input.html('<select  name="text_refno_cb[]"  class="form-control select2  select_cb  "   ><option value="2">2 หลัก</option><option value="4">4 หลัก</option></select>');
                          input.find('select.select2').select2();
                        }else if(row == 'NO'){
                          input.html('<input type="number" name="text_refno_cb[]" class="form-control  number_cb " />');
                        }else{
                          input.html('<input type="text"  name="text_refno_cb[]"  class="form-control  text_cb "  maxlength="512" />');
                        }
                        reset_refno_cb();
                  });
           
    });

  
    function reset_refno_lab(){
      var rows = $('#table_refno_lab').children(); //แถวทั้งหมด
      (rows.length==1)?$('.remove_refno_lab').hide():$('.remove_refno_lab').show();
      var html = '';

      const str1 = '1';
        rows.each(function(index, el) {
              var row =  $(el).find('select.select_refno_lab').val();
              if(row  == 'BE' ){
      const date2 = "{{ date('y') +43 }}";
      const date4 = "{{ date('Y') +543  }}";
                var val = $(el).find('select.select_lab').val();
                    if(val == '2'){
                      html +=   date2 ;
                    }else{
                        html +=  date4 ;
                    }
              }else if(row == 'AC'){
      const date2 = "{{ date('y') }}";
      const date4 = "{{ date('Y') }}";
                  var val = $(el).find('select.select_lab').val();
                    if(val == '2'){
                      html +=  date2;
                    }else{
                     html +=  date4 ;
                    }
              }else if(row == 'NO'){
                var number = $(el).find('.number_lab').val();
                  html +=  str1.padStart(number, '0');
              }else{
                  html += $(el).find('.text_lab').val();
              }
        });

         $('#footer_refno_lab').html(html);
    }

    function reset_refno_ib(){
          var rows = $('#table_refno_ib').children(); //แถวทั้งหมด
          (rows.length==1)?$('.remove_refno_ib').hide():$('.remove_refno_ib').show();
          var html = '';
    
          const str1 = '1';
            rows.each(function(index, el) {
                  var row =  $(el).find('select.select_refno_ib').val();
                  if(row  == 'BE' ){
          const date2 = "{{ date('y') +43 }}";
          const date4 = "{{ date('Y') +543  }}";
                    var val = $(el).find('select.select_ib').val();
                        if(val == '2'){
                          html +=   date2 ;
                        }else{
                            html +=  date4 ;
                        }
                  }else if(row == 'AC'){
          const date2 = "{{ date('y') }}";
          const date4 = "{{ date('Y') }}";
                      var val = $(el).find('select.select_ib').val();
                        if(val == '2'){
                          html +=  date2;
                        }else{
                         html +=  date4 ;
                        }
                  }else if(row == 'NO'){
                    var number = $(el).find('.number_ib').val();
                      html +=  str1.padStart(number, '0');
                  }else{
                      html += $(el).find('.text_ib').val();
                  }
            });
    
             $('#footer_refno_ib').html(html);
        }
 
        function reset_refno_cb(){
          var rows = $('#table_refno_cb').children(); //แถวทั้งหมด
          (rows.length==1)?$('.remove_refno_cb').hide():$('.remove_refno_cb').show();
          var html = '';
    
          const str1 = '1';
            rows.each(function(index, el) {
                  var row =  $(el).find('select.select_refno_cb').val();
                  if(row  == 'BE' ){
          const date2 = "{{ date('y') +43 }}";
          const date4 = "{{ date('Y') +543  }}";
                    var val = $(el).find('select.select_cb').val();
                        if(val == '2'){
                          html +=   date2 ;
                        }else{
                            html +=  date4 ;
                        }
                  }else if(row == 'AC'){
          const date2 = "{{ date('y') }}";
          const date4 = "{{ date('Y') }}";
                      var val = $(el).find('select.select_cb').val();
                        if(val == '2'){
                          html +=  date2;
                        }else{
                         html +=  date4 ;
                        }
                  }else if(row == 'NO'){
                    var number = $(el).find('.number_cb').val();
                      html +=  str1.padStart(number, '0');
                  }else{
                      html += $(el).find('.text_cb').val();
                  }
            });
    
             $('#footer_refno_cb').html(html);
        }

  </script> 
@endpush
