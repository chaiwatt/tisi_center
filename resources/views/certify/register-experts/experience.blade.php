
<div class="row">
              <div class="col-md-12">
                  <div class="panel block4">
                      <div class="panel-group" id="accordion03">
                          <div class="panel panel-info">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                   <a data-toggle="collapse" data-parent="#accordion03" href="#experience"> <dd style="color:Black"> <i class='fa fa-suitcase' style="font-size:20px"></i> ประสบการณ์ </dd>  </a>
                              </h4>
                          </div>
       
        <div id="experience" class="panel-collapse experience in">
              <br>
              <div class="row">
                     <div class="col-md-1"></div>  
                     <div class="col-md-10 form-group">
                            <table class="table color-bordered-table info-bordered-table">
                                   <thead>
                                   <tr class=" text-center" >
                                       <th class="text-center"  width="1%">No.</th>
                                       <th class="text-center"  width="10%">ปี </th>
                                       <th class="text-center"  width="25%">หน่วยงาน </th>
                                       <th class="text-center"  width="25%">ตำแหน่ง </th>
                                       <th class="text-center"  width="25%">บทบาทหน้าที่</th>
                                   </tr>
                                   </thead>
                                   <tbody  >
                                          @if (count($registerexperts->expert_experiences_many) > 0)
                                                 @foreach ($registerexperts->expert_experiences_many as $key => $experience)
                                                        <tr>
                                                               <td class="text-center">
                                                                      {{ ($key+1)}}
                                                               </td>
                                                               <td>
                                                                 {!! Form::text('',  !empty($experience->years) ?  ( $experience->years +543) :  null,  ['class' => 'form-control  autofill', 'placeholder' => 'ปี', 'disabled' => true]) !!}
                                                               </td>
                                                               <td>
                                                                      {!! Form::text('', !empty($experience->appoint_department_to->title) ? $experience->appoint_department_to->title : null,  ['class' => 'form-control  autofill', 'placeholder' => 'หน่วยงาน', 'disabled' => true]) !!}
                                                                    
                                                               </td>
                                                               <td> 
                                                                      {!! Form::text('',  $experience->position ??  null,  ['class' => 'form-control autofill', 'placeholder' => 'ตำแหน่ง', 'disabled' => true]) !!}
                                                               </td>
                                                               <td >
                                                                      {!! Form::text('',  $experience->role ??  null,  ['class' => 'form-control autofill', 'placeholder' => 'บทบาทหน้าที่', 'disabled' => true]) !!}
                                                               </td>
                                                              
                                                        </tr>
                                                 @endforeach 
                                          @endif
                                        
                                   </tbody>
                             </table>
                     </div> 
                     <div class="col-md-1"></div>     
                     </div>   
                     
       </div>
                        </div>
                   </div>
               </div> 
           </div> 
</div>


<div class="row">
              <div class="col-md-12">
                  <div class="panel block4">
                      <div class="panel-group" id="accordion04">
                          <div class="panel panel-info">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                   <a data-toggle="collapse" data-parent="#accordion04" href="#desktop"> <dd style="color:Black"> <i class='fa fa-desktop' style="font-size:20px"></i> ประวัติการดำเนินงานกับ สมอ. </dd>  </a>
                              </h4>
                          </div>
       
        <div id="desktop" class="panel-collapse desktop in">
              <br>
              <div class="row">
                     <div class="col-md-1"></div>  
                     <div class="col-md-10 form-group">
                            <table class="table color-bordered-table info-bordered-table">
                                   <thead>
                                   <tr class=" text-center" >
                                       <th class="text-center"  width="1%">No.</th>
                                       <th class="text-center"  width="15%">วันที่ดำเนินการ </th>
                                       <th class="text-center"  width="20%">หน่วยงาน </th>
                                       <th class="text-center"  width="15%">คำสั่งที่ </th>
                                       <th class="text-center"  width="20%">ความเชียวชาญด้าน</th>
                                       <th class="text-center"  width="20%">ความเชียวชาญด้าน</th>
                                   </tr>
                                   </thead>
                                   <tbody  >
                                          @if (count($registerexperts->expert_historys_many) > 0)
                                                 @foreach ($registerexperts->expert_historys_many as $key => $history)
                                                        <tr>
                                                               <td class="text-center">
                                                                      {{ ($key+1)}}
                                                               </td>
                                                               <td>
                                                                 {!! Form::text('',  !empty( $history->operation_at)? HP::revertDate($history->operation_at,true):null ,  ['class' => 'form-control  autofill', 'placeholder' => 'ปี', 'disabled' => true]) !!}
                                                               </td>
                                                               <td>
                                                                      {!! Form::text('', !empty($history->appoint_department_to->title) ? $history->appoint_department_to->title : null,  ['class' => 'form-control  autofill', 'placeholder' => 'หน่วยงาน', 'disabled' => true]) !!}
                                                               </td>
                                                               <td> 
                                                                      {!! Form::text('',  $history->committee_no ??  null,  ['class' => 'form-control autofill', 'placeholder' => 'คำสั่งที่', 'disabled' => true]) !!}
                                                               </td>
                                                               <td >
                                                                      {!! Form::text('', !empty($history->expert_group_to->title) ? $history->expert_group_to->title : null,  ['class' => 'form-control  autofill', 'placeholder' => 'ความเชียวชาญด้าน', 'disabled' => true]) !!}
                                                               </td>
                                                               <td >
                                                                      {!! Form::text('', !empty($history->board_type_to->title) ? $history->board_type_to->title : null,  ['class' => 'form-control  autofill', 'placeholder' => 'ความเชียวชาญด้าน', 'disabled' => true]) !!}
                                                               </td>
                                                        </tr>
                                                 @endforeach 
                                          @endif
                                        
                                   </tbody>
                             </table>
                     </div> 
                     <div class="col-md-1"></div>     
                     </div>   
                     
       </div>
                        </div>
                   </div>
               </div> 
           </div> 
</div>