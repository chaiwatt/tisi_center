<template>
    <div class="modal fade" ref="modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">รายละเอียดความเห็นแต่งตั้งคณะผู้ตรวจประเมิน</h3>
                </div>
                <div class="modal-body">
                    <div class="white-box">
                        <h3 class="box-title">คณะผู้ตรวจประเมิน 1</h3>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">ชื่อคณะผู้ตรวจประเมิน : </label>
                            <label class="col-md-7">คณะผู้ตรวจประเมินที่ 1</label>
                        </div>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">วันที่ตรวจประเมิน : </label>
                            <label class="col-md-7">13 ก.ค. 2562</label>
                        </div>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">หนังสือแต่งตั้งคณะผู้ตรวจประเมิน : </label>
                            <div class="col-md-7">
                                <a href="#">หนังสือแต่งตั้ง เลขที่XXXXXX.jpg</a>
                            </div>
                        </div>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">โดยคณะผู้ตรวจประเมินมีรายนามดังต่อไปนี้ : </label>
                            <div class="col-md-7">
                                <div class="row">
                                    <span class="col-md-5">1. วีรยุทธ อัศวรำชสกุล</span>
                                    <span class="col-md-7">หัวหน้ำคณะผู้ตรวจประเมิน</span>
                                </div>
                                <div class="row">
                                    <span class="col-md-5">2. อนุสรณ์ ทรัพย์ปรีชำ</span>
                                    <span class="col-md-7">ผู้ตรวจประเมิน</span>
                                </div>
                                <div class="row">
                                    <span class="col-md-5">3. .........</span>
                                    <span class="col-md-7">........</span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">ความเห็นในการแต่งตั้งคณะผู้ตรวจประเมิน : </label>
                            <label class="col-md-7">ไม่เห็นด้วย</label>
                        </div>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">เหตุผล : </label>
                            <label class="col-md-7">ผู้ตรวจประเมินลำดับที่ 2 ยังไม่มีประสบกำรณ์ในกำรตรวจห้องปฏิบัติสำขำนี้ ซึ่งคิดว่ำยังไม่เหมำะในกำรแต่งตั้งเป็นคณะ ผู้ตรวจประเมินครั้งนี้</label>
                        </div>
                        <div class="row p-l-15 text-left">
                            <label class="col-md-5">ไฟล์แนบ (ถ้ำมี) : </label>
                            <div class="col-md-7">
                                <a href="#">refer.pdf</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            ปิด
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Modal Mounted.');
        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: top;
    }

</style>