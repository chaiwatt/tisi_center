<template>

    <select>
        <slot></slot>
    </select>

</template>

<script>
    export default {
        props: ['value'],
        template: '#select2-template',
        mounted: function () {
            var vm = this
            $(this.$el)
            // init select2
                .select2()
                .val(this.value)
                .trigger('change')
                // emit event on change.
                .on('change', function () {
                    vm.$emit('input', this.value)
                });

            console.log("Select2 Mounted.");
        },
        watch: {
            value: function (value) {
                // update value
                $(this.$el)
                    .val(value)
                    .trigger('change')
            },
            options: function (options) {
                // update options
                $(this.$el).empty().select2()
            }
        },
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }
    }
</script>
