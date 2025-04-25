<?php $key=0?>
<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>5. รายชื่อคุณวุฒิประสบการณ์และขอบข่ายความรับผิดชอบของเจ้าหน้าที่ (List of relevant personnel providing name, qualification, experience and responsibility)</h3></legend>
    </div>
      <div class="row">
        <div class="col-md-11 col-md-offset-1">
                <div class="row">
                     <div class="col-md-12 text-left">
                        @if (!is_null($certi_lab_attach_all5) && $certi_lab_attach_all5->count() > 0)
                            <div class="row">
                                @foreach($certi_lab_attach_all5 as $data)
                                @if ($data->file)
                                    <div class="col-md-12 form-group">
                                        {{-- {{url('certify/check/file_client/'.$data->file.'/'.( !is_null($data->file_client_name) ? $data->file_client_name : basename($data->file)  ))}} --}}
                                        <a href="{{url('certify/check/file_client/'.$data->file.'/'.( !is_null($data->file_client_name) ? $data->file_client_name : basename($data->file)  ))}}" target="_blank">
                                            {!! HP::FileExtension($data->file)  ?? '' !!}
                                            {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->file)   }}
                                        </a>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
         </div>
    </div>
</div>       
