

        {!! Form::model($set_standard, [
            'method' => 'PATCH',
            'url' => ['/tis/set_standard', $set_standard->id],
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'cost_form'
        ]) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('statusOperation_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('statusOperation_id', 'กิจกรรม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('statusOperation_id', 
                 App\Models\Basic\StatusOperation::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
              'id' => 'statusOperation_id1',
              'placeholder'=>'-เลือกกิจกรรม-', 
              'required' => true]); !!}
                {!! $errors->first('statusOperation_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('appointName_id') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('appointName_id', 'ชื่อคณะประชุม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('appointName_id', 
                 App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->Where('state',1)->Where('title','!=','')->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), 
               null,
              ['class' => 'form-control',
               'id' => 'appointName_id',
              'placeholder'=>'-เลือกใชื่อคณะประชุม-', 
              'required' => true]); !!}
                {!! $errors->first('appointName_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('meetingNo') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('meetingNo', 'ครั้งที่ประชุม'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                 {!! Form::text('meetingNo', null, ['class' => 'form-control', 'required' =>  false]) !!}
                {!! $errors->first('meetingNo', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('year') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('year', 'ปีงบประมาณ'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                {!! Form::select('year', 
                 HP::Years(), 
               null,
              ['class' => 'form-control',
               'id' => 'year1',
              'placeholder'=>'-เลือกปีงบประมาณ-', 
              'required' => true]); !!}
                {!! $errors->first('year', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('startdate') ? 'has-error' : ''}}">
            {!! HTML::decode(Form::label('startdate', 'วัน'.' :', ['class' => 'col-md-3 control-label'])) !!}
             <div class="col-md-9">
                <div class="input-daterange input-group date-range">
                    {!! Form::text('startdate', null, ['id'=>'startdate1','class' => 'form-control date', 'required' => true]) !!}
                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                    {!! Form::text('enddate', null, ['id'=>'enddate1','class' => 'form-control date', 'required' => true]) !!}
                  </div>
                {!! $errors->first('startdate', '<p class="help-block">:message</p>') !!}
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
                     <input type="text"  name="numpeople_g"   id="numpeople_g" class="input_number form-control text-right required" placeholder="จำนวน (คน)" required    />
                </td>
                <td>
                    <input type="text"  name="allowances_referee_g" id="allowances_referee_g" class="amount form-control text-right required"  placeholder="เบี้ยประชุม(กรรมการ)" required    />
                </td>
                <td>
                    <input type="text"  name="allowances_persident_g"  id="allowances_persident_g" class="amount form-control text-right required"  placeholder="เบี้ยประชุม(ประธาน)" required     />
                </td>
                <td>&nbsp;</td>
                <td>
                      <input type="text" class="form-control text-right" id="sum_g" name="sum_g"  readonly>
                </td>
            </tr>

            <tr>
                <td>2. ผู้เข้าร่วมประชุมทั้งหมด (คน)</td>
                <td>
                      <input type="text"  name="numpeople_attendees" id="numpeople_attendees" class="input_number form-control text-right required" placeholder="จำนวน (คน)" required     />
                </td>
                <td>
                    <input type="text"  name="food_morning_attendees"   id="food_morning_attendees"  class="amount form-control text-right required" placeholder="ราคาอาหารว่าง(ช่วงเช้า)"  required    />
                </td>
                <td>
                    <input type="text"  name="food_noon_attendees"  id="food_noon_attendees" class=" amount form-control text-right required"  placeholder="ราคาอาหาร(กลางวัน)"   required    />
                </td>
                <td>
                    <input type="text"  name="food_afternoon_attendees"  id="food_afternoon_attendees" class="amount form-control text-right required" placeholder="ราคาอาหารว่าง(ช่วงบ่าย)"  required     />
                </td>
                <td>
                    <input type="text" class="form-control text-right" id="sum_attendees" name="sum_attendees"  readonly>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-center">รวม (บาท)</td>
                <td>
                    <input type="text" class="form-control text-right" id="sum" name="sum"  readonly>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
<input type="hidden"   id="plan_id" >

<div class="row">
    <div class="col-md-12 form-group ">
        <button class="btn btn-primary pull-right" type="button" id="btn_plan">
            <i class="fa fa-plus"></i> เพิ่ม
        </button>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!--แสดงตาราง -->
        <table id="tb_plan" class="table table-bordered">
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
             <tbody  id="tbody_plan">
                    @if (isset($set_standard->set_standard_plan) && count($set_standard->set_standard_plan) > 0)    
                        @foreach ($set_standard->set_standard_plan as $key => $plan)
                        <tr>
                            <td class="text-center">{{ ($key+1)}}</td>
                            <td>{{  !empty($plan->status_operation->title) ?  $plan->status_operation->title : '' }}</td>
                            <td>{{  !empty($plan->year) ?  $plan->year : '' }}</td>
                            <td>{{  !empty($plan->strQuarter()) ?  $plan->strQuarter() : '' }}</td>
                            <td>{{  !empty($plan->startdate) && !empty($plan->enddate) ?  HP::DateThai($plan->startdate).' - '.HP::DateThai($plan->enddate) : '' }}</td>
                            <td  class="text-right">
                                {{  !empty($plan->sum_g) ?  number_format($plan->sum_g,2)  : '' }}
                                 <input type="hidden"   class="sum_g"   value="{{    !empty($plan->sum_g) ?  number_format($plan->sum_g,2)  : '0.00'  }}">
                            </td>
                            <td  class="text-right">
                                {{  !empty($plan->sum_attendees) ?   number_format($plan->sum_attendees,2)    : '' }}
                                <input type="hidden"   class="sum_attendees"   value="{{   !empty($plan->sum_attendees) ?  number_format($plan->sum_attendees,2)  : '0.00'  }}">
                            </td>
                            <td class="text-center">
                                <button title="ลบ" type="button"     class="btn btn-light deleteplan"  data-id="{{ $plan->id}}">  <i class="fa fa-trash-o text-danger"></i>    </button>
                            </td class="text-center">
                            <td>
                                <button title="แก้ไข" type="button" class="btn  btn-light editplan"  data-id="{{ $plan->id}}">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>
                            </td>
                        </tr>
                        @endforeach 
                    @endif
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" align="right">รวม</td>
                <td class="text-right someTotalClass"> </td>
                <td class="text-right someTotalPrice"> </td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5" align="right">รวมทั้งสิ้น</td>
                <td colspan="4"  align="center" class="sumTotal"></td>
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
             ResetTablePlan();
             PlanSum();
 

        
            $("#numpeople_g,#allowances_referee_g,#allowances_persident_g,#numpeople_attendees,#food_morning_attendees,#food_noon_attendees,#food_afternoon_attendees").keyup(function(){
                var numpeople_g                 =      $('#numpeople_g').val();
                var allowances_referee_g        =      $('#allowances_referee_g').val();
                var allowances_persident_g      =      $('#allowances_persident_g').val();

               if(numpeople_g != "" && allowances_referee_g != "" && allowances_persident_g != "" ){
                const n1 =  numpeople_g  *   parseFloat(RemoveCommas(allowances_referee_g)) ;
                const n2 =  parseFloat(RemoveCommas(allowances_persident_g))   -   parseFloat(RemoveCommas(allowances_referee_g)) ;
                const number = n1 + n2;
                const  sum_g =  (Math.round(number * 100) / 100); 
                   $('#sum_g').val(addCommas(sum_g, 2) );
               }else{
                   $('#sum_g').val('0');
               }

                var numpeople_attendees                 =  $('#numpeople_attendees').val();
                var food_morning_attendees              =  $('#food_morning_attendees').val();
                var food_noon_attendees                 =  $('#food_noon_attendees').val();
                var food_afternoon_attendees            =  $('#food_afternoon_attendees').val();


                if(numpeople_attendees != "" && food_morning_attendees != "" && food_noon_attendees != "" && food_afternoon_attendees != "" ){

                const totalBreak =    ((parseFloat(RemoveCommas(food_morning_attendees))   +    parseFloat(RemoveCommas(food_noon_attendees) ))   +    parseFloat(RemoveCommas(food_afternoon_attendees)))  ;
                console.log(totalBreak);
                const number    =  numpeople_attendees *   totalBreak  ;
                const  sum_attendees =  (Math.round(number * 100) / 100); 
                   $('#sum_attendees').val(addCommas(sum_attendees, 2) );
               }else{
                   $('#sum_attendees').val('0');
               }

               if($('#sum_g').val() != "" && $('#sum_g').val() != "0" && $('#sum_attendees').val() != "" && $('#sum_attendees').val() != "0"){
                const sum_g                 =   RemoveCommas($('#sum_g').val())  ;
                const sum_attendees         =   RemoveCommas($('#sum_attendees').val())  ;
                const  number = (sum_g + sum_attendees);
                const  sum = (Math.round(number * 100) / 100);
                 $('#sum').val(addCommas(sum, 2) );
               }else{
                 $('#sum').val('0');
               }
            });

        $("#btn_plan").click(function(){
                const statusOperation_id1 = $('#statusOperation_id1').val();
                const statusOperation = $('#statusOperation_id1 :selected').text();

                const appointName_id = $('#appointName_id').val();
                const meetingNo = $('#meetingNo').val();

                const year1 = $('#year1').val();
                const year = $('#year1 :selected').text();

                const startdate = $('#startdate1').val();
                const enddate = $('#enddate1').val();

                var numpeople_g                 =      $('#numpeople_g').val();
                var allowances_referee_g        =      $('#allowances_referee_g').val();
                var allowances_persident_g      =      $('#allowances_persident_g').val();

                
                var numpeople_attendees         =  $('#numpeople_attendees').val();
                var food_morning_attendees      =  $('#food_morning_attendees').val();
                var food_noon_attendees         =  $('#food_noon_attendees').val();
                var food_afternoon_attendees    =  $('#food_afternoon_attendees').val();

                var sum_g                     =  $('#sum_g').val();
                var sum_attendees             =  $('#sum_attendees').val();
                var sum                       =  $('#sum').val();

            if (checkNone(statusOperation_id1)  && checkNone(year1) &&  checkNone(startdate)  && checkNone(enddate)  &&( checkNone(sum) && sum != '0' )    ) {

                  const url = "{{ url('tis/set_standard/update-plans/'.$set_standard->id) }}";
                  $.ajax({
                        url: url,
                        type: 'get',
                        dataType: 'json',
                        cache: false,
                        data: { 
                                '_token': "{{ csrf_token() }}",
                                'plan_id' : $('#plan_id').val(),
                                'statusOperation_id' :statusOperation_id1,
                                'appointName_id' :appointName_id,
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
                            if(datas.set_standard_plans.length > 0){ 
                                $('#tbody_plan').html('');
                                $.each(datas.set_standard_plans,function (index,value) {
                                    // var set_standard = datas.set_standard;
                                $('#tbody_plan').append('<tr>' +
                                    '<td class="text-center">1</td>' +
                                    '<td>'+value.operation+'</td>' +
                                    '<td>'+value.year+'</td>' +
                                    '<td>'+value.quarter+'</td>' +
                                    '<td>'+ value.startdates+' - '+ value.enddates +'</td>' +
                                    '<td class="text-right">'+value.sum_gs+' <input type="hidden"   class="sum_g"   value="'+value.sum_gs+'"> </td>' +    
                                    '<td class="text-right">'+value.sum_attendeess+' <input type="hidden"   class="sum_attendees"   value="'+value.sum_attendeess+'"></td>' +    
                                    '<td class="text-center">' +
                                        '<button title="ลบ" type="button"     class="btn btn-light deleteplan"  data-id="'+value.id+'">  <i class="fa fa-trash-o text-danger"></i>    </button>' +
                                    '</td>' +
                                    '<td class="text-center">' +
                                        '<button title="แก้ไข" type="button" class="btn   btn-light editplan" data-id="'+value.id+'">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>' +
                                    '</td>' +
                                    '</tr>');
                              });
                                ResetTablePlan();
                                PlanSum();
                                InputPlanNull();
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

        $('body').on('click', '.editplan', function(event) {
            var id = $(this).data('id');
 
            const url = "{{ url('tis/set_standard/set_standard_plans') }}";
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
                    if(datas.set_standard_plans){
                     var plans = datas.set_standard_plans;
                        $('#statusOperation_id1').val(plans.statusOperation_id).select2();
                        $('#appointName_id').val(plans.appointName_id).select2();
                        $('#meetingNo').val(plans.meetingNo);
                        $('#year1').val(plans.year).select2();
                        $('#startdate1').val(plans.startdates);
                        $('#enddate1').val(plans.enddates);
                        
                        $('#plan_id').val(plans.id);

                        $('#numpeople_g').val(plans.numpeople_g);
                        $('#allowances_referee_g').val(plans.allowances_referee_g);
                        $('#allowances_persident_g').val(plans.allowances_persident_g);
                        $('#numpeople_attendees').val(plans.numpeople_attendees);
                        $('#food_morning_attendees').val(plans.food_morning_attendees);
                        $('#food_noon_attendees').val(plans.food_noon_attendees);
                        $('#food_afternoon_attendees').val(plans.food_afternoon_attendees);
                        $('#sum_g').val(plans.sum_g);
                        $('#sum_attendees').val(plans.sum_attendees);
                        $('#sum').val(plans.sum);
                    }
                 
                }
            });
            // $(this).parent().parent().remove();
            // ResetTablePlan();
            // PlanSum();
        });
            

    $('body').on('click', '.deleteplan', function(event) {
     
            var id = $(this).data('id');
            const url = "{{ url('tis/set_standard/delete_set_standard_plans') }}";
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
                                            $('#tbody_plan').html('');
                                    if(datas.set_standard_plans.length > 0){ 
                                                
                                                $.each(datas.set_standard_plans,function (index,value) {
                                                    console.log(value);
                                                $('#tbody_plan').append('<tr>' +
                                                    '<td class="text-center">1</td>' +
                                                    '<td>'+value.operation+'</td>' +
                                                    '<td>'+value.year+'</td>' +
                                                    '<td>'+value.quarter+'</td>' +
                                                    '<td>'+ value.startdates+' - '+ value.enddates +'</td>' +
                                                    '<td class="text-right">'+value.sum_gs+' <input type="hidden"   class="sum_g"   value="'+value.sum_gs+'"> </td>' +    
                                                    '<td class="text-right">'+value.sum_attendeess+' <input type="hidden"   class="sum_attendees"   value="'+value.sum_attendeess+'"></td>' +    
                                                    '<td class="text-center">' +
                                                        '<button title="ลบ" type="button"     class="btn btn-light deleteplan"  data-id="'+value.id+'">  <i class="fa fa-trash-o text-danger"></i>    </button>' +
                                                    '</td>' +
                                                    '<td class="text-center">' +
                                                        '<button title="แก้ไข" type="button" class="btn   btn-light editplan" data-id="'+value.id+'">   <i class="fa fa-pencil-square-o text-danger"></i>  </button>' +
                                                    '</td>' +
                                                    '</tr>');
                                            });
                                        // $(this).parent().parent().remove();
                                        ResetTablePlan();
                                        PlanSum();
                                      
                                    }
                                }
                            });
                    }
                });

    });

});

    
    function ResetTablePlan(){
      var rows = $('#tbody_plan').children(); //แถวทั้งหมด
         rows.each(function(index, el) {
             //เลขรัน
               $(el).children().first().html(index+1);
          });
     }

         //คำนวณผลรวม
    function PlanSum(){
            var sum_g = 0;
            $('.sum_g').each(function(index, input) {
                var amount = RemoveCommas($(input).val());
                if(isInt(amount) || isFloat(amount)){
                    sum_g += parseFloat(amount);
                }
            });
            $('.someTotalClass').html(addCommas(sum_g, 2));

            var sum_attendees = 0;
            $('.sum_attendees').each(function(index, input) {
                var amount = RemoveCommas($(input).val());
                if(isInt(amount) || isFloat(amount)){
                    sum_attendees += parseFloat(amount);
                }
            });
            $('.someTotalPrice').html(addCommas(sum_attendees, 2));

            var  sumTotal =  (sum_attendees + sum_g);
            $('.sumTotal').html(addCommas(sumTotal, 2));

        }

        //  input nulll
        function InputPlanNull(){
            $('#statusOperation_id1').val('').select2();
            $('#appointName_id').val('').select2();
            $('#meetingNo').val('');
            $('#year1').val('').select2();
            $('#startdate1').val('');
            $('#enddate1').val('');
            
            $('#plan_id').val('');

            $('#numpeople_g').val('');
            $('#allowances_referee_g').val('');
            $('#allowances_persident_g').val('');
            $('#numpeople_attendees').val('');
            $('#food_morning_attendees').val('');
            $('#food_noon_attendees').val('');
            $('#food_afternoon_attendees').val('');
            $('#sum_g').val('');
            $('#sum_attendees').val('');
            $('#sum').val('');

        }

  </script>
  @endpush
  