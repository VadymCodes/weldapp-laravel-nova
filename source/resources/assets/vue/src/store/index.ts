import Vue from 'vue';
import Vuex from 'vuex';

import state from './state';
import * as actions from './actions';
import mutations from './mutations';
import * as getters from './getters';

import auth from './auth/index';
import mechanic from './mechanic/index';
import driver from './driver/index';
import job from './job/index';

Vue.use(Vuex);

export default new Vuex.Store({
  state,
  actions,
  mutations,
  getters,
  modules: {
    auth,
    mechanic,
    driver,
    job,
  },
});
