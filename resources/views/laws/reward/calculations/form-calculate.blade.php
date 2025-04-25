<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน</h5>
            </legend>
<div class="form-group">
    <div class="col-md-12">
        <div class="pull-right">
            <label class="control-label col-md-12 text-right font-medium-6">
                แก้ไขสัดส่วนเงินคำนวณ :
                {!! Form::checkbox('edit_income', '1', !empty($cases->law_reward_to->edit_income) && $cases->law_reward_to->edit_income == '1' ?  true : false  , ['class' => 'js-switch','id'=>'edit_income', 'data-color'=>'#13dafe']) !!}
            </label>
        </div>
    </div> 
</div>
<div class="form-group">
      <div class="col-md-12">
           <div class="table">
                 <table class="table   table-bordered"  >
                     <thead>
                          <tr>
                              <th class="text-center text-top" rowspan="2" width="2%">ลำดับ</th>
                              <th class="text-center text-top" rowspan="2" width="18%">รายการ</th>
                              <th class="text-center text-top" rowspan="2" width="10%">ยอดยกมา</th>
                              <th class="text-center text-top" colspan="3" width="40%">คำนวณเงิน</th>
                              <th class="text-center text-top" rowspan="2"  width="10%" >ส่วนต่าง</th>
                              <th class="text-center text-top" rowspan="2" width="10%" >รวม</th>
                              <th class="text-center text-top" rowspan="2" width="10%" >หมายเหตุ</th>
                          </tr>
                          <tr>
                              <th  class="text-center text-top" width="10%">
                                       {!! Form::select('cal_type1',
                                        ['1'=>'สัดส่วน','2'=>'จำนวนเงิน(ระบุเอง)'],
                                        (!empty($cases->law_reward_to->law_calculation1_to->cal_type) ?$cases->law_reward_to->law_calculation1_to->cal_type : '1'),
                                       ['class' => 'form-control input-xs', 
                                       'id' => 'cal_type1',
                                       'required' => true,'style'=>'width:100px;']) 
                                         !!}   
                              </th>
                              <th  class="text-center text-top"  width="10%">
                                       จำนวนเงิน
                              </th>
                              <th  class="text-center text-top"  width="10%">
                                       เพดาน
                              </th>
                          </tr>
                       </thead>
                       <tbody id="table_tbody_calculate1">
                            <tr>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6">
                                   เงินค่าปรับ
                              </td>
                               <td  class=" text-top text-right  font-medium-6">
                                     {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : null !!}
                               </td>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6"></td>
                               <td  class=" text-top font-medium-6"></td>
                            </tr>  
                            @if (!empty($cases->law_reward_to->law_calculation1_many) && count($cases->law_reward_to->law_calculation1_many) > 0)
                                    @foreach ($cases->law_reward_to->law_calculation1_many as  $key => $item)
                                        <tr>
                                            <td  class=" text-top text-center font-medium-6">
                                                {{ $key +1}}
                                            </td>
                                            <td  class=" text-top font-medium-6">
                                                {{ $item->division_name  ?? null}}
                                            </td>
                                            <td  class=" text-top">
                                            
                                            </td> 
                                            <td  class="text-top">
                                                <div class=" input-group " style='background-color:#ffffff;'>
                                                    {!! Form::text('calculation1[division][]',
                                                    (!empty($item->division) ?  $item->division :  '40' ),
                                                    ['class' => 'form-control input-xs   text-center division input_required reward', 'required' => false]) !!}
                                                    <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                                </div> 
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation1[amount][]',
                                                        (!empty($item->amount) ? number_format($item->amount,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs amount input_amount text-right input_required reward', 'required' => false]) !!}
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation1[max][]',
                                                    (!empty($item->max) ?  $item->max : '0.00' ),
                                                    ['class' => 'form-control input-xs max input_amount text-right input_required', 'required' => false , 'readonly' => ($key == 0 ? true: false) ]) !!}
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation1[difference][]',
                                                        (!empty($item->difference) ?  number_format($item->difference,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs input_amount difference  text-right input_required', 'required' => false]) !!}
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation1[total][]',
                                                    (!empty($item->total) ?  number_format($item->total,2) :  '0.00' ),
                                                    ['class' => 'form-control input-xs input_amount total text-right  ', 'readonly' => true]) !!}
                                            </td>
                                            <td  class="text-top">
                                                {!! Form::textarea('calculation1[remark][]', !empty($item->remark) ? $item->remark : null  , ['class' => 'form-control', 'rows'=>'1'  ]); !!}
                                                <input type="hidden"  name="calculation1[id][]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                <input type="hidden"  name="calculation1[basic_division_category_id][]"  value="{{ !empty($item->basic_division_category_id) ? $item->basic_division_category_id : null }}">
                                            </td>
                                        </tr>     
                                    @endforeach                  
                            @else
                                @if (count($categorys) > 0)
                                @foreach ($categorys as  $key => $item)
                                    <tr>
                                        <td  class=" text-top text-center font-medium-6">
                                            {{ $key +1}}
                                        </td>
                                        <td  class=" text-top font-medium-6">
                                            {{ $item->title  ?? null}}
                                        </td>
                                        <td  class=" text-top">
                                        
                                        </td> 
                                        <td  class="text-top">
                                            <div class=" input-group " style='background-color:#ffffff;'>
                                                {!! Form::text('calculation1[division][]',
                                                (!empty($item->division) ?  $item->division : ( !empty($cases->division) ? $cases->division : '40' )),
                                                ['class' => 'form-control input-xs   text-center division input_required reward', 'required' => false]) !!}
                                                <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                            </div> 
                                        </td>
                                        <td  class="text-top">
                                                {!! Form::text('calculation1[amount][]',
                                                    (!empty($item->amount) ?  $item->amount : ( !empty($cases->amount) ? number_format($cases->amount,2) : '0.00' )),
                                                ['class' => 'form-control input-xs amount input_amount text-right input_required reward', 'required' => false]) !!}
                                        </td>
                                        <td  class="text-top">
                                                {!! Form::text('calculation1[max][]',
                                                (!empty($item->max) ?  $item->max : ( !empty($cases->max) ? number_format($cases->max,2) : '0.00' )),
                                                ['class' => 'form-control input-xs max input_amount text-right input_required', 'required' => false , 'readonly' => ($key == 0 ? true: false) ]) !!}
                                        </td>
                                        <td  class="text-top">
                                                {!! Form::text('calculation1[difference][]',
                                                    (!empty($item->difference) ?  $item->difference : ( !empty($cases->difference) ? number_format($cases->difference,2) : '0.00' )),
                                                ['class' => 'form-control input-xs input_amount difference  text-right input_required', 'required' => false ]) !!}
                                        </td>
                                        <td  class="text-top">
                                                {!! Form::text('calculation1[total][]',
                                                (!empty($item->total) ?  $item->total : ( !empty($cases->total) ? number_format($cases->total,2) : '0.00' )),
                                                ['class' => 'form-control input-xs input_amount total text-right  ', 'readonly' => true]) !!}
                                        </td>
                                        <td  class="text-top">
                                            {!! Form::textarea('calculation1[remark][]', null , ['class' => 'form-control', 'rows'=>'1'  ]); !!}
                                            <input type="hidden"  name="calculation1[id][]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                            <input type="hidden"  name="calculation1[basic_division_category_id][]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                        </td>
                                    </tr>     
                                @endforeach                  
                            @endif 
                            @endif  

                      </tbody>
                      <footer>
                          <tr>
                                 <td   class="text-top text-right  font-medium-6"  colspan="2" >
                                        <b>รวม</b>
                                 </td>  
                                 <td  class=" text-top text-right  font-medium-6"> 
                                       <b> {!! !empty($cases->law_reward_to->paid_amount) ? number_format($cases->law_reward_to->paid_amount,2) : null !!}</b>
                                 </td>
                                 <td  class=" text-top text-right  font-medium-6">
                                       <b id="division_total"></b>
                                 </td>   
                                 <td  class=" text-top text-right  font-medium-6">
                                    <b id="amount_total"></b>
                                 </td>   
                                 <td  class=" text-top text-right  font-medium-6"></td>
                                 <td  class=" text-top text-right  font-medium-6"></td>
                                 <td  class=" text-top text-right  font-medium-6">
                                    <b id="total"></b>
                                 </td>   
                                 <td  class=" text-top text-right  font-medium-6"></td>
                          </tr>
                      </footer>
                 </table>
           </div>  
     </div>
</div>

<div class="form-group">
    <div class="col-md-8 ">
        <b><u>สรุป</u></b>
    </div>
    <div class="col-md-4 font-medium-6 text-right ">
        @if (!empty($cases->config_section))
           <p class="record" id="modal_record">คลิกดูอัตราเพดานเงินค่าปรับ</p>  
        @endif
    </div>
</div>

<div class="form-group" id="part1">
    <div class="col-md-12 ">
        @if (!empty($cases->law_reward_to->law_calculation1_many) && count($cases->law_reward_to->law_calculation1_many) > 0)
        @foreach ($cases->law_reward_to->law_calculation1_many as  $key => $item)
            <div class="form-group">
                {!! HTML::decode(Form::label('', $item->division_name , ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                <div class="col-md-4">
                    {!! Form::text('',null, ['class' => 'form-control categorys  text-right ',  'disabled' => true]) !!}
                </div>
            </div>
        @endforeach                  
        @else
            @if (count($categorys) > 0)
            @foreach ($categorys as  $key => $item)
                <div class="form-group">
                    {!! HTML::decode(Form::label('', $item->title , ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                    <div class="col-md-4">
                        {!! Form::text('',null, ['class' => 'form-control categorys  text-right ',  'disabled' => true]) !!}
                    </div>
                </div>
        @endforeach                  
        @endif 
        @endif  

    </div>
</div>



         </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน</h5>
            </legend>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="pull-right">
                        <label class="control-label col-md-12 text-right font-medium-6">
                            แก้ไขสัดส่วนเงินคำนวณ :
                            {!! Form::checkbox('edit_proportion', '1', !empty($cases->law_reward_to->edit_proportion) && $cases->law_reward_to->edit_proportion == '1' ?  true : false  , ['class' => 'js-switch','id'=>'edit_proportion', 'data-color'=>'#13dafe']) !!}
                        </label>
                    </div>
                </div> 
           </div>
            <div class="form-group">
                <div class="col-md-12">
                     <div class="table">
                           <table class="table   table-bordered"  >
                               <thead>
                                    <tr>
                                        <th class="text-center text-top" rowspan="2" width="2%">ลำดับ</th>
                                        <th class="text-center text-top" rowspan="2" width="18%">กลุ่มผู้มีสิทธิ์ได้รับเงินรางวัล</th>
                                        <th class="text-center text-top" rowspan="2" width="10%">ยอดยกมา</th>
                                        <th class="text-center text-top" colspan="2" width="25%">คำนวณเงิน</th>
                                        <th class="text-center text-top" colspan="2"  width="25%" >จำนวนเงินที่ได้รับ/คน</th>

                                        <th class="text-center text-top" rowspan="2" width="10%" >หมายเหตุ</th>
                                    </tr>
                                    <tr>
                                        <th  class="text-center text-top" width="10%">
                                                 {!! Form::select('cal_type2',
                                                  ['1'=>'สัดส่วน','2'=>'จำนวนเงิน(ระบุเอง)'],
                                                  (!empty($cases->law_reward_to->law_calculation2_to->cal_type) ?$cases->law_reward_to->law_calculation2_to->cal_type : '1'),
                                                 ['class' => 'form-control input-xs', 
                                                 'id' => 'cal_type2',
                                                 'required' => true,'style'=>'width:100px;']) 
                                                   !!}   
                                        </th>
                                        <th  class="text-center text-top"  width="10%">
                                             จำนวนเงิน
                                        </th>
                                        <th  class="text-center text-top"  width="10%">
                                               เฉลี่ย
                                        </th>
                                        <th  class="text-center text-top"  width="15%">
                                            จำนวนเงิน
                                       </th>
                                    </tr>
                                 </thead>
                                 <tbody id="table_tbody_calculate2">
                                      <tr>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6">
                                             เงินสินบน เงินรางวัล ค่าใช้จ่ายดำเนินงาน
                                        </td>
                                         <td  class=" text-top text-right  font-medium-6">
                                              <p id="paid_amount2"> </p>
                                         </td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                      </tr>    
                     @if (!empty($cases->law_reward_to->law_calculation2_many) && count($cases->law_reward_to->law_calculation2_many) > 0)
                            @php
                                       if($cases->whistleblower  != '0'){
                                            $divisions = ['25','25','50'];
                                        }else{
                                            $divisions = ['25','0','75'];
                                        }
                                       
                            @endphp
                        @foreach ($cases->law_reward_to->law_calculation2_many as  $key => $item)
                                        <tr>
                                            <td  class=" text-top text-center font-medium-6">
                                                {{ $key +1}}
                                            </td>
                                            <td  class=" text-top font-medium-6">
                                                {{ $item->division_type_name  ?? null}}
                                            </td>
                                            <td  class=" text-top">
                                            
                                            </td> 
                                            <td  class="text-top">
                                                <div class=" input-group " style='background-color:#ffffff;'>
                                                    {!! Form::text('calculation2[division]['.$key.']',
                                                    (!empty($item->division) ?  $item->division : $divisions[$key] ),
                                                    ['class' => 'form-control input-xs   text-center division input_required reward', 'required' => false]) !!}
                                                    <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                                </div> 
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation2[amount]['.$key.']',
                                                        (!empty($item->amount) ?  number_format($item->amount,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs amount input_amount text-right input_required reward', 'required' => false]) !!}
                                            </td>
                                            <td  class="text-top">
                                                 @if (!is_null($item->average) && $item->average > '0')
                                                   <div class=" input-group " style='background-color:#ffffff;'>
                                                        {!! Form::text('calculation2[average]['.$key.']',
                                                        (!empty($item->average) ?  $item->average : '0' ),
                                                        ['class' => 'form-control input-xs average  text-right input_required', 'readonly' => true]) !!}
                                                       <span class="input-group-addon " style='background-color:#ffffff;' > <i class="fa fa-user"></i> </span>
                                                  </div> 
                                                 @endif
                                                 
                                            </td>
                                            <td  class="text-top">
                                                 @if (!is_null($item->total))
                                                    {!! Form::text('calculation2[total]['.$key.']',
                                                    (!empty($item->total) ?  number_format($item->total,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs total input_amount text-right ','readonly' => true]) !!}
                                                 @endif
                                            </td>
                                            <td  class="text-top">
                                                {!! Form::textarea('calculation2[remark]['.$key.']', !empty($item->remark) ? $item->remark : null  , ['class' => 'form-control', 'rows'=>'1'  ]); !!}
                                                <input type="hidden"  name="calculation2[id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                <input type="hidden"  name="calculation2[basic_division_type_id][{{$key}}]"  value="{{ !empty($item->basic_division_type_id) ? $item->basic_division_type_id : null }}">
                                            </td>
                                         </tr>    

                        @endforeach                  
                        @else
                                      @if (count($division_type) > 0)
                                      @php
                                          if($cases->whistleblower != '0'){
                                            $divisions = ['25','25','50'];
                                        }else{
                                            $divisions = ['25','0','75'];
                                        }
                                       
                                      @endphp
                                 
                                      @foreach ($division_type as  $key => $item)
                                          <tr>
                                            <td  class=" text-top text-center font-medium-6">
                                                {{ $key +1}}
                                            </td>
                                            <td  class=" text-top font-medium-6">
                                                {{ $item->title  ?? null}}
                                            </td>
                                            <td  class=" text-top">
                                            
                                            </td> 
                                            <td  class="text-top">
                                                <div class=" input-group " style='background-color:#ffffff;'>
                                                    {!! Form::text('calculation2[division]['.$key.']',
                                                    (!empty($item->division) ?  $item->division : $divisions[$key] ),
                                                    ['class' => 'form-control input-xs   text-center division input_required reward', 'required' => false]) !!}
                                                    <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                                </div> 
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation2[amount]['.$key.']',
                                                        (!empty($item->amount) ?  number_format($item->amount,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs amount input_amount text-right input_required reward', 'required' => false]) !!}
                                            </td>
                                            <td  class="text-top">
                                                 @if (!is_null($item->reward_group_id))
                                                 <div class=" input-group " style='background-color:#ffffff;'>
                                                      {!! Form::text('calculation2[average]['.$key.']',
                                                         $cases->whistleblower ?? '0',
                                                      ['class' => 'form-control input-xs average  text-right input_required', 'readonly' => true]) !!}
                                                     <span class="input-group-addon " style='background-color:#ffffff;' > <i class="fa fa-user"></i> </span>
                                                </div> 
                                                 @endif
                                                 
                                            </td>
                                            <td  class="text-top">
                                                @if (!is_null($item->reward_group_id))
                                                    {!! Form::text('calculation2[total]['.$key.']',
                                                    (!empty($item->total) ?  number_format($item->total,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs total input_amount text-right ','readonly' => true]) !!}
                                                 @endif
                                            </td>
                                            <td  class="text-top">
                                                {!! Form::textarea('calculation2[remark]['.$key.']', !empty($item->remark) ? $item->remark : null  , ['class' => 'form-control', 'rows'=>'1'  ]); !!}
                                                <input type="hidden"  name="calculation2[id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                <input type="hidden"  name="calculation2[basic_division_type_id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                            </td>
                                         </tr>     
                           @endforeach                  
                            @endif 
                            @endif  
                                </tbody>
                                <footer>
                                    <tr>
                                           <td   class="text-top text-right  font-medium-6"  colspan="2" >
                                                  <b>รวม</b>
                                           </td>  
                                           <td  class=" text-top text-right  font-medium-6"> 
                                                 <b id="paid_amount3"> </b>
                                           </td>
                                           <td  class=" text-top text-right  font-medium-6">
                                                 <b id="division2_total"></b>
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6">
                                                 <b id="amount2_total"></b>
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6"></td>
                                           <td  class=" text-top text-right  font-medium-6">
                                  
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6"></td>
                                    </tr>
                                </footer>
                           </table>
                     </div>  
               </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 ">
                <b><u>สรุป</u></b>
            </div>
        </div>
        
        <div class="form-group" id="part2">
            <div class="col-md-12 ">
                @if (!empty($cases->law_reward_to->law_calculation2_many) && count($cases->law_reward_to->law_calculation2_many) > 0)
                    @foreach ($cases->law_reward_to->law_calculation2_many as  $key => $item)
                    <div class="form-group">
                        {!! HTML::decode(Form::label('', $item->division_type_name , ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('',null, ['class' => 'form-control division_type  text-right ',  'disabled' => true]) !!}
                        </div>
                    </div>
                    @endforeach  
                @else
                    @if (count($division_type) > 0)
                    @foreach ($division_type as  $key => $item)
                    <div class="form-group">
                        {!! HTML::decode(Form::label('', $item->title , ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4">
                            {!! Form::text('',null, ['class' => 'form-control division_type  text-right ',  'disabled' => true]) !!}
                        </div>
                    </div>
                    @endforeach                  
                    @endif 
                @endif 
            </div>
        </div>
        


        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend">
                <h5>ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล</h5>
            </legend>
            <div class="form-group">
                <div class="col-md-7 required">
                    {!! Form::label('law_config_reward_id', 'กลุ่มผู้มีสิทธิ์ ฯ', ['class' => 'col-md-3 control-label font-medium-6']) !!}
                    <div class="col-md-8">
                        {!! Form::select('law_config_reward_id',
                         App\Models\Law\Config\LawConfigReward::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                         (!empty($cases->law_reward_to->law_config_reward_id) ?  $cases->law_reward_to->law_config_reward_id :   (!empty($cases->law_config_reward_id) ?  $cases->law_config_reward_id : null)), 
                        ['class' => 'form-control ',
                        'id' =>'law_config_reward_id',
                        'placeholder'=>'- เลือกกลุ่มผู้มีสิทธิ์ -',
                         'required' => true]) 
                        !!}
                    </div>
                </div> 
                <div class="col-md-5">
                    <div class="pull-right">
                        <label class="control-label col-md-12 text-right font-medium-6">
                            แก้ไขสัดส่วนเงินคำนวณ :
                            {!! Form::checkbox('edit_reward', '1', !empty($cases->law_reward_to->edit_reward) && $cases->law_reward_to->edit_reward == '1' ?  true : false  , ['class' => 'js-switch','id'=>'edit_reward', 'data-color'=>'#13dafe']) !!}
                        </label>
                    </div>
                </div> 
           </div>
            <div class="form-group">

                <div class="col-md-12">
                     <div class="table">
                           <table class="table   table-bordered" >
                               <thead>
                                    <tr>
                                        <th class="text-center text-top" rowspan="2" width="2%">ลำดับ</th>
                                        <th class="text-center text-top" rowspan="2" width="18%">กลุ่มผู้มีสิทธิ์ได้รับเงินรางวัล</th>
                                        <th class="text-center text-top" rowspan="2" width="10%">ยอดยกมา</th>
                                        <th class="text-center text-top" colspan="2" width="25%">สัดส่วนเงินคำนวณ</th>
                                        <th class="text-center text-top" colspan="2"  width="25%" >จำนวนเงินที่ได้รับ/คน</th>

                                        <th class="text-center text-top" rowspan="2" width="10%" >หมายเหตุ</th>
                                    </tr>
                                    <tr>
                                        <th  class="text-center text-top" width="10%">
                                                 {!! Form::select('cal_type3',
                                                  ['1'=>'สัดส่วน','2'=>'จำนวนเงิน(ระบุเอง)'],
                                                  (!empty($cases->law_reward_to->law_calculation2_to->cal_type) ?$cases->law_reward_to->law_calculation2_to->cal_type : '1'),
                                                 ['class' => 'form-control input-xs', 
                                                 'id' => 'cal_type3',
                                                 'required' => true,'style'=>'width:100px;']) 
                                                   !!}   
                                        </th>
                                        <th  class="text-center text-top"  width="10%">
                                             จำนวนเงิน
                                        </th>
                                        <th  class="text-center text-top"  width="10%">
                                               เฉลี่ย
                                        </th>
                                        <th  class="text-center text-top"  width="15%">
                                            จำนวนเงิน
                                       </th>
                                    </tr>
                                 </thead>
                                 <tbody id="table_tbody_calculate3">
                                      <tr>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6">
                                              เงินรางวัล
                                        </td>
                                         <td  class=" text-top text-right  font-medium-6">
                                              <p id="paid_amount4"> </p>
                                         </td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                         <td  class=" text-top font-medium-6"></td>
                                     </tr>
                                     @if (!empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) > 0)
                                        @foreach ($cases->law_reward_to->law_calculation3_many as  $key => $item)
                                        <tr>
                                            <td  class=" text-top text-center font-medium-6">
                                                {{ $key +1}}
                                            </td>
                                            <td  class=" text-top font-medium-6">
                                                {{ $item->name  ?? null}}
                                            </td>
                                            <td  class=" text-top">
                                            
                                            </td> 
                                            <td  class="text-top">
                                                <div class=" input-group " style='background-color:#ffffff;'>
                                                    {!! Form::text('calculation3[division]['.$key.']',
                                                (!empty($item->division) ?  $item->division : '0' ),
                                                    ['class' => 'form-control input-xs  reward text-center division input_required', 'required' => false]) !!}
                                                    <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                                </div> 
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation3[amount]['.$key.']',
                                                        (!empty($item->amount) ?  number_format($item->amount,2) : '0.00' ),
                                                    ['class' => 'form-control reward  input-xs amount input_amount text-right input_required', 'required' => false]) !!}
                                            </td>
                                            <td  class="text-top">
                                                <div class=" input-group " style='background-color:#ffffff;'>
                                                    {!! Form::text('calculation3[average]['.$key.']',
                                                    (!empty($item->average) ?  $item->average : '0' ),
                                                    ['class' => 'form-control input-xs average  text-right input_required', 'readonly' => true]) !!}
                                                     <span class="input-group-addon " style='background-color:#ffffff;' > <i class="fa fa-user"></i> </span>
                                                </div> 
                                            </td>
                                            <td  class="text-top">
                                                    {!! Form::text('calculation3[total]['.$key.']',
                                                    (!empty($item->total) ?  number_format($item->total,2) : '0.00' ),
                                                    ['class' => 'form-control input-xs total input_amount text-right ','readonly' => true]) !!}
                                            </td>
                                            <td  class="text-top">
                                                {!! Form::textarea('calculation3[remark]['.$key.']', !empty($item->remark) ? $item->remark : null  , ['class' => 'form-control', 'rows'=>'1'  ]) !!}
                                                <input type="hidden"  name="calculation3[id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                <input type="hidden"  name="calculation3[law_reward_calculation_id][{{$key}}]"  value="{{ !empty($item->law_reward_calculation_id) ? $item->law_reward_calculation_id : null }}">
                                                <input type="hidden"  name="calculation3[law_basic_reward_group_id][{{$key}}]"  value="{{ !empty($item->law_basic_reward_group_id) ? $item->law_basic_reward_group_id : null }}">
                                            </td>
                                        </tr>      
                                        @endforeach     

                                     @else

                                        @if (count($config_reward_sub) > 0)
                                        @foreach ($config_reward_sub as  $key => $item)
                                            <tr>
                                                <td  class=" text-top text-center font-medium-6">
                                                    {{ $key +1}}
                                                </td>
                                                <td  class=" text-top font-medium-6">
                                                    {{ $item->law_reward_group_to->title  ?? null}}
                                                </td>
                                                <td  class=" text-top">
                                                
                                                </td> 
                                                <td  class="text-top">
                                                      @php
                                                            $decimal = '0';
                                                            if(!empty($item->amount)){
                                                                $division =  explode(".",$item->amount);
                                                                if(!empty($division) && count($division) == '2'){
                                                                    if($division[1] > 0){
                                                                        $decimal = $division[0].'.'.mb_substr($division[1],0,1);    
                                                                    }else{
                                                                        $decimal = $division[0];    
                                                                    }
                                                                }else{
                                                                    $decimal =  number_format($item->amount);
                                                                }
                                                            }

                                                            $average = App\Models\Law\Reward\LawlRewardStaffLists::where('law_case_id',$cases->id)->where('basic_reward_group_id',$item->reward_group_id)->get()->count();
                                                      @endphp
                                                    <div class=" input-group " style='background-color:#ffffff;'>
                                                        {!! Form::text('calculation3[division]['.$key.']',
                                                           $decimal,
                                                        ['class' => 'form-control input-xs  reward text-center division input_required', 'required' => false]) !!}
                                                        <span class="input-group-addon " style='background-color:#ffffff;' > % </span>
                                                    </div> 
                                                </td>
                                                <td  class="text-top">
                                                        {!! Form::text('calculation3[amount]['.$key.']',
                                                            (!empty($item->amount) ?  number_format($item->amount,2) : '0.00' ),
                                                        ['class' => 'form-control input-xs reward amount input_amount text-right input_required', 'required' => false]) !!}
                                                </td>
                                                <td  class="text-top">
                                                    <div class=" input-group " style='background-color:#ffffff;'>
                                                        {!! Form::text('calculation3[average]['.$key.']',
                                                         $average,
                                                       ['class' => 'form-control input-xs average  text-right input_required', 'readonly' => true]) !!}
                                                         <span class="input-group-addon " style='background-color:#ffffff;' > <i class="fa fa-user"></i> </span>
                                                    </div> 
                                                </td>
                                                <td  class="text-top">
                                                        {!! Form::text('calculation3[total]['.$key.']',
                                                        (!empty($item->total) ?  number_format($item->total,2) : '0.00' ),
                                                        ['class' => 'form-control input-xs total input_amount text-right ','readonly' => true]) !!}
                                                </td>
                                                <td  class="text-top">
                                                    {!! Form::textarea('calculation3[remark]['.$key.']', !empty($item->remark) ? $item->remark : null  , ['class' => 'form-control', 'rows'=>'1'  ]) !!}
                                                    <input type="hidden"  name="calculation3[id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                    <input type="hidden"  name="calculation3[law_reward_calculation_id][{{$key}}]"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                    <input type="hidden"  name="calculation3[law_basic_reward_group_id][{{$key}}]"  value="{{ !empty($item->reward_group_id) ? $item->reward_group_id : null }}">
                                                </td>
                                            </tr>      
                                            @endforeach
                                        @endif
                                      @endif
                                </tbody>
                                <footer>
                                    <tr>
                                           <td   class="text-top text-right  font-medium-6"  colspan="2" >
                                                  <b>รวม</b>
                                           </td>  
                                           <td  class=" text-top text-right  font-medium-6"> 
                                                 <b id="paid_amount5"> </b>
                                           </td>
                                           <td  class=" text-top text-right  font-medium-6">
                                                 <b id="division3_total"></b>
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6">
                                                 <b id="amount3_total"></b>
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6">
                                                <b id="average3_total"></b>
                                           </td>
                                           <td  class=" text-top text-right  font-medium-6">
                                               <b id="total3_total"></b>  
                                           </td>   
                                           <td  class=" text-top text-right  font-medium-6"></td>
                                    </tr>
                                </footer>
                           </table>
                     </div>  
               </div>
          </div>





        </fieldset>
    </div>
</div>


@push('js')
<script src="{{ asset('js/function.js') }}"></script>
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

<script>

 $(document).ready(function() {
          $("body").on("click", "#modal_record", function() {
                $('#RecordModals').modal('show'); 
            });
            
                 Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                     $('.js-switch').each(function() {
                         new Switchery($(this)[0], { secondaryColor :'red', size: 'small' });
                    });

                    $('body').on('change', '#edit_income', function(){
                        edit_income();
                    });
                    $('body').on('change', '#edit_proportion', function(){
                        edit_proportion();
                    });
 
                     $('body').on('change', '#edit_reward', function(){
                         edit_reward();
                    });

                    $('body').on('change', '#law_config_reward_id', function(){

                          if(checkNone($(this).val())){
                                $.ajax({
                                        method: "GET",
                                        url: "{{ url('law/reward/calculations/config_reward') }}",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "reward_id": $(this).val(),
                                            "cases_id": '{{ @$cases->id }}'
                                        }
                                    }).success(function (msg) { 
                                        console.log(msg);
                                        if(msg.message == true){

                                            var rows = $('#table_tbody_calculate3').find("tr"); 
                                            rows.each(function(index, el) {
                                                $(el).find("input.total").parent().parent().remove();
                                           }); 

                                            $(msg.datas).each(function(index, el) {
                                                var  text = '';
                                                     text += '<tr>';
                                                     text += '<td class=" text-top text-center font-medium-6">'+(index+1)+'</td>';
                                                     text += '<td class="text-top font-medium-6">'+(el.title)+'</td>';
                                                     text += '<td class="text-top"></td>';
                                                     text += '<td class="text-top">';
                                                     text += '  <div class=" input-group " style="background-color:#ffffff;">';
                                                     text += '     <input type="text" name="calculation3[division]['+index+']"  class="form-control input-xs  reward text-center division input_required"   value="'+(el.decimal)+'">';
                                                     text += '       <span class="input-group-addon " style="background-color:#ffffff; "> % </span>';
                                                     text += '  </div>';
                                                     text += '</td>';
                                                     text += '<td class="text-top">';
                                                     text += '     <input type="text" name="calculation3[amount]['+index+']"  class="form-control input-xs reward amount input_amount text-right input_required"    >';
                                                     text += '</td>';
                                                     text += '<td class="text-top">';
                                                     text += '  <div class=" input-group " style="background-color:#ffffff;">';
                                                     text += '       <input type="text" name="calculation3[average]['+index+']"  class="form-control input-xs average  text-right input_required"   readonly value="'+(el.average)+'">';
                                                     text += '       <span class="input-group-addon " style="background-color:#ffffff; "> <i class="fa fa-user"></i> </span>';
                                                     text += '  </div>';
                                                     text += '</td>';
                                                     text += '<td class="text-top">';
                                                     text += '     <input type="text" name="calculation3[total]['+index+']"  class="form-control input-xs total input_amount text-right"   readonly >';
                                                     text += '</td>';
                                                     text += '<td class="text-top">';   
                                                     text += '    <textarea name="calculation3[remark]['+index+']"    class="form-control" rows="1"></textarea>';
                                                     text += '     <input type="hidden" name="calculation3[id]['+index+']"  value="'+(el.id)+'">';
                                                     text += '     <input type="hidden" name="calculation3[law_reward_calculation_id]['+index+']"  value="'+(el.id)+'">';
                                                     text += '     <input type="hidden" name="calculation3[law_basic_reward_group_id]['+index+']"  value="'+(el.reward_group_id)+'">';
                                                     text += '</td>';
                                                     text += '</tr>';
                                                 $('#table_tbody_calculate3').append(text);
                                           }); 
                                           cal_type3();
                                           calculate3_first();
                                           calculate_total3();
  

                                        }
                                         
                                    });
                            }
                    });
              
   
                    @if ($cases->step_froms == '2' && !empty($cases->law_reward_to->law_calculation3_many) && count($cases->law_reward_to->law_calculation3_many) == 0)
                         $('#law_config_reward_id').change();
                    @endif
                                             
                                                   
                         
                                               
                                               
                    
            //  ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน
            // หักเป็นรายได้แผ่นดิน (สัดส่วน)
            $('#table_tbody_calculate1').on('keyup', '.division:eq(0)', function(){
                var $this =   $(this).parent().parent().parent();
                    if(checkNone($(this).val())){
                        var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                        var division = 0;
                    var rows = $('#table_tbody_calculate1').find( "tr" ).eq( 2 ); 
                         rows.each(function(index, el) {
                            if(checkNone($(el).find("input.division").val())){
                                  division  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
                             }
                         }); 
                               division  +=    parseFloat($(this).val());

                            if(division > 100){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'warning',
                                        title: 'กรุณากรอกสัดส่วนรวมไม่เกิน 100 %',
                                        width:600,
                                        showConfirmButton:true
                                        });
                                    $(this).val('');
                                    $this.find("input.amount").val('0.00'); 
                                    $this.find("input.total").val('0.00'); 
                                    calculate_total1();
                                    calculate2_first();
                                    calculate_total2();
                                    calculate3_first();
                                    calculate_total3();
                                    return false;
                            }

                       
                            var amount =   (parseFloat(RemoveCommas(paid_amount)) *  parseFloat($(this).val())) / 100;
                               $this.find("input.amount").val(addCommas(amount.toFixed(2), 2));

                               if(checkNone($this.find("input.difference").val())){
                                   amount  +=  parseFloat(RemoveCommas($this.find("input.difference").val()));
                                }

                                $this.find("input.total").val(addCommas(amount.toFixed(2), 2));
                    }else{
                        $this.find("input.amount").val('0.00'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total1();
                    calculate2_first();
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
             });


                // หักเป็นรายได้แผ่นดิน  (จำนวนเงิน)
                $('#table_tbody_calculate1').on('keyup', '.amount:eq(0)', function(){
                    var $this =   $(this).parent().parent();
                        if(checkNone($(this).val())){
                            var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                            var amount = 0;
                        var rows = $('#table_tbody_calculate1').find( "tr" ).eq( 2 ); 
                            rows.each(function(index, el) {
                                if(checkNone($(el).find("input.amount").val())){
                                    amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
                                }
                            }); 
                                amount  +=    parseFloat(RemoveCommas($(this).val()));

                                if(amount > paid_amount){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกผลรวมไม่เกิน '+ addCommas(parseFloat(paid_amount).toFixed(2), 2),
                                            showConfirmButton: true
                                            });
                                        $(this).val('');
                                        $this.find("input.division").val('0'); 
                                        $this.find("input.total").val('0.00'); 
                                        calculate_total1();
                                        calculate2_first();
                                        calculate_total2();
                                        calculate3_first();
                                        calculate_total3();
                                        return false;
                                } 
                                    var amount1     =  parseFloat(RemoveCommas($(this).val()));
                                    var division    = 0;
                                        paid_amount =  parseFloat(RemoveCommas(paid_amount));
                                        division    = (amount1 / paid_amount)  * 100;
                                        $this.find("input.division").val(division);

                                if(checkNone($this.find("input.difference").val())){
                                        amount1  +=  parseFloat(RemoveCommas($this.find("input.difference").val()));
                                    }

                                    $this.find("input.total").val(addCommas(amount1.toFixed(2), 2));
                        }else{
                            $this.find("input.division").val('0'); 
                            $this.find("input.total").val('0.00'); 
                        }
                        calculate_total1();
                        calculate2_first();
                        calculate_total2();
                        calculate3_first();
                        calculate_total3();
                  });


             $('#table_tbody_calculate1').on('keyup', '.max:eq(1)', function(){
                        var $this =   $(this).parent().parent();
                        var first_rows = $('#table_tbody_calculate1').find( "tr" ).eq(1); 
                          var amount = 0;
                          var  max = 0;
                           if(checkNone($this.find("input.amount").val())){
                               amount  +=  parseFloat(RemoveCommas($this.find("input.amount").val()));
                            }
                            if(checkNone($(this).val())){
                                                max    +=  parseFloat(RemoveCommas($(this).val()));
                                            if(amount > max){
                                               var difference = (amount - max);
                                                 $this.find("input.total").val(addCommas(max.toFixed(2), 2));

                                                //  หักเป็นรายได้แผ่นดิน
                                                $(first_rows).find("input.difference").val(addCommas(difference.toFixed(2), 2));
                                                   var total =  0;
                                                if(checkNone( $(first_rows).find("input.amount").val())){
                                                   total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                                    }
                                                     total += difference;
                                                $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));

                                            }else{
                                                  $this.find("input.total").val(addCommas(amount.toFixed(2), 2));

                                                $(first_rows).find("input.difference").val('0.00');
                                                  var total =  0;
                                                 if(checkNone( $(first_rows).find("input.amount").val())){
                                                      total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                                    }
    
                                                $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));
                                                
                                            }

                            }else{
                                   $this.find("input.total").val('0.00');
                                  $(first_rows).find("input.difference").val(addCommas(amount.toFixed(2), 2));
                                   var total =  0;
                                  if(checkNone( $(first_rows).find("input.amount").val())){
                                      total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                    }
                                       total += amount;
                                  $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));
                            }
                            calculate_total1();
                            calculate2_first();
                            calculate_total2();
                            calculate3_first();
                            calculate_total3();
                    });


                    // หักเป็นรายได้แผ่นดิน  (ส่วนต่าง)
                    $('#table_tbody_calculate1').on('keyup', '.difference:eq(0)', function(){
                            var $this =   $(this).parent().parent();
                          var first_rows = $('#table_tbody_calculate1').find( "tr" ).eq(2); 
                             var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                             var amount =  0;
                            if(checkNone($(this).val())){
                                           
                                                       var total =  0;    
                                                       if(checkNone( $(first_rows).find("input.total").val())){
                                                            total  +=  parseFloat(RemoveCommas($(first_rows).find("input.total").val()));
                                                         }
                                                         if(checkNone($this.find("input.amount").val())){
                                                            total  +=  parseFloat(RemoveCommas($this.find("input.amount").val()));
                                                            amount +=  parseFloat(RemoveCommas($this.find("input.amount").val()));
                                                        } 
                                                            total  +=    parseFloat($(this).val());
                                                         if(total > paid_amount){
                                                                Swal.fire({
                                                                    position: 'center',
                                                                    icon: 'warning',
                                                                    title: 'กรุณากรอกผลรวมไม่เกิน '+ addCommas(parseFloat(paid_amount).toFixed(2), 2),
                                                                    showConfirmButton:true,
                                                                    });
                                                                $(this).val('');  
                                                                $this.find("input.total").val(addCommas(amount.toFixed(2), 2));
                                                                calculate_total1();
                                                                calculate2_first();
                                                                calculate_total2();
                                                                calculate3_first();
                                                                calculate_total3();
                                                                return false;
                                                        } 
                                                        $this.find("input.total").val(addCommas(total.toFixed(2), 2));

                            }else{
                                if(checkNone($this.find("input.amount").val())){
                                         amount +=  parseFloat(RemoveCommas($this.find("input.amount").val()));
                                  } 
                                $this.find("input.total").val(addCommas(amount.toFixed(2), 2));
                            }
                            calculate_total1();
                            calculate2_first();
                            calculate_total2();
                            calculate3_first();
                            calculate_total3();
                    });

                     // เงินสินบน เงินรางวัล ค่าใช้จ่ายดำเนินงาน  (ส่วนต่าง)
                    $('#table_tbody_calculate1').on('keyup', '.difference:eq(1)', function(){
                            var $this =   $(this).parent().parent();
                          var first_rows = $('#table_tbody_calculate1').find( "tr" ).eq(1); 
                             var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                             var amount =  0;
                             var  max = 0;
                             if(checkNone($this.find("input.amount").val())){
                                amount +=  parseFloat(RemoveCommas($this.find("input.amount").val()));
                              } 
                             if(checkNone($this.find("input.max").val())){
                                 max  +=  parseFloat(RemoveCommas($this.find("input.max").val()));
                              } 

                              

                            if(checkNone($(this).val())){
                                           
                                                        var total =  0;    

                                                        var rows1 = $('#table_tbody_calculate1').children(); 
                                                            rows1.each(function(index, el) {
                                                                if(checkNone($(el).find("input.total").val())){
                                                                    total  +=  parseFloat(RemoveCommas($(el).find("input.total").val()));
                                                                }
                                                            }); 
                                                            total   +=    parseFloat($(this).val());
                                                            amount  +=    parseFloat($(this).val());
                                                         if(total > paid_amount){
                                                                Swal.fire({
                                                                    position: 'center',
                                                                    icon: 'warning',
                                                                    title: 'กรุณากรอกผลรวมไม่เกิน '+ addCommas(parseFloat(paid_amount).toFixed(2), 2),
                                                                    showConfirmButton:true,
                                                                    });
                                                                $(this).val('');  
                                                                $this.find("input.total").val('0.00');
                                                                calculate_total1();
                                                                calculate2_first();
                                                                calculate_total2();
                                                                calculate3_first();
                                                                calculate_total3();
                                                                return false;
                                                        } 

                                                        if(total > paid_amount){
                                                                Swal.fire({
                                                                    position: 'center',
                                                                    icon: 'warning',
                                                                    title: 'กรุณากรอกผลรวมไม่เกิน '+ addCommas(parseFloat(paid_amount).toFixed(2), 2),
                                                                    showConfirmButton:true
                                                                    });
                                                                $(this).val('');  
                                                                $this.find("input.total").val('0.00');
                                                                calculate_total1();
                                                                calculate2_first();
                                                                calculate_total2();
                                                                calculate3_first();
                                                                calculate_total3();
                                                                return false;
                                                        } 

                                                        if(amount > max){
                                                                Swal.fire({
                                                                    position: 'center',
                                                                    icon: 'warning',
                                                                    title: 'กรุณากรอกส่วนต่างไม่เกินเพตาน '+ addCommas(max.toFixed(2), 2),
                                                                    showConfirmButton:true,
                                                                    });
                                                                $(this).val('');  
                                                                $this.find("input.total").val('0.00');
                                                                calculate_total1();
                                                                calculate2_first();
                                                                calculate_total2();
                                                                calculate3_first();
                                                                calculate_total3();
                                                                return false;
                                                        } 

                                                        $this.find("input.total").val(addCommas(amount.toFixed(2), 2));
                                              
                                                                

                            }else{
                                    if(amount > max){
                                        $this.find("input.total").val(addCommas(max.toFixed(2), 2));
                                    }else{
                                        $this.find("input.total").val(addCommas(amount.toFixed(2), 2));
                                    }
                            }
                            calculate_total1();
                            calculate2_first();
                            calculate_total2();
                            calculate3_first();
                            calculate_total3();
                    });


            // หักเป็นรายได้แผ่นดิน  (สันส่วน)
            $('#table_tbody_calculate1').on('keyup', '.division:eq(1)', function(){
              
                var $this =   $(this).parent().parent().parent();
                    if(checkNone($(this).val())){
                        var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                        var division = 0;
                    var rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1); 
                         rows.each(function(index, el) {
                            if(checkNone($(el).find("input.division").val())){
                                  division  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
                                
                             }
                         }); 
                            division  +=    parseFloat($(this).val());

                                if(division > 100){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกสัดส่วนรวมไม่เกิน 100 %',
                                            width:600,
                                            showConfirmButton:true
                                            });
                                        $(this).val('');
                                        $this.find("input.amount").val('0.00'); 
                                        $this.find("input.difference").val('0.00'); 
                                        $this.find("input.total").val('0.00');
                                        calculate_total1(); 
                                        calculate2_first();
                                        calculate_total2();
                                        calculate3_first();
                                        calculate_total3();
                                        return false;
                                }

                       
                                var amount =   (parseFloat(RemoveCommas(paid_amount)) *  parseFloat($(this).val())) / 100;
                                 $this.find("input.amount").val(addCommas(amount.toFixed(2), 2));

                                 var   first_rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1 ); 
                                 var max      = parseFloat(RemoveCommas($this.find("input.max").val()));
                                 if(amount > max){
                                        var difference = (amount - max);
                                            $this.find("input.total").val(addCommas(max.toFixed(2), 2));

                                        //  หักเป็นรายได้แผ่นดิน
                                        $(first_rows).find("input.difference").val(addCommas(difference.toFixed(2), 2));
                                            var total =  0;
                                        if(checkNone( $(first_rows).find("input.amount").val())){
                                            total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                            }
                                                total += difference;
                                        $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));

                                    }else{
                                            $this.find("input.total").val(addCommas(amount.toFixed(2), 2));

                                        $(first_rows).find("input.difference").val('0.00');
                                            var total =  0;
                                            if(checkNone( $(first_rows).find("input.amount").val())){
                                                total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                            }

                                        $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));
                                        
                                    }



                                //       var   first_rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1 ); 
                                //    if(checkNone($this.find("input.max").val())){
                                //        var maxs      = parseFloat(RemoveCommas($this.find("input.max").val()));
                                //         if(amount > maxs){
                                //             var totals =  (amount - maxs);
                                //             // $this.find("input.difference").val(addCommas(totals.toFixed(2), 2));
                                //             $this.find("input.difference").val('0.00');
                                //            $(first_rows).find( ".difference" ).val('0.00');
                                      
                                //            var total_amount =  $(first_rows).find( ".amount" ).val();
                                //             var  totals1     =   parseFloat(RemoveCommas(total_amount));
                                //             $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
                                //         }else{
                                //             var totals =   maxs;
                                //             $this.find("input.difference").val('0.00');

                                //             var   total_difference  =  (amount -  maxs);
                                //             $(first_rows).find( ".difference" ).val(addCommas(total_difference.toFixed(2), 2));

                                //             var total_amount =  $(first_rows).find( ".amount" ).val();
                                //             var  totals1     =  (parseFloat(RemoveCommas(total_amount)) + total_difference);
                                //             $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
                                //         }
                                //         $this.find( "input.total" ).val(addCommas(totals.toFixed(2), 2));
                                //      }

            
                    }else{ 
                        $this.find("input.amount").val('0.00'); 
                        $this.find("input.total").val('0.00'); 
                        $this.find("input.difference").val('0.00'); 
                    }
                    calculate_total1();
                    calculate2_first();
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
             });

              // เงินสินบน เงินรางวัล ค่าใช้จ่ายดำเนินงาน  (จำนวนเงิน)
              $('#table_tbody_calculate1').on('keyup', '.amount:eq(1)', function(){
                var $this =   $(this).parent().parent();
                    if(checkNone($(this).val())){
                        var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
                        var amount = 0;
                    var rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1 ); 
                         rows.each(function(index, el) {
                            if(checkNone($(el).find("input.amount").val())){
                                amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
                             }
                         }); 
                              amount  +=    parseFloat(RemoveCommas($(this).val()));

                            if(amount > paid_amount){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'warning',
                                        title: 'กรุณากรอกผลรวมไม่เกิน '+ addCommas(parseFloat(paid_amount).toFixed(2), 2),
                                        showConfirmButton:true
                                        });
                                    $(this).val('');
                                    $this.find("input.division").val('0'); 
                                    $this.find("input.total").val('0.00'); 
                                    calculate_total1();
                                    calculate2_first();
                                    calculate_total2();
                                    calculate3_first();
                                    calculate_total3();
                                    return false;
                            } 

                            
                                var amount1     =  parseFloat(RemoveCommas($(this).val()));
                                var division    = 0;
                                    paid_amount =  parseFloat(RemoveCommas(paid_amount));
                                    division    = (amount1 / paid_amount)  * 100;
                                    $this.find("input.division").val(division);

                                       var   first_rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1 ); 
                                            if(checkNone($this.find("input.max").val())){
                                            var maxs      = parseFloat(RemoveCommas($this.find("input.max").val()));
                                            if(amount1 > maxs){
                                                var difference = (amount1 - maxs);
                                                    $this.find("input.total").val(addCommas(maxs.toFixed(2), 2));

                                                //  หักเป็นรายได้แผ่นดิน
                                                $(first_rows).find("input.difference").val(addCommas(difference.toFixed(2), 2));
                                                    var total =  0;
                                                if(checkNone( $(first_rows).find("input.amount").val())){
                                                    total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                                    }
                                                    total += difference;
                                                  $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));

                                            }else{
                                                    $this.find("input.total").val(addCommas(amount1.toFixed(2), 2));

                                                $(first_rows).find("input.difference").val('0.00');
                                                    var total =  0;
                                                    if(checkNone( $(first_rows).find("input.amount").val())){
                                                        total  +=  parseFloat(RemoveCommas($(first_rows).find("input.amount").val()));
                                                    }

                                                $(first_rows).find("input.total").val(addCommas(total.toFixed(2), 2));
                                                
                                            }


                                        // if(amount1 < maxs){
                                        //     var totals =  (maxs - amount1);
                                        //     $this.find("input.difference").val(addCommas(totals.toFixed(2), 2));
                                        //    $(first_rows).find( ".difference" ).val('0.00');
                                      
                                        //    var total_amount =  $(first_rows).find( ".amount" ).val();
                                        //     var  totals1     =   parseFloat(RemoveCommas(total_amount));
                                        //     $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
                                        // }else{
                                        //     var totals =   maxs;
                                        //     $this.find("input.difference").val('0.00');

                                        //     var   total_difference  =  (amount1 -  maxs);
                                        //     $(first_rows).find( ".difference" ).val(addCommas(total_difference.toFixed(2), 2));

                                        //     var total_amount =  $(first_rows).find( ".amount" ).val();
                                        //     var  totals1     =  (parseFloat(RemoveCommas(total_amount)) + total_difference);
                                        //     $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
                                        // }
                                        // $this.find( "input.total" ).val(addCommas(totals.toFixed(2), 2));
                                     }else{
                                   
                                        $this.find( "input.total" ).val(addCommas(amount1.toFixed(2), 2));
                                        $(first_rows).find( ".difference" ).val('0.00');
                                        var total_amount =  $(first_rows).find( ".amount" ).val();
                                        if(checkNone(total_amount)){
                                            var  totals1     =  (parseFloat(RemoveCommas(total_amount)));
                                            $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
                                        }else{
                                            $(first_rows).find( ".total" ).val('0.00');
                                        }
                                     }

                    }else{
                        $this.find("input.division").val('0'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total1();
                    calculate2_first();
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
             });



            //  ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน
            // สัดส่วน
             $('#table_tbody_calculate2').on('keyup', '.division', function(){
                var paid_amount2 =  $('#paid_amount2').html();
                var $this =   $(this).parent().parent().parent();
                var division = 0;
                    if(checkNone($(this).val())){

                        var rows = $('#table_tbody_calculate2').find( "tr" ); 
                           rows.each(function(index, el) {
                            if(checkNone($(el).find("input.division").val())){
                                  division  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
                             }
                         }); 
                            if(division > 100){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกสัดส่วนรวมไม่เกิน 100 %',
                                            width:600,
                                            showConfirmButton:true
                                            });
                                        $(this).val('');
                                        $this.find("input.amount").val('0'); 
                                        $this.find("input.total").val('0.00');
                                        calculate_total2();
                                        calculate3_first();
                                        calculate_total3();
                                        return false;
                            }
                            var amount =   (parseFloat(RemoveCommas(paid_amount2)) *  parseFloat($(this).val())) / 100;
                                $this.find("input.amount").val(addCommas(amount.toFixed(2), 2));

                            var average =    $this.find("input.average").val();
                            if(checkNone(average)){
                                var total = (amount /  parseFloat(average));
                                $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                            }
                    }else{
                        $this.find("input.amount").val('0'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
              });

              //   จำนวนเงิน
              $('#table_tbody_calculate2').on('keyup', '.amount', function(){
                var paid_amount2 =  $('#paid_amount2').html();
                var $this =   $(this).parent().parent();
                var amount = 0;
                    if(checkNone($(this).val())){

                        var rows = $('#table_tbody_calculate2').find( "tr" ); 
                           rows.each(function(index, el) {
                            if(checkNone($(el).find("input.amount").val())){
                                amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
                             }
                         }); 
                  
                           paid_amount2 =  parseFloat(RemoveCommas(paid_amount2));
                           if(amount > paid_amount2){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกรวมไม่เกิน ' + addCommas(paid_amount2.toFixed(2), 2),
                                            showConfirmButton: true
                                            });
                                        $(this).val('');
                                        $this.find("input.division").val('0'); 
                                        $this.find("input.total").val('0.00');
                                        calculate_total2();
                                        calculate3_first();
                                        calculate_total3();
                                        return false;
                            }

                                 var amount1     =  parseFloat(RemoveCommas($(this).val()));
                                 var division    =  0;
                                     division    = (amount1 / paid_amount2)  * 100;
                                    $this.find("input.division").val(division);

                                var average =   $this.find("input.average").val();
                                if(checkNone(average)){
                                   if(average == '0'){
                                            $this.find("input.total").val('0.00');
                                    }else{
                                     var total = (amount1 /  parseFloat(average));
                                        $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                                    }
                                }
 
                    }else{
                        $this.find("input.division").val('0'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
              });

               //   เฉลี่ย	
              $('#table_tbody_calculate2').on('keyup', '.average', function(){
                var paid_amount2 =  $('#paid_amount2').html();
                var $this =   $(this).parent().parent();
                    if(checkNone($(this).val())){

                           var  average = $(this).val();
                                average =  parseFloat(average);
                            //    if(average <= 0){
                            //                 Swal.fire({
                            //                     position: 'center',
                            //                     icon: 'warning',
                            //                     title: 'กรุณากรอกเฉลี่ยมากกว่า 1',
                            //                     showConfirmButton: true
                            //                     });
                            //                 $(this).val('');
                            //                 $this.find("input.total").val('0.00');
                            //                 calculate_total2();
                            //                 calculate3_first();
                            //                 calculate_total3();
                            //                 return false;
                            //      }

                                 var amount1     =  parseFloat(RemoveCommas($this.find("input.amount").val()));
                                if(checkNone(average)){
                                     if(average == '0'){
                                        $this.find("input.total").val('0.00');
                                     }else{
                                        var total = (amount1 /  average);
                                        $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                                     }
                                }
 
                    }else{
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total2();
                    calculate3_first();
                    calculate_total3();
              });


            //  ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน
            // สัดส่วน
            $('#table_tbody_calculate3').on('keyup', '.division', function(){
                var paid_amount4 =  $('#paid_amount4').html();
                var $this =   $(this).parent().parent().parent();
                var division = 0;
                    if(checkNone($(this).val())){

                        var rows = $('#table_tbody_calculate3').find( "tr" ); 
                           rows.each(function(index, el) {
                            if(checkNone($(el).find("input.division").val())){
                                  division  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
                             }
                         }); 
                            if(division > 100){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกสัดส่วนรวมไม่เกิน 100 %',
                                            width:600,
                                            showConfirmButton:true
                                            });
                                        $(this).val('');
                                        $this.find("input.amount").val('0'); 
                                        $this.find("input.total").val('0.00');
                                        calculate_total3();
                                        return false;
                            }
                            var amount =   (parseFloat(RemoveCommas(paid_amount4)) *  parseFloat($(this).val())) / 100;
                                $this.find("input.amount").val(addCommas(amount.toFixed(2), 2));

                            var average =    $this.find("input.average").val();
                            if(checkNone(average)){

                                if(average == '0'){
                                        $this.find("input.total").val('0.00');
                                }else{
                                        var total = (amount /  parseFloat(average));
                                        $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                                }
                            }
                    }else{
                        $this.find("input.amount").val('0'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total3();
              });

              //   จำนวนเงิน
              $('#table_tbody_calculate3').on('keyup', '.amount', function(){
                var paid_amount4 =  $('#paid_amount4').html();
                var $this =   $(this).parent().parent();
                var amount = 0;
                    if(checkNone($(this).val())){

                        var rows = $('#table_tbody_calculate3').find( "tr" ); 
                           rows.each(function(index, el) {
                            if(checkNone($(el).find("input.amount").val())){
                                amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
                             }
                         }); 
                  
                         paid_amount4 =  parseFloat(RemoveCommas(paid_amount4));
                           if(amount > paid_amount4){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'warning',
                                            title: 'กรุณากรอกรวมไม่เกิน ' + addCommas(paid_amount4.toFixed(2), 2),
                                            showConfirmButton: true
                                            });
                                        $(this).val('');
                                        $this.find("input.division").val('0'); 
                                        $this.find("input.total").val('0.00');
                                        calculate_total3();
                                        return false;
                            }

                                 var amount1     =  parseFloat(RemoveCommas($(this).val()));
                                 var division    =  0;
                                     division    = (amount1 / paid_amount4)  * 100;
                                    $this.find("input.division").val(division);

                                  var average =   $this.find("input.average").val();
                                    if(checkNone(average)){
                                        if(average == '0'){
                                            $this.find("input.total").val('0.00');
                                    }else{
                                        var total = (amount1 /  parseFloat(average));
                                            $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                                    }
                                }
 
                    }else{
                        $this.find("input.division").val('0'); 
                        $this.find("input.total").val('0.00'); 
                    }
                    calculate_total3();
              });

               //   เฉลี่ย	
              $('#table_tbody_calculate3').on('keyup', '.average', function(){
                var paid_amount2 =  $('#paid_amount2').html();
                var $this =   $(this).parent().parent();
                    if(checkNone($(this).val())){

                           var  average = $(this).val();
                                average =  parseFloat(average);
                                // if(average <= 0){
                                //                 Swal.fire({
                                //                     position: 'center',
                                //                     icon: 'warning',
                                //                     title: 'กรุณากรอกเฉลี่ยมากกว่า 1',
                                //                     showConfirmButton: true
                                //                     });
                                //                 $(this).val('');
                                //                 $this.find("input.total").val('0.00');

                                //                 calculate_total3();
                                //                 return false;
                                //     }

                                 var amount1     =  parseFloat(RemoveCommas($this.find("input.amount").val()));
                                if(checkNone(average)){
                                    if(average == '0'){
                                        $this.find("input.total").val('0.00');
                                     }else{
                                        var total = (amount1 /  average);
                                        $this.find("input.total").val(addCommas(total.toFixed(2), 2));
                                     }
                                }
 
                    }else{
                        $this.find("input.total").val('0.00'); 
                    }

                    calculate_total3();
              });


              



          $('#cal_type1').change(function(event) {
            cal_type1() ;   
          });
            cal_type1();
            edit_income();
         $('#cal_type2').change(function(event) {
            cal_type2() ;   
          });
          cal_type2();
          edit_proportion();
          $('#cal_type3').change(function(event) {
            cal_type3() ;   
          });
          cal_type3();
          edit_reward();
         @if (!empty($cases->law_reward_to) && count($cases->law_reward_to->law_calculation1_many) == 0)
            calculate_first();
            calculate_total1();
            calculate2_first();
            calculate_total2();
            calculate3_first();
            calculate_total3();
            
         @else
            calculate_total1();
            calculate_total2();
            calculate_total3();
         @endif

           
        // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
        $(".division, .average").on("keypress",function(e){
        var eKey = e.which || e.keyCode;
        if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
        return false;
        }
        }); 
      IsInputNumber() ;
});


function calculate3_first() {
         var rows2 = $('#table_tbody_calculate2').children(); 
        var paid_amount4   =  $('#paid_amount4').html();
      if(checkNone(paid_amount4)){
        var  paid_amount4  =  parseFloat(RemoveCommas(paid_amount4));   
      }else{
        var  paid_amount4  = '0.00';   
      }
 
      var rows3 = $('#table_tbody_calculate3').children(); 
          rows3.each(function(index, el) {

            if(checkNone($(el).find("input.division").val())){
                var input_division1    =   parseFloat(RemoveCommas($(el).find("input.division").val()));
                    input_division1    =  ((paid_amount4 * input_division1) / 100)
                    $(el).find( "input.amount" ).val(addCommas(input_division1.toFixed(2), 2));
            }

            if(checkNone($(el).find("input.average").val()) && checkNone($(el).find("input.amount").val())){
               var average =   parseFloat(RemoveCommas($(el).find("input.average").val()));
               if(average == '0'){
                   $(el).find( "input.total" ).val('0.00');
                 }else{
                  average =  (parseFloat(RemoveCommas($(el).find("input.amount").val())) / average) ;   
                  $(el).find( "input.total" ).val(addCommas(average.toFixed(2), 2));
                }
            }else{
                $(el).find( "input.total" ).val('0.00');
            }
       }); 

} 


function calculate2_first() {

var paid_amount2   = $('#paid_amount2').html();
  if(checkNone(paid_amount2)){
    paid_amount2 =  parseFloat(RemoveCommas(paid_amount2));
}else{
    paid_amount2  = '0.00';   
}
var rows2 = $('#table_tbody_calculate2').children(); 
rows2.each(function(index, el) {
    if(checkNone($(el).find("input.division").val())){
        var input_division    =   parseFloat(RemoveCommas($(el).find("input.division").val()));
            input_division    =  ((paid_amount2 * input_division) / 100)
           $(el).find( "input.amount" ).val(addCommas(input_division.toFixed(2), 2));
    }

    if(checkNone($(el).find("input.average").val()) && checkNone($(el).find("input.amount").val())){
        var average =   parseFloat(RemoveCommas($(el).find("input.average").val()));
            if(average == '0'){
                $(el).find( "input.total" ).val('0.00');
                }else{
                average =  (parseFloat(RemoveCommas($(el).find("input.amount").val())) / average) ;   
                $(el).find( "input.total" ).val(addCommas(average.toFixed(2), 2));
            }
    }
}); 

} 

 
function calculate_first() {
       var rows = $('#table_tbody_calculate1').find( "tr" ).eq( 2 ); 
        var division = 100;
        var amount = 0;
        var paid_amount =  '{!! !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : 0.00 !!}';
        var difference = 0; 
        var max = 0;
         rows.each(function(index, el) {

            if(checkNone($(el).find("input.division").val())){
                division  -=  parseFloat(RemoveCommas($(el).find("input.division").val()));
            }
 
            if(checkNone($(el).find("input.amount").val())){
                amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
            }
            if(checkNone($(el).find("input.max").val())){
                max     +=  parseFloat(RemoveCommas($(el).find("input.max").val()));
            }

            // if(checkNone($(el).find("input.amount").val()) && checkNone($(el).find("input.max").val())){
            //     var amounts   = parseFloat(RemoveCommas($(el).find("input.amount").val()));
            //     var maxs      = parseFloat(RemoveCommas($(el).find("input.max").val()));
            //         if(amounts < maxs){
            //             var totals =  (maxs - amount);
            //         }else{
            //             var totals =   maxs;
            //         }
            //         $(el).find( "input.total" ).val(addCommas(totals.toFixed(2), 2));
              
            // }

            if(checkNone($(el).find("input.difference").val())){
                difference  +=  parseFloat(RemoveCommas($(el).find("input.difference").val()));
            }
       }); 
       var first_rows = $('#table_tbody_calculate1').find( "tr" ).eq( 1 ); 
       $(first_rows).find( ".division" ).val(division );
       var   total_amount     =  (parseFloat(RemoveCommas(paid_amount)) -  amount);
         $(first_rows).find( ".amount" ).val(addCommas(total_amount.toFixed(2), 2));
         $(first_rows).find( ".max" ).val('0.00');
         if(amount > max){
            var   total_difference  =  (amount -  max);
            var   totals1  =  (total_amount + total_difference);
            $(first_rows).find( ".difference" ).val(addCommas(total_difference.toFixed(2), 2));
            $(first_rows).find( ".total" ).val(addCommas(totals1.toFixed(2), 2));
  
         }else{
            $(first_rows).find( ".difference" ).val('0.00');
            $(first_rows).find( ".total" ).val(addCommas(total_amount.toFixed(2), 2));
         }
}




function calculate_total3() {
      var divisio3 = 0;
      var amount3  = 0;
      var average3  = 0;
      var total3  = 0;
      var rows3 = $('#table_tbody_calculate3').children(); 
          rows3.each(function(index, el) {
            if(checkNone($(el).find("input.division").val())){
                divisio3  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
            }

            if(checkNone($(el).find("input.amount").val())){
                amount3  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
            }

            if(checkNone($(el).find("input.average").val())){
                average3  +=  parseFloat(RemoveCommas($(el).find("input.average").val()));
            }
 

            if(checkNone($(el).find("input.total").val())){
                total3  +=  parseFloat(RemoveCommas($(el).find("input.total").val()));
            }
       }); 

        //   ยอดยกมา
        $('#division3_total').html(divisio3);
        //    จำนวนเงิน
        $('#amount3_total').html(addCommas(amount3.toFixed(2), 2));
          //   เฉลี่ย	
        $('#average3_total').html(average3);
        //    จำนวนเงิน
        $('#total3_total').html(addCommas(total3.toFixed(2), 2));
        check_calculation();
} 


function calculate_total2() {
        var division2 = 0;
        var amount2   = 0;
        var rows2 = $('#table_tbody_calculate2').children(); 
        rows2.each(function(index, el) {
            if(checkNone($(el).find("input.division").val())){
                division2  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
            }
            if(checkNone($(el).find("input.amount").val())){
                amount2  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
                $('#part2').find('.division_type').eq(index-1).val($(el).find("input.amount").val());
            }
        
        }); 
        //   ยอดยกมา
        $('#division2_total').html(division2);
        //    จำนวนเงิน
        $('#amount2_total').html(addCommas(amount2.toFixed(2), 2));

        var division_type   =  $('#part2').find('.division_type').eq((rows2.length -2)).val();
        if(checkNone(division_type)){
        $('#paid_amount4,#paid_amount5').html(division_type);   
        }else{
        $('#paid_amount4,#paid_amount5').html('0.00');    
        }
} 


function calculate_total1() {
        var rows = $('#table_tbody_calculate1').children(); //แถวทั้งหมด
    
        var division = 0;
        var amount = 0.00;
        var total = 0.00;

         rows.each(function(index, el) {
            if(checkNone($(el).find("input.division").val())){
                division  +=  parseFloat(RemoveCommas($(el).find("input.division").val()));
            }
            if(checkNone($(el).find("input.amount").val())){
                amount  +=  parseFloat(RemoveCommas($(el).find("input.amount").val()));
            }
            if(checkNone($(el).find("input.total").val())){
                total  +=  parseFloat(RemoveCommas($(el).find("input.total").val()));
                $('#part1').find('.categorys').eq(index-1).val($(el).find("input.total").val());
            }
      
        }); 
        //   ยอดยกมา
         $('#division_total').html(division);
         //    จำนวนเงิน
         $('#amount_total').html(addCommas(amount.toFixed(2), 2));
         //    รวม
          $('#total').html(addCommas(total.toFixed(2), 2));

        var brought   =  $('#part1').find('.categorys').eq(1).val();
        if(checkNone(brought)){
            $('#paid_amount2,#paid_amount3').html(brought);   
        }else{
            $('#paid_amount2,#paid_amount3').html('0.00');   
        }
} 





function cal_type1() {
    if($('#cal_type1').val() == '2'){
        $('#table_tbody_calculate1').find('.division').prop('readonly' ,true );
        $('#table_tbody_calculate1').find('.amount').prop('readonly' ,false ); 
    }else{
        $('#table_tbody_calculate1').find('.division').prop('readonly' ,false );
        $('#table_tbody_calculate1').find('.amount').prop('readonly' ,true ); 

    }
}
function cal_type2() {
    if($('#cal_type2').val() == '2'){
        $('#table_tbody_calculate2').find('.division').prop('readonly' ,true );
        $('#table_tbody_calculate2').find('.amount').prop('readonly' ,false ); 
    }else{
        $('#table_tbody_calculate2').find('.division').prop('readonly' ,false );
        $('#table_tbody_calculate2').find('.amount').prop('readonly' ,true ); 

    }
}
function cal_type3() {
    if($('#cal_type3').val() == '2'){
        $('#table_tbody_calculate3').find('.division').prop('readonly' ,true );
        $('#table_tbody_calculate3').find('.amount').prop('readonly' ,false ); 
    }else{
        $('#table_tbody_calculate3').find('.division').prop('readonly' ,false );
        $('#table_tbody_calculate3').find('.amount').prop('readonly' ,true ); 

    }
}

function check_calculation() {
     var division1 =    $('#division_total').html();
         division1 =  parseFloat(division1);
     var division2 =    $('#division2_total').html();
         division2 =  parseFloat(division2);
     var division3 =    $('#division3_total').html();
         division3 =  parseFloat(division3);
         if(
            (checkNone(division1) && division1 == 100) && (checkNone(division2) && division2 == 100)  &&  (checkNone(division3) && division3 == 100)
           ){
            $('.save_calculate').attr('id', 'save_calculate');
         }else{
            $('.save_calculate').attr('id', 'not_complete_calculate');
         }
} 
function edit_income() {
        if($('#edit_income').is(':checked',true)){
             $('#table_tbody_calculate1').find('.reward').prop('readonly' ,false );
            $('#cal_type1').prop('disabled' ,false );
            cal_type1();
        }else{ 
            $('#cal_type1').prop('disabled' ,true );
             $('#table_tbody_calculate1').find('.reward').prop('readonly' ,true );
        }
} 
function edit_proportion() {
        if($('#edit_proportion').is(':checked',true)){
            $('#table_tbody_calculate2').find('.reward').prop('readonly' ,false );
            $('#cal_type2').prop('disabled' ,false );
            cal_type2();
        }else{ 
            $('#cal_type2').prop('disabled' ,true );
            $('#table_tbody_calculate2').find('.reward').prop('readonly' ,true );
        }
} 
function edit_reward() {
 
        if($('#edit_reward').is(':checked',true)){
            $('#table_tbody_calculate3').find('.reward').prop('readonly' ,false );
            $('#cal_type3,#law_config_reward_id').prop('disabled' ,false );
            cal_type3();
        }else{  
            $('#cal_type3,#law_config_reward_id').prop('disabled' ,true );
            $('#table_tbody_calculate3').find('.reward').prop('readonly' ,true );
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
             $(".input_amount").on("keypress",function(e){
              var eKey = e.which || e.keyCode;
              if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
             return false;
              }
             }); 
             
             // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
             $(".input_amount").on("change",function(){
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

</script>
@endpush
