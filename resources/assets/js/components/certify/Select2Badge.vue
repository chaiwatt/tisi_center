<template>
    <div>
        <select name="auditors" ref="auditors" class="form-control not_select2">
            <option value="">-เลือกคณะผู้ตรวจประเมิน-</option>
            <option v-for="option in options" :value="option.value">{{ option.text }}</option>
        </select>
        <div class="text-left">
             <span class="m-5 p-10 badge" v-for="(label, index) in labels" style="background-color: #D9D9D9;">
                 <span>{{ label.text }}</span>
                 <i class="fa fa-times text-danger" style="cursor: pointer;" @click="removeLabel(index)"></i>
                 <input type="hidden" :name="'auditors[]'" :value="label.value">
             </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            options: Array,
            labels: Array,
        },
        mounted() {
            let that = this;
            $(this.$refs.auditors).select2().on('change', function () {
                let options = this.options;
                if(options.selectedIndex > -1 && options.selectedIndex !== 0) {
                    let text = options[options.selectedIndex].text;
                    let val = this.value;
                    if (!that.labels.find(element => element.value === val)) {
                        that.labels.push({text: text, value: val});
                    }
                }
            });
            console.log('Select2 Badge Mounted.');
        },
        methods: {
            removeLabel: function (index) {
                this.labels.splice(index, 1);
            },
        }
    }
</script>
