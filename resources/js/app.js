
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


// Dependencies --------------------------------------

import Toasted from 'vue-toasted';
import VueClip from 'vue-clip'
import Multiselect from 'vue-multiselect'
import swal from 'sweetalert';
import VueContentPlaceholders from 'vue-content-placeholders'
import Vue from 'vue';

Vue.use(require('vue-moment'));
Vue.use(Toasted)
Vue.toasted.register('error', message => message, {
    position : 'bottom-center',
    duration : 1000
})
Vue.use(VueClip)
Vue.component('multiselect', Multiselect)
Vue.use(VueContentPlaceholders)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 // Layout
 Vue.component('sidebar', require('./components/layout/Sidebar.vue').default)

// Dashboard
Vue.component('users-count', require('./components/dashboard/UsersCount.vue').default)
Vue.component('roles-count', require('./components/dashboard/RolesCount.vue').default)
Vue.component('balance-count', require('./components/dashboard/BalanceCount.vue').default)
// Profile
Vue.component('profile', require('./components/profile/Profile.vue').default)
Vue.component('profile-password', require('./components/profile/Password.vue').default)

// Users
Vue.component('users-index', require('./components/users/Index.vue').default)
Vue.component('users-create', require('./components/users/Create.vue').default)
Vue.component('users-edit', require('./components/users/Edit.vue').default)


//tools
Vue.component("select-tool",require("./components/tools/selectTool.vue").default);
Vue.component("whatsapp",require("./components/tools/whatsapp.vue").default); 

// whatsapp
Vue.component("cover-page",require("./components/tools/whatsapp/coverPage.vue").default); 
Vue.component("color-page",require("./components/tools/whatsapp/colorPage.vue").default); 

// Agent Topup
Vue.component('orders-list', require('./components/orders/Index.vue').default)
// Vue.component('topup-create', require('./components/agents/Create.vue').default)
// Vue.component('read-transaction', require('./components/agents/Transactions.vue').default)
// Rolestopup-create
Vue.component('roles-index', require('./components/roles/Index.vue').default)
Vue.component('roles-create', require('./components/roles/Create.vue').default)
Vue.component('roles-edit', require('./components/roles/Edit.vue').default)

const app = new Vue({
    el: '#app'
});
