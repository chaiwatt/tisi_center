

<div class="row">
    
    <div class="col-sm-12 m-t-15" v-if="isTable">
        <table class="table color-bordered-table primary-bordered-table no-hover-animate">
            <thead>
            <tr>
                <th class="text-center" width="2%">ลำดับ</th>
                <th class="text-center" width="10%">รายงานที่</th>
                <th class="text-center" width="18%">ผลการประเมินที่พบ</th>
                <th class="text-center" width="20%" >แนวทางการแก้ไข</th>
                <th class="text-center" width="12%" >ผลการประเมิน</th>
                <th class="text-center" width="13%" >หลักฐาน</th>
            </tr>
            </thead>
            <tbody  id="table_body">
                @if(count($find_notice->items) > 0)
                @foreach($find_notice->items as $key => $item)
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
                        <td class="text-center" style="padding: 0px;">
                            {{$key+1}}
                        </td>
                        <td style="padding: 0px;">
                            <input type="hidden" name="id[]" value="{{ !empty($item->id) ? $item->id : null }}" class="form-control">
                            <textarea name="report[]" class="form-control non-editable auto-expand" style="border-right: 1px solid #ccc;" >{{ $item->report ?? null }}</textarea>
                        </td>
                        <td style="padding: 0px;">
                            <textarea name="notice[]" class="form-control non-editable notice auto-expand" style="border-left: none; border-right: 1px solid #ccc;">{{ $item->remark ?? null }}</textarea>
                        </td>
                        <td style="padding: 0px;">
                            <textarea name="details[]" class="form-control non-editable auto-expand" style="border-left: none; border-right: 1px solid #ccc;">{{ $item->details ?? null }}</textarea>
                        </td>
                        <td class="text-center">
                            <label>
                                <input type="checkbox" name="status[{{ $item->id }}]" value="1" 
                                class="check checkbox_status {{ $status }} assessment_results" 
                                data-checkbox="icheckbox_flat-green" 
                                data-key="{{ $key+1 }}" {{ !empty($item->status == 1) ? 'checked' : '' }}>
                                &nbsp;ผ่าน &nbsp;
                            </label>
                        </td>
                        <td class="text-center">
                            {{-- {{$item->attachs}} --}}
                            @if(!is_null($item->attachs))
                                <a href="{{ url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name : 'null' )) }}" 
                                title="{{ !empty($item->attachs_client_name) ? $item->attachs_client_name : basename($item->attachs) }}" 
                                target="_blank">
                                {!! HP::FileExtension($item->attachs) ?? '' !!}
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" name="file_status[{{ $item->id }}]" value="1" 
                                    class="check {{ $file_status }} file_status" 
                                    data-checkbox="icheckbox_flat-green" 
                                    data-key="{{ $key+1 }}" {{ !empty($item->file_status == 1) ? 'checked' : '' }}>
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
    <div class="col-sm-12 text-left">ระบุข้อคิดเห็น (ผลการประเมิน) :</div>
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
                @if(count($find_notice->items) > 0)
                @foreach($find_notice->items as $key => $item)
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
                                    {{-- {!! Form::textarea('comment['.$item->id.']',null, [ 'class' => 'form-control','rows' => 3,'required'=>true]) !!} --}}
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
    <div class="col-sm-12 text-left">ระบุข้อคิดเห็น (หลักฐาน) :</div>
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
                @if(count($find_notice->items) > 0)
                @foreach($find_notice->items as $key => $item)
                        @if($item->status == 1 &&   $item->file_status != 1)
                            <tr>
                                <td class="text-center" style="padding: 0px">
                                    {{$key+1}}
                                </td>
                                <td style="padding: 0px">
                                    {{ $item->remark ?? null }}
                                </td>
                                <td style="padding: 0px">
                                    
                                     <input type="hidden" class="type_itme" value="{{$item->id}}">
                                     {{-- {!! Form::textarea('file_comment['.$item->id.']', null ,  ['class' => 'form-control file_comment auto-expand','rows' => 3,'required'=>true])!!} --}}
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

            {{-- <div class="row ">
                <div class="col-sm-4 text-right"><span class="text-danger">*</span>รายงานปิด Car  :</div>
                <div class="col-sm-6">
                    @if(isset($find_notice)  && !is_null($find_notice->file_car))
                    <p id="RemoveFlieScope">
                        <a href="{{url('certify/check/files/'.$find_notice->file_car.'/'.( !empty($find_notice->file_car_client_name) ? $find_notice->file_car_client_name : 'null' ))}}"
                            title="{{ !empty($find_notice->file_car_client_name) ? $find_notice->file_car_client_name :  basename($find_notice->file_car) }}" target="_blank">
                          {!! HP::FileExtension($find_notice->file_car)  ?? '' !!}
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

     <div class="row form-group" id="div_details">
         <div class="col-md-12">
             <div class="white-box" style="border: 2px solid #e5ebec;">
             <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>

                <div class="row">
                    <div class="col-md-12 ">
                        <div id="other_attach-box">
                        @if(!is_null($find_notice->file_scope))
                       
                            @php
                                $file_scope = json_decode($find_notice->file_scope);
                                // dd($file_scope) ;
                            @endphp


                            @if(!empty($file_scope) && is_array($file_scope))
                                @foreach($file_scope  as $key => $item)
                                    {{-- <p>Attachs: {{ $file->attachs }}</p>
                                    <p>File Client Name: {{ $file->file_client_name }}</p> --}}
                                    <p id="remove_attach_all{{$key}}">
                                        <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) ))}}"
                                            title="{{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) }}" target="_blank">
                                            {!! HP::FileExtension($item->attachs)  ?? '' !!} {{basename($item->attachs)}}
                                        </a>
                                    </p>
                                @endforeach
                            @endif

                            {{-- @foreach($file_scope as $key => $item)
                                @if(property_exists($item, 'id'))
                                    <p id="remove_attach_all{{$item->id}}">
                                        <a href="{{url('certify/check/files/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) ))}}"
                                            title="{{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs) }}" target="_blank">
                                            {!! HP::FileExtension($item->attachs)  ?? '' !!}
                                        </a>
                                    </p>
                                @endif
                            @endforeach --}}
                        @else
                            <div class="form-group other_attach_scope" >
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
                                            <input type="file"  name="file_scope[]" class=" check_max_size_file">
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


            </div>
        </div>
    </div>
            </div>


        {{-- </div> --}}
    </div>
 </div>

@push('js')

<script>
    $(document).ready(function(){


        $('.auto-expand').each(function () {
                autoExpand(this);
                syncRowHeight(this);
            });

        // ฟังก์ชันปรับขนาด textarea
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
            autoExpand(this); // ปรับ textarea ที่มีการเปลี่ยนแปลง
            syncRowHeight(this); // ปรับ textarea ทั้งแถว
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
                  html += '<td class="text-center" style="padding: 0px">'+key+'</td>';
                  html += '<td style="padding: 0px">'+notice_id+'</td>';
                  html += '<td style="padding: 0px"> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="file_comment['+itme+']" style="border-right: 1px solid #ccc;" rows="5" required  class="form-control auto-expand"> </textarea> </td>';
                  html += '<td style="padding: 0px"><textarea  name="cause['+itme+']" rows="5" required style="border-left: none; border-right: 1px solid #ccc;" class="form-control auto-expand"> </textarea> </td>';
                  html += '</tr>';
                  table.append(html);
            }

            // ResetTableFileNumber();
            //
            let file_status =  $(".file_status:checked").length;
            let notice = '{{ !empty($find_notice->items) ? count($find_notice->items) : 0 }}';
            if(file_status == notice){
                // $('.div_hide_show_scope').show();
                $('.status_bug_report').hide();
                $('#div_file_comment').hide();
                
                $('.report_scope').prop('required', true);
                $('.file_scope_required').prop('required', true);
                // console.log('wow all passed')
                // assessment_passed
                $('#assessment_passed').val("1")
            }else{
                // $('.div_hide_show_scope').hide();
                $('#div_file_comment').show();
                $('.status_bug_report').show();
                $('.report_scope').prop('required', false);
                $('.file_scope_required').prop('required', false);
                $('#assessment_passed').val("0")
            }

         });

        let file_status =    $('#table_body').find('.file_status:not(:checked)').length;
        if(file_status > 0){
            $('#div_file_comment').show();
            $('.file_comment').prop('required', true);
        }else{
            $('#div_file_comment').hide();
            $('.file_comment').prop('required', false);
        }

        let results =  $(".assessment_results:checked").length;
            let notice = '{{ !empty($find_notice->items) ? count($find_notice->items) : 0 }}';
            if(results == notice){
                $('#div_comment').hide();
            }







    });




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
                      html += '<td> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="comment['+itme+']" rows="5" style="border-right: 1px solid #ccc;" required  class="form-control auto-expand"> </textarea> </td>';
                      html += '<td> <input type="hidden" class="type_itme" value="'+itme+'">  <textarea  name="cause['+itme+']" rows="5" required style="border-left: none; border-right: 1px solid #ccc;" class="form-control auto-expand"> </textarea> </td>';
                      html += '</tr>';
                      table.append(html);
                // ResetTableNumber();
        }



   });
  </script>
@endpush
