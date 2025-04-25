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

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่ากลุ่มรายงานจาก Power BI</h3>

                    <div class="pull-right">

                        @can('add-'.str_slug('configs-report-power-bi-group'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/config/report-power-bi-group/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('configs-report-power-bi-group'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/config/report-power-bi-group', 'method' => 'get', 'id' => 'myFilter']) !!}

						<div class="col-md-3">
						    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
							<div class="col-md-9">
								{!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
						    </div>
						</div>

						<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
						<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

					{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        {!! Form::open(['url' => '/config/report-power-bi-group/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                        {!! Form::close() !!}

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                <th class="text-center">@sortablelink('ordering', '⇵')</th>
                                <th>#</th>
                                <th><input type="checkbox" id="checkall"></th>
                                <th>@sortablelink('title', 'ชื่อกลุ่ม')</th>
								<th>@sortablelink('created_by', 'ผู้สร้าง')</th>
                                <th>@sortablelink('created_at', 'วันที่สร้าง')</th>
								<th>@sortablelink('updated_by', 'ผู้แก้ไข')</th>
								<th>@sortablelink('updated_at', 'วันที่แก้ไข')</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
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
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->CreatedName }}</td>
                                    <td>{{ HP::DateThai($item->created_at) }}</td>
                                    <td>{{ $item->updatedName }}</td>
                                    <td>{{ HP::DateThai($item->updated_at) }}</td>

                                    <td>
                                        @can('view-'.str_slug('configs-report-power-bi-group'))
                                            <a href="{{ url('/config/report-power-bi-group/' . $item->id) }}"
                                               title="ดูรายละเอียด กลุ่มรายงานจาก Power BI" class="btn btn-info btn-xs">
                                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        @endcan

                                        @can('edit-'.str_slug('configs-report-power-bi-group'))
                                            <a href="{{ url('/config/report-power-bi-group/' . $item->id . '/edit') }}"
                                               title="แก้ไข กลุ่มรายงานจาก Power BI" class="btn btn-primary btn-xs">
                                                  <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                            </a>
                                        @endcan

                                        @can('delete-'.str_slug('configs-report-power-bi-group'))
                                            {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/config/report-power-bi-group', $item->id],
                                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-xs',
                                                    'title' => 'ลบ กลุ่มรายงานจาก Power BI',
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

                        $.post('{{ url('config/report-power-bi-group/update_order') }}',
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

    </script>

@endpush
