@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">เบิกเงินรางวัล</h3>
                    @can('view-'.str_slug('law-reward-withdraws'))
                         <a class="btn btn-default pull-right" href="{{url('/law/reward/withdraws')}}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($withdraws, [
                        'method' => 'PATCH',
                        'url' => ['/law/reward/withdraws', $withdraws->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>'myForm'
                    ]) !!}

                        @include ('laws.reward.withdraws.form')

                        @if ((!empty($withdraws->status) && in_array($withdraws->status,['2'])))
                        <div class="clearfix"></div>
                        <a  href="{{ url('/law/reward/withdraws') }}"  class="btn btn-default btn-lg btn-block">
                            <i class="fa fa-rotate-left"></i>
                            <b>กลับ</b>
                        </a>
                       @endif
    
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    
    <script>
        $(document).ready(function () {
            @if ((!empty($withdraws->status) && in_array($withdraws->status,['2'])))
                    //Disable
                    $('#myForm').find('input, select, textarea').prop('disabled', true);
                     $('#myForm').find('button').hide();
                    $('#myForm').find('.show_tag_a').hide();
                    $('#myForm').find('.box_remove').remove();
                    $('#myForm').find('.cancel').show();
            @endif
            
        });
    </script>
@endpush
