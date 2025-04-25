<div id="app_result_container" v-show="mountFinish">

    <div class="row p-r-15">
        <div class="col-md-12">
            <!--แสดงตาราง -->
            <table id="tb_plan" class="table table-bordered table-hover">
                <tr class="info">
                    <th>No.</th>
                    <th>กิจกรรม</th>
                    <th>ปี</th>
                    <th>ไตรมาส</th>
                    <th>วันที่ทำกิจกรรม</th>
                    <th>เบี้ยประชุม</th>
                    <th>ค่าอาหารว่าง</th>
                    <th>ลบ</th>
                </tr>
                <tbody>
                @foreach ($set_standard->set_standard_plan()->orderBy('startdate')->get() as $set_standard_plan)
                    <tr @click="onClickPlan('{{ $set_standard_plan->id }}')" style="cursor: pointer;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $set_standard_plan->status_operation->title }}</td>
                        <td>{{ $set_standard_plan->year }}</td>
                        <td>{{ $set_standard_plan->strQuarter() }}</td>
                        <td>{{ HP::DateThai($set_standard_plan->startdate) . ' - ' . HP::DateThai($set_standard_plan->enddate) }}</td>
                        <td>{{ $set_standard_plan->totalAllowances() }}</td>
                        <td>{{ $set_standard_plan->totalFoods() }}</td>
                        <td>
                            <form action="{{ url("tis/set_standard_plan/{$set_standard_plan->id}/delete") }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button title="ลบ" type="submit" class="btn btn-light" onclick="return confirm('ต้องการลบแพลนหรือไม่?')">
                                    <i class="fa fa-trash-o text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" align="right">รวม</td>
                    <td class="someTotalClass">{{ $set_standard->totalAllowances() }}</td>
                    <td class="someTotalPrice">{{ $set_standard->totalFoods() }}</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" align="right">รวมทั้งสิ้น</td>
                    <td colspan="4" class="sumTotal text-right">{{ $set_standard->total() }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {!! Form::model($set_standard, [
        'method' => 'POST',
        'url' => ['tis/set_standard_plan/update'],
        'class' => 'form-horizontal',
        'files' => true
    ]) !!}

        <plan-form
                :years="years"
                :prop-status-id="status_id"
                :prop-appoint-name-id="appointNameId"
                :prop-meeting-no="meetingNo"
                :status-operations="status_operations"
                :appoint-names="appoint_names"
                :prop-values="values"
                :prop-plan-id="plan_id"
                class="m-t-15"
                v-show="values">
        </plan-form>

    {!! Form::close() !!}

    <div class="row" v-show="!values">
        <div class="col-md-12 text-center">
            <h4><b>คลิกเลือกข้อมูลเพื่อแสดงการแก้ไข</b></h4>
        </div>
    </div>

</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        new Vue({
            el: '#app_result_container',
            data: {
                values: null,
                status_id: null,
                appointNameId: null,
                meetingNo: null,
                plan_id: null,
                years: [],
                status_operations: [],
                appoint_names: [],
                mountFinish: false,
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
                        return {value: status_operation.id, text: status_operation.title};
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

                console.log("Result Mounted.");
                this.mountFinish = true;
            },
            methods: {
                onClickPlan: async function (plan_id) {
                    try {
                        const url = '{{ url('api/tis/set_standard_plan') }}/' + plan_id;
                        const res = await axios(url);
                        const plan = res.data.plan;
                        this.values = {
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

                        this.status_id = plan.status_operation.id;
                        this.appointNameId = plan.appoint_name.id;
                        this.meetingNo = "4444444";
                        this.plan_id = plan_id;
                    } catch (e) {
                        this.values = null;
                        this.status_id = null;
                        this.appointNameId = null;
                        this.meetingNo = "55555555";
                        this.plan_id = null;
                    }
                }
            }
        })
    </script>
@endpush
