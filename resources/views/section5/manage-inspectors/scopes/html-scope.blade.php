@php
    $bs_branch_group = $scope->bs_branch_group;

    $bs_branch = $scope->bs_branch;

@endphp

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('show_detail_branch_group', 'หมวดอุตสากรรม.'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                {!! Form::text('show_detail_branch_group', !empty( $bs_branch_group->title )?$bs_branch_group->title:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('show_detail_branch', 'รายสาขา.'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
                {!! Form::text('show_detail_branch', !empty( $bs_branch->title )?$bs_branch->title:null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('start_date', 'วันที่มีผล'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                {!! Form::text('start_date', !empty( $scope->start_date )?HP::revertDate($scope->start_date,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('end_date', 'วันที่สิ้นสุด'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
            <div class="col-md-8">
                {!! Form::text('end_date', !empty( $scope->end_date )?HP::revertDate($scope->end_date,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
            </div>
        </div>
    </div>
</div>

<!-- เป็นข้อมูลนำเข้าผ่านระบบ Labs -->
@if( $scope->type == 2 )
    @php
        $file_scopes = App\AttachFile::where('ref_table', (new App\Models\Section5\InspectorsScope )->getTable() )
                                        ->where('ref_id', $scope->id )
                                        ->where('section', 'file_attach_scope')
                                        ->get();
    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('end_date', 'นำเข้าข้อมูลเมื่อ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-4">
                    {!! Form::text('end_date', !empty( $scope->created_at )?HP::revertDate($scope->created_at,true):null,['class' => 'form-control co_input_show', 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('remarks', 'หมายเหตุ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                <div class="col-md-10">
                    {!! Form::textarea('remarks', !empty( $scope->remarks )?$scope->remarks:null,['class' => 'form-control co_input_show', 'rows' => 4, 'disabled' => true ]) !!}
                </div>
            </div>
        </div>
    </div>

    @foreach (  $file_scopes as $file )
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('test_title', 'เอกสารแนบ'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
                    <div class="col-md-10">
                        <a href="{!! HP::getFileStorage($file->url) !!}" target="_blank">
                            {!! HP::FileExtension($file->filename)  ?? '' !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('tis', 'มอก.'.' :', ['class' => 'col-md-2 control-label text-right']) !!}
            <div class="col-md-10">
   
                <ul>
                    @foreach ( $detail as $scopes_tis )
                        <li>
                            {!! $scopes_tis->tis_no !!} : {!! !is_null($scopes_tis->scope_tis_std) ? $scopes_tis->scope_tis_std->tb3_TisThainame:null !!}
                        </li>
                    @endforeach

                    @if( count($detail) == 0 )
                        </li><i>ข้อมูลไม่สมบูรณ์</i></li>
                    @endif
                </ul>

            </div>
        </div>
    </div>
</div>