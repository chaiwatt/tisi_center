@if (filter_var($url, FILTER_VALIDATE_URL))
    <iframe src="{{ $url }}" class="col-md-12 col-sm-12" height="520" frameborder="0" allowFullScreen="true"></iframe>
@else
    รูปแบบข้อมูล URL <code>{{ $url }}</code>ไม่ถูกต้อง
@endif
