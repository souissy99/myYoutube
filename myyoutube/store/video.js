const state = () => ({
    video: [],
  })

const getters = {
    getVideo (state) {
      return state.video
    }
  }

const mutations = {
    add (state, { data }) {
      state.video = data;
    }
  }

export default {
    namespaced: true,
    state,
    getters,
    mutations
}