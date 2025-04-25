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

                        <form id="form_minus_scope">

                            {!! Form::hidden('lab_id', $labs->id) !!}
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
                                        {!! Form::textarea('mn_close_remarks', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'mn_close_remarks']) !!}
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="box_minus_scope"></div>
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
    <script>
        jQuery(document).ready(function() {
            
            //Open Modal
            $('#MinusScopeModal').on('show.bs.modal', function (e) {
                LoadScopeAction();
            });

            $(document).on('click', '#save_minus_scope', function (e) {

                var close_remarks = $('#mn_close_remarks').val();

                var id = [];
                $('.scope_id_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                if( checkNone(close_remarks)  ){

                    if( id.length > 0 ){

                        $.post( "{!! url('/section5/labs/minus_scope') !!}", $('#form_minus_scope').serialize())
                        .done(function( data ) {
                            if(data=='success'){
                                toastr.success('บันทึกข้อมูลสำเร็จ !');
                                LoadGroupScope();
                            }else{
                                toastr.error('บันทึกข้อมูลล้มเหลว !');
                            }
                        });

                        $('#MinusScopeModal').modal('hide');
                    }else {
                        alert("โปรดเลือกอย่างน้อย 1 รายการ");
                    }

                }else{
                    alert('กรุณากรอกหมายเหตุ !');
                }

            });


        });

        function LoadScopeAction(){
            var id = "{!! $labs->id !!}";
            $('.box_minus_scope').html("");
            $.ajax({
                url: "{!! url('/section5/labs/get_scope_active') !!}" + "/" + id
            }).done(function( object ) {
                $('.box_minus_scope').html(object);
            });


        }
    </script>
@endpush