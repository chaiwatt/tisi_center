@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่าการตรวจติดตามใบรับรอง</h3>
 
                    <div class="clearfix"></div>
                    <hr>

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#Lab" aria-controls="Lap" role="tab"  data-toggle="tab" aria-expanded="true">
                            <span  class="hidden-xs"> ตรวจติดตาม Lab</span></a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#IB" aria-controls="IB" role="tab"  data-toggle="tab" aria-expanded="true">
                            <span  class="hidden-xs"> ตรวจติดตาม IB</span></a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#CB" aria-controls="CB" role="tab"  data-toggle="tab" aria-expanded="true">
                            <span  class="hidden-xs"> ตรวจติดตาม CB</span></a>
                        </li>
                    
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="Lab">
                            @include('bcertify.setting-config.form-lab')
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="IB">
                            @include('bcertify.setting-config.form-ib')
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="CB">
                            @include('bcertify.setting-config.form-cb')
                        </div>
                   
                    </div>
              </div>
          </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script>
    $(document).ready(function() {
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

            $('.numberonly').keypress(function (e) {     
               var charCode = (e.which) ? e.which : event.keyCode    
                if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                    return false;                        

            });   
    });
</script>
@endpush
