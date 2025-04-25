@extends('layouts.master')

@push('css')

<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

<style>
  .label-filter {
    margin-top: 7px;
  }

  /*
	Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
	*/
  @mediaonly screen and (max-width: 760px),
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
        <h3 class="box-title pull-left">ตั้งค่าวาระ</h3>

        <div class="pull-right">


        </div>

        <div class="clearfix"></div>
        <hr>

        {!! Form::model($config_term, ['url' => '/basic/config_term', 'class' => 'form-horizontal']) !!}

        <div class="form-group {{ $errors->has('age') ? 'has-error' : ''}}">
          {!! Form::label('age', 'อายุของแต่ละวาระ', ['class' => 'col-md-4 control-label required']) !!}
          <div class="col-md-1">
            {!! Form::text('age', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('age', '<p class="help-block">:message</p>') !!}
          </div>
          <div class="col-md-2 label-filter">ปี</div>
        </div>

        <div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
          {!! Form::label('amount', 'จำนวนวาระต่อคน', ['class' => 'col-md-4 control-label required']) !!}
          <div class="col-md-1">
            {!! Form::text('amount', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
          </div>
          <div class="col-md-2 label-filter">วาระ</div>
        </div>

        <div class="table-responsive">

          <div style="margin-bottom:5px;"><b>แจ้งเตือนหมดอายุวาระ</b></div>

          <table class="table table-striped table-bordered color-bordered-table info-bordered-table">
            <thead>
              <tr>
                <th class="text-center">เปิดใช้งาน</th>
                <th class="text-center">สีที่แสดง</th>
                <th class="text-center">เงื่อนไข</th>
                <th class="text-center">ค่าที่ต้องการ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center">
                  {{ Form::checkbox('state1', '1', null, ['class'=>'switch']) }}
                </td>
                <td class="text-center col-md-3"><span class="label label-danger"><b>&nbsp;&nbsp;แดง&nbsp;&nbsp;</b></span></td>
                <td>{{
                        Form::select('condition1',
                                     ['>'=>'มากกว่า', '<'=>'น้อยกว่า', ','=>'ระหว่าง'],
                                     null,
                                     ['class' => 'form-control input-xs condition', 'placeholder' => '-เงื่อนไข-']
                                    )
                    }}
                </td>
                <td class="col-md-4">
                    <div class="col-md-3 equal">
                      {!! Form::text('alert10', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-1 label-filter equal">
                      ถึง
                    </div>
                    <div class="col-md-3">
                      {!! Form::text('alert1', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-1 label-filter">
                      ปี
                    </div>
                </td>
              </tr>
              <tr>
                <td class="text-center">
                  {{ Form::checkbox('state2', '1', null, ['class'=>'switch']) }}
                </td>
                <td class="text-center col-md-3"><span class="label label-warning"><b>เหลือง</b></span></td>
                <td>{{
                          Form::select('condition2',
                                       ['>'=>'มากกว่า','<'=>'น้อยกว่า', ','=>'ระหว่าง'],
                                       null,
                                       ['class' => 'form-control input-xs condition', 'placeholder' => '-เงื่อนไข-']
                                      )
                    }}
                </td>
                <td class="col-md-4">
                    <div class="col-md-3 equal">
                      {!! Form::text('alert20', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-1 label-filter equal">
                      ถึง
                    </div>
                    <div class="col-md-3">
                      {!! Form::text('alert2', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-1 label-filter">
                      ปี
                    </div>
                </td>
              </tr>
              <tr>
                <td class="text-center">
                  {{ Form::checkbox('state3', '1', null, ['class'=>'switch']) }}
                </td>
                <td class="text-center col-md-3"><span class="label label-success"><b>&nbsp;เขียว&nbsp;</b></span></td>
                <td>{{
                          Form::select('condition3',
                                       ['>'=>'มากกว่า', '<'=>'น้อยกว่า', ','=>'ระหว่าง'],
                                       null,
                                       ['class' => 'form-control input-xs condition', 'placeholder' => '-เงื่อนไข-']
                                      )
                    }}
                </td>
                <td class="col-md-4">
                  <div class="col-md-3 equal">
                    {!! Form::text('alert30', null, ['class' => 'form-control']) !!}
                  </div>
                  <div class="col-md-1 label-filter equal">
                    ถึง
                  </div>
                  <div class="col-md-3">
                    {!! Form::text('alert3', null, ['class' => 'form-control']) !!}
                  </div>
                  <div class="col-md-3 label-filter">
                    ปี
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

        </div>

        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">

                <button class="btn btn-primary" type="submit">
                  <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('config_term'))
                    <a class="btn btn-default" href="{{ url()->previous() }}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>
@endsection



@push('js')
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<!-- Switchery -->
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function() {

    @if(\Session::has('flash_message'))
    $.toast({
      heading: 'Success!',
      position: 'top-center',
      text: '{{session()->get('
      flash_message ')}}',
      loaderBg: '#70b7d6',
      icon: 'success',
      hideAfter: 3000,
      stack: 6
    });
    @endif

    // Switchery
    $(".switch").each(function() {
      new Switchery($(this)[0], {
                                  color: '#13dafe'
                                })
    });

    //condition change
    $('.condition').change(function(){

      if($(this).val()==','){//ระหว่าง
        $(this).parent().parent().find('.equal').show();
      }else{
        $(this).parent().parent().find('.equal').hide();
      }

    });

    $('.condition').change();


  });

</script>

@endpush
