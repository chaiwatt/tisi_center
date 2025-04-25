@extends('layouts.master')

@push('css')
    <style type="text/css">
        table#show-detail > thead > tr > th {
            color: white;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบรับฟังความคิดเห็นต่อร่างกฎกระทรวง #{{ $listen_std_draft->id }}</h3>
                    @can('view-'.str_slug('listen_std_draft'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th width="15%">ชื่อ มอก. :</th>
                                <td>{{ $listen_std_draft->standard_name }}</td>
                            </tr>
                            <tr>
                                <th>เลข มอก. :</th>
                                <td>{{ $listen_std_draft->standard_no }}</td>
                            </tr>
                            <tr>
                                <th>กลุ่มผลิตภัณฑ์/สาขา :</th>
                                <td>{{ $listen_std_draft->product_group }}</td>
                            </tr>
                            <tr>
                                <th>ความคิดเห็น :</th>
                                <td>{{ $listen_std_draft->commentName }}</td>
                            </tr>
                            @if ($listen_std_draft->comment != 'confirm_standard')
                            <tr>
                                <td colspan="2">
                                    <table class="table table-bordered" id="show-detail">
                                        <thead>
                                        <tr style="background-color: #5B9BD5">
                                            <th class="text-center">No.</th>
                                            <th class="text-center">ข้อคิดเห็น</th>
                                            <th class="text-center">เอกสารที่เกี่ยวข้อง</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($listen_std_draft_detail as $key=>$item)
                                            <tr>
                                                <td class="text-center">{{ $key+1 }}</td>
                                                <td>{{ $item->comment_detail }}</td>
                                                <td>
                                                   
                                                    @if (!is_null($item->attach))
                                                    @php $attach_details = json_decode($item->attach)  @endphp
                                                       @if($attach_details->file_name!='' && HP::checkFileStorage($attach_path.$attach_details->file_name))
                                                         <a href="{{ HP::getFileStorage($attach_path.$attach_details->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i> {{ $attach_details->file_client_name }}</a>
                                                       @endif
                                                    @endif
                                            
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>เอกสารแนบเพิ่มเติม :</th>
                                <td>
                                    @foreach ($attachs as $key => $attach)

                                        @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                            <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i> {{ $attach->file_client_name }}</a>
                                        @endif

                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>ชื่อ - สกุล ผู้ให้ข้อมูล :</th>
                                <td> {{ $listen_std_draft->name }} </td>
                            </tr>
                            <tr>
                                <th>เบอร์โทร :</th>
                                <td> {{ $listen_std_draft->tel }} </td>
                            </tr>
                            <tr>
                                <th>E-mail :</th>
                                <td> {{ $listen_std_draft->email }} </td>
                            </tr>
                            <tr>
                                <th>หน่วยงาน :</th>
                                <td> {{ $listen_std_draft->departmentNameName }} </td>
                            </tr>
                            <tr>
                                <th>ผู้สร้าง :</th>
                                <td> {{ $listen_std_draft->createdName }} </td>
                            </tr>
                            <tr>
                                <th>วันเวลาที่สร้าง :</th>
                                <td> {{ HP::DateTimeThai($listen_std_draft->created_at) }} </td>
                            </tr>
                            {{-- <tr>
                                <th>ผู้แก้ไข :</th>
                                <td> {{ $listen_std_draft->UpdatedName }} </td>
                            </tr>
                            <tr>
                                <th>วันเวลาที่แก้ไข :</th>
                                <td> {{ HP::DateTimeThai($listen_std_draft->updated_at) }} </td>
                            </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
