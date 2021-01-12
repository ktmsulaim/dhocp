const state = {
    title: null,
};

const getters = {
    pageTitle(state) {
        return state.title;
    }
};

const actions = {
    assignTitle({commit}, data) {
        commit('setTitle', data);
    }
};

const mutations = {
    setTitle(state, data) {
        state.title = data;

        document.title = state.title + ' | Darul Huda Online Certificate Portal'
    }
};

export default {
    state, getters, actions, mutations
}