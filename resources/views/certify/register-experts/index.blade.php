@extends('layouts.master')

@push('css')

<style>
  /*
	Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
	*/
  @media only screen and (max-width: 760px),
  (min-device-width: 768px) and (max-device-width: 1024px) {

    /* Force table to not be like tables anymore */
    table,
    thead,
    tbody,
    th,
    td,
    tr {
      display: block;
    }

    /* Hide table headers (but not display: none;, for accessibility) */
    thead tr {
      position: absolute;
      top: -9999px;
      left: -9999px;
    }

    tr {
      margin: 0 0 1rem 0;
    }

    tr:nth-child(odd) {
      background: #eee;
    }

    td {
      /* Behave  like a "row" */
      border: none;
      border-bottom: 1px solid #eee;
      position: relative;
      padding-left: 50%;
    }

    td:before {
      /* Now like a table header */
      /*position: absolute;*/
      /* Top/left values mimic padding */
      top: 0;
      left: 6px;
      width: 45%;
      padding-right: 10px;
      white-space: nowrap;
    }

    /*
		Label the data
    You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
		*/
    /*td:nth-of-type(1):before { content: "Column Name"; }*/

  }
</style>

@endpush

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">พิจารณาคำขอผู้เชี่ยวชาญ</h3>

        <div class="pull-right">
          @if(isset($select_users) && count($select_users) > 0)
          @can('assign_work-'.str_slug('registerexperts'))
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> มอบหมาย
          </button>
          <!--   popup ข้อมูลผู้ตรวจการประเมิน   -->
          <div class="modal fade" id="exampleModal">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="exampleModalLabel1">มอบหมายผู้รับผิดชอบคำขอ</h4>
                </div>
                <div class="modal-body">
                  <form id="form_assign" action="{{ route('register-experts.assign') }}" method="post">
                    {{ csrf_field() }}
                    <div class="white-box">
                      <div class="row form-group">
                        <div class="col-md-12">
                          <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                            {!! Form::label('checker', 'เลือกเจ้าหน้าที่ตรวจสอบคำขอ', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                            <div class="col-md-6">
                              {!! Form::select('',
                              $select_users,
                              null,
                              ['class' => 'form-control',
                              'id'=>"checker",
                              'placeholder'=>'-เลือกผู้ที่ต้องการมอบหมายงาน-']); !!}
                            </div>
                            <div class="col-md-2">
                              <button type="button" class="btn btn-sm btn-primary pull-left m-l-5" id="add_items">&nbsp; เลือก &nbsp;</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row " id="div_checker">
                        <div class="col-md-12">
                          <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                              <div class="table-responsive">
                                <table class="table color-bordered-table info-bordered-table">
                                  <thead>
                                    <tr>
                                      <th class="text-center" width="2%">#</th>
                                      <th class="text-center" width="88%">เจ้าหน้าที่ตรวจสอบคำขอ</th>
                                      <th class="text-center" width="10%">ลบ</th>
                                    </tr>
                                  </thead>
                                  <tbody id="table_tbody">

                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="button" class="btn btn-primary" onclick="submit_form('1');return false"><i class="icon-check"></i> บันทึก</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ยกเลิก') !!}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endcan
          @endif
        </div>
        <div class="clearfix"></div>
        <hr>


        {!! Form::model($filter, ['url' => '/certify/register-experts', 'method' => 'get', 'id' => 'myFilter']) !!}

        <div class="row">
            <div class="col-md-3">
                {!! Form::label('filter_status', 'สถานะ:', ['class' => 'col-md-2 control-label label-filter text-right']) !!}
                <div class="form-group col-md-10">
                    {!! Form::select('filter_status',
                    $status,
                    null,
                    ['class' => 'form-control',
                    'id'=>'filter_status',
                    'placeholder'=>'-เลือกสถานะ-']) !!}
                </div>
            </div><!-- /form-group -->

          <div class="col-md-5">
              {!! Form::label('filter_search', 'search:', ['class' => 'col-md-2 control-label label-filter text-right']) !!}

              <div class="form-group col-md-5">
                  {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>' search ','id'=>'filter_search']); !!}
              </div>
              <div class="form-group col-md-5">
                  {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                  <div class="col-md-8">
                      {!! Form::select('perPage',
                      ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100','500'=>'500'],
                      null,
                      ['class' => 'form-control']) !!}
                  </div>
              </div>
          </div><!-- /.col-lg-5 -->

          <div class="col-md-2">
            <div class="form-group">
              <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
              </button>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group  pull-left">
              <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
            </div>
            <div class="form-group  pull-left m-l-15">
              <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                ล้าง
              </button>
            </div>
          </div><!-- /.col-lg-1 -->
        </div><!-- /.row -->

        <div id="search-btn" class="panel-collapse collapse">
          <div class="white-box" style="display: flex; flex-direction: column;">

            <div class="row">
              <div class="form-group col-md-6">
                  {{-- {!! Form::label('filter_start_date', 'วันที่บันทึก:', ['class' => 'col-md-3 control-label label-filter']) !!}
                  <div class="col-md-8">
                      <div class="input-daterange input-group" id="date-range">
                          {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                          <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                          {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                      </div>
                  </div> --}}
              </div>

              <div class="form-group col-md-6">
                {{-- {!! Form::label('filter_type_unit', 'หน่วยตรวจประเภท:', ['class' => 'col-md-4 control-label label-filter']) !!}
                <div class="col-md-7">
                  {!! Form::select('filter_type_unit',
                  ['1'=>'A','2'=>'B','3'=>'C'],
                  null,
                  ['class' => 'form-control',
                  'id'=>'filter_type_unit',
                  'placeholder'=>'-เลือกหน่วยตรวจประเภท-']) !!}
                </div> --}}
              </div>
            </div>


          </div>
        </div>



        <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
        <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

        {!! Form::close() !!}


        <div class="table-responsive">

          {!! Form::open(['url' => '/experts/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

          {!! Form::close() !!}

          {!! Form::open(['url' => '/experts/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
          <input type="hidden" name="state" id="state" />
          {!! Form::close() !!}

          <table class="table table-borderless" id="myTable">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center"><input type="checkbox" id="checkall"></th>
                <th class="text-center">เลขคำขอ</th>
                <th class="text-center">ชื่อผู้ประกอบการ</th>
                <th class="text-center">เลขประจำตัวผู้เสียภาษี</th>
                <th class="text-center">ไฟล์ความเชี่ยวชาญ</th>
                <th class="text-center">ผู้รับผิดชอบ</th>
                <th class="text-center">@sortablelink('state', 'สถานะ')</th>
                <th class="text-center">เปิด/ปิด </th>
                <th class="text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              @foreach($registerexperts as $item)
              <tr >
                <td class="text-center">{{ $loop->iteration or $item->id }}</td>
                <td class="text-center"><input type="checkbox" name="ep[]" class="ep" value="{{ $item->id }}"></td>
                <td  >
                  {{ $item->ref_no }}
                  <br>
                 <i>{{  !empty( $item->created_at)? HP::DateThai($item->created_at):null  }}</i>
                </td>
                <td>{{ $item->head_name }}</td>
                <td>{{ $item->taxid }}</td>
                <td class="text-center">
                      @if (isset($item) && $item->AttachFileHistorycvFileTo)
            @php
                $attach = $item->AttachFileHistorycvFileTo;
            @endphp
         
         
                        {!! !empty($attach->caption) ? $attach->caption : '' !!}
                        <a href="{{url('funtions/get-view/'.$attach->url.'/'.( !empty($attach->filename) ? $attach->filename :  basename($attach->url)  ))}}" target="_blank" 
                          title="{!! !empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ' !!}" >
                           {!! HP::FileIcon($attach->filename, '20px') !!}
                      </a>
            @endif
                </td>  
                <td>{!! $item->ShowAssigns !!}</td>  
                <td class="text-center">
                    {{{  array_key_exists($item->status,$status) ? $status[$item->status] : null }}}
                </td>
                <td class="text-center">
                
                  @can('edit-'.str_slug('registerexperts'))

                      {!! Form::open([
                            'method'=>'PUT',
                            'url' => ['/certify/register-experts/update-state'],
                            'style' => 'display:inline'
                          ])
                      !!}

                      {!! Form::hidden('cb[]', $item->id) !!}

                      @if($item->state=='1')

                        {!! Form::hidden('state', 0) !!}

                        <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="ปิดใช้งาน">
                          <i class="fa fa-check-circle fa-lg text-success"></i>
                        </a>

                      @else

                        {!! Form::hidden('state', 1) !!}

                        <a href="javascript:void(0)" onclick="$(this).parent().submit();" title="เปิดใช้งาน">
                          <i class="fa fa-times-circle fa-lg text-danger"></i>
                        </a>

                      @endif

                      {!! Form::close() !!}
                  @endcan
              </td>
                <td class="text-center">
                  @can('view-'.str_slug('registerexperts'))
                  <a href="{{ url('/certify/register-experts/' . $item->id) }}" title="View Expert" class="btn btn-info btn-xs">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                  </a>
                  @endcan

                  @can('edit-'.str_slug('registerexperts'))
                  <a href="{{ url('/certify/register-experts/' . $item->id . '/edit') }}" title="Edit Expert" class="btn btn-primary btn-xs">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                  </a>
                  @endcan

                  {{-- @can('delete-'.str_slug('registerexperts'))
                  {!! Form::open([
                  'method'=>'DELETE',
                  'url' => ['/certify/register-experts', $item->id],
                  'style' => 'display:inline'
                  ]) !!}
                  {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                  'type' => 'submit',
                  'class' => 'btn btn-danger btn-xs',
                  'title' => 'Delete Expert',
                  'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                  )) !!}
                  {!! Form::close() !!}
                  @endcan --}}

                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          <div class="pagination-wrapper">
            {!!
            $registerexperts->appends(['search' => Request::get('search'),
            'sort' => Request::get('sort'),
            'direction' => Request::get('direction')
            ])->render()
            !!}
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection



@push('js')
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

<script>
  $(document).ready(function() {

    $( "#filter_clear" ).click(function() {
          $('#filter_status').val('').select2();
          $('#filter_search').val('');

          window.location.assign("{{url('/certify/register-experts')}}");
    });

    $('#add_items').on('click', function() {
      let row = $('#checker').val();
      if (row != '') {
        $('#div_checker').show();
        let checker = $('#checker').find('option[value="' + row + '"]').text();
        let table_tbody = $('#table_tbody');
        // table_tbody.empty();
        table_tbody.append('<tr>\n' +
          '                    <td class="text-center">1</td>\n' +
          '                    <td class="text-left">' + checker + '</td>\n' +
          '                    <td class="text-center">' +
          '                    <input type="hidden" name="checker[]"   class="data_checker" value="' + row + '">\n' +
          '                    <button type="button" class="btn btn-danger btn-xs inTypeDelete" data-types="' + row + '" ><i class="fa fa-remove"></i></button></td>\n' +
          '                </tr>');
        $("#checker option[value=" + row + "]").prop('disabled', true); //  เปิดรายการ 
        ResetTableNumber();
        $('#checker').val('').select2();
      } else {
        Swal.fire('กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!');
      }

    });
    ResetTableNumber();
    $(document).on('click', '.inTypeDelete', function() {
      let types = $(this).attr('data-types');
      $("#checker option[value=" + types + "]").prop('disabled', false); //  เปิดรายการ 
      $(this).parent().parent().remove();
      ResetTableNumber();
    });


    @if(\Session::has('flash_message'))
    $.toast({
      heading: 'Success!',
      position: 'top-center',
      text: '{{session()->get('flash_message')}}',
      loaderBg: '#70b7d6',
      icon: 'success',
      hideAfter: 3000,
      stack: 6
    });
    @endif

    //เลือกทั้งหมด
    $('#checkall').change(function(event) {

    if ($(this).prop('checked')) { //เลือกทั้งหมด
    $('#myTable').find('input.cb').prop('checked', true);
    } else {
    $('#myTable').find('input.cb').prop('checked', false);
    }

    });


      $('#form_assign').on('submit', function(e) {
        let eps = $('input.ep:checked');
        if (eps.length === 0) {
          e.preventDefault();
          return;
        }

      let form = $(this);
        form.children('input.apps').remove();
          eps.each(function() {
            let value = $(this).val();
            let input = $('<input type="hidden" name="apps[]" class="apps" value="' + value + '" />');
            input.appendTo(form);
          });
      })


   

  });

  function Delete() {

    if ($('#myTable').find('input.ep:checked').length > 0) { //ถ้าเลือกแล้ว
      if (confirm_delete()) {
        $('#myTable').find('input.ep:checked').appendTo("#myForm");
        $('#myForm').submit();
      }
    } else { //ยังไม่ได้เลือก
      alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
    }

  }

  function confirm_delete() {
    return confirm("ยืนยันการลบข้อมูล?");
  }

  function UpdateState(state) {

    if ($('#myTable').find('input.ep:checked').length > 0) { //ถ้าเลือกแล้ว
      $('#myTable').find('input.ep:checked').appendTo("#myFormState");
      $('#state').val(state);
      $('#myFormState').submit();
    } else { //ยังไม่ได้เลือก
      if (state == '1') {
        alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
      } else {
        alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
      }
    }

  }

  //รีเซตเลขลำดับ
  function ResetTableNumber() {
    var rows = $('#table_tbody').children(); //แถวทั้งหมด
    (rows.length == 0) ? $('#div_checker').hide(): $('#div_checker').show();
    rows.each(function(index, el) {
      $(el).children().first().html(index + 1);
    });
  }

  function submit_form() {
    var data_checker = $(".data_checker").length;
    let eps = $('input.ep:checked').length;

    if (data_checker > 0 && eps > 0) {
      // Text
      $.LoadingOverlay("show", {
        image: "",
        text: "กำลังบันทึก กรุณารอสักครู่..."
      });
      $('#form_assign').submit();
    } else if (eps <= 0) {
      Swal.fire(
        'กรุณาเลือกเลขที่คำขอ !!',
        '',
        'info'
      )
    } else {
      Swal.fire(
        'กรุณาเลือกเจ้าหน้าที่ตรวจสอบคำขอ !!',
        '',
        'info'
      )
    }
  }
</script>

@endpush