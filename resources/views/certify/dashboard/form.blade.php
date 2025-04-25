@push('css')
<link href="{{asset('plugins/components/morrisjs/morris.css')}}" rel="stylesheet">
@endpush


<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <h4 class="box-title">จำนวนสมัครคำขอ</h4>
                <div class="row">
                    <div class="col-md-10">
                        <div id="chart_app"></div>
                    </div>
                    <div class="col-md-2">
                            {!! Form::select('filter_year_app',
                           App\Models\Certify\Applicant\CertiLab::select(DB::raw('year(start_date) AS year'))
                                                                ->whereNotNull('start_date')                                
                                                                ->groupBy(DB::raw('year(start_date)'))
                                                                ->orderby('year','desc')
                                                                 ->pluck('year','year'),
                            null, 
                            ['class' => 'form-control', 
                            'id'=>'filter_year_app',
                            'placeholder' => '- เลือกปี -'])!!}
                            <br><br>
                            <p><i class="fa fa-circle text-success m-r-5"></i>ห้องปฏิบัติการ</p> 
                            <p><i class="fa fa-circle text-primary m-r-5"></i>หน่วยรับรอง</p> 
                            <p><i class="fa fa-circle text-warning m-r-5"></i>หน่วยตรวจ</p>  
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <h4 class="box-title">จำนวนใบรับรอง</h4>
                <div class="row">
                    <div class="col-md-10">
                        <div id="chart_cer"></div>
                    </div>
                    <div class="col-md-2">
                            {!! Form::select('filter_year_cer',
                             App\CertificateExport::select(DB::raw('year(certificate_date_start) AS year'))
                                                                ->whereNotNull('certificate_date_start')     
                                                                 ->whereIn('status',[3])                                 
                                                                ->groupBy(DB::raw('year(certificate_date_start)'))
                                                                ->orderby('year','desc')
                                                                 ->pluck('year','year'),
                            null, 
                            ['class' => 'form-control', 
                            'id'=>'filter_year_cer',
                            'placeholder' => '- เลือกปี -'])!!}
                            <br><br>
                            <p><i class="fa fa-circle text-success m-r-5"></i>ห้องปฏิบัติการ</p> 
                            <p><i class="fa fa-circle text-primary m-r-5"></i>หน่วยรับรอง</p> 
                            <p><i class="fa fa-circle text-warning m-r-5"></i>หน่วยตรวจ</p>  
                    </div>
                </div>
        </div>
    </div>
</div>

@push('js')
   <!--Morris JavaScript -->
   <script src="{{asset('plugins/components/raphael/raphael-min.js')}}"></script>
   <script src="{{asset('plugins/components/morrisjs/morris.js')}}"></script>
 

     <script>
      $(document).ready(function() {
                draw_app();
                chart_cer();
            $('#filter_year_app').change(function(){
                draw_app();
            });
         
            $('#filter_year_cer').change(function(){
                chart_cer();
            });
        });

 
    function draw_app(){
        $.ajax({
            url: '{{ url('certify/dashboard/draw_app') }}',
            data: {
                filter_year_app: $('#filter_year_app').val()
            },
            type: 'GET',
        }).done(function( object ) {
                    if(object.message  == true){
                     var item_name = Array();
                        $.each(object.datas, function( index, data ) {
                            item_name.push(data);
                        });
                        $('#chart_app').html('');
                        Morris.Bar({
                            element: 'chart_app',
                            data: item_name,
                            xkey: 'year',
                            ykeys: ['sum_labs', 'sum_cbs', 'sum_ibs'],
                            labels: ['ห้องปฏิบัติการ', 'หน่วยรับรอง', 'หน่วยตรวจ'],
                            barColors: ['#2ecc71', '#0283cc', '#ffb136'],
                            hideHover: 'auto',
                            gridLineColor: '#e0e0e0',
                            resize: true

                        });
                    }
            });
    }
    function chart_cer(){
        $.ajax({
            url: '{{ url('certify/dashboard/chart_cer') }}',
            data: {
                filter_year_cer: $('#filter_year_cer').val()
            },
            type: 'GET',
        }).done(function( object ) {
                    if(object.message  == true){
                     var items = Array();
                        $.each(object.datas, function( index, data ) {
                            items.push(data);
                        });
                        $('#chart_cer').html('');
                        Morris.Bar({
                            element: 'chart_cer',
                            data: items,
                            xkey: 'year',
                            ykeys: ['sum_labs', 'sum_cbs', 'sum_ibs'],
                            labels: ['ห้องปฏิบัติการ', 'หน่วยรับรอง', 'หน่วยตรวจ'],
                            barColors: ['#2ecc71', '#0283cc', '#ffb136'],
                            hideHover: 'auto',
                            gridLineColor: '#e0e0e0',
                            resize: true


                        });
                    }
            });
    }


    </script>

  @endpush
   
  