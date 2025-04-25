<template>
    <div class="form-group">
        <label class="col-md-4 control-label label-filter text-right">{{ title }} : </label>
        <div class="col-md-4 text-left">
            <input type="text" :name="inputName" class="form-control mydatepicker" :value="value ? value : ''" autocomplete="off" required>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            inputName: String,
            value: String
        },
        mounted() {
            console.log('Input Date Mounted.');
        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>
