
<div class="row form-group">
    <div class="col-md-12">
        <div class="white-box" style="border: 2px solid #e5ebec;">
            <legend><h4> 5. เครื่องมือ (Equipment) </h4></legend>
 

                <div class="clearfix"></div>
                @if (isset($certi_ib) && $certi_ib->FileAttach4->count() > 0)
                <div class="row">
                    @foreach($certi_ib->FileAttach4 as $data)
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
                  </div>
                @endif
        
      </div>  
    </div>
</div>