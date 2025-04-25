@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
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
                    <h3 class="box-title pull-left">E-mail   ใบรับรอง LAB,CB,IB</h3>

                    <a class="btn btn-success pull-right" href="{{url('/certify')}}">
                         <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
     
                    <div class="clearfix"></div>  
                    <hr>
    {!! Form::open(['url' => '/certify/authorities-lt', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="row">
                           <div class="col-md-12 text-right form-group">
                              <button type="button" class="btn btn-success btn-sm" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
                           </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table color-bordered-table info-bordered-table table-bordered" id="myTable">
                                        <thead>
                                                <tr>
                                                    <th class="text-center" width="2%">ลำดับ</th>
                                                    <th class="text-center" width="25%">ใบรับรอง</th>
                                                    <th class="text-center" width="25%">สิทธิ์</th>
                                                    <th class="text-center" width="15%">ส่งแบบ</th>
                                                    <th class="text-center" width="25%">Email</th>
                                                    <th class="text-center" width="10%">
                                                      <i class="fa fa-pencil-square-o"></i>
                                                    </th>
                                                </tr>
                                        </thead>
                                        <tbody id="table-body">
                                          @php 
                                          $data = ['1'=>'LAB','2'=>'CB','3'=>'IB'];
                                        @endphp
                                      @foreach($certi as $key =>  $item)
                                          <tr>
                                            <td class="text-center">{{  ($key +1)  }}</td>
                                            <td>   
                                              <input type="hidden"  name="data[id][]" value=" {{ $item->id ?? null }}">
                                               {!! Form::select('data[certi][]',
                                                ['1802'=>'กลุ่มรับรองหน่วยตรวจ',
                                                 '1803'=>'กลุ่มรับรองหน่วยรับรอง',
                                                 '1804'=>'กลุ่มรับรองห้องปฏิบัติการ 1',
                                                 '1805'=>'กลุ่มรับรองห้องปฏิบัติการ 2',
                                                 '1806'=>'กลุ่มรับรองห้องปฏิบัติการ 3'
                                                ],
                                                $item->certi ?? null, 
                                                ['class' => 'form-control select2', 
                                                  'required' => true,
                                                  'placeholder' =>'- เลือกใบรับรอง -']) !!}
                                             </td>
                                            <td> 
                                              {!! Form::select('data[roles][]',
                                                ['1'=>'ผู้อำนวยการกลุ่ม สก.',
                                                '2'=>'เจ้าหน้าที่ ลท.',
                                                '3'=>'mail กลาง'
                                                ],
                                                 $item->roles ?? null, 
                                                ['class' => 'form-control select2', 
                                                'required' => true,
                                                'placeholder' =>'- เลือกสิทธิ์ -']) !!}

                                            </td>
                                            <td> 
                                              <label>
                                                {!! Form::checkbox('data[cc][]', '0', 
                                                   ($item->cc == 1) ? true  : false,    
                                                ['class'=>'check cc',
                                                'data-checkbox'=>"icheckbox_flat-green"]) !!} 
                                                  CC
                                              </label>
                                              <label>
                                                {!! Form::checkbox('data[reply_to][]', '0', 
                                                  ($item->reply_to == 1) ? true  : false,    
                                                ['class'=>'check reply_to',
                                                'data-checkbox'=>"icheckbox_flat-green"]) !!} 
                                                  replyTo
                                              </label>
                                              </td>
                                             <td>
                                              {!! Form::email('data[emails][]',
                                               $item->emails ?? null,
                                                ['class' => 'form-control ',
                                                'required'=>true])!!}
                                             </td>
                                           
                                             <td class="text-center">
                                               <button class="btn btn-danger btn-sm remove-row" type="button">
                                                  <i class="icon-close"></i>  
                                                </button>
                                            </td>
                                          </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                  </div>
                  
    
<div class="form-group">
  <div class="col-md-offset-4 col-md-4">
      <button class="btn btn-primary btn-lg btn-block" type="submit" id="form-save" >
          <i class="fa fa-paper-plane"></i> บันทึก
      </button>
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
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>
       $(document).ready(function () {
 

        ResetTableNumber();
        //เพิ่มแถว
        $('#addCostInput').click(function(event) {
          //Clone
          $('#table-body').children('tr:first()').clone().appendTo('#table-body');
          //Clear value
            var row = $('#table-body').children('tr:last()');
            row.find('select.select2').val('');
            row.find('select.select2').prev().remove();
            row.find('select.select2').removeAttr('style');
            row.find('select.select2').select2();
   
            row.find('input[type="email"],input[type="hidden"]').val('');
            row.find('input[type="email"]').removeClass('parsley-error');
            row.find('.parsley-required').html('');
            
            row.find('.cc').prependTo(row.find('.cc').parent().parent());
            row.find('.reply_to').prependTo(row.find('.reply_to').parent().parent());

            row.find('.icheckbox_flat-green').remove();

            row.find('.check').prop('checked',false);
            row.find('.check').each(function() {
            var ck = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-green';
            var rd = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-green';

            if (ck.indexOf('_line') > -1 || rd.indexOf('_line') > -1) {
                $(this).iCheck({
                    checkboxClass: ck,
                    radioClass: rd,
                    insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                });
            } else {
                $(this).iCheck({
                    checkboxClass: ck,
                    radioClass: rd
                });
            }
          });

              ResetTableNumber();
        });
        
            //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
          });

          function ResetTableNumber(){
             var rows = $('#table-body').children(); //แถวทั้งหมด
              (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
              rows.each(function(index, el) {
                  //เลขรัน
                  $(el).children().first().html(index+1);
                  //เลข index checkbox
                  $(el).children().find('.cc').prop('name', 'data[cc]['+index+']');
                  $(el).children().find('.reply_to').prop('name', 'data[reply_to]['+index+']');
              });
            }
        
       });
 
     </script>
@endpush
