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
                    
                    <h3 class="box-title pull-left">รายงานข้อคิดเห็นต่อร่างมาตรฐาน</h3>

                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::model(['url' => '/tis/report_comment', 'method' => 'get', 'id' => 'myFilter']) !!}

                    <div class="col-md-4">
                        {!! Form::label('filter_standrad', 'มาตรฐาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                          {!! Form::select('standrad', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'placeholder'=>'- เลือกมาตรฐาน -', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('filter_standrad_group', 'กลุ่มมาตรฐาน/สาขา:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                          {!! Form::select('standrad_group', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'placeholder' => '-- เลือกกลุ่มผลิตภัณฑ์/สาขา -', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('filter_search', 'ค้นหา:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                              {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder'=>'ค้นชื่อมาตรฐานหรือเลข มอก.', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        {!! Form::label('filter_standrad', 'หน่วยงาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                          {!! Form::select('standrad', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'placeholder' => '- เลือกหน่วยงาน -', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('filter_standrad_group', 'ความคิดเห็น :', ['class' => 'col-md-3 control-label label-filter']) !!}
                        <div class="col-md-9">
                          {!! Form::select('standrad_group', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'placeholder' => '- เลือกความคิดเห็น -' ,'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                    <div class="col-md-4">
                        
                        <div class="col-md-9">
                            <input type="button" class="btn btn-primary" value="แสดงรายงาน">
                        </div>
                    </div>


                    <input type="hidden" name="sort" value="{{ Request::get('sort') }}" />
                    <input type="hidden" name="direction" value="{{ Request::get('direction') }}" />

                    {!! Form::close() !!}

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                      {!! Form::open(['url' => '/tis/report_comment/multiple', 'method' => 'delete', 'id' => 'myForm', 'class'=>'hide']) !!}

                      {!! Form::close() !!}

                      {!! Form::open(['url' => '/tis/report_comment/update-state', 'method' => 'put', 'id' => 'myFormState', 'class'=>'hide']) !!}
                        <input type="hidden" name="state" id="state" />
                      {!! Form::close() !!}

                    <center><h2>รายงานข้อคิดเห็นต่อร่างมาตรฐาน</h2>
                        <h5>ข้อมูล ณ วันที่ 14 พฤษภาคม 2562 เวลา 12:00 น.</h5>
                    </center>

                      <div class="col-md-4">
                        {!! Form::label('perPage', 'Show:', ['class' => 'col-md-2 control-label label-filter']) !!}
                        <div class="col-md-5">
                              {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                        </div>
                    </div>

                      <table class="table table-borderless" id="myTable">
                          <thead>
                          <tr>
                            <th>No.</th>
                            <th>@sortablelink('title', 'วันที่')</th>
                            <th>@sortablelink('tis_no', 'ผู้ให้ความคิดเห็น')</th>
                            <th>@sortablelink('', 'หน่วยงาน')</th>
                            <th>@sortablelink('', 'เลขมอก.')</th>
                            <th>@sortablelink('', 'ชื่อมาตรฐาน')</th>
                            <th>@sortablelink('date_draft', 'กลุ่มผลิตภัณฑ์/สาขา')</th>
                            <th>@sortablelink('work_group', 'เบอร์โทร')</th>
                            <th>@sortablelink('comment', 'ความคิดเห็น')</th>
                            <th>@sortablelink('', 'E-mail')</th>
                            <th>เครื่องมือ</th>
                          </tr>      
                      </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')

@endpush