const state = {
    user: null,
};

const getters = {
    user(state) {
        return state.user;
    }
};

const actions = {
    fetchUser({commit}, data) {
        commit('setUser', data);
    }
};

const mutations = {
    setUser(state, data) {
        state.user = data;
    }
};

export default {
    state, getters, actions, mutations
}
