@extends('layouts.app')
@push('css')
@endpush

@section('content')
    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <i class="fa fa-file-pdf-o" style="font-size:250px;color:red"></i>
                <h3 class="text-uppercase text-dark">ขออภัยไม่พบใบรับรอง</h3>
            </div>
            <footer class="footer text-center">© 2565 สมอ.</footer>
        </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
    </script>
@endpush
