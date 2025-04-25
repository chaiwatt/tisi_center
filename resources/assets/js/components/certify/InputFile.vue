<template>
    <div class="form-group">
        <label class="col-md-4 control-label label-filter text-right">รายละเอียดการชำระเงิน (ทับไฟล์เก่า)</label>
        <div class="col-md-7 text-left">
            <label>
                <div class="btn btn-success btn-sm">Browse...</div>
                <input @change="doFile(field, $event)" type="file" :name="inputName ? inputName : 'file'" style="display: none;">
            </label>
            <span v-if="field.original_name">ไฟล์ใหม่ : {{ field.original_name }}</span>
            <br>
            <span v-if="oldUrl && oldFileName">ไฟล์ปัจจุบัน : <a :href="oldUrl">{{ path.basename(oldFileName) }}</a></span>
        </div>
    </div>
</template>

<script>
    const initField = {name: "", original_name: "", file: null};

    export default {
        props: {
            inputName: String,
            oldUrl: String,
            oldFileName: String
        },
        mounted() {
            console.log('Input File Mounted.');
        },
        data() {
            return {
                field: _.clone(initField),
                path: require('path')
            }
        },
        methods: {
            doFile: function (field, event) {
                const file = event.target.files[0];
                const original_name = file.name;
                field.original_name = original_name;
                field.file = file;
            },
        }
    }
</script>
