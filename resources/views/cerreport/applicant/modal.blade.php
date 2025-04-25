 
  <!-- Modal -->
  <div class="modal fade" id="modal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
          <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h3 class="modal-title" > 
                      {{ $item->subject ?? null}}
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                 </h3>
              </div>
              <div class="modal-body " id="modals{{$item->id}}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('detail') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('detail', 'รายละเอียด :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> {!!  !empty($item->detail) ? $item->detail : null  !!} </p> 
                            </div>
                        </div>
                    </div>
           
                @if(!empty($item->email))
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('email', 'อีเมลผู่ส่ง :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> {!!  $item->email  !!} </p> 
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($item->email_to))
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('email_to') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('email_to', 'อีเมลผู่รับ :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> {!!  $item->email_to  !!} </p> 
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($item->email_cc))
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('email_cc') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('email_cc', 'อีเมลผู่รับสำเนา :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> {!!  $item->email_cc  !!} </p> 
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($item->email_reply))
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('email_reply') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('email_reply', 'อีเมลตอบกลับ :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> {!!  $item->email_reply  !!} </p> 
                            </div>
                        </div>
                    </div>
               @endif

               @if(!empty($item->attach))
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('attach') ? 'has-error' : ''}}">
                            {!! HTML::decode(Form::label('attach', 'ไฟล์ :', ['class' => 'col-md-2 control-label text-right'])) !!}
                            <div class="col-md-10">
                                <p> 
                                    <a href="{{url($item->attach)}}" target="_blank">
                                        {!! HP::FileExtension( basename($item->attach))  !!}
                                    </a>
                                </p> 
                            </div>
                        </div>
                    </div>
               @endif
               </div>
            </div>
        </div>
    </div>
</div>


@push('js')
<script type="text/javascript">
 
    $(document).ready(function () {
             var id =  '{{$item->id}}';
            $('#modals'+id).parent().find('img').attr("height", '100px');
            $('#modals'+id).parent().find('img').attr("width", '100px');

            $('#modals'+id).parent().find('#style').css({"width": "100%" });

      
    });
</script>
@endpush
 
