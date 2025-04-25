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
                    <h3 class="box-title pull-left">ระบบข้อมูลความคิดเห็นในการทบทวนมาตรฐาน #{{ $comment_standard_review->id }}</h3>
                    @can('view-'.str_slug('comment_standard_reviews'))
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
                                <td>{{  !empty($comment_standard_review->public_draft->StandardName) ? $comment_standard_review->public_draft->StandardName  : "n/a"}}</td>
                            </tr>
                            <tr>
                                <th>เลข มอก. :</th>
                                <td>{{ !empty($comment_standard_review->public_draft->tis_no) ? $comment_standard_review->public_draft->tis_no :"n/a"}}</td>
                            </tr>
                            <tr>
                                <th>กลุ่มผลิตภัณฑ์/สาขา :</th>
                                <td>{{ !empty($comment_standard_review->public_draft->StandardBranchTitle) ? $comment_standard_review->public_draft->StandardBranchTitle : "n/a"}}</td>
                            </tr>
                            <tr>
                                <th>ความคิดเห็น :</th>
                                <td>{{ $comment_standard_review->commentName  ?? "n/a" }}</td>
                            </tr>
                            @if ($comment_standard_review->comment != 'confirm_standard')
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
                                            @if (count($comment_standard_review_detail) > 0)
                                                @foreach($comment_standard_review_detail as $key=>$item)
                                                    <tr>
                                                        <td class="text-center">{{ $key+1 }}</td>
                                                        <td>{{ $item->comment_detail }}</td>
                                                        <td>
                                                            @php $attach_details = json_decode($item->attach)  @endphp
                                                            @if($attach_details->file_name!='' && HP::checkFileStorage($attach_path.$attach_details->file_name))
                                                                <a href="{{ HP::getFileStorage($attach_path.$attach_details->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i> {{{ $attach_details->file_client_name }}}</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach                                          
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>เอกสารแนบเพิ่มเติม :</th>
                                <td>
                                    @if (count($comment_standard_review_detail) > 0)
                                    @foreach ($comment_standard_review_detail as $key => $attach)

                                        @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                            <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i> {{{ $attach->file_client_name }}}</a>
                                        @endif

                                    @endforeach                                     
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>ชื่อ - สกุล ผู้ให้ข้อมูล :</th>
                                <td> {{ $comment_standard_review->name   ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>เบอร์โทร :</th>
                                <td> {{ $comment_standard_review->tel  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>E-mail :</th>
                                <td> {{ $comment_standard_review->email  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>หน่วยงาน :</th>
                                <td> {{ $comment_standard_review->departmentNameName  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>ผู้สร้าง :</th>
                                <td> {{ $comment_standard_review->createdName  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>วันเวลาที่สร้าง :</th>
                                <td> {{ HP::DateTimeThai($comment_standard_review->created_at)  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>ผู้แก้ไข :</th>
                                <td> {{ $comment_standard_review->UpdatedName  ?? "n/a" }} </td>
                            </tr>
                            <tr>
                                <th>วันเวลาที่แก้ไข :</th>
                                <td> {{ HP::DateTimeThai($comment_standard_review->updated_at)  ?? "n/a" }} </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
