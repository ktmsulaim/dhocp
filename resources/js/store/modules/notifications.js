const state = {
    unread: 0,
};

const getters = {
    unread(state){
        return state.unread;
    }
};

const actions = {
    fetchUnreadCount({commit}, data) {
        commit('setUnreadCount', data);
    }
};

const mutations = {
    setUnreadCount(state, data){
        state.unread = data;
    }
};

export default {
    state, getters, actions, mutations
}