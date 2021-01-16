import Vue from 'vue'
import Vuex from 'vuex'

import Title from './modules/title'
import ItemOptions from './modules/itemOptions'
import Item from './modules/item'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Title,
        ItemOptions,
        Item
    }
});