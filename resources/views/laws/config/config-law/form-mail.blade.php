@push('css')
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 70px;
            border-radius: 0;
            width: 100% !important;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
        .note-editor.note-frame {
            border-radius: 4px !important;
        }

    </style>
@endpush


<div class="pull-right">
    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal_add_form" ><span class="btn-label"><i class="fa fa-plus"></i></span>เพิ่ม</button>
</div>

{!! Form::open(['url' => '/law/config/config-law/sendemail/update', 'class' => 'form-horizontal' , 'method' => 'POST', 'files' => true]) !!}
<!-- Tab panes -->
<div class="tab-content">
@isset($law_config_email_notis)
    @foreach ( $law_config_email_notis as $item )
        <div class="form-row">
            <div class="col-sm-12">
                <input type="hidden"   name="law_config_email_notis_id[]"  value="{{$item->id}}">
                <div class="form-group {{ $errors->has('email_list') ? 'has-error' : ''}}">
                    {!! Form::label('email_list', $item->title, ['class' => 'col-md-12']) !!}
                    <div class="col-md-12">
                        {!! Form::text('email_list['.$item->id.'][]', !empty( $item->email_list )?implode(',',json_decode($item->email_list,true)):null,['class' => 'form-control tag', 'id'=>'mail_list', 'data-role' => "tagsinput"]) !!}
                        {!! $errors->first('email_list', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
    
    @endforeach
@endisset

</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('config-law'))
            <a class="btn btn-default" href="{{ url()->previous() }}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

{!! Form::close() !!}

<div class="modal fade text-left" id="modal_add_form" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">เพิ่ม ตั้งค่าการส่งอีเมล</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group row{{ $errors->has('name') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('email_title', 'ชื่อตั้งค่าการส่งอีเมล', ['class' => 'col-md-12 control-label text-left font-medium-3a'])) !!}
                    <div class="col-md-12">
                        {!! Form::text('email_title', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                        {!! $errors->first('email_title', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">ยกเลิก</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="btn_add_form" >
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">บันทึก</span>
                </button>
            </div>
        </div>
    </div>
</div>


@push('js')
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
@endpush