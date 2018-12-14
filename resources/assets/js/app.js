require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import VeeValidate from 'vee-validate'
import Notifications from 'vue-notification'
import swal from 'sweetalert'
import Multiselect from 'vue-multiselect'
import vSelect from 'vue-select'
import store from './store/index'
import JsonExcel from 'vue-json-excel'
// Load the exporting module.
// import HighchartsVue from 'highcharts-vue'
import VueHighcharts from 'vue-highcharts';
import Highcharts from 'highcharts'
import stockInit from 'highcharts/modules/stock'
import VModal from 'vue-js-modal'


import {Vuetable, VuetablePagination, VuetablePaginationDropDown, VuetablePaginationInfo} from 'vuetable-2'
//This is very important to used with the window object

stockInit(Highcharts)
window.Vue = require('vue');
// import routes from 'routes';
import {routes} from './routes.js'

Vue.component('adminnavbar', require('./components/main/AdminNavbar'))
Vue.component('my-vuetable', require('./components/vuetable/Vuetable'))
Vue.component('multiselect', Multiselect)
Vue.component('downloadExcel', JsonExcel)
Vue.component('receipt-table', require('./components/receipt/table/Vuetable'))
// Accounts Component
Vue.component('pettycash-table', require('./components/accounts/table/pettycash/Vuetable'))
Vue.component('cashbook-table', require('./components/accounts/table/cashbook/Vuetable'))
Vue.component('businessmen-table', require('./components/people/businessmen/table/Vuetable'))
Vue.component('business-table', require('./components/people/business/table/Vuetable'))
// Vue.component('pettycash-table', require('./components/accounts/table/pettycash/bootTable'))
Vue.component('user-table', require('./components/people/users/table/Vuetable'))
Vue.component('editmodal', require('./components/modals/EditModal'))
Vue.component('print', require('./components/modals/Print'))
Vue.component('printview', require('./components/modals/printView'))




// Vue.component("vuetable", Vuetable)
// Vue.component("vuetable-pagination", VuetablePagination)
// Vue.component("vuetable-pagination-dropdown", VuetablePaginationDropDown)
// Vue.component("vuetable-pagination-info", VuetablePaginationInfo)

const router = new VueRouter({
    // mode: 'history',
    linkExactActiveClass: 'nav-active',
    routes
})


// const store = new Vuex.Store({
// 	StoreData,
// });

router.beforeEach((to, from, next) => {

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.state.admin != 1) {
      next({
        path: '/',
        query: {
          redirect: to.fullPath,
        },
      });
    } else {
      next()
    }
  } else {
    next()
  }
})


Vue.use(VModal, { dynamic: true , injectModalsContainer: true })
Vue.use(VueHighcharts, { Highcharts })
Vue.component('v-select', vSelect)
Vue.use(VueRouter)
Vue.use(Notifications)
Vue.use(VeeValidate)
const app = new Vue({
	store,
    router
}).$mount('#app')
