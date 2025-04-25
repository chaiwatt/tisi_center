<div class="row">      
    <center>
        <h3>รายงานผลการทดสอบ</h3>
    </center>
</div>


@php
    $product_details =  App\Models\Bsection5\ReportTestProductDetail::where('test_product_id', $testproduct->id )->get();
@endphp

@foreach ( $product_details AS $itemD  )

<fieldset class="white-box">
    <legend>รายละเอียยดผลิตภัณฑ์ : {!! !empty($itemD->sample_no)?$itemD->sample_no:null !!}</legend>

    <div class="row">
        <div class="form-group col-md-7">
            {!! Form::label('product_detail', 'รายละเอียยดผลิตภัณฑ์', ['class' => 'col-md-3 control-label text-right']) !!}
            <div class="col-sm-8">
                {!! Form::text('product_detail', !empty($itemD->product_detail)?$itemD->product_detail:null , ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>  
        <div class="form-group col-md-5">
            {!! Form::label('sample_no', 'หมายเลขตัวอย่าง', ['class' => 'col-md-3 control-label text-right']) !!}
            <div class="col-sm-8">
                {!! Form::text('sample_no', !empty($itemD->sample_no)?$itemD->sample_no:null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>  
    </div>

    @php
        $main_test_item = DB::table((new App\Models\Bsection5\ReportTestProductDetailItem)->getTable().' AS item')
                                ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS b_item', 'b_item.id', '=', 'item.test_item_id')
                                ->leftJoin((new App\Models\Bsection5\Unit)->getTable().' AS b_unit', 'b_unit.id', '=', 'b_item.unit_id')
                                ->select('b_item.main_topic_id', 'b_item.title')
                                ->where('item.detail_id', $itemD->id)
                                ->groupBy('b_item.main_topic_id')
                                ->get();


    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered myTable_details">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="5%">#</th>
                            <th rowspan="2" colspan="2" class="text-center">รายการทดสอบ</th>
                            <th rowspan="2" class="text-center" width="10%">หน่วย</th>
                            <th rowspan="2" class="text-center" width="10%">เกณฑ์กำหนด</th>
                            <th class="text-center" colspan="4">ผลการทดสอบ</th>
                        </tr>
                        <tr>
                            <th class="text-center" width="10%">#1</th>
                            <th class="text-center" width="10%">#2</th>
                            <th class="text-center" width="10%">#3</th>
                            <th class="text-center" width="10%">สรุปผลทดสอบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $main_test_item AS $keyM => $itemM  )
                            @php
                                $details_items = DB::table((new App\Models\Bsection5\ReportTestProductDetailItem)->getTable().' AS item')
                                                ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS b_item', 'b_item.id', '=', 'item.test_item_id')
                                                ->leftJoin((new App\Models\Bsection5\Unit)->getTable().' AS b_unit', 'b_unit.id', '=', 'b_item.unit_id')
                                                ->selectRaw('item.*, b_item.type, b_item.parent_id, b_item.main_topic_id, b_item.level, b_item.title, b_unit.title AS unit_name , b_item.criteria')
                                                ->where('item.detail_id', $itemD->id)
                                                ->where('b_item.main_topic_id', $itemM->main_topic_id)
                                                ->Orderby('b_item.parent_id', 'b_item.level')
                                                ->get();
                            @endphp

                            @foreach ( $details_items AS $keyD => $itemD  )
                                @php
                                    $result = App\Models\Bsection5\ReportTestProductDetailResult::where('item_id', $itemD->id)->get();
                                    $result_list = [];
                                    foreach( $result as $results){
                                        $result_list[ $results->test_no ] = $results->test_result;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{!! $keyM+1 !!}</td>
                                    <td>{!! !empty($itemM->title)?$itemM->title:null !!}</td>
                                    <td>{!! !empty($itemD->test_item_name)?$itemD->test_item_name:null !!}</td>
                                    <td class="text-center">{!! !empty($itemD->unit_name)?$itemD->unit_name:null !!}</td>
                                    <td class="text-center">{!! !empty($itemD->criteria)?$itemD->criteria:null !!}</td>
                                    <td class="text-center">
                                        {!!  isset($result_list) && array_key_exists(  1, $result_list )?$result_list[1]:null!!}
                                    </td>
                                    <td class="text-center">
                                        {!!  isset($result_list) && array_key_exists(  2, $result_list )?$result_list[2]:null!!}
                                    </td>
                                    <td class="text-center">
                                        {!!  isset($result_list) && array_key_exists(  3, $result_list )?$result_list[3]:null!!}
                                    </td>
                                    <td class="text-center">{!! !empty($itemD->test_result)?$itemD->test_result:null!!}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>
    
@endforeach

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            merge_table();//merge cell ตาราง รายละเอียดตัวอย่าง
        });

        //merge cell ตารางรายละเอียดตัวอย่าง ที่รายละเอียดตัวอย่างเป็นตัวเดียวกัน
        function merge_table(){
            const table = document.querySelector('.myTable_details'); //อยู่ใน form.php

            //Col 1
            let headerCell = null;
            for (let row of table.rows) {
                const Cell1 = row.cells[0];
                const Cell2 = row.cells[1];

                if (headerCell === null || Cell1.innerText !== headerCell.innerText) {
                    headerCell = Cell1;
                    header2Cell = Cell2;
                } else {
                    headerCell.rowSpan++;
                    header2Cell.rowSpan++;
                    Cell1.remove();//ลบคอลัมภ์แรก
                    Cell2.remove();//ลบคอลัมภ์สอง

                }
            }
        }
    </script>
@endpush