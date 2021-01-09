const state = {
    admin: null,
};

const getters = {
    admin(state) {
        return state.admin;
    }
};

const actions = {
    fetchAdmin({commit}) {
        commit('setAdmin');
    }
};

const mutations = {
    setAdmin(state, data) {
        const token = localStorage.getItem('token');

        if(token) {
            axios.get('/api/admin/user?api_token=' + token)
            .then((resp) => {
                state.admin = resp.data;
            })
            .catch(err => {
                console.log('Unable to load the admin!');
            })
        }
    }
};

export default {
    state, getters, actions, mutations
}
