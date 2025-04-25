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

                            {!! Form::hidden('inspectors_id', $inspector->id) !!}
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

                            @php

                                $scopes = $inspector->scopes;
                                $scope_groups = count($scopes) > 0 ? $scopes->groupBy('agency_taxid') : [] ;
                                $agency_scope = App\Models\Section5\InspectorsScope::where('inspectors_id', $inspector->id)
                                                            ->with('agency_user')
                                                            ->groupBy('agency_taxid')
                                                            ->get()
                                                            ->pluck('agency_user.name','agency_user.tax_number')
                                                            ->toArray();
                            @endphp

                            <table class="table" id="table-minus-scope">
                                <tbody>

                                    @foreach ($scope_groups as $key => $group)

                                        <tr class="info">
                                            <td colspan="2" >
                                               <span class="h4">หน่วยงาน : {!! ( array_key_exists( $key, $agency_scope  )?$agency_scope[ $key ]:null ).( !empty($key)?' ('.$key.')':null ) !!}</span>
                                            </td>
                                        </tr>

                                        @foreach ( $group->where('state',1)->groupBy('branch_group_id') as $branch_group_id => $Ibranch )

                                            @php
                                                $bs_branch_group = !empty($Ibranch->first())?$Ibranch->first()->bs_branch_group:null;
                                            @endphp

                                            <tr>
                                                <td colspan="2">
                                                    <b>หมวดอุตสากรรม : {!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="80%">
                                                    รายสาขา
                                                </td>
                                                <td>
                                                    วันที่หมดอายุ
                                                </td>
                                            </tr>
                                            @foreach ( $Ibranch as $branch )
                                                @php
                                                    $bs_branch = $branch->bs_branch;
                                                @endphp
                                                <tr>
                                                    <td width="80%">
                                                        <span class="m-l-30">
                                                            <input type="checkbox" name="scope_id[]" class="scope_id_checkbox"  value="{!! $branch->id !!}">
                                                            {!! !empty($bs_branch->title)?$bs_branch->title:null !!}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {!! !empty($branch->end_date)?HP::DateThai( $branch->end_date ):null !!}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            
                                        @endforeach
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
                    url: "{{ url('/section5/inspectors/minus_scope') }}",
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