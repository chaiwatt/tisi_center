@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขหมวดหมู่</h3>
                    @can('view-blog-category')
                        <a class="btn btn-success pull-right" href="{{url('blog-category')}}">
                          <i class="icon-action-undo"></i>&nbsp; กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            @can('edit-blog-category')
                                <form class="form-horizontal" method="post"
                                      action="{{url('blog-category/edit/'.$category->id)}}">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label">ชื่อหมวดหมู่: </label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                   name="title"
                                                   value="{{ $category->title }}"
                                                   autofocus
                                                   required>
                                            @if ($errors->has('title'))
                                                <span class="invalid-feedback">
                                                  <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group m-b-0">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">
                                                <i class="icon-paper-plane"></i> บันทึก
                                            </button>

                                            <a href="{{ url('blog-category') }}" class="btn btn-default waves-effect waves-light m-t-10">
                                              <i class="fa fa-rotate-left"></i> ยกเลิก
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            @endcan
                        </div>
                    </div>

                </div>
            </div>
        </div>


        @include('layouts.partials.right-sidebar')
    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    {{--<script src="{{asset('js/toastr.js')}}"></script>--}}
    <script>

        @if(\Session::has('message'))
        $.toast({
            heading: 'Success!',
            position: 'top-center',
            text: '{{session()->get('message')}}',
            loaderBg: '#70b7d6',
            icon: 'success',
            hideAfter: 3000,
            stack: 6
        });
        @endif
    </script>
@endpush
