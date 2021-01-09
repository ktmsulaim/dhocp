import "../assets/vendor/nucleo/css/nucleo.css";

import globalComponents from "./globalComponents";
import globalDirectives from "./globalDirectives";
import SidebarPlugin from "../components/SidebarPlugin/index"
import NotificationPlugin from "../components/NotificationPlugin"

export default {
  install(Vue) {
    Vue.use(globalComponents);
    Vue.use(globalDirectives);
    Vue.use(SidebarPlugin);
    Vue.use(NotificationPlugin);
  }
};
