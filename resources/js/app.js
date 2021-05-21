/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import store from './store'
//owl.carousel (1)
// import 'owl-carousel/owl-carousel/owl.carousel.min.js';


//eshop
// require  ('./eshop_js/active.js');
// require  ('./eshop_js/easing.js');
// require  ('./eshop_js/facnybox.min.js');
// require  ('./eshop_js/finalcountdown.min.js');
// require  ('./eshop_js/magnific-popup.js');
// require  ('./eshop_js/nicesellect.js');
// require  ('./eshop_js/onepage-nav.min.js');
// require  ('./eshop_js/popper.min.js');
// require  ('./eshop_js/scrollup.js');
// require  ('./eshop_js/slicknav.min.js');
// require  ('./eshop_js/waypoints.min.js');
// require  ('./eshop_js/ytplayer.min.js');
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
const getCookieValue = (name) => (
  document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || ''
)

window.vm = new Vue();

//ProductPage = require('./components/ProductPage.vue').default;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
   	data: () => ({
   		
        }),
   	mounted() {

  	}
});


/*
notes
 */
//(1): import cái này mà ko import dependency của nó là jquery sẽ báo lỗi warning. Cuối cùng nguyên file app.js này sẽ ko chạy luôn