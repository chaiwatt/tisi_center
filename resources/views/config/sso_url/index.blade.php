@extends('layouts.master')

@push('css')

<style>

  .label-filter{
    margin-top: 7px;
  }
  /*
	Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
	*/
	@media
	  only screen
    and (max-width: 760px), (min-device-width: 768px)
    and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
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

<style type="text/css" id="css-after-load">

</style>
<div id="tmp-after-load" class="hide">

</div>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่า URL SSO</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('SsoUrl'))

                            <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                                <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                            </a>

                            <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                                <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                            </a>

                        @endcan

                        @can('add-'.str_slug('SsoUrl'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/config/sso-url/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('SsoUrl'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/config/sso-url', 'method' => 'get', 'id' => 'myFilter']) !!}

						<div class="col-md-3">
						    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
							<div class="col-md-9">
								{!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
						    </div>
						</div>

						<div class="col-md-3">
							{!! Form::label('title', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter']) !!}
							<div class="col-md-9">
							    {!! Form::select('filter_state', ['1'=>'เปิดใช้งาน', '0'=>'ปิดใช้งาน'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'onchange'=>'this.form.submit()']); !!}
							</div>
						</div>

                        <div class="col-md-5">
							{!! Form::label('filter_group_id', 'กลุ่มรายงาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
							<div class="col-md-8">
							    {!! Form::select('filter_group_id', $groups, null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มรายงาน-', 'onchange'=>'this.form.submit()']); !!}
							</div>
						</div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        {!! Form::open(['url' => '/config/sso-url/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        {!! Form::open(['url' => '/config/sso-url/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                            <input type="hidden" name="state" id="state" />
                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">@sortablelink('ordering', '⇵')</th>
                                    <th>#</th>
                                    <th><input type="checkbox" id="checkall"></th>
                                    <th class="col-md-2">@sortablelink('title', 'ชื่อระบบ')</th>
                                    <th class="col-md-2">@sortablelink('urls', 'URL')</th>
                                    <th>@sortablelink('transfer_method', 'วิธีไปปลายทาง')</th>
                                    <th>@sortablelink('group_id', 'กลุ่ม URL')</th>
                                    <th>@sortablelink('icons', 'ไอคอน')</th>
                                    <th>@sortablelink('colors', 'สี')</th>
    								<th>@sortablelink('updated_by', 'ผู้แก้ไข')</th>
    								<th>@sortablelink('updated_at', 'วันที่แก้ไข')</th>
                                    <th>@sortablelink('state', 'สถานะ')</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="sortable">
                            @php
                                $transfer_methods = App\Models\Config\SettingSystem::transfer_methods();
                            @endphp
                            @foreach($ssourl as $item)
                                <tr>
                                    <td class="text-center">
                                        @if(Request::get('sort')=='ordering')
                                            <i class="fa fa-ellipsis-v sort-item p-l-20 p-r-20"></i>
                                            <input type="hidden" name="order[]" class="order" value="{{ $item->ordering }}">
                                        @else
                                            <i class="fa fa-ellipsis-v text-muted"></i>
                                        @endif
                                    </td>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                    <td>
                                        {{ $item->title }}
                                        <div class="text-muted font-12" title="รายละเอียด">{{ $item->details }}</div>
                                        <div class="text-primary font-12" title="ชื่อที่ใช้สื่อสารกันระหว่างระบบ">{{ $item->app_name }}</div>
                                    </td>
                                    <td class="font-12">{{ $item->urls }}</td>
                                    <td>{{ $transfer_methods[$item->transfer_method] }}</td>
                                    <td>{!! !is_null($item->group) ? $item->group->title : '<i class="text-muted">ไม่มีกลุ่ม</i>' !!}</td>
                                    <td><i class="mdi {{ $item->icons }} pre-icon"></i></td>
                                    <td><i class="mdi mdi-solid pre-icon bg-color {{ $item->colors }}" data-color="{{ $item->colors }}"></i></td>
									<td>{{ $item->updatedName }}</td>
                                    <td>{{ HP::DateThai($item->updated_at) }}</td>
                                    <td>

                                      @can('edit-'.str_slug('SsoUrl'))

                                          {!! Form::open([
                                                'method'=>'PUT',
                                                'url' => ['/config/sso-url/update-state'],
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

                                        {!! $item->branch_block==1 ? '<div class="text-danger font-12">ไม่ให้สาขาใช้</div>' : '' !!}

                                    </td>
                                    <td>
                                        @can('view-'.str_slug('SsoUrl'))
                                            <a href="{{ url('/config/sso-url/' . $item->id) }}"
                                               title="View SsoUrl" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('SsoUrl'))
                                            <a href="{{ url('/config/sso-url/' . $item->id . '/edit') }}"
                                               title="Edit SsoUrl" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('SsoUrl'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/config/sso-url', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'Delete SsoUrl',
                                                    'onclick'=>'return confirm("ยืนยันการลบข้อมูล?")'
                                            )) !!}
                                            {!! Form::close() !!}
                                        @endcan

                                    </td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        <div class="pagination-wrapper">
                          {!!
                              $ssourl->appends(['search' => Request::get('search'),
                                                      'sort' => Request::get('sort'),
                                                      'direction' => Request::get('direction'),
                                                      'perPage' => Request::get('perPage'),
                                                      'filter_state' => Request::get('filter_state')
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
        $(document).ready(function () {

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

              if($(this).prop('checked')){//เลือกทั้งหมด
                $('#myTable').find('input.cb').prop('checked', true);
              }else{
                $('#myTable').find('input.cb').prop('checked', false);
              }

            });

            //สร้าง box div เพื่อดึงค่าสีตาม class css และเอาไปสร้างเป็น css ชุดใหม่ใน css-after-load
            var css_colors = Array();
            $('#myTable').find('.bg-color').each(function(index, el) {
                $('#tmp-after-load').append('<div class="'+$(el).data('color')+'"></div>');
                var color = '.' + $(el).data('color') + '{';
                    color += ' color: ' + $('#tmp-after-load').find('.'+$(el).data('color')).css('background-color') + ' !important;';
                    color += ' background-color: transparent !important;';
                    color += '}';
                css_colors.push(color);
            });
            $('#css-after-load').html(css_colors.join(' '));

            //ถ้ากดเรียงตามลำดับ
            @if(Request::get('sort')=='ordering')

                //เรียง
                $( "#sortable" ).sortable({
                    placeholder: "ui-state-highlight",
                    start: function(event, ui) {
                        console.log(1);
                    },
                    stop:function(event, ui) {
                        console.log(2);

                        var ids    = [];
                        var orders = [];
                        $('.cb').each(function(index, el) {
                            ids.push($(el).val());
                            orders.push($(el).closest('tr').find('.order').val());
                        });

                        $.post('{{ url('config/sso-url/update_order') }}',
                               {
                                   _token: "{{ csrf_token() }}",
                                   ids: ids,
                                   orders: orders,
                                   direction: '{{ Request::get('direction') }}',
                               },
                               function( data ) {
                                   $.toast({
                                       heading: 'Success!',
                                       position: 'top-center',
                                       text: data.message,
                                       loaderBg: '#70b7d6',
                                       icon: data.status,
                                       hideAfter: 3000,
                                       stack: 6
                                   });
                               }
                        );
                    }
                });

            @endif
        });

        function Delete(){

          if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
            if(confirm_delete()){
              $('#myTable').find('input.cb:checked').appendTo("#myForm");
              $('#myForm').submit();
            }
          }else{//ยังไม่ได้เลือก
            alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
          }

        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function UpdateState(state){

          if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
              $('#myTable').find('input.cb:checked').appendTo("#myFormState");
              $('#state').val(state);
              $('#myFormState').submit();
          }else{//ยังไม่ได้เลือก
            if(state=='1'){
              alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
            }else{
              alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
            }
          }

        }

    </script>

@endpush
