import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import Helper from './helper';
let _helper = new Helper()
import createPersistedState from "vuex-persistedstate";

export default new Vuex.Store({
  state: {
    selectedGuest: {},
    selectedGuestIndex:''
  },
  mutations: {
    SET_SELECTED_GUEST(state, payload) {
      this.state.selectedGuest = payload;
    },
    SET_SELECTED_GUEST_INDEX(state, payload) {
      this.state.selectedGuestIndex = payload;
    }
  },
  actions: {

  },
  plugins: [
    createPersistedState({
        paths:[ 'selectedGuest' , 'selectedGuestIndex' ]
    })
  ],

})
