require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { App, plugin } from '@inertiajs/inertia-vue'
import ArgonDashboard from './plugins/argon-dashboard'
import { InertiaProgress } from '@inertiajs/progress'
import store from './store'
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'




Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(plugin)
Vue.use(ArgonDashboard)
InertiaProgress.init()
Vue.use(flatPickr);
Vue.use(VueFileAgent);
Vue.use(Viewer);



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