/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 * eslint-env node
 */
$(function() {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
});
require('./bootstrap');

window.Vue = require('vue');

import store from './store';
Vue.prototype.$store = store;

//helper.js
import Helper from './helper';
Vue.prototype.$Helper = new Helper();

import common from './common';
Vue.mixin(common)

window.vm = new Vue();

import Echo from "laravel-echo";

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '0004ac5a6265f2b52e4e',
    cluster: 'ap1',
    forceTLS: true,
    encrypted: true,
    // authEndpoint: "/broadcasting/auth/guest",
      // auth: {
      //       headers: {
      //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //       }
      // }
});
window.Pusher.logToConsole = true

Date.prototype.addHours = function(h) {
  this.setTime(this.getTime() + (h*60*60*1000));
  return this;
}
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('product-page', require('./components/ProductPage.vue').default);
Vue.component('cart', require('./components/Cart.vue').default);
Vue.component('popover', require('./components/Popover.vue').default);
Vue.component('dropdown-cart', require('./components/DropdownCart.vue').default);
Vue.component('chat', require('./components/Chat.vue').default);

const getCookieValue = (name) => (
  document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || ''
)

window.vm = new Vue();

/*
  Convert vue object
*/
global.getVueObject = obj => {
  return JSON.parse(JSON.stringify( obj ));
};


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    //store,
   	data: () => ({
   		
        }),
   	mounted() {

  	}
});

// const cart = new Vue({
//   el: '#dropdown-cart'
// })


/*
notes
 */
//(1): import cái này mà ko import dependency của nó là jquery sẽ báo lỗi warning. Cuối cùng nguyên file app.js này sẽ ko chạy luôn