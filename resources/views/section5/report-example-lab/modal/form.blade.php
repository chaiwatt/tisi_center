@php
    $max_test = $data_map->labs_test_item->max('amount_test_list');
    $details = $data_map->details;
@endphp

<div class="row">
    <div class="col-md-12">

        <div class="form-group row">
            {!! Form::label('detail_product_maplap', 'รายละเอียดผลิตภัณฑ์ : ', ['class' => 'col-md-3 control-label text-right']) !!}
            <div class="col-md-9">
                <p class="form-control-static">{{ !empty($data_map->detail_product_maplap) ? HP::map_lap_sizedetail($data_map->detail_product_maplap) : null }}</p>
            </div>
        </div>

        <div class="form-group row">
            {!! Form::label('example_id', 'หมายเลขตัวอย่าง : ', ['class' => 'col-md-3 control-label  text-right']) !!}
            <div class="col-md-9">
                <p class="form-control-static">{{ !empty($data_map->detail_product_maplap) ? HP::map_lap_number3($data_map->detail_product_maplap, $data_map->example_id) : null }}</p>
            </div>
        </div>

        <input type="hidden" name="id" id="id" value="{!! $data_map->id !!}">

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table color-bordered-table primary-bordered-table" id="myTableResult">
                <thead>
                    <tr>
                        <th width="3%" class="center">#</th>
                        <th width="20%" class="center">รายการทดสอบ</th>
                        <?php for( $i=1; $i <= $max_test; $i++ ) { ?>
                            <th width="10%" class="center">#<?php echo $i; ?></th>
                        <?php } ?>
                        <th width="20%" class="center">สรุปผลทดสอบ</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $key_no = 0;
                    @endphp

                    @foreach ( $details  as $item )
                        @php
                            $test_item = $item->test_item;

                            $results_arr = $item->results->pluck('test_result', 'test_no')->toArray();

                        @endphp
                        <tr>
                            <td class="text-center">{!! ++$key_no !!}</td>
                            <td class="text-left text-top">{!! !empty($test_item->TestItemHtml)?$test_item->TestItemHtml:null !!}</td>
                            @for ($i=1; $i <= $max_test; $i++)
                                <td class="text-top">
                                    @if ($test_item->amount_test_list >= $i)
                                        {!! array_key_exists($i, $results_arr) ? $results_arr[$i] : null !!}
                                    @endif
                                </td>
                            @endfor
                            <td class="text-top">
                                {!! !empty($item->test_result) ? $item->test_result : null !!}
                            </td>
                        </tr>

                    @endforeach

                    @if( count( $data_map->details) == 0 )
                        <tr><td colspan="4" class="text-center">ไม่พบรายทดสอบ</td></tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
