@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<style>
    #style {
        width: 100%;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานประวัติการส่งอีเมล</h3>
                    @can('view-'.str_slug('cerreport-epayments'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                 
                        <div class="col-sm-12">
                              <p class="col-md-3 text-right">ชื่อผู้ประกอบการ : </p>
                              <p class="col-md-9"> {!! !empty($log->sso_user_to->name)?  $log->sso_user_to->name:''  !!} </p>
                            </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">เลขประจำตัวผู้เสียภาษี : </p>
                          <p class="col-md-9"> {!! !empty($log->sso_user_to->tax_number)?  $log->sso_user_to->tax_number:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">ที่ตั้งสำนักงานใหญ่ : </p>
                          <p class="col-md-9"> {!! !empty($log->sso_user_to->FormatAddress)?  $log->sso_user_to->FormatAddress:''  !!} </p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                              <table class="table  table-bordered color-bordered-table info-bordered-table"  width="100%">   
                                  <thead>
                                          <tr>
                                              <th class="text-center " width="1%">ลำดับ</th>
                                              <th class="text-center " width="14%">เลขอ้างอิง</th>
                                              <th class="text-center " width="30%">ชื่อเรื่อง</th>
                                              <th class="text-center " width="10%">สถานะ</th>
                                              <th class="text-center " width="20%">ผ้ส่งอีเมล</th>
                                              <th class="text-center " width="15%">วันที่ส่ง</th>
                                              <th class="text-center " width="10%">รายละเอียด</th>
                                          </tr>
                                  </thead> 
                                  <tbody>
                                      @if (count($log_mails) > 0)
                                        @foreach ($log_mails as  $key => $item) 
                                            <tr> 
                                                <td class="text-center">{!! ($key+1) !!}</td>
                                                <td> {{ $item->app_no ?? '-'}} </td>
                                                <td> {{ $item->subject ?? '-'}} </td>
                                                <td>
                                                        @if ($item->status == 1)
                                                          <span class="text-success">ส่งสำเร็จ</span>
                                                        @else 
                                                              <span class="text-danger">ส่งไม่สำเร็จ</span>
                                                        @endif
                                                </td>
                                                <td>
                                                    @if (!empty($item->user_created->FullName))
                                                        {{ $item->user_created->FullName }}
                                                    @else 
                                                         {{  !empty($item->sso_user_to->name) ?    $item->sso_user_to->name  : null }}
                                                         @if (!empty($item->sso_user_agent_to->name))
                                                             <p class="text-danger">({{ $item->sso_user_agent_to->name }})</p>
                                                         @endif
                                                    @endif
                                                </td>
                                                <td> {{  (!empty($item->created_at) ?  HP::DateThai($item->created_at) : '')   }} </td>
                                                <td  >  
                                                    <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal{{$item->id}}">
                                                        <i class="fa fa-eye"></i> 
                                                    </button>
                                                    @include ('cerreport.applicant.modal',['item' => $item])
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

@endsection
@push('js')
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script> 

        jQuery(document).ready(function() {
            $('#myTable').DataTable( {
                dom: 'Brtip',
                pageLength:5,
                processing: true,
                lengthChange: false,
                ordering: false,
                order: [[ 0, "desc" ]]
            });

        });
    </script>
     
@endpush
