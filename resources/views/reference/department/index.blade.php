@extends('layouts.master')

@push('css')

<link rel="stylesheet" href="{!! asset('plugins/components/datatables/media/css/jquery.dataTables.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('plugins/components/datatables/buttons.dataTables.min.css') !!}" />

<style>

  /*
	Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
	*/
	@media not print {
		@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {

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
				border: none;
				border-bottom: 1px solid #eee;
				position: relative;
				padding-left: 50%;
			}

			td:before {
				top: 0;
				left: 6px;
				width: 45%;
				padding-right: 10px;
				white-space: nowrap;
			}

			td:nth-of-type(1):before { content: "#:"; }
		    td:nth-of-type(2):before { content: "รหัสหน่วยงานย่อย:"; }
		    td:nth-of-type(3):before { content: "ชื่อหน่วยงานย่อย:"; }
		    td:nth-of-type(4):before { content: "ชื่อย่อหน่วยงานย่อย:"; }
		    td:nth-of-type(5):before { content: "รหัสหน่วยงานหลัก:"; }
		    td:nth-of-type(6):before { content: "ชื่อหน่วยงานหลัก:"; }

		}
	}

    table.dataTable thead .sorting_desc::after{
        content: "";
    }
</style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หน่วยงาน</h3>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="table-responsive">

                        <table class="table table-borderless" id="myTable">
                            <thead>
	                            <tr>
	                                <th class="col-md-1">#</th>
	                                <th class="col-md-2">รหัสหน่วยงานย่อย</th>
	                                <th class="col-md-3">ชื่อหน่วยงานย่อย</th>
	                                <th class="col-md-2">ชื่อย่อหน่วยงานย่อย</th>
	                                <th class="col-md-1">รหัสหน่วยงานหลัก</th>
	                                <th class="col-md-3">ชื่อหน่วยงานหลัก</th>
	                            </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->sub_id }}</td>
                                        <td>{{ $item->sub_departname }}</td>
                                        <td>{{ $item->sub_depart_shortname }}</td>
                                        <td>{{ $item->did }}</td>
                                        <td>{{ $item->department->depart_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/dataTables.buttons.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/ajax/jszip.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/ajax/pdfmake.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/ajax/vfs_fonts-th.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/button/buttons.html5.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('plugins/components/datatables/media/js/button/buttons.print.min.js') !!}"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
				dom: 'Bfrtip',
				buttons: [
		   			'copy', 'print',
					{ // กำหนดพิเศษเฉพาะปุ่ม pdf
				        "extend": 'pdf', // ปุ่มสร้าง pdf ไฟล์
				        "text": 'PDF', // ข้อความที่แสดง
						"title": 'ข้อมูลหน่วยงาน',//ชื่อไฟล์
				        "pageSize": 'A4',   // ขนาดหน้ากระดาษเป็น A4
				        "customize": function(doc){ // ส่วนกำหนดเพิ่มเติม ส่วนนี้จะใช้จัดการกับ pdfmake
				            // กำหนด style หลัก
				            doc.defaultStyle = {
				                font:'THSarabun',
				                fontSize:14
				            };

							// กำหนดความกว้างของ header แต่ละคอลัมน์หัวข้อ
            				doc.content[1].table.widths = [18, 40, 160, 50, 40, 168];
				        }
				    }, // สิ้นสุดกำหนดพิเศษปุ่ม pdf
					{
		                extend: 'csvHtml5',
		                title: 'ข้อมูลหน่วยงาน'
		            },
					{
		                extend: 'excelHtml5',
		                title: 'ข้อมูลหน่วยงาน'
		            }
	   			],
                pageLength : 50,
                language: {
                    lengthMenu: "แสดง _MENU_ รายการ",
                    search: "ค้นหา:",
                    paginate: {
                        previous:   "ก่อนหน้า",
                        next:       "ต่อไป",
                    }
                }
            });

			//Config Export PDF Font
			pdfMake.fonts = {
				THSarabun: {
				    normal: 'THSarabun.ttf',
				    bold: 'THSarabun-Bold.ttf',
				    italics: 'THSarabun-Italic.ttf',
				    bolditalics: 'THSarabun-BoldItalic.ttf'
				}
			}

        });
    </script>
@endpush
