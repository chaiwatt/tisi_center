@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
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

	}
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดทำมาตรฐานรับรอง</h3>

                    <div class="pull-right">

                      @can('edit-'.str_slug('certifystandard'))


                        <button class="btn btn-primary btn-sm btn-outline waves-effect waves-light" type="button" id="bulk_update">
                            <span class="btn-label"><i class="fa fa-paper-plane"></i></span><b>เผยแพร่</b>
                        </button>


                      @endcan

                      @can('add-'.str_slug('certifystandard'))
                          <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/standards/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                          </a>
                      @endcan


                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-3">
                            <div class="form-group">
                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นชื่อมาตรฐานหรือเลข มอก.']); !!}
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            {!! Form::select('filter_state', ['1'=>'รอเผยแพร่', '2'=>'เผยแพร่', '3'=>'ยกเลิก'], null, ['id'=>'filter_state', 'class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                    <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="form-group  pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light" id="button_search" style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="form-group  pull-left m-l-15">
                                <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                    ล้าง
                                </button>
                            </div>
                        </div>
              
                    </div>

                    <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        {!! Form::select('filter_status', ['4'=>'อยู่ระหว่างจัดทำมาตรฐานการรับรอง', '5'=>'แจ้งระบุเลข ISBN', '6'=>'ดำเนินการ และเสนอผู้มีอำนาจลงนาม', '7'=>'ลงนามเรียบร้อย','8'=>'เสนอราชกิจจานุเบกษา','9'=>'ประกาศราชกิจจานุเบกษาเรียบร้อย'], null, ['id'=>'filter_status', 'class' => 'form-control', 'placeholder'=>'-ค้นหาขั้นตอนการดำเนินงาน-']); !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                  
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                    <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                    <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th width="2%"><input type="checkbox" id="checkall"></th>
                                            <th width="2%" class="text-center">No.</th>
                                            <th width="20%" class="text-center">ประเภทมาตรฐาน</th>
                                            <th width="8%" class="text-center">เลขมาตรฐาน</th>
                                            <th width="15%" class="text-center">ชื่อมาตรฐาน</th>
                                            <th width="15%" class="text-center">ขั้นตอนการจัดทำ</th>
                                            <th width="8%" class="text-center">สถานะ</th>
                                            <th width="10%" class="text-center">จัดการ</th>
                                            <th width="5%" class="text-center">เลขISBN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                    </tbody>
                                </table>
    
                            </div>
                        </div>

      

                </div>
            </div>
        </div>
    </div>

    @include ('certify/standards.modal-isbn')

@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('js/jasny-bootstrap.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
 

<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
 <!-- input file -->
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script>
  $(document).ready(function () {

    //ช่วงวันที่
    $('.date-range').datepicker({
      toggleActive: true,
      language:'th-th',
      format: 'dd/mm/yyyy',
    });

    $('.mydatepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        language:'th-th',
        format: 'dd/mm/yyyy',
        orientation: 'bottom'
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

      var table = $('#myTable').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          ajax: {
              url: '{!! url('/certify/standards/data_list') !!}',
              data: function (d) {
                
                  d.filter_search = $('#filter_text_search').val();
                  d.filter_state = $('#filter_state').val();
                  d.filter_status = $('#filter_status').val();
              }
          },
          columns: [
              { data: 'checkbox', searchable: false, orderable: false},
              { data: 'DT_Row_Index', searchable: false, orderable: false},
              { data: 'set_standard_id', name: 'set_standard_id' },
              { data: 'std_no', name: 'std_no' }, 
              { data: 'std_title', name: 'std_title' },
              { data: 'status_id', name: 'status_id' },
              { data: 'publish_state', name: 'publish_state' },
              { data: 'action', name: 'action' },
              { data: 'isbn_no', name: 'isbn_no' },
          ],
          columnDefs: [
              { className: "text-center", targets:[0,-1,-2] },
  
          ],
          fnDrawCallback: function() {
              $('#myTable_length').find('.totalrec').remove();
              var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
              $('#myTable_length').append(el);

              $('#myTable tbody').find('.dataTables_empty').addClass('text-center');

              
          }
      });



      $( "#button_search" ).click(function() {
            table.draw();
      });

      $( "#filter_clear" ).click(function() {
        $('#filter_search').val('');
        $('#filter_state').val('').select2();
        $('#filter_status').val('').select2();
        table.draw();
      });

      $('#checkall').change(function (event) {

          if ($(this).prop('checked')) {//เลือกทั้งหมด
              $('#myTable').find('input.item_checkbox').prop('checked', true);
          } else {
              $('#myTable').find('input.item_checkbox').prop('checked', false);
          }

    });

    $('#p')


      $(document).on('click', '#bulk_update', function(){

          var id = [];
          $('.item_checkbox:checked').each(function(index, element){
              id.push($(element).val());
          });

          if(id.length > 0){

              if (confirm("ยืนยันการอัพเดทสถานะเผยแพร่มาตรฐาน " + id.length + " แถว นี้ ?")) {
                  $.ajax({
                          type:"POST",
                          url:  "{{ url('/certify/standards/publish_state') }}",
                          data:{
                              _token: "{{ csrf_token() }}",
                              id: id
                          },
                          success:function(data){
                              table.draw();
                              $.toast({
                                  heading: 'Success!',
                                  position: 'top-center',
                                  text: 'อัพเดทสถานะเผยแพร่สำเร็จ !',
                                  loaderBg: '#70b7d6',
                                  icon: 'success',
                                  hideAfter: 3000,
                                  stack: 6
                              });
                              $('#checkall').prop('checked', false);
                          }
                  });
              }

          }else{
              alert("โปรดเลือกอย่างน้อย 1 รายการ");
          }
      });


  });



  function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

  function confirm_delete() {
              return confirm("ยืนยันการลบข้อมูล?");
  }

  function Comma(Num)
  {
      Num += '';
      Num = Num.replace(/,/g, '');

      x = Num.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1))
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
      return x1 + x2;
  }





</script>

@endpush
