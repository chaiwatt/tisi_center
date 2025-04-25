@extends('layouts.master')

@push('css')
    {{-- <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .a_custom {
            text-decoration: underline;
        }
    </style> --}}
@endpush

@section('content')
    <div class="container-fluid" id="app_check_deail">
        <div class="text-right m-b-15">
            @can('view-'.str_slug('auditor'))
                <a class="btn btn-danger btn-sm waves-effect waves-light" href="{{ route('check_assessment.index') }}">
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                </a>
            @endcan
        </div>
        <!-- .row -->
{{--        <card-certificate-detail url="{{ route('check_certificate.api.get.app', ['app'=> $ca->applicant]) }}"></card-certificate-detail>--}}

        <h3 class="box-title" style="display: inline-block;">คำขอรับใบรับรองห้องปฏิบัติการ {{ $ca->applicant->app_no ?? '-' }}</h3>
        <div class="m-b-15">
            <a class="btn btn-primary" href="{{route('show.certificate.applicant.detail',['certilab'=>$ca->applicant])}}" target="_blank">รายละเอียดคำขอ</a>
            <a  class="btn btn-info" v-if="isShowApplicant"  href="{{ route('estimated_cost.index') }}" target="_blank">
                การประมาณค่าใช้จ่าย
            </a>
        </div>
        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">สถานะการตรวจประเมิน</h3>
                    <form action="{{ route('check_assessment.update.status', ['ca' => $ca]) }}" class="form-horizontal" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="row text-center">
                            <div class="col-sm-8">
                                <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                    <label for="status" class="col-md-4 control-label label-filter text-right">สถานะ : </label>
                                    <div class="col-md-8">
                                        {!! Form::select('status',
                                            $checking_list,
                                            $ca->applicant->status <= $maxStatus ? $ca->applicant->status : $maxStatus,
                                            ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'required' => true]);
                                        !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">ประมาณค่าใช้จ่าย</h3>
                    <div class="row text-center">
                        <div class="col-sm-8">
                            <a href="{{ route('estimated_cost.index') . '?app=' . $ca->applicant->id }}">
                                <h3 class="text-muted"><คลิกเพื่อเพิ่ม/ดูรายการประมาณค่าใช้จ่าย></h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">คณะผู้ตรวจประเมิน</h3>
                    <div class="row text-center">
                        <div class="col-sm-8">
                            <a href="{{ url('certify/auditor') . '?app=' . $ca->applicant->id }}">
                                <h3 class="text-muted"><คลิกเพื่อเพิ่ม/ดูข้อมูลคณะผู้ตรวจประเมิน></h3>
                            </a>
                        </div>
                    </div>
                    <h3 class="box-title">ขอความเห็นแต่งตั้งคณะผู้ตรวจประเมิน</h3>
                    <div class="row text-center">
                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                <label for="comment_number" class="col-md-4 control-label label-filter text-right">ขอความเห็นครั้งที่ : </label>
                                <div class="col-md-2">
                                    <input type="text" name="comment_number" class="form-control" :value="group_auditors.length" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 m-t-15">
                            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                <label for="comment_number" class="col-md-4 control-label label-filter text-right">วันที่ตรวจประเมิน : </label>
                                <div class="col-md-4">
                                    <input type="text" ref="check_date" name="date" class="form-control mydatepicker" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 m-t-15">
                            <div class="form-group {{ $errors->has('no') ? 'has-error' : ''}}">
                                <label for="auditors" class="col-md-4 control-label label-filter text-right">คณะผู้ตรวจประเมิน : </label>
                                <div class="col-md-7">
                                    <select2-badge :options="form.options" :labels="form.labels"></select2-badge>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 m-t-15">
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4 m-t-15">

                                    <button @click="clearLabels" type="button" class="btn btn-default" href="{{url('/certify/auditor')}}">
                                        <i class="fa fa-times-circle"></i> ล้าง
                                    </button>

                                    <button @click="saveForm" :disabled="form.hasSend" class="btn btn-primary" type="submit" id="form-save">
                                        <i class="fa fa-paper-plane"></i> ส่ง
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 m-t-15">
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                <tr>
                                    <th class="text-center">ครั้งที่</th>
                                    <th class="text-center">วันที่ตรวจประเมิน</th>
                                    <th class="text-center">คณะผู้ตรวจประเมิน</th>
                                    <th class="text-center">สถานะการขอความเห็น</th>
                                    <th class="text-center">วันที่ขอความเห็น</th>
                                    <th class="text-center">รายละเอียด</th>
                                </tr>
                                </thead>
                                <tbody id="table-body">
                                <tr v-for="(group, index) in group_auditors">
                                    <td class="text-top">
                                        @{{ index+1 }}
                                        <input type="hidden" :name="'sequence['+index+']'" :value="index+1">
                                    </td>
                                    <td class="text-top">@{{ group.auditor_date }}</td>
                                    <td>
                                        <input v-for="item in group.auditors" type="text" class="form-control m-b-5" :value="item.text" readonly>
                                    </td>
                                    <td class="text-top">
                                        <label v-if="group.assessment == 1">เห็นชอบ</label>
                                        <label v-else-if="group.assessment == 2">ไม่เห็นชอบ</label>
                                        <label v-else>-</label>
                                    </td>
                                    <td class="text-top">@{{ group.assessment_date }}</td>
                                    <td class="text-top">
                                        <button type="button" class="btn btn-info btn-sm" @click="openModal(group)">รายละเอียด</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-8 m-t-15">
                            <form action="{{ route('check_assessment.agree', ['ca' => $ca]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="text-left">
                                    <label><input name="agree" type="checkbox" {{ $ca->agree_status ? 'checked' : '' }}> แต่งตั้งและขอความเห็นชอบแต่งตั้งคณะผู้ตรวจประเมินเรียบร้อยแล้ว</label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">แจ้งรายละเอียดการชำระเงินค่าตรวจประเมิน</h3>
                    <div class="row text-center">
                        <form action="{{ route('check_assessment.update.cost', ['cost' => $ca->cost_assessment]) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="comment_number" class="col-md-4 control-label label-filter text-right">จำนวนเงิน : </label>
                                    <div class="col-md-4">
                                        <vue-autonumeric :options="costOptions" name="comment_number" class="form-control" value="{{ $ca->cost_assessment->amount }}"></vue-autonumeric>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <input-file
                                        old-url="{{ route('applicants.file', ['path'=>'cost_assessments', 'filename'=> basename($ca->cost_assessment->amount_invoice) ]) }}"
                                        old-file-name="{{ $ca->cost_assessment->amount_invoice }}">
                                </input-file>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label for="comment_number" class="col-md-4 control-label label-filter text-right">เจ้าหน้าที่ : </label>
                                    <div class="col-md-6 text-left">
                                        <input type="text" name="reporter" class="form-control" value="{{ auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <input-date title="วันที่บันทึก" input-name="savedate" value="{{ $ca->cost_assessment->report_date ? $ca->cost_assessment->report_date->format('d/m/Y') : '' }}"></input-date>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br><hr>
                    @if ($ca->cost_assessment->invoice)
                        <h3 class="box-title">หลักฐานการชำระเงิน</h3>
                        <form action="{{ route('check_assessment.update.cost.confirm', ['cost' => $ca->cost_assessment]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="text-left">
                                <label for="comment_number" class="col-sm-12 control-label label-filter">
                                    หลักฐานการชำระเงิน :
                                    <a href="{{ url('/certify/files/applicants/cost_assessments') . '/' . basename($ca->cost_assessment->invoice) }}" class="m-l-15">{{ basename($ca->cost_assessment->invoice) }}</a>
                                </label>
                                <br>
                                <label class="col-sm-12"><input name="status_confirmed" type="checkbox" {{ $ca->cost_assessment->status_confirmed ? 'checked' : '' }}> ได้รับกำรชำระเงินค่ำตรวจประเมินเรียบร้อยแล้ว</label>
                            </div>
                            <div class="row text-center">
                                <div class="col-sm-8 m-t-15">
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-4 m-t-15">
                                            <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                            <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">การตรวจประเมินและข้อบกพร่อง/ข้อสังเกต</h3>
                    <div class="row text-center">
                        <div class="col-sm-8">
                            <a href="{{ route('save_assessment.index') . '?app=' . $ca->applicant->id }}">
                                <h3 class="text-muted"><คลิกเพื่อเพิ่ม/ดูข้อมูลการตรวจประเมินและข้อบกพร่อง/ข้อสังเกต></h3>
                            </a>
                        </div>
                    </div>
                    <h3 class="box-title">การแก้ไขข้อบกพร่อง/ข้อสังเกต</h3>
                    <div class="row text-center">
                        <div class="col-sm-12 m-t-15">
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                <tr>
                                    <th class="text-center">วันที่ตรวจฯ</th>
                                    <th class="text-center">ข้อบกพร่อง/ข้อสังเกต</th>
                                    <th class="text-center">ข้อ</th>
                                    <th class="text-center">ประเภท</th>
                                    <th class="text-center">ผู้พบ</th>
                                    <th class="text-center">การแก้ไข</th>
                                    <th class="text-center">ผลการแก้ไข</th>
                                </tr>
                                </thead>
                                <tbody id="table-body" v-for="(group_item, index) in group_notice_items">
                                    <tr class="text-top">
                                        <td>@{{ group_item.item.notice.assessment_date ? group_item.item.notice.assessment_date.format('ll') : '-' }}</td>
                                        <td>@{{ group_item.item.remark }}</td>
                                        <td>@{{ group_item.item.no }}</td>
                                        <td>
                                            <span v-if="group_item.item.type==1">Major</span>
                                            <span v-else-if="group_item.item.type==2">Minor</span>
                                            <span v-else-if="group_item.item.type==3">Observation</span>
                                            <span v-else>Error</span>
                                        </td>
                                        <td>@{{ group_item.item.reporter.fname_th + ' ' + group_item.item.reporter.lname_th }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" @click="openModal2(group_item)">รายละเอียด</button>
                                        </td>
                                        <td>
                                            <span v-if="group_item.item.notice.status==1">ผ่าน</span>
                                            <span v-else-if="group_item.item.notice.status==2">ไม่ผ่าน</span>
                                            <span v-else>-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-8 m-t-15">
                            <form action="{{ route('check_assessment.update.status.notice', ['ca' => $ca]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="text-left">
                                    <label><input name="status_confirmed" type="checkbox" {{ $ca->assessment_status ? 'checked' : '' }}> ตรวจประเมินและแก้ไขข้อบกพร่อง/ข้อสังเกตเรียบร้อยแล้ว</label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">สรุปรายงานการตรวจประเมินและมติคณะอนุกรรมการ</h3>
                    <div class="row text-left">
                        <form action="{{ route('check_assessment.update.report', ['report' => $ca->report]) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">วันที่ประชุม : </label>
                                    <div class="col-md-4 text-left">
                                        <input type="text" name="meetdate" class="form-control mydatepicker" autocomplete="off" value="{{ $ca->report->meet_date ? $ca->report->meet_date->format('d/m/Y') : '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <input-file input-name="report"
                                     old-url="{{ route('applicants.file', ['path'=>'cost_assessments', 'filename'=> basename($ca->report->file) ]) }}"
                                     old-file-name="{{ $ca->report->file }}">
                                </input-file>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">มติคณะอนุกรรมการ : </label>
                                    <div class="col-md-6">
                                        <label><input name="resolution" type="radio" value="1" {{ $ca->report->status==1 ? 'checked':'' }}> เห็นชอบ</label>
                                        <label><input name="resolution" type="radio" value="2" {{ $ca->report->status==2 ? 'checked':'' }}> ไม่เห็นชอบ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">รายละเอียด : </label>
                                    <div class="col-md-8 text-left">
                                        <textarea name="desc" cols="30" rows="5" class="form-control">{{ $ca->report->desc }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <input-file-multiple></input-file-multiple>
                            </div>
                            @if ($ca->report->files()->exists())
                                <div class="col-sm-8 m-t-15">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label label-filter text-right">ไฟล์เก่า : </label>
                                        <div class="col-md-8 text-left">
                                            @foreach ($ca->report->files as $report_file)
                                                <a href="{{ url('/certify/files/applicants/cost_assessments') . '/' . basename($report_file->file) }}">{{ basename($report_file->file) }}</a><br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">เจ้าหน้าที่ : </label>
                                    <div class="col-md-6 text-left">
                                        <input type="text" name="saver" class="form-control" value="{{ auth()->user()->reg_fname.' '.auth()->user()->reg_lname }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">วันที่บันทึก : </label>
                                    <div class="col-md-4 text-left">
                                        <input type="text" name="savedate" class="form-control mydatepicker" autocomplete="off" value="{{ $ca->report->save_date ? $ca->report->save_date->format('d/m/Y') : '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15 text-center">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="white-box">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">แจ้งรายละเอียดการชำระเงินค่าใบรับรอง</h3>
                    <form action="{{ route('check_assessment.update.cost_certificate',['costcertificate' => $ca->cost_certificate]) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="row text-center">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">จำนวนเงิน : </label>
                                    <div class="col-md-4">
                                        <vue-autonumeric :options="costOptions" name="amount" class="form-control" value="{{ $ca->cost_certificate->amount }}"></vue-autonumeric>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <input-file input-name="report"
                                     old-url="{{ route('applicants.file', ['path'=>'cost_assessments', 'filename'=> basename($ca->cost_certificate->amount_file) ]) }}"
                                     old-file-name="{{ $ca->cost_certificate->amount_file }}">
                                </input-file>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">เจ้าหน้าที่ : </label>
                                    <div class="col-md-6 text-left">
                                        <input type="text" name="saver" class="form-control" value="{{ auth()->user()->reg_fname.' '.auth()->user()->reg_lname }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <label class="col-md-4 control-label label-filter text-right">วันที่บันทึก : </label>
                                    <div class="col-md-4 text-left">
                                        <input type="text" name="savedate" class="form-control mydatepicker" autocomplete="off" value="{{ $ca->cost_certificate->report_date ? $ca->cost_certificate->report_date->format('d/m/Y') : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><hr>
                        <h3 class="box-title">หลักฐานการชำระเงิน</h3>
                        <div class="text-left">
                            <label class="col-sm-12 control-label label-filter">หลักฐานการชำระเงิน : <a href="{{url('/certify/files/applicants/check_files/'.basename($ca->cost_certificate->invoice))}}" class="m-l-15">{{ basename($ca->cost_certificate->invoice) }}</a></label>
                            <br>
                            <label class="col-sm-12"><input name="status_confirmed" type="checkbox" {{ $ca->cost_certificate->status_confirmed==1?'checked':'' }}> ได้รับการชำระเงินค่าตรวจค่าใบรับรองเรียบร้อยแล้ว</label>
                            <label class="col-sm-12"><input name="status_later" type="checkbox" {{ $ca->cost_certificate->status_later==1?'checked':'' }}> ชำระเงินค่าใบรับรองภายหลัง</label>
                        </div>
                        <div class="row text-center">
                            <div class="col-sm-8 m-t-15">
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-4 m-t-15">
                                        <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                        <a href="{{ route('check_assessment.show', ['ca' => $ca]) }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" ref="modal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" v-if="modalData">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title">รายละเอียดความเห็นแต่งตั้งคณะผู้ตรวจประเมิน</h3>
                    </div>
                    <div class="modal-body">
                        <div class="white-box" v-for="auditor in modalData.auditors">
                            <h3 class="box-title">@{{ auditor.ba.no }}</h3>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">ชื่อคณะผู้ตรวจประเมิน : </label>
                                <label class="col-md-7">@{{ auditor.ba.no }}</label>
                            </div>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">วันที่ตรวจประเมิน : </label>
                                <label class="col-md-7">@{{ modalData.auditor_date ? modalData.auditor_date : '' }}</label>
                            </div>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">หนังสือแต่งตั้งคณะผู้ตรวจประเมิน : </label>
                                <div class="col-md-7">
                                    <a :href="'{{ url('certify/auditor/files') }}/' + auditor.ba.file">@{{ auditor.ba.file }}</a>
                                </div>
                            </div>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">โดยคณะผู้ตรวจประเมินมีรายนามดังต่อไปนี้ : </label>
                                <div class="col-md-7" v-for="(name, index) in auditor.ba.auditor_names">
                                    <div class="row">
                                        <span class="col-md-5">@{{ index+1 }}. @{{ name }}</span>
                                        <span class="col-md-7">หัวหน้ำคณะผู้ตรวจประเมิน</span>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">ความเห็นในการแต่งตั้งคณะผู้ตรวจประเมิน : </label>
                                <label class="col-md-7" v-if="modalData.group.status == 1">เห็นชอบ</label>
                                <label class="col-md-7" v-else-if="modalData.group.status == 2">ไม่เห็นชอบ</label>
                                <label class="col-md-7" v-else>-</label>
                            </div>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">เหตุผล : </label>
                                <label class="col-md-7">@{{ modalData.group.remark ? modalData.group.remark : '-' }}</label>
                            </div>
                            <div class="row p-l-15 text-left">
                                <label class="col-md-5">ไฟล์แนบ (ถ้ำมี) : </label>
                                <div class="col-md-7" v-for="file in modalData.group.files">
                                    <a :href="file.file ? url_file + file.file.split('/').reverse()[0] : ''">@{{ file.file ? file.file.split('/').reverse()[0] : '' }}</a><br>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="modal_2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" v-if="modalData2">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title">รายละเอียดการแก้ไขข้อบกพร่อง/ข้อสังเกต</h3>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('save_assessment.status.update') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="notice_id" :value="modalData2.item.notice.id">
                            <div class="white-box text-right">
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">วันที่ตรวจประเมิน : </label>
                                    <label class="col-md-7 text-left">@{{ modalData2.item.notice.assessment_date ? modalData2.item.notice.assessment_date.format('ll') : '' }}</label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ชื่อคณะผู้ตรวจประเมิน : </label>
                                    <label class="col-md-7 text-left">คณะผู้ตรวจประเมินที่ 1</label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ข้อบกพร่อง/ข้อสังเกต : </label>
                                    <label class="col-md-7 text-left">@{{ modalData2.item.remark }}</label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">มอก. 17025 ข้อ : </label>
                                    <label class="col-md-7 text-left">@{{ modalData2.item.no }}</label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ประเภท : </label>
                                    <label class="col-md-7 text-left">
                                        <span v-if="modalData2.item.type==1">Major</span>
                                        <span v-else-if="modalData2.item.type==2">Minor</span>
                                        <span v-else-if="modalData2.item.type==3">Observation</span>
                                        <span v-else>Error</span>
                                    </label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ผู้พบ : </label>
                                    <label class="col-md-7 text-left">
                                        @{{ modalData2.item.reporter.fname_th + ' ' + modalData2.item.reporter.lname_th }}
                                    </label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">การแก้ไข : </label>
                                    <label class="col-md-7 text-left">@{{ modalData2.item.notice.desc }}</label>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ไฟล์แนบ (ถ้ามี) : </label>
                                    <div class="col-md-7 text-left" v-for="file in modalData2.item.notice.files">
                                        <a :href="file.file ? '{{ url('certify/files/applicants/notice_files') }}/' + file.file.split('/').reverse()[0] : '#'">@{{ file.file ? file.file.split('/').reverse()[0] : '' }}</a>
                                    </div>
                                </div>
                                <div class="row p-l-15 ">
                                    <label class="col-md-3">ผลการแก้ไข : </label>
                                    <div class="col-md-3 text-left">
                                        <select class="form-control" name="status">
                                            <option value="" selected>-ผลการแก้ไข-</option>
                                            <option value="1" :selected="modalData2.item.notice.status==1">ผ่าน</option>
                                            <option value="2" :selected="modalData2.item.notice.status==2">ไม่ผ่าน</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="form-save"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                <button type="button" class="btn btn-default" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><i class="fa fa-rotate-left"></i> ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        const GROUP_AUDITOR = {
            auditor_date: moment("13-07-2019", "DD-MM-YYYY").locale('th').add(543,'year').format('ll'),
            auditors: [
                {text: "คณะผู้ตรวจประเมิน 1"},
                {text: "คณะผู้ตรวจประเมิน 2"},
                {text: "คณะผู้ตรวจประเมินเอกสาร"},
            ],
            assessment: "ให้ความเห็นชอบแล้ว",
            assessment_date: moment("17-04-2019", "DD-MM-YYYY").locale('th').add(543,'year').format('ll'),
            token: 1,
            showModal: false,
        };

        new Vue({
            el: '#app_check_deail',
            data: {
                url_file: '{{ url('certify/auditor/files/group_files') }}/',
                form: {
                    options: [],
                    labels: [],
                    check_date: '',
                    hasSend: false,
                },
                group_auditors: [],
                assessments: [],
                costOptions: [
                    'float',
                    {
                        digitGroupSeparator: '',
                        maximumValue: '9999999',
                        minimumValue: '0',
                        emptyInputBehavior: 'zero'
                    }
                ],
                modalData: null,
                modalData2: null,
                group_notice_items: [],
                valueChecking: "{{  $ca->applicant->status <= $maxStatus ? $ca->applicant->status : $maxStatus }}",
                isShowApplicant: false
            },
            methods: {
                clearLabels: function () {
                    this.form.labels = [];
                },
                saveForm: function () {
                    this.form.hasSend = true;


                    const url = "{{ route('check_assessment.api.store') }}";
                    axios.post(url, {
                        labels: this.form.labels,
                        check_date: $(this.$refs.check_date).val(),
                        assessment_id: '{{ $ca->id }}'
                    }).catch(error => {
                        console.log('BAD', error);
                        alertError();

                    }).then((response) => {
                        console.log(response);
                        const data = response;
                        let auditors = _.clone(this.form.labels);

                        const dataAuditors = [];
                        auditors.map(async (element) => {
                            const url = "{{ route('board_auditor.api.get') }}/" + element.value;
                            try {
                                const res = await axios(url);
                                let ba = res.data.ba;
                                ba.check_date = moment(ba.check_date, "YYYY-MM-DD").locale('th');
                                const auditor_information = ba.auditor_information;
                                ba.auditor_names = [...new Set(auditor_information.map(x => x.auditor.fname_th + " " + x.auditor.lname_th))];
                                dataAuditors.push({value: ba.id, text: ba.no, ba: ba});
                            } catch (e) {
                                console.log(e);
                            }
                        });

                        let group = {
                            auditor_date: moment(data.check_date, "DD-MM-YYYY").locale('th').add(543,'year').format('ll'),
                            auditors: dataAuditors,
                            assessment: "-",
                            assessment_date: "-",
                            token: 1,
                            showModal: false,
                            group: data.group
                        };

                        this.group_auditors.push(group);
                        this.clearLabels();

                        alertSuccess();

                    }).finally(() => {
                        this.form.hasSend = false;
                    });
                },

                openModal: function (group) {
                    this.modalData = group;
                    $(this.$refs.modal).modal('show')
                },

                openModal2: function (group_item) {
                    this.modalData2 = group_item;
                    $(this.$refs.modal_2).modal('show')
                },
                initSelect: async function () {
                    try {
                        const url = "{{ route('check_assessment.api.get.auditors') }}";
                        const res = await axios(url);
                        const data = res.data;
                        data.auditors.map(auditor => {
                            this.form.options.push({value: auditor.id, text: auditor.no})
                        });
                    } catch (e) {
                        console.log(e.response);
                    }
                },
                initGroups: async function() {
                    try {
                        const url = "{{ route('check_assessment.api.get.groups', ['ca' => $ca]) }}";
                        const res = await axios(url);
                        const data = res.data;

                        data.groups.map(group => {
                            let BaseBA;
                            const auditors = group.auditors.map(element => {
                                element.auditor.check_date = moment(element.auditor.check_date, "YYYY-MM-DD").locale('th');
                                const ba = element.auditor;
                                BaseBA = ba;
                                const auditor_information = ba.auditor_information;
                                const distinctArr = [...new Set(auditor_information.map(x => x.auditor.fname_th + " " + x.auditor.lname_th))];
                                ba.auditor_names = distinctArr;
                                return {value: element.id, text: ba.no, ba: ba}
                            });

                            let newGroup = {
                                auditor_date: moment(group.assessment_date, "YYYY-MM-DD").locale('th').add(543,'year').format('ll'),
                                auditors: auditors,
                                assessment: group.status,
                                assessment_date: BaseBA ? moment(BaseBA.check_date, "YYYY-MM-DD").locale('th').add(543,'year').format('ll') : '-',
                                token: 1,
                                showModal: false,
                                group: group,
                            };
                            this.group_auditors.push(newGroup);
                        })
                    } catch (e) {
                        console.log(e);
                    }
                },
                initNotices: async function() {
                    try {
                        const url = "{{ route('save_assessment.api.get.notices', ['ca'=> $ca]) }}";
                        const res = await axios(url);
                        res.data.items.map(item => {
                            item.notice.assessment_date = moment(item.notice.assessment_date, "YYYY-MM-DD").locale('th');
                            this.group_notice_items.push({
                                item: item
                            });
                        });
                        console.log(res);
                    } catch (e) {
                        console.log(e);
                    }
                },
                doShowHide: function() {
                    const showWhen3 = ["9"].includes(this.valueChecking);
                    this.isShowApplicant = showWhen3;
                }
            },
            mounted() {
                this.initSelect();
                this.initGroups();
                this.initNotices();
                this.doShowHide();
                console.log('Assessment Group Mounted.');
            }
        });


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

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

        });

        function alertSuccess() {
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: 'แต่งตั้งคณะผู้ตรวจประเมินเรียบร้อย',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        }

        function alertError() {
            $.toast({
                heading: 'Wrong!',
                position: 'top-center',
                text: 'พบข้อผิดพลาด',
                icon: 'error',
                hideAfter: 3000,
                stack: 6
            });
        }

    </script>

@endpush
