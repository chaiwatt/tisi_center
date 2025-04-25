@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
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
    td:nth-of-type(1):before { content: "No.:"; }
    td:nth-of-type(2):before { content: "ประกอบการ:"; }
    td:nth-of-type(3):before { content: "วันที่แจ้ง:"; }
    td:nth-of-type(4):before { content: "ประเภทการแจ้ง:"; }
    td:nth-of-type(5):before { content: "เรื่อง:"; }
    td:nth-of-type(6):before { content: "รายละเอียด:"; }
    td:nth-of-type(7):before { content: "ผู้บันทึก:"; }
    td:nth-of-type(8):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ดูการแจ้งข้อมูลอื่นๆ</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>
{{-- 
                    {!! Form::model($filter, ['url' => '/esurv/other/report', 'method' => 'get', 'id' => 'myFilter']) !!}

											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!} --}}

                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        <table class="table table-borderless" id="myTable">
                            <thead>
                            <tr>
                                  <th>#</th>
                                  <th class="col-md-4">รายละเอียด</th>
                                  <th class="col-md-4">จำนวน (ราย)</th>
                                  {{-- <th class="col-md-2">จำนวน (มอก.)</th> --}}
                                  <th class="col-md-4">จำนวน (ครั้ง)</th>
                                  {{-- <th class="col-md-2">จำนวน (ใบอนุญาต)</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($other as $item)
                                <tr>
                                    <td class="text-top">{{ $loop->iteration or $item->id }}</td>
                                    <td class="text-top">จำนวนผปก.ที่เข้าใช้ระบบแจ้งข้อมูลอื่นๆ</td>
                                    <td class="text-top">{{ $item->list_name }}</td>
                                    {{-- <td class="text-top">{{ $item->list_tis }}</td> --}}
                                    <td class="text-top">{{ $item->total }}</td>
                                    {{-- <td class="text-top">{{ $item->license_list }}</td> --}}
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        {{-- <div class="pagination-wrapper">
                          {!!
                              $other->appends(['search' => Request::get('search'),
                                               'sort' => Request::get('sort'),
                                               'direction' => Request::get('direction'),
                                               'perPage' => Request::get('perPage'),
                                               'filter_state' => Request::get('filter_state')
                                              ])->render()
                          !!}
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

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

        });

    </script>

@endpush
