<template>
    <div class="white-box">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="box-title" style="display: inline-block;">คำขอรับใบรับรองห้องปฏิบัติการ {{ app ? app.app_no : '' }}</h3>
                <a class="label" v-bind:class="{'label-default': !isShowDetail, 'label-success': isShowDetail}" v-on:click="doDetail">
                    <span v-if="isShowDetail">ซ่อนรายละเอียด</span>
                    <span v-else>แสดงรายละเอียด</span>
                </a>
                <div class="clearfix"></div>
                <div v-show="isShowDetail">
                    <div v-if="app">
                        <div class="row">
                            <label class="col-sm-3">เลขที่คำขอ : </label>
                            <label class="col-sm-6">{{ app.app_no }}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">หน่วยงาน : </label>
                            <label class="col-sm-6">{{ app.trader.trader_operater_name }}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">ความสามารถห้องปฏิบัติการ : </label>
                            <label class="col-sm-6">{{ app.str_lab_type }}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">สาขา : </label>
                            <label class="col-sm-6">{{ app.str_branches }}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">วันที่ยื่น : </label>
                            <label class="col-sm-6">{{ app.str_created_at }}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">สถานะคำขอ : </label>
                            <label class="col-sm-6">{{ app.str_status}}</label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3">เจ้าหน้าที่ตรวจสอบคำขอ : </label>
                            <label class="col-sm-6">{{ app.str_checker }}</label>
                        </div>
                    </div>
                    <div v-else>
                        <div class="row">
                            <label class="col-sm-3">เกิดข้อผิดพลาด</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            url: String
        },
        async mounted() {
            try {
                const res = await axios(this.url);
                const data = res.data;
                const app = data.app;
                this.app = app;
                console.log(data);
            } catch (e) {

            }
            console.log('Card Certificate Detail Mounted.');
        },
        data() {
            return {
                isShowDetail: false,
                app: null
            }
        },
        methods: {
            doDetail: function () {
                this.isShowDetail = !this.isShowDetail
            },
        }
    }
</script>
