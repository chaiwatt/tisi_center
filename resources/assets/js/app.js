
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.a = require('vue-autonumeric');
Vue.component('vue-autonumeric', a);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('card-certificate-detail', require('./components/certify/CardCertificateDetail.vue'));
Vue.component('select2-badge', require('./components/certify/Select2Badge.vue'));
Vue.component('modal', require('./components/certify/Modal.vue'));
Vue.component('input-file', require('./components/certify/InputFile.vue'));
Vue.component('input-file2', require('./components/certify/InputFile2.vue'));
Vue.component('input-file-multiple', require('./components/certify/InputFileMultiple.vue'));
Vue.component('input-date', require('./components/certify/InputDate.vue'));

Vue.component('select2', require('./components/tis/set_standard/Select2.vue'));



Vue.component('plan-form', require('./components/tis/set_standard/PlanForm.vue'));


// const app = new Vue({
//     el: '#app_check_certificate',
//     data: {
//         list: [],
//     }
// });
