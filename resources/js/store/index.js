import Vue from 'vue'
import Vuex from 'vuex'

import User from './modules/user'
import Admin from './modules/admin'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        User,
        Admin
    }
});