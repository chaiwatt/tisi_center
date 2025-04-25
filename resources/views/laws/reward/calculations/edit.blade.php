@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">คำนวณสินบน</h3>
                    @can('view-'.str_slug('law-reward-calculations'))
                         <a class="btn btn-default pull-right" href="{{url('/law/reward/calculations')}}">
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

                      @include ('laws.reward.calculations.modals.paid')
         

                    {!! Form::model($cases, [
                        'method' => 'PATCH',
                        'url' => ['/law/reward/calculations', $cases->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>'myForm'
                    ]) !!}
                    
                        @include ('laws.reward.calculations.form')

                        <div class="clearfix"></div>

                        <div class="form-group" id="div_save_paid">
                            <div class="col-md-offset-4 col-md-4">
                                @can('view-'.str_slug('law-reward-calculations'))
                                    <a class="btn btn-default show_tag_a" href="{{url('/law/reward/calculations')}}">
                                        {{-- <i class="fa fa-rotate-right"></i>  --}}
                                        ยกเลิก
                                   </a>
                                 @endcan
                                <button class="btn btn-primary" id="save_paid" type="button">
                                    บันทึก ทำรายการถัดไป >>
                                </button>
                    
                            </div>
                        </div>

                        <div class="form-group" id="div_save_calculate">
                            <div class="col-md-offset-4 col-md-4">
                                <button class="btn btn-default" id="save_draft_calculate" type="button">
                                     ฉบับร่าง
                                </button>
                                <button class="btn btn-primary save_calculate" id="save_calculate" type="button">
                                      ยืนยันคำนวณ
                                </button>
                    
                            </div>
                        </div>

                        <div class="form-group" id="div_save_print">
                            <div class="col-md-offset-4 col-md-4">
                                @can('view-'.str_slug('law-reward-calculations'))
                                  <a class="btn btn-default show_tag_a" href="{{url('/law/reward/calculations')}}">
                                      {{-- <i class="fa fa-rotate-right"></i>  --}}
                                    ยกเลิก
                                   </a>
                                 @endcan
                                <button class="btn btn-primary" id="save_print" type="button">
                                    บันทึก
                                </button>
                            </div>
                        </div>

                          @if ((!empty($cases->law_reward_to->status) && in_array($cases->law_reward_to->status,['2','3','4','5'])))
                              <div class="clearfix"></div>
                            <a  href="{{ url('/law/reward/calculations') }}"  class="btn btn-default btn-lg btn-block">
                                <i class="fa fa-rotate-left"></i>
                                <b>กลับ</b>
                            </a>
                          @endif
          


                    {!! Form::close() !!}


                    @if (!empty($cases->config_section))
                    <div class="modal fade" id="RecordModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
                        <div  class="modal-dialog   modal-xl" >  
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="AssignModalLabel1">เลทเพดานเงิน</h4>
                                </div>
                                <div class="modal-body">
                                   
                                    <div class="white-box">
                                        <div class="row form-group">
                                          <div class="col-md-12 ">
                                              <div class="table">
                                                  <table class="table table-striped table-bordered "  >
                                                          <thead>
                                                          <tr>
                                                              <th class="text-center" width="2%">#</th>
                                                              <th class="text-center" width="20%">มาตรา</th>
                                                              <th class="text-center" width="20%">ความผิดตามมาตรา</th>
                                                              <th class="text-center" width="20%">เพดานเงิน</th>
                                                          </tr>
                                                          </thead>
                                                          <tbody>
                                                            @if (count($cases->config_section) > 0)
                                                                    @foreach ($cases->config_section as $key => $item)
                                                                        <tr>
                                                                             <td  class="text-top text-center font-medium-6">
                                                                                {!! ($key+1) !!}
                                                                            </td>
                                                                            <td  class="text-top font-medium-6">
                                                                                {!!   !empty($item->basic_section->number) ?  $item->basic_section->number : '' !!}
                                                                            </td>
                                                                            <td  class="text-top font-medium-6">
                                                                                {!!  !empty($item->SectionRelationNumber) ?  $item->SectionRelationNumber : '' !!}
                                                                            </td>
                                                                            <td  class="text-top font-medium-6">
                                                                                {!!  !empty($item->LawConfigRewardMaxTitle) ?  $item->LawConfigRewardMaxTitle : '' !!}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                            @endif

                                                          </tbody>
                                                      </table>
                                                  </div>
                                          </div>
                                       </div>
                                   </div>
            
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                  </div>
                            </div>
                        </div>    
                    </div>
                    @endif

                
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    
    <script>
        $(document).ready(function () {
            save_paid();
            @if ((!empty($cases->law_reward_to->status) && in_array($cases->law_reward_to->status,['2','3','4','5'])))
                    //Disable
                    $('#myForm').find('input, select, textarea').prop('disabled', true);
                    $('#myForm').find('button').remove();
                    $('#myForm').find('.show_tag_a').hide();
                    $('#myForm').find('.box_remove').remove();
            @endif
          
        });

        function save_paid() {
            var rows = $('#table_tbody_paid').children(); //แถวทั้งหมด
                if(rows.length > 0){
                    $('#save_paid').prop('disabled', false);
                }else{
                    $('#save_paid').prop('disabled', true);
                }
         
        }
   
  
  
    </script>
@endpush
