@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">รายงานการเบิกจ่ายเงินสินบนรางวัล</h3>
                    @can('view-'.str_slug('law-report-rewards'))
                        <a class="btn btn-default pull-right" href="{{url('/law/report/rewards')}}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

               
                          <div class="form-group  {{ $errors->has('reference_no') ? 'has-error' : ''}}">
                                        {!! Form::label('reference_no', 'เลขอ้างอิงการเบิกจ่าย', ['class' => 'col-md-2 control-label text-right']) !!}
                                <div class="col-md-4">
                                       {!! Form::text('reference_no', !empty($withdraws->law_reward_withdraws_to->reference_no) ?  $withdraws->law_reward_withdraws_to->reference_no : null , ['class' => 'form-control text-center',  'disabled' => true]) !!}
                                       {!! $errors->first('reference_no', '<p class="help-block">:message</p>') !!}
                               </div>
                               {!! Form::label('case_number', 'เลขคดี', ['class' => 'col-md-2 control-label text-right']) !!}
                               <div class="col-md-4">
                                      {!! Form::text('case_number', !empty($withdraws->case_number) ?  $withdraws->case_number : null , ['class' => 'form-control text-center',  'disabled' => true]) !!}
                                      {!! $errors->first('case_number', '<p class="help-block">:message</p>') !!}
                              </div>
                          </div>
                          <div class="clearfix  "></div>
                         <br>
                     <table class="table table-striped table-bordered"  >
                          <thead>
                              <tr>
                                  <th width="1%" class="text-center">ลำดับ</th>
                                  <th width="25%" class="text-center">ชื่อสิทธิ์</th>
                                  <th width="25%" class="text-center">กลุ่มผู้มีสิทธิ์</th>
                                  <th width="14%" class="text-center">จำนวนเงิน</th>
                                  <th width="35%" class="text-center">หมายเหตุ</th> 
                              </tr>
                          </thead>
                          <tbody>
                                       @php
                                               $amount = 0;     
                                       @endphp
                                  @if (count($detail_subs))
                                       @foreach ($detail_subs as $key => $item)
                                              @php
                                                    if(!empty($item->amount)){
                                                         $amount += $item->amount;     
                                                    }
                                              @endphp
                                                    <tr>
                                                    <td class="text-center text-top">
                                                                 {!! ($key+1) !!}
                                                    </td>
                                                    <td class=" text-top">
                                                                 {!! !empty($item->name) ? $item->name : null !!}
                                                    </td>
                                                    <td class=" text-top">
                                                                 {!! !empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : null !!}
                                                    </td>
                                                    <td class="text-right text-top">
                                                                 {!! !empty($item->amount) ?  number_format($item->amount,2) : null !!}
                                                    </td>
                                                    <td class=" text-top">
                                                                 {!! !empty($item->remark) ? $item->remark : null !!}
                                                    </td>
                                                    </tr>
                                       @endforeach
                                  @endif                  
                          </tbody>
                            <tfoot>
                              <tr>
                                    <td class="text-right text-top" colspan="3"> 
                                              <b>รวม</b>      
                                    </td>
                                    <td class="text-right text-top">
                                        {!!  number_format($amount,2) !!}
                                    </td>
                                    <td>

                                    </td>
                              </tr>
                           </tfoot>
                      </table>
                      <div class="clearfix"></div>
                      <a  href="{{ url('/law/report/rewards') }}"  class="btn btn-default btn-lg btn-block">
                          <i class="fa fa-rotate-left"></i>
                          <b>กลับ</b>
                      </a>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable
            $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();
        });

    </script>
@endpush
