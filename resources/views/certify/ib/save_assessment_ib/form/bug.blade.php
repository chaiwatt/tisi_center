<div class="row">
    <div class="col-sm-12 m-t-15" v-if="isTable">
        <table class="table color-bordered-table primary-bordered-table no-hover-animate">
            <thead>
            <tr>
                <th class="text-center" width="2%">ลำดับ</th>
                <th class="text-center" width="10%">รายงานที่</th>
                <th class="text-center" width="18%">ผลการประเมินที่พบ</th>
                {{-- <th class="text-center" width="10%">มอก. 17025 : ข้อ</th>
                <th class="text-center" width="15%">ประเภท</th> --}}
                <th class="text-center" width="20%" >แนวทางการแก้ไข</th>
                <th class="text-center" width="12%" >ผลการประเมิน</th>
                <th class="text-center" width="13%" >หลักฐาน</th>
            </tr>
            </thead>
            <tbody  id="table_body">
                @if(count($assessment->CertiIBBugMany) > 0)
                @foreach($assessment->CertiIBBugMany as $key => $item)
                @php
                    $type =   ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต'];
                    $status = '';
                    if($item->status == 1){
                       $status = 'check_readonly';
                    }
                    $file_status = '';
                    if($item->file_status == 1){
                       $file_status = 'check_readonly';
                    }
                @endphp
                <tr>
                    <td class="text-center">
                        {{$key+1}}
                    </td>
                    {{-- <td>
                        {!! Form::hidden('id[]',!empty($item->id)?$item->id:null, ['class' => 'form-control'])  !!}
                       {!! Form::text('report[]', $item->report ?? null,  ['class' => 'form-control ','disabled'=>true])!!}
                    </td>
                    <td>
                  
                        {!! Form::text('notice[]', $item->remark ?? null,  ['class' => 'form-control notice','disabled'=>true])!!}
                    </td>
                    <td>
                         {!! Form::text('details[]', $item->details ?? null,  ['class' => 'form-control','disabled'=>true])!!}
                    </td> --}}
                    <td style="padding: 0px;">
                        <input type="hidden" name="id[]" value="{{ !empty($item->id) ? $item->id : '' }}" class="form-control">
                        <textarea name="report[]" class="form-control non-editable auto-expand" style="border-right: 1px solid #ccc;" >{{ $item->report ?? '' }}</textarea>
                    </td>
                    <td style="padding: 0px;">
                        <textarea name="notice[]" class="form-control non-editable notice auto-expand" style="border-left: none; border-right: 1px solid #ccc;" >{{ $item->remark ?? '' }}</textarea>
                    </td>
                    
                    <td style="padding: 0px;">
                        <textarea name="details" class="form-control non-editable  auto-expand" style="border-left: none; border-right: 1px solid #ccc;"  >{{ $item->details ?? '' }}</textarea>
                    </td>

                    {{-- <td  class="text-center">
                          <label>{!! Form::checkbox('status['.$item->id.']', '1', !empty($item->status == 1 ) ? true : false,
                           ['class'=>"check checkbox_status $status assessment_results",'data-checkbox'=>"icheckbox_flat-green", "data-key"=>($key+1)]) !!} &nbsp;ผ่าน &nbsp;
                           </label>
                   </td> --}}
                   <td class="text-center" style="vertical-align: top">
                    <label>
                        <input type="checkbox" name="status[{{ $item->id }}]" value="1" 
                            class="check checkbox_status {{ $status }} assessment_results" 
                            data-checkbox="icheckbox_flat-green" 
                            data-key="{{ $key+1 }}"
                            {{ !empty($item->status) && $item->status == 1 ? 'checked' : '' }}>
                        &nbsp;ผ่าน &nbsp;
                    </label>
                </td>
                   <td  class="text-center">

                       @if(!is_null($item->attachs))
                            <a href="{{url('certify/check/file_ib_client/'.$item->attachs.'/'.( !empty($item->attach_client_name) ? $item->attach_client_name :   basename($item->attachs) ))}}" 
                                    title="{{ !empty($item->attach_client_name) ? $item->attach_client_name :  basename($item->attachs) }}" target="_blank">
                                    {!! HP::FileExtension($item->attachs)  ?? '' !!}
                            </a>
                           &nbsp;&nbsp;&nbsp;  
                           <label>
                               {!! Form::checkbox('file_status['.$item->id.']', '1', !empty($item->file_status == 1 ) ? true : false, 
                                ['class'=>"check $file_status file_status",'data-checkbox'=>"icheckbox_flat-green", "data-key"=>($key+1)]) !!} 
                                &nbsp;ผ่าน &nbsp;
                          </label>
                        @endif

                   </td>
                 </tr>
                   @endforeach
                  @endif
            </tbody>
        </table>
    </div>
</div>
<div class="row" id="div_comment">
    <div class="col-sm-12">ระบุข้อคิดเห็น (ผลการประเมิน) :</div>
    <div class="col-sm-12">
        <table class="table color-bordered-table primary-bordered-table no-hover-animate">
            <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    <th class="text-center" width="30%">ผลการประเมินที่พบ</th>
                    <th class="text-center" width="38%">ข้อคิดเห็นของคณะผู้ตรวจประเมิน</th>
                    <th class="text-center" width="30%">สาเหตุ</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @if(count($assessment->CertiIBBugMany) > 0)
                @foreach($assessment->CertiIBBugMany as $key => $item)
                        @if($item->status != 1)
                            <tr>
                                <td class="text-center" style="padding: 0px">
                                    {{$key+1}}
                                </td>
                                <td style="padding: 0px;pointer-events: none;opacity: 0.6;">
                                    {{ $item->remark ?? null }}
                                </td>
                                <td style="padding: 0px">
                                    <input type="hidden" class="type_itme" value="{{$item->id}}">
                                    {{-- {!! Form::textarea('comment['.$item->id.']',null, [ 'class' => 'form-control','rows' => 3,'required'=>true]) !!}  --}}
                                    <textarea name="comment[{{ $item->id }}]" class="form-control auto-expand" style="border-right: 1px solid #ccc;"  rows="5" required></textarea>
                                </td>
                                <td style="padding: 0px">
                                    <textarea name="cause[{{ $item->id }}]" class="form-control auto-expand" style="border-left: none; border-right: 1px solid #ccc;" rows="5" required></textarea>
                                </td>
                            </tr>
                        @endif
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


<div class="row" id="div_file_comment">
    <div class="col-sm-12 text-right">ระบุข้อคิดเห็น (หลักฐาน) :</div>
    <div class="col-sm-12">
        <table class="table color-bordered-table primary-bordered-table no-hover-animate">
            <thead>
                <tr>
                    <th class="text-center" width="2%">ลำดับ</th>
                    <th class="text-center" width="30%">ผลการประเมินที่พบ</th>
                    <th class="text-center" width="38%">ข้อคิดเห็นของคณะผู้ตรวจประเมิน</th>
                    <th class="text-center" width="30%">สาเหตุ</th>
                </tr>
            </thead>
            <tbody id="table_body_file">
                @if(count($assessment->CertiIBBugMany) > 0)
                @foreach($assessment->CertiIBBugMany as $key => $item)
                        @if($item->status == 1 &&   $item->file_status != 1)
                            <tr>
                                <td class="text-center" style="padding: 0px">
                                    {{$key+1}}
                                </td>
                                <td style="padding: 0px">
                                    {{ $item->remark ?? null }}
                                </td>
                                <td>
                                     <input type="hidden" class="type_itme" value="{{$item->id}}">
                                     {{-- {!! Form::textarea('file_comment['.$item->id.']', null ,  ['class' => 'form-control file_comment','rows' => 3,'required'=>true])!!} --}}
                                     <textarea name="file_comment[{{ $item->id }}]" class="form-control file_comment auto-expand" style="border-right: 1px solid #ccc;" rows="5" required></textarea>
                                </td>
                                <td style="padding: 0px">
                                    <textarea name="cause[{{ $item->id }}]" class="form-control auto-expand" style="border-left: none; border-right: 1px solid #ccc;" rows="5" required></textarea>
                                </td>
                            </tr>
                        @endif
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="row div_hide_show_scope">
    <div class="col-md-12">
         {{-- <div class="white-box"> --}}
{{-- 
            <div class="row ">
                <div class="col-sm-4 text-right"><span class="text-danger">*</span>รายงานปิด Car  :</div>
                <div class="col-sm-6">
                    @if(isset($assessment)  && !is_null($assessment->FileAttachAssessment4To)) 
                    <p id="RemoveFlieScope">
                        <a href="{{url('certify/check/file_ib_client/'.$assessment->file.'/'.( !empty($assessment->FileAttachAssessment4To->file_client_name) ? $assessment->FileAttachAssessment4To->file_client_name : 'null' ))}}" 
                            title="{{ !empty($assessment->FileAttachAssessment4To->file_client_name) ? $assessment->FileAttachAssessment4To->file_client_name :  basename($assessment->FileAttachAssessment4To->file) }}" target="_blank">
                          {!! HP::FileExtension($assessment->FileAttachAssessment4To->file)  ?? '' !!}
                       </a>
                    </p>
                    @else 
                       <div class="fileinput fileinput-new input-group" data-provides="fileinput" >
                        <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                            <input type="file" name="file_car" class="report_scope check_max_size_file" >
                            </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                        </div>
                    @endif
                </div>
            </div> --}}
            
            <div class="form-group" id="div_file_scope">
 
     <div class="row form-group" id="div_details" hidden>
         <div class="col-md-12">
             <div class="white-box" style="border: 2px solid #e5ebec;">
             <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>   
                   
                <div class="row">
                    <div class="col-md-12 ">
                        <div id="other_attach-box">
                        @if(!is_null($assessment) && (count($assessment->FileAttachAssessment2Many) > 0 ) )
                            @foreach($assessment->FileAttachAssessment2Many as  $key => $item)
                              <p id="remove_attach_all{{$item->id}}">
                                <a href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                                    title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                                     {!! HP::FileExtension($item->file)  ?? '' !!}
                                </a>   
                              </p>
                            @endforeach
                        @else 
                            <div class="form-group other_attach_scope">
                                <div class="col-md-4 text-right">
                                    <label class="attach_remove"><span class="text-danger">*</span>Scope  </label>
                                </div>
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file"  name="file_scope[]" class="file_scope_required check_max_size_file">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div class="col-md-2 text-left">
                                    <button type="button" class="btn btn-sm btn-success attach_remove" id="attach_add_scope">
                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                    </button>
                                    <div class="button_remove_scope"></div>
                                </div> 
                            </div>
                        @endif
                          
                           </div>
                     </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12 ">
                        <div id="other_attach_report">
                            @if(!is_null($assessment) && (count($assessment->FileAttachAssessment3Many) > 0 ) )
                            @foreach($assessment->FileAttachAssessment3Many as  $key => $item)
                              <p id="remove_attach_all{{$item->id}}">
                                <a href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                                    title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                                    {!! HP::FileExtension($item->file)  ?? '' !!}
                                </a>   
                              </p>
                            @endforeach
                          @else 
                          <div class="form-group other_attach_report">
                            <div class="col-md-4 text-right">
                                <label class="attach_remove"><!--<span class="text-danger">*</span>--> สรุปรายงานการตรวจทุกครั้ง </label>
                            </div>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file"  name="file_report[]" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-md-2 text-left">
                                <button type="button" class="btn btn-sm btn-success attach_remove" id="attach_add_report">
                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                </button>
                                <div class="button_remove_report"></div>
                            </div> 
                         </div>
                          @endif

                           </div>
                     </div>
                </div> --}}
    
            </div>
        </div>
    </div>         
            </div>


        {{-- </div> --}}
    </div>     
 </div> 

@push('js')
{{-- <script>
    $(document).ready(function () {
        $("input[name=main_state]").on("ifChanged", function(event) {
            main_state();
          });
        function main_state(){
              var row = $("input[name=main_state]:checked").val();
              if(row == "1"){ 
                 $('#div_details').show();
                 $('.file_scope_required').prop('required', true);
              } else{
                $('#div_details').hide();
                $('.file_scope_required').prop('required', false);
              }
          }
    });

</script> --}}
<script>
    $(document).ready(function(){

        function autoExpand(textarea) {
                    textarea.style.height = 'auto'; // รีเซ็ตความสูง
                    textarea.style.height = textarea.scrollHeight + 'px'; // กำหนดความสูงตามเนื้อหา
                }
    
                // ฟังก์ชันปรับขนาด textarea ทุกตัวในแถวเดียวกัน
                function syncRowHeight(textarea) {
                    let $row = $(textarea).closest('tr'); // หา tr ที่ textarea อยู่
                    let maxHeight = 0;
    
                    // วนลูปหา maxHeight ใน textarea ทุกตัวในแถว
                    $row.find('.auto-expand').each(function () {
                        this.style.height = 'auto'; // รีเซ็ตความสูงก่อนคำนวณ
                        let currentHeight = this.scrollHeight;
                        if (currentHeight > maxHeight) {
                            maxHeight = currentHeight;
                        }
                    });
    
                    // กำหนดความสูงให้ textarea ทุกตัวในแถวเท่ากัน
                    $row.find('.auto-expand').each(function () {
                        this.style.height = maxHeight + 'px';
                    });
                }
    
                // ดักจับ event input
                $(document).on('input', '.auto-expand', function () {
                    // console.log('aha');
                    autoExpand(this); // ปรับ textarea ที่มีการเปลี่ยนแปลง
                    syncRowHeight(this); // ปรับ textarea ทั้งแถว
                });
    
                // ปรับขนาดทุก textarea เมื่อโหลดหน้าเว็บ
                $('.auto-expand').each(function () {
                    autoExpand(this);
                    syncRowHeight(this);
                });
        
            //   ResetTableFileNumber();
            check_max_size_file();
        $('.div_hide_show_scope').hide();
        $(".file_status").on("ifChanged",function(){
            var itme =   $(this).parent().parent().parent().parent().find('input[type="hidden"]').val();
            if($(this).prop('checked')){
                $('#table_body_file').find('.type_itme[value="'+itme+'"]').parent().parent().remove();
            }else{
             var notice_id =   $(this).parent().parent().parent().parent().find('.notice').val();
             let key = $(this).data('key');
             var table = $('#table_body_file');
             var  html = [];
                  html += '<tr>';
                  html += '<td class="text-center">'+key+'</td>';
                  html += '<td>'+notice_id+'</td>';
                  html += '<td> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="file_comment['+itme+']" rows="5" style="border-right: 1px solid #ccc;" required  class="form-control file_comment auto-expand"> </textarea></td>';
                  html += '<td> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="cause['+itme+']" rows="5" style="border-left: none; border-right: 1px solid #ccc;" required  class="form-control auto-expand"> </textarea></td>';
                  html += '</tr>';
                  table.append(html);
            }
            // html += '<td style="padding: 0px"> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="cause['+itme+']" class="form-control auto-expand" style="border-left: none; border-right: 1px solid #ccc;"  rows="5"  required > </textarea> </td>';
           
            // ResetTableFileNumber();
            // 
            let file_status =  $(".file_status:checked").length;
            let notice = '{{ !empty($assessment->CertiIBBugMany) ? count($assessment->CertiIBBugMany) : 0 }}';
            // console.log('notice',notice)
            if(file_status == notice){  
                $('.div_hide_show_scope').show();
                $('#div_file_comment').hide();
                $('.status_bug_report').hide();
                $('.report_scope').prop('required', true);
                $('.file_scope_required').prop('required', true);
                $('#assessment_passed').val("1")
            }else{
                $('#div_file_comment').show();
                $('.div_hide_show_scope').hide();
                $('.status_bug_report').show();
                $('.report_scope').prop('required', false);
                $('.file_scope_required').prop('required', false);
                $('#assessment_passed').val("0")
            } 

         });

        let file_status =    $('#table_body').find('.file_status:not(:checked)').length;

        console.log('file_status',file_status)
        if(file_status > 0){
            $('#div_file_comment').show();   
            $('.file_comment').prop('required', true);
        }else{
            $('#div_file_comment').hide();   
            $('.file_comment').prop('required', false);
        }

        let results =  $(".assessment_results:checked").length;
            let notice = '{{ !empty($assessment->CertiIBBugMany) ? count($assessment->CertiIBBugMany) : 0 }}';
            if(results == notice){
                
                $('#div_comment').hide();
            }

           //รีเซตเลขลำดับ
        // function ResetTableFileNumber(){
        //     var rows = $('#table_body_file').children(); //แถวทั้งหมด
        //     rows.each(function(index, el) {
        //         //เลขรัน
        //         $(el).children().first().html(index+1);
        //     });
        //     }

       

      $("#button_audit_report").click(function(){
          let row =  $(this).parent();
          var  html = [];
          let id = '{{ !empty($find_notice->id) ?  $find_notice->id : null }}';
          if(id != null  && confirm("ยืนยันการลบหลักฐาน !")){
               $("#audit_report").show(); 
                $.ajax({
                url: "{!! url('certify/save_assessment/remove_file') !!}" + "/" + id
                 }).done(function( object ) {
                    if(object.status = true){
                        row.remove(); 
                        html += '<div class="fileinput fileinput-new input-group " data-provides="fileinput">';
                        html += '<div class="form-control" data-trigger="fileinput">';
                        html += '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                        html += '<span class="fileinput-filename"></span>';
                        html += '</div>';
                        html += '<span class="input-group-addon btn btn-default btn-file">';
                        html += ' <span class="fileinput-new">เลือกไฟล์</span>';
                        html += ' <span class="fileinput-exists">เปลี่ยน</span>';
                        html += ' <input type="file" name="file" required class="input_required"></span>';
                        html += ' <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a></div>';
                        $("#audit_report").html(html); 
                        check_max_size_file();
                    }else{
                        alert('ไม่สามารถลบหลักฐานได้ !');  
                    }
               });
           }
      });





    });

    function remove_attachs(keys){
          let id = '{{ !empty($find_notice->id) ?  $find_notice->id : null }}';
          if(id != null  && confirm("ยืนยันการลบหลักฐาน !")){
               $("#audit_report").show(); 
                $.ajax({
                url: "{!! url('certify/save_assessment/remove_attachs') !!}" + "/" + id  + "/" +  keys
                 }).done(function( object ) {
                    if(object.status = true){
                        $('#remove_attachs').find('.attachs'+keys).remove();
                    }else{
                        alert('ไม่สามารถลบหลักฐานได้ !');  
                    }
               });
           }
      }

      function remove_file_car(keys){
          let id = '{{ !empty($find_notice->id) ?  $find_notice->id : null }}';
          if(id != null  && confirm("ยืนยันการลบหลักฐาน !")){
               $("#audit_report").show(); 
                $.ajax({
                url: "{!! url('certify/save_assessment/remove_file_car') !!}" + "/" + id  + "/" +  keys
                 }).done(function( object ) {
                    if(object.status = true){
                        $('#remove_file_car').find('.attachs'+keys).remove();
                    }else{
                        alert('ไม่สามารถลบหลักฐานได้ !');  
                    }
               });
           }
      }
    </script>
 <script>

   jQuery(document).ready(function() {

            // ResetTableNumber();
        //  รายงานข้อบกพร่อง
        $(".checkbox_status").on("ifChanged",function(){
              var itme =   $(this).parent().parent().parent().parent().find('input[type="hidden"]').val();
              var notice =   $(this).parent().parent().parent().parent().find('.notice').val();
              let key = $(this).data('key');
                if($(this).prop('checked')){
                    $('#table-body').find('.type_itme[value="'+itme+'"]').parent().parent().remove();
                }else{
                    radio_status(itme,notice,key);
                }
                // ResetTableNumber();
             });
          
         function radio_status(itme,notice,key){
            var table = $('#table-body');
                 var  html = [];
                      html += '<tr>';
                      html += '<td class="text-center">'+key+'</td>';
                      html += '<td>'+notice+'</td>';
                      html += '<td style="padding: 0px"> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="comment['+itme+']" class="form-control auto-expand" style="border-right: 1px solid #ccc;"  rows="5" required ></textarea></td>';
                      html += '<td style="padding: 0px"> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="cause['+itme+']" class="form-control auto-expand" style="border-left: none; border-right: 1px solid #ccc;"  rows="5"  required ></textarea></td>';
                      html += '</tr>';
                      table.append(html);
                // ResetTableNumber();
                // <textarea name="cause[{{ $item->id }}]" class="form-control auto-expand" style="border-left: none; border-right: 1px solid #ccc;" rows="5" required></textarea>
        }
 
        //รีเซตเลขลำดับ
        // function ResetTableNumber(){
        //   var rows = $('#table-body').children(); //แถวทั้งหมด
        //   rows.each(function(index, el) {
        //     //เลขรัน
        //     $(el).children().first().html(index+1);
        //   });
        // }
 
   });
  </script>
@endpush
