const state = {
    options: [],
};

const getters = {
    options(state) {
        return state.options;
    }
};

const actions = {
    fetchOptions({commit}, data) {
        commit('setOptions', data);
    },
    addOptionToForm({commit}, data) {
        commit('addToOptions', data);
    },
    removeFromForm({commit}, data) {
        commit('removeFromOptions', data);
    }
};

const mutations = {
    setOptions(state, data) {
        state.options = data;
    },
    addToOptions(state, data){
        state.options.push(data);
    },
    removeFromOptions(state, data){
        const index = state.options.indexOf(data);
        state.options.splice(index, 1);
    }
};

export default {
    state, getters, actions, mutations
}
