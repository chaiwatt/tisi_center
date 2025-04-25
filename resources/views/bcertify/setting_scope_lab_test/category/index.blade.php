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
    td:nth-of-type(1):before { content: "No.:"; }
    td:nth-of-type(2):before { content: "สถานะการดำเนินงาน:"; }
    td:nth-of-type(3):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title pull-left">หมวดหมู่ #{{$testBranch->title}}</h3>

                <div class="pull-right">

                  @can('edit-'.str_slug('bcertify-scope-lab-test'))

                      <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                        <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                      </a>

                      <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                        <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                      </a>

                  @endcan

                  @can('add-'.str_slug('bcertify-scope-lab-test'))
                      <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/bcertify/setting_scope_lab_test/category/create/' . $testBranch->id) }}">
                        <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                      </a>
                  @endcan

                  {{-- @can('delete-'.str_slug('bcertify-scope-lab-test'))
                      <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                        <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                      </a>
                  @endcan --}}

                  <a class="btn btn-info btn-sm waves-effect waves-light" href="{{ url('/bcertify/setting_scope_lab_test') }}" >
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i>
                  </a>

                </div>


                  <div class="clearfix"></div>
                  <hr>

                  {!! Form::model($filter, ['url' => '/bcertify/setting_scope_lab_test/category/'.$testBranch->id, 'method' => 'get', 'id' => 'myFilter']) !!}
                  <div class="row">
                      <div class="col-md-6 form-group">
                          {!! Form::label('filter_search', 'search:', ['class' => 'col-md-5 control-label label-filter text-right']) !!}
                          <div class="form-group col-md-5">
                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'','id'=>'filter_search']); !!}
                          </div>
                      </div>
                      <div class="col-md-5">
                          <div class="form-group col-md-5">
                              {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                              <div class="col-md-8">
                                  {!! Form::select('perPage', 
                                  ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100',
                                  '500'=>'500'], null, ['class' => 'form-control']); !!}
                              </div>
                          </div>
                          <div class="col-md-2">
                              <div class="form-group  pull-left">
                                  <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                              </div>
                            </div>
                      </div>
                   </div>

                  {!! Form::close() !!}


                <div class="clearfix"></div>
                <div class="table-responsive">

                  {!! Form::open(['url' => '/bcertify/setting_scope_lab_test/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                  {!! Form::close() !!}

                  {!! Form::open(['url' => '/bcertify/setting_scope_lab_test/category/update-state/'.$testBranch->id, 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                    <input type="hidden" name="state" id="state" />
                  {!! Form::close() !!}

                    <table class="table table-borderless" id="myTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><input type="checkbox" id="checkall"></th>
                            <th style="width:30%">หมวดหมู่</th>
                            <th>หมวดหมู่ Eng</th>
                            <th>สถานะ</th>
                            <th class="text-right">จัดการ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($testBranchCategories as $testBranchCategory)
                            <tr>
                                <td>{{ $loop->iteration or $testBranchCategory->id }}</td>
                                <td><input type="checkbox" name="item-selection[]" class="item-selection" value="{{ $testBranchCategory->id }}"></td>
                                <td>{!! $testBranchCategory->name !!}</td>
                                <td>{!! $testBranchCategory->name_eng !!}</td>
                                <td>
                                  @if($testBranchCategory->state=='1')
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
                                </td>
                                
                                <td class="text-right">
                                    @can('view-'.str_slug('bcertify-scope-lab-test'))
                                        <a href="{{ url('/bcertify/setting_scope_lab_test/category/show/' . $testBranchCategory->id) }}"
                                           title="View" class="btn btn-info btn-xs">
                                              <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('edit-'.str_slug('bcertify-scope-lab-test'))
                                        <a href="{{ url('/bcertify/setting_scope_lab_test/category/edit/' . $testBranchCategory->id) }}"
                                           title="Edit" class="btn btn-primary btn-xs">
                                              <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                        </a>
                                    @endcan
                                    @can('edit-'.str_slug('bcertify-scope-lab-test'))
                                      <a href="{{ url('/bcertify/setting_scope_lab_test/category/parameter/' . $testBranchCategory->id) }}"
                                        title="พารามิเตอร์" class="btn btn-warning btn-xs">
                                          <i class="fa fa-link" aria-hidden="true"> </i>
                                      </a>
                                  @endcan
                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-wrapper">
                      {!!
                          $testBranchCategories->appends([
                                        'perPage' => Request::get('perPage'),
                                                 ])->render()
                      !!}
                    </div>
                </div>
                <hr>
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
              $('#myTable').find('input.item-selection').prop('checked', true);
            }else{
              $('#myTable').find('input.item-selection').prop('checked', false);
            }
 
          });

      });

      function UpdateState(state){

        if($('#myTable').find('input.item-selection:checked').length > 0){//ถ้าเลือกแล้ว
            $('#myTable').find('input.item-selection:checked').appendTo("#myFormState");
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
