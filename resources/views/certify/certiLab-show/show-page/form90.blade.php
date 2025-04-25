
<div id="viewForm90" class="{{$certi_lab->lab_type == 3 ? 'show':'hide'}}">
    <div class="white-box"style="border: 2px solid #e5ebec;">
        <div class="box-title">
            <legend><h3> 6. ขอบข่ายที่ยื่นขอรับการรับรอง (<span class="text-warning">ห้องปฏิบัติการทดสอบ</span>) (Scope of Accreditation Sought (<span class="text-warning">For testing laboratory</span>)) </h3></legend>
           {{-- @if ($labCalRequest->count() != 0)
                <a type="button" href="{{route('certify.generate_pdf_lab_cal_scope',['id' => $certi_lab->id])}}" class="btn btn-info" style="float:right"><b>ส่งออก PDF</b> </a>
            @elseif($labTestRequest->count() != 0)
               <a type="button" href="{{route('certify.generate_pdf_lab_test_scope',['id' => $certi_lab->id])}}" class="btn btn-info" style="float:right"><b>ส่งออก PDF</b> </a>
           @endif --}}
            
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($certi_lab_attach_all61->count() > 0)
                    <div class="col-md-11 col-md-offset-1">
                        <div class="row">
                            <div class="col-md-12 text-left">
                                <label for="#" class="label_other_attach ctext-light">แนบไฟล์ขอบข่ายที่ต้องการยื่นขอการรับรอง</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-11 col-md-offset-1">
                        <div class="row">
                            <div class="col-md-12 text-left">
                                {{-- @foreach($certi_lab_attach_all61 as $data)
                                    @if ($data->file)
                                        <div class="col-md-12 form-group">
                                            <a href="{{url('certify/check/file_client/'.$data->file.'/'.( !is_null($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
                                                {!! HP::FileExtension($data->file)  ?? '' !!}
                                                {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach --}}

                                @php
                                    $latestFile = $certi_lab_attach_all61->sortByDesc('created_at')->first(); // สมมติว่าใช้ 'created_at' แทนวันที่
                                @endphp

                                @if ($latestFile && $latestFile->file)
                                    <div class="col-md-12 form-group">
                                        <a href="{{ url('certify/check/file_client/'.$latestFile->file.'/'.( !is_null($latestFile->file_client_name) ? $latestFile->file_client_name : basename($latestFile->file) )) }}" target="_blank">
                                            {!! HP::FileExtension($latestFile->file) ?? '' !!}
                                            {{ !empty($latestFile->file_client_name) ? $latestFile->file_client_name : basename($latestFile->file) }}
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif
             </div>
            <div class="col-md-12" id="scope_table_wrapper">

            </div>          
        </div>
    </div>       
</div>

