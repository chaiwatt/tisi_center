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
                    <h3 class="box-title pull-left">สถิติการออกใบรับรอง</h3>
 

                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    {!! Form::model($filter, ['url' => '/cerreport/certificate_export', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-md-12">
              
                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                                  {!! Form::select('filter_certify', ['1'=>'ใบรับรองอิเล็กทรอนิกส์','2'=>'ไม่เป็นใบรับรองอิเล็กทรอนิกส์'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกปรเภทใบรอง-','id'=>'filter_certify']); !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                             {!! Form::select('filter_years', $filter_years, null, ['class' => 'form-control', 'placeholder'=>'-เลือกปี-','id'=>'filter_years']); !!}
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
                                                  $array = [];
                                           @endphp
                               @if (count($years) > 0)
                                        @foreach ($years as  $year => $item)
                                        @php
                                               $sum = 0;
                                               $ibs = array_key_exists($year,$export_ibs)  ? $export_ibs[$year] : 0;
                                               $cbs = array_key_exists($year,$export_cbs)  ? $export_cbs[$year] : 0;
                                               $labs = array_key_exists($year,$export_labs)  ? $export_labs[$year] : 0;

                                               $ibs_sums  +=  $ibs;
                                               $cbs_sums  +=  $cbs;
                                               $labs_sums +=  $labs;

                                               $sum +=  $ibs;
                                               $sum +=  $cbs;
                                               $sum +=  $labs;
                                               $sums += $sum;

                                               $object           = (object)[];
                                               $object->year     =  ''.($year +543).'' ;
                                               $object->ibs      = $ibs ;
                                               $object->cbs      = $cbs ;
                                               $object->labs     = $labs ;
                                               $array[]          = $object;
                                        @endphp
                                           <tr>
                                                  <td class="text-center">{!! $year +543 !!}</td>
                                                  <td class="text-center">{!! $ibs !!}</td>
                                                  <td class="text-center">{!! $cbs !!}</td>
                                                  <td class="text-center">{!! $labs !!}</td>
                                                  <td class="text-center">{!! $sum !!}</td>
                                           </tr>           
                                        @endforeach 
                               @endif
                                </tbody>
                                <tfoot>
                                        <tr>
                                          <td class="text-center"><b>รวม</b></td>
                                          <td class="text-center"><b>{!!  $ibs_sums !!}</b> </td>
                                          <td class="text-center"><b>{!!  $cbs_sums !!}</b></td>
                                          <td class="text-center"><b>{!!  $labs_sums !!}</b></td>
                                          <td class="text-center"><b> {!!  $sums !!}</b></td>
                                        </tr>
                                </tfoot>
                                @php
                                    sort($array);
                               @endphp
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
                window.location.assign("{{url('/cerreport/certificate_export')}}");
            });
 
            var array = {!! !empty($array)?json_encode($array): "null" !!};
            if(array != null){
                    var item_name = Array();
                        $.each(array, function( index, data ) {
                            item_name.push(data);
                        });
            }else{
                    var item_name =[
                        {
                        year: '',
                        ibs: 0,
                        cbs: 0,
                        labs: 0
                    }]; 
            }
            console.log(item_name);
             Morris.Area({
                element: 'chart_cer',
                data: item_name,
                xkey: 'year',
                ykeys: ['ibs', 'cbs', 'labs'],
                labels: ['หน่วยตรวจ', 'หน่วยรับรอง', 'ห้องปฏิบัติการ'],
                barColors: ['#2ecc71', '#0283cc', '#ffb136'],
                pointSize: 3,
                fillOpacity: 0,
                pointStrokeColors: ['#00bbd9', '#ffb136', '#4a23ad'],
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                lineWidth: 1,
                hideHover: 'auto',
                lineColors: ['#00bbd9', '#ffb136', '#4a23ad'],
                resize: true
            });



        });
 


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
 
    </script> 
@endpush
