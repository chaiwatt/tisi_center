@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div id="show_or_not">

    <div class="row">
        <div class="form-group {{ $errors->has('reference_number') ? 'has-error' : ''}}">
            <div class="col-md-offset-7">
                {!! Form::label('reference_number', 'เลขที่เอกสาร:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('reference_number', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('reference_number', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group required{{ $errors->has('trader_autonumber') ? 'has-error' : ''}}">
            {!! Form::label('trader_autonumber', 'ชื่อผู้รับใบอนญาต:', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::select('trader_autonumber', HP::get_tb4_tradername_oldname_orderlatest(), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกผู้รับใบอนุญาต-', 'autocomplete'=>'off']) !!}
                {!! $errors->first('trader_autonumber', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group required{{ $errors->has('tb3_Tisno') ? 'has-error' : ''}}">
            {!! Form::label('tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::select('tb3_Tisno', $tb3_Tisno, null, ['class' => 'form-control tis', 'placeholder'=>'-เลือกมาตรฐาน-', 'required' => 'required', 'autocomplete'=>'off']); !!}
                {!! $errors->first('tb3_Tisno', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group required">
            {!! Form::label('license', 'ใบอนุญาต', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9 select-all">
                <div class="checkbox checkbox-success">
                    <input id="license-all" class="license-all" type="checkbox">
                    <label for="license-all"> เลือกทั้งหมด </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group">
            <div class="col-md-offset-2 col-md-9">
                <div class="license-list">
                    <!-- แสดงเลขที่ใบอนุญาต -->
                    @foreach ((array)$license_by_trader as $key=>$item)
                        <div class="col-md-4">
                            <div class="checkbox checkbox-success">
                            <input name="tbl_licenseNo[]" id="license{{ $item->Autono }}" data-license="{{ $item->Autono }}" data-license_type="{{ $item->tbl_licenseType }}" class="license-item" type="checkbox" value="{{ $item->tbl_licenseNo }}" {{ (in_array($item->tbl_licenseNo, $arr_test))?'checked':'' }}>
                                <label for="license{{ $item->Autono }}">{{ $item->tbl_licenseNo }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group">
            {!! Form::label('factory_name', 'ชื่อโรงงาน/สำนักงาน', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-9">
                {!! Form::text('factory_name', !empty($follow_up->factory_name)?$follow_up->factory_name:'', ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('factory_address_no', @$follow_up->factory_address_no, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_industrial_estate', 'นิคมอุตสาหกรรม(ถ้ามี)', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('factory_address_industrial_estate', @$follow_up->factory_address_industrial_estate, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_alley', 'ตรอก/ซอย', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('factory_address_alley', @$follow_up->factory_address_alley, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_road', 'ถนน', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('factory_address_road', @$follow_up->factory_address_road, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_village_no', 'หมู่ที่', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('factory_address_village_no', @$follow_up->factory_address_village_no, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_province', 'จังหวัด', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::select('factory_address_province', HP::get_address_province() , @$follow_up->factory_address_province, ['class' => 'form-control', 'placeholder'=>'-เลือกจังหวัด-', 'onchange' => 'add_factory_address_province();remove_factory_address_province()']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_amphoe', 'อำเภอ/เขต', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('factory_address_amphoe', [], @$follow_up->factory_address_amphoe, ['class' => 'form-control', 'placeholder'=>'-เลือกอำเภอ/เขต-', 'onchange' =>  'add_factory_address_amphoe();remove_factory_address_amphoe();']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_tambon', 'ตำบล/แขวง', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('factory_address_tambon', [], @$follow_up->factory_address_tambon, ['class' => 'form-control', 'placeholder'=>'-เลือกตำบล/แขวง-']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('factory_address_zip_code', 'รหัสไปรษณีย์', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('factory_address_zip_code', @$follow_up->factory_address_zip_code, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_tel', 'โทรศัพท์', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::text('factory_tel', @$follow_up->factory_tel, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_fax', 'โทรสาร', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('factory_fax', @$follow_up->factory_fax, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('factory_latitude', 'พิกัดที่ตั้ง', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::number('factory_latitude', @$follow_up->factory_latitude, ['class' => 'form-control', 'step' => 'any', 'id' => 'lat1']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('factory_longitude', 'พิกัดที่ตั้ง', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-7">
                {!! Form::number('factory_longitude', @$follow_up->factory_longitude, ['class' => 'form-control', 'step' => 'any', 'id' => 'lng1']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            <div class="col-md-offset-4">
                <button class="btn btn-default" type="button"  onclick="show_map();" {{ !empty($follow_up->check_status)?($follow_up->check_status =='1' || $follow_up->check_status =='2')?"disabled":"":"" }}>
                    ค้นหาจากแผนที่
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times</span>
                    </button>
                </div>
                <div class="modal-body">
                    <style>
                        .controls {
                            margin-top: 10px;
                            border: 1px solid transparent;
                            border-radius: 2px 0 0 2px;
                            box-sizing: border-box;
                            -moz-box-sizing: border-box;
                            height: 32px;
                            outline: none;
                            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                        }

                        #pac-input {
                            background-color: #fff;
                            font-size: 15px;
                            font-weight: 300;
                            margin-left: 12px;
                            padding: 0 11px 0 13px;
                            text-overflow: ellipsis;
                            width: 300px;
                        }

                        #pac-input:focus {
                            border-color: #4d90fe;
                        }

                    </style>
                    <input id="pac-input" class="controls"  type="text" placeholder="Search Box">
                    <div id="map" style="height: 400px;"></div>
                    <input id="lat2" class="controls" type="text"  placeholder="ละติจูด" disabled>
                    <input id="lng2" class="controls" type="text" placeholder="ลองติจูด" disabled>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">ยืนยัน</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('warehouse') ? 'has-error' : ''}}">
        {!! Form::label('warehouse', 'ที่ตั้งคลังสินค้า', ['class' => 'col-md-3 control-label required']) !!}
        <div class="col-md-8">
            @php $warehouse = !empty($follow_up->warehouse)?$follow_up->warehouse:''; @endphp
            <div class="row select-all">
                <div class="col-md-4">
                    <div class="checkbox checkbox-success">
                        <input id="warehouse" class="warehouse" type="checkbox" name="warehouse" value="1" {{ ($warehouse=='1')?'checked':'' }}>
                        <label for="warehouse"> มี </label>
                    </div>
                </div>
            </div>
            {!! $errors->first('warehouse', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="row product-list-show" style="display:none;">
        <div class="row m-b-10">
            <div class="form-group">
                {!! Form::label('warehouse_name', 'ชื่อที่ตั้งคลังสินค้า', ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-9">
                    {!! Form::text('warehouse_name', !empty($follow_up->warehouse_name)?$follow_up->warehouse_name:'',['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row m-b-10">
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('warehouse_address_no', @$follow_up->warehouse_address_no, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_industrial_estate', 'นิคมอุตสาหกรรม(ถ้ามี)', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('warehouse_address_industrial_estate', @$follow_up->warehouse_address_industrial_estate, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row m-b-10">
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_alley', 'ตรอก/ซอย', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('warehouse_address_alley', @$follow_up->warehouse_address_alley, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_road', 'ถนน', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('warehouse_address_road', @$follow_up->warehouse_address_road, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_village_no', 'หมู่ที่', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('warehouse_address_village_no', @$follow_up->warehouse_address_village_no, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row m-b-10">
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_province', 'จังหวัด', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::select('warehouse_address_province', HP::get_address_province() , @$follow_up->warehouse_address_province, ['class' => 'form-control', 'placeholder'=>'-เลือกจังหวัด-', 'onchange' => 'add_warehouse_address_province();remove_warehouse_address_province()']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_amphoe', 'อำเภอ/เขต', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::select('warehouse_address_amphoe', [], @$follow_up->warehouse_address_amphoe, ['class' => 'form-control', 'placeholder'=>'-เลือกอำเภอ/เขต-', 'onchange' =>  'add_warehouse_address_amphoe();remove_warehouse_address_amphoe();']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_tambon', 'ตำบล/แขวง', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::select('warehouse_address_tambon', [], @$follow_up->warehouse_address_tambon, ['class' => 'form-control', 'placeholder'=>'-เลือกตำบล/แขวง-']) !!}
                </div>
            </div>
        </div>
    
        <div class="row m-b-10">
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_address_zip_code', 'รหัสไปรษณีย์', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('warehouse_address_zip_code', @$follow_up->warehouse_address_zip_code, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_tel', 'โทรศัพท์', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::text('warehouse_tel', @$follow_up->warehouse_tel, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_fax', 'โทรสาร', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('warehouse_fax', @$follow_up->warehouse_fax, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="row m-b-10">
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_latitude', 'พิกัดที่ตั้ง', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::number('warehouse_latitude', @$follow_up->warehouse_latitude, ['class' => 'form-control', 'step' => 'any', 'id' => 'w_lat1']) !!}
                </div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('warehouse_longitude', 'พิกัดที่ตั้ง', ['class' => 'col-md-5 control-label']) !!}
                <div class="col-md-7">
                    {!! Form::number('warehouse_longitude', @$follow_up->warehouse_longitude, ['class' => 'form-control', 'step' => 'any', 'id' => 'w_lng1']) !!}
                </div>
            </div>
        </div>

    </div>

<div class="col-md-2"></div>
<div class="col-md-8">
    บุคคลที่พบ
    <button type="button" class="btn btn-sm btn-success pull-right" id="person-add" style="margin-bottom:3px;">
        <i class="icon-plus"></i>&nbsp;เพิ่ม
    </button>
</div>
<div class="clearfix"></div>

<div class="col-md-2"></div>
<div class="col-md-8 table-responsive">
    <table class="table color-bordered-table primary-bordered-table">
      <thead>
        <tr>
          <th class="col-md-1 text-center">#</th>
          <th class="col-md-3 text-center">ชื่อ-สกุล</th>
          <th class="col-md-3 text-center">ตำแหน่ง</th>
          <th class="col-md-2 text-center">เบอร์โทร</th>
          <th class="col-md-2 text-center">E-mail</th>
          <th class="col-md-1 text-center">จัดการ</th>
        </tr>
      </thead>
      <tbody id="person-list">

          @if(!empty($follow_up->person))
            @foreach($follow_up->person->name as $key=>$item)
              <tr>
              <td class="text-center">1.</td>
                <td>
                <input type="text" name="person[name][]" class="form-control" value="{{ @$item }}" />
                </td>
                <td>
                <input type="text" name="person[position][]" class="form-control" value="{{ @$follow_up->person->position[$key] }}" />
                </td>
                <td>
                <input type="text" name="person[tel][]" class="form-control" value="{{ @$follow_up->person->tel[$key] }}" />
                </td>
                <td>
                <input type="text" name="person[email][]" class="form-control" value="{{ @$follow_up->person->email[$key] }}" />
                </td>
                <td class="text-center">
                  <button class="btn btn-danger btn-sm person-remove" type="button">
                    <i class="icon-close"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td class="text-center">1.</td>
                <td>
                <input type="text" name="person[name][]" class="form-control" value="" />
                </td>
                <td>
                <input type="text" name="person[position][]" class="form-control" value="" />
                </td>
                <td>
                <input type="text" name="person[tel][]" class="form-control" value="" />
                </td>
                <td>
                <input type="text" name="person[email][]" class="form-control" value="" />
                </td>
                <td class="text-center">
                  <button class="btn btn-danger btn-sm person-remove" type="button">
                    <i class="icon-close"></i>
                  </button>
                </td>
            </tr>
        @endif

      </tbody>
    </table>
</div>
<div class="clearfix"></div>

<div class="form-group required{{ $errors->has('check_date') ? 'has-error' : ''}}">
  {!! Form::label('check_date', 'วันที่ตรวจ:', ['class' => 'col-md-3 control-label']) !!}
  <div class="col-md-3">
    <div class="input-group">
      {!! Form::text('check_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
      <span class="input-group-addon"><i class="icon-calender"></i></span>
    </div>
    {!! $errors->first('check_date', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="col-md-2"></div>
<div class="col-md-8">
    พนักงานเจ้าหน้าที่
    <button type="button" class="btn btn-sm btn-success pull-right" id="staff-add" style="margin-bottom:3px;">
        <i class="icon-plus"></i>&nbsp;เพิ่ม
    </button>
</div>
<div class="clearfix"></div>

<div class="col-md-2"></div>
<div class="col-md-8 table-responsive">
    <table class="table color-bordered-table primary-bordered-table">
      <thead>
        <tr>
          <th class="col-md-1 text-center">#</th>
          <th class="col-md-5 text-center">ชื่อ-สกุล</th>
          <th class="col-md-5 text-center">ตำแหน่ง</th>
          <th class="col-md-1 text-center">จัดการ</th>
        </tr>
      </thead>
      <tbody id="staff-list">
        @if(!empty($follow_up->staff))
        @foreach ($follow_up->staff->name as $key=>$item)
        <tr>
          <td class="text-center">1.</td>
          <td>
           <select name="staff[name][]"
                    id="staff_name"
                    class="form-control select2">
                    <option id="staff_name" value="">&nbsp;</option>
                @foreach(HP::get_people_found() as $name)
                    <option id="staff_name"
                            value="{{$name->reg_fname . ' ' . $name->reg_lname}}" {!! (($name->reg_fname . ' ' . $name->reg_lname)==$item)?'selected':'' !!}>{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                @endforeach
            </select>
          </td>
          <td>
          <input type="text" name="staff[position][]" class="form-control" value="{{ $follow_up->staff->position[$key] }}" />
          </td>
          <td class="text-center">
            <button class="btn btn-danger btn-sm staff-remove" type="button">
              <i class="icon-close"></i>
            </button>
          </td>
        </tr>
        @endforeach
        @else
        <tr>
          <td class="text-center">1.</td>
          <td>
          {{-- <input type="text" name="staff[name][]" class="form-control" value="" /> --}}
          {{-- <select name="staff[name][]" class="form-control" value="" /> --}}
            <select name="staff[name][]"
                    id="staff_name"
                    class="form-control select2">
                    <option id="staff_name" value="">&nbsp;</option>
                @foreach(HP::get_people_found() as $name)
                    <option id="staff_name"
                            value="{{$name->reg_fname . ' ' . $name->reg_lname}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                @endforeach
            </select>
          </td>
          <td>
          <input type="text" name="staff[position][]" class="form-control" value="" />
          </td>
          <td class="text-center">
            <button class="btn btn-danger btn-sm staff-remove" type="button">
              <i class="icon-close"></i>
            </button>
          </td>
        </tr>
        @endif
      </tbody>
    </table>
</div>
<div class="clearfix"></div>


<div class="form-group {{ $errors->has('follow_type') ? 'has-error' : ''}}">
  {!! Form::label('follow_type', '1.', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <label>{!! Form::radio('follow_type', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} การผลิต</label>
    <label>{!! Form::radio('follow_type', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} การนำเข้า</label>
    {!! $errors->first('follow_type', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div id="div_follow_type">
  <div class="form-group {{ $errors->has('inform_manufacture') ? 'has-error' : ''}}">
    {!! Form::label('inform_manufacture', ' ', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-6">
      <div class="col-md-12">
        <label>{!! Form::radio('inform_manufacture', '1', false, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} แจ้งการผลิต <a id="inform_manu" href="" target="_blank">ดูข้อมูลแจ้งการผลิต</a></label>
      </div>
      <div class="col-md-12">
        <label> {!! Form::radio('inform_manufacture', '2', false, ['class'=>' check', 'data-radio'=>'iradio_square-red']) !!} ไม่แจ้งการผลิต (โปรดระบุเหตุผล)</a></label>
      </div>
      <div class="col-md-12 inform_manufacture_remark">
        <div class="col-md-12">
          {!! Form::textarea('inform_manufacture_remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('inform_manufacture_remark', '<p class="help-block">:message</p>') !!}
       </div>
     </div>
      <div class="col-md-12">
        <label>{!! Form::radio('inform_manufacture', '3', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ</label>
        {!! $errors->first('inform_manufacture', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="col-md-12 inform_manufacture_text">
        <div class="col-md-12">
          {!! Form::textarea('inform_manufacture_text', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('inform_manufacture_text', '<p class="help-block">:message</p>') !!}
       </div>
      </div>
    </div>
  </div>
</div>



<div class="form-group">
  {!! Form::label('label2', '2. ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    เครื่องหมายการค้าที่แสดงกับผลิตภัณฑ์ (แสดงเครื่องหมายมาตรฐาน) / ชื่อย่อที่ได้รับอนุญาตจาก สมอ.
  </div>
</div>
<div class="form-group {{ $errors->has('check_product') ? 'has-error' : ''}}">
  <div class="col-md-2"></div>
  <div class="col-md-6">
    <label>{!! Form::radio('check_product', '1',!empty(isset($follow_up->check_product)  &&  ($follow_up->check_product == 1)) ?  true :false , ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มีการตรวจ</label>
    <label>{!! Form::radio('check_product', '2',!empty(isset($follow_up->check_product)  &&  ($follow_up->check_product != 1)) ?  false:true , ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ</label>
    {!! $errors->first('check_product', '<p class="help-block">:message</p>') !!}
  </div>
</div>
 <div id="div_check_product">
<div class="form-group {{ $errors->has('show_mark') ? 'has-error' : ''}}">
  {!! Form::label('show_mark', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <div class="col-md-12 row">
      <div class="checkbox checkbox-success">
        {!! Form::checkbox('show_mark', '1', isset($follow_up->show_mark)?$follow_up->show_mark:true, ['class' => 'form-control']) !!}
        <label for="show_mark"> แสดงเครื่องหมาย มอก.</label>
      </div>
    </div>
  </div>
</div>

<div class="form-group {{ $errors->has('show_manufacturer') ? 'has-error' : ''}}">
  {!! Form::label('show_manufacturer', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <div class="col-md-12 row">
      <div class="checkbox checkbox-success">
        {!! Form::checkbox('show_manufacturer', '1', isset($follow_up->show_manufacturer)?$follow_up->show_manufacturer:true, ['class' => 'form-control']) !!}
        <label for="show_manufacturer"> แสดงชื่อผู้ผลิต</label>
      </div>
    </div>
  </div>
</div>

<div class="form-group {{ $errors->has('show_manufacturer_sub') ? 'has-error' : ''}}" id="manufacturer_sub_for_show" style="display:none">
  {!! Form::label('show_manufacturer_sub', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    <div class="col-md-12">
      <div class="checkbox checkbox-success">
      {!! Form::checkbox('show_manufacturer_sub[]', '1',  !empty(isset($follow_up->show_manufacturer_sub)  &&  in_array("1",@$follow_up->show_manufacturer_sub)) ?  true :false , ['class' => 'form-control']) !!}
      <label>  ใช้ชื่อผู้ผลิต/ผู้รับใบอนุญาต</label>
      </div>
  </div>
    <div class="col-md-12">
         <div class="checkbox checkbox-success">
            {!! Form::checkbox('show_manufacturer_sub[]', '2',  !empty(isset($follow_up->show_manufacturer_sub)  && in_array("2",@$follow_up->show_manufacturer_sub)) ?  true :false , ['class' => 'form-control','id'=>'show_manufacturer_sub2']) !!}
            <label  for="show_manufacturer_sub2">ใช้เครื่องหมายการค้า</label>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-7 show_manufacturer_sub2">

          <div class="fileinput fileinput-new input-group" data-provides="fileinput">
            <div class="form-control" data-trigger="fileinput">
              <i class="glyphicon glyphicon-file fileinput-exists"></i>
              <span class="fileinput-filename"></span>
            </div>
            <span class="input-group-addon btn btn-default btn-file">
              <span class="fileinput-new">เลือกไฟล์</span>
              <span class="fileinput-exists">เปลี่ยน</span>
                <input type="file" name="show_manufacturer_image" class="check_max_size_file" />
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
          </div>
          {!! $errors->first('show_manufacturer_image', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-md-5 show_manufacturer_sub2">
          {{-- @if($follow_up && $single_attach) --}}
            @if($single_attach->file_name!="" && HP::checkFileStorage($attach_path.$single_attach->file_name))
              <a href="{{ HP::getFileStorage($attach_path.$single_attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
            @endif
          {{-- @endif --}}
          แนบรูปภาพเครื่องหมายการค้า
        </div>
     </div>

      <div class="col-md-12">
        <div class="checkbox checkbox-success">
          {!! Form::checkbox('show_manufacturer_sub[]', '3',!empty(isset($follow_up->show_manufacturer_sub)  &&  in_array("3",@$follow_up->show_manufacturer_sub)) ?  true :false , ['class' => 'form-control','id'=>'show_manufacturer_sub3']) !!}
          <label>ใช้ชื่อย่อ</label>
         </div>
         <div class="clearfix"></div>
         <div class="col-md-7 show_manufacturer_sub3">
            {!! Form::text('show_manufacturer_text', null, ['class' => 'form-control']) !!}
           {!! $errors->first('show_manufacturer_text', '<p class="help-block">:message</p>') !!}
         </div>
      </div>

    {!! $errors->first('show_manufacturer_sub', '<p class="help-block">:message</p>') !!}
  </div>
</div>
</div>
<div class="form-group  check_product_text{{ $errors->has('reason_not_inform') ? 'has-error' : ''}}">
  {!! Form::label('reason_not_inform', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <div class="col-md-12">{!! Form::textarea('reason_not_inform', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}</div>
    {!! $errors->first('reason_not_inform', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', '3. ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    การปฏิบัติตามเงื่อนไขในการออกใบอนุญาต
  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    3.1 ระบบการควบคุมคุณภาพ
    <a  id="inform_QC"  href="{{url('esurv/receive_quality_control')}}" target="_blank"> ดูข้อมูลตรวจประเมิน QC </a>
    <div class="col-md-12">
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('quality_control', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ 1.1 หัวข้อ
        </label>
        <div class="col-md-12 quality_control_text_yes">
          {!! Form::textarea('quality_control_text_yes', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('quality_control_text_yes', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('quality_control', '2', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ 1.1 หัวข้อ
        </label>
        <div class="col-md-12 quality_control_text_no">
          {!! Form::textarea('quality_control_text_no', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('quality_control_text_no', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('quality_control', '3', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ
        </label>
        <div class="col-md-12 quality_control_remark">
          {!! Form::textarea('quality_control_remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('quality_control_remark', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
  </div>
</div>



<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    3.2 การตรวจสอบผลิตภัณฑ์ และเครื่องมือทดสอบ
    <div class="col-md-12">
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('test_tool_product', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เป็นไปตามหลักเกณฑ์เฉพาะฯ
        </label>
        <div class="col-md-12 test_tool_product_text_no">
          {!! Form::textarea('test_tool_product_text_no', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('test_tool_product_text_no', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('test_tool_product', '2', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ
        </label>
        <div class="col-md-12 test_tool_product_text">
          {!! Form::textarea('test_tool_product_text', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('test_tool_product_text', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('test_tool_product', '3', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ
        </label>
        <div class="col-md-12 test_tool_product_remark">
          {!! Form::textarea('test_tool_product_remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('test_tool_product_remark', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
  </div>
</div>




<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    3.3 การดำเนินการกับข้อร้องเรียนเกี่ยวกับคุณภาพผลิตภัณฑ์

    <div class="form-group {{ $errors->has('check_proceed') ? 'has-error' : ''}}">

      <div class="col-md-6">
        <label>{!! Form::radio('check_proceed', '1',!empty(isset($follow_up->check_proceed)  &&  ($follow_up->check_proceed == 1)) ?  true :false , ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มีการตรวจ</label>
        <label>{!! Form::radio('check_proceed', '2',!empty(isset($follow_up->check_proceed)  &&  ($follow_up->check_proceed != 1)) ?  false:true , ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ</label>
        {!! $errors->first('check_proceed', '<p class="help-block">:message</p>') !!}
      </div>
    </div>


    <div class="col-md-12 check_proceed">

      <div class="form-group {{ $errors->has('complaint_amount') ? 'has-error' : ''}}">
        <div class="col-md-5">
          3.3.1 มีการร้องเรียนกี่ครั้ง โปรดระบุ
        </div>
        <div class="col-md-3">
          {!! Form::number('complaint_amount', null, ['class' => 'form-control']) !!}
          {!! $errors->first('complaint_amount', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

    </div>

    <div class="col-md-12 check_proceed">

      <div class="form-group {{ $errors->has('complaint_collect') ? 'has-error' : ''}}">
        <div class="col-md-5">
          3.3.2 การจัดเก็บข้อร้องเรียน
        </div>
        <div class="col-md-6">
          <label>{!! Form::radio('complaint_collect', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มี</label>
          <label>{!! Form::radio('complaint_collect', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มี</label>
          {!! $errors->first('complaint_collect', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

    </div>

    <div class="col-md-12 check_proceed">

      <div class="form-group {{ $errors->has('complaint_handle') ? 'has-error' : ''}}">
        <div class="col-md-5">
          3.3.3 การจัดการข้อร้องเรียน
        </div>
        <div class="col-md-6">
          <label>{!! Form::radio('complaint_handle', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มีประสิทธิผล</label>
          <label>{!! Form::radio('complaint_handle', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีประสิทธิผล</label>
          {!! $errors->first('complaint_handle', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

    </div>

    <div class="col-md-12 check_proceed_text">
      <div class="form-group {{ $errors->has('check_proceed_text') ? 'has-error' : ''}}">
        <div class="col-md-7">
          {!! Form::textarea('check_proceed_text', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}
          {!! $errors->first('check_proceed_text', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>

  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    3.4 การแสดงเครื่องหมายมาตรฐานกับผลิตภัณฑ์
    <div class="col-md-12">
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('show_mark_product', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} แสดงเครื่องหมายแล้ว
        </label>
      </div>
      <div class="col-md-12 row" id="for_show_mark_product_detail" style="display:none">
        <div class="col-md-12 row">
          <label class="col-md-6" style="margin-left:35px;">
            {!! Form::radio('show_mark_product_detail', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ครบตามกำหนด
          </label>
        </div>
        <div class="col-md-12 row">
          <label class="col-md-6" style="margin-left:35px;">
            {!! Form::radio('show_mark_product_detail', '2', null, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่ครบตามกำหนด ขาดรายการ
          </label>
          <div class="col-md-12 show_mark_product_detail_text">
            {!! Form::textarea('show_mark_product_detail_text', null, ['class' => 'form-control', 'rows' => '2']) !!}
          </div>
        </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-12">
          {!! Form::radio('show_mark_product', '2', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่แสดงเครื่องหมาย โปรดระบุเหตุผล
        </label>
          <div class="col-md-12 show_mark_product_remark">
            {!! Form::textarea('show_mark_product_remark', null, ['class' => 'form-control', 'rows' => '2']) !!}
         </div>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-12">
          {!! Form::radio('show_mark_product', '3', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ
        </label>
        <div class="col-md-12 show_mark_product_text">
          {!! Form::textarea('show_mark_product_text', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
      </div>
    </div>
  </div>
</div>


<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    3.5 การแจ้งการนำเข้า
    <div class="col-md-12">
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('inform_import', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เป็นไปตามหลักเกณฑ์เฉพาะฯ
        </label>
      </div>
      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('inform_import', '2', null, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ
        </label>
        <div class="col-md-12 inform_import_remark">
          {!! Form::textarea('inform_import_remark', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
      </div>

      <div class="col-md-12 row">
        <label class="col-md-12">
          {!! Form::radio('inform_import', '3', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ
        </label>
        <div class="col-md-12 inform_import_text">
          {!! Form::textarea('inform_import_text', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
      </div>
    </div>
  </div>
</div>



<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    <div class="col-md-1"><b><u>สรุป</u></b></div>
    <div class="col-md-11">
        <label>{!! Form::radio('summarize', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เป็นไปตามเงื่อนไข</label>
        <label>{!! Form::radio('summarize', '2', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่เป็นไปตามเงื่อนไข</label>
        {!! $errors->first('summarize', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', '4. ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-4">
    บันทึกผลตรวจสอบผลิตภัณฑ์สำเร็จรูป ระหว่าง
  </div>
  <div class="col-md-4">
    <div class="input-daterange input-group" id="date-range">
      {!! Form::text('inspection_result_date_start', null, ['class' => 'form-control']); !!}
      <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
      {!! Form::text('inspection_result_date_end', null, ['class' => 'form-control']); !!}
    </div>
  </div>
</div>

<div class="form-group {{ $errors->has('inspection_result') ? 'has-error' : ''}}">
  {!! Form::label('inspection_result', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <div class="col-md-12"><label>{!! Form::radio('inspection_result', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มีการตรวจ</label></div>
    <div class="col-md-12"><label>{!! Form::radio('inspection_result', '2', true, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่มีการตรวจ (โปรดระบุเหตุผล)</label></div>
    {!! $errors->first('inspection_result', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group inspection_result_text{{ $errors->has('inspection_result_text') ? 'has-error' : ''}}">
  {!! Form::label('inspection_result_text', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    <div class="col-md-12">{!! Form::textarea('inspection_result_text', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'ระบุเหตุผล...']) !!}</div>
    {!! $errors->first('inspection_result_text', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', '5. ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-10">
    การเก็บตัวอย่าง (ระบุตามใบอนุญาต, เครื่องหมายการค้า, ชื่อย่อที่ได้รับอนุญาต, รหัสการผลิต, จำนวน, ปริมาณตัวอย่าง และเลขที่ใบรับตัวอย่าง)
  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', ' ', ['class' => 'col-md-2 control-label']) !!}

  <div class="col-md-10">

      <div class="col-md-12 row">
        <label class="col-md-7">
          {!! Form::radio('sampling', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ไม่มีการเก็บตัวอย่าง
        </label>
      </div>

      <div class="col-md-12 row">
        <label class="col-md-6">
          {!! Form::radio('sampling', '2', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} มีการเก็บตัวอย่าง (โปรดเลือกเลขที่เอกสารอ้างอิง)
        </label>
        <div class="col-md-3">
          {!! Form::select('sampling_reference_document', $refer_doc, null, ['class' => 'form-control', 'placeholder' => '-เลือกเอกสารอ้างอิง-']) !!}
        </div>
        <div class="col-md-3">
          <a id="add_document_number" href="#">เพิ่มเลขที่เอกสาร</a>
        </div>
      </div>

  </div>
</div>

<div class="form-group">
  {!! Form::label('label2', '6. ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-8">
    บันทึกเพิ่มเติม
  </div>
</div>

<div class="form-group {{ $errors->has('additional_note') ? 'has-error' : ''}}">
  {!! Form::label('additional_note', ' ', ['class' => 'col-md-2 control-label']) !!}
  <div class="col-md-6">
    {!! Form::textarea('additional_note', null, ['class' => 'form-control', 'rows' => '2']) !!}
    {!! $errors->first('additional_note', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  {!! Html::decode(Form::label('attach', '7. ', ['class' => 'col-md-2 control-label'])) !!}
  <div class="col-md-8">
    ไฟล์แนบเพิ่มเติม:
    <button type="button" class="btn btn-sm btn-success" id="attach-add">
      <i class="icon-plus"></i>&nbsp;เพิ่ม
    </button>
  </div>
</div>


<div id="other_attach-box">

  @foreach ($attachs as $key => $attach)

  <div class="form-group other_attach_item">
    <div class="col-md-2">
      {!! Form::hidden('attach_filenames[]', $attach->file_name); !!}
    </div>
    <div class="col-md-3">
      {!! Form::text('attach_notes[]', $attach->file_note, ['class' => 'form-control', 'placeholder' => 'คำอธิบายไฟล์แนบ(ถ้ามี)']) !!}
    </div>
    <div class="col-md-3">

      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
        <div class="form-control" data-trigger="fileinput">
          <i class="glyphicon glyphicon-file fileinput-exists"></i>
          <span class="fileinput-filename"></span>
        </div>
        <span class="input-group-addon btn btn-default btn-file">
          <span class="fileinput-new">เลือกไฟล์</span>
          <span class="fileinput-exists">เปลี่ยน</span>
          <input type="file" name="attachs[]" class="check_max_size_file">
        </span>
        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
      </div>
      {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="col-md-2">

      @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
        <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
      @endif

      <button class="btn btn-danger btn-sm attach-remove" type="button">
        <i class="icon-close"></i>
      </button>

    </div>

  </div>

  @endforeach

</div>

</div>

@if(isset($follow_up->status_history) && $follow_up->status_history != null)
@php
 $status_history =  json_decode($follow_up->status_history);
$status =   ['0' => 'ฉบับร่าง',  '1' => 'อยู่ระหว่าง ผก.รับรอง','2' => 'ผก.รับรองแล้ว', '3' => 'อยู่ระหว่าง ผอ.รับรอง','4' => 'ผอ.รับรองแล้ว',  '5' => 'ปรับปรุงแก้ไข'];
$person =   ['0' => 'ผู้บันทึกร่าง','1' => 'ผู้ส่งรายงาน','2' => 'ผู้ตรวจประเมิน','3' => 'ผู้ตรวจประเมิน','4' => 'ผู้ตรวจประเมิน','5' => 'ผู้ตรวจประเมิน'];
 $User = App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id') ->pluck('titels','id');
@endphp
<div class="row form-group">
    <div class="col-md-12">
            <legend><h3>ประวัติการประเมินตรวจควบคุมฯ</h3></legend>
            @foreach($status_history as $key => $item)
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-md-12">

                                  <div class="row">
                                    <div class="col-md-1 text-right">{{($key+1)}}</div>
                                    <div class="col-md-2 text-right">
                                       <label for="">สถานะ</label>
                                    </div>
                                    <div class="col-md-9">
                                      @if(array_key_exists($item->check_status,$status))
                                      <strong class="text-left">{{$status[$item->check_status]}}</strong>
                                      @else
                                      @endif
                                    </div>
                                  </div>

                                  @if($item->conclude_result != null)
                                  <div class="row">
                                    <div class="col-md-1 text-right"></div>
                                    <div class="col-md-2 text-right">
                                       <label for="">ประเมินผลการตรวจ</label>
                                    </div>
                                    <div class="col-md-9">
                                       @if($item->conclude_result == 'เห็นชอบและโปรดดำเนินการต่อไป')
                                           เห็นชอบและโปรดดำเนินการต่อไป
                                      @else
                                       {{ 'อื่นๆ' }}
                                       <p>
                                         {{ @$item->conclude_result_remark }}
                                       </p>
                                      @endif
                                    </div>
                                  </div>
                                  @endif

                                  <div class="row">
                                    <div class="col-md-1 text-right"></div>
                                    <div class="col-md-2 text-right">
                                       <label for="">
                                            @if(array_key_exists($item->check_status,$person))
                                                {{$person[$item->check_status]}}
                                            @else
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                      {{ !empty($User[$item->created_by]) ? $User[$item->created_by] : null   }}
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-1 text-right"></div>
                                    <div class="col-md-2 text-right">
                                       <label for="">วันที่ </label>
                                    </div>
                                    <div class="col-md-9">
                                      {{ !empty($item->date) ? HP::DateThai($item->date)  : null   }}
                                    </div>
                                  </div>

                            </div>
                        </div>
                    </div>
                </div>
             </div>
            @endforeach
    </div>
</div>
@endif


<input type="hidden" name="previousUrl" value="{{$previousUrl}}">
<div id="status_btn"></div>

<div class="row form-group">
  <div class="col-md-12 ">
    @if($follow_up->id=='0' || (isset($follow_up->check_status) && $follow_up->check_status !='2'))
      <div class="row">
          <div class="form-group text-center">
              <button class="btn bg-primary btn-md waves-effect waves-light m-r-30"
                      type="submit"
                      onclick="add_status_btn('1'); return false">
                  <i class="fa fa-send"></i>
                  <b>ส่งรายงาน</b>
              </button>
              <button class="btn btn-info btn-md waves-effect waves-light m-r-30"
                      type="submit"
                      onclick="add_status_btn('0'); return false">
                      <i class="fa fa-book"></i>
                  <b>บันทึกร่าง</b>
              </button>
              <a class="btn btn-default btn-md waves-effect waves-light"
                href="{{ url($previousUrl) }}">
                <i class="fa fa-rotate-left"></i>
                  <b>ยกเลิก</b>
              </a>
          </div>
      </div>
    @endif
  </div>
</div>

{{-- {{var_dump(in_array('5', auth()->user()->RoleListId))}} --}}

@if(in_array('5', auth()->user()->RoleListId) && (isset($follow_up->check_status) && $follow_up->check_status==1))  <!--อยู่ระหว่าง ผก.รับรอง -->
<div class="row form-group">
  <div class="col-md-12">
    @php
       $conclude_result = !empty($follow_up->conclude_result)?$follow_up->conclude_result:'';
    @endphp
      <fieldset>
          <legend><h3>สำหรับ ผก. รับรอง</h3></legend>
          <div class="row">
              <div class="col-md-10">
                  <div class="row">
                      <div class="form-group m-b-10">
                          <div class="col-md-3"></div>
                          <div class="col-md-6">
                              <input type="radio"
                                     name="conclude_result"
                                     value="เห็นชอบและโปรดดำเนินการต่อไป"
                                     class="col-sm-1 check not_remark"
                                     {{ !empty($follow_up) && $follow_up->check_status==2?'disabled':''}}
                                     {{ (!$conclude_result || $conclude_result=="เห็นชอบและโปรดดำเนินการต่อไป")?"checked":""}} data-radio="iradio_square-green">
                              <label>เห็นชอบและโปรดดำเนินการต่อไป</label>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group m-b-10">
                          <div class="col-md-3"></div>
                          <div class="col-md-6">
                              <input type="radio"
                                     name="conclude_result"
                                     value="อื่นๆ"
                                     class="col-sm-1 check"
                                     {{ !empty($follow_up) && $follow_up->check_status==2?'disabled':''}}
                                     id="show_remark" data-radio="iradio_square-green" {{ ($conclude_result && $conclude_result=="อื่นๆ")?"checked":""}}
                              >
                              <label>อื่นๆ</label>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-10 m-b-10">
                    <div class="row">
                      <div class="form-group">
                        {!! Form::label('conclude_result_remark', ' ', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-6">
                          {{-- <div class="col-md-12"> --}}
                            {!! Form::textarea('conclude_result_remark', null, ['class' => 'form-control', 'rows' => '4', !empty($follow_up) && $follow_up->check_status==2?'disabled':'']) !!}
                          {{-- </div> --}}
                        </div>
                      </div>
                    </div>
                  </div>

                      <div class="col-md-10 m-b-10">
                          <div class="row">
                              <div class="form-group">
                                  <label class="col-md-3 text-right">ผู้ตรวจประเมิน:</label>
                        <div class="col-md-5">
                                      <input type="text"
                                             class="form-control"
                                             value="{{ !empty($follow_up) && $follow_up->assessor!=''?HP::get_consider_name($follow_up->assessor):auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                             disabled>
                                      <input type="hidden" name="assessor" value="{{ !empty($follow_up) && $follow_up->assessor!=''?$follow_up->assessor:auth()->user()->runrecno }}">
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-10 m-b-40">
                          <div class="row">
                              <div class="form-group">
                                  <label class="col-md-3 text-right">วันที่ตรวจประเมิน
                                      :</label>
                        <div class="col-md-5">
                                      <input type="text"
                                             class="form-control"
                                             value="{{ !empty($follow_up) && $follow_up->assessment_date!=''?HP::revertDate($follow_up->assessment_date):date("d/m/Y") }}"
                                             disabled>
                                      <input value="{{ !empty($follow_up) && $follow_up->assessment_date!=''?$follow_up->assessment_date:date("Y-m-d") }}" name="assessment_date"
                                             hidden>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div id="status_btn"></div>

                      <div class="col-md-12 ">
                          <div class="row">
                              <div class="form-group text-center">
                                  <button class="btn btn-info btn-md waves-effect waves-light m-r-30"
                                          type="submit"
                                           {{ !empty($follow_up) && $follow_up->check_status==2?'disabled':''}}
                                          onclick="add_status_btn('2'); return false">
                                          <i class="fa fa-save"></i>
                                      <b>บันทึก</b>
                                  </button>
                                  <a class="btn btn-default btn-md waves-effect waves-light"
                                      href="{{url("$previousUrl")}}">
                                     <i class="fa fa-rotate-left"></i>
                                      <b>ยกเลิก</b>
                                  </a>
                              </div>
                          </div>
                      </div>

              </div>
          </div>
      </fieldset>
  </div>
</div>
@endif

@if(!empty($follow_up->check_status) &&  $follow_up->check_status == 2)
            <a  href="{{ url("$previousUrl") }}">
              <div class="alert alert-dark text-center" role="alert">
                  <i class="fa fa-close"></i>
                  <b>กลับ</b>
              </div>
          </a>
 @endif
@push('js')
<script>
  // This example adds a search box to a map, using the Google Place Autocomplete
  // feature. People can enter geographical searches. The search box will return a
  // pick list containing a mix of places and predicted search terms.
  var markers = [];

  function initAutocomplete() {
      var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 13.7563309, lng: 100.50176510000006},
          zoom: 10,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      // Create the search box and link it to the UI element.
      var input = document.getElementById('pac-input');
      var searchBox = new google.maps.places.SearchBox(input);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      // Bias the SearchBox results towards current map's viewport.
      map.addListener('bounds_changed', function () {
          searchBox.setBounds(map.getBounds());
      });
      markers = new google.maps.Marker({
          position: {lat: 13.7563309, lng: 100.50176510000006},
          map: map,
      });

      google.maps.event.addListener(map, 'click', function (event) {
          markers.setMap(null);

          markers = new google.maps.Marker({
              position: {lat: event.latLng.lat(), lng: event.latLng.lng()},
              map: map,
          });
          var lat1 = document.getElementById('lat1');
          var lat2 = document.getElementById('lat2');
          lat1.value = event.latLng.lat();
          lat2.value = event.latLng.lat();
          var lng1 = document.getElementById('lng1');
          var lng2 = document.getElementById('lng2');
          lng1.value = event.latLng.lng();
          lng2.value = event.latLng.lng();
      });
      // [START region_getplaces]
      // Listen for the event fired when the user selects a prediction and retrieve
      // more details for that place.
      searchBox.addListener('places_changed', function () {
          markers.setMap(null);
          var places = searchBox.getPlaces();

          if (places.length == 0) {
              return;
          }

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function (place) {
              var lat1 = document.getElementById('lat1');
              var lat2 = document.getElementById('lat2');
              lat1.value = place.geometry.location.lat();
              lat2.value = place.geometry.location.lat();
              var lng1 = document.getElementById('lng1');
              var lng2 = document.getElementById('lng2');
              lng1.value = place.geometry.location.lng();
              lng2.value = place.geometry.location.lng();
              var icon = {
                  url: place.icon,
                  size: new google.maps.Size(71, 71),
                  origin: new google.maps.Point(0, 0),
                  anchor: new google.maps.Point(17, 34),
                  scaledSize: new google.maps.Size(25, 25)
              };

              // Create a marker for each place.
              markers = new google.maps.Marker({
                  position: {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()},
                  map: map,
              });

              if (place.geometry.viewport) {
                  // Only geocodes have viewport.
                  bounds.union(place.geometry.viewport);
              } else {
                  bounds.extend(place.geometry.location);
              }
          });
          map.fitBounds(bounds);
      });
      // [END region_getplaces]
  }


</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkwr5rmzY9btU08sQlU9N0qfmo8YmE91Y&libraries=places&callback=initAutocomplete"
async defer></script>

<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<!-- input calendar -->
<script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

<script type="text/javascript">

      window.onload = function () {
            @if(!empty($follow_up->check_status)? $follow_up->check_status =='2' :false)
            $('#show_or_not').find('input, textarea, checkbox, radio').attr('disabled', 'disabled');
            $('#show_or_not').find('input').iCheck('disabled');
            $('#show_or_not').find('select option:not(:selected), button').attr('disabled','disabled').trigger('liszt:updated');
            @endif
        };

    $(document).ready(function() {

        $("#trader_autonumber").select2({minimumInputLength: 2});
        // $(".select_staff").trigger("change");

        //ปฎิทิน
        $('.mydatepicker').datepicker({
          autoclose: true,
          todayHighlight: true,
          format: 'dd/mm/yyyy'
        });

        //ช่วงวันที่
        jQuery('#date-range').datepicker({
          toggleActive: true,
          format: 'dd/mm/yyyy',
        });

        if($('#trader_autonumber').val() != ''){
          get_link_by_trader_autono($('#trader_autonumber').val());
          get_sample_save_link_by_trader_autono($('#trader_autonumber').val());
        }


        $('#trader_autonumber').change(function(){
          // $("a#inform_manu").attr("href", "{{url('/esurv/receive_volume?filter_created_by=') }}"+$(this).val());
          get_link_by_trader_autono($(this).val());
          get_sample_save_link_by_trader_autono($(this).val());

            var data_val = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/add_filter_license')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#tb3_Tisno').empty();
                    $('select[name="sampling_reference_document"]').empty();
                    var response = data;
                    var list = response.data;
                    var opt = "<option value=''>-เลือกมาตรฐาน-</option>";
                    $.each(list, function (key, val) {
                        opt += "<option  value='" + val.tb3_Tisno + "'>" + val.tb3_Tisno + ' : ' + val.tb3_TisThainame + "</option>";
                    });
                    $('#tb3_Tisno').append(opt).trigger("change");

                    var list2 = response.data2;
                    var opt2 = "<option value=''>-เลือกเอกสารอ้างอิง-</option>";
                    $.each(list2, function (key, val) {
                        opt2 += "<option  value='" + val.id + "'>"  + val.no + "</option>";
                    });
                    $('select[name="sampling_reference_document"]').append(opt2).trigger("change");
                }
            });
        });


        //เมื่อเลือกมาตรฐาน
        $('.tis').change(function(event) {

          $(".license-list").html('');
          $(".license-detail").html('');

          if ($('#trader_autonumber').val() != "" && $('#tb3_Tisno').val() != "") { //ถ้าเลือกใบอนุญาตและผู้รับใบอนุญาต

            $.ajax("{{ url('basic/license-list-trader2') }}/" + $('#trader_autonumber').val() + "/" + $('#tb3_Tisno').val())
              .done(function(data) {
                $.each(data, function(key, value) {
                  var input_html = [];
                  input_html.push('<div class="col-md-4">');
                  input_html.push('  <div class="checkbox checkbox-success">');
                  input_html.push('   <input name="tbl_licenseNo[]" id="license' + value.Autono + '" data-license="' + value.Autono + '" data-license_type="' + value.tbl_licenseType + '" class="license-item" type="checkbox" value="' + value.tbl_licenseNo + '">');
                  input_html.push('   <label for="license' + value.Autono + '"> ' + value.tbl_licenseNo + ' </label>');
                  input_html.push('  </div>');
                  input_html.push('</div>');

                  $(".license-list").append(input_html.join(''));

                });

              });

          }

        });


        if($('input[name="show_mark_product"][value="1"]').is(':checked')){
            $('#for_show_mark_product_detail').show(200);
        }

        $('input[name="show_mark_product"]').on('ifChecked', function (event) {
            if(event.target.value==1){
              $('input[name="show_mark_product_detail"]').iCheck('enable');
              $('#for_show_mark_product_detail').show(400);
            } else {
              $('input[name="show_mark_product_detail_text"]').val('');
              $('input[name="show_mark_product_detail"]').iCheck('disable');
              $('#for_show_mark_product_detail').hide(400);
            }

        });

        $("input[name=show_mark_product_detail]").on("ifChanged",function(){
          status_show_mark_product_detail_text();
          });
          status_show_mark_product_detail_text();
        function status_show_mark_product_detail_text(){
              var row = $("input[name=show_mark_product_detail]:checked").val();
                $('.show_mark_product_detail_text').hide(400);
              if(row == "2"){
                $('.show_mark_product_detail_text').show(200);
              } else{
                $('.show_mark_product_detail_text').hide(400);
              }
          }

// เริ่ม 1 การผลิต
        $("input[name=inform_manufacture]").on("ifChanged",function(){
          status_inform_manufacture();
            });
            status_inform_manufacture();
        function status_inform_manufacture(){
                 var row = $("input[name=inform_manufacture]:checked").val();
                $('.inform_manufacture_text').hide(400);
                $('.inform_manufacture_remark').hide(400);
              if(row == "2"){
                $('.inform_manufacture_remark').show(200);
                $('.inform_manufacture_text').hide(400);
              }else   if(row == "3"){
                $('.inform_manufacture_text').show(200);
                $('.inform_manufacture_remark').hide(400);
              } else{
                $('.inform_manufacture_text').hide(400);
                $('.inform_manufacture_remark').hide(400);
              }
          }
// จบ 1 การผลิต
// เริ่ม 2.เครื่องหมายการค้าที่แสดงกับผลิตภัณฑ์ (แสดงเครื่องหมายมาตรฐาน) / ชื่อย่อที่ได้รับอนุญาตจาก สมอ.

        $("input[name=check_product]").on("ifChanged",function(){
          status_check_product();
            });
            status_check_product();
        function status_check_product(){
                 var row = $("input[name=check_product]:checked").val();
             $('#div_check_product').hide(400);
             $('.check_product_text').show(200);
            if(row == "1"){
             $('#div_check_product').show(200);
             $('.check_product_text').hide(400);
              }else{
                $('#div_check_product').hide(400);
                $('.check_product_text').show(200);
              }
          }


        if($('#show_manufacturer').is(':checked')){
            $('div#manufacturer_sub_for_show').show(200);
        }else{
            $('div#manufacturer_sub_for_show').hide(400);
        }
        $('#show_manufacturer').on('click', function (event) {
            if($(this).is(':checked')){
              $('div#manufacturer_sub_for_show').show(400);
            } else {
              $('div#manufacturer_sub_for_show').hide(400);
            }
        });

        // ใช้เครื่องหมายการค้า
        if($('#show_manufacturer_sub2').is(':checked')){
            $('.show_manufacturer_sub2').show(200);
        }else{
          $('.show_manufacturer_sub2').hide(400);
        }
        $('#show_manufacturer_sub2').on('click', function (event) {
            if($(this).is(':checked')){
              $('.show_manufacturer_sub2').show(400);
            } else {
              $('.show_manufacturer_sub2').hide(400);
            }
        });

        // ใช้ชื่อย่อ
        if($('#show_manufacturer_sub3').is(':checked')){
            $('.show_manufacturer_sub3').show(200);
        }else{
             $('.show_manufacturer_sub3').hide(400);
         }
        $('#show_manufacturer_sub3').on('click', function (event) {
            if($(this).is(':checked')){
              $('.show_manufacturer_sub3').show(400);
            } else {
              $('.show_manufacturer_sub3').hide(400);
            }
        });

// จบ 2.เครื่องหมายการค้าที่แสดงกับผลิตภัณฑ์ (แสดงเครื่องหมายมาตรฐาน) / ชื่อย่อที่ได้รับอนุญาตจาก สมอ.


// เริ่ม 3 การปฏิบัติตามเงื่อนไขในการออกใบอนุญาต
      $("input[name=quality_control]").on("ifChanged",function(){
        status_quality_control();
            });
            status_quality_control();
        function status_quality_control(){
                 var row = $("input[name=quality_control]:checked").val();
                $('.quality_control_text_yes').hide(400);
                $('.quality_control_text_no').hide(400);
                $('.quality_control_remark').hide(400);
            if(row == "1"){
                $('.quality_control_text_yes').show(200);
                $('.quality_control_text_no').hide(400);
                $('.quality_control_remark').hide(400);
             }else if(row == "2"){
                $('.quality_control_text_yes').hide(400);
                $('.quality_control_text_no').show(200);
                $('.quality_control_remark').hide(400);
            }else if(row == "3"){
                $('.quality_control_text_yes').hide(400);
                $('.quality_control_text_no').hide(400);
                $('.quality_control_remark').show(200);
              } else{
                $('.quality_control_text_yes').hide(400);
                $('.quality_control_text_no').hide(400);
                $('.quality_control_remark').hide(400);
             }
          }

     $("input[name=test_tool_product]").on("ifChanged",function(){
        status_test_tool_product();
            });
            status_test_tool_product();
        function status_test_tool_product(){
                 var row = $("input[name=test_tool_product]:checked").val();
             $('.test_tool_product_text_no').hide(400);
                $('.test_tool_product_text').hide(400);
                $('.test_tool_product_remark').hide(400);
            if(row == "1"){
                $('.test_tool_product_text_no').show(200);
                $('.test_tool_product_text').hide(400);
                $('.test_tool_product_remark').hide(400);
             }else if(row == "2"){
                $('.test_tool_product_text_no').hide(400);
                $('.test_tool_product_text').show(200);
                $('.test_tool_product_remark').hide(400);
            }else if(row == "3"){
                $('.test_tool_product_text_no').hide(400);
                $('.test_tool_product_text').hide(400);
                $('.test_tool_product_remark').show(200);
              } else{
                $('.test_tool_product_text_no').hide(400);
                $('.test_tool_product_text').hide(400);
                $('.test_tool_product_remark').hide(400);
             }
          }

        $("input[name=check_proceed]").on("ifChanged",function(){
          status_check_proceed();
            });
          status_check_proceed();
        function status_check_proceed(){
                 var row = $("input[name=check_proceed]:checked").val();
             $('.check_proceed').hide(400);
             $('.check_proceed_text').show(200);
            if(row == "1"){
             $('.check_proceed').show(200);
             $('.check_proceed_text').hide(400);
              }else{
                $('.check_proceed').hide(400);
                $('.check_proceed_text').show(200);
              }
          }

          $("input[name=show_mark_product]").on("ifChanged",function(){
        status_show_mark_product();
            });
            status_show_mark_product();
        function status_show_mark_product(){
                 var row = $("input[name=show_mark_product]:checked").val();
                $('.show_mark_product_remark').hide(400);
                $('.show_mark_product_text').hide(400);
              if(row == "2"){
                $('.show_mark_product_remark').show(200);
                $('.show_mark_product_text').hide(400);
              }else   if(row == "3"){
                $('.show_mark_product_text').show(200);
                $('.show_mark_product_remark').hide(400);
              }else{
                $('.show_mark_product_remark').hide(400);
                $('.show_mark_product_text').hide(400);
              }
          }


          $("input[name=inform_import]").on("ifChanged",function(){
            status_inform_import();
            });
            status_inform_import();
        function status_inform_import(){
                 var row = $("input[name=inform_import]:checked").val();
               $('.inform_import_remark').hide(400);
                $('.inform_import_text').hide(400);
              if(row == "2"){
                $('.inform_import_remark').show(200);
                $('.inform_import_text').hide(400);
              }else   if(row == "3"){
                $('.inform_import_text').show(200);
                $('.inform_import_remark').hide(400);
              }else{
                $('.inform_import_remark').hide(400);
                $('.inform_import_text').hide(400);
              }

          }

// จบ 3 การปฏิบัติตามเงื่อนไขในการออกใบอนุญาต

// เริ่ม 4 บันทึกผลตรวจสอบผลิตภัณฑ์สำเร็จรูป ระหว่าง
      $("input[name=inspection_result]").on("ifChanged",function(){
           status_inspection_result();
            });
            status_inspection_result();
        function status_inspection_result(){
                 var row = $("input[name=inspection_result]:checked").val();
             $('.inspection_result_text').hide(400);
            if(row == "2"){
             $('.inspection_result_text').show(200);
              }else{
                $('.inspection_result_text').hide(400);
              }
          }

// จบ 4 บันทึกผลตรวจสอบผลิตภัณฑ์สำเร็จรูป ระหว่าง

        //เลือกใบอนุญาตทั้งหมด
        $('.license-all').change(function(event) {

          if ($(this).prop('checked')) { //ถ้าเลือก
            $('.license-item').prop('checked', true);
          } else { //ถ้าไม่เลือก
            $('.license-item').prop('checked', false);
          }

        });

           //เลือกที่ตั้งคลังสินค้า
           $('.warehouse').change(function(event) {

              if ($(this).prop('checked')) { //ถ้าเลือก
                $('.product-list-show').show(300);
              } else { //ถ้าไม่เลือก
                $('.product-list-show').hide(300);
              }

          });

          $('.warehouse').change();

        //เพิ่มบุคคล
        $('#person-add').click(function(event) {
          $('#person-list').children(':first').clone().appendTo('#person-list');
          ShowHideRemoveBtnPerson();
        });

        //ลบบุคคล
        $('body').on('click', '.person-remove', function(event) {
          $(this).parent().parent().remove();
          ShowHideRemoveBtnPerson();
        });

        //เพิ่มเจ้าหน้าที่
        $('#staff-add').click(function(event) {
          $('#staff-list').children(':first').clone().appendTo('#staff-list');
          var select_staff_list = $('#staff-list').children(':last');
           //Clear value select
          $(select_staff_list).find('select').val('');
          $(select_staff_list).find('select').prev().remove();
          $(select_staff_list).find('select').removeAttr('style');
          $(select_staff_list).find('select').select2();
          $(select_staff_list).find('input').val('');
          ShowHideRemoveBtnStaff();
        });

        //ลบเจ้าหน้าที่
        $('body').on('click', '.staff-remove', function(event) {
          $(this).parent().parent().remove();
          ShowHideRemoveBtnStaff();
        });

        //เพิ่มไฟล์แนบ
        $('#attach-add').click(function(event) {
          $('.other_attach_item:first').clone().appendTo('#other_attach-box');

          $('.other_attach_item:last').find('input').val('');
          $('.other_attach_item:last').find('a.fileinput-exists').click();
          $('.other_attach_item:last').find('a.view-attach').remove();

          ShowHideRemoveBtn();
          check_max_size_file();
        });

        //ลบไฟล์แนบ
        $('body').on('click', '.attach-remove', function(event) {
          $(this).parent().parent().remove();
          ShowHideRemoveBtn();
        });

        ShowHideRemoveBtnPerson();
        ShowHideRemoveBtnStaff();
        ShowHideRemoveBtn();


      if($("#factory_address_province :selected").val()!=""){
          add_factory_address_province();
          add_factory_address_amphoe();
        }

        if($("#warehouse_address_province :selected").val()!=""){
          add_warehouse_address_province();
          add_warehouse_address_amphoe();
        }

    });

    function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="check_status" value="' + status + '" hidden>');
            if ($('#trader_autonumber').val() != "" && $('#tb3_Tisno').val() != "") {
                $('#form_follow_up').submit();
            }

        }

    function show_map() {
            $('#modal-default').modal('show');
        }


    function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

      if ($('.other_attach_item').length > 1) {
        $('.attach-remove').show();
      } else {
        $('.attach-remove').hide();
      }

    }

    function ShowHideRemoveBtnPerson() { //ซ่อน-แสดงปุ่มลบ (บุคคล)

      $($('#person-list').children()).each(function(index, el) {
        $(el).children(':first').html((index+1)+'.');
      });

      if ($('#person-list').children().length > 1) {
        $('.person-remove').show();
      } else {
        $('.person-remove').hide();
      }

    }

    function ShowHideRemoveBtnStaff() { //ซ่อน-แสดงปุ่มลบ (เจ้าหน้าที่)

      $($('#staff-list').children()).each(function(index, el) {
        $(el).children(':first').html((index+1)+'.');
      });

      if ($('#staff-list').children().length > 1) {
        $('.staff-remove').show();
      } else {
        $('.staff-remove').hide();
      }

    }

    function add_factory_address_province() {
            var data_val = $("#factory_address_province :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/add_factory_address_province')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var amphur = "{{ !empty($follow_up->factory_address_amphoe)?$follow_up->factory_address_amphoe:'' }}";
                    var opt;
                    opt += "<option value=''>-เลือกอำเภอ/เขต-</option>";
                    $.each(list, function (key, val) {
                      if(val.AMPHUR_ID==amphur){var selected_amphur = "selected"; } else { var selected_amphur = "";};
                        opt += "<option id=\"factory_address_amphoe\" value='" + val.AMPHUR_ID + "' "+selected_amphur+" >" + val.AMPHUR_NAME + "</option>";
                    });
                    $("#factory_address_amphoe").html(opt).trigger("change");
                }
            });
        }

        function remove_factory_address_province() {
            $('#factory_address_amphoe').empty()
        }

        function add_factory_address_amphoe() {
            var data_val = $("#factory_address_amphoe :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/add_factory_address_tambon')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var tambon = "{{ !empty($follow_up->factory_address_tambon)?$follow_up->factory_address_tambon:'' }}";
                    var opt;
                    opt += "<option value=''>-เลือกตำบล/แขวง-</option>";
                    $.each(list, function (key, val) {
                      if(val.DISTRICT_ID==tambon){var selected_tambon = "selected"; } else { var selected_tambon = "";};
                        opt += "<option id=\"factory_address_tambon\" value='" + val.DISTRICT_ID + "' "+selected_tambon+" >" + val.DISTRICT_NAME + "</option>";
                    });
                    $("#factory_address_tambon").html(opt).trigger("change");

                }
            });
        }

        function remove_factory_address_amphoe() {
            $('#factory_address_tambon').empty();
        }


        function add_warehouse_address_province() {
            var data_val = $("#warehouse_address_province :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/add_warehouse_address_province')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var amphur = "{{ !empty($follow_up->warehouse_address_amphoe)?$follow_up->warehouse_address_amphoe:'' }}";
                    var opt;
                    opt += "<option value=''>-เลือกอำเภอ/เขต-</option>";
                    $.each(list, function (key, val) {
                      if(val.AMPHUR_ID==amphur){var selected_amphur = "selected"; } else { var selected_amphur = "";};
                        opt += "<option id=\"warehouse_address_amphoe\" value='" + val.AMPHUR_ID + "' "+selected_amphur+" >" + val.AMPHUR_NAME + "</option>";
                    });
                    $("#warehouse_address_amphoe").html(opt).trigger("change");
                }
            });
        }

        function remove_warehouse_address_province() {
            $('#warehouse_address_amphoe').empty();
        }

        function add_warehouse_address_amphoe() {
            var data_val = $("#warehouse_address_amphoe :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/add_warehouse_address_tambon')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var tambon = "{{ !empty($follow_up->warehouse_address_tambon)?$follow_up->warehouse_address_tambon:'' }}";
                    var opt;
                    opt += "<option value=''>-เลือกตำบล/แขวง-</option>"
                    $.each(list, function (key, val) {
                      if(val.DISTRICT_ID==tambon){var selected_tambon = "selected"; } else { var selected_tambon = "";};
                        opt += "<option id=\"warehouse_address_tambon\" value='" + val.DISTRICT_ID + "' "+selected_tambon+" >" + val.DISTRICT_NAME + "</option>"
                    });
                    $("#warehouse_address_tambon").html(opt).trigger("change");
                }
            });
        }

        function remove_warehouse_address_amphoe() {
            $('#warehouse_address_tambon').empty();
        }

        function get_link_by_trader_autono(tax_number){
            var data_val = tax_number;
             $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/get_trader_autono')}}",
                datatype: "html",
                data: {
                    tax_number: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    console.log(list);
                  $("a#inform_manu").attr("href", "{{url('/esurv/receive_volume?filter_created_by=') }}"+list);

                  $("a#inform_QC").attr("href", "{{url('/esurv/receive_quality_control?filter_created_by=') }}"+list);
                }
            });
        }

         function get_sample_save_link_by_trader_autono(tax_number){
            var data_val = tax_number;
             $.ajax({
                type: "GET",
                url: "{{url('/esurv/follow_up/get_trader_autono')}}",
                datatype: "html",
                data: {
                    tax_number: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    console.log(list);
                  $("a#add_document_number").attr("href", "{{url('/ssurv/save_example?filter_created_by=') }}"+list);
                }
            });
        }

</script>

@endpush
