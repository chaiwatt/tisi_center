@php      
    $subdepart_ids    = ['0600','0601','0602','0603','0604'];//เจ้าหน้าที่ กม.
    $subdepart_list      = json_encode($subdepart_ids);    

    //นิติกร
    $users            = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')->whereIn('reg_subdepart',$subdepart_ids)->get();    
    $users_list       = json_encode($users);     

    //ผู้รับมอบหมาย
    $users_assign     = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                ->whereHas('data_list_roles', function($query){
                                        $query->where('role_id', [49]);
                                })
                                ->whereIn('reg_subdepart',$subdepart_ids)
                                ->get();  
    $assign_list      = json_encode($users_assign);     

    $option_deparment = App\Models\Basic\SubDepartment::where('did',06)->orderbyRaw('CONVERT(sub_departname USING tis620)')->pluck('sub_departname', 'sub_id')
@endphp

<div class="modal fade" id="AssignModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="AssignModalLabel1">มอบหมาย</h4>
            </div>
            <div class="modal-body">
                <form id="form_assign" class="form-horizontal"  method="post" >

                    {{ csrf_field() }}

                    <div class="form-group required">
                        {!! HTML::decode(Form::label('sub_department_id', 'ค้นหารายชื่อจากกลุ่ม', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                        <div class="col-md-6">
                            {!! Form::select('sub_department_id', $option_deparment , null, ['class' => 'form-control', 'id'=>"sub_department_id", 'required' => true,'placeholder'=>'- เลือกกลุ่ม -']); !!}
                        </div>
                    </div>

                    <div class="form-group required">
                        {!! HTML::decode(Form::label('assign_id', 'ผู้รับมอบหมาย', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                        <div class="col-md-6">
                            {!! Form::select('assign_id', [] , null, ['class' => 'form-control', 'id'=>"assign_id", 'required' => true,'placeholder'=>'- เลือกผู้รับมอบหมาย -']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('status_show', 'ข้อมูลคดี', ['class' => 'col-md-3 control-label font-medium-6']) !!}
                        <div class="col-md-9">
                            <div class="table">
                                <table class="table table-striped"  >
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="48%">เลขที่อ้างอิง</th>
                                        <th class="text-center" width="50%">ผู้ประกอบการ/TAXID</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_tbody_assign">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <div class="col-md-offset-3 col-md-10">
                            <div class="checkbox checkbox-primary">
                                <input id="assign_checkall" type="checkbox">
                                <label for="assign_checkall">มอบหมายงานนิติกร</label>
                           </div>
                        </div>
                    </div> --}}

                    <div class="form-group"> 
                        {!! HTML::decode(Form::label('lawyer_check', 'มอบหมายงานนิติกร', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                        <div class=" col-md-9">
                            <label>{!! Form::radio('lawyer_check', '1', false  , ['class'=>'check', 'data-radio'=>'iradio_square-yellow', 'id'=>'lawyer_check_1']) !!}&nbsp; ภายใต้กลุ่มงาน &nbsp;</label>
                            <label>{!! Form::radio('lawyer_check', '2', false , ['class'=>'check', 'data-radio'=>'iradio_square-yellow','id'=>'lawyer_check_2']) !!}&nbsp; กลุ่มงานอื่นๆ &nbsp;</label>
                            {!! $errors->first('lawyer_check', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group lawyers_box">  
                        {!! HTML::decode(Form::label('lawyers', 'นิติกร', ['class' => 'col-md-3 control-label font-medium-6  text-right'])) !!}
                        <div class="col-md-6">
                            {!! Form::select('lawyers', [] , null, ['class' => 'form-control', 'id'=>"lawyer_ids", 'required' => false,'placeholder'=>'- เลือกนิติกร -']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-6">
                            <p class="font-medium-6"> {{ auth()->user()->FullName.' | '.HP::DateTimeThai(date('Y-m-d H:i:s')) }}</p>
                        </div>
                    </div>
        
                    <input type="hidden" name="assigns_id" id="assigns_id">

                    <div class="text-center">
                        <button type="submit"class="btn btn-primary" ><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    
    <script>
        $(document).ready(function () {

            var users_list = jQuery.parseJSON('{!! $users_list !!}');
            $('#lawyer_ids').html('<option value=""> - เลือกนิติกร - </option>');
            if( checkNone(users_list) ){
                $.each(users_list, function( index, data ) {
                    $('#lawyer_ids').append('<option value="'+data.id+'" data-subdepart="'+data.reg_subdepart+'" >'+data.title+'</option>');
                });                          
            }

            var assign_list = jQuery.parseJSON('{!! $assign_list !!}');  
            $('#assign_id').html('<option value=""> - เลือกผู้รับมอบหมาย - </option>');
            if( checkNone(assign_list) ){
                $.each(assign_list, function( index, data ) {
                    $('#assign_id').append('<option value="'+data.id+'" data-subdepart="'+data.reg_subdepart+'" >'+data.title+'</option>');
                });                          
            }

            $('#sub_department_id').change(function(){

                $("select[id='assign_id']").val('').change(); 
                $("select[id='assign_id'] option") .removeClass('show').addClass('hide');
                
                if($(this).val() == '0600'){
                    $("select[id='assign_id'] option[data-subdepart='" + $(this).val() + "']") .removeClass('hide').addClass('show');
                }else   if($(this).val() !== ''){
                    $("select[id='assign_id'] option[data-subdepart='" + $(this).val() + "']") .removeClass('hide').addClass('show');
                }else{
                    $("select[id='assign_id'] option[data-subdepart='" + $(this).val() + "']") .removeClass('hide').addClass('show');
                }

                if($('input[name="lawyer_check"]:checked').val() == '2'){
                      $("select[id='lawyer_ids'] option") .removeClass('hide').addClass('show');
                }else{
                     $("select[id='lawyer_ids']").val('').change(); 
                     $("select[id='lawyer_ids'] option") .removeClass('show').addClass('hide');
                        if($(this).val() == '0600'){
                        var subdepart_list = jQuery.parseJSON('{!! $subdepart_list !!}');
                        if( checkNone(subdepart_list) ){
                            $.each(subdepart_list, function( index, data ) {
                                $("select[id='lawyer_ids'] option[data-subdepart='" + data + "']") .removeClass('hide').addClass('show');
                            });                          
                        }
                    }else   if($(this).val() !== ''){
                        $("select[id='lawyer_ids'] option[data-subdepart='" + $(this).val() + "']") .removeClass('hide').addClass('show');
                    }else{
                        $("select[id='lawyer_ids'] option[data-subdepart='" + $(this).val() + "']") .removeClass('hide').addClass('show');
                    }
                }
              
            });


            // มอบหมาย
            $('#form_assign').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {

                           // Text
                   $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังมอบหมาย กรุณารอสักครู่..."
                   });
                var ids = [];
                //Iterate over all checkboxes in the table
                table.$('.item_checkbox:checked').each(function (index, rowId) {
                    ids.push(rowId.value);
                });

                 $.ajax({
                    method: "post",
                    url: "{{ url('law/cases/assigns/save_assign') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "ids": ids,
                        "id": $('#assigns_id').val(),
                        "sub_department_id": $('#sub_department_id').val(),
                        "assign_id": $('#assign_id').val(),
                        "lawyer_ids": $('#lawyer_ids').val(),
                        "lawyer_check":$('input[name="lawyer_check"]:checked').val()
                    }
                }).success(function (msg) {
                      $.LoadingOverlay("hide");
                    $('#form_assign').find('ul.parsley-errors-list').remove();
                    if (msg.message == true) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'บันทึกเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#checkall').prop('checked',false );
                        table.ajax.reload( null, false );
                        $('#AssignModals').modal('hide');
                        $("select[id='sub_department_id']").val('').change(); 
                        $("select[id='assign_id']").val('').change(); 
                        $("select[id='lawyer_ids']").val('').trigger('change');
                    }else{
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#AssignModals').modal('hide');
                        $("select[id='sub_department_id']").val('').change(); 
                        $("select[id='assign_id']").val('').change(); 
                        $("select[id='lawyer_ids']").val('').trigger('change');
                    }
                });

                return false;
            });

            $('input[name="lawyer_check"]').on('ifChecked', function (event) {

                     $("select[id='lawyer_ids']").val('').change(); 
                if($('input[name="lawyer_check"]:checked').val() == '2'){
                      $("select[id='lawyer_ids'] option") .removeClass('hide').addClass('show');
                }else{
                     $("select[id='lawyer_ids'] option") .removeClass('show').addClass('hide');
                    var sub_department_id = $('#sub_department_id').val();
                            if(sub_department_id == '0600'){
                                var subdepart_list = jQuery.parseJSON('{!! $subdepart_list !!}');
                                if( checkNone(subdepart_list) ){
                                    $.each(subdepart_list, function( index, data ) {
                                        $("select[id='lawyer_ids'] option[data-subdepart='" + data + "']") .removeClass('hide').addClass('show');
                                    });                          
                                }
                            }else   if(sub_department_id !== ''){
                                $("select[id='lawyer_ids'] option[data-subdepart='" + sub_department_id + "']") .removeClass('hide').addClass('show');
                            }else{
                                $("select[id='lawyer_ids'] option[data-subdepart='" +sub_department_id + "']") .removeClass('hide').addClass('show');
                      }
                }   
                
                   BoxLawyers();
            });

            // $('#assign_checkall').on('click', function(e) {
            //     $("select[id='lawyer_ids']").val('').trigger('change');
            //     if($(this).is(':checked',true)){
            //         BoxLawyers();
            //         $("select[id='lawyer_ids']").find('.show').prop("selected", "selected");
            //         $("select[id='lawyer_ids']").trigger("change");
            //     }else{
            //         BoxLawyers()
            //     }
            // });

            //   $('#department_checkall').on('click', function(e) {
            //         $("select[id='lawyer_ids']").val('').change(); 
            //        if($(this).is(':checked',true)){
            //             $("select[id='lawyer_ids'] option") .removeClass('hide').addClass('show');
            //        }else{
            //             $("select[id='lawyer_ids'] option") .removeClass('show').addClass('hide');
            //             var sub_department_id = $('#sub_department_id').val();
            //                if(sub_department_id != ''){
            //                     if(sub_department_id == '0600'){
            //                         var subdepart_list = jQuery.parseJSON('{!! $subdepart_list !!}');
            //                         if( checkNone(subdepart_list) ){
            //                             $.each(subdepart_list, function( index, data ) {
            //                                 $("select[id='lawyer_ids'] option[data-subdepart='" + data + "']") .removeClass('hide').addClass('show');
            //                             });                          
            //                         }
            //                     }else   if(sub_department_id !== ''){
            //                         $("select[id='lawyer_ids'] option[data-subdepart='" + sub_department_id + "']") .removeClass('hide').addClass('show');
            //                     }else{
            //                         $("select[id='lawyer_ids'] option[data-subdepart='" + sub_department_id + "']") .removeClass('hide').addClass('show');
            //                     }
            //                }else{
            //                     $("select[id='lawyer_ids'] option") .removeClass('show').addClass('hide');
            //                }
                        
            //        }
            //   });


            // BoxLawyers();
        });

    </script>
@endpush
