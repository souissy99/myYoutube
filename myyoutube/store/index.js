import Vue from "vue";
import Vuex from "vuex";
import VuexPersistence from 'vuex-persist'
import auth from "./auth";
import video from "./video";
const MockStorage = require('mockstorage').MockStorage

Vue.use(Vuex);

const vuexLocalStorage = new VuexPersistence({
    storage: new MockStorage(),
    reducer: (state) => ({ video: state.video }), //only save navigation module
  })

export const store = () => {
    return new Vuex.Store({
        modules: {
            auth,
            video
        },
        plugins: [vuexLocalStorage.plugin]
    })
}

export default store
