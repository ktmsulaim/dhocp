require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue'
import ArgonDashboard from './plugins/argon-dashboard'
import { InertiaProgress } from '@inertiajs/progress'
import store from './store'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";


Vue.use(plugin)
Vue.use(ArgonDashboard)
InertiaProgress.init()
Vue.use(flatPickr);
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

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