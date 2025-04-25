@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush




<div class="form-group {{ $errors->has('department_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('department_id', 'กลุ่มงานหลัก'.':', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        <p class="col-md-12 m-t-10">สำนักงานคณะกรรมการการมาตรฐานแห่งชาติ</p>
        <input type="hidden" name="department_id" value="18" id="department_id">
        {{-- {!! Form::select('department_id',
          App\Models\Besurv\Department::orderbyRaw('CONVERT(depart_name USING tis620)')->pluck('depart_name','did'), 
          null,
         ['class' => 'form-control select2',
         'placeholder'=>'- เลือกกลุ่มงานหลัก -', 
         'id' =>'department_id',
         'required' => true]); !!}
        {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!} --}}
    </div>
</div>
<div class="form-group {{ $errors->has('sub_department_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('sub_department_id', '<span class="text-danger">*</span> กลุ่มงานย่อย'.':', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('sub_department_id',
         App\Models\Basic\SubDepartment::where('did',18)->orderbyRaw('CONVERT(sub_departname USING tis620)')->pluck('sub_departname','sub_id'), 
          null,
         ['class' => 'form-control select2',
         'placeholder'=>'- เลือกกลุ่มงานย่อย -', 
         'id' =>'sub_department_id',
         'required' => true]); !!}
        {!! $errors->first('sub_department_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('formula_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('formula_id', '<span class="text-danger">*</span> มาตรฐานที่รับผิดชอบ'.':', ['class' => 'col-md-4 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('formula_id',
          App\Models\Bcertify\Formula::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'), 
          null,
         ['class' => 'form-control select2',
         'placeholder'=>'- เลือกมาตรฐาน -', 
         'id' =>'formula_id']); !!}
        {!! $errors->first('formula_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div id="branch">
    <div class="form-group {{ $errors->has('lab_ability') ? 'has-error' : ''}}">
        {{-- {!! HTML::decode(Form::label('lab_ability', '<span class="text-danger">*</span> มาตรฐานที่รับผิดชอบ'.':', ['class' => 'col-md-4 control-label'])) !!} --}}
        <div class="col-md-4 "></div>
        <div class="col-md-7">
            <label>{!! Form::radio('lab_ability', '1', true, ['class'=>'check checkLab', 'data-radio'=>'iradio_square-green']) !!} &nbsp;ทดสอบ &nbsp;</label>
            <label>{!! Form::radio('lab_ability', '2', false, ['class'=>'check checkLab', 'data-radio'=>'iradio_square-red']) !!} &nbsp;สอบเทียบ &nbsp;</label>
            {!! $errors->first('lab_ability', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('test_branch_id') ? 'has-error' : ''}}" id="div_test_branch_id">
        {!! HTML::decode(Form::label('test_branch_id', '<span class="text-danger">*</span> สาขาวิชาทดสอบที่รับผิดชอบ'.':', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-6">
            {!! Form::select('test_branch_id',
            App\Models\Bcertify\TestBranch::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'), 
            null,
            ['class' => 'form-control select2',
            'placeholder'=>'- เลือกมาตรฐาน -', 
            'id' =>'test_branch_id']); !!}
            {!! $errors->first('test_branch_id', '<p class="help-block">:message</p>') !!}
        </div>
         <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_test_branch"><i class="icon-plus"></i>&nbsp; ทั้งหมด</button>
        </div>
    </div>
    <div class="form-group {{ $errors->has('items_id') ? 'has-error' : ''}}" id="div_items_id">
        {!! HTML::decode(Form::label('items_id', '<span class="text-danger">*</span> สาขาวิชาสอบเทียบที่รับผิดชอบ'.':', ['class' => 'col-md-4 control-label'])) !!}
        <div class="col-md-6">
            {!! Form::select('items_id',
            App\Models\Bcertify\CalibrationBranch::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'), 
            null,
            ['class' => 'form-control select2',
            'placeholder'=>'- เลือกมาตรฐาน -', 
            'id' =>'items_id']); !!}
            {!! $errors->first('items_id', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_items"><i class="icon-plus"></i>&nbsp; ทั้งหมด</button>
        </div>
    </div>
</div>


<div class="col-md-12 m-t-10">
    <button type="button" class="btn btn-sm btn-success pull-right m-l-5" id="add_testTable"> <i class="icon-plus"></i>&nbsp;เพิ่ม</button>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="white-box m-t-20" style="border: 2px solid #e5ebec;">
            <table class="table table-bordered" id="myTable_labTest">
                <thead class="bg-primary">
                 <tr>
                    <th class="text-center text-white col-xs-1" width="2%">ลำดับ</th>
                    <th class="text-center text-white col-xs-2" width="40;">มาตรฐาน</th>
                    <th class="text-center text-white col-xs-1  th_id_HideShow" width="50%">
                        <div id="ability">
                            @if(isset($setstandard))
                                @if($setstandard->lab_ability==1)
                                    สาขาการทดสอบ
                                @elseif($setstandard->lab_ability==2)
                                    สาขาการสอบเทียบ
                                @endif
                            @endif
                        </div>
                    </th>
                    <th class="text-center text-white col-xs-1" width="10%">ลบรายการ</th>
                 </tr>
                </thead>
                <tbody id="labtest_tbody">
                    @if(isset($setstandard) && count($setstandard->DataSetStandardUserSub) > 0)
                        @foreach($setstandard->DataSetStandardUserSub as $kry => $itme)
                        @php 
                            $branch_id = '';
                            $branch_title = '';
                            if($setstandard->lab_ability==1){
                                $branch_id =  $itme->test_branch_id ?? '';
                                $branch_title =  $itme->department->title ?? '';
                            } elseif($setstandard->lab_ability==2){
                                $branch_id = $itme->items_id ?? '';
                                $branch_title = $itme->calibration_branch_to->title ?? '';
                            }
                        @endphp
                            <tr>
                                <td class="text-center" style="vertical-align:top">1</td>
                                <td class="text-left " style="vertical-align:top">
                                    {{ $itme->DataFormula->title ?? ''}}
                                </td>
                                <td class="text-left th_id_HideShow" style="vertical-align:top">
                                    {{ $branch_title ?? '' }}
                                </td>
                                <td class="text-center" style="vertical-align:top">
                                      <input type="hidden" class="formula_id"  name="formula_id[]" value="{{ $itme->standard_id ??  '' }}">
                                      <input type="hidden"  class="branch"  name="branch[]"  value="{{ $branch_id ??  '' }}">
                                      <button type="button" class="btn btn-danger btn-xs FormulaDelete" 
                                             data-types="{{ @$itme->standard_id }}" data-sub_department="{{ @$branch_id }}">
                                            <i class="fa fa-remove"></i>
                                      </button>
                                </td>
                            </tr>
                         @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('tisusercertify'))
            <a class="btn btn-default" href="{{url('/certify/set-standard-user')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
  <script>
    jQuery(document).ready(function() {
     
   var sub_department_ids = '{{!empty($setstandard->sub_department_id) ? $setstandard->sub_department_id : null}}';
       if(sub_department_ids == "1804" || sub_department_ids == "1805" || sub_department_ids == "1806"){
             $('#branch').show();
             $('.th_id_HideShow').show();
             var row = $("input[name=lab_ability]:checked").val();
             if(row == 1){
                $('#div_test_branch_id').show();
                $('#div_items_id').hide();
             }else{
                $('#div_test_branch_id').hide();
                $('#div_items_id').show();
             }
        }else{
            $('#branch').hide();
            $('.th_id_HideShow').hide();
        }

      // กลุ่มงานหลัก ->  กลุ่มงานย่อย
        // $('#department_id').change(function(){
        //   $('#formula_id').val('').select2();
        //   $('#sub_department_id').append('<option>- เลือกกลุ่มงานย่อย -</option>');
        //   if($(this).val()!=""){
         
        //       $.ajax({
        //           url: "{!! url('certify/set-standard-user/department') !!}" + "/" + $(this).val()
        //       }).done(function( object ) {
        //           $.each(object, function( index, data ) {
        //               $('#sub_department_id').append('<option value="'+data.sub_id+'">'+data.sub_departname+'</option>');
        //           });
        //       });
        //    }
        // });
        // $('#department_id').change();
        let sub = $('#sub_department_id').find('option:selected').val();
           $.ajax({
                 url: "{!! url('certify/set-standard-user/sub_department/1') !!}"
             }).done(function( object ) { 
             
                 $.each(object, function( index, data ) {
                      if(sub != data.sub_department_id){
                         $("#sub_department_id option[value=" + data.sub_department_id + "]").prop("disabled", true); //  disabled รายการ 
                      }
                  });
     
              });

        $('#sub_department_id').change(function(){

                   
            // if($(this).val() != ''){
                    $('#formula_id').find('option:disabled').prop('disabled', false);
                    $('#test_branch_id').find('option:disabled').prop('disabled', false);
                    $('#items_id').find('option:disabled').prop('disabled', false);
                if($(this).val() == "1804" || $(this).val() == "1805" || $(this).val() == "1806"){
                    $('#branch').show();
                    $('.th_id_HideShow').show();
                    status_show_lab_ability();
                }else{
                    let theTable = $('#labtest_tbody');
                        theTable.empty();
                    $('#branch').hide();
                    $('.th_id_HideShow').hide();
                }
            // }
        });
       

       $('#test_branch_id').change(function(){
             $('#test_branch_id').find('option').prop('disabled', false);
             $('#labtest_tbody').children('tr').each(function(index, tr) {
                 if($('#formula_id').val() == $(tr).find('.formula_id').val()){
                    let row = $(tr).find('.branch').val();
                    $("#test_branch_id option[value=" + row + "]").prop("disabled", true); //  disabled รายการ 
                 }
             }); 
        });


        $('#items_id').change(function(){
             $('#items_id').find('option').prop('disabled', false);
             $('#labtest_tbody').children('tr').each(function(index, tr) {
                 if($('#formula_id').val() == $(tr).find('.formula_id').val()){
                    let row = $(tr).find('.branch').val();
                    $("#items_id option[value=" + row + "]").prop("disabled", true); //  disabled รายการ 
                 }
             }); 
        });

        $('#formula_id').change(function(){
            $('#test_branch_id').change();
            $('#items_id').change();
        });
        
        // ทั้งหมด สาขาวิชาทดสอบที่รับผิดชอบ:
        $('#add_test_branch').on('click',function () {
            let formula = $('#formula_id').find('option:selected').val();
            let formula_text = $('#formula_id').find('option:selected').text();
            let sub_department_id = $('#sub_department_id').find('option:selected').val();
            if(formula != ''){
                let theTable = $('#labtest_tbody');   
                var options = $('#test_branch_id option:not([disabled]');
                $.map(options ,function(option) {
                 if(option.value != ''){
                    var   branch = $('#test_branch_id').find('option[value="'+option.value+'"]').text();  
                    theTable.append('<tr>\n' +
                        '                    <td class="text-center" style="vertical-align:top">1</td>\n' +
                        '                    <td class="text-left" style="vertical-align:top">'+formula_text+'</td>\n' +
                        '                    <td class="text-left" style="vertical-align:top">'+branch+'</td>\n' +
                        '                    <td class="text-center">' +
                        '                    <input type="hidden" class="formula_id"  name="formula_id[]" value="'+ formula +'">\n' +
                        '                    <input type="hidden"  class="branch"  name="branch[]"  value="'+  option.value +'">\n' +
                        '                    <button type="button" class="btn btn-danger btn-xs FormulaDelete" data-types="'+ option.value+'" data-sub_department="'+sub_department_id+'"><i class="fa fa-remove"></i></button>' +
                        '                    </td>\n' +
                        '                </tr>');

                        $('#test_branch_id').find('option[value="'+option.value+'"]').prop('disabled', true); //  disabled รายการ .
                    }
                   });
                ResetTableNumber();
            }else{
                Swal.fire('กรุณาเลือกมาตรฐาน !');
            }
           });

       // ทั้งหมด สาขาวิชาสอบเทียบที่รับผิดชอบ:
        $('#add_items').on('click',function () {
            let formula = $('#formula_id').find('option:selected').val();
            let formula_text = $('#formula_id').find('option:selected').text();
            let sub_department_id = $('#sub_department_id').find('option:selected').val();
            if(formula != ''){
                let theTable = $('#labtest_tbody');   
                var options = $('#items_id option:not([disabled]');
                $.map(options ,function(option) {
                 if(option.value != ''){
                    var   branch = $('#items_id').find('option[value="'+option.value+'"]').text();  
                    theTable.append('<tr>\n' +
                        '                    <td class="text-center" style="vertical-align:top">1</td>\n' +
                        '                    <td class="text-left" style="vertical-align:top">'+formula_text+'</td>\n' +
                        '                    <td class="text-left" style="vertical-align:top">'+branch+'</td>\n' +
                        '                    <td class="text-center">' +
                        '                    <input type="hidden" class="formula_id"  name="formula_id[]" value="'+ formula +'">\n' +
                        '                    <input type="hidden"  class="branch"  name="branch[]"  value="'+  option.value +'">\n' +
                        '                    <button type="button" class="btn btn-danger btn-xs FormulaDelete" data-types="'+ option.value+'" data-sub_department="'+sub_department_id+'"><i class="fa fa-remove"></i></button>' +
                        '                    </td>\n' +
                        '                </tr>');

                        $('#items_id').find('option[value="'+option.value+'"]').prop('disabled', true); //  disabled รายการ .
                    }
                   });
                ResetTableNumber();
            }else{
                Swal.fire('กรุณาเลือกมาตรฐาน !');
            }
          });

        ResetTableNumber();
        $("input[name=lab_ability]").on("ifChanged",function(){
            status_show_lab_ability();
        });

         /////////////////////// เพิ่มลง ตาราง ///////////////////////////
         $('#add_testTable').on('click',function () {
                let sub_department_id = $('#sub_department_id').find('option:selected').val();
                let formula_id = $('#formula_id').find('option:selected').val();
                let test_branch_id = $('#test_branch_id').val();
                let items_id = $('#items_id').val();
                    if (checkNone(sub_department_id) && checkNone(formula_id)){
                        if(sub_department_id == "1804" || sub_department_id == "1805" || sub_department_id == "1806"){
                            if(items_id != '' || test_branch_id != ''){
                                writeTable(sub_department_id, formula_id);
                            }else{
                                Swal.fire('กรุณาใส่ข้อมูลให้ครบ !');
                            }
                        }else{
                            writeTable(sub_department_id, formula_id);
                        }
                      
                    }else{
                        Swal.fire('กรุณาใส่ข้อมูลให้ครบ !');
                    }
            });

         /////////////////////// เลือกมาลบ ////////////////////////////
           $(document).on('click','.FormulaDelete',function () { 
                let types = $(this).attr('data-types');
                let sub_department = $(this).attr('data-sub_department');
                if(sub_department_id == "1804" || sub_department_id == "1805" || sub_department_id == "1806"){
                    var row = $("input[name=lab_ability]:checked").val();
                        if(row == 1){
                            $("#test_branch_id option[value=" + types + "]").prop('disabled', false); // เปิด disabled รายการ  สาขาวิชาทดสอบที่รับผิดชอบ
                        }else{
                            $("#items_id option[value=" + types + "]").prop('disabled', false); // เปิด disabled รายการ  สาขาวิชาสอบเทียบที่รับผิดชอบ
                        }
                }else{
                             $("#formula_id option[value=" + types + "]").prop('disabled', false); // เปิด disabled รายการ  มาตรฐานที่รับผิดชอบ
                }
               
                $(this).parent().parent().remove();
                ResetTableNumber();
            });
    });
    // 1. ทดสอบ  2. สอบเทียบ
     function status_show_lab_ability(){
           var row = $("input[name=lab_ability]:checked").val();
           let theTable = $('#labtest_tbody');     
           if(row == 1){
                $('#div_test_branch_id').show();
                $('#div_items_id').hide();
                $('div#ability').html('สาขาการทดสอบ');
                $('#test_branch_id').find('option:disabled').prop('disabled', false);
                theTable.empty();
           }
           if(row == 2){
                $('#div_test_branch_id').hide();
                $('#div_items_id').show();
                $('div#ability').html('สาขาการสอบเทียบ');
                $('#items_id').find('option:disabled').prop('disabled', false);
                theTable.empty();
           }
      }

      
      function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        
        function writeTable(sub_department_id, formula_id){

              let theTable = $('#labtest_tbody');
              let department = $('#sub_department_id').find('option[value="'+sub_department_id+'"]').text();
              let formula = $('#formula_id').find('option[value="'+formula_id+'"]').text();

              if(sub_department_id == "1804" || sub_department_id == "1805" || sub_department_id == "1806"){
                    var row = $("input[name=lab_ability]:checked").val();
                    var  branch = '';
                    var  branch_id = '';
                    if(row == 1){
                        let test_branch_id = $('#test_branch_id').find('option:selected').val();
                            branch = $('#test_branch_id').find('option[value="'+test_branch_id+'"]').text();  
                            branch_id  = test_branch_id;
                            $("#test_branch_id option[value=" + test_branch_id + "]").prop('disabled', true); //  disabled รายการ มาตรฐานที่รับผิดชอบ
                    }else{
                        let  items_id = $('#items_id').find('option:selected').val();
                             branch = $('#items_id').find('option[value="'+items_id+'"]').text();   
                             branch_id  = items_id;
                             $("#items_id option[value=" + items_id + "]").prop('disabled', true); //  disabled รายการ มาตรฐานที่รับผิดชอบ
                    }
            
                  theTable.append('<tr>\n' +
                    '                    <td class="text-center" style="vertical-align:top">1</td>\n' +
                    '                    <td class="text-left" style="vertical-align:top">'+formula+'</td>\n' +
                    '                    <td class="text-left" style="vertical-align:top">'+branch+'</td>\n' +
                    '                    <td class="text-center">' +
                    '                    <input type="hidden" class="formula_id"  name="formula_id[]" value="'+ formula_id +'">\n' +
                    '                    <input type="hidden"  class="branch"  name="branch[]"  value="'+ branch_id +'">\n' +
                    '                    <button type="button" class="btn btn-danger btn-xs FormulaDelete" data-types="'+branch_id+'" data-sub_department="'+sub_department_id+'"><i class="fa fa-remove"></i></button>' +
                    '                    </td>\n' +
                    '                </tr>');
                    clearInputLabAbility(row);

                }else{
                    theTable.append('<tr>\n' +
                    '                    <td class="text-center" style="vertical-align:top">1</td>\n' +
                    '                    <td class="text-left" style="vertical-align:top">'+formula+'</td>\n' +
                    '                    <td class="text-center">' +
                    '                    <input type="hidden"  class="formula_id" name="formula_id[]" value="'+ formula_id +'">\n' +
                    '                    <button type="button" class="btn btn-danger btn-xs FormulaDelete" data-types="'+formula_id+'"  data-sub_department="'+sub_department_id+'"><i class="fa fa-remove"></i></button>' +
                    '                    </td>\n' +
                    '                </tr>');

                    $("#formula_id option[value=" + formula_id + "]").prop('disabled', true); //  disabled รายการ มาตรฐานที่รับผิดชอบ
                    clearInputLabTest();
                }

            ResetTableNumber();
        }

        function clearInputLabTest() {
            // $('#sub_department_id').val('').change();
            $('#formula_id').val('').change();
        }

        function clearInputLabAbility(row){
            if(row == 1){
                $('#test_branch_id').val('').select2();
            }else{
                $('#items_id').val('').select2();
            }
        }
        //รีเซตเลขลำดับ
        function ResetTableNumber(){
          var rows = $('#labtest_tbody').children(); //แถวทั้งหมด
          rows.each(function(index, el) {
            $(el).children().first().html(index+1);
          });
        }
</script>
@endpush
