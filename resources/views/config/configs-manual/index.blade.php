@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">ตั้งค่าคู่มือ</h3>

                    <div class="pull-right">



                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ตั้งค่าคู่มือ</em></p>
                   
                    <div class="clearfix"></div>

                    
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Nav tabs -->
                            <ul class="nav customtab nav-tabs" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#center" aria-controls="center" role="tab" data-toggle="tab">
                                    <span class="visible-xs">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs">Tisi Center</span>
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#esur" aria-controls="e-surv" role="tab" data-toggle="tab">
                                    <span class="visible-xs">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs">Tisi e-Surveillance</span>
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#e-acc" aria-controls="e-acc" role="tab" data-toggle="tab">
                                    <span class="visible-xs">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs">Tisi e-Accreditation</span>
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#sso" aria-controls="sso" role="tab" data-toggle="tab">
                                    <span class="visible-xs">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs">Tisi SSO</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane fade active in" id="center">
                        <div class="row">
                            <div class="col-md-12 repeater-form">

                                
                                {!! Form::open(['url' => '/config/manual', 'class' => 'form-horizontal', 'files' => true]) !!}

                                <input name="sytem" type="hidden" value="tisi-center">

                                @include ('config.configs-manual.form-center')

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="esur">
                        <div class="row">
                            <div class="col-md-12 repeater-form">

                                {!! Form::open(['url' => '/config/manual', 'class' => 'form-horizontal', 'files' => true]) !!}

                                <input name="sytem" type="hidden" value="tisi-esurv">

                                @include ('config.configs-manual.form-esurv')

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade " id="e-acc">
                        <div class="row">
                            <div class="col-md-12 repeater-form">

                                {!! Form::open(['url' => '/config/manual', 'class' => 'form-horizontal', 'files' => true]) !!}

                                <input name="sytem" type="hidden" value="tisi-e-acc">

                                @include ('config.configs-manual.form-e-acc')

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="sso">
                        <div class="row">
                            <div class="col-md-12 repeater-form">

                                {!! Form::open(['url' => '/config/manual', 'class' => 'form-horizontal', 'files' => true]) !!}

                                <input name="sytem" type="hidden" value="tisi-sso">

                                @include ('config.configs-manual.form-sso')

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- summernote -->
    <script src="{{asset('plugins/components/summernote/summernote.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {
            @if(\Session::has('flash_message'))
                $.toast({
                    heading: 'Success!',
                    position: 'top-center',
                    text: '{{session()->get('flash_message')}}',
                    loaderBg: '#70b7d6',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 6
                });
            @endif
        });

        $(function () {

            $('.repeater-form').repeater({
                show: function () {
                    $(this).slideDown();

                    if( $(this).find('.show_tag_a').length > 0 ){

                        // var inputFile = '<div class="fileinput fileinput-new input-group" data-provides="fileinput">';
                        //     inputFile += '<div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>';
                        //     inputFile += '<span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">เลือกไฟล์</span><span class="fileinput-exists">เปลี่ยน</span><input type="file" name="upload_file" id="upload_file" required></span>'
                        //     inputFile += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';   
                        //     inputFile += '</div>';  
                                
                        // $(this).find('[for="upload_file"]').next().append(inputFile);

                        $(this).find('.fileinput').show();
                        $(this).find('.upload_file').prop('disabled', false);
                        $(this).find('.upload_file').prop('required', true);
                        
                        $(this).find('.show_tag_a').remove();

                    }

                    
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                       
                        setTimeout(function(){
                       
                        }, 400);
                    }
                }
            });

        });

        // function checkNone(value) {
        //     return value !== '' && value !== null && value !== undefined;
        // }

    </script> 
@endpush