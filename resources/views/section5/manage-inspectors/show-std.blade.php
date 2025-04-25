@php
    $std_lists  = App\Models\Section5\InspectorsScopeTis::with(['scope_tis_std', 'inspector_scope'])->where('inspectors_code', $inspector->inspectors_code)->get();
@endphp

<div class="col-md-12 col-sm-12">

                         <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_search', 'ค้นหา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อผลิตภัณฑ์ เลขที่ มอก.']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch_group', 'ค้นหาสาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                        {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch', 'ค้นหารายสาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                        {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                     
                                        </div>
                                    </div>
                                </div>
                            </div>

    <table width="100%" class="table table-bordered table-striped" id="myTable2">

        <thead>
            <tr>
                <th class="text-center">ลำดับ</th>
                <th class="text-center">ผลิตภัณฑ์อุตสาหกรรม</th>
                <th class="text-center">มอก. เลขที่</th>
                <th class="text-center">ชื่อ มอก.</th>
                <th class="text-center">รายสาขา</th>
                <th class="text-center">สาขา</th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="5"></td>
            </tr>
        </tfoot>

        <tbody>
            {{-- @foreach ( $std_lists as $key=>$std_list )

            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ !is_null($std_list->scope_tis_std) ? $std_list->scope_tis_std->title : '<i>ข้อมูลไม่สมบูรณ์</i>' }}</td>
                <td>{{ $std_list->tis_no }}</td>
                <td>{{ !is_null($std_list->inspector_scope) ? $std_list->inspector_scope->BranchTitle : '<i>ข้อมูลไม่สมบูรณ์</i>' }}</td>
                <td>{{ !is_null($std_list->inspector_scope) ? $std_list->inspector_scope->BranchGroupTitle : '<i>ข้อมูลไม่สมบูรณ์</i>' }}</td>
            </tr>

            @endforeach --}}
        </tbody>

    </table>

</div>
