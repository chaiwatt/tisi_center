
<style>
    .table tr:hover {
    background-color: inherit !important;
    transition: none !important;
}

.table td {
    border: none !important;
}
</style>
<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4> 4. ขอบข่ายที่ยื่นขอรับการรับรอง (Scope of Accreditation Sought)</h4></legend>
 {{-- {{$certi_ib->FileAttach3->count()}} --}}

                <div class="clearfix"></div>
                @if (isset($certi_ib) && $certi_ib->FileAttach3->count() > 0)
                <div class="row">
                    @foreach($certi_ib->FileAttach3 as $data)
                      @if ($data->file)
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4 text-light"> </div>
                                <div class="col-md-6 text-light">
                                    <a href="{{url('certify/check/file_ib_client/'.$data->file.'/'.( !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                        {!! HP::FileExtension($data->file)  ?? '' !!}
                                        {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                    </a>
                                </div>
                             
                            </div>
                        </div>
                        @endif
                     @endforeach
                     <div class="col-md-12">
                        <table class="table" style="border: none; background-color: inherit;margin-top:15px">
                            <tr>
                                <th>หมวดหมู่ / สาขาการตรวจ </th>
                                <th>ขั้นตอนและช่วงการตรวจ </th>
                                <th>ข้อกำหนดที่ใช้ </th>
                            </tr>
                            <tbody id="ib_scope_wrapper"></tbody>
                            
                        </table>
                    </div>
                  </div>
                @endif

      </div>  

    </div>
</div>