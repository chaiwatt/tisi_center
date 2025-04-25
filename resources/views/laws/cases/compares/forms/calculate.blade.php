@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <style>


    </style>
@endpush
@php
    $result_section = $lawcases->result_section;
    $offender_cases = $lawcases->offender_cases();
    $offender       = $lawcases->offender;
    $calculate      = $lawcases->compare_calculate->last();

    if( !empty( $offender) ){
        $url_offender = url('/law/cases/offender/'.$offender->id );
    }else{
        $url_offender = url('law/cases/offender?filter_search='.$lawcases->offend_taxid );
    }

@endphp

<div class="col-md-12">
    <p>
        ตรวจสอบประวัติการกระทำความผิด
        <a class="" href="{!!  $url_offender !!}" target="_blank"> คลิก</a>
    </p>
</div>

<div class="col-md-12">
    <div class="table-responsive repeater-calculate">
        <table class="table table-bordered" id="tb-calculate">
            <thead>
                <tr>
                    <th class="text-center text-top" rowspan="2" width="2%">
                        ลำดับ
                    </th>
                    <th class="text-center text-top" rowspan="2" width="18%">
                        มาตรความผิด
                    </th>
                    <th class="text-center text-top" rowspan="2" width="18%">
                        บทกำหนดลงโทษ
                    </th>
                    <th class="text-center text-top" rowspan="2" width="10%">
                        อัตราต่ำสุด
                        <div>-</div>
                        อัตราสูงสุด
                    </th>
                    <th class="text-center text-top" rowspan="2" width="10%">
                        ความผิดครั้งที่
                    </th>
                    <th class="text-center text-top" rowspan="2" width="10%">
                        มูลค่าผลิตภัณฑ์
                    </th>
                    <th class="text-center text-top" colspan="2" width="32%">
                        คำนวณค่าปรับ (เบื้องต้น)
                    </th>
                </tr>
                <tr>
                    <th class="text-center text-top" width="18%">
                        <div class="m-b-10">อัตราค่าปรับ</div>
                        {!! Form::select('cal_type', ['1'=>'สัดส่วน','2'=>'จำนวนเงิน(ระบุเอง)'], !empty($calculate->cal_type)?$calculate->cal_type:1 , ['class' => 'form-control',  'id' => 'cal_type', 'required' => true])  !!}   
                    </th>
                    <th class="text-center text-top" width="18%">
                        <div>รวมค่าปรับ</div>
                        <small class="text-muted"><em>(มูลค่าผลิตภัณฑ์ x อัตราค่าปรับ/100)</em></small>
                    </th>
                </tr>
            </thead>
            <tbody data-repeater-list="calculate">
                @if( count($result_section) == 0  )
                    <tr><td colspan="9" class="text-center">ไม่พบข้อมูล</td></tr>
                @endif
                @foreach ( $result_section as $key => $Isection )

                    @php
                        //จำยวนความผิดที่ฝ่าฝืน
                        $count             = $offender_cases->whereNotIn('law_cases_id', [ $lawcases->id ] )->whereJsonContains('section', (array)$Isection->section )->count();
                        //มูลค่าผลิตภัณฑ์
                        $total_value       = !empty($lawcases->law_cases_impound_to->total_value) ? number_format($lawcases->law_cases_impound_to->total_value,2) : number_format(0, 2);

                        //บทกำหนดลงโทษ
                        $bs_punish         = $Isection->punish_to;
                        //มาตรความผิด
                        $bs_section        = $Isection->section_to;
                        //คำนวณ
                        $compare_calculate = $Isection->compare_calculate;

                    @endphp

                    <tr data-repeater-item>
                        <td class="text-center text-top">
                            {!! ++$key !!}
                            {!! Form::hidden('law_result_section_id',  $Isection->id ,  ['class' => 'form-control' ]) !!}
                        </td>
                        <td class="text-left text-top">
                            {!! !empty( $bs_section )?$bs_section->number:null !!}
                            {!! !empty( $bs_section )?': '.$bs_section->title:null !!}
                        </td>
                        <td class="text-left text-top">
                            {!! !empty( $bs_punish )?$bs_punish->number:null !!}
                            {!! !empty( $bs_punish )?': '.$bs_punish->title:null !!}
                        </td>
                        <td class="text-center text-top">
               
                            @if( !empty( $bs_punish ) && $bs_punish->adjustment_type == 1 )
                                {!! !empty($bs_punish->adjustment)?number_format($bs_punish->adjustment, 2):number_format(0,2) !!}
                            @elseif( !empty( $bs_punish ) && $bs_punish->adjustment_type == 2  )
                                {!! !empty($bs_punish->adjustment)?number_format($bs_punish->adjustment, 2):number_format(0,2) !!} 
                                {!! !empty($bs_punish->adjustment_max)?' - '.number_format($bs_punish->adjustment_max, 2):null !!}
                            @endif
                        </td>
                        <td class="text-center text-top">
                            {{-- {!! number_format( ($count + 1) ) !!} --}}
                            {!! Form::text('mistake',  !empty(  $compare_calculate->mistake)? $compare_calculate->mistake: number_format( ($count + 1)), ['class' => 'form-control  input_number text-center']) !!}
                        </td>
                        <td class="text-right text-top">
                            {!! $total_value  !!}
                            {!! Form::hidden('total_value',  $total_value ,  ['class' => 'form-control text-center cal_total_value' ]) !!}
                        </td>        
                        <td class="text-left text-top">
                            <div class="input-group">
                                {!! Form::text('division',  !empty(  $compare_calculate->division )?$compare_calculate->division:null ,  ['class' => 'form-control text-center input_number cal_division' ]) !!}
                                <span class="input-group-addon bg-white cal_text_addon"> % </span>
                            </div>
                        </td>
                        <td class="text-left text-top">
                            {!! Form::text('amount',  !empty(  $compare_calculate->amount )?$compare_calculate->amount:null, ['class' => 'form-control cal_total input_number text-right', 'readonly' => true]) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="7">รวมค่าปรับ</td>
                    <td class="foot_all_total text-right"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<center>
    <div class="form-group m-t-15">
        <div class="col-md-12">

            <button class="btn btn-primary btn-rounded" type="submit">
                <i class="fa fa-save"></i> บันทึก
            </button>
    
            @can('view-'.str_slug('law-cases-compares'))
                <a class="btn btn-default show_tag_a btn-rounded"  href="{{ url('/law/cases/compares') }}">
                    <i class="fa fa-rotate-right"></i> ยกเลิก
                </a>
            @endcan
        </div>
    </div>
</center>

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.repeater-calculate').repeater({
                show: function () {
                    $(this).slideDown();

                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                        setTimeout(function(){
    
                        }, 500);

                    }
                }
            });

            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".input_number").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 
            CalAmount();

            $("input.cal_division").on("keyup",function(e){ 

                var cal_type     = $('#cal_type').val();
                var row          = $(this).closest('tr');

                let division     = $(this).val();
                let cal_division = parseFloat( RemoveCommas( checkNone(division)?division:"0" ) );

                if( cal_type == 1 &&  cal_division > 100  ){
                    $(this).val(100);
                }
                CalAmount();
            });

            $('#cal_type').change(function (e) { 
                Condition();
            });
            Condition();

            $("input.cal_total").on("keyup",function(e){ 
                CalAmount();
            });

        });

        function CalAmount(){

            var cal_type       = $('#cal_type').val();
            var tb_calculate   = $('#tb-calculate > tbody  > tr');
            var foot_all_total = 0;
            if(  tb_calculate.length >= 1 ){
                tb_calculate.each(function (index, rowId) {

                    let total       = $(rowId).find('input.cal_total_value').val();
                    let total_value = parseFloat( RemoveCommas( checkNone(total)?total:"0" ) );
                    if( !checkNone(total_value) ){
                        total_value = 0;
                    }

                    let division     = $(rowId).find('input.cal_division').val();
                    let cal_division = parseFloat( RemoveCommas( checkNone(division)?division:"0" ) );
                    if( !checkNone(cal_division) ){
                        cal_division = 0;
                    }
 
                    if( cal_type == "1"){ // %

                        let amount = 0;

                        if( cal_division > 100 ){
                            cal_division = 100;
                        }
                        amount = (parseFloat( total_value ) *  parseFloat(  cal_division ) ) / 100;

                        foot_all_total += amount;

                        $(rowId).find('input.cal_total').val( addCommas(amount.toFixed(2), 2) );
                    }else{

                        let amount       = $(rowId).find('input.cal_total').val();
                        let amount_value = parseFloat( RemoveCommas( checkNone(amount)?amount:"0" ) );
                        if( !checkNone(amount_value) ){
                            amount_value = 0;
                        }

                        foot_all_total += amount_value;
                    }

                });
            }

            $('.foot_all_total').text( addCommas(foot_all_total.toFixed(2), 2) );


        }

        function Condition(){

            var cal_type       = $('#cal_type').val();
            var tb_calculate   = $('#tb-calculate > tbody  > tr');

            tb_calculate.find('input.cal_division').prop('readonly', true);
            tb_calculate.find('input.cal_total').prop('readonly', true);

            if(  tb_calculate.length >= 1 ){
                if( cal_type == "1" ){
                    tb_calculate.find('input.cal_division').prop('readonly', false);
                    tb_calculate.find('input.cal_division').prop('required', true);
                    tb_calculate.find('input.cal_total').prop('readonly', true);
                    tb_calculate.find('input.cal_total').prop('required', false);
                }else{
                    tb_calculate.find('input.cal_division').val('');
                    tb_calculate.find('input.cal_division').prop('required', false);
                    tb_calculate.find('input.cal_total').prop('readonly', false);
                    tb_calculate.find('input.cal_total').prop('required', true);
                }
            }


        }
    </script>

@endpush