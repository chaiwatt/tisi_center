@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">คณะผู้ตรวจประเมิน #{{ $ba->id }}</h3>
                    <div class="pull-right">

                        @can('edit-'.str_slug('board-auditor'))
                            <a class="btn btn-primary btn-sm waves-effect waves-light" title="Edit board auditor"
                               href="{{ url('/certify/auditor/'.$ba->id.'/edit') }}">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> แก้ไข
                            </a>
                        @endcan

                        @can('view-'.str_slug('board-auditor'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/certify/auditor') }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                            </a>
                        @endcan

                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th>ID</th>
                                  <td>{{ $ba->id }}</td>
                              </tr>
                              <tr><th> เลขที่คำขอ </th><td> {{ $ba->certi_no ?? '' }} </td></tr>
                              <tr><th> ชื่อคณะผู้ตรวจประเมิน </th><td> {{ $ba->no ?? '' }} </td></tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ @$ba->user_created->FullName ?? '' }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($ba->created_at)  ?? '' }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ @$ba->user_updated->FullName ?? ''  }}  </td>
                              </tr> 
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($ba->updated_at) ?? ''  }} </td>
                              </tr>
                              <tr>
                                  <th> วันที่ตรวจประเมิน </th>
                                  <td> 
                                    @if(count($ba->DataBoardAuditorDate) > 0)
                                    {!!  $ba->DataBoardAuditorDateTitle ?? ''  !!}
                                    @else 
                                        {{  HP::DateThai($ba->check_date) ?? '' }}  ถึง     {{  HP::DateThai($ba->check_end_date) ?? '' }} 
                                     @endif
                                 </td>
                              </tr>
                              <tr>
                                  <th> หนังสือแต่งตั้ง </th>
                                  <td>
                                    <a href="{{ url('certify/check/files') . '/' . $ba->file }}" target="_blank">
                                        <i class="fa fa-file-pdf-o" style="font-size:38px; color:red"
                                           aria-hidden="true"></i>
                                    </a>
                                  </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                        <table class="table color-bordered-table primary-bordered-table">
                            <thead>
                            <tr>
                                <th class="text-center">ลำดับ</th>
                                <th class="text-center">สถานะผู้ตรวจประเมิน</th>
                                <th class="text-center">ชื่อคณะผู้ตรวจประเมิน</th>
                                <th class="text-center">หน่วยงาน</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @forelse($ba->groups as $group)
                                <tr>
                                    <td class="text-center text-top">{{ $loop->iteration }}</td>
                                    <td class="text-center text-top">{{ $group->sa->title }}</td>
                                    <td class="text-top">
                                    @foreach ($group->auditors as $ai)
                                        @if (!$loop->first)
                                            <hr>
                                        @endif
                                        <p>{{ $loop->iteration.'. '.$ai->auditor->name_th }}</p>
                                    @endforeach
                                    </td>
                                    <td class="text-top">
                                        @foreach ($group->auditors as $ai)
                                            @if (!$loop->first)
                                                <hr>
                                            @endif
                                            <p>{{ $loop->iteration.'. '.$ai->auditor->department->title }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>

@endsection
