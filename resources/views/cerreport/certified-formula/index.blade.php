@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/morrisjs/morris.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานคำขอตาม มอก.</h3>
 

                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    {!! Form::model($filter, ['url' => '/cerreport/certified-formula', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-md-12">
              
                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                                  {!! Form::select('filter_formula',
                                                 App\Models\Bcertify\Formula::pluck('title','id') , 
                                                  null, 
                                                  ['class' => 'form-control', 'placeholder'=>'-เลือก มอก.-','id'=>'filter_formula']); !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                             {!! Form::select('filter_years', HP::YearList(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกปี-','id'=>'filter_years']); !!}
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group  pull-left">
                                            <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="submit">ค้นหา</button>
                                        </div>
                                        <div class="form-group  pull-left m-l-15">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                                ล้าง
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   {!! Form::close() !!}
                   
                    <div class="clearfix"></div>
      
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table  table-bordered color-bordered-table info-bordered-table" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">มอก.</th>
                                        <th width="9%" class="text-center">ปี</th>
                                        <th width="10%" class="text-center">IB</th>
                                        <th width="10%" class="text-center">CB</th>
                                        <th width="10%"  class="text-center">LAB</th>
                                        <th width="10%" class="text-center">รวม</th>           
                                    </tr>
                                </thead>
                                <tbody>
                                             @php
                                                  $ibs_sums = 0;
                                                  $cbs_sums = 0;
                                                  $labs_sums = 0;
                                                  $sums = 0;
                                               
                                           @endphp
                                        @if (count($formula) > 0)
                                            @foreach ($formula as  $key => $item)
                                                @php
                                                    $labs   =   $app_labs->where('standard_id',$item->id)->pluck('year')->toArray();
                                                    $cbs    =   $app_cbs->where('standard_id',$item->id)->pluck('year')->toArray();
                                                    $ibs    =   $app_ibs->where('standard_id',$item->id)->pluck('year')->toArray();
                                                    $years  =  array_merge($labs,$cbs,$ibs)
                                                @endphp
                                       
                                                @if (count($years) > 0)
                                                      @foreach ($years as  $key1 => $year)
                                                      @php
                                                       
                                                        $count_labs   =   $app_labs->where('standard_id',$item->id)->where('year',$year)->count();
                                                        $count_cbs   =   $app_cbs->where('standard_id',$item->id)->where('year',$year)->count();
                                                        $count_ibs   =   $app_ibs->where('standard_id',$item->id)->where('year',$year)->count();


                                                        $ibs_sums  +=  $count_ibs;
                                                        $cbs_sums  +=  $count_cbs;
                                                        $labs_sums +=  $count_labs;


                                                        $sum = 0;
                                                        $sum +=  $count_labs;
                                                        $sum +=  $count_cbs;
                                                        $sum +=  $count_ibs;
                                                        $sums += $sum;

                                                    @endphp
                                                            <tr>
                                                                <td>{!! ($key1 == 0)  ? $item->title : "" !!}</td>
                                                                <td class="text-center">{!! $year +543 !!}</td>
                                                                <td class="text-center">{!! $count_labs  !!}</td>
                                                                <td class="text-center">{!! $count_cbs  !!}</td>
                                                                <td class="text-center">{!! $count_ibs  !!}</td>
                                                                <td class="text-center">{!! $sum  !!}</td>
                                                            </tr>    
                                                      @endforeach
                                                @else
                                                        <tr>
                                                            <td >{!!    $item->title   !!}</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">0</td>
                                                            <td class="text-center">0</td>
                                                            <td class="text-center">0</td>
                                                            <td class="text-center">0</td>
                                                        </tr>    
                                                @endif
                                                  
                                            @endforeach
                                        @endif
                                </tbody>
                                <tfoot>
                                        <tr>
                                          <td class="text-center"><b>รวม</b></td>
                                          <td class="text-center"> </td>
                                          <td class="text-center"><b>{!!  $ibs_sums !!}</b> </td>
                                          <td class="text-center"><b>{!!  $cbs_sums !!}</b></td>
                                          <td class="text-center"><b>{!!  $labs_sums !!}</b></td>
                                          <td class="text-center"><b> {!!  $sums !!}</b></td>
                                        </tr>
                                </tfoot>
                              
                            </table>
      
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"> </div>
                        <div class="col-md-10">
                              <div id="chart_cer"></div>
                              <div class="clearfix"></div>
                        </div>
                        <div class="col-md-1"> </div>
                    </div>
       
      
                </div>
            </div>
        </div>
      
      </div>
@endsection
@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
       <!--Morris JavaScript -->
   <script src="{{asset('plugins/components/raphael/raphael-min.js')}}"></script>
   <script src="{{asset('plugins/components/morrisjs/morris.js')}}"></script>
 

    <script>
        $(document).ready(function () {
          $( "#btn_clean" ).click(function() {
                $('#filter_certify').val('').select2();
                $('#filter_years').val('').select2();
                window.location.assign("{{url('/cerreport/certified-formula')}}");
            });
  



        });
 


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
 
    </script> 
@endpush
