<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="MinusScopeModalLabel" aria-hidden="true" id="MinusScopeModal" >
    <div class="modal-dialog modal-dialog-centere modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title" id="MinusScopeModalLabel">ลดขอบข่าย</h4>
            </div>
            <div class="modal-body">
                <div class="row form-horizontal">
                    <div class="col-md-12">

                        <form class="form-horizontal" id="form_minus_scope" onsubmit="return false">

                            {!! Form::hidden('ibcb_id', $ibcb->id) !!}
                            {!! Form::hidden('_token', csrf_token()) !!}

                            <div class="row">
                                <div class="form-group required">
                                    {!! Form::label('mn_close_date', 'วันที่ลดขอบข่าย', ['class' => 'control-label text-right col-md-3']) !!}
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            {!! Form::text('mn_close_date', HP::revertDate(date('Y-m-d'),true),  ['class' => 'form-control mydatepicker', 'disabled']) !!}
                                            <span class="input-group-addon"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>                    
                            </div>

                            <div class="row">
                                <div class="form-group required">
                                    {!! Form::label('mn_close_remarks', 'หมายเหตุ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('mn_close_remarks', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'mn_close_remarks', 'required' => true ]) !!}
                                    </div>
                                </div>
                            </div>

                            <table class="table" id="table-minus-scope">
                                <thead>
                                    <tr>
                                        <th width="5%"></th>
                                        <th width="40%">สาขาผลิตภัณฑ์</th>
                                        <th width="35%">สาขาผลิตภัณฑ์</th>
                                        <th width="20%">วันที่หมดอายุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $ibcb->scopes_group->where('state', 1) as $group )
                                        @php
                                            $bs_branch_group = $group->bs_branch_group;
                                        @endphp
                            
                                        <tr>
                                            <td class="text-center text-top"><input type="checkbox" name="scope_id[]" class="scope_id_checkbox"  value="{!! $group->id !!}"></td>
                                            <td class="text-top">{!! !empty($bs_branch_group->title)?$bs_branch_group->title:null !!}</td>
                                            <td class="text-top">
                                                <ul>
                                                    @foreach ( $group->scopes_details as $Idetail )
                                                        @php
                                                            $bs_branch = $Idetail->bs_branch;
                                                        @endphp
                                                        <li> {!! !empty($bs_branch->title)?$bs_branch->title:null !!}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="text-top">{!! !empty($group->end_date)?HP::revertDate($group->end_date,true):null !!}</td>  
                                        </tr>
                                

                                    @endforeach
                                </tbody>
                            </table>

                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success waves-effect" id="save_minus_scope" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#save_minus_scope').click(function (e) {

                var scope =  $('#table-minus-scope').find(".scope_id_checkbox:checked").length
                if( scope >= 1 ){
                    $('#form_minus_scope').submit();
                }else{
                    alert('กรุณาเเลือกสาขาผลิตภัณฑ์ ?');
                }
                  
            });

            $('#form_minus_scope').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#form_minus_scope")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังบันทึกข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/ibcb/minus_scope') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                       
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#MinusScopeModal').modal('hide');

                            setTimeout(function() { 
                                location.reload(); 
                            }, 1500);
                    
                        }
                    }
                });

            });
        });
    </script>
@endpush