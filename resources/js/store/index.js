import Vue from 'vue'
import Vuex from 'vuex'

import User from './modules/user'
import Admin from './modules/admin'
import Title from './modules/title'
import ItemOptions from './modules/itemOptions'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        User,
        Admin,
        Title,
        ItemOptions
    }
});