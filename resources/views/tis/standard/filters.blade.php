<div class="row">
    {{-- <div class="form-group col-md-6">
      {!! Form::label('filter_number_book_year', 'เลขที่-เล่ม-ปี', ['class' => 'col-md-4 control-label label-filter']) !!}
      <div class="col-md-8">
        {!! Form::text('filter_number_book_year', null, ['class' => 'form-control', 'placeholder'=>'ค้นหาเลขที่, เล่ม, ปี']); !!}
      </div>
    </div> --}}
    <div class="form-group col-md-6">
        {!! Form::label('filter_publish_date_start', 'วันที่ประกาศใช้', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            <div class="input-daterange input-group" id="date-range">
                {!! Form::text('filter_publish_date_start', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th"]); !!}
                <span class="input-group-addon bg-info b-0 text-white">ถึง</span>
                {!! Form::text('filter_publish_date_end', null, ['class' => 'form-control datepicker', 'data-provide' =>"datepicker", 'data-date-language'=>"th-th", 'id'=>'filter_publish_date_end']); !!}
            </div>
        </div>
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('filter_staff_responsible', 'ชื่อผู้รับผิดชอบ', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::text('filter_staff_responsible', null, ['class' => 'form-control', 'placeholder'=>'ค้นหาชื่อผู้รับผิดชอบ']); !!}
        </div>
    </div>
</div>

  <div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('filter_refer', 'มาตรฐานอ้างอิง', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::text('filter_refer', null, ['class' => 'form-control', 'placeholder'=>'ค้นหามาตรฐานอ้างอิง']); !!}
        </div>
    </div>
    <div class="from-group col-md-6">
        {!! Form::label('filter_set_format', 'การกำหนด', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::select('filter_set_format', App\Models\Basic\SetFormat::pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกรูปแบบ-']); !!}
        </div>
    </div>
</div>

  <div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('filter_review_status', 'ทบทวน', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::select('filter_review_status', ['1'=>'มาตรฐานเดิม','2'=>'ทบทวนมาตรฐาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกทบทวน-']); !!}
        </div>
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('filter_product_group', 'กลุ่มผลิตภัณฑ์', ['class' => 'col-md-4 control-label label-fillter'])!!}
        <div class="col-md-8">
            {!! Form::select('filter_product_group[]', App\Models\Basic\ProductGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'filter_product_group', 'data-placeholder' => '-เลือกประเภท-']); !!}
        </div>
    </div>
</div>

  <div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('filter_board_type', 'คณะที่จัดทำ', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::select('filter_board_type[]', App\Models\Tis\Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->orderbyRaw('cast(board_position as unsigned)')->where('state',1)->pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'filter_board_type', 'data-placeholder'=>'-เลือกคณะที่จัดทำ-']); !!}
        </div>
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('filter_staff_group', 'กลุ่มเจ้าหน้าที่', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::select('filter_staff_group[]', App\Models\Basic\StaffGroup::selectRaw('CONCAT(`order`," ",title) As title, id')->where('state',1)->pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'filter_staff_group', 'data-placeholder'=>'-เลือกกลุ่มเจ้าหน้าที่-']); !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('filter_gazette', 'การประกาศราชกิจจา', ['class' => 'col-md-4 control-label label-filter']) !!}
        <div class="col-md-8">
            {!! Form::select('filter_gazette', ['y'=>'มาตรฐานที่ประกาศราชกิจจาแล้ว','w'=>'มาตรฐานที่ผ่าน กมอ. แล้ว รอประกาศราชกิจจา'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกการประกาศราชกิจจา-']); !!}
        </div>
    </div>
    <div class="form-group col-md-6">

    </div>
</div>
