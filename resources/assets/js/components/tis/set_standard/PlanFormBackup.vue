<template>

<div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-md-3 control-label">กิจกรรม:</label>
                <div class="col-md-9">
                    <!-- ดึงข้อมูลจาก App\Models\Basic\StatusOperation::pluck('title', 'id') -->
                    <select2 name="statusOperation_id" id="statusOperation" v-model="statusId" class="form-control not_select2" required>
                        <option value="">- เลือกกิจกรรม -</option>
                        <option v-for="status in statusOperations" v-bind:key="status.value" :value="status.value">{{ status.text }}</option>
                    </select2>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="col-md-4 control-label">ชื่อคณะประชุม:</label>
                <div class="col-md-8">
                    <select2 name="appointName_id" id="appointName" v-model="appointNameId" class="form-control not_select2" required>
                        <option value="">- เลือกคณะกรรมการ -</option>
                        <option v-for="appoint in appointNames" v-bind:key="appoint.value" :value="appoint.value">{{ appoint.text }}</option>
                    </select2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="year" class="col-md-5 control-label">ครั้งที่ประชุม:</label>
                <div class="col-md-7">
                    <input type="text" ref="meetingNo" id="meetingNo" v-model="meetingNo" name="meetingNo" class="form-control" required>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-t-15">
        <!-- <div class="col-md-4">
            <div class="form-group">
                <label for="quarter" class="col-md-3 control-label">ไตรมาส:</label>
                <div class="col-md-9">
                    <select2 name="quarter" id="quarter" v-model="quarter" class="form-control not_select2" required>
                        <option value="">- เลือกไตรมาส -</option>
                        <option value="1">ไตรมาสที่ 1</option>
                        <option value="2">ไตรมาสที่ 2</option>
                        <option value="3">ไตรมาสที่ 3</option>
                        <option value="4">ไตรมาสที่ 4</option>
                    </select2>
                </div>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="year" class="col-md-5 control-label">ปีงบประมาณ:</label>
                <div class="col-md-7">
                    <select2 name="year" id="year" v-model="year" class="form-control not_select2" required>
                        <option value="">- เลือก -</option>
                        <option v-for="year in years" v-bind:key="year.value" :value="year.value">{{ year.text }}</option>
                    </select2>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="startdate" class="col-md-1 control-label">วัน:</label>
                <div class="col-md-5">
                    <input type="text" ref="startdate" id="startdate" v-model="startDate" name="startdate" class="form-control datepicker" data-provide="datepicker" data-date-language="th-th" placeholder="dd/mm/yyyy" autocomplete="off" required>
                </div>
                <label for="enddate" class="col-md-1 control-label">ถึง:</label>
                <div class="col-md-5">
                    <input type="text" ref="enddate" id="enddate" v-model="endDate" name="enddate" class="form-control datepicker" data-provide="datepicker" data-date-language="th-th" placeholder="dd/mm/yyyy" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="row m-t-15">
        <div class="col-md-12">
            <input type="hidden" name="plan_id" v-model="propPlanId">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="280px">#</th>
                        <th class="text-center" width="120px">จำนวน (คน)</th>
                        <th class="text-center" colspan="3">เบี้ยประชุม / ค่าอาหาร</th>
                        <th class="text-center" width="175px">ราคา (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1. เบี้ยประชุมคณะกว./กว.รายสาขา</td>
                    <td>
                        <vue-autonumeric :options="intOptions" v-model="values.no1.v1" name="numpeople_g" class="form-control text-right" placeholder="จำนวน (คน)" required></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no1.v2" name="allowances_referee_g" class="form-control text-right" placeholder="เบี้ยประชุม(กรรมการ)" required></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no1.v3" name="allowances_persident_g" class="form-control text-right" placeholder="เบี้ยประชุม(ประธาน)" required></vue-autonumeric>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <input type="text" class="form-control text-right" id="sum_g" name="sum_g" :value="new Intl.NumberFormat().format(totalNo1)" readonly>
                    </td>
                </tr>
                <tr>
                    <td>2. เบี้ยประชุมคณะอนุ กว./อนุกว.รายสาขา</td>
                    <td>
                        <vue-autonumeric :options="intOptions" v-model="values.no2.v1" name="numpeople_subg" class="form-control text-right" placeholder="จำนวน (คน)"></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no2.v2" name="allowances_referee_subg" class="form-control text-right" placeholder="เบี้ยประชุม(กรรมการ)" required></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no2.v3" name="allowances_persident_subg" class="form-control text-right" placeholder="เบี้ยประชุม(ประธาน)" required></vue-autonumeric>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <input type="text" class="form-control text-right" id="sum_subg" name="sum_subg" :value="new Intl.NumberFormat().format(totalNo2)" readonly>
                    </td>
                </tr>
                <tr>
                    <td>2. ผู้เข้าร่วมประชุมทั้งหมด (คน)</td>
                    <td>
                        <vue-autonumeric :options="intOptions" v-model="values.no3.v1" name="numpeople_attendees" class="form-control text-right" placeholder="จำนวน (คน)"></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no3.v2" name="food_morning_attendees" class="form-control text-right" placeholder="ราคาอาหารว่าง(ช่วงเช้า)" required></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no3.v3" name="food_noon_attendees" class="form-control text-right" placeholder="ราคาอาหาร(กลางวัน)" required></vue-autonumeric>
                    </td>
                    <td>
                        <vue-autonumeric :options="floatOptions" v-model="values.no3.v4" name="food_afternoon_attendees" class="form-control text-right" placeholder="ราคาอาหารว่าง(ช่วงบ่าย)" required></vue-autonumeric>
                    </td>
                    <td>
                        <input type="text" class="form-control text-right" id="sum_attendees" name="sum_attendees" :value="new Intl.NumberFormat().format(totalNo3)" readonly>
                    </td>
                </tr>
                <tr>
                    <!-- <td>รวม (คน)</td>
                    <td>
                        <input type="text" class="form-control" id="total" name="total" :value="totalPeople" readonly>
                    </td> -->
                    <td colspan="4"></td>
                    <td class="text-center">รวม (บาท)</td>
                    <td>
                        <input type="text" class="form-control text-right" id="sum" name="sum" :value="new Intl.NumberFormat().format(totalNoAll)" readonly>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

        <div class="col-md-12 text-right">
            <div class="form-group m-r-5">
                <div v-if="propValues">
                    <button class="btn btn-primary" type="button" @click="onSubmit" :disabled="hasSubmitting">
                        <i class="fa fa-save"></i> บันทึก
                    </button>
                    <button class="btn btn-danger" type="button" @click="onClickCancel">
                        <i class="fa fa-trash"></i> ยกเลิก
                    </button>
                </div>
                <button class="btn btn-primary" type="button" @click="onSubmit" v-else :disabled="hasSubmitting">
                    <i class="fa fa-plus"></i> เพิ่ม
                </button>
            </div>
        </div>

    </div>
</div>

</template>


<script>
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

    export default {
        props: [
            'propUrl',
            'years',
            'propStatusId',
            'propAppointNameId',
            'propMeetingNo',
            'statusOperations',
            'appointNames',
            'propYear',
            'propQuarter',
            'propStartDate',
            'propEndDate',
            'propValues',
            'propPlanId',
            'propAddNewPlan',
            'propAddNewResult',
            'propResetPlatFormData'
        ],
        async mounted() {
            $(this.$refs.startdate).on(
                'change', () => { this.startDate = $(this.$refs.startdate).val() }
            );

            $(this.$refs.enddate).on(
                'change', () => { this.endDate = $(this.$refs.enddate).val() }
            );
        },
        data() {
            return {
                hasSubmitting: false,
                intOptions: [
                    'integer',
                    {
                        digitGroupSeparator: '',
                        maximumValue: '9999999',
                        minimumValue: '0',
                        emptyInputBehavior: 'null'
                    }
                ],
                floatOptions: [
                    'float',
                    {
                        digitGroupSeparator: '',
                        maximumValue: '9999999',
                        minimumValue: '0',
                        emptyInputBehavior: 'null'
                    }
                ],
                values: _.cloneDeep(INITIAL_VALUES),
                statusId: '',
                year: '',
                quarter: '',
                startDate: '',
                endDate: '',
                meetingNo: '',
                appointNameId: '',
            }
        },
        watch: {
            propValues: function () {
                if (this.propValues) {
                    this.values = _.cloneDeep(this.propValues);
                } else {
                    this.values = _.cloneDeep(INITIAL_VALUES);
                }
            },
            propStatusId: function () {
                if (this.propStatusId) {
                    this.statusId = this.propStatusId;
                } else {
                    this.statusId = '';
                }
            },
            propYear: function () {
                if (this.propYear) {
                    this.year = this.propYear;
                } else {
                    this.year = '';
                }
            },
            propQuarter: function () {
                if (this.propQuarter) {
                    this.quarter = this.propQuarter;
                } else {
                    this.quarter = '';
                }
            },
            propStartDate: function () {
                if (this.propStartDate) {
                    this.startDate = this.propStartDate;
                } else {
                    this.startDate = '';
                }
            },
            propEndDate: function () {
                if (this.propEndDate) {
                    this.endDate = this.propEndDate;
                } else {
                    this.endDate = '';
                }
            },
            propAppointNameId: function () {
                if (this.propAppointNameId) {
                    this.appointNameId = this.propAppointNameId;
                } else {
                    this.appointNameId = '';
                }
            },
            propMeetingNo: function () {
                if (this.propMeetingNo) {
                    this.meetingNo = this.propMeetingNo;
                } else {
                    this.meetingNo = '';
                }
            },
        },
        computed: {
            totalNo1: function() {
                const no = this.values.no1;
                return this.calTotalNo(no);
            },
            totalNo2: function() {
                const no = this.values.no2;
                return this.calTotalNo(no);
            },
            totalNo3: function() {
                const no = this.values.no3;
                const totalBreak = no.v2 + no.v3 + no.v4;
                const number = no.v1 * totalBreak;
                return Math.round(number * 100) / 100;
            },
            totalNoAll: function() {
                const number = this.totalNo1 + this.totalNo2 + this.totalNo3;
                return Math.round(number * 100) / 100;
            },
            totalPeople: function () {
                return this.values.no3.v1 - (this.values.no1.v1 + this.values.no2.v1);
            }
        },
        methods: {
            calTotalNo: function (no) {
                const n1 = no.v1 * no.v2;
                const n2 = no.v3 - no.v2;
                const number = n1 + n2;
                return Math.round(number * 100) / 100;
            },
            onSubmit: async function () {
                this.hasSubmitting = true;
                try {
                console.log(this.propUrl);
                    if (isEmpty(this.statusId) || isEmpty(this.year)
                        || isEmpty(this.startDate) || isEmpty(this.endDate) || isEmpty(this.appointNameId)) {
                        const message = 'ข้อมูลไม่ครบถ้วน';
                        $.toast({
                            heading: 'Wrong!',
                            position: 'top-center',
                            text: message,
                            icon: 'error',
                            hideAfter: 3000,
                            stack: 6
                        });

                        return;
                    }

                    const url = this.propUrl;
                    // console.log(url);
                    const res = await axios.post(url, {
                        numpeople_g: isEmpty(this.values.no1.v1)?0:this.values.no1.v1,
                        allowances_referee_g: isEmpty(this.values.no1.v2)?0:this.values.no1.v2,
                        allowances_persident_g: isEmpty(this.values.no1.v3)?0:this.values.no1.v3,
                        sum_g: this.totalNo1,
                        numpeople_subg: isEmpty(this.values.no2.v1)?0:this.values.no2.v1,
                        allowances_referee_subg: isEmpty(this.values.no2.v2)?0:this.values.no2.v2,
                        allowances_persident_subg: isEmpty(this.values.no2.v3)?0:this.values.no2.v3,
                        sum_subg: this.totalNo2,
                        numpeople_attendees: isEmpty(this.values.no3.v1)?0:this.values.no3.v1,
                        food_morning_attendees: isEmpty(this.values.no3.v2)?0:this.values.no3.v2,
                        food_noon_attendees: isEmpty(this.values.no3.v3)?0:this.values.no3.v3,
                        food_afternoon_attendees: isEmpty(this.values.no3.v4)?0:this.values.no3.v4,
                        sum_attendees: this.totalNo3,
                        total: this.totalPeople,
                        sum: this.totalNoAll,
                        startdate: this.startDate,
                        enddate: this.endDate,
                        quarter: this.quarter,
                        year: this.year,
                        plan_id: this.propPlanId,
                        statusOperation_id: this.statusId,
                        appointName_id: this.appointNameId,
                        meetingNo: this.meetingNo,
                    });

                    const set_standard = res.data.set_standard;

                    if(this.propAddNewPlan){
                        this.propAddNewPlan(set_standard.id);
                    }else{
                        this.propAddNewResult(set_standard.id);
                    }

                    this.values = _.cloneDeep(INITIAL_VALUES);
                    this.resetData();
                } catch (e) {
                    console.log(e)
                } finally {
                    this.hasSubmitting = false;
                }
            },
            onClickCancel: function () {
                this.resetData();
            },
            resetData: function () {
                this.propResetPlatFormData();
                this.statusId = '';
                this.year = '';
                this.quarter = '';
                this.startDate = '';
                this.endDate = '';
                this.appointNameId = '';
                this.meetingNo = '';
            }
        },
    }

    function isEmpty(text) {
        if (text === undefined || text === null || text === '') {
            return true;
        }
        return false;
    }
</script>

<style scoped>
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  font-size: 16px;
}
::-moz-placeholder { /* Firefox 19+ */
  font-size: 16px;
}
:-ms-input-placeholder { /* IE 10+ */
  font-size: 16px;
}
:-moz-placeholder { /* Firefox 18- */
  font-size: 16px;
}
</style>
