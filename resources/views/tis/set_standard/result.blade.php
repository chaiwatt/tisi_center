

        {!! Form::model($set_standard, [
            'method' => 'PATCH',
            'url' => ['/tis/set_standard', $set_standard->id],
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'cost_form'
        ]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('statusOperation_id2') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('statusOperation_id2', 'กิจกรรม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('statusOperation_id2', 
                 App\Models\Basic\StatusOperation::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'statusOperation_id2',
              'placeholder'=>'-เลือกกิจกรรม-', 
              'required' => true]); !!}
                {!! $errors->first('statusOperation_id2', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('appointName_id2') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('appointName_id2', 'ชื่อคณะประชุม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('appointName_id2', 
                 App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->Where('state',1)->Where('title','!=','')->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
               'id' => 'appointName_id2',
              'placeholder'=>'-เลือกใชื่อคณะประชุม-', 
              'required' => true]); !!}
                {!! $errors->first('appointName_id2', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('meetingNo2') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('meetingNo2', 'ครั้งที่ประชุม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                 {!! Form::text('meetingNo2', null, ['class' => 'form-control', 'required' =>  false]) !!}
                {!! $errors->first('meetingNo2', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('year2') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('year2', 'ปีงบประมาณ'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('year2', 
                 HP::Years(), 
               null,
              ['class' => 'form-control',
               'id' => 'year2',
              'placeholder'=>'-เลือกปีงบประมาณ-', 
              'required' => true]); !!}
                {!! $errors->first('year2', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('startdate2') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('startdate2', 'วัน'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                <div class="input-daterange input-group date-range">
                    {!! Form::text('startdate2', null, ['id'=>'startdate2','class' => 'form-control date', 'required' => true]) !!}
                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                    {!! Form::text('enddate2', null, ['id'=>'enddate2','class' => 'form-control date', 'required' => true]) !!}
                  </div>
                {!! $errors->first('startdate2', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="280px">#</th>
                    <th class="text-center" width="120px">จำนวน (คน)</th>
                    <th class="text-center" colspan="3">เบี้ยประชุม / ค่าอาหาร</th>
                    <th class="text-center" width="175px">ราคา (บาท)</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>1. เบี้ยประชุมคณะกว./กว.รายสาขา</td>
                <td>
                    <input type="text"    id="numpeople_g2" class="input_number form-control text-right required" placeholder="จำนวน (คน)" required    />
               </td>
               <td>
                   <input type="text"  id="allowances_referee_g2" class="amount form-control text-right required"  placeholder="เบี้ยประชุม(กรรมการ)" required    />
               </td>
               <td>
                   <input type="text"     id="allowances_persident_g2" class="amount form-control text-right required"  placeholder="เบี้ยประชุม(ประธาน)" required     />
               </td>
               <td>&nbsp;</td>
               <td>
                     <input type="text"  class="form-control text-right"  id="sum_g2"    readonly>
               </td>
            </tr>

            <tr>
                <td>2. ผู้เข้าร่วมประชุมทั้งหมด (คน)</td>
                <td>
                      <input type="text"    id="numpeople_attendees2" class="input_number form-control text-right required" placeholder="จำนวน (คน)" required     />
                </td>
                <td>
                    <input type="text"     id="food_morning_attendees2"  class="amount form-control text-right required" placeholder="ราคาอาหารว่าง(ช่วงเช้า)"  required    />
                </td>
                <td>
                    <input type="text"    id="food_noon_attendees2" class=" amount form-control text-right required"  placeholder="ราคาอาหาร(กลางวัน)"   required    />
                </td>
                <td>
                    <input type="text"   id="food_afternoon_attendees2" class="amount form-control text-right required" placeholder="ราคาอาหารว่าง(ช่วงบ่าย)"  required     />
                </td>
                <td>
                    <input type="text" class="form-control text-right" id="sum_attendees2"   readonly>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-center">รวม (บาท)</td>
                <td>
                    <input type="text" class="form-control text-right" id="sum2"  readonly>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
<input type="hidden"   id="result_id" >
<div class="row">
    <div class="col-md-12 form-group ">
        <button class="btn btn-primary pull-right" type="button"   id="btn_result">
            <i class="fa fa-plus"></i> เพิ่ม
        </button>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!--แสดงตาราง -->
        <table id="tb_result" class="table table-bordered">
            <tr class="info">
                <th>No.</th>
                <th>กิจกรรม</th>
                <th>ปี</th>
                <th>ไตรมาส</th>
                <th>วันที่ทำกิจกรรม</th>
                <th>เบี้ยประชุม</th>
                <th>ค่าอาหารว่าง</th>
                <th>ลบ</th>
                <th>แก้ไข</th>
            </tr>
            <tbody  id="tbody_result">
                @if (isset($set_standard->set_standard_result) && count($set_standard->set_standard_result) > 0)    
                @foreach ($set_standard->set_standard_result as $key => $result)
                <tr>
                    <td class="text-center">{{ ($key+1)}}</td>
                    <td>{{  !empty($result->status_operation->title) ?  $result->status_operation->title : '' }}</td>
                    <td>{{  !empty($result->year) ?  $result->year : '' }}</td>
                    <td>{{  !empty($result->strQuarter()) ?  $result->strQuarter() : '' }}</td>
                    <td>{{  !empty($result->startdate) && !empty($result->enddate) ?  HP::DateThai($result->startdate).' - '.HP::DateThai($result->enddate) : '' }}</td>
                    <td  class="text-right">
                        {{  !empty($result->sum_g) ?  number_format($result->sum_g,2)  : '' }}
                         <input type="hidden"   class="sum_g2"   value="{{    !empty($result->sum_g) ?  number_format($result->sum_g,2)  : '0.00'  }}">
                    </td>
                    <td  class="text-right">
                        {{  !empty($result->sum_attendees) ?   number_format($result->sum_attendees,2)    : '' }}
                        <input type="hidden"   class="sum_attendees2"   value="{{   !empty($result->sum_attendees) ?  number_format($result->sum_attendees,2)  : '0.00'  }}">
                    </td>
                    <td class="text-center">
                        <button title="ลบ" type="button"     class="btn btn-light deleteresult"  data-id="{{ $result->id}}">  <i class="fa fa-trash-o text-danger"></i>    </button>
                    </td class="text-center">
                    <td>
                        <button title="แก้ไข" type="button" class="btn  btn-light editresult"  data-id="{{ $result->id}}">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>
                    </td>
                </tr>
                @endforeach 
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" align="right">รวม</td>
                <td class="text-right someTotalClass2"> </td>
                <td class="text-right someTotalPrice2"> </td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5" align="right">รวมทั้งสิ้น</td>
                <td colspan="4"  align="center"   class="sumTotal2"></td>
            </tr>
            </tfoot>
        </table>

    </div>
</div>

  {!! Form::close() !!}

  @push('js')
    
<script src="{{ asset('js/function.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function() {

        ResetTableResult();
        ResultSum();

        $("#numpeople_g2,#allowances_referee_g2,#allowances_persident_g2,#numpeople_attendees2,#food_morning_attendees2,#food_noon_attendees2,#food_afternoon_attendees2").keyup(function(){
                var numpeople_g2                 =      $('#numpeople_g2').val();
                var allowances_referee_g2        =      $('#allowances_referee_g2').val();
                var allowances_persident_g2      =      $('#allowances_persident_g2').val();

               if(numpeople_g2 != "" && allowances_referee_g2 != "" && allowances_persident_g2 != "" ){
                const n1 =  numpeople_g2  *   parseFloat(RemoveCommas(allowances_referee_g2)) ;
                const n2 =  parseFloat(RemoveCommas(allowances_persident_g2))   -   parseFloat(RemoveCommas(allowances_referee_g2)) ;
                const number = n1 + n2;
                const  sum_g2 =  (Math.round(number * 100) / 100); 
                   $('#sum_g2').val(addCommas(sum_g2, 2) );
               }else{
                   $('#sum_g2').val('0');
               }

                var numpeople_attendees2                 =  $('#numpeople_attendees2').val();
                var food_morning_attendees2              =  $('#food_morning_attendees2').val();
                var food_noon_attendees2                 =  $('#food_noon_attendees2').val();
                var food_afternoon_attendees2            =  $('#food_afternoon_attendees2').val();


                if(numpeople_attendees2 != "" && food_morning_attendees2 != "" && food_noon_attendees2 != "" && food_afternoon_attendees2 != "" ){

                const totalBreak =    ((parseFloat(RemoveCommas(food_morning_attendees2))   +    parseFloat(RemoveCommas(food_noon_attendees2) ))   +    parseFloat(RemoveCommas(food_afternoon_attendees2)))  ;
       
                const number    =  numpeople_attendees2 *   totalBreak  ;
                const  sum_attendees2 =  (Math.round(number * 100) / 100); 
                   $('#sum_attendees2').val(addCommas(sum_attendees2, 2) );
               }else{
                   $('#sum_attendees2').val('0');
               }

               if($('#sum_g2').val() != "" && $('#sum_g2').val() != "0" && $('#sum_attendees2').val() != "" && $('#sum_attendees2').val() != "0"){
                const sum_g2                 =   RemoveCommas($('#sum_g2').val())  ;
                const sum_attendees2         =   RemoveCommas($('#sum_attendees2').val())  ;
                const  number = (sum_g2 + sum_attendees2);
                const  sum2 = (Math.round(number * 100) / 100);
                 $('#sum2').val(addCommas(sum2, 2) );
               }else{
                 $('#sum2').val('0');
               }
        });


        $("#btn_result").click(function(){
                const statusOperation_id2 = $('#statusOperation_id2').val();
                const statusOperation = $('#statusOperation_id2 :selected').text();

                const appointName_id2 = $('#appointName_id2').val();
                const meetingNo = $('#meetingNo2').val();

                const year = $('#year2').val();
                const year2 = $('#year2 :selected').text();

                const startdate = $('#startdate2').val();
                const enddate = $('#enddate2').val();

                var numpeople_g                 =      $('#numpeople_g2').val();
                var allowances_referee_g        =      $('#allowances_referee_g2').val();
                var allowances_persident_g      =      $('#allowances_persident_g2').val();

                
                var numpeople_attendees         =  $('#numpeople_attendees2').val();
                var food_morning_attendees      =  $('#food_morning_attendees2').val();
                var food_noon_attendees         =  $('#food_noon_attendees2').val();
                var food_afternoon_attendees    =  $('#food_afternoon_attendees2').val();

                var sum_g                     =  $('#sum_g2').val();
                var sum_attendees             =  $('#sum_attendees2').val();
                var sum                       =  $('#sum2').val();
     
            if (checkNone(statusOperation_id2)  && checkNone(year) &&  checkNone(startdate)  && checkNone(enddate)  &&( checkNone(sum) && sum != '0' )    ) {
 
                  const url = "{{ url('tis/set_standard/update-results/'.$set_standard->id) }}";
 
                  $.ajax({
                        url: url,
                        type: 'get',
                        dataType: 'json',
                        cache: false,
                        data: { 
                                '_token': "{{ csrf_token() }}",
                                'result_id' : $('#result_id').val(),
                                'statusOperation_id' :statusOperation_id2,
                                'appointName_id' :appointName_id2,
                                'meetingNo' :meetingNo,
                                'year' :year,
                                'startdate' :startdate,
                                'enddate' :enddate,
                                'numpeople_g' :numpeople_g,
                                'allowances_referee_g' : allowances_referee_g,
                                'allowances_persident_g' : allowances_persident_g,
                                'numpeople_attendees' : numpeople_attendees,
                                'food_morning_attendees' : food_morning_attendees,
                                'food_noon_attendees' : food_noon_attendees,
                                'food_afternoon_attendees' : food_afternoon_attendees,
                                'sum_g' : sum_g,
                                'sum_attendees' : sum_attendees,
                                'sum' : sum
                            },
                        success: function (datas) {
                            if(datas.set_standard_results.length > 0){ 
                                $('#tbody_result').html('');
                                $.each(datas.set_standard_results,function (index,value) {

                                $('#tbody_result').append('<tr>' +
                                    '<td class="text-center">1</td>' +
                                    '<td>'+value.operation+'</td>' +
                                    '<td>'+value.year+'</td>' +
                                    '<td>'+value.quarter+'</td>' +
                                    '<td>'+ value.startdates+' - '+ value.enddates +'</td>' +
                                    '<td class="text-right">'+value.sum_gs+' <input type="hidden"   class="sum_g2"   value="'+value.sum_gs+'"> </td>' +    
                                    '<td class="text-right">'+value.sum_attendeess+' <input type="hidden"   class="sum_attendees2"   value="'+value.sum_attendeess+'"></td>' +    
                                    '<td class="text-center">' +
                                        '<button title="ลบ" type="button"     class="btn btn-light deleteresult"  data-id="'+value.id+'">  <i class="fa fa-trash-o text-danger"></i>    </button>' +
                                    '</td>' +
                                    '<td class="text-center">' +
                                        '<button title="แก้ไข" type="button" class="btn   btn-light editresult" data-id="'+value.id+'">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>' +
                                    '</td>' +
                                    '</tr>');
                              });
                               ResetTableResult();
                                ResultSum();
                                InputResetNull();
 
                            }
                            
                        }
                    });

            }else {
                
                Swal.fire({
                    position: 'center',
                    title: 'กรุณาเลือกให้ครบ !',
                    showConfirmButton: false,
                    timer: 2000
                });
    
            }  
        });


        $('body').on('click', '.editresult', function(event) {
            var id = $(this).data('id');
            const url = "{{ url('tis/set_standard/set_standard_result') }}";
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                cache: false,
                data: { 
                        '_token': "{{ csrf_token() }}",
                        'id' : id
                    },
                success: function (datas) {
                  if(datas.set_standard_result){
                     var result = datas.set_standard_result;
                        $('#statusOperation_id2').val(result.statusOperation_id).select2();
                        $('#appointName_id2').val(result.appointName_id).select2();
                        $('#meetingNo2').val(result.meetingNo);
                        $('#year2').val(result.year).select2();
                        $('#startdate2').val(result.startdates);
                        $('#enddate2').val(result.enddates);
                        
                        $('#result_id').val(result.id);

                        $('#numpeople_g2').val(result.numpeople_g);
                        $('#allowances_referee_g2').val(result.allowances_referee_g);
                        $('#allowances_persident_g2').val(result.allowances_persident_g);
                        $('#numpeople_attendees2').val(result.numpeople_attendees);
                        $('#food_morning_attendees2').val(result.food_morning_attendees);
                        $('#food_noon_attendees2').val(result.food_noon_attendees);
                        $('#food_afternoon_attendees2').val(result.food_afternoon_attendees);
                        $('#sum_g2').val(result.sum_g);
                        $('#sum_attendees2').val(result.sum_attendees);
                        $('#sum2').val(result.sum);
                      
                  }
                }
            });
 
        });

        $('body').on('click', '.deleteresult', function(event) {
            var id = $(this).data('id');
            const url = "{{ url('tis/set_standard/delete_set_standard_result') }}";
            Swal.fire({
                icon: 'error',
                title: 'ยื่นยันการลบ !',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'get',
                                dataType: 'json',
                                cache: false,
                                data: { 
                                        '_token': "{{ csrf_token() }}",
                                        'id' : id,
                                        'standard_id' :  "{{ $set_standard->id }}"
                                    },
                                success: function (datas) {
                                          $('#tbody_result').html('');
                                    if(datas.delete_set_standard_result.length > 0){ 
                                               
                                                $.each(datas.delete_set_standard_result,function (index,value) {
                                                $('#tbody_result').append('<tr>' +
                                                    '<td class="text-center">1</td>' +
                                                    '<td>'+value.operation+'</td>' +
                                                    '<td>'+value.year+'</td>' +
                                                    '<td>'+value.quarter+'</td>' +
                                                    '<td>'+ value.startdates+' - '+ value.enddates +'</td>' +
                                                    '<td class="text-right">'+value.sum_gs+' <input type="hidden"   class="sum_g2"   value="'+value.sum_gs+'"> </td>' +    
                                                    '<td class="text-right">'+value.sum_attendeess+' <input type="hidden"   class="sum_attendees2"   value="'+value.sum_attendeess+'"></td>' +    
                                                    '<td class="text-center">' +
                                                        '<button title="ลบ" type="button"     class="btn btn-light deleteplan"  data-id="'+value.id+'">  <i class="fa fa-trash-o text-danger"></i>    </button>' +
                                                    '</td>' +
                                                    '<td class="text-center">' +
                                                        '<button title="แก้ไข" type="button" class="btn   btn-light editplan" data-id="'+value.id+'">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>' +
                                                    '</td>' +
                                                    '</tr>');
                                            });
                                        ResetTableResult();
                                        ResultSum();
                                        // $(this).parent().parent().remove();
                                    }
                                }
                            });
                    }
                });

    });

    });
    function ResetTableResult(){
      var rows = $('#tbody_result').children(); //แถวทั้งหมด
         rows.each(function(index, el) {
             //เลขรัน
               $(el).children().first().html(index+1);
          });
     }

         //คำนวณผลรวม
    function ResultSum(){
            var sum_g = 0;
            $('.sum_g2').each(function(index, input) {
                var amount = RemoveCommas($(input).val());
                if(isInt(amount) || isFloat(amount)){
                    sum_g += parseFloat(amount);
                }
            });
            $('.someTotalClass2').html(addCommas(sum_g, 2));

            var sum_attendees = 0;
            $('.sum_attendees2').each(function(index, input) {
                var amount = RemoveCommas($(input).val());
                if(isInt(amount) || isFloat(amount)){
                    sum_attendees += parseFloat(amount);
                }
            });
            $('.someTotalPrice2').html(addCommas(sum_attendees, 2));

            var  sumTotal =  (sum_attendees + sum_g);
            $('.sumTotal2').html(addCommas(sumTotal, 2));

        }

        //  input nulll
        function InputResetNull(){
            $('#statusOperation_id2').val('').select2();
            $('#appointName_id2').val('').select2();
            $('#meetingNo2').val('');
            $('#year2').val('').select2();
            $('#startdate2').val('');
            $('#enddate2').val('');
            
            $('#result_id').val('');

            $('#numpeople_g2').val('');
            $('#allowances_referee_g2').val('');
            $('#allowances_persident_g2').val('');
            $('#numpeople_attendees2').val('');
            $('#food_morning_attendees2').val('');
            $('#food_noon_attendees2').val('');
            $('#food_afternoon_attendees2').val('');
            $('#sum_g2').val('');
            $('#sum_attendees2').val('');
            $('#sum2').val('');

        }

    
    </script>
  @endpush
  