
<div class="row">
       <div class="col-md-12">
           <div class="panel block4">
               <div class="panel-group" id="accordion01">
                   <div class="panel panel-info">
                   <div class="panel-heading">
                       <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion01" href="#education"> <dd style="color:Black"> <i class='fa fa-graduation-cap' style="font-size:20px"></i> ข้อมูลกด้านการศึกษา </dd>  </a>
                       </h4>
                   </div>

 <div id="education" class="panel-collapse education in">
       <br>
       <div class="row">
              <div class="col-md-1"></div>  
              <div class="col-md-10 form-group">
                     <table class="table color-bordered-table info-bordered-table">
                            <thead>
                            <tr class=" text-center" >
                                <th class="text-center"  width="1%">No.</th>
                                <th class="text-center"  width="15%">ปีที่สำเร็จ </th>
                                <th class="text-center"  width="30%">วุฒิการศึกษา </th>
                                <th class="text-center"  width="30%">สถานศึกษา </th>
                                <th class="text-center"  width="15%">หลักฐานการศึกษา</th>
                            </tr>
                            </thead>
                            <tbody  >
                                   @if (count($registerexperts->expert_education_many) > 0)
                                   @php
                                         $educations =   ['1'=>'ป.ตรี','2'=>'ป.โท','3'=>'ป.เอก'];
                                   @endphp
                                          @foreach ($registerexperts->expert_education_many as $key => $education)
                                                 <tr>
                                                        <td class="text-center">
                                                               {{ ($key+1)}}
                                                        </td>
                                                        <td>
                                                          {!! Form::text('detail[year][]', $education->year ?? null,  ['class' => 'form-control  autofill', 'maxlength' => 4, 'placeholder' => 'ปีที่สำเร็จ', 'disabled' => true]) !!}
                                                        </td>
                                                        <td>
                                                               {!! Form::text('detail[education_id][]', array_key_exists($education->education_id,$educations) ? $educations[$education->education_id] : null,  ['class' => 'form-control  autofill', 'maxlength' => 255, 'placeholder' => 'วุฒิการศึกษา', 'disabled' => true]) !!}
                                                             
                                                        </td>
                                                        <td>
                                                               {!! Form::text('detail[academy][]',  $education->academy ??  null,  ['class' => 'form-control autofill', 'maxlength' => 255, 'placeholder' => 'สถานศึกษา', 'disabled' => true]) !!}
                                                        </td>
                                                        <td class="text-center">
                                                               @if (isset($education) && !is_null($education->AttachFileEducationTo))
                                                                      @php
                                                                             $attach = $education->AttachFileEducationTo;
                                                                      @endphp
                                                                             <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                                                                             title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" class="a_file_education" >
                                                                                    {!! HP::FileExtension($attach->filename)  ?? '' !!}
                                                                             </a>
                                                               @endif
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