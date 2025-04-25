@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet">
    <style>
        .font_size{
            font-size: 14px;
        }

        .label-height{
            line-height: 16px;
        }
    </style>
@endpush

<input type="hidden" id="id" value="{!! $certificate->id !!}">
<input type="hidden" name="certificate_type" id="certificate_type" value="{!! $certificate->certificate_type !!}">
<input type="hidden" name="certificate_id" id="certificate_id" value="{!! $certificate->certificate_id !!}">

<!-- ประเภทใบรับรอง 1-CB , 2-IB , 3-LAB -->
@if( in_array($certificate->certificate_type, [1]) )
    @include('cerreport.logesignaures.form.cb')
@elseif( in_array($certificate->certificate_type, [2]) )
    @include('cerreport.logesignaures.form.ib')
@elseif( in_array($certificate->certificate_type, [3]) )
    @include('cerreport.logesignaures.form.lab')
@endif

<center>
    <div class="form-group">
        @can('view-'.str_slug('cerreport-logesignaures'))
            <button class="btn btn-outline btn-success show_tag_a" type="button" id="btn_example">
                <i class="fa fa-file-text-o"></i> แสดงตัวย่าง
            </button>
        @endcan
        @can('edit-'.str_slug('cerreport-logesignaures'))
            <button class="btn btn-outline btn-primary show_tag_a" type="button" id="btn_save">
                <i class="fa fa-save"></i> บันทึกและลงนาม
            </button>
        @endcan
        <a class="btn btn-default show_tag_a" href="{{url('/cerreport/logesignaures')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    </div>
</center>

@push('js')
    <script src="{{asset('plugins/components/icheck/icheck.min.js')}}"></script>
    <script src="{{asset('plugins/components/icheck/icheck.init.js')}}"></script>
    <script src="{{asset('js/mask/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('js/mask/mask.init.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>
        $(document).ready(function () {

            @if ( \Session::has('success_message'))
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    showConfirmButton: false,
                    timer: 2000
                })
            @endif
            //ปฎิทิน
            $('.mydatepicker').datepicker({
                language:'th-th',
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy'
            });

            //บันทึกและลงนาม
            $('body').on('click', '#btn_save', function(){
          
                Swal.fire({
                    title: 'ยืนยันการส่งลงนามใหม่',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#e74a25',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $('#MyForm').submit();

                    }
                });


            });

            //แสดงตัวย่าง
            $('body').on('click', '#btn_example', function(){
                let formData = $('#MyForm input').not("input[name='_method'],input[name='_token'] ").serialize();
                var url      = "{!! url('/') !!}"+'//cerreport/logesignaures/preview?'+formData;
                window.open(url,'_blank');
            });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>
@endpush