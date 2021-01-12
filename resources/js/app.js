require('./bootstrap');

window.Vue = require('vue').default;

import { App, plugin } from '@inertiajs/inertia-vue'
import ArgonDashboard from './plugins/argon-dashboard'
import { InertiaProgress } from '@inertiajs/progress'
import Vue from 'vue'
import store from './store'
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";


Vue.use(plugin)
Vue.use(ArgonDashboard)
InertiaProgress.init()
Vue.use(flatPickr);

Vue.prototype.$route = route

const el = document.getElementById('app')

new Vue({
  store,
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => require(`./Pages/${name}`).default,
    },
  }),
}).$mount(el)