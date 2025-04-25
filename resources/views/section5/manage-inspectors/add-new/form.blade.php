@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style>

    </style>
@endpush

<!-- ข้อมูลผู้ยื่น -->
@include ('section5.manage-inspectors.add-new.infomation')

<!-- ข้อมูลขอรับบริการ -->
@include ('section5.manage-inspectors.add-new.scope')


<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('start_date', 'ขอบข่ายที่ขอรับการแต่งตั้งมีผลตั้งแต่วันที่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('start_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            {!! Form::label('end_date', 'สิ้นสุดวันที่', ['class' => 'col-md-5 control-label']) !!}
            <div class="col-md-5">
                <div class="input-group">
                    {!! Form::text('end_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<center>
    <div class="form-group m-t-10">
        <div class="col-md-offset-4 col-md-4">

            @can('add-'.str_slug('manage-inspector'))
                <button class="btn btn-primary show_tag_a" type="submit">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
            @endcan
            <a class="btn btn-default show_tag_a" href="{{url('/section5/inspectors')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        </div>
    </div>
</center>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script>
        jQuery(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });
            
            $('#start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#end_date').val(expire_date);
                }else{
                    $('#end_date').val('');
                }
            });

            //เมื่อ Submit form
            $('#create_froms').submit(function (e) { 
        
                var values = $('.repeater-scope').find('.branch_group_id').map(function(){return $(this).val(); }).get();
                if(values.length ==0 ){//ถ้ามีเอกสารอบรม แต่ไม่บันทึกประวัติการอบรม
                    alert('กรุณาบันทึก ข้อมูลขอรับบริการ');
                    event.preventDefault(); //this will prevent the default submit
                }
                
            });
        });

        
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        
        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 5); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            console.log(str_pad(date_start.getMonth() + 1));
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = ( '0' + date_start.getDate() ).slice( -2 );

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2){
                return str;
            }else{
                return String(str).padStart(2, '0');
            }
        }
          
    </script>
@endpush