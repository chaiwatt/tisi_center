
<?php $key92=0?>
<div id="viewForm92" class="{{$certi_lab->lab_type == 4 ? 'show':'hide'}}">
    <div class="white-box"style="border: 2px solid #e5ebec;">
        <div class="box-title">
            <legend><h3>7.เครื่องมือ (Equipment)</h3></legend>

        </div>

        <div class="row">
            <div class="col-md-11 col-md-offset-1">
    
                  <div class="col-md-12">
                            @if (!is_null($certi_lab_attach_all71) && $certi_lab_attach_all71->count() > 0)
                            <div class="row">
                                @foreach($certi_lab_attach_all71 as $data)
                                    @if ($data->file)
                                        <div class="col-md-12 form-group">
                                            <a href="{{url('certify/check/file_client/'.$data->file.'/'.( !is_null($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
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
        
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
    
                  <div class="col-md-12">
                            @if (!is_null($certi_lab_attach_all72) && $certi_lab_attach_all72->count() > 0)
                            <div class="row">
                                @foreach($certi_lab_attach_all72 as $data)
                                @if ($data->file)
                                    <div class="col-md-12 form-group">
                                        <a href="{{url('certify/check/file_client/'.$data->file.'/'.( !is_null($data->file_client_name) ? $data->file_client_name :  basename($data->file)  ))}}" target="_blank">
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
