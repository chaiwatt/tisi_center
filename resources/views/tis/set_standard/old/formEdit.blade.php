@php
    $labelSize = 'col-md-4';
    $formSize = 'col-md-8';
@endphp

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
@endpush

<div class="row">

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#mor" aria-controls="mor" role="tab" data-toggle="tab">มอก.</a>
        </li>
        <li role="presentation"><a href="#plan" aria-controls="plan" role="tab" data-toggle="tab">แผน</a></li>
        <li role="presentation"><a href="#result" aria-controls="result" role="tab" data-toggle="tab">ผล</a></li>
    </ul>

    <div class="tab-content" id="app_tab_container">
        <div role="tabpanel" class="tab-pane active" id="mor">
            {!! Form::model($set_standard, [
                'method' => 'PATCH',
                'url' => ['/tis/set_standard', $set_standard->id],
                'class' => 'form-horizontal',
                'files' => true
            ]) !!}

            @include('tis.set_standard.mor', ['set_standard' => $set_standard])

            {!! Form::close() !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="plan">

            <div class="clearfix"></div>
            <div id="app_plan_container">

{{--                {!! Form::model($set_standard, [--}}
{{--                    'method' => 'PUT',--}}
{{--                    'url' => ['/tis/set_standard/update-plan', $set_standard->id],--}}
{{--                    'class' => 'form-horizontal',--}}
{{--                    'files' => true--}}
{{--                ]) !!}--}}

                <plan-form
                        prop-url="{{ url('/tis/set_standard/update-plan') . '/' . $set_standard->id }}"
                        :years="years"
                        :status-operations="status_operations"
                        :appoint-names="appoint_names"
                        :prop-values="values"
                        :prop-plan-id="plan_id"
                        :prop-status-id="status_id"
                        :prop-appoint-name-id="appoint_name_id"
                        :prop-meeting-no="meetingNo"
                        :prop-year="year"
                        :prop-quarter="quarter"
                        :prop-start-date="startDate"
                        :prop-end-date="endDate"
                        :prop-add-new-plan="addNewPlan"
                        :prop-reset-plat-form-data="resetPlatformData">
                </plan-form>

                {{-- {!! Form::close() !!} --}}

                <div class="row p-r-15">
                    <div class="col-md-12">
                        <!--แสดงตาราง -->
                        <table id="tb_plan" class="table table-bordered">
                            <tr class="info">
                                <th>No.</th>
                                <th>กิจกรรม</th>
                                <th>ปี</th>
                                <th>ไตรมาส</th>
                                <th>วันที่ทำกิจกรรม</th>
                                <th>เบี้ยประชุม</th>
                                <th>ค่าอาหารว่าง</th>
                                <th class="text-center">แก้ไข</th>
                                <th class="text-center">ลบ</th>
                                {{-- <th>คำนวนวันที่แล้วเสร็จ</th> --}}
                            </tr>
                            <tbody v-for="(plan, index) in plans">
                            <tr @click="onClickPlan(plan.id)" style="cursor: pointer;">
                                <td>@{{ index+1 }}</td>
                                <td>@{{ plan.status_operation.title }}</td>
                                <td>@{{ plan.year }}</td>
                                <td>@{{ plan.strQuarter }}</td>
                                <td>@{{ plan.strDate }}</td>
                                <td class="text-right">@{{ new Intl.NumberFormat().format(plan.totalAllowances) }}</td>
                                <td class="text-right">@{{ new Intl.NumberFormat().format(plan.totalFoods) }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-light button-for-show">
                                        <i class="fa fa-pencil text-danger"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button title="ลบ" type="button" @click="deletePlan(plan)"
                                            class="btn btn-light" :disabled="hasDeleting">
                                        <i class="fa fa-trash-o text-danger"></i>
                                    </button>
                                </td>
                                {{-- <td>
                                    <label><input type="radio" name="status"> สถานะสุดท้าย</label>
                                </td> --}}
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5" align="right">รวม</td>
                                <td class="someTotalClass text-right">@{{ set_standard ? new Intl.NumberFormat().format(set_standard.totalAllowances) : 0 }}</td>
                                <td class="someTotalPrice text-right">@{{ set_standard ? new Intl.NumberFormat().format(set_standard.totalFoods) : 0 }}</td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right">รวมทั้งสิ้น</td>
                                <td colspan="2" class="sumTotal text-center">@{{ set_standard ? new Intl.NumberFormat().format(set_standard.total) : 0 }}</td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="result">

            <div v-show="mountFinish">

                 <plan-form
                    prop-url="{{ url('/tis/set_standard/update-result') . '/' . $set_standard->id }}"
                    :years="years"
                    :status-operations="status_operations"
                    :appoint-names="appoint_names"
                    :prop-values="values"
                    :prop-plan-id="plan_id"
                    :prop-status-id="status_id"
                    :prop-appoint-name-id="appoint_name_id"
                    :prop-meeting-no="meetingNo"
                    :prop-year="year"
                    :prop-quarter="quarter"
                    :prop-start-date="startDate"
                    :prop-end-date="endDate"
                    :prop-add-new-result="addNewResult"
                    :prop-reset-plat-form-data="resetPlatformDataResult">
                </plan-form>

                {{-- <div class="row" v-show="!result.values">
                    <div class="col-md-12 text-center">
                        <h4><b>คลิกเลือกข้อมูลเพื่อแสดงการแก้ไข</b></h4>
                    </div>
                </div> --}}

                <div class="row p-r-15">
                    <div class="col-md-12">
                        <!--แสดงตาราง -->
                        <table id="tb_plan" class="table table-bordered">
                            <tr class="info">
                                <th>No.</th>
                                <th>กิจกรรม</th>
                                <th>ปี</th>
                                <th>ไตรมาส</th>
                                <th>วันที่ทำกิจกรรม</th>
                                <th>เบี้ยประชุม</th>
                                <th>ค่าอาหารว่าง</th>
                                <th class="text-center">แก้ไข</th>
                                <th class="text-center">ลบ</th>
                            </tr>
                            <tbody v-for="(result, index) in results">
                            <tr @click="onClickPlan(result.id, 'result')" style="cursor: pointer;">
                                <td>@{{ index+1 }}</td>
                                <td>@{{ result.status_operation.title }}</td>
                                <td>@{{ result.year }}</td>
                                <td>@{{ result.strQuarter }}</td>
                                <td>@{{ result.strDate }}</td>
                                <td class="text-right">@{{ new Intl.NumberFormat().format(result.totalAllowances) }}</td>
                                <td class="text-right">@{{ new Intl.NumberFormat().format(result.totalFoods) }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-light button-for-show">
                                        <i class="fa fa-pencil text-danger"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button title="ลบ" type="button" @click="deleteResult(result)"
                                            {{-- class="btn btn-light" :disabled="hasDeletingResult"> --}}
                                            class="btn btn-light">
                                        <i class="fa fa-trash-o text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5" align="right">รวม</td>
                                <td class="someTotalClass text-right">@{{ set_standard ?  new Intl.NumberFormat().format(set_standard.totalAllowancesResult) : 0  }}</td>
                                <td class="someTotalPrice text-right">@{{ set_standard ? new Intl.NumberFormat().format(set_standard.totalFoodsResult)  : 0 }}</td>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right">รวมทั้งสิ้น</td>
                                <td colspan="4" class="sumTotal text-center">@{{  set_standard ? new Intl.NumberFormat().format(set_standard.totalResult) : 0  }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>




            </div>

        </div>

    </div>
</div>


</div>


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <!-- tag input -->
    <script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Function -->
    <script src="{{asset('js/function.js')}}"></script>

    <script type="text/javascript">

        const INITIAL_VALUES = {
            no1: {
                v1: '',
                v2: '',
                v3: ''
            },
            no2: {
                v1: '',
                v2: '',
                v3: ''
            },
            no3: {
                v1: '',
                v2: '',
                v3: '',
                v4: ''
            }
        };

        const INITIAL_FORM = {
            title: '',
            title_en: '',
            start_year: '',
            plan_year: '',
            // made_by, sdo_name not use
            product_group_id: '',
            appoint_id: '',
            standard_type_id: '',
            standard_format_id: '',
            remark: '',
            set_format_id: '',
            method_id: '',
            industry_target_id: '',
            staff_group: '',
            cluster_id: '',
            refer: [{value: ''}],
            attaches: []
        };

        new Vue({
            el: '#app_tab_container',
            data: {
                values: null,
                status_id: null,
                appoint_name_id: null,
                meetingNo: null,
                year: null,
                quarter: null,
                startDate: null,
                endDate: null,
                plan_id: null,
                show: false,
                years: [],
                status_operations: [],
                appoint_names: [],
                plans: [],
                results: [],
                set_standard: null,
                hasDeleting: false,
                hasDeletingResult: false,
                mountFinish: false,

                result: {
                    values: null,
                    status_id: null,
                    appoint_name_id: null,
                    meetingNo: null,
                    plan_id: null,
                    year: null,
                    quarter: null,
                    startDate: null,
                    endDate: null,
                },
                // results: {
                //     values: null,
                //     status_id: null,
                //     appoint_name_id: null,
                //     meetingNo: null,
                //     plan_id: null,
                //     year: null,
                //     quarter: null,
                //     startDate: null,
                //     endDate: null,
                // },

                form: _.clone(INITIAL_FORM),
                review_status: '1',
                revise_status: '',
                made_by: null,
                sdo_name: "",
                tis_no: "",
                tis_no_text: "",
                tis_book: '',
                method_id: '',
                method_id_detail: '',
                tis_nos: [],
                method_details: []
            },
            async mounted() {
                try {
                    const url = '{{ url('api/tis/years') }}';
                    const res = await axios(url);
                    this.years = res.data.years.map(year => {
                        return {value: year, text: year};
                    });
                } catch (e) {
                    console.log(e);
                }

                try {
                    const url = '{{ url('api/tis/status_operations') }}';
                    const res = await axios(url);
                    this.status_operations = res.data.status_operations.map(status_operation => {
                        return {value: status_operation.id, text: status_operation.acronym+' - '+status_operation.title};
                    });
                } catch (e) {
                    console.log(e);
                }

                try {
                    const url = '{{ url('api/tis/appoint_names') }}';
                    const res = await axios(url);
                    this.appoint_names = res.data.appoint_names.map(appoint_name => {
                        return {value: appoint_name.id, text: appoint_name.title};
                    });
                } catch (e) {
                    console.log(e);
                }

                this.setPlans('{{ $set_standard->id }}');
                this.setResults('{{ $set_standard->id }}');

                this.initEditForm('{{ $set_standard ? $set_standard->id : '' }}');

                const vm = this;
                $(this.$refs.start_year).select2().on('change', function () {
                    vm.form.start_year = this.value;
                    $(vm.$refs.start_year).val(this.value)
                });
                $(this.$refs.plan_year).select2().on('change', function () {
                    vm.form.plan_year = this.value;
                    $(vm.$refs.plan_year).val(this.value)
                });

                this.mountFinish = true;
            },
            computed: {
                selectSDO: function () {
                    return this.made_by === "SDO";
                },
                selectReview: function () {
                    return this.review_status === "2";
                },
                selectNo: function () {
                    return this.tis_no !== "";
                },
                getBooks: function () {
                    let book = [];
                    if (this.tis_no !== "") {
                        this.tis_nos.map(tis => {
                            if (tis.id === this.tis_no) {
                                book.push({value: tis.tis_book});
                            }
                        });
                    }
                    return book;
                }
            },
            methods: {
                onClickPlan: async function (plan_id, type = 'plan') {
                    try {
                        let url = '{{ url('api/tis/set_standard_plan') }}/' + plan_id;
                        if (type === 'result') {
                            url = '{{ url('api/tis/set_standard_result') }}/' + plan_id;
                        }
                        const res = await axios(url);
                        const plan = res.data.plan;
                        // console.log(plan);
                        const values = {
                            no1: {
                                v1: plan.numpeople_g,
                                v2: plan.allowances_referee_g,
                                v3: plan.allowances_persident_g
                            },
                            no2: {
                                v1: plan.numpeople_subg,
                                v2: plan.allowances_referee_subg,
                                v3: plan.allowances_persident_subg
                            },
                            no3: {
                                v1: plan.numpeople_attendees,
                                v2: plan.food_morning_attendees,
                                v3: plan.food_noon_attendees,
                                v4: plan.food_afternoon_attendees
                            },
                        };

                        if (type === 'result') {
                            this.values = values;
                            this.status_id = plan.status_operation.id;
                            this.appoint_name_id = plan.appointName_id;
                            this.meetingNo = plan.meetingNo;
                            this.year = plan.year;
                            this.quarter = plan.quarter;
                            this.startDate = plan.strStartDate;
                            this.endDate = plan.strEndDate;
                            this.plan_id = plan_id;
                        } else {
                            this.values = values;
                            this.status_id = plan.status_operation.id;
                            this.appoint_name_id = plan.appointName_id;
                            this.meetingNo = plan.meetingNo;
                            this.year = plan.year;
                            this.quarter = plan.quarter;
                            this.startDate = plan.strStartDate;
                            this.endDate = plan.strEndDate;
                            this.plan_id = plan_id;
                        }
                    } catch (e) {
                        if (type === 'result') {
                            this.result.values = null;
                            this.result.status_id = null;
                            this.result.appoint_name_id = null;
                            this.result.meetingNo = null;
                            this.result.year = null;
                            this.result.quarter = null;
                            this.result.startDate = null;
                            this.result.endDate = null;
                            this.result.plan_id = null;
                        } else {
                            this.values = null;
                            this.status_id = null;
                            this.appoint_name_id = null;
                            this.meetingNo = null;
                            this.year = null;
                            this.quarter = null;
                            this.startDate = null;
                            this.endDate = null;
                            this.plan_id = null;
                        }
                    }
                },
                addNewPlan: function (set_standard_id) {
                    this.setPlans(set_standard_id);
                },
                addNewResult: function (set_standard_id) {
                    this.setResults(set_standard_id);
                },
                resetPlatformData: function() {
                    this.values = null;
                    this.status_id = null;
                    this.appoint_name_id = null;
                    this.meetingNo = null;
                    this.year = null;
                    this.quarter = null;
                    this.startDate = null;
                    this.endDate = null;
                    this.plan_id = null;
                },
                resetPlatformDataResult: function() {
                    this.values = null;
                    this.status_id = null;
                    this.appoint_name_id = null;
                    this.meetingNo = null;
                    this.year = null;
                    this.quarter = null;
                    this.startDate = null;
                    this.endDate = null;
                    this.plan_id = null;
                },
                deletePlan: async function (plan) {
                    this.hasDeleting = true;

                    if (!confirm('ต้องการลบแพลนหรือไม่?')) {
                        return;
                    }

                    try {
                        const url = '{{ url("api/tis/set_standard_plan") }}/' + plan.id + '/delete';
                        const res = await axios.delete(url);
                        console.log(res);
                        this.setPlans(plan.id_tis_set_standards);

                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.hasDeleting = false;
                    }
                },
                deleteResult: async function (result) {
                    this.hasDeletingResult = true;

                    if (!confirm('ต้องการลบผลหรือไม่?')) {
                        return;
                    }

                    try {
                        const url = '{{ url("api/tis/set_standard_result") }}/' + result.id + '/delete';
                        const res = await axios.delete(url);
                        console.log(res);
                        this.setResults(result.id_tis_set_standards);

                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.hasDeletingResult = false;
                    }
                },
                setPlans: async function (set_standard_id) {
                    try {
                        const url = '{{ url('api/tis/plans') }}/' + set_standard_id;
                        const res = await axios(url);
                        this.plans = res.data.plans;
                        this.set_standard = res.data.set_standard;

                    } catch (e) {
                        console.log(e);
                        this.plans = null;
                        this.set_standard = null;
                    }
                },
                setResults: async function (set_standard_id) {
                    try {
                        const url_result = '{{ url('api/tis/results') }}/' + set_standard_id;
                        const res_result = await axios(url_result);

                        this.results = res_result.data.results;
                        this.set_standard = res_result.data.set_standard;
                        console.table( this.results);
                    } catch (e) {
                        console.log(e);
                        this.results = null;
                        this.set_standard = null;
                    }
                },
                onChangeTisNo: async function () {
                    if (this.review_status === "2") {
                        $('#show_revise').show(300);
                    } else {
                        this.tis_no = "";
                        this.tis_no_text = "";
                        this.form = _.clone(INITIAL_FORM);
                        $('#show_revise').hide(300);
                    }
                },
                onChangeRevise: async function () {
                    if (this.revise_status === "1") {
                        const url = "{{ url('api/tis/public_drafts') }}";
                        try {
                            const res = await axios(url);
                            const data = res.data;
                            this.tis_nos = data.standards.filter(standard => {
                                return standard.review_status === 2;
                            });
                            console.log(data.standards);
                        } catch (e) {
                            console.log(e);
                        }
                    } else if (this.revise_status === "2") {
                        const url2 = "{{ url('api/tis/standards') }}";
                        try {
                            const res2 = await axios(url2);
                            const data2 = res2.data;
                            this.tis_nos = data2.standards
                            console.log(data2.standards);
                        } catch (e) {
                            console.log(e);
                        }
                    }
                },
                onChangeNo: function() {
                    const tis_no = this.tis_nos.find(tis => {
                        return tis.id === this.tis_no;
                    });
                    if (tis_no) {
                        this.form = {
                            title: tis_no.title,
                            title_en: tis_no.title_en,
                            start_year: tis_no.tis_year,
                            plan_year: tis_no.plan_year,
                            product_group_id: tis_no.product_group_id,
                            appoint_id: tis_no.appoint_id,
                            standard_type_id: tis_no.standard_type_id,
                            standard_format_id: tis_no.standard_format_id,
                            remark: tis_no.remark,
                            set_format_id: tis_no.set_format_id,
                            method_id: tis_no.method_id,
                            industry_target_id: tis_no.industry_target_id,
                            staff_group: tis_no.staff_group,
                            refer: tis_no.refers.map(refer => {
                                return {value: refer};
                            }),
                            attaches: tis_no.attaches,
                        };
                    } else {
                        this.form = _.clone(INITIAL_FORM);
                    }
                },
                onChangeMethodDetail: async function () {
                let method_id = this.form.method_id;
                // console.log(this.form.method_id);
                 const url = "{{ url('api/tis/get_method_detail') }}/"+method_id;
                      try {
                          const res = await axios(url);
                          const data = res.data;
                          this.method_details = data.data
                          console.log(data.data);
                      } catch (e) {
                          console.log(e);
                      }

                },
                onClickAttachAdd: function () {
                    this.form.attaches.push({
                        file_name: '',
                        file_note: ''
                    })
                },
                onClickReferAdd: function () {
                    this.form.refer.push({value: ''});
                },
                onClickReferRemove: function (index) {
                    this.form.refer.splice(index, 1);
                },
                initEditForm: async function (id) {
                    if (id === '') {
                        return;
                    }

                    try {
                        const url = "{{ url('api/tis/set_standard') }}/" + id;
                        const res = await axios(url);
                        // console.log(res);
                        const tis_no = res.data.set_standard;

                        this.review_status = tis_no.review_status ? tis_no.review_status.toString() : '';

                        if(this.review_status==2){
                            $('#show_revise').show(300);
                        }

                        this.revise_status = tis_no.revise_status;

                            if(this.revise_status=="1"){

                                this.tis_nos = res.data.standards.filter(standard => {
                                return standard.review_status === 2;
                                });

                            } else if(this.revise_status=="2") {
                                this.tis_nos = res.data.standards
                            }

                        this.tis_no = tis_no.standard_id ? tis_no.standard_id : '';
                        this.tis_no_text = tis_no.tis_no;

                        this.made_by = tis_no.made_by;
                        this.sdo_name = tis_no.sdo_name;

                        this.tis_book = tis_no.tis_book;

                        const url2 = "{{ url('api/tis/get_method_detail') }}/"+tis_no.method_id;
                        const res2 = await axios(url2);
                        const data2 = res2.data;
                        this.method_details = data2.data
                        this.method_id_detail = tis_no.method_id_detail;

                        this.form = {
                            title: tis_no.title,
                            title_en: tis_no.title_en,
                            start_year: tis_no.start_year,
                            plan_year: tis_no.plan_year,
                            product_group_id: tis_no.product_group_id,
                            appoint_id: tis_no.appoint_id,
                            standard_type_id: tis_no.standard_type_id,
                            standard_format_id: tis_no.standard_format_id,
                            remark: tis_no.remark,
                            set_format_id: tis_no.set_format_id,
                            method_id: tis_no.method_id,

                            industry_target_id: tis_no.industry_target_id,
                            staff_group: tis_no.staff_group,
                            cluster_id: tis_no.cluster_id,
                            refer: tis_no.refers.map(refer => {
                                return {value: refer};
                            }),
                            attaches: tis_no.attaches
                        };

                    } catch (e) {
                        console.log(e);
                    }

                }
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

            //เมื่อเลือกจัดทำโดย
            $('#made_by').change(function (event) {
                if ($(this).val() == 'SDO') {
                    $('.sdo_name').show();
                } else {
                    $('.sdo_name').hide();
                    $('#sdo_name').val('');
                }
            });

            //เมื่อเพิ่มข้อมูลอ้างอิง
            $('#add-refer').click(function () {

                $('#refer-box').children(':first').clone().appendTo('#refer-box'); //Clone Element

                //edit button
                var last_new = $('#refer-box').children(':last');
                $(last_new).find('input').val('');
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger remove-refer');
                $(last_new).find('button').html('<i class="icon-close"></i>');

            });

            //เมื่อลบข้อมูลอ้างอิง
            $('body').on('click', '.remove-refer', function (event) {
                $(this).parent().parent().remove();
            });

            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function (event) {
                $('.other_attach_item:first').clone().appendTo('#other_attach-box');

                $('.other_attach_item:last').find('input').val('');
                $('.other_attach_item:last').find('a.fileinput-exists').click();
                $('.other_attach_item:last').find('a.view-attach').remove();

                ShowHideRemoveBtn();

            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function (event) {
                $(this).parent().parent().remove();
                ShowHideRemoveBtn();
            });

            ShowHideRemoveBtn();

            $('#made_by').change();

            $('#secretary').tagsinput({
                onTagExists: function(item, $tag) {
                    $tag.hide().fadeIn();
                },
                maxTags: 3,
            });

            $('div.bootstrap-tagsinput').addClass('col-md-12');

            // function delayInputDisable() {
            //     $("div.bootstrap-tagsinput input").attr('readonly', true);
            // }
            // setTimeout(delayInputDisable, 1000);  // use setTimeout() to execute.

            $('#appoint_id').change(function(){
            // $('body').on('change', '#appoint_id', function () {
                if($('#secretary').val()){
                    return false;
                } else {
                    var data_val = $(this).val();
                    if(data_val!=""){
                        $.ajax({
                            type: "GET",
                            url: "{{url('tis/set_standard/get_secretary')}}",
                            datatype: "html",
                            data: {
                                appoint_id: data_val,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                var response = data;
                                var list = response.data;
                                $("#secretary").tagsinput('add',list);
                            }
                        });
                    }
                }
            });

            $('#standard_announcement').click(function(){
                    var set_standard_id = $(this).data("set_standard_id");
                    var r = confirm("กรุณาตรวจสอบข้อมูล มอก. และเอกสารแนบ ให้ถูกต้องก่อนกด OK เพื่อส่งข้อมูลไปยังระบบ มาตรฐาน มอก. และดำเนินการต่อไป");
                        if (r == true) {
                        $.ajax({
                            type: "POST",
                            url: "{{url('tis/set_standard/standard_announcement')}}",
                            datatype: "html",
                            data: {
                                set_standard_id: set_standard_id,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                if(data.status=='success'){
                                    $.toast({
                                        heading: 'Success!',
                                        position: 'top-center',
                                        text: 'ประกาศมาตรฐานเรียบร้อยแล้ว',
                                        loaderBg: '#70b7d6',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 6
                                    });
                                    window.location.assign("{{url('/tis/set_standard')}}");
                                }

                            }
                        });
                    }
            });

            $('#cancel_announcement').click(function(){
                var set_standard_id = $(this).data("set_standard_id");
                    var r = confirm("กรุณาแจ้ง กวป. เพื่อลบข้อมูลในระบบ มาตรฐาน มอก. ก่อนกด OK มิฉะนั้นจะเกิดข้อมูลซ้ำซ้อนกัน");
                        if (r == true) {
                            $.ajax({
                                type: "POST",
                                url: "{{url('tis/set_standard/cancel_announcement')}}",
                                datatype: "html",
                                data: {
                                    set_standard_id: set_standard_id,
                                    '_token': "{{ csrf_token() }}",
                                },
                                success: function (data) {
                                    if(data.status=='success'){
                                        $.toast({
                                            heading: 'Success!',
                                            position: 'top-center',
                                            text: 'ยกเลิกการประกาศมาตรฐานเรียบร้อยแล้ว',
                                            loaderBg: '#70b7d6',
                                            icon: 'success',
                                            hideAfter: 3000,
                                            stack: 6
                                        });
                                        window.location.reload();
                                    }

                                }
                            });
                        }
            });

        });

        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 0) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }

        }


        function Cal() {

            //คำนวนจำนวนคน//
            var numpeople_g = $("#numpeople_g").val();
            var numpeople_subg = $("#numpeople_subg").val();
            var numpeople_attendees = $("#numpeople_attendees").val();
            var Cal_people = parseFloat(numpeople_subg) + parseFloat(numpeople_g);
            Cal_people = (isNaN(Cal_people)) ? 0 : Cal_people;
            var Cal_total = parseFloat(numpeople_attendees) - Cal_people;
            $("#total").val(Cal_total);

            //คำนวนเงินกว.//
            var allowances_referee_g = $("#allowances_referee_g").val();
            var allowances_persident_g = $("#allowances_persident_g").val();
            var cal_g_allowances1 = parseFloat(numpeople_g) * parseFloat(allowances_referee_g);
            var cal_g_allowances2 = parseFloat(allowances_persident_g) - parseFloat(allowances_referee_g);
            var total_allowances = cal_g_allowances1 + cal_g_allowances2;
            $("#sum_g").val(total_allowances);

            //คำนวณเงิน อนุกว.//
            var allowances_referee_subg = $("#allowances_referee_subg").val();
            var allowances_persident_subg = $("#allowances_persident_subg").val();
            var cal_sumg_allowances1 = parseFloat(numpeople_subg) * parseFloat(allowances_referee_subg);
            var cal_sumg_allowances2 = parseFloat(allowances_persident_subg) - parseFloat(allowances_referee_subg);
            var total_allowances_sumg = cal_sumg_allowances1 + cal_sumg_allowances2;
            $("#sum_subg").val(total_allowances_sumg);

            //คำนวณเงินผู้เข้าร่วมประชุม//
            var food_morning_attendees = $("#food_morning_attendees").val();
            var food_noon_attendees = $("#food_noon_attendees").val();
            var food_afternoon_attendees = $("#food_afternoon_attendees").val();
            var cal_food = parseFloat(food_morning_attendees) + parseFloat(food_noon_attendees) + parseFloat(food_afternoon_attendees);
            var total_attendees = parseFloat(numpeople_attendees) * parseFloat(cal_food);
            $("#sum_attendees").val(total_attendees);

            //คำนวนรวม(บาท)//
            var sum_g = $("#sum_g").val();
            var sum_subg = $("#sum_subg").val();
            var sum_attendees = $("#sum_attendees").val();
            var sum_total = parseFloat(sum_g) + parseFloat(sum_subg) + parseFloat(sum_attendees);
            $("#sum").val(sum_total);
        }

        function sumOfColumns() {
            var totalQuantity = 0;
            var totalPrice = 0;
            $(".sumtotal").each(function () {

                console.log($(this).html());
                if(checkNone($(this).html())){
                    var number1 =RemoveCommas($(this).html());
                    console.log(number1);
                    totalQuantity += parseFloat(number1);
                }
            });
            $(".someTotalClass").html(totalQuantity);

            $(".sumtotal_att").each(function () {
                if(checkNone($(this).html())){
                    var number2 =RemoveCommas($(this).html());
                    console.log(number2);
                     totalPrice += parseFloat(number2);
                }
            });
            $(".someTotalPrice").html(totalPrice);

            var sumCalTotal = parseFloat(totalQuantity) + parseFloat(totalPrice);
            $(".sumTotal").html(sumCalTotal);
        }


        function remove(id) {
            $("#row" + id).remove();
            sumOfColumns();
        }
        function checkNone(value) {
                      return value !== '' && value !== null && value !== undefined;
                 }
        $(document).ready(function () {
            $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
        });


        //JqueryUpdatePlan//
        $(document).ready(function () {

            var url = "/bigdata-itisi-center/public/tis/set_standard_plan";

            // $('.table').on("click", '.btn-edit', function () {
            //     //$("#planedit-form").show();

            //     var id = $(this).data("id");
            //     var editUrl = url + '/' + id + '/edit';
            //     $.get(editUrl, function (data) {
            //         //  get field values
            //         $('#record_id').val(data.id);
            //         $("input[name='statusOperation_ed']").val(data.statusOperation_id);
            //         $("input[name='year_ed']").val(data.year);
            //         $("input[name='quarter_ed']").val(data.quarter);
            //         $("input[name='startdate_ed']").val(data.start_day);
            //         $("input[name='enddate_ed']").val(data.end_day);
            //         //Row1//
            //         $("input[name='numpeople_g_ed']").val(data.numpeople_g);
            //         $("input[name='allowances_referee_g_ed']").val(data.allowances_referee_g)
            //         $("input[name='allowances_persident_g_ed']").val(data.allowances_persident_g);
            //         $("input[name='sum_g_ed']").val(data.sum_g)
            //         //Row2//
            //         $("input[name='numpeople_subg_ed']").val(data.numpeople_subg);
            //         $("input[name='allowances_referee_subg_ed']").val(data.allowances_referee_subg);
            //         $("input[name='allowances_persident_subg_ed']").val(data.allowances_persident_subg);
            //         $("input[name='sum_subg_ed']").val(data.sum_subg);
            //         //Row3//
            //         $("input[name='numpeople_attendees_ed']").val(data.numpeople_attendees);
            //         $("input[name='food_morning_attendees_ed']").val(data.food_morning_attendees);
            //         $("input[name='food_noon_attendees_ed']").val(data.food_noon_attendees);
            //         $("input[name='food_afternoon_attendees_ed']").val(data.food_afternoon_attendees);
            //         $("input[name='sum_attendees_ed']").val(data.sum_attendees);
            //         //RowSum//
            //         $("input[name='total_ed']").val(data.total);
            //         $("input[name='sum_ed']").val(data.sum);


            //         $('.btn_updatePlan').val("update");
            //     })
            // });

            // $(".btn_updatePlan").click(function () {
            //     var operation_id = $("input[name='statusOperation_ed']").select2().find(":selected").attr("id")
            //     var $select = $("input[name='statusOperation_ed']").parent().find("select"); // it's <select> element
            //     var value = $select.val();

            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //         }
            //     });

            //     var formData = {
            //         id: $('#record_id').val(),
            //         statusOperation_id: $(".select2 option:selected input[name='statusOperation_ed']").select2().val(),
            //         year: $("input[name='year_ed'] :selected").select2().val(),
            //         quarter: $("input[name='quarter_ed'] :selected").select2().val(),
            //         start_day: $("input[name='startdate_ed']").val(),
            //         end_day: $("input[name='enddate_ed']").val(),
            //         //Row1//
            //         numpeople_g: $("input[name='numpeople_g_ed']").val(),
            //         allowances_referee_g: $("input[name='allowances_referee_g_ed']").val(),
            //         allowances_persident_g: $("input[name='allowances_persident_g_ed']").val(),
            //         sum_g: $("input[name='sum_g_ed']").val(),
            //         //Row2//
            //         numpeople_sub: $("input[name='numpeople_subg_ed']").val(),
            //         allowances_referee_subg: $("input[name='allowances_referee_subg_ed']").val(),
            //         allowances_persident_subg: $("input[name='allowances_persident_subg_ed']").val(),
            //         sum_subg: $("input[name='sum_subg_ed']").val(),
            //         //Row3//
            //         numpeople_attendees: $("input[name='numpeople_attendees_ed']").val(),
            //         food_morning_attendees: $("input[name='food_morning_attendees_ed']").val(),
            //         food_noon_attendees: $("input[name='food_noon_attendees_ed']").val(),
            //         food_afternoon_attendees: $("input[name='food_afternoon_attendees_ed']").val(),
            //         sum_attendees: $("input[name='sum_attendees_ed']").val(),
            //         //RowSum//
            //         total: $("input[name='total_ed']").val(),
            //     };

            //     //used to determine the http verb to use [add=POST], [update=PUT]
            //     var state = $('.btn_updatePlan').val();
            //     var type = "GET"; //for creating new resource
            //     var id = $('#' + 'record_id').val(); // btn-save ID
            //     var my_url = url + '/' + id + '/update';

            //     $.ajax({
            //         type: type,
            //         url: my_url,
            //         data: formData,
            //         dataType: 'json',
            //         success: function (data) {
            //             alert('Record updated successfully');
            //             window.location.reload();
            //         },
            //         error: function (data) {
            //             console.log('Error:', data);
            //             var obj = {};
            //         }
            //     });
            // });
        });

    </script>
@endpush
