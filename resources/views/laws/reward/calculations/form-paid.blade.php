<div class="form-group">
      <div class="col-md-12">
           <div class="table">
                <table class="table table-striped table-bordered"  >
                     <thead>
                          <tr>
                              <th class="text-center text-top" width="2%">#</th>
                              <th class="text-center text-top" width="20%">ชื่อ-สกุล</th>
                              <th class="text-center text-top" width="25%">ที่อยู่<br>(สำหรับออกใบสำคัญรับเงินรางวัล)</th>
                              <th class="text-center text-top" width="15%">หน่วยงาน</th>
                              <th class="text-center text-top" width="15%">ชื่อบัญชี/เลขที่บัญชี</th>
                              <th class="text-center text-top" width="15%">กลุ่มผู้มีส่วนร่วมในคดี</th>
                              <th class="text-center text-top show_tag_a" width="15%">จัดการ</th>
                          </tr>
                       </thead>
                       <tbody id="table_tbody_paid">
                              @if (count($staff_lists) > 0)
                               @foreach ($staff_lists as  $key =>$item)
                                    <tr  >
                                          <td class="text-center text-top">
                                                {{$key+1}}
                                          </td>
                                          <td class="text-top font-medium-6 ">
                                                {{ !empty($item->name) ? $item->name : null }}
                                                {{-- {!! !! !empty($item->taxid) ? '<br/>'.$item->taxid : null !!} --}}
                                          </td>
                                          <td class="text-top font-medium-6 ">
                                                {{ !empty($item->address) ? $item->address : null }}
                                          </td>
                                          <td class="text-top font-medium-6 ">
                                                @if ($item->depart_type == 2)
                                                   {{ !empty($item->law_deparment->title_short) ? $item->law_deparment->title_short : null }}
                                                @else  
                                                   {{ !empty($item->sub_department->sub_depart_shortname) ? $item->sub_department->sub_depart_shortname : null }}
                                                @endif
                                             
                                          </td>
                                          <td class="text-top font-medium-6 ">
                                                {{ !empty($item->bank_account_name) && !empty($item->bank_account_number)  ? $item->bank_account_name.' : เลขที่'.$item->bank_account_number : null }}
                                          </td>
                                          <td class="text-top font-medium-6 ">
                                                {{ (!empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : (!empty($item->reward_group->title) ? $item->reward_group->title : null)) }}

                                                @if (!empty($item->file_law_attach_calculations_to))
                                                @php
                                                    $attach = $item->file_law_attach_calculations_to;
                                                    $url = url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)));
                                                @endphp
                                                      <span class="a_file_attach" >
                                                            <a href="{!! $url !!}"  target="_blank">
                                                                  {!! HP::FileExtension($attach->url) ?? '' !!}
                                                              </a>
                                                      </span>
                                                @elseif (!empty($item->file_book_bank))
                                                      @php
                                                      $attach = $item->file_book_bank;
                                                       @endphp
                                                      <span class="a_file_attach" >
                                                            <a href="{!!  HP::getFileStorage($attach->url) !!}"  target="_blank">
                                                                  {!! HP::FileExtension($attach->url) ?? '' !!}
                                                            </a>
                                                      </span>
                                                      <input type="hidden" value="{{$attach->id}}" class="attach_ids"/>
                                                 @endif
                                          </td> 
                                          <td class="text-center text-top font-medium-6 show_tag_a">
                                                <i class="pointer fa fa-pencil text-primary icon-pencil" data-krys="{{$key+1}}" style="font-size: 1.5em;"></i>
                                                <i class="pointer fa fa-remove text-danger icon-remove"  data-krys="{{$key+1}}"  style="font-size: 1.5em;"></i>

                                                <input type="hidden"  class="id"  value="{{ !empty($item->id) ? $item->id : null }}">
                                                <input type="hidden"  class="keys"  value="{{ $key }}">
                                                <input type="hidden"  class="basic_reward_group_id"  value="{{ !empty($item->basic_reward_group_id) ? $item->basic_reward_group_id : null }}">
                                                <input type="hidden"  class="depart_type"  value="{{ !empty($item->depart_type) ? $item->depart_type : null }}">
                                             
                                                <input type="hidden"  class="sub_department_id"  value="{{ !empty($item->sub_department_id) ? $item->sub_department_id : null }}">  
                                                <input type="hidden"  class="basic_department_id"  value="{{ !empty($item->basic_department_id) ? $item->basic_department_id : null }}">  
                                                <input type="hidden"  class="taxid"  value="{{ !empty($item->taxid) ? $item->taxid : null }}">  
                                                <input type="hidden"  class="name"  value="{{ !empty($item->name) ? $item->name : null }}">   
                                                <input type="hidden"  class="address"  value="{{ !empty($item->address) ? $item->address : null }}">  
                                                <input type="hidden"  class="mobile"  value="{{ !empty($item->mobile) ? $item->mobile : null }}">  
                                                <input type="hidden"  class="email"  value="{{ !empty($item->email) ? $item->email : null }}">  
                                                <input type="hidden"  class="basic_bank_id"  value="{{ !empty($item->basic_bank_id) ? $item->basic_bank_id : null }}">  
                                                <input type="hidden"  class="basic_bank_name"  value="{{ (!empty($item->basic_bank_name) ? $item->basic_bank_name : ( !empty($item->ac_bank->title) ? $item->ac_bank->title : null)) }}">  
                                                <input type="hidden"  class="bank_account_name"  value="{{ !empty($item->bank_account_name) ? $item->bank_account_name : null }}">  
                                                <input type="hidden"  class="bank_account_number"  value="{{ (!empty($item->bank_account_number) ? $item->bank_account_number :  null) }}">  
                                          </td>
                                    </tr>
                               @endforeach
                              @endif
                      </tbody> 
                 </table>
           </div>
     </div>
     <p class="font-medium-6 text-muted">&nbsp;&nbsp;หมายเหตุ : กรุณาตรวจสอบข้อมูลให้ถูกต้อง เนื่องจากเป็นข้อมูลสำหรับออกใบสำคัญรับเงินรางวัล</p>
</div>

@push('js')
 
    <script>

        $(document).ready(function() {
            
   
                   //ลบแถว
                   $('body').on('click', '.icon-remove', function(){
                         $(this).parent().parent().remove();
                         ResetTablePaidNumber();
                         save_paid();
                    });
                    // แก้ไข
                    $('body').on('click', '.icon-pencil', function(){

                        $('#form_paid').find('ul.parsley-errors-list').remove();
                        $('#form_paid').find('input, select, textarea').val('');
                        $('#form_paid').find('select').select2();
                        $('#form_paid').find('input,textarea').removeClass('parsley-success');
                        $('#form_paid').find('input,textarea').removeClass('parsley-error');



                        var rows =  $(this).parent().parent();
    
                        // id
                        var id = rows.find('.id').val();
                        if(checkNone(id)){
                              $('#modal_id').val(id);
                        }

                          // keys
                         var keys = rows.find('.keys').val();
                        if(checkNone(keys)){
                              $('#modal_keys').val(keys);
                        }
                        // ส่วนร่วมในคดี
                        var basic_reward_group_id = rows.find('.basic_reward_group_id').val();
                        if(checkNone(basic_reward_group_id)){
                              $('#basic_reward_group_id').val(basic_reward_group_id);
                              $('#basic_reward_group_id').select2();
                        }

                        // หน่วยงาน
                        var depart_type = rows.find('.depart_type').val();
                        if(checkNone(depart_type)){
                              $('#depart_type').val(depart_type);
                              $('#depart_type').select2();
                              depart_types();
                        }
                    
                        $('#sub_department_id,#basic_department_id').val('').select2();
                        if($('#depart_type').val() == '2'){
                              $('#span_basic_department_id').show();
                              $('#span_sub_department_id').hide();
                              $('.departmen').show(); 
                              $('#sub_department_id').prop('required' ,false );     
                              $('#basic_department_id').prop('required' ,true);     
                        }else  if($('#depart_type').val() == '1'){
                              $('#span_sub_department_id').show();
                              $('#span_basic_department_id').hide();
                              $('.departmen').show(); 
                              $('#sub_department_id').prop('required' ,true);     
                              $('#basic_department_id').prop('required' ,false);     
                        }else{ 
                              $('.departmen').hide(); 
                              $('#sub_department_id,#basic_department_id').prop('required' ,false);     
                        }

                        // กอง/กลุ่ม (กรณีภายใน)
                        var sub_department_id = rows.find('.sub_department_id').val();
                        if(checkNone(sub_department_id)){

                              $('#sub_department_id').val(sub_department_id);
                              $('#sub_department_id').select2();
                        }
 
                        // กอง/กลุ่ม (กรณีภายนอก)
                        var basic_department_id = rows.find('.basic_department_id').val();
                        if(checkNone(basic_department_id)){
                              $('#basic_department_id').val(basic_department_id);
                              $('#basic_department_id').select2();
                        }

                        // เลขประจำตัวประชาชน
                        var taxid = rows.find('.taxid').val();
                        if(checkNone(taxid)){
                              $('#taxid').val(taxid);
                        }

                        //  ชื่อ-สกุล
                        var name = rows.find('.name').val();
                        if(checkNone(name)){
                              $('#name').val(name);
                        }
 
                        // ที่อยู่
                        var address = rows.find('.address').val();
                        if(checkNone(address)){
                              $('#address').val(address);
                        }

                        // เบอร์มือถือ
                        var mobile = rows.find('.mobile').val();
                        if(checkNone(mobile)){
                              $('#mobile').val(mobile);
                        }
         
                        // อีเมล
                        var email = rows.find('.email').val();
                        if(checkNone(email)){
                              $('#email').val(email);
                        }
     
                        // ชื่อธนาคาร
                        var basic_bank_id = rows.find('.basic_bank_id').val();
                        if(checkNone(basic_bank_id)){
                              $('#basic_bank_id').val(basic_bank_id);
                              $('#basic_bank_id').select2();
                        }

                        // ชื่อบัญชี
                        var bank_account_name = rows.find('.bank_account_name').val();
                        if(checkNone(bank_account_name)){
                              $('#bank_account_name').val(bank_account_name);
                        }
         
                        // เลขที่บัญชี
                        var bank_account_number = rows.find('.bank_account_number').val();
                        if(checkNone(bank_account_number)){
                              $('#bank_account_number').val(bank_account_number);
                        }
                        
                        $('#div_attach_modal').html('');  
                        var a_file_attach = rows.find('.a_file_attach').html();
                        if(checkNone(a_file_attach)){
                              $('#div_attach_modal').html(a_file_attach);  
                        }
                      
                        // ไฟล์หลักฐาน
                        var attach_ids = rows.find('.attach_ids').val();
                        if(checkNone(attach_ids)){
                              $('#attach_ids').val(attach_ids);
                        }
                        

                        $('#PaidModals').modal('show');
                        
                    });
             




               ResetTablePaidNumber();
                
        });

        function ResetTablePaidNumber(){
                var rows = $('#table_tbody_paid').children(); //แถวทั้งหมด
                rows.each(function(index, el) {
                       var key = (index+1);
                        //เลขรัน
                        $(el).children().first().html(key);
                        $(el).find('.id').attr('name', 'staffs[id]['+index+']');
                       
                        $(el).find('.keys').val(index);
                        $(el).find('.basic_reward_group_id').attr('name', 'staffs[basic_reward_group_id]['+index+']');
                        $(el).find('.depart_type').attr('name', 'staffs[depart_type]['+index+']');
                        // $(el).find('.depart_name').attr('name', 'staffs[depart_name]['+index+']');
                        $(el).find('.sub_department_id').attr('name', 'staffs[sub_department_id]['+index+']');
                        $(el).find('.basic_department_id').attr('name', 'staffs[basic_department_id]['+index+']');
                        $(el).find('.taxid').attr('name', 'staffs[taxid]['+index+']');
                        $(el).find('.name').attr('name', 'staffs[name]['+index+']');
                        $(el).find('.address').attr('name', 'staffs[address]['+index+']');
                        $(el).find('.mobile').attr('name', 'staffs[mobile]['+index+']');
                        $(el).find('.email').attr('name', 'staffs[email]['+index+']');
                        $(el).find('.basic_bank_id').attr('name', 'staffs[basic_bank_id]['+index+']');
                        $(el).find('.basic_bank_name').attr('name', 'staffs[basic_bank_name]['+index+']');
                        $(el).find('.bank_account_name').attr('name', 'staffs[bank_account_name]['+index+']');
                        $(el).find('.bank_account_number').attr('name', 'staffs[bank_account_number]['+index+']');
                        $(el).find('.input_file_attach_name').attr('name', 'staffs[input_file_attach_name]['+index+']');
                        $(el).find('.file_attach').attr('name', 'staffs[file_attach]['+index+']');
                        $(el).find('.attach_ids').attr('name', 'staffs[attach_ids]['+index+']');
     
                }); 
 
         }
 
         function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        function depart_types() {
                     $('#sub_department_id,#basic_department_id').val('').select2();
                    if($('#depart_type').val() == '2'){
                        $('#span_basic_department_id').show();
                        $('#span_sub_department_id').hide();
                        $('.departmen').show(); 
                        $('#sub_department_id').prop('required' ,false );     
                        $('#basic_department_id').prop('required' ,true);  
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,true);    
                        $('.div_basic_bank').show();    
                    }else  if($('#depart_type').val() == '1'){
                        $('#span_sub_department_id').show();
                        $('#span_basic_department_id').hide();
                        $('.departmen').show(); 
                        $('#sub_department_id').prop('required' ,true);     
                        $('#basic_department_id').prop('required' ,false);  
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,false);    
                        $('.div_basic_bank').hide();      
                     }else{ 
                        $('.departmen').hide(); 
                        $('#sub_department_id,#basic_department_id').prop('required' ,false);     
                        $('#basic_bank_id, #bank_account_name, #bank_account_number').prop('required' ,true);    
                       $('.div_basic_bank').show(); 
                    }
        }

    </script>
@endpush
