@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ) #{{ $data->id }}</h3>
                    @can('view-'.str_slug('receive-applicant-21bi'))
                        <a class="btn btn-success pull-right" href="{{ url('/asurv/receive_applicant_21bis') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    @include ('asurv.receive_applicant_21bis.form')

                        <fieldset class="row wrapper-detail">
                            <legend> ผลการพิจารณา</legend>

                            <div class="form-group ">
                                <div class="col-sm-4 control-label text-right"> สถานะ :</div>
                                <div class="col-sm-6 m-b-10">
                                    {{ HP::StatusReceiveApplicants()[$data->state] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 control-label text-right"> ความคิดเห็นเพิ่มเติม :</div>
                                <div class="col-sm-6 m-b-10">

                                    {{ $data->consider_comment }}

                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="col-sm-4 control-label" align="right"> ผู้พิจารณา :</div>
                                <div class="col-sm-6">

                                    {{ $data->consider }}

                                </div>
                            </div>

                        </fieldset>


                </div>
            </div>
        </div>
    </div>

@endsection
