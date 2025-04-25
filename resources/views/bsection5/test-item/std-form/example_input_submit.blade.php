@extends('layouts.app')

@push('css')

@endpush

@section('content')

    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <img src="{!! asset('icon/check-mark.png') !!}" width="20%" class="img-rounded" />
                <h3 class="text-uppercase text-dark">ทดสอบบันทึกข้อมูลสำเร็จ</h3>
                <a href="{{ url()->previous() }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">กลับหน้าที่แล้ว</a>
            </div>
            <footer class="footer text-center">© 2565 สมอ.</footer>
        </div>
    </section>

@endsection

@push('js')

@endpush
