<div class="white-box"style="border: 2px solid #e5ebec;">
    <div class="box-title">
        <legend><h3>4.	การปฏิบัติของห้องปฏิบัติการที่สอดคล้องตามข้อกำหนดมาตรฐานเลขที่ มอก. 17025 – 2561 (ISO/IEC 17025 : 2017)  (Laboratory’s implementations which are conformed with TIS 17025 - 2561 (2018) (ISO/IEC 17025 : 2017))</h3></legend>
  
    </div>
      <div class="row">
        <div class="col-md-11 col-md-offset-1">

              <div class="col-md-12">
                <div class="row">
                     <div class="col-md-2 text-right"> </div>
                     <div class="col-md-10 text-left">
                        @if (!is_null($certi_lab_chack_box_image) && $certi_lab_chack_box_image->count() > 0)
                            <div class="row">
                                @foreach($certi_lab_chack_box_image as $data)
                                @if ($data->path_image)
                                    <div class="col-md-12 form-group">
                                        <a href="{{url('certify/check/file_client/'.$data->path_image.'/'.( !is_null($data->file_client_name) ? $data->file_client_name : basename($data->path_image) ))}}" target="_blank">
                                            {!! HP::FileExtension($data->path_image)  ?? '' !!}
                                            {{  !empty($data->file_client_name) ? $data->file_client_name :  basename($data->path_image)   }}  
                                        </a>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif (!is_null($certi_lab_attach_all4) && $certi_lab_attach_all4->count() > 0)
                            <div class="row">
                                @foreach($certi_lab_attach_all4 as $data)
                                @if ($data->file)
                                    <div class="col-md-12 form-group">
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
</div>       
