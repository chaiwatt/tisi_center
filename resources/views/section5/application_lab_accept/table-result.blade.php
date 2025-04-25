@push('css')

@endpush

<div class="row">
    <div class="col-md-12">
        <p>เลือกรายการทดสอบที่<u>ผ่านการตรวจประเมิน</u> ตามมาตรฐานเลขที่ <u>{{ array_key_exists($tis_id, $standards)?$standards[$tis_id]:null }}</u></p>
        <table class="table color-bordered-table primary-bordered-table" id="myTable{{ $tis_id }}">
            <thead>
            <tr>
                <th class="text-center" width="10%"><input type="checkbox" id="checkall{{ $tis_id }}"></th>
                <th class="text-center" width="10%">ลำดับ</th>
                <th class="text-center" width="70%">รายการทดสอบ</th>
                <th class="text-center">หมายเหตุ</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($application_labs_scope_group as $key=>$application_labs_scope)
                    @php
                        $test_item = $application_labs_scope->test_item;
                    @endphp
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="application_scope_id[]" class="item_checkbox{{ $tis_id }}"  value="{{ $application_labs_scope->id }}" {!! ( ($application_labs_scope->audit_result == 1)?'checked':'' ) !!} >
                        </td>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td>
                            {!! !is_null($test_item) ? $test_item->ItemHtml : '' !!}
                        </td>
                        <td>
                            {!! Form::hidden('scope_id[]', $application_labs_scope->id) !!}
                            {!! Form::textarea("remark[{$application_labs_scope->id}]", $application_labs_scope->remark, ['class' => 'form-control', 'rows'=>'2', 'cols' => "30"]) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('js')
  <script>
    jQuery(document).ready(function() {

        var table{{ $tis_id }} = $('#myTable{{ $tis_id }}').DataTable({
            dom: "Bfrt<'row'<'col-sm-6'l><'col-sm-6'p>>",
            processing: false,
            serverSide: false,
            searching: false,
            columnDefs: [
                // { className: "text-center", targets:[0,-1,-2] }
            ],
            fnDrawCallback: function() {

            }
        });

        //เลือกทั้งหมด
        $('#checkall{{ $tis_id }}').on('click', function(e) {
            if($(this).is(':checked')){
                $(".item_checkbox{{ $tis_id }}").prop('checked', true);
            } else {
                $(".item_checkbox{{ $tis_id }}").prop('checked', false);
            }
        });

    });
  </script>
@endpush
