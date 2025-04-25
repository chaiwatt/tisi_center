<template>
    <div class="form-group">
        <label class="col-md-4 text-right">ไฟล์แนบ (ทับของเก่า) : </label>
        <div class="col-md-8 text-left">
            <button type="button" class="btn btn-success btn-sm" @click="addAttachInput"><i class="icon-plus"></i> เพิ่ม</button>
            <div class="row m-b-5 m-t-15" v-for="(field, index) in fields">
                <div class="col-sm-5">
                    <input type="text" :name="'name['+index+']'" v-model="field.name" class="form-control" :readonly="field.exist">
                </div>
                <div class="col-sm-5">
                    <input type="text" :name="'original_name['+index+']'" v-model="field.original_name" class="form-control" readonly>
                </div>
                <label>
                    <div class="btn btn-success btn-sm" v-if="!field.exist"><i class="fa fa-upload"></i></div>
                    <input @change="doFile(field, $event)" type="file" :name="'file['+index+']'" required style="display: none;">
                    <input type="hidden" :name="'exist['+index+']'" :value="field.exist ? 1 : 0">
                    <input type="hidden" :name="'doDelete['+index+']'" v-model="field.doDelete">
                </label>
                <button type="button" class="btn btn-danger btn-sm" v-if="!field.exist" @click="deleteAttachInput(index)"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    const initField = {name: "", original_name: "", file: null, exist: false, doDelete: false};

    export default {
        props: {
            files: Array
        },
        mounted() {
            // this.fields = this.files ? _.cloneDeep(this.files) : [_.clone(initField)];
            console.log('Input File Multiple Mounted.');
        },
        data() {
            return {
                fields: [],
            }
        },
        methods: {
            addAttachInput: function () {
                const newField = _.clone(initField);
                this.fields.push(newField)
            },
            deleteAttachInput: function (index) {
                const field = this.fields[index];
                if (field.exist) {
                    field.doDelete = true;
                } else {
                    this.fields.splice(index, 1);
                }
            },
            doFile: function (field, event) {
                const file = event.target.files[0];
                const original_name = file.name;
                field.original_name = original_name;
                field.file = file;
            },
        }
    }
</script>
