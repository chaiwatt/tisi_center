@extends('layouts.master')

@push('style')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .a_custom {
            text-decoration: underline;
        }

        .center-block {
            display: block;
            margin-right: auto;
            margin-left: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" id="app_save_assessment">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ route('save_assessment.index', ['app' => $app ? $app->id : '']) }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
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

                    {!! Form::open(['url' => route('save_assessment.store', ['app' => $app ? $app->id : '']), 'class' => 'form-horizontal', 'method' => 'put']) !!}

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4"><span class="text-danger">*</span> เลขคำขอ : </label>
                                    <div class="col-md-8">
                                        <input type="hidden" name="group_id" v-model="appGroupId" required>
                                        {!! Form::select('app_no', 
                                          App\Models\Certify\Applicant\CertiLab::whereIn('status',[13,14,15])->pluck('app_no', 'app_no'),
                                         null,
                                         ['class' => 'form-control',
                                         'ref'=>'app_no',
                                         'placeholder'=>'-เลือกคำขอ-'])
                                          !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-md-4">หน่วยงาน : </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" v-model="appDepart" name="department" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4">ชื่อห้องปฏิบัติการ : </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="lab_name" v-model="appLabName" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-4">คณะผู้ตรวจประเมิน : </label>
                                    <div class="col-md-8">
                                        <div>
                                            <select2-badge :options="appOptions" :labels="labels"></select2-badge>
{{--                                            <select name="department" id="" class="form-control">--}}
{{--                                                <option v-repeat="label in appLabels" value="label.id">@{{ label.text }}</option>--}}
{{--                                            </select>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-md-4"><span class="text-danger">*</span> วันที่ตรวจประเมิน : </label>
                                    <div class="col-md-8">
                                        <input type="text"   v-model="SaveDate"  class="form-control mydatepicker" name="savedate" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="col-md-5"><span class="text-danger">*</span> รายงานข้อบกพร่อง : </label>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <label class="col-md-6"><input type="radio" name="optradio" value="1" @click="isoptradio()" required> มี</label>
                                            <label class="col-md-6"><input type="radio" name="optradio" value="2" @click="isNotoptradio()" checked  required> ไม่มี</label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6 text-right" v-if="isTable">
                                    <button type="button" class="btn btn-success btn-sm" @click="addAssessmentInput"><i class="icon-plus"></i> เพิ่ม</button>
                                </div>
                                <div class="col-sm-12 m-t-15" v-if="isTable">
                                    <table class="table color-bordered-table primary-bordered-table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">ลำดับ</th>
                                            <th class="text-center">ข้อบกพร่อง/ข้อสังเกต</th>
                                            <th class="text-center">มอก. 17025 : ข้อ</th>
                                            <th class="text-center">ประเภท</th>
                                            <th class="text-center">ผู้พบ</th>
                                            <th class="text-center">เครื่องมือ</th>
                                        </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        <tr class="text-top" v-for="(assessment, index) in assessments"  :key="index">
                                            <td class="text-center">
                                                @{{(index +1)}}
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" :name="'notice['+index+']'" v-model="assessment.notice" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" :name="'nok['+index+']'" v-model="assessment.mok" required>
                                            </td>
                                            <td>
                                                <select :name="'type['+index+']'" class="form-control not_select2" v-model="assessment.type" required>
                                                    <option v-for="type in types" :value="type.value">@{{ type.text }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select :name="'found['+index+']'" class="form-control not_select2" v-model="assessment.found" required>
                                                    <option v-for="found in founds" :value="found.value">@{{ found.text }}</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info btn-sm" @click="deleteAssessmentInput(index)"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4 m-t-15">
                                    <button type="submit" v-if="isDraft" class="btn btn-success" name="draft" value="1"> <i class="fa fa-file-o"></i> ฉบับร่าง</button>
                                    <button type="submit" class="btn btn-primary" id="form-save" name="draft" value="0"><i class="fa fa-paper-plane"></i> บันทึก</button>
                                    <a class="btn btn-default" href="{{url('/certify/save_assessment')}}"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

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
     <!-- input calendar thai -->
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
     <!-- thai extension -->
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
     <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        const INIT_ASSESSMENT = {notice: "", mok: "", type: "", found: ""};

        new Vue({
            el: '#app_save_assessment',
            data: {
                types: [
                    {value: "", text: 'เลือกประเภท'},
                    {value: 1, text: 'ข้อบกพร่อง'},
                    {value: 2, text: 'ข้อสัเกต'}
                ],
                founds: [
                    {value: "", text: 'เลือกผู้พบ'},
                ],
                assessments: [_.clone(INIT_ASSESSMENT)],
                isDraft: true,
                isTable: false,
                appNo: '{{ $app ? $app->app_no : '' }}',
                appDepart: '{{ $app ? $app->trader->trader_operater_name : '' }}',
                appLabName: '{{ $app ? $app->lab_name : '' }}',
                appOptions: [],
                labels: [],
                appGroupId: "",
                SaveDate: ""
            }, 
            methods: {
                addAssessmentInput: function () {
                    this.assessments.push(_.clone(INIT_ASSESSMENT));
                },
                deleteAssessmentInput: function (index) {
                    this.assessments.splice(index, 1);
                },
                isoptradio: function () {
                    this.isDraft=true;
                    this.isTable=true;
                },
                isNotoptradio: function () {
                    this.isDraft =! this.isDraft;
                    this.isTable=false;
                },

                doAppNoChange: async function () {
                    try {
                        const url = '{{ route('save_assessment.api.get.app') }}/' + this.appNo;
                        const res = await axios(url);
                        // console.table(res);
                        if (res.data.status === false) {
                            throw res.data.message;
                        }

                        const data = res.data;
                        const app = data.app;
                        const auditors = data.auditors;
                        const Select = data.Select;
    
    
                        const afs = data.afs;
                        this.appDepart = app ? app.trader.trader_operater_name : '';
                        this.SaveDate = data.created_at ?? '';
                        this.appLabName = app ? app.lab_name : '';
                        this.appGroupId = data.group_id;

                        this.appOptions = [];
                        Select.map(element => {
                            this.appOptions.push({value: element.id, text: element.no});
                        });
                        // auditors.map(element => {
                        //     this.appOptions.push({value: element.id, text: element.auditor.no});
                        // });

                        this.founds = [{value: "", text: 'เลือกผู้พบ'}];
                        afs.map(af => {
                            this.founds.push({text: af.fname_th+' '+af.lname_th, value: af.id})
                        });
                    } catch (e) {
                        const res = e.response;
                        console.log(res);
                        this.appDepart = '';
                        this.SaveDate = '';
                        this.appLabName = '';
                        this.appOptions = [];
                        this.founds = [{value: "", text: 'เลือกผู้พบ'}];
                        this.appGroupId = "";
                        alertError(e);
                    }
                }
            },
            mounted() {
                @if ($app)
                    @php
                        $group = $app->assessment->groups()->where('status', 1)->first();
                    @endphp
                    this.appGroupId = '{{ $group->id }}';
                    @foreach($group->auditors as $ag)
                        this.appOptions.push({value: '{{ $ag->id }}', text: '{{ $ag->auditor->no }}'});
                    @endforeach
                    @foreach($app->assessment->getSelectAuditors() as $af)
                        this.founds.push({text: '{{ $af->fname_th.' '.$af->lname_th }}', value: '{{ $af->id }}'});
                    @endforeach
                @endif

                const vm = this;
                $(this.$refs.app_no).select2().on('change', function () {
                    vm.appNo = this.value;
                    $(vm.$refs.app_no).val(this.value);
                    vm.doAppNoChange();
                });
                console.log("Save Assessment Mounted.");
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
                language:'th-th',
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

        function alertError(message = 'พบข้อผิดพลาด') {
            $.toast({
                heading: 'Wrong!',
                position: 'top-center',
                text: message,
                icon: 'error',
                hideAfter: 3000,
                stack: 6
            });
        }

    </script>

@endpush
