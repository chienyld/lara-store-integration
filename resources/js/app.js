/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
/*
import http from 'axios'
Vue.prototype.$http = http
window.Vue = require('vue');*/
import  VueResource  from 'vue-resource'
 
Vue.use(VueResource);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('amount-input', require('./components/AmountInput.vue').default);
Vue.component('follow-button', require('./components/FollowButton.vue').default);
Vue.component('verify-status', require('./components/VerifyStatus.vue').default);
Vue.component('verify-status2', require('./components/VerifyStatus2.vue').default);
Vue.component('user-type', require('./components/UserType.vue').default);
Vue.component('cart', require('./components/Cart.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
});


    
