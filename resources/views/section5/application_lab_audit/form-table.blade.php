@push('css')
    <style>
        input[type=checkbox] {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.5) !important;
            /* IE */
            -moz-transform: scale(1.5) !important;
            /* FF */
            -webkit-transform: scale(1.5) !important;
            /* Safari and Chrome */
            -o-transform: scale(1.5) !important;
            /* Opera */
            transform: scale(1.4) !important;
            padding: 15px !important;
        }
        /* Might want to wrap a span around your checkbox text */
        .checkboxtext {
            /* Checkbox text */
            font-size: 110%;
            display: inline;
        }

    </style>
@endpush

@php
    $KeyScopeList = [];
    $key          = 0;
@endphp

<div class="row">
    <div class="col-md-12">
        <p>เลือกรายการทดสอบที่<u>ผ่านการตรวจประเมิน</u> ตามมาตรฐานเลขที่ <u>{{ array_key_exists($tis_id, $standards)?$standards[$tis_id]:null }}</u></p>
        <table class="table color-bordered-table primary-bordered-table repeater-tb-{{ $tis_id }}" id="myTable{{ $tis_id }}">
            <thead>
            <tr>
                <th class="text-center" width="8%"><input type="checkbox" id="checkall{{ $tis_id }}"></th>
                <th class="text-center" width="10%">ลำดับ</th>
                <th class="text-center" width="70%">รายการทดสอบ</th>
                <th class="text-center">หมายเหตุ</th>
            </tr>
            </thead>
            <tbody data-repeater-list="list-standard-{{ $tis_id }}">
                @foreach ($application_labs_scope_group as $Iscope)

                    @if (  !array_key_exists($Iscope->test_item_id, $KeyScopeList) )
                        @php
                            $KeyScopeList[ $Iscope->test_item_id ] = $Iscope->test_item_id;
                            $test_item                             = $Iscope->test_item;
                        @endphp
                        <tr data-repeater-item>
                            <td class="text-center">
                                <input type="checkbox" name="audit_result" class="scope_checkbox item_checkbox{{ $tis_id }}" data-type="{!! $applicationlabaudit->audit_type !!}"  value="{{ $Iscope->test_item_id }}" {!! ( ($Iscope->audit_result == 1)?'checked':'' ) !!}>
                            </td>
                            <td class="text-center">{{ ++$key }}</td>
                            <td>
                                {!! !is_null($test_item) ? $test_item->ItemHtml : '' !!}
                            </td>
                            <td>
                                {!! Form::hidden('test_item_id', $Iscope->test_item_id) !!}
                                {!! Form::textarea("remark", $Iscope->remark, ['class' => 'form-control test_item_remark', 'rows'=>'2', 'cols' => "30"]) !!}
                            </td>
                        </tr>
                    @endif

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/icheck/icheck.min.js')}}"></script>
    <script src="{{asset('plugins/components/icheck/icheck.init.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script>
        jQuery(document).ready(function() {

            var table{{ $tis_id }} = $('#myTable{{ $tis_id }}').DataTable({
                dom: "Bfrt<'row'<'col-sm-6'l><'col-sm-6'p>>",
                processing: false,
                serverSide: false,
                searching: false,
                pageLength: -1,
                columnDefs: [
                    // { className: "text-center", targets:[0,-1,-2] }
                ],
                fnDrawCallback: function() {

                }
            });

            $('.repeater-tb-{{ $tis_id }}').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) { }
            });

            //เลือกทั้งหมด
            $('#checkall{{ $tis_id }}').on('click', function(e) {
                if($(this).is(':checked')){
                    $(".item_checkbox{{ $tis_id }}").prop('checked', true);        
                } else {
                    $(".item_checkbox{{ $tis_id }}").prop('checked', false);
                }

                $(".item_checkbox{{ $tis_id }}").each(function(index, element){ 
                    var id   = $(element).val();
                    var type = $(element).data('type');
                    var tr   = $(element).closest('tr');

                    if($(element).is(':checked',true)){
                        tr.find('textarea.test_item_remark').val( type == 1?'เป็นไปตาม 17025':'เป็นไปตามภาคผนวก ก'  );
                    }else{
                        tr.find('textarea.test_item_remark').val('');
                    }
                });
            });

        });
    </script>
@endpush
