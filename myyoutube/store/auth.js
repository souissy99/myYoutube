const state = () => ({
    user: [],
    connected: false
  })

const getters = {
    getUser (state) {
      return state.user
    },
    getStatus (state) {
        return state.connected
      }
  }

const mutations = {
    add (state, { data }) {
      state.user.push(data);
      state.connected = true
    },
    logout (state) {
        state.connected = !state.connected;
        state.user = [];
      }
  }

export default {
    namespaced: true,
    state,
    getters,
    mutations
}