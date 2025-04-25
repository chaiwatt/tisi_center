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
		td:nth-of-type(1):before { content: "ลำดับ:"; }
		td:nth-of-type(2):before { content: "ผู้รับใบอนุญาต:"; }
		td:nth-of-type(3):before { content: "มาตรฐาน:"; }
		td:nth-of-type(4):before { content: "เลขที่ใบอนุญาต:"; }
		td:nth-of-type(5):before { content: "เดือน:"; }
		td:nth-of-type(6):before { content: "ปี:"; }
		td:nth-of-type(7):before { content: "วันที่แจ้ง:"; }
		td:nth-of-type(8):before { content: "สถานะ:"; }
		td:nth-of-type(9):before { content: "จัดการ:"; }

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงาน รับแจ้งปริมาณการผลิตตามเงื่อนไข (ทดสอบ)</h3>

                    <div class="pull-right">

                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model($filter, ['url' => '/esurv/receive_volume/report', 'method' => 'get', 'id' => 'myFilter']) !!}

                      <div class="col-md-6">
                        {!! Form::label('filter_department', 'กลุ่มงานหลัก:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-8">
                          {!! Form::select('filter_department', App\Models\Besurv\Department::whereIn('did',[10,11,12])->pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>


											<input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
											<input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

										{!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">


                        <table class="table table-borderless" id="myTable">
                            <thead>
                              <tr>
                                  <th>#</th>
                                  <th class="col-md-4">รายละเอียด</th>
                                  <th class="col-md-2">จำนวน (ราย)</th>
                                  <th class="col-md-2">จำนวน (มอก.)</th>
                                  <th class="col-md-2">จำนวน (ครั้ง)</th>
                                  <th class="col-md-2">จำนวน (ใบอนุญาต)</th>

                              </tr>
                            </thead>
                            <tbody>
                            @php
                                $status_css = ['1'=>'label-info', '2'=>'label-success', '3'=>'label-danger'];
                                // $user_tis_list = $user_tis->toArray();
                            @endphp
                            @foreach($receive_volume as $item)
                                <tr>
                                <td class="text-top">{{ $receive_volume->perPage()*($receive_volume->currentPage()-1)+$loop->iteration }}</td>
                                    <td class="text-top">จำนวนผปก.ที่เข้าใช้ระบบแจ้งปริมาณการผลิต</td>
                                    <td class="text-top">{{ $item->list_name }}</td>
                                    <td class="text-top">{{ $item->list_tis }}</td>
                                <td class="text-top">{{ $item->total }}</td>
                                    <td class="text-top">{{ $item->license_list }}</td>



                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                        {{-- <div class="pagination-wrapper">
                          {!!
                              $receive_volume->appends(['search' => Request::get('search'),
                                                      'sort' => Request::get('sort'),
                                                      'direction' => Request::get('direction'),
                                                    //   'perPage' => Request::get('perPage'),
                                                      'filter_department' => Request::get('filter_department'),
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

        });

        function getSubDepartment(){
          var data_val = $("#filter_department").val();
            if(data_val!=""){
              $.ajax({
                type: "GET",
                url: "{{url('/esurv/receive_volume/add_subdepartment')}}",
                datatype: "html",
                data: {
                    department_id: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    opt += "<option value=''>-เลือกกลุ่มงานหลักย่อย-</option>";
                    $.each(list, function (key, val) {
                        opt += "<option value='" + key + "'>" + val + "</option>";
                    });
                    $("#filter_sub_department").html(opt).trigger("change");
                    // $('#myFilter').submit();
                }
              });
            }
        }

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
