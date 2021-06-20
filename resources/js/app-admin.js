/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 * eslint-env node
 */

require('./bootstrap');
require('admin-lte');

window.Vue = require('vue');

import store from './store-admin';
Vue.prototype.$store = store;

import router from './router';

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

window.vm = new Vue();

import {
  Button,
  HasError,
  AlertError,
  AlertErrors,
  AlertSuccess
} from 'vform/src/components/bootstrap4'
// 'vform/src/components/bootstrap4'
// 'vform/src/components/tailwind'

Vue.component(Button.name, Button)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


window.vm = new Vue();

import Echo from "laravel-echo";

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '0004ac5a6265f2b52e4e',
    cluster: 'ap1',

});
window.Pusher.logToConsole = true
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
   	data: () => ({
   		
        }),
   	mounted() {

  	}
});




/*
notes
 */
//(1): import cái này mà ko import dependency của nó là jquery sẽ báo lỗi warning. Cuối cùng nguyên file app.js này sẽ ko chạy luôn