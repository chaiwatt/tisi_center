@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกผลเสนอคณะอนุกรรมการ (LAB) #{{ $applicationlab->id }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application-lab-board-approve') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ</a>

                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($applicationlab, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application-lab-board-approve/gazette-save', $applicationlab->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}


                    @include ('section5.application-lab-board-approve.form', ['submitButtonText' => 'Update'])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            //ปิดฟอร์ม ผลเสนอคณะอนุกรรมการ
            $('#box-approve').find('button[type="submit"]').closest('.row').remove();//กรอบบันทึก/ยกเลิก
            $('#box-approve').find('button[type="button"]').remove();//ปุ่มเพิ่ม/ลบ ไฟล์
            $('#box-approve').find('input[type="radio"]:not(:checked)').prop('disabled', true);
            $('#box-approve').find('input[type="text"], input[type="file"], textarea').prop('disabled', true);
            $('#box-approve').find('input[type="text"], input[type="file"], textarea').prop('required', false);
            $('#box-approve').find('.show_tag_a').hide();

            //ปิดฟอร์ม ผลเสนอ กมอ.
            $('#box-result').find('button[type="submit"]').closest('.row').remove();//กรอบบันทึก/ยกเลิก
            $('#box-result').find('button[type="button"]').remove();//ปุ่มเพิ่ม/ลบ ไฟล์
            $('#box-result').find('input[type="radio"]:not(:checked)').prop('disabled', true);
            $('#box-result').find('input[type="text"], input[type="file"], textarea').prop('disabled', true);
            $('#box-result').find('input[type="text"], input[type="file"], textarea').prop('required', false);
            $('#box-result').find('.show_tag_a').hide();

            // ตรวจตามตรวจตามภาคผนวก ก.
            @if ($applicationlab->audit_type==2)
                $('#lab_start_date').change(function (e) {
                    var val = $(this).val();
                    if( val != ''){
                        var expire_date = CalExpireDate(val);
                        $('#lab_end_date').val(expire_date);
                    }else{
                        $('#lab_end_date').val('');
                    }
                });
            @endif

        });

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = str_pad(date_start.getDate());

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

    </script>
@endpush
